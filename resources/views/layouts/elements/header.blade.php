<style type="text/css">
	.search-modal-dialog {
		.width: 100%; 
		height: 100%; 
		margin: 0; 
		padding: 0; 
		max-width: unset;
	}
	.search-modal-content {
		height: auto; 
		min-height: 100%; 
		border-radius: 0;
	}
	.search-modal-header {
		border-bottom: unset;
	}
	.search-modal-close {
		font-size: 5em; 
		margin-left: 98% !important;
	}
	#searchText {
		font-weight: 100%; 
		font-size: 48px; 
		border: none; 
		border-bottom: 1px solid #dee3e7;
	}
	.search-upcoming-appointments {
		margin: 3% auto;
	}
	.upcoming-appointments-container {
		overflow-y: scroll;
		padding: 3%;
		max-height: 50vh;
	}
	.single-appointments {
		border-bottom: 1px solid #ccc;
	}
	.search-client-container {
		overflow-y: scroll; 
		overflow-x: unset;
		padding: 3%;
		max-height: 50vh;
	}
	.single-client {
		margin: 5%;
	}
	.client-initial {
		background-color: #dee3e7; 
		border-radius: 50%; 
		font-size: 25px; 
		padding: 10px 25px;
	}
	@media only screen and (max-width: 768px) {
		#searchText {
			font-size: 35px; 
		}
	}
	@media only screen and (max-width: 576px) {
		#searchText {
			font-size: 26px; 
		}
	}
	@media only screen and (max-width: 415px) {
		#searchText {
			font-size: 18px; 
		}
	}
</style>

<div id="kt_header" class="header header-fixed">
	<!--begin::Container-->
	<div class="container-fluid d-flex align-items-stretch justify-content-between">
		<!--begin::Header Menu Wrapper-->
		<div class="header-menu-wrapper header-menu-wrapper-left" id="kt_header_menu_wrapper">
			<!--begin::Header Menu-->
			<div id="kt_header_menu" class="header-menu header-menu-mobile header-menu-layout-default">
				<h2 style="line-height: 65px;">
					@if(request()->is('partners/home'))
						Home
					@elseif(request()->is('partners/calander'))
						Calendar
					@elseif(Request::segment(2) == 'sales')
						Sales
					@elseif(request()->is('partners/voucher'))
						Voucher
					@elseif(request()->is('partners/clients'))
						Clients
					@elseif(request()->is('partners/staff')||request()->is('partners/staff_closed')||request()->is('partners/staff_members')||request()->is('partners/getUserPermission'))
						Staff
					@elseif(request()->is('partners/service')||request()->is('partners/service/services/plans'))
						Services
					@elseif(request()->is('partners/inventory')||request()->is('partners/order')||request()->is('partners/brands')||request()->is('partners/categories')||request()->is('partners/suppliers'))
						Inventory
					@elseif(Request::segment(2) == 'analytics')
						Analytics
					@elseif(Request::segment(2) == 'setup')
						Setup
					@elseif(Request::segment(2) == 'online_booking')
						Online Booking
					@elseif(Request::segment(2) == 'marketing')
						Marketing
					@elseif(Request::segment(2) == 'conForm')
						Consultation Forms
					@elseif(Request::segment(2) == 'clientMessage')
						Client Messages
					@elseif(Request::segment(2) == 'userNotificationSettings')
						My Notification Settings
					@elseif(Request::segment(2) == 'overview')
						Overview
					@endif
				</h2>
			</div>
			<!--end::Header Menu-->
		</div>
		<!--end::Header Menu Wrapper-->
		<!--begin::Topbar-->
		<div class="topbar">
			<!--begin::Search-->
			<div class="dropdown1 topbar-item" id="kt_quick_search_toggle1">
				<!--begin::Toggle-->
				<div class="topbar-item"><!--  data-toggle="dropdown" data-offset="10px,0px" -->
					<div class="btn btn-icon btn-clean btn-dropdown mr-1" data-toggle="modal" data-target="#searchModal">
						<span class="svg-icon svg-icon-xl svg-icon-primary">
							<!--begin::Svg Icon | path:assets/media/svg/icons/General/Search.svg-->
							<svg xmlns="http://www.w3.org/2000/svg"
								xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
								viewBox="0 0 24 24" version="1.1">
								<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
									<rect x="0" y="0" width="24" height="24" />
									<path
										d="M14.2928932,16.7071068 C13.9023689,16.3165825 13.9023689,15.6834175 14.2928932,15.2928932 C14.6834175,14.9023689 15.3165825,14.9023689 15.7071068,15.2928932 L19.7071068,19.2928932 C20.0976311,19.6834175 20.0976311,20.3165825 19.7071068,20.7071068 C19.3165825,21.0976311 18.6834175,21.0976311 18.2928932,20.7071068 L14.2928932,16.7071068 Z"
										fill="#000000" fill-rule="nonzero" opacity="0.3" />
									<path
										d="M11,16 C13.7614237,16 16,13.7614237 16,11 C16,8.23857625 13.7614237,6 11,6 C8.23857625,6 6,8.23857625 6,11 C6,13.7614237 8.23857625,16 11,16 Z M11,18 C7.13400675,18 4,14.8659932 4,11 C4,7.13400675 7.13400675,4 11,4 C14.8659932,4 18,7.13400675 18,11 C18,14.8659932 14.8659932,18 11,18 Z"
										fill="#000000" fill-rule="nonzero" />
								</g>
							</svg>
							<!--end::Svg Icon-->
						</span>
					</div>
				</div>
				<!--end::Toggle-->
				<!--begin::Dropdown-->
				<div
					class="dropdown-menu p-0 m-0 dropdown-menu-right dropdown-menu-anim-up dropdown-menu-lg">
					<div class="quick-search quick-search-dropdown" id="kt_quick_search_dropdown">
						<!--begin:Form-->
						<form method="get" class="quick-search-form">
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text">
										<span class="svg-icon svg-icon-lg">
											<!--begin::Svg Icon | path:assets/media/svg/icons/General/Search.svg-->
											<svg xmlns="http://www.w3.org/2000/svg"
												xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
												height="24px" viewBox="0 0 24 24" version="1.1">
												<g stroke="none" stroke-width="1" fill="none"
													fill-rule="evenodd">
													<rect x="0" y="0" width="24" height="24" />
													<path
														d="M14.2928932,16.7071068 C13.9023689,16.3165825 13.9023689,15.6834175 14.2928932,15.2928932 C14.6834175,14.9023689 15.3165825,14.9023689 15.7071068,15.2928932 L19.7071068,19.2928932 C20.0976311,19.6834175 20.0976311,20.3165825 19.7071068,20.7071068 C19.3165825,21.0976311 18.6834175,21.0976311 18.2928932,20.7071068 L14.2928932,16.7071068 Z"
														fill="#000000" fill-rule="nonzero"
														opacity="0.3" />
													<path
														d="M11,16 C13.7614237,16 16,13.7614237 16,11 C16,8.23857625 13.7614237,6 11,6 C8.23857625,6 6,8.23857625 6,11 C6,13.7614237 8.23857625,16 11,16 Z M11,18 C7.13400675,18 4,14.8659932 4,11 C4,7.13400675 7.13400675,4 11,4 C14.8659932,4 18,7.13400675 18,11 C18,14.8659932 14.8659932,18 11,18 Z"
														fill="#000000" fill-rule="nonzero" />
												</g>
											</svg>
											<!--end::Svg Icon-->
										</span>
									</span>
								</div>
								<input type="text" class="form-control" placeholder="Search..." />
								<div class="input-group-append">
									<span class="input-group-text">
										<i
											class="quick-search-close ki ki-close icon-sm text-muted"></i>
									</span>
								</div>
							</div>
						</form>
						<!--end::Form-->
						<!--begin::Scroll-->
						<div class="quick-search-wrapper scroll" data-scroll="true" data-height="325"
							data-mobile-height="200"></div>
						<!--end::Scroll-->
					</div>
				</div>
				<!--end::Dropdown-->
			</div>
			<!--end::Search-->
			<!--begin::Quick panel-->
			<div class="topbar-item">
				<div class="btn btn-icon btn-clean mr-1" id="kt_quick_panel_toggle" style="position: relative;">
					<span class="svg-icon svg-icon-xl svg-icon-primary">
						<!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-03-183419/theme/html/demo1/dist/../src/media/svg/icons/General/Notifications1.svg-->
						<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
						    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
						        <path d="M17,12 L18.5,12 C19.3284271,12 20,12.6715729 20,13.5 C20,14.3284271 19.3284271,15 18.5,15 L5.5,15 C4.67157288,15 4,14.3284271 4,13.5 C4,12.6715729 4.67157288,12 5.5,12 L7,12 L7.5582739,6.97553494 C7.80974924,4.71225688 9.72279394,3 12,3 C14.2772061,3 16.1902508,4.71225688 16.4417261,6.97553494 L17,12 Z" fill="#000000"/>
						        <rect fill="#000000" opacity="0.3" x="10" y="16" width="4" height="4" rx="2"/>
						    </g>
						</svg><!--end::Svg Icon-->
						<span class="label label-danger notification-count" style="position: absolute; top: 1%; left: 70%; display: none;"></span>
					</span>
				</div>
			</div>
			<!--end::Quick panel-->
			<!--begin::User-->
			<div class="topbar-item">
				<div class="btn btn-icon btn-icon-mobile w-auto btn-clean d-flex align-items-center px-2"
					id="kt_quick_user_toggle">
					<span class="symbol symbol-lg-35 symbol-25 symbol-light-success">
						<span class="symbol-label font-size-h3 font-weight-bold">{{ substr(Auth::user()->first_name, 0, 1) }}</span>
					</span>
				</div>
			</div>
			<!--end::User-->
		</div>
		<!--end::Topbar-->
	</div>
	<!--end::Container-->
</div>

<!-- Modal -->
<div id="searchModal" class="modal fade" role="dialog">
  	<div class="modal-dialog modal-lg search-modal-dialog">

	    <!-- Modal content-->
	    <div class="modal-content search-modal-content">
	      	<div class="modal-header search-modal-header">
		        <button type="button" class="close search-modal-close" data-dismiss="modal">&times;</button>
		        <!-- <h4 class="modal-title">Modal Header</h4> -->
	      	</div>
	      	<div class="modal-body">
	      		<div class="container">
		        	<div class="row">
		        		<div class="col-md-12">
			        		<input type="text" class="form-control" id="searchText" placeholder="What are you looking for?">
			        		<span>Search by client name, mobile, email or booking reference</span>
		        		</div>
		        	</div>

		        	<div class="row search-upcoming-appointments">
		        		<div class="col-md-6">
		        			<h2>
		        				<b>Upcoming Appointments</b>
		        			</h2>

		        			<div class="upcoming-appointments-container">
			        			
			        		</div>
		        		</div>
		        		<div class="col-md-6">
		        			<h2>
		        				<b>Clients (recently added)
		        				</b>
		        			</h2>

		        			<div class="search-client-container">
			        			
			        		</div>
		        		</div>
		        	</div>
		        </div>
	      	</div>
	      	<div class="modal-footer">
	        	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	      	</div>
	    </div>

  	</div>
</div>