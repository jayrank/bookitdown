@php
    /*echo "<pre>";
    print_r($categoryID);
    echo "<pre>";*/
@endphp
<section class="header-main shadow-sm bg-white">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-2">
                <a href="{{ route('/') }}" class="brand-wrap mb-0">
                    <img alt="#" class="img-fluid" src="{{ asset('frontend/img/logo_web.png') }}">
                </a>
                <!-- brand-wrap. -->
            </div>
            <input type="hidden" id="locationSearchUrl" value="{{ route('searchLocation') }}">
            <input type="hidden" id="searchFilterUrl" value="{{ route('categorySearchFilter') }}">
            <div class="col-5 d-flex align-items-center m-none">
                <div class="input-group rounded"> 
                     <form action="javascript:void(0)" class="search m-0" method="post"> 
                        <input type="text" name="q" id="mainSearchBar" autocomplete="off" placeholder="Search for a service or venue" />
                        <div class="results">
                            <ul class="popular-section" id="categorySection">
                               <li><a href="{{ route('search') }}"><i class="fa fa-star-o mr-2"></i>All categories</a></li>
                                @foreach($bussinessType as $catKey => $catVal)
                                    <li class="bussinessTypes" data-title="{{ $catVal->name }}"><a href="{{ route('search',['category' => Crypt::encryptString($catVal->id)]) }}" data-type-id="{{ $catVal->id }}" class="searchByType">{{ $catVal->name }}</a></li>    
                                    @if(isset($categoryID) && $categoryID == $catVal->id)
                                        <input type="hidden" id="selected_id" name="selected_name" value="{{ $catVal->name }}" data-cat-id="{{ $catVal->id }}">
                                    @endif
                                @endforeach
                            </ul> 
                            <!--<ul class="recent-section flexbox-ul" >
                                <div class="search-title">Recent searches</div>
                                <li>
                                    <div class="staff-flexbox">
                                        <svg class="" width="56" height="56"><path fill="#037AFF" d="M25.715 14.545c6.168 0 11.17 5 11.17 11.17 0 3.015-1.196 5.752-3.138 7.761l7.272 7.272a.78.78 0 01-1.016 1.178l-.087-.075-7.33-7.33a11.122 11.122 0 01-6.871 2.363c-6.17 0-11.17-5-11.17-11.17 0-6.168 5-11.17 11.17-11.17zm0 1.56a9.61 9.61 0 100 19.22 9.61 9.61 0 000-19.22zm0 2.336a.78.78 0 010 1.56A5.714 5.714 0 0020 25.715a.78.78 0 11-1.56 0 7.274 7.274 0 017.274-7.274z"></path></svg>
                                    </div>
                                    <div class="staff-flexbox">
                                        <p>Hair Salon</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="staff-flexbox">
                                        <svg class="" width="56" height="56"><path fill="#037AFF" d="M25.715 14.545c6.168 0 11.17 5 11.17 11.17 0 3.015-1.196 5.752-3.138 7.761l7.272 7.272a.78.78 0 01-1.016 1.178l-.087-.075-7.33-7.33a11.122 11.122 0 01-6.871 2.363c-6.17 0-11.17-5-11.17-11.17 0-6.168 5-11.17 11.17-11.17zm0 1.56a9.61 9.61 0 100 19.22 9.61 9.61 0 000-19.22zm0 2.336a.78.78 0 010 1.56A5.714 5.714 0 0020 25.715a.78.78 0 11-1.56 0 7.274 7.274 0 017.274-7.274z"></path></svg>
                                    </div>
                                    <div class="staff-flexbox">
                                        <p>Beauty Salon</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="staff-flexbox">
                                        <svg class="" width="56" height="56"><path fill="#037AFF" d="M25.715 14.545c6.168 0 11.17 5 11.17 11.17 0 3.015-1.196 5.752-3.138 7.761l7.272 7.272a.78.78 0 01-1.016 1.178l-.087-.075-7.33-7.33a11.122 11.122 0 01-6.871 2.363c-6.17 0-11.17-5-11.17-11.17 0-6.168 5-11.17 11.17-11.17zm0 1.56a9.61 9.61 0 100 19.22 9.61 9.61 0 000-19.22zm0 2.336a.78.78 0 010 1.56A5.714 5.714 0 0020 25.715a.78.78 0 11-1.56 0 7.274 7.274 0 017.274-7.274z"></path></svg>
                                    </div>
                                    <div class="staff-flexbox">
                                        <p>Spa</p>
                                    </div>
                                </li>
                            </ul-->
                            <ul class="location-section flexbox-ul">
                                <div class="search-title">Venues</div>
                                <div id="filteredVenues">
                                    
                                </div>
                                {{-- <li>
                                    <div class="staff-flexbox">
                                        <div class="venue-img">
                                            <img src="https://cdn-uploads.fresha.com/location-profile-images/431473/414831/thumb_9a64f78c-4720-4f11-8425-66750121f0c3.jpg">
                                        </div>
                                    </div>
                                    <div class="staff-flexbox">
                                        <div class="venue-info">
                                            <p>Nyk gym</p>
                                            <p class="address">Mandi Road, New Delhi (Chhatarpur), Delhi</p>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="staff-flexbox">
                                        <div class="venue-img">
                                            <img src="https://cdn-uploads.fresha.com/location-profile-images/348509/326405/thumb_2073f29a-7a15-4914-ad13-705768fd6c02.jpg">
                                        </div>
                                    </div>
                                    <div class="staff-flexbox">
                                        <div class="venue-info">
                                            <p>ACSA gym</p>
                                            <p class="address">Default Address, Default City</p>
                                        </div>
                                    </div>
                                </li> --}}
                            </ul>
                        </div>
                    </form>
                </div>
                <div class="col-5 d-flex align-items-center m-none">
                    <div class="input-group rounded shadow-sm"> 
                         {{-- <form class="search" method="post">  --}}
                            <input type="text" name="searchLocation" id="searchLocation" autocomplete="off" placeholder="Search Location" class="form-control" value="{{ !empty($requestSearch) ? $requestSearch : Session::get('country') }}" />
                            <input type="hidden" name="lat" id="lat">
                            <input type="hidden" name="lng" id="lng">
                            <input type="hidden" class="form-control loc_address">
                            <input type="hidden" class="form-control loc_apt">
                            <input type="hidden" class="form-control loc_district">
                            <input type="hidden" class="form-control loc_city" value="{{ !empty($requestCity) ? $requestCity : '' }}">
                            <!-- <input type="hidden" class="form-control loc_county"> -->
                            <input type="hidden" class="form-control loc_region">
                            <input type="hidden" class="form-control loc_postcode">
                            <input type="hidden" class="form-control loc_country" value="{{ !empty($requestCountry) ? $requestCountry : '' }}">
                        {{-- </form> --}}
                    </div>
                </div>
                {{-- <div class="dropdown ml-3">
                    <a class="text-dark dropdown-toggle d-flex align-items-center py-3" href="#"
                        id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <div><i class="feather-map-pin mr-2 bg-light rounded-pill p-2 icofont-size"></i></div>
                        <div>
                            <p class="text-muted mb-0 small">Select Location</p>
                            USA
                        </div>
                    </a>
                    <div class="dropdown-menu p-0 drop-loc" aria-labelledby="navbarDropdown">
                        <div class="osahan-country">
                            <div class="search_location bg-primary p-3 text-right">
                                <div class="input-group rounded shadow-sm overflow-hidden">
                                    <div class="input-group-prepend">
                                        <button class="border-0 btn btn-outline-secondary text-dark bg-white btn-block"><i class="feather-search"></i></button>
                                    </div>
                                    <input type="text" class="shadow-none border-0 form-control" id="searchLocation" placeholder="Enter Your Location"> 
                                </div>
                            </div>
                            <div class="p-3 border-bottom">
                                <a href="index.html" class="text-decoration-none">
                                    <p class="font-weight-bold text-primary m-0"><i class="feather-navigation"></i> New York, USA</p>
                                </a>
                            </div>
                            <div class="filter">
                                <h6 class="px-3 py-3 bg-light pb-1 m-0 border-bottom">Choose your country</h6>
                                @foreach($searchLocation as $locKey => $searchLoc)
                                    <div class="custom-control  border-bottom px-0 custom-radio">
                                        <input type="radio" id="customRadio{{ $locKey }}" name="location" class="custom-control-input">
                                        <label class="custom-control-label py-3 w-100 px-3" for="customRadio{{ $locKey }}">{{ $searchLoc->loc_country }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
            <!-- col.// -->
            <div class="col-5">
                <div class="d-flex align-items-center justify-content-end pr-5">
                    <!-- search -->
                    {{-- <a href="{{ route('search') }}" class="widget-header mr-4 text-dark">
                        <div class="icon d-flex align-items-center">
                            <i class="feather-search h6 mr-2 mb-0"></i> <span>Search</span>
                        </div>
                    </a> --}}
                    <!-- offers -->
                    <!-- <a href="{{ route('offers') }}" class="widget-header mr-4 text-white btn bg-primary m-none">
                        <div class="icon d-flex align-items-center">
                            <i class="feather-disc h6 mr-2 mb-0"></i> <span>Offers</span>
                        </div>
                    </a> -->
                    @if(Auth::guard('fuser')->user()==null)
                    <!-- signin -->
                     <a href="{{ url('/login') }}" class="widget-header header-link mr-4 text-dark m-none">
                        <div class="icon d-flex align-items-center">
                            <span>For Partners</span>
                        </div>
                    </a>
                    <a href="{{ route('fsignup') }}" class="widget-header header-link mr-4 text-dark m-  none">
                        <div class="icon d-flex align-items-center">
                            <i class="feather-user h6 mr-2 mb-0"></i> <span>Sign up</span>
                        </div>
                    </a>
                    <a href="{{ route('flogin') }}" class="widget-header mr-4 text-dark m-none">
                        <div class="icon d-flex align-items-center">
                            <i class="feather-log-in h6 mr-2 mb-0"></i> <span>Sign in</span>
                        </div>
                    </a>
                    @endif
                    <!-- my account -->
                    @if(Auth::guard('fuser')->user()!==null)
                    <div class="dropdown mr-4 m-none">
                        <a href="#" class="dropdown-toggle text-dark py-3 d-block" id="dropdownMenuButton"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img alt="#" src="{{ asset('frontend/img/user2.png') }}"
                                class="img-fluid rounded-circle header-user mr-2 header-user"> Hi {{ Auth::guard('fuser')->user()->name }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="{{ route('profile') }}">My account</a>
                            <!-- <a class="dropdown-item" href="#">Contant us</a> -->
                            <a class="dropdown-item" href="#">Term of use</a>
                            <a class="dropdown-item" href="#">Privacy policy</a>
                            <a class="dropdown-item" href="{{ route('flogout') }}">Logout</a>
                        </div>
                    </div>
                    
                    <!-- signin -->
                    {{--  <a href="#" class="widget-header mr-4 text-dark">
                        <div class="icon d-flex align-items-center">
                            <i class="feather-calendar h6 mr-2 mb-0"></i> <span>Cart</span>
                        </div>
                    </a>  --}}
                    <a class="toggle hc-nav-trigger hc-nav-1" href="#" role="button" aria-controls="hc-nav-1">
                        <span></span>
                    </a>
                    @endif
                </div>
                <!-- widgets-wrap.// -->
            </div>
            <!-- col.// -->
        </div>
        <!-- row.// -->
    </div>
    <!-- container.// -->
</section>
