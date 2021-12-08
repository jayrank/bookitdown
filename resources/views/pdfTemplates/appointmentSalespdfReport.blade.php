<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Appointment Report</title>

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
				<h2>Appointments List Report</h2>
				<p>all staff, all locations,  generated, {{ date("l, d M Y") }} as {{ date("h:i A") }}</p>
			</td>
			<td></td>
		</tr>
	</table>
	
	<table width="100%">
		<thead>
			<tr>
				<th style="text-align:left;">Ref #</th>
				<th style="text-align:left;">Channel</th>
				<th style="text-align:left;">Created Date</th>
				<th style="text-align:left;">Created By</th>
				<th style="text-align:left;">Client</th>
				<th style="text-align:left;">Service</th>
				<th style="text-align:left;">Scheduled Date</th>
				<th style="text-align:left;">Time</th>
				<th style="text-align:left;">Location</th>
				<th style="text-align:left;">Duration</th>
				<th style="text-align:left;">Staff</th>
				<th style="text-align:left;">Price</th>
				<th style="text-align:left;">Status</th>
			</tr>
		</thead>
		<tbody>
			@if(!empty($appointmentEvents))
				@foreach($appointmentEvents as $key => $value)
					<tr>
						<td style="text-align:left;">{{ $value['ref_no'] }}</td>
						<td style="text-align:left;">{{ $value['is_online_appointment'] }}</td>
						<td style="text-align:left;">{{ $value['created_at'] }}</td>
						<td style="text-align:left;">{{ $value['created_by'] }}</td>
						<td style="text-align:left;">{{ $value['client_name'] }}</td>
						<td style="text-align:left;">{{ $value['service_name'] }}</td>
						<td style="text-align:left;">{{ $value['appointment_date'] }}</td>
						<td style="text-align:left;">{{ $value['appointment_time'] }}</td>
						<td style="text-align:left;">{{ $value['location_name'] }}</td>
						<td style="text-align:left;">{{ $value['duration'] }}</td>
						<td style="text-align:left;">{{ $value['staff_name'] }}</td>
						<td style="text-align:left;">{{ $value['price'] }}</td>
						<td style="text-align:left;">{{ $value['status'] }}</td>
					</tr>
				@endforeach
			@endif
		</tbody>
	</table>
	
</body>
</html>