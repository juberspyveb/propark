<aside id="ms-side-nav" class="side-nav fixed ms-aside-scrollable ms-aside-left">
	<!-- Logo -->
		<div class="logo-sn ms-d-block-lg" style="padding:20px;">
			<a class="pl-0 ml-0 text-center" href="{{ route('admin-users-list') }}">
				<img src="{{ asset('assets/images/logo.png') }}" alt="logo" style="width: 135px;">
			</a>
		</div>
	<!-- Logo -->

	<!-- Navigation -->
		<ul class="accordion ms-main-aside fs-14" id="side-nav-accordion">
			{{--<li class="menu-item">
				<a href="{{ route('admin-dashboard') }}" class="{{ (request()->is('admin/dashboard*')) ? 'active' : '' }}"> 
					<span><i class="fas fa-home"></i>Dashboard</span>
				</a>
			</li>--}}
			<li class="menu-item">
				<a href="{{ route('admin-users-list') }}" class="{{ (request()->is('admin/users/*')) ? 'active' : '' }}">
				    <span><i class="fas fa-user"></i>Attendants</span>
				</a>
			</li>
			<li class="menu-item">
				<a href="{{ route('admin-customers-list') }}" class="{{ (request()->is('admin/customers/*') || request()->is('admin/customer/*')) ? 'active' : '' }}">
				    <span><i class="fas fa-users"></i>Customers </span>
				</a>
			</li>

                        <li class="menu-item">
                            <a href="{{ route('admin-supervisor-list') }}" class="{{ (request()->is('admin/supervisors/*') || request()->is('admin/supervisor/*')) ? 'active' : '' }}">
                                <span><i class="fas fa-user"></i>Supervisors</span>
                            </a>
                        </li>
						
			      		<li class="menu-item">
                            <a href="{{ route('admin-lot-list') }}" class="{{ (request()->is('admin/lots/*') || request()->is('admin/lot/*')) ? 'active' : '' }}">
                                <span><i class="far fa-building"></i>Lots</span>
                            </a>
                        </li>
						<li class="menu-item">
                            <a href="{{ route('admin-slots-list') }}" class="{{ (request()->is('admin/slots/*') || request()->is('admin/slot/*')) ? 'active' : '' }}">
                                <span><i class="fas fa-building"></i>Bays</span>
                            </a>
                        </li>
                        

                        <li class="menu-item">
                            <a href="{{ route('admin-transaction-list') }}" class="{{ (request()->is('admin/transaction/*')) ? 'active' : '' }}">
                                <span><i class="fas fa-shopping-cart"></i>Transaction</span>
                            </a>
                        </li>

		</ul>
	<!-- Navigation -->
</aside>