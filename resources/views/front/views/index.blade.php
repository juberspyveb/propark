@extends('front.layouts.layout')



@section('title')

House Signs - Finest Welsh Slate Signs

@endsection



@section('meta')

    <meta name="description"  content="We specialise in all areas of Welsh Slate, the worlds best slate. Let the Industry experts produce your fabulous new slate sign. Fifteen fantastic reasons to" />

@endsection



@section('styles')

@endsection



@section('content')

<link rel="stylesheet" type="text/css" href="{{ URL::to('/') }}/ex_plugins/toastr/css/toastr.min.css">

<script src="{{ URL::to('/') }}/ex_plugins/toastr/js/toastr.min.js"></script>

    <div class="right mobile">

        <div id="meteor-slideshow" class="meteor-slides navnone">

            <div class="meteor-clip">

                <img class="meteor-shim" src="{{ asset('front/images/slider/100-WELSH-SLATE-369x370.jpg') }}" alt="" />

            </div>

        </div>

    </div>

    <div id="top-bar" class="mobile">

        <div class="order">

            <a href="order"><span class="link"></span></a>

        </div>

        <div id="sig-link" class="mobile">
            <a href="{{ route('signature-collection') }}"><span class="link"></span></a>
        </div>

        <div class="gift-vouchers">

            <a href="gift-voucher"><span class="link"></span></a>

        </div>

    </div>

    <div id="main" class="home">

        <div id="container">

            <h1 class="entry-title">Welcome To <span>The True Home Of 100%</span> <span class="chunk">Welsh Slate House Signs...</span></h1>

            <div id="content">

                <div id="post-6" class="post-6 page type-page status-publish hentry">

                    <div class="entry-content">

                        <div class="left">

                            <p class="intro">We specialise in all areas of Welsh Slate, the<br />

                                worlds best slate. Let the Industry experts<br />

                                produce your fabulous new slate sign.</p>

                            <p class="intro">16 fantastic reasons to order with us today&#8230;</p>

                            <ul>

                                <li>Delivery 7-10 Working Days.</li>

                                <li>Advanced Urgent Order available (5 working days)</li>

                                <li>All signs approx 15-20mm thick.</li>

                                <li>We deeply engrave and enamel fill all of our signs. (Printed signs fade and wear)</li>

                                <li>100% Welsh Blue-Black Slate.</li>

                                <li>Sourced and manufactured by hand in the UK.</li>

                                <li>Accurate on-line proof creator.</li>

                                <li>Choice of edge types including Classic and Rustic.</li>

                                <li>Doesn’t fade like other imported slate.</li>

                                <li>Motif’s and bespoke requests available.</li>

                                <li>Secret fixings.</li>

                                <li>Easy ordering system.</li>

                                <li>Range of sizes.</li>

                                <li>Choice of infill colours, including 24ct Gold.</li>

                                <li>Range of font types, or request your own.</li>

                                <li class="last">We produce our signs to last a lifetime and beyond.</li>

                            </ul>

                        </div>

                        <div class="icons mobile"></div>

                        <div class="mid">

                            <div class="item proof">

                                <a href="{{ route('order') }}"><span class="link"></span></a>

                            </div>

                            <div class="item signature">

                                <a href="{{ route('signature-collection') }}"><span class="link"></span></a>

                            </div>

                            <div class="item gallery">

                                <a href="{{ route('classic-edge-gallery') }}"><span class="link"></span></a>

                            </div>

                            <div class="item fixings">

                                <a href="{{ route('secret-fixings-gallery') }}"><span class="link"></span></a>

                            </div>

                            

                        </div>

                        <div class="right desktop">

                            <div id="meteor-slideshow" class="meteor-slides  navnone">

                                <div class="meteor-clip">

                                    <img style="visibility: hidden;" class="meteor-shim" src="{{ asset('front/images/slider/home-promo-slide-signature-range.jpg') }}" alt="" />

                                    <div class="mslide mslide-1">

                                        <img width="369" height="370" src="{{ asset('front/images/slider/home-promo-slide-signature-range.jpg') }}" 

                                        class="attachment-featured-slide size-featured-slide wp-post-image" alt="" title="100% Welsh Slate" 

                                        srcset="{{ asset('front/images/slider/home-promo-slide-signature-range.jpg') }} 369w, 

                                        {{ asset('front/images/slider/home-promo-slide-signature-range.jpg') }} 150w, 

                                        {{ asset('front/images/slider/home-promo-slide-signature-range.jpg') }} 300w, 

                                        {{ asset('front/images/slider/home-promo-slide-signature-range.jpg') }} 250w, 

                                        {{ asset('front/images/slider/home-promo-slide-signature-range.jpg') }} 738w" sizes="(max-width: 369px) 100vw, 369px" />

                                    </div>

                                    <div class="mslide mslide-1">

                                        <img width="369" height="370" src="{{ asset('front/images/slider/100-WELSH-SLATE-369x370.jpg') }}" 

                                        class="attachment-featured-slide size-featured-slide wp-post-image" alt="" title="100% Welsh Slate" 

                                        srcset="{{ asset('front/images/slider/100-WELSH-SLATE-369x370.jpg') }} 369w, 

                                        {{ asset('front/images/slider/100-WELSH-SLATE-1150x150.jpg') }} 150w, 

                                        {{ asset('front/images/slider/100-WELSH-SLATE-300x300.jpg') }} 300w, 

                                        {{ asset('front/images/slider/100-WELSH-SLATE-250x251.jpg') }} 250w, 

                                        {{ asset('front/images/slider/100-WELSH-SLATE.jpg') }} 738w" sizes="(max-width: 369px) 100vw, 369px" />

                                    </div>

                                    <div class="mslide mslide-2">

                                        <img width="369" height="370" src="{{ asset('front/images/slider/CHOICE-AVAILABLE-369x370.jpg') }}" 

                                        class="attachment-featured-slide size-featured-slide wp-post-image" alt="" title="100% Welsh Slate" 

                                         sizes="(max-width: 369px) 100vw, 369px" />

                                    </div>

                                    <div class="mslide mslide-3">

                                        <img width="369" height="370" src="{{ asset('front/images/slider/NEED-IT-BESPOKE1-369x370.jpg') }}" 

                                        class="attachment-featured-slide size-featured-slide wp-post-image" alt="" title="Bespoke" 

                                        srcset="{{ asset('front/images/slider/NEED-IT-BESPOKE1-369x370.jpg') }} 369w, 

                                        {{ asset('front/images/slider/NEED-IT-BESPOKE1-150x150.jpg') }} 150w, 

                                        {{ asset('front/images/slider/NEED-IT-BESPOKE1-300x300.jpg') }} 300w, 

                                        {{ asset('front/images/slider/NEED-IT-BESPOKE1-250x251.jpg') }} 250w, 

                                        {{ asset('front/images/slider/NEED-IT-BESPOKE1.jpg') }} 738w" sizes="(max-width: 369px) 100vw, 369px" />

                                    </div>

                                    <div class="mslide mslide-4">

                                        <img width="369" height="370" src="{{ asset('front/images/slider/FANCY-24CT-GOLD-369x370.jpg') }}" 

                                        class="attachment-featured-slide size-featured-slide wp-post-image" alt="" title="24CT Gold" 

                                        srcset="{{ asset('front/images/slider/FANCY-24CT-GOLD-369x370.jpg') }} 369w, 

                                        {{ asset('front/images/slider/FANCY-24CT-GOLD-150x150.jpg') }} 150w, 

                                        {{ asset('front/images/slider/FANCY-24CT-GOLD-300x300.jpg') }} 300w, 

                                        {{ asset('front/images/slider/FANCY-24CT-GOLD-250x251.jpg') }} 250w, 

                                        {{ asset('front/images/slider/FANCY-24CT-GOLD.jpg') }} 738w" sizes="(max-width: 369px) 100vw, 369px" />

                                    </div>

                                    <div class="mslide mslide-5">

                                        <img width="369" height="370" src="{{ asset('front/images/slider/NEED-A-MOTIF-369x370.jpg') }}" 

                                        class="attachment-featured-slide size-featured-slide wp-post-image" alt="" title="Need A Motif" 

                                        srcset="{{ asset('front/images/slider/NEED-A-MOTIF-369x370.jpg') }} 369w, 

                                        {{ asset('front/images/slider/NEED-A-MOTIF-150x150.jpg') }} 150w, 

                                        {{ asset('front/images/slider/NEED-A-MOTIF-300x300.jpg') }} 300w, 

                                        {{ asset('front/images/slider/NEED-A-MOTIF-250x251.jpg') }} 250w, 

                                        {{ asset('front/images/slider/NEED-A-MOTIF.jpg') }} 738w" sizes="(max-width: 369px) 100vw, 369px" />

                                    </div>

                                </div>

                            </div>

                            <div id="sig-link">
                                <a href="{{ route('signature-collection') }}"><span class="link"></span></a>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>



    <?php

        if(isset($payment_info) && $payment_info && $payment_info != NULL)

        {

    ?>

            <script type="text/javascript">

                var cartObjectCurrent = localStorage.getItem("cartObjectCurrent");

                // var comm100_visitor_38784 = localStorage.getItem("comm100_visitor_38784");

                var currentStep = localStorage.getItem("currentStep");

                var cartObject = localStorage.getItem("cartObject");

                var giftVoucherCart = localStorage.getItem("giftVoucherCart");

                var cartOtherInfo = localStorage.getItem("cartOtherInfo");

                var url = "{{ route('save-order-data') }}";

                var txn_id = '<?php echo $payment_info->txn_id ?>';

                //alert('hiiii');

                $.ajax(

                {

                    url: url,

                    type: "POST",

                    data: {

                        // comm100_visitor_38784: comm100_visitor_38784,

                        cartObjectCurrent: cartObjectCurrent,

                        currentStep: currentStep,

                        cartObject: cartObject,

                        giftVoucherCart: giftVoucherCart,

                        cartOtherInfo: cartOtherInfo,

                        txn_id: txn_id,

                    },

                    success: function (data)

                    {

                        if(data.FLASH_STATUS == "S")

                        {

                            localStorage.removeItem('cartObjectCurrent');

                            localStorage.removeItem('currentStep');

                            localStorage.removeItem('cartObject');

                            localStorage.removeItem('giftVoucherCart');

                            localStorage.removeItem('cartOtherInfo');

                            toastr.success("Your Payment has been Successful", 'Success', {"iconClass": 'custom-class'});

                        }

                    },

                    error: function (jqXHR, textStatus, errorThrown)

                    {

                    }

                });

            </script>

    <?php

        }

        else

        {

    ?>

            <script type="text/javascript">

                // toastr.error("Your Payment has Failed", "Error", {"iconClass": 'custom-class'});

            </script>

    <?php

        }

    ?>

    <script type="text/javascript">

        <?php

            if(session()->exists('zero_total_order'))

            {

        ?>

                toastr.success("Your Payment has been Successful", 'Success', {"iconClass": 'custom-class'});

        <?php

            }

        ?>



        <?php

            if(session()->exists('stripePaymentSuccess'))

            {

        ?>

                // var modal = document.getElementById("myModal");

                // modal.style.display = "block";



                // var span = document.getElementsByClassName("close")[0];

                // span.onclick = function() {

                //     modal.style.display = "none";

                // }

                toastr.success("Your Payment has been Successful. We will email you with an order details.", 'Success', {"iconClass": 'custom-class'});

        <?php

            }

        ?>

    </script>

@endsection



@section('scripts')

@endsection

