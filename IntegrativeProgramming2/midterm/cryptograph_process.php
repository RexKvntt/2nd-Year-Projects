<?php

// Cipher Key
define('SECRET_KEY', 'my_secret_key_123456');

// Encryption function
function encryptData($data) {
    $key = hash('sha256', SECRET_KEY);
    $iv = substr(hash('sha256', 'iv_secret'), 0, 16);

    return openssl_encrypt($data, 'AES-256-CBC', $key, 0, $iv);
}


// Decryption function
function decryptData($data) {
    $key = hash('sha256', SECRET_KEY);
    $iv = substr(hash('sha256', 'iv_secret'), 0, 16);

    return openssl_decrypt($data, 'AES-256-CBC', $key, 0, $iv);
}

?>