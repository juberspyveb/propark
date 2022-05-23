<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Product extends Model
{
    public $table = 'product';
    protected $fillable = [
        'cat_id',
        'product_name',
        'product_price',
        'product_qty',
        'currency_id',
        'product_desc',
        'product_image',
        'status',
        'is_deleted'
    ];
    public function __construct()
    {
        $this->urlProduct  = asset('/uploads/product/').'/';
        $this->productStatus = array(
            'ACTIVE'=>'Active',
            'DEACTIVE'=>'Deactive',
        );
    }


    public function getAllProductData()
    {
        $data = DB::table('product')->orderBy('created_at','desc')->get();
        return $data;
    }
    public function insertProductData($crud)
    {
        $insert = DB::table('product')->insertGetId($crud);
        return $insert;
    }
    public function getProductDataById($id)
    {
        $data = DB::table('product')->where('id', $id)->first();
        return $data;
    }
    public function getSignatureCollections()
    {
        $data = DB::table('product')->select('id','product_slug','name','price','size','finish','colour','description','quantity','character_limit','text_type',DB::raw("CONCAT('".$this->urlProduct ."', image) as product_image"),DB::raw("CONCAT('".$this->urlProduct ."_462x346_', image) as product_details_image"))->where('status', $this->productStatus['ACTIVE'])->where('is_deleted', "N")->orderBy('created_at','desc')->get();
        return $data;
    }
    public function getSignatureCollectionDetails($slug)
    {
        $data = DB::table('product')->select('id','product_slug','name','price','size','finish','colour','description','quantity','character_limit','text_type',DB::raw("CONCAT('".$this->urlProduct ."', image) as product_image"),DB::raw("CONCAT('".$this->urlProduct ."_462x346_', image) as product_details_image"))->where('status', $this->productStatus['ACTIVE'])->where('is_deleted', "N")->where('product_slug', $slug)->get()->first();
        return $data;
    }
}
