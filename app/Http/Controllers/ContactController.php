<?php
namespace App\Http\Controllers;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Validator, Auth, Session, DB, Mail, Hash, URL, DateTime;
use Illuminate\Support\Facades\Artisan;
use App\Coupon, App\Payment;
use App\Libs\Stripe\StripeLib;
class ContactController extends Controller{

	/** contact */
    public function contact(Request $request){
        return view("front.views.contact");
    }
    /** contact */
    /** contact-us */
    public function contact_us(Request $request){
        $this->validate(request(), [
            'your_name' => ['required', 'string', 'max:255'],
            'your_email' => ['required', 'string', 'max:255', 'email'],
            'your_number' => ['required'],
            'your_message' => ['required']
        ]);

        $name = $request->your_name;
        $email = $request->your_email;
        $number = $request->your_number;
        $message = $request->your_message;
        $crud = array(
            'name' => $name,
            'email' => $email,
            'number' => $number,
            'message' => $message,
            'is_read' => 'N',
        );
        $last_id = DB::table('contact_us')->insertGetId($crud);
        if($last_id > 0){
            /* send email */
            $to_email = "info@slatesign.co.uk";
            $mail_content = (object)[];
            $mail_content->var_greeting_name = "Admin";
            $mail_content->name = $name;
            $mail_content->email = $email;
            $mail_content->number = $number;
            $mail_content->message = $message;
            $body_content = _email_template('CONTACT_US', $mail_content);
            $site_mail_name = "Slate Sign";
            $send_mail_from = "sales@slatesign.co.uk";
            $logo_url = _get_site_logo('header_logo');
            $title = 'Contact form | Slate Sign';
            $footer_text = 'Slate Sign';
            $mail_content->body = _header_footer($logo_url, $title, $body_content->html, $footer_text);
            $mail_content->to = $to_email;
            $mail_content->from = $send_mail_from;
            $mail_content->from_name = $site_mail_name;
            $mail_content->subject = $title;
            $mail_content = (array)$mail_content;
            $error_message = "";
            try
            {
                Mail::send([], [], function ($message) use ($mail_content,$email,$name) {
                    $message->to($mail_content['to'])
                        ->from($mail_content['from'], $mail_content['from_name'])
                        ->replyTo($email, $name)
                        ->subject($mail_content['subject'])
                        ->setBody($mail_content['body'], 'text/html');
                });
            }
            catch (\Swift_TransportException $e)
            {
                $error_message = "Failed to send email. Please contact to administrator.";
            }
            catch (\Swift_RfcComplianceException $e)
            {
                $error_message = $e->getMessage();
            }
            catch (Exception $e)
            {
                $error_message = "Failed to send email. Please contact to administrator.";
            }
            if( !empty(Mail::failures()) )
            {
                $error_message = "There was one or more failures. They were: <br />";
                foreach(Mail::failures() as $email_address) {
                    $error_message .= " - $email_address <br />";
                }
                $error_message .= "Please contact to administrator.";
            }
            if($error_message != "")
            {
                return redirect()->back()->with('error', $error_message)->withInput();
            }
            return redirect()->back()->with('success', 'Thank you for contact us.');
        }else{
            return redirect()->back()->with('error', 'something went wrong.')->withInput();
        }
    }
    /** contact-us */










}