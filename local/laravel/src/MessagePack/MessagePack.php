<?php
namespace MessagePack;

/**
 * Mediator-like design pattern that stands between PHP values and MessagePack binary
 */
abstract class MessagePack {
    static function init() {
        defined("BIG_ENDIAN") or define("BIG_ENDIAN", pack("S", 1) == pack("n", 1));   
    }

    public $binary;
    public $data;

    public $start;
    public $finish;

    protected $pos;
    protected $len;

    protected abstract function countLength($binary);
    public abstract function parseBinary($binary);
    public abstract function pack();

    public function packData() {
        return $this->pack();
    }

    public function unpack($binary, $start=0) {
        $this->binary = $binary;
        $this->start = $start;
        $this->finish = $start;
        $this->pos = $start;
        $this->len = $this->countLength($binary);
        $this->data = $this->parseBinary($binary);
        return $this;
    }

    public function value($value=null) {
        if (isset($value)) {
            $this->data = $value;
        }
        return $this->data;
    }
}
MessagePack::init();
