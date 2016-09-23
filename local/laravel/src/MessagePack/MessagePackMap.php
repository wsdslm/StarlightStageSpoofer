<?php
namespace MessagePack;

use Log;

class MessagePackMap extends MessagePack {
    public $bit;
    public $map;

    protected function countLength($binary) {
        $pos = $this->pos;
        $first = ord($binary[$pos]);
        $bit; $len;

        ++$pos;
        switch ($first) {
        case 0xdf:
            $bit = 32;
            $len = unpack("N*", substr($binary, $pos, 4))[1];
            $pos += 4;
            break;
        case 0xde:
            $bit = 16;
            $len = unpack("n*", substr($binary, $pos, 2))[1];
            $pos += 2;
            break;
        default:
            $bit = 0;
            $len = $first - 0x80;
            break;
        }

        $this->bit = $bit;
        $this->pos = $pos;
        return $len;
    }

    public function parseBinary($binary) {
        $data = [];
        $map = [];
        $key; $keyPack;

        $flag = 0; // 0 = key, 1 = value
        $pos = $this->pos;
        $count = 0;
        for ($pos; $pos < strlen($this->binary); $pos) {
            if ($count >= $this->len) break;

            $pack = MessagePackFactory::unpack($binary, $pos);
            if ($pack == null) {
                $pos++;
                continue;
            }

            $pos = $pack->finish + 1;
            if ($flag == 0) {
                $key = $pack->data;
                $keyPack = $pack;
                $flag = 1;
            } else if ($flag == 1) {
                $data[$key] = $pack->data;
                $map[$key] = [
                    "key"   => $keyPack,
                    "value" => $pack
                ];
                $flag = 0;
                $count++;
            }
        }

        $this->map = $map;
        $this->finish = --$pos;
        return $data;
    }

    public function pack() {
        $len = count($this->map);
        if ($len < 16) {
            $bin = chr($len + 0x80);
        } else {
            $bin = chr(0xdf).pack("N*", $len);
        }

        foreach($this->map as $key => $val) {
            $bin .= $val['key']->pack();
            $bin .= $val['value']->pack();
        }
        return $bin;
    }

    public function packData() {
        $len = $this->len = count($this->data);
        if ($len < 16) {
            $bin = chr($len + 0x80);
        } else if ($len < 32768) {
            $bin = chr(0xde).pack("n*", $len);
        } else {
            $bin = chr(0xdf).pack("N*", $len);
        }

        $this->map = [];
        foreach($this->data as $key => $val) {
            $keyPack = MessagePackFactory::pack($key);
            $valPack = MessagePackFactory::pack($val);
            $this->map[$key] = [
                "key"   => $keyPack,
                "value" => $valPack
            ];

            $bin .= $keyPack->packData();
            $bin .= $valPack->packData();
        }
        return $bin;
    }

    public function map($idx, $key=null, $value=null) {
        if ($key != null) {
            if (!array_key_exists($key, $this->map)) {
                return null;
            }
            $map = $this->map[$key][$idx];
            if ($value != null) {
                $map->value($value);
            }
            return $map;
        } else {
            $maps = [];
            foreach ($this->map as $key => $map) {
                $maps[] = $map[$idx];
            }
            return $maps;
        }
    }

    public function key($key=null, $value=null) {
        return $this->map('key', $key, $value);
    }

    public function value($key=null, $value=null) {
        return $this->map('value', $key, $value);
    }

    public function iterate($path, $value=null) {
        $path = explode(".", $path);
        $msgpack = $this;

        $data = null;
        $lastKey = null;

        foreach ($path as $key) {
            $msgpack = $msgpack->value($key);

            if (is_null($lastKey)) {
                $data = $this->data;
            } else {
                $data = $data[$lastKey];
            }
            $lastKey = $key;

            if ($msgpack == null) {
                return null;
            }
        }

        if ($value != null) {
            $msgpack->value($value);
            $data[$lastKey] = $value;
        }
        return $msgpack;
    }

    public function add($key, MessagePack $keyPack, MessagePack $valpack) {
        $this->map[$key] = [
            "key"   => $keyPack,
            "value" => $valPack
        ];
    }
}
