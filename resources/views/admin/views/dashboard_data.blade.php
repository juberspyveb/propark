@if(isset($data['edge_data_array']) && !empty($data['edge_data_array']))
<thead>
	<tr>
		<th scope="col">No</th>
		<th scope="col">Edge Name</th>
		<th scope="col">Edge Revenue</th>	
	</tr>	
</thead>
<tbody>
	<?php $i=1; ?>
	@foreach($data['edge_data_array'] as $key=>$value)
	<tr>
		<td>{{$i}}</td>
		<td>
			@if((property_exists($value,"edge_name")))
				{{$value->edge_name}}
			@else
				All
			@endif
		</td>
		<td>{{config('constants.currency')}}{{ _number_format($value->total ?? 0) }}</td>
	</tr>
	<?php $i++; ?>
	@endforeach
</tbody>
@else
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
	@if (!$data['QualityIssueOrders']->isEmpty())
		@php $i = 1; @endphp
		@foreach($data['QualityIssueOrders'] as $qualityOrders)
			<tr>
				<td>{{ $i }}</td>
				<td><a href="{{ route('admin-orders-view', base64_encode($qualityOrders->order_id)) }}">#{{ $qualityOrders->generate_code ?? '' }}</a></td>
				<td>{{ $qualityOrders->customer_name ?? '-' }}</td>
				<td>{{ $qualityOrders->order_type  }}</td>
				<td>{{config('constants.currency')}}{{ _number_format($qualityOrders->total_payable) }}</td>
				<td>
					@if($qualityOrders->order_status == 'Processing')
						<span class="badge badge-gradient-danger">{{ $qualityOrders->order_status }}</span>
					@elseif($qualityOrders->order_status == 'Proof Emailed')
						<span class="badge badge-gradient" style="background-color: blue;">{{ $qualityOrders->order_status }}</span>
					@elseif($qualityOrders->order_status == 'In Production')
						<span class="badge badge-gradient" style="background-color: orange;">{{ $qualityOrders->order_status }}</span>
					@elseif($qualityOrders->order_status == 'Quality Issue')
						<span class="badge badge-gradient" style="background-color: black;">{{ $qualityOrders->order_status }}</span>
					@elseif($qualityOrders->order_status == 'Completed')
						<span class="badge badge-gradient-success">{{ $qualityOrders->order_status }}</span>
					@else
															
					@endif
				</td>
				<td>{{$qualityOrders->order_date_time}}</td>
			</tr>
			@php $i++; @endphp
		@endforeach
	@else
			<tr>
				<td colspan="3">
					<h4 class="text-center">No Data Found ...!!!</h4>
				</td>
			</tr>
	@endif
</tbody>
@endif