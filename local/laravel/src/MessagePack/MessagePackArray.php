<?php
namespace MessagePack;

class MessagePackArray extends MessagePack {
    public $array;
    public $bit;

    protected function countLength($binary) {
        $pos = $this->pos;
        $first = ord($binary[$pos]);
        $bit; $len;

        ++$pos;
        switch ($first) {
        case 0xdd:
            $bit = 32;
            $len = unpack("N*", substr($binary, $pos, 4))[1];
            $pos += 4;
            break;
        case 0xdc:
            $bit = 16;
            $len = unpack("n*", substr($binary, $pos, 2))[1];
            $pos += 2;
            break;
        default:
            $bit = 0;
            $len = $first - 0x90;
            break;
        }

        $this->bit = $bit;
        $this->pos = $pos;
        return $len;
    }

    public function parseBinary($binary) {
        $data = [];
        $array = [];

        $pos = $this->pos;
        $count = 0;
        for ($pos; $pos < strlen($this->binary); $pos) {
            if ($count >= $this->len) break;

            $pack = MessagePackFactory::unpack($binary, $pos);
            $count++;
            if ($pack == null) {
                $pos++;
                continue;
            }

            $pos = $pack->finish + 1;
            $data[] = $pack->data;
            $array[] = $pack;
        }

        $this->array = $array;
        $this->finish = --$pos;
        return $data;
    }

    public function pack() {
        $len = count($this->array);
        if ($len < 16) {
            $bin = chr($len + 0x90);
        } else {
            $bin = chr(0xdd).pack("N*", $len);
        }

        foreach($this->array as $val) {
            $bin .= $val->pack();
        }
        return $bin;
    }

    public function packData() {
        $len = count($this->data);
        if ($len < 16) {
            $bin = chr($len + 0x90);
            $this->bit = 0;
        } else if ($len < 32768) {
            $bin = chr(0xdc).pack("n*", $len);
            $this->bit = 16;
        } else {
            $bin = chr(0xdd).pack("N*", $len);
            $this->bit = 32;
        }

        $this->array = [];
        foreach($this->data as $val) {
            $valPack = MessagePackFactory::pack($val);
            $this->array[] = $valPack;
            $bin .= $valPack->packData();
        }
        return $bin;
    }

    public function value($idx=null, $value=null) {
        if ($idx !== null) {
            if (!is_int($idx)) {
                $idx = intval($idx);
            }
            if (!array_key_exists($idx, $this->array)) return null;
            $item = $this->array[$idx];
            if ($value != null) {
                $item->value($value);
            }
            return $item;
        } else {
            return $this->array;
        }
    }

    public function add(MessagePack $msgpack) {
        $this->array[] = $msgpack;
        $this->data[] = $msgpack->data;
    }
}
