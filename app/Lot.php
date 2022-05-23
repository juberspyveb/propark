<?php

    namespace App;

    use Illuminate\Database\Eloquent\Model;
    use DB;

    class Lot extends Model
    {
        protected $table = "lots";
       

        protected $fillable = ['lot_name',
                                'lot_address',
                                'lot_latitude',
                                'lot_longitude',
                                'lot_notes',
                                'status',
                                
                            ];


        public function getData(){

            $data = DB::table('lots as l')
            
                ->leftJoin('slots as s', 'l.id', 's.lot_id')
                ->leftJoin('users as u', 'l.id', 'u.lot_id')
                ->leftJoin('supervisors as sp', 'l.supervisor_id','sp.id')
                ->select('l.*', DB::Raw("SUM(s.status = 'Active') as slotCount"),  DB::raw("SUM(s.is_available = '1' AND s.status = 'Active') AS availableCount"),'u.name as attedent','sp.supervisor_name as supervisor')
                ->groupBy('l.id')
                ->get();

         
            return $data;
        }

        public function getDatabyId($id){
            $data = Lot::where('id', $id)->first();
            return $data;
        }

        public function getDatabyIdForData($id){
            $data = DB::table('lots as l')
            
                ->leftJoin('slots as s', 'l.id', 's.lot_id')
                ->Join('users as u', 'l.id', 'u.lot_id')
                ->leftJoin('supervisors as sp', 'l.supervisor_id','sp.id')
                ->select('l.*', DB::Raw("SUM(s.status = 'Active') as slotCount"),  DB::raw("SUM(s.is_available = '1' AND s.status = 'Active') AS availableCount"),'u.name as attedent','sp.supervisor_name as supervisor')
                ->where('l.id', $id)
                ->get();

         
            return $data;
            // return $data;
        }
        public function insertData($crud){
            $data = Lot::insertGetId($crud);
            return $data;
        }
        public function updateData($crud, $id){
           
            $data = Lot::where('id', $id)->update($crud);
            return $data;
        }
    }
