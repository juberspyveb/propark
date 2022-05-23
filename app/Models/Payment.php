<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Payment extends Model
{
    protected $table = 'payments';
    protected $primaryKey = 'payment_id';
    protected $fillable = [];
    public function __construct()
    {
    }

    public function get_info_by_id($email)
    {
        // select * from payments where payment_method='Stripe' and email='email'
        $payment_info = DB::table('payments')
            ->select('payments.*')
            ->where('payments.payment_method', 'Stripe')
            ->where('payments.email', $email)
            ->first();
        return $payment_info;
    }

}
