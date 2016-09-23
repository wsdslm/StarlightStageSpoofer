<?php
namespace MessagePack;

class MessagePackBoolean extends MessagePack {
    protected function countLength($binary) {
        return 0;
    }

    public function parseBinary($binary) {
        $data = ord($binary[$this->start]);
        return $data == 0xc3;
    }

    public function pack() {
        return $this->data ? chr(0xc3) : chr(0xc2);
    }
}
