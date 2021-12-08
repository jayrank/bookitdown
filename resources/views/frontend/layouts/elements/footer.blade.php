<div class="osahan-menu-fotter fixed-bottom bg-white px-3 py-2 text-center m-block">
    <div class="row">
        <div class="col selected">
            <a href="{{ route('/') }}" class="text-primary small font-weight-bold text-decoration-none">
                <p class="h4 m-0"><i class="feather-home text-primary"></i></p>
                Home
            </a>
        </div>
        <div class="col">
            <a href="{{ route('search') }}" class="text-dark small font-weight-bold text-decoration-none">
                <p class="h4 m-0"><i class="feather-map-pin"></i></p>
                Trending
            </a>
        </div>
        <div class="col bg-white rounded-circle mt-n4 px-3 py-2">
            <div class="bg-primary rounded-circle mt-n0 shadow">
                <a href="{{ route('/') }}" class="text-white small font-weight-bold text-decoration-none">
                    <i class="feather-calendar"></i>
                </a>
            </div>
        </div>
        <div class="col">
            <a href="{{ url('favourites') }}" class="text-dark small font-weight-bold text-decoration-none">
                <p class="h4 m-0"><i class="feather-heart"></i></p>
                Favorites
            </a>
        </div>
        <div class="col">
            <a href="{{ url('profile') }}" class="text-dark small font-weight-bold text-decoration-none">
                <p class="h4 m-0"><i class="feather-user"></i></p>
                Profile
            </a>
        </div>
    </div>
</div>
<!-- footer -->
<footer class="section-footer border-top bg-dark" data-aos="fade-up">
    <div class="container">
        <section class="footer-top padding-y py-5">
            <div class="row">
                <aside class="col-md-4 footer-about">
                    <article class="d-flex flex-column pb-3 pr-4">
                        <div classs=""><img alt="#" src="{{ asset('frontend/img/logo_web.png') }}" class="logo-footer mb-2"></div>
                        <div>
                            <p class="text-white">Some short text about company like You might remember the Dell
                                computer commercials in which a youth reports.</p>
                            <div class="d-flex align-items-center">
                                <a class="btn btn-icon btn-outline-light mr-1 btn-sm" title="Facebook"
                                    target="_blank" href="#"><i class="feather-facebook"></i></a>
                                <a class="btn btn-icon btn-outline-light mr-1 btn-sm" title="Instagram"
                                    target="_blank" href="#"><i class="feather-instagram"></i></a>
                                <a class="btn btn-icon btn-outline-light mr-1 btn-sm" title="Youtube"
                                    target="_blank" href="#"><i class="feather-youtube"></i></a>
                                <a class="btn btn-icon btn-outline-light mr-1 btn-sm" title="Twitter"
                                    target="_blank" href="#"><i class="feather-twitter"></i></a>
                            </div>
                        </div>
                    </article>
                </aside>
                <!-- <aside class="col-sm-3 col-md-2 text-white">
                    <h6 class="title">About us</h6>
                    <ul class="list-unstyled hov_footer">
                        <li> <a href="#" class="text-white">Customer Support</a></li>
                    </ul>
                </aside> -->
                <aside class="col-sm-3 col-md-2 text-white">
                    <h6 class="title">For business</h6>
                    <ul class="list-unstyled hov_footer">
                        <li> <a href="{{ route('for-partners') }}" class="text-white"> For partners </a></li>
                        <li> <a href="{{ route('pricing') }}" class="text-white"> Pricing </a></li>
                        <!-- <li> <a href="#" class="text-white"> Support Partners </a></li> -->
                    </ul>
                </aside>

                <aside class="col-sm-3  col-md-2 text-white">
                    <h6 class="title">Legal</h6>
                    <ul class="list-unstyled hov_footer">
                        <li> <a href="{{ route('booking-terms') }}" target="_blank" class="text-white"> Booking terms </a></li>
                        <li> <a href="{{ route('privacy-policy') }}" target="_blank" class="text-white"> Privacy policy </a></li>
                        <li> <a href="{{ route('website-terms') }}" target="_blank" class="text-white"> Website terms </a></li>
                    </ul>
                </aside>
            </div>
            <!-- row.// -->
        </section>
        <!-- footer-top.// -->
        <section class="footer-center border-top padding-y py-5">
            <h6 class="title text-white">Countries</h6>
            <div class="row">
                @php
                    if(Request::segment(1) == 'search') {
                        $searchCategory = Request::segment(2);
                    } else {
                        $searchCategory = '';
                    }
                @endphp
                <aside class="col-sm-2 col-md-2 text-white">
                    <ul class="list-unstyled hov_footer">
                        <li> <a href="{{ route('search', ['category' => $searchCategory]) }}?location=India&country=India" class="text-white">India</a></li>
                        <li> <a href="{{ route('search', ['category' => $searchCategory]) }}?location=Indonesia&country=Indonesia" class="text-white">Indonesia</a></li>
                        <li> <a href="{{ route('search', ['category' => $searchCategory]) }}?location=Ireland&country=Ireland" class="text-white">Ireland</a></li>
                        <li> <a href="{{ route('search', ['category' => $searchCategory]) }}?location=Italy&country=Italy" class="text-white">Italy</a></li>
                        <li> <a href="{{ route('search', ['category' => $searchCategory]) }}?location=Lebanon&country=Lebanon" class="text-white">Lebanon</a></li>
                    </ul>
                </aside>
                <aside class="col-sm-2 col-md-2 text-white">
                    <ul class="list-unstyled hov_footer">
                        <li> <a href="{{ route('search', ['category' => $searchCategory]) }}?location=Malaysia&country=Malaysia" class="text-white">Malaysia</a></li>
                        <li> <a href="{{ route('search', ['category' => $searchCategory]) }}?location=New Zealand&country=New Zealand" class="text-white">New Zealand</a></li>
                        <li> <a href="{{ route('search', ['category' => $searchCategory]) }}?location=Philippines&country=Philippines" class="text-white">Philippines</a></li>
                        <li> <a href="{{ route('search', ['category' => $searchCategory]) }}?location=Poland&country=Poland" class="text-white">Poland</a></li>
                        <li> <a href="{{ route('search', ['category' => $searchCategory]) }}?location=Portugal&country=Portugal" class="text-white">Portugal</a></li>
                    </ul>
                </aside>
                <aside class="col-sm-2 col-md-2 text-white">
                    <ul class="list-unstyled hov_footer">
                        <li> <a href="{{ route('search', ['category' => $searchCategory]) }}?location=Australia&country=Australia" class="text-white">Australia</a></li>
                        <li> <a href="{{ route('search', ['category' => $searchCategory]) }}?location=Brasil&country=Brasil" class="text-white">Brasil</a></li>
                        <li> <a href="{{ route('search', ['category' => $searchCategory]) }}?location=Canada&country=Canada" class="text-white">Canada</a></li>
                        <li> <a href="{{ route('search', ['category' => $searchCategory]) }}?location=Chile&country=Chile" class="text-white">Chile</a></li>
                        <li> <a href="{{ route('search', ['category' => $searchCategory]) }}?location=Czech Republic&country=Czech Republic" class="text-white">Czech Republic</a></li>
                    </ul>
                </aside>
                <aside class="col-sm-2 col-md-2 text-white">
                    <ul class="list-unstyled hov_footer">
                        <li> <a href="{{ route('search', ['category' => $searchCategory]) }}?location=Turkey&country=Turkey" class="text-white">Turkey</a></li>
                        <li> <a href="{{ route('search', ['category' => $searchCategory]) }}?location=UAE&country=UAE" class="text-white">UAE</a></li>
                        <li> <a href="{{ route('search', ['category' => $searchCategory]) }}?location=United Kingdom&country=United Kingdom" class="text-white">United Kingdom</a></li>
                        <li> <a href="{{ route('search', ['category' => $searchCategory]) }}?location=United States&country=United States" class="text-white">United States</a></li>
                        <li> <a href="{{ route('search', ['category' => $searchCategory]) }}?location=Sri Lanka&country=Sri Lanka" class="text-white">Sri Lanka</a></li>
                    </ul>
                </aside>
                <aside class="col-sm-2 col-md-2 text-white">
                    <ul class="list-unstyled hov_footer">
                        <li> <a href="{{ route('search', ['category' => $searchCategory]) }}?location=Qatar&country=Qatar" class="text-white">Qatar</a></li>
                        <li> <a href="{{ route('search', ['category' => $searchCategory]) }}?location=Singapore&country=Singapore" class="text-white">Singapore</a></li>
                        <li> <a href="{{ route('search', ['category' => $searchCategory]) }}?location=Slovakia&country=Slovakia" class="text-white">Slovakia</a></li>
                        <li> <a href="{{ route('search', ['category' => $searchCategory]) }}?location=South Africa&country=South Africa" class="text-white">South Africa</a></li>
                        <li> <a href="{{ route('search', ['category' => $searchCategory]) }}?location=Green Land&country=Green Land" class="text-white">Green Land</a></li>
                    </ul>
                </aside>
                <aside class="col-sm-2 col-md-2 text-white">
                    <ul class="list-unstyled hov_footer">
                        <li> <a href="{{ route('search', ['category' => $searchCategory]) }}?location=Pakistan&country=Pakistan" class="text-white">Pakistan</a></li>
                        <li> <a href="{{ route('search', ['category' => $searchCategory]) }}?location=Bangladesh&country=Bangladesh" class="text-white">Bangladesh</a></li>
                        <li> <a href="{{ route('search', ['category' => $searchCategory]) }}?location=Bhutaan&country=Bhutaan" class="text-white">Bhutaan</a></li>
                        <li> <a href="{{ route('search', ['category' => $searchCategory]) }}?location=Nepal&country=Nepal" class="text-white">Nepal</a></li>
                    </ul>
                </aside>
            </div>
            <!-- row.// -->
        </section>
    </div>
    <!-- //container -->
    <section class="footer-copyright border-top py-3 bg-light">
        <div class="container d-flex align-items-center">
            <p class="mb-0"> © 2020 Company All rights reserved </p>
            <p class="text-white mb-0 ml-auto d-flex align-items-center">
                <!-- <a href="#" class="d-block"><img alt="#" src="{{ asset('frontend/img/appstore.png') }}" width="180px" height="40"></a> -->
                <!-- <a href="#" class="d-block ml-3"><img alt="#" src="{{ asset('frontend/img/playmarket.png') }}" width="180px" height="40"></a> -->
            </p>
        </div>
    </section>
</footer>