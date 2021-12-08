{{-- Extends layout --}}
@extends('frontend.layouts.index')

{{-- CSS Section --}}  
@section('innercss')
@endsection

@section('content')
<style>
    .parpal{
        background: linear-gradient(-45deg, rgb(190, 74, 244) 0%, rgb(92, 55, 246) 100%);
    }
    .blue{
        background: linear-gradient(-225deg, rgb(11, 109, 217) 0%, rgb(95, 171, 255) 100%);
    }
    .black{
        background: linear-gradient(-225deg, rgb(16, 25, 40) 0%, rgb(32, 48, 71) 100%);
    }
    .green{
        background: linear-gradient(-45deg, rgb(0, 166, 156) 0%, rgb(0, 157, 98) 100%);
    }
    .orange{
        background: linear-gradient(-45deg, rgb(237, 176, 27) 0%, rgb(222, 100, 38) 100%);
    }
</style>
<section style="background: rgb(242, 242, 247)">
    <div class="container-fluid p-0">
        <div class="my-custom-body-wrapper">
            <div class="my-custom-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 col-md-4 p-0">
                            <div class="p-4" style="height:calc(100vh - 80px);overflow-y:scroll"> 
                                <div class="row my-3">
                                    <div class="d-flex align-items-center w-100 my-2">
                                        <h4 class="font-weight-bolde">Active <span
                                                class="badge badge-light bg-white rounded-circle">{{ $activePaidPlan->count() }}</span></h4>
                                    </div>
                                    @if($activePaidPlan->isNotEmpty())
                                        @foreach($activePaidPlan as $key => $value) 
                                            <div class="my-1 w-100">
                                                <a href="{{ url('myPaidPlans/'.$value->id) }}">
                                                    <div class="voucherSection">
                                                        <div class="voucher-list-item {{ !empty($value->color) ? $value->color : 'parpal' }} square">
                                                            <div class="voucher-card" style="min-height: auto;">
                                                                <h6 class="mb-0">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 14" style="height: 20px; width: 20px; margin-right: 8px;"><path d="M9.333 4.667A4.68 4.68 0 0114 9.333 4.68 4.68 0 019.333 14a4.68 4.68 0 01-4.666-4.667 4.68 4.68 0 014.666-4.666zm.187 2.8H8.4v2.8h2.8v-1.12H9.52v-1.68zM11.2 0c1.03 0 1.867.836 1.867 1.867l-.001 3.266h-.933v-.934L.933 4.2v4.2c0 .479.36.873.825.927l.109.006h1.399v.933h-1.4A1.867 1.867 0 010 8.4V1.867C0 .836.836 0 1.867 0H11.2zm0 .933H1.867a.933.933 0 00-.927.825l-.007.109v1.4l11.2-.001v-1.4A.933.933 0 0011.31.94L11.2.933z" fill="#FFF" fill-rule="evenodd"></path></svg>
                                                                    {{ $value->valid_for }} plan
                                                                </h6>
                                                                <h3 class="font-weight-bolder mb-0 pb-4">{{ $value->name }}</h3>
                                                            </div>
                                                            <h5 class="font-weight-bolder">{{ $value->description }}</h5>
                                                            <h6 class="mb-0">Valid until {{ date('d M Y', strtotime($value->end_date)) }}</h6>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div> 
                                        @endforeach
                                    @else
                                        <h4 class="font-weight-bolder">You have no active paid plan</h4>
                                        <p class="text-muted">They will show here once you purchase one.</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if(!empty($selectedPaidPlan))
                            <div class="col-12 col-md-8 bg-content" style="display: flex; flex-direction: column; padding-top: 32px; padding-bottom: 50px;">
                                <div class="voucher-details">
                                    <div class="d-flex align-items-start flex-row">
                                        <div class="voucher-preview"> 
                                            <div class="text-center voucher-card {{ !empty($value->color) ? $value->color : 'parpal' }} square justify-content-center">
                                                <div class="p-4 text-center"> 
                                                    <img alt="voucher-thumb" class="rounded mb-4" src="{{ asset('assets/images/thumb.jpg') }}" width="80px" height="80px">
                                                    <h5 class="font-weight-bold">{{ $selectedPaidPlan->description }}</h5>
                                                    <h6 class="text-grey">{{ $selectedPaidPlan->location_name }}</h6> 
                                                </div>
                                                <div class="add-vouchers-value">
                                                    <div class="vouchersInner">
                                                        <h6 class="">Sessions</h6>
                                                        <h3 class="font-weight-bolder mb-0">{{ $selectedPaidPlan->no_of_sessions }} sessions</h3> 
                                                    </div>
                                                </div>
                                                <div class="p-4 vouchers-bottom">
                                                    <h6 class="mb-1 font-weight-bold">Redeem on <a class="d-inline-flex align-items-center font-weight-bold text-white" data-toggle="modal" href="#servicesModal">
                                                        @php
                                                            if(!empty($selectedPaidPlan->service_id)) {
                                                                $service_id_array = explode(',',$selectedPaidPlan->service_id);
                                                                $total_services = count($service_id_array);
                                                            } else {
                                                                $total_services = 0;
                                                            }
                                                        @endphp

                                                        {{ $total_services }} services <i class="feather-chevron-right icon-sm"></i></a>
                                                    </h6>
                                                    <h6 class="mb-1">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 14" style="height: 20px; width: 20px; margin-right: 8px;"><path d="M9.333 4.667A4.68 4.68 0 0114 9.333 4.68 4.68 0 019.333 14a4.68 4.68 0 01-4.666-4.667 4.68 4.68 0 014.666-4.666zm.187 2.8H8.4v2.8h2.8v-1.12H9.52v-1.68zM11.2 0c1.03 0 1.867.836 1.867 1.867l-.001 3.266h-.933v-.934L.933 4.2v4.2c0 .479.36.873.825.927l.109.006h1.399v.933h-1.4A1.867 1.867 0 010 8.4V1.867C0 .836.836 0 1.867 0H11.2zm0 .933H1.867a.933.933 0 00-.927.825l-.007.109v1.4l11.2-.001v-1.4A.933.933 0 0011.31.94L11.2.933z" fill="#FFF" fill-rule="evenodd"></path></svg>
                                                            {{ $selectedPaidPlan->valid_for }} plan
                                                        </h6>
                                                    <h6 class="mb-1 font-weight-bold">Valid until {{ date('d M Y', strtotime($selectedPaidPlan->end_date)) }} </h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="voucherDetailWrapper">
                                            <h2 class="font-weight-bolder mb-0 pb-4">{{ $selectedPaidPlan->name }}</h2>
                                            <a href="#" class="text-white btn btn-block btn-dark btn-lg font-weight-bolder mb-4">Book now</a>
                                            <button class="icon-button-invoice" data-toggle="modal" data-target="#activityLogModal">
                                                <div class="styled__ButtonBox">
                                                    <div class="styled__VisualLayer"></div>
                                                    <svg class="" viewBox="0 0 25 24" style="width: 24px; height: 24px;"><g fill="none" fill-rule="evenodd"><path d="M3.3333 12.1667c0-4.3483 3.055-8.0982 7.3135-8.9773 4.2585-.879 8.5486 1.3546 10.2705 5.3474 1.722 3.9928.4017 8.646-3.1605 11.1396-3.5622 2.4936-8.3862 2.1414-11.5485-.843" stroke="#101928" stroke-width="1.5"></path><path stroke="#101928" stroke-width="1.5" stroke-linecap="square" d="M12 7.1667v5h5"></path><polygon fill="#101928" fill-rule="nonzero" points="4 22.1666667 2 16.5 8 16.8333333"></polygon></g></svg>
                                                </div>
                                                <h6 class="mt-2">Activity<br> log</h6>
                                            </button>
                                            <hr class="my-4">
                                            <h6 class="font-weight-bolder">Paid plan description</h6>
                                            <h6>{{ $selectedPaidPlan->name }}</h6>
                                            <h6 class="mt-4"><a href="#tcModal" data-toggle="modal">See paid plans Terms & Conditions</a></h6>
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

<div class="modal fade" id="tcModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header align-items-center">
                <h4 class="modal-title font-weight-bolder">Terms & Conditions</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <h6>{{ !empty($selectedPaidPlan) ? $selectedPaidPlan->description : '' }}</h6>
            </div> 
        </div>
    </div>
</div>

<div class="modal fade" id="servicesModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header align-items-center">
                <h4 class="modal-title font-weight-bolder">Redeemable services</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body pt-0">
                @if(!empty($serviceCategory))
                    @foreach($serviceCategory as $key => $service)
                        <div>
                            @if(!empty($service))
                                @foreach($service as $sKey => $sValue)

                                    @if($sKey == 0) 
                                        <h4 class="font-weight-bolder mb-4 mt-3 category-header">{{ $sValue->category_title }}</h4>
                                    @endif

                                    <div class="border-bottom mb-3 pb-2">
                                        <span class="title d-flex justify-content-between">
                                            <h6 class="font-weight-bolder mb-1">{{ $sValue->service_name }}</h6>
                                            <h6 class="text-muted mb-1">From</h6>
                                        </span>
                                        <span class="title d-flex justify-content-between">
                                            <h6 class="text-muted">{{ $sValue->serviceDuration }}</h6>
                                            <h6 class="font-weight-bolder">&#8377; {{ $sValue->service_price_special_amount }}</h6>
                                        </span>

                                        @if($sValue->service_price_special_amount < $sValue->service_price_amount)
                                            <span class="title d-flex justify-content-end"> 
                                                <h6 class="text-muted"><strike>&#8377; {{ $sValue->service_price_amount }}</strike></h6>
                                            </span>
                                        @endif
                                    </div>
                                @endforeach
                            @endif
                        </div> 
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="activityLogModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header align-items-center">
                <h4 class="modal-title font-weight-bolder">Activity log</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
           <!--  <div class="modal-header align-items-center">
                <ul class="nav nav-pills" id="myTab1" role="tablist">
                    <li class="nav-item mr-2">
                        <a class="nav-link active font-weight-bolder rounded-pill border shadow-sm" id="all" data-toggle="tab" href="#home-1">All (3)</a>
                    </li>
                    <li class="nav-item mr-2">
                        <a class="nav-link font-weight-bolder rounded-pill border shadow-sm" id="statusChange" data-toggle="tab" href="#profile-1" aria-controls="profile">Status change (1)</a>
                    </li> 
                    <li class="nav-item mr-2">
                        <a class="nav-link font-weight-bolder rounded-pill border shadow-sm" id="pastAppointment" data-toggle="tab" href="#contact-1" aria-controls="contact">Past appointments (2)</a>
                    </li>
                </ul>
            </div> -->
            <div class="modal-body p-0"> 
                <div class="tab-content" id="myTabContent1">
                    <div class="tab-pane fade active show" id="home-1" role="tabpanel" aria-labelledby="all"> 
                        <ul class="flexbox-ul"> 
                            <li>
                                <div class="li-inner">
                                    <div class="staff-flexbox">
                                        <div class="venue-img">
                                            <img src="https://cdn-uploads.fresha.com/location-profile-images/442371/478018/thumb_1d8bfa68-7e23-463a-ae3b-3c4f1f7e0e50.jpg">
                                        </div>
                                        <div class="activityStatus">
                                            <span>
                                                <svg xmlns="http://www.w3.org/2000/svg"><g fill="#FFF" fill-rule="evenodd"><path d="M8.033 6.247V4.463a.892.892 0 1 0-1.785 0V7.14c0 .493.4.893.892.893h3.57a.893.893 0 0 0 0-1.785H8.032z"></path><path d="M7.586 15.172A7.586 7.586 0 1 0 7.586 0a7.586 7.586 0 0 0 0 15.172zm0-1.785a5.801 5.801 0 1 1 0-11.602 5.801 5.801 0 0 1 0 11.602z"></path></g></svg>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="staff-flexbox">
                                        <div class="venue-info">
                                            <h6 class="text-muted m-0">Created Time</h6>
                                            <p class="font-weight-bolder">{{ !empty($selectedPaidPlan) ? date('d M Y H:ia', strtotime($value->created_at)) : '' }}</p>
                                            <h6 class="m-0"></h6>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="li-inner">
                                    <div class="staff-flexbox">
                                        <div class="venue-img bg-purple-gradient d-flex align-items-center justify-content-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 14" style="width: 24px; height: 24px; background: none; border-radius: 0"><path d="M9.333 4.667A4.68 4.68 0 0114 9.333 4.68 4.68 0 019.333 14a4.68 4.68 0 01-4.666-4.667 4.68 4.68 0 014.666-4.666zm.187 2.8H8.4v2.8h2.8v-1.12H9.52v-1.68zM11.2 0c1.03 0 1.867.836 1.867 1.867l-.001 3.266h-.933v-.934L.933 4.2v4.2c0 .479.36.873.825.927l.109.006h1.399v.933h-1.4A1.867 1.867 0 010 8.4V1.867C0 .836.836 0 1.867 0H11.2zm0 .933H1.867a.933.933 0 00-.927.825l-.007.109v1.4l11.2-.001v-1.4A.933.933 0 0011.31.94L11.2.933z" fill="#FFF" fill-rule="evenodd"></path></svg>
                                        </div>
                                        <div class="activityStatus">
                                            <span>
                                                <svg xmlns="http://www.w3.org/2000/svg"><g fill="#FFF" fill-rule="evenodd"><path d="M6.7 6.3c-.4-.4-1-.4-1.4 0-.4.4-.4 1 0 1.4l2 2c.2.2.4.3.7.3.3 0 .5-.1.7-.3l7-7c.4-.4.4-1 0-1.4-.4-.4-1-.4-1.4 0L8 7.6 6.7 6.3z"></path><path d="M8 15c3.9 0 7-3.1 7-7 0-.6-.4-1-1-1s-1 .4-1 1c0 2.8-2.2 5-5 5s-5-2.2-5-5 2.2-5 5-5c.6 0 1.1.1 1.7.3.5.2 1.1-.1 1.3-.6.2-.5-.1-1.1-.6-1.3C9.6 1.1 8.8 1 8 1 4.1 1 1 4.1 1 8s3.1 7 7 7z"></path></g></svg>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="staff-flexbox">
                                        <div class="venue-info">
                                            <h6 class="text-muted m-0">Start Date</h6>
                                            <p class="font-weight-bolder">{{ !empty($selectedPaidPlan) ? date('d M Y', strtotime($value->start_date)) : '' }}</p>
                                            <h6 class="m-0"></h6>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="li-inner">
                                    <div class="staff-flexbox">
                                        <div class="venue-img bg-purple-gradient d-flex align-items-center justify-content-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 14" style="width: 24px; height: 24px; background: none; border-radius: 0"><path d="M9.333 4.667A4.68 4.68 0 0114 9.333 4.68 4.68 0 019.333 14a4.68 4.68 0 01-4.666-4.667 4.68 4.68 0 014.666-4.666zm.187 2.8H8.4v2.8h2.8v-1.12H9.52v-1.68zM11.2 0c1.03 0 1.867.836 1.867 1.867l-.001 3.266h-.933v-.934L.933 4.2v4.2c0 .479.36.873.825.927l.109.006h1.399v.933h-1.4A1.867 1.867 0 010 8.4V1.867C0 .836.836 0 1.867 0H11.2zm0 .933H1.867a.933.933 0 00-.927.825l-.007.109v1.4l11.2-.001v-1.4A.933.933 0 0011.31.94L11.2.933z" fill="#FFF" fill-rule="evenodd"></path></svg>
                                        </div>
                                        <div class="activityStatus">
                                            <span>
                                                <svg xmlns="http://www.w3.org/2000/svg"><g fill="#FFF" fill-rule="evenodd"><path d="M6.7 6.3c-.4-.4-1-.4-1.4 0-.4.4-.4 1 0 1.4l2 2c.2.2.4.3.7.3.3 0 .5-.1.7-.3l7-7c.4-.4.4-1 0-1.4-.4-.4-1-.4-1.4 0L8 7.6 6.7 6.3z"></path><path d="M8 15c3.9 0 7-3.1 7-7 0-.6-.4-1-1-1s-1 .4-1 1c0 2.8-2.2 5-5 5s-5-2.2-5-5 2.2-5 5-5c.6 0 1.1.1 1.7.3.5.2 1.1-.1 1.3-.6.2-.5-.1-1.1-.6-1.3C9.6 1.1 8.8 1 8 1 4.1 1 1 4.1 1 8s3.1 7 7 7z"></path></g></svg>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="staff-flexbox">
                                        <div class="venue-info">
                                            <h6 class="text-muted m-0">End Date</h6>
                                            <p class="font-weight-bolder">{{ !empty($selectedPaidPlan) ? date('d M Y', strtotime($value->end_date)) : '' }}</p>
                                            <h6 class="m-0"></h6>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="profile-1" role="tabpanel" aria-labelledby="statusChange">
                        <ul class="flexbox-ul">  
                            <li>
                                <div class="li-inner">
                                    <div class="staff-flexbox">
                                        <div class="venue-img bg-purple-gradient d-flex align-items-center justify-content-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 14 14" style="width: 24px; height: 24px; background: none; border-radius: 0"><path d="M9.333 4.667A4.68 4.68 0 0114 9.333 4.68 4.68 0 019.333 14a4.68 4.68 0 01-4.666-4.667 4.68 4.68 0 014.666-4.666zm.187 2.8H8.4v2.8h2.8v-1.12H9.52v-1.68zM11.2 0c1.03 0 1.867.836 1.867 1.867l-.001 3.266h-.933v-.934L.933 4.2v4.2c0 .479.36.873.825.927l.109.006h1.399v.933h-1.4A1.867 1.867 0 010 8.4V1.867C0 .836.836 0 1.867 0H11.2zm0 .933H1.867a.933.933 0 00-.927.825l-.007.109v1.4l11.2-.001v-1.4A.933.933 0 0011.31.94L11.2.933z" fill="#FFF" fill-rule="evenodd"></path></svg>
                                        </div>
                                        <div class="activityStatus">
                                            <span>
                                                <svg xmlns="http://www.w3.org/2000/svg"><g fill="#FFF" fill-rule="evenodd"><path d="M6.7 6.3c-.4-.4-1-.4-1.4 0-.4.4-.4 1 0 1.4l2 2c.2.2.4.3.7.3.3 0 .5-.1.7-.3l7-7c.4-.4.4-1 0-1.4-.4-.4-1-.4-1.4 0L8 7.6 6.7 6.3z"></path><path d="M8 15c3.9 0 7-3.1 7-7 0-.6-.4-1-1-1s-1 .4-1 1c0 2.8-2.2 5-5 5s-5-2.2-5-5 2.2-5 5-5c.6 0 1.1.1 1.7.3.5.2 1.1-.1 1.3-.6.2-.5-.1-1.1-.6-1.3C9.6 1.1 8.8 1 8 1 4.1 1 1 4.1 1 8s3.1 7 7 7z"></path></g></svg>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="staff-flexbox">
                                        <div class="venue-info">
                                            <h6 class="text-muted m-0">4 May 2021</h6>
                                            <p class="font-weight-bolder">Paid plan purchased</p>
                                            <h6 class="m-0">Invoice 7</h6>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="contact-1" role="tabpanel" aria-labelledby="pastAppointment">
                        <ul class="flexbox-ul"> 
                            <li>
                                <div class="li-inner">
                                    <div class="staff-flexbox">
                                        <div class="venue-img">
                                            <img src="https://cdn-uploads.fresha.com/location-profile-images/442371/478018/thumb_1d8bfa68-7e23-463a-ae3b-3c4f1f7e0e50.jpg">
                                        </div>
                                        <div class="activityStatus">
                                            <span>
                                                <svg xmlns="http://www.w3.org/2000/svg"><g fill="#FFF" fill-rule="evenodd"><path d="M8.033 6.247V4.463a.892.892 0 1 0-1.785 0V7.14c0 .493.4.893.892.893h3.57a.893.893 0 0 0 0-1.785H8.032z"></path><path d="M7.586 15.172A7.586 7.586 0 1 0 7.586 0a7.586 7.586 0 0 0 0 15.172zm0-1.785a5.801 5.801 0 1 1 0-11.602 5.801 5.801 0 0 1 0 11.602z"></path></g></svg>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="staff-flexbox">
                                        <div class="venue-info">
                                            <h6 class="text-muted m-0">8 May 2021 12:00pm</h6>
                                            <p class="font-weight-bolder">Quintessa Battle</p>
                                            <h6 class="m-0">Slow Massage</h6>
                                        </div>
                                    </div>
                                </div>
                            </li> 
                        </ul>
                    </div>
                </div> 
            </div> 
        </div>
    </div>
</div>
@endsection
@section('scripts')
@endsection