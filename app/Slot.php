<?php

    namespace App;

    use Illuminate\Database\Eloquent\Model;
    use DB;

    class Slot extends Model
    {
        protected $table = "slots";
       

        protected $fillable = ['slot_number','lot_name',
                                'lot_id','is_available','status','customer_id','number_plate_id','arrival_time','amount','parking_time'
                                
                            ];


        public function getData(){

            $data = DB::table('slots as s')
                        ->leftJoin('lots as l', 's.lot_id','l.id')
                        ->leftJoin('users as u', 's.customer_id','u.id')
                        ->leftJoin('number_plats as np', 's.number_plate_id','np.id')
                        ->select('s.*','u.name as customer_name','np.number_plate','l.lot_name')
                        ->orderby('l.id', 'ASC')
                        ->orderByRaw('s.slot_number', 'ASC')
                        ->get();
            return $data;
        }

        public function checkExist($lot_id, $number, $id)
        {
            $data = Slot::where('id', '!=' ,$id)->where(['slot_number' => $number,'lot_id' => $lot_id])->first();
            return $data;
        }
        public function getMaxNumberOfSlot(){
            $data = Slot::select(DB::raw('MAX(slot_number) AS max_slot'))->first();
            return $data;
        }

        public function getDatabyId($id){
            $data = Slot::where('id', $id)->first();
            return $data;
        }
        public function insertData($crud){
            $data = Slot::insertGetId($crud);
            return $data;
        }
        public function updateData($crud, $id){
           
            $data = Slot::where('id', $id)->update($crud);
            return $data;
        }
    }
