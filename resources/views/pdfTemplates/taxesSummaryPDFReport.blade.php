<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Taxes Summary Report</title>

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
				<h1 style="font-weight: normal; color: #000">Taxes Summary Report: {{ date("l, d M Y") }}</h1>
				<p style="color: #000">{{ $start_date }} to {{ $end_date }}, {{ $location_name }} , generated {{ date("l, d M Y") }} at {{ date("h:i A") }}</p>
			</td>
			<td></td>
		</tr>
	</table>
    <!-- Payments -->
    <table width="100%">
        <thead>
            <tr>
                <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 20%;">Tax </th>
                <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 20%;">Location</th>
                <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 20%;">Item Sale</th>
                <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 20%">Rate</th>
                <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 20%">Amount</th>
            </tr>
        </thead>
        <tbody>
            @if(!empty($getTaxesSummary['data']))
                @foreach($getTaxesSummary['data'] as $dataKey => $dataValue)
                    <tr> 
                        <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['tax'] }}</td>
                        <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['location'] }}</td>
                        <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['item_sales'] }}</td>
                        <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['tax_rates'] }}</td>
                        <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['amount'] }}</td>
                    </tr>
                @endforeach
            @endif	
        </tbody>
        <tfoot>
            <tr> 
                <td style="padding: 6px 0;font-size: 10px;font-weight: bold;border-top: 1px solid gray;">Total</td>
                <td style="padding: 6px 0;font-size: 10px;font-weight: bold;border-top: 1px solid gray;" colspan="3"></td>
                <td style="padding: 6px 0;font-size: 10px;font-weight: bold;border-top: 1px solid gray;">{{ !empty($getTaxesSummary['total']) ? $getTaxesSummary['total'] : "CA $0.00" }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>