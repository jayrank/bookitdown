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
    <link href="{{ asset('frontend/vendor/animate/animate.min.css') }}" rel="stylesheet">
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
    </style>
</head>

<body class="fixed-bottom-bar"> 
    <div class="my-custom-header bg-transparent text-dark border-bottom">
        <div class="py-3 px-4" style="flex-shrink: 0; display: grid; grid-template-columns: 1fr auto 1fr; align-items: center;">
            <a type="button" class="close" data-dismiss="modal" aria-label="Close" href="{{ route('myVouchers') }}" style="display: flex; justify-content: flex-start; align-items: center;">
                <span aria-hidden="true" class="feather-x"></span>
            </a>
            <h4 class="mb-0 fw-800" style="flex-grow: 1;">Print voucher</h4> 
            <div style="display: flex; flex-grow: 1; grid-auto-flow: column; justify-content: flex-end;">
                <button class="btn btn-lg btn-dark printVoucherButton">Print</button>
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
                                <form method="post" action="{{ route('printVoucherInvoice') }}" id="printVoucher"> 
                                    @csrf()
                                    <input type="hidden" name="soldVoucherID" id="soldVoucherID" value="{{ !empty($soldVoucher) ? $soldVoucher->id : '' }}">
                                    <input type="hidden" name="invoiceID" id="invoiceID" value="{{ !empty($soldVoucher) ? $soldVoucher->invoice_id : '' }}">
                                    <input type="hidden" name="redirectionUrl" id="redirectionUrl" value="{{ route('printVoucher',['soldVoucherId' => !empty($soldVoucher) ? Crypt::encryptString($soldVoucher->id) : '']) }}">
                                    <div class="form-group"> 
                                        <label class="font-weight-bolder h6">Recipient’s name</label>
                                        <input type="text" class="form-control recipient-name" name="recipient_first_name" placeholder="">
                                    </div> 
                                    <div class="form-group">
                                        <label class="font-weight-bolder h6">Personalised message</label>
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
                                        <div class="card p-4" style="margin-top: -8px;"> 
                                            <h6 class="text-center mt-2 mb-0 voucher-name">{{ !empty($soldVoucher) ? $soldVoucher->name : '' }}</h6>
                                            <span class="voucher-name-hidden" style="display: none;">{{ !empty($soldVoucher) ? $soldVoucher->name : '' }}</span>

                                            <div class="voucher-for-container text-center" style="display: none;  margin-top: 3%; font-size: 14px;">
                                                <span>voucher for</span><br>
                                                <h2 class="voucher-for"></h2>
                                            </div>
                                            <div class="voucher-wrapper voucher-border-card mx-5 my-4 text-center justify-content-center">
                                                <div class="p-4 text-center"> 
                                                    @if( !empty($soldVoucher->location_image) )
                                                        <img src="{{ url($soldVoucher->location_image) }}" alt="voucher-thumb"
                                                        class="rounded mb-4" width="80px" height="80px">
                                                    @else
                                                        <img src="{{ asset('assets/images/thumb.jpg') }}" alt="voucher-thumb"
                                                        class="rounded mb-4" width="80px" height="80px">
                                                    @endif
                                                    <h5 class="font-weight-bold text-purple">{{ !empty($soldVoucher) ? $soldVoucher->location_name : '' }}</h5>
                                                    <h6 class="text-grey">{{ !empty($soldVoucher) ? $soldVoucher->location_address : '' }}</h6> 
                                                </div>
                                                <div class="add-vouchers-value">
                                                    <div class="vouchersInner border-purple">
                                                        <h6 class="">Voucher Value</h6>
                                                        <h3 class="font-weight-bolder text-purple mb-0">CA $<span id="vaoucher-price">{{ !empty($soldVoucher) ? $soldVoucher->total_value : '' }}</span> </h3> 
                                                    </div>
                                                </div>
                                                <div class="p-4 vouchers-bottom">
                                                    <p class="mb-4 text-purple">Voucher Code : <span class="font-weight-bolder">{{ !empty($soldVoucher) ? $soldVoucher->voucher_code : '' }}</span></p> 
                                                    <h6 class="mb-1 font-weight-bold">
                                                        Redeem on 
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
                                                    </h6>
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

            $(document).on('keyup mouseup', '.recipient-name', function(){
                $('.voucher-for').text($(this).val());

                if($.trim($(this).val()) == '') {
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

            $(document).on('click', '.printVoucherButton', function(){
                $('#printVoucher').submit();
            });
            
            $('#printVoucher').validate({
                rules: {
                    recipient_first_name: "required"
                },
                messages: {
                    recipient_first_name: "Please enter first name"
        
                },
                submitHandler: function(form) {
                    var form_data = new FormData($("#printVoucher")[0]);
                    var formSubmitUrl = $('#printVoucher').attr('action');
                    
                    $.ajax({
                        type: 'POST',       
                        url: formSubmitUrl,
                        data: form_data,                    
                        processData: false,
                        contentType: false,
                        xhrFields: {
                            responseType: 'blob'
                        },
                        success: function (response)
                        {
                            var blob = new Blob([response]);
                            var link = document.createElement('a');
                            link.href = window.URL.createObjectURL(blob);
                            link.download = "Voucher.pdf";
                            link.click();
                            
                            var invoiceID = $("#invoiceID").val();
                            
                            setTimeout(function() {
                                window.location.href = $("#redirectionUrl").val();
                            }, 1000);
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