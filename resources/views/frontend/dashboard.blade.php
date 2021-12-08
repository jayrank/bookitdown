{{-- Extends layout --}}
@extends('frontend.layouts.index')

{{-- CSS Section --}}  
@section('innercss')
<style type="text/css">
	.list-card-image img.item-img {
	    height: 186px;
	    object-fit: cover;
	}
	.single-featured-venue {
		min-height: 325px;
	}
</style>
@endsection
@section('content')

	<!-- offer sectio slider -->
	<!-- <div class="bg-white">
		<div class="container">
			<h2 class="pt-3" data-aos="fade-up">Top categories</h2>
			<div class="catagory offer-slider">
				@foreach($cat as $value)
					<div class="cat-item px-1 py-3" data-aos="fade-right" data-aos-delay="100">
						<a class="d-block text-center shadow-sm" href="{{ route('search',['category' => Crypt::encryptString($value->id)]) }}">
							@php echo $value->image @endphp	
							<h6>{{ $value->name }}</h6>
						</a>
					</div>
				@endforeach
			</div>
		</div>
	</div> -->
	<div class="container pb-5">
		<!-- trending this week -->
		<div class="pt-4 pb-2 title d-flex align-items-center">
			<h2 class="m-0" data-aos="fade-right">Featured venues</h2>
			<a class="font-weight-bold ml-auto" href="{{ route('getallsalon') }}">View all <i
					class="feather-chevrons-right"></i></a>
		</div>
		<!-- slider -->
		<div class="trending-slider">
			@foreach($locationData as $locKey => $location)
			@php
				$rating = \DB::table('fuser_location_review')->where('location_id',$location->id)->selectRaw('SUM(rating)/COUNT(location_id) AS avg_rating')->first()->avg_rating;
				$ratcount = \DB::table('fuser_location_review')->where('location_id',$location->id)->selectRaw('COUNT(location_id) AS count')->first()->count;
				$rat = round($rating,1);
			@endphp

			<div class="osahan-slider-item" data-aos="fade-left" data-aos-delay="100">
				<div class="list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm single-featured-venue">
					<div class="list-card-image">
						<div class="star position-absolute"><span class="badge badge-success"><i
									class="feather-star"></i> {{ $rat }} ({{ $ratcount }}+)</span></div>

						<div class="member-plan position-absolute">
							{{--  <span class="badge badge-dark">Featured</span>  --}}
						</div>
						<a href="{{ route('search_detail',['id' => Crypt::encryptString($location->id)]) }}">
							<img alt="#" src="{{ ($location->location_image != "") ? url($location->location_image) : asset('uploads/location_images/1259983363.jpg') }}" class="img-fluid item-img w-100">
						</a>
					</div>
					<div class="p-3 position-relative">
						<div class="list-card-body">
							<h6 class="mb-1"><a href="{{ route('search_detail',['id' => Crypt::encryptString($location->id)]) }}" class="text-black">{{ $location->location_name }}</a>
							</h6>
							<p class="text-gray mb-3">{{ $location->location_address }}</p>
						</div>
					</div>
				</div>
			</div>
			@endforeach
		</div>
	</div>

	<div class="bg-white py-5">
	    <div class="container">
	        <!-- Most popular -->
	        <div class="py-3 title d-flex align-items-center" data-aos="fade-up">
	            <h2 class="m-0">Most popular</h2>
	            <a class="font-weight-bold ml-auto" href="{{ route('getallsalon') }}">{{ count($locationData) }} places <i
	                    class="feather-chevrons-right"></i></a>
	        </div>
	        <!-- Most popular -->
	        <div class="most_popular">
	            <div class="row">
					@foreach($locationData as $locKey => $location)
					@php
						$rating = \DB::table('fuser_location_review')->where('location_id',$location->id)->selectRaw('SUM(rating)/COUNT(location_id) AS avg_rating')->first()->avg_rating;
						$ratcount = \DB::table('fuser_location_review')->where('location_id',$location->id)->selectRaw('COUNT(location_id) AS count')->first()->count;
						$rat = round($rating,1);
					@endphp
	                <div class="col-md-3 pb-3" data-aos="zoom-in" data-aos-delay="100">
	                    <div class="list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm">
	                        <div class="list-card-image">
	                            <div class="star position-absolute"><span class="badge badge-success"><i
	                                        class="feather-star"></i> {{ $rat }} ({{ $ratcount }}+)</span></div>

	                            <div class="member-plan position-absolute"><span class="badge badge-dark"></span>
	                            </div>
	                            <a href="{{ route('search_detail',['id' => Crypt::encryptString($location->id)]) }}">
	                                <img alt="#" src="{{ ($location->location_image != "") ? url($location->location_image) : asset('uploads/location_images/1259983363.jpg') }}" class="img-fluid item-img w-100">
	                            </a>
	                        </div>
	                        <div class="p-3 position-relative">
	                            <div class="list-card-body">
	                                <h6 class="mb-1"><a href="{{ route('search_detail',['id' => Crypt::encryptString($location->id)]) }}" class="text-black">{{ $location->location_name }}
	                                    </a>
	                                </h6>
	                                <p class="text-gray mb-1 small">{{ $location->location_address }}
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
	                            {{--  <div class="list-card-badge">
	                                <span class="badge badge-danger">OFFER</span> <small>65% OSAHAN50</small>
	                            </div>  --}}
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
	                                    class="badge badge-light p-2">Women Only</span>
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
	                </div>
	                <div class="col-md-3 pb-3" data-aos="zoom-in" data-aos-delay="100">
	                    <div class="list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm">
	                        <div class="list-card-image">
	                            <div class="star position-absolute"><span class="badge badge-success"><i
	                                        class="feather-star"></i> 3.5 (300+)</span></div>

	                            <div class="member-plan position-absolute"><span class="badge badge-dark"></span>
	                            </div>
	                            <a href="salon_detail.html">
	                                <img alt="#" src="{{ asset('frontend/img/featured5.jpg') }}" class="img-fluid item-img w-100">
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
	                <div class="col-md-3 pb-3" data-aos="zoom-in" data-aos-delay="200">
	                    <div class="list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm">
	                        <div class="list-card-image">
	                            <div class="star position-absolute"><span class="badge badge-success"><i
	                                        class="feather-star"></i> 3.1 (30+)</span></div>

	                            <div class="member-plan position-absolute"><span class="badge badge-dark"></span>
	                            </div>
	                            <a href="salon_detail.html">
	                                <img alt="#" src="{{ asset('frontend/img/featured6.jpg') }}" class="img-fluid item-img w-100">
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
	                </div>
	                <div class="col-md-3 pb-3" data-aos="zoom-in" data-aos-delay="300">
	                    <div class="list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm">
	                        <div class="list-card-image">
	                            <div class="star position-absolute"><span class="badge badge-success"><i
	                                        class="feather-star"></i> 4.1
	                                    (300+)</span></div>

	                            <div class="member-plan position-absolute"><span class="badge badge-dark"></span>
	                            </div>
	                            <a href="salon_detail.html">
	                                <img alt="#" src="{{ asset('frontend/img/featured1.jpg') }}" class="img-fluid item-img w-100">
	                            </a>
	                        </div>
	                        <div class="p-3 position-relative">
	                            <div class="list-card-body">
	                                <h6 class="mb-1"><a href="salon_detail.html" class="text-black">Chelsea Hair and
	                                        Skin
	                                        Clinic By KARDA
	                                    </a>
	                                </h6>
	                                <p class="text-gray mb-1 small">67 Amwell Street, London (Islington), England
	                                </p>
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
	                                    (300+)</span></div>

	                            <div class="member-plan position-absolute"><span class="badge badge-dark"></span>
	                            </div>
	                            <a href="salon_detail.html">
	                                <img alt="#" src="{{ asset('frontend/img/featured1.jpg') }}" class="img-fluid item-img w-100">
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
	                </div> --}}
	            </div>
	        </div>
	    </div>
	</div>
	<section class="bg-white">
	    <div class="container-fluid">
	        <div class="row">
	            <div class="col-12 col-md-5 p-5">
	                <h1 class="font-weight-bolder my-3">Business software without limits, 100% subscription-free
	                </h1>
	                <h5>Supercharge your business for free with the world's top booking platform for salons and
	                    spas. Independently voted no. 1
	                    by industry professionals. Stop paying subscription fees and join the revolution! </h5>
	            </div>
	            <div class="col-12 col-md-7 pt-4 pr-0">
	                <img src="{{ asset('/frontend/img/partners.png') }}" width="100%" style="box-shadow: -4px -2px 10px 2px gainsboro;">
	            </div>
	        </div>
	    </div>
	</section>

	<div class="facts bg-primary aos-init aos-animate">
	    <div class="container-fluid p-0">
	        <div id="projectFacts" class="sectionClass">
	            <div class="fullWidth eight columns">
	                <div class="projectFactsWrap">
	                    <div class="item" data-number="12" style="visibility: visible;" data-aos="fade-up"
	                        data-aos-delay="200">
	                        <p data-toggle="counter-up" class="number">{{ count($salon) }}</p>
	                        <span></span>
	                        <p>Partner businesses</p>
	                    </div>
	                    <div class="item" data-number="55" style="visibility: visible;" data-aos="fade-down"
	                        data-aos-delay="200">
	                        <p data-toggle="counter-up" class="number">{{ count($staff) }}</p>
	                        <span></span>
	                        <p>Stylists and professionals</p>
	                    </div>
	                    <div class="item" data-number="359" style="visibility: visible;" data-aos="fade-up"
	                        data-aos-delay="200">
	                        <p data-toggle="counter-up" class="number">{{ count($ap) }}</p>
	                        <span></span>
	                        <p>Appointments booked</p>
	                    </div>
	                    <div class="item" data-number="246" style="visibility: visible;" data-aos="fade-down"
	                        data-aos-delay="200">
	                        <p data-toggle="counter-up" class="number">246</p>
	                        <span></span>
	                        <p>with ScheduleDown available</p>
	                    </div>
	                </div>
	            </div>
	        </div>
	    </div>
	</div>

	<div class="bg-white">
	    <div class="container pb-5">
	        <!-- trending this week -->
	        <div class="pt-4 pb-2 title d-flex align-items-center">
	            <h2 class="m-0">Book with the best</h2>
	        </div>
	        <!-- slider -->
	        <div class="trending-slider">
	            <div class="osahan-slider-item" data-aos="fade-up" data-aos-delay="100">
	                <figure class="snip">
	                    <blockquote>Calvin: Sometimes when I'm talking with others, my words can't keep up with my
	                        thoughts. I
	                        wonder why we
	                        think faster than we speak. Hobbes: Probably so we can think twice. </blockquote>
	                    <div class="author">
	                        <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/331810/sq-sample1.jpg"
	                            alt="sq-sample1" />
	                        <h5>Pelican Steve <span> LittleSnippets</span></h5>
	                    </div>
	                </figure>
	            </div>
	            <div class="osahan-slider-item" data-aos="fade-up" data-aos-delay="200">
	                <figure class="snip">
	                    <blockquote>My behaviour is addictive functioning in a disease process of toxic
	                        co-dependency. I
	                        need
	                        holistic
	                        healing and wellness before I'll accept any responsibility for my actions.</blockquote>
	                    <div class="author">
	                        <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/331810/sq-sample29.jpg"
	                            alt="sq-sample29" />
	                        <h5>Eleanor Faint<span> LittleSnippets</span></h5>
	                    </div>
	                </figure>
	            </div>
	            <div class="osahan-slider-item" data-aos="fade-up" data-aos-delay="300">
	                <figure class="snip">
	                    <blockquote>My behaviour is addictive functioning in a disease process of toxic
	                        co-dependency. I
	                        need
	                        holistic
	                        healing and wellness before I'll accept any responsibility for my actions.</blockquote>
	                    <div class="author">
	                        <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/331810/sq-sample29.jpg"
	                            alt="sq-sample29" />
	                        <h5>Eleanor Faint<span> LittleSnippets</span></h5>
	                    </div>
	                </figure>
	            </div>
	            <div class="osahan-slider-item" data-aos="fade-up" data-aos-delay="400">
	                <figure class="snip hover">
	                    <blockquote>Thank you. before I begin, I'd like everyone to notice that my report is in a
	                        professional,
	                        clear
	                        plastic binder...When a report looks this good, you know it'll get an A. That's a tip
	                        kids.
	                        Write it
	                        down.
	                    </blockquote>
	                    <div class="author">
	                        <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/331810/sq-sample24.jpg"
	                            alt="sq-sample24" />
	                        <h5>Max Conversion<span> LittleSnippets</span></h5>
	                    </div>
	                </figure>
	            </div>
	        </div>
	    </div>
	</div>

@endsection
@section('scripts')
@endsection