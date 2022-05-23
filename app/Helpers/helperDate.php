<?php
#namespace App\Helpers; // Your helpers namespace
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\Auth;
#use Illuminate\Support\Facades\Session;

if (!function_exists('getCurrentDate')) {
    function  getCurrentDate()
    {
        #    date_default_timezone_set('Africa/Lagos');
        return date('Y-m-d');
    }
}
if (!function_exists('getCurrentDateTime')) {
    function getCurrentDateTime()
    {
        #   date_default_timezone_set('Africa/Lagos');
        return date('Y-m-d H:i:s');
    }
}
if (!function_exists('getCurrentTime')) {
    function getCurrentTime()
    {
        # date_default_timezone_set('Africa/Lagos');
        return date('H:i:s');
    }
}

if (!function_exists('date_time')) {
    function date_time($text, $format = '', $time = ''){
        if ($text == "" || $text == "0000-00-00 00:00:00" || $text == "0000-00-00")
            return "---";
        switch ($format) {
            //us formate
            case "1":
                return date('d/m/Y', strtotime($text));
                break;
            case "2":
                return date('H:i:s ', strtotime($text));
                break;
            case "3":
                return date("jS F, Y", strtotime($text));
                break;
            case "4":
                return date("jS F, Y H:i A", strtotime($text));
                break;
            case "5":
                return date('l, F j, Y', strtotime($text));
                break;
            case "6":
                return date('g:i:s', $text);
                break;
            case "7":
                return date('F j, Y  h:i A', strtotime($text));
                break;
            case "8":
                return date('Y-m-d', strtotime($text));
                break;
            case "9":
                return date('F j, Y', strtotime($text));
                break;
            case "10":
                return date('d/m/Y', strtotime($text));
                break;
            case "11":
                return date('m/d/y', strtotime($text));
                break;
            case "12":
                return date('H:i', strtotime($text));
                break;
            case "13":
                return date('M j, Y h:i a', strtotime($text));
                break;
            case "14":
                return date('j-M-Y', strtotime($text));
                break;
            case "15":
                return date('D', strtotime($text));
                break;
            case "16":
                return date('d', strtotime($text));
                break;
            case "17":
                return date('M Y', strtotime($text));
                break;
            case "18":
                return date('h:i A', strtotime($text));
                break;
            case "19":
                return date('M j, Y', strtotime($text));
                break;
            case "20":
                return date('l,F d', strtotime($text));
                break;
            case "21":
                return date('m/d/y, l', strtotime($text));
                break;
            //Use below(22-23-24) date time format in whole site
            //For Time - 01:00 AM
            case "22":
                return date('h:i A', strtotime($text));
                break;
            //For date 10 Mar 2016
            case "23":
                return date('j M Y', strtotime($text));
                break;
            //For Date and Time  28 Mar 2016 01:00 AM
            case "24":
                return date('j M Y', strtotime($text)) . ' ' . date('h:i A', strtotime($time));
                break;
            //For DateTime type
            case "25":
                return date('j M Y  h:i A', strtotime($text));
                break;
            case "26":
                return date("jS M, Y", strtotime($text));
                break;
            case "27":
                return date("jS M, Y h:i:s a", strtotime($text));
                break;
            case "28":
                return date("jS M, Y h:i a", strtotime($text));
                break;
            case "29":
                return date("d.m.Y", strtotime($text));
                break;
            case "30":
                return date("d.m.Y H:i:s", strtotime($text));
                break;
            default :
                return date('Y-m-d', strtotime($text));
                break;
        }
    }
}

if (!function_exists('dateDifferenceData')) {
    function dateDifferenceData($date1, $date2){
        $interval = $date1->diff($date2);
        if($interval->invert == 1){
            $diff = ' ';
            if ($interval->y != 0) {
                $diff .= $interval->y . ' Yr ';
            }
            if ($interval->m != 0) {
                $diff .= $interval->m . ' mnth ';
            }
            if ($interval->d != 0) {
                $diff .= $interval->d . ' d ';
            }
            if ($interval->h != 0) {
                $diff .= $interval->h . ' h ';
            }
            if ($interval->i != 0) {
                $diff .= $interval->i . ' m ';
            }
            return $diff;
        }else{
            $diff = ' ';
            if ($interval->y != 0) {
                $diff .= $interval->y . ' Yr ';
            }
            if ($interval->m != 0) {
                $diff .= $interval->m . ' mnth ';
            }
            if ($interval->d != 0) {
                $diff .= $interval->d . ' d ';
            }
            if ($interval->h != 0) {
                $diff .= $interval->h . ' h ';
            }
            if ($interval->i != 0) {
                $diff .= $interval->i . ' m ';
            }
            return $diff;
        }
    }
}if (!function_exists('dateDifferenceDataV2')) {
    function dateDifferenceDataV2($date1, $date2,$key=''){

        $date1 = new DateTime($date1);
        $date2 = new DateTime($date2);
        $interval = $date1->diff($date2);
        $returnData['interval'] = $interval ;

        if(!empty($key)){
            return $interval->$key ;
        }else{
            return $interval ;
        }

        #echo "difference " . $interval->y . " years, " . $interval->m." months, ".$interval->d." days ";
        // shows the total amount of days (not divided into years, months and days like above)
        #echo "difference " . $interval->days . " days ";
        #exit;
        // Declare and define two dates
        $date1 = strtotime($date1);
        $date2 = strtotime($date2);
        $returnData = array();

        // Formulate the Difference between two dates
        $diff = abs($date2 - $date1);
        $returnData['diff'] = $diff;
        // To get the year divide the resultant date into
        // total seconds in a year (365*60*60*24)
        $years = floor($diff / (365*60*60*24));
        $returnData['year'] = $years ;

        // To get the month, subtract it with years and
        // divide the resultant date into
        // total seconds in a month (30*60*60*24)
        $months = floor(($diff - $years * 365*60*60*24)/ (30*60*60*24));
        $returnData['months'] = $months;

        // To get the day, subtract it with years and
        // months and divide the resultant date into
        // total seconds in a days (60*60*24)
        $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
        $returnData['days'] = $days;

        // To get the hour, subtract it with years,
        // months & seconds and divide the resultant
        // date into total seconds in a hours (60*60)
        $hours = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24) / (60*60));
        $returnData['hours'] = $hours ;

        // To get the minutes, subtract it with years,
        // months, seconds and hours and divide the
        // resultant date into total seconds i.e. 60
        $minutes = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60);
        $returnData['minutes'] = $minutes ;

        // To get the minutes, subtract it with years,
        // months, seconds, hours and minutes
        $seconds = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minutes*60));
        $returnData['seconds'] = $seconds;
        // Print the result
        #_pre($returnData);
        return $returnData;
        #printf("%d years, %d months, %d days, %d hours, " . "%d minutes, %d seconds", $years, $months,$days, $hours, $minutes, $seconds);

    }
}