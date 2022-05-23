<?php

    namespace App\Http\Controllers;
    
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Mail;
    use Maatwebsite\Excel\Facades\Excel;
    use App\Exports\AdminOrdersExport;
    use File, URL;
    use App\User;
    use App\Lot, App\Supervisor;
    use Datatables;
    use Session;
    use PDF;

    class LotsController extends Controller{
        
        protected $objLot;

        public function __construct()
        {
            $this->objLot = new Lot();
            $this->objSupervisors = new Supervisor();
        }
        /** order listing page */
        public function list($id = null){

            if($id != null)
            {
                $id = base64_decode($id);
            }
            
            return view("admin.views.lots.list", compact('id'));
        }

        /** order listing fetch */
        public function list_fetch(Request $request){
            
            
            if($request->id != null)
            {
                $lots = $this->objLot->getDatabyIdForData($request->id);
            }else{
                $lots = $this->objLot->getData();
            }
            // $lots =  $this->objLot->getData();
        
            return Datatables::of($lots)
                ->addIndexColumn()
                ->editColumn("action", function($lots) {

                    if($lots->status == config('constants.user_status.ACTIVE')) {
                        $status = '<a class="dropdown-item badge-gradient-warning" style="background-color: blue;" href="javascript:void( 0);" onclick="change_status(this);" data-status="2" data-id="'.$lots->id.'">'.config('constants.user_status.INACTIVE').'</a>';
                    }else{
                        $status = '<a class="dropdown-item badge-gradient-success" href="javascript:void( 0);" onclick="change_status(this);" data-status="1" data-id="'.$lots->id.'">'.config('constants.user_status.ACTIVE').'</a>';
                    }
                    
                    $html = '<a href="'.route('admin-edit-lot',['id' => base64_encode($lots->id)]).'"><i class="fas fa-pencil-alt text-success"></i></a>'.
                            '<div class="dropdown" style="display: inline;">'.
                                '<a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="fas fa-bars text-secondary"></i>'.
                                '</a>'.
                                '<ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: top, left; top: 20px; left: -142px;">'.
                                    '<li class="ms-dropdown-list">'.
                                        $status.
                                    '</li>'.
                                '</ul>'.
                            '</div>';
                
                    return $html;

                })

                ->editColumn("bays_status", function($lots) {
                    $lot_count = 0;
                    if($lots->availableCount > 0){
                        $lot_count = $lots->availableCount;
                    }
                    $slot_count = 0;
                    if($lots->slotCount > 0){
                        $slot_count = $lots->slotCount;
                    }
                   return  $lot_count.'/'.$slot_count;
                })
              
                ->editColumn("status", function ($lots) {
                    if($lots->status == config('constants.user_status.ACTIVE')) {
                        return '<span class="badge badge-success">'.config('constants.user_status.ACTIVE').'</span>';
                    }elseif($lots->status == config('constants.user_status.INACTIVE')) {
                        return '<span class="badge badge-warning">'.config('constants.user_status.INACTIVE').'</span>';
                    }elseif($lots->status == config('constants.user_status.DELETED')) {
                        return '<span class="badge badge-danger">'.config('constants.user_status.DELETED').'</span>';
                    }else{
                        return '-';
                    }
                })
                
                
                ->rawColumns(["action", "bays_status","status"])
                ->make(true);
        }

        //get lots bays 
        public function list_lot_bays(Request $request){
            
            /*$slots =  $this->objSlot->getData();*/
            $slots = DB::table('slots as s')
                        ->leftJoin('lots as l', 's.lot_id','l.id')
                        ->select('s.*','l.lot_name')
                        ->where('lot_id',$request->input('id'))
                        ->get();
            // dd($slots);
            return Datatables::of($slots)
                ->addIndexColumn()
                ->editColumn("action", function($slots) {
                    
                    if($slots->status == config('constants.user_status.ACTIVE')) {
                        $status = '<a class="dropdown-item badge-gradient-warning" style="background-color: blue;" href="javascript:void( 0);" onclick="change_status(this);" data-status="2" data-id="'.$slots->id.'">'.config('constants.user_status.INACTIVE').'</a>';
                    }else{
                        $status = '<a class="dropdown-item badge-gradient-success" href="javascript:void( 0);" onclick="change_status(this);" data-status="1" data-id="'.$slots->id.'">'.config('constants.user_status.ACTIVE').'</a>';
                    }
                    
                    $html ='<a href="'.route('admin-edit-slot',['id' => base64_encode($slots->id)]).'"><i class="fas fa-pencil-alt text-success"></i></a>'.
                            '<div class="dropdown" style="display: inline;">'.
                            '<a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="fas fa-bars text-secondary"></i>'.
                            '</a>'.
                            '<ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: top, left; top: 20px; left: -142px;">'.
                                '<li class="ms-dropdown-list">'.
                                    $status.
                                '</li>'.
                            '</ul>'.
                        '</div>'.
                    '';
                    

                    return $html;

                })
                
                ->editColumn("is_available", function ($slots) {
                    if ($slots->is_available) {
                        return '<span class="badge badge-success">'.config('constants.slot.AVAILABLE').'</span>';
                    }else {
                        return '<span class="badge badge-danger">'.config('constants.slot.NOTAVAILABLE').'</span>';
                    }
                })
                ->editColumn("status", function ($slots) {
                    if ($slots->status == config('constants.user_status.ACTIVE')) {
                        return '<span class="badge badge-success">'.config('constants.user_status.ACTIVE').'</span>';
                    } elseif ($slots->status == config('constants.user_status.INACTIVE')) {
                        return '<span class="badge badge-warning">'.config('constants.user_status.INACTIVE').'</span>';
                    } else {
                        return '-';
                    }
                })
                
                ->rawColumns(["action", "status", "is_available"])
                ->make(true);
        }

        /** order view */
        public function view(Request $request, $id){
            $id = base64_decode($id);
            
            $lots = $this->objLot->getDatabyId($id);
                // dd($order);
            if(!empty($lots)){
                return view("admin.views.lots.view", ['data'=>$lots]);
            }else{
                return redirect(route('admin-lots-list'));
            }
        }

      
        /** add */
        public function add()
        {
            $supervisors = $this->objSupervisors->getData();
           
            return view("admin.views.lots.crud", compact('supervisors'));
        }
        /** add */

        /** insert */
        public function insert(Request $request)
        {
            $this->validate(request(), [
                    'lot_name' => ['required', 'string', 'max:255'],
                    'lot_address' => ['required','string', 'max:255'],
                    'lot_latitude' => ['required'],
                    'lot_longitude' => ['required'],
                    'supervisor_id' => ['required'],
                ]);
            // dd($request->all());
            $lot_name = $request->lot_name;
            $lot_address = $request->lot_address;
            $lot_latitude = $request->lot_latitude;
            $lot_longitude = $request->lot_longitude;
            $lot_notes = $request->lot_notes;
            $supervisor_id = $request->supervisor_id;

            $crud_data = array(
                    'lot_name' => $lot_name,
                    'lot_address' => $lot_address,
                    'lot_latitude' => $lot_latitude,
                    'lot_longitude' => $lot_longitude,
                    'lot_notes' => $lot_notes,
                    'supervisor_id' => $supervisor_id, 
                    'status' => config('constants.user_status.ACTIVE'),
                    'created_at' => date('Y-m-d H:i:s')
                );

            $last_inserted_id = $this->objLot->insertData($crud_data);

            if ($last_inserted_id > 0) {
                return redirect()->route('admin-lot-list')->with('success', __('Record inserted successfully.'));
            } else {
                return redirect()->route('admin-lots-add')->with('error', __('Failed to insert record.'))->withInput();
            }
        }
        /** insert */

        /** edit */
        public function edit(Request $request, $id)
        {
            $id = base64_decode($id);
            if (filter_var($id, FILTER_VALIDATE_INT)) {
                
                $data =  $this->objLot->getDatabyId($id);
                $supervisors = $this->objSupervisors->getData();
                return view("admin.views.lots.crud", compact('id','data','supervisors'));
            } else {
                return redirect()->route('admin-lots-list')->with('error', __('message_lang.accessing_data_not_found'));
            }
        }
        /** edit */

        /** update */
        public function update(Request $request, $id)
        {
            // dd('ggg');
            $id = base64_decode($id);
            $this->validate(request(), [
                    'lot_name' => ['required', 'string', 'max:255'],
                    'lot_address' => ['required','string', 'max:255'],
                    'lot_latitude' => ['required'],
                    'lot_longitude' => ['required'],
                    'supervisor_id' => ['required'],
                ]);

            $lot_name = $request->lot_name;
            $lot_address = $request->lot_address;
            $lot_latitude = $request->lot_latitude;
            $lot_longitude = $request->lot_longitude;
            $lot_notes = $request->lot_notes;
            $supervisor_id = $request->supervisor_id;

            $crud_data = array(
                    'lot_name' => $lot_name,
                    'lot_address' => $lot_address,
                    'lot_latitude' => $lot_latitude,
                    'lot_longitude' => $lot_longitude,
                    'lot_notes' => $lot_notes,
                    'supervisor_id' => $supervisor_id, 
                    'updated_at' => date('Y-m-d H:i:s'),
                );
               
            $update =$this->objLot->updateData($crud_data, $id);
             
            if ($update) {
                return redirect()->route('admin-lot-list')->with('success', __('Record updated successfully.'));
            } else {
                return redirect()->back()->with('error', __('Failed to update Record.'))->withInput();
            }
        }
        /** update */

        /** change-status */
        public function change_status(Request $request)
        {
            if (!$request->ajax()) {
                exit('No direct script access allowed');
            }

            if (!empty($request->all())) {
                $status = $request->status;
                $id = $request->id;

                $crud_data = array(
                        'status' => $status,
                        'updated_at' => date('Y-m-d H:i:s')
                    );
               
                $update =$this->objLot->updateData($crud_data, $id);

                if ($update) {
                    echo json_encode(array("status" => "success"));
                    exit;
                }else{
                    echo json_encode(array("status" => "failed"));
                    exit;
                }
                   
            } else {
                echo json_encode(array("status" => "failed"));
                exit;
            }
        }
        /** change-status */


    }
