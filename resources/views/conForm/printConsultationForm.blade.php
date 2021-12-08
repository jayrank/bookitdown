<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="ScheduleDown">
    <meta name="author" content="ScheduleDown">
    <link rel="icon" type="image/png" href="{{ asset('frontend/img/fav.png') }}">
    <title>ScheduleDown</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
</head> 
<body style="margin: 0; padding: 0; font-family: 'Roboto', sans-serif; background-color: #fff;">
    <div style="width: 70%; margin: 0 auto;">
        <div style="padding: 32px 0px 16px;">
            <h1 style="font-size: 40px;">{{ ($LocationInfo) ? $LocationInfo['location_name'] : '' }}</h1>
            <h3 style="text-align:left;font-weight:bold;font-size:20px;margin:22px 0 2px;line-height:24px">Last edited on {{ ($ClientConsultationFormGet) ? date("d M Y",strtotime($ClientConsultationFormGet['updated_at'])) : '' }}</h3>
            <h5 style="font-size:17px;margin: 34px 0 0px;line-height:24px;">Personal Information</h5>
        </div>
        <table cellpadding="0" cellspacing="0" style="width:100%;font-family: 'Roboto', sans-serif;color:#2c2c2c;background:#fff;margin:0 auto"> 
			@if(!empty($ClientConsultationFormGet) && $ClientConsultationFormGet['is_first_name'] == 1)
            <tr>
                <td style="background:#ffffff;padding:16px 0;">
                    <h5 style="font-size:15px;line-height:21px;font-weight: bold;margin: 0">First name</h5>
                    <p style="font-size:15px;line-height:21px;margin: 0">{{ ($ClientConsultationFormGet['client_first_name']) ? $ClientConsultationFormGet['client_first_name'] : '' }}</p> 
                </td>
            </tr>
			@endif
			
            @if(!empty($ClientConsultationFormGet) && $ClientConsultationFormGet['is_last_name'] == 1)
			<tr>
                <td style="background:#ffffff;padding:16px 0;">
                    <h5 style="font-size:15px;line-height:21px;font-weight: bold;margin: 0">Last name</h5>
                    <p style="font-size:15px;line-height:21px;margin: 0">{{ ($ClientConsultationFormGet['client_last_name']) ? $ClientConsultationFormGet['client_last_name'] : '' }}</p> 
                </td>
            </tr>	
			@endif
			
			@if(!empty($ClientConsultationFormGet) && $ClientConsultationFormGet['is_email'] == 1)
			<tr>
                <td style="background:#ffffff;padding:16px 0;">
                    <h5 style="font-size:15px;line-height:21px;font-weight: bold;margin: 0">Email</h5>
                    <p style="font-size:15px;line-height:21px;margin: 0">{{ ($ClientConsultationFormGet['client_email']) ? $ClientConsultationFormGet['client_email'] : '' }}</p> 
                </td>
            </tr>
			@endif	
			
			@if(!empty($ClientConsultationFormGet) && $ClientConsultationFormGet['is_birthday'] == 1)		
			<tr>
                <td style="background:#ffffff;padding:16px 0;">
                    <h5 style="font-size:15px;line-height:21px;font-weight: bold;margin: 0">Birthday</h5>
                    <p style="font-size:15px;line-height:21px;margin: 0">{{ ($ClientConsultationFormGet['client_birthday']) ? date("Y-m-d",strtotime($ClientConsultationFormGet['client_birthday'])) : '' }}</p> 
                </td>
            </tr>
			@endif	
			
			@if(!empty($ClientConsultationFormGet) && $ClientConsultationFormGet['is_mobile'] == 1)
			<tr>
                <td style="background:#ffffff;padding:16px 0;">
                    <h5 style="font-size:15px;line-height:21px;font-weight: bold;margin: 0">Mobile number</h5>
                    <p style="font-size:15px;line-height:21px;margin: 0">+{{ ($ClientConsultationFormGet['country_code']) ? $ClientConsultationFormGet['country_code'] : '' }} {{ ($ClientConsultationFormGet['client_mobile']) ? $ClientConsultationFormGet['client_mobile'] : '' }}</p> 
                </td>
            </tr>
			@endif
		
			@if(!empty($ClientConsultationFormGet) && $ClientConsultationFormGet['is_gender'] == 1)		
			<tr>
                <td style="background:#ffffff;padding:16px 0;">
                    <h5 style="font-size:15px;line-height:21px;font-weight: bold;margin: 0">Gender</h5>
                    <p style="font-size:15px;line-height:21px;margin: 0">{{ ($ClientConsultationFormGet['client_gender']) ? $ClientConsultationFormGet['client_gender'] : '' }}</p> 
                </td>
            </tr>
			@endif	
		
			@if(!empty($ClientConsultationFormGet) && $ClientConsultationFormGet['is_address'] == 1)		
			<tr>
                <td style="background:#ffffff;padding:16px 0;">
                    <h5 style="font-size:15px;line-height:21px;font-weight: bold;margin: 0">Address</h5>
                    <p style="font-size:15px;line-height:21px;margin: 0">{{ ($ClientConsultationFormGet['client_address']) ? $ClientConsultationFormGet['client_address'] : '' }}</p> 
                </td>
            </tr>	
			@endif
			
			@if(!empty($ClientConsultationFormField))
				@php 
					$i = 1; 
				@endphp
				@foreach($ClientConsultationFormField as $ClientConsultationFormFieldData)	
					@php 
						$i++; 
					@endphp
					
					<tr>
						<td style="background:#ffffff;padding:16px 0;border-top:1px solid #e7e8e9;">
							<h5 style="font-size:17px;line-height:21px;font-weight: bold;margin: 0 0 16px">{{ ($ClientConsultationFormFieldData) ? $ClientConsultationFormFieldData['section_title'] : '' }}</h5>
							<p style="font-size:15px;line-height:21px;margin: 0">{{ ($ClientConsultationFormFieldData) ? $ClientConsultationFormFieldData['section_description'] : '' }}</p> 
						</td>
					</tr>
					
					@if(!empty($ClientConsultationFormGet['client_consultation_fields']))
						@foreach($ClientConsultationFormGet['client_consultation_fields'] as $clientConsultationFieldsData)	
							@if($clientConsultationFieldsData['section_id'] == $ClientConsultationFormFieldData['section_id'])
								<tr>
									<td style="background:#ffffff;padding:16px 0;border-top:1px solid #e7e8e9;">
										<h5 style="font-size:15px;line-height:21px;font-weight: bold;margin: 0">{{ $clientConsultationFieldsData['question'] }}</h5>
										<p style="font-size:15px;line-height:21px;margin: 0">{{ $clientConsultationFieldsData['client_answer'] }}</p> 
									</td>
								</tr>
							@endif
						@endforeach
					@endif		
				@endforeach
			@endif
			
			@if(!empty($ClientConsultationFormGet) && $ClientConsultationFormGet['is_signature'] == 1)
            <tr>
                <td style="background:#ffffff;padding:16px 0;border-top:1px solid #e7e8e9;">
                    <h5 style="font-size:17px;line-height:21px;font-weight: bold;margin: 0 0 16px">Signatures</h5>
                    <h5 style="font-size:15px;line-height:21px;font-weight: bold;margin: 0">Full name</h5>
                    <p style="font-size:15px;line-height:21px;margin: 0">{{ ($ClientConsultationFormGet['signature_name']) ? $ClientConsultationFormGet['signature_name'] : '' }}</p> 
                </td>
            </tr>
            <tr>
                <td style="background:#ffffff;padding:16px 0;border-top:1px solid #e7e8e9;">
                    <h5 style="font-size:15px;line-height:21px;font-weight: bold;margin: 0">I confirm the answers I've given are true and correct to the best of my knowledge.</h5>
                    <p style="font-size:15px;line-height:21px;margin: 0">Confirmed by {{ ($ClientConsultationFormGet['signature_name']) ? $ClientConsultationFormGet['signature_name'] : '' }}</p> 
                </td>
            </tr>
			@endif
			
        </table>
    </div>
</body>
</html>
