<?php
#namespace App\Helpers; // Your helpers namespace
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\Auth;
#use Illuminate\Support\Facades\Session;

/** get setting data */
if(!function_exists('_get_setting_data')){
    function _get_setting_data($key){
        $setting = \DB::table('setting')->select('value')->where(['keys' => $key])->first();

        if($setting){
            return $setting->value;
        }else{
            return false;
        }
    }
}
/** get setting data */

/** site logo */
if(!function_exists('_get_site_logo')){
    function _get_site_logo($key = 'header_logo'){
        $path = url('uploads/logo').'/';

        $setting = \DB::table('setting')->select('value')->where(['keys' => $key])->first();

        if($setting){
            return $path.$setting->value;
        }else{
            return false;
        }
    }
}
/** site logo */

/** get role id */
if(!function_exists('_get_role_id')){
    function _get_role_id($name){
        $data = \DB::table('role')->select('id')->where(['name' => ucwords($name)])->first();
        if($data){
            return $data->id;
        }else{
            return false;
        }
    }
}
/** get role id */

/** get role name */
if(!function_exists('_get_role_name')){
    function _get_role_name($id){
        $data = \DB::table('role')->select('name')->where(['id' => $id])->first();

        if($data){
            return $data->name;
        }else{
            return false;
        }
    }
}
/** get role name */
