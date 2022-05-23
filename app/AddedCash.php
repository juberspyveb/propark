<?php

    namespace App;

    use Illuminate\Database\Eloquent\Model;
    use DB;

    class AddedCash extends Model
    {
        protected $table = "added_cash";
        protected $fillable = ['user_id','amount','status'];


        public function getDataByCustomerId($id){

            $data = DB::table('number_plats as np')
                    ->leftJoin('customers as c', 'c.id', 'np.customer_id')
                    ->select('np.*')
                    ->where('c.id', $id)
                    ->get();

            return $data;
        }

        public function getDatabyId($id){
            $data = AddedCash::where('id', $id)->first();
            return $data;
        }
        public function insertData($crud){
            $data = AddedCash::insertGetId($crud);
            return $data;
        }
        public function updateData($crud, $id){
           
            $data = AddedCash::where('id', $id)->update($crud);
            return $data;
        }

        public function updateDataBycId($crud, $id){
           
            $data = AddedCash::where('customer_id', $id)->update($crud);
            return $data;
        }
    }
