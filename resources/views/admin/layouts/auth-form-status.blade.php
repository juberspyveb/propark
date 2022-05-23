@if (session('success'))
	<div class="alert alert-success" role="alert">
		<i class="flaticon-tick-inside-circle"></i>{{ session('success') }}
	</div>
@endif

@if (session('error'))
	<div class="alert alert-danger" role="alert">
		<i class="flaticon-alert-1"></i>{{ session('error') }}
	</div>
@endif

@if (session('errors') && count($errors) > 0)
    <div class="alert alert-danger" role="alert">
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
@endif