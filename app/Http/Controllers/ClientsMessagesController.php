<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\JsonReturn;
use App\Models\NewAppointment;
use App\Models\Services;
use App\Models\ReminderNotification;
use App\Models\RescheduleNotification;
use App\Models\Clients;
use App\Models\thankyouNotification;
use App\Models\cancellationNotification;
use App\Models\noShowNotification;
use App\Models\TippingNotification;
use App\Models\allNotificationStatus;
use App\Models\EmailLog;
use App\Models\Sms_log;
use App\Models\Staff;
use App\Models\User;
use App\Models\Location;
use App\Models\Setting;
use DataTables;
use Illuminate\Support\Facades\Crypt;
use App\Mail\testNewAppointments;
use App\Notification\TelnyxSms;
use DB;
use Mail;

class ClientsMessagesController extends Controller
{
  
    public function __construct()
    {
        $this->middleware('auth');
    }

    
    public function index()
    {
        return view('clientMessage.client_messages');
    }
	
	public function getmessage(Request $request){
		if ($request->ajax()) 
        {
			$authid = Auth::id();

			$email = EmailLog::where(['user_id' => $authid])->get();
			$sms = Sms_log::where(['user_id' => $authid])->get();
			$merged = $email->merge($sms);

			$messagelog = $merged->all();
			
            $data_arr = array();
            foreach($messagelog as $val)
            {
				if(isset($val->client_id)){
					$client = Clients::where('id',$val->client_id)->first();
				}
				$des = isset($val->to_email) ? $val->to_email : $val->send_to ;
				$status = isset($val->email_status) ? $val->email_status : $val->sms_status ;

				$tempData = array(
                    'clientid' => isset($client) ? $client->id : '',
                    'time' => $val->created_at != '' ? date("l, d F Y H:s A",strtotime($val->created_at)) : '',
                    'client' => isset($client->firstname) ? $client->firstname :'' ,
                    'appointment' => isset($val->appointment_id) ? $val->appointment_id : '',
                    'destination' => $des,
                    'type' => $val->module_type_text,
                    'message' => isset($val->message) ? $val->message : '',
                    'status' => $status,
                );
                array_push($data_arr, $tempData);
            }
         
            return Datatables::of($data_arr)
                ->editColumn('client', function ($row) {
					$url = isset($row['client']) ? url('/partners/view/'.$row['clientid'].'/client') : '';
                    $html = '<a class="text-blue" href="'.$url.'">'.$row['client'].'
					</a>';
                    return $html;
                })
				->editColumn('appointment', function ($row) {

					if(!empty($row['appointment'])) {
						$apoid = Crypt::encryptString($row['appointment']);
						$url = isset($row['client']) ? route('viewAppointment', $apoid) : '';
	                    $html = '<a class="text-blue" href="'.$url.'">#'.$row['appointment'].'
						</a>';
					} else {
						$html = '';
					}
                    return $html;
                })
				->editColumn('message', function ($row) {
					
                    $html = '<a data-toggle="modal" id="showsms" data-target="#reminderSmsModal"
					class="cursor-pointer text-blue">'.$row['message'].'</a>';
                    return $html;
                })
				->editColumn('status', function ($row) {
					if(isset($row['status'])){
						$html = '<span class="badge badge-success text-uppercase">
						'.$row['status'].'
						</span>';
					} else {
						$html = '';
					}
                   
                    return $html;
                })
				
                ->rawColumns(['client','message','status','appointment',])
                
                ->make(true);
        }
	}

	public function clientMessageSetting()
	{
		$CurrentUser = auth::user();
		$is_admin = $CurrentUser->is_admin;
		
		if($is_admin == 1){
			$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
			$AdminId = $CurrentStaff->user_id;
			$UserId  = Auth::id();
		} else {
			$AdminId = Auth::id();
			$UserId  = Auth::id();
		}
		
		$noti = allNotificationStatus::where('user_id',$AdminId)->first();

        return view('clientMessage.client_messages_settings',compact('noti'));
	}

	public function newAppoinmentNoti(Request $request)
	{
		$CurrentUser = auth::user();
		$is_admin = $CurrentUser->is_admin;
		
		if($is_admin == 1){
			$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
			$AdminId = $CurrentStaff->user_id;
			$UserId  = Auth::id();
		} else {
			$AdminId = Auth::id();
			$UserId  = Auth::id();
		}
		
        $newappo = NewAppointment::where('user_id',$AdminId)->first();
		$service = Services::where('user_id',$AdminId)->where('is_deleted',0)->with('servicePrice')->first();
		$client = Clients::where('user_id',$AdminId)->where('is_deleted',0)->first();

		return view('clientMessage.new_appoinment_notification',compact('service','newappo','client'));
	}
	
	public function testEmail(Request $request)
	{
		if ($request->ajax())
		{
			$CurrentUser = auth::user();
			$is_admin = $CurrentUser->is_admin;
			
			if($is_admin == 1){
				$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
				$AdminId = $CurrentStaff->user_id;
				$UserId  = Auth::id();
			} else {
				$AdminId = Auth::id();
				$UserId  = Auth::id();
			}
			$getUser = User::getUserbyID($AdminId);
			
			$locationData = Location::select('locations.id', 'locations.location_name', 'locations.location_image', 'locations.location_address')->where('user_id', $AdminId)->orderBy('id', 'ASC')->first();
			
			$is_price_view = $request->is_price_view;
			$note = $request->note;
			$type = $request->type;
			
			$email = $request->email;
			$FROM_EMAIL     = env("MAIL_FROM_ADDRESS", "info@ikotel.ca");
			$FROM_NAME      = 'Test | Scheduledown';
			$TO_EMAIL       = $email;
			$CC_EMAIL       = 'tjcloudtest2@gmail.com';
			
			$FIRST_NAME     = $getUser->first_name;
			
			if($type == 1) {
				$SUBJECT = 'Appointment';
			} else if($type == 2) {
				$SUBJECT = 'Appointment Reminder';
			} else if($type == 3) {
				$SUBJECT = 'Appointment Rescheduled';
			} else if($type == 4) {
				$SUBJECT = 'Thanks for visiting, please rate your experience';
			} else if($type == 5) {
				$SUBJECT = 'Appointment cancelled';
			} else if($type == 6) {
				$SUBJECT = $FIRST_NAME.', you did not show up to your appointment';
			} else if($type == 7) {
				$SUBJECT = 'Thanks for your tip!';
			}		
			
			$UniqueId       = $this->unique_code(30).time();
			
			$SendMail = Mail::to($TO_EMAIL)->cc([$CC_EMAIL])->send(new testNewAppointments($FROM_EMAIL,$FROM_NAME,$SUBJECT,$FIRST_NAME,$is_price_view,$note,$UniqueId,$locationData,$type));	
			
			$data["status"] = true;
			$data["message"] = "Mail send succesfully.";
			return JsonReturn::success($data);
		}
	}
	
	public function testSms(Request $request)
	{
		if ($request->ajax())
		{
			$CurrentUser = auth::user();
			$is_admin = $CurrentUser->is_admin;
			
			if($is_admin == 1){
				$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
				$AdminId = $CurrentStaff->user_id;
				$UserId  = Auth::id();
			} else {
				$AdminId = Auth::id();
				$UserId  = Auth::id();
			}
			$getUser = User::getUserbyID($AdminId);
			
			$locationData = Location::select('locations.id', 'locations.location_name', 'locations.location_image', 'locations.location_address')->where('user_id', $AdminId)->orderBy('id', 'ASC')->first();
			
			$telephone = $request->telephone;
			$tel_country_code = $request->tel_country_code;
			
			$FIRST_NAME     = $getUser->first_name;
			$UniqueId       = $this->unique_code(30).time();
			
			$setting = Setting::first();
			$fromNumber = $setting->telnyx_number;
			
			$customerMobileNo = $tel_country_code.$telephone;
			$sms_body = "Hi ".$FIRST_NAME.", this is a friendly reminder about your appointment with ".$locationData->location_name." for Sun, Sep 26 at 11:00am. Manage booking: [APPOINTMENT_LINK] (NO REPLY)";
			
			$smsResponse = TelnyxSms::sendTelnyxSms($customerMobileNo,$fromNumber,$sms_body);
			if(isset($smsResponse->errors))
			{
				$data["status"] = false;
				$data["message"] = $smsResponse->errors[0]->detail;
			} 
			else
			{
				$data["status"] = true;
				$data["message"] = "SMS send succesfully.";
			}
			
			return JsonReturn::success($data);
		}
	}
	
	public function updateNewAppoNoti(Request $request)
	{
		if ($request->ajax())
		{
			$CurrentUser = auth::user();
			$is_admin = $CurrentUser->is_admin;
			
			if($is_admin == 1){
				$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
				$AdminId = $CurrentStaff->user_id;
				$UserId  = Auth::id();
			} else {
				$AdminId = Auth::id();
				$UserId  = Auth::id();
			}
			
			$it = NewAppointment::where('user_id',$AdminId)->first();
			if($it)
			{
				$it->update([
					'user_id'        => $AdminId,
					'is_manuallyBook'      => $request->is_manuallyBook=='on' ? 1 : 0,
					'is_displayServicePrice'     => $request->is_displayServicePrice=='on' ? 1 : 0,
					'important_info'    => $request->important_info,
				]);
				$data["status"] = true;
				$data["message"] = "New Appointment Notification Details update succesfully.";
			} else {
				$it = NewAppointment::create([
					'user_id'        => $AdminId,
					'is_manuallyBook'      => $request->is_manuallyBook=='on' ? 1 : 0,
					'is_displayServicePrice'     => $request->is_displayServicePrice=='on' ? 1 : 0,
					'important_info'    => $request->important_info,
				]);
				$data["status"] = true;
				$data["message"] = "New Appointment Notification Details added succesfully.";
			}
			return JsonReturn::success($data);
		}
	}
	
	public function remiderNotification()
	{
		$CurrentUser = auth::user();
		$is_admin = $CurrentUser->is_admin;
		
		if($is_admin == 1){
			$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
			$AdminId = $CurrentStaff->user_id;
			$UserId  = Auth::id();
		} else {
			$AdminId = Auth::id();
			$UserId  = Auth::id();
		}

		$new = ReminderNotification::where('user_id',$AdminId)->first();
		$service = Services::where('user_id',$AdminId)->where('is_deleted',0)->with('servicePrice')->first();
		$client = Clients::where('user_id',$AdminId)->where('is_deleted',0)->first();

		return view('clientMessage.appoinment_reminder',compact('new','service','client'));
	}


	public function updateRemiderNoti(Request $request)
	{
		if ($request->ajax())
		{
			$CurrentUser = auth::user();
			$is_admin = $CurrentUser->is_admin;
			
			if($is_admin == 1){
				$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
				$AdminId = $CurrentStaff->user_id;
				$UserId  = Auth::id();
			} else {
				$AdminId = Auth::id();
				$UserId  = Auth::id();
			}
			
			$it = ReminderNotification::where('user_id',$AdminId)->first();
			if($it)
			{
				$it->update([
					'user_id'        => $AdminId,
					'is_reminderMessage'      => $request->is_reminderMessage=='on' ? 1 : 0,
					'is_displayServicePrice'     => $request->is_displayServicePrice=='on' ? 1 : 0,
					'advance_notice'    => $request->advance_notice,
					'additional_reminder'    => $request->additional_reminder,
					'important_info'    => $request->important_info,
				]);
				$data["status"] = true;
				$data["message"] = "Reminder Notification Details update succesfully.";
			} else {
				$it = ReminderNotification::create([
					'user_id'        => $AdminId,
					'is_reminderMessage'      => $request->is_reminderMessage=='on' ? 1 : 0,
					'is_displayServicePrice'     => $request->is_displayServicePrice=='on' ? 1 : 0,
					'advance_notice'    => $request->advance_notice,
					'additional_reminder'    => $request->additional_reminder,
					'important_info'    => $request->important_info,
				]);
				$data["status"] = true;
				$data["message"] = "Reminder Notification Details added succesfully.";
			}
			return JsonReturn::success($data);
		}
	}

	public function rescheduleNotification()
	{
		$CurrentUser = auth::user();
		$is_admin = $CurrentUser->is_admin;
		
		if($is_admin == 1){
			$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
			$AdminId = $CurrentStaff->user_id;
			$UserId  = Auth::id();
		} else {
			$AdminId = Auth::id();
			$UserId  = Auth::id();
		}
		
		$resCh = RescheduleNotification::where('user_id',$AdminId)->first();
		$service = Services::where('user_id',$AdminId)->where('is_deleted',0)->with('servicePrice')->first();
		$client = Clients::where('user_id',$AdminId)->where('is_deleted',0)->first();

		return view('clientMessage.reschedule_appoinment',compact('resCh','service','client'));
	}


	public function updateRescheduleNoti(Request $request)
	{
		if ($request->ajax())
		{
			$CurrentUser = auth::user();
			$is_admin = $CurrentUser->is_admin;
			
			if($is_admin == 1){
				$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
				$AdminId = $CurrentStaff->user_id;
				$UserId  = Auth::id();
			} else {
				$AdminId = Auth::id();
				$UserId  = Auth::id();
			}
			
			$it = RescheduleNotification::where('user_id',$AdminId)->first();
			if($it){
				$it->update([
					'user_id'        => $AdminId,
					'is_rescheduleMessage'      => $request->is_rescheduleMessage=='on' ? 1 : 0,
					'is_displayServicePrice'     => $request->is_displayServicePrice=='on' ? 1 : 0,
					'important_info'    => $request->important_info,
				]);
				$data["status"] = true;
				$data["message"] = "Reschedule Notification Details update succesfully.";
			} else {
				$it = RescheduleNotification::create([
					'user_id'        => $AdminId,
					'is_rescheduleMessage'      => $request->is_rescheduleMessage=='on' ? 1 : 0,
					'is_displayServicePrice'     => $request->is_displayServicePrice=='on' ? 1 : 0,
					'important_info'    => $request->important_info,
				]);
				$data["status"] = true;
				$data["message"] = "Reschedule Notification Details added succesfully.";
			}
			return JsonReturn::success($data);
		}
	}

	public function thankyouNotification()
	{
		$CurrentUser = auth::user();
		$is_admin = $CurrentUser->is_admin;
		
		if($is_admin == 1){
			$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
			$AdminId = $CurrentStaff->user_id;
			$UserId  = Auth::id();
		} else {
			$AdminId = Auth::id();
			$UserId  = Auth::id();
		}
		
		$notifi = thankyouNotification::where('user_id',$AdminId)->first();
		$service = Services::where('user_id',$AdminId)->where('is_deleted',0)->with('servicePrice')->first();
		$client = Clients::where('user_id',$AdminId)->where('is_deleted',0)->first();

		return view('clientMessage.thank_you_notification',compact('notifi','service','client'));
	}


	public function updatethankyouNotifi(Request $request)
	{
		if ($request->ajax())
		{
			$CurrentUser = auth::user();
			$is_admin = $CurrentUser->is_admin;
			
			if($is_admin == 1){
				$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
				$AdminId = $CurrentStaff->user_id;
				$UserId  = Auth::id();
			} else {
				$AdminId = Auth::id();
				$UserId  = Auth::id();
			}
			
			$it = thankyouNotification::where('user_id',$AdminId)->first();
			if($it){
				$it->update([
					'user_id'        => $AdminId,
					'is_thankyouMessage'      => $request->is_thankyouMessage=='on' ? 1 : 0,
					'is_displayServicePrice'     => $request->is_displayServicePrice=='on' ? 1 : 0,
					'important_info'    => $request->important_info,
				]);
				$data["status"] = true;
				$data["message"] = "Thank You Notification Details update succesfully.";
			} else {
				$it = thankyouNotification::create([
					'user_id'        => $AdminId,
					'is_thankyouMessage'      => $request->is_thankyouMessage=='on' ? 1 : 0,
					'is_displayServicePrice'     => $request->is_displayServicePrice=='on' ? 1 : 0,
					'important_info'    => $request->important_info,
				]);
				$data["status"] = true;
				$data["message"] = "Thank You Notification Details added succesfully.";
			}
			return JsonReturn::success($data);
		}
	}

	public function cancellationNotification()
	{
		$CurrentUser = auth::user();
		$is_admin = $CurrentUser->is_admin;
		
		if($is_admin == 1){
			$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
			$AdminId = $CurrentStaff->user_id;
			$UserId  = Auth::id();
		} else {
			$AdminId = Auth::id();
			$UserId  = Auth::id();
		}
		
		$can = cancellationNotification::where('user_id',$AdminId)->first();
		$service = Services::where('user_id',$AdminId)->where('is_deleted',0)->with('servicePrice')->first();
		$client = Clients::where('user_id',$AdminId)->where('is_deleted',0)->first();

		return view('clientMessage.cancelled_appoinment',compact('can','service','client'));
	}


	public function updatecancellationNotifi(Request $request)
	{
		if ($request->ajax())
		{
			$CurrentUser = auth::user();
			$is_admin = $CurrentUser->is_admin;
			
			if($is_admin == 1){
				$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
				$AdminId = $CurrentStaff->user_id;
				$UserId  = Auth::id();
			} else {
				$AdminId = Auth::id();
				$UserId  = Auth::id();
			}
			
			$it = cancellationNotification::where('user_id',$AdminId)->first();
			if($it){
				$it->update([
					'user_id'        => $AdminId,
					'is_cancellationMessage'      => $request->is_cancellationMessage=='on' ? 1 : 0,
					'is_displayServicePrice'     => $request->is_displayServicePrice=='on' ? 1 : 0,
					'important_info'    => $request->important_info,
				]);
				$data["status"] = true;
				$data["message"] = "cancellation Notification Details update succesfully.";
			} else {
				$it = cancellationNotification::create([
					'user_id'        => $AdminId,
					'is_cancellationMessage'      => $request->is_cancellationMessage=='on' ? 1 : 0,
					'is_displayServicePrice'     => $request->is_displayServicePrice=='on' ? 1 : 0,
					'important_info'    => $request->important_info,
				]);
				$data["status"] = true;
				$data["message"] = "cancellation Notification Details added succesfully.";
			}
			return JsonReturn::success($data);
		}
	}

	public function noShowNotification()
	{
		$CurrentUser = auth::user();
		$is_admin = $CurrentUser->is_admin;
		
		if($is_admin == 1){
			$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
			$AdminId = $CurrentStaff->user_id;
			$UserId  = Auth::id();
		} else {
			$AdminId = Auth::id();
			$UserId  = Auth::id();
		}
		
		$noshow = noShowNotification::where('user_id',$AdminId)->first();
		$service = Services::where('user_id',$AdminId)->where('is_deleted',0)->with('servicePrice')->first();
		$client = Clients::where('user_id',$AdminId)->where('is_deleted',0)->first();

		return view('clientMessage.no_show_notification',compact('noshow','service','client'));
	}

	public function updatenoShowNotifi(Request $request)
	{
		if ($request->ajax())
		{
			$CurrentUser = auth::user();
			$is_admin = $CurrentUser->is_admin;
			
			if($is_admin == 1){
				$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
				$AdminId = $CurrentStaff->user_id;
				$UserId  = Auth::id();
			} else {
				$AdminId = Auth::id();
				$UserId  = Auth::id();
			}
			
			$it = noShowNotification::where('user_id',$AdminId)->first();
			if($it){
				$it->update([
					'user_id'        => $AdminId,
					'is_noShowMessage'      => $request->is_noShowMessage=='on' ? 1 : 0,
					'is_displayServicePrice'     => $request->is_displayServicePrice=='on' ? 1 : 0,
					'important_info'    => $request->important_info,
				]);
				$data["status"] = true;
				$data["message"] = "No Show Notification Details update succesfully.";
			} else {
				$it = noShowNotification::create([
					'user_id'        => $AdminId,
					'is_noShowMessage'      => $request->is_noShowMessage=='on' ? 1 : 0,
					'is_displayServicePrice'     => $request->is_displayServicePrice=='on' ? 1 : 0,
					'important_info'    => $request->important_info,
				]);
				$data["status"] = true;
				$data["message"] = "No Show Notification Details added succesfully.";
			}
			return JsonReturn::success($data);
		}
	}
	
	public function tippingNotification()
	{
		$CurrentUser = auth::user();
		$is_admin = $CurrentUser->is_admin;
		
		if($is_admin == 1){
			$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
			$AdminId = $CurrentStaff->user_id;
			$UserId  = Auth::id();
		} else {
			$AdminId = Auth::id();
			$UserId  = Auth::id();
		}
		
		$tip = TippingNotification::where('user_id',$AdminId)->first();
		$service = Services::where('user_id',$AdminId)->where('is_deleted',0)->with('servicePrice')->first();
		$client = Clients::where('user_id',$AdminId)->where('is_deleted',0)->first();

		return view('clientMessage.tipping_notification',compact('tip','service','client'));
	}

	public function updatetippingNotifi(Request $request)
	{
		if ($request->ajax())
		{
			$CurrentUser = auth::user();
			$is_admin = $CurrentUser->is_admin;
			
			if($is_admin == 1){
				$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
				$AdminId = $CurrentStaff->user_id;
				$UserId  = Auth::id();
			} else {
				$AdminId = Auth::id();
				$UserId  = Auth::id();
			}
			
			$it = TippingNotification::where('user_id',$AdminId)->first();
			if($it){
				$it->update([
					'user_id'        => $AdminId,
					'is_message'      => $request->is_message=='on' ? 1 : 0,
					'is_displayServicePrice'     => $request->is_displayServicePrice=='on' ? 1 : 0,
					'important_info'    => $request->important_info,
				]);
				$data["status"] = true;
				$data["message"] = "Tipping Notification Details update succesfully.";
			} else {
				$it = TippingNotification::create([
					'user_id'        => $AdminId,
					'is_message'      => $request->is_message=='on' ? 1 : 0,
					'is_displayServicePrice'     => $request->is_displayServicePrice=='on' ? 1 : 0,
					'important_info'    => $request->important_info,
				]);
				$data["status"] = true;
				$data["message"] = "Tipping Notification Details added succesfully.";
			}
			return JsonReturn::success($data);
		}
	}

	public function desablenotification()
	{
		$CurrentUser = auth::user();
		$is_admin = $CurrentUser->is_admin;
		
		if($is_admin == 1){
			$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
			$AdminId = $CurrentStaff->user_id;
			$UserId  = Auth::id();
		} else {
			$AdminId = Auth::id();
			$UserId  = Auth::id();
		}
		
		$it = allNotificationStatus::where('user_id',$AdminId)->first();
		$new_appointment = NewAppointment::where('user_id',$AdminId)->first();
		$reminder_notification = ReminderNotification::where('user_id',$AdminId)->first();
		$reshedule_notification = RescheduleNotification::where('user_id',$AdminId)->first();
		$thank_you_notification = thankyouNotification::where('user_id',$AdminId)->first();
		$calcelled_notification = cancellationNotification::where('user_id',$AdminId)->first();
		$no_show_notification = noShowNotification::where('user_id',$AdminId)->first();
		$tipping_notification = TippingNotification::where('user_id',$AdminId)->first();

		if($it) {
			$it->update([
				'user_id'  => $AdminId,
				'status'   => 0,
			]);
			$data["status"] = true;
			$data["message"] = "Notification Details update succesfully.";
		} else {
			$it = allNotificationStatus::create([
				'user_id'  => $AdminId,
				'status'   => 0,
			]);
			$data["status"] = true;
			$data["message"] = "Notification Details added succesfully.";
		}
		
		if($new_appointment){
			$new_appointment->update([
				'user_id' => $AdminId,
				'is_manuallyBook'  => 0,

			]);
		} else {
			$new_appointment = NewAppointment::create([
				'user_id' => $AdminId,
				'is_manuallyBook'  => 0,
			]);
		}

		if($reminder_notification){
			$reminder_notification->update([
				'user_id' => $AdminId,
				'is_reminderMessage'  => 0,
			]);
		} else {
			$reminder_notification = ReminderNotification::create([
				'user_id' => $AdminId,
				'is_reminderMessage'  => 0,
			]);
		}

		if($reshedule_notification){
			$reshedule_notification->update([
				'user_id' => $AdminId,
				'is_rescheduleMessage'  => 0,
			]);
		} else {
			$reshedule_notification = RescheduleNotification::create([
				'user_id' => $AdminId,
				'is_rescheduleMessage'  => 0,
			]);
		}

		if($thank_you_notification){
			$thank_you_notification->update([
				'user_id' => $AdminId,
				'is_thankyouMessage'  => 0,
			]);
		} else {
			$thank_you_notification = RescheduleNotification::create([
				'user_id' => $AdminId,
				'is_thankyouMessage'  => 0,
			]);
		}

		if($calcelled_notification){
			$calcelled_notification->update([
				'user_id' => $AdminId,
				'is_cancellationMessage'  => 0,
			]);
		} else {
			$calcelled_notification = cancellationNotification::create([
				'user_id' => $AdminId,
				'is_cancellationMessage'  => 0,
			]);
		}

		if($no_show_notification){
			$no_show_notification->update([
				'user_id' => $AdminId,
				'is_noShowMessage'  => 0,
			]);
		} else {
			$no_show_notification = noShowNotification::create([
				'user_id' => $AdminId,
				'is_noShowMessage'  => 0,
			]);
		}

		if($tipping_notification){
			$tipping_notification->update([
				'user_id' => $AdminId,
				'is_message'  => 0,
			]);
		} else {
			$tipping_notification = TippingNotification::create([
				'user_id' => $AdminId,
				'is_message'  => 0,
			]);
		}
		
		return JsonReturn::success($data);
	}

	public function enablenotification()
	{
		$CurrentUser = auth::user();
		$is_admin = $CurrentUser->is_admin;
		
		if($is_admin == 1){
			$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
			$AdminId = $CurrentStaff->user_id;
			$UserId  = Auth::id();
		} else {
			$AdminId = Auth::id();
			$UserId  = Auth::id();
		}
		
		$it = allNotificationStatus::where('user_id',$AdminId)->first();

		//new_appointment, reminder_notification, reshedule_notification, thank_you_notification, calcelled_notification, no_show_notification, tipping_notification

		$new_appointment = NewAppointment::where('user_id',$AdminId)->first();
		$reminder_notification = ReminderNotification::where('user_id',$AdminId)->first();
		$reshedule_notification = RescheduleNotification::where('user_id',$AdminId)->first();
		$thank_you_notification = thankyouNotification::where('user_id',$AdminId)->first();
		$calcelled_notification = cancellationNotification::where('user_id',$AdminId)->first();
		$no_show_notification = noShowNotification::where('user_id',$AdminId)->first();
		$tipping_notification = TippingNotification::where('user_id',$AdminId)->first();

		if($it){
			$it->update([
				'user_id' => $AdminId,
				'status'  => 1,
			]);
			$data["status"] = true;
			$data["message"] = "Notification Details update succesfully.";
		} else {
			$it = allNotificationStatus::create([
				'user_id' => $AdminId,
				'status'  => 1,
			]);
			$data["status"] = true;
			$data["message"] = "Notification Details added succesfully.";
		}

		if($new_appointment){
			$new_appointment->update([
				'user_id' => $AdminId,
				'is_manuallyBook'  => 1,
			]);
		} else {
			$new_appointment = NewAppointment::create([
				'user_id' => $AdminId,
				'is_manuallyBook'  => 1,
			]);
		}

		if($reminder_notification){
			$reminder_notification->update([
				'user_id' => $AdminId,
				'is_reminderMessage'  => 1,
			]);
		} else {
			$reminder_notification = ReminderNotification::create([
				'user_id' => $AdminId,
				'is_reminderMessage'  => 1,
			]);
		}

		if($reshedule_notification){
			$reshedule_notification->update([
				'user_id' => $AdminId,
				'is_rescheduleMessage'  => 1,
			]);
		} else {
			$reshedule_notification = RescheduleNotification::create([
				'user_id' => $AdminId,
				'is_rescheduleMessage'  => 1,
			]);
		}

		if($thank_you_notification){
			$thank_you_notification->update([
				'user_id' => $AdminId,
				'is_thankyouMessage'  => 1,
			]);
		} else {
			$thank_you_notification = RescheduleNotification::create([
				'user_id' => $AdminId,
				'is_thankyouMessage'  => 1,
			]);
		}

		if($calcelled_notification){
			$calcelled_notification->update([
				'user_id' => $AdminId,
				'is_cancellationMessage'  => 1,
			]);
		} else {
			$calcelled_notification = cancellationNotification::create([
				'user_id' => $AdminId,
				'is_cancellationMessage'  => 1,
			]);
		}

		if($no_show_notification){
			$no_show_notification->update([
				'user_id' => $AdminId,
				'is_noShowMessage'  => 1,
			]);
		} else {
			$no_show_notification = noShowNotification::create([
				'user_id' => $AdminId,
				'is_noShowMessage'  => 1,
			]);
		}

		if($tipping_notification){
			$tipping_notification->update([
				'user_id' => $AdminId,
				'is_message'  => 1,
			]);
		} else {
			$tipping_notification = TippingNotification::create([
				'user_id' => $AdminId,
				'is_message'  => 1,
			]);
		}

		return JsonReturn::success($data);
	}
	
	function unique_code($digits){
        $this->autoRender = false;
        srand((double)microtime() * 10000000);
        $input = array("A", "B", "C", "D", "E", "F", "G", "H", "J", "K", "L", "M", "N", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "1", "2", "3", "4", "5", "6", "7", "8", "9");
        //$input = array("0","1","2","3","4","5","6","7","8","9");
        $random_generator = "";// Initialize the string to store random numbers
        for ($i = 1; $i < $digits + 1; $i++) {
            // Loop the number of times of required digits

            if (rand(1, 2) == 1) {// to decide the digit should be numeric or alphabet
                $rand_index = array_rand($input);
                $random_generator .= $input[$rand_index]; // One char is added
            } else {
                $random_generator .= rand(1, 7); // one number is added
            }
        } // end of for loop
        return $random_generator;
    } // end of function
}
