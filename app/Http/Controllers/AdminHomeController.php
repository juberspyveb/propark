<?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use App\User, App\Order ,App\UserGovernmentId, App\TransactionSetting;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Mail;
    use Auth;
    use Session;
    use Carbon\Carbon;

    class AdminHomeController extends Controller{
            
        protected $objOrder;

        public function __construct()
        {
            $this->objOrder = new Order();
        }

        public function dashboard(){

            return redirect()->route('admin-users-list');
            $adminRoleId= Session::get('role_id');
                
           
/*
            $orders = \DB::table('orders as od')
                            ->select('od.order_id', 'od.customer_id','od.customer_name','od.generate_code','od.total_payable','od.order_type','od.created_at', 
                                    DB::Raw("DATE_FORMAT(".'od.order_date_time'.", '%d-%m-%Y %H:%s:%i') AS order_date_time"),  
                                    DB::Raw("CASE 
                                            WHEN ".'od.order_status'." = '1' THEN 'Processing'
                                             WHEN ".'od.order_status'." = '2' THEN 'Proof Emailed'
                                            WHEN ".'od.order_status'." = '3' THEN 'In Production'
                                            WHEN ".'od.order_status'." = '4' THEN 'Quality Issue'
                                            WHEN ".'od.order_status'." = '5' THEN 'Completed'
                                            WHEN ".'od.order_status'." = '6' THEN 'Cancelled'
                                            END as 'order_status'
                                        "))
                            ->orderBy('od.created_at', 'desc')
                            ->limit('5')
                            ->get();
            
            /* REVENUE */
               /* $today = date("Y-m-d");

                $today_earning=$this->objOrder->getTodayEarning($today);

                $yester_earning= $this->objOrder->getYesterdayEaring();

                $week_earning =$this->objOrder->getWeekEaring($today);
                
                $month_earning =$this->objOrder->getMonthEaring();

                $year_earning =$this->objOrder->getYearEaring();

                $data['today_earning'] = $today_earning;
                $data['yester_earning'] = $yester_earning;
                $data['week_earning'] = $week_earning;
                $data['month_earning'] = $month_earning;
                $data['year_earning'] = $year_earning;*/
            /* REVENUE */

           /* $data = ['orders' => $orders, 'admin_role_id'=>$adminRoleId, ];
                // dd($edge_data_array);
                $data['today_earning'] = $today_earning;
                $data['yester_earning'] = $yester_earning;
                $data['week_earning'] = $week_earning;
                $data['month_earning'] = $month_earning;
                $data['year_earning'] = $year_earning;
            */
            // dd($data);
            return view("admin.views.dashboard");
        }
        // public function countAjax(Request $request)
        // {
           
        //     $fromDate = ($request->fromDatepicker !== NULL) ? $request->fromDatepicker." 00:00:00" : '';
        //     $toDate = ($request->toDatepicker !== NULL) ? $request->toDatepicker." 23:59:59" : '';

        //         if($toDate =="")
        //         {
        //             $toDate = date('Y-m-d');
        //         }

        //     $totalVoucherOrders = DB::table('voucher_orders')->whereBetween('voucher_orders.created_at',[$fromDate,$toDate])->count();

        //     $total_orders = \DB::table('orders as od')->select()->whereBetween('od.created_at',[$fromDate,$toDate])->count();
            
        //     $amount = DB::table('orders')
        //         ->select(\DB::Raw("CASE WHEN SUM(".'orders.total_payable'.") > 0 THEN SUM(".'orders.total_payable'.") ELSE '0' END as total"))
        //         ->join('payments', 'payments.order_id', '=', 'orders.order_id')
        //         ->whereBetween('orders.created_at',[$fromDate,$toDate])
        //         ->where(['payments.payment_status' => 'Completed'])
        //         ->first();
        //     $total_amount =config('constants.currency')._number_format($amount->total);

        //     $QualityIssueOrders =  DB::table('orders as od')->select('od.order_id', 'od.customer_id','od.customer_name','od.generate_code','od.total_payable','od.order_type','od.created_at', 
        //         DB::Raw("DATE_FORMAT(".'od.order_date_time'.", '%d-%m-%Y %H:%s:%i') AS order_date_time"),  
        //         DB::Raw("CASE 
        //                 WHEN ".'od.order_status'." = '1' THEN 'Processing'
        //                 WHEN ".'od.order_status'." = '2' THEN 'Proof Emailed'
        //                 WHEN ".'od.order_status'." = '3' THEN 'In Production'
        //                 WHEN ".'od.order_status'." = '4' THEN 'Quality Issue'
        //                 WHEN ".'od.order_status'." = '5' THEN 'Completed'
        //                 WHEN ".'od.order_status'." = '6' THEN 'Cancelled'
        //                 END as 'order_status'
        //             "))->whereBetween('od.created_at',[$fromDate,$toDate])->where('order_status','=','4')->orderBy('od.created_at','desc')->get();
            
        //     /* EDGE REVENUE */
                
        //         $get_edge_name = getStyleEdge();
               
        //         $edge_data_array = array();
        //         $all_edge= DB::table('order_items')
        //             ->select(\DB::Raw("CASE WHEN SUM(".'order_items.edge_amount'.") > 0 THEN SUM(".'order_items.edge_amount'.") ELSE '0' END as total"))
        //             ->join('payments', 'payments.order_id', '=', 'order_items.order_id')
        //             ->where(['payments.payment_status' => 'Completed'])
        //             ->whereBetween('order_items.created_at',[$fromDate,$toDate])
        //             ->first();
        //         array_push($edge_data_array,$all_edge);
        //         foreach ($get_edge_name as $key => $value) 
        //         {
        //             $val = $value['name'];
        //             $edge_data=DB::table('order_items')
        //                 ->select('edge_name',\DB::Raw("CASE WHEN SUM(".'order_items.edge_amount'.") > 0 THEN SUM(".'order_items.edge_amount'.") ELSE '0' END as total"))
        //                 ->join('payments', 'payments.order_id', '=', 'order_items.order_id')
        //                 ->where(['payments.payment_status' => 'Completed'])
        //                 ->whereBetween('order_items.created_at',[$fromDate,$toDate])
        //                 ->where('edge_name',"=",$val)
        //                 ->first();
        //             if($edge_data->edge_name == "")
        //             {
        //                 $edge_data->edge_name = $val;   
        //             }
                    
        //             array_push($edge_data_array,$edge_data);
        //         }
                
            
        //         $data = ['edge_data_array'=>$edge_data_array];
        //         $edgeDataHtml = view("admin.views.dashboard_data", compact('data'))->render();
        //     /* EDGE REVENUE */

        //         $data = ['QualityIssueOrders' =>$QualityIssueOrders];
               
        //         $qualityHtml = view("admin.views.dashboard_data", compact('data'))->render();
                
        //         return response()->json(['edgeDataHtml'=>$edgeDataHtml , 'qualityHtml'=>$qualityHtml , 'totalVoucherOrders'=>$totalVoucherOrders , 'total_orders'=>$total_orders , 'total_amount'=>$total_amount ]);
           
        // }

        public function profile(Request $request){
            $id = Auth::guard("admin")->user()->id;
            $image_url = url('uploads/admin').'/';

            $user = \DB::table('admins')->select('email',
                                                    \DB::Raw("CONCAT(".'first_name'.", ' ', ".'last_name'.") as name"),  
                                                    \DB::Raw("CONCAT('".$image_url."', ".'profile_image'.") as profile_image")
                                                )
                                                ->where(['id' => $id])->first();
            
            return view("admin.views.profile", ["user" => $user]);
        }

        /*public function chartData(Request $request){
            $filter = $request->filter;
            
            if($filter == 'weekly'){
                $today = date("Y-m-d"); 
                $week = date("Y-m-d", strtotime("6 days ago"));

                $data = [];
                $diffDays = $this->datesRange($week, $today, $step = '+1 day', $output_format = 'Y-m-d');
                
                for($i=0; $i < count($diffDays); $i++){
                    $queryData = \DB::select("
                                    SELECT COUNT(CASE WHEN order_type = '1' THEN 1 END) AS delivery, COUNT(CASE WHEN order_type = '2' THEN 1 END) AS collection 
                                    FROM `orders` 
                                    WHERE created_at BETWEEN '$diffDays[$i] 00:00:00' AND '$diffDays[$i] 23:59:59' 
                                ");

                    $queryData[0]->time = $diffDays[$i];
                    $data[$i] = $queryData[0];
                }         

                $total = DB::table('orders as od')->select(DB::raw("COUNT(".'od.order_id'.") as count"))->whereBetween('created_at', [$week." 00:00:00", $today." 23:59:59"])->first();
                $delivery = DB::table('orders as od')->select(DB::raw("COUNT(".'od.order_id'.") as count"))->whereBetween('created_at', [$week." 00:00:00", $today." 23:59:59"])->where(['order_type' => '1'])->first();
                $collection = DB::table('orders as od')->select(DB::raw("COUNT(".'od.order_id'.") as count"))->whereBetween('created_at', [$week." 00:00:00", $today." 23:59:59"])->where(['order_type' => '2'])->first();

                return json_encode(['data' => ['total' => $total, 'delivery' => $delivery, 'collection' => $collection, 'chartData' => (object)$data]]);

            }elseif($filter == 'monthly'){
                $year = date("Y"); 
                $month = date("m"); 
                $endMonthDate = date("Y-m-d");
                $monthStartDate = $year."-".$month.'-01';
                $monthDates = $this->monthDatesRange($month, $year, $endMonthDate);
                
                for($i=0; $i < count($monthDates); $i++){
                    $queryData = \DB::select("
                                    SELECT COUNT(CASE WHEN order_type = '1' THEN 1 END) AS delivery, COUNT(CASE WHEN order_type = '2' THEN 1 END) AS collection 
                                    FROM `orders` 
                                    WHERE created_at BETWEEN '$monthDates[$i] 00:00:00' AND '$monthDates[$i] 23:59:59' 
                                ");

                    $queryData[0]->time = $monthDates[$i];
                    $data[$i] = $queryData[0];
                }         

                $total = DB::table('orders as od')->select(DB::raw("COUNT(".'od.order_id'.") as count"))->whereBetween('created_at', [$monthStartDate." 00:00:00", $endMonthDate." 23:59:59"])->first();
                $delivery = DB::table('orders as od')->select(DB::raw("COUNT(".'od.order_id'.") as count"))->whereBetween('created_at', [$monthStartDate." 00:00:00", $endMonthDate." 23:59:59"])->where(['order_type' => '1'])->first();
                $collection = DB::table('orders as od')->select(DB::raw("COUNT(".'od.order_id'.") as count"))->whereBetween('created_at', [$monthStartDate." 00:00:00", $endMonthDate." 23:59:59"])->where(['order_type' => '2'])->first();

                return json_encode(['data' => ['total' => $total, 'delivery' => $delivery, 'collection' => $collection, 'chartData' => (object)$data]]);
            }elseif($filter == 'yearly'){
                $year = date("Y");

                $start_date = $year.'-01-01';
                $end_date = date("Y-m-d");

                $data = \DB::select("
                                    SELECT COUNT(CASE WHEN order_type = '1' THEN 1 END) AS delivery, COUNT(CASE WHEN order_type = '2' THEN 1 END) AS collection 
                                    FROM `orders` 
                                    WHERE created_at BETWEEN '$start_date 00:00:00' AND '$end_date 23:59:59' 
                                ");
                $data[0]->time = $year;
                
                $total = DB::table('orders as od')->select(DB::raw("COUNT(".'od.order_id'.") as count"))->whereBetween('created_at', [$start_date." 00:00:00", $end_date." 23:59:59"])->first();
                $delivery = DB::table('orders as od')->select(DB::raw("COUNT(".'od.order_id'.") as count"))->whereBetween('created_at', [$start_date." 00:00:00", $end_date." 23:59:59"])->where(['order_type' => '1'])->first();
                $collection = DB::table('orders as od')->select(DB::raw("COUNT(".'od.order_id'.") as count"))->whereBetween('created_at', [$start_date." 00:00:00", $end_date." 23:59:59"])->where(['order_type' => '2'])->first();
                
                return json_encode(['data' => ['total' => $total, 'delivery' => $delivery, 'collection' => $collection, 'chartData' => (object)$data]]);
            }else{
                $today = date("Y-m-d"); 
                $data = [];
                $hours = $this->hoursRange();

                for($i=0; $i < count($hours)-1; $i++){
                    $start = $hours[$i];
                    
                    if($i == 23){
                        $end = '23:59:59';
                    }else{
                        $end = $hours[$i+1];
                    }
                    \DB::enableQueryLog();
                    $queryData = \DB::select("
                                        SELECT COUNT(CASE WHEN order_type = '1' THEN 1 END) AS delivery, COUNT(CASE WHEN order_type = '2' THEN 1 END) AS collection 
                                        FROM `orders` 
                                        WHERE created_at BETWEEN '$today $start' AND '$today $end' 
                                    ");

                    $queryData[0]->time = $end;
                    $data[$i] = $queryData[0];
                }

                $total = DB::table('orders as od')->select(DB::raw("COUNT(".'od.order_id'.") as count"))->whereRaw('Date(created_at) = CURDATE()')->first();
                $delivery = DB::table('orders as od')->select(DB::raw("COUNT(".'od.order_id'.") as count"))->whereRaw('Date(created_at) = CURDATE()')->where(['order_type' => '1'])->first();
                $collection = DB::table('orders as od')->select(DB::raw("COUNT(".'od.order_id'.") as count"))->whereRaw('Date(created_at) = CURDATE()')->where(['order_type' => '2'])->first();

                return json_encode(['data' => ['total' => $total, 'delivery' => $delivery, 'collection' => $collection, 'chartData' => (object)$data]]);
            }
        }*/

        /*public function weekly_data(Request $request){
            $date = $request->date;
            $_24_hours = _24hours();

            $data = [];

            $count = count($_24_hours);
            $dailyData = [];

            $i = 0;

            foreach($_24_hours as $hRow){
                $i++;

                if($i > 24){
                    continue;
                }

                $delivery = DB::table('orders as od')->select(\DB::Raw("CASE WHEN ".'od.grand_total'." THEN ".'od.grand_total'." ELSE 0 END as total"))->whereBetween('od.created_at', [$date." ".$hRow, $date." ".$_24_hours[$i]])->where(['order_type' => '1'])->first();
                if($delivery){
                    $delivery = $delivery->total;
                }else{
                    $delivery = 0;
                }
                
                $collection = DB::table('orders as od')->select(\DB::Raw("CASE WHEN ".'od.grand_total'." THEN ".'od.grand_total'." ELSE 0 END as total"))->whereBetween('od.created_at', [$date." ".$hRow, $date." ".$_24_hours[$i]])->where(['order_type' => '2'])->first();
                if($collection){
                    $collection = $collection->total;
                }else{
                    $collection = 0;
                }

                $total = $collection + $delivery;

                $dailyData[$hRow] = ['start' => $hRow, 'end' => $_24_hours[$i], 'total' => $total, 'delivery' => $delivery, 'collection' => $collection];
                
            }
            
            return response()->json($dailyData);
        }*/
    }
