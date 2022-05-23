@extends("admin.layouts.layout")

@section("title", "Attendants List")

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
                    <li class="breadcrumb-item active" aria-current="page">Attendants List</li>
                </ol>
            </nav>
            <div class="ms-panel">
                <div class="ms-panel-header">
                    <div class="d-flex justify-content-between">
                        <div class="align-self-center align-left">
                            <h6>Attendants</h6>
                        </div>
                            <a href="{{route('admin-add-user')}}" class="btn btn-primary flot-right">Add Attendants</a>
                    </div>
                </div>

               

                <div class="ms-panel-body">
                    <div class="table-responsive">
                        <table id="data-table-4" name="frm-datatable" class="table w-100 thead-primary nowrap">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Mobile</th>
                                    <!-- <th>Address</th>
                                    <th>Gender</th>
                                    <th>Firebase Id</th> -->
                                    <th>Lot Name</th>
                                    <th>Day sales</th>
                                    <th>Cash to Bank</th>
                                    <th>Commission Due</th>
                                    <th>Supervisor Name</th>
                                    <th>Image</th>
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
                "aaSorting": [],
                
                // "scrollX": true,
                // "scrollY": true,
               
                "ajax":
                {
                    "url": "{{ route('admin-users-list-fetch') }}",
                    "type": "POST",
                    "dataType": "json",
                    "data":
                    {
                        _token: "{{csrf_token()}}"
                    }
                },
                "columnDefs": [
                    {
                        "targets": [0, 7], //first column / numbering column
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
                        data: 'name',
                        name: 'name',
                        orderable: false,
                        sortable:false
                    },
                    {
                        data: 'mobile',
                        name: 'mobile',
                        orderable: false,
                        sortable:false
                    },
                    {
                        data: 'lot_name',
                        name: 'lot_name',
                        orderable: false,
                        sortable:false
                    },
                   
                    {
                        data: 'total_sales_amount',
                        name: 'total_sales_amount',
                        // class:'text-success',
                        orderable: false,
                        sortable:false
                    },
                    {
                        data: 'total_bank_amount',
                        name: 'total_bank_amount',
                        // class:'text-danger',
                        orderable: false,
                        sortable:false
                    },
                    {
                        data: 'commission_amount',
                        name: 'commission_amount',
                        orderable: false,
                        sortable:false
                    },
                    {
                        data: 'supervisor_name',
                        name: 'supervisor_name',
                        orderable: false,
                        sortable:false
                    },
                    {
                        data: 'image',
                        name: 'image',
                        orderable: false,
                        sortable:false
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        sortable:false
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

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
            }).then((result) => {
                
            if(result.value){
                $.ajax({
                    "url": "{!! route('admin-users-change-status') !!}",
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
        })
    }

    

</script>
@endsection