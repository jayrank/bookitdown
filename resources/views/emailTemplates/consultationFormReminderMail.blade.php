{{-- Extends layout --}}
@extends('layouts.email')

@section('content')
<table cellpadding="0" cellspacing="0" style="background-color: rgb(255, 255, 255); border: 1px solid rgb(223, 223, 223); border-spacing: 0px; color: rgb(16, 25, 40); font-family: Arial, sans-serif; height: auto; margin: 0px auto; max-width: 576px; width: 100%;">
    <tbody>
        <tr>
            <td>
                <table cellpadding="0" cellspacing="0" style="border-spacing:0;color:inherit;width:100%;">
                    <tbody>
                        <tr style="background-color:#101928;">
                            <td cellpadding="0" cellspacing="0" style="background-color:inherit;padding:0 8px;width:16px;"></td>
                            <td style="background-color:inherit;max-width:100%;width:100%;">
                                <table width="100%" cellpadding="0" cellspacing="0" style="border-spacing:0;color:#ffffff;width:100%;">
                                    <tbody>
                                        <tr>
                                            <td colspan="2" style="font-size:28px;padding:24px 0 8px 0;">
                                                Hi {{ ($client_consultation_form['firstname']) ? $client_consultation_form['firstname'] : '' }} {{ ($client_consultation_form['lastname']) ? $client_consultation_form['lastname'] : '' }}, we need some details before your appointment
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <table height="28" cellpadding="0" cellspacing="0" style="background:#037aff;border-radius:14px;border-spacing:0;color:inherit;font-size:14px;height:28px;line-height:14px;padding:0px 10px 0px 3px;width:auto;">
                                                    <tbody>
                                                        <tr>
                                                            <td>
																<img width="24" height="24" src="{{ asset('assets/images/confirmed.png') }}" style="height:24px;margin-right:3px;width:24px;">
                                                            </td>
                                                            <td style="font-size:15px;font-weight:bold;height:28px;line-height:28px;padding:0;">Confirmed</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="font-size:20px;line-height:29px;padding-top:24px;">
                                                {{ ($client_consultation_form['complete_before']) ? date("l, M d",strtotime($client_consultation_form['complete_before'])) : '' }} at {{ ($client_consultation_form['complete_before']) ? date("h:i a",strtotime($client_consultation_form['complete_before'])) : '' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:15px;line-height:22px;padding:0;padding-bottom:24px;padding-top:28px;vertical-align:center;">
                                                <a style="color:#ffffff;text-decoration:none !important;">
                                                    <div style="font-size:17px;font-weight:bold;">
                                                        {{ ($client_consultation_form['location_name']) ? $client_consultation_form['location_name'] : '' }}
                                                    </div>
                                                    <div style="color:#878c93;">
                                                        {{ ($client_consultation_form['location_address']) ? $client_consultation_form['location_address'] : '' }}
                                                    </div>
                                                </a>
												<a style="color:#ffffff;text-decoration:none !important;">
                                                    <div style="color:#878c93;">
                                                        Booking ref: {{ ($appointment['id']) ? $appointment['id'] : '' }}
                                                    </div>
                                                </a>
											</td>
                                            <td style="padding:0;text-align:right;vertical-align:center;">
                                                <a style="text-decoration:none !important;">
												<img width="64" height="64" src="{{ ($client_consultation_form['location_image'] != ''	) ? url($client_consultation_form['location_image']) : asset('frontend/img/featured1.jpg') }}" style="border:1px solid #404753;border-radius:12px;height:64px;width:64px;"></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="font-size:15px;line-height:22px;padding:0;padding-bottom:24px;padding-top:0;vertical-align:center;">
                                                <table cellpadding="0" cellspacing="0" style="border-spacing:0;color:inherit;width:100%;">
                                                    <tbody>
                                                        <tr>
                                                            <td style="vertical-align:top;width:25%;">
                                                                <a style="color:#ffffff;text-decoration:none !important;">
                                                                    <table style="border-spacing:0;color:inherit;text-align:center;width:100%;">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>
																					<img width="56" height="56" src="{{ asset('assets/images/icon_directions.png') }}" style="border-radius:10px;height:56px;margin-bottom:13px;width:56px;">
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="color:#ffffff;font-size:14px;">
                                                                                    <a href="http://maps.google.co.uk/maps?q={{ (isset($client_consultation_form['location_latitude'])) ? $client_consultation_form['location_latitude'] : '' }},{{ (isset($client_consultation_form['location_longitude'])) ? $client_consultation_form['location_longitude'] : '' }}">Directions</a>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </a>
															</td>
                                                            <td style="vertical-align:top;width:25%;">
                                                                <a style="color:#ffffff;text-decoration:none !important;">
                                                                    <table style="border-spacing:0;color:inherit;text-align:center;width:100%;">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>
																					<img width="56" height="56" src="{{ asset('assets/images/icon_reschedule.png') }}" style="border-radius:10px;height:56px;margin-bottom:13px;width:56px;">
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="color:#ffffff;font-size:14px;">
                                                                                    <a href="{{ route('frontReschedule',['lid' => ($client_consultation_form['location_id']) ? Crypt::encryptString($client_consultation_form['location_id']) : '','aid' => ($client_consultation_form['appointment_id']) ? $client_consultation_form['appointment_id'] : '']) }}">Reschedule</a>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </a>
															</td>
                                                            <td style="vertical-align:top;width:25%;">
                                                                <a style="color:#ffffff;text-decoration:none !important;">
                                                                    <table style="border-spacing:0;color:inherit;text-align:center;width:100%;">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td>
																					<img width="56" height="56" src="{{ asset('assets/images/icon_cancel.png') }}" style="border-radius:10px;height:56px;margin-bottom:13px;width:56px;">
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="color:#ffffff;font-size:14px;">
                                                                                    <a href="{{ route('myAppointments',['appointmentId' => base64_encode(($appointment['id']) ? $appointment['id'] : '')]) }}">Cancel</a>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </a>
															</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
											<td colspan="2" style="background-color:#037aff;border-radius:4px;color:#ffffff;font-size:15px;line-height:24px;text-align:center;">
												<a href="{{ route('submitConsultationForm',['consultationId' => ($client_consultation_form['id']) ? Crypt::encryptString($client_consultation_form['id']) : '']) }}" style="color:#ffffff;display:block;padding-bottom:12px;padding-top:12px;text-decoration:none !important;"><img width="24" height="24" src="{{ asset('assets/images/icon_form.png') }}" style="height:24px;margin-right:14px;vertical-align:bottom;width:24px;">
													<span style="vertical-align:bottom;">Complete form</span>
												</a>
											</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="font-size:14px;line-height:19px;padding-bottom:40px;padding-top:16px;text-align:center;">
                                                 {{ ($client_consultation_form['location_name']) ? $client_consultation_form['location_name'] : '' }} would like you to complete a consultation form before your appointment. 
												<a href="{{ route('myAppointments',['appointmentId' => base64_encode(($appointment['id']) ? $appointment['id'] : '')]) }}" style="color:#037aff;text-decoration:none !important;"> Manage your appointment </a>
											</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td cellpadding="0" cellspacing="0" style="background-color:inherit;padding:0 8px;width:16px;"></td>
                        </tr>
                        <tr style="background-color:#f2f2f7;">
                            <td cellpadding="0" cellspacing="0" style="background-color:inherit;padding:0 8px;width:16px;"></td>
                            <td style="background-color:inherit;max-width:100%;width:100%;">
                                <table width="100%" cellpadding="0" cellspacing="0"
                                    style="border-spacing:0;color:inherit;width:100%;">
                                    <tbody>
										@if(!empty($client_services))
											@foreach($client_services as $AppointmentServicesData)
                                        <tr>
                                            <td style="padding-bottom:24px;padding-left:0;padding-right:0;padding-top:24px;">
                                                <table width="100%" cellpadding="0"
                                                    cellspacing="0" style="border-spacing:0;color:inherit;width:100%;">
                                                    <tbody>
													
                                                        <tr>
                                                            <td style="font-size:16px;line-height:22px;">
																{{ $AppointmentServicesData['service_name']  }}
                                                            </td>
                                                            <td style="font-size:16px;line-height:22px;text-align:right;">
                                                                CA ${{ ($AppointmentServicesData['special_price'] > 0) ? $AppointmentServicesData['special_price'] : $AppointmentServicesData['price'] }}
                                                            </td>
                                                        </tr>
														
                                                        <tr>
                                                            <td style="font-size:14px;line-height:20px;opacity:0.5;">
                                                                {{ $AppointmentServicesData['duration']  }}
                                                            </td>
                                                            <td style="font-size:14px;line-height:20px;opacity:0.5;text-align:right;text-decoration:line-through;">
																@if($AppointmentServicesData['special_price'] > 0)
																	 CA ${{ $AppointmentServicesData['price'] }}	
																@endif
                                                            </td>
                                                        </tr>
                                                        <tr style="border-bottom:1px solid #dfdfdf;"></tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
										<tr>
                                            <td style="border-bottom:1px solid #dfdfdf;"></td>
                                        </tr>
											@endforeach
										@endif
                                        
                                        <tr>
                                            <td style="padding:22px 0;">
                                                <table width="100%" cellpadding="0" cellspacing="0" style="border-spacing:0;color:inherit;line-height:28px;width:100%;">
                                                    <tbody>
                                                        <!--tr>
                                                            <td style="font-size:14px;opacity:0.5;">
                                                                Taxes
                                                            </td>
                                                            <td style="font-size:14px;opacity:0.5;text-align:right;">
                                                                CA$5.36
                                                            </td>
                                                        </tr-->
                                                        <tr>
                                                            <td style="font-size:16px;font-weight:bold;">
                                                                Total
                                                            </td>
                                                            <td style="font-size:16px;font-weight:bold;text-align:right;">
                                                                CA ${{ $appointment['total_amount'] }}
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td cellpadding="0" cellspacing="0"
                                style="background-color:inherit;padding:0 8px;width:16px;"></td>
                        </tr>
                        <tr>
                            <td colspan="3" style="background-image:url('{{ asset('assets/images/invoice_border_small.png') }}');background-repeat:repeat-x;height:10px;width:100%;">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" cellpadding="0" cellspacing="0"
                                style="padding-bottom:33px;"></td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
@endsection