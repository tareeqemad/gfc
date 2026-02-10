<?php
/**
 * Mcrypt Polyfill for newer PHP versions
 * Allows old CodeIgniter Encrypt library to work without mcrypt extension
 */
if (!function_exists('mcrypt_encrypt')) {

    function mcrypt_encrypt($cipher, $key, $data, $mode = null, $iv = '')
    {
        $cipher = 'AES-256-CBC';
        return openssl_encrypt($data, $cipher, $key, OPENSSL_RAW_DATA, $iv);
    }

    function mcrypt_decrypt($cipher, $key, $data, $mode = null, $iv = '')
    {
        $cipher = 'AES-256-CBC';
        return openssl_decrypt($data, $cipher, $key, OPENSSL_RAW_DATA, $iv);
    }

    function mcrypt_get_iv_size($cipher, $mode)
    {
        return openssl_cipher_iv_length('AES-256-CBC');
    }

    function mcrypt_create_iv($size, $source = null)
    {
        return random_bytes($size);
    }
}
