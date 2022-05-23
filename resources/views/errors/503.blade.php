@extends("front.layouts.layout")
@section("title", "Error | LV Website")
@section("page_style")
@endsection
@section("content")
    <div id="main">
        <div class="wrapper">
            <div class="error-bg"></div>
            <!-- <div class="illustration"></div> -->
            <div id="error503">
                <h1 class="main-title">Error</h1>



                <h3 class="tag-line">503 | <?= __($exception->getMessage() ?: 'Service Unavailable') ?> </h3>
                <p> We will right back in a 15 min</p></p>
                <div class="item email" style="cursor: pointer;">
                    <!-- <a class="email-login" href="#"><span class="link"></span></a> -->
                    <span class="visit">Visit Homepage</span>
                </div>
            </div>


        </div>
    </div>
    <style>

    </style>
@endsection
@section("page_vendors")
@endsection
@section("page_script")
    <script type="text/javascript">
        $(".visit").click(function()
        {
            window.location.href = "{{ route('home') }}";
        });
    </script>
@endsection
{{--
<div class='content'>
<div class="alert alert-success alert-white rounded">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <div class="icon"><i class="fa fa-check"></i></div>
    <strong>Success!</strong> Changes has been saved successfully!
</div>
<div class="alert alert-info alert-white rounded">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <div class="icon"><i class="fa fa-info-circle"></i></div>
    <strong>Info!</strong> You have 3 new messages in your inbox.
</div>
<div class="alert alert-warning alert-white rounded">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <div class="icon"><i class="fa fa-warning"></i></div>
    <strong>Alert!</strong> Don't forget to save your data.
</div>
<div class="alert alert-danger alert-white rounded">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <div class="icon"><i class="fa fa-times-circle"></i></div>
    <strong>Error!</strong> The server is not responding, try again later.
</div>
</div>
--}}