<script type="text/javascript">
    /*var total_cart_items = 0;
    var cart = JSON.parse(localStorage.getItem("cartObject"));
    var giftVoucherCart = JSON.parse(localStorage.getItem("giftVoucherCart"));
    if(cart)
    {
        var ctrItem = cart.length;
        total_cart_items = total_cart_items + ctrItem;
    }
    if(giftVoucherCart)
    {
        var giftVoucherCartLength = giftVoucherCart.length;
        total_cart_items = total_cart_items + giftVoucherCartLength;
    }
    var cart_icon_route = "";
    if(total_cart_items > 0)
    {
        cart_icon_route = "{{ route('review') }}";
    }
    else
    {
        cart_icon_route = "{{ route('order') }}";
    }*/
    cart_icon_route = "{{ route('order') }}";
    <?php if(\Session::get('isCartExist')){ ?>
        cart_icon_route = "{{ route('review') }}";
    <?php }?>
</script>
<!-- Global site tag (gtag.js) - Google Ads: 975230974 --> <script async src="https://www.googletagmanager.com/gtag/js?id=AW-975230974"></script> <script> window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);} gtag('js', new Date()); gtag('config', 'AW-975230974'); </script>

<div id="mySidenav" class="sidenav mobile">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()"></a>
  <a href="{{ route('home') }}">Home</a>
  <a href="{{ route('order') }}">Order</a>
  <a href="{{ route('signature-collection') }}">Signature Collection</a>
  <a href="{{ route('sign-faqs') }}">FAQ's</a>
  <a href="{{ route('classic-edge-gallery') }}">Gallery</a>
  <a href="{{ route('contact') }}">Contact</a>
  @include('front.views.faqs.faq_sidebar')
</div>

<div id="top-header">
    <div class="wrapper">
        <div class="left">
            <p>Take a look at our other slate products...</p>
            <p>
                <a href="{{ route('secret-fixings-gallery') }}">Large &amp; Architectural</a> | 
                <a href="http://www.openingplaques.co.uk/" target="blank">Opening Plaques</a>
            </p>
        </div>
        <div class="right">
            <div class="basket">
                <script type="text/javascript">
                    document.write('<a href="' + cart_icon_route + '"><img src="{{ asset('front/images/basket.svg') }}" alt="" title="" / class="basket_image"></a>');
                </script>
            </div>
            <!-- Use any element to open the sidenav -->
            <span class="nav-open mobile" onclick="openNav()"></span>
            <div id="comm100-button-340"></div> 
            <script type="text/javascript"> var Comm100API = Comm100API || new Object; Comm100API.chat_buttons = Comm100API.chat_buttons || []; var comm100_chatButton = new Object; comm100_chatButton.code_plan = 340; comm100_chatButton.div_id = 'comm100-button-340'; Comm100API.chat_buttons.push(comm100_chatButton); Comm100API.site_id = 38784; Comm100API.main_code_plan = 340; var comm100_lc = document.createElement('script'); comm100_lc.type = 'text/javascript'; comm100_lc.async = true; comm100_lc.src = 'https://chatserver.comm100.com/livechat.ashx?siteId=' + Comm100API.site_id; var comm100_s = document.getElementsByTagName('script')[0]; comm100_s.parentNode.insertBefore(comm100_lc, comm100_s); setTimeout(function() { if (!Comm100API.loaded) { var lc = document.createElement('script'); lc.type = 'text/javascript'; lc.async = true; lc.src = '[ChatServerStandbyUrl]/livechat.ashx?siteId=' + Comm100API.site_id; var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(lc, s); } }, 5000) </script> 
        </div>
    </div>
</div>