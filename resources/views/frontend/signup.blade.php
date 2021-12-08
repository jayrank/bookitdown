<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="ScheduleDown">
    <meta name="author" content="ScheduleDown">
    <link rel="icon" type="image/png" href="img/fav.png">
    <title>ScheduleDown</title>
    <!-- Slick Slider -->
    <link rel="stylesheet" type="text/css" href="{{asset('frontend/vendor/slick/slick.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{asset('frontend/vendor/slick/slick-theme.min.css') }}" />
    <!-- Feather Icon-->
    <link href="{{asset('frontend/vendor/icons/feather.css') }}" rel="stylesheet" type="text/css">
    <!-- Bootstrap core CSS -->
    <link href="{{asset('frontend/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="{{asset('frontend/css/style.css') }}" rel="stylesheet">
    <!-- Sidebar CSS -->
    <link href="{{asset('frontend/vendor/sidebar/demo.css') }}" rel="stylesheet">
</head>

<body>
    <div class="osahan-signup login-page">
        <img alt="bg-img" class="bg-img d-none d-md-block bg-img" src="{{ asset('frontend/img/bg.jpeg') }}" />
        <div class="d-flex align-items-center justify-content-center flex-column vh-100">
            <div class="px-5 col-md-6 ml-auto">
                <div class="px-5 col-10 mx-auto">
                    <h2 class="text-dark my-0">Hello There.</h2>
                    <p class="text-50">Sign up to continue</p>
                    <form class="mt-5 mb-4" name="frontsignup" id="frontsignup" action="{{ route('fsignup') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="exampleInputName1" class="text-dark">Name</label>
                            <input type="text" placeholder="Enter Name" class="form-control" id="name" name="name"
                                aria-describedby="nameHelp">
                                @error('name')
                                    <span style="color: red;">{{ $message }}</span>
                                @enderror
                        </div>
                        <div class="form-group">
                            <label for="exampleInputNumber1" class="text-dark">Email</label>
                            <input type="email" placeholder="Enter Email" class="form-control"
                                id="email" name="email" aria-describedby="numberHelp">
                                @error('email')
                                    <span style="color: red;">{{ $message }}</span>
                                @enderror
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1" class="text-dark">Password</label>
                            <input type="password" placeholder="Enter Password" class="form-control"
                                id="password" name="password" >
                                @error('password')
                                    <span style="color: red;">{{ $message }}</span>
                                @enderror
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                            SIGN UP
                        </button>
                    </form>
                    <div class="py-2">
                        @php
                            $fac = 'facebook';
                        @endphp
                        <a href="{{ route('redirect',$fac) }}">
                        <button class="btn btn-facebook btn-lg btn-block"><i class="feather-facebook"></i> Connect
                            with Facebook</button>
                        </a>
                    </div>
                </div>
                <div class="new-acc d-flex align-items-center justify-content-center">
                    <a href="{{ route('flogin') }}">
                        <p class="text-center m-0">Already an account? Sign in</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
    {{-- <nav id="main-nav">
        <ul class="second-nav">
            <li><a href="index.html"><i class="feather-home mr-2"></i> Homepage</a></li>
            <li><a href="my-appoinment.html"><i class="feather-list mr-2"></i> My Appoinements</a></li>
            <li><a href="vouchers.html"><i class="feather-credit-card mr-2"></i> My Vouchers</a></li>
            <li><a href="paidplan.html"><i class="feather-dollar-sign mr-2"></i> My Paidplan</a></li>
            <li><a href="favorites.html"><i class="feather-heart mr-2"></i> My favorites</a></li>
            <li><a href="consultation-form.html"><i class="feather-file mr-2"></i> My cultation form</a></li>
            <li><a href="settings.html"><i class="feather-list mr-2"></i> Settings</a></li>
            <li><a href="customer-support.html"><i class="feather- mr-2"></i> Custoner support</a></li>
            <li>
                <a href="#"><i class="feather-edit-2 mr-2"></i> Authentication</a>
                <ul>
                    <li><a href="login.html">Login</a></li>
                    <li><a href="signup.html">Register</a></li>
                    <li><a href="forgot_password.html">Forgot Password</a></li>
                    <li><a href="verification.html">Verification</a></li>
                    <li><a href="location.html">Location</a></li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="feather-user mr-2"></i> Profile</a>
                <ul>
                    <li><a href="profile.html">Profile</a></li>
                    <li><a href="favorites.html">Delivery support</a></li>
                    <li><a href="contact-us.html">Contact Us</a></li>
                    <li><a href="terms.html">Terms of use</a></li>
                    <li><a href="privacy.html">Privacy & Policy</a></li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="feather-alert-triangle mr-2"></i> Error</a>
                <ul>
                    <li><a href="not-found.html">Not Found</a>
                    <li><a href="maintence.html"> Maintence</a>
                    <li><a href="coming-soon.html">Coming Soon</a>
                </ul>
            </li>
        </ul>
        <ul class="bottom-nav">
            <li class="email">
                <a class="text-primary" href="index.html">
                    <p class="h5 m-0"><i class="feather-home text-primary"></i></p>
                    Home
                </a>
            </li>
            <li class="github">
                <a href="faq.html">
                    <p class="h5 m-0"><i class="feather-message-circle"></i></p>
                    FAQ
                </a>
            </li>
            <li class="ko-fi">
                <a href="contact-us.html">
                    <p class="h5 m-0"><i class="feather-phone"></i></p>
                    Help
                </a>
            </li>
        </ul>
    </nav> --}}
    <!-- Bootstrap core JavaScript -->
    <script type="text/javascript" src="{{ asset('frontend/vendor/jquery/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- slick Slider JS-->
    <script type="text/javascript" src="{{ asset('frontend/vendor/slick/slick.min.js') }}"></script>
    <!-- Sidebar JS-->
    <script type="text/javascript" src="{{ asset('frontend/vendor/sidebar/hc-offcanvas-nav.js') }}"></script>
    <!-- Custom scripts for all pages-->
    <script type="text/javascript" src="{{ asset('frontend/js/osahan.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script>
        @if(session()->has("worngpass"))
            toastr.error("{{ Session::get('worngpass') }}");
        @endif
        @if(session()->has("logout"))
            toastr.success("{{ Session::get('logout') }}");
        @endif
    </script>
    <script>
        {{-- $(document).on('click','#save', function(){
            var form = $("#frontsignup");
            $.ajax({
                type: "POST",
                url: "{{ route('fsignup') }}",
                headers:{
                            'X-CSRF-Token': '{{ csrf_token() }}',
                        },
                data: form.serialize(),
                success: function (data) {
                    window.location.href = data.url;
                    toastr.success(data.message);
                },
                error: function(data) {
                    var errors = data.responseJSON;
                    var errorsHtml = '';
                    $.each(errors.error, function(key, value) {
                        errorsHtml += '<p>' + value[0] + '</p>';
                    });

                    toastr.options = {
                        "closeButton": false,
                        "debug": false,
                        "newestOnTop": true,
                        "progressBar": true,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "4000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    };

                    toastr.error((errorsHtml) ? errorsHtml : 'There is something wrong!');
                    KTUtil.scrollTop();
                }
            });
        }); --}}
    </script>
</body>

</html>