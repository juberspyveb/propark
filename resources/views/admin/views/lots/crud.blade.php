@extends("admin.layouts.layout")

@if(isset($id))
    @section("title", "Update Lots")
@else
    @section("title", "Add Lots")
@endif

@section("page_style")
    <link href="{{ url('ex_plugins/dropify-master/css/dropify.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ url('assets/css/datatables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('assets/css/custom_table_css.css') }}">
    <style>
        .invalid-feedback, .valid-feedback {
            bottom: 40px;
        }
        </style>
@endsection

@section("content")
      <div class="row">

        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb pl-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin-dashboard') }}"><i class="material-icons">home</i> Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin-lot-list') }}">Lots</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        @if(isset($id))
                            Update Lots
                        @else
                            Add Lots
                        @endif
                    </li>
                </ol>
            </nav>
        </div>

        <div class="col-xl-12 col-lg-12">
            <div class="ms-panel ms-panel-fh">
                <div class="ms-panel-header">
                    @if(isset($id))
                        <h6>Update Lots</h6>
                    @else
                        <h6>Add Lots</h6>
                    @endif
                </div>
                <div class="ms-panel-body">

                  <form id="id_frm_crud_coupon" class="needs-validation clearfix" method="post"  action="@if(isset($id)) {{route('admin-update-lots',['id'=>base64_encode($id)])}} @else{{route('admin-insert-lot')}} @endif" enctype="multipart/form-data">
                        {{ csrf_field() }}
                    <div class="form-row">

                    <div class="col-md-12 mb-3">
                        <label for="cat_name">Lot Name {!!_required_asterisk()!!}</label>
                        
                          <input type="text" name="lot_name" class="form-control" id="lot_name" placeholder="Lot Name" value="{{ old('lot_name', @$data->lot_name) }}" required>
                          @error('lot_name')
                          <div class="invalid-feedback">
                            Please Enter Lot Name
                          </div>
                          @enderror
                        
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="cat_name">Lot Address {!!_required_asterisk()!!}</label>
                       
                          <input type="text" name="lot_address" class="form-control" id="lot_address" placeholder="Lot Address" value="{{ old('lot_address', @$data->lot_address) }}" required>
                          @error('lot_address')
                          <div class="invalid-feedback">
                            Please Enter Lot Address
                          </div>
                          @enderror
                        
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="cat_name">Lot Latitude  {!!_required_asterisk()!!}</label>
                        <!-- <div class="input-group"> -->
                          <input type="text" name="lot_latitude" class="form-control" id="lot_latitude" placeholder="Lot Latitude" value="{{ old('lot_latitude', @$data->lot_latitude) }}" required>
                          @error('lot_latitude')
                          <div class="invalid-feedback">
                            Please Enter Lot Latitude
                          </div>
                          @enderror
                        <!-- </div> -->
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="cat_name">Lot Longitude {!!_required_asterisk()!!}</label>
                        <!-- <div class="input-group"> -->
                          <input type="text" name="lot_longitude" class="form-control" id="lot_longitude" placeholder="Lot Longitude" value="{{ old('lot_longitude', @$data->lot_longitude) }}" required>
                          @error('lot_longitude')
                          <div class="invalid-feedback">
                            Please Enter Lot Longitude
                          </div>
                          @enderror
                        <!-- </div> -->
                    </div>

                    <div class="col-md-12 mb-3">
                        <label for="status">Select Supervisor {!!_required_asterisk()!!}</label>
                        <div class="input-group">
                            <select class="form-control" name="supervisor_id" id="supervisor_id">
                                <option value="0"> Select Supervisor </option>
                                    @if(isset($supervisors) && !empty($supervisors))
                                    @foreach($supervisors as $sup)
                                        <option value="{{ $sup->id  }}"  @if(isset($data->supervisor_id) && !empty($data->supervisor_id) && $data->supervisor_id == $sup->id ) selected @endif>{{ $sup->supervisor_name }}</option>
                                    @endforeach
                                    @endif                                   
                            </select>
                            @error('supervisor_id')
                                <div class="invalid-feedback" style="display: block;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="cat_name">Lot Notes</label>
                        <div class="input-group">
                          <textarea type="text" name="lot_notes" class="form-control" id="lot_notes" placeholder="Lot Notes" value="{{ old('lot_notes', @$data->lot_notes) }}" ></textarea>
                          
                        </div>
                    </div>

                    <div class="col-md-12">
                        <button type="submit" class="btn btn-square btn-primary" type="submit">Save</button>
                        <a href="{{ route('admin-lot-list') }}" class="btn btn-square btn-gradient-light float-right">Cancel</a>
                    </div>

                    </div>
                  </form>

                </div>
            </div>

        </div>


      </div>
    @if(isset($id))
        <div class="row">

            <div class="col-md-8">
                <div class="ms-panel">
                    <div class="ms-panel-header">
                        <div class="d-flex justify-content-between">
                            <div class="align-self-center align-left">
                                <h6>Bays</h6>
                            </div>
                        </div>
                    </div>
                   

                    <div class="ms-panel-body">
                        <div class="table-responsive">
                            <table id="data-table-4" name="frm-datatable" class="table w-100 thead-primary nowrap">
                                <thead>
                                    <tr>
                                        <th>Bay Number</th>
                                        <th>Available</th>
                                        <th>Status</th>
                                        <th>{{ __('message_lang.LBL_ACTIONS') }}</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4"></div>
        </div>
    @endif
@endsection

@section("page_vendors")
    <script src="{{ url('assets/js/datatables.min.js') }}"></script>
    <script src="{{ asset('ex_plugins/jquery-validation-1.19.1/dist/jquery.validate.js') }}"></script>
    <script src="{{ url('ex_plugins/dropify-master/js/dropify.min.js') }}"></script>
    <script src="{{ url('assets/js/promise.min.js') }}"></script>
    <script src="{{ url('assets/js/sweetalert2.min.js') }}"></script>
@endsection

@section("page_script")
<script type="text/javascript">
    $(document).ready(function(){


        // debugger;
         $("#id_frm_crud_coupon").validate({
            errorElement: "div", // contain the error msg in a span tag
            errorClass: 'invalid-feedback',
            errorPlacement: function (error, element) { // render error placement for each input type
                // debugger;  
                error.insertAfter(element);
                // for other inputs, just perform default behavior
            },
            ignore: "",
            rules: {
                lot_name: {
                    required: true
                },
                lot_address: {
                    required: true
                },
                lot_latitude: {
                    required: true
                },
                lot_longitude: {
                    required: true
                }
                // supervisor_id: {
                //     required: true
                // },
            },
            messages: {
                lot_name: {
                    required: '{{ __('Plese Enter Lot Name') }}'
                },
                lot_address: {
                    required: '{{ __('Plese Enter Lot Address') }}'
                },
                lot_latitude: {
                    required: '{{ __('Plese Enter Lot Latitude') }}'
                },
                lot_longitude: {
                    required: '{{ __('Plese Enter Lot Longitude') }}'
                },
                // supervisor_id: {
                //     required: '{{ __('Plese Select Supervisor') }}'
                // },
            },
            invalidHandler: function (event, validator) { //display error alert on form submit
                // debugger;
                //successHandler1.hide();
                //errorHandler1.show();
            },
            highlight: function (element) {
                // debugger;
                $(element).closest('.help-block').removeClass('valid');
                // display OK icon
                $(element).closest('.form-group').removeClass('has-success').addClass('has-error').find('.symbol').removeClass('ok').addClass('required');
                // add the Bootstrap error class to the control group
            },
            unhighlight: function (element) { // revert the change done by hightlight
                // debugger;
                $(element).closest('.form-group').removeClass('has-error');
                // set error class to the control group
            },
            success: function (label, element) {
                // debugger;
                label.addClass('help-block valid');
                // mark the current input as valid and display OK icon
                $(element).closest('.form-group').removeClass('has-error').addClass('has-success').find('.symbol').removeClass('required').addClass('ok');
            },
            submitHandler: function (frmadd) {
                // debugger;
                successHandler1.show();
                errorHandler1.hide();
            }
        });
    });


    var list_table_one;
    $(document).ready(function() {
        @if(isset($id))
            if($('#data-table-4').length > 0)
            {
                list_table_one = $('#data-table-4').DataTable(
                {
                    processing: true,
                    serverSide: true,
                    "responsive": true,
                    "aaSorting": [],
                    
                    "ajax":
                    {
                        "url": "{{ route('list-lot-bays') }}",
                        "type": "POST",
                        "dataType": "json",
                        "data":
                        {
                            _token: "{{csrf_token()}}",
                            id : "{{$id}}"
                        }
                    },
                    "columnDefs": [
                        {
                            "targets": [0, 3], //first column / numbering column
                            "orderable": false, //set not orderable
                        },
                    ],
                    columns: [
                        
                        {
                            data: 'slot_number',
                            name: 'slot_number'
                        },
                        {
                            data: 'is_available',
                            name: 'is_available'
                        },
                        {
                            data: 'status',
                            name: 'status',
                            sortable:false
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            sortable:false
                        }
                    ]
                });
            }
        @endif
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
                    "url": "{!! route('admin-slots-change-status') !!}",
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