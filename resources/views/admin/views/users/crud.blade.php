@extends("admin.layouts.layout")

@if(isset($id))
    @section("title", "Update User")
@else
    @section("title", "Add User")
@endif

@section("page_style")
    <link href="{{ url('ex_plugins/dropify-master/css/dropify.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ url('assets/css/datatables.min.css') }}">
@endsection

@section("content")
      <div class="row">

        <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb pl-0">
                <li class="breadcrumb-item"><a href="{{ route('admin-dashboard') }}"><i class="material-icons">home</i> Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin-users-list') }}">Users</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    @if(isset($id))
                        Update User
                    @else
                        Add User
                    @endif
                </li>
            </ol>
        </nav>
    </div>

         <div class="col-xl-12 col-lg-12">
          <div class="ms-panel ms-panel-fh">
            <div class="ms-panel-header">
                @if(isset($id))
                    <h6>Update User</h6>
                @else
                    <h6>Add User</h6>
                @endif
            </div>
            <div class="ms-panel-body">

              <form id="id_frm_crud_coupon" class="needs-validation clearfix" method="post"  action="@if(isset($id)) {{route('admin-update-user',['id'=>base64_encode($id)])}} @else{{route('admin-insert-user')}} @endif" enctype="multipart/form-data">
                    {{ csrf_field() }}
                <div class="form-row">

                    <div class="col-md-12 mb-3">
                        <label for="cat_name">Full Name {!!_required_asterisk()!!}</label>
                        <div class="input-group">
                        <input type="text" name="name" class="form-control" id="user_name" placeholder="User Name" value="{{ old('name', @$data->name) }}" required>
                        <div class="invalid-feedback">
                            Please Enter Full Name
                        </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="cat_name">Phone No. {!!_required_asterisk()!!}</label>
                        <div class="input-group">
                        <input type="text" name="mobile" class="form-control" id="phone_no" placeholder="Phone Number" value="{{ old('mobile', @$data->mobile) }}" required>
                        <div class="invalid-feedback">
                            Please Enter Phone Number
                        </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-check-label" for="gender">Gender {!!_required_asterisk()!!}</label>
                        <div class="input-group">
                            <ul class="ms-list d-flex">
                                <li class="ms-list-item pl-0">
                                    <label class="ms-checkbox-wrap">
                                    <input class="form-check-input" type="radio" name="gender" value="female" @if(isset($id)){{$data->gender == 'female' ? 'checked' : '' }}@endif > <i class="ms-checkbox-check"></i>
                                    </label> <span> Female </span>
                                </li>
                                <li class="ms-list-item">
                                    <label class="ms-checkbox-wrap">
                                    <input class="form-check-input" type="radio" name="gender"  value="male"  @if(isset($id)){{$data->gender == 'male' ? 'checked' : '' }}@endif > <i class="ms-checkbox-check"></i>
                                    </label> <span> Male </span>
                                </li>
                            </ul>
                            <div class="invalid-feedback">
                                    Please Select Gender 
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="address">Address {!!_required_asterisk()!!}</label>
                        <div class="input-group">
                            <textarea  name="address" id="address" class="form-control" value="" placeholder="address" required>{{ old('address', @$data->address) }}</textarea>
                            <div class="invalid-feedback">
                                Please Enter Address  
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-md-12 mb-3">
                        <label for="cat_name">Firebase Id {!!_required_asterisk()!!}</label>
                        <div class="input-group">
                        <input type="text" name="firebase_id" class="form-control" id="firebase_id" placeholder="Firebase key" value="{{ old('firebase_id', @$data->firebase_id) }}" required>
                        <div class="invalid-feedback">
                            Please Enter Firebase Id
                        </div>
                        </div>
                    </div> -->

                    <div class="col-md-12 mb-3">
                        <label for="status">Select Lot {!!_required_asterisk()!!}</label>
                        <div class="input-group">
                            <select class="form-control" name="lot_id" id="lot_id">
                                @if(isset($lots) && !empty($lots))
                                    @foreach($lots as $l)
                                        <option value="{{ $l->id  }}" @if(isset($data->lot_id) && !empty($data->lot_id) && $data->lot_id == $l->id ) selected @endif >{{ $l->lot_name }}</option>
                                    @endforeach
                                    @endif
                            </select>
                            @error('lot_id')
                                <div class="invalid-feedback" style="display: block;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>
                    </div>
                   {{-- <div class="col-md-12 mb-3">
                        <label for="status">Select Supervisor {!!_required_asterisk()!!}</label>
                        <div class="input-group">
                            <select class="form-control" name="supervisor_id" id="supervisor_id">
                                <option value="0"> Select Supervisor </option>
                                    @if(isset($supervisors) && !empty($supervisors))
                                    @foreach($supervisors as $sup)
                                        <option value="{{ $sup->id  }}"  @if(isset($data->supervisor_id) && !empty($data->supervisor_id) && $data->supervisor_id == $sup->id ) selected @endif>{{ $sup->supervisor_name }}</option>
                                    @endforeach
                                    @endif                                   
                            </select>
                            @error('supervisor_id')
                                <div class="invalid-feedback" style="display: block;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>
                    </div> --}}

                    <div class="col-md-12 mb-3">
                            @if(isset($data) && $data->image != '')
                                 @if(File::exists(public_path('uploads/user/'.$data->image)))
                                    @php $image_url = url('uploads/user/'.$data->image); @endphp
                                @else
                                    @php $image_url = url('uploads/default/default.jpg'); @endphp
                                @endif
                            @else
                                @php $image_url = ''; @endphp
                            @endif
                            <label for="image">Select Image {!!_required_asterisk()!!}</label>
                            <div class="input-group">
                                <input type="file" class="form-control dropify" id="image" name="image" data-default-file="{{ $image_url }}" value="{{ old('image', @$data->image) }}" >

                                @error('image')
                                    <div class="invalid-feedback" style="display: block;">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror

                                <!-- Send Hidden Image (Old Name) -->
                                @if(isset($data->image) && $data->image  !='')
                                    <input type="hidden" name="hidden_image" value="{{ old('image', @$data->image) }}"></input>
                                @else
                                    <input type="hidden" name="hidden_image" value=""></input>
                                @endif
                                <!-- Send Hidden Image (Old Name) -->
                            </div>
                        </div>

                    <div class="col-md-12">
                        <button type="submit" class="btn btn-square btn-primary" type="submit">Save</button>
                        <a href="{{ route('admin-users-list') }}" class="btn btn-square btn-gradient-light float-right">Cancel</a>
                    </div>

                </div>
              </form>

            </div>
          </div>

        </div>
        @if(isset($id))
        <div class="col-xl-12 col-lg-12">
          <div class="ms-panel ms-panel-fh">
            <div class="ms-panel-header">
                <h6> Add Balance</h6>
            </div>
            <div class="ms-panel-body">
           
              <form id="id_frm_crud_coupon" class="needs-validation clearfix" method="post"  action="@if(isset($id)) {{route('admin-insert-cash',['id'=>base64_encode($id)])}} @endif" enctype="multipart/form-data">
                    {{ csrf_field() }}
                <div class="form-row">

                  <div class="col-md-3 text-center">
                    <label for="cat_name">Day sales</label>
                    <div class="text-center">
                    {{ $dayData['total_sales_amount'] }}
                    </div>
                  </div>

                  <div class="col-md-3 text-center">
                    <label for="cat_name">Commission Due</label>
                    <div class="text-center">
                    {{ $dayData['commission_amount'] }}
                    </div>
                  </div>

                  <div class="col-md-3 text-center">
                    <label for="cat_name">Cash to Bank</label>
                    <div class="text-center">
                    {{ $dayData['total_bank_amount'] }}
                    </div>
                  </div>

                  <div class="col-md-3 mb-3">
                    <label for="cat_name">Amount{!!_required_asterisk()!!}</label>
                    <div class="input-group">
                      <input type="number"  name="amount" step="0.01" class="form-control" min="0" required>
                    </div>
                  </div>
                   </div>
                    <div class="row justify-content-center">
                        <button type="submit" class="btn btn-square btn-primary" type="submit">Add Cash</button>
                    </div>

                <!-- </div> -->
              </form>
            
            </div>
          </div>

        </div>
        @endif
      </div>
   @endsection

@section("page_vendors")
    <script src="{{ url('assets/js/datatables.min.js') }}"></script>
    <script src="{{ asset('ex_plugins/jquery-validation-1.19.1/dist/jquery.validate.js') }}"></script>
    <script src="{{ url('ex_plugins/dropify-master/js/dropify.min.js') }}"></script>
    <script src="{{ url('assets/js/promise.min.js') }}"></script>
    <script src="{{ url('assets/js/sweetalert2.min.js') }}"></script>
@endsection

@section("page_script")
<script type="text/javascript">
    $(document).ready(function(){


        // debugger;
        $("#id_frm_crud_coupon").validate({
            errorElement: "div", // contain the error msg in a span tag
            errorClass: 'invalid-feedback',
            errorPlacement: function (error, element) { // render error placement for each input type
                // debugger;  
                error.insertAfter(element);
                // for other inputs, just perform default behavior
            },
            ignore: "",
            rules: {
                lot_name: {
                    required: true
                },
                name: {
                    required: true
                },
                gender: {
                    required: true
                },
                address: {
                    required: true
                },
                mobile: {
                    required: true
                },
                // firebase_id: {
                //     required: true
                // },
                lot_id: {
                    required: true
                },
                
            },
            messages: {
                lot_name: {
                    required: '{{ __('Plese Enter Lot Name') }}'
                },
                name: {
                    required: '{{ __('Plese Enter Name') }}'
                },
                gender: {
                    required: '{{ __('Plese Enter Gender') }}'
                },
                address: {
                    required: '{{ __('Plese Enter Address') }}'
                },
                mobile: {
                    required: '{{ __('Plese Enter Mobile Number') }}'
                },
                // firebase_id: {
                //     required: '{{ __('Plese Enter Firebase Id') }}'
                // },
                lot_id: {
                    required: '{{ __('Plese Select Lot Id') }}'
                },
                

            },
            invalidHandler: function (event, validator) { //display error alert on form submit
                // debugger;
                //successHandler1.hide();
                //errorHandler1.show();
            },
            highlight: function (element) {
                // debugger;
                $(element).closest('.help-block').removeClass('valid');
                // display OK icon
                $(element).closest('.form-group').removeClass('has-success').addClass('has-error').find('.symbol').removeClass('ok').addClass('required');
                // add the Bootstrap error class to the control group
            },
            unhighlight: function (element) { // revert the change done by hightlight
                // debugger;
                $(element).closest('.form-group').removeClass('has-error');
                // set error class to the control group
            },
            success: function (label, element) {
                // debugger;
                label.addClass('help-block valid');
                // mark the current input as valid and display OK icon
                $(element).closest('.form-group').removeClass('has-error').addClass('has-success').find('.symbol').removeClass('required').addClass('ok');
            },
            submitHandler: function (frmadd) {
                // debugger;
                successHandler1.show();
                errorHandler1.hide();
            }
        });
    });


</script>
@endsection