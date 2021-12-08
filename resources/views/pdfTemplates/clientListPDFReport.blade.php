<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Client List Report</title>

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
				<h1 style="font-weight: normal; color: #000">Client List Report: {{ date("l, d M Y") }}</h1>
				<p style="color: #000">{{ $start_date }} to {{ $end_date }}, generated {{ date("l, d M Y") }} at {{ date("h:i A") }}</p>
			</td>
			<td></td>
		</tr>
	</table>
    <table width="100%">
		<thead>
			<tr>
				<th colspan="4" style="text-align:left; padding-top: 16px;">
					<h2 style="font-weight: normal; color: #000">Client List</h2>
				</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 14%;">Name</th>
				<th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 6%;">Blocked</th>
				<th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 5%;">Appointments</th>
				<th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 6%">No Show</th>
                <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 14%">Total Sales</th>
                <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 14%">Outstanding</th>
                <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 6%">Gender</th>
                <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 10%">Added</th>
                <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 10%">Last Appointment</th>
                <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 15%">Last Location</th>
			</tr>
			@if(!empty($data))
				@foreach($data as $dataKey => $dataValue)
                    <tr> 
                        <td style="padding: 6px 0;font-size: 12px;">{{ $dataValue['name'] }}</td>
                        <td style="padding: 6px 0;font-size: 12px;">{{ $dataValue['blocked'] }}</td>
                        <td style="padding: 6px 0;font-size: 12px;">{{ $dataValue['appointments'] }}</td>
                        <td style="padding: 6px 0;font-size: 12px;">{{ $dataValue['no_show'] }}</td>
                        <td style="padding: 6px 0;font-size: 12px;">CA ${{ $dataValue['total_sales'] }}</td>
                        <td style="padding: 6px 0;font-size: 12px;">CA ${{ $dataValue['outstanding'] }}</td>
                        <td style="padding: 6px 0;font-size: 12px;">{{ $dataValue['gender'] }}</td>
                        <td style="padding: 6px 0;font-size: 12px;">{{ $dataValue['added'] }}</td>
                        <td style="padding: 6px 0;font-size: 12px;">{{ $dataValue['last_appointment'] }}</td>
                        <td style="padding: 6px 0;font-size: 12px;">{{ $dataValue['last_location'] }}</td>
                    </tr>
				@endforeach
			@endif	
		</tbody>
	</table>
</body>
</html>