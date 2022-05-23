<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Category extends Model
{
    public $table = 'category';
    protected $fillable = [
        'category_code',
        'type',
        'amount',
        'description',
        'product_ids',
        'limit_of_use',
        'expiry_date',
        'status',
        'is_deleted'
    ];
    

    public function getAllCategoryData()
    {
        $category = DB::table('category')->orderBy('created_at','desc')->get();
        return $category;
    }
    public function insertCategory($crud)
    {
        $lastId = DB::table('category')->insertGetId($crud);
        return $lastId;
    }
    
    public function getCategoryById($id)
    {
        $category = DB::table('category')->where('id',$id)->first();
        return $category;
    }

    public function updateCategory($where,$crud)
    {
        $update = DB::table('category')->where($where)->update($crud);
        return $update;
    }
    public function deleteCategory($where)
    {
        $category = DB::table('category')->where($where)->delete();
        return $category;
    }

}
