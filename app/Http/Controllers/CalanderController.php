<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\JsonReturn;
use App\Models\User;
use App\Models\Clients;
use App\Models\Staff;
use App\Models\Location;	
use App\Models\Permission;
use App\Models\Country;
use App\Models\Services;
use App\Models\ServicesPrice;
use App\Models\ServiceCategory;
use App\Models\StaffLocations;
use App\Models\Appointments;
use App\Models\AppointmentServices;
use App\Models\StaffBlockedTime;
use DataTables;
use Session;
use Crypt;
use Carbon;
  
class CalanderController extends Controller
{
    /**
     * Create a new controller instance. test
     *
     * @return void
     */
    public function __construct()
    {
		$currentRoute = Route::currentRouteName();
		$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
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
		
		$locationId = Location::select('locations.id')->where('user_id', $AdminId)->orderBy('id', 'ASC')->first();
		
		// Get all staff
		
		if (Auth::user()->can('access_other_staff_calendars')) {
			$Staff = Staff::select('staff.id','staff.staff_user_id','users.first_name','users.last_name')->where(['user_id'=>$AdminId])->where(['is_appointment_booked'=>1])->join('users','users.id','=','staff.staff_user_id')->orderBy('staff.id', 'ASC')->get()->toArray();
		} else {
			$Staff = Staff::select('staff.id','staff.staff_user_id','users.first_name','users.last_name')->where(['user_id'=>$AdminId])->where(['is_appointment_booked'=>1])->join('users','users.id','=','staff.staff_user_id')->where('staff.staff_user_id',$CurrentUser->id)->orderBy('staff.id', 'ASC')->get()->toArray();
		}
				
		$staffResource = array();
		if(!empty($Staff)){
			foreach($Staff as $StaffData){
				$tempStaff['id']        = $StaffData['staff_user_id'];
				$tempStaff['name']      = $StaffData['first_name'].' '.$StaffData['last_name'];
				$tempStaff['className'] = 'blue';
				array_push($staffResource,$tempStaff);
			}
		}
		$jsonStaff = json_encode($staffResource);
		
		// get all appointment services
		/*$AppointmentServices = AppointmentServices::select('appointment_services.*','users.first_name','users.last_name','appointments.client_id')->leftJoin('appointments','appointments.id','=','appointment_services.appointment_id')->leftJoin('users','users.id','=','appointment_services.staff_user_id')->where('appointment_services.user_id', $AdminId)->where('appointments.is_cancelled', '0')->orderBy('appointment_services.created_at', 'asc')->get()->toArray();

		$appointmentEvents = array();
		
		if(!empty($AppointmentServices))
		{
			foreach($AppointmentServices as $AppointmentServicesData)
			{
				$StartTime    = date('Y-m-d',strtotime($AppointmentServicesData['appointment_date'])).'T'.date('H:i',strtotime($AppointmentServicesData['start_time']));
				$StartTimeNew = date('Y-m-d',strtotime($AppointmentServicesData['appointment_date'])).' '.date('H:i:s',strtotime($AppointmentServicesData['start_time']));
				
				$EndTime    = date('Y-m-d',strtotime($AppointmentServicesData['appointment_date'])).'T'.date('H:i',strtotime($AppointmentServicesData['end_time'])); 
				$EndTimeNew = date('Y-m-d',strtotime($AppointmentServicesData['appointment_date'])).' '.date('H:i:s',strtotime($AppointmentServicesData['end_time'])); 
				
				$servicePrices = ServicesPrice::select('services_price.pricing_name','services.service_name')->leftJoin('services','services.id','=','services_price.service_id')->where('services_price.id',$AppointmentServicesData['service_price_id'])->orderBy('services_price.id', 'asc')->get()->first();
					
				$serviceName = '';
				if(!empty($servicePrices)){
					$serviceName = $servicePrices->service_name.' - '.$servicePrices->pricing_name;
				} else {
					$serviceName = 'N/A';
				}
				
				if($AppointmentServicesData['client_id'] == 0){
					$ClientName = 'Walk-In';
				} else {
					$ClientInfo = Clients::getClientbyID($AppointmentServicesData['client_id']);	
					if(!empty($ClientInfo)){
						$ClientName = $ClientInfo->firstname.' '.$ClientInfo->lastname;	
					} else {
						$ClientName = 'Walk-In';
					}
				}

				$tempAppEvents = [];
				$tempAppEvents['title']                  = $ClientName.' | '.$serviceName; 
				$tempAppEvents['start']                  = $StartTime; 
				$tempAppEvents['start_time']             = $StartTimeNew; 
				$tempAppEvents['end']                    = $EndTime;
				$tempAppEvents['end_time']               = $EndTimeNew;
				$tempAppEvents['allDay']                 = false; 
				$tempAppEvents['resources']              = $AppointmentServicesData['staff_user_id']; 
				$tempAppEvents['appointment_id']         = $AppointmentServicesData['appointment_id']; 
				$tempAppEvents['appointment_id_enc']     = Crypt::encryptString($AppointmentServicesData['appointment_id']); 
				$tempAppEvents['appointment_service_id'] = $AppointmentServicesData['id']; 
				array_push($appointmentEvents,$tempAppEvents);
			}
		}
		
		// get all blocked time
		$StaffBlockedTime = StaffBlockedTime::select('staff_blocked_time.*','users.first_name','users.last_name', 'staff.id as staff_id')->leftjoin('users','users.id','=','staff_blocked_time.staff_user_id')->leftJoin('staff','staff.staff_user_id','=','users.id')->where('staff_blocked_time.user_id', $AdminId)->orderBy('staff_blocked_time.created_at', 'asc')->get()->toArray();

		if(!empty($StaffBlockedTime))
		{
			foreach($StaffBlockedTime as $StaffBlockedTimeData)
			{
				$StartTime    = date('Y-m-d',strtotime($StaffBlockedTimeData['date'])).'T'.date('H:i',strtotime($StaffBlockedTimeData['start_time']));
				$StartTimeNew = date('Y-m-d',strtotime($StaffBlockedTimeData['date'])).' '.date('H:i:s',strtotime($StaffBlockedTimeData['start_time']));
				
				$EndTime    = date('Y-m-d',strtotime($StaffBlockedTimeData['date'])).'T'.date('H:i',strtotime($StaffBlockedTimeData['end_time'])); 
				$EndTimeNew = date('Y-m-d',strtotime($StaffBlockedTimeData['date'])).' '.date('H:i:s',strtotime($StaffBlockedTimeData['end_time'])); 
				
				$serviceName = 'Blocked Time';
				
				if(!empty($StaffBlockedTimeData['description'])){
					$BlockedTimeDescription = ' | '.$StaffBlockedTimeData['description'];
				}
				
				$tempAppEvents = [];
				$tempAppEvents['title']                  = $serviceName.$BlockedTimeDescription; 
				$tempAppEvents['start']                  = $StartTime; 
				$tempAppEvents['start_time']             = $StartTimeNew; 
				$tempAppEvents['end']                    = $EndTime;
				$tempAppEvents['end_time']               = $EndTimeNew;
				$tempAppEvents['allDay']                 = false; 
				$tempAppEvents['resources']              = $StaffBlockedTimeData['staff_user_id']; 
				$tempAppEvents['appointment_id']         = NULL; 
				$tempAppEvents['appointment_service_id'] = $StaffBlockedTimeData['id']; 
				
				$tempAppEvents['staff_blocked_time_id_enc'] = Crypt::encryptString($StaffBlockedTimeData['id']); 
				$tempAppEvents['staff_user_id']     		= $StaffBlockedTimeData['staff_user_id']; 
				$tempAppEvents['allow_online_booking']    	= $StaffBlockedTimeData['allow_online_booking']; 
				$tempAppEvents['description']     			= $StaffBlockedTimeData['description']; 
				array_push($appointmentEvents,$tempAppEvents);
			}
		}
		
		$jsonAppointment = json_encode($appointmentEvents);*/


		$TimeSlots = array();
		$ClockStartTime = date("00:00:00");
		
		for($Start = 0;$Start < 288;$Start++){
				
			if($Start == 0){
				$Hours       = date("H",strtotime($ClockStartTime)) * 60 * 60;
				$Minutes     = date("i",strtotime($ClockStartTime)) * 60;
				$TotalMinute = $Hours + $Minutes;
				
				$tempSlots['time']          = date("h:ia",strtotime($ClockStartTime));
				$tempSlots['timevalue']     = $ClockStartTime;
				$tempSlots['timeinseconds'] = $TotalMinute;
				array_push($TimeSlots,$tempSlots);
			} else {
				$ClockStartTime = date("H:i:s",strtotime('+5 minutes',strtotime($ClockStartTime)));
				$Hours       = date("H",strtotime($ClockStartTime)) * 60 * 60;
				$Minutes     = date("i",strtotime($ClockStartTime)) * 60;
				$TotalMinute = $Hours + $Minutes;
				
				$tempSlots['time']          = date("h:ia",strtotime($ClockStartTime));
				$tempSlots['timevalue']     = $ClockStartTime;
				$tempSlots['timeinseconds'] = $TotalMinute;
				array_push($TimeSlots,$tempSlots);
			}
		}

		$rescheduleAppointmentId = request()->reschedule;
		
        return view('calander.index', compact('locationId','jsonStaff','staffResource','is_admin','TimeSlots','rescheduleAppointmentId'));//,'jsonAppointment'
    }

    public function fetchCalendarEvents(Request $request)
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

    	// get all appointment services
    	$AppointmentServices = AppointmentServices::select('appointment_services.*','users.first_name','users.last_name','appointments.client_id')->leftJoin('appointments','appointments.id','=','appointment_services.appointment_id')->leftJoin('users','users.id','=','appointment_services.staff_user_id')->where('appointment_services.user_id', $AdminId)->where('appointments.is_cancelled', '0')->whereBetween('appointment_services.appointment_date', [date('Y-m-d', strtotime($request->start)), date('Y-m-d', strtotime($request->end))])->orderBy('appointment_services.created_at', 'asc')->get()->toArray();

    	$appointmentEvents = array();
    	
    	if(!empty($AppointmentServices))
    	{
    		foreach($AppointmentServices as $AppointmentServicesData)
    		{
    			$StartTime    = date('Y-m-d',strtotime($AppointmentServicesData['appointment_date'])).'T'.date('H:i',strtotime($AppointmentServicesData['start_time']));
    			$StartTimeNew = date('Y-m-d',strtotime($AppointmentServicesData['appointment_date'])).' '.date('H:i:s',strtotime($AppointmentServicesData['start_time']));
    			
    			$EndTime    = date('Y-m-d',strtotime($AppointmentServicesData['appointment_date'])).'T'.date('H:i',strtotime($AppointmentServicesData['end_time'])); 
    			$EndTimeNew = date('Y-m-d',strtotime($AppointmentServicesData['appointment_date'])).' '.date('H:i:s',strtotime($AppointmentServicesData['end_time'])); 
    			
    			$servicePrices = ServicesPrice::select('services_price.pricing_name','services.service_name')->leftJoin('services','services.id','=','services_price.service_id')->where('services_price.id',$AppointmentServicesData['service_price_id'])->orderBy('services_price.id', 'asc')->get()->first();
    				
    			$serviceName = '';
    			if(!empty($servicePrices)){
    				$serviceName = $servicePrices->service_name.' - '.$servicePrices->pricing_name;
    			} else {
    				$serviceName = 'N/A';
    			}
    			
    			if($AppointmentServicesData['client_id'] == 0){
    				$ClientName = 'Walk-In';
    			} else {
    				$ClientInfo = Clients::getClientbyID($AppointmentServicesData['client_id']);	
    				if(!empty($ClientInfo)){
    					$ClientName = $ClientInfo->firstname.' '.$ClientInfo->lastname;	
    				} else {
    					$ClientName = 'Walk-In';
    				}
    			}
				
				$staffColor = Staff::select('id','appointment_color')->where('staff_user_id',$AppointmentServicesData['staff_user_id'])->first();
				
				$appointmentColor = !empty($staffColor) ? $staffColor->appointment_color : "#a5dff8";

    			$tempAppEvents = [];
    			$tempAppEvents['title']                  = $ClientName.' | '.$serviceName; 
    			$tempAppEvents['start']                  = $StartTime; 
    			$tempAppEvents['start_time']             = $StartTimeNew; 
    			$tempAppEvents['end']                    = $EndTime;
    			$tempAppEvents['end_time']               = $EndTimeNew;
    			$tempAppEvents['allDay']                 = false; 
    			$tempAppEvents['resources']              = $AppointmentServicesData['staff_user_id']; 
    			$tempAppEvents['appointment_id']         = $AppointmentServicesData['appointment_id']; 
    			$tempAppEvents['appointment_id_enc']     = Crypt::encryptString($AppointmentServicesData['appointment_id']); 
    			$tempAppEvents['appointment_service_id'] = $AppointmentServicesData['id']; 
    			$tempAppEvents['color']     = $appointmentColor;
    			$tempAppEvents['textColor'] = '#000';
    			array_push($appointmentEvents,$tempAppEvents);
    		}
    	}
    	
    	// get all blocked time
    	$StaffBlockedTime = StaffBlockedTime::select('staff_blocked_time.*','users.first_name','users.last_name', 'staff.id as staff_id')->leftjoin('users','users.id','=','staff_blocked_time.staff_user_id')->leftJoin('staff','staff.staff_user_id','=','users.id')->where('staff_blocked_time.user_id', $AdminId)->whereBetween('staff_blocked_time.date', [date('Y-m-d', strtotime($request->start)), date('Y-m-d', strtotime($request->end))])->orderBy('staff_blocked_time.created_at', 'asc')->get()->toArray();

    	if(!empty($StaffBlockedTime))
    	{
    		foreach($StaffBlockedTime as $StaffBlockedTimeData)
    		{
    			$StartTime    = date('Y-m-d',strtotime($StaffBlockedTimeData['date'])).'T'.date('H:i',strtotime($StaffBlockedTimeData['start_time']));
    			$StartTimeNew = date('Y-m-d',strtotime($StaffBlockedTimeData['date'])).' '.date('H:i:s',strtotime($StaffBlockedTimeData['start_time']));
    			
    			$EndTime    = date('Y-m-d',strtotime($StaffBlockedTimeData['date'])).'T'.date('H:i',strtotime($StaffBlockedTimeData['end_time'])); 
    			$EndTimeNew = date('Y-m-d',strtotime($StaffBlockedTimeData['date'])).' '.date('H:i:s',strtotime($StaffBlockedTimeData['end_time'])); 
    			
    			$serviceName = 'Blocked Time';
    			
				$BlockedTimeDescription = !empty($StaffBlockedTimeData['description']) ? ' | '.$StaffBlockedTimeData['description'] : '';
    			
    			$tempAppEvents = [];
    			$tempAppEvents['title']                  = $serviceName.$BlockedTimeDescription; 
    			$tempAppEvents['start']                  = $StartTime; 
    			$tempAppEvents['start_time']             = $StartTimeNew; 
    			$tempAppEvents['end']                    = $EndTime;
    			$tempAppEvents['end_time']               = $EndTimeNew;
    			$tempAppEvents['allDay']                 = false; 
    			$tempAppEvents['resources']              = $StaffBlockedTimeData['staff_user_id']; 
    			$tempAppEvents['appointment_id']         = NULL; 
    			$tempAppEvents['appointment_service_id'] = $StaffBlockedTimeData['id']; 
    			
    			$tempAppEvents['staff_blocked_time_id_enc'] = Crypt::encryptString($StaffBlockedTimeData['id']); 
    			$tempAppEvents['staff_user_id']     		= $StaffBlockedTimeData['staff_user_id']; 
    			$tempAppEvents['allow_online_booking']    	= $StaffBlockedTimeData['allow_online_booking']; 
    			$tempAppEvents['description']     			= $StaffBlockedTimeData['description']; 
    			$tempAppEvents['color']     = '#a4adba';
    			$tempAppEvents['textColor'] = '#000';
    			array_push($appointmentEvents,$tempAppEvents);
    		}
    	}
    	
    	echo json_encode($appointmentEvents);
    }
	
	public function updateAppointmentService(Request $request)
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
			
			$appointment_service_id = ($request->appointment_service_id) ? $request->appointment_service_id : '';
			$staff_user_id          = ($request->staff_user_id) ? $request->staff_user_id : '';
			$start_date_time        = ($request->start_date_time) ? $request->start_date_time : '';
			$end_date_time          = ($request->end_date_time) ? $request->end_date_time : '';
			
			$AppointmentServices = AppointmentServices::find($appointment_service_id);   
			
			if(!empty($AppointmentServices))
			{
				$PrevStartTime    = strtotime($AppointmentServices->start_time);
				$CurrentStartTime = strtotime(date("H:i:s",strtotime($start_date_time)));
				
				if($CurrentStartTime < $PrevStartTime){
					$data["status"]  = 'confirm';
					$data["appointment_service_id"] = $appointment_service_id;
					$data["staff_user_id"]          = $staff_user_id;
					$data["start_date_time"]        = $start_date_time;
					$data["end_date_time"]          = $end_date_time;
					return JsonReturn::success($data);
				}
				
				$datetime1 = strtotime($start_date_time);
				$datetime2 = strtotime($end_date_time);
				$interval  = abs($datetime2 - $datetime1);
				$duration  = round($interval / 60);
				
				$AppointmentServices->staff_user_id    = $staff_user_id;	
				$AppointmentServices->appointment_date = date("Y-m-d",strtotime($start_date_time));	
				$AppointmentServices->start_time       = date("H:i:s",strtotime($start_date_time));	
				$AppointmentServices->end_time         = date("H:i:s",strtotime($end_date_time));	
				$AppointmentServices->duration         = $duration;	
				$AppointmentServices->updated_at       = date("Y-m-d H:i:s");	
				if($AppointmentServices->save()){
					$data["status"] = true;
					$data["message"] = 'Appointment has been updated succesfully.';
				} else {
					$data["status"] = false;
					$data["message"] = 'Something went wrong!';
				}
			}
            return JsonReturn::success($data);
        }
	}
	
	public function updateAppointmentConfirmation(Request $request)
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
			
			$appointment_service_id = ($request->change_appointment_service_id) ? $request->change_appointment_service_id : '';
			$staff_user_id          = ($request->change_staff_user_id) ? $request->change_staff_user_id : '';
			$start_date_time        = ($request->change_start_date_time) ? $request->change_start_date_time : '';
			$end_date_time          = ($request->change_end_date_time) ? $request->change_end_date_time : '';
			
			$AppointmentServices = AppointmentServices::find($appointment_service_id);   
			
			if(!empty($AppointmentServices))
			{
				$datetime1 = strtotime($start_date_time);
				$datetime2 = strtotime($end_date_time);
				$interval  = abs($datetime2 - $datetime1);
				$duration  = round($interval / 60);
				
				$AppointmentServices->staff_user_id    = $staff_user_id;	
				$AppointmentServices->appointment_date = date("Y-m-d",strtotime($start_date_time));	
				$AppointmentServices->start_time       = date("H:i:s",strtotime($start_date_time));	
				$AppointmentServices->end_time         = date("H:i:s",strtotime($end_date_time));	
				$AppointmentServices->duration         = $duration;	
				$AppointmentServices->updated_at       = date("Y-m-d H:i:s");	
				if($AppointmentServices->save()){
					$data["status"] = true;
					$data["message"] = 'Appointment has been updated succesfully.';
				} else {
					$data["status"] = false;
					$data["message"] = 'Something went wrong!';
				}
			}
            return JsonReturn::success($data);
        }
	}
	
	public function getStaffAppointment(Request $request){
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
			
			$staff_user_id = ($request->staff_user_id) ? $request->staff_user_id : '';
			
			if($staff_user_id != 'working' && $staff_user_id != 'all-staff')
			{
				$userDetails = User::getUserbyID($staff_user_id);
				if(!empty($userDetails))
				{
					// get all appointment services
					$AppointmentServices = AppointmentServices::select('appointment_services.*','users.first_name','users.last_name','appointments.client_id')->leftJoin('appointments','appointments.id','=','appointment_services.appointment_id')->leftJoin('users','users.id','=','appointment_services.staff_user_id')->where('appointment_services.staff_user_id', $staff_user_id)->where('appointment_services.user_id', $AdminId)->where('appointments.is_cancelled', '0')->orderBy('appointment_services.created_at', 'asc')->get()->toArray();
					
					$appointmentEvents = array();
					if(!empty($AppointmentServices)){
						foreach($AppointmentServices as $AppointmentServicesData){
							$StartTime = date('Y-m-d',strtotime($AppointmentServicesData['appointment_date'])).'T'.date('H:i',strtotime($AppointmentServicesData['start_time']));
							$EndTime   = date('Y-m-d',strtotime($AppointmentServicesData['appointment_date'])).'T'.date('H:i',strtotime($AppointmentServicesData['end_time'])); 
							
							$servicePrices = ServicesPrice::select('services_price.pricing_name','services.service_name')->leftJoin('services','services.id','=','services_price.service_id')->where('services_price.id',$AppointmentServicesData['service_price_id'])->orderBy('services_price.id', 'asc')->get()->first();
								
							$serviceName = '';
							if(!empty($servicePrices)){
								$serviceName = $servicePrices->service_name.' - '.$servicePrices->pricing_name;
							} else {
								$serviceName = 'N/A';
							}
							
							if($AppointmentServicesData['client_id'] == 0){
								$ClientName = 'Walk-In';
							} else {
								$ClientInfo = Clients::getClientbyID($AppointmentServicesData['client_id']);	
								if(!empty($ClientInfo)){
									$ClientName = $ClientInfo->firstname.' '.$ClientInfo->lastname;	
								} else {
									$ClientName = 'Walk-In';
								}
							}
							
							$tempAppEvents['title']                  = $ClientName.' | '.$serviceName; 
							$tempAppEvents['start']                  = $StartTime; 
							$tempAppEvents['end']                    = $EndTime;
							$tempAppEvents['allDay']                 = false; 
							$tempAppEvents['resources']              = $AppointmentServicesData['staff_user_id']; 
							$tempAppEvents['appointment_id']         = $AppointmentServicesData['appointment_id']; 
							$tempAppEvents['appointment_id_enc']     = Crypt::encryptString($AppointmentServicesData['appointment_id']); 
							$tempAppEvents['appointment_service_id'] = $AppointmentServicesData['id']; 
							array_push($appointmentEvents,$tempAppEvents);
						}
					}
					
					$data["status"] = true;
					$data["events"] = $appointmentEvents;
				}
				else
				{
					$data["status"] = false;
					$data["message"] = 'Something went wrong!';
				}	
			} else {
				if($staff_user_id == 'all-staff')
				{
					// get all appointment services
					$AppointmentServices = AppointmentServices::select('appointment_services.*','users.first_name','users.last_name','appointments.client_id')->leftJoin('appointments','appointments.id','=','appointment_services.appointment_id')->leftJoin('users','users.id','=','appointment_services.staff_user_id')->where('appointment_services.user_id', $AdminId)->where('appointments.is_cancelled', '0')->orderBy('appointment_services.created_at', 'asc')->get()->toArray();
					
					$appointmentEvents = array();
					if(!empty($AppointmentServices)){
						foreach($AppointmentServices as $AppointmentServicesData){
							$StartTime = date('Y-m-d',strtotime($AppointmentServicesData['appointment_date'])).'T'.date('H:i',strtotime($AppointmentServicesData['start_time']));
							$EndTime   = date('Y-m-d',strtotime($AppointmentServicesData['appointment_date'])).'T'.date('H:i',strtotime($AppointmentServicesData['end_time'])); 
							
							$servicePrices = ServicesPrice::select('services_price.pricing_name','services.service_name')->leftJoin('services','services.id','=','services_price.service_id')->where('services_price.id',$AppointmentServicesData['service_price_id'])->orderBy('services_price.id', 'asc')->get()->first();
								
							$serviceName = '';
							if(!empty($servicePrices)){
								$serviceName = $servicePrices->service_name.' - '.$servicePrices->pricing_name;
							} else {
								$serviceName = 'N/A';
							}
							
							if($AppointmentServicesData['client_id'] == 0){
								$ClientName = 'Walk-In';
							} else {
								$ClientInfo = Clients::getClientbyID($AppointmentServicesData['client_id']);	
								if(!empty($ClientInfo)){
									$ClientName = $ClientInfo->firstname.' '.$ClientInfo->lastname;	
								} else {
									$ClientName = 'Walk-In';
								}
							}
							
							$tempAppEvents['title']                  = $ClientName.' | '.$serviceName; 
							$tempAppEvents['start']                  = $StartTime; 
							$tempAppEvents['end']                    = $EndTime;
							$tempAppEvents['allDay']                 = false; 
							$tempAppEvents['resources']              = $AppointmentServicesData['staff_user_id']; 
							$tempAppEvents['appointment_id']         = $AppointmentServicesData['appointment_id']; 
							$tempAppEvents['appointment_id_enc']     = Crypt::encryptString($AppointmentServicesData['appointment_id']); 
							$tempAppEvents['appointment_service_id'] = $AppointmentServicesData['id']; 
							array_push($appointmentEvents,$tempAppEvents);
						}
					}
					
					$data["status"] = true;
					$data["events"] = $appointmentEvents;	
				} else {
					$appointmentEvents = array();
					$data["status"] = true;
					$data["events"] = $appointmentEvents;	
				}
			}
            return JsonReturn::success($data);
        }
	}

    public function addStaffBlockedTime(Request $request)
	{
        if($request->ajax())
        {
            $rules=
            [
                'start_time' => 'required',
                'end_time' => 'required',
                'date' => 'required',
                'staff_user_id' => 'required',
            ];
            $input = $request->only(
                'start_time',
                'end_time',
                'date',
                'staff_user_id',
            );
            $validator = Validator::make($input, $rules);
            if ($validator->fails()) 
            {
    			return JsonReturn::error($validator->messages());
            }
			
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
			
            $date = Carbon\Carbon::parse($request->date)->format('Y-m-d');

            StaffBlockedTime::create([
                'user_id' => $AdminId,
                'staff_user_id' => $request->staff_user_id,
                'date' => $date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'allow_online_booking' => isset($request->allow_online_booking) ? $request->allow_online_booking : 0,
                'description' => !empty($request->description) ? $request->description : NULL,
            ]);
            
            $data["status"] = true;
            $data["message"] = "Blocked time created.";
			return JsonReturn::success($data);	
        }
    }

    public function updateStaffBlockedTime(Request $request)
	{
        if($request->ajax())
        {
            $rules=
            [
                'start_time' => 'required',
                'end_time' => 'required',
                'date' => 'required',
                'staff_user_id' => 'required',
            ];
            $input = $request->only(
                'start_time',
                'end_time',
                'date',
                'staff_user_id',
            );
            $validator = Validator::make($input, $rules);
            if ($validator->fails()) 
            {
    			return JsonReturn::error($validator->messages());
            }

            if(empty($request->staff_blocked_time_id)) {
	            return JsonReturn::error('Invalid request. Please reload and try again.');	
            }

            $staffBlockedTimeId = Crypt::decryptString($request->staff_blocked_time_id);
			
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
			
            $date = Carbon\Carbon::parse($request->date)->format('Y-m-d');

            $StaffBlockedTime = StaffBlockedTime::find($staffBlockedTimeId);

            if(!empty($StaffBlockedTime)) {
            	
            	$StaffBlockedTime->staff_user_id = $request->staff_user_id;
            	$StaffBlockedTime->date = $date;
            	$StaffBlockedTime->start_time = $request->start_time;
            	$StaffBlockedTime->end_time = $request->end_time;
            	$StaffBlockedTime->allow_online_booking = isset($request->allow_online_booking) ? $request->allow_online_booking : 0;
            	$StaffBlockedTime->description = !empty($request->description) ? $request->description : NULL;

            	$StaffBlockedTime->save();
            } else {
	            
	            StaffBlockedTime::create([
	                'user_id' => $AdminId,
	                'staff_user_id' => $request->staff_user_id,
	                'date' => $date,
	                'start_time' => $request->start_time,
	                'end_time' => $request->end_time,
	                'allow_online_booking' => isset($request->allow_online_booking) ? $request->allow_online_booking : 0,
	                'description' => !empty($request->description) ? $request->description : NULL,
	            ]);
	        }
            
            $data["status"] = true;
            $data["message"] = "Blocked time updated.";
			return JsonReturn::success($data);	
        }
    }

	public function deleteStaffBlockedTime(Request $request)
	{
		$staffBlockedTimeId = Crypt::decryptString($request->staff_blocked_time_id);
		$res = StaffBlockedTime::find($staffBlockedTimeId)->delete();

		if ($res){
		  $data=[
		  'status'=>'1',
		  'msg'=>'success'
		];
		}else{
		  $data=[
		  'status'=>'0',
		  'msg'=>'fail'
		];
	}

	$data["status"] = true;
	$data["message"] = "Blocked time Deleted.";
	return JsonReturn::success($data);	
	  

    }


    public function rescheduleAppointment(Request $request)
    {
        if($request->ajax())
        {
            $rules=
            [
                'appointment_id' => 'required',
                'start' => 'required',
                'date' => 'required',
                'staff_user_id' => 'required',
            ];
            $input = $request->only(
                'appointment_id',
                'start',
                'date',
                'staff_user_id',
            );
            $validator = Validator::make($input, $rules);
            if ($validator->fails()) 
            {
    			// return JsonReturn::error($validator->messages());
    			return JsonReturn::error('Invalid request. Please try again.');
            }

            $appointmentId = Crypt::decryptString($request->appointment_id);
			
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
			
            $date = Carbon\Carbon::parse($request->date)->format('Y-m-d');

            $Appointment = Appointments::find($appointmentId);

            if(empty($Appointment)) {
            		            
	            $data["status"] = true;
	            $data["message"] = "Invalid request. Please try again.";
				return JsonReturn::success($data);	
            }

            $Appointment->staff_user_id = $request->staff_user_id;
            $Appointment->appointment_date = $request->date;
        	$Appointment->save();

        	$AppointmentServices = AppointmentServices::where('appointment_id', $appointmentId)
        							->orderBy('start_time', 'ASC')
        							->get();

        	if($AppointmentServices->isNotEmpty()) {
        		foreach($AppointmentServices as $key => $value) {
        			if(!isset($timeDifference)) {
        				$timeDifference = strtotime($request->start) - strtotime($value->start_time);
        			}

        			AppointmentServices::where('id', $value->id)
        			->update([
        				'appointment_date' => date('Y-m-d', strtotime($request->date)),
        				'start_time' => date('H:i:s', strtotime($value->start_time.' +'.$timeDifference.' seconds')),
        				'end_time' => date('H:i:s', strtotime($value->end_time.' +'.$timeDifference.' seconds')),
        				'staff_user_id' => $request->staff_user_id
        			]);
        		}
        	}
            
            $data["status"] = true;
            $data["message"] = "Appointment rescheduled.";
			return JsonReturn::success($data);	
        }
    }

}
