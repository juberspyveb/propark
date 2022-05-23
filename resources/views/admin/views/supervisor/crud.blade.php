@extends("admin.layouts.layout")

@if(isset($id))
    @section("title", "Update Supervisor")
@else
    @section("title", "Add Supervisor")
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
                <li class="breadcrumb-item"><a href="{{ route('admin-supervisor-list') }}">Supervisors</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    @if(isset($id))
                        Update Supervisor
                    @else
                        Add Supervisor
                    @endif
                </li>
            </ol>
        </nav>
    </div>

         <div class="col-xl-12 col-lg-12">
          <div class="ms-panel ms-panel-fh">
            <div class="ms-panel-header">
                @if(isset($id))
                    <h6>Update Supervisor</h6>
                @else
                    <h6>Add Supervisor</h6>
                @endif
            </div>
            <div class="ms-panel-body">

              <form id="id_frm_crud_coupon" class="needs-validation clearfix" method="post"  action="@if(isset($id)) {{route('admin-update-supervisor',['id'=>base64_encode($id)])}} @else{{route('admin-insert-supervisor')}} @endif" enctype="multipart/form-data">
                    {{ csrf_field() }}
                <div class="form-row">

                  <div class="col-md-12 mb-3">
                    <label for="cat_name">Supervisor Name {!!_required_asterisk()!!}</label>
                    <div class="input-group">
                      <input type="text" name="supervisor_name" class="form-control" id="supervisor_name" placeholder="Supervisor Name" value="{{ old('supervisor_name', @$data->supervisor_name) }}" required>
                      <div class="invalid-feedback">
                        Please Enter Supervisor Name
                      </div>
                    </div>
                  </div>

                  <div class="col-md-12 mb-3">
                    <label for="cat_name">Supervisor Mobile {!!_required_asterisk()!!}</label>
                    <div class="input-group">
                      <input type="number" name="supervisor_mobile" class="form-control" id="supervisor_mobile" placeholder="Supervisor Mobile" value="{{ old('supervisor_mobile', @$data->supervisor_mobile) }}" required>
                      <div class="invalid-feedback">
                        Please Enter Supervisor Mobile
                      </div>
                    </div>
                  </div>

                    <div class="col-md-12">
                        <button type="submit" class="btn btn-square btn-primary" type="submit">Save</button>
                        <a href="{{ route('admin-supervisor-list') }}" class="btn btn-square btn-gradient-light float-right">Cancel</a>
                    </div>

                </div>
              </form>

            </div>
          </div>

        </div>
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
                supervisor_name: {
                    required: true
                },
                supervisor_mobile: {
                    required: true
                },
            },
            messages: {
                supervisor_name: {
                    required: '{{ __('Plese Enter Supervisor Name') }}'
                },
                supervisor_mobile: {
                    required:  '{{ __('Plese Enter Supervisor Mobile') }}'
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