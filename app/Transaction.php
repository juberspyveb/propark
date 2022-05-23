<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Transaction extends Model
{
    
    /*public function getData(){

            $data = DB::table('slots as s')
            /*$data = DB::table('transactions as t')

                      
                         ->leftJoin('slot as s', 't.slot_id','s.id')
                        
                        ->leftJoin('customers as c', 't.customer_id','c.id')
                        ->leftJoin('number_plats as np', 't.number_plate_id','np.id')
                        ->select('t.*','u.name as customer_name','np.number_plate')
                        ->orderby('t.id', 'ASC')
                        ->orderByRaw('s.slot_number', 'ASC')
                        ->get();
            return $data;
        }
*/
    public function updateRecord($crud,$where){
        $update = \DB::table('transactions')->where($where)->update($crud);
        return $update;
    }

    public function getDataByTransactionNum($id){
        $currentDateTime = getCurrentDateTime();//, (T.arrival_time-5) as PT
        $select = \DB::raw('transactions.id, transactions.transaction_number, transactions.parking_time, transactions.amount, transactions.paid_amount, transactions.change_amount, transactions.arrival_time, transactions.status, transactions.created_at,
        number_plats.number_plate,
        customers.mobile, 
        TIMESTAMPDIFF(MINUTE, transactions.arrival_time, "'.$currentDateTime.'") as time_diff_in_mins, 
        (transactions.parking_time-5) as PT, 
        (TIMESTAMPDIFF(MINUTE, transactions.arrival_time, "'.$currentDateTime.'") >= (transactions.parking_time-5) ) as TT, 
        slots.slot_number, 
        slots.is_available'); //,np.number_plate
        $dataBase = \DB::table('transactions')->select($select);
        $dataBase->leftJoin('slots', function ($leftJoin) {
            $leftJoin->on('transactions.slot_id', '=', 'slots.id');
        });
        $dataBase->leftJoin('lots', function ($leftJoin) {
            $leftJoin->on('slots.lot_id', '=', 'lots.id');
        });
        $dataBase->leftJoin('number_plats', function ($leftJoin) {
            $leftJoin->on('transactions.number_plate_id', '=', 'number_plats.id');
        });
        $dataBase->leftJoin('customers', function ($leftJoin) {
            $leftJoin->on('transactions.customer_id', '=', 'customers.id');
        });
         $dataBase->where('transactions.transaction_number', $id);
          
        $transaction = $dataBase;
        return $transaction;
    
    }
    public function getData()
    {
        /*$category = \DB::table('transactions')
        ->orderby('transactions.id', 'ASC');
        return $category;
        */
        
        // DB::enableQueryLog();
        // _pre(DB::getQueryLog());
        $currentDateTime = getCurrentDateTime();//, (T.arrival_time-5) as PT
        $select = \DB::raw('transactions.id, transactions.transaction_number, transactions.parking_time, transactions.amount, transactions.paid_amount, transactions.change_amount, transactions.arrival_time, transactions.status, transactions.created_at,
        number_plats.number_plate,
        customers.mobile, 
        TIMESTAMPDIFF(MINUTE, transactions.arrival_time, "'.$currentDateTime.'") as time_diff_in_mins, 
        (transactions.parking_time-5) as PT, 
        (TIMESTAMPDIFF(MINUTE, transactions.arrival_time, "'.$currentDateTime.'") >= (transactions.parking_time-5) ) as TT, 
        slots.slot_number, 
        slots.is_available'); //,np.number_plate
        $dataBase = \DB::table('transactions')->select($select);
        $dataBase->leftJoin('slots', function ($leftJoin) {
            $leftJoin->on('transactions.slot_id', '=', 'slots.id');
        });
        $dataBase->leftJoin('lots', function ($leftJoin) {
            $leftJoin->on('slots.lot_id', '=', 'lots.id');
        });
        $dataBase->leftJoin('number_plats', function ($leftJoin) {
            $leftJoin->on('transactions.number_plate_id', '=', 'number_plats.id');
        });
        $dataBase->leftJoin('customers', function ($leftJoin) {
            $leftJoin->on('transactions.customer_id', '=', 'customers.id');
        });
        //$responseTransactions =  $dataBase->where('lots.id', $lotId);
            //->orderBy('transactions.arrival_time','DESC');
        //exit;
        $transaction = $dataBase;
        return $transaction;
    }  


     public function getDatabyId($id){
            $data = DB::table('transactions as t')
                            ->leftJoin('slots as s','t.slot_id','s.id')
                            ->leftjoin('lots as l','s.lot_id','l.id')
                            ->leftjoin('customers as c','t.customer_id','c.id')
                            ->leftjoin('number_plats as np','c.id','np.customer_id')
                            ->select('t.*','s.slot_number','l.lot_name','c.mobile','np.number_plate')
                            ->where('t.id', $id)->first();
            return $data;
        }

}
        