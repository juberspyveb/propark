<?php
use Illuminate\Encryption\Encrypter;
use Carbon\Carbon;
use Illuminate\Support\Str;

require_once('Helpers/helperAdminPanel.php'); //Admin Panel
require_once('Helpers/helperArray.php'); //All Array Custom Functions
require_once('Helpers/helperCart.php'); //All cart regarding functions
require_once('Helpers/helperDate.php'); //All date custom functions
require_once('Helpers/helperDB.php'); //All DB functions
require_once('Helpers/helperEmails.php'); //All Emails functions
require_once('Helpers/helperEncryption.php'); //All Encryption Function
require_once('Helpers/helperNotifications.php'); //All Notification Function
require_once('Helpers/helperUtility.php'); //All Utility Functions


    function _pre($array, $exit = 1){
        echo '<pre>';
        print_r($array);
        echo '</pre>';
        if ($exit == 1) {
            exit();
        }
    }


if (!function_exists('include_route_files')) {

    /**
     * Loops through a folder and requires all PHP files
     * Searches sub-directories as well.
     *
     * @param $folder
     */
    function include_route_files($folder)
    {
        try {
            $rdi = new recursiveDirectoryIterator($folder);
            $it = new recursiveIteratorIterator($rdi);

            while ($it->valid()) {
                if (!$it->isDot() && $it->isFile() && $it->isReadable() && $it->current()->getExtension() === 'php') {
                    require $it->key();
                }

                $it->next();
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}
    // function test_toastr()
    // {
        // Session::flash('message', 'This is a message!');
    // 	if(Session::has('message'))
    // 	{
    //         echo session('message');
    //         exit;
    // 	}
    // }

    function getRealQuery($query, $dumpIt = true){
        $params = array_map(function ($item) {
            return "'{$item}'";
        }, $query->getBindings());

        $result = str_replace_array('\?', $params, $query->toSql());

        if($dumpIt){
            _pre($result);
        }

        return $result;
    }

    /* Curl For Get Request API*/
    if(!function_exists('get_request_api_data')){
        function get_request_api_data($url, $jsonData=''){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_POST, 0);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, $url);
            $result = curl_exec($ch);
            curl_close($ch);
            return $result;
        }
    }


    if (!function_exists('_set_dash')) {
        function _set_dash($str = ''){
            $str = trim($str);
            if ($str == '') {
                $str = '-';
            }
            return $str;
        }
    }

    if (!function_exists('_random_string')) {
        function _random_string($length = 8){
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ@!_#^*';
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, strlen($characters) - 1)];
            }
            $randomString = str_shuffle($randomString);
            return $randomString;
        }
    }
    if (!function_exists('_required_asterisk')) 
    {
        function _required_asterisk($msg= 0)
        {
            if($msg){
                $htmlView = '<span class="pull-right text-danger">* required fields</span>';
            }else{
                $htmlView = '<span class="required text-danger">*</span>';
            }
            return $htmlView;

        }
    }
    if (!function_exists('_random_alpha_numeric')) {
        function _random_alpha_numeric($length = 8){
            $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, strlen($characters) - 1)];
            }
            $randomString = str_shuffle($randomString);
            return $randomString;
        }
    }

    if (!function_exists('_random_digit')) {
        function _random_digit($length = 4){
            $characters = '0123456789';
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, strlen($characters) - 1)];
            }
            $randomString = str_shuffle($randomString);
            return $randomString;
        }
    }

    /*  Reference: https://stackoverflow.com/questions/54425176/how-can-i-dynamically-change-the-keys-that-crypt-uses-in-laravel  */
    if (!function_exists('_encrypt_decrypt')) {
        function _encrypt_decrypt($type, $data){
            $from_key = "2ywk0281BYriiu@DGf6y@cBab95E^rh3";
            $cipher = "AES-256-CBC";
            $encrypterFrom = new Encrypter($from_key, $cipher);

            if($type == 'encrypt'){
                $encryptedToString = $encrypterFrom->encryptString($data);
                return $encryptedToString;
            }else if($type == 'decrypt'){
                $decryptedFromString = $encrypterFrom->decryptString($data);
                return $decryptedFromString;
            }
        }
    }

    if (!function_exists('_categoryTree')) {
        function _categoryTree($table_name, $parent_id = 0, $sub_mark = '', $selected_category='')
        {
            $category = DB::table($table_name)
                ->where('parent_cat_id', $parent_id)
                ->select('*')
                ->get();

            if(!$category->isEmpty()){
                foreach($category as $key => $value){
                    if($selected_category != ''){
                        if($selected_category == 0){
                            //
                        }else{
                            $option = '<option ';
                            if($value->id == $selected_category){
                                $option .= 'selected ';
                            }
                            $option .= 'value="'.$value->id.'">'.$sub_mark.$value->cat_name.'</option>';
                            echo $option;
                            _categoryTree($table_name, $value->id, $sub_mark.'&nbsp;&nbsp;&nbsp;', $selected_category);
                        }
                    }else{
                        echo '<option value="'.$value->id.'">'.$sub_mark.$value->cat_name.'</option>';
                        _categoryTree($table_name, $value->id, $sub_mark.'&nbsp;&nbsp;&nbsp;');
                    }
                }
            }
        }
    }

    if (!function_exists('_category_list_dash')) {
        function _category_list_dash($table_name, $parent_id = 0, $sub_mark = '', $id, &$sub_category_array=array()){
            if($parent_id == 0){
                $category = DB::table($table_name)
                    ->where('parent_cat_id', $parent_id)
                    ->where('id', $id)
                    ->select('*')
                    ->get();
            }else{
                $category = DB::table($table_name)
                    ->where('parent_cat_id', $parent_id)
                    ->select('*')
                    ->get();
            }
            
            if(!$category->isEmpty()){
                foreach($category as $key => $value){
                    // echo $sub_mark.$value->cat_name.'<br>';
                    $tempStdClassObject = $value;
                    $tempStdClassObject->cat_name_dash = $sub_mark.$value->cat_name;
                    array_push($sub_category_array, $tempStdClassObject);
                    _category_list_dash($table_name, $value->id, $sub_mark.' - ', $id, $sub_category_array);
                }
            }
        }
    }

    /** get admin data */
        if(!function_exists('_get_admin_data')){
            function _get_admin_data(){
                $admin = \DB::table('users')->where(['role' => 1])->first();

                return $admin;
            }
        }
    /** get admin data */

    /** print 24 hour array */
        if(!function_exists('_hoursRange')){
            function _hoursRange( $lower = 0, $upper = 86400, $step = 3600, $format = '' ) {
                $times = array();
            
                if(empty($format)) {
                    $format = 'H:s:i';
                }
            
                $i = 0;
                foreach(range($lower, $upper, $step) as $increment ) {
                    $increment = gmdate('H:s:i', $increment);
                    list($hour, $minutes) = explode(':', $increment);
                    $date = new DateTime($hour.':'.$minutes);
                    $times[$i] = $date->format($format);
                    $i++;
                }
            
                return $times;
            }
        }
    /** print 24 hour array */

    /** email header footer */
        if(!function_exists('_header_footer')){
            function _header_footer($logo_url = '', $title = '', $body_content = '', $footer_text = ''){
                $message = '';
            
                $message .= '
                        <html>
                        <head>
                        <title>' . $title . ' </title>
                        </head>
                        <body>
                        <div style="margin: 0;">
                            <table style="border-collapse: collapse;" border="0" width="100%" cellspacing="0" cellpadding="0" bgcolor="">
                                <tbody>
                                    <tr>
                                        <td valign="top">
                                            <center style="width: 100%;">
                                                <div style="font-size: 1px; line-height: 1px; max-height: 0px; max-width: 0px; overflow: hidden; font-family: sans-serif;">&nbsp;</div>
                                                <div style="max-width: 600px;">
                                                    <table style="max-width: 600px; background: #fff !important;" border="0" width="100%" cellspacing="0" cellpadding="0" align="center" bgcolor="#f49b23">
                                                        <tbody>
                                                            <tr>
                                                                <td style="background-color: #fff; text-align: center; padding: 15px 0; font-family: sans-serif; font-weight: bold; color: #000000; font-size: 30px;">
                                                                    <a href="javascript:void(0)" style="margin-top: 30px">
                                                                        <img class="" src="'.$logo_url.'" style="height: 120px; width: 120px; max-width: 180px; border-radius: unset;">
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <table style="max-width: 600px; border-radius: 5px;" border="0" width="100%" cellspacing="0" cellpadding="0" align="center" bgcolor="#FFFFFF">
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <table border="0" width="100%" cellspacing="0" cellpadding="0">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td style="padding: 20px; font-family: sans-serif; line-height: 24px; color: #555555; font-size: 15px;">' . $body_content . '</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>&nbsp;</td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <table style="background-color: #00853E !important; max-width: 600px; border="0" width="100%" cellspacing="0" cellpadding="0"  text-align="left" bgcolor="#00853E">
                                                        <tbody>
                                                            <tr>
                                                                <td style="padding: 15px; padding-bottom: 12px;width: 100%; font-size: 12px; font-family: sans-serif; line-height: 19px; text-align: left; color: #fff;"> ' .$footer_text. ' </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </center>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="yj6qo">&nbsp;</div>
                            <div class="adL">&nbsp;</div>
                            </div>
                        </body>
                    </html>';
                return $message;
            }
        }
    /** email header footer */
 
    /** email template */
        if(!function_exists('_email_template')){
            function _email_template($key, $content = ''){

                $response = \DB::table('email_templates')->where(['email_key' => "$key"])->first();
                
                if ($key == 'TEST_MAIL') {
                    $content = new stdClass();
                    $content->user_name = 'Vikas M.';
                    $content->contact_number = '+91 9904641070';
                    $content->action_url = base_url();
                    $content->sender_name = 'Backend Brains';
                    $content->website_name = 'wwww.backendbrains.com';
                    $content->varification_link = url('user/verify/');
                    $content->url_link = url('user/verify/');
                } else {
                    $content->var_site_url_admin = url('admin/dashboard/');
                    $content->var_site_name_admin = url('admin/dashboard/');
                }

                if(!empty($response)){
                    $html = $response->email_html;
                    foreach ($content as $key => $value) {
                        $html = str_replace('{'.$key.'}', $value, $html);
                    }
                    $response->html = $html;
                    return $response;
                }else{
                    return 0;
                }
            }
        }
    /** email template */

    /** function admin notificaiton data */
        if(!function_exists('_get_admin_notificaiton')){
            function _get_admin_notificaiton(){
                $admin = _get_admin_data();

                $noti = \DB::select("SELECT * FROM `notification` WHERE `user_id` = $admin->id AND `type` = 'A' AND `is_read` = 'N' ORDER BY id desc LIMIT 5 ");
                
                if(!empty($noti) && $noti != null){
                    $today = \DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
                    foreach($noti as $r){
                        $to_date = \DateTime::createFromFormat('Y-m-d H:i:s', $r->created_at);
                        $r->ago = dateDifferenceData($today, $to_date);
                    }
                }
                
                $noti_count = \DB::select("SELECT * FROM `notification` WHERE `user_id` = $admin->id AND `type` = 'A' AND `is_read` = 'N'");
                
                return ['notifications' => $noti, 'notifications_count' => $noti_count];
            }
        }
    /** function admin notificaiton data */

    /** function user notificaiton data */
        if(!function_exists('_get_user_notificaiton')){
            function _get_user_notificaiton($user){
                $noti = \DB::select("SELECT * FROM `notification` WHERE `user_id` = $user->id AND `type` = 'U' AND `is_read` = 'N' ORDER BY id desc LIMIT 5 ");
                
                if(!empty($noti) && $noti != null){
                    $today = \DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'));
                    foreach($noti as $r){
                        $to_date = \DateTime::createFromFormat('Y-m-d H:i:s', $r->created_at);
                        $r->ago = dateDifferenceData($today, $to_date);
                    }
                }
                
                $noti_count = \DB::select("SELECT * FROM `notification` WHERE `user_id` = $user->id AND `type` = 'U' AND `is_read` = 'N'");

                return ['notifications' => $noti, 'notifications_count' => $noti_count];
            }
        }
    /** function user notificaiton data */

    /** get last 7 days with today */
        if(!function_exists('_last_7_days')){
            function _last_7_days($first, $last, $step = '+1 day', $output_format = 'Y-m-d'){
                $dates = array();
                $current = strtotime($first);
                $last = strtotime($last);
            
                while( $current <= $last ) {
            
                    $dates[] = date($output_format, $current);
                    $current = strtotime($step, $current);
                }
            
                return $dates;
            }
        }
    /** get last 7 days with today */

    /** get fevicon */
        if(!function_exists('_get_favicon')){
            function _get_favicon(){
                return url('uploads\favicon.svg');
            }
        }
    /** get fevicon */

    if('_get_sales_transaction'){
        function _get_sales_transaction($lotId){
            $currentDateTime = getCurrentDateTime();
            $select = \DB::raw('IFNULL(SUM(paid_amount),0) as total_sales'); 
                $dataBase = \DB::table('transactions as T')->select($select);
                $dataBase->leftJoin('slots as S', function ($leftJoin) {
                    $leftJoin->on('T.slot_id', '=', 'S.id');
                });
                $dataBase->leftJoin('lots as L', function ($leftJoin) {
                    $leftJoin->on('S.lot_id', '=', 'L.id');
                });

                $responseTransactions = $dataBase->where('L.id', $lotId)
                                    ->whereIn('T.status', array(config('constants.transactions.IN-PROCESS'),config('constants.transactions.TIME-EXTENDED'),config('constants.transactions.COMPLETE'),config('constants.transactions.CLOSE')))
                                    ->whereRaw('Date(T.arrival_time) = CURDATE()')
                                    ->get()->first();

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

            return $overviewData;
        }
    }

    if('_get_paid_comission'){
        function _get_paid_comission($userId){
            $data = \DB::table('added_cash')->select(\DB::raw('IFNULL(SUM(amount),0) as total_commision'))->where('user_id', $userId)->whereRaw('Date(created_at) = CURDATE()')->get()->first();
            return $data;
        }   
    }



    /** site footer text */
        if(!function_exists('_get_site_footer_text')){
            function _get_site_footer_text(){
                return date('Y').' © LV Website';
            }
        }
    /** site footer text */
    

    if (!function_exists('getUserIpAddress')) {
        function getUserIpAddress()
        {
            $ip = "";
            if(!empty($_SERVER['HTTP_CLIENT_IP']))
            {
                //ip from share internet
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            }
            elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
            {
                //ip pass from proxy
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
            else
            {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
            return $ip;
        }
    }
    if (!function_exists('_generateSeoURLOld')) 
    {
        function _generateSeoURLOld($string, $wordLimit = 0) 
        {
            $separator = '-';
        
        if($wordLimit != 0){
            $wordArr = explode(' ', $string);
            $string = implode(' ', array_slice($wordArr, 0, $wordLimit));
        }

        $quoteSeparator = preg_quote($separator, '#');

        $trans = array(
            '&.+?;'                    => '',
            '[^\w\d _-]'            => '',
            '\s+'                    => $separator,
            '('.$quoteSeparator.')+'=> $separator
        );

        $string = strip_tags($string);
        foreach ($trans as $key => $val){
            $UTF8_ENABLED = true;
            // $string = preg_replace('#'.$key.'#i'.(UTF8_ENABLED ? 'u' : ''), $val, $string);
            $string = preg_replace('#'.$key.'#i'.($UTF8_ENABLED ? 'u' : ''), $val, $string);
        }

        $string = strtolower($string);

        return trim(trim($string, $separator));
    }
}

if (!function_exists('_generateSeoURL')) {
    function _generateSeoURL($string) {
        return strtolower(trim(preg_replace('~[^0-9a-z]+~i', '-', html_entity_decode(preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($string, ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8')), '-'));
    }
}

?>