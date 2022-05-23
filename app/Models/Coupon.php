<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Coupon extends Model
{
    public $table = 'coupon';
    protected $fillable = [
        'coupon_code',
        'type',
        'amount',
        'description',
        'product_ids',
        'limit_of_use',
        'expiry_date',
        'status',
        'is_deleted'
    ];
    public function getInfoByCouponCode($couponCode)
    {
        $today = date('Y-m-d');
        $info = DB::table('coupon')
            ->where('coupon.coupon_code', $couponCode)
            ->where('coupon.status', 'Active')
            ->where('coupon.is_deleted', 'N')
            ->where(function($query) use ($today) {
                $query->whereRaw("coupon.expiry_date >= '$today'")
                    ->orWhereNull('coupon.expiry_date');
            })
            ->select('coupon.*')
            ->first();
        return $info;
    }
    public function getInfoByVoucherCode($couponCode)
    {
        $today = date('Y-m-d');
        $info = DB::table('voucher_orders')->where('voucher_code', $couponCode)->where('product_price', '>', '0')->where('status', '<>', 'Inactive')->first();
        return $info;
    }
    public function updateVoucher($where,$cartData)
    {
        $affectedRows = DB::table('voucher_orders')->where($where)
            ->update(
                $cartData
            );
        return $affectedRows;

    }

    public function getAllCouponData()
    {
        $coupon = DB::table('coupon')->get();
        return $coupon;
    }
    public function insertCoupon($crud)
    {
        $lastId = DB::table('coupon')->insertGetId($crud);
        return $lastId;
    }

    public function updateCoupon($where,$crud)
    {
        $update = DB::table('coupon')->where($where)->update($crud);
        return $update;
    }
}
