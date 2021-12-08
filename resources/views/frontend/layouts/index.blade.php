<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="ScheduleDown">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="ScheduleDown">
    <link rel="icon" type="image/png" href="{{ asset('frontend/img/fav.png') }}">
    <title>ScheduleDown</title>
    <!-- Slick Slider -->
    <link rel="stylesheet" type="text/css" href="{{asset('frontend/vendor/slick/slick.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{asset('frontend/vendor/slick/slick-theme.min.css') }}" />
    <!-- Feather Icon-->
    <link href="{{asset('frontend/vendor/icons/feather.css') }}" rel="stylesheet" type="text/css">
    <!-- Bootstrap core CSS -->
    <link href="{{asset('frontend/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{asset('frontend/vendor/owl.carousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{asset('frontend/vendor/animate/animate.css') }}" rel="stylesheet">
    <link href="{{asset('frontend/vendor/aos/aos.css') }}" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="{{asset('frontend/css/style.css') }}" rel="stylesheet">
    <!-- Sidebar CSS -->
    <link href="{{asset('frontend/vendor/sidebar/demo.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- Fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://js.tilled.com/v1.js"></script>
	{{-- Includable JS --}}
    <style type="text/css">
        .hc-offcanvas-nav li.nav-parent .nav-item:not(:last-child) {
            margin-right: unset;
        }
    </style>
	@yield('innercss')
</head> 
<body class="fixed-bottom-bar">
    <header class="section-header">
        @include('frontend.layouts.elements.header')
        @if(request()->is('/'))
            @include('frontend.layouts.elements.page_header')
        @endif
    </header>
    <div class="osahan-home-page">
        <div class="bg-primary p-3 m-block">
            <div class="text-white">
                <div class="title d-flex align-items-center">
                    <a class="toggle" href="#">
                        <span></span>
                    </a>
                    <h4 class="font-weight-bold m-0 pl-5">Browse</h4>
                    <!-- <a class="text-white font-weight-bold ml-auto" data-toggle="modal" data-target="#exampleModal"
                        href="#">Filter</a> -->
                </div>
            </div>
            <div class="input-group mt-3 rounded shadow-sm overflow-hidden1 search">
                <!-- <div class="input-group-prepend">
                    <button class="border-0 btn btn-outline-secondary text-dark bg-white btn-block"><i
                            class="feather-search"></i></button>
                </div> -->
                <input type="text" id="mainSearchBarMobile" class="shadow-none border-0 form-control"
                    placeholder="Search for a service or venue" autocomplete="off">
                <div class="results">
                    <ul class="popular-section" id="categorySectionMobile">
                       <li><a href="{{ route('search') }}"><i class="fa fa-star-o mr-2"></i>All categories</a></li>
                        @foreach($bussinessType as $catKey => $catVal)
                            <li class="bussinessTypes" data-title="{{ $catVal->name }}"><a href="{{ route('search',['category' => Crypt::encryptString($catVal->id)]) }}" data-type-id="{{ $catVal->id }}" class="searchByType">{{ $catVal->name }}</a></li>    
                            @if(isset($categoryID) && $categoryID == $catVal->id)
                                <input type="hidden" id="selected_id" name="selected_name" value="{{ $catVal->name }}" data-cat-id="{{ $catVal->id }}">
                            @endif
                        @endforeach
                    </ul> 
                    <ul class="location-section flexbox-ul">
                        <div class="search-title">Venues</div>
                        <div id="filteredVenuesMobile">
                            
                        </div>
                    </ul>
                </div>
            </div>
        </div>

        <!-- offer sectio slider -->
        @yield('content')
    </div>
    <!-- Footer -->
    @include('frontend.layouts.elements.footer')
    
    @include('frontend.layouts.elements.sidebar')
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Filter</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <div class="osahan-filter">
                        <div class="filter">
                            <!-- SORT BY -->
                            <div class="p-3 bg-light border-bottom">
                                <h6 class="m-0">SORT BY</h6>
                            </div>
                            <div class="custom-control border-bottom px-0  custom-radio">
                                <input type="radio" id="customRadio1f" name="location" class="custom-control-input nearestFilterModal" value="topRated" checked>
                                <label class="custom-control-label py-3 w-100 px-3" for="customRadio1f">Top
                                    Rated</label>
                            </div>
                            <div class="custom-control border-bottom px-0  custom-radio">
                                <input type="radio" id="customRadio2f" name="location" class="custom-control-input nearestFilterModal" value="Nearest">
                                <label class="custom-control-label py-3 w-100 px-3" for="customRadio2f">Nearest
                                    Me</label>
                            </div>
                            <div class="custom-control border-bottom px-0  custom-radio">
                                <input type="radio" id="customRadio3f" name="location" class="custom-control-input nearestFilterModal" value="Highest">
                                <label class="custom-control-label py-3 w-100 px-3" for="customRadio3f">Cost High to
                                    Low</label>
                            </div>
                            <div class="custom-control border-bottom px-0  custom-radio">
                                <input type="radio" id="customRadio4f" name="location" class="custom-control-input nearestFilterModal" value="Lowest">
                                <label class="custom-control-label py-3 w-100 px-3" for="customRadio4f">Cost Low to
                                    High</label>
                            </div>
                            <div class="custom-control border-bottom px-0  custom-radio">
                                <input type="radio" id="customRadio5f" name="location" class="custom-control-input nearestFilterModal" value="Popular">
                                <label class="custom-control-label py-3 w-100 px-3" for="customRadio5f">Most
                                    Popular</label>
                            </div>
                            <!-- Filter -->
                            <div class="p-3 bg-light border-bottom">
                                <h6 class="m-0">FILTER</h6>
                            </div>
                            <div class="custom-control border-bottom px-0  custom-checkbox">
                                <input type="checkbox" class="custom-control-input openNowFilter" value="openNow" id="defaultCheck1">
                                <label class="custom-control-label py-3 w-100 px-3" for="defaultCheck1">Open
                                    Now</label>
                            </div>
                            <!-- <div class="custom-control border-bottom px-0  custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="defaultCheck2">
                                <label class="custom-control-label py-3 w-100 px-3" for="defaultCheck2">Credit
                                    Cards</label>
                            </div>
                            <div class="custom-control border-bottom px-0  custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="defaultCheck3">
                                <label class="custom-control-label py-3 w-100 px-3" for="defaultCheck3">Alcohol
                                    Served</label>
                            </div> -->
                            <!-- Filter -->
                            <div class="p-3 bg-light border-bottom">
                                <h6 class="m-0">ADDITIONAL FILTERS</h6>
                            </div>
                            <div class="px-3 pt-3">
                                <input type="range" class="custom-range" min="{{ $servicePriceRange->service_lowest_price }}" max="{{ $servicePriceRange->service_highest_price }}" name="minmax">
                                <div class="form-row">
                                    <div class="form-group col-6">
                                        <label>Min</label>
                                        <input class="form-control minRange" placeholder="${{ $servicePriceRange->service_lowest_price }}" type="number" min="{{ $servicePriceRange->service_lowest_price }}">
                                    </div>
                                    <div class="form-group text-right col-6">
                                        <label>Max</label>
                                        <input class="form-control maxRange" placeholder="${{ $servicePriceRange->service_highest_price }}" type="number" min="{{ $servicePriceRange->service_lowest_price }}" max="{{ $servicePriceRange->service_highest_price }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-0 border-0">
                    <div class="col-6 m-0 p-0">
                        <button type="button" class="btn border-top btn-lg btn-block"
                            data-dismiss="modal">Close</button>
                    </div>
                    <div class="col-6 m-0 p-0">
                        <button type="button" class="btn btn-primary btn-lg btn-block applyFilter">Apply</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap core JavaScript -->

    <script type="text/javascript" src="{{asset('frontend/vendor/jquery/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{asset('frontend/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- slick Slider JS-->
    <script type="text/javascript" src="{{asset('frontend/vendor/slick/slick.min.js') }}"></script>
    <!-- Sidebar JS-->
    <script type="text/javascript" src="{{asset('frontend/vendor/sidebar/hc-offcanvas-nav.js') }}"></script>
    <!-- Custom scripts for all pages-->
    <script type="text/javascript" src="{{asset('frontend/js/osahan.js') }}"></script>
    <script src="{{asset('frontend/vendor/jquery.easing/jquery.easing.min.js') }}"></script>
    <script src="{{asset('frontend/vendor/waypoints/jquery.waypoints.min.js') }}"></script>
    <script src="{{asset('frontend/vendor/owl.carousel/owl.carousel.min.js') }}"></script>
    <script src="{{asset('frontend/vendor/counterup/counterup.min.js') }}"></script>
    <script src="{{asset('frontend/vendor/aos/aos.js') }}"></script>
    <script src="{{asset('frontend/js/main.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery.mask.js') }}"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    @yield('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCVdjXD_FnLKsQTNU8ki6Np_YlfwU0Oyec&v=3.exp&libraries=places"></script>
	<script>
		$(document).ready(function(){
			$("#mainSearchBar").unbind().click(function(){
				if($(this).siblings(".results").css("display") == 'none'){
					$(this).siblings(".results").css("display","flex");
				} else {
					$(this).siblings(".results").css("display","none");
				}
			});  

            $("#mainSearchBarMobile").unbind().click(function(){
                if($(this).siblings(".results").css("display") == 'none'){
                    $(this).siblings(".results").css("display","flex");
                } else {
                    $(this).siblings(".results").css("display","none");
                }
            });  
			//hide it when clicking anywhere else except the popup
			$(document).on('click', function(event) {
			  if (!$(event.target).parents().addBack().is('#mainSearchBar')) {
				$('#mainSearchBar').siblings('.results').hide();
			  }
              if (!$(event.target).parents().addBack().is('#mainSearchBarMobile')) {
                $('#mainSearchBarMobile').siblings('.results').hide();
              }
			}); 
			// Stop propagation to prevent hiding "#tooltip" when clicking on it
			$('.results').on('click', function(event) {
				event.stopPropagation();
			}).children().click(function(e) {
				return true;
			});
		});
	</script>
    <script type="text/javascript">
        $(document).ready(function(){
            if (navigator.geolocation) {
                navigator.geolocation.watchPosition(function(position) {
                    navigator.geolocation.getCurrentPosition(showPosition);
                  },
                  function(error) {
                    if (error.code == error.PERMISSION_DENIED)
                      
                      $.get("https://ipinfo.io/json", function (response) {
                          getLocation(response.loc.split(",")[0], response.loc.split(",")[1]);
                      }, "jsonp");
                  });
            } else { 
                console.log('false');

                responseMessages('error',"Geolocation is not supported by this browser.");
            }
        }); 

        function showPosition(position) {
            var latitude = position.coords.latitude;
            var longitude = position.coords.longitude;
            if(latitude != "" && longitude != "")
            {
                getLocation(latitude, longitude);
            }
            console.log(latitude);
            console.log(longitude);
        }

        function getLocation(latitude, longitude)
        {
            if($.trim($('#searchLocation').val()) == '') {
                $.ajax({
                    type:'GET',
                    url:'https://maps.googleapis.com/maps/api/geocode/json?latlng='+latitude+','+longitude+'&key=AIzaSyCVdjXD_FnLKsQTNU8ki6Np_YlfwU0Oyec',
                    success:function(resp){
                        var country = '';
                        // var country = resp.results[5].formatted_address;
                        for (var i = 0; i < resp.results[5].address_components.length; i++) {
                            var addressType = resp.results[5].address_components[i].types[0];
                            
                            // for the country, get the country code (the "short name") also
                            if (addressType == "country") {
                              country = resp.results[5].address_components[i].long_name;
                              break;
                            }
                        }
                        storeUserCurrentLocation(country, latitude, longitude);
                    }
                });
            } else {
                $('.loc_city').change();
            }
        }
        function storeUserCurrentLocation(country, latitude, longitude)
        {
            $.ajaxSetup({
               headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               }
            });
            $.ajax({
                type:'POST',
                url:'{{ route("UserCurrentLocation") }}',
                data:{country:country,lat:latitude,lng:longitude},
                success:function(resp){
                    $('#searchLocation').val(country);
                    $('.loc_country').val(country);
                    searchFilter();
                }
            });
        }
        function initialize() 
        {
            /*var latlng = new google.maps.LatLng();
            var map = new google.maps.Map(document.getElementById('map'), {
                center: latlng,
                zoom: 13
            });*/
            
            /*var marker = new google.maps.Marker({
                position: latlng,
                draggable: true,
                anchorPoint: new google.maps.Point(0, -29)
            });*/
            
            var input = document.getElementById('searchLocation');
            //map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
            var geocoder = new google.maps.Geocoder();
            var autocomplete = new google.maps.places.Autocomplete(input);
            // autocomplete.bindTo('bounds', map);
            //var infowindow = new google.maps.InfoWindow();   
            autocomplete.addListener('place_changed', function() {
                //infowindow.close();
                // marker.setVisible(false);
                var place = autocomplete.getPlace();
                if (!place.geometry) {
                    // window.alert("Autocomplete's returned place contains no geometry");
                    return;
                }
          
                // If the place has a geometry, then present it on a map.
                /*if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(17);
                }
               
                marker.setPosition(place.geometry.location);
                marker.setVisible(true);          
                $(".map_section").show();*/
            
                var premise = addres = district = postal_code = city = region = county = postal_code = country = "";
                var place = autocomplete.getPlace();
                for (var i = 0; i < place.address_components.length; i++) 
                {
                    for (var j = 0; j < place.address_components[i].types.length; j++) 
                    {
                        if (place.address_components[i].types[j] == "premise") 
                        {
                            premise = place.address_components[i].long_name+", ";
                        }
                        if (place.address_components[i].types[j] == "street_number") 
                        {
                            addres += place.address_components[i].long_name;
                        }
                        if (place.address_components[i].types[j] == "route") 
                        {
                            addres += " "+place.address_components[i].long_name;
                        }
                        if (place.address_components[i].types[j] == "neighborhood") 
                        {
                            district = place.address_components[i].long_name;
                        }
                        if (place.address_components[i].types[j] == "sublocality_level_1") 
                        {
                            district = place.address_components[i].long_name;
                        }
                        if (place.address_components[i].types[j] == "locality") 
                        {
                            city = place.address_components[i].long_name;
                        }
                        if (place.address_components[i].types[j] == "administrative_area_level_1") 
                        {
                            region = place.address_components[i].long_name;
                        }
                        if (place.address_components[i].types[j] == "administrative_area_level_2") 
                        {
                            county = place.address_components[i].long_name;
                        }
                        if (place.address_components[i].types[j] == "postal_code") 
                        {
                            postal_code = place.address_components[i].long_name;
                        }
                        if (place.address_components[i].types[j] == "country") 
                        {
                            country = place.address_components[i].long_name;
                        }
                    }
                }
                
                $('.loc_address').val(premise+""+addres);
                $('.loc_district').val(district);
                $('.loc_city').val(city);
                $('.loc_region').val(region);
                $('.loc_county').val(county);
                $('.loc_postcode').val(postal_code);
                $('.loc_country').val(country);

                $('.loc_city').change(); // Ref. public/js/dashboard.js - this event calls 'searchFilter' function
                
                var urlName = '{{ Request::segment(1) }}';

                if(urlName != 'search') {
                    window.location.href = '{{ route("search") }}?location='+input.value+'&city='+city+'&country='+country;
                }

                var address = "";
                var lat = place.geometry.location.lat();
                var lng = place.geometry.location.lng();
                
                if(addres != "") {
                    address += premise+""+addres+", ";
                } 
                if(city != "") {
                    address += city+", ";
                } 
                if(district != "") {
                    address += " ("+district+")"+", ";
                } 
                if(region != "") {
                    address += region;
                }       
                
                // document.getElementById('searchLocation').value = address;
                document.getElementById('lat').value = lat;
                document.getElementById('lng').value = lng;
                /*$.ajaxSetup({
                   headers: {
                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                   }
                });
                var url = $('#locationSearchUrl').val();
                $.ajax({
                    type:'post',
                    url:url,
                    data:{city:city},
                    success:function(resp){
                        if(resp.status == true){
                            $('#searchResult').html(city);
                            $('#filteredData').html(resp.html);
                            $('#totalLocation').html(resp.total_records);
                            sliderFun();
                        }
                        else{
                            $('#filteredData').html("");
                            $('#totalLocation').html(resp.total_records);
                        }
                    }
                });*/
            });
            
            /*google.maps.event.addListener(autocomplete, 'changed', function() {
                console.log('Here');
                
            });*/

        }
        google.maps.event.addDomListener(window, 'load', initialize);
    </script>
    <script type="text/javascript">
        $(document).ready(function(){
            if('{{ Session::has('success') }}')
            {
                responseMessages('success','{{ Session::get('success') }}') 
            }
            else if('{{ Session::has('error') }}')
            {
                responseMessages('error','{{ Session::get('error') }}') 
            }
        });
        function responseMessages(type,message)
        {
            if(type == "success")
            {
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

                toastr.success(message);
            }
            else
            {

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

                toastr.error(message);
                return false;
            }
        }
        function sliderFun()
        {
            $('.popular-slider').slick({
                centerMode: true,
                centerPadding: '30px',
                slidesToShow: 1,
                arrows: true,
                responsive: [{
                    breakpoint: 768,
                    settings: {
                        arrows: true,
                        centerMode: true,
                        centerPadding: '40px',
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        arrows: true,
                        centerMode: true,
                        centerPadding: '40px',
                        slidesToShow: 1
                    }
                }
                ]
            });
        }
    </script>
</body>

</html>