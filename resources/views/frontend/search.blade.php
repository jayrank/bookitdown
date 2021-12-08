{{-- Extends layout --}}
@extends('frontend.layouts.index')

{{-- CSS Section --}}  
@section('innercss')
<style type="text/css">
    .list-card-image {
        height: 186px;
    }
    /*.popular-slider {
        width: 100%;
    }*/
    .slick-arrow {
        padding: 0 30px;
    }
    .list-card-image .popular-slider img {
        height: 186px;
        object-fit: cover;
    }
</style>
@endsection

@section('content')

<div class="osahan-popular">
    <!-- Most popular -->
    <div class="container">
        <div class="search py-4">
            {{-- <div class="input-group mb-3">
                <input type="text" class="form-control form-control-lg input_search border-right-0 locationSearchByName" id="inlineFormInputGroup" value="" placeholder="Search By Name">
                <input type="hidden" id="locationSearchByNameUrl" value="{{ route('searchFilter') }}">
                <div class="input-group-prepend">
                    <div class="btn input-group-text bg-white border_search border-left-0 text-primary"><i class="feather-search"></i></div>
                </div>
            </div> --}}
            {{-- <h6 class="mb-2"><span class="text-muted">Rajkot</span> &bull; Beauty Salon</h6> --}}
            <div class="d-flex flex-wrap align-items-center justify-content-between">
                <h5 class="mb-2"><span id="totalLocation">{{ count($LocationData) }}</span> results for <span id="searchResult">{{ Session::get("country") }}</span></h5>
                <div class="d-flex flex-wrap justify-content-center align-items-center">
                    <div class="dropdown mx-2 mw-220">
                        <a class="text-dark" href="#" id="filter-area" role="button" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <h5 class="text-primary border bg-white border-primary py-2 px-3"
                                style="border-radius: 50px;">
                                <span id="area-filter" class="area_filter">Nearest</span> <i
                                    class="feather-chevron-down pt-1 float-right"></i>
                            </h5>
                        </a>
                        <div class="dropdown-menu p-0" aria-labelledby="filter-area">
                            <div class="filter">
                                <div class="custom-control  border-bottom px-0 custom-radio">
                                    <input type="radio" id="area-nrearest" name="area-filter" class="custom-control-input nearestFilter" data-title="Nearest" value="Nearest">
                                    <label class="custom-control-label py-3 w-100 px-3"
                                        for="area-nrearest">Nearest</label>
                                </div>
                                <div class="custom-control  border-bottom px-0 custom-radio">
                                    <input type="radio" id="top-rated" name="area-filter" class="custom-control-input nearestFilter" data-title="Top rated" value="topRated" checked>
                                    <label class="custom-control-label py-3 w-100 px-3" for="top-rated">Top
                                        rated</label>
                                </div>
                                <div class="custom-control  border-bottom px-0 custom-radio">
                                    <input type="radio" id="newest" data-title="Newest" name="area-filter" class="custom-control-input nearestFilter" value="Newest">
                                    <label class="custom-control-label py-3 w-100 px-3" for="newest">Newest</label>
                                </div>
                                <div class="custom-control  border-bottom px-0 custom-radio">
                                    <input type="radio" id="Lowest price" data-title="Lowest Price" name="area-filter" class="custom-control-input nearestFilter" value="Lowest">
                                    <label class="custom-control-label py-3 w-100 px-3" for="Lowest price">Lowest Price</label>
                                </div>
                                <!-- Don't remove below elements with 'display:none'. Connected with Mobile Filter in views/layouts/index.blade.php -->
                                <div class="custom-control  border-bottom px-0 custom-radio" style="display: none;">
                                    <input type="radio" id="highest-price" data-title="Highest Price" name="area-filter" class="custom-control-input nearestFilter" value="Highest">
                                    <label class="custom-control-label py-3 w-100 px-3" for="Highest Price">Highest Price</label>
                                </div>
                                <div class="custom-control  border-bottom px-0 custom-radio" style="display: none;">
                                    <input type="radio" id="popular" data-title="Popular" name="area-filter" class="custom-control-input nearestFilter" value="Popular">
                                    <label class="custom-control-label py-3 w-100 px-3" for="Popular">Popular</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown mw-220 mx-2">
                        <a class="text-dark" href="#" id="filter-area" role="button" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <h5 class="text-primary  border bg-white border-primary py-2 px-3" style="border-radius: 50px;">
                                <span id="gender-filter" class="availableForHeading">Men and Women</span> <i class="feather-chevron-down pt-1 float-right"></i>
                            </h5>
                        </a>
                        <div class=" dropdown-menu p-0" aria-labelledby="filter-area">
                            <div class="filter">
                                <input type="hidden" id="availableForUrl" value="{{ route("searchFilter") }}">
                                <div class="custom-control  border-bottom px-0 custom-radio">
                                    <input type="radio" checked="" id="menandwomen" name="gender-filter" class="custom-control-input availableFor" data-title="Men and Women" value="0">
                                    <label class="custom-control-label py-3 w-100 px-3" for="menandwomen">Men and Women</label>
                                </div>
                                <div class="custom-control  border-bottom px-0 custom-radio">
                                    <input type="radio" id="men-only" name="gender-filter" class="custom-control-input availableFor" data-title="Men Only" value="1">
                                    <label class="custom-control-label py-3 w-100 px-3" for="men-only">Men Only</label>
                                </div>
                                <div class="custom-control  border-bottom px-0 custom-radio">
                                    <input type="radio" id="women-only" name="gender-filter" class="custom-control-input availableFor" data-title="Women Only" value="2">
                                    <label class="custom-control-label py-3 w-100 px-3" for="women-only">Women Only</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="most_popular">
            <div class="row" id="filteredData">
            	@foreach($LocationData as $locKey => $location)
                    @php
						$rating = \DB::table('fuser_location_review')->where(['location_id' => $location->id, 'status' => 'publish'])->selectRaw('SUM(rating)/COUNT(location_id) AS avg_rating')->first()->avg_rating;
						$ratcount = \DB::table('fuser_location_review')->where(['location_id' => $location->id, 'status' => 'publish'])->selectRaw('COUNT(location_id) AS count')->first()->count;
						$rat = round($rating,1);
					@endphp
	                <div class="col-12 col-md-4 mb-3">
	                    <div class="list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm">
	                        <div class="list-card-image">
	                            <div class="popular-slider">
                                    @if(!$location->images->isEmpty())
                                        @foreach($location->images as $image)
        	                                <div class="osahan-slider-item">
        	                                    <a href="{{ route('search_detail',['id' => Crypt::encryptString($location->id)]) }}">
        	                                        <img alt="#" src="{{ url($image->image_path) }}" class="img-fluid item-img w-100 rounded">
        	                                    </a>
        	                                </div>
                                        @endforeach
                                    @else
                                        <div class="osahan-slider-item">
                                            <a href="{{ route('search_detail',['id' => Crypt::encryptString($location->id)]) }}">
                                                <img alt="#" src="{{ asset('frontend/img/featured1.jpg') }}" class="img-fluid item-img w-100 rounded">
                                            </a>
                                        </div>
                                    @endif
	                            </div>
	                        </div>
	                        <div class="p-3 position-relative">
	                            <div class="list-card-body">
	                                <h6 class="mb-1"><a href="{{ route('search_detail',['id' => Crypt::encryptString($location->id)]) }}" class="text-black">{{ $location->location_name }}</a>
	                                </h6>
	                                <p class="text-gray mb-1 small">{{ $location->location_address }}</p>
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
	                            <div class="list-card-badge">
	                                {{--  <span class="badge badge-danger">OFFER</span> <small>65% OSAHAN50</small>  --}}
	                            </div>
	                        </div>
	                    </div>
	                </div>
	            @endforeach
            </div>
        </div>
    </div>
    <!-- Footer -->
    <div class="osahan-menu-fotter fixed-bottom bg-white px-3 py-2 text-center d-none">
        <div class="row">
            <div class="col">
                <a href="index.html" class="text-dark small font-weight-bold text-decoration-none">
                    <p class="h4 m-0"><i class="feather-home"></i></p>
                    Home
                </a>
            </div>
            <div class="col selected">
                <a href="trending.html" class="text-primary small font-weight-bold text-decoration-none">
                    <p class="h4 m-0"><i class="feather-map-pin text-danger"></i></p>
                    Trending
                </a>
            </div>
            <div class="col bg-white rounded-circle mt-n4 px-3 py-2">
                <div class="bg-primary rounded-circle mt-n0 shadow">
                    <a href="checkout.html" class="text-white small font-weight-bold text-decoration-none">
                        <i class="feather-calendar"></i>
                    </a>
                </div>
            </div>
            <div class="col">
                <a href="favorites.html" class="text-dark small font-weight-bold text-decoration-none">
                    <p class="h4 m-0"><i class="feather-heart"></i></p>
                    Favorites
                </a>
            </div>
            <div class="col">
                <a href="profile.html" class="text-dark small font-weight-bold text-decoration-none">
                    <p class="h4 m-0"><i class="feather-user"></i></p>
                    Profile
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script src="{{ asset('js/dashboard.js') }}"></script>
@endsection