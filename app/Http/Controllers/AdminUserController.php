<?php

    namespace App\Http\Controllers;
    
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Mail;
    use Maatwebsite\Excel\Facades\Excel;
    use App\Exports\AdminOrdersExport;
    use File, URL;
    use App\Models\User;
    use App\Lot;
    use App\Supervisor;
    use Datatables;
    use Session;
    use PDF;

    class AdminUserController extends Controller{
        
        protected $objUser;

        public function __construct()
        {
            $this->objUser = new User();
            $this->objSupervisors = new Supervisor();
            $this->objLot = new Lot();
        }

        /** order listing page */
        public function list(){
            return view("admin.views.users.list");
        }

        /** order listing fetch */
        public function list_fetch(Request $request){
            
            $users =  $this->objUser->getData();

            foreach($users as $row)
            {
                $data = _get_sales_transaction($row->lotId);
                $paid_comission = _get_paid_comission($row->id);
                // dd( $paid_comission);
                $row->total_sales_amount = $data['total_sales_amount'];
                if($row->total_sales_amount != 0){
                    $row->commission_amount = $data['commission_amount'] - $paid_comission->total_commision;
                }else{
                    $row->commission_amount = $data['commission_amount'];
                }
                
                $row->total_bank_amount = $data['total_bank_amount'];
            }
           
            return Datatables::of($users)
                ->addIndexColumn()
                ->editColumn("action", function($users) {

                    if($users->status == config('constants.user_status.ACTIVE')) {
                        $status = '<a class="dropdown-item badge-gradient-warning" style="background-color: blue;" href="javascript:void( 0);" onclick="change_status(this);" data-status="2" data-id="'.$users->id.'">'.config('constants.user_status.INACTIVE').'</a>';
                    }else{
                        $status = '<a class="dropdown-item badge-gradient-success" href="javascript:void( 0);" onclick="change_status(this);" data-status="1" data-id="'.$users->id.'">'.config('constants.user_status.ACTIVE').'</a>';
                    }
                    $html = '<a href="'.route('admin-edit-user',['id' => base64_encode($users->id)]).'"><i class="fas fa-pencil-alt text-success"></i></a>'.
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

                ->editColumn("lot_name", function ($users) {
                    return '<a href="'.route('admin-lot-list',['id' => base64_encode($users->lot_id)]).'">'.$users->lot_name.'</a>';
                })  
                
            
                ->editColumn("status", function ($users) {
                    if ($users->status == config('constants.user_status.ACTIVE')) {
                        return '<span class="badge badge-success">'.config('constants.user_status.ACTIVE').'</span>';
                    } elseif ($users->status == config('constants.user_status.INACTIVE')) {
                        return '<span class="badge badge-warning">'.config('constants.user_status.INACTIVE').'</span>';
                    } elseif ($users->status == config('constants.user_status.DELETED')) {
                        return '<span class="badge badge-danger">'.config('constants.user_status.DELETED').'</span>';
                    } else {
                        return '-';
                    }
                })
                 ->editColumn("image", function($data){
                    if($data->image != '' OR !empty($data->image))
                    {//dd($data->image);
                        $image = $data->image;
                            return '<img src="'.url("uploads/user/$image").'" border="0" width="40" onerror=this.onerror=null;this.src="'.url("uploads/default/100_no_img.jpg").'" />';
                        }
                        else
                        {
                            return '<img src="'.url("uploads/default/100_no_img.jpg").'" border="0" width="40" />';
                        }
                    })
                ->rawColumns(["action", "status", "image","lot_name"])
                ->make(true);
        }

        /** add */
        public function add(Request $request)
        {
            $lots = $this->objLot->getData();
            // $supervisors = $this->objSupervisors->getData();
         
            if (!empty($lots)){   
                return view("admin.views.users.crud", compact('lots'));
            }else{
                return redirect()->route('admin-users-list')->with('error', __('message_lang.accessing_data_not_found'));
            }
        }
        /** add */

        /** insert */
        public function insert(Request $request)
        {  
        // dd(base64_decode($id));
            $gender = $request->gender;
            $address = $request->address;
            $uploadFolder = public_path().'/uploads/user/';
            $newFileName = '';
            $image_name = '';
            if(!empty($request->image))
            {
                //dd($request->image)

                $file = $request->file('image');
                $fileNameWithExtension = $request->file('image')->getClientOriginalName();
                $fileName = pathinfo($fileNameWithExtension, PATHINFO_FILENAME);
                $extension = $request->file('image')->getClientOriginalExtension();
                $timeName = time()."_".$fileName;
                $newFileName = $timeName.'.'.$extension;

                if (!\File::exists($uploadFolder)) {
                    \File::makeDirectory($uploadFolder, 0777, true, true);
                }
                $file->move($uploadFolder, $newFileName);
                $image_name = $newFileName;
            }
            else
            {
                $image_name = null;
            }
            // $id = base64_decode($id);
            if (!empty($request->all())) {


                $this->validate(request(), [
                        'name' => ['required'],
                        'mobile' => 'required|unique:users,mobile',
                        'gender' => ['required'],
                        'address' => ['required'],
                        // 'firebase_id' => ['required'],
                        'lot_id' => ['required'],
                        // 'supervisor_id' => ['required'],
                        //'image' => ['string'],

                    ]);


                $crud_data = array(
                    'name' => $request->name,
                    'mobile' => $request->mobile,
                    'gender' => $request->gender,
                    'address' => $request->address, 
                    // 'firebase_id' => $request->firebase_id,
                    'lot_id' => $request->lot_id,
                    // 'supervisor_id' => $request->supervisor_id, 
                    'image' => $image_name,
                    'user_role_id' => 2,
                    'status' => config('constants.user_status.ACTIVE'),
                    'created_at' => date('Y-m-d H:i:s')
                ); 
                $insert = $this->objUser->insertData($crud_data);
            
                if ($insert > 0) {
                    // if(!empty($request->image)){
                    // $imgSize1 = $uploadFolder.'_462x346_'.$newFileName;//$timeName.'.'.$extension;
                    // //462,346
                    // $img = \Image::make($file)->fit(462, 346)->save($imgSize1);
                    // $file->move($uploadFolder, $newFileName);
                    // }
                    return redirect()->route('admin-users-list')->with('success', __('Record inserted successfully.'));
                } else {
                    return redirect()->route('admin-add-users')->with('error', __('Failed to insert record.'))->withInput();
                }
            }else{
                return redirect()->route('admin-add-users')->with('error', __('Failed to insert record.'))->withInput();
            }
        }
        /** insert */

         /** edit */
         public function edit(Request $request, $id)
         {
             $id = base64_decode($id);
             if (filter_var($id, FILTER_VALIDATE_INT)) {
                 
                 $data =  $this->objUser->getDatabyId($id);
                // dd($data);
                 $lots = $this->objLot->getData();

                 $dayData = _get_sales_transaction($data->lot_id);

                 $paid_comission = _get_paid_comission($data->id);
                 // dd( $paid_comission);
                 
                 if( $dayData['total_sales_amount'] != 0){
                    $dayData['commission_amount'] = $dayData['commission_amount'] - $paid_comission->total_commision;
                 }
                //  dd($dayData);
                //  $supervisors = $this->objSupervisors->getData();
                
                 return view("admin.views.users.crud", compact('id','data','lots','dayData'));
             } else {
                 return redirect()->route('admin-users-list')->with('error', __('message_lang.accessing_data_not_found'));
             }
         }
         /** edit */
 
         /** update */
         public function update(Request $request, $id)
         {
            $id = base64_decode($id);
            $data =  $this->objUser->getDatabyId($id);
            // dd($data);
             $gender = $request->gender;
            $address = $request->address;
            if(!empty($request->image))
            {
                //dd($request->image)
                ///dd("if");
                $file = $request->file('image');
                $fileNameWithExtension = $request->file('image')->getClientOriginalName();
                $fileName = pathinfo($fileNameWithExtension, PATHINFO_FILENAME);
                $extension = $request->file('image')->getClientOriginalExtension();
                $timeName = time()."_".$fileName;
                $newFileName = $timeName.'.'.$extension;

                $uploadFolder = public_path().'/uploads/user/';
                //dd($uploadFolder);
                if (!\File::exists($uploadFolder)) {
                    \File::makeDirectory($uploadFolder, 0777, true, true);
                }
                $file->move($uploadFolder, $newFileName);
                $image_name = $newFileName;
               // dd($newFileName);
            }
            else
            {
               //dd("else");
                 $image_name = $data->image;
            }
            // $id = base64_decode($id);
            $this->validate(request(), [
                        'name' => ['required'],
                        'mobile' => 'required|unique:users,mobile,'.$id,
                        'gender' => ['required'],
                        'address' => ['required'],
                        // 'firebase_id' => ['required'],
                        'lot_id' => ['required'],
                        // 'supervisor_id' => ['required'],
                        
            ]);

 
             $crud_data = array(
                    'name' => $request->name,
                    'mobile' => $request->mobile,
                    'gender' => $request->gender,
                    'address' => $request->address, 
                    // 'firebase_id' => $request->firebase_id,
                    'lot_id' => $request->lot_id,
                    // 'supervisor_id' => $request->supervisor_id, 
                    'image' => $image_name,
                    'user_role_id' => 2,
                    'status' => config('constants.user_status.ACTIVE'),
                    'updated_at' => date('Y-m-d H:i:s')
            ); 
                
             $update =$this->objUser->updateData($crud_data, $id);
              
             if ($update) {
                
                 return redirect()->route('admin-users-list')->with('success', __('Record updated successfully.'));
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
                // dd($request->all());
                $status = $request->status;
                $id = $request->id;

                $crud_data = array(
                        'status' => $status,
                        'updated_at' => date('Y-m-d H:i:s')
                    );
               
                $update =$this->objUser->updateData($crud_data, $id);

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
