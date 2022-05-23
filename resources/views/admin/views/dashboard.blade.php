@extends("admin.layouts.layout")

@section("title", "Dashboard")

@section("page_style")
	<link href="{{ url('assets/css/morris.css') }}" rel="stylesheet">
	<style>
		.ms-stats-grid{
			flex: 0 0 20%;
		}
	</style>
@endsection

@section("content")
	<div class="row">
		<div class="col-md-12">
			<h1 class="db-header-title">Welcome, {{ auth()->guard("admin")->user()->first_name }} {{ auth()->guard("admin")->user()->last_name }}</h1>
		</div>
		<!--  -->
		<!-- REVENUE -->
			<div class="col-xl-12 col-md-12">
				<div class="ms-panel">
					<div class="ms-panel-header">
						
						<div class="d-flex justify-content-between">
							<div class="ms-header-text" style="float:left;">
								<h6>Revenue</h6>
							</div>
						</div>
					</div>
					<div class="ms-panel-body p-0">
						<div class="ms-quick-stats">
							<div class="ms-stats-grid">
								<i class="far fa-clock"></i>
								<p class="ms-text-dark" id="earning_today">{{ config('constants.currency')}}{{ _number_format($data['today_earning']->total ?? 0 )}} </p>
								<span>Today</span>
							</div>
							<div class="ms-stats-grid">
								<i class="far fa-calendar-minus"></i>
								<p class="ms-text-dark" id="earning_yesterday">{{ config('constants.currency')}}{{ _number_format($data['yester_earning']->total ?? 0) }}</p>
								<span>Yesterday</span>
							</div>
							<div class="ms-stats-grid">
								<i class="far fa-calendar"></i>
								<p class="ms-text-dark" id="earning_last_week">{{ config('constants.currency')}}{{ _number_format($data['week_earning']->total ?? 0 )}}</p>
								<span>Last 7 Days</span>
							</div>
							<div class="ms-stats-grid">
								<i class="far fa-calendar-check"></i>
								<p class="ms-text-dark" id="earning_last_month">{{ config('constants.currency')}}{{ _number_format($data['month_earning']->total ?? 0 )}}</p>
								<span>This Month</span>
							</div>
							<div class="ms-stats-grid">
								<i class="far fa-calendar-alt"></i>
								<p class="ms-text-dark" id="earning_last_year">{{ config('constants.currency')}}{{ _number_format($data['year_earning']->total ?? 0 )}}</p>
								<span>This Year</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		<!-- REVENUE -->
		
		<!-- Recent Orders -->
			<div class="col-xl-12 col-md-12">
				<div class="ms-panel">
					<div class="ms-panel-header">
						<div class="d-flex justify-content-between">
							<div class="align-self-center align-left">
								<h6>Recent Orders</h6>
							</div>
							<a href="{{ route('admin-orders-list') }}" class="btn btn-primary">View All</a>
						</div>
					</div>
					<div class="ms-panel-body">
						<div class="table-responsive">
							<table class="table table-hover">
								<thead>
									<tr>
										<th scope="col">No</th>
										<th scope="col">Code</th>
										<th scope="col">Customer Name</th>
										<th scope="col">Order Type</th>
										
											<th scope="col">Order Amount</th>
										
										<th scope="col">Status</th>
										<th scope="col">Date & Time</th>	
									</tr>
								</thead>
								<tbody>
									<!--  -->
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		<!-- Recent Orders -->
		
			<!-- Date Range Filter -->
				<div class="col-xl-12 col-md-12">
					<div class="ms-panel">
						<div class="ms-panel-header">
							<div class="d-flex justify-content-between">
								<div class="align-self-center align-right">
									<h6>Data Filter</h6>
								</div>

									<div class="col-md-4 offset-3">
										From: <input type="date" class="form-control" id="fromDatepicker" name="from_datepicker"  value="">
									</div>
									<div class="col-md-4">
										To: <input type="date" class="form-control" id="toDatepicker" name="to_datepicker"  value="">
									</div>
									<!-- <div class="row" >
					                    <div class="col-sm-6" style="margin-top: 10px; ">
					                        From: <input type="date" class="form-control" id="fromDatepicker" name="from_datepicker"  value="">
					                    </div>
					                    <div class="col-sm-4" style="margin-top: 10px; margin-left: 20px;">
					                        To: <input type="date" class="form-control" id="toDatepicker" name="to_datepicker"  value="">
					                    </div>
			               			</div> -->
							</div>
						</div>
					</div>
			    </div>
		<!-- Date Range Filter -->
		<!-- counter -->
			<div class="col-xl-6 col-lg-6 col-md-6">
				<div class="ms-card ms-widget has-graph-full-width ms-infographics-widget ">
					<span class="ms-chart-label btn-primary totalOrders"><i class="material-icons"></i></span>
					<div class="ms-card-body media p-3">
						<div class="media-body">
							<span class="black-text"><strong>Total Orders</strong></span>	
						</div>
					</div>
				</div>
			</div>
			<div class="col-xl-6 col-lg-6 col-md-6">
				<div class="ms-card ms-widget has-graph-full-width ms-infographics-widget ">
					<span class="ms-chart-label btn-primary totalVoucherOrders"><i class="material-icons"></i></span>
					<div class="ms-card-body media p-3">
						<div class="media-body">
							<span class="black-text"><strong>Total Voucher Orders</strong></span>	
						</div>
					</div>
				</div>
			</div>
			<div class="col-xl-6 col-lg-6 col-md-6">
				<div class="ms-card ms-widget has-graph-full-width ms-infographics-widget ">
					<span class="ms-chart-label btn-primary totalAmount"><i class="material-icons"></i> </span>
					<div class="ms-card-body media p-3">
						<div class="media-body">
							<span class="black-text"><strong>Total Revenue</strong></span>	
						</div>
					</div>
				</div>
			</div>

		<!-- counter -->

		<!-- Quality Issue Orders -->
				<div class="col-xl-12 col-md-12">
					<div class="ms-panel">
						<div class="ms-panel-header">
							<div class="d-flex justify-content-between">
								<div class="align-self-center align-left">
									<h6>Quality Issue Orders</h6>
								</div>
							</div>
						</div>
						<div class="ms-panel-body">
							<div class="table-responsive">
								<table class="table table-hover" id="quality_issue_table">

								</table>
							</div>
						</div>
					</div>
			    </div>
		<!-- Quality Issue Orders -->
		<!-- Edge Revenue -->
				<div class="col-xl-12 col-md-12">
					<div class="ms-panel">
						<div class="ms-panel-header">
							<div class="d-flex justify-content-between">
								<div class="align-self-center align-left">
									<h6>EDGE REVENUE</h6>
								</div>
							</div>
						</div>
						<div class="ms-panel-body">
							<div class="table-responsive">
								<table class="table table-hover" id="edge_revenue_table">
									
								</table>
							</div>
						</div>
					</div>
			    </div>
		<!-- Edge Revenue -->
		
	</div>
	
@endsection

@section("page_vendors")
	<script src="{{url('assets/js/raphael.min.js')}}"></script>
	<script src="{{url('assets/js/morris.min.js')}}"></script>
@endsection

@section("page_script")
	<script>
	
	 window.onload = filterDate;
		var fromDatepicker = document.getElementById('fromDatepicker');
    	var toDatepicker = document.getElementById('toDatepicker');

    	fromDatepicker.addEventListener('change', function() {
	        if (fromDatepicker.value){
	            toDatepicker.min = fromDatepicker.value;
	        }
	        filterDate();
	    }, false);

	    toDatepicker.addEventListener('change', function() {
	        if (toDatepicker.value){
	            fromDatepicker.max = toDatepicker.value;
	        }
	        filterDate();
	    }, false);

		function filterDate()
		{
			$.ajax(
			{
				url: "{{ route('admin-dashboard-count-ajax') }}",
				type: "POST",
				data:
				{
					fromDatepicker: $("#fromDatepicker").val(),
					toDatepicker: $("#toDatepicker").val(),
				},
				dataType: "JSON",
				success: function (data)
				{
					$('#quality_issue_table').html(data.qualityHtml);
					$('#edge_revenue_table').html(data.edgeDataHtml);
					$('.totalOrders').html('<i class="material-icons"></i>' + data.total_orders);
					$('.totalVoucherOrders').html('<i class="material-icons"></i>' + data.totalVoucherOrders);
					$('.totalAmount').html('<i class="material-icons"></i>' + data.total_amount);
				},
				error: function ()
				{
				}
			});
		}
	</script>
@endsection