<nav id="main-nav">
        <ul class="second-nav">
            <li><a href="{{ route('/') }}"><i class="feather-home mr-2"></i> Homepage</a></li>
            <li><a href="{{ route('myAppointments') }}"><i class="feather-list mr-2"></i> My Appointments</a></li>
            <li><a href="{{ route('myVouchers') }}"><i class="feather-credit-card mr-2"></i> My Vouchers</a></li>
            <!-- <li><a href="{{ route('myPaidPlans') }}"><i class="feather-dollar-sign mr-2"></i> My Paidplan</a></li> -->
            <li><a href="{{ route('favourites') }}"><i class="feather-heart mr-2"></i> My Favorites</a></li>
            <li><a href="{{ route('myConsultationForm') }}"><i class="feather-file mr-2"></i> My Consultation Form</a></li>
            <!-- <li><a href="#"><i class="feather-list mr-2"></i> Settings</a></li> -->
            <!-- <li><a href="#"><i class="feather-list mr-2"></i> Customer support</a></li> -->
            <!-- <li>
                <a href="#"><i class="feather-edit-2 mr-2"></i> Authentication</a>
                <ul>
                    {{--  <li><a href="{{ route('flogin') }}">Login</a></li>
                    <li><a href="{{ route('fsignup') }}">Register</a></li>  --}}
                    {{--  <li><a href="#">Forgot Password</a></li>
                    <li><a href="#">Verification</a></li>  --}}
                    <li><a href="#">Location</a></li>
                </ul>
            </li> -->
            <li>
                <a href="#"><i class="feather-user mr-2"></i> Profile</a>
                <ul>
                    <li><a href="{{ route('profile') }}">Profile</a></li>
                    <!-- <li><a href="#">Delivery support</a></li> -->
                    <!-- <li><a href="#">Contact Us</a></li> -->
                    <li><a href="{{ route('website-terms') }}">Terms of use</a></li>
                    <li><a href="{{ route('privacy-policy') }}">Privacy & Policy</a></li>
                </ul>
            </li>
            {{--  <li>
                <a href="#"><i class="feather-alert-triangle mr-2"></i> Error</a>
                <ul>
                    <li><a href="#">Not Found</a>
                    <li><a href="#"> Maintence</a>
                    <li><a href="#">Coming Soon</a>
                </ul>
            </li>  --}}
        </ul>
        <ul class="bottom-nav">
            <li class="email">
                <a class="text-primary" href="{{ route('/') }}">
                    <p class="h5 m-0"><i class="feather-home text-primary"></i></p>
                    Home
                </a>
            </li>
            <!-- <li class="github">
                <a href="#">
                    <p class="h5 m-0"><i class="feather-message-circle"></i></p>
                    FAQ
                </a>
            </li>
            <li class="ko-fi">
                <a href="#">
                    <p class="h5 m-0"><i class="feather-phone"></i></p>
                    Help
                </a>
            </li> -->
        </ul>
    </nav>