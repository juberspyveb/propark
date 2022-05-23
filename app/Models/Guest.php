<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
class Guest extends Model
{
    use HasFactory;
    public $table = 'guests';
    protected $fillable = [
        'id',
        'client_id',
        'prefix',
        'first_name',
        'last_name',
        'email',
        'mobile',
        'phone',
        'zip_code',
        'is_newsletter',
        'comments',
        'status',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
    public function __construct()
    {
        $this->status = array(
            'DELETE'=>0,
            'ACTIVE'=>1,
            'INACTIVE'=>2,
            'BLOCK'=>3,
        );
    }

    public function createRecord($crud)
    {
        $lastId = DB::table('category')->insertGetId($crud);
        return $lastId;
    }
    public function updateRecord($crud,$where)
    {
        $update = DB::table('category')->where($where)->update($crud);
        return $update;
    }

    public function getById($id)
    {
        $category = DB::table('category')->where('id',$id)->first();
        return $category;
    }
    public function getAll($id='',$where=array())
    {
        $dataBase = DB::table($this->table);
        if(!empty($id)){
            $dataBase->where('client_id', $id);
        }
        $dataBase->where('status', "!=",0);
        $responseData = $dataBase->get()->all();
        return $responseData ;
    }
    public function deleteRecord($where)
    {
        $category = DB::table('category')->where($where)->delete();
        return $category;
    }


}
