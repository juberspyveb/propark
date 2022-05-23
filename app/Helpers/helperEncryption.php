<?php
#namespace App\Helpers; // Your helpers namespace
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\Auth;
#use Illuminate\Support\Facades\Session;

if (!function_exists('_encrypt')) {
    function _encrypt($data){
        #$key = "2ywk0281BYriiu@DGf6y@cBab95E^rh3";
        $key = config('encrypterAES.key');
        _pre($key );
        $cipher = "AES-256-CBC";
        $objEncrypter = new Encrypter($key , $cipher);

        $encryptedToString = $objEncrypter->encryptString($data);
        return $encryptedToString;
    }
}if (!function_exists('_decrypt')) {
    function _decrypt($type, $data){
        $key = "2ywk0281BYriiu@DGf6y@cBab95E^rh3";
        $cipher = "AES-256-CBC";
        $objEncrypter = new Encrypter($key , $cipher);
        $decryptedFromString = $objEncrypter->decryptString($data);
        return $decryptedFromString;
    }
}