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
    use App\Transaction;
    use Datatables;
    use Session;
    
class TransactionController extends Controller{

    public function __construct()
        {
            $this->objTransaction = new Transaction();
            
        }

    public function list($id = null){
           
            if($id != null)
            {
                $id = base64_decode($id);

            }
            return view("admin.views.transaction.list", compact('id'));
        }

    public function add(){
        return view("admin.views.transaction.crud");
    }

    public function listFetch(Request $request)
    {
        if($request->id != null)
        {
            $transaction = $this->objTransaction->getDataByTransactionNum($request->id);
        }else{
            $transaction = $this->objTransaction->getData();
        }
       
        $datatables = Datatables::of($transaction)
        ->addIndexColumn()
        ->editColumn("action", function($transaction) {
            //'<a href=""><i class="fas fa-pencil-alt text-success"></i></a>'.
            $html = '<a href="'.route('admin-view-transaction', ['id' => base64_encode($transaction->id)]).'"><i class="fas fa-eye text-success"></i></a> &nbsp; '.
                    '<div class="dropdown" style="display: inline;">'.
                    '<a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="fas fa-bars text-secondary"></i>'.
                    '</a>'.
            '<ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: top, left; top: 20px; left: -142px;">'.
                        '<li class="ms-dropdown-list">'.
                            '<a class="dropdown-item badge-gradient-warning" 
                            href="javascript:void(0);" 
                            onclick="change_status(this);" 
                            data-status="'.config('constants.transactions.IN-PROCESS').'" data-id="'.$transaction->id.'">'.config('constants.transactions.IN-PROCESS').'</a>'.
                            
                            '<a class="dropdown-item badge-gradient-warning" 
                            href="javascript:void(0);" 
                            onclick="change_status(this);" 
                            data-status="'.config('constants.transactions.TIME-EXTENDED').'" data-id="'.$transaction->id.'">'.config('constants.transactions.TIME-EXTENDED').'</a>'.
                            
                            '<a class="dropdown-item badge-gradient-danger"
                            href="javascript:void(0);" 
                            onclick="change_status(this);" 
                            data-status="'.config('constants.transactions.UNPAID').'" data-id="'.$transaction->id.'">'.config('constants.transactions.UNPAID').'</a>'.
                            
                            '<a class="dropdown-item badge-gradient-success" 
                            href="javascript:void(0);" 
                            onclick="change_status(this);" 
                            data-status="'.config('constants.transactions.COMPLETE').'" data-id="'.$transaction->id.'">'.config('constants.transactions.COMPLETE').'</a>'.
                            
                            '<a class="dropdown-item badge-gradient-success" 
                            href="javascript:void(0);" 
                            onclick="change_status(this);" 
                            data-status="'.config('constants.transactions.CLOSE').'" data-id="'.$transaction->id.'">'.config('constants.transactions.CLOSE').'</a>'.
                            
                            '<a class="dropdown-item badge-gradient-danger" 
                            href="javascript:void(0);" 
                            onclick="change_status(this);" 
                            data-status="'.config('constants.transactions.INFRINGEMENT').'" data-id="'.$transaction->id.'">'.config('constants.transactions.INFRINGEMENT').'</a>'.
                        '</li>'.
                    '</ul>'.
                '</div>'.
            '';
            return $html;
        })
        ->editColumn("status", function ($transaction) {
            if($transaction->status == config('constants.transactions.IN-PROCESS')) {
                return '<span class="badge badge-warning">'.config('constants.transactions.IN-PROCESS').'</span>';
            }
            elseif($transaction->status == config('constants.transactions.TIME-EXTENDED')) {
                return '<span class="badge badge-warning">'.config('constants.transactions.TIME-EXTENDED').'</span>';
            }
            elseif($transaction->status == config('constants.transactions.UNPAID')) {
                return '<span class="badge badge-danger">'.config('constants.transactions.UNPAID').'</span>';
            }
            elseif($transaction->status == config('constants.transactions.COMPLETE')) {
                return '<span class="badge badge-success">'.config('constants.transactions.COMPLETE').'</span>';
            }
            elseif($transaction->status == config('constants.transactions.CLOSE')) {
                return '<span class="badge badge-success">'.config('constants.transactions.CLOSE').'</span>';
            }
            elseif($transaction->status == config('constants.transactions.INFRINGEMENT')) {
                return '<span class="badge badge-danger">'.config('constants.transactions.INFRINGEMENT').'</span>';
            }
            else{
                return '-';
            }
        })
        ->rawColumns(["action",'status']);
        if ( strlen( $request->get('search')['value'] ) > 0 ) {
            $keyword = $request->get('search')['value'];
            $datatables->filterColumn('transaction_number', function ($query, $keyword) {
                $query->orWhere('transaction_number', 'like', '%'.$keyword.'%');
            });
            $datatables->filterColumn('slot_number', function ($query, $keyword) {
                $query->orWhere('slot_number', 'like', '%'.$keyword.'%');
            });
            $datatables->filterColumn('mobile', function ($query, $keyword) {
                $query->orWhere('mobile', 'like', '%'.$keyword.'%');
            });
            $datatables->filterColumn('number_plate', function ($query, $keyword) {
                $query->orWhere('number_plate', 'like', '%'.$keyword.'%');
            });
            $datatables->filterColumn('parking_time', function ($query, $keyword) {
                $query->orWhere('parking_time', 'like', '%'.$keyword.'%');
            });
            $datatables->filterColumn('amount', function ($query, $keyword) {
                $query->orWhere('amount', 'like', '%'.$keyword.'%');
            });
            $datatables->filterColumn('paid_amount', function ($query, $keyword) {
                $query->orWhere('paid_amount', 'like', '%'.$keyword.'%');
            });
            $datatables->filterColumn('change_amount', function ($query, $keyword) {
                $query->orWhere('change_amount', 'like', '%'.$keyword.'%');
            });
            $datatables->filterColumn('arrival_time', function ($query, $keyword) {
                $query->orWhere('arrival_time', 'like', '%'.$keyword.'%');
            });
            $datatables->filterColumn('status', function ($query, $keyword) {
                $query->orWhere('transactions.status', 'like', '%'.$keyword.'%');
            });
        }
        return $datatables->make(true);
    }

        
        public function view(Request $request, $id){

            $id = base64_decode($id);
            $data = $this->objTransaction->getDatabyId($id);
            //  dd($data);
            if(!empty($data)){
                return view("admin.views.transaction.view", ['data'=>$data]);
            }else{
                return redirect(route('admin-transaction-list'));
            }

        }

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
                
                $where = array('id' => $id);
               
                $update =$this->objTransaction->updateRecord($crud_data, $where);

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

        







}