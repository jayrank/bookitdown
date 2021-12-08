<div class="subheader pt-2 pt-lg-4 subheader-solid" id="kt_subheader">
	<div
		class="offcanvas-header offcanvas-header-navs d-flex align-items-center justify-content-between mb-1">
		<ul class="border-0 nav nav-tabs nav-tabs-line nav-tabs-primary flex-grow-1 px-5"
			role="tablist">
			<!-- <li class="nav-item">
				<a class="nav-link @if(Route::is('onlineBooking')) active @endif" href="{{ route('onlineBooking') }}">Overview</a>
			</li> -->
			<li class="nav-item">
				<a class="nav-link @if(Route::is('online_profile')) active @endif" href="{{ route('online_profile') }}">Online Profile</a>
			</li>
			<li class="nav-item">
				<a class="nav-link @if(Route::is('clientReview')) active @endif" href="{{ route('clientReview') }}">Client Reviews</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="{{ route('button_links') }}">Buttons and links</a>
			</li>
			<!-- <li class="nav-item">
				<a class="nav-link" href="booking_analytics_tracking.html">Analytics Tracking</a>
			</li> -->
			<li class="nav-item">
				<a class="nav-link @if(Route::is('online_settings')) active @endif" href="{{ route('online_settings') }}">Settings</a>
			</li>
		</ul>
	</div>
</div>