<?php

namespace app\core\security;

class DataEncriptionUtility
{
    private $openSSlKey;
    private static $instances = [];
    protected function __construct()
    {
        $this->openSSlKey = hash('sha256', "ECg=n~uS2BPf/TGZ[qH;AY", true);
    }

    public static function create()
    {
        $className = static::class;
        if (!isset(self::$instances[$className])) {
            self::$instances[$className] = new static();
        }
        return self::$instances[$className];
    }

    public function encryptWithSsl($data)
    {
        // $cipher = "aes-256-cbc";
        $cipher = "aes-128-cbc";

        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));
        if (strlen($iv) > 16) {
            $iv = substr($iv, 0, 16);
        }
        $cifrado = openssl_encrypt($data, $cipher, $this->openSSlKey, 0, $iv);
        return urlencode(base64_encode($cifrado . "::" . $iv));
    }

    public function decryptWithSsl($data)
    {
        $cipher = "aes-256-cbc";
        list($cifrado, $iv) = explode('::', urldecode(base64_decode($data)), 2);
        return openssl_decrypt($cifrado, $cipher, $this->openSSlKey, 0, $iv);
    }
}
