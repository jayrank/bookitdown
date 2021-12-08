<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Discount Summary Report</title>

<style type="text/css">
    * {
        font-family: Verdana, Arial, sans-serif;
        color: gray;
    }
    table{
        font-size: x-small;
    }
    tfoot tr td{ 
        font-weight: bold;
        font-size: x-small;
    }
    .gray {
        background-color: lightgray
    }
</style>

</head>
    <body>
        <table width="100%">
            <tr>
                <td>
                    <h1 style="font-weight: normal; color: #000">Discount Summary Report: {{ date("l, d M Y") }}</h1>
                    <p style="color: #000">{{ !empty($LocationName) ? $LocationName->location_name :'All Locations'}}, {{ !empty($StaffName) ? $StaffName->staffName :'All Staff'}}, generated {{ date("l, d M Y") }} at {{ date("h:i A") }}</p>
                </td>
                <td></td>
            </tr>
        </table>

        <div class="row reports mx-1 my-3">
            <h3 class="my-2">Discounts</h3>
            <div class="table-responsive bg-white rounded">
                <table class="table table-hover" style="width:100%">
                    <thead style="margin: 50px;">
                        <tr style="margin:2%; font-size: 10px; border-bottom:1px solid black;">
                            <th style="text-align: left;">Discount</th>
                            <th style="text-align: right;">Items Discounted</th>
                            <th style="text-align: right;">Items Value</th>
                            <th style="text-align: right;">Discount Amount</th>
                            <th style="text-align: right;">Discount Refunds</th>
                            <th style="text-align: right;">Net Discounts</th>
                        </tr>
                    </thead>
                    <tbody class="discountTotal" style="margin: 20px;">
                        @php
                            $totalItemDiscounted = 0; 
                            $totalItemValue = 0;
                            $totalDiscountAmount = 0;
                            $totalDiscountRefund = 0;
                            $totalNetDiscount = 0;
                        @endphp

                        @if(!empty($getDiscountSummary->original['discountComplete']) || !empty($getDiscountSummary->original['discountRefund']))

                            @foreach($getDiscountSummary->original['discountComplete'] as $discountCompleteKey => $discountCompleteValue)
                                @php

                                    $totalItemDiscounted += ($discountCompleteValue['count'] - (!empty($getDiscountSummary->original[$discountCompleteKey]['discountRefund']) ? (($getDiscountSummary->original[$discountCompleteKey]['discountRefund']['refund_discount_name'] == $discountCompleteValue['discount_name']) ? $getDiscountSummary->original[$discountCompleteKey]['discountRefund']['refund_count'] : 0 ) : 0) );

                                    $totalItemValue += $discountCompleteValue['og_price_sum'] - ( !empty($getDiscountSummary->original[$discountCompleteKey]['discountRefund']) ? ( ( $getDiscountSummary->original[$discountCompleteKey]['discountRefund']['refund_discount_name'] ==  $discountCompleteValue['discount_name']) ? $getDiscountSummary->original[$discountCompleteKey]['discountRefund']['refund_og_price_sum'] : 0 ) : 0);

                                    $totalDiscountAmount += $discountCompleteValue['discount_price'];

                                    $totalDiscountRefund += ( !empty($getDiscountSummary->original[$discountCompleteKey]['discountRefund']) ? ( ( $getDiscountSummary->original[$discountCompleteKey]['discountRefund']['refund_discount_name'] ==  $discountCompleteValue['discount_name']) ? $getDiscountSummary->original[$discountCompleteKey]['discountRefund']['refund_discount_price'] : 0 ) : 0);

                                    $totalNetDiscount += $discountCompleteValue['discount_price'] - ( !empty($getDiscountSummary->original[$discountCompleteKey]['discountRefund']) ? ( ( $getDiscountSummary->original[$discountCompleteKey]['discountRefund']['refund_discount_name'] ==  $discountCompleteValue['discount_name']) ? $getDiscountSummary->original[$discountCompleteKey]['discountRefund']['refund_discount_price'] : 0 ) : 0);

                                @endphp

                                <tr class="font-weight-bolder" @if($discountCompleteKey == 0) style="margin:2%; font-size: 10px;border-top:1px solid black" @else style="margin:2%; font-size: 10px;" @endif>
                                    <td style="text-align: left;">{{$discountCompleteValue['discount_name']}}</td>

                                    <td class="number" style="text-align: right;">
                                        {{ 
                                            ($discountCompleteValue['count'] - (!empty($getDiscountSummary->original[$discountCompleteKey]['discountRefund']) ? (($getDiscountSummary->original[$discountCompleteKey]['discountRefund']['refund_discount_name'] == $discountCompleteValue['discount_name']) ? $getDiscountSummary->original[$discountCompleteKey]['discountRefund']['refund_count'] : 0 ) : 0) ) 
                                        }}
                                    </td>

                                    <td style="text-align: right;">
                                        CA ${{
                                            number_format($discountCompleteValue['og_price_sum'] - ( !empty($getDiscountSummary->original[$discountCompleteKey]['discountRefund']) ? ( ( $getDiscountSummary->original[$discountCompleteKey]['discountRefund']['refund_discount_name'] ==  $discountCompleteValue['discount_name']) ? $getDiscountSummary->original[$discountCompleteKey]['discountRefund']['refund_og_price_sum'] : 0 ) : 0),2,'.','')
                                        }}
                                    </td>
                                    <td style="text-align: right;">
                                        CA ${{
                                            number_format($discountCompleteValue['discount_price'],2,'.','')
                                        }}
                                    </td>
                                    <td style="text-align: right;">
                                        CA ${{
                                            number_format(( !empty($getDiscountSummary->original[$discountCompleteKey]['discountRefund']) ? ( ( $getDiscountSummary->original[$discountCompleteKey]['discountRefund']['refund_discount_name'] ==  $discountCompleteValue['discount_name']) ? $getDiscountSummary->original[$discountCompleteKey]['discountRefund']['refund_discount_price'] : 0 ) : 0),2,'.','')
                                        }}
                                    </td>
                                    <td style="text-align: right;">
                                        CA ${{
                                            number_format($discountCompleteValue['discount_price'] - ( !empty($getDiscountSummary->original[$discountCompleteKey]['discountRefund']) ? ( ( $getDiscountSummary->original[$discountCompleteKey]['discountRefund']['refund_discount_name'] ==  $discountCompleteValue['discount_name']) ? $getDiscountSummary->original[$discountCompleteKey]['discountRefund']['refund_discount_price'] : 0 ) : 0),2,'.','')
                                        }}
                                    </td>
                                </tr>
                            @endforeach
                            <tr class="font-weight-bolder" style="margin:2%; font-size: 10px; font-weight: bold;">
                                <td style="text-align: left; border-top:0.5px solid gray;">Total</td>
                                <td class="number" style="text-align: right; border-top:0.5px solid gray;">{{ $totalItemDiscounted }}</td>
                                <td style="text-align: right; border-top:0.5px solid gray;">CA ${{ number_format($totalItemValue,2,'.','') }} </td>
                                <td style="text-align: right; border-top:0.5px solid gray;">CA ${{ number_format($totalDiscountAmount,2,'.','') }}</td>
                                <td style="text-align: right; border-top:0.5px solid gray;">CA ${{ number_format($totalDiscountRefund,2,'.','') }}</td>
                                <td style="text-align: right; border-top:0.5px solid gray;">CA ${{ number_format($totalNetDiscount,2,'.','') }}</td>
                            </tr>
                        @else
                        <tr class="font-weight-bolder" style="margin:2%; font-size: 10px; font-weight: bold;">
                                <td style="text-align: left; border-top:0.5px solid gray;">Total</td>
                                <td class="number" style="text-align: right; border-top:0.5px solid gray;">0</td>
                                <td style="text-align: right; border-top:0.5px solid gray;">CA $0.00</td>
                                <td style="text-align: right; border-top:0.5px solid gray;">CA $0.00</td>
                                <td style="text-align: right; border-top:0.5px solid gray;">CA $0.00</td>
                                <td style="text-align: right; border-top:0.5px solid gray;">CA $0.00</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <h3 class="my-4">Discounts by Services</h3>
            <div class="table-responsive bg-white rounded">
                <table class="table table-hover" style="width:100%">
                    <thead style="margin: 50px;">
                        <tr style="margin:2%; font-size: 10px; border-bottom:1px solid black;">
                            <th style="text-align: left;">Service Name</th>
                            <th style="text-align: right;">Items Discounted</th>
                            <th style="text-align: right;">Items Value</th>
                            <th style="text-align: right;">Discount Amount</th>
                            <th style="text-align: right;">Discount Refunds</th>
                            <th style="text-align: right;">Net Discounts</th>
                        </tr>
                    </thead>
                    <tbody class="discountTotal" style="margin: 20px;">
                        @php
                            $totalItemDiscounted = 0; 
                            $totalItemValue = 0;
                            $totalDiscountAmount = 0;
                            $totalDiscountRefund = 0;
                            $totalNetDiscount = 0;
                        @endphp

                        @if(!empty($getDiscountSummaryByServices->original['discountComplete']) || $getDiscountSummaryByServices->original['discountRefund'])

                            @foreach($getDiscountSummaryByServices->original['discountComplete'] as $discountCompleteKey => $discountCompleteValue)

                                @php
                                    $totalItemDiscounted += ($discountCompleteValue['count'] - (!empty($getDiscountSummary->original[$discountCompleteKey]['discountRefund']) ? (($getDiscountSummary->original[$discountCompleteKey]['discountRefund']['service_name'] == $discountCompleteValue['service_name']) ? $getDiscountSummary->original[$discountCompleteKey]['discountRefund']['refund_count'] : 0 ) : 0) );

                                    $totalItemValue += $discountCompleteValue['og_price_sum'] - ( !empty($getDiscountSummary->original[$discountCompleteKey]['discountRefund']) ? ( ( $getDiscountSummary->original[$discountCompleteKey]['discountRefund']['service_name'] ==  $discountCompleteValue['service_name']) ? $getDiscountSummary->original[$discountCompleteKey]['discountRefund']['refund_og_price_sum'] : 0 ) : 0);

                                    $totalDiscountAmount += $discountCompleteValue['discount_price'];

                                    $totalDiscountRefund += ( !empty($getDiscountSummary->original[$discountCompleteKey]['discountRefund']) ? ( ( $getDiscountSummary->original[$discountCompleteKey]['discountRefund']['service_name'] ==  $discountCompleteValue['service_name']) ? $getDiscountSummary->original[$discountCompleteKey]['discountRefund']['refund_discount_price'] : 0 ) : 0);

                                    $totalNetDiscount += $discountCompleteValue['discount_price'] - ( !empty($getDiscountSummary->original[$discountCompleteKey]['discountRefund']) ? ( ( $getDiscountSummary->original[$discountCompleteKey]['discountRefund']['service_name'] ==  $discountCompleteValue['service_name']) ? $getDiscountSummary->original[$discountCompleteKey]['discountRefund']['refund_discount_price'] : 0 ) : 0);

                                @endphp
                                <tr class="font-weight-bolder" @if($discountCompleteKey == 0) style="margin:2%; font-size: 10px;border-top:1px solid black" @else style="margin:2%; font-size: 10px;" @endif>
                                    <td style="text-align: left;">{{$discountCompleteValue['service_name']}}</td>

                                    <td class="number" style="text-align: right;">
                                        {{ 
                                            ($discountCompleteValue['count'] - (!empty($getDiscountSummaryByServices->original[$discountCompleteKey]['discountRefund']) ? (($getDiscountSummaryByServices->original[$discountCompleteKey]['discountRefund']['service_name'] == $discountCompleteValue['service_name']) ? $getDiscountSummaryByServices->original[$discountCompleteKey]['discountRefund']['refund_count'] : 0 ) : 0) ) 
                                        }}
                                    </td>

                                    <td style="text-align: right;">
                                        CA ${{
                                            number_format($discountCompleteValue['og_price_sum'] - ( !empty($getDiscountSummaryByServices->original[$discountCompleteKey]['discountRefund']) ? ( ( $getDiscountSummaryByServices->original[$discountCompleteKey]['discountRefund']['service_name'] ==  $discountCompleteValue['service_name']) ? $getDiscountSummaryByServices->original[$discountCompleteKey]['discountRefund']['refund_og_price_sum'] : 0 ) : 0),2,'.','')
                                        }}
                                    </td>
                                    <td style="text-align: right;">
                                        CA ${{
                                            number_format($discountCompleteValue['discount_price'],2,'.','')
                                        }}
                                    </td>
                                    <td style="text-align: right;">
                                        CA ${{
                                            number_format(( !empty($getDiscountSummaryByServices->original[$discountCompleteKey]['discountRefund']) ? ( ( $getDiscountSummaryByServices->original[$discountCompleteKey]['discountRefund']['service_name'] ==  $discountCompleteValue['service_name']) ? $getDiscountSummaryByServices->original[$discountCompleteKey]['discountRefund']['refund_discount_price'] : 0 ) : 0),2,'.','')
                                        }}
                                    </td>
                                    <td style="text-align: right;">
                                        CA ${{
                                            number_format($discountCompleteValue['discount_price'] - ( !empty($getDiscountSummaryByServices->original[$discountCompleteKey]['discountRefund']) ? ( ( $getDiscountSummaryByServices->original[$discountCompleteKey]['discountRefund']['service_name'] ==  $discountCompleteValue['service_name']) ? $getDiscountSummaryByServices->original[$discountCompleteKey]['discountRefund']['refund_discount_price'] : 0 ) : 0),2,'.','')
                                        }}
                                    </td>
                                </tr>
                            @endforeach
                            <tr class="font-weight-bolder" style="margin:2%; font-size: 10px; font-weight: bold;">
                                <td style="text-align: left; border-top:0.5px solid gray;">Total</td>
                                <td class="number" style="text-align: right; border-top:0.5px solid gray;">{{ $totalItemDiscounted }}</td>
                                <td style="text-align: right; border-top:0.5px solid gray;">CA ${{ number_format($totalItemValue,2,'.','') }} </td>
                                <td style="text-align: right; border-top:0.5px solid gray;">CA ${{ number_format($totalDiscountAmount,2,'.','') }}</td>
                                <td style="text-align: right; border-top:0.5px solid gray;">CA ${{ number_format($totalDiscountRefund,2,'.','') }}</td>
                                <td style="text-align: right; border-top:0.5px solid gray;">CA ${{ number_format($totalNetDiscount,2,'.','') }}</td>
                            </tr>
                        @else
                        <tr class="font-weight-bolder" style="margin:2%; font-size: 10px; font-weight: bold;">
                                <td style="text-align: left; border-top:0.5px solid gray;">Total</td>
                                <td class="number" style="text-align: right; border-top:0.5px solid gray;">0</td>
                                <td style="text-align: right; border-top:0.5px solid gray;">CA $0.00</td>
                                <td style="text-align: right; border-top:0.5px solid gray;">CA $0.00</td>
                                <td style="text-align: right; border-top:0.5px solid gray;">CA $0.00</td>
                                <td style="text-align: right; border-top:0.5px solid gray;">CA $0.00</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <h3 class="my-4">Discounts by Product</h3>
            <div class="table-responsive bg-white rounded">
                <table class="table table-hover" style="width:100%">
                    <thead style="margin: 50px;">
                        <tr style="margin:2%; font-size: 10px; border-bottom:1px solid black;">
                            <th style="text-align: left;">Product Name</th>
                            <th style="text-align: right;">Items Discounted</th>
                            <th style="text-align: right;">Items Value</th>
                            <th style="text-align: right;">Discount Amount</th>
                            <th style="text-align: right;">Discount Refunds</th>
                            <th style="text-align: right;">Net Discounts</th>
                        </tr>
                    </thead>
                    <tbody class="discountTotal" style="margin: 20px;">
                        @php
                            $totalItemDiscounted = 0; 
                            $totalItemValue = 0;
                            $totalDiscountAmount = 0;
                            $totalDiscountRefund = 0;
                            $totalNetDiscount = 0;
                        @endphp

                        @if(!empty($getDiscountSummaryByProduct->original['discountComplete']) || $getDiscountSummaryByProduct->original['discountRefund'])

                            @foreach($getDiscountSummaryByProduct->original['discountComplete'] as $discountCompleteKey => $discountCompleteValue)
                                @php
                                    $totalItemDiscounted += ($discountCompleteValue['count'] - (!empty($getDiscountSummary->original[$discountCompleteKey]['discountRefund']) ? (($getDiscountSummary->original[$discountCompleteKey]['discountRefund']['product_name'] == $discountCompleteValue['product_name']) ? $getDiscountSummary->original[$discountCompleteKey]['discountRefund']['refund_count'] : 0 ) : 0) );

                                    $totalItemValue += $discountCompleteValue['og_price_sum'] - ( !empty($getDiscountSummary->original[$discountCompleteKey]['discountRefund']) ? ( ( $getDiscountSummary->original[$discountCompleteKey]['discountRefund']['product_name'] ==  $discountCompleteValue['product_name']) ? $getDiscountSummary->original[$discountCompleteKey]['discountRefund']['refund_og_price_sum'] : 0 ) : 0);

                                    $totalDiscountAmount += $discountCompleteValue['discount_price'];

                                    $totalDiscountRefund += ( !empty($getDiscountSummary->original[$discountCompleteKey]['discountRefund']) ? ( ( $getDiscountSummary->original[$discountCompleteKey]['discountRefund']['product_name'] ==  $discountCompleteValue['product_name']) ? $getDiscountSummary->original[$discountCompleteKey]['discountRefund']['refund_discount_price'] : 0 ) : 0);

                                    $totalNetDiscount += $discountCompleteValue['discount_price'] - ( !empty($getDiscountSummary->original[$discountCompleteKey]['discountRefund']) ? ( ( $getDiscountSummary->original[$discountCompleteKey]['discountRefund']['product_name'] ==  $discountCompleteValue['product_name']) ? $getDiscountSummary->original[$discountCompleteKey]['discountRefund']['refund_discount_price'] : 0 ) : 0);

                                @endphp
                                <tr class="font-weight-bolder" @if($discountCompleteKey == 0) style="margin:2%; font-size: 10px;border-top:1px solid black" @else style="margin:2%; font-size: 10px;" @endif>
                                    <td style="text-align: left;">{{$discountCompleteValue['product_name']}}</td>

                                    <td class="number" style="text-align: right;">
                                        {{ 
                                            ($discountCompleteValue['count'] - (!empty($getDiscountSummaryByProduct->original[$discountCompleteKey]['discountRefund']) ? (($getDiscountSummaryByProduct->original[$discountCompleteKey]['discountRefund']['product_name'] == $discountCompleteValue['product_name']) ? $getDiscountSummaryByProduct->original[$discountCompleteKey]['discountRefund']['refund_count'] : 0 ) : 0) ) 
                                        }}
                                    </td>

                                    <td style="text-align: right;">
                                        CA ${{
                                            number_format($discountCompleteValue['og_price_sum'] - ( !empty($getDiscountSummaryByProduct->original[$discountCompleteKey]['discountRefund']) ? ( ( $getDiscountSummaryByProduct->original[$discountCompleteKey]['discountRefund']['product_name'] ==  $discountCompleteValue['product_name']) ? $getDiscountSummaryByProduct->original[$discountCompleteKey]['discountRefund']['refund_og_price_sum'] : 0 ) : 0),2,'.','')
                                        }}
                                    </td>
                                    <td style="text-align: right;">
                                        CA ${{
                                            number_format($discountCompleteValue['discount_price'],2,'.','')
                                        }}
                                    </td>
                                    <td style="text-align: right;">
                                        CA ${{
                                            number_format(( !empty($getDiscountSummaryByProduct->original[$discountCompleteKey]['discountRefund']) ? ( ( $getDiscountSummaryByProduct->original[$discountCompleteKey]['discountRefund']['product_name'] ==  $discountCompleteValue['product_name']) ? $getDiscountSummaryByProduct->original[$discountCompleteKey]['discountRefund']['refund_discount_price'] : 0 ) : 0),2,'.','')
                                        }}
                                    </td>
                                    <td style="text-align: right;">
                                        CA ${{
                                            number_format($discountCompleteValue['discount_price'] - ( !empty($getDiscountSummaryByProduct->original[$discountCompleteKey]['discountRefund']) ? ( ( $getDiscountSummaryByProduct->original[$discountCompleteKey]['discountRefund']['product_name'] ==  $discountCompleteValue['product_name']) ? $getDiscountSummaryByProduct->original[$discountCompleteKey]['discountRefund']['refund_discount_price'] : 0 ) : 0),2,'.','')
                                        }}
                                    </td>
                                </tr>
                            @endforeach
                            <tr class="font-weight-bolder" style="margin:2%; font-size: 10px; font-weight: bold;">
                                <td style="text-align: left; border-top:0.5px solid gray;">Total</td>
                                <td class="number" style="text-align: right; border-top:0.5px solid gray;">{{ $totalItemDiscounted }}</td>
                                <td style="text-align: right; border-top:0.5px solid gray;">CA ${{ number_format($totalItemValue,2,'.','') }} </td>
                                <td style="text-align: right; border-top:0.5px solid gray;">CA ${{ number_format($totalDiscountAmount,2,'.','') }}</td>
                                <td style="text-align: right; border-top:0.5px solid gray;">CA ${{ number_format($totalDiscountRefund,2,'.','') }}</td>
                                <td style="text-align: right; border-top:0.5px solid gray;">CA ${{ number_format($totalNetDiscount,2,'.','') }}</td>
                            </tr>
                        @else
                            <tr class="font-weight-bolder" style="margin:2%; font-size: 10px; font-weight: bold;">
                                <td style="text-align: left; border-top:0.5px solid gray;">Total</td>
                                <td class="number" style="text-align: right; border-top:0.5px solid gray;">0</td>
                                <td style="text-align: right; border-top:0.5px solid gray;">CA $0.00</td>
                                <td style="text-align: right; border-top:0.5px solid gray;">CA $0.00</td>
                                <td style="text-align: right; border-top:0.5px solid gray;">CA $0.00</td>
                                <td style="text-align: right; border-top:0.5px solid gray;">CA $0.00</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <h3 class="my-4">Discounts by Staff</h3>
            <div class="table-responsive bg-white rounded">
                <table class="table table-hover" style="width:100%">
                    <thead style="margin: 50px;">
                        <tr style="margin:2%; font-size: 10px; border-bottom:1px solid black;">
                            <th style="text-align: left;">Staff Name</th>
                            <th style="text-align: right;">Items Discounted</th>
                            <th style="text-align: right;">Items Value</th>
                            <th style="text-align: right;">Discount Amount</th>
                            <th style="text-align: right;">Discount Refunds</th>
                            <th style="text-align: right;">Net Discounts</th>
                        </tr>
                    </thead>
                    <tbody class="discountTotal" style="margin: 20px;">
                        @php
                            $totalItemDiscounted = 0; 
                            $totalItemValue = 0;
                            $totalDiscountAmount = 0;
                            $totalDiscountRefund = 0;
                            $totalNetDiscount = 0;
                        @endphp

                        @if(!empty($getDiscountSummaryByStaff->original['discountComplete']) || $getDiscountSummaryByStaff->original['discountRefund'])

                            @foreach($getDiscountSummaryByStaff->original['discountComplete'] as $discountCompleteKey => $discountCompleteValue)
                                @php
                                    $totalItemDiscounted += ($discountCompleteValue['count'] - (!empty($getDiscountSummary->original[$discountCompleteKey]['discountRefund']) ? (($getDiscountSummary->original[$discountCompleteKey]['discountRefund']['staff_id'] == $discountCompleteValue['staff_id']) ? $getDiscountSummary->original[$discountCompleteKey]['discountRefund']['refund_count'] : 0 ) : 0) );

                                    $totalItemValue += $discountCompleteValue['og_price_sum'] - ( !empty($getDiscountSummary->original[$discountCompleteKey]['discountRefund']) ? ( ( $getDiscountSummary->original[$discountCompleteKey]['discountRefund']['staff_id'] ==  $discountCompleteValue['staff_id']) ? $getDiscountSummary->original[$discountCompleteKey]['discountRefund']['refund_og_price_sum'] : 0 ) : 0);

                                    $totalDiscountAmount += $discountCompleteValue['discount_price'];

                                    $totalDiscountRefund += ( !empty($getDiscountSummary->original[$discountCompleteKey]['discountRefund']) ? ( ( $getDiscountSummary->original[$discountCompleteKey]['discountRefund']['staff_id'] ==  $discountCompleteValue['staff_id']) ? $getDiscountSummary->original[$discountCompleteKey]['discountRefund']['refund_discount_price'] : 0 ) : 0);

                                    $totalNetDiscount += $discountCompleteValue['discount_price'] - ( !empty($getDiscountSummary->original[$discountCompleteKey]['discountRefund']) ? ( ( $getDiscountSummary->original[$discountCompleteKey]['discountRefund']['staff_id'] ==  $discountCompleteValue['staff_id']) ? $getDiscountSummary->original[$discountCompleteKey]['discountRefund']['refund_discount_price'] : 0 ) : 0);

                                @endphp
                                <tr class="font-weight-bolder" @if($discountCompleteKey == 0) style="margin:2%; font-size: 10px;border-top:1px solid black" @else style="margin:2%; font-size: 10px;" @endif>
                                    <td style="text-align: left;">{{$discountCompleteValue['staff_name']}}</td>

                                    <td class="number" style="text-align: right;">
                                        {{ 
                                            ($discountCompleteValue['count'] - (!empty($getDiscountSummaryByStaff->original[$discountCompleteKey]['discountRefund']) ? (($getDiscountSummaryByStaff->original[$discountCompleteKey]['discountRefund']['staff_id'] == $discountCompleteValue['staff_id']) ? $getDiscountSummaryByStaff->original[$discountCompleteKey]['discountRefund']['refund_count'] : 0 ) : 0) ) 
                                        }}
                                    </td>

                                    <td style="text-align: right;">
                                        CA ${{
                                            number_format($discountCompleteValue['og_price_sum'] - ( !empty($getDiscountSummaryByStaff->original[$discountCompleteKey]['discountRefund']) ? ( ( $getDiscountSummaryByStaff->original[$discountCompleteKey]['discountRefund']['staff_id'] ==  $discountCompleteValue['staff_id']) ? $getDiscountSummaryByStaff->original[$discountCompleteKey]['discountRefund']['refund_og_price_sum'] : 0 ) : 0),2,'.','')
                                        }}
                                    </td>
                                    <td style="text-align: right;">
                                        CA ${{
                                            number_format($discountCompleteValue['discount_price'],2,'.','')
                                        }}
                                    </td>
                                    <td style="text-align: right;">
                                        CA ${{
                                            number_format(( !empty($getDiscountSummaryByStaff->original[$discountCompleteKey]['discountRefund']) ? ( ( $getDiscountSummaryByStaff->original[$discountCompleteKey]['discountRefund']['staff_id'] ==  $discountCompleteValue['staff_id']) ? $getDiscountSummaryByStaff->original[$discountCompleteKey]['discountRefund']['refund_discount_price'] : 0 ) : 0),2,'.','')
                                        }}
                                    </td>
                                    <td style="text-align: right;">
                                        CA ${{
                                            number_format($discountCompleteValue['discount_price'] - ( !empty($getDiscountSummaryByStaff->original[$discountCompleteKey]['discountRefund']) ? ( ( $getDiscountSummaryByStaff->original[$discountCompleteKey]['discountRefund']['staff_id'] ==  $discountCompleteValue['staff_id']) ? $getDiscountSummaryByStaff->original[$discountCompleteKey]['discountRefund']['refund_discount_price'] : 0 ) : 0),2,'.','')
                                        }}
                                    </td>
                                </tr>
                            @endforeach
                            <tr class="font-weight-bolder" style="margin:2%; font-size: 10px; font-weight: bold;">
                                <td style="text-align: left; border-top:0.5px solid gray;">Total</td>
                                <td class="number" style="text-align: right; border-top:0.5px solid gray;">{{ $totalItemDiscounted }}</td>
                                <td style="text-align: right; border-top:0.5px solid gray;">CA ${{ number_format($totalItemValue,2,'.','') }} </td>
                                <td style="text-align: right; border-top:0.5px solid gray;">CA ${{ number_format($totalDiscountAmount,2,'.','') }}</td>
                                <td style="text-align: right; border-top:0.5px solid gray;">CA ${{ number_format($totalDiscountRefund,2,'.','') }}</td>
                                <td style="text-align: right; border-top:0.5px solid gray;">CA ${{ number_format($totalNetDiscount,2,'.','') }}</td>
                            </tr>
                        @else
                            <tr class="font-weight-bolder" style="margin:2%; font-size: 10px; font-weight: bold;">
                                <td style="text-align: left; border-top:0.5px solid gray;">Total</td>
                                <td class="number" style="text-align: right; border-top:0.5px solid gray;">0</td>
                                <td style="text-align: right; border-top:0.5px solid gray;">CA $0.00 </td>
                                <td style="text-align: right; border-top:0.5px solid gray;">CA $0.00</td>
                                <td style="text-align: right; border-top:0.5px solid gray;">CA $0.00</td>
                                <td style="text-align: right; border-top:0.5px solid gray;">CA $0.00</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <h3 class="my-4">Discounts by Type</h3>
            <div class="table-responsive bg-white rounded">
                <table class="table table-hover" style="width:100%">
                    <thead style="margin: 50px;">
                        <tr style="margin:2%; font-size: 10px; border-bottom:1px solid black;">
                            <th style="text-align: left;">Type</th>
                            <th style="text-align: right;">Items Discounted</th>
                            <th style="text-align: right;">Items Value</th>
                            <th style="text-align: right;">Discount Amount</th>
                            <th style="text-align: right;">Discount Refunds</th>
                            <th style="text-align: right;">Net Discounts</th>
                        </tr>
                    </thead>
                    <tbody class="discountTotal" style="margin: 20px;">
                        @php
                            $totalItemDiscounted = 0; 
                            $totalItemValue = 0;
                            $totalDiscountAmount = 0;
                            $totalDiscountRefund = 0;
                            $totalNetDiscount = 0;
                        @endphp

                        @if(!empty($getDiscountSummaryByType->original['discountComplete']) || $getDiscountSummaryByType->original['discountRefund'])

                            @foreach($getDiscountSummaryByType->original['discountComplete'] as $discountCompleteKey => $discountCompleteValue)
                                @php
                                    $totalItemDiscounted += ($discountCompleteValue['count'] - (!empty($getDiscountSummary->original[$discountCompleteKey]['discountRefund']) ? (($getDiscountSummary->original[$discountCompleteKey]['discountRefund']['type'] == $discountCompleteValue['type']) ? $getDiscountSummary->original[$discountCompleteKey]['discountRefund']['refund_count'] : 0 ) : 0) );

                                    $totalItemValue += $discountCompleteValue['og_price_sum'] - ( !empty($getDiscountSummary->original[$discountCompleteKey]['discountRefund']) ? ( ( $getDiscountSummary->original[$discountCompleteKey]['discountRefund']['type'] ==  $discountCompleteValue['type']) ? $getDiscountSummary->original[$discountCompleteKey]['discountRefund']['refund_og_price_sum'] : 0 ) : 0);

                                    $totalDiscountAmount += $discountCompleteValue['discount_price'];

                                    $totalDiscountRefund += ( !empty($getDiscountSummary->original[$discountCompleteKey]['discountRefund']) ? ( ( $getDiscountSummary->original[$discountCompleteKey]['discountRefund']['type'] ==  $discountCompleteValue['type']) ? $getDiscountSummary->original[$discountCompleteKey]['discountRefund']['refund_discount_price'] : 0 ) : 0);

                                    $totalNetDiscount += $discountCompleteValue['discount_price'] - ( !empty($getDiscountSummary->original[$discountCompleteKey]['discountRefund']) ? ( ( $getDiscountSummary->original[$discountCompleteKey]['discountRefund']['type'] ==  $discountCompleteValue['type']) ? $getDiscountSummary->original[$discountCompleteKey]['discountRefund']['refund_discount_price'] : 0 ) : 0);

                                @endphp
                                <tr class="font-weight-bolder" @if($discountCompleteKey == 0) style="margin:2%; font-size: 10px;border-top:1px solid black" @else style="margin:2%; font-size: 10px;" @endif>
                                    <td style="text-align: left;">{{$discountCompleteValue['type']}}</td>

                                    <td class="number" style="text-align: right;">
                                        {{ 
                                            ($discountCompleteValue['count'] - (!empty($getDiscountSummaryByType->original[$discountCompleteKey]['discountRefund']) ? (($getDiscountSummaryByType->original[$discountCompleteKey]['discountRefund']['type'] == $discountCompleteValue['type']) ? $getDiscountSummaryByType->original[$discountCompleteKey]['discountRefund']['refund_count'] : 0 ) : 0) ) 
                                        }}
                                    </td>

                                    <td style="text-align: right;">
                                        CA ${{
                                            number_format($discountCompleteValue['og_price_sum'] - ( !empty($getDiscountSummaryByType->original[$discountCompleteKey]['discountRefund']) ? ( ( $getDiscountSummaryByType->original[$discountCompleteKey]['discountRefund']['type'] ==  $discountCompleteValue['type']) ? $getDiscountSummaryByType->original[$discountCompleteKey]['discountRefund']['refund_og_price_sum'] : 0 ) : 0),2,'.','')
                                        }}
                                    </td>
                                    <td style="text-align: right;">
                                        CA ${{
                                            number_format($discountCompleteValue['discount_price'],2,'.','')
                                        }}
                                    </td>
                                    <td style="text-align: right;">
                                        CA ${{
                                            number_format(( !empty($getDiscountSummaryByType->original[$discountCompleteKey]['discountRefund']) ? ( ( $getDiscountSummaryByType->original[$discountCompleteKey]['discountRefund']['type'] ==  $discountCompleteValue['type']) ? $getDiscountSummaryByType->original[$discountCompleteKey]['discountRefund']['refund_discount_price'] : 0 ) : 0),2,'.','')
                                        }}
                                    </td>
                                    <td style="text-align: right;">
                                        CA ${{
                                            number_format($discountCompleteValue['discount_price'] - ( !empty($getDiscountSummaryByType->original[$discountCompleteKey]['discountRefund']) ? ( ( $getDiscountSummaryByType->original[$discountCompleteKey]['discountRefund']['type'] ==  $discountCompleteValue['type']) ? $getDiscountSummaryByType->original[$discountCompleteKey]['discountRefund']['refund_discount_price'] : 0 ) : 0),2,'.','')
                                        }}
                                    </td>
                                </tr>
                            @endforeach
                            <tr class="font-weight-bolder" style="margin:2%; font-size: 10px; font-weight: bold;">
                                <td style="text-align: left; border-top:0.5px solid gray;">Total</td>
                                <td class="number" style="text-align: right; border-top:0.5px solid gray;">{{ $totalItemDiscounted }}</td>
                                <td style="text-align: right; border-top:0.5px solid gray;">CA ${{ number_format($totalItemValue,2,'.','') }} </td>
                                <td style="text-align: right; border-top:0.5px solid gray;">CA ${{ number_format($totalDiscountAmount,2,'.','') }}</td>
                                <td style="text-align: right; border-top:0.5px solid gray;">CA ${{ number_format($totalDiscountRefund,2,'.','') }}</td>
                                <td style="text-align: right; border-top:0.5px solid gray;">CA ${{ number_format($totalNetDiscount,2,'.','') }}</td>
                            </tr>
                        @else
                            <tr class="font-weight-bolder" style="margin:2%; font-size: 10px; font-weight: bold;">
                                <td style="text-align: left; border-top:0.5px solid gray;">Total</td>
                                <td class="number" style="text-align: right; border-top:0.5px solid gray;">0</td>
                                <td style="text-align: right; border-top:0.5px solid gray;">CA $0.00</td>
                                <td style="text-align: right; border-top:0.5px solid gray;">CA $0.00</td>
                                <td style="text-align: right; border-top:0.5px solid gray;">CA $0.00</td>
                                <td style="text-align: right; border-top:0.5px solid gray;">CA $0.00</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>