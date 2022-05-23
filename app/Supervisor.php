<?php

    namespace App;

    use Illuminate\Database\Eloquent\Model;

    class Supervisor extends Model
    {
        protected $table = "supervisors";
       

        protected $fillable = ['supervisor_name','supervisor_mobile','status'];


        public function getData(){
            $data = Supervisor::all();
            return $data;
        }

        public function getDatabyId($id){
            $data = Supervisor::where('status', 'Active')->where('id', $id)->first();
            return $data;
        }
        public function insertData($crud){
            $data = Supervisor::insertGetId($crud);
            return $data;
        }
        public function updateData($crud, $id){
           
            $data = Supervisor::where('id', $id)->update($crud);
            return $data;
        }
    }
