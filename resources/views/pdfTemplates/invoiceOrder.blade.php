<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="ScheduleDown">
    <meta name="author" content="ScheduleDown">
    <link rel="icon" type="image/png" href="https://scheduledown.com/img/fav.png">
    <title>ScheduleDown</title>
    <!--link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet"-->
</head>
<body style="margin: 0; padding: 0; font-family: 'Roboto', sans-serif;"> 
    <div style="width: 100%; margin: 0 auto; background: #fff">
        <table align="center" cellpadding="0" cellspacing="0" border="0" style="width: 100%; text-align: center;">
            <tr>
                <td><h3 style="margin:22px 0 5px;line-height:24px; font-weight: bold; font-size: 15px;">{{ ($LocationInfo['location_name']) ? $LocationInfo['location_name'] : '' }}</h3></td>
            </tr>
            <tr>
                <td><p style="color: #000; font-size: 14px; margin: 0 0 2px">{{ ($LocationInfo['location_name']) ? $LocationInfo['location_name'] : '' }}, {{ ($LocationInfo['location_address']) ? $LocationInfo['location_address'] : '' }}</p></td>
            </tr>
            <tr>
                <td><p style="color: #000; font-size: 14px; margin: 0px 0 2px">+{{ ($LocationInfo['country_code']) ? $LocationInfo['country_code'] : '' }} {{ ($LocationInfo['location_phone']) ? $LocationInfo['location_phone'] : '' }}</p></td>
            </tr>
            <tr>
                <td><p style="color: #000; font-size: 14px; margin: 0 0 2px">{{ (isset($InvoiceTemplate->receiptLine1)) ? $InvoiceTemplate->receiptLine1 : '' }}</p></td>
            </tr>
            <tr>
                <td><h3 style="margin:22px 0 5px;line-height:24px; font-weight: bold;font-size: 15px;">{{ (isset($InvoiceTemplate->title)) ? $InvoiceTemplate->title : 'Invoice' }} #{{ ($Invoice['id']) ? $Invoice['id'] : '' }}</h3>
                </td>
            </tr>
            <tr>
                <td><p style="color: #000; font-size: 14px; margin: 0 0 2px">{{ ($Invoice['created_at']) ? date("l, d M Y, H:ia",strtotime($Invoice['created_at'])) : '' }}</p></td>
            </tr>
        </table>
        <table cellpadding="0" cellspacing="0" style="width:100%;font-family: 'Roboto', sans-serif;color:#2c2c2c;background:#fff;margin:0 auto">
            <tr>
                <td style="background:#ffffff;padding:0 16px">
                    <table cellpadding="0" cellspacing="0" style="width:100%;color:#2c2c2c">
                        <tbody>
                            <tr style="font-size:15px;line-height:24px">
                                <td style="padding: 30px 0 8px">Client</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="background:#ffffff;padding:0 16px">
                    <table cellpadding="0" cellspacing="0" style="width:100%;color:#2c2c2c">
                        <tbody>
							@if(!empty($ClientInfo))
								<tr style="font-size:17px;line-height:24px">
									<td style="padding-top:12px; border-top: 1px solid #D8D8D8">
										<p style="font-size:15px;margin:0; line-height:24px">{{ ($ClientInfo->firstname) ? $ClientInfo->firstname : '' }} {{ ($ClientInfo->lastname) ? $ClientInfo->lastname : '' }}</p>
										
										@if(isset($InvoiceTemplate->showAddress) && $InvoiceTemplate->showAddress == 1)
										<p style="font-size:15px;margin:0;color:#037aff;line-height:21px"><a href="mailto:{{ ($ClientInfo->email) ? $ClientInfo->email : '' }}" target="_blank">{{ ($ClientInfo->email) ? $ClientInfo->email : '' }}</a></p>
										@endif
										
										@if(isset($InvoiceTemplate->showMobile) && $InvoiceTemplate->showMobile == 1)
										<p style="font-size:15px;margin:0;color:#626262;line-height:21px">+{{ ($ClientInfo->mo_country_code) ? $ClientInfo->mo_country_code : '' }} {{ ($ClientInfo->mobileno) ? $ClientInfo->mobileno : '' }}</p> 
										@endif
									</td>
								</tr>
							@else
                                <tr style="font-size:15px;line-height:24px">
                                    <td style="padding: 8px 0 24px;border-top: 1px solid #D8D8D8; border-bottom: 3px solid #D8D8D8;">Walk-In</td>
                                </tr>
							@endif
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <!--  -->
                <td style="padding:0px 16px 12px;background:#ffffff;">
                    <table cellpadding="0" cellspacing="0" style="width:100%; color:#2c2c2c;">
                        <tbody>
							@if(!empty($InvoiceItemsInfo))
								@foreach($InvoiceItemsInfo as $key => $InvoiceItemsData)
									<tr style="font-size:16px;line-height:24px">
										<td style="width:10%;padding-top: 12px;">{{ ($InvoiceItemsData['quantity']) ? $InvoiceItemsData['quantity'] : 0 }}</td>
										<td style="width:55%;padding-top: 12px;">{{ ($InvoiceItemsData['main_title']) ? $InvoiceItemsData['main_title'] : 'N/A' }}</td>
										<td style="width:35%;padding-top: 12px;text-align:right;vertical-align:top">
											@if($InvoiceItemsData['item_og_price'] > $InvoiceItemsData['item_price'])
												<span>${{ $InvoiceItemsData['item_price'] }}</span>	
												<span style="text-decoration: line-through;">${{ $InvoiceItemsData['item_og_price'] }}</span>	
											@else
												<span>${{ $InvoiceItemsData['item_og_price'] }}</span>	
											@endif
										</td>
									</tr>
									<tr style="color:#878c93;font-size:13px;line-height:21px">
										<td style="width:10%;"></td>
										<td style="width:65%;">{{ ($InvoiceItemsData['staff_name']) ? $InvoiceItemsData['staff_name'] : 'N/A' }}</td>
										<td style="width:35%;"></td>
									</tr>
								@endforeach
							@endif
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding:4px 16px 12px;background:#ffffff">
                    <table cellpadding="0" cellspacing="0" style="width:100%">
                        <tbody>
                            <tr style="font-size:15px;line-height:21px;">
                                <td style="width:65%;padding-right:4%;padding-top:12px;border-top:1px solid #D8D8D8;">Subtotal</td>
                                <td style="width:35%;padding-top:12px;text-align:right;border-top:1px solid #D8D8D8;">${{ $Invoice['invoice_sub_total'] }}</td>
                            </tr>
							@if(!empty($InvoiceTaxes))
								@foreach($InvoiceTaxes as $InvoiceTaxData)
									<tr style="font-size:15px;line-height:21px;">
										<td style="width:65%;padding-right:4%;">{{ $InvoiceTaxData['tax_name'] }} {{ $InvoiceTaxData['tax_rate'] }}</td>
										<td style="width:35%;text-align:right">${{ $InvoiceTaxData['tax_amount'] }}</td>
									</tr> 
								@endforeach
							@endif
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding:4px 16px 12px;background:#ffffff">
                    <table cellpadding="0" cellspacing="0" style="width:100%">
                        <tbody>
                            <tr style="font-size:15px;line-height:21px;">
                                <td style="width:65%;padding-right:4%;padding-top:12px;border-top:1px solid #D8D8D8;">Total</td>
                                <td style="width:35%;padding-top:12px;text-align:right;border-top:1px solid #D8D8D8;">${{ $Invoice['invoice_total'] }}</td>
                            </tr> 
							@if($TotalStaffTip[0]['total_tip'] > 0)
							<tr style="font-size:15px;line-height:21px;">
								<td style="width:65%;padding-right:4%;padding-top:12px;border-top:1px solid #D8D8D8;">Tips</td>
								<td style="width:35%;padding-top:12px;text-align:right;border-top:1px solid #D8D8D8;">${{ $TotalStaffTip[0]['total_tip'] }}</td>
							</tr> 	
							@endif
                        </tbody>
                    </table>
                </td>
            </tr> 
            <tr>
                <td style="padding:4px 16px 12px;background:#ffffff">
                    <table cellpadding="0" cellspacing="0" style="width:100%">
                        <tbody>
                            <tr style="font-size:15px;line-height:21px;">
                                <td style="width:65%;padding-right:4%;padding-top:12px;border-top:1px solid #D8D8D8;">{{ $Invoice['payment_type'] }}</td>
                                <td style="width:35%;padding-top:12px;text-align:right;border-top:1px solid #D8D8D8;">${{ $Invoice['inovice_final_total'] }}</td>
                            </tr>
							@if($Invoice['payment_date'] != '')
                            <tr style="font-size:15px;line-height:21px; color: #878c93">
                                <td colspan="2" style="font-size: 13px;">{{ ($Invoice['payment_date']) ? date("l, d M Y",strtotime($Invoice['payment_date'])) : '' }} at {{ ($Invoice['payment_date']) ? date("h:ia",strtotime($Invoice['payment_date'])) : '' }}</td>
                            </tr> 
							@endif
                        </tbody>
                    </table>
                </td>
            </tr> 
            <tr>
                <td style="padding:0 16px 24px;background:#ffffff">
                    <table cellpadding="0" cellspacing="0" style="width:100%;color:#2c2c2c">
                        <tbody>
                            <tr>
                                <td colspan="2" style="width:100%;border-top:1px solid #D8D8D8;padding-top:16px"></td>
                            </tr>
                            <tr style="font-size:18px;font-weight:bold">
                                <td style="width:65%;padding-right:4%;padding-bottom:16px">Balance</td>
                                <td style="width:35%;padding-bottom:16px;text-align:right">${{ ($Invoice['invoice_status'] == 0) ? $Invoice['inovice_final_total'] : 0 }}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <!-- <tr>
                <td style="padding:0 16px 16px;background:#ffffff">
                    <table cellpadding="0" cellspacing="0" style="width:100%;color:#2c2c2c">
                        <tbody>
                            <tr style="font-size:17px;text-align:left;line-height:24px">
                                <td style="background:#f2f2f7;border-radius:8px;padding:16px">
                                    <p style="text-align: center;"></p>
                                </td>
                            </tr> 
                        </tbody>
                    </table>
                </td>
            </tr>  -->
        </table>
    </div> 
</body>
</html>