<?php

    namespace App;

    use Illuminate\Database\Eloquent\Model;
    use DB;

    class NumberPlate extends Model
    {
        protected $table = "number_plats";
        protected $fillable = ['number_plate','status'];


        public function getDataByCustomerId($id){

            $data = DB::table('number_plats as np')
                    ->leftJoin('customers as c', 'c.id', 'np.customer_id')
                    ->select('np.*')
                    ->where('c.id', $id)
                    ->get();

            return $data;
        }

        public function getDatabyId($id){
            $data = NumberPlate::where('id', $id)->first();
            return $data;
        }
        public function insertData($crud){
            $data = NumberPlate::insertGetId($crud);
            return $data;
        }
        public function updateData($crud, $id){
           
            $data = NumberPlate::where('id', $id)->update($crud);
            return $data;
        }
        public function getDatabyCId($id){
            $data = NumberPlate::where('customer_id', $id)->first();
            return $data;
        }
        public function updateDataBycId($crud, $id){
           
            $data = NumberPlate::where('customer_id', $id)->update($crud);
            return $data;
        }
    }
