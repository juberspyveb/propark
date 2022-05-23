<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Http;
use JWTAuth;
use App\Models\User;
use App\Models\UserToken;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use DB, stdClass;

class AuthController extends ApiController
{
    public $statusCode;
    public $status;
    public $message;
    public function __construct()
    {
        $this->status = TRUE;
        $this->statusCode = Response::HTTP_OK;
        $this->message = '';
        $this->objUser = new User();
        #$this->objProduct = new Product();
    }
    /** Login:
     * Authenticate the Mobile number
     *  If number exist then return success response other wise error
     **/
    public function authenticate(Request $request)
    {
        $credentials = $request->only('mobile');


        //valid credential
        $validator = Validator::make($credentials, [
            //'email' => 'required|email',
            'mobile' => 'required|string'//|min:6|max:50
        ]);

        //Send failed response if request is not valid
        /*if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], Response::HTTP_BAD_REQUEST);
        }*/
        if ($validator->fails()) {
            $this->status = false;
            $this->statusCode = Response::HTTP_BAD_REQUEST;
            $this->message = _lvValidations($validator->messages()->get('*'),true);
            $responseData = array('status'=>$this->status,'message'=>$this->message,'statusCode'=>$this->statusCode,'data'=>array());
            return response()->json($responseData,$this->statusCode);
        }
        try {
            $responseUser = User::where('mobile', '=', $credentials['mobile'])->first();
            if($responseUser){
                return response()->json([
                    'status' => $this->status,
                    'message' => __('api.login_user_exist'),
                    //'data' => $responseUser,
                ]);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => __('api.login_user_not_exist'),
                ]);

            }

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => __('api.common_error_500'),
            ], 500);
        }


    }

    /** get-user:
     *  verify mobile number, If number exist and firebase id not then Update the firebase id against mobile number and return success response.
     *  If both match then return success response other wise error
     **/
    public function getUser(Request $request)
    {
        $credentials = $request->only('mobile','firebase_id');

        //valid credential
        $validator = Validator::make($credentials, [
            'firebase_id' => 'required',
            'mobile' => 'required|string'
        ]);

       

        if ($validator->fails()) {
            $this->status = false;
            $this->statusCode = Response::HTTP_BAD_REQUEST;
            $this->message = _lvValidations($validator->messages()->get('*'),true);
            $responseData = array('status'=>$this->status,'message'=>$this->message,'statusCode'=>$this->statusCode,'data'=>array());
            return response()->json($responseData,$this->statusCode);
        }
        try {
            $responseUser = User::where('mobile', '=', $credentials['mobile'])->first();
            if($responseUser){
                $userId = $responseUser->id;
                #If Null then Update firebase_Id to mobile number
                if(!$responseUser->firebase_id){
                    $crud['firebase_id'] = $credentials['firebase_id'];
                    $crud['updated_at'] = getCurrentDateTime();
                    $responseUpdatePassword = \DB::table('users')->where('id', $userId)->limit(1)->update($crud);
                    if(!$responseUpdatePassword){
                        $this->status = false;
                        $this->statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
                        $this->message =  __('api.update_fire_base_id_failed');
                        $responseData = array('status'=>$this->status,'message'=>$this->message,'statusCode'=>$this->statusCode,'data'=>array());
                        return response()->json($responseData,$this->statusCode);
                    }
                }
                #if not null then compare firebase_id
                $select = \DB::raw('u.id,u.mobile,u.firebase_id,u.name,u.lot_id,l.lot_name,s.supervisor_name,s.supervisor_mobile');
                $dataBase = \DB::table('users as u')->select($select);
               
                $dataBase->leftJoin('lots as l', function ($leftJoin) {
                    $leftJoin->on('u.lot_id', '=', 'l.id');
                    ##$leftJoin->where('active', 1);
                });
                $dataBase->leftJoin('supervisors as s', function ($leftJoin) {
                    $leftJoin->on('l.supervisor_id', '=', 's.id');
                    ##$leftJoin->where('active', 1);
                });
                $dataBase->where('mobile', $credentials['mobile']);
                $responseUser  = $dataBase->first();
                // dd($responseUser);
                #$responseUser = User::where('mobile', '=', $credentials['mobile'])->select('id','mobile','firebase_id','name','lot_name','lot_id','lot_id','supervisor_name','supervisor_mobile')->first();
                if(isset($responseUser->firebase_id)  && $responseUser->firebase_id === $credentials['firebase_id']){
                    #if matched
                   
                    $responseUser->name = ($responseUser->name) ? $responseUser->name : '';
                    $responseUser->lot_name = ($responseUser->lot_name) ? $responseUser->lot_name : '';
                    $responseUser->lot_id = ($responseUser->lot_id) ? $responseUser->lot_id : 0;
                    $responseUser->supervisor_name = ($responseUser->supervisor_name) ? $responseUser->supervisor_name : '';
                    $responseUser->supervisor_mobile = ($responseUser->supervisor_mobile) ? $responseUser->supervisor_mobile : '';
                    #_pre($responseUser);
                    $tokenUser = User::where('mobile', '=', $credentials['mobile'])->select('id','mobile','firebase_id','name','lot_id')->first();
                    $token = JWTAuth::customClaims([])->fromUser($tokenUser );
                    #dd($token );
                    //Token created, return with success response and jwt token
                    /*return response()->json([
                        'status' => true,
                        'token' => $token,
                    ]);*/
                    // dd($responseUser);
                    return response()->json([
                        'status' => $this->status,
                        'message' => __('api.login_success'),
                        'data' => $responseUser,
                        'token' => $token
                    ]);
                }else{
                    #if not matched
                    return response()->json([
                        'status' => false,
                        'message' => __('api.login_user_not_exist'),
                    ], Response::HTTP_UNAUTHORIZED);
                }
            }else{
                //not matched mobile
                return response()->json([
                    'status' => false,
                    'message' => __('api.login_user_not_exist'),
                ], Response::HTTP_UNAUTHORIZED);

            }

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => __('api.common_error_500'),
            ], 500);
        }
    }

    /** get user information from JWT TOKEN **/
    public function _user(Request $request)
    {
        /*$this->validate($request, [
            'token' => 'required'
        ]);*/

        $user = JWTAuth::authenticate($request->token);
        return $user;
    }

    /*public function logout(Request $request)
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
                'status' => true,
                'message' => 'User has been logged out'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'status' => false,
                'message' => 'Sorry, user cannot be logged out'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }*/

}