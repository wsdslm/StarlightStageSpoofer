<?php
namespace MessagePack;

class MessagePackInteger extends MessagePack {
    public $unsigned;
    public $fixint; // 0 = not fixint, 1 = positive fixint, -1 = negative fixint

    protected function countLength($binary) {
        $first = ord($binary[$this->start]);

        if ($first >= 0xcc && $first <= 0xcf) { // uint 8-64 bit
            $this->unsigned = true;
        } else {
            $this->unsigned = false;
        }

        if ($first >= 0x00 && $first <= 0x7f) { // positive fixint
            $this->fixint = 1;
        } else if ($first >= 0xd0 && $first <= 0xd3) { // negative fixint
            $this->fixint = -1;
        } else {
            $this->fixint = 0;
        }

        switch ($first) {
        case 0xcf:
        case 0xd3:
            return 8;
        case 0xce:
        case 0xd2:
            return 4;
        case 0xcd:
        case 0xd1;
            return 2;
        case 0xcc:
        case 0xd0:
            return 1;
        default:
            return 0;
        }
    }

    public function parseBinary($binary) {
        $data = substr($binary, $this->start + 1, $this->len);
        $data = BIG_ENDIAN ? $data : strrev($data);
        switch ($this->len) {
            case 8:
                $data = ($this->unsigned)
                    ? unpack("Q*", $data)[1]
                    : unpack("q*", $data)[1];
                break;
            case 4:
                $data = ($this->unsigned)
                    ? unpack("L*", $data)[1]
                    : unpack("l*", $data)[1];
                break;
            case 2:
                $data = ($this->unsigned)
                    ? unpack("S*", $data)[1]
                    : unpack("s*", $data)[1];
                break;
            case 1:
                $data = ($this->unsigned)
                    ? unpack("C*", $data)[1]
                    : unpack("c*", $data)[1];
                break;
            default:
                $data = ord($binary[$this->start]);
                if ($this->fixint < 0) {
                    $data = ($data - 0xe0) * -1;
                }
                break;
        }
        $this->finish = $this->start + $this->len;
        return $data;
    }

    public function pack() {
        $first = "";
        $data = $this->data;
        $abs = abs($data);

        // NOTE 2016-03-13: Positive length of unsigned != signed. Make sure to validate later.
        // NOTE 2016-03-13: 16-bit integer may cause crash on game client so check that too.
        if ($abs < 128) {
            if ($data < 0) {
                $data = ($data * -1) + 0xe0;
            } 
            $data = chr($data);
        } else if ($abs < 32768) {
            if ($this->unsigned && $data > 0) {
                $first = chr(0xcd);
                $data = pack("S*", $data);
            } else {
                $first = chr(0xd1);
                $data = pack("s*", $data);
            }
        } else if ($abs < 2147483648) {
            if ($this->unsigned && $data > 0) {
                $first = chr(0xce);
                $data = pack("L*", $data);
            } else {
                $first = chr(0xd2);
                $data = pack("l*", $data);
            }
        } else {
            if ($this->unsigned && $data > 0) {
                $first = chr(0xcf);
                $data = pack("Q*", $data);
            } else {
                $first = chr(0xd3);
                $data = pack("q*", $data);
            }
        }

        $data = BIG_ENDIAN ? $data : strrev($data);
        return $first.$data;
    }
}
