@extends("admin.layouts.layout")
@section("title", "Update Email Template")

@section("page_style")
    <link href="{{ url('ex_plugins/dropify-master/css/dropify.min.css') }}" rel="stylesheet">
    <link href="{{ url('assets/css/sweetalert2.min.css') }}" rel="stylesheet">
    
    <style>
        #cke_summary-ckeditor{
            width: 100%;
        }
    </style>
@endsection

@section("content")
<div class="row">
    <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb pl-0">
                <li class="breadcrumb-item"><a href="{{ route('admin-dashboard') }}"><i class="fas fa-home"></i> Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('email-list') }}">Email Template</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    
                    Update Email Template
                    
                </li>
            </ol>
        </nav>
    </div>
    <div class="col-md-12">
        <div class="ms-panel ms-panel-fh">
            <div class="ms-panel-header">
                
                <h6>Update Email Template</h6>

            </div>
            <div class="ms-panel-body">
                <form id="id_frm_crud_user" class="needs-validation clearfix" method="post"  action="{{ route('admin-update-email-post', $user_id) }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-row">

                  <div class="col-md-12 mb-3">
                    <label for="name">Email Key</label>
                    <div class="input-group">
                      <input type="text" name="name" class="form-control" id="name" placeholder="Name" value="{{ old('name', @$user->email_key) }}" readonly>
                    </div>
                  </div>

                  <div class="col-md-12 mb-3">
                    <label for="email_title">Email Title</label>
                    <div class="input-group">
                      <input type="text" name="email_title" class="form-control" id="email_title" placeholder="Enter Email Title" value="{{ old('email_title', @$user->email_title) }}" required>
                      <div class="invalid-feedback">
                        Email Title
                      </div>
                    </div>
                  </div>

                  <div class="col-md-12 mb-3">
                    <label for="email_subject">Email Subject</label>
                      <div class="input-group">
                        <input type="text" name="email_subject" id="email_subject" value="{{ old('email_subject', @$user->email_subject) }}" class="form-control" placeholder="Email Subject" required>
                          <div class="invalid-feedback">
                            Email Subject
                          </div>
                      </div>
                  </div>


                  <div class="col-lg-12 mb-3">
                    <label for="email_html">Email HTML</label>
                      <div class="input-group">
                        <textarea class="form-control" id="summary-ckeditor" name="email_html">{{$user->email_html}}</textarea>
                          <div class="invalid-feedback">
                            Email HTML
                          </div>
                      </div>
                  </div>

                  
                

                    <div class="col-md-12">
                        <button type="submit" class="btn btn-square btn-gradient-success" type="submit">Save</button>
                        <a href="{{ route('email-list') }}" class="btn btn-square btn-gradient-light float-right">Cancel</a>
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
    <script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
    <script src="{{ asset('ex_plugins/jquery-validation-1.19.1/dist/jquery.validate.js') }}"></script>
    <script src="{{ url('ex_plugins/dropify-master/js/dropify.min.js') }}"></script>
    <script src="{{ url('assets/js/promise.min.js') }}"></script>
    <script src="{{ url('assets/js/sweetalert2.min.js') }}"></script>
@endsection

@section("page_script")

<script>
CKEDITOR.replace( 'summary-ckeditor' );
</script>
<script type="text/javascript">
    $(document).ready(function(){

        // debugger;
        $("#id_frm_crud_user").validate({
            errorElement: "div", // contain the error msg in a span tag
            errorClass: 'invalid-feedback',
            errorPlacement: function (error, element) { // render error placement for each input type
                // debugger;
                error.insertAfter(element);
                // for other inputs, just perform default behavior
            },
            ignore: "",
            rules: {
                cat_name: {
                    required: true
                },
               
                cat_status: {
                    required: true,
                }
            },
            messages: {
                cat_name: {
                    required: '{{ __('Plese Enter Category Name') }}'
                },
                cat_status: {
                    required: '{{ __('Plese Select Category Status') }}'
                }
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
