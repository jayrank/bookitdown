<?php
namespace App\Http\Controllers\Front;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\JsonReturn;
use App\Models\Appointments;
use App\Models\AppointmentServices;
use App\Models\Clients;
use App\Models\frontUser;
use App\Models\Location;
use App\Models\FuserLocationReview;
use App\Models\ConsForm;
use App\Models\conFormClientDetails;
use App\Models\conFormCustomSection;
use App\Models\ClientConsultationForm;
use App\Models\ClientConsultationFormField;
use App\Models\Country;
use App\Models\Online_setting;
use DB;
use Crypt;
use Session;
use App\Repositories\NotificationRepositorie;

class AppointmentsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $notificationRepositorie;

    public function __construct(
    	NotificationRepositorie $notificationRepositorie
    )
    {
		$currentRoute = Route::currentRouteName();
		$this->middleware('FUser');

		$this->notificationRepositorie = $notificationRepositorie;
    }

    public function myAppointments($appointmentId = null)
    {
        $appointmentId = base64_decode($appointmentId);
        $fuserId = Auth::guard('fuser')->user()->id;

		$isConsultationForm = (Session::get('isConsultationForm')) ? Session::get('isConsultationForm') : '';
		Session::forget('isConsultationForm');
		
        $clientIds = Clients::where('fuser_id', $fuserId)->pluck('id');

        $pastAppointments = Appointments::with(['apservice', 'invoice'])
                            ->leftJoin('locations', 'locations.id', 'appointments.location_id')
                            ->whereIn('appointments.client_id', $clientIds)
                            ->where('appointments.appointment_date', '<', date('Y-m-d'))
                            ->select('appointments.*', 'locations.location_name', 'locations.location_image')
                            ->get()
                            ->sortBy(function($appointments) { 
                                if(!empty($appointments->apservice)) {
                                	if(isset($appointments->apservice[0])) {
                                    	return $appointments->appointment_date.' '.$appointments->apservice[0]->start_time;
                                    } else {
                                    	return  $appointments->appointment_date;
                                    }
                                }
                            });
        					
        $upcomingAppointments = Appointments::with(['apservice', 'invoice'])
                            ->leftJoin('locations', 'locations.id', 'appointments.location_id')
                            ->whereIn('appointments.client_id', $clientIds)
                            ->where('appointments.appointment_date', '>=', date('Y-m-d'))
                            ->select('appointments.*', 'locations.location_name', 'locations.location_image')
                            ->get()
                            ->sortBy(function($appointments) {
                                if(count($appointments->apservice) > 0 ) {
                                    if(isset($appointments->apservice[0])) {
                                    	return $appointments->appointment_date.' '.$appointments->apservice[0]->start_time;
                                    } else {
                                    	return  $appointments->appointment_date;
                                    }
                                }
                            });
		
        $selectedAppointment = NULL;

        if(!empty($appointmentId)) {
            $selectedAppointment = Appointments::with(['apservice', 'invoice'])
                            ->leftJoin('locations', 'locations.id', 'appointments.location_id')
                            ->whereIn('appointments.client_id', $clientIds)
                            ->where('appointments.id', $appointmentId)
                            ->select('appointments.*', 'locations.location_name', 'locations.location_image', 'locations.location_address')
                            ->first();
        } else {
            if(!$upcomingAppointments->isEmpty()) {
                $selectedAppointment = $upcomingAppointments[0];
            } elseif(!$pastAppointments->isEmpty()) {
                $selectedAppointment = $pastAppointments[0];
            }
        }
		
		$LocationInfo = array();
		if(isset($selectedAppointment->location_id) != 0){
			$LocationInfo = Location::select('*')->where(['id'=> $selectedAppointment->location_id])->get()->first()->toArray();
		}

    	return view('frontend.appointments.all_appointments', compact('pastAppointments', 'upcomingAppointments', 'selectedAppointment','appointmentId','LocationInfo','isConsultationForm'));
    }
	
	public function rescheduleError($appId = null) 
	{
		$appointment = Appointments::find($appId);
		$locationInfo = Location::select('*')->where(['id'=> $appointment->location_id])->first();
		$onlineSettingData = Online_setting::select('appointment_cancel_time')->where('online_setting.user_id',$appointment->user_id)->first();
		
		$hours = ($onlineSettingData->appointment_cancel_time / 60 );
		
		if($hours >= 1) {
			$beforeHours = $hours." hours";
		} else {
			$beforeHours = $onlineSettingData->appointment_cancel_time." minutes";
		}		
		
		$getAppintmentServices = AppointmentServices::select('appointment_services.id','appointment_services.appointment_date','appointment_services.start_time')->where('appointment_services.appointment_id',$appId)->orderBy('appointment_services.id','asc')->first();
		
		return view('frontend.appointments.reschedule-error', compact('appointment','locationInfo','getAppintmentServices','beforeHours'));
	}	

	public function cancelError($appId = null) 
	{
		$appointment = Appointments::find($appId);
		if(!empty($appointment))
		{
			$locationInfo = Location::select('*')->where(['id'=> $appointment->location_id])->first();
			$onlineSettingData = Online_setting::select('appointment_cancel_time')->where('online_setting.user_id',$appointment->user_id)->first();
			
			$hours = ($onlineSettingData->appointment_cancel_time / 60 );
			
			if($hours >= 1) {
				$beforeHours = $hours." hours";
			} else {
				$beforeHours = $onlineSettingData->appointment_cancel_time." minutes";
			}		
			
			$getAppintmentServices = AppointmentServices::select('appointment_services.id','appointment_services.appointment_date','appointment_services.start_time')->where('appointment_services.appointment_id',$appId)->orderBy('appointment_services.id','asc')->first();
			
			/* echo "<pre>";
			print_r(json_decode($locationInfo));
			die; */
			return view('frontend.appointments.cancel-error', compact('appointment','locationInfo','getAppintmentServices','beforeHours'));
		}
		else
		{
			return redirect(route('myAppointments'));
		}
	}	

	public function myConsultationForm() {
		$fuserId = Auth::guard('fuser')->user()->id;
		
		$ClientConsultationFormGet = ClientConsultationForm::select('client_consultation_form.id','client_consultation_form.complete_before','consultation_form.name','locations.location_name','locations.location_image')->leftJoin('consultation_form','consultation_form.id','client_consultation_form.consultation_from_id')->leftJoin('locations','locations.id','client_consultation_form.location_id')->where('client_consultation_form.fuser_id',$fuserId)->where('client_consultation_form.status',0)->get()->toArray();
		
		$CompletedClientConsultationFormGet = ClientConsultationForm::select('client_consultation_form.id','client_consultation_form.complete_before','consultation_form.name','locations.location_name','locations.location_image')->leftJoin('consultation_form','consultation_form.id','client_consultation_form.consultation_from_id')->leftJoin('locations','locations.id','client_consultation_form.location_id')->where('client_consultation_form.fuser_id',$fuserId)->where('client_consultation_form.status',1)->get()->toArray();
		
		return view('frontend.appointments.myConsultationForm',compact('ClientConsultationFormGet','CompletedClientConsultationFormGet'));
	}
	
	public function submitConsultationForm($consultationId = null) {
		if($consultationId != '')
		{
			$consultationId = Crypt::decryptString($consultationId);
			// dd($consultationId);
			$ClientConsultationFormGet = ClientConsultationForm::select('*')->with('clientConsultationFields')->where('id',$consultationId)->get()->first()->toArray();
			
			$ClientConsultationFormField = ClientConsultationFormField::select('section_id','section_title','section_description')->where('client_consultation_form_id',$consultationId)->distinct()->get('section_id')->toArray();
			
			$TotalSteps = count($ClientConsultationFormField);
			
			//main step counter plus
			$TotalSteps = $TotalSteps + 1;
			
			if($ClientConsultationFormGet['is_signature']){
				$TotalSteps = $TotalSteps + 1;
			}
			
			$Country = Country::get()->toArray();
			
			return view('frontend.appointments.submitConsultationForm',compact('ClientConsultationFormGet','ClientConsultationFormField','TotalSteps','Country'));
		}	
	}
	
	public function viewConsultationForm($consultationId = null){
		if($consultationId != '')
		{
			$consultationId = Crypt::decryptString($consultationId);
			
			$ClientConsultationFormGet = ClientConsultationForm::select('*')->with('clientConsultationFields')->where('id',$consultationId)->get()->first()->toArray();
			
			$LocationInfo = array();
			if(!empty($ClientConsultationFormGet)) {
				$LocationInfo = Location::select('location_name')->where(['id'=> $ClientConsultationFormGet['location_id']])->get()->first()->toArray();
			}
			
			$ClientConsultationFormField = ClientConsultationFormField::select('section_id','section_title','section_description')->where('client_consultation_form_id',$consultationId)->distinct()->get('section_id')->toArray();
			
			return view('frontend.appointments.viewConsultationForm',compact('ClientConsultationFormGet','ClientConsultationFormField','LocationInfo'));
		}	
	}
	
	public function printConsultationForm(Request $request){
		if ($request->ajax()){
			$consultationId = Crypt::decryptString($request->consultationFormId);
			
			$ClientConsultationFormGet = ClientConsultationForm::select('*')->with('clientConsultationFields')->where('id',$consultationId)->get()->first()->toArray();
			
			$LocationInfo = array();
			if(!empty($ClientConsultationFormGet)) {
				$LocationInfo = Location::select('location_name')->where(['id'=> $ClientConsultationFormGet['location_id']])->get()->first()->toArray();
			}
			
			$ClientConsultationFormField = ClientConsultationFormField::select('section_id','section_title','section_description')->where('client_consultation_form_id',$consultationId)->distinct()->get('section_id')->toArray();
			
			
			return view('frontend.appointments.printConsultationForm',compact('ClientConsultationFormGet','ClientConsultationFormField','LocationInfo'))->render();
		}
	}
	
	public function saveConsultationForm(Request $request){
		if ($request->isMethod('post')) {
			$client_consultation_form_id       = ($request->client_consultation_form_id) ? $request->client_consultation_form_id : '';
			$client_first_name                 = ($request->client_first_name) ? $request->client_first_name : null;
			$client_last_name                  = ($request->client_last_name) ? $request->client_last_name : null;
			$client_email                      = ($request->client_email) ? $request->client_email : null;
			$client_birthday                   = ($request->client_birthday) ? date("Y-m-d",strtotime($request->client_birthday)) : null;
			$country_code                      = ($request->country_code) ? $request->country_code : null;
			$client_mobile                     = ($request->client_mobile) ? $request->client_mobile : null;
			$client_gender                     = ($request->client_gender) ? $request->client_gender : null;
			$client_address                    = ($request->client_address) ? $request->client_address : null;
			$client_consultation_form_field_id = ($request->client_consultation_form_field_id) ? $request->client_consultation_form_field_id : array();
			$client_answer                     = ($request->client_answer) ? $request->client_answer : array();
			$signature_name                    = ($request->signature_name) ? $request->signature_name : null;
			
			$ClientConsultationFormGet = ClientConsultationForm::select('*')->with('clientConsultationFields')->where('id',$client_consultation_form_id)->get()->first()->toArray();
			
			if(!empty($ClientConsultationFormGet)){
				$ClientConsultationFormUpdate = ClientConsultationForm::find($client_consultation_form_id);
				
				$ClientConsultationFormUpdate->client_first_name = $client_first_name; 
				$ClientConsultationFormUpdate->client_last_name  = $client_last_name; 
				$ClientConsultationFormUpdate->client_email      = $client_email; 
				$ClientConsultationFormUpdate->client_birthday   = $client_birthday; 
				$ClientConsultationFormUpdate->country_code      = $country_code; 
				$ClientConsultationFormUpdate->client_mobile     = $client_mobile; 
				$ClientConsultationFormUpdate->client_gender     = $client_gender; 
				$ClientConsultationFormUpdate->client_address    = $client_address; 
				$ClientConsultationFormUpdate->signature_name    = $signature_name; 
				$ClientConsultationFormUpdate->status            = 1; 
				$ClientConsultationFormUpdate->completed_at      = date("Y-m-d H:i:s"); 
				$ClientConsultationFormUpdate->updated_at        = date("Y-m-d H:i:s"); 
				if($ClientConsultationFormUpdate->save()) 
				{
					if(!empty($client_consultation_form_field_id)) 
					{
						foreach($client_consultation_form_field_id as $CCFFI){
							$ClientConsultationFormField = ClientConsultationFormField::find($CCFFI);
							
							if(!empty($ClientConsultationFormField)){
								if(isset($client_answer[$CCFFI][0]) && count($client_answer[$CCFFI]) > 0){
									
									$elemt = (count($client_answer[$CCFFI]) > 1) ? implode(",",$client_answer[$CCFFI]) : $client_answer[$CCFFI][0];
									
									$ClientConsultationFormField->client_answer = $elemt;		
									$ClientConsultationFormField->updated_at    = date("Y-m-d H:i:s"); 
									$ClientConsultationFormField->save();		
								}
							}		
						}
					}
					
					Session::flash('message', 'Consultation form updated succesfully.');
					return redirect()->route('myConsultationForm');
				} else {
					Session::flash('error', 'Something went wrong.');
					return redirect()->route('myConsultationForm');
				}
			} else {
				Session::flash('error', 'Something went wrong.');
				return redirect()->route('myConsultationForm');
			}
		} else {
			Session::flash('error', 'Something went wrong.');
			return redirect()->route('myConsultationForm');
		}
	}
	
    public function cancelAppointment(Request $request)
    {
        $data = [];
        $data["status"] = false;
        $data["message"] = "Something went wrong!";

        $appointmentId = ($request->appointment_id) ? base64_decode($request->appointment_id) : NULL;

        if($appointmentId != '') {
            $appointment = Appointments::find($appointmentId);

            if(!empty($appointment)) 
			{
                $clientId = $appointment->client_id;
                $client = Clients::find($clientId);
				$onlineSettingData = Online_setting::select('appointment_cancel_time')->where('online_setting.user_id', $appointment->user_id)->first();
				
				if(!empty($onlineSettingData)) {
					if($onlineSettingData->appointment_cancel_time > 0) {
						
						$getAppintmentServices = AppointmentServices::select('appointment_services.id','appointment_services.appointment_date','appointment_services.start_time')->where('appointment_services.appointment_id',$appointmentId)->orderBy('appointment_services.id','asc')->first();
						
						$cancel_time = $onlineSettingData->appointment_cancel_time;
						$app_start_datetime = date("Y-m-d H:i:s", strtotime($getAppintmentServices->appointment_date." ".$getAppintmentServices->start_time));
						$curr_Datetime = date("Y-m-d H:i:s");
							
						$newTime = date("Y-m-d H:i:s", strtotime("- ".$cancel_time." minutes", strtotime($app_start_datetime)));
						
						if(strtotime($curr_Datetime) > strtotime($newTime)) {
							$data["status"] = false;
							$data["is_redirect"] = 1;
							$data["redirect_url"] = route('cancelError', $appointmentId);
							return JsonReturn::success($data);
						}	
					}	
				}	
				
				if(!empty($client)) {

					if($client->fuser_id == Auth::guard('fuser')->user()->id) {

						$appointment->is_cancelled = 1;
						$appointment->save();

						
						$getAppintmentServices = AppointmentServices::select('appointment_services.id','appointment_services.appointment_date','appointment_services.start_time', 'services.service_name')
												->where('appointment_services.appointment_id',$appointmentId)
												->leftJoin('services_price','appointment_services.service_price_id', 'services_price.id')
												->leftJoin('services','services.id', 'services_price.service_id')
												->orderBy('appointment_services.id','asc')
												->first();

						$app_start_datetime = !empty($getAppintmentServices) ? $getAppintmentServices->appointment_date.' '.$getAppintmentServices->start_time : '';

						$locationData = Location::find($appointment->location_id);

						# Send notification
						 /*$notificationParams = [
							'user_id'           => $appointment->user_id,
							'client_id'         => $appointment->client_id,
							'type'              => 'appointment',
							'type_id'           => $appointment->id,
							'title'				=> Auth::guard('fuser')->user()->name.' cancelled online',
							'description' => date('D d M h:ia', strtotime($app_start_datetime)).' '.(isset($getAppintmentServices->service_name) ? $getAppintmentServices->service_name : '').' with '.Auth::guard('fuser')->user()->name.' cancelled online at '.(isset($locationData->location_name) ? $locationData->location_name : '')
						];

						$this->notificationRepositorie->storeNotification($notificationParams); */

						$title = Auth::guard('fuser')->user()->name.' cancelled online';
						$description = date('D d M h:ia', strtotime($app_start_datetime)).' '.(isset($getAppintmentServices->service_name) ? $getAppintmentServices->service_name : '').' with '.Auth::guard('fuser')->user()->name.' cancelled online at '.(isset($locationData->location_name) ? $locationData->location_name : '');

						$this->notificationRepositorie->sendNotification($appointment->staff_user_id, $appointment->client_id, 'appointment', $appointment->id, $title, $description, $appointment->location_id, 'cancellations'); 
						
						$data["status"] = true;
						$data["message"] = "Appointment has been cancelled succesfully."; 
					} else {
						$data["status"] = false;
						$data["message"] = "Something went wrong!";
					}
				} else {
					$data["status"] = false;
					$data["message"] = "Client details not found.";
				}
            } else {
                $data["status"] = false;
                $data["message"] = "Appointment details not found.";
            }
        } else {
            $data["status"] = false;
            $data["message"] = "Direct access to this URL is not found.";
        }

        return JsonReturn::success($data);
    }

    public function postReview(Request $request)
    {
        $data = [];
        $data["status"] = false;
        $data["message"] = "Something went wrong!";

        if(empty($request->location_id)) {
            $data['status'] = false;
            $data['message'] = 'Something went wrong. Please reload and try again.';

        } else {

            if(empty($request->rating)) {
                $data['status'] = false;
                $data['message'] = 'Please fill up the review form.';
            } else {
            	$appointment_id = !empty($request->appointment_id) ? $request->appointment_id : NULL;

                if( FuserLocationReview::where([
                    'fuser_id' => Auth::guard('fuser')->user()->id,
                    'location_id' => $request->location_id,
                    'appointment_id' => $appointment_id
                ])->exists() ) {
                 
                    FuserLocationReview::where([
                        'fuser_id' => Auth::guard('fuser')->user()->id,
                        'location_id' => $request->location_id,
                        'appointment_id' => $appointment_id
                    ])->update([
                        'rating' => $request->rating,
                        'feedback' => !empty($request->feedback) ? $request->feedback : NULL,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);

                } else {

                    FuserLocationReview::create([
                        'fuser_id' => Auth::guard('fuser')->user()->id,
                        'location_id' => $request->location_id,
                        'appointment_id' => $appointment_id,
                        'rating' => $request->rating,
                        'feedback' => !empty($request->feedback) ? $request->feedback : NULL,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }

                $data['status'] = true;
                $data['message'] = 'Your review is submitted successfully.';
            }
        }

        return JsonReturn::success($data);
    }
}
