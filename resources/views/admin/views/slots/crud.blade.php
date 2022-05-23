@extends("admin.layouts.layout")

@if(isset($id))
    @section("title", "Update Bays")
@else
    @section("title", "Add Bays")
@endif

@section("page_style")
    <link href="{{ url('ex_plugins/dropify-master/css/dropify.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ url('assets/css/datatables.min.css') }}">
@endsection

@section("content")
      <div class="row">

        <div class="col-md-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb pl-0">
                <li class="breadcrumb-item"><a href="{{ route('admin-dashboard') }}"><i class="material-icons">home</i> Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin-slots-list') }}">Bays</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    @if(isset($id))
                        Update Bays
                    @else
                        Add Bays
                    @endif
                </li>
            </ol>
        </nav>
    </div>

         <div class="col-xl-12 col-lg-12">
          <div class="ms-panel ms-panel-fh">
            <div class="ms-panel-header">
                @if(isset($id))
                    <h6>Update Bays</h6>
                @else
                    <h6>Add Bays</h6>
                @endif
              
              
            </div>
            <div class="ms-panel-body">

            <form id="id_frm_crud_coupon" class="needs-validation clearfix" method="post"  action="@if(isset($id)) {{route('admin-update-slots',['id'=>base64_encode($id)])}} @else{{route('admin-insert-slots')}} @endif" enctype="multipart/form-data">
                    {{ csrf_field() }}
                <input type="hidden" name="previous_url" value="{{isset($previous_url)?$previous_url:''}}">
                <div class="form-row">

                    <div class="col-md-12 mb-3">
                        <label for="status">Select Lot</label>
                        <div class="input-group">
                            <select class="form-control" name="lot_id" id="lot_id">
                                 <option value=""> Select Lot </option>
                                    @if(isset($lots) && !empty($lots))
                                    @foreach($lots as $l)
                                        <option value="{{ $l->id  }}" @if(isset($data->lot_id) && !empty($data->lot_id) && $data->lot_id == $l->id ) selected @endif >{{ $l->lot_name }}</option>
                                    @endforeach
                                    @endif
                            </select>
                            @error('lot_id')
                                <div class="invalid-feedback" style="display: block;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>
                    </div>
                    @if(!isset($id))
                        <div class="col-md-12 mb-3 type-cls">
                            <label class="form-check-label" for="gender">Select Type <span class="required text-danger">*</span></label>
                            <div class="input-group">
                                <ul class="ms-list d-flex">
                                    <li class="ms-list-item pl-0">
                                        <label class="ms-checkbox-wrap" for="qty_range1">
                                        <input class="form-check-input" type="radio" name="qty_range" id="qty_range1" value="single" @if(old('qty_range') == 'single') checked @endif > <i class="ms-checkbox-check"></i>
                                        </label> <span> Single </span>
                                    </li>
                                    <li class="ms-list-item">
                                        <label class="ms-checkbox-wrap" for="qty_range2">
                                        <input class="form-check-input" type="radio" name="qty_range" id="qty_range2" value="range" @if(old('qty_range') == 'range') checked @endif> <i class="ms-checkbox-check"></i>
                                        </label> <span> Range </span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    @endif


                    <div class="col-md-12 mb-3 single-cls">
                        <label for="cat_name">Bay Number {!!_required_asterisk()!!}</label>
                        <div class="input-group">
                            <input type="number" name="slot_number" class="form-control" id="slot_number" placeholder="Bay Number" value="{{ old('slot_number', @$data->slot_number) }}">
                            @error('slot_number')
                                <div class="invalid-feedback" style="display: block;">
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                            <div class="invalid-feedback">
                                Please Enter Bay Number
                            </div>
                        </div>
                    </div>
                    @if(!isset($id))
                        <div class="col-md-12 mb-3 range-cls" style="display: none;">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label for="cat_name">Bay Range {!!_required_asterisk()!!}</label>
                                    <div class="input-group">
                                        <input type="number" name="start_number" class="form-control" id="start_number" placeholder="Start Number" value="{{ old('start_number') }}">
                                        @error('start_number')
                                            <div class="invalid-feedback" style="display: block;">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                        <div class="invalid-feedback">
                                            Please Enter Start Number
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="cat_name">&nbsp;</label>
                                    <div class="input-group">
                                        <input type="number" name="end_number" class="form-control" id="end_number" placeholder="End Number" value="{{ old('end_number') }}">
                                        @error('end_number')
                                            <div class="invalid-feedback" style="display: block;">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                        <div class="invalid-feedback">
                                            Please Enter End Number
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="col-md-12">
                        <button type="submit" class="btn btn-square btn-primary" type="submit">Save</button>
                        <a href="{{ route('admin-slots-list') }}" class="btn btn-square btn-gradient-light float-right">Cancel</a>
                    </div>

                </div>
            </form>
            </div>
          </div>

        </div>
      </div>
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
        // $("#id_frm_crud_coupon").validate({
        //     errorElement: "div", // contain the error msg in a span tag
        //     errorClass: 'invalid-feedback',
        //     errorPlacement: function (error, element) { // render error placement for each input type
        //         // debugger;  
        //         error.insertAfter(element);
        //         // for other inputs, just perform default behavior
        //     },
        //     ignore: "",
        //     // rules: {
        //     //     lot_id: {
        //     //         required: true
        //     //     },
        //     //     slot_number: {
        //     //         required: true
        //     //     }
        //     // },
        //     // messages: {
        //     //     lot_id: {
        //     //         required: '{{ __('Please select Lot') }}'
        //     //     },
        //     //     slot_number: {
        //     //         required: '{{ __('Please Enter Bay Quantity') }}'
        //     //     },
        //     // },
        //     invalidHandler: function (event, validator) { //display error alert on form submit
        //         // debugger;
        //         //successHandler1.hide();
        //         //errorHandler1.show();
        //     },
        //     highlight: function (element) {
        //         // debugger;
        //         $(element).closest('.help-block').removeClass('valid');
        //         // display OK icon
        //         $(element).closest('.form-group').removeClass('has-success').addClass('has-error').find('.symbol').removeClass('ok').addClass('required');
        //         // add the Bootstrap error class to the control group
        //     },
        //     unhighlight: function (element) { // revert the change done by hightlight
        //         // debugger;
        //         $(element).closest('.form-group').removeClass('has-error');
        //         // set error class to the control group
        //     },
        //     success: function (label, element) {
        //         // debugger;
        //         label.addClass('help-block valid');
        //         // mark the current input as valid and display OK icon
        //         $(element).closest('.form-group').removeClass('has-error').addClass('has-success').find('.symbol').removeClass('required').addClass('ok');
        //     },
        //     submitHandler: function (frmadd) {
        //         // debugger;
        //         successHandler1.show();
        //         errorHandler1.hide();
        //     }
        // });

        setTimeout(function(){ 

            var type = $('input[type=radio][name="qty_range"]:checked').val();
            if(type == 'range'){
                $('.range-cls').show();
                $('.single-cls').hide();
                $('#qty_range2').prop('checked',true);
            }else{
                $('#qty_range1').prop('checked',true);
                $('.range-cls').hide();
                $('.single-cls').show();
            }

        }, 100);

        $('input[type=radio][name="qty_range"]').change(function(){
             
            if(this.value == 'range'){
                $('.range-cls').show();
                $('.single-cls').hide();
            }else{
                $('.range-cls').hide();
                $('.single-cls').show();
            }
        })

    });


</script>
@endsection