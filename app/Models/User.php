<?php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use DB;

class User extends Authenticatable implements JWTSubject
{
    #use HasFactor, Notifiable;
    use Notifiable;
    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
    public function products()
    {
        return $this->hasMany(Test::class);
    }

    public function getSettingByUserId($id){
        $select = DB::raw('*');
        $dataBase = DB::table('user')->select($select);
        $responseData= $dataBase->where('id', $id)->get()->first();
        return $responseData;
    }
    public function updateUserSettingRecord($crud,$where)
    {
        $update = DB::table('user')->where($where)->update($crud);
        return $update;
    }
    public function getInfoBySlug($slug)
    {
        $select = DB::raw('*');
        $dataBase = DB::table('user')->select($select);
        $responseData= $dataBase->where('slug', $slug)->get()->first();
        return $responseData;
    }


    public function getData(){
        
        // $currentDateTime = getCurrentDateTime();

        
        $data = DB::table('users as u')
                    ->leftJoin('lots as l','u.lot_id','l.id')
                    ->leftJoin('supervisors as sp','l.supervisor_id','sp.id')
                    ->select('u.*','l.lot_name','sp.supervisor_name','l.id as lotId')
                    ->orderBy('created_at','desc')
                    ->get();
                    
        return $data;
    }

    public function getDatabyId($id){
        $data = User::where('status', 'Active')->where('id', $id)->first();
        return $data;
    }

    public function insertData($crud){
        $data = User::insertGetId($crud);
        return $data;
    }
    public function updateData($crud, $id){
       
        $data = User::where('id', $id)->update($crud);
        return $data;
    }
}