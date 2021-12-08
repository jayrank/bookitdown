<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Client Retention Report</title>

<style type="text/css">
    * {
        font-family: Verdana, Arial, sans-serif;
        color: gray;
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
				<h1 style="font-weight: normal; color: #000">Client Retention Report: {{ date("l, d M Y") }}</h1>
				<p style="color: #000">{{ $start_date }} to {{ $end_date }}, generated {{ date("l, d M Y") }} at {{ date("h:i A") }}</p>
			</td>
			<td></td>
		</tr>
	</table>
    <table width="100%">
		<thead>
			<tr>
				<th colspan="4" style="text-align:left; padding-top: 16px;">
					<h2 style="font-weight: normal; color: #000">Client Retention</h2>
				</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 13%;">Name</th>
				<th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 12%;">Mobile Number</th>
				<th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 15%;">Email</th>
				<th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 12%">Last Appointment</th>
                <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 9%">Days Absent</th>
                <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 13%">Staff</th>
                <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 13%">Last Visit Sales</th>
                <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 13%">Total Sales</th>
			</tr>
			@if(!empty($data))
				@foreach($data as $dataKey => $dataValue)
                    <tr> 
                        <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['name'] }}</td>
                        <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['mobile_no'] }}</td>
                        <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['email'] }}</td>
                        <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['last_appointment'] }}</td>
                        <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['days_absent'] }}</td>
                        <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['staff'] }}</td>
                        <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['last_visit_sales'] }}</td>
                        <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['total_sales'] }}</td>
                    </tr>
				@endforeach
			@endif	
		</tbody>
	</table>
</body>
</html>