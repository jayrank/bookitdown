<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Tips Collected Report</title>

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
				<h1 style="font-weight: normal; color: #000">Tips Collected Report: {{ date("l, d M Y") }}</h1>
				<p style="color: #000">{{ $start_date }} To {{ $end_date }}, {{ $staff_name }}, {{ $location_name }}, generated {{ date("l, d M Y") }} at {{ date("h:i A") }}</p>
			</td>
			<td></td>
		</tr>
	</table>
        <table width="100%">
            <thead>
                <tr>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 20%;">Date</th>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 20%;">Invoice No.</th>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 20%;">Location</th>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 20%;">Staff Name</th>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 20%;">Tips Colected</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($getTipsCollected['data']))
                    @foreach($getTipsCollected['data'] as $dataKey => $dataValue)
                        <tr> 
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['date'] }}</td>
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['invoice_id'] }}</td>
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['location_name'] }}</td>
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['staff'] }}</td>
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['tips_collected'] }}</td>
                        </tr>
                    @endforeach
                @endif	
            </tbody>
            <tfoot>
                <tr>
                    <td style="padding: 6px 0;font-size: 10px;font-weight: bold;border-top: 1px solid gray;">Total</td>
                    <td style="padding: 6px 0;font-size: 10px;font-weight: bold;border-top: 1px solid gray;" colspan="3"></td>
                    <td style="padding: 6px 0;font-size: 10px;font-weight: bold;border-top: 1px solid gray;">{{ $getTipsCollected['total'] }}</td>
                </tr>
            </tfoot>
        </table>
</body>
</html>