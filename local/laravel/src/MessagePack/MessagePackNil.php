<?php
namespace MessagePack;

class MessagePackNil extends MessagePack {
    protected function countLength($binary) {
        return 0;
    }

    public function parseBinary($binary) {
        return null;
    }

    public function pack() {
        return chr(0xc0);
    }

    public function packData() {
    	return $this->binary = $this->pack();
    }
}
