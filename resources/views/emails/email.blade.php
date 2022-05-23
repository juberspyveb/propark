@if($email_type == 'SIGN_UP')
	<p><strong>
	Hi {{ $email }},
	</strong></p>
	<p>Your verification code is {{ $random_digit }}</p>
	<p></p>
	<p>&nbsp;</p>

@elseif($email_type == 'FORGOT_PASSWORD')
	<p><strong>
	Hi {{ $var_greeting_name }},
	</strong></p>
	<p></p>
	<p><a href="{{ $reset_link }}" style="background-color: #4caf50; color: white; padding: 14px 25px; text-align: center; text-decoration: none;display: inline-block;" onMouseOver="this.style.color='background-color: #008000'" target="_blank">Reset Password</a></p>
	<p>&nbsp;</p>

@elseif($email_type == 'RESEND_CODE')
	<p><strong>
	Hi {{ $email }},
	</strong></p>
	<p>Your verification code is {{ $random_digit }}</p>
	<p></p>
	<p>&nbsp;</p>

@elseif($email_type == 'RECEIPT_EMAIL')
<p><strong>Hello {{ $var_greeting_name }},</strong></p>
<p><strong>Receipt email address: {{ $receipt_email_address }},</strong></p>
<p><strong>Delivery address: {{ $receipt_address }},</strong></p>
<p>Your order has been placed successfully.</p>
<!--<p>Following is your order data.</p>-->
<!DOCTYPE html>
<html>
	<head>
		<style>
			table {
				border-collapse: collapse;
				width: 100%;
			}
			.cls_order_receipt td, .cls_order_receipt th {
				border: 1px solid #dddddd;
				text-align: left;
				padding: 8px;
			}
		</style>
	</head>
	<body>
		@if($payment_info->cart_object != 'null' && $payment_info->cart_object != '')
			<h2>LV Website</h2>
			<table class="cls_order_receipt">
				<tr>
					<th>#</th>
					<th>Description</th>
					<th>Price</th>
				</tr>
			@php
	            $cart_object = json_decode($payment_info->cart_object);
	            // _pre($cart_object);
	            $cart_product_description = '';
	            $cart_product_price = '';
			@endphp
			@foreach($cart_object as $key => $value)
				@php
					$cart_product_description .= 'Size: '.@$value->step1->sign_size.'<br>';
	                $cart_product_description .= 'Edge: '.@$value->step21->sign_edge_style.'<br>';
	                $cart_product_description .= 'Line 1: '.@$value->step3->line1.'<br>';
	                $cart_product_description .= 'Line 2: '.@$value->step3->line2.'<br>';
	                $cart_product_description .= 'Line 3: '.@$value->step3->line3.'<br>';
	                $cart_product_description .= 'Line 3: '.@$value->step3->line3.'<br>';
	                $cart_product_description .= 'Font: '.@$value->step3->font_name.'<br>';
	                $cart_product_description .= 'Colour: '.@$value->step4->color.'<br>';
	                $cart_product_description .= 'Fixing Type: '.@$value->step51->fixing_type_name.'<br>';

	                $sign_price_tot = _number_format(@$value->total_summary->sign_price_tot);
	                $gold_price_tot = _number_format(@$value->total_summary->gold_price_tot);
	                $fixing_type_price = _number_format(@$value->total_summary->fixing_type_price);
	                $vat = _number_format(@$value->total_summary->vat);

	                $cart_product_price .= 'Quantity: '.@$value->total_summary->qty.'<br>';
	                $cart_product_price .= 'Sign: '.$sign_price_tot.'<br>';
	                $cart_product_price .= '24ct Gold: '.$gold_price_tot.'<br>';
	                $cart_product_price .= 'Fixings: '.$fixing_type_price.'<br>';
	                $cart_product_price .= 'VAT: '.$vat.'<br>';
	                $total = $sign_price_tot + $gold_price_tot + $fixing_type_price + $vat;
	                $cart_product_price .= 'Total: '.$total.'<br>';
				@endphp
				<tr>
					<td>{{ $key+1 }}</td>
					<td>{!! $cart_product_description !!}</td>
					<td>{!! $cart_product_price !!}</td>
				</tr>
			@endforeach
			</table>
		@endif

		@if($payment_info->gift_voucher_cart != 'null' && $payment_info->gift_voucher_cart != '')
			<h2>Gift Voucher</h2>
			<table class="cls_order_receipt">
				<tr>
					<th>#</th>
					<th>Description</th>
					<th>Price</th>
				</tr>
			@php
	            $gift_voucher_cart = json_decode($payment_info->gift_voucher_cart);
	            // _pre($cart_object);
	            $cart_product_description = '';
	            $cart_product_price = '';
			@endphp
			@foreach($gift_voucher_cart as $key => $value)
				@php
					$cart_product_description .= 'Gift Voucher Value: '.@$value->product_price.'<br>';
	                $cart_product_description .= 'To: '.@$value->recipient_first_name.' '.@$value->recipient_last_name.'<br>';
	                $cart_product_description .= 'Email: '.@$value->recipient_email_address.'<br>';
	                $cart_product_description .= 'From: '.@$value->voucher_from.'<br>';
	                $cart_product_description .= 'Send Date: '.@$value->when_to_email_voucher.'<br>';
	                $cart_product_description .= 'Greeting Message: '.@$value->personal_message.'<br>';

	                $cart_product_price .= 'Quantity: '.@$value->quantity.'<br>';
	                $cart_product_price .= 'Voucher: '.@$value->remaining_amount.'<br>';
	                $cart_product_price .= 'VAT: '.@$value->gift_voucher_vat.'<br>';
	                $cart_product_price .= 'Total: '.@$value->product_price.'<br>';
				@endphp
				<tr>
					<td>{{ $key+1 }}</td>
					<td>{!! $cart_product_description !!}</td>
					<td>{!! $cart_product_price !!}</td>
				</tr>
			@endforeach
			</table>
		@endif
	</body>
</html>
@if($payment_info->cart_other_info != 'null' && $payment_info->cart_other_info != '')
	@php
        $cart_other_info = json_decode($payment_info->cart_other_info);
	@endphp
	<p>Delivery: {{ ucfirst($cart_other_info->is_delivery) }}</p>
	@if($cart_other_info->is_delivery == 'yes')
	    <p>Delivery price: {{ ucfirst($cart_other_info->delivery_price) }}</p>
	    <p>Delivery vat: {{ ucfirst($cart_other_info->delivery_vat) }}</p>
	@endif
	<p>Price: {{ $cart_other_info->total_price }} | VAT: {{ $cart_other_info->total_vat_price }}</p>
@endif
<p></p>

@elseif($email_type == 'ORDER_GIFT_VOUCHER')
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
	<!--[if gte mso 9]>
	<xml>
		<o:OfficeDocumentSettings>
		<o:AllowPNG/>
		<o:PixelsPerInch>96</o:PixelsPerInch>
		</o:OfficeDocumentSettings>
	</xml>
	<![endif]-->
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="format-detection" content="date=no" />
	<meta name="format-detection" content="address=no" />
	<meta name="format-detection" content="telephone=no" />
	<meta name="x-apple-disable-message-reformatting" />
    <!--[if !mso]><!-->
	<link href="https://fonts.googleapis.com/css?family=Fira+Mono:400,500,700|Ubuntu:400,400i,500,500i,700,700i" rel="stylesheet" />
    <!--<![endif]-->
	<title>Email Template</title>
	<!--[if gte mso 9]>
	<style type="text/css" media="all">
		sup { font-size: 100% !important; }
	</style>
	<![endif]-->
	

	<style type="text/css" media="screen">
		/* Linked Styles */
		body { padding:0 !important; margin:0 !important; display:block !important; min-width:100% !important; width:100% !important; background:#e4e4e5; -webkit-text-size-adjust:none }
		a { color:#000001; text-decoration:none }
		p { padding:0 !important; margin:0 !important } 
		img { -ms-interpolation-mode: bicubic; /* Allow smoother rendering of resized image in Internet Explorer */ }
		.mcnPreviewText { display: none !important; }
		
		/* Mobile styles */
		@media only screen and (max-device-width: 480px), only screen and (max-width: 480px) {
			.mobile-shell { width: 100% !important; min-width: 100% !important; }
			
			.m-center { text-align: center !important; }
			.text3,
			.text-footer,
			.text-header { text-align: center !important; }
			
			.center { margin: 0 auto !important; }
			
			.td { width: 100% !important; min-width: 100% !important; }
			
			.m-br-15 { height: 15px !important; }
			.p30-15 { padding: 30px 15px !important; }
			.p30-15-0 { padding: 30px 15px 0px 15px !important; }
			.p40 { padding-bottom: 30px !important; }
			.box,
			.footer,
			.p15 { padding: 15px !important; }
			.h2-white { font-size: 40px !important; line-height: 44px !important; text-align: center !important; }

			.h2 { font-size: 42px !important; line-height: 50px !important; }

			.m-td,
			.m-hide { display: none !important; width: 0 !important; height: 0 !important; font-size: 0 !important; line-height: 0 !important; min-height: 0 !important; }

			.m-block { display: block !important; }
			.container { padding: 0px !important; }
			.separator { padding-top: 30px !important; }

			.fluid-img img { width: 100% !important; max-width: 100% !important; height: auto !important; }

			.column,
			.column-top,
			.column-dir,
			.column-empty,
			.column-empty2,
			.column-bottom,
			.column-dir-top,
			.column-dir-bottom { float: left !important; width: 100% !important; display: block !important; }

			.column-empty { padding-bottom: 10px !important; }
			.column-empty2 { padding-bottom: 30px !important; }

			.content-spacing { width: 15px !important; }
		}
	</style>
</head>
<body class="body" style="padding:0 !important; margin:0 !important; display:block !important; min-width:100% !important; width:100% !important; background:#e4e4e5; -webkit-text-size-adjust:none;">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#e4e4e5">
		<tr>
			<td align="center" valign="top" class="container" style="padding:50px 10px;">
				<!-- Container -->
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td align="center">
							<table width="650" border="0" cellspacing="0" cellpadding="0" class="mobile-shell">
								<tr>
									<td class="td" bgcolor="#ffffff" style="width:650px; min-width:650px; font-size:0pt; line-height:0pt; padding:0; margin:0; font-weight:normal;">
										<!-- Header -->
										<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
											<tr>
												<td class="p30-15-0" style="padding: 40px 30px 0px 30px;">
													<table width="100%" border="0" cellspacing="0" cellpadding="0">
														<tr>
															<th class="column" style="font-size:0pt; line-height:0pt; padding:0; margin:0; font-weight:normal;">
																<table width="100%" border="0" cellspacing="0" cellpadding="0">
																	<tr>
																		<td class="img m-center" style="font-size:0pt; line-height:0pt; text-align:center;"><img src="{{ $header_logo }}" width="182" height="182" border="0" alt="" /></td>
																	</tr>
																</table>
															</th>
														</tr>
													</table>
												</td>
											</tr>
										</table>
										<!-- END Header -->

										<!-- Intro -->
										<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
											<tr>
												<td class="p30-15" style="padding: 70px 30px 70px 30px;">
													<table width="100%" border="0" cellspacing="0" cellpadding="0">
														<tr>
															<td class="h2 center pb10" style="color:#595a5d; font-family:'Ubuntu', Arial,sans-serif; font-size:20px; line-height:60px; text-align:center; padding-bottom:10px;text-transform: uppercase;">Hi {{ $var_greeting_name }} you've been sent a gift!</td>
														</tr>
														<tr>
															<td class="h5 center blue pb30" style="font-family:'Ubuntu', Arial,sans-serif; font-size:32px; line-height:26px; text-align:center; color:#595a5d; padding-bottom:10px;text-transform: uppercase;font-style: italic;font-weight: 500;">Gift Voucher</td>
														</tr>
														<tr>
															<td class="h5 center blue pb30" style="font-family:'Ubuntu', Arial,sans-serif; font-size:20px; line-height:26px; text-align:center; color:#595a5d; padding-bottom:30px;text-transform: uppercase;">To the value of</td>
														</tr>
														<tr>
															<td class="fluid-img pb40" style="font-size:0pt; line-height:0pt; text-align:left; padding-bottom:40px;"><img src="{{ $voucher_image }}" width="590" height="auto" border="0" alt="" /></td>
														</tr>
														<tr>
															<td class="h5 center blue pb30" style="font-family:'Ubuntu', Arial,sans-serif; font-size:22px; line-height:26px; text-align:center; color:#595a5d; padding-bottom:10px;text-transform: uppercase;font-style: normal;font-weight: normal;">Presented to</td>
														</tr>
														<tr>
															<td class="h5 center blue pb30" style="font-family:'Ubuntu', Arial,sans-serif; font-size:32px; line-height:26px; text-align:center; color:#595a5d; padding-bottom:30px;text-transform: uppercase;font-weight: 500;">{{ $var_greeting_name }}</td>
														</tr>
														<tr>
															<td class="h5 center blue pb30" style="font-family:'Ubuntu', Arial,sans-serif; font-size:22px; line-height:26px; text-align:center; color:#595a5d; padding-bottom:50px;text-transform: none;font-weight: normal;font-style: italic;padding-top: 25px;">{{ $personal_message }}</td>
														</tr>
														<tr>
															<td class="h5 center blue pb30" style="font-family:'Ubuntu', Arial,sans-serif; font-size:22px; line-height:26px; text-align:center; color:#595a5d; padding-bottom:10px;text-transform: uppercase;font-style: normal;font-weight: normal;">From</td>
														</tr>
														<tr>
															<td class="h5 center blue pb30" style="font-family:'Ubuntu', Arial,sans-serif; font-size:32px; line-height:26px; text-align:center; color:#595a5d; padding-bottom:30px;text-transform: uppercase;font-weight: 500;">{{ $voucher_from }}</td>
														</tr>
														<tr>
															<td class="h5 center blue pb30" style="font-family:'Ubuntu', Arial,sans-serif; font-size:18px; line-height:26px; text-align:center; color:#595a5d; padding-bottom:10px;text-transform: none;font-style: normal;font-weight: 500;padding-top: 40px;">Voucher Valid Until : {{ $voucher_valid_until }}</td>
														</tr>
														<tr>
															<td class="h5 center blue pb30" style="font-family:'Ubuntu', Arial,sans-serif; font-size:18px; line-height:26px; text-align:center; color:#595a5d; padding-bottom:10px;text-transform: none;font-style: normal;font-weight: 500;padding-top: 0;">Voucher Number : {{ $voucher_code }}</td>
														</tr>
														<tr>
															<td class="h5 center blue pb30" style="font-family:'Ubuntu', Arial,sans-serif; font-size:18px; line-height:26px; text-align:center; color:#595a5d; padding-bottom:50px;text-transform: none;font-style: normal;font-weight: lighter;padding-top: 0;">Enter this code at checkout</td>
														</tr>
														<!-- Button -->
														<tr>
															<td align="center">
																<table width="200" border="0" cellspacing="0" cellpadding="0">
																	<tr>
																		<td class="text-button" style="background:#8CC63F; color:#ffffff; font-family:'Ubuntu', Arial,sans-serif; font-size:16px; line-height:50px; text-align:left; padding:0 15px;text-transform: uppercase;"><a href="{{ $website_url }}" target="_blank" class="link-white" style="color:#ffffff; text-decoration:none;"><span class="link-white" style="color:#ffffff; text-decoration:none;">Visit Website <span style="float:right">&gt;</span></span></a></td>
																	</tr>
																</table>
															</td>
														</tr>

														<tr>
															<td class="h5 center blue pb30" style="font-family:'Ubuntu', Arial,sans-serif; font-size:18px; line-height:26px; text-align:center; color:#595a5d; padding-bottom:50px;text-transform: none;font-style: normal;font-weight: lighter;padding-top: 50px;">Got a question? Email us at<br><a href="mailto:info@slatesign.co.uk">info@slatesign.co.uk</a></td>
														</tr>
														<tr>
															<td class="h5 center blue pb30" style="font-family:'Ubuntu', Arial,sans-serif; font-size:18px; line-height:26px; text-align:center; color:#595a5d; padding-bottom:50px;text-transform: none;font-style: normal;font-weight: lighter;padding-top: 0;"><a href="{{ $terms_url }}">Terms & Conditions Apply ></a></td>
														</tr>
														<!-- END Button -->
													</table>
												</td>
											</tr>
										</table>
										<!-- END Intro -->

									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<!-- END Container -->
			</td>
		</tr>
	</table>
</body>
</html>
@endif
