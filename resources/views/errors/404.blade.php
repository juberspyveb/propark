@extends("front.layouts.layout")
@section("title", "Error | LV Website")
@section("page_style")
@endsection
@section("content")
<div id="main">
	<div class="wrapper">
		<div class="error-bg"></div>
		<!-- <div class="illustration"></div> -->
		<div id="error404">
			<h1 class="main-title">Error</h1>

				<h1 class="error404_heading" >Sorry This Page Does Not Exist...</h1>

				<h3 class="tag-line">Apologies but we were unable to find what you are looking for.</h3>


			<div class="item email" style="cursor: pointer;">
				<span class="visit">Visit Homepage</span>
			</div>
		</div>



	</div>
</div>
@endsection
@section("page_vendors")
@endsection
@section("page_script")
<script type="text/javascript">
	$(".visit").click(function()
	{
		window.location.href = "";
	});
</script>
@endsection