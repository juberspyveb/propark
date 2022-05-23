<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Validator;
use Auth;
use Session;
use DB;

class AdminController extends Controller
{
    public function adminLoginForm()
    {
        // Get URLs
    
    $urlPrevious = url()->previous();
    $urlBase = url()->to('/');
    
        if(session("is_active") == 1)
        {
            //return redirect()->route('admin-dashboard');
            return redirect()->route('admin-users-list');
        }
        else
        {
            return view("admin.views.login_form");
        }
    }

    public function checkUserLogin(Request $request)
    {
       # dd($request->all());
        $validator = Validator::make(array(
                "email" => $request->email,
                "password" => $request->password
            ), array(
                "email" => "required",
                "password" => "required"
            ));

        if($validator->fails())
        {
            return redirect()->route('adminlogin')->withErrors($validator)->withInput();
        }
        else
        {
            $user_info = array(
                "email" => $request->email,
                "password" => $request->password
            );
            #print_r($user_info);exit;

            # var_dump(auth()->guard("admin")->attempt($user_info));
             #exit;
              
            if(auth()->guard("admin")->attempt($user_info))
            {
                $logged_user_details = auth()->guard("admin")->user();
                $role_id=$logged_user_details->role_id;
                 Session::put('role_id', $role_id);
                session(["is_active" => 1]);
                session(["user_details" => $logged_user_details]);
                #return redirect()->route('admin-dashboard');
                return redirect()->route('admin-users-list');
            }
            else
            {
                $error_message = __('message_lang.INVALID_CREDENTIALS');
                return redirect()->back()->withErrors($error_message);
            }
        }
    }

    public function logout(Request $request)
    {
        // Session::flush();
        
        // Forget multiple keys...
        $request->session()->forget(['is_active', 'user_details']);
        
        Auth::guard("admin")->logout();
        return redirect()->route('adminlogin');
    }

}
