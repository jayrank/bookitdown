{{-- Extends layout --}}
@extends('frontend.layouts.index')

{{-- CSS Section --}}  
@section('innercss')
<style type="text/css">
    .img-slider img[alt="image-gallery"] {
        height: 180px;
        object-fit: cover;
        width: 100%;
    }
    .restaurant-pic{
        height:500px;
        width: 841px;
        margin: 0px 0px;
        right: -18px;

    }
    .rating-stars .feather-star{
        font-size: 13px;
    }
    .small, small {
    font-size: 12px !important;
    
}
@media screen and (min-width: 600px) {
    .ml-49 {
    margin-left:49px;
  }
}
</style>
@endsection

@section('content')
@php
    $curDay = date('l');
    $store_is_open = 1;
    $store_open_time = "";
    $store_close_time = "";
    if(!empty($StoreTiming))
    {
        $sunday_is_open = $StoreTiming->is_open_sunday;
        $sunday_open_time = $StoreTiming->sunday_open_time;
        $sunday_close_time = $StoreTiming->sunday_close_time;
        $monday_is_open = $StoreTiming->is_open_monday;
        $monday_open_time = $StoreTiming->monday_open_time;
        $monday_close_time = $StoreTiming->monday_close_time;
        $tuesday_is_open = $StoreTiming->is_open_tuesday;
        $tuesday_open_time = $StoreTiming->tuesday_open_time;
        $tuesday_close_time = $StoreTiming->tuesday_close_time;
        $wednesday_is_open = $StoreTiming->is_open_wednesday;
        $wednesday_open_time = $StoreTiming->wednesday_open_time;
        $wednesday_close_time = $StoreTiming->wednesday_close_time;
        $thursday_is_open = $StoreTiming->is_open_thursday;
        $thursday_open_time = $StoreTiming->thursday_open_time;
        $thursday_close_time = $StoreTiming->thursday_close_time;
        $friday_is_open = $StoreTiming->is_open_friday;
        $friday_open_time = $StoreTiming->friday_open_time;
        $friday_close_time = $StoreTiming->friday_close_time;
        $saturday_is_open = $StoreTiming->is_open_saturday;
        $saturday_open_time = $StoreTiming->saturday_open_time;
        $saturday_close_time = $StoreTiming->saturday_close_time;
        if($curDay == 'Sunday'){
            $store_is_open = $StoreTiming->is_open_sunday;
            $store_open_time = $StoreTiming->sunday_open_time;
            $store_close_time = $StoreTiming->sunday_close_time;
            $sunday_open_time = $StoreTiming->sunday_open_time;
            $sunday_close_time = $StoreTiming->sunday_close_time;
        }else if($curDay == 'Monday'){
            $store_is_open = $StoreTiming->is_open_monday;
            $store_open_time = $StoreTiming->monday_open_time;
            $store_close_time = $StoreTiming->monday_close_time;
        }else if($curDay == 'Tuesday'){
            $store_is_open = $StoreTiming->is_open_tuesday;
            $store_open_time = $StoreTiming->tuesday_open_time;
            $store_close_time = $StoreTiming->tuesday_close_time;
        }else if($curDay == 'Wednesday'){
            $store_is_open = $StoreTiming->is_open_wednesday;
            $store_open_time = $StoreTiming->wednesday_open_time;
            $store_close_time = $StoreTiming->wednesday_close_time;
        }else if($curDay == 'Thursday'){
            $store_is_open = $StoreTiming->is_open_thursday;
            $store_open_time = $StoreTiming->thursday_open_time;
            $store_close_time = $StoreTiming->thursday_close_time;
        }else if($curDay == 'Friday'){
            $store_is_open = $StoreTiming->is_open_friday;
            $store_open_time = $StoreTiming->friday_open_time;
            $store_close_time = $StoreTiming->friday_close_time;
        }else if($curDay == 'Saturday'){
            $store_is_open = $StoreTiming->is_open_saturday;
            $store_open_time = $StoreTiming->saturday_open_time;
            $store_close_time = $StoreTiming->saturday_close_time;
        }
    }
@endphp
    <!-- <div class="offer-section py-4 bg-primary">
        <div class="container position-relative">
            <img alt="#" src="{{ ($LocationData->location_image != "") ? url($LocationData->location_image) : asset('frontend/img/featured1.jpg') }}" class="restaurant-pic">
            <div class="pt-3 text-white">
                <h2 class="font-weight-bold">{{ ($LocationData->location_name != "") ? $LocationData->location_name : "" }}</h2>
                <p class="text-white m-0">{{ ($LocationData->location_address != "") ? $LocationData->location_address : "" }}</p>
                <div class="rating-wrap d-flex align-items-center mt-2">
                    <ul class="rating-stars list-unstyled">
                        <li>
                            <i class="feather-star {{ ($locationRating >= 1) ? 'text-warning' : '' }}"></i>
                            <i class="feather-star {{ ($locationRating >= 2) ? 'text-warning' : '' }}"></i>
                            <i class="feather-star {{ ($locationRating >= 3) ? 'text-warning' : '' }}"></i>
                            <i class="feather-star {{ ($locationRating >= 4) ? 'text-warning' : '' }}"></i>
                            <i class="feather-star {{ ($locationRating >= 5) ? 'text-warning' : '' }}"></i>
                        </li>
                    </ul>
                    <p class="label-rating text-white ml-2 small"> ({{ $totalRatings }} Reviews)</p>
                </div>
            </div>
            <div class="pb-4">
                <div class="row">
                    <div class="col-6 col-md-2">
                        <p class="text-dark font-weight-bold m-0 small">Open time</p>
                        <p class="text-white m-0">{{ ($store_is_open == 1) ? date('h:i A',strtotime($store_open_time)) : "Closed Now" }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

    <div class="container-fluid">
        <div class="row" style="background-color:#F2F2F7;">
            <div class="col-lg-4 ml-lg49" style="background-color:#F2F2F7;height: 500px;">
                <div style="text-align: center; border-radius: 6px;">
                <p style="font-size: 43px;color: rgb(16, 25, 40);font-weight: 900;margin-top: 90px; line-height: 58px;">{{ ($LocationData->location_name != "") ? $LocationData->location_name : "" }}</p>
                <h5 class="m-auto" style="width:300px;color: #6c757d !important;">{{ ($LocationData->location_address != "") ? $LocationData->location_address : "" }}</h5>
                 <div class="justify-content-center rating-wrap d-flex align-items-center mt-2">
                    <ul class="rating-stars list-unstyled">
                        <li>
                            <i style="font-size:20px;margin:0 1px" class="feather-star {{ ($locationRating >= 1) ? 'star_active' : '' }}"></i>
                            <i style="font-size:20px;margin:0 1px" class="feather-star {{ ($locationRating >= 2) ? 'star_active' : '' }}"></i>
                            <i style="font-size:20px;margin:0 1px" class="feather-star {{ ($locationRating >= 3) ? 'star_active' : '' }}"></i>
                            <i style="font-size:20px;margin:0 1px" class="feather-star {{ ($locationRating >= 4) ? 'star_active' : '' }}"></i>
                            <i style="font-size:20px;margin:0 1px" class="feather-star {{ ($locationRating >= 5) ? 'star_active' : '' }}"></i>
                        </li>
                    </ul>
                    <p class="label-rating ml-2" style="font-size:16px"> ({{ $totalRatings }} Reviews)</p>
                </div>
                <!-- <p style="font-size: 15px;">Open time : <span> 12:00 AM</span></p> -->
               
                <p class="text-dark font-weight-bold m-0" style="font-size:16px">Open time</p>
                        <p class="text-dark m-0">{{ ($store_is_open == 1) ? date('h:i A',strtotime($store_open_time)) : "Closed Now" }}</p>
                <a href="{{ route('frontBooking', $encrptLocationId) }}"><button class="btn-book-now" >BOOK NOW</button></a>
            </div>
                
            </div>
            <div class="col-sm-8
             restaurant-pic">
                <!-- <img src="image/img.jpg" style="height:500px;width: 841px;"> -->
                <!-- <img alt="#" src="{{ ($LocationData->location_image != "") ? url($LocationData->location_image) : asset('frontend/img/featured1.jpg') }}" class="restaurant-pic"> -->
                <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
  <ol class="carousel-indicators">
    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
    <li data-target="#carouselExampleIndicators" data-slide-to="1" class=""></li>
    <li data-target="#carouselExampleIndicators" data-slide-to="2" class=""></li>
  </ol>
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img class="d-block w-100 " src="{{ ($LocationData->location_image != "") ? url($LocationData->location_image) : asset('frontend/img/featured1.jpg') }}" alt="First slide">
    </div>
    <div class="carousel-item">
      <img class="d-block w-100 " src="{{ ($LocationData->location_image != "") ? url($LocationData->location_image) : asset('frontend/img/featured1.jpg') }}" alt="Second slide">
    </div>
    <div class="carousel-item">
      <img class="d-block w-100 " src="{{ ($LocationData->location_image != "") ? url($LocationData->location_image) : asset('frontend/img/featured1.jpg') }}" alt="Third slide">
    </div>
  </div>
  <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>
                
            </div>
            
        </div>
        
    </div>




    <div class="container">
        <div class="p-3 mt-n3 rounded position-relative" style="background-color:#fff">
            <div class="d-flex align-items-center">
                <div class="feather_icon">
                    <a href="#book-with-staff" class="text-decoration-none text-light"><i
                            class="p-2 bg-dark rounded-circle font-w fa-2x eight-bold  feather-user"></i></a>
                    <a href="#ratings-and-reviews" class="text-decoration-none text-light mx-2"><i
                            class="p-2 bg-dark rounded-circle font-w fa-2x eight-bold  feather-star"></i></a>
                    <a href="#location" class="text-decoration-none text-light"><i
                            class="p-2 bg-dark rounded-circle font-w fa-2x eight-bold feather-map-pin"></i></a>

                    @if(\Auth::guard('fuser')->check())
                        <a href="javascript:void(0)" class="text-decoration-none text-light mx-2 favourite" data-location_id="{{ !empty($LocationData) ? $LocationData->id : '' }}"><i
                                class="p-2 bg-dark rounded-circle font-w fa-2x eight-bold feather-heart" style="{{ ($markFavourite) ? 'color: #f00;' : 'color: #fff;' }}"></i></a>
                    @endif
                </div>
                <a href="{{ route('frontBooking', $encrptLocationId) }}" style="font-size:17px" class="btn btn-sm btn-dark ml-auto">Book Now</a>
            </div>
        </div>
    </div>
    <div class="container my-4">
        <div class="row">
            <div class="col-12 col-md-7">
                <h3 class="font-weight-bolder">About Chelsea Hair and Skin Clinic By KARDA</h3>
                <div class="d-flex flex-wrap justify-content-start my-3">
                    <h6 class="mr-4">
                        <svg width="24" height="24">
                            <path fill="#101928" fill-rule="evenodd"
                                d="M10.361 14.378l-1.583 6.434c-.242.982.992 1.618 1.63.84l9.373-11.425c.509-.62.075-1.562-.72-1.562h-4.382l1.534-5.451C16.383 2.605 15.934 2 15.31 2H8.75a.94.94 0 0 0-.904.702L5.034 13.175c-.163.606.286 1.203.904 1.203h4.423zM14.07 3.904l-1.534 5.452c-.17.608.279 1.213.902 1.213h3.623l-5.47 6.667.881-3.58a.949.949 0 0 0-.909-1.182H7.165l2.3-8.57h4.604z">
                            </path>
                        </svg>
                        Instant confirmation
                    </h6>

                    @if(!is_null($LocationData->available_for))
                        <h6 class="mr-4">
                            @if($LocationData->available_for == 0)
                                <svg class="" style="width:24px" viewBox="0 0 24 24">
                                    <path fill="#101928" fill-rule="evenodd"
                                        d="M23,1 C23.0064615,1 23.0129086,1.00006128 23.0193409,1.0001833 C23.042681,1.00063489 23.0659949,1.00189498 23.0892501,1.00396641 C23.1096949,1.00574796 23.1294204,1.00810098 23.1489612,1.01101934 C23.165949,1.01362808 23.1833753,1.01670217 23.2007258,1.02024007 C23.2227461,1.0246398 23.2444365,1.02983363 23.2658396,1.03572097 C23.2814633,1.04009773 23.2968513,1.04472872 23.3121425,1.04973809 C23.3317977,1.05612249 23.3515792,1.06331574 23.3710585,1.07110396 C23.3887956,1.07819642 23.4060985,1.08567905 23.4232215,1.09367336 C23.4438914,1.10337664 23.4642939,1.11379392 23.484277,1.12487577 C23.4963944,1.13149883 23.5086131,1.13860692 23.5207088,1.14599545 C23.546275,1.1617187 23.5711307,1.17849009 23.5951593,1.19631351 C23.6343256,1.22531295 23.6717127,1.25749917 23.7071068,1.29289322 L23.6167501,1.21278596 C23.6801818,1.26255171 23.7374483,1.31981825 23.787214,1.38324992 C23.7927155,1.39015759 23.7982466,1.39739696 23.8036654,1.40469339 C23.8215099,1.42886931 23.8382813,1.45372505 23.8539326,1.47933981 C23.8613931,1.49138689 23.8685012,1.50360556 23.8753288,1.5159379 C23.8862061,1.53570605 23.8966234,1.5561086 23.9063462,1.57690085 C23.914321,1.5939015 23.9218036,1.61120435 23.9287745,1.62866398 C23.9366843,1.6484208 23.9438775,1.66820232 23.9504533,1.68826359 C23.9552713,1.70314866 23.9599023,1.71853672 23.9641549,1.73400703 C23.9701664,1.75556352 23.9753602,1.77725392 23.9798348,1.7992059 C23.9832978,1.81662474 23.9863719,1.83405099 23.9889822,1.85153313 C23.9962388,1.89963791 24,1.94937972 24,2 L23.9962979,1.9137692 C23.9978436,1.93173451 23.9989053,1.94973363 23.9994829,1.96774538 L24,2 L24,6 C24,6.55228475 23.5522847,7 23,7 C22.4477153,7 22,6.55228475 22,6 L22,6 L22,4.414 L19.6071076,6.80879437 C20.4818819,7.97720873 21,9.42809788 21,11 C21,14.8659932 17.8659932,18 14,18 C12.7252362,18 11.530059,17.6592489 10.5005842,17.0638626 C9.74515287,17.5002478 8.90068306,17.8001445 8.00101007,17.9289666 L8,20 L9,20 C9.51283584,20 9.93550716,20.3860402 9.99327227,20.8833789 L10,21 C10,21.5522847 9.55228475,22 9,22 L9,22 L8,22 L8,23 C8,23.5128358 7.61395981,23.9355072 7.11662113,23.9932723 L7,24 C6.44771525,24 6,23.5522847 6,23 L6,23 L6,22 L5,22 C4.48716416,22 4.06449284,21.6139598 4.00672773,21.1166211 L4,21 C4,20.4477153 4.44771525,20 5,20 L5,20 L6,20 L5.99999177,17.92911 C2.60770164,17.4438768 0,14.5264691 0,11 C0,7.13400675 3.13400675,4 7,4 C8.27517269,4 9.47070764,4.34096967 10.5004062,4.93671038 C11.530059,4.34075107 12.7252362,4 14,4 C15.5719021,4 17.0227913,4.51811807 18.1912056,5.39289239 L20.584,3 L19,3 C18.4871642,3 18.0644928,2.61395981 18.0067277,2.11662113 L18,2 C18,1.44771525 18.4477153,1 19,1 L19,1 Z M7,6 C4.23857625,6 2,8.23857625 2,11 C2,13.7614237 4.23857625,16 7,16 C7.62954738,16 8.23192,15.883651 8.78678798,15.6712829 C7.67551784,14.4319358 7,12.7948557 7,11 C7,9.2051443 7.67551784,7.56806424 8.78618256,6.32913079 C8.23192,6.11634899 7.62954738,6 7,6 Z M14,6 C13.3704526,6 12.76808,6.11634899 12.213212,6.3287171 C13.3244822,7.56806424 14,9.2051443 14,11 C14,12.7948557 13.3244822,14.4319358 12.2138174,15.6708692 C12.76808,15.883651 13.3704526,16 14,16 C16.7614237,16 19,13.7614237 19,11 C19,9.72949482 18.5261306,8.56966778 17.7454927,7.68761975 C17.6533143,7.64376382 17.5685503,7.58276391 17.4928932,7.50710678 C17.4172361,7.43144965 17.3562362,7.3466857 17.3098935,7.25634348 C16.4303322,6.47386939 15.2705052,6 14,6 Z M10.5011206,7.43038252 L10.4644661,7.46446609 C9.55964406,8.36928813 9,9.61928813 9,11 C9,12.3981325 9.57385539,13.662234 10.4988794,14.5696175 C11.425736,13.663134 12,12.3986303 12,11 C12,9.60186746 11.4261446,8.33776604 10.5011206,7.43038252 Z">
                                    </path>
                                </svg> Unisex
                            @elseif($LocationData->available_for == 1)
                                <svg style="width:24px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                     viewBox="0 0 229.575 229.575" style="enable-background:new 0 0 229.575 229.575;" xml:space="preserve">
                                <path d="M187.433,72.646C187.433,32.589,154.844,0,114.788,0S42.142,32.589,42.142,72.646c0,34.071,23.583,62.726,55.279,70.532
                                    v24.238H73.788v34.732h23.634v27.427h34.732v-27.427h23.634v-34.732h-23.634v-24.238C163.85,135.371,187.433,106.717,187.433,72.646
                                    z M114.788,110.559c-20.905,0-37.913-17.008-37.913-37.913s17.008-37.913,37.913-37.913s37.913,17.008,37.913,37.913
                                    S135.693,110.559,114.788,110.559z"/><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g>
                                </svg> For Female
                            @elseif($LocationData->available_for == 2)
                                <svg style="width:24px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 354.422 354.422" style="enable-background:new 0 0 354.422 354.422;"
                                     xml:space="preserve">
                                <g>
                                    <path d="M281.604,9.914c-0.393-3.917-2.849-7.329-6.438-8.945c-3.587-1.611-7.765-1.191-10.964,1.105l-56.708,40.829
                                        c-3.195,2.3-4.92,6.131-4.524,10.049c0.394,3.917,2.85,7.326,6.438,8.942l21.809,9.822L184.41,175.633
                                        c-8.796-2.763-17.896-4.216-27.185-4.216c-35.943,0-68.702,21.168-83.456,53.933c-10.035,22.277-10.794,47.133-2.136,69.983
                                        c8.657,22.85,25.695,40.961,47.978,50.999c11.915,5.364,24.541,8.087,37.52,8.09c0.006,0,0.006,0,0.011,0
                                        c35.929,0,68.688-21.17,83.446-53.934c10.035-22.279,10.794-47.136,2.139-69.984c-7.339-19.374-20.75-35.278-38.225-45.807
                                        l46.812-103.927l21.803,9.819c1.447,0.651,2.989,0.972,4.524,0.972c0.038,0,0.078,0,0.11,0c6.086,0,11.02-4.935,11.02-11.021
                                        C288.772,79.627,281.604,9.914,281.604,9.914z M222.115,238.312c6.571,17.345,5.994,36.213-1.625,53.126
                                        c-11.203,24.873-36.07,40.945-63.35,40.945c-0.003,0-0.005,0-0.008,0c-9.839-0.003-19.419-2.069-28.473-6.147
                                        c-16.911-7.616-29.844-21.366-36.417-38.712c-6.572-17.346-5.996-36.212,1.623-53.124c11.2-24.872,36.069-40.941,63.358-40.941
                                        c9.841,0,19.422,2.068,28.477,6.147C202.612,207.221,215.545,220.969,222.115,238.312z M235.863,49.638l25.725-18.522l3.178,31.542
                                        L235.863,49.638z"/>
                                </g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg> For Male
                            @endif
                        </h6>
                    @endif
                    <h6 class="">
                        <svg width="24" height="24">
                            <path fill="#101928" fill-rule="evenodd"
                                d="M17.727 2a1 1 0 0 1 .994.883l.006.117v5.882c1.843.505 3.164 1.55 3.267 2.942L22 12v6.545C22 20.642 19.284 22 16.09 22c-3.106 0-5.761-1.286-5.902-3.286l-.006-.169V12l.006-.168c.141-2 2.797-3.287 5.903-3.287.215 0 .427.007.637.019V4H4v7.818h3.91a1 1 0 0 1 .116 1.993l-.117.007H3a1 1 0 0 1-.993-.883L2 12.818V3a1 1 0 0 1 .883-.993L3 2h14.727zM20 17.914c-1.052.522-2.43.813-3.91.813s-2.857-.292-3.91-.814l.002.632c0 .615 1.679 1.455 3.909 1.455 2.15 0 3.788-.78 3.903-1.388l.006-.067v-.631zm-7.82-3.273l.002.632c0 .615 1.679 1.454 3.909 1.454 2.15 0 3.788-.78 3.903-1.388l.006-.066v-.632c-1.052.522-2.43.814-3.91.814s-2.857-.292-3.91-.814zm3.91-4.096c-2.229 0-3.908.84-3.908 1.455 0 .615 1.68 1.455 3.909 1.455 2.23 0 3.909-.84 3.909-1.455 0-.615-1.68-1.455-3.91-1.455zm-5.726-4.454a1.818 1.818 0 1 1 0 3.636 1.818 1.818 0 0 1 0-3.636z">
                            </path>
                        </svg>
                        Pay by app
                    </h6>
                </div>
                <h6 class="text-justify">{{ !empty($LocationData) ? $LocationData->location_description : "" }}</h6>
            </div>
            <div class="col-12 col-md-5 px-5" id="location">
                <h3 class="font-weight-bolder text-center">Location</h3>
                <div class="d-flex align-items-center">
                    <h3 class="p-3 feather-map-pin bg-primary rounded-circle text-white"></h3>
                    <h5 class="pl-3">{{ ($LocationData->location_address != "") ? $LocationData->location_address : "" }}</h5>
                </div>
                <hr>
                <a class="text-dark d-flex justify-content-between collapsed" data-toggle="collapse" href="#collapseExample"
                    role="button" aria-expanded="false" aria-controls="collapseExample">
                    <span class="d-flex">
                        <h5 class="font-weight-bolder feather-clock mr-2"></h5>
                        <h6 class="font-weight-bolder">
                            @if($store_is_open == 1)
                                {{ 'Open now '.date('h:i A',strtotime($store_open_time)).' '.date('h:i A',strtotime($store_close_time)) }}
                            @endif
                        </h6>
                    </span>
                    <span>
                        <i class="feather-chevron-down font-weight-bolder"></i>
                    </span>
                </a>
                <div class="collapse" id="collapseExample">
                    <div class="card-body mr-2 pr-0">
                        <div class="d-flex justify-content-between">
                            <div class="day">
                                {{--  font-weight-bolder --}}
                                <h6 class="mb-1 {{ ($curDay == "Monday") ? 'font-weight-bolder' : '' }}">Monday</h6>
                                <h6 class="mb-1 {{ ($curDay == "Tuesday") ? 'font-weight-bolder' : '' }}">Tuesday</h6>
                                <h6 class="mb-1 {{ ($curDay == "Wednesday") ? 'font-weight-bolder' : '' }}">Wednesday</h6>
                                <h6 class="mb-1 {{ ($curDay == "Thursday") ? 'font-weight-bolder' : '' }}">Thursday</h6>
                                <h6 class="mb-1 {{ ($curDay == "Friday") ? 'font-weight-bolder' : '' }}">Friday</h6>
                                <h6 class="mb-1 {{ ($curDay == "Saturday") ? 'font-weight-bolder' : '' }}">Saturday</h6>
                                <h6 class="mb-1 {{ ($curDay == "Sunday") ? 'font-weight-bolder' : '' }}">Sunday</h6>
                            </div>
                            <div class="status">
                                <h6 class="mb-1 {{ ($curDay == "Monday") ? 'font-weight-bolder' : '' }}">
                                    @if(isset($monday_is_open) && $monday_is_open == 1)
                                        {{ date('h:i A',strtotime($monday_open_time)) }} - {{ date('h:i A',strtotime($monday_close_time)) }}
                                    @else
                                        Close
                                    @endif
                                </h6>
                                <h6 class="mb-1 {{ ($curDay == "Tuesday") ? 'font-weight-bolder' : '' }}">
                                    @if(isset($tuesday_is_open) && $tuesday_is_open == 1)
                                        {{ date('h:i A',strtotime($tuesday_open_time)) }} - {{ date('h:i A',strtotime($tuesday_close_time)) }}
                                    @else
                                        Close
                                    @endif
                                </h6>
                                <h6 class="mb-1 {{ ($curDay == "Wednesday") ? 'font-weight-bolder' : '' }}">
                                    @if(isset($wednesday_is_open) && $wednesday_is_open == 1)
                                        {{ date('h:i A',strtotime($wednesday_open_time)) }} - {{ date('h:i A',strtotime($wednesday_close_time)) }}
                                    @else
                                        Close
                                    @endif
                                </h6>
                                <h6 class="mb-1 {{ ($curDay == "Thursday") ? 'font-weight-bolder' : '' }}">
                                    @if(isset($thursday_is_open) && $thursday_is_open == 1)
                                        {{ date('h:i A',strtotime($thursday_open_time)) }} - {{ date('h:i A',strtotime($thursday_close_time)) }}
                                    @else
                                        Close
                                    @endif
                                </h6>
                                <h6 class="mb-1 {{ ($curDay == "Friday") ? 'font-weight-bolder' : '' }}">
                                    @if(isset($friday_is_open) && $friday_is_open == 1)
                                        {{ date('h:i A',strtotime($friday_open_time)) }} - {{ date('h:i A',strtotime($friday_close_time)) }}
                                    @else
                                        Close
                                    @endif
                                </h6>
                                <h6 class="mb-1 {{ ($curDay == "Saturday") ? 'font-weight-bolder' : '' }}">
                                    @if(isset($saturday_is_open) && $saturday_is_open == 1)
                                        {{ date('h:i A',strtotime($saturday_open_time)) }} - {{ date('h:i A',strtotime($saturday_close_time)) }}
                                    @else
                                        Close
                                    @endif
                                </h6>
                                <h6 class="mb-1 {{ ($curDay == "Sunday") ? 'font-weight-bolder' : '' }}">
                                     @if(isset($sunday_is_open) && $sunday_is_open == 1)
                                        {{ date('h:i A',strtotime($sunday_open_time)) }} - {{ date('h:i A',strtotime($sunday_close_time)) }}
                                    @else
                                        Close
                                    @endif
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(!$Location_image->isEmpty())
        <section>
            <div class="container">
                <div class="py-3 title d-flex align-items-center" data-aos="fade-up">
                    <h2 class="m-0">Image Gallery</h2>
                </div>
                <div class="img-slider">
                    @foreach($Location_image as $images)
                    <div class="image-gallery gallery-item px-1 py-3">
                        <img alt="image-gallery" class="img-fluid" src="{{ url($images->image_path) }}" />
                    </div>
                    @endforeach
                    {{-- <div class="image-gallery gallery-item px-1 py-3">
                        <img alt="image-gallery" class="img-fluid" src="{{ asset('frontend/img/featured2.jpg') }}" />
                    </div>
                    <div class="image-gallery gallery-item px-1 py-3">
                        <img alt="image-gallery" class="img-fluid" src="{{ asset('frontend/img/featured3.jpg') }}" />
                    </div>
                    <div class="image-gallery gallery-item px-1 py-3">
                        <img alt="image-gallery" class="img-fluid" src="{{ asset('frontend/img/featured4.jpg') }}" />
                    </div>
                    <div class="image-gallery gallery-item px-1 py-3">
                        <img alt="image-gallery" class="img-fluid" src="{{ asset('frontend/img/featured5.jpg') }}" />
                    </div> --}}
                </div>
                <hr class="my-4">
            </div>
        </section>
    @endif
    @if(!$voucherData->isEmpty())
    <section>
        <div class="container">
            <div class="py-3 title d-flex align-items-center" data-aos="fade-up">
                <h2 class="m-0">Vouchers</h2>
            </div>
            <div class="catagory voucher-slider">
                @foreach($voucherData as $voucher)
                    <div class="voucher-item px-1 py-3">
                        <a href="{{ route('frontSellVoucher', [$encrptLocationId, 'voucher_id' => $voucher->id]) }}">
                            <div class="card voucher-card {{ ($voucher->color) ? $voucher->color : 'purple' }}" style="@if(!$voucher->is_online) cursor: unset; @endif">
                                <div class="card-body text-white p-6">
                                    <div class="my-3 text-center">
                                        <h6 class="font-weight-bold">Voucher value</h6>
                                        <h2 class="font-weight-bolder">CA ${{ $voucher->retailprice }}</h2>
                                    </div>
                                    <div class="mt-10 font-weight-bold d-flex justify-content-between">
                                        <div>
                                            <h6 class="font-weight-bolder">{{ $voucher->name }}</h6>
                                            <h6>Redeem on {{ count(explode(',', $voucher->services_ids)) }} services</h6>
                                        </div>
                                        <div class="text-right">
                                            <h6 class="font-weight-bolder">CA ${{ $voucher->retailprice }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
            <h6 class="text-primary"><a href="{{ route('frontSellVoucher', $encrptLocationId) }}">See All Vouchers</a></h6>
            <hr class="my-4">
        </div>
    </section>
    @endif
    <section class="my-4">
        <div class="container">
            <div class="py-3 title d-flex align-items-center" data-aos="fade-up">
                <h2 class="m-0">Services</h2>
            </div>
            <div class="row justify-content-between">
                <div class="col-md-3 col-12">
                    <div class="nav flex-column nav-pills services" style="position: sticky;top: 20px;" id="v-pills-tab"
                        role="tablist" aria-orientation="vertical">
                       {{--  @php
                        dd($serviceCategory);
                        @endphp --}}
                        @if(!empty($serviceCategory))
                            @foreach($serviceCategory as $catKey => $serviceCat)
                                <a class="nav-link font-weight-bolder {{ ($catKey == 0) ? 'active' : '' }}" id="featured-id-{{ $serviceCat->id }}" data-toggle="pill" href="#category_{{ $serviceCat->id }}" role="tab" aria-controls="packages" aria-selected="false">{{ $serviceCat->category_title }} <span class="p-2 font-weight-bolder badge badge-pill badge-light">{{ count($serviceCat->services) }}</span></a>
                            @endforeach
                        @endif
                        {{-- <a class="nav-link font-weight-bolder" id="packages-tab" data-toggle="pill" href="#packages"
                            role="tab" aria-controls="packages" aria-selected="false">
                            Packages <span class="p-2 font-weight-bolder badge badge-pill badge-light">2</span></a>
                        <a class="nav-link font-weight-bolder" id="hair-care-tab" data-toggle="pill" href="#hair-care"
                            role="tab" aria-controls="hair-care" aria-selected="false">
                            Hair Care <span class="p-2 font-weight-bolder badge badge-pill badge-light">3</span></a></a>
                        <a class="nav-link font-weight-bolder" id="skin-care-tab" data-toggle="pill" href="#skin-care"
                            role="tab" aria-controls="skin-care" aria-selected="false">
                            Skincare <span class="p-2 font-weight-bolder badge badge-pill badge-light">5</span></a>
                        </a> --}}
                    </div>
                </div>
                <div class="col-md-8 col-12">
                    <div class="tab-content" id="v-pills-tabContent">
                       @if(!empty($serviceCategory))
                            @foreach($serviceCategory as $catKey => $serviceCat)
                                <div class="tab-pane fade {{ ($catKey == 0) ? 'show active' : '' }}" id="category_{{ $serviceCat->id }}" role="tabpanel"
                                    aria-labelledby="feature-tab">
                                    <ul class="list-style-none services">
                                        @if(!empty($serviceCat->services))
                                            @foreach($serviceCat->services as $serviceKey => $service)
                                                @php
                                                    $servicePrice = \DB::table('services_price')->where('service_id',$service->id)->first();
                                                    $priceType = '';
                                                    if(!empty($servicePrice)) {
                                                        if($servicePrice->price_type=='free'){ $priceType='Free';}
                                                        if($servicePrice->price_type=='from'){ $priceType='From';}
                                                        if($servicePrice->price_type=='fixed'){ $priceType='Fixed';}
                                                    }
                                                @endphp
                                               
                                                <li>
                                                    <div class="title d-flex justify-content-between">
                                                        <h5>{{ $service->service_name }}</h5> <p style="float: right;"> {{ $priceType }} </p>
                                                    </div>
                                                    <h6 style="float: right;"> {{ !empty($servicePrice) ? $servicePrice->price : '' }} </h6>

                                                    {{-- @dump($servicePrice) --}}

                                                    <h6 class="font-weight-bolder text-muted"> {{ $serviceCat->serviceDuration ?? '' }}</h6> 
                                                        <p>{{ $service->service_description }}</p> 
                                                </li>
                                                <hr>
                                            @endforeach
                                        @endif
                                        </ul>
                                    </ul>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if($is_allowed_staff_selection)
    <section id="book-with-staff" class="my-4 py-4">
        <div class="container">
            <div class="py-3 title d-flex align-items-center" data-aos="fade-up">
                <h2 class="m-0">Book with staff</h2>
            </div>
            <div id="staff-container">
                @foreach($staffLists as $staff)
                    <figure>
						<a href="{{ route('frontBooking', [$encrptLocationId, 'staffId' =>  $staff->id]) }}">
							<div class="staff-profile" style="background: url('{{ asset('frontend/img/featured2.jpg') }}') no-repeat center;background-size: cover;">
								{{--  <div class="rating rating-stars"><i class="feather-star text-warning mr-2"></i>5.0</div>  --}}
							</div>
							<h6>{{ $staff->first_name.' '.$staff->last_name }}</h6>
							<p class="role font-weight-bolder text-muted">{{ $staff->staff_title }}</p>
						</a>	
                    </figure>
                @endforeach
            </div>
            <hr>
        </div>
    </section>
    @endif
    
    <section class="reviews my-4 py-4" id="ratings-and-reviews">
        <div class="container">
            <div class="py-3 title d-flex align-items-center" data-aos="fade-up">
                <h2 class="m-0">Reviews</h2>
            </div>
            <div class="row">
                <div class="col-md-4 col-12">
                    <div class="bg-white rounded p-3 mb-3 clearfix graph-star-rating rounded shadow-sm">
                        <ul class="rating-stars list-unstyled">
                            <li>
                                <i class="feather-star {{ ($locationRating >= 1) ? 'star_active' : '' }}"></i>
                                <i class="feather-star {{ ($locationRating >= 2) ? 'star_active' : '' }}"></i>
                                <i class="feather-star {{ ($locationRating >= 3) ? 'star_active' : '' }}"></i>
                                <i class="feather-star {{ ($locationRating >= 4) ? 'star_active' : '' }}"></i>
                                <i class="feather-star {{ ($locationRating >= 5) ? 'star_active' : '' }}"></i>
                            </li>
                        </ul>
                        <h6 class="mb-4 mt-1 font-weight-bolder">{{ $locationRating }} 
                        @if($locationRating > 4)
                            Great
                        @elseif($locationRating > 3)
                            Good
                        @elseif($locationRating > 2)
                            Okay
                        @elseif($locationRating > 1)
                            Bad
                        @elseif($locationRating > 0)
                            Terrible
                        @endif
                        <span class="font-weight-normal text-muted">{{ $totalRatings }} Rating</span></h6>
                        <div class="graph-star-rating-body">
                            <div class="rating-list">
                                <input type="checkbox" {{ ($starWiseRating['5starRating'] == 0) ? 'disabled' : 'checked' }} class="form-control ratingCheckbox" data-rating="5" style="font-size: 4px;width: 15px;">
                                <div class="rating-list-center">
                                    <div class="progress" style="height: 10px;">
                                        <div role="progressbar" class="progress-bar bg-warning" aria-valuenow="{{ $ratingWisePercentage['5starRating'] }}"
                                            aria-valuemin="0" aria-valuemax="100" style="width: {{ $ratingWisePercentage['5starRating'] }}%;"></div>
                                    </div>
                                </div>
                                <h5 class="rating-list-right font-weight-bolder">{{ $starWiseRating['5starRating'] }}</h5>
                            </div>
                            <div class="rating-list">
                                <input type="checkbox" {{ ($starWiseRating['4starRating'] == 0) ? 'disabled' : 'checked' }} class="form-control ratingCheckbox" data-rating="4" style="font-size: 4px;width: 15px;">
                                <div class="rating-list-center">
                                    <div class="progress" style="height: 10px;">
                                        <div role="progressbar" class="progress-bar bg-warning" aria-valuenow="{{ $ratingWisePercentage['4starRating'] }}"
                                            aria-valuemin="0" aria-valuemax="100" style="width: {{ $ratingWisePercentage['4starRating'] }}%;"></div>
                                    </div>
                                </div>
                                <h5 class="rating-list-right font-weight-bolder">{{ $starWiseRating['4starRating'] }}</h5>
                            </div>
                            <div class="rating-list">
                                <input type="checkbox" {{ ($starWiseRating['3starRating'] == 0) ? 'disabled' : 'checked' }} class="form-control ratingCheckbox" data-rating="3" style="font-size: 4px;width: 15px;">
                                <div class="rating-list-center">
                                    <div class="progress" style="height: 10px;">
                                        <div role="progressbar" class="progress-bar bg-warning" aria-valuenow="{{ $ratingWisePercentage['3starRating'] }}"
                                            aria-valuemin="0" aria-valuemax="100" style="width: {{ $ratingWisePercentage['3starRating'] }}%;"></div>
                                    </div>
                                </div>
                                <h5 class="rating-list-right font-weight-bolder">{{ $starWiseRating['3starRating'] }}</h5>
                            </div>
                            <div class="rating-list">
                                <input type="checkbox" {{ ($starWiseRating['2starRating'] == 0) ? 'disabled' : 'checked' }} class="form-control ratingCheckbox" data-rating="2" style="font-size: 4px;width: 15px;">
                                <div class="rating-list-center">
                                    <div class="progress" style="height: 10px;">
                                        <div role="progressbar" class="progress-bar bg-warning" aria-valuenow="{{ $ratingWisePercentage['2starRating'] }}"
                                            aria-valuemin="0" aria-valuemax="100" style="width: {{ $ratingWisePercentage['2starRating'] }}%;"></div>
                                    </div>
                                </div>
                                <h5 class="rating-list-right font-weight-bolder">{{ $starWiseRating['2starRating'] }}</h5>
                            </div>
                            <div class="rating-list">
                                <input type="checkbox" {{ ($starWiseRating['1starRating'] == 0) ? 'disabled' : 'checked' }} class="form-control ratingCheckbox" data-rating="1"
                                    style="font-size: 4px;width: 15px;">
                                <div class="rating-list-center">
                                    <div class="progress" style="height: 10px;">
                                        <div role="progressbar" class="progress-bar bg-warning" aria-valuenow="{{ $ratingWisePercentage['1starRating'] }}"
                                            aria-valuemin="0" aria-valuemax="100" style="width: {{ $ratingWisePercentage['1starRating'] }}%;"></div>
                                    </div>
                                </div>
                                <h5 class="rating-list-right font-weight-bolder">{{ $starWiseRating['1starRating'] }}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card my-3 bg-content">
                        <div class="d-flex">
                            <div class="m-2" style="width: 40px;height: 40px;">
                                <svg class="" viewBox="0 0 23 27">
                                    <defs>
                                        <path
                                            d="M20.754 3.59L11.034.025a1.099 1.099 0 00-.468 0L.846 3.59C.35 3.7 0 4.142 0 4.652v10.874C0 21.532 4.835 26.4 10.8 26.4c5.965 0 10.8-4.868 10.8-10.874V4.652c0-.51-.351-.952-.846-1.062z"
                                            id="a"></path>
                                    </defs>
                                    <g transform="translate(.6 .2)" fill="none" fill-rule="evenodd">
                                        <mask>
                                            <use xlink:href="#a"></use>
                                        </mask>
                                        <use fill="#39B374" xlink:href="#a"></use>
                                        <path
                                            d="M14.664 10.078L9 15.53l-2.064-1.987a.925.925 0 00-1.261.01.843.843 0 00-.011 1.215l2.7 2.6a.925.925 0 001.272 0l6.3-6.065a.843.843 0 00-.01-1.215.925.925 0 00-1.262-.01z"
                                            fill="#FFF"></path>
                                    </g>
                                </svg>
                            </div>
                            <div class="">
                                <h6 class="font-weight-bolder m-2">Reviews you can trust</h6>
                                <p class="m-2">All our ratings are from genuine customers, following verified visits
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8 col-12">
                    <div class="bg-white p-3 mb-3 restaurant-detailed-ratings-and-reviews shadow-sm rounded">
                        <h6 class="mb-1">All Ratings and Reviews</h6>

                        <div class="reviewsContainer" style="max-height: 280px;overflow-y: scroll;overflow-x:hidden"></div>
                        <a class="text-center w-100 d-block mt-3 font-weight-bold fetchReviews" href="javascript:void(0)" data-lastId="">Load More Reviews</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if(!empty($nearestLocations))
    <section class="my-4 py-4 bg-content">
        <div class="container">

            <div class="py-3 title d-flex align-items-center" data-aos="fade-up">
                <h2 class="m-0">Venues near {{ ($LocationData->location_name != "") ? $LocationData->location_name : "" }}</h2>
            </div>

            <div class="most_popular">
                <div class="row">
                        @foreach($nearestLocations as $nearKey => $nearLocation)
                        @php
            				$ratcount = \DB::table('fuser_location_review')->where('location_id',$nearLocation->id)->selectRaw('COUNT(location_id) AS count')->first()->count;
                        @endphp

                        @php
                            $rating = \DB::table('fuser_location_review')->where(['location_id' => $nearLocation->id, 'status' => 'publish'])->selectRaw('SUM(rating)/COUNT(location_id) AS avg_rating')->first()->avg_rating;
                            $ratcount = \DB::table('fuser_location_review')->where(['location_id' => $nearLocation->id, 'status' => 'publish'])->selectRaw('COUNT(location_id) AS count')->first()->count;

                            $rat = round($rating,1);
                        @endphp
                            <div class="col-md-3 pb-3" data-aos="zoom-in" data-aos-delay="100">
                                <div class="list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm">
                                    <div class="list-card-image">
                                        <div class="star position-absolute">
                                            <span class="badge badge-success">
                                                <i class="feather-star"></i> {{ $rat }} ({{ $ratcount }}+)
                                            </span>
                                        </div>
                                        {{-- <div class="member-plan text-danger position-absolute"><span class="badge badge-light p-2">Men Only</span></div> --}}
                                        <div class="member-plan position-absolute"><span class="badge badge-dark"></span>
                                        </div>
                                        <a href="{{ url('search_detail/'.Crypt::encryptString($nearLocation->id)) }}">
                                            @if(!empty($nearLocation->location_image))
                                                <img alt="#" src="{{ url($nearLocation->location_image) }}" class="img-fluid item-img w-100">
                                            @else 
                                                <img alt="image-gallery" class="img-fluid" src="{{ asset('frontend/img/featured2.jpg') }}" />
                                            @endif
                                        </a>
                                    </div>
                                    <div class="p-3 position-relative">
                                        <div class="list-card-body">
                                            <h6 class="mb-1"><a href="{{ url('search_detail/'.Crypt::encryptString($nearLocation->id)) }}" class="text-black">{{ $nearLocation->location_name }}</a>
                                            </h6>
                                            <p class="text-gray mb-1 small">{{ $nearLocation->location_address }}
                                            </p>
                                            <p class="text-gray mb-1 rating">
                                            </p>
                                            <ul class="rating-stars list-unstyled">
                                                <li>
                                                    <i class="feather-star {{ ($rat >= 1) ? 'star_active' : '' }}"></i>
                                                    <i class="feather-star {{ ($rat >= 2) ? 'star_active' : '' }}"></i>
                                                    <i class="feather-star {{ ($rat >= 3) ? 'star_active' : '' }}"></i>
                                                    <i class="feather-star {{ ($rat >= 4) ? 'star_active' : '' }}"></i>
                                                    <i class="feather-star {{ ($rat >= 5) ? 'star_active' : '' }}"></i>
                                                </li>
                                            </ul>
                                            <p></p>
                                        </div>
                                        <!-- <div class="list-card-badge">
                                            <span class="badge badge-danger">OFFER</span> <small>65% OSAHAN50</small>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    {{-- <div class="col-md-3 pb-3" data-aos="zoom-in" data-aos-delay="200">
                        <div class="list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm">
                            <div class="list-card-image">
                                <div class="star position-absolute"><span class="badge badge-success"><i
                                            class="feather-star"></i> 3.1 (300+)</span></div>
                                <div class="member-plan text-danger position-absolute"><span
                                        class="badge badge-light p-2">Women
                                        Only</span>
                                </div>
                                <div class="member-plan position-absolute"><span class="badge badge-dark"></span>
                                </div>
                                <a href="salon_detail.html">
                                    <img alt="#" src="{{ asset('frontend/img/featured2.jpg') }}" class="img-fluid item-img w-100">
                                </a>
                            </div>
                            <div class="p-3 position-relative">
                                <div class="list-card-body">
                                    <h6 class="mb-1"><a href="salon_detail.html" class="text-black">Thai Famous
                                            Indian
                                            Cuisine</a></h6>
                                    <p class="text-gray mb-1 small">152 New Cavendish Street, London (Fitzrovia),
                                        England</p>
                                    <p class="text-gray mb-1 rating">
                                    </p>
                                    <ul class="rating-stars list-unstyled">
                                        <li>
                                            <i class="feather-star star_active"></i>
                                            <i class="feather-star star_active"></i>
                                            <i class="feather-star star_active"></i>
                                            <i class="feather-star star_active"></i>
                                            <i class="feather-star"></i>
                                        </li>
                                    </ul>
                                    <p></p>
                                </div>
                                <div class="list-card-badge">
                                    <span class="badge badge-success">OFFER</span> <small>65% off</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 pb-3" data-aos="zoom-in" data-aos-delay="300">
                        <div class="list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm">
                            <div class="list-card-image">
                                <div class="star position-absolute"><span class="badge badge-success"><i
                                            class="feather-star"></i> 3.5
                                        (300+)</span></div>

                                <div class="member-plan position-absolute"><span class="badge badge-dark"></span>
                                </div>
                                <a href="salon_detail.html">
                                    <img alt="#" src="{{ asset('frontend/img/featured3.jpg') }}" class="img-fluid item-img w-100">
                                </a>
                            </div>
                            <div class="p-3 position-relative">
                                <div class="list-card-body">
                                    <h6 class="mb-1"><a href="salon_detail.html" class="text-black">ELP Barbershop
                                        </a>
                                    </h6>
                                    <p class="text-gray mb-1 small">52 New Cavendish Street, London (Fitzrovia),
                                        England</p>
                                    <p class="text-gray mb-1 rating">
                                    </p>
                                    <ul class="rating-stars list-unstyled">
                                        <li>
                                            <i class="feather-star star_active"></i>
                                            <i class="feather-star star_active"></i>
                                            <i class="feather-star star_active"></i>
                                            <i class="feather-star star_active"></i>
                                            <i class="feather-star"></i>
                                        </li>
                                    </ul>
                                    <p></p>
                                </div>
                                <div class="list-card-badge">
                                    <span class="badge badge-danger">OFFER</span> <small>65% OSAHAN50</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 pb-3" data-aos="zoom-in" data-aos-delay="400">
                        <div class="list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm">
                            <div class="list-card-image">
                                <div class="star position-absolute"><span class="badge badge-success"><i
                                            class="feather-star"></i> 3.1
                                        (30+)</span></div>

                                <div class="member-plan position-absolute"><span class="badge badge-dark"></span>
                                </div>
                                <a href="salon_detail.html">
                                    <img alt="#" src="{{ asset('frontend/img/featured4.jpg') }}" class="img-fluid item-img w-100">
                                </a>
                            </div>
                            <div class="p-3 position-relative">
                                <div class="list-card-body">
                                    <h6 class="mb-1"><a href="salon_detail.html" class="text-black">Bond</a></h6>
                                    <p class="text-gray mb-1 small">Test</p>
                                    <p class="text-gray mb-1 rating">
                                    </p>
                                    <ul class="rating-stars list-unstyled">
                                        <li>
                                            <i class="feather-star star_active"></i>
                                            <i class="feather-star star_active"></i>
                                            <i class="feather-star star_active"></i>
                                            <i class="feather-star star_active"></i>
                                            <i class="feather-star"></i>
                                        </li>
                                    </ul>
                                    <p></p>
                                </div>
                                <div class="list-card-badge">
                                    <span class="badge badge-success">OFFER</span> <small>65% off</small>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </section>

    @endif

@endsection
@section('scripts')
<script type="text/javascript" src="{{ url('public/frontend/js/strftime-min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function(){
        fetchReviews(true);

        $(document).on('click','.favourite',function(){
            var self = $(this);
            var location_id = self.attr('data-location_id');

            $.ajax({
                url: '{{ url("toggleFavourite") }}',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    "_token": "{{ csrf_token() }}",
                    location_id: location_id
                },
                success: function(response) {
                    if(response.status) {
                        responseMessages('success', response.message);

                        if(response.marked_favourite) {
                            self.find('i').css('color', '#f00');
                        } else {
                            self.find('i').css('color', '#fff');
                        }
                    } else {
                        responseMessages('error', response.message);
                    }
                },
                error: function(response) {
                    responseMessages('error', 'Something went wrong. Please reload and try again.');
                },
                complete: function(response) {

                }
            });
        });

        $(document).on('click','.fetchReviews',function(){
            fetchReviews();
        });

        $(document).on('change','.ratingCheckbox',function(){
            $('.ratingCheckbox').each(function(){
                if($(this).is(':checked')) {
                    $('.ratingDiv'+$(this).attr('data-rating')).show(500);
                } else {
                    $('.ratingDiv'+$(this).attr('data-rating')).hide(500);
                }
            });
        });
    });

    function fetchReviews(onload = false) {

        var lastId = $('.fetchReviews').attr('data-lastId');

        $.ajax({
            url: '{{ url("fetchReviews") }}',
            type: 'POST',
            dataType: 'JSON',
            data: {
                "_token": "{{ csrf_token() }}",
                lastId: lastId,
                locationId: '{{ $LocationData->id }}'
            },
            success: function(response) {
                if(response.status) {
                    var content = '';
                    var url = '{{ url("public/") }}/';

                    if(response.data.length > 0) {
                        for( var index = 0; index < response.data.length; index++ ) {
                            var dateObj = new Date( response.data[index].updated_at );
                            var dayOfYear = dateObj.strftime("%a, %d %b %G");
                            var fuserImage = ($.isEmptyObject(response.data[index].fuser) ? response.data[index].fuser.image : "");

                            if($.trim(fuserImage) == '') {
                                fuserImage = 'frontend/img/user2.png';
                            }

                            content += '<div class="reviews-members py-3 ratingDiv'+response.data[index].rating+'">';
                                content += '<div class="media">';
                                    content += '<a><img alt="#" src="'+ url + fuserImage+'" width="60px" height="60px" class="mr-3 rounded-pill"></a>';
                                    content += '<div class="media-body">';
                                        content += '<div class="reviews-members-header">';
                                            content += '<div class="star-rating float-right">';
                                                content += '<div class="d-inline-block" style="font-size: 14px;">';
                                                    content += '<i class="feather-star '+((response.data[index].rating >= 1) ? "text-warning" : "")+'"></i>';
                                                    content += '<i class="feather-star '+((response.data[index].rating >= 2) ? "text-warning" : "")+'"></i>';
                                                    content += '<i class="feather-star '+((response.data[index].rating >= 3) ? "text-warning" : "")+'"></i>';
                                                    content += '<i class="feather-star '+((response.data[index].rating >= 4) ? "text-warning" : "")+'"></i>';
                                                    content += '<i class="feather-star '+((response.data[index].rating >= 5) ? "text-warning" : "")+'"></i>';
                                                content += '</div>';
                                            content += '</div>';
                                            content += '<h6 class="mb-0"><a class="text-dark">'+($.isEmptyObject(response.data[index].fuser) ? response.data[index].fuser.name+' '+response.data[index].fuser.last_name : "")+'</a></h6>';
                                            content += '<p class="text-muted small">'+dayOfYear+'</p>';
                                        content += '</div>';
                                        content += '<div class="reviews-members-body">';
                                            content += '<p>'+response.data[index].feedback+'</p>';
                                        content += '</div>';
                                    content += '</div>';
                                content += '</div>';
                            content += '</div>';
                            content += '<hr>';

                            lastId = response.data[index].id;
                        }

                        $('.reviewsContainer').append(content);
                        $('.fetchReviews').attr('data-lastId', lastId);

                        $(".reviewsContainer").animate({ scrollTop: $(".reviewsContainer")[0].scrollHeight}, 1000);
                    } else {
                        if(!onload) {
                            responseMessages('error','There are no more reviews.');
                        }
                    }

                    if(response.hideMoreButton) {
                        $('.fetchReviews').removeClass('d-block').hide();
                    }
                } else {
                    if(!onload) {
                        responseMessages('error', response.message);
                    }
                }
            },
            error: function(response) {
                if(!onload) {
                    responseMessages('error', 'Something went wrong. Please reload and try again.');
                }
            },
            complete: function(response) {

            }
        });
    }
</script>
@endsection