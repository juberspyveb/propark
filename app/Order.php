<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Order extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'order_id';
    protected $fillable = [];
    public $productURL ='';
    public $orderTypeArray = [];
    public function __construct()
    {
        $this->productURL = url('uploads/product');
        #$this->orderTypeArray = array('Processing' => 1, 'Proof Emailed'=>2,'In Production'=>3, 'Quality Issue'=>4, 'Completed'=>5);
        #$this->orderTypeByIdArray = array(1 => 'Processing', 2 => 'Proof Emailed', 3 => 'In Production', 4 => 'Quality Issue',5 => 'Completed');
        $this->orderTypeArray = array('PROCESSING' => 1, 'Proof Emailed'=>2, 'In Production'=>3, 'Quality Issue'=>4, 'Completed'=>5, 'FAILED'=>6);
        $this->orderTypeByIdArray = array(1 => 'Processing', 2 => 'Proof Emailed', 3 => 'In Production',4 => 'Quality Issue', 5 =>'Completed', 6 => 'Failed');
    }
    //Add Order BY VP
    public function addOrder($orderData)
    {
        $returnId = DB::table('orders')->insertGetId($orderData);
        return $returnId;
    }

    //Add Order Items
    public function addOrderItem($orderData)
    {
        $returnId = DB::table('order_items')->insertGetId($orderData);
        return $returnId;
    }
    public function insert_order($crud_data)
    {
        $order_id = DB::table('orders')->insertGetId($crud_data);
        return $order_id;
    }
    
    public function insert_order_items($crud_data)
    {
        DB::table('order_items')->insert($crud_data);
        return true;
    }

    //Add Order BY VP
    public function addOrderPayment($orderData)
    {
        $returnId = DB::table('payments')->insertGetId($orderData);
        return $returnId;
    }
    public function getInfoById($order_id)
    {
        return DB::table('orders')
            ->join('users', 'users.id', '=', 'orders.customer_id')
            ->where('orders.order_id', $order_id)
            ->select('orders.*', 'users.first_name', 'users.last_name', 'users.email', DB::Raw("CONCAT(".'users.first_name'.", ' ', ".'users.last_name'.") as user_full_name"))
            ->first();
    }

    public function getDataByid($id)
    {
        $data = DB::table('orders')->where('order_id', $id)->first();
        return $data;
    }
    //Add Voucher Items
    public function addVoucher($orderData)
    {
        $returnId = DB::table('voucher_orders')->insertGetId($orderData);
        return $returnId;
    }

    public function getOrderByOrderUserId($orderId, $userId)
    {
        return DB::table('orders')
            ->join('users', 'users.id', '=', 'orders.customer_id')
            ->where('orders.order_id', $orderId)
            ->where('users.id', $userId)
            ->select('orders.*', 'users.stripe_customer_id', 'users.first_name', 'users.last_name', 'users.email', DB::Raw("CONCAT(".'users.first_name'.", ' ', ".'users.last_name'.") as user_full_name"))
            ->first();
    }
    
    public function get_last_order_info($order_id)
    {
        $last_order_info = DB::table('orders')->where('customer_id', $user_id)->orderBy('order_id', 'DESC')->first();
        return $last_order_info;
    }
    public function updateData($crud_data, $id)
    {
        $update = DB::table('orders')->where('order_id', $id)->limit(1)->update($crud_data);
        return $update;
    }

    public function checkExistrecord($id, $customer_id)
    {
        $last_order_info = DB::table('orders')->where('customer_id', $customer_id)->where('generate_code', $id)->first();
        return $last_order_info;
    }

    public function checkExistedrecordStatus($order_id, $status){
        $last_order_info = DB::table('orders')->where('order_id', $order_id)->where('order_status', $status)->first();
        return $last_order_info;
    }
    public function updateOrder($crud_data, $where)
    {
        DB::table('orders')->where($where)->limit(1)->update($crud_data);
        return true;
    }

    public function getActiveStackSurpriseOrders()
    {
        $orders = DB::table('orders')->
            join('user_subscriptions', 'orders.order_id', '=', 'user_subscriptions.order_id')->
            whereIn('orders.package', array('stack_me', 'surprise_me'))->
            where('user_subscriptions.status', 'active')->
            orderBy('user_subscriptions.id', 'desc')->
            groupBy('user_subscriptions.order_id')->
            select('orders.*')->
            get();
        return $orders;
    }

    public function getActiveBespokeOrders()
    {
        $orders = DB::table('orders')->
            join('user_subscriptions', 'orders.order_id', '=', 'user_subscriptions.order_id')->
            whereIn('orders.package', array('bespoke_me'))->
            where(function($query)
            {
                $query->where('orders.vip', "1")
                      ->orWhere('orders.non_vip_subscribe', 1);
            })->
            where('user_subscriptions.status', 'active')->
            orderBy('user_subscriptions.id', 'desc')->
            groupBy('user_subscriptions.order_id')->
            select('orders.*')->
            get();
        return $orders;
    }

    /*-----------------------------------------------------------------------*/

    public function getOrderProducts($orderId,$sideId=0,$weekNumber=0)
    {
        $productURL = $this->productURL;
         $databaseQuery  = DB::table('order_items')
            ->leftjoin('product', 'product.id', '=', 'order_items.product_id')
            ->select(
                        'order_items.*', 'product.name as product_name',
                        DB::Raw("CONCAT(".'"'.$productURL.'"'.", '/', ".'product.image'.") as product_image"),
                        DB::Raw("CONCAT(".'"'.$productURL.'"'.", '/100X100_', ".'product.image'.") as product_image_100X100"),
                        DB::Raw("CONCAT(".'"'.$productURL.'"'.", '/300X192_', ".'product.image'.") as product_image_300X192"),
                        DB::Raw("CONCAT(".'"'.$productURL.'"'.", '/155X100_', ".'product.image'.") as product_image_155X100"),
                        DB::Raw("CONCAT(".'"'.$productURL.'"'.", '/265X175_', ".'product.image'.") as product_image_265X175"),
                        DB::Raw("CONCAT(".'"'.$productURL.'"'.", '/500X585_', ".'product.image'.") as product_image_500X585")
                        
                    )
            //->where('organization.isDelete', 0)
            ->where('order_items.order_id', $orderId)
            #->whereRaw('NOT FIND_IN_SET(category_ids, '.$sideId.')');
            ->whereRaw('NOT FIND_IN_SET(?,category_ids)', [$sideId]);
        // if($weekNumber>0){
            $databaseQuery->where("order_items.week_number", $weekNumber);
        // }
        $responseList = $databaseQuery->orderBy('order_items.order_item_id','DESC')->get()->toArray();
        #dd($responseList);
        return $responseList;
    }

    public function getTotalProduct($orderId,$sideId)
    {
        $select = DB::raw('COALESCE(sum(order_items.quantity),0) AS totalProduct');
        /*$select = DB::raw('sum(quantity) AS totalItems');
        $dataBase = DB::table('order_items')->select($select);
        $dataBase->where('cart_id', $orderId);
        $result = $dataBase->get()->first();
        return $result->totalItems;*/
        $responseList  = DB::table('order_items')
            ->leftjoin('product', 'product.id', '=', 'order_items.product_id')
            ->select($select)
            //->where('organization.isDelete', 0)
            ->where('order_items.order_id', $orderId)
            #->whereRaw('NOT FIND_IN_SET(category_ids, '.$sideId.')')
            ->whereRaw('NOT FIND_IN_SET(?,category_ids)', [$sideId])
            ->get()->first();

        return $responseList->totalProduct;
    }

    public function getOrderSides($orderId,$sideId=0,$weekNumber=0)
    {
        $productURL = $this->productURL;
         $responseList  = DB::table('order_items')
            ->leftjoin('product', 'product.id', '=', 'order_items.product_id')
            ->select(
                        'order_items.*', 'product.name as product_name',
                        DB::Raw("CONCAT(".'"'.$productURL.'"'.", '/', ".'product.image'.") as product_image"),
                        DB::Raw("CONCAT(".'"'.$productURL.'"'.", '/100X100_', ".'product.image'.") as product_image_100X100"),
                        DB::Raw("CONCAT(".'"'.$productURL.'"'.", '/300X192_', ".'product.image'.") as product_image_300X192"),
                        DB::Raw("CONCAT(".'"'.$productURL.'"'.", '/155X100_', ".'product.image'.") as product_image_155X100"),
                        DB::Raw("CONCAT(".'"'.$productURL.'"'.", '/265X175_', ".'product.image'.") as product_image_265X175"),
                        DB::Raw("CONCAT(".'"'.$productURL.'"'.", '/500X585_', ".'product.image'.") as product_image_500X585")
                    )
            //->where('organization.isDelete', 0)
            ->where('order_items.order_id', $orderId)
            #->whereRaw('FIND_IN_SET(category_ids, '.$sideId.')')
            ->whereRaw('FIND_IN_SET(?,category_ids)', [$sideId])

            ->where('order_items.week_number', $weekNumber)
            
            ->orderBy('order_items.order_item_id','DESC')
           
            ->get()->toArray();
        return $responseList;
    }

    public function getTotalSides($orderId,$sideId)
    {
        $select = DB::raw('COALESCE(sum(order_items.quantity),0) AS totalProduct');
        /*$select = DB::raw('sum(quantity) AS totalItems');
        $dataBase = DB::table('order_items')->select($select);
        $dataBase->where('cart_id', $orderId);
        $result = $dataBase->get()->first();
        return $result->totalItems;*/
        $responseList  = DB::table('order_items')
            ->leftjoin('product', 'product.id', '=', 'order_items.product_id')
            ->select($select)
            //->where('organization.isDelete', 0)
            ->where('order_items.order_id', $orderId)
            #->whereRaw('FIND_IN_SET(category_ids, '.$sideId.')')
            ->whereRaw('FIND_IN_SET(?,category_ids)', [$sideId])
            ->get()->first();

        return $responseList->totalProduct;
    }

    public function getTotalSidesAmount($orderId,$sideId)
    {
        $select = DB::raw('COALESCE(sum(order_items.total_price),0) AS totalSidesPrice');
        /*$select = DB::raw('sum(quantity) AS totalItems');
        $dataBase = DB::table('order_items')->select($select);
        $dataBase->where('cart_id', $orderId);
        $result = $dataBase->get()->first();
        return $result->totalItems;*/
        $responseList  = DB::table('order_items')
            ->leftjoin('product', 'product.id', '=', 'order_items.product_id')
            ->select($select)
            //->where('organization.isDelete', 0)
            ->where('order_items.order_id', $orderId)
            #->whereRaw('FIND_IN_SET(category_ids, '.$sideId.')')
            ->whereRaw('FIND_IN_SET(?,category_ids)', [$sideId])
            ->get()->first();

        return $responseList->totalSidesPrice;
    }

    public function getOrdersByUserId($id){
        return DB::table('orders')
            ->select('orders.*')
            ->where(['customer_id' => $id])
            ->orderBy('order_id', 'desc')
            ->get()
            ->toArray();
    }

    public function getInfoByGenerateCode($generateCode,$userId)
    {
        if($userId==0){
            return DB::table('orders')
                ->join('users', 'users.id', '=', 'orders.customer_id')
                ->where('orders.generate_code', $generateCode)
                ->select('orders.*', 'users.first_name', 'users.last_name', 'users.email', DB::Raw("CONCAT(".'users.first_name'.", ' ', ".'users.last_name'.") as user_full_name"))
                ->first();
        }else{
            return DB::table('orders')
                ->join('users', 'users.id', '=', 'orders.customer_id')
                ->where('orders.generate_code', $generateCode)
                ->where('orders.customer_id', $userId)
                ->select('orders.*', 'users.first_name', 'users.last_name', 'users.email', DB::Raw("CONCAT(".'users.first_name'.", ' ', ".'users.last_name'.") as user_full_name"))
                ->first();
        }

    }

    public function countAllPaidOrders($searchArray)
    {
        // select count(*) as totalOrders FROM orders where payment_status='Paid'

        $query = DB::table('orders')->where('orders.payment_status', 'Paid');

        if(!empty($searchArray['filterBy']))
        {
            $option = $searchArray['filterBy']['option'];
            if($option == 'all')
            {
                $fromDate = $searchArray['filterBy']['startDate'];
                $toDate = $searchArray['filterBy']['endDate'];
                
                if(!empty($fromDate) && !empty($toDate))
                {
                    $query = $query->whereDate('orders.created_at', '>=', $fromDate)->whereDate('orders.created_at', '<=', $toDate);
                }
                else if(!empty($fromDate) && empty($toDate))
                {
                    $query = $query->whereDate('orders.created_at', '>=', $fromDate);
                }
                else if(empty($fromDate) && !empty($toDate))
                {
                    $query = $query->whereDate('orders.created_at', '<=', $toDate);
                }
            }
            else if($option == 'today' || $option == 'yesterday') {
                $searchDate = $searchArray['filterBy']['searchDate'];
                $query = $query->whereDate('orders.created_at', '=', $searchDate);
            }
            else if($option == 'lastWeek' || $option == 'lastMonth' || $option == 'lastYear') {
                $startDate = $searchArray['filterBy']['startDate'];
                $endDate = $searchArray['filterBy']['endDate'];
                $query = $query->whereDate('orders.created_at', '>=', $startDate)->whereDate('orders.created_at', '<=', $endDate);
            }
        }

        return $query->count();
    }

    public function totalEarning($searchArray)
    {
        // select sum(paid_amount) as totalEarning FROM orders where payment_status='Paid'

        $query = DB::table('orders')->where('orders.payment_status', 'Paid');

        if(!empty($searchArray['filterBy']))
        {
            $option = $searchArray['filterBy']['option'];
            if($option == 'all')
            {
                $fromDate = $searchArray['filterBy']['startDate'];
                $toDate = $searchArray['filterBy']['endDate'];
                
                if(!empty($fromDate) && !empty($toDate))
                {
                    $query = $query->whereDate('orders.created_at', '>=', $fromDate)->whereDate('orders.created_at', '<=', $toDate);
                }
                else if(!empty($fromDate) && empty($toDate))
                {
                    $query = $query->whereDate('orders.created_at', '>=', $fromDate);
                }
                else if(empty($fromDate) && !empty($toDate))
                {
                    $query = $query->whereDate('orders.created_at', '<=', $toDate);
                }
            }
            else if($option == 'today' || $option == 'yesterday') {
                $searchDate = $searchArray['filterBy']['searchDate'];
                $query = $query->whereDate('orders.created_at', '=', $searchDate);
            }
            else if($option == 'lastWeek' || $option == 'lastMonth' || $option == 'lastYear') {
                $startDate = $searchArray['filterBy']['startDate'];
                $endDate = $searchArray['filterBy']['endDate'];
                $query = $query->whereDate('orders.created_at', '>=', $startDate)->whereDate('orders.created_at', '<=', $endDate);
            }
        }

        return $query->sum('paid_amount');
    }

    public function recentOrders()
    {
        // select orders.order_id, orders.customer_id, orders.generate_code, orders.order_status, orders.currency, DATE_FORMAT(orders.order_date_time, '%d-%m-%Y %h-%s-%i') AS order_date_time, orders.paid_amount, CONCAT(users.first_name, ' ', users.last_name) as customer_name from orders left join users on users.id = orders.customer_id order by orders.order_id desc limit 10 offset 0

        return DB::table('orders')
            ->select('orders.order_id', 'orders.customer_id', 'orders.generate_code', 'orders.order_status', 'orders.currency', DB::Raw("DATE_FORMAT(".'orders.order_date_time'.", '%d-%m-%Y %h-%s-%i') AS order_date_time"), 'orders.paid_amount', DB::Raw("CONCAT(".'users.first_name'.", ' ', ".'users.last_name'.") as customer_name"))
            ->leftjoin('users', 'users.id', 'orders.customer_id')
            ->orderBy('orders.order_id', 'desc')
            ->offset(0)
            ->limit(10)
            ->get()
            ->toArray();
    }
    public function getInfoByOrderConfirmationId($orderConfirmationId)
    {
        return DB::table('orders')
            ->where('orders.generate_code', $orderConfirmationId)
            ->select('orders.*')
            ->first();
    }

    /* WK Start */
    //New Meal Selection Process
    public function getUpcomingOrdersUserId($id,$currentDate ,$type=1 ){
        $select = DB::raw("order_delivery_schedule.*,orders.order_id, orders.generate_code, orders.payment_status, orders.package, orders.total_meal");
        if($type){
            return DB::table('orders')
                ->select($select)
                ->join('order_delivery_schedule', 'order_delivery_schedule.order_id', '=','orders.order_id' )
                ->where(['orders.customer_id' => $id])
                #->where('order_delivery_schedule.order_id', $orderId)
                ->where('order_delivery_schedule.reminder_date', '<=',$currentDate )
                ->where('order_delivery_schedule.date_is_meal_selected', '>=',$currentDate )
                ->where('order_delivery_schedule.is_meal_selected','0')
                ->get()
                ->first();
        }else{
            return DB::table('orders')
                ->select($select)
                ->join('order_delivery_schedule', 'order_delivery_schedule.order_id', '=','orders.order_id' )
                ->where(['orders.customer_id' => $id])
                #->where('order_delivery_schedule.order_id', $orderId)
                ->where('order_delivery_schedule.reminder_date', '<=',$currentDate )
                ->where('order_delivery_schedule.date_is_meal_selected', '>=',$currentDate )
                #->where('order_delivery_schedule.is_meal_selected','0')
                ->get()
                ->first();
        }

    }


    public function getOrderProductList($categoryId='',$allergyIds='',$orderId=0,$isSide=false,$weekNumber=0)
    {
        $productURL = $this->productURL;
        $select = DB::raw("pd.*, pd.category_ids, pd.name, pd.price, pd.quantity, pd.currency_id, pd.description, pd.tags,
            CONCAT(" . "'" . $productURL . "'" . ", '/', " . "pd.image" . ") as image,
            CONCAT(" . "'" . $productURL . "'" . ", '/100X100_', " . "pd.image" . ") as image_100X100,
            CONCAT(" . "'" . $productURL . "'" . ", '/300X192_', " . "pd.image" . ") as image_300X192,
            CONCAT(" . "'" . $productURL . "'" . ", '/155X100_', " . "pd.image" . ") as image_155X100,
            CONCAT(" . "'" . $productURL . "'" . ", '/265X175_', " . "pd.image" . ") as image_265X175,
            CONCAT(" . "'" . $productURL . "'" . ", '/500X585_', " . "pd.image" . ") as image_500X585,
            COALESCE(order_items.quantity,0) as quantity");
        $dataBase = DB::table('product AS pd')->select($select);
        #$dataBase->leftjoin('cart_products', 'product.id', '=', 'cart_products.product_id');
        $dataBase->leftJoin('order_items', function ($leftJoin)use($orderId,$weekNumber)  {
            $leftJoin->on('order_items.product_id', '=', 'pd.id');
            $leftJoin->where('order_items.order_id', $orderId);
            if($weekNumber>0){
                $leftJoin->where("order_items.week_number", $weekNumber);
            }
        });
        if(!empty($categoryId)){
            $dataBase->whereRaw('NOT FIND_IN_SET(?,pd.category_ids)', [$categoryId]);
        }
        /*if(!empty($categoryId)){
            if($isSide){ #FOR SIDE
                $dataBase->whereRaw('FIND_IN_SET(?,pd.category_ids)', [$categoryId]);
            }else{#FOR PRODUCT
                $dataBase->whereRaw('FIND_IN_SET(?,pd.category_ids)', [$categoryId]);
            }
        }*/


        /*if($slideId>0){
            $dataBase->whereRaw('NOT FIND_IN_SET(?,pd.category_ids)', [$slideId]);
        }*/
        if(!empty($allergyIds)){
            $dataBase->whereNotIn('pd.tags', $allergyIds);
        }
        if(_getTagBoneId()){
            $dataBase->whereRaw('Not FIND_IN_SET(?,pd.tags)', [_getTagBoneId()]);
        }
        if(_getOtherCategoryId()){
            $dataBase->whereRaw('NOT FIND_IN_SET(?,pd.category_ids)', [_getOtherCategoryId()]);
        }
        $dataBase->where("pd.is_deleted", "=", "N");
        $dataBase->where("status", "Active");
        $dataBase->orderBy('pd.id','DESC');
        $result = $dataBase->get();
        return $result;
    }
    public function getOrderProductBoneList($categoryId='',$allergyIds='',$orderId=0,$isSide=false,$weekNumber=0)
    {
        $productURL = $this->productURL;
        $select = DB::raw("pd.*, pd.category_ids, pd.name, pd.price, pd.quantity, pd.currency_id, pd.description, pd.tags,
            CONCAT(" . "'" . $productURL . "'" . ", '/', " . "pd.image" . ") as image,
            CONCAT(" . "'" . $productURL . "'" . ", '/100X100_', " . "pd.image" . ") as image_100X100,
            CONCAT(" . "'" . $productURL . "'" . ", '/300X192_', " . "pd.image" . ") as image_300X192,
            CONCAT(" . "'" . $productURL . "'" . ", '/155X100_', " . "pd.image" . ") as image_155X100,
            CONCAT(" . "'" . $productURL . "'" . ", '/265X175_', " . "pd.image" . ") as image_265X175,
            CONCAT(" . "'" . $productURL . "'" . ", '/500X585_', " . "pd.image" . ") as image_500X585,
            COALESCE(order_items.quantity,0) as quantity");
        $dataBase = DB::table('product AS pd')->select($select);
        #$dataBase->leftjoin('cart_products', 'product.id', '=', 'cart_products.product_id');
        $dataBase->leftJoin('order_items', function ($leftJoin)use($orderId,$weekNumber)  {
            $leftJoin->on('order_items.product_id', '=', 'pd.id');
            $leftJoin->where('order_items.order_id', $orderId);
            if($weekNumber>0){
                $leftJoin->where("order_items.week_number", $weekNumber);
            }
        });
        if(!empty($categoryId)){
            $dataBase->whereRaw('NOT FIND_IN_SET(?,pd.category_ids)', [$categoryId]);
        }
        /*if(!empty($categoryId)){
            if($isSide){ #FOR SIDE
                $dataBase->whereRaw('FIND_IN_SET(?,pd.category_ids)', [$categoryId]);
            }else{#FOR PRODUCT
                $dataBase->whereRaw('FIND_IN_SET(?,pd.category_ids)', [$categoryId]);
            }
        }*/


        /*if($slideId>0){
            $dataBase->whereRaw('NOT FIND_IN_SET(?,pd.category_ids)', [$slideId]);
        }*/
        if(!empty($allergyIds)){
            $dataBase->whereNotIn('pd.tags', $allergyIds);
        }
        if(_getTagBoneId()){
            $dataBase->whereRaw('FIND_IN_SET(?,pd.tags)', [_getTagBoneId()]);
        }
        if(_getOtherCategoryId()){
            #$dataBase->whereRaw('NOT FIND_IN_SET(?,pd.category_ids)', [_getOtherCategoryId()]);
        }
        $dataBase->where("pd.is_deleted", "=", "N");
        $dataBase->where("status", "Active");
        $dataBase->orderBy('pd.id','DESC');
        $result = $dataBase->get();
        return $result;
    }

    //item exist in cart or not
    public function isItemExistInOrder($orderId, $productId,$weekNumber=1)
    {
        $responseData = \DB::table('order_items')->select('*')->where(['order_id' => $orderId, 'product_id' => $productId, 'week_number' => $weekNumber])->first();
        return $responseData;
    }


    //Update Item into cart Item
    public function updateOrderItem($where,$cartItem)
    {
        $affectedRows = DB::table('order_items')->where($where)
            ->update(
                $cartItem
            );
        return $affectedRows;
    }
    //Remove Item from Cart
    public function removeOrderItem($orderItemId)
    {
        $dataBase = DB::table('order_items');
        $dataBase->where("order_item_id", $orderItemId);
        #$dataBase->where("order_id", $orderId);
        #$dataBase->where("product_id", $productId);
        return $dataBase->delete();
    }

    public function getTotalProductCount($orderId,$upcomingWeek){
        $select = DB::raw('COALESCE(sum(order_items.quantity),0) AS totalProduct');
        $responseData = DB::table('order_items')
            ->select($select)
            ->where('order_id',$orderId )
            ->where('week_number',$upcomingWeek)
            ->get()
            ->first();
        return $responseData->totalProduct;
    }
    public function myOrder($userId)
    {
        $responseData = \DB::table('orders')
            ->select('order_id as id', 'generate_code', 'subscription_amount', 'paid_amount', 'package', 'order_status', 'total_meal', 'subscription_status',
                \DB::Raw("DATE_FORMAT(".'created_at'.",'%d-%m-%Y') as created_at")
            )
            ->where(['customer_id' => $userId])->orderBy('id', 'desc')
            ->get();
        return $responseData;
    }
    /* WK End */

    public function getTodayEarning($today)
    {
        $today_earning = \DB::table('orders as od')
            ->select(\DB::Raw("CASE WHEN SUM(".'od.total_payable'.") > 0 THEN SUM(".'od.total_payable'.") ELSE '0' END as total"))
            ->join('payments', 'payments.order_id', '=', 'od.order_id')
            ->whereBetween('od.created_at', [$today.' 00:00:00', $today.' 23:59:59'])
            ->where(['payments.payment_status' => 'Completed'])
            ->first();

        return  $today_earning;
    }

    public function getYesterdayEaring()
    {
        $yester_day = date("Y-m-d", strtotime("1 days ago"));
        // dd($yester_day);
        $yester_earning = \DB::table('orders as od')
            ->select(\DB::Raw("CASE WHEN SUM(".'od.total_payable'.") > 0 THEN SUM(".'od.total_payable'.") ELSE '0' END as total"))
            ->join('payments', 'payments.order_id', '=', 'od.order_id')
            ->whereBetween('od.created_at', [$yester_day.' 00:00:00', $yester_day.' 23:59:59'])
            ->where(['payments.payment_status' => 'Completed'])
            ->first();
        return $yester_earning;
    }
    public function getWeekEaring($today)
    {
       $start_date = date('Y-m-d', strtotime("1 days ago"));
        $end_date =  date("Y-m-d", strtotime("7 days ago"));
        //dd($start_date , $end_date);
        $week_earning = \DB::table('orders as od')
            ->select(\DB::Raw("CASE WHEN SUM(".'od.total_payable'.") > 0 THEN SUM(".'od.total_payable'.") ELSE '0' END as total"))
            ->join('payments', 'payments.order_id', '=', 'od.order_id')
            ->whereBetween('od.created_at',[$end_date.' 00:00:00', $start_date.' 23:59:59'])
            ->where(['payments.payment_status' => 'Completed'])
            ->first();
        
        // dd($week_earning);
        return $week_earning;
    }

    public function getMonthEaring()
    {
        $year = date("Y");
        $month = date("m");
        $endMonthDate = date("Y-m-d");
        $monthStartDate = $year."-".$month.'-01';
            $month_earning = \DB::table('orders as od')
                ->select(\DB::Raw("CASE WHEN SUM(".'od.total_payable'.") > 0 THEN SUM(".'od.total_payable'.") ELSE '0' END as total"))
                ->join('payments', 'payments.order_id', '=', 'od.order_id')
                ->whereBetween('od.created_at', [$monthStartDate.' 00:00:00', $endMonthDate.' 23:59:59'])
                ->where(['payments.payment_status' => 'Completed'])
                ->first();

        return $month_earning;
    }
    public function getYearEaring()
    {
        $year = date("Y");
        $start_year = $year.'-01-01';
        $end_year = date("Y-m-d");
        $year_earning = \DB::table('orders as od')
            ->select(\DB::Raw("CASE WHEN SUM(".'od.total_payable'.") > 0 THEN SUM(".'od.total_payable'.") ELSE '0' END as total"))
            ->join('payments', 'payments.order_id', '=', 'od.order_id')
            ->whereBetween('od.created_at', [$start_year.' 00:00:00', $end_year.' 23:59:59'])
            ->where(['payments.payment_status' => 'Completed'])
            ->first();

        return $year_earning;
    }


    public function updatePaymentDetails($crud_data, $where)
    {
        DB::table('payments')->where($where)->limit(1)->update($crud_data);
        return true;
    }

    public function getOrderDetailsById($order_id)
    {
        return DB::table('orders')
            ->where('orders.order_id', $order_id)
            ->select('orders.*')
            ->first();
    }
    public function getOrderVoucherById($order_id)
    {
        return DB::table('voucher_orders')
            ->where('order_id', $order_id)
            ->select('*')
            ->first();
    }
}
