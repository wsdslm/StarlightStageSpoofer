<?php
namespace MessagePack;

class MessagePackFloat extends MessagePack {
    protected function countLength($binary) {
        $first = ord($binary[$this->start]);

        switch ($first) {
        case 0xcb:
            return 8;
        default:
            return 4;
        }
    }

    public function parseBinary($binary) {
        $data = substr($binary, $this->start + 1, $this->len);
        $data = BIG_ENDIAN ? $data : strrev($data);
        $data = unpack("d", $data)[1];
        $this->finish = $this->start + $this->len;
        return $data;
    }

    public function pack() {
        $first = "";
        $data = $this->data;
        switch ($this->len) {
        case 8:
            $first = chr(0xcb);
            break;
        default:
            $first = chr(0xca);
            break;
        }
        $data = pack("d", $data);
        $data = BIG_ENDIAN ? $data : strrev($data);
        return $first.$data;
    }
}
