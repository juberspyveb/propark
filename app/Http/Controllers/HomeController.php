<?php
namespace App\Http\Controllers;
use App\Models\Admin;
use App\Models\UserToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Validator, Auth, Session, DB, Mail, Hash, URL, DateTime;
use Illuminate\Support\Facades\Artisan;
class HomeController extends Controller{


    public function __construct()
    {

    }
    public function index(Request $request){
        if(!Auth::check()){
            return redirect(route('adminlogin'));
        }
        return "Under Development";
        #return view("front.views.index");
    }
    public function logout(Request $request){
        // $request->session()->forget(['is_front_active', 'login_details']);
        $request->session()->forget(['is_front_active', 'login_details', 'cart_user_id','userId']);
        \Session::flush();

        Auth::logout();
        // return redirect()->route('/');
        return redirect(_getLogoutUrl());
    }
}
