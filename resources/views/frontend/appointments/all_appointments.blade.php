{{-- Extends layout --}}
@extends('frontend.layouts.index')

{{-- CSS Section --}}  
@section('innercss')
@endsection

<style type="text/css"> 
  .rating-section {
    width: 100%;
    text-align: center;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
  }
  .rate {
     float: left; 
    /*height: 46px;*/
    padding: 0 10px; 
  }
  .rate:not(:checked) > input {
      position:absolute;
      top:-9999px;
  }
  .rate:not(:checked) > label {
      float:right;
      /*width:1em;*/
      overflow:hidden;
      white-space:nowrap;
      cursor:pointer;
      font-size:30px;
      color:#ccc;
  }
  .rate:not(:checked) > label:before {
      content: '★ ';
      font-size: 76px;
  }
  .rate > input:checked ~ label {
      color: #ffc700;    
  }
  .rate:not(:checked) > label:hover,
  .rate:not(:checked) > label:hover ~ label {
      color: #deb217;  
  }
  .rate > input:checked + label:hover,
  .rate > input:checked + label:hover ~ label,
  .rate > input:checked ~ label:hover,
  .rate > input:checked ~ label:hover ~ label,
  .rate > label:hover ~ input:checked ~ label {
      color: #c59b08;
  } 
  .rate:not(:checked) > label p{
      font-size: 16px;
      margin: -20px 0 0;
  }
</style>
@section('content')
    <section>
        <div class="container-fluid p-0">
            <div class="my-custom-body-wrapper">
                <div class="my-custom-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12 col-md-3 p-0">
                                <div class="p-4" style="height:calc(100vh - 80px);overflow-y:scroll">
                                    <div class="">
                                        @if($upcomingAppointments->isEmpty())
                                            <h4 class="font-weight-bolder">No upcoming appointments</h4>
                                            <p class="text-muted">Have fun making some! Any booking you make will show up
                                                here.</p>

                                        @else

                                            <h4 class="font-weight-bolder">Upcoming appointments 
                                                <span class="badge badge-secondary rounded-circle">{{ $upcomingAppointments->count() }}</span>
                                            </h4>

                                            @foreach($upcomingAppointments as $key => $value)

                                            <a href="{{ url('myAppointments/'.base64_encode($value->id)) }}">
                                                <div class="card my-1">
                                                    <div class="card-body p-2">
                                                        <div class="d-flex flex-wrap align-items-center">
                                                            @if( !empty($value->location_image) )
                                                                <img src="{{ url($value->location_image) }}" alt="salon-img"
                                                                class="img-fluid rounded" width="120px" height="auto">
                                                            @else
                                                                <img src="{{ url('public/frontend/img/featured1.jpg') }}" alt="salon-img"
                                                                class="img-fluid rounded" width="120px" height="auto">
                                                            @endif
                                                            <div class="ml-md-2 ml-3">
                                                                <span class="text-muted">
                                                                    {{ date('d M Y', strtotime($value->appointment_date)) }}

                                                                    @if(count($value->apservice) > 0)
                                                                        {{ date('h:iA', strtotime($value->apservice[0]->start_time)) }}
                                                                    @endif
                                                                </span>
                                                                <h6 class="mb-0 font-weight-bolder" style="color: #000;">
                                                                    {{ $value->location_name }}
                                                                </h6>

                                                                @if(!empty($value->apservice))
                                                                    
                                                                    <p class="mb-1">
                                                                        @php $service_name = ''; @endphp

                                                                        @foreach($value->apservice as $a_key => $a_value)

                                                                            @php
                                                                                $service_name .= $a_value->service_name.', ';

                                                                            @endphp

                                                                        @endforeach

                                                                        {{ trim($service_name, ', ') }}
                                                                    </p>
                                                                @endif

                                                                @if($value->is_cancelled)
                                                                    <span class="p-1 rounded bagde badge-danger">
                                                                        Cancelled
                                                                    </span>
                                                                @else
                                                                    <span class="p-1 rounded bagde badge-success">
                                                                        @if($value->appointment_status == 0)
                                                                            New Appointment
                                                                        @elseif($value->appointment_status == 1)
                                                                            Confirmed
                                                                        @elseif($value->appointment_status == 2) 
                                                                            Arrived
                                                                        @elseif($value->appointment_status == 3) 
                                                                            Started
                                                                        @elseif($value->appointment_status == 4) 
                                                                            Completed
                                                                        @endif
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            @endforeach
                                        @endif
                                        
                                        <a href="{{ url('search') }}" class="my-3 btn btn-dark">Find Salons near me</a>

                                    </div>
                                    <div class="my-3">

                                        @if($pastAppointments->isEmpty())
                                            <h4 class="font-weight-bolder">No past appointments</h4>
                                            <p class="text-muted">You don’t have past appointments. Once you do, they'll show up here. Now get booking!</p>

                                        @else
                                            <h4 class="font-weight-bolder">Past appointments 
                                                <span class="badge badge-secondary rounded-circle">{{ $pastAppointments->count() }}</span>
                                            </h4>

                                            @foreach($pastAppointments as $key => $value)

                                            <a href="{{ url('myAppointments/'.base64_encode($value->id)) }}">
                                                <div class="card my-1">
                                                    <div class="card-body p-2">
                                                        <div class="d-flex flex-wrap align-items-center">
                                                            @if( !empty($value->location_image) )
                                                                <img src="{{ url($value->location_image) }}" alt="salon-img"
                                                                class="img-fluid rounded" width="120px" height="auto">
                                                            @else
                                                                <img src="{{ url('public/frontend/img/featured1.jpg') }}" alt="salon-img"
                                                                class="img-fluid rounded" width="120px" height="auto">
                                                            @endif
                                                            <div class="ml-1">
                                                                <span class="text-muted">
                                                                    {{ date('d M Y', strtotime($value->appointment_date)) }}

                                                                    @if(!empty($value->apservice[0]))
                                                                        {{ date('H:iA', strtotime($value->apservice[0]->start_time)) }}
                                                                    @endif
                                                                </span>
                                                                <h6 class="mb-0 font-weight-bolder" style="color: #000;">
                                                                    {{ $value->location_name }}
                                                                </h6>

                                                                @if(!empty($value->apservice))
                                                                    
                                                                    <p class="mb-1">
                                                                        @php $service_name = ''; @endphp

                                                                        @foreach($value->apservice as $a_key => $a_value)

                                                                            @php
                                                                                $service_name .= $a_value->service_name.', ';

                                                                            @endphp

                                                                        @endforeach

                                                                        {{ trim($service_name, ', ') }}
                                                                    </p>
                                                                @endif

                                                                @if($value->is_cancelled)
                                                                    <span class="p-1 rounded bagde badge-danger">
                                                                        Cancelled
                                                                    </span>
                                                                @else
                                                                    <span class="p-1 rounded bagde badge-success">
                                                                        @if($value->appointment_status == 0)
                                                                            New Appointment
                                                                        @elseif($value->appointment_status == 1)
                                                                            Confirmed
                                                                        @elseif($value->appointment_status == 2) 
                                                                            Arrived
                                                                        @elseif($value->appointment_status == 3) 
                                                                            Started
                                                                        @elseif($value->appointment_status == 4) 
                                                                            Completed
                                                                        @endif
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>

                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if(!empty($selectedAppointment))
                                <div class="col-12 col-md-9 p-0 bg-content"
                                    style="height:calc(100vh - 80px);overflow-y:scroll">
                                    <div class="p-4">
                                        <div class="card">
                                            <div class="card-body rounded bg-dark text-white pb-5">
                                                <div class="message">
                                                    <h2>
                                                        {{ date('d M Y', strtotime($selectedAppointment->appointment_date)) }}

                                                        @if(count($selectedAppointment->apservice) > 0)
                                                            {{ date('H:iA', strtotime($selectedAppointment->apservice[0]->start_time)) }}
                                                        @endif
                                                    </h2>
                                                        @if($selectedAppointment->is_cancelled)
                                                            <span class="p-2 my-2 badge badge-pill badge-danger select_appointment_status">
                                                                Cancelled
                                                            </span>
                                                        @else
                                                            <span class="p-2 my-2 badge badge-pill badge-success select_appointment_status">
                                                                @if($selectedAppointment->appointment_status == 0)
                                                                    New Appointment
                                                                @elseif($selectedAppointment->appointment_status == 1)
                                                                    Confirmed
                                                                @elseif($selectedAppointment->appointment_status == 2) 
                                                                    Arrived
                                                                @elseif($selectedAppointment->appointment_status == 3) 
                                                                    Started
                                                                @elseif($selectedAppointment->appointment_status == 4) 
                                                                    Completed
                                                                @endif
                                                            </span>
                                                        @endif
                                                    <div class="addr my-3 d-flex flex-wrap">
                                                        <div class="mr-3">
                                                            @if( !empty($selectedAppointment->location_image) )
                                                                <img src="{{ url($selectedAppointment->location_image) }}" alt="salon-img"
                                                                class="img-fluid rounded" width="200px" height="auto">
                                                            @else
                                                                <img src="{{ url('public/frontend/img/featured1.jpg') }}" alt="salon-img"
                                                                class="img-fluid rounded" width="120px" height="auto">
                                                            @endif
                                                            
                                                        </div>
                                                        <div>
                                                            <h4>{{ $selectedAppointment->location_name }}</h4>
                                                            <h6>
                                                                {{ $selectedAppointment->location_address }}
                                                            </h6>
                                                        </div>
                                                        <div class="selected_appointment_button_container" style="@if($selectedAppointment->is_cancelled) display: none; @endif margin: 10px auto;" >
                                                            <div class="icons justify-content-center d-flex ">
                                                                <!-- If status is apointment status is completed -->
                                                                @if($selectedAppointment->appointment_status == 4)
                                                                    <a href="#reviewModal" data-toggle="modal" class="mx-2 icon text-center font-weight-bolder" style="color: #fff;">
                                                                        <i class="text-dark feather-star  bg-warning px-2 py-2 fa-2x"></i>
                                                                        Review
                                                                    </a>
                                                                @endif

                                                                <a href="https://www.google.com/maps?daddr={{ $selectedAppointment->location_address }}" class="mx-2 icon text-center font-weight-bolder" target="_blank" style="color: #fff;">
                                                                    <i class="text-white feather-map-pin  bg-success px-2 py-2 fa-2x"></i>
                                                                    Direction
                                                                </a>
                                                                <!-- Hidden Temporarily -->
                                                                @php
                                                                    if(!empty($selectedAppointment->apservice[0])) {
                                                                        $appointmentDateTime = $selectedAppointment->appointment_date .' '.$selectedAppointment->apservice[0]->start_time;
                                                                    } else {
                                                                        $appointmentDateTime = $selectedAppointment->appointment_date;
                                                                    }
                                                                @endphp

                                                                @if( strtotime('now') <= strtotime($appointmentDateTime) )
                                                                    <a href="{{ route('frontReschedule', ['lid' => Crypt::encryptString($selectedAppointment->location_id), 'aid' => $selectedAppointment->id]) }}" class="mx-2 icon text-center font-weight-bolder" style="color: #fff;">
                                                                        <i class="text-white feather-calendar  bg-warning px-2 py-2 fa-2x"></i>
                                                                        Schedule
                                                                    </a>
                                                                @endif
                                                                <a href="javascript:void(0)" class="mx-2 icon text-center font-weight-bolder cancel_appointment" style="color: #fff;" data-appointment_id="{{ base64_encode($selectedAppointment->id) }}">
                                                                    <i class="text-white feather-x-circle bg-danger px-2 py-2 fa-2x"></i>
                                                                    Cancel
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    
                                                    <div class="rebook_button_container" style="@if(!$selectedAppointment->is_cancelled) display: none; @endif">
                                                        <!-- Hidden Temporarily -->
                                                        <!-- <div class="my-4 icons justify-content-center d-flex " >
                                                            <a href="javascript:void(0)" class="mx-2 icon text-center" style="color: #fff;">
                                                                <i class="text-dark feather-map-pin"></i>
                                                                Rebook
                                                            </a>
                                                        </div> -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card w-80 m-auto" style="bottom: 35px;">
                                            <div class="card-body">
                                                @if(!empty($selectedAppointment->apservice))

                                                    @foreach($selectedAppointment->apservice as $key => $value)
                                                        <div class="d-flex justify-content-between">
                                                            <div>
                                                                <h5 class="mb-0">{{ $value->service_name }}</h5>
                                                                <h6 class="text-muted">{{ $value->service_description }}</h6>
                                                            </div>
                                                            <div>
                                                                <h5>&#8377; {{ $value->special_price }}</h5>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                    @endforeach

                                                @endif
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <!--h6 class="text-muted">Taxes</h6-->
                                                        <h6 class="text-muted">Total</h6>
                                                    </div>
                                                    <div>
                                                        <!--h6 class="text-muted">&#8377; {{ !empty($selectedAppointment->invoice) ? $selectedAppointment->invoice->tax_amount : 0.00 }}</h6-->
                                                        <h6>&#8377; {{ $selectedAppointment->total_amount }}</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal" id="cancel_modal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <h4 style="margin: 1% 5% 5%;">Are you sure you want to cancel?</h4>

                    <div>
                        <button type="button" class="btn btn-default" data-dismiss="modal" style="width: 49%;">Close</button>
                        <button type="button" class="btn btn-primary confirm_cancel" style="width: 49%;">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="reviewModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <button type="button" class="close" data-dismiss="modal" style="font-size: 1rem">Skip</button>
                </div>
                <div class="modal-body">
                    <div class="text-center"> 
                        <form action="javascript:void(0)" method="post" class="review_form">
                            {!! csrf_field() !!}

                            <input type="hidden" name="location_id" value="{{ !empty($selectedAppointment) ?  $selectedAppointment->location_id : '' }}">
                            <input type="hidden" name="appointment_id" value="{{ !empty($selectedAppointment) ?  $selectedAppointment->id : '' }}">

                            <img alt="voucher-thumb" class="mb-4 border" src="{{ (isset($LocationInfo['location_image'])) ? $LocationInfo['location_image'] : asset('frontend/img/featured1.jpg') }}" width="80px" height="80px" style="border-radius: 20px;">
                            <h2 class="font-weight-bolder">How was {{ !empty($selectedAppointment) ? $selectedAppointment->location_name : '' }}?</h2>  
                            <div class="rating-section">
                              <div class="rate">
                                <input type="radio" id="star5" name="rating" class="rating" value="5" />
                                <label for="star5" title="text"><p>Great</p></label>
                                <input type="radio" id="star4" name="rating" class="rating" value="4" />
                                <label for="star4" title="text"><p>Good</p></label>
                                <input type="radio" id="star3" name="rating" class="rating" value="3" />
                                <label for="star3" title="text"><p>Okay</p></label>
                                <input type="radio" id="star2" name="rating" class="rating" value="2" />
                                <label for="star2" title="text"><p>Bad</p></label>
                                <input type="radio" id="star1" name="rating" class="rating" value="1" />
                                <label for="star1" title="text"><p>Terrible</p></label>
                              </div>
                            </div>
                            <div class="p-4 form-group mb-0 text-left">
                                <label class="h6 d-flex justify-content-between">
                                  Add comment
                                  <div id="the-count">
                                    <span id="current">0</span>
                                    <span id="maximum">/ 600</span>
                                  </div>
                                </label> 
                                <textarea class="form-control" rows="4" name="feedback" id="the-textarea" maxlength="600" placeholder="Share your feedback"></textarea>
                                <button type="submit" class="btn btn-block btn-dark btn-lg font-weight-bolder mt-3">Continue</button>
                            </div>
                        </form>
                    </div>
                </div> 
            </div>
        </div>
    </div>
	
	<div class="modal" id="consultationFormModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">Maybe later</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
					<div class="text-center"> 
					
						<img alt="voucher-thumb" class="mb-4 border" src="{{ (isset($LocationInfo['location_image'])) ? $LocationInfo['location_image'] : asset('frontend/img/featured1.jpg') }}" width="80px" height="80px" style="border-radius: 20px;">
						
						<h2 class="font-weight-bolder">{{ (isset($LocationInfo['location_name'])) ? ($LocationInfo['location_name']) : '' }} would like you to complete a consultation form</h2>  
						
                        <div class="p-4 form-group mb-0 text-left">
                            <label class="h6 d-flex justify-content-between">Save time by completing the consultation form before your appointment on {{ !empty($selectedAppointment) ? date("d M Y",strtotime($selectedAppointment->appointment_date)) : '' }}.</label> 
                            <button type="button" class="btn btn-block btn-dark btn-lg font-weight-bolder mt-3" onclick="window.location.href='{{ route('myConsultationForm') }}'">Continue</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	
@endsection
@section('scripts')
<script type="text/javascript">
    $(document).ready(function(){
		@php
			if(isset($isConsultationForm) && $isConsultationForm != ''){
		@endphp
		$("#consultationFormModal").modal('show');
		@php
			}
		@endphp
		
        $(document).off('click','.cancel_appointment').on('click','.cancel_appointment', function(){
            $('#cancel_modal').modal('show');
        });

        $(document).off('click','.confirm_cancel').on('click','.confirm_cancel', function(){
            var appointment_id = $('.cancel_appointment').attr('data-appointment_id');

            $.ajax({
                url: '{{ url("cancelAppointment") }}',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    "_token": "{{ csrf_token() }}",
                    appointment_id: appointment_id
                },
                success: function(response) {
                    if(response.status) {
                        responseMessages('success', response.message);

                        $('.select_appointment_status').text('Cancelled').removeClass('badge-success').addClass('badge-danger');
                        $('.selected_appointment_button_container').hide();
                        $('.rebook_button_container').show();
                    } else {
						if(response.is_redirect == 1) {
							location.href = response.redirect_url;	
						} else {	
							responseMessages('error', response.message);
						}	
                    }

                    $('#cancel_modal').modal('hide');
                },
                error: function(response) {
                    responseMessages('error', 'Something went wrong. Please reload and try again.');
                },
                complete: function(response) {

                }
            });
        });

        $('textarea').keyup(function() { 
          var characterCount = $(this).val().length,
              current = $('#current'),
              maximum = $('#maximum'),
              theCount = $('#the-count');
            
          current.text(characterCount); 
          
          /*This isn't entirely necessary, just playin around*/
          if (characterCount < 70) {
            current.css('color', '#666');
          }
          if (characterCount > 70 && characterCount < 90) {
            current.css('color', '#6d5555');
          }
          if (characterCount > 90 && characterCount < 100) {
            current.css('color', '#793535');
          }
          if (characterCount > 100 && characterCount < 120) {
            current.css('color', '#841c1c');
          }
          if (characterCount > 120 && characterCount < 139) {
            current.css('color', '#8f0001');
          }
          
          if (characterCount >= 140) {
            maximum.css('color', '#8f0001');
            current.css('color', '#8f0001');
            theCount.css('font-weight','bold');
          } else {
            maximum.css('color','#666');
            theCount.css('font-weight','normal');
          }  
        });

        $(document).on('submit', '.review_form', function(){
            
            var form_data = $(this).serialize();

            $.ajax({
                url: '{{ url("postReview") }}',
                type: 'POST',
                dataType: 'JSON',
                data: form_data,
                success: function(response) {
                    if(response.status) {
                        $('#reviewModal').modal('hide');
                        responseMessages('success', response.message);

                        $('.review_form').find("textarea").val("");
                        $('.review_form').find('.rating:checked').prop('checked', false);
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
    });
</script>

@endsection