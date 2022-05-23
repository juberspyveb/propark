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

class NumberPlatController extends ApiController
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
    /** check-number-plate
     *  Check number plate exist then return success response otherwise error response
     **/
    public function checkNumberPlate(Request $request)
    {
        try {

            $userId = $this->user->id;
            $requestData = $request->only('number_plate');

            //valid credential
            $validator = Validator::make($requestData, [
                'number_plate' => 'required',
            ]);


            if ($validator->fails()) {
                $this->status = false;
                $this->statusCode = Response::HTTP_BAD_REQUEST;
                $this->message = _lvValidations($validator->messages()->get('*'),true);
                $responseData = array('status'=>$this->status,'message'=>$this->message);
                return response()->json($responseData,$this->statusCode);
            }else{
                $userId = $this->user->id;
                #$responseUserDetails = \DB::table('users')->where(['id' => $userId])->first();

                $select = \DB::raw('np.*,c.mobile');
                $dataBase = \DB::table('number_plats as np')->select($select);
                $dataBase->leftJoin('customers as c', function ($leftJoin) {
                    $leftJoin->on('np.customer_id', '=', 'c.id');
                });
                $dataBase->where('number_plate', $requestData['number_plate']);
                $responseNumberPlate  =  $dataBase->get()->first();
                if($responseNumberPlate){
                    //exist
                    $responseTempData = array(
                        "is_licence_plate_added" =>true,
                        "customer_id" =>$responseNumberPlate->customer_id,
                        "mobile" => ($responseNumberPlate->mobile) ? $responseNumberPlate->mobile : '',
                        "number_plate_id" => $responseNumberPlate->id
                    );
                    #$this->message =  __('api.api_current_password_success');
                    $responseData = array('status'=>$this->status,'message'=>$this->message,'data'=>$responseTempData);
                    return response()->json($responseData,$this->statusCode);
                }else {
                    //insert customer and number plate
                    /*$crud['mobile'] = '';
                    $crud['created_at'] = getCurrentDateTime();
                    $crud['created_by'] = $userId;
                    $insertedId = \DB::table('customers')->insertGetId($crud);
                    if($insertedId){
                        $crudNp['number_plate'] = trim($requestData['numberPlate']);
                        $crudNp['customer_id'] = $insertedId ;
                        $crudNp['created_at'] = getCurrentDateTime();
                        $insertedIdNp = \DB::table('number_plats')->insertGetId($crudNp);
                        if($insertedIdNp){
                            $responseTempData = array(
                                "is_licence_plate_added" =>true,
                                "customer_id" => $insertedId,
                                "mobile" =>  '',
                            );
                            $responseData = array('status'=>$this->status,'message'=>$this->message,'data'=>$responseTempData);
                            return response()->json($responseData,$this->statusCode);
                        }
                    }*/
                    $this->status = false;
                    //$this->statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
                    $this->message =  __('api.api_number_plate_not_available');
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

    /** create-customer
     *  create customer using mobile[optional] and number plate then return success response otherwise error response
     **/
    public function createCustomer(Request $request)
    {
        try {

            $userId = $this->user->id;
            $requestData = $request->only('number_plate','mobile');
            //valid credential
            $validator = Validator::make($requestData, [
                'number_plate' => 'required',
                // 'mobile' => 'required',
            ]);
            if ($validator->fails()) {
                $this->status = false;
                $this->statusCode = Response::HTTP_BAD_REQUEST;
                $this->message = _lvValidations($validator->messages()->get('*'),true);
                $responseData = array('status'=>$this->status,'message'=>$this->message);
                return response()->json($responseData,$this->statusCode);
            }else{
                $userId = $this->user->id;
                #$responseUserDetails = \DB::table('users')->where(['id' => $userId])->first();

                $select = \DB::raw('np.*,c.mobile');
                $dataBase = \DB::table('number_plats as np')->select($select);
                $dataBase->leftJoin('customers as c', function ($leftJoin) {
                    $leftJoin->on('np.customer_id', '=', 'c.id');
                });
                $dataBase->where('number_plate', $requestData['number_plate']);
                $responseNumberPlate  =  $dataBase->get()->first();
                // dd($responseNumberPlate);
                if($responseNumberPlate){
                    $crud['mobile'] = $requestData['mobile'];
                    $crud['updated_at'] = getCurrentDateTime();
                    $crud['updated_by'] = $userId;
                    $responseUpdateCustomer = \DB::table('customers')->where('id', $responseNumberPlate->customer_id)->limit(1)->update($crud);
                    if($responseUpdateCustomer){
                        #$responseNumberPlate->mobile = $crud['mobile'] ;
                        #$this->message =  __('api.api_current_password_success');
                        $responseTempData = array(
                            'customer_id' => $responseNumberPlate->customer_id,
                            'number_plate' => $responseNumberPlate->number_plate,
                            'mobile' => ($requestData['mobile'])? $requestData['mobile'] : '',
                            "number_plate_id" => $responseNumberPlate->id
                        );
                        $responseData = array('status'=>$this->status,'message'=>$this->message,'data'=>$responseTempData);
                        return response()->json($responseData,$this->statusCode);
                    }else{
                        $this->status = false;
                        $this->statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
                        $this->message =  __('api.common_error_500');
                        $responseData = array('status'=>$this->status,'message'=>$this->message);
                        return response()->json($responseData,$this->statusCode);
                    }
                }else {
                    //insert customer and number plate
                    $crud['mobile'] = $requestData['mobile'];
                    $crud['created_at'] = getCurrentDateTime();
                    $crud['created_by'] = $userId;
                    $insertedId = \DB::table('customers')->insertGetId($crud);
                    if($insertedId){
                        $crudNp['number_plate'] = trim($requestData['number_plate']);
                        $crudNp['customer_id'] = $insertedId ;
                        $crudNp['created_at'] = getCurrentDateTime();
                        $insertedIdNp = \DB::table('number_plats')->insertGetId($crudNp);
                        if($insertedIdNp){
                            $responseTempData = array(
                                "customer_id" => $insertedId,
                                'number_plate' => $crudNp['number_plate'],
                                'mobile' => ($requestData['mobile']) ? $requestData['mobile'] : '',
                                "number_plate_id" => $insertedIdNp
                            );
                            $responseData = array('status'=>$this->status,'message'=>$this->message,'data'=>$responseTempData);
                            return response()->json($responseData,$this->statusCode);
                        }
                    }
                    $this->status = false;
                    $this->statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
                    $this->message =  __('api.common_error_500');
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

    /** customer/{id}
     *  Get customer Details
     **/
    public function getCustomer($customerId)
    {
        try {

            $userId = $this->user->id;
            $requestData = array('customer_id'=>$customerId);//$request->only('customer_id');
            //valid credential
            $validator = Validator::make($requestData, [
                'customer_id' => 'required',
            ]);
            if ($validator->fails()) {
                $this->status = false;
                $this->statusCode = Response::HTTP_BAD_REQUEST;
                $this->message = _lvValidations($validator->messages()->get('*'),true);
                $responseData = array('status'=>$this->status,'message'=>$this->message);
                return response()->json($responseData,$this->statusCode);
            }else{
                $userId = $this->user->id;

                $select = \DB::raw('np.*,c.mobile');
                $dataBase = \DB::table('customers as c')->select($select);
                $dataBase->leftJoin('number_plats as np', function ($leftJoin) {
                    $leftJoin->on('c.id', '=', 'np.customer_id');
                });
                $dataBase->where('c.id', $requestData['customer_id']);
                $responseCustomer  =  $dataBase->get()->first();
                if($responseCustomer){
                    $responseTempData = array(
                        'customer_id' => $responseCustomer->customer_id,
                        'number_plate' => $responseCustomer->number_plate,
                        'mobile' => ($responseCustomer->mobile) ? $responseCustomer->mobile : '',
                        "number_plate_id" => $responseCustomer->id
                    );
                    $responseData = array('status'=>$this->status,'message'=>$this->message,'data'=>$responseTempData);
                    return response()->json($responseData,$this->statusCode);
                }else {

                    $this->status = false;
                    #$this->statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
                    $this->message =  __('api.api_customer_not_available');
                    $responseData = array('status'=>$this->status,'message'=>$this->message);
                    return response()->json($responseData,$this->statusCode);
                }
            }


        } catch (\Exception $e) {
            dd($e);
            return response()->json([
                'success' => false,
                'message' => __('api.common_error_500'),
            ], 500);
        }
    }
}
