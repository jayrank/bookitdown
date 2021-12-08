<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Services Report</title>

<style type="text/css">
    * {
        font-family: Verdana, Arial, sans-serif;
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
				<h2>Invoice List Report</h2>
				<p>all staff, all locations,  generated, {{ date("l, d M Y") }} as {{ date("h:i A") }}</p>
			</td>
			<td></td>
		</tr>
	</table>
	
	<table width="100%">
		<thead>
			<tr>
				<th style="text-align:left;">Service Name</th>
				<th style="text-align:left;">Retail Price</th>
				<th style="text-align:left;">Special Price</th>
				<th style="text-align:left;">Duration</th>
				<th style="text-align:left;">Extra Time</th>
				<th style="text-align:left;">Tax</th>
				<th style="text-align:left;">Description</th>
				<th style="text-align:left;">Category Name</th>
				<th style="text-align:left;">Treatment Type</th>
				<th style="text-align:left;">Online Booking</th>
				<th style="text-align:left;">Available For</th>
				<th style="text-align:left;">Voucher Sales</th>
				<th style="text-align:left;">Commissions</th>
				<th style="text-align:left;">Serivce ID</th>
			</tr>
		</thead>
		<tbody>
			@if(!empty($ServicesPrice))
				@foreach($ServicesPrice as $key => $value)
					@php
        				if($value->available_for==0){ 
        					$ava = 'Everyone'; 
        				}elseif ($value->available_for==1) {
	        				$ava = 'Females';
	        			}else {
		        			$ava='Males';
		        		}
					@endphp
					<tr>
						<td style="text-align:left;">{{ $value->service_name }}</td>
						<td style="text-align:left;">{{ ($value->price != "") ? $value->price : '' }}</td>
						<td style="text-align:left;">{{ ($value->special_price != "") ? $value->special_price : '' }}</td>
						<td style="text-align:left;">{{ $value->duration }}</td>
						<td style="text-align:left;">{{ !empty($value->is_extra_time) ? $value->extra_time_duration : ''}}</td>
						<td style="text-align:left;">{{ ($value->tax_name != "") ? $value->tax_name : '' }}</td>
						<td style="text-align:left;">{{ ($value->service_description != "") ? $value->service_description : '' }}</td>
						<td style="text-align:left;">{{ $value->category_title }}</td>
						<td style="text-align:left;">{{ $value->treatment_type }}</td>
						<td style="text-align:left;">{{ $value->is_online_booking==0 ? 'Disabled' : 'Enabled' }}</td>
						<td style="text-align:left;">{{ $ava }}</td>
						<td style="text-align:left;">{{ $value->is_voucher_enable==0 ? 'Disabled' : 'Enabled' }}</td>
						<td style="text-align:left;">{{ $value->is_staff_commision_enable==0 ? '' : 'Enabled' }}</td>
						<td style="text-align:left;">{{ $value->id }}</td>
					</tr>
				@endforeach
			@endif
		</tbody>
	</table>
	
</body>
</html>