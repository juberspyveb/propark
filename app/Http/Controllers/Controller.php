<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use DateTime;
use DatePeriod;
use DateInterval;
use Carbon\Carbon;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /** email template */
    function email_template($email_key, $content_data = ''){

        $mail_template_response = \DB::table('email_templates')->where(['email_key' => "$email_key"])->first();
        
        if ($email_key == 'TEST_MAIL') {
            $content_data = new stdClass();
            $content_data->user_name = 'Vikrant P.';
            $content_data->contact_number = '+91 8000255245';
            $content_data->action_url = base_url();
            $content_data->sender_name = 'Backend Brains';
            $content_data->website_name = 'wwww.backendbrains.com';
            $content_data->varification_link = url('user/verify/');
            $content_data->url_link = url('user/verify/');
        } else {
            $content_data->var_site_url_admin = url('admin/dashboard/');
            $content_data->var_site_name_admin = url('admin/dashboard/');
        }

        if(!empty($mail_template_response)){
            $email_html = $mail_template_response->email_html;
            foreach ($content_data as $key => $value) {
                $email_html = str_replace('{'.$key.'}', $value, $email_html);
            }
            $mail_template_response->email_html = $email_html;
            return $mail_template_response;
        }else{
            return 0;
        }
    }

    /** email header footer */
    function header_footer($log_url = '', $title = '', $body_content = '', $footer_text = ''){
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
                                                        <td style="background-color: #fff; text-align: left; padding: 15px 0; font-family: sans-serif; font-weight: bold; color: #000000; font-size: 30px;">
                                                        <a href="javascript:void(0)" style="margin-top: 30px"><img class="" src="'.$log_url.'" style="height: 80px; width: 130px;"></a>

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

    /** print 24 hour array */
    function hoursRange( $lower = 0, $upper = 86400, $step = 3600, $format = '' ) {
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

    /** get last 7 days with today */
    function datesRange($first, $last, $step = '+1 day', $output_format = 'Y-m-d') {

        $dates = array();
        $current = strtotime($first);
        $last = strtotime($last);
    
        while( $current <= $last ) {
    
            $dates[] = date($output_format, $current);
            $current = strtotime($step, $current);
        }
    
        return $dates;
    }

    /** get last months days with today */
    function monthDatesRange($month, $year, $endMonthDate){
        $list = [];

        $start_date = "01-".$month."-".$year;
        $start_time = strtotime($start_date);

        $end_time = strtotime($endMonthDate, $start_time);

        for($i=$start_time; $i<=$end_time; $i+=86400){
            $list[] = date('Y-m-d', $i);
        }

        return $list;
    }
    
}