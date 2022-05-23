@extends("admin.layouts.layout")

@section("title", "Transactions List")

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
                    <li class="breadcrumb-item active" aria-current="page">Transactions List</li>
                </ol>
            </nav>
            <div class="ms-panel">
                <div class="ms-panel-header">
                    <div class="d-flex justify-content-between">
                        <div class="align-self-center align-left">
                            <h6>Transactions</h6>
                        </div>
                            <!--<a href="{{-- route('admin-add-transaction') --}}" class="btn btn-primary flot-right">Add Transaction</a>-->
                    </div>
                </div>

               

                <div class="ms-panel-body">
                    <div class="table-responsive">
                        <table id="data-table-4" name="frm-datatable" class="table w-100 thead-primary nowrap">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Transaction Number</th>
                                    <th>Slot Number</th>
                                    <th>Mobile</th>
                                    <th>Number Plate</th>    
                                    <th>Parking Time</th>
                                    <th>Amount</th>
                                    <th>Paid Amount</th>
                                    <th>Change Amount</th>
                                    <th>Arrival Time</th>
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

                "responsive": true,
                //"aaSorting": [],
                "sorting": [[12, 'desc']],
             
                "ajax":
                {
                    "url": "{{ route('admin-transaction-list-fetch') }}",
                    "type": "POST",
                    "dataType": "json",
                    "data":
                    {
                        _token: "{{csrf_token()}}",
                        id: "{{ $id }}",
                    }
                },
                "columnDefs": [
                    {
                        "targets": [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11], //first column / numbering column
                        "orderable": false, //set not orderable
                    },
                ],
                columns: [
                    {
                        // data: 'id',
                        // name: 'id'
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        searchable:false,orderable: false,sortable:false
                    },
                    {
                        data: 'transaction_number',
                        name: 'transaction_number'
                    },
                    {
                        // data: 'slots.slot_number',
                        // name: 'slots.slot_number'
                        data: 'slot_number',
                        name: 'slot_number'
                    },
                    {
                        // data: 'customers.mobile',
                        // name: 'customers.mobile'
                        data: 'mobile',
                        name: 'mobile'
                    },
                    {
                        // data: 'number_plats.number_plate',
                        // name: 'number_plats.number_plate'
                        data: 'number_plate',
                        name: 'number_plate'
                    },
                    {
                        data: 'parking_time',
                        name: 'parking_time'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'paid_amount',
                        name: 'paid_amount'
                    },
                    {
                        data: 'change_amount',
                        name: 'change_amount'
                    },
                    {
                        data: 'arrival_time',
                        name: 'arrival_time',
                    },
                    {
                        data: 'status',
                        name: 'status',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        searchable:false,orderable: false,sortable:false
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        visible:false
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
            "url": "{!! route('admin-transaction-change-status') !!}",
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
    
       
   
    

</script>
@endsection