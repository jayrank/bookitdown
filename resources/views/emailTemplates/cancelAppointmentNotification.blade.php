<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
	<div style="background-color: rgb(255, 255, 255); border: 1px solid rgb(223, 223, 223); border-spacing: 0px; color: rgb(16, 25, 40); font-family: Arial, sans-serif; height: auto; margin: 0px auto; max-width: 576px; width: 100%;">
		<table cellpadding="0" cellspacing="0">
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
														Hi {{ $client->firstname }} {{ $client->lastname }} your appointment
														is cancelled!
													</td>
												</tr>
												<tr>
													<td colspan="2">
														<table height="28" cellpadding="0" cellspacing="0"
															style="background:#da2346;border-radius:14px;border-spacing:0;color:inherit;font-size:14px;height:28px;line-height:14px;padding:0px 10px 0px 3px;width:auto;">
															<tbody>
																<tr>
																	<td><img width="24" height="24"
																			src="{{ asset('assets/images/pill_cancelled.png') }}"
																			style="height:24px;margin-right:3px;width:24px;">
																	</td>
																	<td
																		style="font-size:15px;font-weight:bold;height:28px;line-height:28px;padding:0;">
																		Cancelled</td>
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
														<?php echo date("H:i", strtotime($AppointmentServices->start_time)); ?>
													</td>
												</tr>
												<tr>
													<td
														style="font-size:15px;line-height:22px;padding:0;padding-bottom:24px;padding-top:28px;vertical-align:center;">
														<a style="color:#ffffff;text-decoration:none !important;">
															<div style="font-size:17px;font-weight:bold;">
	
															</div>
															<div style="color:#878c93;">
																{{ $client->address }}
															</div>
														</a>
														<a style="color:#ffffff;text-decoration:none !important;">
															<div style="color:#878c93;">
																Booking ref: 8D556245
															</div>
														</a>
													</td>
													<td style="padding:0;text-align:right;vertical-align:center;">
														<a style="text-decoration:none !important;"><img width="64"
																height="64" src="{{ asset('assets/images/thumb.jpg') }}"
																style="border:1px solid #404753;border-radius:12px;height:64px;width:64px;"></a>
													</td>
												</tr>
												<tr>
													<td colspan="2"
														style="font-size:15px;line-height:22px;padding:0;padding-bottom:24px;padding-top:0;vertical-align:center;">
														<table cellpadding="0" cellspacing="0"
															style="border-spacing:0;color:inherit;width:100%;">
															<tbody>
																<tr>
																	<td>
																		<a style="text-decoration:none !important;">
																			<table cellpadding="0" cellspacing="0"
																				style="background:#3f4753;border-radius:4px;border-spacing:0;color:white;font-size:14px;height:45px;padding:10px 32px;vertical-align:middle;width:auto;">
																				<tbody>
																					<tr>
																						<td
																							style="height:25px;vertical-align:middle;width:25px;">
																						</td>
																					</tr>
																				</tbody>
																			</table>
																		</a>
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr> -->
											</tbody>
										</table>
									</td>
									<td cellpadding="0" cellspacing="0"
										style="background-color:inherit;padding:0 8px;width:16px;"></td>
								</tr>
								<tr style="background-color:#f2f2f7;">
									<td cellpadding="0" cellspacing="0"
										style="background-color:inherit;padding:0 8px;width:16px;"></td>
									<td style="background-color:inherit;max-width:100%;width:100%;">
										<table width="100%" cellpadding="0" cellspacing="0"
											style="border-spacing:0;color:inherit;width:100%;">
											<tbody>
												<tr>
													<td
														style="padding-bottom:24px;padding-left:0;padding-right:0;padding-top:24px;">
														<table width="100%" cellpadding="0" cellspacing="0"
															style="border-spacing:0;color:inherit;width:100%;">
															<tbody>
																<tr>
																	<td style="font-size:16px;line-height:22px;">
																		{{ $service->service_name }}
																	</td>
																	<td
																		style="font-size:16px;line-height:22px;text-align:right;">
																		@if($remider->is_displayServicePrice==1)CA${{
																		$appointment->total_amount }}@endif
																	</td>
																</tr>
																<tr>
																	<td
																		style="font-size:14px;line-height:20px;opacity:0.5;">
																		@php if($AppointmentServices->duration<=0 )
																			{echo '00h 00min' ;} else { if(sprintf( "%02d"
																			,floor($AppointmentServices->duration / 60)) >
																			0){echo
																			sprintf("%02d",floor($AppointmentServices->duration
																			/ 60)).'h ';}
																			if(sprintf("%02d",str_pad(($AppointmentServices->duration
																			% 60), 2, "0", STR_PAD_LEFT)) > 0){echo
																			sprintf("%02d",str_pad(($AppointmentServices->duration
																			% 60), 2, "0", STR_PAD_LEFT)). "min";}} @endphp
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
															style="border-spacing:0;color:inherit;line-height:28px;width:100%;">
															<tbody>
																<tr>
																	<td style="font-size:16px;font-weight:bold;">
																		Total
																	</td>
																	<td
																		style="font-size:16px;font-weight:bold;text-align:right;">
																		CA${{ $appointment->final_total }}
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
												@endif
											</tbody>
										</table>
									</td>
									<td cellpadding="0" cellspacing="0"
										style="background-color:inherit;padding:0 8px;width:16px;"></td>
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
		<div>
</body>
</html>