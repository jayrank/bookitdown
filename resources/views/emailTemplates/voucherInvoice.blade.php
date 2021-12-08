<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="ScheduleDown">
    <meta name="author" content="ScheduleDown">
    <link rel="icon" type="image/png" href="{{ asset('frontend/img/fav.png') }}">
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

    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2-rc.1/css/select2.min.css" rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="{{ asset('frontend/css/style.css') }}" rel="stylesheet">
    <!-- Sidebar CSS -->
    <link href="{{ asset('frontend/vendor/sidebar/demo.css') }}" rel="stylesheet">
</head>

<body style="background: #F2F2F7; margin: 0; padding: 0; ">
    <div style="margin: 0 auto;background: #F2F2F7;max-width: 550px;width: 100%;">
        <div class="card border-0 m-5 p-2 shadow">
            <div style="padding: 32px 16px;background: #F2F2F7;">
                <h5 class="d-flex justify-content-end mb-0">
                    <a href="{{ route('myVouchers') }}" type="button" class="close" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true" class="feather-x"></span>
                    </a>
                </h5>
                <p style="text-align:left;font-size:15px;margin:0;line-height:21px;color:#878c93">{{
                    ($Invoice['created_at']) ? date("d M Y",strtotime($Invoice['created_at'])) : '' }}</p>
                <h3 class="fw-800">Invoice {{ $Invoice['invoice_prefix']." ".$Invoice['invoice_id'] }}</h3>
                <h4 style="text-align:left;font-weight:bold;font-size:17px;margin:22px 0 2px;line-height:24px">{{
                    !empty($ClientInfo) ? $ClientInfo->firstname : '' }} {{ !empty($ClientInfo) ? $ClientInfo->lastname
                    : '' }}</h4>
                <p style="text-align:left;font-size:15px;margin:0;line-height:21px">{{ ($LocationInfo) ?
                    $LocationInfo['location_name'] : '' }}</p>

                @if(!empty($ClientInfo))
                <address
                    style="text-align:left;font-size:15px;margin:0;font-style:normal;color:#878c93;line-height:21px">{{
                    !empty($ClientInfo->address) ? $ClientInfo->address.", ".$ClientInfo->suburb.",
                    ".$ClientInfo->city.", ".$ClientInfo->state.", ".$ClientInfo->zipcode : '' }}
                </address>
                @endif
                <p style="text-align:left;font-size:15px;margin:0;color:#878c93;line-height:21px">{{ !empty($ClientInfo)
                    ? $ClientInfo->mo_country_code : '' }} {{ !empty($ClientInfo) ? $ClientInfo->mobileno : '' }}</p>
                <p style="font-size:14px;margin:0;padding-top:24px;font-size:15px;color:#878c93;line-height:21px">Billed
                    to:</p>
                <p style="font-size:17px;margin:0;font-weight:bold;line-height:24px">{{ !empty($UserInfo) ?
                    $UserInfo->first_name : '' }} {{ !empty($UserInfo) ? $UserInfo->last_name : '' }}</p>
                <p style="font-size:15px;margin:0;color:#037aff;line-height:21px"><a
                        href="mailto:{{ !empty($UserInfo) ? $UserInfo->email : '' }}" target="_blank">{{
                        !empty($UserInfo) ? $UserInfo->email : '' }}</a></p>
            </div>
            <table cellpadding="0" cellspacing="0" style="color:#2c2c2c;background:#fff;margin:0 auto">
                @if(!empty($InvoiceItemsInfo))
                @foreach($InvoiceItemsInfo as $InvoiceItemsData)
                <tr>
                    <td style="background:#ffffff;padding:0 32px">
                        <table cellpadding="0" cellspacing="0" style="color:#2c2c2c">
                            <tbody>
                                <tr style="font-size:17px;line-height:24px">
                                    <td style="padding-top:32px">{{ ($InvoiceItemsData['main_title']) ?
                                        $InvoiceItemsData['main_title'] : 'N/A' }}</td>
                                    <td style="padding-top:32px;text-align:right;vertical-align:top">₹{{
                                        $InvoiceItemsData['item_og_price'] }}</td>
                                </tr>
                                <tr style="color:#878c93;font-size:15px;line-height:21px">
                                    <td style="padding-top:8px">{{ ($InvoiceItemsData['staff_name']) ?
                                        $InvoiceItemsData['staff_name'] : 'N/A' }}</td>

                                    @if($InvoiceItemsData['item_og_price'] > $InvoiceItemsData['item_price'])
                                    <td style="padding-top:8px;text-align:right;vertical-align:top">
                                        <strike>₹{{ $InvoiceItemsData['item_og_price'] }}</strike>
                                    </td>
                                    @endif


                                </tr>
                                <tr style="color:#878c93;font-size:15px;line-height:21px">
                                    <td style="padding:8px 0 0"> {{ ($InvoiceItemsData['quantity']) ?
                                        $InvoiceItemsData['quantity'] : 0 }} item </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                @endforeach
                @endif
                <tr>
                    <td style="padding:24px 32px 8px;background:#ffffff">
                        <table cellpadding="0" cellspacing="0" style="width:100%">
                            <tbody>
                                <tr style="font-size:15px;line-height:21px;color:#878c93">
                                    <td
                                        style="padding-right:4%;padding-top:25px;border-top:1px solid #e7e8e9;padding-bottom:16px">
                                        Subtotal</td>
                                    <td
                                        style="padding-top:25px;text-align:right;border-top:1px solid #e7e8e9;padding-bottom:16px">
                                        ${{ $Invoice['invoice_sub_total'] }}</td>
                                </tr>
                                @if(!empty($InvoiceTaxes))
                                @foreach($InvoiceTaxes as $InvoiceTaxData)
                                <tr style="font-size:15px;line-height:21px;color:#878c93">
                                    <td
                                        style="padding-right:4%;padding-top:25px;border-top:1px solid #e7e8e9;padding-bottom:16px">
                                        {{ $InvoiceTaxData['tax_name'] }} {{ $InvoiceTaxData['tax_rate'] }}</td>
                                    <td
                                        style="padding-top:25px;text-align:right;border-top:1px solid #e7e8e9;padding-bottom:16px">
                                        ${{ $InvoiceTaxData['tax_amount'] }}</td>
                                </tr>
                                @endforeach
                                @endif
                                <tr style="font-size:17px;line-height:24px;color:#101928;font-weight:bold">
                                    <td style="padding-right:4%;padding-bottom:16px">Total</td>
                                    <td style="padding-bottom:16px;text-align:right">${{
                                        $Invoice['invoice_total'] }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding:0 32px;background:#ffffff">
                        <table cellpadding="0" cellspacing="0" style="color:#2c2c2c">
                            <tbody>
                                <tr>
                                    <td colspan="2" style="border-top:1px solid #e7e8e9;padding-top:25px">
                                    </td>
                                </tr>
                                <tr style="font-size:17px;line-height:24px;color:#101928">
                                    <td style="padding-right:4%;padding-left:0">{{ $Invoice['payment_type'] }}
                                    </td>
                                    <td style="text-align:right;padding-right:0">${{
                                        $Invoice['inovice_final_total'] }}</td>
                                </tr>
                                <tr style="font-size:15px;line-height:21px;color:#878c93">
                                    <td colspan="2" style="padding:0 0 24px">
                                        @if($Invoice['payment_date'] != '')
                                        {{ ($Invoice['payment_date']) ? date("l, d M
                                        Y",strtotime($Invoice['payment_date'])) : '' }} at {{ ($Invoice['payment_date'])
                                        ? date("h:ia",strtotime($Invoice['payment_date'])) : '' }}
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding:0 32px 24px;background:#ffffff">
                        <table cellpadding="0" cellspacing="0" style="color:#2c2c2c">
                            <tbody>
                                <tr>
                                    <td colspan="2" style="border-top:1px solid #e7e8e9;padding-top:24px">
                                    </td>
                                </tr>
                                <tr style="font-size:24px;font-weight:800">
                                    <td>Balance</td>
                                    <td style="text-align:right">${{ ($Invoice['invoice_status'] == 0) ?
                                        $Invoice['inovice_final_total'] : 0 }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Bootstrap core JavaScript -->
    <script type="text/javascript" src="{{ asset('frontend/vendor/jquery/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('frontend/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>