<div class="aside aside-left aside-fixed d-flex flex-column flex-row-auto" id="kt_aside">
	<!--begin::Brand-->
	<div class="brand flex-column-auto bg-dark" id="kt_brand" style="background-color: #1a1a27">
		<!--begin::Logo-->
		<a href="{{ route('home') }}" class="brand-logo text-white">
			<h2 class="">Scheduledown</h2>
			<!-- <img alt="Logo" src="assets/media/logos/logo-light.png" /> -->
		</a>
		<!--end::Logo-->
		<!--begin::Toggle-->
		<button class="brand-toggle btn btn-sm px-0" id="kt_aside_toggle">
			<span class="svg-icon svg-icon svg-icon-xl">
				<!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Angle-double-left.svg-->
				<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
					width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
					<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
						<polygon points="0 0 24 0 24 24 0 24" />
						<path d="M5.29288961,6.70710318 C4.90236532,6.31657888 4.90236532,5.68341391 5.29288961,5.29288961 C5.68341391,4.90236532 6.31657888,4.90236532 6.70710318,5.29288961 L12.7071032,11.2928896 C13.0856821,11.6714686 13.0989277,12.281055 12.7371505,12.675721 L7.23715054,18.675721 C6.86395813,19.08284 6.23139076,19.1103429 5.82427177,18.7371505 C5.41715278,18.3639581 5.38964985,17.7313908 5.76284226,17.3242718 L10.6158586,12.0300721 L5.29288961,6.70710318 Z" fill="#000000" fill-rule="nonzero" transform="translate(8.999997, 11.999999) scale(-1, 1) translate(-8.999997, -11.999999)" />
						<path d="M10.7071009,15.7071068 C10.3165766,16.0976311 9.68341162,16.0976311 9.29288733,15.7071068 C8.90236304,15.3165825 8.90236304,14.6834175 9.29288733,14.2928932 L15.2928873,8.29289322 C15.6714663,7.91431428 16.2810527,7.90106866 16.6757187,8.26284586 L22.6757187,13.7628459 C23.0828377,14.1360383 23.1103407,14.7686056 22.7371482,15.1757246 C22.3639558,15.5828436 21.7313885,15.6103465 21.3242695,15.2371541 L16.0300699,10.3841378 L10.7071009,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(15.999997, 11.999999) scale(-1, 1) rotate(-270.000000) translate(-15.999997, -11.999999)" />
					</g>
				</svg>
				<!--end::Svg Icon-->
			</span>
		</button>
		<!--end::Toolbar-->
	</div>
	<!--end::Brand-->
	<!--begin::Aside Menu-->
	<div class="aside-menu-wrapper flex-column-fluid" id="kt_aside_menu_wrapper" style="background-color: #1e1e2d;">
		<!--begin::Menu Container-->
		<div id="kt_aside_menu" class="aside-menu my-4" data-menu-vertical="1" data-menu-scroll="1" data-menu-dropdown-timeout="500" style="background-color: #1e1e2d;">
			<!--begin::Menu Nav-->
			<ul class="menu-nav" style="background-color: #1e1e2d;">
				@if (Auth::user()->can('home'))
					<li class="menu-item {{ (request()->is('partners/home')) ? 'menu-item-active' : '' }}" aria-haspopup=" true">
						<a href="{{ route('home') }}" class="menu-link">
							<span class="svg-icon menu-icon fas fa-house-user"></span>
							<span class="menu-text">Home</span>
						</a>
					</li>
				@endif	
					<li class="menu-item {{ (request()->is('partners/calander')) ? 'menu-item-active' : '' }}" aria-haspopup="true">
						<a href="{{ route('calander') }}" class="menu-link">
							<span class="svg-icon menu-icon far fa-calendar-alt"></span>
							<span class="menu-text">Calendar</span>
						</a>
					</li>
				@if (Auth::user()->can('daily_sales') || Auth::user()->can('sales_appointments') || Auth::user()->can('sales_invoices') || Auth::user()->can('sales_vouchers'))
					<li class="menu-item {{ (request()->is('partners/sales/dailySale')) ? 'menu-item-active' : '' }}" aria-haspopup="true">
						<a href="{{ route('dailySale') }}" class="menu-link">
							<span class="svg-icon menu-icon fas fa-receipt"></span>
							<span class="menu-text">Sales</span>
						</a>
					</li>
				@endif
				
				@if (Auth::user()->can('view_voucher_list'))
					<li class="menu-item {{ (request()->is('partners/voucher')) ? 'menu-item-active' : '' }}" aria-haspopup="true">
						<a href="{{ route('voucherindex') }}" class="menu-link">
							<span class="svg-icon menu-icon fas fa-ticket-alt"></span>
							<span class="menu-text">Vouchers</span>
						</a>
					</li>
				@endif
				
				@if (Auth::user()->can('clients'))
					<li class="menu-item {{ (request()->is('partners/clients')) ? 'menu-item-active' : '' }}" aria-haspopup="true">
						<a href="{{ url('partners/clients') }}" class="menu-link">
							<span class="svg-icon menu-icon far fa-user"></span>
							<span class="menu-text">Clients</span>
						</a>
					</li>
				@endif	
				
				@if (Auth::user()->can('working_hours') || Auth::user()->can('closed_dates') || Auth::user()->can('staff_members') || Auth::user()->can('permission_levels'))
					<li class="menu-item {{ (request()->is('partners/staff') || request()->is('partners/staff_members') || request()->is('partners/add_staff_member') || request()->is('partners/staff_closed') || request()->is('partners/getUserPermission')) ? 'menu-item-active' : '' }}" aria-haspopup="true">
						<a href="{{ url('/partners/staff') }}" class="menu-link">
							<span class="svg-icon menu-icon fas fa-briefcase"></span>
							<span class="menu-text">Staff</span>
						</a>
					</li>
				@endif

				@if (Auth::user()->can('services'))
					<li class="menu-item {{ (request()->is('partners/service') || request()->is('partners/service/services/plans')) ? 'menu-item-active' : '' }}" aria-haspopup="true">
						<a href="{{ route('service') }}" class="menu-link">
							<span class="svg-icon menu-icon fas fa-clipboard-list"></span>
							<span class="menu-text">Services</span>
						</a>
					</li>
				@endif

				@if (Auth::user()->can('products'))
					<li class="menu-item {{ (request()->is('partners/inventory') || request()->is('partners/order') || request()->is('partners/brands') || request()->is('partners/categories') || request()->is('partners/suppliers')) ? 'menu-item-active' : '' }}" aria-haspopup="true">
						<a href="{{ url('/partners/inventory') }}" class="menu-link">
							<span class="svg-icon menu-icon far fa-building"></span>
							<span class="menu-text">Inventory</span>
						</a>
					</li>
				@endif	

				@if (Auth::user()->can('all_reports'))
					<li class="menu-item {{ (request()->is('partners/analytics') || request()->is('partners/analytics/list')) ? 'menu-item-active' : '' }}" aria-haspopup="true">
						<a href="{{ route('analyticsHome') }}" class="menu-link">
							<span class="svg-icon menu-icon fas fa-chart-pie"></span>
							<span class="menu-text">Analytics</span>
						</a>
					</li>
				@endif	

				@if (Auth::user()->can('account_setup') || Auth::user()->can('point_of_sale') || Auth::user()->can('messages') || Auth::user()->can('online_booking') || Auth::user()->can('marketing') || Auth::user()->can('client_settings'))
					<li class="menu-item {{ (request()->is('partners/setup') || request()->is('partners/setup/account') || request()->is('partners/setup/location') || request()->is('partners/setup/resources') || request()->is('partners/setup/invoiceTemplate') || request()->is('partners/setup/InvoiceSequencing') || request()->is('partners/setup/taxes') || request()->is('partners/setup/paymentType') || request()->is('partners/setup/getdiscount') || request()->is('partners/setup/getsales') || request()->is('partners/setup/showreferral') || request()->is('partners/setup/cancellationReasons')) ? 'menu-item-active' : '' }}" aria-haspopup="true">
						<a href="{{ url('partners/setup') }}" class="menu-link">
							<span class="svg-icon menu-icon fas fa-cog"></span>
							<span class="menu-text">Setup</span>
						</a>
					</li>
				@endif

				<li class="menu-section" style="background-color: #1a1a27">
					<h4 class="menu-text">Schedulethat&nbsp;<span style="color: #3699FF !important";>PLUS</span></h4>
					<i class="menu-icon ki ki-bold-more-hor icon-md"></i>
				</li>

				<li class="menu-item" aria-haspopup="true">
					<a href="{{ route('overviewWallet') }}" class="menu-link">
						<span class="svg-icon menu-icon fas fa-th-large"></span>
						<span class="menu-text">Overview</span>
					</a>
				</li>

				@if (Auth::user()->can('online_booking'))
					<li class="menu-item {{ (request()->is('partners/online_booking') || request()->is('partners/online_booking/online_profile') || request()->is('partners/online_booking/settings')) ? 'menu-item-active' : '' }}" aria-haspopup="true">
						<a href="{{ url('partners/online_booking/online_profile') }}" class="menu-link">
							<span class="svg-icon menu-icon far fa-calendar-check"></span>
							<span class="menu-text">Online Booking</span>
						</a>
					</li>
				@endif

				@if (Auth::user()->can('marketing'))
					<li class="menu-item {{ (request()->is('partners/marketing') || request()->is('partners/marketing/smart_campaigns') || request()->is('partners/marketing/marketing_blast_messages')) ? 'menu-item-active' : '' }}" aria-haspopup="true">
						<a href="{{ url('partners/marketing/smart_campaigns') }}" class="menu-link">
							<span class="svg-icon menu-icon fas fa-bullhorn"></span>
							<span class="menu-text">Marketing</span>
						</a>
					</li>
				@endif

				@if (Auth::user()->can('manage_consultation_forms'))
					<li class="menu-item {{ (request()->is('partners/conForm') || request()->is('partners/conForm/showconForm')) ? 'menu-item-active' : '' }}" aria-haspopup="true">
						<a href="{{ url('partners/conForm/showconForm') }}" class="menu-link">
							<span class="svg-icon menu-icon fas fa-clipboard-check"></span>
							<span class="menu-text">Consultation Forms</span>
						</a>
					</li>
				@endif

				@if (Auth::user()->can('messages'))
					<li class="menu-item {{ (request()->is('partners/clientMessage') || request()->is('partners/clientMessage/clientMessageSetting')) ? 'menu-item-active' : '' }}" aria-haspopup="true">
						<a href="{{ url('partners/clientMessage') }}" class="menu-link">
							<span class="svg-icon menu-icon far fa-comment"></span>
							<span class="menu-text">Client Messages</span>
						</a>
					</li>
				@endif	
			</ul>
			<!--end::Menu Nav-->
		</div>
		<!--end::Menu Container-->
	</div>
	<!--end::Aside Menu-->
</div>