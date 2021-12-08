<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="ScheduleDown">
    <meta name="author" content="ScheduleDown">
    <link rel="icon" type="image/png" href="https://scheduledown.com/img/fav.png">
    <title>ScheduleDown</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
</head>

<body style="background: #DFDFE4; margin: 0; padding: 0; font-family: 'Roboto', sans-serif;">
    <div style="margin: 0 auto; background: #F2F2F7;width: 100%;max-width: 550px;">
        <div style="padding: 32px 16px;">
            <h1>{{ ($invoice_template->title) ? $invoice_template->title : 'Invoice' }} #{{ ($invoice['id']) ?
                $invoice['id'] : '' }}</h1>
            <p style="text-align:left;font-size:15px;margin:0;line-height:21px;color:#878c93">{{
                ($invoice['created_at']) ? date("d M Y",strtotime($invoice['created_at'])) : '' }}</p>
            <h4 style="text-align:left;font-weight:bold;font-size:17px;margin:22px 0 2px;line-height:24px">{{
                ($location_info['location_name']) ? $location_info['location_name'] : '' }}</h4>
            <address style="text-align:left;font-size:15px;margin:0;font-style:normal;color:#878c93;line-height:21px">
                {{ ($location_info['location_address']) ? $location_info['location_address'] : '' }}
            </address>
            <p style="text-align:left;font-size:15px;margin:0;color:#878c93;line-height:21px">+{{
                ($location_info['country_code']) ? $location_info['country_code'] : '' }} {{
                ($location_info['location_phone']) ? $location_info['location_phone'] : '' }}</p>
            <p style="text-align:left;font-size:15px;margin:0;color:#878c93;line-height:21px">{{
                ($invoice_template->receiptLine1) ? $invoice_template->receiptLine1 : '' }}</p>

            @if(!empty($client_info))
            <p style="font-size:14px;margin:0;padding-top:24px;font-size:15px;color:#878c93;line-height:21px">Billed to:
            </p>
            <p style="font-size:17px;margin:0;font-weight:bold;line-height:24px">{{ ($client_info->firstname) ?
                $client_info->firstname : '' }} {{ ($client_info->lastname) ? $client_info->lastname : '' }}</p>

            @if(isset($invoice_template->showAddress) && $invoice_template->showAddress == 1)
            <p style="font-size:15px;margin:0;color:#037aff;line-height:21px"><a
                    href="mailto:{{ ($client_info->email) ? $client_info->email : '' }}" target="_blank">{{
                    ($client_info->email) ? $client_info->email : '' }}</a></p>
            @endif

            @if(isset($invoice_template->showMobile) && $invoice_template->showMobile == 1)
            <p style="font-size:15px;margin:0;color:#626262;line-height:21px">+{{ ($client_info->mo_country_code) ?
                $client_info->mo_country_code : '' }} {{ ($client_info->mobileno) ? $client_info->mobileno : '' }}</p>
            @endif
            @else
            <p style="font-size:14px;margin:0;padding-top:24px;font-size:15px;color:#878c93;line-height:21px">Billed to:
            </p>
            <p style="font-size:17px;margin:0;font-weight:bold;line-height:24px">Walk-In</p>
            @endif
            <p style="text-align:left;font-size:15px;margin:0;color:#878c93;line-height:21px">{{
                ($invoice_template->receiptLine2) ? $invoice_template->receiptLine2 : '' }}</p>
        </div>

        <div style="color:#2c2c2c;">
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <td style="background:#ffffff;padding:0 16px">
                        <table cellpadding="0" cellspacing="0" style="color:#2c2c2c; width:100%;">
                            <tbody>
                                @if(!empty($invoice_item_info))
                                @foreach($invoice_item_info as $key => $InvoiceItemsData)
                                <tr style="font-size:17px;line-height:24px;border-top:1px solid #e7e8e9 !importatn;">
                                    <td style="padding-top:32px;font-size: 17px;">{{
                                        ($InvoiceItemsData['main_title']) ? $InvoiceItemsData['main_title'] : 'N/A' }}
                                    </td>
                                    <td style="padding-top:32px;text-align:right;vertical-align:top; font-size: 17px;">
                                        @if($InvoiceItemsData['item_og_price'] > $InvoiceItemsData['item_price'])
                                        <span>${{ $InvoiceItemsData['item_price'] }}</span>
                                        <span style="text-decoration: line-through;">${{
                                            $InvoiceItemsData['item_og_price']
                                            }}</span>
                                        @else
                                        <span>${{ $InvoiceItemsData['item_og_price'] }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr style="color:#878c93;font-size:15px;line-height:21px">
                                    <td style="padding-top:8px">{{ ($InvoiceItemsData['staff_name']) ?
                                        $InvoiceItemsData['staff_name'] : 'N/A' }}</td>
                                    <td style="padding-top:8px"></td>
                                </tr>
                                <tr style="color:#878c93;font-size:15px;line-height:21px">
                                    <td style="padding:8px 0 0">{{ ($InvoiceItemsData['quantity']) ?
                                        $InvoiceItemsData['quantity'] : 0 }} item</td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding:24px 16px 8px;background:#ffffff">
                        <table cellpadding="0" cellspacing="0" style="width:100%">
                            <tbody>
                                <tr style="font-size:15px;line-height:21px;color:#878c93">
                                    <td
                                        style="padding-right:4%;padding-top:25px;border-top:1px solid #e7e8e9;padding-bottom:16px">
                                        Subtotal</td>
                                    <td
                                        style="padding-top:25px;text-align:right;border-top:1px solid #e7e8e9;padding-bottom:16px">
                                        ${{ $invoice['invoice_sub_total'] }}</td>
                                </tr>
                                @if(!empty($invoice_taxes))
                                @foreach($invoice_taxes as $InvoiceTaxData)
                                <tr style="font-size:15px;line-height:21px;color:#878c93">
                                    <td style="padding-right:4%;padding-bottom:16px">{{
                                        $InvoiceTaxData['tax_name'] }} {{ $InvoiceTaxData['tax_rate'] }}</td>
                                    <td style="padding-bottom:16px;text-align:right">${{
                                        $InvoiceTaxData['tax_amount'] }}</td>
                                </tr>
                                @endforeach
                                @endif
                                <tr style="font-size:17px;line-height:24px;color:#101928;font-weight:bold">
                                    <td style="padding-right:4%;padding-bottom:16px">Total</td>
                                    <td style="padding-bottom:16px;text-align:right">${{ $invoice['invoice_total']
                                        }}</td>
                                </tr>
                                @if($total_staff_tip[0]['total_tip'] > 0)
                                <tr style="font-size:17px;line-height:24px;color:#101928;font-weight:bold">
                                    <td style="padding-right:4%;padding-bottom:16px">Tips</td>
                                    <td style="padding-bottom:16px;text-align:right">${{
                                        $total_staff_tip[0]['total_tip'] }}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding:0 16px;background:#ffffff">
                        <table cellpadding="0" cellspacing="0" style="color:#2c2c2c">
                            <tbody>
                                <tr>
                                    <td colspan="2" style="border-top:1px solid #e7e8e9;padding-top:25px"></td>
                                </tr>
                                <tr style="font-size:17px;line-height:24px;color:#101928">
                                    <td style="padding-right:4%;padding-left:0">{{ $invoice['payment_type'] }}
                                    </td>
                                    <td style="text-align:right;padding-right:0">${{
                                        $invoice['inovice_final_total'] }}</td>
                                </tr>
                                @if($invoice['payment_date'] != '')
                                <tr style="font-size:15px;line-height:21px;color:#878c93">
                                    <td colspan="2" style="padding:0 0 24px">{{ ($invoice['payment_date']) ? date("l, d
                                        M
                                        Y",strtotime($invoice['payment_date'])) : '' }} at {{ ($invoice['payment_date'])
                                        ?
                                        date("h:ia",strtotime($invoice['payment_date'])) : '' }}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding:0 16px 24px;background:#ffffff">
                        <table cellpadding="0" cellspacing="0" style="color:#2c2c2c; width:100%;">
                            <tbody>
                                <tr>
                                    <td colspan="2" style="border-top:1px solid #e7e8e9;padding-top:24px"></td>
                                </tr>
                                <tr style="font-size:24px;font-weight:bold">
                                    <td>Balance</td>
                                    <td style="text-align:right">${{ ($invoice['invoice_status'] == 0) ?
                                        $invoice['inovice_final_total'] : 0 }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding:0 16px 16px;background:#ffffff">
                        <table cellpadding="0" cellspacing="0" style="color:#2c2c2c">
                            <tbody>
                                <tr style="font-size:17px;text-align:left;line-height:24px">
                                    <td style="background:#f2f2f7;border-radius:8px;padding:16px">
                                        <p>{{ ($invoice_template->receiptfooter) ? $invoice_template->receiptfooter : ''
                                            }}
                                        </p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding:0 16px;background:#ffffff" class="">
                        <table cellpadding="0" cellspacing="0" style="width:100%">
                            <tbody>
                                <tr>
                                    <td colspan="2"
                                        style="font-size:16px;font-weight:bold;line-height:22px;text-align:center;padding-top:26px;border-top:1px solid #e7e8e9">
                                        Instantly book salons and spas nearby using</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="padding:32px 0 64px;text-align:center">
                                        <a href="https://scheduledown.com/" target="_blank">
                                        <img src="{{ asset('/assets/images/logo.png') }}"/></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>