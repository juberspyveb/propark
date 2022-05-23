@extends("admin.layouts.layout")

@section("title", "Customers Details")

@section("page_style")
    <link rel="stylesheet" type="text/css" href="{{ url('assets/css/datatables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('assets/css/custom_table_css.css') }}">
@endsection

@section("content")
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb" class="new">
                <ol class="breadcrumb pl-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin-dashboard') }}"><i class="fas fa-home"></i> Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Customers Detail List</li>
                </ol>
            </nav>
            <!-- <div class="ms-panel">
                <div class="ms-panel-header">
                    <div class="d-flex justify-content-between">
                        <div class="align-self-center align-left">
                            <h6>Customers Detail</h6>
                        </div>
                            {{--<a href="{{route('admin-add-customer')}}" class="btn btn-primary flot-right">Add Customer</a>--}}
                    </div>
                </div>
                <div class="ms-panel-body">
                    <div class="table-responsive">
                        <table id="data-table-4" name="frm-datatable" class="table w-100 thead-primary nowrap">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Customer Mobile</th>
                                    <th>Total Transactions</th>
                                    <th>Last Transactions</th>
                                    <th>Balance Owing</th>
                                    <th>Status</th>
                                    <th>{{ __('message_lang.LBL_ACTIONS') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div> -->
            <div class="ms-panel">
                <div class="ms-panel-header">
                    <div class="d-flex justify-content-between">
                        <div class="align-self-center align-left">
                            <h6>Vehicles</h6>
                        </div>
                    </div>
                </div>
                <div class="ms-panel-body">
                    <div class="table-responsive">
                        <table id="data-table-5" name="frm-datatable" class="table w-100 thead-primary nowrap">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Number Plate</th>
                                    <th>Status</th>
                                    <th>{{ __('message_lang.LBL_ACTIONS') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("page_vendors")
     <script src="{{ url('assets/js/datatables.min.js') }}"></script>
    {{--<script src="{{ url('ex_plugins/datatableV2/datatables.min.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ url('ex_plugins/datatableV2/datatables.min.css') }}">--}}
@endsection

@section("page_script")
    {{--<script src="{{ url('ex_plugins/datatableV2/dataTables.checkboxes.min.js') }}"></script>
    <script src="{{ url('ex_plugins/datatableV2/dataTables.buttons.min.js') }}"></script>
    <script src="{{ url('ex_plugins/datatableV2/buttons.html5.min.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ url('ex_plugins/datatableV2/dataTables.checkboxes.css') }}">--}}
    <script type="text/javascript">
    var list_table_one;
    $(document).ready(function() {
        if($('#data-table-4').length > 0)
        {
            list_table_one = $('#data-table-4').DataTable(
            {
                processing: true,
                serverSide: true,

                // "pageLength": 10,
                // "iDisplayLength": 10,
                "responsive": true,
                "aaSorting": [],
                // "order": [], //Initial no order.
                //     "aLengthMenu": [
                //     [5, 10, 25, 50, 100, -1],
                //     [5, 10, 25, 50, 100, "All"]
                // ],
                
                "scrollX": true,
                "scrollY": true,
                
                // "scrollCollapse": false,
                // scrollCollapse: true,
                
                "ajax":
                {
                    "url": "{{ route('admin-customers-list-fetch', $id) }}",
                    "type": "POST",
                    "dataType": "json",
                    "data":
                    {
                        _token: "{{csrf_token()}}"
                    }
                },
                "columnDefs": [
                    {
                        "targets": [0, 2], //first column / numbering column
                        "orderable": false, //set not orderable
                    },
                ],
                columns: [
                    {
                        // data: 'id',
                        // name: 'id'
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        sortable:false
                    },
                    
                    {
                        data: 'mobile',
                        name: 'mobile'
                    },
                    {
                        data: 'total_transaction',
                        name: 'total_transaction'
                    },
                    {
                        data: 'last_transaction',
                        name: 'last_transaction'
                    },
                    {
                        data: 'balance_owing',
                        name: 'balance_owing'
                    },
                    {
                        data: 'status',
                        name: 'status',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        sortable:false
                    },
                ]
            });
        }
    });

    var list_table_two;
    $(document).ready(function() {
        if($('#data-table-5').length > 0)
        {
            list_table_two = $('#data-table-5').DataTable(
            {
                processing: true,
                serverSide: true,

                "responsive": true,
                "aaSorting": [],
                "scrollX": true,
                "scrollY": true,
                
                "ajax":
                {
                    "url": "{{ route('admin-vehicle-list-fetch', $id) }}",
                    "type": "POST",
                    "dataType": "json",
                    "data":
                    {
                        _token: "{{csrf_token()}}"
                    }
                },
                "columnDefs": [
                    {
                        "targets": [0, 2], //first column / numbering column
                        "orderable": false, //set not orderable
                    },
                ],
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        sortable:false
                    },
                    
                    {
                        data: 'number_plate',
                        name: 'number_plate'
                    },
                    {
                        data: 'status',
                        name: 'status',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        sortable:false
                    },
                ]
            });
        }
    });
       
    function change_status(a_object)
    {
        var status = $(a_object).data("status");
        var id = $(a_object).data('id');
        $.ajax({
            "url": "{!! route('admin-customer-change-status') !!}",
            "dataType": "json",
            "type": "POST",
            "data":{
                id: id,
                status: status,
                _token: "{{csrf_token()}}"
            },
            success: function (response){
                if (response.status == "success"){
                    list_table_one.ajax.reload(null, false); //reload datatable ajax
                    toastr.success('{{ __('message_lang.STATUS_CHANGED_SUCCESSFULLY') }}', 'Success');
                } else {
                    toastr.error('{{ __('message_lang.FAILED_TO_UPDATE_STATUS') }}', 'Error');
                }
            }
        });
    }

    function change_vehicle_status(a_object)
    {
        var status = $(a_object).data("status");
        var id = $(a_object).data('id');
        $.ajax({
            "url": "{!! route('admin-vehicle-change-status') !!}",
            "dataType": "json",
            "type": "POST",
            "data":{
                id: id,
                status: status,
                _token: "{{csrf_token()}}"
            },
            success: function (response){
                if (response.status == "success"){
                    list_table_two.ajax.reload(null, false); //reload datatable ajax
                    toastr.success('{{ __('message_lang.STATUS_CHANGED_SUCCESSFULLY') }}', 'Success');
                } else {
                    toastr.error('{{ __('message_lang.FAILED_TO_UPDATE_STATUS') }}', 'Error');
                }
            }
        });
    }
</script>
@endsection