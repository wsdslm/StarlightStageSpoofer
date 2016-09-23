<?php
namespace MessagePack;

class MessagePackString extends MessagePack {
    public $bit;
    protected function countLength($binary) {
        $bit; $len;
        $pos = $this->start;
        $first = ord($binary[$pos]);
        switch ($first) {
            case 0xdb:
                $bit = 32;
                $len = unpack("N*", substr($binary, $pos+1, 4))[1];
                break;
            case 0xda:
                $bit = 16;
                $len = unpack("n*", substr($binary, $pos+1, 2))[1];
                break;
            case 0xd9:
                $bit = 8;
                $len = unpack("C*", substr($binary, $pos+1, 1))[1];
                break;
            default:
                $bit = 0;
                $len = ord($binary[$pos]) - 0xa0;
                break;
        }

        $this->bit = $bit;
        $this->pos = $pos + ($bit / 8) + 1;
        return $len;
    }

    public function parseBinary($binary) {
        $data = "";
        $finish = $this->pos + $this->len - 1;
        for ($i = 0; $i < $this->len; $i++) {
            $bin = $binary[$this->pos + $i];
            $data .= $bin;
        }

        $this->finish = $finish;
        return $data;
    }

    public function pack() {
        $len = strlen($this->data);
        if ($len < 32) {
            $first = chr($len + 0xa0);
        } else if ($len < 32768) {
            $first = chr(0xda).pack("n*", $len);
        } else {
            $first = chr(0xdb).pack("N*", $len);
        }
        return $first.$this->data;
    }
}
