	<script type='text/javascript'>
	    /* <![CDATA[ */
		    var wpcf7 = {
		        "apiSettings": {
		            "root": "https:\/\/www.housesigns.wales\/wp-json\/contact-form-7\/v1",
		            "namespace": "contact-form-7\/v1"
		        }
		    };
	    /* ]]> */
    </script>
    <script type='text/javascript' src="{{ asset('front/js/wp-embed.min.js') }}"></script>
    
    <script type="text/javascript">
		;(function($) {
		    $('.swipebox').swipebox();
		})(jQuery);
	</script>
<script type="text/javascript">
        jQuery.validator.addMethod("noSpace", function(value, element) { 
        return value == '' || value.trim().length != 0;  
        }, "No space please and don't leave it empty");
        $("#decline_form").validate({
				errorElement: "div",
				errorClass: 'invalid-feedback',
				errorPlacement: function (error, element){
					error.insertAfter(element);
				},
				ignore: "",
				rules:{
					your_message:{
						required: true,
                        noSpace: true,
					},
				},
				messages:{
					your_message:{
						required: 'Please enter decline reason'
					},
				},
				invalidHandler: function (event, validator){
				},
				highlight: function (element){
					$(element).closest('.help-block').removeClass('valid');
					$(element).closest('.form-group').removeClass('has-success').addClass('has-error').find('.symbol').removeClass('ok').addClass('required');
				},
				unhighlight: function (element){
					$(element).closest('.form-group').removeClass('has-error');
				},
				success: function (label, element){
					label.addClass('help-block valid');
					$(element).closest('.form-group').removeClass('has-error').addClass('has-success').find('.symbol').removeClass('required').addClass('ok');
				},
				submitHandler: function (frmadd, event){
					successHandler1.show();
					errorHandler1.hide();
				}
			});
</script>
	<script>
	function openNav() {
		document.getElementById("mySidenav").style.width = "100%";
	}

	function closeNav() {
		document.getElementById("mySidenav").style.width = "0";
	}

	function openGalleryNav() {
		document.getElementById("gallerySidenav").style.width = "100%";
	}

	function closeGalleryNav() {
		document.getElementById("gallerySidenav").style.width = "0";
	}

	</script>
    {{--<script type="text/javascript" src="{{ url('assets/js/promise.min.js') }}"></script>--}}
    <script type="text/javascript" src="{{ url('assets/js/sweetalert2.min.js') }}"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.6.9/angular.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.5.8/angular-route.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/angular.js/1.5.8/angular-resource.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.6.9/angular-sanitize.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.16.0/moment.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment-range/2.2.0/moment-range.min.js"></script>
    <script type='text/javascript' src='{{ asset("front/js/custom/main.js".'?t='.time()) }}'></script>
    @yield('page_script')

    @yield('script')
    <script>

        @if (\Session::has('success'))

            Swal.fire({
                type: 'success',
                //text: "You won't be able to revert this!",
                html: ('{{ \Session::get('success') }}').replace(/&lt;br&gt;/g, '<br/>'),
                showConfirmButton: true
                //timer: timer
            }).then(function() {
                // window.location = url;
            });

        @endif
         @if (\Session::has('error'))
            Swal.fire({
                type: 'error',
                //text: "You won't be able to revert this!",
                html: ('{{ \Session::get('error') }}').replace(/&lt;br&gt;/g, '<br/>'),
                showConfirmButton: true
                //timer: timer
            }).then(function() {
                // window.location = url;
            });
        @endif
    </script>