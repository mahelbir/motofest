<?php

/**
 *
 */
class Mecrypt
{
    /**
     * @param $data
     * @return string
     */
    function encode($data): string
    {
        $method = "AES-256-CBC";
        $key    = hash('sha256', setup('encryption_key'), TRUE);
        $iv     = openssl_random_pseudo_bytes(16);
        $ciphertext = openssl_encrypt($data, $method, $key, OPENSSL_RAW_DATA, $iv);
        $hash       = hash_hmac('sha256', $ciphertext . $iv, $key, TRUE);

        return trim(base64_encode($iv . $hash . $ciphertext));
    }

    /**
     * @param $data
     * @return false|string|null
     */
    function decode($data) {
        $data = base64_decode(trim($data));
        $method           = "AES-256-CBC";
        $iv               = substr($data, 0, 16);
        $hash             = substr($data, 16, 32);
        $ciphertext       = substr($data, 48);
        $key              = hash('sha256', setup('encryption_key'), TRUE);

        if(!hash_equals(hash_hmac('sha256', $ciphertext . $iv, $key, TRUE), $hash)) {
            return NULL;
        }

        return openssl_decrypt($ciphertext, $method, $key, OPENSSL_RAW_DATA, $iv);
    }

}