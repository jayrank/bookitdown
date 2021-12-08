<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Appointment Cancellation Report</title>

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
				<h1 style="font-weight: normal; color: #000">Appointment Cancellations: {{ date("l, d M Y") }}</h1>
				<p style="color: #000">{{ $location_name }}, {{ $staff_name }}, generated {{ date("l, d M Y") }} at {{ date("h:i A") }}</p>
			</td>
			<td></td>
		</tr>
	</table>
    <div class="card w-100">
        <div class="card-body">
            <h3 class="font-weight-bolder">Summary</h3>
            <ul class="list-group summary" style="list-style-type: none;font-size: 12px;">
                @if(!empty($getAppointmentCancellationsSummary))
                    @php
                        $nullCount = 0;
                        $flag = 0;
                        foreach($getAppointmentCancellationsSummary as $getAppointmentCancellationsSummaryKey => $getAppointmentCancellationsSummaryValue){
                            if(empty($getAppointmentCancellationsSummaryValue['reason'])){
                                $nullCount = $getAppointmentCancellationsSummaryValue['reason_count'];
                            }
                        }

                    @endphp

                    @foreach($getAppointmentCancellationsSummary as $getAppointmentCancellationsSummaryKey => $getAppointmentCancellationsSummaryValue)

                        @if($getAppointmentCancellationsSummaryValue['reason'] === "No reason")
                            @php
                                $flag = 1;
                            @endphp
                            <li class="border-0 list-group-item d-flex justify-content-between align-items-center" style="font-weight: bold;">{{ $getAppointmentCancellationsSummaryValue['reason'] }}<span class="" style="margin-left: 30%;">{{ ($getAppointmentCancellationsSummaryValue['reason_count'] + $nullCount) }}</span></li>
                        @elseif(!empty($getAppointmentCancellationsSummaryValue['reason']))
                        <li class="border-0 list-group-item d-flex justify-content-between align-items-center" style="font-weight: bold;">{{ $getAppointmentCancellationsSummaryValue['reason'] }}<span class="" style="margin-left: 30%;">{{ $getAppointmentCancellationsSummaryValue['reason_count'] }}</span></li>
                        @else

                        @endif

                    @endforeach

                    @if($flag == 0)
                        <li class="border-0 list-group-item d-flex justify-content-between align-items-center" style="font-weight: bold;">No reason<span class="" style="margin-left: 30%;">{{ $nullCount }}</span></li>
                    @endif
                @endif
            </ul>
        </div>
    </div>
    <table width="100%">
		<thead>
            <tr>
				<th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 9%;">Ref#</th>
				<th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 13%;">Client</th>
				<th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 13%;">Service</th>
				<th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 13%">Scheduled Date</th>
                <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 13%">Cancelled Date</th>
                <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 13%">Cancelled By</th>
                <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 13%">Reason</th>
                <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 13%">Price</th>
			</tr>
		</thead>
		<tbody>
			@if(!empty($getAppointmentCancellations))
				@foreach($getAppointmentCancellations as $getAppointmentCancellationsKey => $getAppointmentCancellationsValue)
                    <tr> 
                        <td style="padding: 6px 0;font-size: 12px;">{{ $getAppointmentCancellationsValue['ref'] }}</td>
                        <td style="padding: 6px 0;font-size: 12px;">{{ $getAppointmentCancellationsValue['client'] }}</td>
                        <td style="padding: 6px 0;font-size: 12px;">{{ $getAppointmentCancellationsValue['service'] }}</td>
                        <td style="padding: 6px 0;font-size: 12px;">{{ $getAppointmentCancellationsValue['scheduled_date'] }}</td>
                        <td style="padding: 6px 0;font-size: 12px;">{{ $getAppointmentCancellationsValue['cancelled_date'] }}</td>
                        <td style="padding: 6px 0;font-size: 12px;">{{ $getAppointmentCancellationsValue['cancelled_by'] }}</td>
                        <td style="padding: 6px 0;font-size: 12px;">{{ $getAppointmentCancellationsValue['reason'] }}</td>
                        <td style="padding: 6px 0;font-size: 12px;">{{ $getAppointmentCancellationsValue['price'] }}</td>                        
                    </tr>
				@endforeach
			@endif	
		</tbody>
	</table>
</body>
</html>