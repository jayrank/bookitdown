<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	</head>
	<body>
		@if($type == 1)
			<div style="background-color: rgb(255, 255, 255); border: 1px solid rgb(223, 223, 223); border-spacing: 0px; color: rgb(16, 25, 40); font-family: Arial, sans-serif; height: auto; margin: 0px auto; max-width: 576px; width: 100%;">
			
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
																Hi {{ $first_name }} your
																appointment
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
															<td colspan="2" style="font-size:20px;line-height:29px;padding-top:24px;">
																 Saturday, 25 Sep at 11:00am 
															</td>
														</tr>
														<tr>
															<td
																style="font-size:15px;line-height:22px;padding:0;padding-bottom:24px;padding-top:28px;vertical-align:center;">
																<a style="color:#ffffff;text-decoration:none !important;">
																	<div style="font-size: 18px;">
																	{{ $locationData->location_name }}
																	</div>
																	<div style="font-size: 14px;">
																	{{ $locationData->location_address }}
																	</div>
																	
																</a>
																<a style="color:#ffffff;text-decoration:none !important;">
																</a>
															</td>
															<td style="padding:0;text-align:right;vertical-align:center;">
																@if($locationData->location_image != '')
																	<a style="text-decoration:none !important;">
																		<img width="64" height="64" src="{{ url($locationData->location_image) }}" style="border:1px solid #404753;border-radius:12px;height:64px;width:64px;">
																	</a>
																@else
																	<a style="text-decoration:none !important;"><img width="64" height="64" src="{{ asset('assets/images/thumb.jpg') }}" style="border:1px solid #404753;border-radius:12px;height:64px;width:64px;"></a>
																@endif
																	
															</td>
														</tr>
														<tr>
															<td colspan="2" style="background-color:#037aff;border-radius:4px;color:#ffffff;font-size:15px;line-height:24px;text-align:center;">
																<a style="color:#ffffff;display:block;padding-bottom:12px;padding-top:12px;text-decoration:none !important;"><img
																		width="24" height="24"
																		src="{{ asset('assets/images/icon_form.png') }}"
																		style="height:24px;margin-right:14px;vertical-align:bottom;width:24px;"><span
																		style="vertical-align:bottom;">Complete form</span></a>
															</td>
														</tr>
														<tr>
															<td colspan="2"
																style="font-size:14px;line-height:19px;padding-bottom:40px;padding-top:16px;text-align:center;">
																{{ $locationData->location_name }} would like you to complete a consultation form before
																your
																appointment. <a style="color:#037aff;text-decoration:none !important;"> Manage your appointment </a>
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
															<td style="padding-bottom:24px;padding-left:0;padding-right:0;padding-top:24px;">
																<table width="100%" cellpadding="0" cellspacing="0"
																	style="border-spacing:0;color:inherit;">
																	<tbody>
																		<tr>
																			<td style="font-size:16px;line-height:22px;">
																				Hair Cut
																			</td>
																			<td style="font-size:16px;line-height:22px;text-align:right;">
																				@if($is_price_view == 1)
																					CA$100.00
																				@endif
																			</td>
																		</tr>
																		<tr>
																			<td
																				style="font-size:14px;line-height:20px;opacity:0.5;">
																				1h 15min
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
														@if($is_price_view == 1)
														<tr>
															<td style="padding:22px 0;">
																<table width="100%" cellpadding="0" cellspacing="0"
																	style="border-spacing:0;color:inherit;line-height:28px;">
																	<tbody>
																		<tr>
																			<td style="font-size:16px;font-weight:bold;">
																				Total
																			</td>
																			<td style="font-size:16px;font-weight:bold;text-align:right;">
																				CA $100.00
																			</td>
																		</tr>
																	</tbody>
																</table>
															</td>
														</tr>
														@endif
														<tr>
															@if($note != "")
															<div class=" d-flex justify-content-between ">
																<div>
																	<h3 class="font-weight-bolder shownote">Important info</h3>
																	<p class="m-0 shownote" id="desshow">{{ $note }}</p>
																</div>
															</div>
															@endif
														</tr>
													</tbody>
												</table>
											</td>
											<td cellpadding="0" cellspacing="0" style="background-color:inherit;padding:0 8px;"></td>
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
			
		@elseif($type == 2)
			
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
															Hi {{ $first_name }} See You Soon
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
														<td style="font-size:15px;font-weight:bold;height:28px;line-height:28px;padding:0;">
															Confirmed</td>
													</tr>
												</tbody>
											</table>
										</td>
									</tr>
									<tr>
										<td colspan="2" style="font-size:20px;line-height:29px;padding-top:24px;">
											Saturday, 25 Sep at 11:00am 
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
													Booking ref: 8D556245
												</div>
											</a>
										</td>
										<td style="padding:0;text-align:right;vertical-align:center;">
											<a style="text-decoration:none !important;"><img width="64" height="64"
													src="{{ asset('assets/images/thumb.jpg') }}"
													style="border:1px solid #404753;border-radius:12px;height:64px;width:64px;"></a>
										</td>
									</tr>
								</tbody>
							</table>
						</td>
						<td cellpadding="0" cellspacing="0" style="background-color:inherit;padding:0 8px;width:16px;"></td>
					</tr>
					<tr style="background-color:#f2f2f7;">
						<td cellpadding="0" cellspacing="0" style="background-color:inherit;padding:0 8px;width:16px;"></td>
							<td style="background-color:inherit;max-width:100%;width:100%;">
								<table width="100%" cellpadding="0" cellspacing="0"
									style="border-spacing:0;color:inherit;width:100%;">
									<tbody>
										<tr>
											<td style="padding-bottom:24px;padding-left:0;padding-right:0;padding-top:24px;">
												<table width="100%" cellpadding="0" cellspacing="0"
													style="border-spacing:0;color:inherit;width:100%;">
													<tbody>
														<tr>
															<td style="font-size:16px;line-height:22px;">
																Hair Cut
															</td>
															<td style="font-size:16px;line-height:22px;text-align:right;">
																@if($is_price_view == 1)
																	CA$100.00
																@endif
															</td>
														</tr>
														<tr>
															<td
																style="font-size:14px;line-height:20px;opacity:0.5;">
																1h 15min
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
																CA$100.00
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
									</tbody>
								</table>
							</td>
							<td cellpadding="0" cellspacing="0" style="background-color:inherit;padding:0 8px;width:16px;"></td>
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
		
		@elseif($type == 3)
			<table cellpadding="0" cellspacing="0" style="background-color: rgb(255, 255, 255); border: 1px solid rgb(223, 223, 223); border-spacing: 0px; color: rgb(16, 25, 40); font-family: Arial, sans-serif; height: auto; margin: 0px auto; max-width: 576px; width: 100%;">
				<tbody>
					<tr>
						<td>
							<table cellpadding="0" cellspacing="0" style="border-spacing:0;color:inherit;width:100%;">
								<tbody>
									<tr style="background-color:#101928;">
										<td cellpadding="0" cellspacing="0" style="background-color:inherit;padding:0 8px;width:16px;"></td>
										<td style="background-color:inherit;max-width:100%;width:100%;">
											<table width="100%" cellpadding="0" cellspacing="0"
												style="border-spacing:0;color:#ffffff;width:100%;">
												<tbody>
													<tr>
														<td colspan="2" style="font-size:28px;padding:24px 0 8px 0;">
															Hi {{ $first_name }} your appointment is rescheduled!
														</td>
													</tr>
													<tr>
														<td colspan="2">
															<table height="28" cellpadding="0" cellspacing="0"
																style="background:#037aff;border-radius:14px;border-spacing:0;color:inherit;font-size:14px;height:28px;line-height:14px;padding:0px 10px 0px 3px;width:auto;">
																<tbody>
																	<tr>
																		<td><img width="24" height="24" src="{{ asset('assets/images/confirmed.png') }}" style="height:24px;margin-right:3px;width:24px;">
														</td>
														<td style="font-size:15px;font-weight:bold;height:28px;line-height:28px;padding:0;"> Confirmed</td>
													</tr>
												</tbody>
											</table>
										</td>
									</tr>
									<tr>
										<td colspan="2" style="font-size:20px;line-height:29px;padding-top:24px;">
											Tuesday, 5 Jan at 10:00am
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
													Booking ref: 8D556245
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
						<td cellpadding="0" cellspacing="0" style="background-color:inherit;padding:0 8px;width:16px;"></td>
						<td style="background-color:inherit;max-width:100%;width:100%;">
							<table width="100%" cellpadding="0" cellspacing="0"
								style="border-spacing:0;color:inherit;width:100%;">
								<tbody>
									<tr>
										<td style="padding-bottom:24px;padding-left:0;padding-right:0;padding-top:24px;">
											<table width="100%" cellpadding="0" cellspacing="0"
												style="border-spacing:0;color:inherit;width:100%;">
												<tbody>
													<tr>
														<td style="font-size:16px;line-height:22px;">
															Hair Cut
														</td>
														<td style="font-size:16px;line-height:22px;text-align:right;">
															@if($is_price_view == 1)
																CA$100.00
															@endif
														</td>
													</tr>
													<tr>
														<td
															style="font-size:14px;line-height:20px;opacity:0.5;">
															1h 15min
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
																CA$100.00
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
								</tbody>
							</table>
						</td>
						<td cellpadding="0" cellspacing="0" style="background-color:inherit;padding:0 8px;width:16px;"></td>
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
		@elseif($type == 4)
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
															Thank you for visiting {{ $first_name }}
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
																	Booking ref: 8D556245
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
														<td style="padding-bottom:24px;padding-left:0;padding-right:0;padding-top:24px;">
															<table width="100%" cellpadding="0" cellspacing="0"
																style="border-spacing:0;color:inherit;width:100%;">
																<tbody>
																	<tr>
																		<td style="font-size:16px;line-height:22px;">
																			Hair Cut
																		</td>
																		<td style="font-size:16px;line-height:22px;text-align:right;">
																			@if($is_price_view == 1)
																				CA$100.00
																			@endif
																		</td>
																	</tr>
																	<tr>
																		<td
																			style="font-size:14px;line-height:20px;opacity:0.5;">
																			1h 15min
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
																		<td
																			style="font-size:16px;font-weight:bold;text-align:right;">
																			CA$100.00
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
														<div class="total d-flex justify-content-between">
															<h3 class="">Paid by Card</h3>
															<h5 class="">
																<img alt="card-img"
																	src="https://schedulethat.tjcg.in/public/assets/images/mastercard.png">
																**** 1234
															</h5>
														</div>
													</tr>
												</tbody>
											</table>
										</td>
										<td cellpadding="0" cellspacing="0" style="background-color:inherit;padding:0 8px;width:16px;"></td>
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
		@elseif($type == 5)
			
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
															Hi {{ $first_name }} your appointment is cancelled!
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
																		<td style="font-size:15px;font-weight:bold;height:28px;line-height:28px;padding:0;"> Cancelled</td>
																	</tr>
																</tbody>
															</table>
														</td>
													</tr>
													<tr>
														<td colspan="2" style="font-size:20px;line-height:29px;padding-top:24px;">
															Tuesday, 5 Jan at 10:00am
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
																	Booking ref: 8D556245
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
																	<tr>
																		<td style="font-size:16px;line-height:22px;">
																			Hair Cut
																		</td>
																		<td style="font-size:16px;line-height:22px;text-align:right;">
																			@if($is_price_view == 1)
																				CA$100.00
																			@endif
																		</td>
																	</tr>
																	<tr>
																		<td
																			style="font-size:14px;line-height:20px;opacity:0.5;">
																			1h 15min
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
																			CA$100.00
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
										<td cellpadding="0" cellspacing="0" style="background-color:inherit;padding:0 8px;width:16px;"></td>
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
		
		@elseif($type == 6)
		
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
															Hi {{ $first_name }}, you did not show up
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
																		<td style="font-size:15px;font-weight:bold;height:28px;line-height:28px;padding:0;"> Cancelled</td>
																	</tr>
																</tbody>
															</table>
														</td>
														
													</tr>
													<tr>
														<td colspan="2" style="font-size:20px;line-height:29px;padding-top:24px;">
															 Thursday, 23 Sep at 11:00am 
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
																	Booking ref: 8D556245
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
																	<tr>
																		<td style="font-size:16px;line-height:22px;">
																			Hair Cut
																		</td>
																		<td style="font-size:16px;line-height:22px;text-align:right;">
																			@if($is_price_view == 1)
																				CA$100.00
																			@endif
																		</td>
																	</tr>
																	<tr>
																		<td
																			style="font-size:14px;line-height:20px;opacity:0.5;">
																			1h 15min
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
																			CA$100.00
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
														<div class="total d-flex justify-content-between">
															<h6 class="">Paid by Card</h6>
															<h6 class="">
																<img alt="card-img" src="https://schedulethat.tjcg.in/public/assets/images/mastercard.png">
																**** 1234
															</h6>
														</div>
													</tr>
												</tbody>
											</table>
										</td>
										<td cellpadding="0" cellspacing="0" style="background-color:inherit;padding:0 8px;width:16px;"></td>
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
		
		@elseif($type == 7)
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
															 Thursday, 23 Sep at 11:00am 
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
																	Booking ref: 8D556245
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
																	<tr>
																		<td style="font-size:16px;line-height:22px;">
																			Hair Cut
																		</td>
																		<td style="font-size:16px;line-height:22px;text-align:right;">
																			@if($is_price_view == 1)
																				CA$100.00
																			@endif
																		</td>
																	</tr>
																	<tr>
																		<td
																			style="font-size:14px;line-height:20px;opacity:0.5;">
																			1h 15min
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
																			CA$100.00
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
														<div class="total d-flex justify-content-between">
															<h6 class="">Paid by Card</h6>
															<h6 class="">
																<img alt="card-img" src="https://schedulethat.tjcg.in/public/assets/images/mastercard.png">
																**** 1234
															</h6>
														</div>
													</tr>
												</tbody>
											</table>
										</td>
										<td cellpadding="0" cellspacing="0" style="background-color:inherit;padding:0 8px;width:16px;"></td>
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
		@endif		
	</body>

</html>