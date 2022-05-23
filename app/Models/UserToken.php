<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
class UserToken extends Model
{

    #use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'user_id', 'token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'user_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        //'email_verified_at' => 'datetime',
    ];

    public function checkSSO($sso){
        $select = DB::raw('*');
        $dataBase = DB::table('user_tokens')->select($select);
        $dataBase->leftJoin('user', function ($leftJoin) {
            $leftJoin->on('user_tokens.user_id', '=', 'user.id');
            #$leftJoin->where('product_image.active', 1);
            #$leftJoin->where('product_image.isDelete', 0);
        });
        $dataBase->where('token', $sso);
        $result = $dataBase->get()->first();
        return $result;
    }

}