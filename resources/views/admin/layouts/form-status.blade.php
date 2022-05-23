@if (session('success'))
    <div class="ms-content-wrapper" style="padding-bottom: 0px;">
        <div class="row">
            <div class="col-md-12">
            	<div class="alert alert-success" role="alert" style="margin-bottom: 0px;">
            		<i class="flaticon-tick-inside-circle"></i>{{ session('success') }}
            	</div>
            </div>
        </div>
    </div>
@endif

@if (session('error'))
    <div class="ms-content-wrapper" style="padding-bottom: 0px;">
        <div class="row">
            <div class="col-md-12">
            	<div class="alert alert-danger" role="alert" style="margin-bottom: 0px;">
            		<i class="flaticon-alert-1"></i>{{ session('error') }}
            	</div>
            </div>
        </div>
    </div>
@endif

@if (session('errors') && count($errors) > 0)
<div class="ms-content-wrapper" style="padding-bottom: 0px;">
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-danger" role="alert" style="padding-bottom: 0px; margin-bottom: 0px;">
                <h6 style="text-align: center; color: #f9423c;"><strong>Whoops! There were some problems with your input.</strong></h6>
                <div style="display: flex; align-items: center;">
                    <i class="flaticon-alert-1"></i>
                    <ul>
                        @foreach ($errors->all() as $error)
                        	<li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endif