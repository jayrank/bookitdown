<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- CSRF Token -->
	<meta name="csrf-token" content="4P3pypntgERz1dE0Q9kCW5U3d9JlHK8YOJk05Tyh">
	<title>Quick Update Email</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700">
	<link href="https://schedulethat.tjcg.in/public/assets/plugins/global/plugins.bundle.css" rel="stylesheet"
		type="text/css">
	<link href="https://schedulethat.tjcg.in/public/prismjs/prismjs.bundle.css" rel="stylesheet" type="text/css">
	<link href="https://schedulethat.tjcg.in/public/assets/css/style.bundle.css" rel="stylesheet" type="text/css">
	<link href="https://schedulethat.tjcg.in/public/assets/css/custom.css" rel="stylesheet" type="text/css">
	<link href="https://schedulethat.tjcg.in/public/assets/css/themes/layout/header/base/light.css" rel="stylesheet"
		type="text/css">
	<link href="https://schedulethat.tjcg.in/public/assets/css/themes/layout/header/menu/light.css" rel="stylesheet"
		type="text/css">
	<link href="https://schedulethat.tjcg.in/public/assets/css/themes/layout/brand/dark.css" rel="stylesheet"
		type="text/css">
	<link href="https://schedulethat.tjcg.in/public/assets/css/themes/layout/aside/dark.css" rel="stylesheet"
		type="text/css">
	<link href="https://schedulethat.tjcg.in/public/assets/css/image-picker.css" rel="stylesheet" type="text/css">
	<link href="https://schedulethat.tjcg.in/public/assets/css/intlTelInput.css" rel="stylesheet" type="text/css">
	<link rel="shortcut icon" href="https://schedulethat.tjcg.in/public/assets/media/logos/favicon.ico">
	<style type="text/css">
		.table-padding {
			width: 10px;
		}

		@media only screen and (min-device-width: 767px) {
			.table-padding {
				padding-left: 18px !important;
				padding-right: 18px !important;
			}
		}
	</style>
</head>

<body>
	<div class="border-light-dark card m-auto my-4 w-60"
		style="width: 100%;border: 1px solid #D1D3E0;border-radius: 0.42rem;margin: 0 auto;max-width: 550px;">
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
										<table cellpadding="0" cellspacing="0" class="header"
											style="text-align:left;width:100%;">
											<tbody>
												<tr>
													@if(count($location) <= 1) <td class="header-text"
														style="vertical-align:center;">
														<div class="header-name" style="color:#000000;font-size:20px;">
															{{ $location[0]['location_name'] }}</div>
														<div class="header-address"
															style="color:#000000;font-size:14px;opacity:0.6;padding-top:4px;">
															{{ $location[0]['location_address'] }}</div>
									</td>
									@else
									<td class="header-text" style="vertical-align:center;">
										<div class="header-name" style="color:#000000;font-size:20px;">{{
											$location[0]['location_name'] }}</div>
										<div class="header-address"
											style="color:#000000;font-size:14px;opacity:0.6;padding-top:4px;">{{
											count($location) }} Locations</div>
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
							<img class="image-img" src="{{ url($message_data->image_path) }}"
								style="border-radius:8px;height:auto;max-height:100%;max-width:100%;">
						</div>
					</td>
					<td class="table-padding"></td>
				</tr>
				<tr>
					<td class="table-padding"></td>
					<td>
						<div
							style="color:#000000;font-size:36px;font-weight:normal;line-height:42px;padding-bottom:30px;white-space:normal;word-break:break-word;word-wrap:break-word;">
							{{ $message_data->title }}</div>
						<div
							style="color:#000000;font-size:17px;line-height:27px;opacity:0.6;text-align:left;white-space:normal;word-break:break-word;word-wrap:break-word;margin-bottom: 40px;">
							<p style="margin: 0">@php echo $message_data->message @endphp</p>
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
					<td class="table-padding"></td>
					<td>
						<div style="text-align:center; padding: 40px 0">
							<table cellpadding="0" cellspacing="0" class="cta-button-container-table"
								style="margin:0 auto;">
								<tbody>
									<tr>
										<td></td>
										<td class="cta-button-cell"
											style="-webkit-text-size-adjust:none;background:#000000;border-radius:4px;font-size:14px;font-weight:bold;height:48px;line-height:48px;min-width:230px;text-align:center;text-decoration:none;text-transform:uppercase;">
											<a class="cta-button-link" clicktracking="off"
												href="{{ ($message_data->is_button == 2) ? $message_data->button_link : route('/') }}"
												style="color:#FFFFFF !important;display:inline-block;min-width:230px;padding:0 8px;text-decoration:none;">
												@if($message_data->is_button == 1)
												Book now
												@elseif($message_data->is_button == 2)
												{{ $message_data->button_text }}
												@endif
											</a>
										</td>
										<td></td>
									</tr>
								</tbody>
							</table>
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
					<td class="table-padding"></td>
					<td>
						<table class="social-media-table" cellpadding="0" cellspacing="0"
							style="width:100%;margin:40px 0">
							<tbody>
								<tr>
									<td></td>
									@if($message_data->social_media_enable == 1)

									<td class="social-media-cell" style="height:44px;padding:0 10px;width:44px;">
										<a class="social-media-anchor" href="{{ $message_data->facebook_link }}"
											style="display:block;"><img class="social-media-img" alt="Facebook"
												src="{{ url('public/uploads/schedule_library/social-fb.png') }}"
												style="display:block;height:24px;margin:0 auto;width:24px;"></a>
									</td>
									<td class="social-media-cell" style="height:44px;padding:0 10px;width:44px;">
										<a class="social-media-anchor" href="{{ $message_data->instagram_link }}"
											style="display:block;"><img class="social-media-img" alt="Instagram"
												src="{{ url('public/uploads/schedule_library/social-insta.png') }}"
												style="display:block;height:24px;margin:0 auto;width:24px;"></a>
									</td>
									<td class="social-media-cell" style="height:44px;padding:0 10px;width:44px;">
										<a class="social-media-anchor" href="{{ $message_data->website }}"
											style="display:block;"><img class="social-media-img"
												alt="{{ $message_data->website }}"
												src="{{ url('public/uploads/schedule_library/social-web.png') }}"
												style="display:block;height:24px;margin:0 auto;width:24px;"></a>
									</td>
									@endif
									<td></td>
								</tr>
							</tbody>
						</table>
					</td>
					<td class="table-padding"></td>
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