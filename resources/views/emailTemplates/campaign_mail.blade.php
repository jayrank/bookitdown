<!DOCTYPE html>
<html> 
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- CSRF Token -->
	<meta name="csrf-token" content="4P3pypntgERz1dE0Q9kCW5U3d9JlHK8YOJk05Tyh">
	<title>Campaign Email</title>
	
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700">
	<link href="https://schedulethat.tjcg.in/public/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css">
	<link href="https://schedulethat.tjcg.in/public/prismjs/prismjs.bundle.css" rel="stylesheet" type="text/css">
	<link href="https://schedulethat.tjcg.in/public/assets/css/style.bundle.css" rel="stylesheet" type="text/css">
	<link href="https://schedulethat.tjcg.in/public/assets/css/custom.css" rel="stylesheet" type="text/css">
	<link href="https://schedulethat.tjcg.in/public/assets/css/themes/layout/header/base/light.css" rel="stylesheet" type="text/css">
	<link href="https://schedulethat.tjcg.in/public/assets/css/themes/layout/header/menu/light.css" rel="stylesheet" type="text/css">
	<link href="https://schedulethat.tjcg.in/public/assets/css/themes/layout/brand/dark.css" rel="stylesheet" type="text/css">
	<link href="https://schedulethat.tjcg.in/public/assets/css/themes/layout/aside/dark.css" rel="stylesheet" type="text/css">
	<link href="https://schedulethat.tjcg.in/public/assets/css/image-picker.css" rel="stylesheet" type="text/css">
	<link href="https://schedulethat.tjcg.in/public/assets/css/intlTelInput.css" rel="stylesheet" type="text/css">
	<link rel="shortcut icon" href="https://schedulethat.tjcg.in/public/assets/media/logos/favicon.ico">	
	<style type="text/css">
		.table-padding {
		    width: 10px;
		}
		@media only screen and (min-device-width: 767px){
			.table-padding {
			    padding-left: 18px !important;
			    padding-right: 18px !important;
			}
		}
		.table-vertical-padding {
		    padding-bottom: 40px;
		}
	</style>
</head>
<!-- @php
	/*echo "<pre>";
	print_r($location);
	print_r($campaign_data);
	print_r(count($location));
	exit();*/
@endphp -->
<body>
	<div class="border-light-dark card m-auto my-4 w-60" style="width: 100%;max-width:550px;border: 1px solid #D1D3E0;border-radius: 0.42rem;margin: 0 auto;">
		<table class="main-table-wrapper" cellpadding="0" cellspacing="0">
			<tbody>
				<tr>
					<td>
						<table class="main-table" cellpadding="0" cellspacing="0">
							<tbody>
								<tr>
									<td class="table-vertical-padding" colspan="3" style="padding-bottom: 40px;"></td>
								</tr>
								<tr>
									<td class="table-padding"></td>
									<td>
										<table cellpadding="0" cellspacing="0" class="header" style="text-align:left;width:100%;">
											<tbody>
												<tr>
												@if(count($location) <= 1)
													<td class="header-text" style="vertical-align:center;">
														<div class="header-name" style="color:#000000;font-size:20px;">{{ $location[0]['location_name'] }}</div>
														<div class="header-address" style="color:#000000;font-size:14px;opacity:0.6;padding-top:4px;">{{ $location[0]['location_address'] }}</div>
													</td>
												@else
													<td class="header-text" style="vertical-align:center;">
														<div class="header-name" style="color:#000000;font-size:20px;">{{ $location[0]['location_name'] }}</div>
														<div class="header-address" style="color:#000000;font-size:14px;opacity:0.6;padding-top:4px;">{{ count($location) }} Locations</div>
													</td>
												@endif
												</tr>
											</tbody>
										</table>
									</td>
									<td class="table-padding"></td>
								</tr> 
								<tr>
									<td class="table-padding"></td>
									<td>
										<div style="height:100px;text-align:center;margin: 40px 0;">
											<img class="image-img" src="{{ url($message_data->image_path) }}" style="border-radius:8px;height:auto;max-height:100%;max-width:100%;">
										</div>
									</td>
									<td class="table-padding"></td>
								</tr>
								<tr>
									<td class="table-padding"></td>
									<td>
										<div style="color:#000000;font-size:36px;font-weight:normal;line-height:42px;padding-bottom:30px;white-space:normal;word-break:break-word;word-wrap:break-word;">{{ $message_data->headline_text }}</div>
										<div style="color:#000000;font-size:17px;line-height:27px;opacity:0.6;text-align:left;white-space:normal;word-break:break-word;word-wrap:break-word;margin-bottom: 40px;">
											@php echo $message_data->body_text @endphp
										</div>
									</td>
									<td class="table-padding"></td>
								</tr>
								<tr>
									<td class="table-padding"></td>
									<td>
										<div class="section-divider" style="border-bottom:1px solid #d8d8d8;width:100%;"></div>
									</td>
									<td class="table-padding"></td>
								</tr>
								<tr>
				                  	<td class="table-vertical-padding" colspan="3"></td>
				                </tr>
								<tr>
									<td class="table-padding"></td>
									<td>
										<div class="offer-box-container" style="background:no-repeat url(http://schedulethat.tjcg.in/public/uploads/schedule_library/offer-box-glow.png);background-color:#000000;background-size:100% auto;border-radius:8px;color:#FFFFFF;padding:10px;text-align:center;">
											<div class="offer-box" style="border:1px solid rgba(255, 255, 255, 0.4);border-radius:8px;padding:20px;">
												<div class="offer-box-headline" style="font-size:2rem;font-weight:bold;margin-bottom:10px;text-transform:uppercase;">
													{{ ($message_data->discount_type == 1) ? 'CA $': '' }}
													{{ $message_data->discount_value }}
													{{ ($message_data->discount_type == 2) ? '%': '' }} 
													off
												</div>
												<div class="offer-box-subheadline" style="font-size:15px;margin-bottom:25px;">your next {{ ($message_data->appoinment_limit != "unlimited") ? $message_data->appoinment_limit : '' }} appointment</div>
												<table cellpadding="0" cellspacing="0" class="cta-button-container-table" style="margin:0 auto;">
													<tbody>
														<tr>
															<td></td>
															<td class="cta-button-cell" style="-webkit-text-size-adjust:none;background:#FFFFFF;border-radius:4px;font-size:14px;font-weight:bold;height:48px;line-height:48px;min-width:230px;text-align:center;text-decoration:none;text-transform:uppercase;"><a class="cta-button-link" clicktracking="off" href="#" style="color:#000000 !important;display:inline-block;min-width:230px;padding:0 8px;text-decoration:none;">Book now</a></td>
															<td></td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
										<!-- @php 
										$services = json_decode($message_data->services);
										@endphp -->
										<div class="offer-box-footer" style="color:#000000;font-size:14px;padding-top:20px;text-align:center;">Applies to {{ count($services) }} service{{ (count($services) > 1) ? 's':'' }} , valid for {{ $message_data->valid_for }} days</div>
									</td>
									<td class="table-padding"></td>
								</tr>
								 
								<tr>
									<td class="table-vertical-padding" colspan="3"></td>
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