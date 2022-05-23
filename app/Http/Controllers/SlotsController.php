<?php

    namespace App\Http\Controllers;
    
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Mail;
    use Maatwebsite\Excel\Facades\Excel;
    use App\Exports\AdminOrdersExport;
    use File, URL;
    use App\User;
    use App\Lot;
    use App\Slot;
    use Datatables;
    use Session;
    use PDF;

    class SlotsController extends Controller{
        
        protected $objLot;

        public function __construct()
        {
            $this->objLot = new Lot();
            $this->objSlot = new Slot();
        }
        /** order listing page */
        public function list(){

            return view("admin.views.slots.list");
        }

        /** order listing fetch */
        public function list_fetch(Request $request){
           
            /*$slots =  $this->objSlot->getData();*/
            $slots = DB::table('slots as s')
                        ->leftJoin('lots as l', 's.lot_id','l.id')
                        ->select('s.*','l.lot_name')
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
            } else {
                return redirect(route('admin-lots-list'));
            }
        }
      
        /** add */
        public function add(Request $request)
        {
            $lots = $this->objLot->getData();
            return view("admin.views.slots.crud",compact('lots'));
        }
        /** add */

        /** insert */
        public function insert(Request $request)
        { 
        
            if(isset($request->qty_range) && $request->qty_range == 'range'){
                $validate = [
                        'start_number' => "required",
                        'end_number' => "required"
                        ];
            }else{
                $validate = ['slot_number' => "required"];
            }

            $this->validate(request(),$validate);

            $data = $this->objSlot->getMaxNumberOfSlot();
            
            $slot_number = $request->slot_number;
            $crud_data = array(
                    
                );

            if(isset($request->lot_id) && $request->lot_id>0){
                $crud_data['lot_id'] = $request->lot_id;
                $crud_data['status'] = config('constants.user_status.ACTIVE');
            }else{
                $crud_data['status'] = config('constants.user_status.INACTIVE');
            }
            if(isset($request->qty_range) && $request->qty_range == 'range'){

                //check slot number
                $check = $this->objSlot->whereBetween('slot_number',[$request->start_number,$request->end_number])->count();
                if($check){
                    return redirect()->route('admin-add-slots')->with('error', __('In this range Bay Number already added!.'))->withInput();
                }
                $start_number = $request->start_number;
                for($i=$start_number; $i <= $request->end_number; $i++){

                    $crud_data['slot_number'] = $start_number;
                    $insert = $this->objSlot->insertData($crud_data);
                    $start_number++;
                }
            }else{
                //check slot number
                $check = $this->objSlot->where('slot_number',$request->slot_number)->count();
                if($check){
                    return redirect()->route('admin-add-slots')->with('error', __('This Bay Number is added!'))->withInput();
                }
                $crud_data['slot_number'] = $request->slot_number;
                $insert = $this->objSlot->insertData($crud_data);
            }

            
            if ($insert > 0) {
                return redirect()->route('admin-slots-list')->with('success', __('Record inserted successfully.'));
            } else {
                return redirect()->route('admin-add-slots')->with('error', __('Failed to insert record.'))->withInput();
            }
            
        }
        /** insert */

        public function edit(Request $request, $id)
        {
            $id = base64_decode($id);
            if (filter_var($id, FILTER_VALIDATE_INT)) {
                
                $data =  $this->objSlot->getDatabyId($id);
                $lots = $this->objLot->getData();
                $previous_url = url()->previous();
                    
                // $supervisors = $this->objSupervisors->getData();
                return view("admin.views.slots.crud", compact('id','data','lots','previous_url'));
            } else {
                return redirect()->route('admin-slots-list')->with('error', __('message_lang.accessing_data_not_found'));
            }
        }
        /** edit */

        /** update */
        public function update(Request $request, $id)
        {
            //  dd($id);
            $id = base64_decode($id);
            if (filter_var($id, FILTER_VALIDATE_INT)) {
                $this->validate(request(), [
                    'slot_number' => ['required'],
                ]);
                $checkExist =$this->objSlot->checkExist($request->lot_id, $request->slot_number, $id);
                // dd($checkExist);
                if(isset($checkExist) && !empty($checkExist))
                {
                    return redirect()->back()->with('error', __('Slot number already exist!.'));
                }else{
                        $crud_data = array(
                            'lot_id' => $request->lot_id,
                            'slot_number' => $request->slot_number,
                            'is_available' => 1,
                            'status' => config('constants.user_status.ACTIVE'),
                            'created_at' => date('Y-m-d H:i:s')
                        ); 
                        
                        $update =$this->objSlot->updateData($crud_data, $id);
                        
                        if ($update) {
                            
                            if(isset($request->previous_url) && $request->previous_url!=''){
                                return redirect($request->previous_url)->with('success', __('Record updated successfully.'));
                            }
                            return redirect()->route('admin-slots-list')->with('success', __('Record updated successfully.'));
                        } else {
                            return redirect()->back()->with('error', __('Failed to update Record.'))->withInput();
                        }
                }
            } else {
                return redirect()->route('admin-slots-list')->with('error', __('message_lang.accessing_data_not_found'));
            }
        }

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
               
                $update =$this->objSlot->updateData($crud_data, $id);

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
