@extends("admin.layouts.layout")


@section("title", "View Email Template")

@section("page_style")
<link href="{{ url('ex_plugins/dropify-master/css/dropify.min.css') }}" rel="stylesheet">
<link href="{{ url('assets/css/sweetalert2.min.css') }}" rel="stylesheet">
@endsection

@section("content")
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pl-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin-dashboard') }}"><i class="fas fa-home"></i> Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('email-list') }}">Email Template</a></li>
                    <li class="breadcrumb-item active" aria-current="page">View Email Template</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-12">
            <div class="ms-panel ms-panel-fh">
                <div class="ms-panel-header">
                    <div class="d-flex justify-content-between">
                        <div class="align-self-center align-left">
                            <h6>View Email Template</h6>
                        </div>
                        <a href="{{ route('email-list') }}" class="btn btn-square btn-gradient-dark">Back</a>
                    </div>
                </div>
                <div class="ms-panel-body">
                    <div class="col-md-12">
                        <?php echo _header_footer($email->logo_url, $email->email_title, $email->email_html, $email->footer_text); ?> 
                    </div>
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
