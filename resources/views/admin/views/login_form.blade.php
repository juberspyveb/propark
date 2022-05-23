@extends("admin.layouts.auth_layout")

@section("title", "Admin Login")

@section("content")
    <main class="body-content">
        <div class="ms-content-wrapper ms-auth">
            <div class="ms-auth-container" style="background: #007db4;">
                <div class="ms-auth-col">
                    <div class="ms-auth-bg" style="background-size: auto; " ></div>
                </div>
                <div class="ms-auth-col" style="background: #fff;">
                    <div class="ms-auth-form">
                        <form id="id_frm_login" class="needs-validation" method="post" action="{{ route('checklogin') }}">
                            {{ csrf_field() }}
                            
                            @include("admin.layouts.auth-form-status")
                            <div class="mb-3">
                                <label for="email">Email Address</label>
                                <div class="input-group">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Email Address">
                                    @error('email')
                                        <div class="invalid-feedback" style="display: block;">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-2">
                                <label for="password">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                                    @error('password')
                                        <div class="invalid-feedback" style="display: block;">
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group">
                                <!-- <label class="ms-checkbox-wrap">
                                <input class="form-check-input" type="checkbox" value="">
                                <i class="ms-checkbox-check"></i>
                                </label>
                                <span> Remember Password </span> -->
                                {{--<label class="d-block mt-3"><a href="#" class="btn-link" data-toggle="modal" data-target="#modal-12">Forgot Password?</a></label>--}}
                            </div>
                            <button class="btn btn-primary mt-4 d-block w-100" type="submit">Sign In</button>
                            <!-- <p class="mb-0 mt-3 text-center">Don't have an account? <a class="btn-link" href="default-register.html">Create Account</a> </p> -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Forgot Password Modal -->
        <div class="modal fade" id="modal-12" tabindex="-1" role="dialog" aria-labelledby="modal-12">
            <div class="modal-dialog modal-dialog-centered modal-min" role="document">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <i class="flaticon-secure-shield d-block"></i>
                        <h1>Forgot Password?</h1>
                        <p> Enter your email to recover your password </p>
                        <form method="post">
                            <div class="ms-form-group has-icon">
                                <input type="text" placeholder="Email Address" class="form-control" name="forgot-password" value="">
                                <i class="material-icons">email</i>
                            </div>
                            <button type="submit" class="btn btn-primary shadow-none">Reset Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script type="text/javascript">
        $(document).ready(function(){
            $("#id_frm_login").validate({
                errorElement: "div", // contain the error msg in a span tag
                errorClass: 'invalid-feedback',
                errorPlacement: function (error, element) { // render error placement for each input type
                    // debugger;
                    error.insertAfter(element);
                    // for other inputs, just perform default behavior
                },
                ignore: "",
                rules: {
                    email: {
                        required: true,
                        email: true,
                        normalizer: function(value) {
                            this.value = $.trim(value);
                            return this.value;
                        }
                    },
                    password: {
                        required: true,
                        minlength: 7
                    },
                },
                messages: {
                    email: {
                        required: '{{ __('message_lang.PLEASE_ENTER_EMAIL') }}',
                        email: '{{ __('message_lang.PLEASE_ENTER_VALID_EMAIL') }}'
                    },
                    password: {
                        required: '{{ __('message_lang.PLEASE_ENTER_PASSWORD') }}',
                        minlength: '{{ __('message_lang.PLEASE_ENTER_NUMERIC_PASSWORD') }}'
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
                }
                // submitHandler: function (frmadd) {
                //     // debugger;
                //     successHandler1.show();
                //     errorHandler1.hide();
                // }
            });
        });
    </script>
@endsection