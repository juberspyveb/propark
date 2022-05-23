<?php

    namespace App\Http\Controllers;
    
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Mail;
    use Maatwebsite\Excel\Facades\Excel;
    use File, URL;
    use App\User, App\AddedCash;
    use App\Customer;
    use Datatables;
    use Session;
    use PDF;

    class AddedCashController extends Controller{
        
        protected $objCustomer;

        public function __construct()
        {
            $this->objCustomer = new Customer();
            $this->objAddedCash = new AddedCash();
        }
        /** order listing page */
        // public function list(){

        //     // $customers =  $this->objCustomer->getData();
        //     // // dd( $customers);
        //     return view("admin.views.customer.list");
        // }

        /** order listing fetch */
        // public function list_fetch(Request $request, $id = null){
          
          
        //     if(!empty($id))
        //     {
        //         $customers =  $this->objCustomer->getDatabyIdDetail($id);
        //     }else{
        //         $customers =  $this->objCustomer->getData();
        //         // dd( $customers);
        //     }
        // // dd( $customers);
           
        
        //     return Datatables::of($customers)
        //         ->addIndexColumn()
        //         ->editColumn("action", function($customers) use($id) {

        //             if(!empty($id)){
        //                 $detail = '';
        //             }else{
        //                 // $detail = '';
        //                 $detail = '<a href="'.route('admin-customer-detail',['id' => base64_encode($customers->id)]).'"><i class="fas fa-eye text-success"></i></a>';
        //             }
        //             if($customers->status == config('constants.user_status.ACTIVE')) {
        //                 $status = '<a class="dropdown-item badge-gradient-warning" style="background-color: blue;" href="javascript:void( 0);" onclick="change_status(this);" data-status="2" data-id="'.$customers->id.'">'.config('constants.user_status.INACTIVE').'</a>';
        //             }else{
        //                 $status = '<a class="dropdown-item badge-gradient-success" href="javascript:void( 0);" onclick="change_status(this);" data-status="1" data-id="'.$customers->id.'">'.config('constants.user_status.ACTIVE').'</a>';
        //             }
                    
        //             $html =
        //                     '<a href="'.route('admin-edit-customer',['id' => base64_encode($customers->id)]).'"><i class="fas fa-pencil-alt text-success"></i></a>'.
        //                     $detail .
        //                     '<div class="dropdown" style="display: inline;">'.
        //                         '<a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="fas fa-bars text-secondary"></i>'.
        //                         '</a>'.
        //                         '<ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: top, left; top: 20px; left: -142px;">'.
        //                             '<li class="ms-dropdown-list">'.
        //                             $status.
        //                             '</li>'.
        //                         '</ul>'.
        //                     '</div>'.
        //                     '';

        //             return $html;

        //         })

        //         ->editColumn("total_transaction", function ($customers) {       
        //             if($customers->total_transaction != null)
        //             {
        //                 return $customers->total_transaction;
        //             }else{
        //                 return '0';
        //             } 
        //             })

                    
        //         ->editColumn("last_transaction", function ($customers) {
                         
        //              $data =  $this->objCustomer->getDataLastTransactionByNumberId($customers->plate_id);
        //             // dd($data);
        //             if( isset($data->transaction_number) && $data->transaction_number != null)
        //             {
        //                 return '<a href="'.route('admin-transaction-list',['id' => base64_encode($data->transaction_number)]).'">'.$data->transaction_number.'</a>';
        //             }else{
        //                 return '-';
        //             }
                
        //         })
                

        //         ->editColumn("balance_owing", function ($customers) {
        //             $data =  $this->objCustomer->getDataPending($customers->plate_id);
        //             return $data->total_unpaid_amount;

        //         })

        //         ->editColumn("status", function ($customers) {
        //             if($customers->status == config('constants.user_status.ACTIVE')) {
        //                 return '<span class="badge badge-success">'.config('constants.user_status.ACTIVE').'</span>';
        //             }elseif($customers->status == config('constants.user_status.INACTIVE')) {
        //                 return '<span class="badge badge-warning">'.config('constants.user_status.INACTIVE').'</span>';
        //             }elseif($customers->status == config('constants.user_status.DELETED')) {
        //                 return '<span class="badge badge-danger">'.config('constants.user_status.DELETED').'</span>';
        //             }else{
        //                 return '-';
        //             }
        //         })
                
        //         ->rawColumns(["action","status","last_transaction"])
        //         ->make(true);
        // }

        /** order view */
        // public function view(Request $request, $id){
        //     $id = base64_decode($id);
        
            
        //     $customer = $this->objCustomer->getDatabyId($id);
        //         // dd($order);
            
        //     if(!empty($customer))
        //     {
        //         return view("admin.views.customer.view", ['data'=>$customer]);
        //     }
        //     else
        //     {
        //         return redirect(route('admin-customers-list'));
        //     }

        // }

      
        /** add */
        // public function add(Request $request)
        // {
        //     return view("admin.views.customer.crud");
        // }
        /** add */

        /** insert */
        public function insert(Request $request, $id)
        {
            $id = base64_decode($id);
            if (filter_var($id, FILTER_VALIDATE_INT)) {
                    $this->validate(request(), [
                            'amount' => ['required'],
                        ]);

                    $crud_data = array(
                            'user_id' =>  $id,
                            'amount' => $request->amount,
                            'status' => '1',
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => $id,
                        );

                    $last_inserted_id = $this->objAddedCash->insertData($crud_data);

                    if ($last_inserted_id > 0) {
                        return redirect()->route('admin-users-list')->with('success', __('Record inserted successfully.'));
                    } else {
                        return redirect()->route('admin-add-user')->with('error', __('Failed to insert record.'))->withInput();
                    }
            }else{
                return redirect()->route('admin-users-list')->with('error', __('Failed to insert record.'));
            }
        }
        /** insert */

        /** edit */
        // public function edit(Request $request, $id)
        // {
        //     $id = base64_decode($id);
        //     if (filter_var($id, FILTER_VALIDATE_INT)) {
                
        //         $data =  $this->objCustomer->getDatabyId($id);
        //         // dd($data);
        //         return view("admin.views.customer.crud", ["id" => $id,'data' => $data]);
        //     } else {
        //         return redirect()->route('admin-customers-list')->with('error', __('message_lang.accessing_data_not_found'));
        //     }
        // }
        /** edit */

        /** update */
        // // public function update(Request $request, $id)
        // // {
        // //     $id = base64_decode($id);
        // //     $this->validate(request(), [
        // //             'customer_mobile' => ['required'],
        // //             'number_plate' => ['required'],
        // //         ]);

        // //     $mobile_no = $request->customer_mobile;
        // //     $number_plate = $request->number_plate;
            

        // //     $crud_data = array(
        // //             'mobile' => $mobile_no,
        // //             'updated_at' => date('Y-m-d H:i:s'),
        // //         );

        // //     $np_crud_data = array(
        // //             'mobile' => $mobile_no,
        // //             'updated_at' => date('Y-m-d H:i:s'),
        // //         );
               
        // //     $update =$this->objCustomer->updateData($crud_data, $id);
        // //     $update =$this->objNumberPlate->updateDataBycId($np_crud_data, $id);
             
        // //     if ($update) {
        // //         return redirect()->route('admin-customers-list')->with('success', __('Record updated successfully.'));
        // //     } else {
        // //         return redirect()->back()->with('error', __('Failed to update Record.'))->withInput();
        // //     }
        // // }
        // /** update */

        // /** change-status */
        // public function change_status(Request $request)
        // {
        //     if (!$request->ajax()) {
        //         exit('No direct script access allowed');
        //     }

        //     if (!empty($request->all())) {
        //         $status = $request->status;
        //         $id = $request->id;

        //         $crud_data = array(
        //                 'status' => $status,
        //                 'updated_at' => date('Y-m-d H:i:s')
        //             );
               
        //         $update =$this->objCustomer->updateData($crud_data, $id);

        //         if ($update) {
        //             echo json_encode(array("status" => "success"));
        //             exit;
        //         }else{
        //             echo json_encode(array("status" => "failed"));
        //             exit;
        //         }
                   
        //     } else {
        //         echo json_encode(array("status" => "failed"));
        //         exit;
        //     }
        // }
        // /** change-status */

        // public function  customer_detail($id){
        //     $id = base64_decode($id);
            
        //     $customer = $this->objCustomer->getDatabyId($id);
        //         // dd($order);
            
        //     if(!empty($customer))
        //     {
        //         return view("admin.views.customer.details", ['data'=>$customer, 'id' => $id]);
        //     }
        //     else
        //     {
        //          return redirect(route('admin-customers-list'));
        //     }
        // }


    }
