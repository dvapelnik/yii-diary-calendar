<?php
class Cryptor
{
    private $encrypt_method = "AES-256-CBC";
    private $secret_key = 'key';
    private $secret_iv = 'iv';

    private $key;
    private $iv;

    public function __construct($key, $iv)
    {
        $this->secret_key = $key;
        $this->secret_iv = $iv;

        $this->key = hash('sha256', $this->secret_key);
        $this->iv = substr(hash('sha256', $this->secret_iv), 0, 16);
    }

    public function encrypt($string)
    {
        $output = openssl_encrypt($string, $this->encrypt_method, $this->key, 0, $this->iv);
        $output = base64_encode($output);

        return $output;
    }

    public function decrypt($string)
    {
        return openssl_decrypt(base64_decode($string), $this->encrypt_method, $this->key, 0, $this->iv);
    }
}