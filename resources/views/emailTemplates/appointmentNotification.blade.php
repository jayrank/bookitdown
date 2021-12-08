<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Skill Triangle</title>
	<style type="text/css">
		#contentpdf {
			width: 70%;
			color: #000000;
		} 
		@media only screen and (max-width: 991px) {
			#contentpdf {
				width: 75%
			}
		}
		@media only screen and (max-width: 767px) {
			a.logo img {
				width: 115px;
			}

			span.spanwidth {
				min-width: 80px !important
			}

			#contentpdf {
				padding: 15px !important;
			} 
		} 
	</style>
</head>
	<body style="font-size: 14px; font-family: Product Sans Regular, sans-serif; padding: 10px; color: #000000; background: #f7f9fb;"> 
		<div id="contentpdf" style="margin: 0 auto; padding: 40px; border-radius: 8px; background: #fff;">
			<div class="wrapper" style="border-radius: 8px; color: #000000">
				<div id="content" style="margin-top: 0; background: none; color: #000000">
					<div class="panel panel-default">
						<p style="text-align:left; font-size: 23px;">Appointment confirmed</p>
						<p style="text-align:left; margin-bottom: 20px;">Hi,</p>
						<p style="text-align:left; margin-bottom: 10px;">The following appointment has been booked online:</p>
						
						@foreach($content as $data)	
							<p style="text-align:left; margin-bottom: 1px;">{{ $data['name'] }}</p>
							<p style="text-align:left; margin-bottom: 5px; margin-top: 0px;">{{ $data['time'] }}</p>
						@endforeach
						
						<p style="text-align:left; margin-bottom: 20px;margin-top: 20px;">At this location:</p>
						
						<!--p style="text-align:left; margin-bottom: 1px;"> Test with Jay Rock </p-->
						<p style="text-align:left; margin-bottom: 5px; margin-top: 0px;">{{ $location }}</p>
						
						<p style="text-align:left; margin-bottom: 5px; margin-top: 20px;">Customer details:</p>
						<p style="text-align:left; margin-bottom: 5px; margin-top: 0px;">{{ $customer }}</p>
						
						<p style="text-align:left; margin-bottom: 1px; margin-top: 20px;">Powered by</p>
						<p style="text-align:left; margin-bottom: 5px; margin-top: 0px;">Schedulethat</p>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>