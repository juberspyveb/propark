<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-GB">

<head profile="http://gmpg.org/xfn/11">

    <title>@yield('title')</title>

	@include('front.layouts.meta')

    @include('front.layouts.styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/cookieconsent@3/build/cookieconsent.min.css" />
</head>

<body ng-app="app" data-rsssl=1 class="page-template page-template-order-page page-template-order-page-php page page-id-9">

    <div id="wrapper" class="hfeed">

		@yield('content')

	</div>
	<script src="https://cdn.jsdelivr.net/npm/cookieconsent@3/build/cookieconsent.min.js" data-cfasync="false"></script>
	<script>

    </script>
</body>
</html>