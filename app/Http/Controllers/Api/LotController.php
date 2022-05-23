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
use Illuminate\Validation\Rule;
class LotController extends ApiController
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
    /** get-bays
     *  Return bays list attendant wise
     **/
    public function getBays(Request $request)
    {
        try {

            $userId = $this->user->id;
            $lotId = $this->user->lot_id;
            if($lotId ){
                $responseBays = \DB::table('lots')->where('id', $lotId)->first();
                if($responseBays){
                    #$responseSlots = \DB::table('slots')->where('lot_id', $lotId)->orderBy('is_available','DESC')->get()->all();

                    $select = \DB::raw('s.*'); //,np.number_plate
                    $dataBase = \DB::table('slots as s')->select($select);
                    /*$dataBase->leftJoin('customers as c', function ($leftJoin) {
                        $leftJoin->on('s.customer_id', '=', 'c.id');
                    });*/
                    /*$dataBase->leftJoin('number_plats as np', function ($leftJoin) {
                        $leftJoin->on('s.number_plate_id', '=', 'np.id');
                        ##$leftJoin->where('active', 1);
                    });*/
                    $responseSlots  =  $dataBase->where('s.lot_id',$lotId)->orderBy('s.is_available','ASC')->get()->all();
                    $slotList = array('lot_id'=>$responseBays->id,'lot_name'=>$responseBays->lot_name,'bays'=>[]);

                    if($responseSlots){
                        foreach($responseSlots as $k=>&$row){
                            #$responseData
                            # $row->arrival_time = ($row->arrival_time) ? $row->arrival_time  : '';
                            #$row->number_plate = ($row->number_plate) ? $row->number_plate  : '';
                            unset($row->created_at);
                            unset($row->updated_at);
                            unset($row->status);
                            if(!$row->is_available){

                                #$responseTransaction = \DB::table('transactions')->where('slot_id', $row->id)->first();
                                $select2 = \DB::raw('T.*,np.number_plate,C.mobile');
                                $dataBase2 = \DB::table('transactions as T')->select($select2);
                                $dataBase2->leftJoin('number_plats as np', function ($leftJoin) {
                                    $leftJoin->on('T.number_plate_id', '=', 'np.id');
                                    ##$leftJoin->where('active', 1);
                                });
                                $dataBase2->leftJoin('customers as C', function ($leftJoin) {
                                    $leftJoin->on('T.customer_id', '=', 'C.id');
                                });

                                $responseTransaction  =  $dataBase2->where('T.slot_id', $row->id)->orderBy('T.id','DESC')->get()->first();
                                #dd($responseTransaction  );
                                if($responseTransaction){
                                    $row->customer_id = $responseTransaction->customer_id;
                                    $row->created_at = $responseTransaction->created_at;
                                    $row->number_plate_id = $responseTransaction->number_plate_id;
                                    $row->transaction_number = $responseTransaction->transaction_number;
                                    $row->number_plate = $responseTransaction->number_plate;
                                    $row->arrival_time = $responseTransaction->arrival_time;
                                    $row->parking_time = $responseTransaction->parking_time;
                                    $row->amount = $responseTransaction->amount;
                                    $row->paid_amount = $responseTransaction->paid_amount;
                                    $row->change_amount = $responseTransaction->change_amount;
                                    $row->status = $responseTransaction->status;
                                    $row->mobile = ($responseTransaction->mobile) ? $responseTransaction->mobile : '';
                                    $row->remaining_time = 0;
                                    #$mins = dateDifferenceDataV2($responseTransaction->arrival_time,getCurrentDateTime(),'i');
                                    if($row->arrival_time > getCurrentDateTime()){
                                        $diffData = dateDifferenceDataV2($row->arrival_time,getCurrentDateTime());
                                        $mins = ( ($diffData->d * 24*60)  +  ($diffData->h * 60)  + ($diffData->i) );
                                        $row->remaining_time = ($responseTransaction->parking_time + $mins );
                                    }else{
                                        $diffData = dateDifferenceDataV2($row->arrival_time,getCurrentDateTime());
                                        $mins = ( ($diffData->d * 24*60)  +  ($diffData->h * 60)  + ($diffData->i) );
                                        $row->remaining_time = ($responseTransaction->parking_time - $mins );
                                    }
                                    /*$diffData = dateDifferenceDataV2($row->arrival_time,getCurrentDateTime());
                                    $mins = ( ($diffData->d * 24*60)  +  ($diffData->h * 60)  + ($diffData->i) );
                                    $row->remaining_time = ($responseTransaction->parking_time - $mins );*/
                                }
                                #dd($responseTransaction );

                            }
                        }
                    }
                    $slotList['bays'] = $responseSlots;

                    #$this->message =  __('api.api_online_status_update_success');
                    $responseData = array('status'=>$this->status,'message'=>$this->message,'data'=>$slotList);
                    return response()->json($responseData,$this->statusCode);
                }else{
                    //$this->status = false;
                    $this->message =  __('api.api_lot_not_available');
                    $responseData = array('status'=>$this->status,'message'=>$this->message);
                    return response()->json($responseData,$this->statusCode);
                }
            }else{
                //$this->status = false;
                $this->message =  __('api.api_lot_not_available');
                $responseData = array('status'=>$this->status,'message'=>$this->message);
                return response()->json($responseData,$this->statusCode);
            }


        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('api.common_error_500'),
            ], 500);
        }
    }

    /** add-time
     *  Add parking time and time extended process  [Transaction Create Process]
     **/
    public function addTime(Request $request){
        try {

            $userId = $this->user->id;
            $lotId = $this->user->lot_id;
            $requestData = $request->only('customer_id','bay_id','number_plate_id','arrival_time','amount','paid_amount','change_amount','parking_time','time_extended');
            //valid credential
            $validator = Validator::make($requestData, [
                'customer_id' => 'required',
                'bay_id' => 'required',
                'number_plate_id' => 'required',
                # 'arrival_time' => 'required',
                'amount' => 'required',
                'paid_amount' => 'required',
                'change_amount' => 'required',
                'parking_time' => 'required|numeric',
                'time_extended' => 'required',
            ]);

            if ($validator->fails()) {
                $this->status = false;
                $this->statusCode = Response::HTTP_BAD_REQUEST;
                $this->message = _lvValidations($validator->messages()->get('*'),true);
                $responseData = array('status'=>$this->status,'message'=>$this->message);
                return response()->json($responseData,$this->statusCode);
            }else {
                $customerId = $requestData['customer_id'];
                $bayId = $requestData['bay_id'];
                $numberPlateId = $requestData['number_plate_id'];
                $amount = $requestData['amount'];
                $paidAmount = $requestData['paid_amount'];
                $changeAmount = $requestData['change_amount'];
                $parkingTime = $requestData['parking_time'];
                $timeExtended =  intval(filter_var(    $requestData['time_extended'], FILTER_VALIDATE_BOOLEAN)) ;//? intval($requestData['time_extended']) : 0 ;

                $responseSlot = \DB::table('slots')->where('id', $bayId)->where('lot_id', $lotId)->where('status','Active')->first();
                $responseCustomer = \DB::table('customers')->where('id', $customerId)->where('status','Active')->first();
                $responseNP = \DB::table('number_plats')->where('id', $numberPlateId)->where('status','Active')->first();
                if($responseSlot && $responseNP && $responseCustomer){
                    if($responseSlot->is_available ){
                        $crud = array(
                            'transaction_number' => 1,
                            'slot_id' => $bayId,
                            'customer_id' => $customerId,
                            'number_plate_id' => $numberPlateId,
                            'amount' => $amount,
                            'paid_amount' => $paidAmount,
                            'change_amount' => $changeAmount,
                            'arrival_time' => getCurrentDateTime(),
                            'status' => config('constants.transactions.IN-PROCESS'),
                            'created_at' => getCurrentDateTime(),
                            'created_by' => $userId,
                            'parking_time' => $parkingTime,
                            'time_extended' => $requestData['time_extended'],
                            
                        );
                        $orderId = \DB::table('transactions')->insertGetId($crud);
                        if($orderId ){
                            #TODO mark slot as not available
                            $crudSlot['is_available'] = 0;
                            $crudSlot['updated_at'] = getCurrentDateTime();
                            $responseMarkUnavailable = \DB::table('slots')->where('id', $bayId)->limit(1)->update($crudSlot);

                            $crud['lot_id'] = $responseSlot->lot_id;
                            $transactionNumber = _orderNumber($orderId, $crud);

                            $crudUpdate['transaction_number'] = $transactionNumber;
                            $crudUpdate['updated_at'] = getCurrentDateTime();
                            $crudUpdate['updated_by'] = $userId;
                            $responseUpdatTrans = \DB::table('transactions')->where('id', $orderId)->limit(1)->update($crudUpdate);

                            $select = \DB::raw('T.*,C.mobile,S.is_available,NP.number_plate,S.slot_number');
                            $dataBase = \DB::table('transactions as T')->select($select);
                            $dataBase->leftJoin('customers as C', function ($leftJoin) {
                                $leftJoin->on('C.id', '=', 'T.customer_id');
                            });
                            $dataBase->leftJoin('slots as S', function ($leftJoin) {
                                $leftJoin->on('S.id', '=', 'T.slot_id');
                            });
                            $dataBase->leftJoin('number_plats as NP', function ($leftJoin) {
                                $leftJoin->on('NP.id', '=', 'T.number_plate_id');
                                ##$leftJoin->where('active', 1);
                            });
                            $responseTransaction  =  $dataBase->where('T.id', $orderId)->get()->first();
                            $responseTransaction->mobile = ($responseTransaction->mobile) ? $responseTransaction->mobile : '';
                            $responseTransaction->remaining_time = $responseTransaction->parking_time;

                            $responseData = array('status'=>$this->status,'message'=>$this->message,'data'=>$responseTransaction);
                            return response()->json($responseData,$this->statusCode);

                        }else{
                            $this->status = false;
                            $this->statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
                            $this->message =  __('api.api_number_plate_not_available');
                            $responseData = array('status'=>$this->status,'message'=>$this->message);
                            return response()->json($responseData,$this->statusCode);
                        }
                    }
                    else{
                        if($timeExtended){
                            $select = \DB::raw('T.*,C.mobile,S.is_available,NP.number_plate,S.slot_number');
                            $dataBase = \DB::table('transactions as T')->select($select);
                            $dataBase->leftJoin('customers as C', function ($leftJoin) {
                                $leftJoin->on('C.id', '=', 'T.customer_id');
                            });
                            $dataBase->leftJoin('slots as S', function ($leftJoin) {
                                $leftJoin->on('S.id', '=', 'T.slot_id');
                            });
                            $dataBase->leftJoin('number_plats as NP', function ($leftJoin) {
                                $leftJoin->on('NP.id', '=', 'T.number_plate_id');
                                ##$leftJoin->where('active', 1);
                            });
                            $responseTransaction  =  $dataBase->where('T.number_plate_id', $numberPlateId)->where('T.status', config('constants.transactions.IN-PROCESS'))->orderBy('T.id','DESC')->get()->first();

                            //&& config('constants.transactions.IN-PROCESS') == $responseTransaction->status
                            if($responseTransaction && $bayId == $responseTransaction->slot_id ){
                                $orderId = $responseTransaction->id;
                                $crudUpdate['updated_at'] = getCurrentDateTime();
                                $crudUpdate['updated_by'] = $userId;
                                $crudUpdate['status'] = config('constants.transactions.TIME-EXTENDED');
                                $responseUpdatTrans = \DB::table('transactions')->where('id', $orderId)->limit(1)->update($crudUpdate);

                                #Insert new records
                                //$oldArriavleTime = $responseTransaction->arrival_time
                                $oldParkingTime = $responseTransaction->parking_time;
                                $newArrivalTime = date("Y-m-d H:i:s", strtotime('+'.$oldParkingTime.' minutes', strtotime($responseTransaction->arrival_time)));
                                $crud = array(
                                    'transaction_number' => 1,
                                    'slot_id' => $bayId,
                                    'customer_id' => $customerId,
                                    'number_plate_id' => $numberPlateId,
                                    'amount' => $amount,
                                    'paid_amount' => $paidAmount,
                                    'change_amount' => $changeAmount,
                                    'arrival_time' => $newArrivalTime,//getCurrentDateTime(),
                                    'status' => config('constants.transactions.IN-PROCESS'),
                                    'created_at' => getCurrentDateTime(),
                                    'created_by' => $userId,
                                    'parking_time' => $parkingTime,
                                );
                                $orderId = \DB::table('transactions')->insertGetId($crud);
                                if($orderId ){
                                    #TODO mark slot as not available
                                    $crudSlot['is_available'] = 0;
                                    $crudSlot['updated_at'] = getCurrentDateTime();
                                    $responseMarkUnavailable = \DB::table('slots')->where('id', $bayId)->limit(1)->update($crudSlot);

                                    $crud['lot_id'] = $responseSlot->lot_id;
                                    $transactionNumber = _orderNumber($orderId, $crud);

                                    $crudUpdate2['transaction_number'] = $transactionNumber;
                                    $crudUpdate2['updated_at'] = getCurrentDateTime();
                                    $crudUpdate2['updated_by'] = $userId;
                                    $responseUpdatTrans = \DB::table('transactions')->where('id', $orderId)->limit(1)->update($crudUpdate2);

                                    $select = \DB::raw('T.*,C.mobile,S.is_available,NP.number_plate,S.slot_number');
                                    $dataBase = \DB::table('transactions as T')->select($select);
                                    $dataBase->leftJoin('customers as C', function ($leftJoin) {
                                        $leftJoin->on('C.id', '=', 'T.customer_id');
                                    });
                                    $dataBase->leftJoin('slots as S', function ($leftJoin) {
                                        $leftJoin->on('S.id', '=', 'T.slot_id');
                                    });
                                    $dataBase->leftJoin('number_plats as NP', function ($leftJoin) {
                                        $leftJoin->on('NP.id', '=', 'T.number_plate_id');
                                        ##$leftJoin->where('active', 1);
                                    });
                                    $responseTransaction  =  $dataBase->where('T.id', $orderId)->get()->first();
                                    $responseTransaction->mobile = ($responseTransaction->mobile) ? $responseTransaction->mobile : '';
                                    $responseTransaction->remaining_time = $responseTransaction->parking_time;

                                    if($responseTransaction->arrival_time > getCurrentDateTime()){
                                        $diffData = dateDifferenceDataV2($responseTransaction->arrival_time,getCurrentDateTime());
                                        $mins = ( ($diffData->d * 24*60)  +  ($diffData->h * 60)  + ($diffData->i) );
                                        $responseTransaction->remaining_time = ($responseTransaction->parking_time + $mins );
                                    }else{
                                        $diffData = dateDifferenceDataV2($responseTransaction->arrival_time,getCurrentDateTime());
                                        $mins = ( ($diffData->d * 24*60)  +  ($diffData->h * 60)  + ($diffData->i) );
                                        $responseTransaction->remaining_time = ($responseTransaction->parking_time - $mins );
                                    }

                                    $responseData = array('status'=>$this->status,'message'=>$this->message,'data'=>$responseTransaction);
                                    return response()->json($responseData,$this->statusCode);

                                }else{
                                    $this->status = false;
                                    $this->statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
                                    $this->message =  __('api.api_number_plate_not_available');
                                    $responseData = array('status'=>$this->status,'message'=>$this->message);
                                    return response()->json($responseData,$this->statusCode);
                                }
                            }else{
                                #todo Slot not available
                                if($responseTransaction){
                                    $this->status = false;
                                    #$this->statusCode = Response::HTTP_BAD_REQUEST;
                                    $this->message =  __('api.api_add_time_already_booked');
                                    $responseData = array('status'=>$this->status,'message'=>$this->message);
                                    return response()->json($responseData,$this->statusCode);
                                }

                            }
                            #dd($responseTransaction);
                        }
                        #todo Slot not available
                        $this->status = false;
                        #$this->statusCode = Response::HTTP_BAD_REQUEST;
                        $this->message =  __('api.api_add_time_already_booked');
                        $responseData = array('status'=>$this->status,'message'=>$this->message);
                        return response()->json($responseData,$this->statusCode);
                    }
                }else{
                    #slot/customer/NP  not exist
                    //bad request
                    $this->status = false;
                    $this->statusCode = Response::HTTP_BAD_REQUEST;
                    $this->message =  __('api.api_add_time_bad_request');
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

    /** release-bay
     *  Transaction mark as complete/close and release bay
     **/
    public function releaseBay(Request $request)
    {
        try {

            $userId = $this->user->id;
            $requestData = $request->only('number_plate_id','is_overdue','parking_time','amount');
            //valid credential
            $validator = Validator::make($requestData, [
                'number_plate_id' => 'required',
                #'amount' => 'required',
                'is_overdue' => ['required', Rule::in(['true', 'false'])],

            ]);


            if ($validator->fails()) {
                $this->status = false;
                $this->statusCode = Response::HTTP_BAD_REQUEST;
                $this->message = _lvValidations($validator->messages()->get('*'),true);
                $responseData = array('status'=>$this->status,'message'=>$this->message);
                return response()->json($responseData,$this->statusCode);
            }else{
                $userId = $this->user->id;
                $numberPlateId = $requestData['number_plate_id'];
                // $isOverDue = intval($requestData['is_overdue']) ;
                $isOverDue = $requestData['is_overdue'];
                $parkingTime = (@$requestData['parking_time']) ? @$requestData['parking_time'] : 0 ;
                $amount = (@$requestData['amount']) ? @$requestData['amount'] : 0.00 ;

                $select = \DB::raw('T.*,C.mobile,S.is_available,NP.number_plate');
                $dataBase = \DB::table('transactions as T')->select($select);
                $dataBase->leftJoin('customers as C', function ($leftJoin) {
                    $leftJoin->on('C.id', '=', 'T.customer_id');
                });
                $dataBase->leftJoin('slots as S', function ($leftJoin) {
                    $leftJoin->on('S.id', '=', 'T.slot_id');
                });
                $dataBase->leftJoin('number_plats as NP', function ($leftJoin) {
                    $leftJoin->on('NP.id', '=', 'T.number_plate_id');
                    ##$leftJoin->where('active', 1);
                });
                $responseTransaction  =  $dataBase->where('T.number_plate_id', $numberPlateId)->orderBy('T.id','DESC')->get()->first();
                #dd($responseTransaction);
                if($responseTransaction ){
                    $orderId = $responseTransaction->id;
                    $bayId = $responseTransaction->slot_id;
                    #TODO mark slot as not available
                    $crudSlot['is_available'] = 1;
                    $crudSlot['updated_at'] = getCurrentDateTime();
                    $responseMarkUnavailable = \DB::table('slots')->where('id', $bayId)->limit(1)->update($crudSlot);

                    
                    if($isOverDue == 'true'){
                        
                        //Update current transaction
                        $crudUpdate['updated_at'] = getCurrentDateTime();
                        $crudUpdate['updated_by'] = $userId;
                        $crudUpdate['status'] = config('constants.transactions.INFRINGEMENT');
                        $responseUpdatTrans = \DB::table('transactions')->where('id', $orderId)->limit(1)->update($crudUpdate);

                        //Create new Transaction

                        $crud = array(
                            'transaction_number' => 1,
                            'slot_id' => $responseTransaction->slot_id,
                            'customer_id' => $responseTransaction->customer_id,
                            'number_plate_id' => $responseTransaction->number_plate_id,
                            'amount' => $amount,
                            'paid_amount' => 0.00,
                            'change_amount' => 0.00,
                            'arrival_time' => null,
                            'status' => config('constants.transactions.INFRINGEMENT'),
                            'created_at' => getCurrentDateTime(),
                            'created_by' => $userId,
                            'parking_time' => $parkingTime,
                        );
                        $orderId = \DB::table('transactions')->insertGetId($crud);

                        $this->message =  __('api.api_release_success');
                        $responseData = array('status'=>$this->status,'message'=>$this->message);
                        return response()->json($responseData,$this->statusCode);

                    }else{
                        
                        //Update transaction status
                        #$crudUpdate['transaction_number'] = $transactionNumber;
                        $crudUpdate['updated_at'] = getCurrentDateTime();
                        $crudUpdate['updated_by'] = $userId;
                        $crudUpdate['status'] = config('constants.transactions.CLOSE');
                        $responseUpdatTrans = \DB::table('transactions')->where('id', $orderId)->limit(1)->update($crudUpdate);

                        #$this->status = false;
                        //$this->statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
                        $this->message =  __('api.api_release_success');
                        $responseData = array('status'=>$this->status,'message'=>$this->message);
                        return response()->json($responseData,$this->statusCode);
                    }

                }else{
                    $this->status = false;
                    //$this->statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
                    $this->message =  __('api.api_number_plate_not_available');
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


    /** get-alerts
     *  Return alert list, all overdue bay list/transaction also consider 5 mins to goes to overdue
     **/
    public function getAlerts(Request $request)
    {
        try {

            #$userId = $this->user->id;
            $lotId = $this->user->lot_id;
            if($lotId ){
                $currentDateTime = getCurrentDateTime();//, (T.arrival_time-5) as PT
                $select = \DB::raw('T.*,np.number_plate,c.mobile, TIMESTAMPDIFF(MINUTE, T.arrival_time, "'.$currentDateTime.'") as time_diff_in_mins, (T.parking_time-5) as PT, (TIMESTAMPDIFF(MINUTE, T.arrival_time, "'.$currentDateTime.'") >= (T.parking_time-5) ) as TT , S.slot_number, S.is_available '); //,np.number_plate
                $dataBase = \DB::table('transactions as T')->select($select);
                $dataBase->leftJoin('slots as S', function ($leftJoin) {
                    $leftJoin->on('T.slot_id', '=', 'S.id');
                });
                $dataBase->leftJoin('lots as L', function ($leftJoin) {
                    $leftJoin->on('S.lot_id', '=', 'L.id');
                });
                $dataBase->leftJoin('number_plats as np', function ($leftJoin) {
                    $leftJoin->on('T.number_plate_id', '=', 'np.id');
                    ##$leftJoin->where('active', 1);
                });
                $dataBase->leftJoin('customers as c', function ($leftJoin) {
                    $leftJoin->on('T.customer_id', '=', 'c.id');
                });
                $responseAlerts =  $dataBase->where('L.id', $lotId)
                    ->where('T.status', config('constants.transactions.IN-PROCESS'))
                    ->whereRaw('TIMESTAMPDIFF(MINUTE, T.arrival_time,  "'.$currentDateTime.'") >= (T.parking_time-5)')
                    ->orderBy('T.arrival_time','ASC')->get()->all();
                #->whereRaw('TIMESTAMPDIFF(MINUTE, T.arrival_time, ?) >= ?', [$currentDateTime,'(T.parking_time-5)'])


                if($responseAlerts){
                    foreach($responseAlerts as $k=>&$row){
                        #$responseData
                        # $row->arrival_time = ($row->arrival_time) ? $row->arrival_time  : '';
                        #$row->number_plate = ($row->number_plate) ? $row->number_plate  : '';
                        #unset($row->created_at);
                        unset($row->updated_at);
                        unset($row->created_by);
                        unset($row->updated_by);
                        $row->mobile = ($row->mobile) ? $row->mobile : '';
                       # unset($row->status);

                        $row->remaining_time = 0;
                        if($row->arrival_time > getCurrentDateTime()){
                            $diffData = dateDifferenceDataV2($row->arrival_time,getCurrentDateTime());
                            $mins = ( ($diffData->d * 24*60)  +  ($diffData->h * 60)  + ($diffData->i) );
                            $row->remaining_time = ($row->parking_time + $mins );
                        }else{
                            $diffData = dateDifferenceDataV2($row->arrival_time,getCurrentDateTime());
                            $mins = ( ($diffData->d * 24*60)  +  ($diffData->h * 60)  + ($diffData->i) );
                            $row->remaining_time = ($row->parking_time - $mins );
                        }
                        #$diffData = dateDifferenceDataV2($row->arrival_time,getCurrentDateTime());
                        #$mins = ( ($diffData->d * 24*60)  +  ($diffData->h * 60)  + ($diffData->i) );
                        #_pre($mins,0);
                        #_pre($diffData);
                        #$row->remaining_time = ($row->parking_time - $mins );
                        $t = ($row->parking_time - $row->time_diff_in_mins);
                        /*$row->ta = $t;
                        if($t > 5 ){
                            $row->park_status = 'ALMOST DUE';
                        }else{
                             $row->park_status = 'OVERDUE';
                        }*/
                    }
                }
                $slotList['alerts'] = $responseAlerts;

                #$this->message =  __('api.api_online_status_update_success');
                $responseData = array('status'=>$this->status,'message'=>$this->message,'data'=>$slotList);
                return response()->json($responseData,$this->statusCode);

            }else{
                //$this->status = false;
                $this->message =  __('api.api_lot_not_available');
                $responseData = array('status'=>$this->status,'message'=>$this->message);
                return response()->json($responseData,$this->statusCode);
            }


        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('api.common_error_500'),
            ], 500);
        }
    }

    /**
     *  get-transactions
     *  return all transaction list attendant lot wise
    **/
    public function getTransactions(Request $request)
    {
        try {

            #$userId = $this->user->id;
            $lotId = $this->user->lot_id;
            if($lotId ){
                $filterDate  = ($request->filter_date) ? $request->filter_date : getCurrentDate() ;
                $currentDateTime = getCurrentDateTime();//, (T.arrival_time-5) as PT
                $select = \DB::raw('T.*,np.number_plate,c.mobile, TIMESTAMPDIFF(MINUTE, T.arrival_time, "'.$currentDateTime.'") as time_diff_in_mins, (T.parking_time-5) as PT, (TIMESTAMPDIFF(MINUTE, T.arrival_time, "'.$currentDateTime.'") >= (T.parking_time-5) ) as TT, S.slot_number, S.is_available '); //,np.number_plate
                $dataBase = \DB::table('transactions as T')->select($select);
                $dataBase->leftJoin('slots as S', function ($leftJoin) {
                    $leftJoin->on('T.slot_id', '=', 'S.id');
                });
                $dataBase->leftJoin('lots as L', function ($leftJoin) {
                    $leftJoin->on('S.lot_id', '=', 'L.id');
                });
                $dataBase->leftJoin('number_plats as np', function ($leftJoin) {
                    $leftJoin->on('T.number_plate_id', '=', 'np.id');
                    ##$leftJoin->where('active', 1);
                });
                $dataBase->leftJoin('customers as c', function ($leftJoin) {
                    $leftJoin->on('T.customer_id', '=', 'c.id');
                });
                if($filterDate){
                    $from = date('Y-m-d 00:00:00',strtotime($filterDate));
                    $to = date('Y-m-d 23:59:59',strtotime($filterDate));
                    //$dataBase->where('T.created_at1',">=", [$filterDate]);
                    $dataBase->whereBetween('T.created_at', [$from, $to]);
                }
                $responseTransactions =  $dataBase->where('L.id', $lotId)
                    #->where('T.status', config('constants.transactions.IN-PROCESS'))
                    #->whereRaw('TIMESTAMPDIFF(MINUTE, T.arrival_time, ?) >= ?', [$currentDateTime,'(T.parking_time-5)'])
                    #->whereRaw('TIMESTAMPDIFF(MINUTE, T.arrival_time,  "'.$currentDateTime.'") >= (T.parking_time-5)')
                    ->orderBy('T.arrival_time','DESC')->get()->all();

                if($responseTransactions){
                    foreach($responseTransactions as $k=>&$row){
                        #$responseData
                        # $row->arrival_time = ($row->arrival_time) ? $row->arrival_time  : '';
                        #$row->number_plate = ($row->number_plate) ? $row->number_plate  : '';
                        #unset($row->created_at);
                        unset($row->updated_at);
                        unset($row->created_by);
                        unset($row->updated_by);
                        $row->mobile = ($row->mobile) ? $row->mobile : '';
                       # unset($row->status);

                        $row->remaining_time = 0;
                        /*$diffData = dateDifferenceDataV2($row->arrival_time,getCurrentDateTime());
                        $mins = ( ($diffData->d * 24*60)  +  ($diffData->h * 60)  + ($diffData->i) );
                        $row->remaining_time = ($row->parking_time - $mins );*/
                        if($row->arrival_time > getCurrentDateTime()){
                            $diffData = dateDifferenceDataV2($row->arrival_time,getCurrentDateTime());
                            $mins = ( ($diffData->d * 24*60)  +  ($diffData->h * 60)  + ($diffData->i) );
                            $row->remaining_time = ($row->parking_time + $mins );
                        }else{
                            $diffData = dateDifferenceDataV2($row->arrival_time,getCurrentDateTime());
                            $mins = ( ($diffData->d * 24*60)  +  ($diffData->h * 60)  + ($diffData->i) );
                            $row->remaining_time = ($row->parking_time - $mins );
                        }
                        $t = ($row->parking_time - $row->time_diff_in_mins);
                        /*$row->ta = $t;
                        if($t > 5 ){
                            $row->park_status = 'ALMOST DUE';
                        }else{
                             $row->park_status = 'OVERDUE';
                        }*/
                    }
                }
                $slotList['transactions'] = $responseTransactions;

                #$this->message =  __('api.api_online_status_update_success');
                $responseData = array('status'=>$this->status,'message'=>$this->message,'data'=>$slotList);
                return response()->json($responseData,$this->statusCode);

            }else{
                //$this->status = false;
                $this->message =  __('api.api_lot_not_available');
                $responseData = array('status'=>$this->status,'message'=>$this->message);
                return response()->json($responseData,$this->statusCode);
            }


        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('api.common_error_500'),
            ], 500);
        }
    }

    /**
     *  add-log-issue
     *  add log issue
     **/
    public function addLogIssue(Request $request){
        try {

            $userId = $this->user->id;
            $lotId = $this->user->lot_id;
            $requestData = $request->only('title','description');
            $validator = Validator::make($requestData, [
                'title' => 'required',
                'description' => 'required',
            ]);
            if ($validator->fails()) {
                $this->status = false;
                $this->statusCode = Response::HTTP_BAD_REQUEST;
                $this->message = _lvValidations($validator->messages()->get('*'),true);
                $responseData = array('status'=>$this->status,'message'=>$this->message);
                return response()->json($responseData,$this->statusCode);
            }else {
                $title = $requestData['title'];
                $description = $requestData['description'];
                $crud = array(
                    'title' => $title,
                    'description' => $description,
                    'created_at' => getCurrentDateTime(),
                    'created_by' => $userId,
                );
                $responseInsertedId = \DB::table('log_issues')->insertGetId($crud);
                if($responseInsertedId ){
                    $this->message =  __('api.api_log_issue_success');
                    $responseData = array('status'=>$this->status,'message'=>$this->message);
                    return response()->json($responseData,$this->statusCode);

                }else{
                    $this->status = false;
                    $this->statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
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
    /**
     *  attendant-overview
     *  return attendant overview datewise/currentdate wise
     **/

    public function attendantOverview(Request $request)
    {
        try {
            $userId = $this->user->id;
            $lotId = $this->user->lot_id;
            if($lotId ){
                $filterDate  = ($request->filter_date) ? $request->filter_date : getCurrentDate() ;
                $currentDateTime = getCurrentDateTime();//, (T.arrival_time-5) as PT
                $select = \DB::raw('IFNULL(SUM(paid_amount),0) as total_sales'); //,np.number_plate
                $dataBase = \DB::table('transactions as T')->select($select);
                $dataBase->leftJoin('slots as S', function ($leftJoin) {
                    $leftJoin->on('T.slot_id', '=', 'S.id');
                });
                $dataBase->leftJoin('lots as L', function ($leftJoin) {
                    $leftJoin->on('S.lot_id', '=', 'L.id');
                });
                /*$dataBase->leftJoin('number_plats as np', function ($leftJoin) {
                    $leftJoin->on('T.number_plate_id', '=', 'np.id');
                    ##$leftJoin->where('active', 1);
                });
                $dataBase->leftJoin('customers as c', function ($leftJoin) {
                    $leftJoin->on('T.customer_id', '=', 'c.id');
                });
                if($filterDate){
                    $from = date('Y-m-01 00:00:00',strtotime($filterDate));
                    $to = date('Y-m-d 23:59:59',strtotime($filterDate));
                    //$dataBase->where('T.created_at1',">=", [$filterDate]);
                    $dataBase->whereBetween('T.created_at', [$from, $to]);
                }*/
                if($currentDateTime){
                    $from = date('Y-m-d 00:00:00',strtotime($currentDateTime));
                    $to = date('Y-m-d 23:59:59',strtotime($currentDateTime));
                    //$dataBase->where('T.created_at1',">=", [$filterDate]);
                    $dataBase->whereBetween('T.created_at', [$from, $to]);
                }
                $responseTransactions =  $dataBase->where('L.id', $lotId)
                    ->whereIn('T.status', array(config('constants.transactions.IN-PROCESS'),config('constants.transactions.TIME-EXTENDED'),config('constants.transactions.COMPLETE'),config('constants.transactions.CLOSE')))
                    #->whereRaw('TIMESTAMPDIFF(MINUTE, T.arrival_time, ?) >= ?', [$currentDateTime,'(T.parking_time-5)'])
                    #->whereRaw('TIMESTAMPDIFF(MINUTE, T.arrival_time,  "'.$currentDateTime.'") >= (T.parking_time-5)')
                    ->get()->first();
               # dd($responseTransactions);
                $totalSales = $responseTransactions->total_sales;
                $PCT = 40 ;
                $overviewData['total_sales_amount'] = $totalSales ;
                $overviewData['commission_pct'] = $PCT ;
                $overviewData['commission_amount'] = "0.00";
                $overviewData['total_bank_amount'] = "0.00";
                if($responseTransactions->total_sales>0){
                    $amountT = (($totalSales  * $PCT ) / 100);
                    $overviewData['commission_amount'] = _number_format($amountT,2) ;
                    $overviewData['total_bank_amount'] = _number_format(($totalSales - $amountT ),2);
                }
                #$this->message =  __('api.api_online_status_update_success');
                $responseData = array('status'=>$this->status,'message'=>$this->message,'data'=>$overviewData);
                return response()->json($responseData,$this->statusCode);

            }else{
                //$this->status = false;
                $this->message =  __('api.api_lot_not_available');
                $responseData = array('status'=>$this->status,'message'=>$this->message);
                return response()->json($responseData,$this->statusCode);
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
