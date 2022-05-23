<?php
#namespace App\Helpers; // Your helpers namespace
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\Auth;
#use Illuminate\Support\Facades\Session;
if(!function_exists('_object_array_search')){
    function _object_array_search($array, $index, $value){
        foreach ($array as $arrayInf) {
            if ($arrayInf->{$index} == $value) {
                return $arrayInf;
            }
        }
        return null;
    }
}