<?php
    
namespace App\Http\Controllers;
    
use Illuminate\Http\Request;
use App\User, App\UserGovernmentId, App\TransactionSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Datatables;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductsExport;
use App\Product;
use App\Category;
use Image;
use File;
use URL;

    
class ProductController extends Controller
{
    public function __construct()
    {
        $this->ObjProduct = new Product(); 
        $this->ObjCategory = new Category(); 
    }    
    public function list()
    {
        return view("admin.views.product.list");
    }
    /** list */
    public function lists(Request $request)
    {
        $data = $this->ObjProduct->getAllProductData();
        if(!empty($data))
        {
            foreach($data as $d)
            {
                if(isset($d->category_ids) && $d->category_ids != null)
                {
                    $category_ids = explode(',',$d->category_ids);
                    $categories = '';
                    foreach($category_ids as $ct)
                    {
                        $category = $this->ObjCategory->getCategoryById($ct);
                        if($category)
                        {
                            $categories .= $category->cat_name.', ';
                        }
                    }
                    $categories = rtrim($categories, ", ");                        
                    $d->category_ids = $categories;
                }
            }
        }
        return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn("action", function($data) {
                        return '<a href="'.route('admin-product-view', ['id' => base64_encode($data->id)]).'"><i class="fas fa-eye text-success"></i></a> &nbsp; '.'<a href="'.route('admin-product-edit', ['id' => base64_encode($data->id)]).'"><i class="fas fa-pencil-alt text-primary"></i></a> &nbsp; '.
                            '<div class="dropdown" style="display: inline;">'.
                            '<a href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="fas fa-bars text-secondary"></i></a>'.
                            '<ul class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: top, left; top: 20px; left: -142px;">'.
                            '<li class="ms-dropdown-list">'.
                            '<a class="dropdown-item badge-gradient-success" href="javascript:void(0);" onclick="change_status(this);" data-status="'.config('constants.product_status.ACTIVE').'" data-id="'.$data->id.'">'.config('constants.product_status.ACTIVE').'</a>'.
                            '<a class="dropdown-item badge-gradient-warning" href="javascript:void(0);" onclick="change_status(this);" data-status="'.config('constants.product_status.INACTIVE').'" data-id="'.$data->id.'">'.config('constants.product_status.INACTIVE').'</a>'.
                            '<a class="dropdown-item badge-gradient-danger" href="javascript:void(0);" onclick="change_status(this);" data-status="'.config('constants.product_status.DELETED').'" data-id="'.$data->id.'">'.config('constants.product_status.DELETED').'</a>'.
                            '</li>'.
                        '</ul>'.
                    '</div>';
                })
                ->editColumn("status", function($data) 
                {
                    if($data->status == config('constants.product_status.ACTIVE'))
                    {
                            return '<span class="badge badge-gradient-success">'.config('constants.product_status.ACTIVE').'</span>';
                    }
                    else if($data->status == config('constants.product_status.INACTIVE'))
                    {
                        return '<span class="badge badge-gradient-warning">'.config('constants.product_status.INACTIVE').'</span>';
                    }
                    else if($data->status == config('constants.product_status.DELETED'))
                    {
                        return '<span class="badge badge-gradient-danger">'.config('constants.product_status.DELETED').'</span>';   
                    }
                    else
                    {
                        return '-';
                    }
                })
                ->editColumn("image", function($data){
                    if($data->image != '' OR !empty($data->image))
                    {
                        $image = $data->image;
                            return '<img src="'.url("uploads/product/$image").'" border="0" width="40" onerror=this.onerror=null;this.src="'.url("uploads/default/100_no_img.jpg").'" />';
                        }
                        else
                        {
                            return '<img src="'.url("uploads/default/100_no_img.jpg").'" border="0" width="40" />';
                        }
                    })
                    ->rawColumns(['action', 'status', 'image'])
                    ->make(true);
            }
        /** lists */

        /** add */
        public function add(){
            $category = DB::table('category')->get();                
            return view('admin.views.product.crud', ['category' => $category]);
        }
        /** add */
    
        /** insert */
        public function insert(Request $request)
        {
            // dd($request->all());
            $this->validate(request(), [
                'name' => ['required', 'string', 'max:255'],
                'price' => ['required','numeric'],
                'size' => ['required','max:255'],
                'finish' => ['required','max:255'],
                'colour' => ['required', 'max:255'],
                'text_type' => ['required', 'max:255'],
            ]);
        
            $name = ucfirst($request->name);
            $slug = _generateSeoURL($name);
            // $category_ids = isset($request->category_ids) ? implode(',', $request->category_ids) : '';
            $price = $request->price;
            $size = $request->size;
            $finish = $request->finish;
            $colour = $request->colour;
            $description = $request->description;
            $textType = $request->text_type;
            if(!empty($request->image))
            {
                /*$file = $request->file('image');
                 $filenameWithExtension = $request->file('image')->getClientOriginalName();
                $filename = pathinfo($filenameWithExtension, PATHINFO_FILENAME);
                $filenameToStore = time()."_".$filename.'.'.$extension;
                $extension = $request->file('image')->getClientOriginalExtension();
                # 462x346 Resize Image
                    $image_resize = Image::make($file->getRealPath());              
                    $image_resize->resize(462,346);
                    $_462x346 = '_462x346_'.$filenameToStore;
                    $image_resize->save(public_path('uploads/product/' .$_462x346));
                # 462x346 Resize Image 
                # 369x213 Resize Image 
                    $image_resize = Image::make($file->getRealPath());              
                    $image_resize->resize(369,213);
                    $_369x213 = '_369x213_'.$filenameToStore;
                    $image_resize->save(public_path('uploads/product/' .$_369x213));
                # 369x213 Resize Image 
                $filenameWithExtension = $request->file('image')->getClientOriginalName();
                $filename = pathinfo($filenameWithExtension, PATHINFO_FILENAME);
                $folder_to_upload = public_path().'/uploads/product/';

                if (!File::exists($folder_to_upload)) 
                {
                    File::makeDirectory($folder_to_upload, 0777, true, true);
                }
                    $file->move("uploads/product/", $filenameToStore);
                    $url = URL::to('/')."/uploads/product/".$filenameToStore;
                    $image_name = $filenameToStore;*/

                $file = $request->file('image');
                $fileNameWithExtension = $request->file('image')->getClientOriginalName();
                $fileName = pathinfo($fileNameWithExtension, PATHINFO_FILENAME);
                $extension = $request->file('image')->getClientOriginalExtension();
                $timeName = time()."_".$fileName;
                $newFileName = $timeName.'.'.$extension;

                $uploadFolder = public_path().'/uploads/product/';

                if (!\File::exists($uploadFolder)) {
                    \File::makeDirectory($uploadFolder, 0777, true, true);
                }
                
                $image_name = $newFileName;

            }
            else
            {
                $image_name = null;
            }

            $crud = array(
                'name' => $name,
                'product_slug'=> $slug,
                'price' => _number_format($price),
                'size' => $size,
                'finish' => $finish,
                'colour' => $colour,
                'text_type' => $textType,
                'description' => $description,
                'image' => $image_name,
                'status' => 'Active',
                'is_deleted' => 'N',
                'character_limit' => 20,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => auth()->guard("admin")->user()->id,
            );

            $last_id = $this->ObjProduct->insertProductData($crud);
    
            if($last_id > 0)
            {
                // dd($last_id);
                $updateSlug = $slug."-".$last_id;
                $crud = array(
                        'product_slug'=>$updateSlug,
                    );
                $update = DB::table('product')->where('id', $last_id)->limit(1)->update($crud);
                if(!empty($request->image)){
                    $imgSize1 = $uploadFolder.'_462x346_'.$newFileName;//$timeName.'.'.$extension;
                    //462,346
                    $img = \Image::make($file)->fit(462, 346)->save($imgSize1);
                    $file->move($uploadFolder, $newFileName);
                }
                return redirect()->route('admin-product-list')->with('success', __('message_lang.PRODUCT_ADDED_SUCCESSFULLY'));
            }else{
                return redirect()->back()->with('error',  __('message_lang.FAILED_TO_ADD_PRODUCT'))->withInput();
            }
        }
        /** insert */
        /** edit */
        public function edit(Request $request, $id)
        {
            $id = base64_decode($id);
            $data = $this->ObjProduct->getProductDataById($id);
            $category = $this->ObjCategory->getAllCategoryData();
            
            return view('admin.views.product.crud', ['data' => $data, 'id' => $id, 'category' => $category]);
        }
        /** edit */

        /** update */
        public function update(Request $request, $id)
        {        
            $this->validate(request(), [
                'name' => ['required', 'string', 'max:255'],
                'price' => ['required','numeric'],
                'size' => ['required','max:255'],
                'finish' => ['required','max:255'],
                'colour' => ['required', 'max:255'],
                'text_type' => ['required', 'max:255'],
            ]);
                
            $name = $request->name;
            // $category_ids = isset($request->category_ids) ? implode(',', $request->category_ids) : '';
            $quantity = $request->quantity;
            $price = $request->price;
            $size = $request->size;
            $finish = $request->finish;
            $colour = $request->colour;
            $description = $request->description;
            $textType = $request->text_type;

            if(!empty($request->image))
            {
               /* $file = $request->file('image');
                $filenameWithExtension = $request->file('image')->getClientOriginalName();
                $filename = pathinfo($filenameWithExtension, PATHINFO_FILENAME);
                $extension = $request->file('image')->getClientOriginalExtension();
                $filenameToStore = time()."_".$filename.'.'.$extension;
                # 462x346 Resize Image 
                    $image_resize = Image::make($file->getRealPath());              
                    $image_resize->resize(462,346);
                    $_462x346 = '_462x346_'.$filenameToStore;
                    $image_resize->save(public_path('uploads/product/' .$_462x346));
                # 462x346 Resize Image 
                #369x213 Resize Image 
                    $image_resize = Image::make($file->getRealPath());              
                    $image_resize->resize(369,213);
                    $_369x213 = '_369x213_'.$filenameToStore;
                    $image_resize->save(public_path('uploads/product/' .$_369x213));
                # 369x213 Resize Image 
                
                $folder_to_upload = public_path().'/uploads/product/';
                if (!File::exists($folder_to_upload)) 
                {
                    File::makeDirectory($folder_to_upload, 0777, true, true);
                }
                    $file->move("uploads/product/", $filenameToStore);
                    $url = URL::to('/')."/uploads/product/".$filenameToStore;
                    $image_name = $filenameToStore;*/

                $file = $request->file('image');
                $fileNameWithExtension = $request->file('image')->getClientOriginalName();
                $fileName = pathinfo($fileNameWithExtension, PATHINFO_FILENAME);
                $extension = $request->file('image')->getClientOriginalExtension();
                $timeName = time()."_".$fileName;
                $newFileName = $timeName.'.'.$extension;

                $uploadFolder = public_path().'/uploads/product/';

                if (!\File::exists($uploadFolder)) {
                    \File::makeDirectory($uploadFolder, 0777, true, true);
                }
                
                $image_name = $newFileName;
            }
            else
            {
                $image_name = $request->hidden_image;
            }
        
            $crud = [
                        'name' => $name,
                        'price' => _number_format($price),
                        'size' => $size,
                        'finish' => $finish,
                        'colour' => $colour,
                        'text_type' => $textType,
                        'currency_id' => '1',
                        'description' => $description,
                        'image' => $image_name,
                        'updated_at' => date('Y-m-d H:i:s'),
                        'updated_by' => auth()->guard("admin")->user()->id,
                    ];
    
            $update = DB::table('product')->where('id', $id)->limit(1)->update($crud);
    
            if($update){
                if(!empty($request->image)){
                    $imgSize1 = $uploadFolder.'_462x346_'.$newFileName;//$timeName.'.'.$extension;
                    //462,346
                    $img = \Image::make($file)->fit(462, 346)->save($imgSize1);
                    $file->move($uploadFolder, $newFileName);
                }
                return redirect()->route('admin-product-list')->with('success', 'Record updated successfully.');
            }else{
                return redirect()->back()->with('error', 'Record update failed.')->withInput();
            }
        }
        /** update */

        /** remove-image */
        public function remove_image(Request $request)
        {
            if(!$request->ajax())
            {
                exit('No direct script access allowed');
            }
        
            if(!empty($request->all()))
            {
                $encoded_id = $request->encoded_id;        
                $id = base64_decode($encoded_id);
                $data = \DB::table('product')->find($id);
                if($data)
                {
                    if($data->image != '')
                    {
                        $update = \DB::table('product')->where('id', $id)->limit(1)->update(['image' => '']);
        
                        if($update)
                        {
                            @unlink(public_path('uploads/product/'.$data->image));
                            @unlink(public_path('uploads/product/'.'_462x346_'.$data->image));
                            @unlink(public_path('uploads/product/'.'_369x213_'.$data->image));
                               
                            echo json_encode(['code' => '200']); exit;
                        }
                        else
                        {
                            echo json_encode(['code' => '201']); exit;
                        }
                    }
                    else
                    {
                        echo json_encode(['code' => '200']); exit;
                    }
                }
                else
                {
                    echo json_encode(['code' => '201']); exit;
                }
            }
            else
            {
                echo json_encode(['code' => '201']); exit;
            }
        }
        /** remove-image */
        
        /** change-status */
        public function change_status(Request $request)
        {
            if(!$request->ajax())
            {
                exit('No direct script access allowed');
            }
        
            if(!empty($request->all()))
            {
                $status = $request->status;
                $id = $request->id;
    
                $crud = array(
                    'status' => $status,
                    'updated_at' => date('Y-m-d H:i:s')
                );
                $update = DB::table('product')->where('id', $id)->update($crud);
        
                if($update)
                {
                    echo json_encode(array("status" => "success"));
                    exit;
                }
                else
                {
                    echo json_encode(array("status" => "failed"));
                    exit;
                }
            }
            else
            {
                echo json_encode(array("status" => "failed"));
                exit;
            }
        }
        /** change-status */
    /** view */
            public function view(Request $request, $id){
                $encoded_id = $id;
                $id = base64_decode($encoded_id);
                $product_url = url('uploads/product');
                
                $data = DB::table('product as pd')
                                ->select('pd.id', 'pd.name', 'pd.price', 'pd.size', 'pd.finish', 'pd.colour', 'pd.description','pd.status', 'pd.category_ids',
                                    \DB::Raw("CONCAT(".'"'.$product_url.'"'.", '/', ".'pd.image'.") as image"),
                                    )
                                ->where(['pd.id' => $id])
                                ->first();
                                
                if(!empty($data)){
                   
                    
                    if(isset($data->category_ids) && $data->category_ids != null){
                        $category_ids = explode(',',$data->category_ids);
                        $categories = '';
                        foreach($category_ids as $ct){
                            $category = \DB::table('category')->select('cat_name as name')->where(['id' => $ct])->first();
                            if($category){
                                $categories .= $category->name.', ';
                            }
                        }
                        $categories = rtrim($categories, ", ");
                        
                        $data->category_ids = $categories;
                    }
                }
                
                return view("admin.views.product.view", ["data" => $data, "id" => $id]);  
            }
        /** view */

}