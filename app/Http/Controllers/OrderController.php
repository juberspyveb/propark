<?php

    namespace App\Http\Controllers;
    
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Mail;
    use Maatwebsite\Excel\Facades\Excel;
    use App\Exports\AdminOrdersExport;
    use File, URL;
    use App\User;
    use App\Order;
    use Datatables;
    use Session;
    use PDF;

    class OrderController extends Controller{
        
        protected $objOrder;

        public function __construct()
        {
            $this->objOrder = new Order();
        }
        /** order listing page */
        public function list(){
             $adminRoleId= Session::get('role_id');
            return view("admin.views.orders.list")->with(['admin_role_id'=>$adminRoleId]);
        }

        /** order listing fetch */
        public function list_fetch(Request $request){
            $delivery_type=$request->delivery_type;
            $query = \DB::table('orders as od')
                        ->select('od.order_id','od.total_payable','od.is_urgent_order','od.customer_id','od.customer_name','od.is_delivery_bespoke', DB::Raw("DATE_FORMAT(".'od.order_date_time'.", '%d-%m-%Y %H:%s:%i') AS order_date_time"), 'od.generate_code', 'od.order_type', 'od.order_status', 'p.txn_id', 'p.payment_method', 'p.payment_status')->where("od.order_type",'!=','VOUCHER')->leftjoin('payments as p', 'p.order_id', 'od.order_id');
            if($delivery_type != '')
            {
               
                $orders = $query->where("od.is_urgent_order",$delivery_type)->orderBy('od.created_at', 'desc')->get();
            }
            else
            {
                $orders = $query->orderBy('od.created_at', 'desc')->get();
            }

            return Datatables::of($orders)
                ->addIndexColumn()
                ->editColumn("action", function($orders) {
                    $email = '';
                    if($orders->order_status == config('constants.order_status.PROCESSING') || $orders->order_status == config('constants.order_status.REVIEW_PROOF'))
                    {
                        $email = '<a data-id="'.$orders->order_id.'" class="upload_pdf text-primary" data-target="#pdf-modal" data-toggle="modal" href="#"><i class="far fa-envelope"></i></a>';
                    }
                    $html = '<a href="'.route('admin-orders-view',['id' => base64_encode($orders->order_id)]).'"><i class="fas fa-eye text-success"></i></a>'.
                        $email.
                        '<div class="dropdown" style="display: inline;">'.
                            '<a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="fas fa-bars text-secondary"></i>'.
                            '</a>'.
                            '<ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: top, left; top: 20px; left: -142px;">'.
                                '<li class="ms-dropdown-list">'.
                                    '<a class="dropdown-item badge-gradient-danger" href="javascript:void( 0);" onclick="change_status(this);" data-status="1" data-id="'.$orders->order_id.'">Processing</a>'.
                                    '<a class="dropdown-item badge-gradient" style="background-color: blue;" href="javascript:void( 0);" onclick="change_status(this);" data-status="2" data-id="'.$orders->order_id.'">Proof Emailed</a>'.
                                    '<a class="dropdown-item badge-gradient" style="background-color: #07be6e;" href="javascript:void(0);" onclick="change_status(this);" data-status="8" data-id="'.$orders->order_id.'">Approved</a>'.
                                    '<a class="dropdown-item badge-gradient" style="background-color: pink;" href="javascript:void( 0);" onclick="change_status(this);" data-status="7" data-id="'.$orders->order_id.'">Review Proof</a>'.                                    
                                    '<a class="dropdown-item badge-gradient" style="background-color: orange;" href="javascript:void(0);" onclick="change_status(this);" data-status="3" data-id="'.$orders->order_id.'">In Production</a>'.
                                    '<a class="dropdown-item badge-gradient" style="background-color: black;" href="javascript:void(0);" onclick="change_status(this);" data-status="4" data-id="'.$orders->order_id.'">Quality Issue</a>'.
                                    '<a class="dropdown-item badge-gradient-success" href="javascript:void(0);" onclick="change_status(this);" data-status="5" data-id="'.$orders->order_id.'">Completed</a>'.
                                    '<a class="dropdown-item badge-gradient" style="background-color: #9F10C6;" href="javascript:void(0);" onclick="change_status(this);" data-status="6" data-id="'.$orders->order_id.'">Cancelled</a>'.
                                '</li>'.
                            '</ul>'.
                        '</div>'.
                    '';
                    

                    return $html;

                })
                ->editColumn("generate_code",function($orders){
                    return '<a href="'.route('admin-orders-view',['id' => base64_encode($orders->order_id)]).'">'.'#'.$orders->generate_code.'</a>';
                })
                ->editColumn("customer_name",function($orders){
                    if($orders->customer_name == "")
                    {
                        return "-";
                    }
                    else
                    {
                        return _ucWords($orders->customer_name);
                    }
                })
                ->editColumn('is_urgent_order',function($orders){
                    $type  = 'Normal Delivery';
                    if(!empty($orders->is_delivery_bespoke)){
                        #$type = shippingOptionsForBespoke($orders->is_delivery_bespoke,'name');
                        $type = $type  . "<br>[".shippingOptionsForBespoke($orders->is_delivery_bespoke,'name')."]";
                    }

                    if($orders->is_urgent_order == "1")
                    {
                        $type = '';
                        if(!empty($orders->is_delivery_bespoke)){
                            $type = "<br> [".shippingOptionsForBespoke($orders->is_delivery_bespoke,'name')."]";
                        }
                        return "Urgent Delivery ".$type ;
                    }
                    else
                    {
                        #return "Normal Delivery" ;
                        return $type ;
                    }
                })
                ->editColumn("order_status", function($orders) {
                    if($orders->order_status == config('constants.order_status.PROCESSING')){
                        return '<span class="badge badge-gradient-danger">Processing</span>';
                    }elseif($orders->order_status == config('constants.order_status.PROOF_EMAILED')){
                        return '<span class="badge badge-gradient" style="background-color: blue;">Proof Emailed</span>';
                    }elseif($orders->order_status == config('constants.order_status.APPROVED')){
                        return '<span class="badge badge-gradient"  style="background-color: #07be6e;">Approved</span>';
                    }elseif($orders->order_status == config('constants.order_status.IN_PRODUCATION')){
                        return '<span class="badge badge-gradient" style="background-color: orange;">In Production</span>';
                    }elseif($orders->order_status == config('constants.order_status.QUALITY_ISSUE')){
                        return '<span class="badge badge-gradient" style="background-color: black;">Quality Issue</span>';
                    }
                    elseif($orders->order_status == config('constants.order_status.COMPLETED')){
                        return '<span class="badge badge-gradient-success">Completed</span>';
                    }elseif($orders->order_status == config('constants.order_status.CANCELLED')){
                        return '<span class="badge badge-gradient"  style="background-color: #9F10C6;">Cancelled</span>';
                    }elseif($orders->order_status == config('constants.order_status.REVIEW_PROOF')){
                        return '<span class="badge badge-gradient"  style="background-color: pink;">Review Proof</span>';
                    }else{
                        return '-';
                    }
                })
                ->editColumn("total_payable",function($orders){
                    return config('constants.currency').$orders->total_payable;
                })
                ->editColumn("payment_method",function($orders){
                    $paymentStatus = '';
                    if($orders->payment_method == 'Manual' && $orders->payment_status != 'Completed'){
                        $paymentStatus = '<span class="badge badge-gradient-danger">'.$orders->payment_status.'</span>';
                    }
                    return $orders->payment_method . '</br>'.$paymentStatus ;
                })
                ->rawColumns(["generate_code","action", "order_status","total_payable","customer_name","is_urgent_order","payment_method"])
                ->make(true);
        }

        /** order view */
        public function view(Request $request, $id){
            $id = base64_decode($id);
            $adminRoleId= Session::get('role_id');
            // dd($adminRoleId);
            // $array=;
            // dd($data);
            
            /**
             * Note :   if you do changes any king in order view blade please do copy same in mail blade file
             *          and also same do query for send_invoice function
             */
            $order = \DB::table('orders as od')
                            ->select('od.order_id as id', 'od.customer_id','od.customer_phone','od.customer_email','od.customer_postcode','od.billing_address', 'od.shipping_address', 'od.generate_code','od.order_status','od.shipping_charge','od.shipping_charge_vat','od.total_payable','od.discount_amount','od.discount_vat','od.discount_code','od.discount_code_id','od.is_urgent_order','od.is_urgent_order_amount','od.is_urgent_order_vat','od.customer_name','od.order_type','od.payable_amount','od.payable_amount','od.payable_vat','od.subtotal_vat',
                                 'p.txn_id', 'p.payment_method', 'p.payment_status', 'od.is_delivery_bespoke',
                                    \DB::Raw("DATE_FORMAT(".'od.order_date_time'.", '%d-%m-%Y %H:%s:%i') as order_date_time"),  
                                    \DB::Raw("od.order_status as order_status_original"),  
                                    \DB::Raw("CASE 
                                                WHEN ".'od.order_status'." = '1' THEN 'Processing' 
                                                WHEN ".'od.order_status'." = '2' THEN 'Proof Emailed'
                                                WHEN ".'od.order_status'." = '3' THEN 'In Production'
                                                WHEN ".'od.order_status'." = '4' THEN 'Quality Issue' 
                                                WHEN ".'od.order_status'." = '5' THEN 'Completed' 
                                                WHEN ".'od.order_status'." = '6' THEN 'Cancelled' 
                                            END as order_status"),'u.email as user_email')
                            ->leftjoin('users as u', 'u.id', 'od.customer_id')
                            ->leftjoin('payments as p', 'p.order_id', 'od.order_id')
                            ->where(['od.order_id' => $id])
                            ->first();
                // dd($order);
                $order_items = DB::table('order_items') 
                            ->select('order_items.quantity',
                                'order_items.is_product','order_items.recipient_first_name','order_items.recipient_last_name','order_items.recipient_email_address',
                                'order_items.total_price','order_items.voucher_from','order_items.personal_message','order_items.when_to_email_voucher',
                                'order_items.total_item_price','order_items.product_name','order_items.when_to_email_voucher_type','order_items.unit_price','order_items.unit_vat','order_items.unit_price','order_items.size_name','order_items.edge_name','order_items.font_name','order_items.is_product','order_items.color_name','order_items.fixing_type','order_items.line1','order_items.line2','order_items.line3','order_items.preview_data')
                            ->where(['order_items.order_id' => $id])
                            ->get();
                $voucher = DB::table('voucher_orders') 
                            ->select('voucher_orders.product_name','voucher_orders.product_price','voucher_orders.recipient_first_name','voucher_orders.quantity','voucher_orders.recipient_last_name','voucher_orders.recipient_email_address','voucher_orders.voucher_from','voucher_orders.when_to_email_voucher','voucher_orders.when_to_email_voucher_type','voucher_orders.personal_message')
                            ->where(['voucher_orders.order_id' => $id])
                            ->first();
                // dd($voucher);
                // dd($order);
                // if(is_array($order->preview_data)) // without login
                // {
                //     $extraArray = $order->preview_data;
                // }
                // else
                // {
                //     $extraArray = json_decode($order->preview_data, true);
                // }
               
                //   $order->preview_data = $extraArray;   
                        
                        // $order->cartItem = $cartItem;
                    
                    // dd($order);
                   // dd($value);
                   // dd(implode($myArray,','));
                                    
            // if(isset($order) && !empty($order)){

            //     $order_item = \DB::table('order_items')
            //                         ->where('order_id',$order->id)
            //                         ->get();
            //     // dd($order_item);
            //     if(isset($order_item) && !empty($order_item)){
            //         $ingrediants = [];
            //         foreach($order_item as $r){
            //             if(isset($r->product_ingrediants_quantity) && !empty($r->product_ingrediants_quantity)){
            //                 $ingAry = json_decode($r->product_ingrediants_quantity);
                            
            //                 if(isset($ingAry) && !empty($ingAry)){
            //                     $i = 0;
            //                     foreach($ingAry as $ing){
            //                         $ingData = \DB::table('ingredients_addons as ia')->select('ia.name', 'ia.price')->where(['ia.id' => $ing->id])->first();
            //                         if(!empty($ingData) && isset($ingData)){
            //                             $r->ingrediants[$i] = ['name' => $ingData->name, 'price' => $ingData->price, 'quantity' => $ing->quantity];
            //                         }
            //                         $i++;
            //                     }
            //                 }
            //             }
            //         }
            //         $order->order_item = $order_item;
            //     }
            // }
            // dd($order);
            if(!empty($order))
            {
                return view("admin.views.orders.view", ['data'=>$order , 'order'=>$order_items , 'voucher'=>$voucher, 'adminRoleId'=>$adminRoleId]);
            }
            else
            {
                $adminRoleId= Session::get('role_id');
                // return view("admin.views.orders.list";
                 return redirect(route('admin-orders-list'))->with(['admin_role_id'=>$adminRoleId]);
            }

        }

        /** order chagne status */
        public function change_status(Request $request){
            // dd($request->all());
            if(!$request->ajax()){
                exit('No direct script access allowed');
            }

            if(!empty($request->all())){
                
                $id = $request->id;
                if($request->status =="Processing")
                {
                    $status = "1";
                }
                 elseif($request->status =="Proof Emailed")
                {
                    $status = "2";
                }
                elseif($request->status =="In Production")
                {
                    $status = "3";
                }
                elseif($request->status =="Quality Issue")
                {
                    $status = "4";
                } elseif($request->status =="Completed")
                {
                    $status = "5";
                } elseif($request->status =="Cancelled")
                {
                    $status = "6";
                }
                else
                {
                    $status = $request->status;
                }
                     $crud_data = array(
                        'order_status' => $status,
                        'change_status_update_at' => date("Y-m-d H:i:s")
                        );
                // dd($crud_data);
                $order= DB::table('orders')->where('order_id',"=",$id)->first();
                $mailItems = array(
                    'order_id' =>$id,
                    'code' => $order->generate_code,
                    'order_status' =>$status,
                    'customer_name' => $order->customer_name,
                    'customer_email'=> $order->customer_email,
                );
           
                $update_result = \DB::table('orders')->where('order_id', $id)->update($crud_data);

                if($update_result){
                    if($status !="6")
                    {
                        _sendAdminChangeStatusEmail($mailItems);
                    }
                    echo json_encode(array("status" => "success"));
                    exit;
                }else{
                    echo json_encode(array("status" => "failed"));
                    exit;
                }
            }else{
                echo json_encode(array("status" => "failed"));
                exit;
            }
        }

        /** send order invoice over mail */
        public function send_invoice(Request $request, $id, $customer_id){

            /**
             * Note : if you do changes any king in order view blade please do copy same in mail blade file
             */

            $id = base64_decode($id); 
            $customer_id = base64_decode($customer_id);
            
            $order = \DB::table('orders as od')
                            ->select('od.order_id as id', 'od.customer_id', 'od.billing_address', 'od.shipping_address', 'od.generate_code', 'od.coupon_id', 'od.payment_method', 'od.order_status as order_status_code', 'od.order_type as order_type_code', 
                                    \DB::Raw("DATE_FORMAT(".'od.order_date_time'.", '%d-%m-%Y %H-%s-%i') as order_date_time"), 
                                    \DB::Raw("DATE_FORMAT(".'od.payment_date_time'.", '%d-%m-%Y %H-%s-%i') as 'payment_date_time'"),  
                                    \DB::Raw("CASE
                                                WHEN ".'od.order_type'." = '1' THEN 'Delivery'
                                                WHEN ".'od.order_type'." = '2' THEN 'Collaction'   
                                            END as order_type"), 
                                    \DB::Raw("CASE 
                                                WHEN ".'od.order_status'." = '1' THEN 'Processing' 
                                                WHEN ".'od.order_status'." = '2' THEN 'In Production'
                                                WHEN ".'od.order_status'." = '3' THEN 'Completed' 
                                                 
                                            END as order_status"), 'od.delivery_charge', 'od.service_tax', 'od.total_order_sub_amount', 'od.total_order_amount', 'od.stripe_token', 'od.stripe_fee',  
                                    \DB::Raw("CONCAT(".'u.first_name'.", ' ', ".'u.last_name'.") as user_full_name"), 'u.email as user_email','cp.amount as coupon_amount', 'cp.description as coupon_description'
                                    )
                            ->leftjoin('users as u', 'u.id', 'od.customer_id')
                            ->leftjoin('coupon as cp', 'cp.id', 'od.coupon_id')
                            ->where(['od.order_id' => $id])
                            ->first();
                            
            if(isset($order) && !empty($order)){

                $order_item = \DB::table('order_items as oi')
                                    ->select('oi.product_id', 'oi.quantity', 'oi.total_prize', 'oi.product_ingrediants_quantity', 'oi.product_prize', 'oi.quantity', 'pd.product_name')
                                    ->leftjoin('product as pd', 'pd.id', 'oi.product_id')
                                    ->where(['oi.order_id' => $order->id])
                                    ->get();

                if(isset($order_item) && !empty($order_item)){
                    $ingrediants = [];
                    foreach($order_item as $r){
                        if(isset($r->product_ingrediants_quantity) && !empty($r->product_ingrediants_quantity)){
                            $ingAry = json_decode($r->product_ingrediants_quantity);
                            
                            if(isset($ingAry) && !empty($ingAry)){
                                $i = 0;
                                foreach($ingAry as $ing){
                                    $ingData = \DB::table('ingredients_addons as ia')->select('ia.name', 'ia.price')->where(['ia.id' => $ing->id])->first();
                                    if(!empty($ingData) && isset($ingData)){
                                        $r->ingrediants[$i] = ['name' => $ingData->name, 'price' => $ingData->price, 'quantity' => $ing->quantity];
                                    }
                                    $i++;
                                }
                            }
                        }
                    }
                    $order->order_item = $order_item;
                }
            }

            $pdf = PDF::loadView('admin.views.orders.mail', ['data' => $order]);

            $logo = url('uploads/logo');
            $logo_data = \DB::table('setting')->select(\DB::Raw("CONCAT("."'$logo'".", '/', ".'value'.") as logo"))->where(['keys' => 'header_logo'])->first();
            $logo_url = $logo_data->logo;
            
            $user = \DB::table('users')->select(\DB::Raw("CONCAT(".'first_name'.", ' ', ".'last_name'.") as user_full_name"), 'email')->where(['id' => $customer_id])->first();

            $mail_content_data = (object) [];
            $mail_content_data->var_greeting_name = $user->user_full_name;
            $mail_content_data->var_order_number = '#'.$order->generate_code;

            $mail_data = $this->email_template('AM_ORDER_DETAIL', $mail_content_data);
            $mail_data->body = $this->header_footer($logo_url, $mail_data->email_subject, $mail_data->email_html, $mail_data->tags);
             $mail_data->to = $user->email;
            //$mail_data->to = 'hardik.patel.backendbrains@gmail.com';
            $mail_data->from = auth()->guard("admin")->user()->email;
            $mail_data->from_name = auth()->guard("admin")->user()->first_name.' '.auth()->guard("admin")->user()->last_name;
            $mail_data->subject = $mail_data->email_subject;
            
            if(is_object($mail_data)){
                $email_data = (array)$mail_data;
            }

            try{

                Mail::send([], $email_data, function($message) use($email_data, $pdf) {
                    $message->to($email_data['to'])->subject($email_data['subject']);
                    $message->from($email_data['from'], $email_data['from_name']);
                    $message->setBody($email_data['body'], 'text/html');
                    $message->attachData($pdf->output(), "invoice.pdf");
                });

                return redirect()->back()->with('success', 'Order detail mail sent successfully.');
            }catch(\Throwable $th) {
                return redirect()->back()->with('error', 'Something went wrong.');

            }
        }
        public function orders_export(Request $request)
        {
            // dd($request->all());
            $array= array();
            $delivery_type=$request->delivery_type; 
            $checked_ids_arr = $request->checked_ids_arr;          
           /* if(!empty($checked_ids_arr)  && $delivery_type !="" )
            {
                        $orders = DB::table('orders as od')
                                    ->select('od.order_id','od.customer_name','od.customer_email','od.address_line1','od.address_line2','od.city','od.customer_postcode','order_items.size_type','od.created_at')
                                    ->leftjoin('order_items','order_items.order_id','od.order_id')
                                    ->wherein('od.order_id',$checked_ids_arr)
                                    ->where("od.order_type",'!=','VOUCHER')
                                    ->where('od.is_urgent_order',$delivery_type)
                                    ->orderBy('od.created_at','desc')->get();
                      
            }
            elseif($checked_ids_arr=="" && $delivery_type !="")
            {
                 $orders = DB::table('orders as od')
                                    ->select('od.order_id','od.customer_name','od.customer_email','od.address_line1','od.address_line2','od.city','od.customer_postcode','order_items.size_type','od.created_at')
                                    ->leftjoin('order_items','order_items.order_id','od.order_id')
                                    ->where('od.is_urgent_order',$delivery_type)
                                    ->where("od.order_type",'!=','VOUCHER')
                                    ->orderBy('od.created_at','desc')->get();
               
            }
            elseif($checked_ids_arr!="" && $delivery_type == "")
            {
                        $orders = DB::table('orders as od')
                                    ->select('od.order_id','od.customer_name','od.customer_email','od.address_line1','od.address_line2','od.city','od.customer_postcode','order_items.size_type','od.created_at')
                                    ->leftjoin('order_items','order_items.order_id','od.order_id')
                                    ->wherein('od.order_id',$checked_ids_arr)
                                    ->where("od.order_type",'!=','VOUCHER')
                                    ->orderBy('od.created_at','desc')->get();

            }
            elseif($checked_ids_arr =="" && $delivery_type =="" )
            {
                $orders = DB::table('orders as od')
                                    ->select('od.order_id','od.customer_name','od.customer_email','od.address_line1','od.address_line2','od.city','od.customer_postcode','order_items.size_type','od.created_at')
                                    ->leftjoin('order_items','order_items.order_id','od.order_id')
                                    ->where("od.order_type",'!=','VOUCHER')
                                    ->orderBy('od.created_at','desc')->get();
            }*/
                /*if($delivery_type !="")
                {
                    $orders = DB::table('orders as od')
                                        ->select('od.order_id','od.customer_name','od.customer_email','od.address_line1','od.address_line2','od.city','od.customer_postcode','od.customer_phone',DB::raw("GROUP_CONCAT(order_items.size_type SEPARATOR ',') as `size_type`"),'od.created_at','od.order_status','od.generate_code')
                                        ->leftjoin('order_items','order_items.order_id','od.order_id')
                                        ->where("od.order_type",'!=','VOUCHER')
                                        ->where('od.is_urgent_order',$delivery_type)
                                        ->whereBetween('od.change_status_update_at', [date("Y-m-d").' 00:00:00', date("Y-m-d").' 23:59:59'])
                                        ->where("od.order_status","5")
                                        ->groupBy('od.order_id')
                                        ->orderBy('od.created_at','desc')->get();
                }
                else
                {
                     $orders = DB::table('orders as od')
                                        ->select('od.order_id','od.customer_name','od.customer_email','od.address_line1','od.address_line2','od.city','od.customer_postcode','od.customer_phone',DB::raw("GROUP_CONCAT(order_items.size_type SEPARATOR ',') as `size_type`"),'od.created_at','od.order_status','od.generate_code')
                                        ->leftjoin('order_items','order_items.order_id','od.order_id')
                                        ->where("od.order_type",'!=','VOUCHER')
                                        ->whereBetween('od.change_status_update_at', [date("Y-m-d").' 00:00:00', date("Y-m-d").' 23:59:59'])
                                        ->where("od.order_status","5")
                                        ->groupBy('od.order_id')
                                        ->orderBy('od.created_at','desc')->get();
                }
                // dd($orders);
                foreach($orders as $key=>$row)
                {
                        // dd($value);
                       if($row->size_type !="")
                       {
                            $size_type = explode(",",$row->size_type);
                            
                            // echo "<pre>";
                            // print_r($size_type);
                            $count_weight=0;
                            foreach($size_type as $key=>$value)
                            {
                                if(count($size_type) == 1)
                                {
                                    if($value !="")
                                    {
                                        if($value == "style1" || $value == "style2" || $value == "style3")
                                        {
                                            $count_weight = 1; //1-2kg
                                        }
                                        elseif($value == "style4" || $value == "style5" || $value == "style6" || $value == "style7")
                                        {
                                            $count_weight = 2; //2-5 kg
                                        }
                                        elseif($value == "style8" || $value == "style9" || $value == "style10" || $value == "Style9")
                                        {
                                            $count_weight = 5; //5-10kg
                                        }
                                        else
                                        {
                                            $count_weight = 0;
                                        }
                                    }
                                    else
                                    {
                                        $count_weight = 0;
                                    }
                                }
                                else
                                {
                                    
                                   if($value == "style1" || $value == "style2" || $value == "style3")
                                        {
                                            $count_weight += 1; //1-2kg
                                        }
                                        elseif($value == "style4" || $value == "style5" || $value == "style6" || $value == "style7")
                                        {
                                            $count_weight += 2; //2-5 kg
                                        }
                                        elseif($value == "style8" || $value == "style9" || $value == "style10" || $value == "Style9")
                                        {
                                            $count_weight += 5; //5-10kg
                                        }
                                        elseif($value =="")
                                        {
                                            $count_weight += 1;
                                        }
                                        else
                                        {
                                            $count_weight = 0;
                                        }
                                }
                                
                               
                            }

                            if(count($size_type) == 1)
                            {
                               if($count_weight == 1 )
                                {
                                    $weight = 1; //1-2kg
                                }
                                elseif($count_weight == 2 )
                                {
                                    $weight = 2; //2-5 kg
                                }
                                elseif($count_weight == 5)
                                {
                                    $weight = 5;  //5-10kg
                                }
                                elseif($count_weight ==10)
                                {
                                    $weight = 10;  //10-15kg
                                }
                                else
                                {
                                    $weight = 0;
                                }
                            }
                            else
                            {
                                if($count_weight == 1)
                                {
                                    $weight = 1; //1-2kg
                                }
                                elseif($count_weight == 2 || $count_weight == 3 || $count_weight == 4 )
                                {
                                    $weight = 2; //2-5 kg
                                }
                                elseif($count_weight == 5 || $count_weight == 6 || $count_weight == 7 || $count_weight == 8 || $count_weight == 9 )
                                {
                                    $weight = 5;  //5-10kg
                                }
                                elseif($count_weight >= 10)
                                {
                                    $weight = 10;  //10-15kg
                                }
                                else
                                {
                                    $weight = 0;
                                }
                            }
                            
                            #$orders[$key]->weight = $weight;
                            $row->weight = $weight;
                             if($row->order_id == 801){
                               # _pre($size_type,0);
                                #_pre($row,0);
                                #dd($count_weight);
                            }
                       }
                       else
                       {
                            #$orders[$key]->weight = 0;
                            $row->weight = 0;
                       }

                }*/
            if($delivery_type !="")
            {
                $orders = DB::table('orders as od')
                                    ->select('od.order_id','od.customer_name','od.customer_email','od.address_line1','od.address_line2','od.city','od.customer_postcode','od.customer_phone','od.created_at','od.order_status','od.generate_code')
                                    ->where("od.order_type",'!=','VOUCHER')
                                    ->where('od.is_urgent_order',$delivery_type)
                                    ->whereBetween('od.change_status_update_at', [date("Y-m-d").' 00:00:00', date("Y-m-d").' 23:59:59'])
                                    ->where("od.order_status","5")
                                    ->orderBy('od.created_at','desc')
                                    ->get();
            }
            else
            {
                $orders = DB::table('orders as od')
                                    ->select('od.order_id','od.customer_name','od.customer_email','od.address_line1','od.address_line2','od.city','od.customer_postcode','od.customer_phone','od.created_at','od.order_status','od.generate_code')
                                    ->where("od.order_type",'!=','VOUCHER')
                                    ->whereBetween('od.change_status_update_at', [date("Y-m-d").' 00:00:00', date("Y-m-d").' 23:59:59'])
                                    ->where("od.order_status","5")
                                    ->orderBy('od.created_at','desc')
                                    ->get();
            }
            foreach($orders as $key=>$value)
            {
                $count_weight=0;
                $orderItems = DB::table('order_items')->select('order_id','is_product','quantity','size_type')->where('order_id',$value->order_id)->get();
                foreach($orderItems as $keys =>$values)
                {
                    if($values->is_product == 3)
                    {
                        $count_weight += 1;
                    }
                    elseif($values->is_product == 1)
                    {
                        $sizeType = $values->size_type;
                        if($sizeType == "style1" || $sizeType == "style2" || $sizeType == "style3")
                        {
                            $count_weight += 1; //1-2kg
                        }
                        elseif($sizeType == "style4" || $sizeType == "style5" || $sizeType == "style6" || $sizeType == "style7")
                        {
                            $count_weight += 2; //2-5 kg
                        }
                        elseif($sizeType == "style8" || $sizeType == "style9" || $sizeType == "style10" || $sizeType == "Style9")
                        {
                            $count_weight += 5; //5-10kg
                        }
                        else
                        {
                            $count_weight += 0;
                        }
                    }
                }
                if($count_weight == 1)
                {
                    $weight = 1; //1-2kg
                }
                elseif($count_weight == 2 || $count_weight == 3 || $count_weight == 4 )
                {
                    $weight = 2; //2-5 kg
                }
                elseif($count_weight == 5 || $count_weight == 6 || $count_weight == 7 || $count_weight == 8 || $count_weight == 9 )
                {
                    $weight = 5;  //5-10kg
                }
                elseif($count_weight >= 10)
                {
                    $weight = 10;  //10-15kg
                }
                else
                {
                    $weight = 0;
                }
                $value->weight = $weight;
            }
            // dd($orders);
            return Excel::download(new AdminOrdersExport($orders), 'OrdersReport.CSV');
        }

        public function order_pdf_upload_fetch_data(Request $request){
            if(!$request->ajax()){
                exit('No direct script access allowed');
            }

            if(!empty($request->all())){
               
                $orderData =  $this->objOrder->getDataByid($request->id);
                if(isset($orderData) && !empty($orderData))
                {
                   
                    $html = view('admin.views.orders.__subView', compact('orderData'))->render();

                    echo json_encode(array("status" => "success", 'html' => $html));
                    exit;
                }else
                {
                    echo json_encode(array("status" => "failed"));
                    exit;
                }
            }else{
                echo json_encode(array("status" => "failed"));
                exit;
            }
        }


        public function order_pdf_store_send_mail(Request $request){
           

            if(!empty($request->all())){

                $fromPage = $request->fromPage;
                $orderData =  $this->objOrder->getDataByid($request->order_id);
                \DB::beginTransaction();
                if ($request->hasFile('file') && $request->file('file')) 
                {
                    $file = $request->file('file');
                    $filenameWithExtension = $request->file('file')->getClientOriginalName();
                    $filename = pathinfo($filenameWithExtension, PATHINFO_FILENAME);
                    $extension = $request->file('file')->getClientOriginalExtension();
                    $filenameToStore = time()."_".$filename.'.'.$extension;

                    $folder_to_upload = public_path().'/uploads/orders/pdf/';

                    if (!File::exists($folder_to_upload)) {
                        File::makeDirectory($folder_to_upload, 0777, true, true);
                    }
    
                    $file->move("uploads/orders/pdf/", $filenameToStore);

                    $crud_data = array(
                        'pdf' => $filenameToStore,
                        'order_status' => config('constants.order_status.PROOF_EMAILED'),
                    );
    
                    $update = $this->objOrder->updateData($crud_data, $request->order_id);
                    
                    $mailItems = array(
                        'order_id' =>$orderData->order_id,
                        'code' => $orderData->generate_code,
                        'order_status' => config('constants.order_status.PROOF_EMAILED'),
                        'customer_name' => $orderData->customer_name,
                        'customer_email'=> $orderData->customer_email,
                        'decline_reason' => $orderData->decline_reason,
                        'url' => url('proof-verification/'.base64_encode( $orderData->generate_code).'/'.base64_encode($orderData->customer_id)),
                        'decline_url' => url('decline-reason/'.base64_encode( $orderData->generate_code).'/'.base64_encode($orderData->customer_id)),
                        'path' => url('/uploads/orders/pdf/'.$filenameToStore),
                    );
                    $attachmentFiles = array(public_path('/uploads/orders/pdf/').$filenameToStore);
                    $mailItems['attachmentFiles'] = $attachmentFiles;
                    if($update)
                    {
                        DB::commit();
                        _sendAdminChangeStatusEmailProofed($mailItems);
                        if($fromPage && $fromPage=="viewPage"){
                            return redirect()->route('admin-orders-view',['id' => base64_encode($orderData->order_id)])->with('success', __('message_lang.EMAIL_SEND_SUCCESSFULLY'));
                        }else{
                            return redirect()->route('admin-orders-list')->with('success', __('message_lang.EMAIL_SEND_SUCCESSFULLY'));
                        }
                    }else
                    {
                        \DB::rollback();
                        $adminRoleId= Session::get('role_id');
                        // return view("admin.views.orders.list";
                        return redirect(route('admin-orders-list'))->with(['admin_role_id'=>$adminRoleId]);
                    }
                }else
                {
                    \DB::rollback();
                    $adminRoleId= Session::get('role_id');
                    // return view("admin.views.orders.list";
                    return redirect(route('admin-orders-list'))->with(['admin_role_id'=>$adminRoleId]);
                }
               
            }else{
              
            }
        }

    }
