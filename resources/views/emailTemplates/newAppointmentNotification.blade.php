<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>

<body>
	<div
		style="background-color: rgb(255, 255, 255); border: 1px solid rgb(223, 223, 223); border-spacing: 0px; color: rgb(16, 25, 40); font-family: Arial, sans-serif; height: auto; margin: 0px auto; max-width: 576px; width: 100%;">
		<table cellpadding="0" cellspacing="0">
			<tbody>
				<tr>
					<td>
						<table cellpadding="0" cellspacing="0" style="border-spacing:0;color:inherit;">
							<tbody>
								<tr style="background-color:#101928;">
									<td cellpadding="0" cellspacing="0" style="background-color:inherit;padding:0 8px;">
									</td>
									<td style="background-color:inherit;">
										<table cellpadding="0" cellspacing="0" style="border-spacing:0;color:#ffffff;">
											<tbody>
												<tr>
													<td colspan="2" style="font-size:28px;padding:24px 0 8px 0;">
														Hi {{ $client->firstname }} {{ $client->lastname }} your
														appoinment
														is confirmed
													</td>
												</tr>
												<tr>
													<td colspan="2">
														<table height="28" cellpadding="0" cellspacing="0"
															style="background:#037aff;border-radius:14px;border-spacing:0;color:inherit;font-size:14px;height:28px;line-height:14px;padding:0px 10px 0px 3px;width:auto;">
															<tbody>
																<tr>
																	<td><img width="24" height="24"
																			src="{{ asset('assets/images/confirmed.png') }}"
																			style="height:24px;margin-right:3px;width:24px;">
																	</td>
																	<td
																		style="font-size:15px;font-weight:bold;height:28px;line-height:28px;padding:0;">
																		Confirmed</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
												<tr>
													<td colspan="2"
														style="font-size:20px;line-height:29px;padding-top:24px;">
														<?php echo date("l, d F Y",strtotime($AppointmentServices->appointment_date)) ?>
														at
														<?php echo date("H:i A", strtotime($AppointmentServices->start_time)); ?>
													</td>
												</tr>
												<tr>
													<td
														style="font-size:15px;line-height:22px;padding:0;padding-bottom:24px;padding-top:28px;vertical-align:center;">
														<a style="color:#ffffff;text-decoration:none !important;">
															<div style="color:#878c93;">
																{{ $client->address }}
															</div>
														</a>
														<a style="color:#ffffff;text-decoration:none !important;">
															<!-- <div style="color:#878c93;">
																Booking ref: 3C29F5F9
															</div> -->
														</a>
													</td>
													<td style="padding:0;text-align:right;vertical-align:center;">
														<a style="text-decoration:none !important;"><img width="64"
																height="64" border:1px solid
																#404753;border-radius:12px;height:64px;width:64px;
																src="{{ asset('assets/images/thumb.jpg') }}"
																style="border:1px solid #404753;border-radius:12px;height:64px;width:64px;"></a>
													</td>
												</tr>
												<!-- <tr>
													<td colspan="2"
														style="background-color:#037aff;border-radius:4px;color:#ffffff;font-size:15px;line-height:24px;text-align:center;">
														<a
															style="color:#ffffff;display:block;padding-bottom:12px;padding-top:12px;text-decoration:none !important;"><img
																width="24" height="24"
																src="{{ asset('assets/images/icon_form.png') }}"
																style="height:24px;margin-right:14px;vertical-align:bottom;width:24px;"><span
																style="vertical-align:bottom;">Complete form</span></a>
													</td>
												</tr> -->
												<tr>
													<td colspan="2"
														style="font-size:14px;line-height:19px;padding-bottom:40px;padding-top:16px;text-align:center;">
														<?php $locid = Crypt::encryptString($getappo->location_id); $url = url('reschedule/'.$locid.'/'.$getappo->id); ?>
														Gym Zone would like you to complete a consultation form before
														your
														appointment. <a href="{{ route('myAppointments',['appointmentId' => base64_encode(($getappo->id) ? $getappo->id : '')]) }}"
															style="color:#037aff;text-decoration:none !important;"
															> Manage your
															appointment </a>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
									<td cellpadding="0" cellspacing="0" style="background-color:inherit;padding:0 8px;">
									</td>
								</tr>
								<tr style="background-color:#f2f2f7;">
									<td cellpadding="0" cellspacing="0" style="background-color:inherit;padding:0 8px;">
									</td>
									<td style="background-color:inherit;">
										<table width="100%" cellpadding="0" cellspacing="0"
											style="border-spacing:0;color:inherit;">
											<tbody>
												<tr>
													<td
														style="padding-bottom:24px;padding-left:0;padding-right:0;padding-top:24px;">
														<table width="100%" cellpadding="0" cellspacing="0"
															style="border-spacing:0;color:inherit;">
															<tbody>
																<tr>
																	<td style="font-size:16px;line-height:22px;">
																		{{ $service->service_name }}
																	</td>
																	<td
																		style="font-size:16px;line-height:22px;text-align:right;">
																		@if($remider->is_displayServicePrice==1)CA${{
																		$getappo->total_amount }}@endif
																	</td>
																</tr>
																<tr>
																	<td
																		style="font-size:14px;line-height:20px;opacity:0.5;">
																		<?php 
																			if($AppointmentServices->duration<=0 ) {  
																				"00h 00min";
																			} else { 
																				if(sprintf( "%02d",floor($AppointmentServices->duration / 60)) > 0){ 
																					echo sprintf("%02d",floor($AppointmentServices->duration / 60)). "h";
																				} 
																				if(sprintf("%02d",str_pad(($AppointmentServices->duration % 60), 2, "0", STR_PAD_LEFT)) > 0){ 
																					echo sprintf("%02d",str_pad(($AppointmentServices->duration % 60), 2, "0", STR_PAD_LEFT)). "min";
																				} 
																			} 
																		?>
																	</td>
																	<td
																		style="font-size:14px;line-height:20px;opacity:0.5;text-align:right;text-decoration:line-through;">
																	</td>
																</tr>
																<tr style="border-bottom:1px solid #dfdfdf;"></tr>
															</tbody>
														</table>
													</td>
												</tr>
												<tr>
													<td style="border-bottom:1px solid #dfdfdf;"></td>
												</tr>
												@if($remider->is_displayServicePrice==1)
												<tr>
													<td style="padding:22px 0;">
														<table width="100%" cellpadding="0" cellspacing="0"
															style="border-spacing:0;color:inherit;line-height:28px;">
															<tbody>
																<tr>
																	<td style="font-size:16px;font-weight:bold;">
																		Total
																	</td>
																	<td
																		style="font-size:16px;font-weight:bold;text-align:right;">
																		CA${{ $getappo->final_total }}
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
												@endif
												<tr>
													@if($remider->important_info != null)
													<div class=" d-flex justify-content-between ">
														<div>
															<h3 class="font-weight-bolder shownote">Important info</h3>
															<p class="m-0 shownote" id="desshow">{{
																$remider->important_info
																}}</p>
														</div>
													</div>
													@endif
												</tr>
											</tbody>
										</table>
									</td>
									<td cellpadding="0" cellspacing="0" style="background-color:inherit;padding:0 8px;">
									</td>
								</tr>

								<tr>
									<td colspan="3" cellpadding="0" cellspacing="0" style="padding-bottom:33px;"></td>
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