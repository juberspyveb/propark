<?php

    namespace App\Http\Controllers;
    
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Mail;
    use Maatwebsite\Excel\Facades\Excel;
    use App\Exports\AdminOrdersExport;
    use File, URL;
    use App\NumberPlate;
    use App\Customer;
    use Datatables;
    use Session;
    use PDF;

    class NumberPlateController extends Controller{
        
        protected $objNumberPlate;

        public function __construct()
        {
            $this->objNumberPlate = new NumberPlate();
        }
        /** order listing page */
        public function list(){

            return view("admin.views.number_plate.list");
        }

        /** order listing fetch */
        public function list_fetch(Request $request, $id){
            
            // $id = base64_decode($id);
            $data = $this->objNumberPlate->getDataByCustomerId($id);
           
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn("action", function($data) {

                    if($data->status == config('constants.user_status.ACTIVE')) {
                        $status = '<a class="dropdown-item badge-gradient-warning" style="background-color: blue;" href="javascript:void( 0);" onclick="change_status(this);" data-status="2" data-id="'.$data->id.'">'.config('constants.user_status.INACTIVE').'</a>';
                    }else{
                        $status = '<a class="dropdown-item badge-gradient-success" href="javascript:void( 0);" onclick="change_status(this);" data-status="1" data-id="'.$data->id.'">'.config('constants.user_status.ACTIVE').'</a>';
                    }
                    
                    
                    $html =
    
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

                ->editColumn("status", function ($data) {
                    if($data->status == config('constants.user_status.ACTIVE')) {
                        return '<span class="badge badge-success">'.config('constants.user_status.ACTIVE').'</span>';
                    }elseif($data->status == config('constants.user_status.INACTIVE')) {
                        return '<span class="badge badge-warning">'.config('constants.user_status.INACTIVE').'</span>';
                    }else{
                        return '-';
                    }
                })
                
                ->rawColumns(["action","status"])
                ->make(true);
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
               
                $update =$this->objNumberPlate->updateData($crud_data, $id);

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