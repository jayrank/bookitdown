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
    .voucher-list-item, .voucher-wrapper {
        color: #fff;
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
                                <div class="">
                                    <div class="d-flex align-items-center w-100 my-2">
                                        <h4 class="font-weight-bolde">Active Vouchers <span
                                                class="badge badge-light bg-white rounded-circle">{{ $activeVouchers->count() }}</span></h4>
                                    </div>
                                    @if($activeVouchers->isNotEmpty())
                                        @foreach($activeVouchers as $key => $value) 
                                        <div class="my-1 w-100">
                                            <a href="{{ url('myVouchers/'.$value->id) }}">
                                                <div class="voucherSection">
                                                    <div class="voucher-list-item {{ !empty($value->color) ? $value->color : 'parpal' }}">
                                                        <div class="voucher-card" style="min-height: auto; color: #ccc!important">
                                                            <h6 class="mb-0">Voucher Value</h6>
                                                            <h2 class="font-weight-bolder mb-0 pb-4">CA $<span id="vaoucher-price">{{ $value->total_value }}</span></h2>
                                                        </div>
                                                        <h5 class="font-weight-bolder">{{ $value->location_name }}</h5>
                                                        <h6 class="mb-0">Valid until {{ date('d M Y', strtotime($value->end_date)) }}</h6>
                                                    </div>
                                                </div>
                                            </a>
                                        </div> 
                                        @endforeach
                                    @else
                                        <h4 class="font-weight-bolder">You have no active vouchers</h4>
                                        <p class="text-muted">They will show here once you purchase one.</p>
                                    @endif
                                    <button class="my-3 btn btn-dark font-weight-bolder"><a href="{{ url('search') }}" class="text-white">Find salons near you</a></button>
                                </div>
                                <div class=" my-3">
                                    <div class="d-flex align-items-center w-100 my-2">
                                        <h4 class="font-weight-bolde">Used or sent <span
                                                class="badge badge-light bg-white rounded-circle">{{ $usedVouchers->count() }}</span></h4>
                                    </div>

                                    @if($usedVouchers->isNotEmpty())
                                        @foreach($usedVouchers as $key => $value) 
                                        <div class="my-1 w-100">
                                            <a href="{{ url('myVouchers/'.$value->id) }}">
                                                <div class="voucherSection">
                                                    <div class="voucher-list-item {{ !empty($value->color) ? $value->color : 'parpal' }}">
                                                        <div class="voucher-card" style="min-height: auto; color: #ccc!important">
                                                            <h6 class="mb-0">Voucher Value</h6>
                                                            <h2 class="font-weight-bolder mb-0 pb-4 my-2">CA $<span id="vaoucher-price">{{ $value->total_value }}</span></h2>
                                                        </div>
                                                        <h5 class="font-weight-bolder">{{ $value->location_name }}</h5>
                                                        <h6 class="mb-0">Valid until {{ date('d M Y', strtotime($value->end_date)) }}</h6>
                                                    </div>
                                                </div>
                                            </a>
                                        </div> 
                                        @endforeach
                                    @else
                                        <h4 class="font-weight-bolder">You have no used / sent vouchers</h4>
                                        <p class="text-muted">They will show here once you purchase one.</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if(!empty($selectedVoucher))
                            <div class="col-12 col-md-8 bg-content" style="display: flex; flex-direction: column; padding-top: 32px; padding-bottom: 50px;">
                                <div class="voucher-details">
                                    <div class="d-flex align-items-start flex-wrap">
                                        <div class="voucher-preview m-auto"> 
                                            <div class="text-center voucher-wrapper justify-content-center {{ $selectedVoucher->color }}">
                                                <div class="p-4 text-center"> 
                                                    @if( !empty($value->location_image) )
                                                        <img src="{{ url($selectedVoucher->location_image) }}" alt="voucher-thumb"
                                                        class="rounded mb-4" width="80px" height="80px">
                                                    @else
                                                        <img src="{{ asset('assets/images/thumb.jpg') }}" alt="voucher-thumb"
                                                        class="rounded mb-4" width="80px" height="80px">
                                                    @endif
                                                    <h5 class="font-weight-bold">{{ $selectedVoucher->location_name }}</h5>
                                                    <h6 class="text-grey">{{ $selectedVoucher->location_address }}</h6> 
                                                </div>
                                                <div class="add-vouchers-value">
                                                    <div class="vouchersInner">
                                                        <h6 class="">Voucher Value</h6>
                                                        <h3 class="font-weight-bolder mb-0">CA $<span id="vaoucher-price">{{ $selectedVoucher->total_value }}</span> </h3> 
                                                    </div>
                                                </div>
                                                <div class="p-4 vouchers-bottom">
                                                    <h6 class="mb-4">Voucher Code : <span class="font-weight-bolder">{{ $selectedVoucher->voucher_code }}</span></h6> 
                                                    <h6 class="mb-1 font-weight-bold">Redeem on <a class="font-weight-bolder" data-toggle="modal" href="#servicesModal">
                                                    @php
                                                        if(!empty($selectedVoucher->service_id)) {
                                                            $service_id_array = explode(',',$selectedVoucher->service_id);
                                                            $total_services = count($service_id_array);
                                                        } else {
                                                            $total_services = 0;
                                                        }
                                                    @endphp

                                                    {{ $total_services }} services
                                                    </a> <i class="fa fa-chevron-right icon-sm"></i> </h6>
                                                    <h6 class="mb-1 font-weight-bold">Valid until {{ date('d M Y', strtotime($selectedVoucher->end_date)) }} </h6>
                                                    <h6 class="mb-1 font-weight-bold">For multiple-use</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="voucherDetailWrapper m-auto">
                                            <h2 class="font-weight-bolder mb-0 pb-4">CA $<span id="vaoucher-price">{{ $selectedVoucher->total_value }}</span> Your {{ $selectedVoucher->name }} voucher</h2>
                                            <a href="{{ route('search_detail', ['id' => Crypt::encryptString($selectedVoucher->location_id)]) }}" class="text-white btn btn-block btn-dark btn-lg font-weight-bolder mb-4" style="padding:10px !important">View available vouchers</a>
                                            <div style="display: grid;grid-template-columns: repeat(auto-fill, 72px);gap: 16px;">
                                                <a href="{{ route('viewVoucherInvoice', ['soldVoucherId' => Crypt::encryptString($selectedVoucher->id)]) }}" class="icon-button-invoice">
                                                    <div class="styled__ButtonBox">
                                                        <div class="styled__VisualLayer"></div>
                                                        <svg class="" width="24" height="24">
                                                            <g fill="none" fill-rule="evenodd">
                                                                <path d="M0 0h24v24H0z"></path>
                                                                <g stroke="#101928" stroke-width="1.5">
                                                                    <path d="M5 20.092v-16a1 1 0 011-1h12a1 1 0 011 1v16h0l-2.35-2.465-2.15 2.465-2.5-2.465-2.25 2.465-2.33-2.465L5 20.092z" stroke-linejoin="round"></path>
                                                                    <g stroke-linecap="round">
                                                                        <path d="M9 8h6M9 12h6"></path>
                                                                    </g>
                                                                </g>
                                                            </g>
                                                        </svg>
                                                    </div>
                                                    <h6 class="mt-2">View<br>invoice</h6>
                                                </a>
                                                <a href="{{ route('printVoucher', ['soldVoucherId' => Crypt::encryptString($selectedVoucher->id)]) }}" class="icon-button-invoice">
                                                    <div class="styled__ButtonBox">
                                                        <div class="styled__VisualLayer"></div>
                                                        <svg class="" width="24" height="24"><g stroke="#101928" stroke-width="1.5" fill="none" fill-rule="evenodd" stroke-linecap="round" stroke-linejoin="round"><path d="M5.5 6V3h13v3M5.318 18H1.5V9.818C1.5 8.814 2.355 8 3.41 8h17.18c1.055 0 1.91.814 1.91 1.818V18h-3.818"></path><path d="M5.5 12h13v10h-13z"></path></g></svg>
                                                    </div>
                                                    <h6 class="mt-2">Print<br>voucher</h6>
                                                </a>
                                                <a href="{{ route('emailVoucher', ['soldVoucherId' => Crypt::encryptString($selectedVoucher->id)]) }}" class="icon-button-invoice">
                                                    <div class="styled__ButtonBox">
                                                        <div class="styled__VisualLayer"></div>
                                                        <svg class="" width="24" height="24"><g stroke="#101928" stroke-width="1.5" fill="none" fill-rule="evenodd" stroke-linecap="round"><path d="M2.612 13.068v5.14c0 .947.767 1.714 1.714 1.714h15.422c.946 0 1.714-.767 1.714-1.713v-5.141"></path><path d="M21.462 8.784v-2.57c0-.947-.768-1.714-1.714-1.714H4.326c-.947 0-1.714.767-1.714 1.714v2.57l9.425 5.14 9.425-5.14z"></path></g></svg>
                                                    </div>
                                                    <h6 class="mt-2">Email<br>voucher</h6>
                                                </a>
                                            </div>
                                            <hr class="my-4">
                                            <h6 class="font-weight-bolder">Note from the sender</h6>
                                            <h6>{{ $selectedVoucher->description }}</h6>
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
                                            <h6 class="text-muted mb-1">{{ ($sValue->price_type == 'from' || ($sValue->is_staff_price == 1)) ? 'From' : '' }}</h6>
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
@endsection
@section('scripts')
@endsection