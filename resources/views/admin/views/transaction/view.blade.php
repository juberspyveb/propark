@extends("admin.layouts.layout")

@section("title", "Transaction View")

@section("page_style")

@endsection


@section("content")

    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pl-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin-dashboard') }}"><i class="fas fa-home"></i> Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Transaction Details</li>
                </ol>
            </nav>
            <div class="ms-panel" id="printArea">
                <div class="ms-panel-header header-mini">
                    <div class="d-flex justify-content-between">
                        <h6>Transaction Details</h6>
                    </div>
                </div>
                <div class="ms-panel-body">
                    <!-- Invoice To -->
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <table class="table ms-profile-information">
                                <tbody>
                                    <tr>
                                        <th scope="row">Transaction Id</th>
                                        <td>{{ $data->transaction_number }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Transaction Status</th>
                                        <td>
                                            @if($data->status == config('constants.transactions.IN-PROCESS')) 
                                                <span class="badge badge-warning">{{ $data->status }}</span>
                                            @elseif($data->status == config('constants.transactions.TIME-EXTENDED')) 
                                                <span class="badge badge-warning">{{ $data->status }}</span>
                                            @elseif($data->status == config('constants.transactions.UNPAID'))
                                                <span class="badge badge-danger">{{ $data->status }}</span>
                                            @elseif($data->status == config('constants.transactions.COMPLETE')) 
                                                <span class="badge badge-success">{{ $data->status }}</span>
                                            @elseif($data->status == config('constants.transactions.CLOSE')) 
                                                <span class="badge badge-success">{{ $data->status }}</span>
                                            @elseif($data->status == config('constants.transactions.INFRINGEMENT'))
                                                <span class="badge badge-danger">{{ $data->status }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Date & Time</th>
                                        <td>{{ $data->created_at }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Bay number</th>
                                        <td>{{ $data->slot_number }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Lot number</th>
                                        <td>{{ $data->lot_name }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Attendant</th>
                                        <td>{{ $data->mobile }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Licence plate</th>
                                        <td>{{ $data->number_plate }}</td>
                                    </tr>
                                </tbody>
                            </table>                        
                        </div>
                        <div class="col-md-6">
                            <table class="table ms-profile-information">
                                <tbody>
                                <tr>
                                    <th scope="row">Time Arrived</th>
                                    <td>{{ $data->arrival_time }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Time of release</th>
                                    <td>{{ $data->updated_at }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">GPS Location</th>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th scope="row">Parking Time</th>
                                    <td>{{ $data->parking_time }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Transaction Value</th>
                                    <td>{{ $data->amount }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Change given</th>
                                    <td>{{ $data->change_amount }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Cash tendered</th>
                                    <td>{{ $data->paid_amount }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Invoice Table -->
                </div>
            </div>
        </div>
    </div>
@endsection

@section("page_vendors")
@endsection

@section("page_script")
    <script>

 
    </script>
@endsection