    <div id="header">
        <div id="masthead">
            <!-- <div id="christmas-message"></div> -->
            <div id="branding">
                <p class="logo">
                    <a href="{{ route('home') }}">
                        <img class="desktop" src="{{ route('home') }}/front/images/House-Signs-Logo-And-Strapline.png" alt="" title="" />
                        <img class="mobile" src="{{ route('home') }}/front/images/logo-mob.png" alt="" title="" />
                    </a>
                </p>
            </div>

            <div id="top-bar">
                <a href="{{ route('gift-voucher') }}"><p class="email voucher">
                    Order 
                    <strong>GIFT VOUCHERS HERE> </strong>
                    <!--<a href="mailto:sales@slatesign.co.uk">sales@<strong>slatesign.co.uk</strong></a>-->
                </p></a>
                <p class="strapline">Is <span class="white">your</span> sign made from the worlds finest slate?</p>
            </div>
        </div>
    </div>
    
    @php $routeName = Route::currentRouteName(); @endphp
    
    @if($routeName != 'order')
        <div id="access">
            <div class="menu-main-nav-container">
                <ul id="menu-main-nav" class="menu">
                    <li id="menu-item-27" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-home current-menu-item page_item page-item-6 current_page_item menu-item-27">
                        <a href="{{ route('home') }}" aria-current="page">Home
                            <span class="chev">></span>
                        </a>
                    </li>
                    <li id="menu-item-26" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-26">
                        <a href="{{ route('order') }}">Order
                            <span class="chev">></span>
                        </a>
                    </li>
                    <li id="menu-item-654" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-654">
                        <a href="{{ route('sign-faqs') }}">FAQ&#8217;s
                            <span class="chev">></span>
                        </a>
                    </li>
                    <li id="menu-item-24" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-24">
                        <a href="{{ route('classic-edge-gallery') }}">Gallery
                            <span class="chev">></span>
                        </a>
                    </li>
                    <li id="menu-item-23" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-23">
                        <a href="{{ route('contact') }}">Contact
                            <span class="chev">></span>
                        </a>
                    </li>
                </ul>
            </div>	
        </div>
    @else
        <div id="access">
            <ul class="order">
                <li>Step 1 : <span class="white">Size</span></li>
                <li>Step 2 : <span class="white">Style</span></li>
                <li>Step 3 : <span class="white">Design</span></li>
                <li>Step 4 : <span class="white">Colour</span></li>
                <li class="last">Step 5 : <span class="white">Confirm</span></li>
            </ul>
        </div>
    @endif