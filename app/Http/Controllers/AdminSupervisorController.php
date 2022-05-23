<?php

    namespace App\Http\Controllers;
    
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Mail;
    use Maatwebsite\Excel\Facades\Excel;
    use App\Exports\AdminOrdersExport;
    use File, URL;
    use App\User;
    use App\Supervisor;
    use Datatables;
    use Session;
    use PDF;

    class AdminSupervisorController extends Controller{
        
        protected $objSupervisor;

        public function __construct()
        {
            $this->objSupervisor = new Supervisor();
        }
        /** order listing page */
        public function list(){

            return view("admin.views.supervisor.list");
        }

        /** order listing fetch */
        public function list_fetch(Request $request){
          
            $lots =  $this->objSupervisor->getData();
           

            return Datatables::of($lots)
                ->addIndexColumn()
                ->editColumn("action", function($lots) {
                    if($lots->status == config('constants.user_status.ACTIVE')) {
                        $status = '<a class="dropdown-item badge-gradient-warning" style="background-color: blue;" href="javascript:void( 0);" onclick="change_status(this);" data-status="2" data-id="'.$lots->id.'">'.config('constants.user_status.INACTIVE').'</a>';
                    }else{
                        $status = '<a class="dropdown-item badge-gradient-success" href="javascript:void( 0);" onclick="change_status(this);" data-status="1" data-id="'.$lots->id.'">'.config('constants.user_status.ACTIVE').'</a>';
                    }
                    $html = 
                         '<a href="'.route('admin-edit-supervisor',['id' => base64_encode($lots->id)]).'"><i class="fas fa-pencil-alt text-success"></i></a>'.
                        '<div class="dropdown" style="display: inline;">'.
                            '<a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="fas fa-bars text-secondary"></i>'.
                            '</a>'.
                            '<ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: top, left; top: 20px; left: -142px;">'.
                                '<li class="ms-dropdown-list">'.
                                $status
                                .'</li>'.
                            '</ul>'.
                        '</div>'.
                    '';
                    

                    return $html;

                })
              
                ->editColumn("status", function ($lots) {
                    if ($lots->status == config('constants.user_status.ACTIVE')) {
                        return '<span class="badge badge-success">'.config('constants.user_status.ACTIVE').'</span>';
                    } elseif ($lots->status == config('constants.user_status.INACTIVE')) {
                        return '<span class="badge badge-warning">'.config('constants.user_status.INACTIVE').'</span>';
                    } elseif ($lots->status == config('constants.user_status.DELETED')) {
                        return '<span class="badge badge-danger">'.config('constants.user_status.DELETED').'</span>';
                    } else {
                        return '-';
                    }
                })
                
                ->rawColumns(["action", "status"])
                ->make(true);
        }

        /** order view */
        public function view(Request $request, $id){
            $id = base64_decode($id);
        
            
            $supervisor = $this->objSupervisor->getDatabyId($id);
                // dd($order);
            
            if(!empty($supervisor))
            {
                return view("admin.views.lots.view", ['data'=>$supervisor]);
            }
            else
            {
              
                 return redirect(route('admin-supervisor-list'));
            }

        }

      
        /** add */
        public function add(Request $request)
        {
            return view("admin.views.supervisor.crud");
        }
        /** add */

        /** insert */
        public function insert(Request $request)
        {

            $this->validate(request(), [
                    'supervisor_name' => ['required', 'string', 'max:255'],
                    'supervisor_mobile' => ['required'],
                ]);

            $supervisor_name = $request->supervisor_name;
            $supervisor_mobile = $request->supervisor_mobile;

            $crud_data = array(
                    'supervisor_name' => $supervisor_name,
                    'supervisor_mobile' => $supervisor_mobile,
                    'status' => config('constants.user_status.ACTIVE'),
                    'created_at' => date('Y-m-d H:i:s')
                );

            $last_inserted_id = $this->objSupervisor->insertData($crud_data);

            if ($last_inserted_id > 0) {
                return redirect()->route('admin-supervisor-list')->with('success', __('Record inserted successfully.'));
            } else {
                return redirect()->route('admin-supervisor-add')->with('error', __('Failed to insert record.'))->withInput();
            }
        }
        /** insert */

        /** edit */
        public function edit(Request $request, $id)
        {
            $id = base64_decode($id);
            if (filter_var($id, FILTER_VALIDATE_INT)) {
                
                $data =  $this->objSupervisor->getDatabyId($id);
               
                return view("admin.views.supervisor.crud", ["id" => $id,'data' => $data]);
            } else {
                return redirect()->route('admin-supervisor-list')->with('error', __('message_lang.accessing_data_not_found'));
            }
        }
        /** edit */

        /** update */
        public function update(Request $request, $id)
        {
            $id = base64_decode($id);
            $this->validate(request(), [
                'supervisor_name' => ['required', 'string', 'max:255'],
                'supervisor_mobile' => ['required'],
            ]);

            $supervisor_name = $request->supervisor_name;
            $supervisor_mobile = $request->supervisor_mobile;

            $crud_data = array(
                    'supervisor_name' => $supervisor_name,
                    'supervisor_mobile' => $supervisor_mobile,
                    'status' => config('constants.user_status.ACTIVE'),
                    'created_at' => date('Y-m-d H:i:s')
                );

            $update =$this->objSupervisor->updateData($crud_data, $id);
             
            if ($update) {
                return redirect()->route('admin-supervisor-list')->with('success', __('Record updated successfully.'));
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
               
                $update =$this->objSupervisor->updateData($crud_data, $id);

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
