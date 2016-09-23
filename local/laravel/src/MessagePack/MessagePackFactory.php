<?php
namespace MessagePack;

class MessagePackFactory {
    /**
     * Unpack binary into MessagePack object
     * @param string $bin Binary string to unpack
     * @param int $start Where first byte is located inside the binary string
     * @return MessagePack
     */
    public static function unpack($bin, $start=0) {
        $first = ord($bin[$start]);
        if ($first >= 0x00 && $first <= 0x7f) { // positive fixint
            $msgpack = new MessagePackInteger();
        } else if ($first >= 0x80 && $first <= 0x8f) { // fixmap
            $msgpack = new MessagePackMap();
        } else if ($first >= 0x90 && $first <= 0x9f) { // fixarray
            $msgpack = new MessagePackArray();
        } else if ($first >= 0xa0 && $first <= 0xbf) { // fixstr
            $msgpack = new MessagePackString();
        } else if ($first == 0xc0) { // nil
            $msgpack = new MessagePackNil();
        } else if ($first == 0xc2 || $first == 0xc3) { // boolean
            $msgpack = new MessagePackBoolean();
        } else if ($first == 0xca || $first == 0xcb) { // float 32-64 bit
            $msgpack = new MessagePackFloat();
        } else if ($first >= 0xcc && $first <= 0xcf) { // uint 8-64 bit
            $msgpack = new MessagePackInteger();
        } else if ($first >= 0xd0 && $first <= 0xd3) { // int 8-64 bit
            $msgpack = new MessagePackInteger();
        } else if ($first >= 0xd9 && $first <= 0xdb) { // str 8-32 bit
            $msgpack = new MessagePackString();
        } else if ($first >= 0xdc && $first <= 0xdd) { // array 16-32 bit
            $msgpack = new MessagePackArray();
        } else if ($first >= 0xde && $first <= 0xdf) { // map 16-32 bit
            $msgpack = new MessagePackMap();
        } else if ($first >= 0xe0 && $first <= 0xff) { // negative fixint
            $msgpack = new MessagePackInteger();
        } else {
            throw new \Exception("Unknown first byte: ".bin2hex($bin[$start]));
        }

        return $msgpack->unpack($bin, $start);
    }

    private static function is_assoc($array) {
        return is_array($array) && array_keys($array) !== range(0, count($array) - 1);
    }

    /**
     * Pack PHP object into MessagePack object
     * @param mixed $value Object to pack
     * @return MessagePack
     */
    public static function pack($value) {
        if (is_float($value)) { // float
            $msgpack = new MessagePackFloat();
        } else if (is_int($value)) { // integer
            $msgpack = new MessagePackInteger();
        } else if (self::is_assoc($value)) { // map
            $msgpack = new MessagePackMap();
        } else if (is_array($value)) { // array
            $msgpack = new MessagePackArray();
        } else if (is_string($value)) { // string
            $msgpack = new MessagePackString();
        } else if (is_null($value)) { // null
            $msgpack = new MessagePackNil();
        } else if (is_bool($value)) { // boolean
            $msgpack = new MessagePackBoolean();
        } else {
            throw new \Exception("Unknown object: ".get_class($value));
        }

        $msgpack->data = $value;
        return $msgpack;
    }
}
