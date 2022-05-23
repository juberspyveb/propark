<?php

#namespace App\Helpers; // Your helpers namespace
use App\Models\Admin, App\Product, App\Cart, App\User, App\Order, App\OrderItem, App\OrderAddress;
use App\Order_delivery_schedule;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\Auth;
#use Illuminate\Support\Facades\Session;

if (! function_exists('testHelper')) {
    function testHelper()
    {
        return " Test Helper Work!";

    }
}



/** Generate  Random Unique Id for Cart Or use Database User Id if logged In*/
if (! function_exists('cartUserId')) {
    function cartUserId()
    {
        if(Session::has('userId')){
            # \Session::forget(['userId', 'login_details']);
            return Session::get('userId');
            # die(" in asda ".Session::get('userId'));
        }

        $objUser = Auth::user();
        if($objUser != null)
        {
            $userId= $objUser->id;
        }else{
            $userId=  time().rand(111,999);//uniqid();
            #$userId=  uniqid('u');//with prefix U
            #$userId=  hexdec(uniqid());//convert into numbers
        }
        Session::put('userId', $userId);
        Session::save();
        return $userId;
    }
}

/*
 Transaction id format:
Lot number + Bay Number + Attendant Id + Sequential #
Ex 0125 + 0372 + 0055 + 0000001
=> 0125037200550000001
Transaction status:
Hold, Complete, Unpaid, Infringement, Closed

  */

if (! function_exists('_orderNumber')) {
    function _orderNumber($orderId, $data)
    {
        $select = DB::raw('COALESCE(count(id),0) AS totalOrderNumber');
        $responseTotalOrders = \DB::table('transactions')->select($select)->where('id', '<', $orderId)->first();
        $tempNo = $responseTotalOrders->totalOrderNumber + 1;
        $sequenceNo = str_pad($tempNo , 7, 0, STR_PAD_LEFT);
        $lotNumber =  str_pad($data['lot_id'], 4, 0, STR_PAD_LEFT);
        $bayNumber =  str_pad($data['slot_id'], 5, 0, STR_PAD_LEFT);
        $attendantId =  str_pad($data['created_by'], 5, 0, STR_PAD_LEFT);
        return $lotNumber.$bayNumber.$attendantId.$sequenceNo;
    }
}


if (! function_exists('isAdminLogged')) {
    function isAdminLogged()
    {
        return (session()->get('is_active')) ? true : false ;
    }
}
