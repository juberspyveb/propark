<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\Category;
use App\Models\MenuBranding;
use App\Models\User;
use App\Models\UserCategory;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use JWTAuth;
use Mockery\Exception;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class UserController extends ApiController
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
        $this->user = JWTAuth::parseToken()->authenticate();

    }
    public function index(){
    }
    /** update-online-status:
     *  Update attendant online status.
     *
     **/
    public function updateOnlineStatus(Request $request)
    {
        try {
            $requestData = $request->only('is_online');
            //valid credential
            $validator = Validator::make($requestData, [
                'is_online' => 'required|in:0,1'
            ]);

            //Send failed response if request is not valid
            /*if ($validator->fails()) {
                return response()->json(['error' => $validator->messages()], Response::HTTP_BAD_REQUEST);
            }*/
            if ($validator->fails()) {
                $this->status = false;
                $this->statusCode = Response::HTTP_BAD_REQUEST;
                $this->message = _lvValidations($validator->messages()->get('*'),true);
                $responseData = array('status'=>$this->status,'message'=>$this->message);
                return response()->json($responseData,$this->statusCode);
            }else{
                $userId = $this->user->id;

                $crud['is_online'] = $requestData['is_online'];
                $crud['updated_at'] = getCurrentDateTime();
                $responseUpdatePassword = \DB::table('users')->where('id', $userId)->limit(1)->update($crud);
                if($responseUpdatePassword){
                    $this->message =  __('api.api_online_status_update_success');
                    $responseData = array('status'=>$this->status,'message'=>$this->message);
                    return response()->json($responseData,$this->statusCode);
                }else{
                    $this->status = false;
                   # $this->statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
                    $this->message =  __('api.api_online_status_update_error');
                    $responseData = array('status'=>$this->status,'message'=>$this->message);
                    return response()->json($responseData,$this->statusCode);
                }
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('api.common_error_500'),
            ], 500);
        }
    }

}
