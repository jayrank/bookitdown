<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>

<body>
	<div style="background-color: rgb(255, 255, 255); border: 1px solid rgb(223, 223, 223); border-spacing: 0px; color: rgb(16, 25, 40); font-family: Arial, sans-serif; height: auto; margin: 0px auto; max-width: 576px; width: 100%;">
	

		<table cellpadding="0" cellspacing="0" style="background-color: rgb(255, 255, 255); border: 1px solid rgb(223, 223, 223); border-spacing: 0px; color: rgb(16, 25, 40); font-family: Arial, sans-serif; height: auto; margin: 0px auto; max-width: 576px; width: 100%;">
			<tbody>
				<tr>
					<td>
						<table cellpadding="0" cellspacing="0" style="border-spacing:0;color:inherit;width:100%;">
							<tbody>
								<tr style="background-color:#101928;">
									<td cellpadding="0" cellspacing="0"
										style="background-color:inherit;padding:0 8px;width:16px;"></td>
									<td style="background-color:inherit;max-width:100%;width:100%;">
										<table width="100%" cellpadding="0" cellspacing="0"
											style="border-spacing:0;color:#ffffff;width:100%;">
											<tbody>
												<tr>
													<td colspan="2" style="font-size:28px;padding:24px 0 8px 0;">
															Thanks for tipping {{ $first_name }}!
													</td>
												</tr>
												<tr>
													<td colspan="2">
														<table height="28" cellpadding="0" cellspacing="0"
															style="background:#037aff;border-radius:14px;border-spacing:0;color:inherit;font-size:14px;height:28px;line-height:14px;padding:0px 10px 0px 3px;width:auto;">
															<tbody>
																<tr>
																	<td><img width="24" height="24"
																			src="{{ asset('assets/images/completed.png') }}"
																			style="height:24px;margin-right:3px;width:24px;">
																	</td>
																	<td style="font-size:15px;font-weight:bold;height:28px;line-height:28px;padding:0;"> Completed</td>
																</tr>
															</tbody>
														</table>
													</td>
													
												</tr>
												<tr>
													<td colspan="2" style="font-size:20px;line-height:29px;padding-top:24px;">
														{{ ($Invoice['created_at']) ? date("l, d M Y",strtotime($Invoice['created_at'])) : '' }}
													</td>
												</tr>
												<tr>
													<td style="font-size:15px;line-height:22px;padding:0;padding-bottom:24px;padding-top:28px;vertical-align:center;">
														<a style="color:#ffffff;text-decoration:none !important;">
															<div style="font-size: 18px;">
															{{ $locationData->location_name }}
															</div>
															<div style="font-size: 14px;">
															{{ $locationData->location_address }}
															</div>
														</a>
														<a style="color:#ffffff;text-decoration:none !important;">
															<div style="color:#878c93;">
																<!-- Booking ref: 8D556245 -->
															</div>
														</a>
													</td>
													<td style="padding:0;text-align:right;vertical-align:center;">
														<a style="text-decoration:none !important;"><img width="64" height="64" src="{{ asset('assets/images/thumb.jpg') }}" style="border:1px solid #404753;border-radius:12px;height:64px;width:64px;"></a>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
									<td cellpadding="0" cellspacing="0" style="background-color:inherit;padding:0 8px;width:16px;"></td>
								</tr>
								<tr style="background-color:#f2f2f7;">
									<td cellpadding="0" cellspacing="0"
										style="background-color:inherit;padding:0 8px;width:16px;"></td>
									<td style="background-color:inherit;max-width:100%;width:100%;">
										<table width="100%" cellpadding="0" cellspacing="0"
											style="border-spacing:0;color:inherit;width:100%;">
											<tbody>
												<tr>
													<td style="padding-bottom:24px;padding-left:0;padding-right:0;padding-top:24px;">
														<table width="100%" cellpadding="0" cellspacing="0"
															style="border-spacing:0;color:inherit;width:100%;">
															<tbody>
																@if(!empty($InvoiceItemsInfo))
																	@foreach($InvoiceItemsInfo as $InvoiceItemsData)
																		<tr>
																			<td style="font-size:16px;line-height:22px;">
																				{{ ($InvoiceItemsData['main_title']) ? $InvoiceItemsData['main_title'] : 'N/A' }}
																			</td>
																			<td style="font-size:16px;line-height:22px;text-align:right;">
																				@if($is_price_view == 1)
																					@if($InvoiceItemsData['item_og_price'] > $InvoiceItemsData['item_price'])
																						<h4>Ca ${{ $InvoiceItemsData['item_price'] }}</h4>
																					@else
																						<h4>Ca ${{ $InvoiceItemsData['item_og_price'] }}</h4>	
																					@endif
																				@endif
																			</td>
																		</tr>
																	@endforeach
																@endif
																<tr>
																	<td
																		style="font-size:14px;line-height:20px;opacity:0.5;">
																		
																		@if(!empty($AppointmentServices))
																			@php 
																				$AppointmentTime = $AppointmentServices->duration + $AppointmentServices->extra_time_duration;
																				if($AppointmentTime<=0 )
																				{echo '00h 00min' ;} else {
																				if(sprintf( "%02d"
																				,floor($AppointmentTime / 60))
																				> 0){echo
																				sprintf("%02d",floor($AppointmentTime / 60)).'h ';}
																				if(sprintf("%02d",str_pad(($AppointmentTime
																				% 60), 2, "0", STR_PAD_LEFT)) > 0){echo
																				sprintf("%02d",str_pad(($AppointmentTime
																				% 60), 2, "0", STR_PAD_LEFT)). "min";}}
																			@endphp
																		@endif
																	</td>
																	<td style="font-size:14px;line-height:20px;opacity:0.5;text-align:right;text-decoration:line-through;">
																	</td>
																</tr>
																<tr style="border-bottom:1px solid #dfdfdf;"></tr>
															</tbody>
														</table>
													</td>
												</tr>
												<tr>
													<td style="padding-bottom:24px;padding-left:0;padding-right:0;padding-top:24px;">
														<table width="100%" cellpadding="0" cellspacing="0"
															style="border-spacing:0;color:inherit;width:100%;">
															<tbody>
																<tr>
																	@if(!empty($Invoice))
																		<td style="font-size:16px;line-height:22px;">
																			Subtotal
																		</td>
																		<td style="font-size:16px;line-height:22px;text-align:right;">
																			Ca ${{ $Invoice['invoice_sub_total'] }}
																		</td>
																	@endif
																</tr>
																<tr style="border-bottom:1px solid #dfdfdf;"></tr>
															</tbody>
														</table>
													</td>
												</tr>
												<tr>
													<td style="padding-bottom:24px;padding-left:0;padding-right:0;padding-top:24px;">
														<table width="100%" cellpadding="0" cellspacing="0"
															style="border-spacing:0;color:inherit;width:100%;">
															<tbody>
																@if(!empty($InvoiceTaxes))
																	@foreach($InvoiceTaxes as $InvoiceTaxData)
																		<tr>
																			<td style="font-size:16px;line-height:22px;">
																				{{ $InvoiceTaxData['tax_name'] }} {{ $InvoiceTaxData['tax_rate'] }}%
																			</td>
																			<td style="font-size:16px;line-height:22px;text-align:right;">
																				Ca ${{ $InvoiceTaxData['tax_amount'] }}
																			</td>
																		</tr>
																	@endforeach
																@endif
																<tr style="border-bottom:1px solid #dfdfdf;"></tr>
															</tbody>
														</table>
													</td>
												</tr>
												<tr>
													<td style="padding-bottom:24px;padding-left:0;padding-right:0;padding-top:24px;">
														<table width="100%" cellpadding="0" cellspacing="0"
															style="border-spacing:0;color:inherit;width:100%;">
															<tbody>
																<tr>
																	@if($TotalStaffTip[0]['total_tip'] > 0)
																		<td style="font-size:16px;line-height:22px;">
																			Tips
																		</td>
																		<td style="font-size:16px;line-height:22px;text-align:right;">
																			Ca ${{ $TotalStaffTip[0]['total_tip'] }}
																		</td>
																	@endif
																</tr>
																<tr style="border-bottom:1px solid #dfdfdf;"></tr>
															</tbody>
														</table>
													</td>
												</tr>
												<tr>
													<td style="border-bottom:1px solid #dfdfdf;"></td>
												</tr>
												@if($is_price_view == 1)
												<tr>
													<td style="padding:22px 0;">
														<table width="100%" cellpadding="0" cellspacing="0"
															style="border-spacing:0;color:inherit;line-height:28px;width:100%;">
															<tbody>
																<tr>
																	<td style="font-size:16px;font-weight:bold;">
																		Total
																	</td>
																	<td style="font-size:16px;font-weight:bold;text-align:right;">
																		Ca ${{ $Invoice->inovice_final_total }}
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
												@endif
												<tr>
													@if($note != '')
													<div class=" d-flex justify-content-between ">
														<div>
															<h3 class="font-weight-bolder shownote">Important info</h3>
															<p class="m-0 shownote" id="desshow">{{ $note }}</p>
														</div>
													</div>
													@endif
												</tr>
												<tr>
													@if($Invoice['payment_id'] > 0)
														<div class="total d-flex justify-content-between">
															<span><p class="" style="font-size:16px;font-weight:bold;text-align:left;">Paid by {{ $Invoice['payment_type'] }}</p></span>
															<span>
																<p class="">
																	<img alt="card-img" src="{{ asset('assets/images/mastercard.png') }}">
																</p>
															</span>
														</div>
													@endif
												</tr>
											</tbody>
										</table>
									</td>
									<td cellpadding="0" cellspacing="0" style="background-color:inherit;padding:0 8px;width:16px;"></td>
								</tr>
								<tr>
									<td colspan="3" cellpadding="0" cellspacing="0" style="padding-bottom:33px;padding-top:33px;text-align:center;color:blue"> <a href="{{route('viewInvoice',$Invoice['id'])}}"> View Invoice </a></td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</body>

</html>