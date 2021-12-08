{{-- Extends layout --}}
@extends('frontend.layouts.index')

{{-- CSS Section --}}  
@section('innercss')
@endsection

@section('content')

<div class="osahan-popular">
    <!-- Most popular -->
    <div class="container">
        <div class="search py-4">
            <h3>Favourite</h3>
        </div>
        <div class="most_popular">
            <div class="row" id="filteredData">
            @if($LocationData->isNotEmpty())
            	@foreach($LocationData as $locKey => $location)
	                <div class="col-12 col-md-4 mb-3">
	                    <div class="list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm">
	                        <div class="list-card-image">
	                            <div class="popular-slider">
                                    @if(!$location->images->isEmpty())
                                        @foreach($location->images as $image)
        	                                <div class="osahan-slider-item">
        	                                    <a href="{{ url('search_detail/'.Crypt::encryptString($location->id)) }}">
        	                                        <img alt="#" src="{{ url($image->image_path) }}" class="img-fluid item-img w-100 rounded">
        	                                    </a>
        	                                </div>
                                        @endforeach
                                    @else
                                        <div class="osahan-slider-item">
                                            <a href="{{ url('search_detail/'.Crypt::encryptString($location->id)) }}">
                                                <img alt="#" src="{{ asset('frontend/img/featured1.jpg') }}" class="img-fluid item-img w-100 rounded">
                                            </a>
                                        </div>
                                    @endif
	                            </div>
	                        </div>
	                        <div class="p-3 position-relative">
	                            <div class="list-card-body">
	                                <h6 class="mb-1"><a href="{{ url('search_detail/'.Crypt::encryptString($location->id)) }}" class="text-black">{{ $location->location_name }}</a>
	                                </h6>
	                                <p class="text-gray mb-1 small">{{ $location->location_address }}</p>
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
	            @endforeach
            @else
                <i style="margin: 1%; font-size: 2em;">Favourites list is empty!</i>
            @endif
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
@endsection