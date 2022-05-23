<?php
#namespace App\Helpers; // Your helpers namespace
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\Auth;
#use Mail;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\Log;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
#use Illuminate\Support\Facades\Session;


if (!function_exists('_number_format')) {
    function _number_format($amount)
    {
        $amount = number_format((float)$amount, 2, '.', '');
        return $amount;
    }
}
if (!function_exists('startsWith')) {
    function startsWith($string, $startString)
    {
        $len = strlen($startString);
        return (substr($string, 0, $len) === $startString);
    }
}
if (!function_exists('_ucWords')) {
    function _ucWords($string='')
    {
        if(empty($string)) {
            return '';
        }
        return ucwords(strtolower($string));
    }
}
if (!function_exists('_lvValidations')) {
    function _lvValidations($validationErrors,$isString=false)
    {
        $errors = array();
        $fieldsWithErrorMessagesArray = $validationErrors;
        if($fieldsWithErrorMessagesArray ){
            foreach($fieldsWithErrorMessagesArray  as $key =>$row){
                $errors[$key] = $row[0];
            }
            if($isString){
                $errors = implode("<br>",$errors);
            }
        }

        return $errors;
    }
}