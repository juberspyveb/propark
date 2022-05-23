<?php
#namespace App\Helpers; // Your helpers namespace
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\Auth;
#use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;


if (!function_exists('_sendTestEmail')) {
    function _sendTestEmail($mailData)
    {
        $mailData = (object)$mailData;
        $toEmail = @$mailData->toEmail;
        #$bccEmail = config('constants.mails.bcc_emails');
        if (empty($toEmail)) { // if local then send email to
            $toEmail = config('constants.mails.dev_emails');
        }
        $varGreetingName = 'Dev. Test';
        $emailData['data'] = array(
            'varGreetingName' => $varGreetingName,
            'email' => $toEmail,
            'giftVoucherData' => $mailData,
            'websiteUrl' => route('home'),
            'termsUrl' => route('terms'),
            'headerLogo'=> url('uploads/logo/header_logo.png')
        );
        $sendMailFrom = _get_setting_data('site_mail_email');
        $siteMailName  = _get_setting_data('site_mail_name');
        $title = config('constants.mails.front.test_email') ;
        #_pre($mailData,0);
        $errorMessage = "";
        try
        {
            //Customer Mail
            @Mail::send('emails.developer_email', $emailData, function ($message) use ($toEmail, $sendMailFrom, $siteMailName, $title) {
                $message->to($toEmail);
                $message->from($sendMailFrom, $siteMailName);
                $message->subject($title);
            });
            return true;
        }
        catch (\Swift_TransportException $e)
        {
            $errorMessage = @$e->getMessage();//"Failed to send email. Please contact to administrator.";
        }
        catch (\Swift_RfcComplianceException $e)
        {
            $errorMessage = @$e->getMessage();
        }
        catch (Exception $e)
        {
            $errorMessage = @$e->getMessage();//"Failed to send email. Please contact to administrator.";
        }

        if( !empty(Mail::failures()) )
        {
            $errorMessage = "There was one or more failures. They were: <br />";
            foreach(Mail::failures() as $email_address) {
                $errorMessage .= " - $email_address <br />";
            }
            $errorMessage .= "Please contact to administrator.";
        }

        if($errorMessage != "")
        {
            #TODO LOG CREATE
            Log::channel('emails')->error(['action'=>'_sendTestEmail','title'=>$title ,'errorMessage'=>$errorMessage,'emailData' => $emailData]);
        }
        return $errorMessage;
    }
}
if (!function_exists('_sendGiftVoucherEmail')) {
    function _sendGiftVoucherEmail($mailData)
    {
        $mailData = (object)$mailData;
        $toEmail = $mailData->recipient_email_address;
        $bccEmail = config('constants.mails.bcc_emails');
        if (MAIL_TEST_MODE == MAIL_TEST_MODE_TEST) { // if local then send email to
            $toEmail = config('constants.mails.dev_emails');
            $bccEmail = config('constants.mails.dev_emails');
        }
        $varGreetingName = $mailData->recipient_first_name. ' '.$mailData->recipient_last_name;
        $mailData->validUntilDate = date('d m y',strtotime('+1 year',strtotime(getCurrentDate())));
        $mailData->imgUrl = getVoucherType(round($mailData->product_price),'imgUrl');
        $emailData = array(
            'varGreetingName' => $varGreetingName,
            'email' => $toEmail,
            'giftVoucherData' => $mailData,
            'websiteUrl' => route('home'),
            'termsUrl' => route('terms'),
            'headerLogo'=> url('uploads/logo/header_logo.png')
        );
        $sendMailFrom = _get_setting_data('site_mail_email');
        $siteMailName  = _get_setting_data('site_mail_name');
        $title = config('constants.mails.front.gift_voucher_send') ;
        $errorMessage = "";
        #$toEmail = MAIL_TEST_EMAIL;
        #_pre($emailData);
        try
        {
            //Customer Mail
            @Mail::send('emails.customer_gift_voucher_mail', $emailData, function ($message) use ($toEmail, $sendMailFrom, $siteMailName, $title,$bccEmail) {
                $message->to($toEmail);
                #$message->bcc($bccEmail);
                $message->from($sendMailFrom, $siteMailName);
                $message->subject($title);
            });
            return true;
        }
        catch (\Swift_TransportException $e)
        {
            $errorMessage = @$e->getMessage();//"Failed to send email. Please contact to administrator.";
        }
        catch (\Swift_RfcComplianceException $e)
        {
            $errorMessage = @$e->getMessage();
        }
        catch (Exception $e)
        {
            $errorMessage = @$e->getMessage();//"Failed to send email. Please contact to administrator.";
        }

        if( !empty(Mail::failures()) )
        {
            $errorMessage = "There was one or more failures. They were: <br />";
            foreach(Mail::failures() as $email_address) {
                $errorMessage .= " - $email_address <br />";
            }
            $errorMessage .= "Please contact to administrator.";
        }

        if($errorMessage != "")
        {
            #TODO LOG CREATE
            Log::channel('emails')->error(['action'=>'_sendOrderSuccessEmail','title'=>$title ,'emailData' => $emailData]);
        }
        return $errorMessage;
    }
}
if (!function_exists('_sendOrderSuccessEmail')) {
    function _sendOrderSuccessEmail($finalOrderCrudData, $cartArray , $isCustomer = 1)
    {
        $orderInfo = (object)$finalOrderCrudData;
        $toEmail = $orderInfo->customer_email;
        if (MAIL_TEST_MODE == MAIL_TEST_MODE_TEST) { // if local then send email to
            #$toEmail = MAIL_TEST_EMAIL;
            $toEmail = config('constants.mails.dev_emails');
        }
        $varGreetingName = $orderInfo->customer_name;
        $emailData = array(
            'varGreetingName' => $varGreetingName,
            'email' => $toEmail,
            'currency' => config('constants.currency'),
            'orderData' => $orderInfo,
            'cartArray' => $cartArray,
            'websiteUrl' => route('home'),
            'termsUrl' => route('terms'),
            'headerLogo'=> url('uploads/logo/header_logo.png')
        );
        $sendMailFrom = _get_setting_data('site_mail_email');
        $siteMailName  = _get_setting_data('site_mail_name');
        $title = config('constants.mails.front.place_order_success') ;
        $errorMessage = "";
        try
        {
            if($isCustomer)
            {
                 $bccEmail = config('constants.mails.bcc_emails');
                //Customer Mail
                @Mail::send('emails.customer_order_success', $emailData, function ($message) use ($toEmail, $sendMailFrom, $siteMailName, $title,$bccEmail) {
                    $message->to($toEmail);
                    $message->from($sendMailFrom, $siteMailName);
                   // $message->bcc($bccEmail);
                    $message->subject($title);
                });
            }
            else
            {
                 #Admin Mail.
                $toEmail = config('constants.mails.sales_emails');
                $bccEmail = config('constants.mails.bcc_emails');
                $title = config('constants.mails.admin.admin_place_order_success') ;
                if (MAIL_TEST_MODE == MAIL_TEST_MODE_TEST) { // if local then send email to
                    $toEmail = config('constants.mails.dev_emails');
                    $bccEmail  = config('constants.mails.dev_emails');
                }
                @Mail::send('emails.admin_order_success', $emailData, function ($message) use ($toEmail, $bccEmail, $sendMailFrom, $siteMailName, $title) {
                    $message->to($toEmail);
                    #$message->bcc($bccEmail);
                    $message->from($sendMailFrom, $siteMailName);
                    $message->subject($title);
                });
                #DEVELOPER EMAIL
                $toEmail = config('constants.mails.dev_emails');
                $bccEmail  = config('constants.mails.dev_emails');
                 @Mail::send('emails.customer_order_success', $emailData, function ($message) use ($toEmail, $bccEmail, $sendMailFrom, $siteMailName, $title) {
                    $message->to($toEmail);
                    #$message->bcc($bccEmail);
                    $message->from($sendMailFrom, $siteMailName);
                    $message->subject($title);
                });
            }
        }
        catch (\Swift_TransportException $e)
        {
            $errorMessage = @$e->getMessage();//"Failed to send email. Please contact to administrator.";
        }
        catch (\Swift_RfcComplianceException $e)
        {
            $errorMessage = @$e->getMessage();
        }
        catch (Exception $e)
        {
            $errorMessage = @$e->getMessage();//"Failed to send email. Please contact to administrator.";
        }

        if( !empty(Mail::failures()) )
        {
            $errorMessage = "There was one or more failures. They were: <br />";
            foreach(Mail::failures() as $email_address) {
                $errorMessage .= " - $email_address <br />";
            }
            $errorMessage .= "Please contact to administrator.";
        }

           
        if($errorMessage != "")
        {
            #TODO LOG CREATE
            Log::channel('emails')->error(['action'=>'_sendOrderSuccessEmail','title'=>$title ,'errorMessage'=>$errorMessage,'emailData' => $emailData]);
        }
        if($isCustomer)
        {
            _sendOrderSuccessEmail($finalOrderCrudData, $cartArray , 0);
        }
    }
}
if (!function_exists('_sendAdminChangeStatusEmail')) {
    function _sendAdminChangeStatusEmail($mailData)
    {
        // dd($mailData);
        $mailData = (object)$mailData;
        $toEmail = $mailData->customer_email;
        $bccEmail = config('constants.mails.bcc_emails');
        if (MAIL_TEST_MODE == MAIL_TEST_MODE_TEST) { // if local then send email to
            $toEmail = config('constants.mails.dev_emails');
            $bccEmail = config('constants.mails.dev_emails');
        }
        $varGreetingName = $mailData->customer_name;
        $mailData->validUntilDate = date('d m y',strtotime('+1 year',strtotime(getCurrentDate())));
        // $mailData->imgUrl = getVoucherType(round($mailData->product_price),'imgUrl');
        $emailData = array(
            'varGreetingName' => $varGreetingName,
            'email' => $toEmail,
            'changeStatusData' => $mailData,
            'websiteUrl' => route('home'),
            'termsUrl' => route('terms'),
            'headerLogo'=> url('uploads/logo/header_logo.png')
        );
        $sendMailFrom = _get_setting_data('site_mail_email');
        $siteMailName  = _get_setting_data('site_mail_name');
        $title = config('constants.mails.admin.admin_change_status_mail') ;
        $errorMessage = "";
        #$toEmail = MAIL_TEST_EMAIL;
        // _pre($emailData);
        try
        {
            //Customer Mail
            @Mail::send('emails.admin_change_status_mail', $emailData, function ($message) use ($toEmail, $sendMailFrom, $siteMailName, $title,$bccEmail) {
                $message->to($toEmail);
                #$message->bcc($bccEmail);
                $message->from($sendMailFrom, $siteMailName);
                $message->subject($title);
            });
            return true;
        }
        catch (\Swift_TransportException $e)
        {
            $errorMessage = @$e->getMessage();//"Failed to send email. Please contact to administrator.";
        }
        catch (\Swift_RfcComplianceException $e)
        {
            $errorMessage = @$e->getMessage();
        }
        catch (Exception $e)
        {
            $errorMessage = @$e->getMessage();//"Failed to send email. Please contact to administrator.";
        }

        if( !empty(Mail::failures()) )
        {
            $errorMessage = "There was one or more failures. They were: <br />";
            foreach(Mail::failures() as $email_address) {
                $errorMessage .= " - $email_address <br />";
            }
            $errorMessage .= "Please contact to administrator.";
        }

        if($errorMessage != "")
        {
            #TODO LOG CREATE
            #dd($errorMessage);
            Log::channel('emails')->error(['action'=>'_sendAdminChangeStatusEmail','title'=>$title ,'errorMessage'=>$errorMessage,'emailData' => $emailData]);
        }
        return $errorMessage;
    }
}


if (!function_exists('_sendOrderInvoiceEmail')) {
    function _sendOrderInvoiceEmail($finalOrderCrudData, $cartArray , $isCustomer = 1)
    {
        $orderInfo = (object)$finalOrderCrudData;
        $toEmail = $orderInfo->customer_email;
        if (MAIL_TEST_MODE == MAIL_TEST_MODE_TEST) { // if local then send email to
            #$toEmail = MAIL_TEST_EMAIL;
            $toEmail = config('constants.mails.dev_emails');
        }
        $varGreetingName = $orderInfo->customer_name;
        $emailData = array(
            'varGreetingName' => $varGreetingName,
            'email' => $toEmail,
            'currency' => config('constants.currency'),
            'orderData' => $orderInfo,
            'cartArray' => $cartArray,
            'websiteUrl' => route('home'),
            'termsUrl' => route('terms'),
            'headerLogo'=> url('uploads/logo/header_logo.png')
        );
        $sendMailFrom = _get_setting_data('site_mail_email');
        $siteMailName  = _get_setting_data('site_mail_name');
        $title = config('constants.mails.front.invoice_order_success') ;
        $errorMessage = "";
        try
        {
            if($isCustomer)
            {
                $bccEmail = config('constants.mails.bcc_emails');
                //Customer Mail
                @Mail::send('emails.customer_order_invoice', $emailData, function ($message) use ($toEmail, $sendMailFrom, $siteMailName, $title,$bccEmail) {
                    $message->to($toEmail);
                    $message->from($sendMailFrom, $siteMailName);
                    // $message->bcc($bccEmail);
                    $message->subject($title);
                });
            }
            else
            {
                #Admin Mail.
                $toEmail = config('constants.mails.sales_emails');
                $bccEmail = config('constants.mails.bcc_emails');
                $title = config('constants.mails.admin.admin_place_order_success') ;
                if (MAIL_TEST_MODE == MAIL_TEST_MODE_TEST) { // if local then send email to
                    $toEmail = config('constants.mails.dev_emails');
                    $bccEmail  = config('constants.mails.dev_emails');
                }
                @Mail::send('emails.customer_order_invoice', $emailData, function ($message) use ($toEmail, $bccEmail, $sendMailFrom, $siteMailName, $title) {
                    $message->to($toEmail);
                    #$message->bcc($bccEmail);
                    $message->from($sendMailFrom, $siteMailName);
                    $message->subject($title);
                });
                #DEVELOPER EMAIL
                $toEmail = config('constants.mails.dev_emails');
                $bccEmail  = config('constants.mails.dev_emails');
                @Mail::send('emails.customer_order_invoice', $emailData, function ($message) use ($toEmail, $bccEmail, $sendMailFrom, $siteMailName, $title) {
                    $message->to($toEmail);
                    #$message->bcc($bccEmail);
                    $message->from($sendMailFrom, $siteMailName);
                    $message->subject($title);
                });
            }
        }
        catch (\Swift_TransportException $e)
        {
            $errorMessage = @$e->getMessage();//"Failed to send email. Please contact to administrator.";
        }
        catch (\Swift_RfcComplianceException $e)
        {
            $errorMessage = @$e->getMessage();
        }
        catch (Exception $e)
        {
            $errorMessage = @$e->getMessage();//"Failed to send email. Please contact to administrator.";
        }

        if( !empty(Mail::failures()) )
        {
            $errorMessage = "There was one or more failures. They were: <br />";
            foreach(Mail::failures() as $email_address) {
                $errorMessage .= " - $email_address <br />";
            }
            $errorMessage .= "Please contact to administrator.";
        }


        if($errorMessage != "")
        {
            #TODO LOG CREATE
            Log::channel('emails')->error(['action'=>'_sendOrderSuccessEmail','title'=>$title ,'errorMessage'=>$errorMessage,'emailData' => $emailData]);
        }
        if($isCustomer)
        {
            _sendOrderSuccessEmail($finalOrderCrudData, $cartArray , 0);
        }
    }
}