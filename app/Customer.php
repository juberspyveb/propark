<?php

    namespace App;

    use Illuminate\Database\Eloquent\Model;
    use DB;

    class Customer extends Model
    {
        protected $table = "customers";
       

        protected $fillable = ['mobile','status'];


        public function getData(){

            $data = DB::table('number_plats as np')
                        ->leftJoin("customers as c",'np.customer_id','c.id')
                        ->leftJoin('transactions as t','c.id','t.customer_id')
                        ->select('c.*', DB::Raw("SUM(t.status = 'Close') as total_transaction"),'np.number_plate','np.id as plate_id')
                        ->Groupby('np.id')
                        ->orderBy('t.id', 'Desc')
                        ->get();

            return $data;
        }

        public function getDataLastTransactionByNumberId($id){
            $data = DB::table('transactions as t')->select('transaction_number')->where('number_plate_id', $id)->orderBy('arrival_time', 'Desc')->first();
            return $data;
        }

        public function getDataPending($id){
            $select = \DB::raw('IFNULL(SUM(paid_amount),0) as total_unpaid_amount'); 
            $dataBase = \DB::table('transactions as T')->select($select);
            $responseTransactions = $dataBase->where('number_plate_id', $id)
                                    ->whereIn('T.status', array(config('constants.transactions.UNPAID'),config('constants.transactions.INFRINGEMENT')))
                                    ->get()->first();
            return $responseTransactions;
            // dd($responseTransactions);
        }

        public function getDatabyId($id){
            $data = DB::table('number_plats as np')
                            ->leftJoin("customers as c",'np.customer_id','c.id')
                            ->select('np.number_plate','c.mobile')
                            ->where('np.id', $id)->first();
            return $data;
        }
        public function  getDatabyIdDetail($id){
            $data = Customer::where('status', 'Active')->where('id', $id)->get();
            return $data;
        }
        public function insertData($crud){
            $data = Customer::insertGetId($crud);
            return $data;
        }
        public function updateData($crud, $id){
           
            $data = Customer::where('id', $id)->update($crud);
            return $data;
        }
    }
