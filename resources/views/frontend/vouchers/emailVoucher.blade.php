<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="ScheduleDown">
    <meta name="author" content="ScheduleDown">
    <link rel="icon" type="image/png" href="img/fav.png">
    <title>ScheduleDown</title>
    <!-- Slick Slider -->
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/vendor/slick/slick.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/vendor/slick/slick-theme.min.css') }}" />
    <!-- Feather Icon-->
    <link href="{{ asset('frontend/vendor/icons/feather.css') }}" rel="stylesheet" type="text/css">
    <!-- Bootstrap core CSS -->
    <link href="{{ asset('frontend/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/vendor/owl.carousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/vendor/animate.css/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/vendor/aos/aos.css') }}" rel="stylesheet">

    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2-rc.1/css/select2.min.css" rel="stylesheet"/>
    <!-- Custom styles for this template -->
    <link href="{{ asset('frontend/css/style.css') }}" rel="stylesheet">
    <!-- Sidebar CSS -->
    <link href="{{ asset('frontend/vendor/sidebar/demo.css') }}" rel="stylesheet"> 
    <style type="text/css">
        .my-custom-body div::-webkit-scrollbar{
            display: none;
        }
        .my-custom-body div{
            scrollbar-width: none;
        }
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
</head>

<body class="fixed-bottom-bar"> 
    <div class="my-custom-header bg-transparent text-dark border-bottom">
        <div class="py-3 px-4" style="flex-shrink: 0; display: grid; grid-template-columns: 1fr auto 1fr; align-items: center;">
            <a type="button" class="close" data-dismiss="modal" aria-label="Close" href="{{ route('myVouchers') }}" style="display: flex; justify-content: flex-start; align-items: center;">
                <span aria-hidden="true" class="feather-x"></span>
            </a>
            <h4 class="mb-0 fw-800" style="flex-grow: 1;">Email voucher</h4> 
            <div style="display: flex; flex-grow: 1; grid-auto-flow: column; justify-content: flex-end;">
                <button class="btn btn-lg btn-dark emailVoucherButton">Email</button>
            </div>
        </div>
    </div>
    <section>
        <div class="container-fluid p-0">
            <div class="my-custom-body-wrapper h-auto">
                <div class="my-custom-body bg-content"> 
                    <div class="row m-0">
                        <div class="col-12 col-md-4 bg-white">
                            <div class="p-4"> 
                                <h4 class="font-weight-bolder mb-4">Recipient's info</h4>
                                <form method="post" action="{{ route('sendVoucherByEmail') }}" id="emailVoucher"> 
                                    @csrf()
                                    <input type="hidden" name="soldVoucherID" id="soldVoucherID" value="{{ !empty($soldVoucher) ? $soldVoucher->id : '' }}">
                                    <input type="hidden" name="invoiceID" id="invoiceID" value="{{ !empty($soldVoucher) ? $soldVoucher->invoice_id : '' }}">
                                    <div class="form-group"> 
                                        <label class="font-weight-bolder h6">First name</label>
                                        <input type="text" class="form-control first-name" name="recipient_first_name" placeholder="">
                                    </div> 
                                    <div class="form-group"> 
                                        <label class="font-weight-bolder h6">Last name <span class="text-muted">(Optional)</span></label>
                                        <input type="text" class="form-control last-name" name="recipient_last_name" placeholder="">
                                    </div> 
                                    <div class="form-group">
                                        <label class="font-weight-bolder h6">Email</label>
                                        <input type="email" class="form-control" name="recipient_email">
                                        <p class="text-muted mb-0">The voucher will be sent to this email address.</p>
                                    </div>
                                    <div class="form-group">
                                        <label class="font-weight-bolder h6">Personalised message <span class="text-muted">(Optional)</span></label>
                                        <textarea class="form-control personalised-message" name="recipient_personalized_email" rows="6"></textarea>
                                    </div>  
                                </form>
                            </div>
                        </div>
                        <div class="col-12 col-md-8 bg-content">
                            <div class="fullscreen-modal px-6 py-4" id="printPreview"> 
                                <div class="modal-content"> 
                                    <div class="modal-body"> 
                                        <div class="voucher-preview-title h6 font-weight-bolder mb-0">Voucher preview</div>
                                        <div class="voucher-preview-title justify-content-start h6 mb-0" style="color: #878c93; background: #E8E8EE">Voucher email subject: <span class="text-dark ml-2">Special Discount Voucher from Dev TJ</span></div>
                                        <div class="card p-4" style="margin-top: -8px;"> 
                                            <h6 class="text-center mt-2 mb-0 voucher-name">{{ !empty($soldVoucher) ? $soldVoucher->name : '' }}</h6>
                                            <span class="voucher-name-hidden" style="display: none;">{{ !empty($soldVoucher) ? $soldVoucher->name : '' }}</span>

                                            <div class="voucher-for-container text-center" style="display: none;  margin-top: 3%; font-size: 14px;">
                                                <span>voucher for</span><br>
                                                <h2>
                                                    <span class="voucher-for-first-name"></span>&nbsp;<span class="voucher-for-last-name"></span>
                                                </h2>
                                            </div>
                                            <div class="voucher-wrapper {{ !empty($value->color) ? $value->color : 'parpal' }} mx-5 my-4 text-center justify-content-center" style="color: #fff !important;">
                                                <div class="p-4 text-center"> 
                                                    @if( !empty($soldVoucher->location_image) )
                                                        <img src="{{ url($soldVoucher->location_image) }}" alt="voucher-thumb"
                                                        class="rounded mb-4" width="80px" height="80px">
                                                    @else
                                                        <img src="{{ asset('assets/images/thumb.jpg') }}" alt="voucher-thumb"
                                                        class="rounded mb-4" width="80px" height="80px">
                                                    @endif
                                                    <h5 class="font-weight-bold">{{ !empty($soldVoucher) ? $soldVoucher->location_name : '' }}</h5>
                                                    <h6 class="text-grey">{{ !empty($soldVoucher) ? $soldVoucher->location_address : '' }}</h6> 
                                                </div>
                                                <div class="add-vouchers-value">
                                                    <div class="vouchersInner border-light">
                                                        <h6 class="">Voucher Value</h6>
                                                        <h3 class="font-weight-bolder mb-0">CA $<span id="vaoucher-price">{{ !empty($soldVoucher) ? $soldVoucher->total_value : '' }}</span> </h3> 
                                                    </div>
                                                </div>
                                                <div class="p-4 vouchers-bottom">
                                                    <p class="mb-4">Voucher Code : <span class="font-weight-bolder">{{ !empty($soldVoucher) ? $soldVoucher->voucher_code : '' }}</span></p>  
                                                    <h6 class="mb-1 font-weight-bold">Redeem on <a class="d-inline-flex align-items-center font-weight-bold text-white" data-toggle="modal" href="#servicesModal">
                                                        @php
                                                            if(!empty($soldVoucher)) {
                                                                if(!empty($soldVoucher->service_id)) {
                                                                    $service_id_array = explode(',',$soldVoucher->service_id);
                                                                    $total_services = count($service_id_array);
                                                                } else {
                                                                    $total_services = 0;
                                                                }
                                                            }
                                                        @endphp

                                                        {{ $total_services }} services
                                                     <i class="feather-chevron-right icon-sm"></i></a></h6>
                                                    <h6 class="mb-1 font-weight-bold">Valid until {{ !empty($soldVoucher) ? date('d M Y', strtotime($soldVoucher->end_date)) : '' }} </h6>
                                                    <h6 class="mb-1 font-weight-bold">For multiple-use</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
     

    <!-- Bootstrap core JavaScript -->
    <script type="text/javascript" src="{{ asset('frontend/vendor/jquery/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- slick Slider JS-->
    <script type="text/javascript" src="{{ asset('frontend/vendor/slick/slick.min.js') }}"></script>
    <!-- Sidebar JS-->
    <script type="text/javascript" src="{{ asset('frontend/vendor/sidebar/hc-offcanvas-nav.js') }}"></script>
    <!-- Custom scripts for all pages-->
    <script type="text/javascript" src="{{ asset('frontend/js/osahan.js') }}"></script>
    <script src="{{ asset('frontend/vendor/jquery.easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('frontend/vendor/waypoints/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('frontend/vendor/owl.carousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('frontend/vendor/counterup/counterup.min.js') }}"></script>
    <script src="{{ asset('frontend/vendor/aos/aos.js') }}"></script>
    <script src="{{ asset('frontend/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('frontend/js/main.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            /*var headerHeight = $('.section-header').outerHeight();
            $('.my-custom-body-wrapper').css('height', 'calc(100vh - '+ headerHeight +'px)');*/

            $(document).on('keyup mouseup', '.first-name, .last-name', function(){
                var first_name = $('.first-name').val();
                var last_name = $('.last-name').val();

                $('.voucher-for-first-name').text(first_name);
                $('.voucher-for-last-name').text(last_name);

                if($.trim(first_name) == '' && $.trim(last_name) == '') {
                    $('.voucher-for-container').hide();
                } else {
                    $('.voucher-for-container').show();
                }
            });
            $(document).on('keyup mouseup', '.personalised-message', function(){

                if($.trim($(this).val()) == '') {
                    $('.voucher-name').text($('.voucher-name-hidden').text());
                } else {
                    $('.voucher-name').text($(this).val());
                }
            });

            $(document).on('click', '.emailVoucherButton', function(){
                $('#emailVoucher').submit();
            });
            
            $('#emailVoucher').validate({
                rules: {
                    recipient_first_name: "required",
                    recipient_email: "required",
                },
                messages: {
                    recipient_first_name: "Please enter first name",
                    recipient_email: "Please enter email"
        
                },
                submitHandler: function(form) {
                    var form_data = new FormData($("#emailVoucher")[0]);
                    var formSubmitUrl = $('#emailVoucher').attr('action');
                    
                    $.ajax({
                        type: 'POST',       
                        url: formSubmitUrl,
                        data: form_data,        
                        dataType: 'json',                   
                        processData: false,
                        contentType: false,
                        success: function (response)
                        {
                            if(response.status == true){
                                window.location.href = response.redirect;   
                            } else {
                                responseMessage('error', ((response.message) ? response.message : "Something went wrong!"));
                            }
                        },
                        error: function (data) 
                        {
                            responseMessage('error', "Something went wrong!");
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>