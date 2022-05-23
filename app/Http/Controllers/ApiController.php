<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    public function __construct()
    {
        $this->status = TRUE;
        $this->statusCode = Response::HTTP_OK;
        $this->message = '';
        $methodName = \Illuminate\Support\Facades\Route::getCurrentRoute()->getActionMethod();
        $exceptActionArray = array('login','forgotPassword','getUser');
        if(!in_array($methodName,$exceptActionArray)){
            $this->tokenData();
        }

    }
    public function tokenData(){
        $this->user = JWTAuth::parseToken()->authenticate();
        #$payload = JWTAuth::parseToken()->getPayload();

        $this->test = 'This is test';
        #_pre($this->useToken);
        /*if($payload->get('useSession')){
            $this->useToken = $payload->get('useSession');
        }*/

        #dd($this->useToken);
    }

    public function logout(Request $request)
    {
        //valid credential
        $validator = Validator::make($request->only('token'), [
            'token' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is validated, do logout
        try {
            JWTAuth::invalidate($request->token);

            return response()->json([
                'success' => true,
                'message' => 'User has been logged out'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, user cannot be logged out'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

   /* public function get_user(Request $request)
    {

        $user = JWTAuth::authenticate($request->token);
        #dd($user);
        return response()->json(['user' => $user]);
    }*/
}