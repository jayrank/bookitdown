<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\JsonReturn;
use App\Models\User;
use App\Models\Staff;
use App\Models\Clients;
use App\Models\Location;	
use App\Models\Permission;
use App\Models\Country;
use App\Models\Services;
use App\Models\ServicesPrice;
use App\Models\ServiceCategory;
use App\Models\StaffLocations;
use App\Models\Appointments;
use App\Models\AppointmentServices;
use App\Models\Invoice;
use App\Models\InvoiceItems;
use App\Models\InvoiceTemplate;
use App\Models\InventoryProducts;
use App\Models\InventoryOrderLogs;
use App\Models\PaidPlan;
use App\Models\SoldPaidPlan;
use App\Models\Voucher;
use App\Models\SoldVoucher;
use App\Models\Taxes;
use App\Models\InvoiceTaxes;
use App\Models\StaffTip;
use App\Models\NewAppointment;
use App\Models\CancellationReasons;
use App\Models\noShowNotification;
use App\Models\paymentType;
use App\Models\EmailLog;
use App\Models\Online_setting;
use App\Mail\InvoiceMail;
use App\Mail\VoucherEmail;
use App\Mail\newAppointments;
use App\Mail\cancelAppointment;
use App\Mail\noShowAppointments;
use DataTables;
use Session;
use PDF;
use Mail;
use Crypt;
use DB;
use DateTime;
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
		$this->middleware('auth');

		$this->notificationRepositorie = $notificationRepositorie;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function createAppointment($locationId = null)
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
		
		// Get all services
		$Services = ServicesPrice::select('services_price.id as service_price_id','services_price.duration','services_price.price_type','services_price.price','services_price.special_price','services_price.pricing_name','services_price.lowest_price','services_price.staff_prices','services_price.is_staff_price','services.id as service_id','services.service_name','services.extra_time','services.is_extra_time','services.extra_time_duration')->join('services','services.id','=','services_price.service_id')->where(['services.user_id'=>$AdminId])->orderBy('services_price.id', 'ASC')->get()->toArray();
		
		// Get all staff
		$Staff = Staff::select('staff.staff_user_id','users.first_name','users.last_name')->where(['user_id'=>$AdminId])->join('users','users.id','=','staff.staff_user_id')->orderBy('staff.id', 'ASC')->get()->toArray();
			
		// get all clients	
		$Clients = Clients::select('id','firstname','lastname','email','mo_country_code','mobileno')->where('is_deleted',0)->where('is_blocked',0)->where('user_id', $AdminId)->orderBy('id', 'desc')->limit(25)->get();	
			
		$TimeSlots = array();
		$ClockStartTime = date("00:00:00");
		
		$CurrentTime    = new DateTime(date("Y-m-d H:i:s"));
		$NearestTimeObj = $this->roundToNearestMinuteInterval($CurrentTime);
	
		$NearestTime = $NearestTimeObj->format("H:i:00");
		
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
		
        return view('appointments.newAppointment',compact('Services','Staff','TimeSlots','Clients','NearestTime','locationId'));
    }
	
	public function roundToNearestMinuteInterval(\DateTime $dateTime, $minuteInterval = 10){
		return $dateTime->setTime(
			$dateTime->format('H'),
			round($dateTime->format('i') / $minuteInterval) * $minuteInterval,
			0
		);
	}
	
	public function getStaffPriceDetails(Request $request){
		if ($request->ajax()){
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
			 
			$staffId        = ($request->staffId) ? $request->staffId : '';
			
			$serviceId = ($request->serviceId) ? $request->serviceId : '';
			
			// Get all services
			$Services = ServicesPrice::select('services_price.id as service_price_id','services_price.duration','services_price.price_type','services_price.price','services_price.special_price','services_price.pricing_name','services_price.lowest_price','services_price.staff_prices','services_price.is_staff_price','services.id as service_id','services.service_name','services.extra_time','services.is_extra_time','services.extra_time_duration')->join('services','services.id','=','services_price.service_id')->where(['services.user_id'=>$AdminId])->where(['services_price.id'=>$serviceId])->orderBy('services_price.id', 'ASC')->get()->first()->toArray();
			
			if($Services['is_staff_price'] == 1){
				$staffPrices = json_decode($Services['staff_prices'], true);
				
				if(!empty($staffPrices)) {
					foreach($staffPrices as $staffPriceData) {
						if($staffPriceData['staff_id'] == $staffId) {
							$data['timeDuration']  = ($staffPriceData['staff_duration']) ? ($staffPriceData['staff_duration'] * 60) : 0;
							$data['price']         = ($staffPriceData['staff_price']) ? $staffPriceData['staff_price'] : 0;
							$data['special_price'] = ($staffPriceData['staff_special_price']) ? $staffPriceData['staff_special_price'] : 0;
							$data['lowest_price']  = 0;
							break;
						} else {
							$data['timeDuration']  = ($Services['duration']) ? ($Services['duration'] * 60) : 0;
							$data['price']         = ($Services['price']) ? $Services['price'] : 0;
							$data['special_price'] = ($Services['special_price']) ? $Services['special_price'] : 0;
							$data['lowest_price']  = ($Services['lowest_price']) ? $Services['lowest_price'] : 0;
						}
					}
				} else {
					$data['timeDuration']  = ($Services['duration']) ? ($Services['duration'] * 60) : 0;
					$data['price']         = ($Services['price']) ? $Services['price'] : 0;
					$data['special_price'] = ($Services['special_price']) ? $Services['special_price'] : 0;
					$data['lowest_price']  = ($Services['lowest_price']) ? $Services['lowest_price'] : 0;
				}	
			} else {
				$data['timeDuration']  = ($Services['duration']) ? ($Services['duration'] * 60) : 0;
				$data['price']         = ($Services['price']) ? $Services['price'] : 0;
				$data['special_price'] = ($Services['special_price']) ? $Services['special_price'] : 0;
				$data['lowest_price']  = ($Services['lowest_price']) ? $Services['lowest_price'] : 0;
			}
			
			return JsonReturn::success($data);
        }
	}
	
	public function createNewAppointment(Request $request){
		$status = 0;
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
			
			$appointment_date  = ($request->appointment_date) ? date("Y-m-d",strtotime($request->appointment_date)) : null;
			$appointment_notes = ($request->appointment_notes) ? $request->appointment_notes : '';
			$client_id         = ($request->clientId) ? $request->clientId : 0;
			$location_id       = ($request->location_id) ? $request->location_id : 0;
			
			$start_time        = ($request->start_time) ? $request->start_time : array();
			$service_id        = ($request->service_id) ? $request->service_id : array();
			$duration          = ($request->duration) ? $request->duration : array();
			$staff_user_id     = ($request->staff_user_id) ? $request->staff_user_id : array();
			
			$isExtraTime       = ($request->isExtraTime) ? $request->isExtraTime : array();
			$extraTimeType     = ($request->extraTimeType) ? $request->extraTimeType : array();
			$timeExtraDuration = ($request->timeExtraDuration) ? $request->timeExtraDuration : array();
			$total_price       = 0;
			
			$special_price     =  ($request->special_price) ? $request->special_price : array();
			
			$repeatAppointmentFrequency = ($request->repeatAppointmentFrequency) ? $request->repeatAppointmentFrequency : '';
			$repeatAppointmentCount     = ($request->repeatAppointmentCount) ? $request->repeatAppointmentCount : '';
			$repeatAppointmentDate      = ($request->repeatAppointmentDate) ? date("Y-m-d",strtotime($request->repeatAppointmentDate)) : '';
			
			$saveAppointment   = ($request->saveAppointment) ? $request->saveAppointment : '';
			$expressCheckout   = ($request->expressCheckout) ? $request->expressCheckout : '';
			
			$firstAppointmentId = 0;
			
			if($location_id == 0){
				$FirstLocation = Location::select('id')->where(['user_id'=>$AdminId])->orderBy('id', 'ASC')->get()->first()->toArray();
				if(!empty($FirstLocation)){
					$location_id = $FirstLocation['id'];
				}
			}
			
			if(!empty($service_id))
			{
				if($repeatAppointmentFrequency != '' && $repeatAppointmentCount != '')
				{
					$explodeRepeatFrequency = explode(":",$repeatAppointmentFrequency);
				
					$typeOfFrequency = $explodeRepeatFrequency[0];
					$numberOfRepeat  = $explodeRepeatFrequency[1];
					
					$nextAppointmentDate = '';
					
					$addString = '';
					if($typeOfFrequency == 'daily'){
						$addString = 'days';	
					} else if($typeOfFrequency == 'weekly') {
						$addString = 'week';
					} else if($typeOfFrequency == 'monthly') {
						$addString = 'month';
					}
					 
					if($repeatAppointmentCount == 'specific_date'){
						$startDate = strtotime($appointment_date);
						$endDate   = strtotime($repeatAppointmentDate);
						$datediff  = $startDate - $endDate;

						$totalRepeat = abs(round($datediff / (60 * 60 * 24)));
						
						$prevAppointmentDate = '';
						$prevAppointmentDate = $appointment_date;
						
						$everyDays = floor($totalRepeat / $numberOfRepeat);
						
						for($i = 0;$i <= $everyDays;$i++){
							$total_price       = 0;
							
							if($i == 0){
								$nextAppointmentDate = $prevAppointmentDate;
							} else {
								$nextAppointmentDate = date("Y-m-d",strtotime('+'.$numberOfRepeat.' '.$addString,strtotime($prevAppointmentDate)));
							}
							$prevAppointmentDate = $nextAppointmentDate;	
							
							// create appointment on repeat mode
							$Appointments = Appointments::create([
								'user_id'            => $AdminId,
								'staff_user_id'      => $UserId,
								'location_id'        => $location_id,
								'appointment_date'   => $nextAppointmentDate,
								'appointment_notes'  => $appointment_notes,
								'client_id'          => $client_id,
								'appointment_status' => 0,
								'is_paid'            => 0,
								'created_by'         => $UserId,
								'created_at'         => date("Y-m-d H:i:s"),
								'updated_at'         => date("Y-m-d H:i:s")
							]);
							$AppointmentId = $Appointments->id;
							
							if($i == 0){
								$firstAppointmentId = Crypt::encryptString($AppointmentId);
							}
							
							if($AppointmentId)
							{
								foreach($service_id as $mainKey => $service_ids)
								{
									$durationval = (isset($duration[$mainKey])) ? $duration[$mainKey] : 0;
									$staffuserid = (isset($staff_user_id[$mainKey])) ? $staff_user_id[$mainKey] : 0;
									
									$is_extra_time       = (isset($isExtraTime[$mainKey])) ? $isExtraTime[$mainKey] : 0;
									$extra_time          = (isset($extraTimeType[$mainKey])) ? $extraTimeType[$mainKey] : 0;
									$extra_time_duration = (isset($timeExtraDuration[$mainKey])) ? $timeExtraDuration[$mainKey] : 0;
									
									$MainDuration   = $durationval;
									$MinuteDuration = $durationval / 60;
									if($is_extra_time == 1){
										$ExtraDuration = $extra_time_duration * 60;	
										$MainDuration = $MainDuration + $ExtraDuration;
									}
									
									$start_time_val = (isset($start_time[$mainKey])) ? date("H:i:s",strtotime($start_time[$mainKey])) : '';
									$end_time       = date("H:i:s",strtotime('+'.$MainDuration.' second',strtotime($start_time_val)));
									
									$price_val = (isset($special_price[$mainKey])) ? $special_price[$mainKey] : 0;
									
									if($service_ids != '' && $durationval != '' && $staffuserid != '' && $start_time_val != '' && $price_val != ''){
										$total_price = $total_price + floatval($price_val);
											
										$AppointmentServices = AppointmentServices::create([
											'appointment_id'      => $AppointmentId,
											'user_id'             => $UserId,
											'appointment_date'    => $nextAppointmentDate,
											'start_time'          => $start_time_val,
											'end_time'            => $end_time,
											'service_price_id'    => $service_ids,
											'duration'            => $MinuteDuration,
											'is_extra_time'       => $is_extra_time,
											'extra_time'          => $extra_time,
											'extra_time_duration' => $extra_time_duration,
											'staff_user_id'       => $staffuserid,
											'special_price'       => $price_val,
											'created_at'          => date("Y-m-d H:i:s"),
											'updated_at'          => date("Y-m-d H:i:s")
										]);
										$AppointmentServicesId = $AppointmentServices->id;	
									}
								}
								
								$AppointmentFind = Appointments::find($AppointmentId);   
								if(!empty($AppointmentFind)){
									$AppointmentFind->total_amount = $total_price;
									$AppointmentFind->final_total  = $total_price;
									$AppointmentFind->save();
								}	
								
								if($saveAppointment != '' && $expressCheckout == ''){
									$redirectUrl = url('partners//calander');
								} else if($saveAppointment == '' && $expressCheckout != ''){
									$redirectUrl = url('partners/appointments/checkout/'.$firstAppointmentId);
								} else {
									$redirectUrl = url('partners/calander');
								}
								
								Session::flash('message', 'Appointment has been created succesfully.');
								$data["status"] = true;
								$data["message"] = "Appointment has been created succesfully.";	
								$data["redirect"] = $redirectUrl;
							} else {
								$data["status"] = false;
								$data["message"] = "Something went wrong!";	
							}
						}
					} else if($repeatAppointmentCount == 'ongoing'){
						$startDate = strtotime($appointment_date);
						$endDate   = strtotime(date("Y-m-d",strtotime('+1 year',$startDate)));
						$datediff  = $startDate - $endDate;

						$totalRepeat = abs(round($datediff / (60 * 60 * 24)));
						
						$prevAppointmentDate = '';
						$prevAppointmentDate = $appointment_date;
						
						$everyDays = floor($totalRepeat / $numberOfRepeat);
						
						for($i = 0;$i <= $everyDays;$i++){
							$total_price       = 0;
							
							if($i == 0){
								$nextAppointmentDate = $prevAppointmentDate;
							} else {
								$nextAppointmentDate = date("Y-m-d",strtotime('+'.$numberOfRepeat.' '.$addString,strtotime($prevAppointmentDate)));
							}	
							$prevAppointmentDate = $nextAppointmentDate;
							
							// create appointment on repeat mode
							$Appointments = Appointments::create([
								'user_id'            => $AdminId,
								'staff_user_id'      => $UserId,
								'location_id'        => $location_id,
								'appointment_date'   => $nextAppointmentDate,
								'appointment_notes'  => $appointment_notes,
								'client_id'          => $client_id,
								'appointment_status' => 0,
								'is_paid'            => 0,
								'created_by'         => $UserId,
								'created_at'         => date("Y-m-d H:i:s"),
								'updated_at'         => date("Y-m-d H:i:s")
							]);
							$AppointmentId = $Appointments->id;
							
							if($i == 0){
								$firstAppointmentId = Crypt::encryptString($AppointmentId);
							}
							
							if($AppointmentId)
							{
								foreach($service_id as $mainKey => $service_ids)
								{
									$durationval = (isset($duration[$mainKey])) ? $duration[$mainKey] : 0;
									$staffuserid = (isset($staff_user_id[$mainKey])) ? $staff_user_id[$mainKey] : 0;
									
									$is_extra_time       = (isset($isExtraTime[$mainKey])) ? $isExtraTime[$mainKey] : 0;
									$extra_time          = (isset($extraTimeType[$mainKey])) ? $extraTimeType[$mainKey] : 0;
									$extra_time_duration = (isset($timeExtraDuration[$mainKey])) ? $timeExtraDuration[$mainKey] : 0;
									
									$MainDuration   = $durationval;
									$MinuteDuration = $durationval / 60;
									if($is_extra_time == 1){
										$ExtraDuration = $extra_time_duration * 60;	
										$MainDuration = $MainDuration + $ExtraDuration;
									}
									
									$start_time_val = (isset($start_time[$mainKey])) ? date("H:i:s",strtotime($start_time[$mainKey])) : '';
									$end_time       = date("H:i:s",strtotime('+'.$MainDuration.' second',strtotime($start_time_val)));
									
									$price_val = (isset($special_price[$mainKey])) ? $special_price[$mainKey] : 0;
									
									if($service_ids != '' && $durationval != '' && $staffuserid != '' && $start_time_val != '' && $price_val != ''){
										$total_price = $total_price + floatval($price_val);
											
										$AppointmentServices = AppointmentServices::create([
											'appointment_id'      => $AppointmentId,
											'user_id'             => $UserId,
											'appointment_date'    => $nextAppointmentDate,
											'start_time'          => $start_time_val,
											'end_time'            => $end_time,
											'service_price_id'    => $service_ids,
											'duration'            => $MinuteDuration,
											'is_extra_time'       => $is_extra_time,
											'extra_time'          => $extra_time,
											'extra_time_duration' => $extra_time_duration,
											'staff_user_id'       => $staffuserid,
											'special_price'       => $price_val,
											'created_at'          => date("Y-m-d H:i:s"),
											'updated_at'          => date("Y-m-d H:i:s")
										]);
										$AppointmentServicesId = $AppointmentServices->id;	
									}
								}
								
								$AppointmentFind = Appointments::find($AppointmentId);   
								if(!empty($AppointmentFind)){
									$AppointmentFind->total_amount = $total_price;
									$AppointmentFind->final_total  = $total_price;
									$AppointmentFind->save();
								}	
								
								if($saveAppointment != '' && $expressCheckout == ''){
									$redirectUrl = url('partners/calander');
								} else if($saveAppointment == '' && $expressCheckout != ''){
									$redirectUrl = url('partners/appointments/checkout/'.$firstAppointmentId);
								} else {
									$redirectUrl = url('partners/calander');
								}
								
								Session::flash('message', 'Appointment has been created succesfully.');
								$data["status"] = true;
								$data["message"] = "Appointment has been created succesfully.";	
								$data["redirect"] = $redirectUrl;
							} else {
								$data["status"] = false;
								$data["message"] = "Something went wrong!";	
							}
						}
					} else {
						$explodeRepeatCount = explode(":",$repeatAppointmentCount);
						$totalRepeat = ($explodeRepeatCount[1]) ? $explodeRepeatCount[1] : 0;
						
						$prevAppointmentDate = '';
						$prevAppointmentDate = $appointment_date;
						
						for($i = 0;$i < $totalRepeat;$i++){
							$total_price       = 0;
							
							if($i == 0){
								$nextAppointmentDate = $prevAppointmentDate;
							} else {
								$nextAppointmentDate = date("Y-m-d",strtotime('+'.$numberOfRepeat.' '.$addString,strtotime($prevAppointmentDate)));	
							}
							$prevAppointmentDate = $nextAppointmentDate;
							
							// create appointment on repeat mode
							$Appointments = Appointments::create([
								'user_id'            => $AdminId,
								'staff_user_id'      => $UserId,
								'location_id'        => $location_id,
								'appointment_date'   => $nextAppointmentDate,
								'appointment_notes'  => $appointment_notes,
								'client_id'          => $client_id,
								'appointment_status' => 0,
								'is_paid'            => 0,
								'created_by'         => $UserId,
								'created_at'         => date("Y-m-d H:i:s"),
								'updated_at'         => date("Y-m-d H:i:s")
							]);
							$AppointmentId = $Appointments->id;
							
							if($i == 0){
								$firstAppointmentId = Crypt::encryptString($AppointmentId);
							}
							
							if($AppointmentId)
							{
								foreach($service_id as $mainKey => $service_ids)
								{
									$durationval = (isset($duration[$mainKey])) ? $duration[$mainKey] : 0;
									$staffuserid = (isset($staff_user_id[$mainKey])) ? $staff_user_id[$mainKey] : 0;
									
									$is_extra_time       = (isset($isExtraTime[$mainKey])) ? $isExtraTime[$mainKey] : 0;
									$extra_time          = (isset($extraTimeType[$mainKey])) ? $extraTimeType[$mainKey] : 0;
									$extra_time_duration = (isset($timeExtraDuration[$mainKey])) ? $timeExtraDuration[$mainKey] : 0;
									
									$MainDuration   = $durationval;
									$MinuteDuration = $durationval / 60;
									if($is_extra_time == 1){
										$ExtraDuration = $extra_time_duration * 60;	
										$MainDuration = $MainDuration + $ExtraDuration;
									}
									
									$start_time_val = (isset($start_time[$mainKey])) ? date("H:i:s",strtotime($start_time[$mainKey])) : '';
									$end_time       = date("H:i:s",strtotime('+'.$MainDuration.' second',strtotime($start_time_val)));
									
									$price_val = (isset($special_price[$mainKey])) ? $special_price[$mainKey] : 0;
									
									if($service_ids != '' && $durationval != '' && $staffuserid != '' && $start_time_val != '' && $price_val != ''){
										$total_price = $total_price + floatval($price_val);
											
										$AppointmentServices = AppointmentServices::create([
											'appointment_id'      => $AppointmentId,
											'user_id'             => $UserId,
											'appointment_date'    => $nextAppointmentDate,
											'start_time'          => $start_time_val,
											'end_time'            => $end_time,
											'service_price_id'    => $service_ids,
											'duration'            => $MinuteDuration,
											'is_extra_time'       => $is_extra_time,
											'extra_time'          => $extra_time,
											'extra_time_duration' => $extra_time_duration,
											'staff_user_id'       => $staffuserid,
											'special_price'       => $price_val,
											'created_at'          => date("Y-m-d H:i:s"),
											'updated_at'          => date("Y-m-d H:i:s")
										]);
										$AppointmentServicesId = $AppointmentServices->id;	
									}
								}
								
								$AppointmentFind = Appointments::find($AppointmentId);   
								if(!empty($AppointmentFind)){
									$AppointmentFind->total_amount = $total_price;
									$AppointmentFind->final_total  = $total_price;
									$AppointmentFind->save();
								}	
								
								if($saveAppointment != '' && $expressCheckout == ''){
									$redirectUrl = url('partners/calander');
								} else if($saveAppointment == '' && $expressCheckout != ''){
									$redirectUrl = url('partners/appointments/checkout/'.$firstAppointmentId);
								} else {
									$redirectUrl = url('partners/calander');
								}
								
								Session::flash('message', 'Appointment has been created succesfully.');
								$data["status"] = true;
								$data["message"] = "Appointment has been created succesfully.";	
								$data["redirect"] = $redirectUrl;
							} else {
								$data["status"] = false;
								$data["message"] = "Something went wrong!";	
							}
						}
					}
				} 
				else 
				{
					$Appointments = Appointments::create([
						'user_id'            => $AdminId,
						'staff_user_id'      => $UserId,
						'location_id'        => $location_id,
						'appointment_date'   => $appointment_date,
						'appointment_notes'  => $appointment_notes,
						'client_id'          => $client_id,
						'appointment_status' => 0,
						'is_paid'            => 0,
						'created_by'         => $UserId,
						'created_at'         => date("Y-m-d H:i:s"),
						'updated_at'         => date("Y-m-d H:i:s")
					]);
					$AppointmentId = $Appointments->id;
					
					$firstAppointmentId = Crypt::encryptString($AppointmentId);
					
					if($AppointmentId){
						foreach($service_id as $mainKey => $service_ids){
							$durationval = (isset($duration[$mainKey])) ? $duration[$mainKey] : 0;
							$staffuserid = (isset($staff_user_id[$mainKey])) ? $staff_user_id[$mainKey] : 0;
							
							$is_extra_time       = (isset($isExtraTime[$mainKey])) ? $isExtraTime[$mainKey] : 0;
							$extra_time          = (isset($extraTimeType[$mainKey])) ? $extraTimeType[$mainKey] : 0;
							$extra_time_duration = (isset($timeExtraDuration[$mainKey])) ? $timeExtraDuration[$mainKey] : 0;
							
							$MainDuration   = $durationval;
							$MinuteDuration = $durationval / 60;
							if($is_extra_time == 1){
								$ExtraDuration = $extra_time_duration * 60;	
								$MainDuration = $MainDuration + $ExtraDuration;
							}
							
							$start_time_val = (isset($start_time[$mainKey])) ? date("H:i:s",strtotime($start_time[$mainKey])) : '';
							$end_time       = date("H:i:s",strtotime('+'.$MainDuration.' second',strtotime($start_time_val)));
							
							$price_val = (isset($special_price[$mainKey])) ? $special_price[$mainKey] : 0;
							
							if($service_ids != '' && $durationval != '' && $staffuserid != '' && $start_time_val != '' && $price_val != ''){
								$total_price = $total_price + floatval($price_val);
									
								$AppointmentServices = AppointmentServices::create([
									'appointment_id'      => $AppointmentId,
									'user_id'             => $UserId,
									'appointment_date'    => $appointment_date,
									'start_time'          => $start_time_val,
									'end_time'            => $end_time,
									'service_price_id'    => $service_ids,
									'duration'            => $MinuteDuration,
									'is_extra_time'       => $is_extra_time,
									'extra_time'          => $extra_time,
									'extra_time_duration' => $extra_time_duration,
									'staff_user_id'       => $staffuserid,
									'special_price'       => $price_val,
									'created_at'          => date("Y-m-d H:i:s"),
									'updated_at'          => date("Y-m-d H:i:s")
								]);
								$AppointmentServicesId = $AppointmentServices->id;	
							}
						}
						
						$AppointmentFind = Appointments::find($AppointmentId);   
						if(!empty($AppointmentFind)){
							$AppointmentFind->total_amount = $total_price;
							$AppointmentFind->final_total  = $total_price;
							$AppointmentFind->save();
						}	
						
						if($saveAppointment != '' && $expressCheckout == ''){
							$redirectUrl = url('partners/calander');
						} else if($saveAppointment == '' && $expressCheckout != ''){
							$redirectUrl = url('partners/checkout/'.$location_id.'/appointment/'.$firstAppointmentId);
						} else {
							$redirectUrl = url('partners/calander');
						}
						
						Session::flash('message', 'Appointment has been created succesfully.');
						$data["status"] = true;
						$data["message"] = "Appointment has been created succesfully.";	
						$data["redirect"] = $redirectUrl;
						// send email
						$client = Clients::where('id',$client_id)->first();
						$serPrice = ServicesPrice::where('id',$AppointmentServices->service_price_id)->first();
						$service = Services::where('id',$serPrice->service_id)->first();
						$getappo = Appointments::where('id',$AppointmentServices->appointment_id)->first();
						$remidernoti = NewAppointment::where('user_id',$AdminId)->first();

						if(!empty($client) && !empty($remidernoti)){
							$email = $client->email;
							$FROM_EMAIL     = env("MAIL_FROM_ADDRESS", "info@ikotel.ca");
							$FROM_NAME      = 'Scheduledown';
							$TO_EMAIL       = $email;
							$CC_EMAIL       = 'tjcloudtest2@gmail.com';
							$SUBJECT        = 'Appointment';
							// $MESSAGE        = 'Hi  Please see attached purchase order Have a great day! ';
							$OrderId        = 0;
							
							$UniqueId       = $this->unique_code(30).time();
							
							$SendMail = Mail::to($TO_EMAIL)->cc([$CC_EMAIL])->send(new newAppointments($FROM_EMAIL,$FROM_NAME,$SUBJECT,$AppointmentServices,$client,$UniqueId,$getappo,$service,$remidernoti));	
							
							EmailLog::create([
                                'user_id' => $AdminId,
								'client_id' => $client->id,
                                'appointment_id' => $getappo->id,
                                'unique_id' => $UniqueId,
                                'from_email' => $FROM_EMAIL,
                                'to_email' => $TO_EMAIL,
                                'module_type_text' => 'New Appointment Email',
                                'created_at'       => date("Y-m-d H:i:s")
                            ]);
						}
						//end

					} else {
						$data["status"] = false;
						$data["message"] = "Something went wrong!";	
					}
				}	
			}
			
            return JsonReturn::success($data);
        }
	}
	
	

	public function viewAppointment($appointmentId = null)
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
		
		if($appointmentId != '')
		{
			$appId = Crypt::decryptString($appointmentId);
			
			// get all clients	
			$CancellationReasons = CancellationReasons::select('*')->where('user_id', $AdminId)->orderBy('id', 'desc')->get()->toArray();	
			
			// get all clients	
			$Clients = Clients::select('id','firstname','lastname','email','mo_country_code','mobileno')->where('is_deleted',0)->where('user_id', $AdminId)->orderBy('id', 'desc')->limit(25)->get();	
			
			$Appointment = Appointments::select('*')->where('id',$appId)->where('user_id', $AdminId)->orderBy('id', 'desc')->get()->first()->toArray();
			
			$ClientInfo = array();
			if($Appointment['client_id'] != 0){
				$ClientInfo = Clients::getClientbyID($Appointment['client_id']);
			}
			
			$InvoiceInfo = array();
			if($Appointment['invoice_id'] != 0){
				$InvoiceInfo = Invoice::getInvoicebyID($Appointment['invoice_id']);
			}
			
			$curr_date = date("Y-m-d");
			$appointDate = date("Y-m-d", strtotime($Appointment['appointment_date']));
			
			$canCancelAppointment = 0;
			
			if(strtotime($appointDate) < strtotime($curr_date)) {
				$canCancelAppointment = 1;	
			}	
			
			$AppointmentServices = AppointmentServices::select('*')->where('appointment_id',$appId)->where('user_id', $AdminId)->orderBy('id', 'desc')->get()->toArray();
			
			$ClientServices = array();
			$TotalDuration = 0;
			
			if(!empty($AppointmentServices))
			{
				foreach($AppointmentServices as $AppointmentServiceData)
				{
					$service_price_id = $AppointmentServiceData['service_price_id'];
					
					$Services = ServicesPrice::select('services_price.id as service_price_id','services_price.duration','services_price.price_type','services_price.price','services_price.special_price','services_price.pricing_name','services_price.lowest_price','services_price.staff_prices','services.id as service_id','services.service_name','services.extra_time','services.is_extra_time','services.extra_time_duration')->join('services','services.id','=','services_price.service_id')->where('services_price.id',$service_price_id)->where('services.user_id',$AdminId)->orderBy('services_price.id', 'ASC')->get()->first()->toArray();
					
					$getUser = User::getUserbyID($AppointmentServiceData['staff_user_id']);
					
					$TotalDuration = $TotalDuration + $AppointmentServiceData['duration'];
					
					$tempClientServices['appointment_service_id'] = $AppointmentServiceData['id'];
					$tempClientServices['duration']               = $this->hoursandmins($AppointmentServiceData['duration']);
					$tempClientServices['special_price']          = $AppointmentServiceData['special_price'];
					$tempClientServices['staff_user_id']          = $AppointmentServiceData['staff_user_id'];
					$tempClientServices['staff_name']             = $getUser->first_name.' '.$getUser->last_name;
					$tempClientServices['service_name']           = ($Services['service_name']) ? $Services['service_name'] : '';
					$tempClientServices['service_pricing_name']   = ($Services['pricing_name']) ? $Services['pricing_name'] : '';
					$tempClientServices['start_time']             = ($AppointmentServiceData['start_time']) ? date("h:ia",strtotime($AppointmentServiceData['start_time'])) : '';
					
					array_push($ClientServices,$tempClientServices);
				}
			}
			
			$TotalDurationString = $this->hoursandmins($TotalDuration);
			
			$PreviousAppointment = array();
			$PreviousAppointmentServices = array();
			$PreviousServices = array();
			$TotalSpend = 0;
			
			$soldProduct     = array();
			$ClientProducts  = '';
			$clientInvoices  = array();
			$ClientInovices  = '';
			
			if($Appointment['client_id'] != 0){
				$PreviousAppointment = Appointments::select('*')->where('client_id',$Appointment['client_id'])->where('user_id', $AdminId)->orderBy('id', 'desc')->get()->toArray();
				
				$PreviousAppointmentServices = AppointmentServices::select('appointment_services.*')->join('appointments','appointments.id','=','appointment_services.appointment_id')->where('appointments.client_id',$Appointment['client_id'])->where('appointments.user_id', $AdminId)->orderBy('id', 'desc')->get()->toArray();
				
				if(!empty($PreviousAppointmentServices))
				{
					foreach($PreviousAppointmentServices as $AppointmentServiceData)
					{
						$appointment_date = $AppointmentServiceData['appointment_date'];
						$start_time       = $AppointmentServiceData['start_time'];
						$duration         = $this->hoursandmins($AppointmentServiceData['duration']);
						$special_price    = $AppointmentServiceData['special_price'];
						$StaffUserId      = $AppointmentServiceData['staff_user_id'];
						
						$StaffDetails = Staff::select('staff.staff_user_id','users.first_name','users.last_name')->join('users','users.id','=','staff.staff_user_id')->where(['staff.staff_user_id'=>$StaffUserId])->orderBy('staff.id', 'ASC')->get()->first()->toArray();
						
						if(!empty($StaffDetails)){
							$staff_name   = $StaffDetails['first_name'].' '.$StaffDetails['last_name'];
						} else {
							$staff_name   = 'N/A';
						}
						
						$servicePrices = ServicesPrice::select('pricing_name')->where('id',$AppointmentServiceData['service_price_id'])->orderBy('id', 'asc')->get()->first();
						
						$serviceName = '';
						if(!empty($servicePrices)){
							$serviceName = $servicePrices->pricing_name;
						} else {
							$serviceName = 'N/A';
						}
						
						$TotalSpend = $TotalSpend + $special_price;
						
						$tempServices['appointment_date_month'] = date("d M",strtotime($appointment_date));
						$tempServices['appointment_date_hours'] = date("D",strtotime($appointment_date)).' '.date('h:ia',strtotime($start_time));
						$tempServices['serviceName']            = $serviceName;
						$tempServices['duration']               = $duration;
						$tempServices['staff_name']             = $staff_name;
						$tempServices['special_price']          = $special_price;
						array_push($PreviousServices,$tempServices);
					}
				}
				
				$soldProduct = InvoiceItems::select('invoice_items.quantity','invoice_items.item_price','invoice_items.created_at','inventory_products.product_name')->join('inventory_products','inventory_products.id','=','invoice_items.item_id')->where('invoice_items.client_id',$Appointment['client_id'])->where('invoice_items.item_type', 'product')->orderBy('invoice_items.id', 'desc')->get();

				$ClientProducts = "";			
				if(!empty($soldProduct)) {
					foreach($soldProduct as $key => $product) {
						$ClientProducts .= '
							<div class="client-apoinments-list mb-6">
								<div class="d-flex align-items-center flex-grow-1">
									<div class="d-flex flex-wrap align-items-center justify-content-between w-100">
										<div class="d-flex flex-column align-items-cente py-2 w-75">
											<h6 class="text-muted font-weight-bold">'.$product->quantity.' sold</h6>
											<h6 class="text-muted font-weight-bold">'.$product->product_name.'</h6>
											<h6 class="text-muted font-weight-bold">'.date("D, d M Y", strtotime($product->created_at)).'</h6>
										</div>
										<h6 class="font-weight-bolder py-4">CA $'.($product->quantity * $product->item_price).'</h6>
									</div>
								</div>
							</div>';	
					}	
				} else {
					$ClientProducts .= '<h3>No product</h3>';
				}		
				
				$clientInvoices = Invoice::select('invoice.id','invoice.inovice_final_total','invoice.payment_date','invoice.invoice_status')->where('invoice.client_id',$Appointment['client_id'])->orderBy('invoice.id', 'desc')->get();
				
				$TotalSales = 0;
				$ClientInovices = "";
				if(!empty($clientInvoices)) {
					foreach($clientInvoices as $key => $inv) {
						$stats = "";
						if($inv->invoice_status == 0) {
							$stats = "Unpaid";
						} else if($inv->invoice_status == 1) {
							$stats = "Completed";
						} else if($inv->invoice_status == 2) {
							$stats = "Refund";
						} else if($inv->invoice_status == 3) {
							$stats = "Void";
						}	
						$ClientInovices .= '
							<tr>
								<td><span class="badge badge-pill badge-success">'.$stats.'</span></td>
								<td>'.$inv->id.'</td>
								<td>'.date("d M Y", strtotime($inv->payment_date)).'</td>
								<td>CA $'.$inv->inovice_final_total.'</td>
							</tr>';	
						$TotalSales += $inv->inovice_final_total;
					}	
				} else {
					$ClientInovices .= '<h3>No inovice</h3>';
				}
			}
			
			return view('appointments.viewAppointment',compact('Appointment','ClientServices','ClientInfo','Clients','TotalDurationString','PreviousAppointment','PreviousServices','TotalSpend','appointmentId','soldProduct','ClientProducts','clientInvoices','ClientInovices','CancellationReasons','InvoiceInfo','canCancelAppointment'));
		}		
	}
	
	public function editAppointment($appointmentId = null)
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
		
		if($appointmentId != '')
		{
			$appId = Crypt::decryptString($appointmentId);
			
			// fetch data from the db related to appointment
			// Get all services
			$Services = ServicesPrice::select('services_price.id as service_price_id','services_price.duration','services_price.price_type','services_price.price','services_price.special_price','services_price.pricing_name','services_price.lowest_price','services_price.staff_prices','services.id as service_id','services.service_name','services.extra_time','services.is_extra_time','services.extra_time_duration')->join('services','services.id','=','services_price.service_id')->where(['services.user_id'=>$UserId])->orderBy('services_price.id', 'ASC')->get()->toArray();
			
			// Get all staff
			$Staff = Staff::select('staff.staff_user_id','users.first_name','users.last_name')->where(['user_id'=>$AdminId])->join('users','users.id','=','staff.staff_user_id')->orderBy('staff.id', 'ASC')->get()->toArray();
				
			// get all clients	
			$Clients = Clients::select('id','firstname','lastname','email','mo_country_code','mobileno')->where('is_deleted',0)->where('is_blocked',0)->where('user_id', $AdminId)->orderBy('id', 'desc')->limit(25)->get();	
				
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
					$Hours          = date("H",strtotime($ClockStartTime)) * 60 * 60;
					$Minutes        = date("i",strtotime($ClockStartTime)) * 60;
					$TotalMinute    = $Hours + $Minutes;
					
					$tempSlots['time']          = date("h:ia",strtotime($ClockStartTime));
					$tempSlots['timevalue']     = $ClockStartTime;
					$tempSlots['timeinseconds'] = $TotalMinute;
					array_push($TimeSlots,$tempSlots);
				}
			}
			// fetch data from the db related to appointment
			
			// selected appointmet data
			
			// get all clients	
			$Appointment = Appointments::select('*')->where('id',$appId)->where('user_id', $AdminId)->orderBy('id', 'desc')->get()->first()->toArray();
			
			$ClientInfo = array();
			if($Appointment['client_id'] != 0){
				$ClientInfo = Clients::getClientbyID($Appointment['client_id']);
			}
			
			$AppointmentServices = AppointmentServices::select('*')->where('appointment_id',$appId)->where('user_id', $AdminId)->orderBy('id', 'asc')->get()->toArray();
			
			$ClientServices = array();
			$TotalDuration = 0;
			
			$LastStartTime = date("Y-m-d H:i:s");
			$LastDuration  = 0;
			
			if(!empty($AppointmentServices))
			{
				foreach($AppointmentServices as $AppointmentServiceData)
				{
					$service_price_id = $AppointmentServiceData['service_price_id'];
					
					$PreviousServices = ServicesPrice::select('services_price.id as service_price_id','services_price.duration','services_price.price_type','services_price.price','services_price.special_price','services_price.pricing_name','services_price.lowest_price','services_price.staff_prices','services.id as service_id','services.service_name','services.extra_time','services.is_extra_time','services.extra_time_duration')->join('services','services.id','=','services_price.service_id')->where('services_price.id',$service_price_id)->where('services.user_id',$AdminId)->orderBy('services_price.id', 'ASC')->get()->first()->toArray();
					
					$getUser = User::getUserbyID($AppointmentServiceData['staff_user_id']);
					
					$TotalDuration = $TotalDuration + $AppointmentServiceData['duration'];
					
					$unique_code = $this->unique_code(10);
					
					$tempClientServices['unique_code']            = $unique_code;
					$tempClientServices['appointment_service_id'] = $AppointmentServiceData['id'];
					$tempClientServices['special_price']          = $AppointmentServiceData['special_price'];
					$tempClientServices['durationSeconds']        = $AppointmentServiceData['duration'];
					$tempClientServices['isExtraTime']            = $AppointmentServiceData['is_extra_time'];
					$tempClientServices['extraTimeType']          = $AppointmentServiceData['extra_time'];
					$tempClientServices['timeExtraDurationSec']   = $AppointmentServiceData['extra_time_duration'];
					$tempClientServices['timeExtraDuration']      = $this->hoursandmins($AppointmentServiceData['extra_time_duration']);
					$tempClientServices['start_time']             = date("H:i:s",strtotime($AppointmentServiceData['start_time']));
					$tempClientServices['service_id']             = $AppointmentServiceData['service_price_id'];
					$tempClientServices['duration']               = ($AppointmentServiceData['duration'] * 60);
					$tempClientServices['staff_user_id']          = $AppointmentServiceData['staff_user_id'];
					
					array_push($ClientServices,$tempClientServices);
					
					$LastStartTime = date("Y-m-d H:i:s",strtotime($AppointmentServiceData['appointment_date'].' '.$AppointmentServiceData['start_time']));
					$LastDuration  = $AppointmentServiceData['duration'] + $AppointmentServiceData['extra_time_duration'];
				}
			}
			
			$NearestTime = date("H:i:s",strtotime('+'.$LastDuration.' minutes',strtotime($LastStartTime)));
			
			$TotalDurationString = $this->hoursandmins($TotalDuration);
			
			$PreviousAppointment = array();
			$PreviousAppointmentServices = array();
			$PreviousServices = array();
			$TotalSpend = 0;
			
			$soldProduct     = array();
			$ClientProducts  = '';
			$clientInvoices  = array();
			$ClientInovices  = '';
			
			if($Appointment['client_id'] != 0){
				$PreviousAppointment = Appointments::select('*')->where('client_id',$Appointment['client_id'])->where('user_id', $AdminId)->orderBy('id', 'desc')->get()->toArray();
				
				$PreviousAppointmentServices = AppointmentServices::select('appointment_services.*')->join('appointments','appointments.id','=','appointment_services.appointment_id')->where('appointments.client_id',$Appointment['client_id'])->where('appointments.user_id', $AdminId)->orderBy('id', 'desc')->get()->toArray();
				
				if(!empty($PreviousAppointmentServices))
				{
					foreach($PreviousAppointmentServices as $AppointmentServiceData)
					{
						$appointment_date = $AppointmentServiceData['appointment_date'];
						$start_time       = $AppointmentServiceData['start_time'];
						$duration         = $this->hoursandmins($AppointmentServiceData['duration']);
						$special_price    = $AppointmentServiceData['special_price'];
						$StaffUserId      = $AppointmentServiceData['staff_user_id'];
						
						$StaffDetails = Staff::select('staff.staff_user_id','users.first_name','users.last_name')->join('users','users.id','=','staff.staff_user_id')->where(['staff.staff_user_id'=>$StaffUserId])->orderBy('staff.id', 'ASC')->get()->first()->toArray();
						
						if(!empty($StaffDetails)){
							$staff_name   = $StaffDetails['first_name'].' '.$StaffDetails['last_name'];
						} else {
							$staff_name   = 'N/A';
						}
						
						$servicePrices = ServicesPrice::select('pricing_name')->where('id',$AppointmentServiceData['service_price_id'])->orderBy('id', 'asc')->get()->first();
						
						$serviceName = '';
						if(!empty($servicePrices)){
							$serviceName = $servicePrices->pricing_name;
						} else {
							$serviceName = 'N/A';
						}
						
						$TotalSpend = $TotalSpend + $special_price;
						
						$tempServices['appointment_date_month'] = date("d M",strtotime($appointment_date));
						$tempServices['appointment_date_hours'] = date("D",strtotime($appointment_date)).' '.date('h:ia',strtotime($start_time));
						$tempServices['serviceName']            = $serviceName;
						$tempServices['duration']               = $duration;
						$tempServices['staff_name']             = $staff_name;
						$tempServices['special_price']          = $special_price;
						array_push($PreviousServices,$tempServices);
					}
				}
				
				$soldProduct = InvoiceItems::select('invoice_items.quantity','invoice_items.item_price','invoice_items.created_at','inventory_products.product_name')->join('inventory_products','inventory_products.id','=','invoice_items.item_id')->where('invoice_items.client_id',$Appointment['client_id'])->where('invoice_items.item_type', 'product')->orderBy('invoice_items.id', 'desc')->get();

				$ClientProducts = "";			
				if(!empty($soldProduct)) {
					foreach($soldProduct as $key => $product) {
						$ClientProducts .= '
							<div class="client-apoinments-list mb-6">
								<div class="d-flex align-items-center flex-grow-1">
									<div class="d-flex flex-wrap align-items-center justify-content-between w-100">
										<div class="d-flex flex-column align-items-cente py-2 w-75">
											<h6 class="text-muted font-weight-bold">'.$product->quantity.' sold</h6>
											<h6 class="text-muted font-weight-bold">'.$product->product_name.'</h6>
											<h6 class="text-muted font-weight-bold">'.date("D, d M Y", strtotime($product->created_at)).'</h6>
										</div>
										<h6 class="font-weight-bolder py-4">CA $'.($product->quantity * $product->item_price).'</h6>
									</div>
								</div>
							</div>';	
					}	
				} else {
					$ClientProducts .= '<h3>No product</h3>';
				}		
				
				$clientInvoices = Invoice::select('invoice.id','invoice.inovice_final_total','invoice.payment_date','invoice.invoice_status')->where('invoice.client_id',$Appointment['client_id'])->orderBy('invoice.id', 'desc')->get();
				
				$TotalSales = 0;
				$ClientInovices = "";
				if(!empty($clientInvoices)) {
					foreach($clientInvoices as $key => $inv) {
						$stats = "";
						if($inv->invoice_status == 0) {
							$stats = "Unpaid";
						} else if($inv->invoice_status == 1) {
							$stats = "Completed";
						} else if($inv->invoice_status == 2) {
							$stats = "Refund";
						} else if($inv->invoice_status == 3) {
							$stats = "Void";
						}	
						$ClientInovices .= '
							<tr>
								<td><span class="badge badge-pill badge-success">'.$stats.'</span></td>
								<td>'.$inv->id.'</td>
								<td>'.date("d M Y", strtotime($inv->payment_date)).'</td>
								<td>CA $'.$inv->inovice_final_total.'</td>
							</tr>';	
						$TotalSales += $inv->inovice_final_total;
					}	
				} else {
					$ClientInovices .= '<h3>No inovice</h3>';
				}
			}
			
			return view('appointments.editAppointment',compact('Services','Staff','TimeSlots','Clients','ClientServices','Appointment','ClientInfo','PreviousServices','TotalSpend','TotalDurationString','NearestTime','soldProduct','ClientProducts','clientInvoices','ClientInovices'));
		}
	}
	
	public function editAppointmentInfo(Request $request)
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
			
			$appoinment_id     = ($request->appoinment_id) ? $request->appoinment_id : 0;
			$appointment_date  = ($request->appointment_date) ? date("Y-m-d",strtotime($request->appointment_date)) : null;
			$appointment_notes = ($request->appointment_notes) ? $request->appointment_notes : '';
			$client_id         = ($request->clientId) ? $request->clientId : 0;
			$location_id       = ($request->location_id) ? $request->location_id : 0;
			
			$start_time        = ($request->start_time) ? $request->start_time : array();
			$service_id        = ($request->service_id) ? $request->service_id : array();
			$duration          = ($request->duration) ? $request->duration : array();
			$staff_user_id     = ($request->staff_user_id) ? $request->staff_user_id : array();
			
			$isExtraTime       = ($request->isExtraTime) ? $request->isExtraTime : array();
			$extraTimeType     = ($request->extraTimeType) ? $request->extraTimeType : array();
			$timeExtraDuration = ($request->timeExtraDuration) ? $request->timeExtraDuration : array();
			$total_price       = 0;
			
			$special_price     =  ($request->special_price) ? $request->special_price : array();
			
			// edit service params
			$edit_appointment_services_id = ($request->edit_appointment_services_id) ? $request->edit_appointment_services_id : array();
			$edit_start_time              = ($request->edit_start_time) ? $request->edit_start_time : array();
			$edit_service_id              = ($request->edit_service_id) ? $request->edit_service_id : array();
			$edit_duration                = ($request->edit_duration) ? $request->edit_duration : array();
			$edit_staff_user_id           = ($request->edit_staff_user_id) ? $request->edit_staff_user_id : array();
									      
			$edit_isExtraTime             = ($request->edit_isExtraTime) ? $request->edit_isExtraTime : array();
			$edit_extraTimeType           = ($request->edit_extraTimeType) ? $request->edit_extraTimeType : array();
			$edit_timeExtraDuration       = ($request->edit_timeExtraDuration) ? $request->edit_timeExtraDuration : array();
			$total_price                  = 0;
			
			$edit_special_price     =  ($request->edit_special_price) ? $request->edit_special_price : array();
			// edit service params
			
			$repeatAppointmentFrequency = ($request->repeatAppointmentFrequency) ? $request->repeatAppointmentFrequency : '';
			$repeatAppointmentCount     = ($request->repeatAppointmentCount) ? $request->repeatAppointmentCount : '';
			$repeatAppointmentDate      = ($request->repeatAppointmentDate) ? date("Y-m-d",strtotime($request->repeatAppointmentDate)) : '';
			
			$saveAppointment   = ($request->saveAppointment) ? $request->saveAppointment : '';
			$expressCheckout   = ($request->expressCheckout) ? $request->expressCheckout : '';
			
			$firstAppointmentId = 0;
			
			if($location_id == 0){
				$FirstLocation = Location::select('id')->where(['user_id'=>$AdminId])->orderBy('id', 'ASC')->get()->first()->toArray();
				if(!empty($FirstLocation)){
					$location_id = $FirstLocation['id'];
				}
			}
			
			if($appoinment_id){
				// update primary appointment record
				$Appointments = Appointments::find($appoinment_id);
				$Appointments->appointment_date  = $appointment_date;
				$Appointments->appointment_notes = $appointment_notes;
				$Appointments->client_id         = $client_id;
				$Appointments->updated_at        = date("Y-m-d H:i:s");
				$Appointments->save();
				
				$AppointmentId = $appoinment_id;
				
				$firstAppointmentId = Crypt::encryptString($AppointmentId);
				
				if($AppointmentId){
					
					if(!empty($edit_appointment_services_id)){
						foreach($edit_appointment_services_id as $mainKey => $edit_services_id)
						{
							$durationval = (isset($edit_duration[$mainKey])) ? $edit_duration[$mainKey] : 0;
							$staffuserid = (isset($edit_staff_user_id[$mainKey])) ? $edit_staff_user_id[$mainKey] : 0;
							
							$is_extra_time       = (isset($edit_isExtraTime[$mainKey])) ? $edit_isExtraTime[$mainKey] : 0;
							$extra_time          = (isset($edit_extraTimeType[$mainKey])) ? $edit_extraTimeType[$mainKey] : 0;
							$extra_time_duration = (isset($edit_timeExtraDuration[$mainKey])) ? $edit_timeExtraDuration[$mainKey] : 0;
							
							$MainDuration   = $durationval;
							$MinuteDuration = $durationval / 60;
							if($is_extra_time == 1){
								$ExtraDuration = intval($extra_time_duration) * 60;	
								$MainDuration = $MainDuration + $ExtraDuration;
							}
							
							$start_time_val = (isset($edit_start_time[$mainKey])) ? date("H:i:s",strtotime($edit_start_time[$mainKey])) : '';
							$end_time       = date("H:i:s",strtotime('+'.$MainDuration.' second',strtotime($start_time_val)));
							
							$price_val   = (isset($edit_special_price[$mainKey])) ? $edit_special_price[$mainKey] : 0;
							$service_ids = (isset($edit_service_id[$mainKey])) ? $edit_service_id[$mainKey] : 0;
							
							if($edit_services_id != '' && $service_ids != '' && $durationval != '' && $staffuserid != '' && $start_time_val != '' && $price_val != ''){
								$total_price = $total_price + floatval($price_val);
										
								$EditAppointmentService = AppointmentServices::find($edit_services_id);	
								
								$EditAppointmentService->appointment_date    = $appointment_date;
								$EditAppointmentService->start_time          = $start_time_val;
								$EditAppointmentService->end_time            = $end_time;
								$EditAppointmentService->service_price_id    = $service_ids;
								$EditAppointmentService->duration            = $MinuteDuration;
								$EditAppointmentService->is_extra_time       = $is_extra_time;
								$EditAppointmentService->extra_time          = $extra_time;
								$EditAppointmentService->extra_time_duration = $extra_time_duration;
								$EditAppointmentService->staff_user_id       = $staffuserid;
								$EditAppointmentService->special_price       = $price_val;
								$EditAppointmentService->updated_at          = date("Y-m-d H:i:s");
								$EditAppointmentService->save();
							}
						}
					}
					
					if(!empty($service_id)){
						foreach($service_id as $mainKey => $service_ids){
							$durationval = (isset($duration[$mainKey])) ? $duration[$mainKey] : 0;
							$staffuserid = (isset($staff_user_id[$mainKey])) ? $staff_user_id[$mainKey] : 0;
							
							$is_extra_time       = (isset($isExtraTime[$mainKey])) ? $isExtraTime[$mainKey] : 0;
							$extra_time          = (isset($extraTimeType[$mainKey])) ? $extraTimeType[$mainKey] : 0;
							$extra_time_duration = (isset($timeExtraDuration[$mainKey])) ? $timeExtraDuration[$mainKey] : 0;
							
							$MainDuration   = $durationval;
							$MinuteDuration = $durationval / 60;
							if($is_extra_time == 1){
								$ExtraDuration = $extra_time_duration * 60;	
								$MainDuration = $MainDuration + $ExtraDuration;
							}
							
							$start_time_val = (isset($start_time[$mainKey])) ? date("H:i:s",strtotime($start_time[$mainKey])) : '';
							$end_time       = date("H:i:s",strtotime('+'.$MainDuration.' second',strtotime($start_time_val)));
							
							$price_val = (isset($special_price[$mainKey])) ? $special_price[$mainKey] : 0;
							
							if($service_ids != '' && $durationval != '' && $staffuserid != '' && $start_time_val != '' && $price_val != ''){
								$total_price = $total_price + floatval($price_val);
									
								$AppointmentServices = AppointmentServices::create([
									'appointment_id'      => $AppointmentId,
									'user_id'             => $UserId,
									'appointment_date'    => $appointment_date,
									'start_time'          => $start_time_val,
									'end_time'            => $end_time,
									'service_price_id'    => $service_ids,
									'duration'            => $MinuteDuration,
									'is_extra_time'       => $is_extra_time,
									'extra_time'          => $extra_time,
									'extra_time_duration' => $extra_time_duration,
									'staff_user_id'       => $staffuserid,
									'special_price'       => $price_val,
									'created_at'          => date("Y-m-d H:i:s"),
									'updated_at'          => date("Y-m-d H:i:s")
								]);
								$AppointmentServicesId = $AppointmentServices->id;	
							}
						}
					}
				
					$AppointmentFind = Appointments::find($AppointmentId);   
					if(!empty($AppointmentFind)){
						$AppointmentFind->total_amount = $total_price;
						$AppointmentFind->final_total  = $total_price;
						$AppointmentFind->save();
					}	
					
					if($saveAppointment != '' && $expressCheckout == ''){
						$redirectUrl = url('partners/calander');
					} else if($saveAppointment == '' && $expressCheckout != ''){
						$redirectUrl = url('partners/checkout/'.$location_id.'/appointment/'.$firstAppointmentId);
					} else {
						$redirectUrl = url('partners/calander');
					}
					
					Session::flash('message', 'Appointment has been updated succesfully.');
					$data["status"] = true;
					$data["message"] = "Appointment has been updated succesfully.";	
					$data["redirect"] = $redirectUrl;
				} else {
					$data["status"] = false;
					$data["message"] = "Something went wrong!";	
				}
			}
			
            return JsonReturn::success($data);
        }
	}
	
	function checkForRepeatAppointment(Request $request)
	{
		if ($request->ajax()) 
        {
			$appointmentStartDate = ($request->appointmentStartDate) ? date("Y-m-d",strtotime($request->appointmentStartDate)) : '';
			$repeatFrequency      = ($request->repeatFrequency) ? $request->repeatFrequency : '';
			$repeatCount          = ($request->repeatCount) ? $request->repeatCount : '';
			$repeatDateString     = ($request->repeatDate) ? date("Y-m-d",strtotime($request->repeatDate)) : '';
			$repeatDate           = ($request->repeatDateEnd) ? date("Y-m-d",strtotime($request->repeatDateEnd)) : '';
			
			if($repeatFrequency != ''){
				if($repeatCount == 'specific_date' && $repeatDateString == ''){
					$data["status"] = false;
					$data["message"] = "End date is required!";	
					return JsonReturn::success($data);     
				}
				
				$explodeRepeatFrequency = explode(":",$repeatFrequency);
				
				$typeOfFrequency = $explodeRepeatFrequency[0];
				$numberOfRepeat  = $explodeRepeatFrequency[1];
				
				$nextAppointmentDate = '';
				
				$addString = '';
				if($typeOfFrequency == 'daily'){
					$addString = 'days';	
				} else if($typeOfFrequency == 'weekly') {
					$addString = 'week';
				} else if($typeOfFrequency == 'monthly') {
					$addString = 'month';
				}
				
				if($repeatCount == 'specific_date'){
					$startDate = strtotime($appointmentStartDate);
					$endDate   = strtotime($repeatDate);
					$datediff  = $startDate - $endDate;

					$totalRepeat = abs(round($datediff / (60 * 60 * 24)));
					
					$prevAppointmentDate = '';
					$prevAppointmentDate = $appointmentStartDate;
					
					$everyDays = floor($totalRepeat / $numberOfRepeat);
					
					for($i = 1;$i <= $everyDays;$i++){
						$nextAppointmentDate = date("Y-m-d",strtotime('+'.$numberOfRepeat.' '.$addString,strtotime($prevAppointmentDate)));
						$prevAppointmentDate = $nextAppointmentDate;
					}
				} else if($repeatCount == 'ongoing'){
					$startDate = strtotime($appointmentStartDate);
					$endDate   = strtotime(date("Y-m-d",strtotime('+1 year',$startDate)));
					$datediff  = $startDate - $endDate;

					$totalRepeat = abs(round($datediff / (60 * 60 * 24)));
					
					$prevAppointmentDate = '';
					$prevAppointmentDate = $appointmentStartDate;
					
					$everyDays = floor($totalRepeat / $numberOfRepeat);
					
					for($i = 1;$i <= $everyDays;$i++){
						$nextAppointmentDate = date("Y-m-d",strtotime('+'.$numberOfRepeat.' '.$addString,strtotime($prevAppointmentDate)));
						$prevAppointmentDate = $nextAppointmentDate;
					}
				} else {
					$explodeRepeatCount = explode(":",$repeatCount);
					$totalRepeat = $explodeRepeatCount[1];
					
					$prevAppointmentDate = '';
					$prevAppointmentDate = $appointmentStartDate;
					
					for($i = 1;$i < $totalRepeat;$i++){
						$nextAppointmentDate = date("Y-m-d",strtotime('+'.$numberOfRepeat.' '.$addString,strtotime($prevAppointmentDate)));
						$prevAppointmentDate = $nextAppointmentDate;
					}
				}
			}
			
			$data["status"] = true;
			$data["message"] = "Repeat until ".date("l, d M Y",strtotime($nextAppointmentDate));	
            return JsonReturn::success($data);     
        }
        else
        {
            $data["status"] = false;
            $data["message"] = array("Sorry somethig went wrong.");
            $data["message_code"] = array("Out of ajax condition.");
            return JsonReturn::success($data);     
        }
	}
	
	function searchClients(Request $request)
	{
		$status = 0;
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
			
			$searchText = ($request->searchText) ? $request->searchText : '';
			
			$HTML = '';
			
			if($searchText != ''){
				$Clients = Clients::select('*')->where(function ($query) use ($searchText){
													$query->where('firstname','LIKE','%'.$searchText.'%')
														  ->orWhere('lastname','LIKE','%'.$searchText.'%')
														  ->orWhere('mobileno','LIKE','%'.$searchText.'%')
														  ->orWhere('telephoneno','LIKE','%'.$searchText.'%')
														  ->orWhere('email','LIKE','%'.$searchText.'%');
												})->where('is_deleted',0)->where('user_id',$AdminId)->orderBy('id', 'desc')->get()->toArray();
					
				if(!empty($Clients)) {
					foreach($Clients as $Clientdata){
						$profilePicture = asset('assets/media/users/300_13.jpg');
						
						$HTML .= '
						<div class="d-flex align-items-center border-bottom p-6 customer-data" onclick="getClientHistory('.$Clientdata['id'].');">
							<div class="symbol symbol-60 symbol-xxl-100 mr-5 align-self-start align-self-xxl-center">
								<div class="symbol-label rounded-circle" style="background-image:url('.$profilePicture.')">
								</div>
							</div>
							<div>
								<h6 class="font-weight-bolder">'.$Clientdata['firstname'].' '.$Clientdata['lastname'].'</h6>
								<div class="text-muted">';
									if($Clientdata['email'] != ''){
										$HTML .= $Clientdata['email'];
									} elseif($Clientdata['mobileno'] != '') {
										$HTML .= $Clientdata['mo_country_code'].' '.$Clientdata['mobileno'];
									}
								$HTML .= '
								</div>
							</div>
						</div>';
					}
				} else {
					$noContentImage = asset('assets/media/users/300_13.jpg');
					
					$HTML .= '
					<div class="d-flex align-items-center border-bottom p-6 customer-data">
						<div class="symbol symbol-60 symbol-xxl-100 mr-5 align-self-start align-self-xxl-center">
							<div class="symbol-label rounded-circle" style="background-image:url('.$noContentImage.')">
							</div>
						</div>
						<div>
							<div class="text-muted">No client found!</div>
						</div>
					</div>';
				}
			} else {
				$Clients = Clients::select('id','firstname','lastname','email','mo_country_code','mobileno')->where('is_deleted',0)->where('user_id', $AdminId)->orderBy('id', 'desc')->limit(25)->get()->toArray();
				
				if(!empty($Clients)) {
					foreach($Clients as $Clientdata){
						$profilePicture = asset('assets/media/users/300_13.jpg');
						
						$HTML .= '
						<div class="d-flex align-items-center border-bottom p-6 customer-data" onclick="getClientHistory('.$Clientdata['id'].');">
							<div class="symbol symbol-60 symbol-xxl-100 mr-5 align-self-start align-self-xxl-center">
								<div class="symbol-label rounded-circle" style="background-image:url('.$profilePicture.')">
								</div>
							</div>
							<div>
								<h6 class="font-weight-bolder">'.$Clientdata['firstname'].' '.$Clientdata['lastname'].'</h6>
								<div class="text-muted">';
									if($Clientdata['email'] != ''){
										$HTML .= $Clientdata['email'];
									} elseif($Clientdata['mobileno'] != '') {
										$HTML .= $Clientdata['mo_country_code'].' '.$Clientdata['mobileno'];
									}
								$HTML .= '
								</div>
							</div>
						</div>';
					}
				} else {
					$noContentImage = asset('assets/media/users/300_13.jpg');
					
					$HTML .= '
					<div class="d-flex align-items-center border-bottom p-6 customer-data">
						<div class="symbol symbol-60 symbol-xxl-100 mr-5 align-self-start align-self-xxl-center">
							<div class="symbol-label rounded-circle" style="background-image:url('.$noContentImage.')">
							</div>
						</div>
						<div>
							<div class="text-muted">No client found!</div>
						</div>
					</div>';
				}
			}
			
			$data["status"] = true;
			$data["html"] = $HTML;
            return JsonReturn::success($data);
        }
	}
	
	function checkoutAppointment($locationId = null,$type = null,$appointmentId = null)
	{	
		if($locationId > 0)
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
			
			$serviceCategory = ServiceCategory::select('id','category_title')->where('user_id', $AdminId)->orderBy('order_id', 'asc')->get();
			
			if($appointmentId != '')
			{			
				$appId = Crypt::decryptString($appointmentId);
				
				// get all clients	
				$Clients = Clients::select('id','firstname','lastname','email','mo_country_code','mobileno')->where('is_deleted',0)->where('user_id', $AdminId)->orderBy('id', 'desc')->limit(25)->get();	
				
				$Appointment = Appointments::select('*')->where('id',$appId)->where('user_id', $AdminId)->orderBy('id', 'desc')->get()->first()->toArray();
				
				$ClientInfo = array();
				if($Appointment['client_id'] != 0){
					$ClientInfo = Clients::getClientbyID($Appointment['client_id']);
				}
				
				$AppointmentServices = AppointmentServices::select('*')->where('appointment_id',$appId)->where('user_id', $AdminId)->orderBy('id', 'desc')->get()->toArray();
				
				$ClientServices = array();
				
				if(!empty($AppointmentServices))
				{
					foreach($AppointmentServices as $AppointmentServiceData)
					{
						
						$service_price_id = $AppointmentServiceData['service_price_id'];
						
						$Services = ServicesPrice::select('services_price.id as service_price_id','services_price.duration','services_price.price_type','services_price.price','services_price.special_price','services_price.pricing_name','services_price.lowest_price','services_price.staff_prices','services.id as service_id','services.service_name','services.extra_time','services.is_extra_time','services.extra_time_duration')->join('services','services.id','=','services_price.service_id')->where('services_price.id',$service_price_id)->where('services.user_id',$AdminId)->orderBy('services_price.id', 'ASC')->get()->first()->toArray();
						
						$getUser = User::getUserbyID($AppointmentServiceData['staff_user_id']);
						
						$tempClientServices['appointment_service_id'] = $AppointmentServiceData['id'];
						$tempClientServices['duration']               = $this->hoursandmins($AppointmentServiceData['duration']);
						$tempClientServices['special_price']          = $AppointmentServiceData['special_price'];
						$tempClientServices['staff_user_id']          = $AppointmentServiceData['staff_user_id'];
						$tempClientServices['staff_name']             = $getUser->first_name.' '.$getUser->last_name;
						$tempClientServices['service_name']           = ($Services['service_name']) ? $Services['service_name'] : '';
						$tempClientServices['service_pricing_name']   = ($Services['pricing_name']) ? $Services['pricing_name'] : '';
						array_push($ClientServices,$tempClientServices);
					}
				}
				
				// Get all staff
				$Staff = Staff::select('staff.staff_user_id','users.first_name','users.last_name')->where(['user_id'=>$AdminId])->join('users','users.id','=','staff.staff_user_id')->orderBy('staff.id', 'ASC')->get()->toArray();
				
				return view('appointments.checkout',compact('Appointment','ClientServices','Staff','ClientInfo','Clients','serviceCategory','locationId'));
			}
						
			return view('appointments.checkout', compact('serviceCategory', 'locationId'));
		
		} else {
			return redirect()->route('calander');
		}		
		
	}
	
	public function getServiceByCategory(Request $request)
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
			
			$catid = $request->catid;
			
			$serviceLists = Services::select('id','service_name')->where('service_category', $catid)->where('user_id', $AdminId)->orderBy('order_id', 'asc')->get();
			
			$servicehtml = "";
			foreach($serviceLists as $key => $service)
			{
				$pricearr = array();
				$servicePrices = ServicesPrice::select('id', 'duration', 'lowest_price', 'price', 'special_price')->where('service_id', $service->id)->where('user_id', $AdminId)->orderBy('id', 'asc')->get();
				
				foreach($servicePrices as $key2 => $servprice)
				{
					$sprice = $servprice->lowest_price;
					$duration = "";
					
					if($servprice->duration <= 0) {
						$duration = '00h 00min';
					}
					else 
					{  
						if(sprintf("%02d",floor($servprice->duration / 60)) > 0)
						{
							$duration .= sprintf("%02d",floor($servprice->duration / 60)).'h ';
						} 
							
						if(sprintf("%02d",str_pad(($servprice->duration % 60), 2, "0", STR_PAD_LEFT)) > 0)
						{
							$duration .= " ".sprintf("%02d",str_pad(($servprice->duration % 60), 2, "0", STR_PAD_LEFT)). "min";
						}
					}
					
					$pr = "";
					if(count($servicePrices) > 1) {
						$pr = "pr".(++$key2).",";	
					}	
						
					if($servprice->price != $sprice)	
					{
						$servicehtml .= '<li class="d-flex justify-content-between align-items-center text-primary list-group-item list-group-item-action additemcheckout" data-type="service" data-id="'.$servprice->id.'" >
							<span>
								<p class="m-0">'.$service->service_name.'</p>
								<p class="m-0">'.$pr." ".$duration.'</p>
							</span>
							<p class="font-weight-bolder specialprice-txt">CA '.$servprice->price.'</p>
							<p class="font-weight-bolder">CA '.$sprice.'</p>
						</li>';
					} else {
						$servicehtml .= '<li class="d-flex justify-content-between align-items-center text-primary list-group-item list-group-item-action additemcheckout" data-type="service" data-id="'.$servprice->id.'" >
							<span>
								<p class="m-0">'.$service->service_name.'</p>
								<p class="m-0">'.$pr." ".$duration.'</p>
							</span>
							<p class="font-weight-bolder">CA '.$sprice.'</p>
						</li>';
					}	
				}

			}	
			
            $data["data"] = $servicehtml;
            return JsonReturn::success($data);
		}		
	}	
	
	public function addItemToCheckout(Request $request)
	{
		if($request->ajax())
		{
			$id = $request->id;
			$locationId = $request->locationId;
			$type = $request->type;

			if($type == "service")
			{
				$serviceData = ServicesPrice::select('services_price.id', 'services_price.service_id', 'services_price.duration', 'services_price.lowest_price', 'services_price.price', 'services_price.special_price','services_price.is_staff_price','services_price.staff_prices','services.service_name')->leftJoin('services', 'services_price.service_id', '=', 'services.id')->where('services_price.id', $id)->first();
				
				$staffPrices = json_decode($serviceData->staff_prices, true);
				
				$staffPriceArr = array();
				$staffData = StaffLocations::select('staff_locations.id','staff_locations.staff_id','users.first_name','users.last_name')->leftJoin('users', 'staff_locations.staff_user_id', '=', 'users.id')->where('staff_locations.location_id', $locationId)->get();
				
				foreach($staffData as $key => $staff)
				{
					if($serviceData->is_staff_price == 1)
					{	
						$index = "";
						//echo "<br> ind = ".$index = array_search($staff->staff_id, array_column($staffPrices, 'staff_id'));
						
						$search = ['staff_id' => $staff->staff_id];
						$keys = array_keys(array_filter($staffPrices,function ($v) use ($search) { return $v['staff_id'] == $search['staff_id']; } ) );
						if(isset($keys[0])){
							$index = $keys[0];
							$staff_duration = $staffPrices[$index]['staff_duration'];
							$staff_price = $staffPrices[$index]['staff_price'];
							$staff_special_price = $staffPrices[$index]['staff_special_price'];
							
							$staffData[$key]->staff_duration = $this->convertDurationText($staff_duration);
							$staffData[$key]->staff_price = $staff_price;
							$staffData[$key]->staff_special_price = $staff_special_price;
							
						} else {
							$staffData[$key]->staff_duration = $this->convertDurationText($serviceData->duration);
							$staffData[$key]->staff_price = $serviceData->price;
							$staffData[$key]->staff_special_price = $serviceData->special_price;
						}	
					} else {
						$staffData[$key]->staff_duration = $this->convertDurationText($serviceData->duration);
						$staffData[$key]->staff_price = $serviceData->price;
						$staffData[$key]->staff_special_price = $serviceData->special_price;
					}		
				}	
				
				$serviceduration = $staffData[0]->staff_duration." with ".$staffData[0]->first_name." ".$staffData[0]->last_name;
				$staffSpecialPrice = $staffData[0]->staff_special_price;
				$staffPrice = $staffData[0]->staff_price;
				
				$uniqid = $this->quickRandom();
				
				$servicehtml = '<div class="card cardId'.$uniqid.'">
							<div class="card-body border-left border-primary border-3">
								<div class="row flex-wrap justify-content-between">
									<div class="d-flex">
										<h4 class="m-1 p-4 fonter-weight-bolder">1</h4>
										<div>
											<h3 class="m-0">'.$serviceData->service_name.'</h3>
											<p class="text-dark-50 itmduration-txt'.$uniqid.'">'.$serviceduration.'</p>
										</div>
									</div>';
									
									$itempr = ""; $displacls = ""; $txtreadonly = "";
									if($staffSpecialPrice > 0) {
										$itempr = $staffSpecialPrice;
										$txtreadonly = "readonly";
									} else {
										$displacls = "d-none";
										$itempr = $staffPrice;
									}		
									
								$servicehtml .= '<div class="d-flex flex-wrap">
									<div>
										<h3 class="m-0 itmpr-txt'.$uniqid.'">CA $<span>'.$itempr.'</span></h3>
										<h5 class="m-0 text-dark-50 itmspr-txt'.$uniqid.' '.$displacls.'"><s>CA $<span>'.$staffPrice.'</span></s></h5>
									</div>
									<i class="fa fa-times cursor-pointer text-danger fa-1x mt-2 ml-3"></i>
								</div>';		
								$servicehtml .= '
									<input type="hidden" name="item_id[]" value="'.$serviceData->service_id.'">
									<input type="hidden" name="item_type[]" value="services">
									<input type="hidden" name="item_price[]" class="itmpr itemprice'.$uniqid.'" value="'.$itempr.'">
								</div>
								<div class="row px-8">
									<div class="col-md-1 col-sm-6">
										<div class="form-group">
											<label class="font-weight-bolder">Quantity</label>
											<input class="form-control" readonly value="1" name="quantity[]" type="text">
										</div>
									</div>
									<div class="col-md-3 col-sm-6" id="pricing-type">
										<div class="form-group">
											<label class="font-weight-bolder">Price</label>
											<div class="input-group">
												<div class="input-group-prepend">
													<span class="input-group-text">CA $</span>
												</div>';
												$servicehtml .= '<input type="text" '.$txtreadonly.' class="form-control itmpr-inpt'.$uniqid.'" value="'.$itempr.'" placeholder="0.00">';
											$servicehtml .= '</div>
										</div>
									</div>
									<div class="col-md-4 col-sm-6">
										<div class="form-group">
											<label class="font-weight-bolder">Staff</label>
											<select name="staff_id[]" class="form-control staff-list" data-uid="'.$uniqid.'" >';
												foreach($staffData as $key => $staff) {
													$servicehtml .= '<option value="'.$staff->staff_id.'" data-pr="'.$staff->staff_price.'" data-spr="'.$staff->staff_special_price.'" data-dur="'.$staff->staff_duration.'">'.$staff->first_name.' '.$staff->last_name.'</option>';
												}	 
											$servicehtml .= '</select>
										</div>
									</div>
									<div class="col-md-4 col-sm-6">
										<div class="form-group">
											<label class="font-weight-bolder">Discount</label>
											<select class="form-control" name="discount_id[]" data-uid="'.$uniqid.'">
												<option value="no-discount">No Discount</option>
												<option value="special-offer">Special Discount CA $10 off</option>
											</select>
										</div>
									</div>
								</div>
							</div>
						</div>';
						
				$data["data"] = $servicehtml;
				return JsonReturn::success($data);		
			}		
		}	
	}	
	
	function convertDurationText($duration)
	{
		$duration_txt = "";
		if($duration <= 0) {
			$duration_txt = '00h 00min';
		}
		else 
		{  
			if(sprintf("%02d",floor($duration / 60)) > 0)
			{
				$duration_txt .= sprintf("%02d",floor($duration / 60)).'h ';
			} 
				
			if(sprintf("%02d",str_pad(($duration % 60), 2, "0", STR_PAD_LEFT)) > 0)
			{
				$duration_txt .= " ".sprintf("%02d",str_pad(($duration % 60), 2, "0", STR_PAD_LEFT)). "min";
			}
		}
		return trim($duration_txt);
	}	
	
	public static function quickRandom($length = 5)
	{
		$pool = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

		return substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
	}
	
	function hoursandmins($time, $format = '%02d:%02d')
	{
		if ($time < 1) {
			return;
		}
		$hours = floor($time / 60);
		$minutes = ($time % 60);
		
		$returnText = '';
		if($hours == 0){
			$returnText = $minutes.'min';
		} else {
			$returnText = $hours.'h '.$minutes.'min';
		}
		
		return $returnText;
	}
	
	public function getClientInformation(Request $request){
		$status = 0;
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
			
			$client_id  = ($request->client_id) ? $request->client_id : '';
			$ClientInfo = Clients::getClientbyID($client_id);
			
			$Appointment = Appointments::select('*')->where('client_id',$client_id)->where('user_id', $AdminId)->orderBy('id', 'desc')->get()->toArray();
			
			$AppointmentServices = AppointmentServices::select('appointment_services.*')->join('appointments','appointments.id','=','appointment_services.appointment_id')->where('appointments.client_id',$client_id)->where('appointments.user_id', $AdminId)->orderBy('id', 'desc')->get()->toArray();
			
			$TotalSpend = 0;
			$ClientServices = '';
			if(!empty($AppointmentServices))
			{
				foreach($AppointmentServices as $AppointmentServiceData)
				{
					$appointment_date = $AppointmentServiceData['appointment_date'];
					$start_time       = $AppointmentServiceData['start_time'];
					$duration         = $this->hoursandmins($AppointmentServiceData['duration']);
					$special_price    = $AppointmentServiceData['special_price'];
					$StaffUserId      = $AppointmentServiceData['staff_user_id'];
					
					$StaffDetails = Staff::select('staff.staff_user_id','users.first_name','users.last_name')->join('users','users.id','=','staff.staff_user_id')->where(['staff.staff_user_id'=>$StaffUserId])->orderBy('staff.id', 'ASC')->get()->first()->toArray();
					
					if(!empty($StaffDetails)){
						$staff_name   = $StaffDetails['first_name'].' '.$StaffDetails['last_name'];
					} else {
						$staff_name   = 'N/A';
					}
					
					$servicePrices = ServicesPrice::select('pricing_name')->where('id',$AppointmentServiceData['service_price_id'])->orderBy('id', 'asc')->get()->first();
					
					$serviceName = '';
					if(!empty($servicePrices)){
						$serviceName = $servicePrices->pricing_name;
					} else {
						$serviceName = 'N/A';
					}
					
					$TotalSpend = $TotalSpend + $special_price;
					
					$ClientServices .= '
					<div class="client-apoinments-list mb-6">
						<div class="d-flex align-items-center flex-grow-1">
							<h6 class="font-weight-bolder text-dark">'.date("d M",strtotime($appointment_date)).'</h6>
							<div class="d-flex flex-wrap align-items-center justify-content-between w-100">
								<div class="d-flex flex-column align-items-cente py-2 w-75">
									<h6 class="text-muted font-weight-bold">
										'.date("D",strtotime($appointment_date)).' '.date('h:ia',strtotime($start_time)).' 
										<a class="text-blue" href="#">New</a>
									</h6>
									<a href="#" class="text-dark-75 font-weight-bolder text-hover-primary font-size-lg mb-1">'.$serviceName.'</a>
									<span class="text-muted font-weight-bold">'.$duration.' with <i class="fa fa-heart text-danger"></i> '.$staff_name.' </span>
								</div>
								<h6 class="font-weight-bolder py-4">CA $'.$special_price.'</h6>
							</div>
						</div>
					</div>';
				}
			}
			
			if($ClientInfo->accept_marketing_notification == 1){
				$MarketingNotification = 'Accepts marketing notifications';
			} else {
				$MarketingNotification = 'Not accepts marketing notifications';
			}
			
			$soldProduct = InvoiceItems::select('invoice_items.quantity','invoice_items.item_price','invoice_items.created_at','inventory_products.product_name')->join('inventory_products','inventory_products.id','=','invoice_items.item_id')->where('invoice_items.client_id',$client_id)->where('invoice_items.item_type', 'product')->orderBy('invoice_items.id', 'desc')->get();

			$ClientProducts = "";			
			if(!empty($soldProduct)) {
				foreach($soldProduct as $key => $product) {
					$ClientProducts .= '
						<div class="client-apoinments-list mb-6">
							<div class="d-flex align-items-center flex-grow-1">
								<div class="d-flex flex-wrap align-items-center justify-content-between w-100">
									<div class="d-flex flex-column align-items-cente py-2 w-75">
										<h6 class="text-muted font-weight-bold">'.$product->quantity.' sold</h6>
										<h6 class="text-muted font-weight-bold">'.$product->product_name.'</h6>
										<h6 class="text-muted font-weight-bold">'.date("D, d M Y", strtotime($product->created_at)).'</h6>
									</div>
									<h6 class="font-weight-bolder py-4">CA $'.($product->quantity * $product->item_price).'</h6>
								</div>
							</div>
						</div>';	
				}	
			} else {
				$ClientProducts .= '<h3>No product</h3>';
			}		
			
			$clientInvoices = Invoice::select('invoice.id','invoice.inovice_final_total','invoice.payment_date','invoice.invoice_status')->where('invoice.client_id',$client_id)->orderBy('invoice.id', 'desc')->get();
			
			$TotalSales = 0;
			$ClientInovices = "";
			if(!empty($clientInvoices)) {
				foreach($clientInvoices as $key => $inv) {
					$stats = "";
					if($inv->invoice_status == 0) {
						$stats = "Unpaid";
					} else if($inv->invoice_status == 1) {
						$stats = "Completed";
					} else if($inv->invoice_status == 2) {
						$stats = "Refund";
					} else if($inv->invoice_status == 3) {
						$stats = "Void";
					}	
					$ClientInovices .= '
						<tr>
							<td><span class="badge badge-pill badge-success">'.$stats.'</span></td>
							<td>'.$inv->id.'</td>
							<td>'.date("d M Y", strtotime($inv->payment_date)).'</td>
							<td>CA $'.$inv->inovice_final_total.'</td>
						</tr>';	
					$TotalSales += $inv->inovice_final_total;
				}	
			} else {
				$ClientInovices .= '<h3>No inovice</h3>';
			}
			
			$HTML = '
			<div class="view-appoinment mb-2" style="height: calc(100vh - 270px);" style="display:none;">							
				<div id="" class="bg-white active">
					<div class="d-flex align-items-center border-bottom p-6 customer-data" id="sidebarCollapse">
						<div class="symbol symbol-60 symbol-xxl-100 mr-5 align-self-start align-self-xxl-center">
							<div class="symbol-label rounded-circle" style="background-image:url(\''.asset('assets/media/users/300_13.jpg').'\')"></div>
						</div>
						<div>
							<a href="#" class="font-weight-bold font-size-h5 text-dark-75 text-hover-primary">'.$ClientInfo->firstname.' '.$ClientInfo->lastname.'<span class="fonter-weight-bolder">*</span></a>
							<div class="text-muted">+'.$ClientInfo->mo_country_code.' '.$ClientInfo->mobileno.' <span class="font-weight-bolder">*</span></div>
							<div class="text-muted">'.$ClientInfo->email.'</div>
						</div>
						
						<div class="dropdown dropdown-inline" style="margin-left: 185px;">
							<a href="#" class="btn btn-clean btn-hover-primary btn-sm btn-icon"
								data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="ki ki-bold-more-hor"></i>
							</a>
							<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
								<ul class="navi navi-hover">
									<li class="navi-item">
										<a href="'.url('partners/view/'.$ClientInfo->id.'/client').'" class="cursor-pointer navi-link">
											<span class="navi-text">Edit client details</span>
										</a>
									</li>
									<li class="navi-item">
										<a data-toggle="modal" data-target="#blockClientModal"
											class="cursor-pointer navi-link clickBlockClient" data-client_id="'.$ClientInfo->id.'">
											<span class="navi-text">Block client</span>
										</a>
									</li>
									<li class="navi-item">
										<a class="cursor-pointer navi-link removeClientFromAppoin">
											<span class="navi-text">Remove from appointment</span>
										</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<div class="card-body p-1">
						<div class="total-appoinment-data justify-content-around d-flex">
							<div class="text-center w-100 data pt-1 p-42">
								<h3 class="price font-weight-bolder text-center text-dark">'.count($AppointmentServices).'</h3>
								<p class="title text-muted">Total Booking</p>
							</div>
							<div class=" text-center w-100 data pt-1 p-2">
								<h3 class="price font-weight-bolder text-center text-dark">CA $'.$TotalSpend.'</h3>
								<p class="title text-muted">Total Sales</p>
							</div>
						</div>
						<ul class="nav nav-tabs nav-tabs-line nav-tabs-primary nav-tabs-line-2x" role="tablist">
							<li class="nav-item">
								<a style="margin:0px;padding:0 6px;font-size: 12px;" class="nav-link active" data-toggle="tab" href="#appointments">Appointments ('.count($AppointmentServices).')</a>
							</li>
							<li class="nav-item">
								<a style="margin:0px;padding:0 6px;font-size: 12px;" class="nav-link" data-toggle="tab" href="#products">Products ('.count($soldProduct).')</a>
							</li>
							<li class="nav-item">
								<a style="margin:0px;padding:0 6px;font-size: 12px;" class="nav-link" data-toggle="tab" href="#invoices">Invoices ('.count($clientInvoices).')</a>
							</li>
							<li class="nav-item">
								<a style="margin:0px;padding:0 6px;font-size: 12px;" class="nav-link" data-toggle="tab" href="#consultationforms">Info</a>
							</li>
						</ul>
						<div class="tab-content mt-5" id="myTabContent">
							<div class="tab-pane fade show active" id="appointments"
								role="tabpanel" aria-labelledby="appointments">
								<div class="row">
									<div class="card-body py-2 px-8">
										'.$ClientServices.'
									</div>
								</div>
							</div>
							<div class="tab-pane fade" id="products" role="tabpanel" aria-labelledby="products">
								'.$ClientProducts.'
							</div>
							<div class="tab-pane fade" id="invoices" role="tabpanel" aria-labelledby="invoices">
								<div class="col-12 col-md-12">
									<table class="table table-hover">
										<thead>
											<tr>
												<th>Status</th>
												<th>Invoice#</th>
												<th>Invoice date</th>
												<th>Total</th>
											</tr>
										</thead>
										<tbody>
											'.$ClientInovices.'
										</tbody>
									</table>
								</div>
							</div>
							<div class="tab-pane fade" id="consultationforms" role="tabpanel" aria-labelledby="consultationforms">
								<h6>Email</h6>
								<h6>'.$ClientInfo->email.'</h6>
								<br>
								<h6>Gender</h6>
								<h6>'.$ClientInfo->gender.'</h6>
								<br>
								<h6>Marketing notifications</h6>
								<h6>'.$MarketingNotification.'</h6>
							</div>
						</div>
					</div>
				</div>
			</div>';
			
			$data["status"] = true;
			$data["html"] = $HTML;
            return JsonReturn::success($data);
        }
	}
	
	public function blockClient(Request $request){
		$status = 0;
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
			
			$client_id    = ($request->block_client_id) ? $request->block_client_id : '';
			$block_reason = ($request->block_reason)    ? $request->block_reason    : '';
			$block_notes  = ($request->block_notes)     ? $request->block_notes     : '';
			
			if($block_reason == 'Other'){
				if($block_notes == ''){
					$data["status"]  = false;
					$data["message"] = 'Block notes is required';
					return JsonReturn::success($data);
				}
			}
			
			$ClientInfo = Clients::find($client_id);   
			if(!empty($ClientInfo)){
				$ClientInfo->is_blocked   = 1;
				$ClientInfo->block_reason = $block_reason;
				$ClientInfo->block_notes  = $block_notes;
				$ClientInfo->save();
				
				$data["status"] = true;
				$data["message"] = 'Client has been blocked succesfully.';
				Session::flash('message', 'Client has been blocked succesfully.');
			} else {
				$data["status"] = false;
				$data["message"] = 'Something went wrong!';
			}
			
            return JsonReturn::success($data);
        }
	}
	
	public function unblockClient(Request $request){
		$status = 0;
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
			
			$unblock_client_id    = ($request->unblock_client_id) ? $request->unblock_client_id : '';
			
			$ClientInfo = Clients::find($unblock_client_id);   
			if(!empty($ClientInfo)){
				$ClientInfo->is_blocked   = 0;
				$ClientInfo->block_reason = '';
				$ClientInfo->block_notes  = '';
				$ClientInfo->save();
				
				$data["status"] = true;
				$data["message"] = 'Client has been unblocked succesfully.';
				Session::flash('message', 'Client has been unblocked succesfully.');
			} else {
				$data["status"] = false;
				$data["message"] = 'Something went wrong!';
			}
			
            return JsonReturn::success($data);
        }
	}
	
	public function appointmentStatus(Request $request){
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
			
			$appointment_id     = ($request->appointment_id) ? $request->appointment_id : '';
			$appointment_status = ($request->appointment_status)    ? $request->appointment_status    : 0;
			
			$AppointmentsInfo = Appointments::find($appointment_id);   
			if(!empty($AppointmentsInfo)){
				$AppointmentsInfo->appointment_status = $appointment_status;
				$AppointmentsInfo->updated_at         = date("Y-m-d H:i:s");
				$AppointmentsInfo->save();
				
				$client = Clients::where('id',$AppointmentsInfo->client_id)->first();
				$AppointmentServices = AppointmentServices::where('appointment_id',$AppointmentsInfo->id)->first();

				if(!empty($AppointmentServices)) {
					$serPrice = ServicesPrice::where('id',$AppointmentServices->service_price_id)->first();
					$service = Services::where('id',$serPrice->service_id)->first();
				} else {
					$serPrice = new \stdClass();
					$service = new \stdClass();
				}

				$app_start_datetime = !empty($AppointmentServices) ? $AppointmentServices->appointment_date.' '.$AppointmentServices->start_time : '';
				$client_name = isset($client->firstname) ? $client->firstname : 'Walk-In';
				$client_id = isset($client->id) ? $client->id : '';
				$locationData = Location::find($AppointmentsInfo->location_id);
				$staffUser = User::find($AppointmentsInfo->staff_user_id);
				$staffUserName = isset($staffUser->first_name) ? $staffUser->last_name : '';

				if($appointment_status == 0){
					$data["message"] = 'Appointment has been marked as new succesfully.';
					Session::flash('message', 'Appointment has been marked as new succesfully.');
				} else if($appointment_status == 1){
					$data["message"] = 'Appointment has been marked as confirmed succesfully.';
					Session::flash('message', 'Appointment has been marked as confirmed succesfully.');

					// Notification
					$title = 'Appointment confirmed';
					$description = date('D d M h:ia', strtotime($app_start_datetime)).' '.(isset($service->service_name) ? $service->service_name : '').' for '.$client_name.' with '.$staffUserName.' by '.Auth::user()->first_name.' at '.(isset($locationData->location_name) ? $locationData->location_name : '');

					$this->notificationRepositorie->sendNotification($UserId, $client_id, 'appointment', $AppointmentsInfo->id, $title, $description, $AppointmentsInfo->location_id, 'confirmations', false); 

				} else if($appointment_status == 2){
					$data["message"] = 'Appointment has been marked as arrived succesfully.';
					Session::flash('message', 'Appointment has been marked as arrived succesfully.');

					// Notification
					$totalServices = AppointmentServices::where('appointment_id', $AppointmentsInfo->id)->count();

					$title = $client_name.' has arrived';
					$description = date('D d M h:ia', strtotime($app_start_datetime)).' '.(isset($service->service_name) ? $service->service_name : '').' for '.$totalServices.' services with '.$staffUserName.' at '.(isset($locationData->location_name) ? $locationData->location_name : '');

					$this->notificationRepositorie->sendNotification($UserId, $client_id, 'appointment', $AppointmentsInfo->id, $title, $description, $AppointmentsInfo->location_id, 'arrived', false);
				} else if($appointment_status == 3){
					$data["message"] = 'Appointment has been marked as started succesfully.';
					Session::flash('message', 'Appointment has been marked as started succesfully.');

					// Notification
					$title = 'Appointment started';
					$description = date('D d M h:ia', strtotime($app_start_datetime)).' '.(isset($service->service_name) ? $service->service_name : '').' for '.$client_name.' with '.$staffUserName.' at '.(isset($locationData->location_name) ? $locationData->location_name : '');

					$this->notificationRepositorie->sendNotification($UserId, $client_id, 'appointment', $AppointmentsInfo->id, $title, $description, $AppointmentsInfo->location_id, 'started', false);
				}
				
				$data["status"] = true;
				$data["redirect"] = route('calander');
				
			} else {
				$data["status"] = false;
				$data["message"] = 'Something went wrong!';
			}
			
            return JsonReturn::success($data);
        }
	}
	
	public function cancelAppointment(Request $request)
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
			
			$appointment_id            = ($request->cancel_appointment_id) ? $request->cancel_appointment_id : '';
			$cancel_appointment_reason = ($request->cancel_appointment_reason) ? $request->cancel_appointment_reason : '';
			$cancel_appointment_notify = ($request->cancel_appointment_notify) ? $request->cancel_appointment_notify : '';
			
			$AppointmentsInfo = Appointments::find($appointment_id);   
			if(!empty($AppointmentsInfo)) 
			{				
				/* $onlineSettingData = Online_setting::select('appointment_cancel_time')->where('online_setting.user_id', $AdminId)->first();
				
				if($onlineSettingData->appointment_cancel_time > 0) {
					$AppointmentServices = AppointmentServices::where('appointment_id',$AppointmentsInfo->id)->first();	
					
					$appointment_time = date("H:i", strtotime($AppointmentServices->start_time));
					$curr_time = date("H:i");
					$cancel_time = date("H:i", strtotime("+".$onlineSettingData->appointment_cancel_time." minutes", strtotime($appointment_time)));
					
				}	 
				echo "asd";
				die; */
				$AppointmentsInfo->is_cancelled        = 1;
				$AppointmentsInfo->cancellation_reason = $cancel_appointment_reason;
				$AppointmentsInfo->updated_at          = date("Y-m-d H:i:s");
				$AppointmentsInfo->save();
				
				$data["message"] = 'Appointment has been marked as cancelled succesfully.';
				Session::flash('message', 'Appointment has been marked as cancelled succesfully.');
				
				$data["status"] = true;
				$data["redirect"] = route('calander');
				
				// send email
				$client = Clients::where('id',$AppointmentsInfo->client_id)->first();
				$AppointmentServices = AppointmentServices::where('appointment_id',$AppointmentsInfo->id)->first();
				$serPrice = ServicesPrice::where('id',$AppointmentServices->service_price_id)->first();
				$service = Services::where('id',$serPrice->service_id)->first();
				$appointment = $AppointmentsInfo;
				$remidernoti = cancellationNotification::where('user_id',$AdminId)->first();

				if(!empty($client) && !empty($remidernoti)){
					$email = $client->email;
					$FROM_EMAIL     = env("MAIL_FROM_ADDRESS", "info@ikotel.ca");
					$FROM_NAME      = 'Scheduledown';
					$TO_EMAIL       = $email;
					$CC_EMAIL       = 'tjcloudtest2@gmail.com';
					$SUBJECT        = 'Cancel Appointment';
					// $MESSAGE        = 'Hi  Please see attached purchase order Have a great day! ';
					$OrderId        = 0;
					
					$UniqueId       = $this->unique_code(30).time();
					
					$SendMail = Mail::to($TO_EMAIL)->cc([$CC_EMAIL])->send(new cancelAppointment($FROM_EMAIL,$FROM_NAME,$SUBJECT,$AppointmentServices,$appointment,$UniqueId,$client,$service,$remidernoti));	
					
					EmailLog::create([
						'user_id' => $AdminId,
						'client_id' => $client->id,
                        'appointment_id' => $appointment->id,
						'unique_id' => $UniqueId,
						'from_email' => $FROM_EMAIL,
						'to_email' => $TO_EMAIL,
						'module_type_text' => 'Cancel Appointment Email',
						'created_at'       => date("Y-m-d H:i:s")
					]);
				}
				//end

			} else {
				$data["status"] = false;
				$data["message"] = 'Something went wrong!';
			}
			
            return JsonReturn::success($data);
        }
	}
	
	public function appointmentNoshow(Request $request){
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
			
			$appointment_id = ($request->appointment_id) ? $request->appointment_id : '';
			$noshow_status  = ($request->noshow_status) ? $request->noshow_status : 0;
			
			$AppointmentsInfo = Appointments::find($appointment_id);   
			if(!empty($AppointmentsInfo)){
				
				$currentDate     = date("Y-m-d");
				$appointmateDate = date("Y-m-d",strtotime($AppointmentsInfo->appointment_date));
				
				if($appointmateDate > $currentDate){
					$data["status"] = false;
					$data["message"] = 'You can not mark upcoming appointment as now show!';
					return JsonReturn::success($data);
				}
				
				if($noshow_status == 0){
					$AppointmentsInfo->is_noshow          = $noshow_status;
					$AppointmentsInfo->appointment_status = 0;
					$data["message"] = 'Appointment has been set to new succesfully.';
					Session::flash('message', 'Appointment has been set to new succesfully.');
				} else {
					$AppointmentsInfo->is_noshow  = $noshow_status;
					$data["message"] = 'Appointment has been set to no show succesfully.';
					Session::flash('message', 'Appointment has been set to no show succesfully.');

					// send email
					$client = Clients::where('id',$AppointmentsInfo->client_id)->first();
					$AppointmentServices = AppointmentServices::where('appointment_id',$AppointmentsInfo->id)->first();
					$serPrice = ServicesPrice::where('id',$AppointmentServices->service_price_id)->first();
					$service = Services::where('id',$serPrice->service_id)->first();
					$appointment = $AppointmentsInfo;
					$remidernoti = noShowNotification::where('user_id',$AdminId)->first();

					if(!empty($client) && !empty($remidernoti)){
						$email = $client->email;
						$FROM_EMAIL     = env("MAIL_FROM_ADDRESS", "info@ikotel.ca");
						$FROM_NAME      = 'Scheduledown';
						$TO_EMAIL       = $email;
						$CC_EMAIL       = 'tjcloudtest2@gmail.com';
						$SUBJECT        = 'Appointment';
						$OrderId        = 0;
						
						$UniqueId       = $this->unique_code(30).time();
						
						$SendMail = Mail::to($TO_EMAIL)->cc([$CC_EMAIL])->send(new noShowAppointments($FROM_EMAIL,$FROM_NAME,$SUBJECT,$AppointmentServices,$appointment,$UniqueId,$client,$service,$remidernoti));	
						
						EmailLog::create([
							'user_id' => $AdminId,
							'client_id' => $client->id,
                            'appointment_id' => $appointment->id,
							'unique_id' => $UniqueId,
							'from_email' => $FROM_EMAIL,
							'to_email' => $TO_EMAIL,
							'module_type_text' => 'No Show Notification Email',
							'created_at'       => date("Y-m-d H:i:s")
						]);
					}
					//end

					// Notification
					$app_start_datetime = !empty($AppointmentServices) ? $AppointmentServices->appointment_date.' '.$AppointmentServices->start_time : '';
					$client_name = isset($client->firstname) ? $client->firstname : 'Walk-In';
					$client_id = isset($client->id) ? $client->id : '';
					$locationData = Location::find($AppointmentsInfo->location_id);
					$staffUser = User::find($AppointmentsInfo->staff_user_id);
					$staffUserName = isset($staffUser->first_name) ? $staffUser->last_name : '';

					$title = $client_name.' did not show';
					$description = date('D d M h:ia', strtotime($app_start_datetime)).' '.(isset($service->service_name) ? $service->service_name : '').' with '.$staffUserName.' at '.(isset($locationData->location_name) ? $locationData->location_name : '');

					$this->notificationRepositorie->sendNotification($UserId, $client_id, 'appointment', $appointment->id, $title, $description, $appointment->location_id, 'no_shows', false); 
				}
				
				$AppointmentsInfo->updated_at          = date("Y-m-d H:i:s");
				$AppointmentsInfo->save();
				
				$data["status"] = true;
				$data["redirect"] = route('calander');
				
			} else {
				$data["status"] = false;
				$data["message"] = 'Something went wrong!';
			}
			
            return JsonReturn::success($data);
        }
	}
	
	public function editAppointmentNote(Request $request){
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
			
			$appointment_id     = ($request->edit_appointment_id) ? $request->edit_appointment_id : '';
			$appointment_notes  = ($request->appointment_notes) ? $request->appointment_notes : 0;
			
			$AppointmentsInfo = Appointments::find($appointment_id);   
			if(!empty($AppointmentsInfo)){
				$AppointmentsInfo->appointment_notes = $appointment_notes;
				$AppointmentsInfo->updated_at        = date("Y-m-d H:i:s");
				$AppointmentsInfo->save();
				
				$data["status"] = true;
				$data["message"] = 'Appointment notes has been updated succesfully.';
				Session::flash('message', 'Appointment notes has been updated succesfully.');
				$data["redirect"] = route('calander');
			} else {
				$data["status"] = false;
				$data["message"] = 'Something went wrong!';
			}
			
            return JsonReturn::success($data);
        }
	}
	
	public function removeAppointmentService(Request $request){
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
			
			$appointmentServiceID = ($request->appointmentServiceID) ? $request->appointmentServiceID : '';
			$AppointmentServices = AppointmentServices::find($appointmentServiceID);   
			if(!empty($AppointmentServices)){
				if($AppointmentServices->delete()) {
					$data["status"] = true;
					$data["message"] = 'Service has been removed succesfully.';
				} else {
					$data["status"] = false;
					$data["message"] = 'Something went wrong!';
				}
			} else {
				$data["status"] = false;
				$data["message"] = 'Something went wrong!';
			}
			
            return JsonReturn::success($data);
        }
	}
	
	public function viewInvoice($invoiceId = null)
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
		
		if($invoiceId != '')
		{
			$invoiceId = Crypt::encryptString($invoiceId);
			$invID = Crypt::decryptString($invoiceId);
			
			$Invoice = Invoice::select('*')->where('id',$invID)->where('user_id', $AdminId)->orderBy('id', 'desc')->get()->first()->toArray();
			$checkInvRefund = Invoice::select('id')->where('original_invoice_id',$invID)->where('user_id', $AdminId)->first();
			
			$isRefunded = 0;
			if(!empty($checkInvRefund)) {
				$isRefunded = 1;	
			}	
			
			$InvoiceItems = InvoiceItems::select('*')->where('invoice_id',$invID)->orderBy('id', 'desc')->get()->toArray();
			
			$InvoiceTemplate = InvoiceTemplate::where('user_id',$AdminId)->first();
			
			$InvoiceItemsInfo = array();
			if(!empty($InvoiceItems)){
				foreach($InvoiceItems as $InvoiceItemDetail){
					if($InvoiceItemDetail['item_type'] == 'services'){
						
						$SERVICE_ID = $InvoiceItemDetail['item_id'];
						
						$singleService = Services::select('id','service_name')->where('id', $SERVICE_ID)->where('user_id', $AdminId)->orderBy('order_id', 'asc')->get()->first();
						
						$tempItemDetail['main_title'] = ($singleService) ? $singleService->service_name : '';
						
						$CurrentStaff = Staff::getStaffDetailByID($InvoiceItemDetail['staff_id']);
					
						$staffFirstName = ($CurrentStaff) ? $CurrentStaff->first_name : '';
						$staffLastName  = ($CurrentStaff) ? $CurrentStaff->last_name : '';
						$tempItemDetail['staff_name']    = 'With '.$staffFirstName.' '.$staffLastName;
						
						$tempItemDetail['quantity']      = $InvoiceItemDetail['quantity'];
						$tempItemDetail['item_og_price'] = ($InvoiceItemDetail['item_og_price'] * $InvoiceItemDetail['quantity']);
						$tempItemDetail['item_price']    = ($InvoiceItemDetail['item_price'] * $InvoiceItemDetail['quantity']);
						array_push($InvoiceItemsInfo,$tempItemDetail);
					} else if($InvoiceItemDetail['item_type'] == 'product'){
						
						$PRODUCT_ID = $InvoiceItemDetail['item_id'];
						
						$InventoryProducts = InventoryProducts::select('id','product_name')->where('id', $PRODUCT_ID)->where('user_id', $AdminId)->orderBy('id', 'desc')->get()->first();
						
						$tempItemDetail['main_title'] = ($InventoryProducts) ? $InventoryProducts->product_name : '';
						
						$CurrentStaff = Staff::getStaffDetailByID($InvoiceItemDetail['staff_id']);
					
						$staffFirstName = ($CurrentStaff) ? $CurrentStaff->first_name : '';
						$staffLastName  = ($CurrentStaff) ? $CurrentStaff->last_name : '';
						$tempItemDetail['staff_name']    = $staffFirstName.' '.$staffLastName;
						
						$tempItemDetail['quantity']      = $InvoiceItemDetail['quantity'];
						$tempItemDetail['item_og_price'] = ($InvoiceItemDetail['item_og_price'] * $InvoiceItemDetail['quantity']);
						$tempItemDetail['item_price']    = ($InvoiceItemDetail['item_price'] * $InvoiceItemDetail['quantity']);
						array_push($InvoiceItemsInfo,$tempItemDetail);
					} else if($InvoiceItemDetail['item_type'] == 'voucher'){
						
						$VOUCHER_ID = $InvoiceItemDetail['item_id'];
						
						$Voucher = SoldVoucher::select('vouchers.title','sold_voucher.voucher_code','sold_voucher.end_date','vouchers.name')->leftJoin('vouchers', 'vouchers.id', '=', 'sold_voucher.voucher_id')->where('sold_voucher.voucher_id', $VOUCHER_ID)->where('sold_voucher.invoice_id', $invID)->where('sold_voucher.user_id', $AdminId)->orderBy('sold_voucher.id', 'desc')->get()->toArray();
						
						if(!empty($Voucher)){
							foreach($Voucher as $VoucherData){
								$CurrentStaff = Staff::getStaffDetailByID($InvoiceItemDetail['staff_id']);
					
								$staffFirstName = ($CurrentStaff) ? $CurrentStaff->first_name : '';
								$staffLastName  = ($CurrentStaff) ? $CurrentStaff->last_name : '';
								
								$tempItemDetail['main_title'] = ($VoucherData['name']) ? $VoucherData['name'] : '';
								$tempItemDetail['staff_name'] = 'Code: '.$VoucherData['voucher_code'].', expires on '.date("d M Y",strtotime($VoucherData['end_date'])).', '.$staffFirstName.' '.$staffLastName;
								
								$tempItemDetail['quantity']      = 1;
								$tempItemDetail['item_og_price'] = $InvoiceItemDetail['item_og_price'];
								$tempItemDetail['item_price']    = $InvoiceItemDetail['item_price'];
								array_push($InvoiceItemsInfo,$tempItemDetail);
							}
						}
					} else if($InvoiceItemDetail['item_type'] == 'paidplan'){
						$PAIDPLAN_ID = $InvoiceItemDetail['item_id'];
						
						$PaidPlan = SoldPaidPlan::select('paid_plan.name','sold_paidplan.end_date')->leftJoin('paid_plan', 'paid_plan.id', '=', 'sold_paidplan.paidplan_id')->where('sold_paidplan.paidplan_id', $PAIDPLAN_ID)->where('sold_paidplan.invoice_id', $invID)->where('sold_paidplan.user_id', $AdminId)->get()->first()->toArray();
						
						$PlanName = ($PaidPlan) ? $PaidPlan['name'] : 'N/A';
						$ExpireOn = ($PaidPlan) ? date("d M Y",strtotime($PaidPlan['end_date'])) : 'N/A';
						
						$tempItemDetail['main_title'] = $PlanName;
						$tempItemDetail['staff_name'] = 'Plan: '.$PlanName.', expires on '.$ExpireOn;
						
						$tempItemDetail['quantity']      = $InvoiceItemDetail['quantity'];
						$tempItemDetail['item_og_price'] = ($InvoiceItemDetail['item_og_price'] * $InvoiceItemDetail['quantity']);
						$tempItemDetail['item_price']    = ($InvoiceItemDetail['item_price'] * $InvoiceItemDetail['quantity']);
						array_push($InvoiceItemsInfo,$tempItemDetail);
					}
				}
			}
			
			$VoucherSold = SoldVoucher::select('locations.location_name','locations.location_address','locations.location_image','vouchers.title','sold_voucher.voucher_code','sold_voucher.end_date','vouchers.name','sold_voucher.price','vouchers.color')->leftJoin('vouchers', 'vouchers.id', '=', 'sold_voucher.voucher_id')->leftJoin('locations', 'locations.id', '=', 'sold_voucher.location_id')->where('sold_voucher.invoice_id', $invID)->where('sold_voucher.user_id', $AdminId)->orderBy('sold_voucher.id', 'desc')->get()->toArray();
			
			$PlanSold = SoldPaidPlan::select('locations.location_name','locations.location_address','locations.location_image','paid_plan.name','sold_paidplan.end_date','sold_paidplan.no_of_sessions','sold_paidplan.valid_for','sold_paidplan.end_date','sold_paidplan.price')->leftJoin('paid_plan', 'paid_plan.id', '=', 'sold_paidplan.paidplan_id')->leftJoin('locations', 'locations.id', '=', 'sold_paidplan.location_id')->where('sold_paidplan.invoice_id', $invID)->where('sold_paidplan.user_id', $AdminId)->get()->toArray();
			
			$InvoiceTaxes = InvoiceTaxes::select('invoice_taxes.tax_rate','invoice_taxes.tax_amount','taxes.tax_name')->leftJoin('taxes', 'taxes.id', '=', 'invoice_taxes.tax_id')->where('invoice_taxes.invoice_id', $invID)->get()->toArray();
			
			$TotalStaffTip = StaffTip::select(DB::raw('SUM(tip_amount) as total_tip'))->where('staff_tip.inovice_id', $invID)->get()->toArray();
			
			$LocationInfo = array();
			if($Invoice['location_id'] != 0){
				$LocationInfo = Location::select('location_name')->where(['id'=>$Invoice['location_id']])->orderBy('id', 'ASC')->get()->first()->toArray();
			}
			
			$PaymentDoneBy = array();
			if($Invoice['created_by'] != 0){
				$PaymentDoneBy = Staff::select('staff.staff_user_id','users.first_name','users.last_name')->join('users','users.id','=','staff.staff_user_id')->where(['staff.staff_user_id'=>$Invoice['created_by']])->orderBy('staff.id', 'ASC')->get()->first()->toArray();	
			}
			
			// get previous history
			$PreviousAppointment = array();
			$PreviousAppointmentServices = array();
			$PreviousServices = array();
			$TotalSpend = 0;
			
			$ClientInfo      = array();
			$soldProduct     = array();
			$ClientProducts  = '';
			$clientInvoices  = array();
			$ClientInovices  = '';
			
			if($Invoice['client_id'] != 0)
			{
				$ClientInfo = Clients::getClientbyID($Invoice['client_id']);
				
				$PreviousAppointment = Appointments::select('*')->where('client_id',$Invoice['client_id'])->where('user_id', $AdminId)->orderBy('id', 'desc')->get()->toArray();
				
				$PreviousAppointmentServices = AppointmentServices::select('appointment_services.*')->join('appointments','appointments.id','=','appointment_services.appointment_id')->where('appointments.client_id',$Invoice['client_id'])->where('appointments.user_id', $AdminId)->orderBy('id', 'desc')->get()->toArray();
				
				if(!empty($PreviousAppointmentServices))
				{
					foreach($PreviousAppointmentServices as $AppointmentServiceData)
					{
						$appointment_date = $AppointmentServiceData['appointment_date'];
						$start_time       = $AppointmentServiceData['start_time'];
						$duration         = $this->hoursandmins($AppointmentServiceData['duration']);
						$special_price    = $AppointmentServiceData['special_price'];
						$StaffUserId      = $AppointmentServiceData['staff_user_id'];
						
						$StaffDetails = Staff::select('staff.staff_user_id','users.first_name','users.last_name')->join('users','users.id','=','staff.staff_user_id')->where(['staff.staff_user_id'=>$StaffUserId])->orderBy('staff.id', 'ASC')->get()->first()->toArray();
						
						if(!empty($StaffDetails)){
							$staff_name   = $StaffDetails['first_name'].' '.$StaffDetails['last_name'];
						} else {
							$staff_name   = 'N/A';
						}
						
						$servicePrices = ServicesPrice::select('pricing_name')->where('id',$AppointmentServiceData['service_price_id'])->orderBy('id', 'asc')->get()->first();
						
						$serviceName = '';
						if(!empty($servicePrices)){
							$serviceName = $servicePrices->pricing_name;
						} else {
							$serviceName = 'N/A';
						}
						
						$TotalSpend = $TotalSpend + $special_price;
						
						$tempServices['appointment_date_month'] = date("d M",strtotime($appointment_date));
						$tempServices['appointment_date_hours'] = date("D",strtotime($appointment_date)).' '.date('h:ia',strtotime($start_time));
						$tempServices['serviceName']            = $serviceName;
						$tempServices['duration']               = $duration;
						$tempServices['staff_name']             = $staff_name;
						$tempServices['special_price']          = $special_price;
						array_push($PreviousServices,$tempServices);
					}
				}
				
				$soldProduct = InvoiceItems::select('invoice_items.quantity','invoice_items.item_price','invoice_items.created_at','inventory_products.product_name')->join('inventory_products','inventory_products.id','=','invoice_items.item_id')->where('invoice_items.client_id',$Invoice['client_id'])->where('invoice_items.item_type', 'product')->orderBy('invoice_items.id', 'desc')->get();

				$ClientProducts = "";			
				if(!empty($soldProduct)) {
					foreach($soldProduct as $key => $product) {
						$ClientProducts .= '
							<div class="client-apoinments-list mb-6">
								<div class="d-flex align-items-center flex-grow-1">
									<div class="d-flex flex-wrap align-items-center justify-content-between w-100">
										<div class="d-flex flex-column align-items-cente py-2 w-75">
											<h6 class="text-muted font-weight-bold">'.$product->quantity.' sold</h6>
											<h6 class="text-muted font-weight-bold">'.$product->product_name.'</h6>
											<h6 class="text-muted font-weight-bold">'.date("D, d M Y", strtotime($product->created_at)).'</h6>
										</div>
										<h6 class="font-weight-bolder py-4">CA $'.($product->quantity * $product->item_price).'</h6>
									</div>
								</div>
							</div>';	
					}	
				} else {
					$ClientProducts .= '<h3>No product</h3>';
				}		
				
				$clientInvoices = Invoice::select('invoice.id','invoice.inovice_final_total','invoice.payment_date','invoice.invoice_status')->where('invoice.client_id',$Invoice['client_id'])->orderBy('invoice.id', 'desc')->get();
				
				$TotalSales = 0;
				$ClientInovices = "";
				if(!empty($clientInvoices)) {
					foreach($clientInvoices as $key => $inv) {
						$stats = "";
						if($inv->invoice_status == 0) {
							$stats = "Unpaid";
						} else if($inv->invoice_status == 1) {
							$stats = "Completed";
						} else if($inv->invoice_status == 2) {
							$stats = "Refund";
						} else if($inv->invoice_status == 3) {
							$stats = "Void";
						}	
						$ClientInovices .= '
							<tr>
								<td><span class="badge badge-pill badge-success">'.$stats.'</span></td>
								<td>'.$inv->id.'</td>
								<td>'.date("d M Y", strtotime($inv->payment_date)).'</td>
								<td>CA $'.$inv->inovice_final_total.'</td>
							</tr>';	
						$TotalSales += $inv->inovice_final_total;
					}	
				} else {
					$ClientInovices .= '<h3>No inovice</h3>';
				}
			}
			
			return view('appointments.viewInvoice',compact('invoiceId','Invoice','InvoiceItemsInfo','ClientInfo','InvoiceTaxes','TotalStaffTip','LocationInfo','PaymentDoneBy','VoucherSold','PlanSold','InvoiceTemplate','isRefunded','PreviousAppointment','PreviousServices','TotalSpend','soldProduct','ClientProducts','clientInvoices','ClientInovices'));
		}
	}
	
	public function applyPayment($invoiceId = null)
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
		
		if($invoiceId != '')
		{
			$invoiceId = Crypt::encryptString($invoiceId);
			$invID = Crypt::decryptString($invoiceId);
			
			$Invoice = Invoice::select('*')->where('id',$invID)->where('user_id', $AdminId)->orderBy('id', 'desc')->get()->first()->toArray();
			$checkInvRefund = Invoice::select('id')->where('original_invoice_id',$invID)->where('user_id', $AdminId)->first();
			
			$isRefunded = 0;
			if(!empty($checkInvRefund)) {
				$isRefunded = 1;	
			}	
			
			$InvoiceItems = InvoiceItems::select('*')->where('invoice_id',$invID)->orderBy('id', 'desc')->get()->toArray();
			
			$InvoiceTemplate = InvoiceTemplate::where('user_id',$AdminId)->first();
			
			$InvoiceItemsInfo = array();
			if(!empty($InvoiceItems)){
				foreach($InvoiceItems as $InvoiceItemDetail){
					if($InvoiceItemDetail['item_type'] == 'services'){
						
						$SERVICE_ID = $InvoiceItemDetail['item_id'];
						
						$singleService = Services::select('id','service_name')->where('id', $SERVICE_ID)->where('user_id', $AdminId)->orderBy('order_id', 'asc')->get()->first();
						
						$tempItemDetail['main_title'] = ($singleService) ? $singleService->service_name : '';
						
						$CurrentStaff = Staff::getStaffDetailByID($InvoiceItemDetail['staff_id']);
					
						$staffFirstName = ($CurrentStaff) ? $CurrentStaff->first_name : '';
						$staffLastName  = ($CurrentStaff) ? $CurrentStaff->last_name : '';
						$tempItemDetail['staff_name']    = 'With '.$staffFirstName.' '.$staffLastName;
						
						$tempItemDetail['quantity']      = $InvoiceItemDetail['quantity'];
						$tempItemDetail['item_og_price'] = ($InvoiceItemDetail['item_og_price'] * $InvoiceItemDetail['quantity']);
						$tempItemDetail['item_price']    = ($InvoiceItemDetail['item_price'] * $InvoiceItemDetail['quantity']);
						array_push($InvoiceItemsInfo,$tempItemDetail);
					} else if($InvoiceItemDetail['item_type'] == 'product'){
						
						$PRODUCT_ID = $InvoiceItemDetail['item_id'];
						
						$InventoryProducts = InventoryProducts::select('id','product_name')->where('id', $PRODUCT_ID)->where('user_id', $AdminId)->orderBy('id', 'desc')->get()->first();
						
						$tempItemDetail['main_title'] = ($InventoryProducts) ? $InventoryProducts->product_name : '';
						
						$CurrentStaff = Staff::getStaffDetailByID($InvoiceItemDetail['staff_id']);
					
						$staffFirstName = ($CurrentStaff) ? $CurrentStaff->first_name : '';
						$staffLastName  = ($CurrentStaff) ? $CurrentStaff->last_name : '';
						$tempItemDetail['staff_name']    = $staffFirstName.' '.$staffLastName;
						
						$tempItemDetail['quantity']      = $InvoiceItemDetail['quantity'];
						$tempItemDetail['item_og_price'] = ($InvoiceItemDetail['item_og_price'] * $InvoiceItemDetail['quantity']);
						$tempItemDetail['item_price']    = ($InvoiceItemDetail['item_price'] * $InvoiceItemDetail['quantity']);
						array_push($InvoiceItemsInfo,$tempItemDetail);
					} else if($InvoiceItemDetail['item_type'] == 'voucher'){
						
						$VOUCHER_ID = $InvoiceItemDetail['item_id'];
						
						$Voucher = SoldVoucher::select('vouchers.title','sold_voucher.voucher_code','sold_voucher.end_date','vouchers.name')->leftJoin('vouchers', 'vouchers.id', '=', 'sold_voucher.voucher_id')->where('sold_voucher.voucher_id', $VOUCHER_ID)->where('sold_voucher.invoice_id', $invID)->where('sold_voucher.user_id', $AdminId)->orderBy('sold_voucher.id', 'desc')->get()->toArray();
						
						if(!empty($Voucher)){
							foreach($Voucher as $VoucherData){
								$CurrentStaff = Staff::getStaffDetailByID($InvoiceItemDetail['staff_id']);
					
								$staffFirstName = ($CurrentStaff) ? $CurrentStaff->first_name : '';
								$staffLastName  = ($CurrentStaff) ? $CurrentStaff->last_name : '';
								
								$tempItemDetail['main_title'] = ($VoucherData['name']) ? $VoucherData['name'] : '';
								$tempItemDetail['staff_name'] = 'Code: '.$VoucherData['voucher_code'].', expires on '.date("d M Y",strtotime($VoucherData['end_date'])).', '.$staffFirstName.' '.$staffLastName;
								
								$tempItemDetail['quantity']      = 1;
								$tempItemDetail['item_og_price'] = $InvoiceItemDetail['item_og_price'];
								$tempItemDetail['item_price']    = $InvoiceItemDetail['item_price'];
								array_push($InvoiceItemsInfo,$tempItemDetail);
							}
						}
					} else if($InvoiceItemDetail['item_type'] == 'paidplan'){
						$PAIDPLAN_ID = $InvoiceItemDetail['item_id'];
						
						$PaidPlan = SoldPaidPlan::select('paid_plan.name','sold_paidplan.end_date')->leftJoin('paid_plan', 'paid_plan.id', '=', 'sold_paidplan.paidplan_id')->where('sold_paidplan.paidplan_id', $PAIDPLAN_ID)->where('sold_paidplan.invoice_id', $invID)->where('sold_paidplan.user_id', $AdminId)->get()->first()->toArray();
						
						$PlanName = ($PaidPlan) ? $PaidPlan['name'] : 'N/A';
						$ExpireOn = ($PaidPlan) ? date("d M Y",strtotime($PaidPlan['end_date'])) : 'N/A';
						
						$tempItemDetail['main_title'] = $PlanName;
						$tempItemDetail['staff_name'] = 'Plan: '.$PlanName.', expires on '.$ExpireOn;
						
						$tempItemDetail['quantity']      = $InvoiceItemDetail['quantity'];
						$tempItemDetail['item_og_price'] = ($InvoiceItemDetail['item_og_price'] * $InvoiceItemDetail['quantity']);
						$tempItemDetail['item_price']    = ($InvoiceItemDetail['item_price'] * $InvoiceItemDetail['quantity']);
						array_push($InvoiceItemsInfo,$tempItemDetail);
					}
				}
			}
			
			$VoucherSold = SoldVoucher::select('locations.location_name','locations.location_address','locations.location_image','vouchers.title','sold_voucher.voucher_code','sold_voucher.end_date','vouchers.name','sold_voucher.price')->leftJoin('vouchers', 'vouchers.id', '=', 'sold_voucher.voucher_id')->leftJoin('locations', 'locations.id', '=', 'sold_voucher.location_id')->where('sold_voucher.invoice_id', $invID)->where('sold_voucher.user_id', $AdminId)->orderBy('sold_voucher.id', 'desc')->get()->toArray();
			
			$PlanSold = SoldPaidPlan::select('locations.location_name','locations.location_address','locations.location_image','paid_plan.name','sold_paidplan.end_date','sold_paidplan.no_of_sessions','sold_paidplan.valid_for','sold_paidplan.end_date','sold_paidplan.price')->leftJoin('paid_plan', 'paid_plan.id', '=', 'sold_paidplan.paidplan_id')->leftJoin('locations', 'locations.id', '=', 'sold_paidplan.location_id')->where('sold_paidplan.invoice_id', $invID)->where('sold_paidplan.user_id', $AdminId)->get()->toArray();
			
			$InvoiceTaxes = InvoiceTaxes::select('invoice_taxes.tax_rate','invoice_taxes.tax_amount','taxes.tax_name')->leftJoin('taxes', 'taxes.id', '=', 'invoice_taxes.tax_id')->where('invoice_taxes.invoice_id', $invID)->get()->toArray();
			
			$TotalStaffTip = StaffTip::select(DB::raw('SUM(tip_amount) as total_tip'))->where('staff_tip.inovice_id', $invID)->get()->toArray();
			
			$LocationInfo = array();
			if($Invoice['location_id'] != 0){
				$LocationInfo = Location::select('location_name')->where(['id'=>$Invoice['location_id']])->orderBy('id', 'ASC')->get()->first()->toArray();
			}
			
			$PaymentDoneBy = array();
			if($Invoice['created_by'] != 0){
				$PaymentDoneBy = Staff::select('staff.staff_user_id','users.first_name','users.last_name')->join('users','users.id','=','staff.staff_user_id')->where(['staff.staff_user_id'=>$Invoice['created_by']])->orderBy('staff.id', 'ASC')->get()->first()->toArray();	
			}
			
			// get previous history
			$PreviousAppointment = array();
			$PreviousAppointmentServices = array();
			$PreviousServices = array();
			$TotalSpend = 0;
			
			$ClientInfo      = array();
			$soldProduct     = array();
			$ClientProducts  = '';
			$clientInvoices  = array();
			$ClientInovices  = '';
			
			if($Invoice['client_id'] != 0)
			{
				$ClientInfo = Clients::getClientbyID($Invoice['client_id']);
				
				$PreviousAppointment = Appointments::select('*')->where('client_id',$Invoice['client_id'])->where('user_id', $AdminId)->orderBy('id', 'desc')->get()->toArray();
				
				$PreviousAppointmentServices = AppointmentServices::select('appointment_services.*')->join('appointments','appointments.id','=','appointment_services.appointment_id')->where('appointments.client_id',$Invoice['client_id'])->where('appointments.user_id', $AdminId)->orderBy('id', 'desc')->get()->toArray();
				
				if(!empty($PreviousAppointmentServices))
				{
					foreach($PreviousAppointmentServices as $AppointmentServiceData)
					{
						$appointment_date = $AppointmentServiceData['appointment_date'];
						$start_time       = $AppointmentServiceData['start_time'];
						$duration         = $this->hoursandmins($AppointmentServiceData['duration']);
						$special_price    = $AppointmentServiceData['special_price'];
						$StaffUserId      = $AppointmentServiceData['staff_user_id'];
						
						$StaffDetails = Staff::select('staff.staff_user_id','users.first_name','users.last_name')->join('users','users.id','=','staff.staff_user_id')->where(['staff.staff_user_id'=>$StaffUserId])->orderBy('staff.id', 'ASC')->get()->first()->toArray();
						
						if(!empty($StaffDetails)){
							$staff_name   = $StaffDetails['first_name'].' '.$StaffDetails['last_name'];
						} else {
							$staff_name   = 'N/A';
						}
						
						$servicePrices = ServicesPrice::select('pricing_name')->where('id',$AppointmentServiceData['service_price_id'])->orderBy('id', 'asc')->get()->first();
						
						$serviceName = '';
						if(!empty($servicePrices)){
							$serviceName = $servicePrices->pricing_name;
						} else {
							$serviceName = 'N/A';
						}
						
						$TotalSpend = $TotalSpend + $special_price;
						
						$tempServices['appointment_date_month'] = date("d M",strtotime($appointment_date));
						$tempServices['appointment_date_hours'] = date("D",strtotime($appointment_date)).' '.date('h:ia',strtotime($start_time));
						$tempServices['serviceName']            = $serviceName;
						$tempServices['duration']               = $duration;
						$tempServices['staff_name']             = $staff_name;
						$tempServices['special_price']          = $special_price;
						array_push($PreviousServices,$tempServices);
					}
				}
				
				$soldProduct = InvoiceItems::select('invoice_items.quantity','invoice_items.item_price','invoice_items.created_at','inventory_products.product_name')->join('inventory_products','inventory_products.id','=','invoice_items.item_id')->where('invoice_items.client_id',$Invoice['client_id'])->where('invoice_items.item_type', 'product')->orderBy('invoice_items.id', 'desc')->get();

				$ClientProducts = "";			
				if(!empty($soldProduct)) {
					foreach($soldProduct as $key => $product) {
						$ClientProducts .= '
							<div class="client-apoinments-list mb-6">
								<div class="d-flex align-items-center flex-grow-1">
									<div class="d-flex flex-wrap align-items-center justify-content-between w-100">
										<div class="d-flex flex-column align-items-cente py-2 w-75">
											<h6 class="text-muted font-weight-bold">'.$product->quantity.' sold</h6>
											<h6 class="text-muted font-weight-bold">'.$product->product_name.'</h6>
											<h6 class="text-muted font-weight-bold">'.date("D, d M Y", strtotime($product->created_at)).'</h6>
										</div>
										<h6 class="font-weight-bolder py-4">CA $'.($product->quantity * $product->item_price).'</h6>
									</div>
								</div>
							</div>';	
					}	
				} else {
					$ClientProducts .= '<h3>No product</h3>';
				}		
				
				$clientInvoices = Invoice::select('invoice.id','invoice.inovice_final_total','invoice.payment_date','invoice.invoice_status')->where('invoice.client_id',$Invoice['client_id'])->orderBy('invoice.id', 'desc')->get();
				
				$TotalSales = 0;
				$ClientInovices = "";
				if(!empty($clientInvoices)) {
					foreach($clientInvoices as $key => $inv) {
						$stats = "";
						if($inv->invoice_status == 0) {
							$stats = "Unpaid";
						} else if($inv->invoice_status == 1) {
							$stats = "Completed";
						} else if($inv->invoice_status == 2) {
							$stats = "Refund";
						} else if($inv->invoice_status == 3) {
							$stats = "Void";
						}	
						$ClientInovices .= '
							<tr>
								<td><span class="badge badge-pill badge-success">'.$stats.'</span></td>
								<td>'.$inv->id.'</td>
								<td>'.date("d M Y", strtotime($inv->payment_date)).'</td>
								<td>CA $'.$inv->inovice_final_total.'</td>
							</tr>';	
						$TotalSales += $inv->inovice_final_total;
					}	
				} else {
					$ClientInovices .= '<h3>No inovice</h3>';
				}
			}
			
			$paymentTypes = paymentType::select('id','payment_type')->where('user_id', $AdminId)->orderBy('order_id', 'asc')->get();
			
			$staffLists = Staff::select('users.id','users.first_name','users.last_name')->leftJoin('users', 'users.id', '=', 'staff.staff_user_id')->where('staff.user_id', $AdminId)->orderBy('staff.order_id', 'asc')->get()->toArray();
			$userData = User::select('users.id','users.first_name','users.last_name')->where('id', $AdminId)->first();
			
			$tmp_arr = array(
				'id' => $userData->id,
				'first_name' => $userData->first_name,
				'last_name' => $userData->last_name
			);
			array_push($staffLists, $tmp_arr);
			
			return view('appointments.applyPayment',compact('invoiceId','Invoice','InvoiceItemsInfo','ClientInfo','InvoiceTaxes','TotalStaffTip','LocationInfo','PaymentDoneBy','VoucherSold','PlanSold','InvoiceTemplate','isRefunded','paymentTypes','staffLists','PreviousAppointment','PreviousServices','TotalSpend','soldProduct','ClientProducts','clientInvoices','ClientInovices'));
		}
	}
	
	public function sendInvoiceMail(Request $request){
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
			
			$clientInvoiceEmail = ($request->clientInvoiceEmail) ? $request->clientInvoiceEmail : '';
			$invoiceId          = ($request->invoiceId) ? $request->invoiceId : '';
			
			$fileName = ($invoiceId) ? $invoiceId : 0;
			
			$Invoice = Invoice::select('*')->where('id',$invoiceId)->where('user_id', $AdminId)->orderBy('id', 'desc')->get()->first()->toArray();
			$InvoiceItems = InvoiceItems::select('*')->where('invoice_id',$invoiceId)->orderBy('id', 'desc')->get()->toArray();
			
			$InvoiceTemplate = InvoiceTemplate::where('user_id',$AdminId)->first();
			
			$InvoiceItemsInfo = array();
			if(!empty($InvoiceItems)){
				foreach($InvoiceItems as $InvoiceItemDetail){
					if($InvoiceItemDetail['item_type'] == 'services'){
						
						$SERVICE_ID = $InvoiceItemDetail['item_id'];
						
						$singleService = Services::select('id','service_name')->where('id', $SERVICE_ID)->where('user_id', $AdminId)->orderBy('order_id', 'asc')->get()->first();
						
						$tempItemDetail['main_title'] = ($singleService) ? $singleService->service_name : '';
						
						$CurrentStaff = Staff::getStaffDetailByID($InvoiceItemDetail['staff_id']);
					
						$staffFirstName = ($CurrentStaff) ? $CurrentStaff->first_name : '';
						$staffLastName  = ($CurrentStaff) ? $CurrentStaff->last_name : '';
						$tempItemDetail['staff_name']    = 'With '.$staffFirstName.' '.$staffLastName;
						
						$tempItemDetail['quantity']      = $InvoiceItemDetail['quantity'];
						$tempItemDetail['item_og_price'] = ($InvoiceItemDetail['item_og_price'] * $InvoiceItemDetail['quantity']);
						$tempItemDetail['item_price']    = ($InvoiceItemDetail['item_price'] * $InvoiceItemDetail['quantity']);
						array_push($InvoiceItemsInfo,$tempItemDetail);
					} else if($InvoiceItemDetail['item_type'] == 'product'){
						
						$PRODUCT_ID = $InvoiceItemDetail['item_id'];
						
						$InventoryProducts = InventoryProducts::select('id','product_name')->where('id', $PRODUCT_ID)->where('user_id', $AdminId)->orderBy('id', 'desc')->get()->first();
						
						$tempItemDetail['main_title'] = ($InventoryProducts) ? $InventoryProducts->product_name : '';
						
						$CurrentStaff = Staff::getStaffDetailByID($InvoiceItemDetail['staff_id']);
					
						$staffFirstName = ($CurrentStaff) ? $CurrentStaff->first_name : '';
						$staffLastName  = ($CurrentStaff) ? $CurrentStaff->last_name : '';
						$tempItemDetail['staff_name']    = $staffFirstName.' '.$staffLastName;
						
						$tempItemDetail['quantity']      = $InvoiceItemDetail['quantity'];
						$tempItemDetail['item_og_price'] = ($InvoiceItemDetail['item_og_price'] * $InvoiceItemDetail['quantity']);
						$tempItemDetail['item_price']    = ($InvoiceItemDetail['item_price'] * $InvoiceItemDetail['quantity']);
						array_push($InvoiceItemsInfo,$tempItemDetail);
					} else if($InvoiceItemDetail['item_type'] == 'voucher'){
						
						$VOUCHER_ID = $InvoiceItemDetail['item_id'];
						
						$Voucher = SoldVoucher::select('vouchers.title','sold_voucher.voucher_code','sold_voucher.end_date','vouchers.name')->leftJoin('vouchers', 'vouchers.id', '=', 'sold_voucher.voucher_id')->where('sold_voucher.voucher_id', $VOUCHER_ID)->where('sold_voucher.invoice_id', $invoiceId)->where('sold_voucher.user_id', $AdminId)->orderBy('sold_voucher.id', 'desc')->get()->toArray();
						
						if(!empty($Voucher)){
							foreach($Voucher as $VoucherData){
								$CurrentStaff = Staff::getStaffDetailByID($InvoiceItemDetail['staff_id']);
					
								$staffFirstName = ($CurrentStaff) ? $CurrentStaff->first_name : '';
								$staffLastName  = ($CurrentStaff) ? $CurrentStaff->last_name : '';
								
								$tempItemDetail['main_title'] = ($VoucherData['name']) ? $VoucherData['name'] : '';
								$tempItemDetail['staff_name'] = 'Code: '.$VoucherData['voucher_code'].', expires on '.date("d M Y",strtotime($VoucherData['end_date'])).', '.$staffFirstName.' '.$staffLastName;
								
								$tempItemDetail['quantity']      = 1;
								$tempItemDetail['item_og_price'] = $InvoiceItemDetail['item_og_price'];
								$tempItemDetail['item_price']    = $InvoiceItemDetail['item_price'];
								array_push($InvoiceItemsInfo,$tempItemDetail);
							}
						}
					} else if($InvoiceItemDetail['item_type'] == 'paidplan'){
						$PAIDPLAN_ID = $InvoiceItemDetail['item_id'];
						
						$PaidPlan = SoldPaidPlan::select('paid_plan.name','sold_paidplan.end_date')->leftJoin('paid_plan', 'paid_plan.id', '=', 'sold_paidplan.paidplan_id')->where('sold_paidplan.paidplan_id', $PAIDPLAN_ID)->where('sold_paidplan.invoice_id', $invoiceId)->where('sold_paidplan.user_id', $AdminId)->get()->first()->toArray();
						
						$PlanName = ($PaidPlan) ? $PaidPlan['name'] : 'N/A';
						$ExpireOn = ($PaidPlan) ? date("d M Y",strtotime($PaidPlan['end_date'])) : 'N/A';
						
						$tempItemDetail['main_title'] = $PlanName;
						$tempItemDetail['staff_name'] = 'Plan: '.$PlanName.', expires on '.$ExpireOn;
						
						$tempItemDetail['quantity']      = $InvoiceItemDetail['quantity'];
						$tempItemDetail['item_og_price'] = ($InvoiceItemDetail['item_og_price'] * $InvoiceItemDetail['quantity']);
						$tempItemDetail['item_price']    = ($InvoiceItemDetail['item_price'] * $InvoiceItemDetail['quantity']);
						array_push($InvoiceItemsInfo,$tempItemDetail);
					}
				}
			}
			
			$InvoiceTaxes = InvoiceTaxes::select('invoice_taxes.tax_rate','invoice_taxes.tax_amount','taxes.tax_name')->leftJoin('taxes', 'taxes.id', '=', 'invoice_taxes.tax_id')->where('invoice_taxes.invoice_id', $invoiceId)->get()->toArray();
			
			$TotalStaffTip = StaffTip::select(DB::raw('SUM(tip_amount) as total_tip'))->where('staff_tip.inovice_id', $invoiceId)->get()->toArray();
			
			$LocationInfo = array();
			if($Invoice['location_id'] != 0){
				$LocationInfo = Location::select('location_name','location_address','country_code','location_phone')->where(['id'=>$Invoice['location_id']])->orderBy('id', 'ASC')->get()->first()->toArray();
			}
			
			$ClientInfo = array();
			if($Invoice['client_id'] != '' && $Invoice['client_id'] != 0){
				$ClientInfo = Clients::getClientbyID($Invoice['client_id']);
			}
			
			// generate pdf
			$pdfData = array();
			$pdfData['Invoice']          = $Invoice;
			$pdfData['InvoiceItemsInfo'] = $InvoiceItemsInfo;
			$pdfData['InvoiceTemplate']  = $InvoiceTemplate;
			$pdfData['InvoiceTaxes']     = $InvoiceTaxes;
			$pdfData['TotalStaffTip']    = $TotalStaffTip;
			$pdfData['LocationInfo']     = $LocationInfo;
			$pdfData['ClientInfo']       = $ClientInfo;
			
			$PDF = PDF::loadView('pdfTemplates.invoiceOrder',$pdfData)->setPaper('a4');
			// generate pdf
			
			$FROM_EMAIL     = env("MAIL_FROM_ADDRESS", "info@ikotel.ca");
			$FROM_NAME      = (isset($LocationInfo['location_name'])) ? $LocationInfo['location_name'] : 'Invoice copy';
			$TO_EMAIL       = $clientInvoiceEmail;
			$SUBJECT        = 'Invoice copy';
			$MESSAGE        = 'Hi  Please see attached invoice order Have a great day! ';
			
			$SendMail = Mail::to($TO_EMAIL)->send(new InvoiceMail($FROM_EMAIL,$FROM_NAME,$SUBJECT,$MESSAGE,$Invoice,$InvoiceItemsInfo,$InvoiceTaxes,$TotalStaffTip,$LocationInfo,$InvoiceTemplate,$ClientInfo,$PDF->output(),'Invoice-'.$fileName.'.pdf'));	
			/*echo "<pre>";
			print_r($SendMail);
			exit;*/
			$data["status"] = true;
			$data["message"] = "Invoice Mail has been sent succesfully to ".$clientInvoiceEmail.".";	
			
            return JsonReturn::success($data);
        }
	}
	
	public function markInvoiceVoid(Request $request){
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
			
			$InvoiceID = ($request->void_invoice_id) ? $request->void_invoice_id : '';
			
			$Invoice = Invoice::select('*')->where('id',$InvoiceID)->where('user_id', $AdminId)->orderBy('id', 'desc')->get()->first()->toArray();
			$InvoiceItems = InvoiceItems::select('*')->where('invoice_id',$InvoiceID)->orderBy('id', 'desc')->get()->toArray();
			
			$Success = 0;
			
			if(!empty($Invoice)) {
				if(!empty($InvoiceItems)) {
					foreach($InvoiceItems as $InvoiceItemData) {
						$getAppointmentService = InvoiceItems::find($InvoiceItemData['id']); 
						
						if($InvoiceItemData['item_type'] == 'services'){
							$getAppointmentService->is_void = 1;
						} else if($InvoiceItemData['item_type'] == 'product'){
							$getAppointmentService->is_void = 1;
							
							$PRODUCT_ID = $InvoiceItemData['item_id'];
							
							$getProductDetails = InventoryProducts::find($PRODUCT_ID); 
							
							$PrevStock    = ($getProductDetails->initial_stock) ? $getProductDetails->initial_stock : 0;
							$InvoiceStock = ($InvoiceItemData['quantity']) ? $InvoiceItemData['quantity'] : 0;
							$InvoicePrice = ($InvoiceItemData['item_price'] > 0) ? $InvoiceItemData['item_price'] : (($InvoiceItemData['item_og_price']) ? $InvoiceItemData['item_og_price'] : 0);
							
							$TotalStock = $PrevStock + $InvoiceStock;
							
							// save stock qty
							$getProductDetails->initial_stock = $TotalStock;
							$getProductDetails->updated_at    = date("Y-m-d H:i:s");
							if($getProductDetails->save()) {
								$InventoryOrderLogs = InventoryOrderLogs::create([
									'item_id'              => $PRODUCT_ID,
									'received_date'        => date("Y-m-d H:i:s"),
									'received_by'          => Auth::id(),
									'location_id'          => $Invoice['location_id'],
									'supplier_id'          => $getProductDetails->supplier_id,
									'order_id'             => 0,
									'invoice_id'           => $InvoiceID,
									'order_type'           => 1,
									'order_action'         => 'Return: Invoice '.$InvoiceID,
									'qty_adjusted'         => $InvoiceStock,
									'cost_price'           => $InvoicePrice,
									'stock_on_hand'        => $TotalStock,
									'enable_stock_control' => $getProductDetails->enable_stock_control,
									'is_void_invoice'      => 1,
									'created_at'           => date("Y-m-d H:i:s"),
									'updated_at'           => date("Y-m-d H:i:s")
								]);	
							}
						}
						
						$getAppointmentService->updated_at = date("Y-m-d H:i:s");
						if($getAppointmentService->save()) {
							$getInvoiceDetail = Invoice::find($InvoiceID); 
							$getInvoiceDetail->invoice_status = 3;
							$getInvoiceDetail->updated_by = Auth::id();
							$getInvoiceDetail->updated_at = date("Y-m-d H:i:s");
							$getInvoiceDetail->save();
							$Success = 1;	
						}
					}
				}
			}
			
			if($Success == 1){
				$data["status"] = true;
				$data["message"] = "Invoice has been marked as void succesfully.";	
				Session::flash('message', 'Invoice has been marked as void succesfully.');
				$data["redirect"] = route('calander');
			} else {
				$data["status"] = true;
				$data["message"] = "Something went wrong!";		
			}
			
            return JsonReturn::success($data);
        }
	}
	
	public function saveInvoicePdf($id = null){
		if($id != '')
		{
			try {
				$invoiceId = Crypt::decryptString($id);
				
				$fileName = ($invoiceId) ? $invoiceId : 0;
				
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
				
				$Invoice = Invoice::select('*')->where('id',$invoiceId)->where('user_id', $AdminId)->orderBy('id', 'desc')->get()->first()->toArray();
				$InvoiceItems = InvoiceItems::select('*')->where('invoice_id',$invoiceId)->orderBy('id', 'desc')->get()->toArray();
				
				$InvoiceTemplate = InvoiceTemplate::where('user_id',$AdminId)->first();
				
				$InvoiceItemsInfo = array();
				if(!empty($InvoiceItems)){
					foreach($InvoiceItems as $InvoiceItemDetail){
						if($InvoiceItemDetail['item_type'] == 'services'){
							
							$SERVICE_ID = $InvoiceItemDetail['item_id'];
							
							$singleService = Services::select('id','service_name')->where('id', $SERVICE_ID)->where('user_id', $AdminId)->orderBy('order_id', 'asc')->get()->first();
							
							$tempItemDetail['main_title'] = ($singleService) ? $singleService->service_name : '';
							
							$CurrentStaff = Staff::getStaffDetailByID($InvoiceItemDetail['staff_id']);
						
							$staffFirstName = ($CurrentStaff) ? $CurrentStaff->first_name : '';
							$staffLastName  = ($CurrentStaff) ? $CurrentStaff->last_name : '';
							$tempItemDetail['staff_name']    = 'With '.$staffFirstName.' '.$staffLastName;
							
							$tempItemDetail['quantity']      = $InvoiceItemDetail['quantity'];
							$tempItemDetail['item_og_price'] = ($InvoiceItemDetail['item_og_price'] * $InvoiceItemDetail['quantity']);
							$tempItemDetail['item_price']    = ($InvoiceItemDetail['item_price'] * $InvoiceItemDetail['quantity']);
							array_push($InvoiceItemsInfo,$tempItemDetail);
						} else if($InvoiceItemDetail['item_type'] == 'product'){
							
							$PRODUCT_ID = $InvoiceItemDetail['item_id'];
							
							$InventoryProducts = InventoryProducts::select('id','product_name')->where('id', $PRODUCT_ID)->where('user_id', $AdminId)->orderBy('id', 'desc')->get()->first();
							
							$tempItemDetail['main_title'] = ($InventoryProducts) ? $InventoryProducts->product_name : '';
							
							$CurrentStaff = Staff::getStaffDetailByID($InvoiceItemDetail['staff_id']);
						
							$staffFirstName = ($CurrentStaff) ? $CurrentStaff->first_name : '';
							$staffLastName  = ($CurrentStaff) ? $CurrentStaff->last_name : '';
							$tempItemDetail['staff_name']    = $staffFirstName.' '.$staffLastName;
							
							$tempItemDetail['quantity']      = $InvoiceItemDetail['quantity'];
							$tempItemDetail['item_og_price'] = ($InvoiceItemDetail['item_og_price'] * $InvoiceItemDetail['quantity']);
							$tempItemDetail['item_price']    = ($InvoiceItemDetail['item_price'] * $InvoiceItemDetail['quantity']);
							array_push($InvoiceItemsInfo,$tempItemDetail);
						} else if($InvoiceItemDetail['item_type'] == 'voucher'){
							
							$VOUCHER_ID = $InvoiceItemDetail['item_id'];
							
							$Voucher = SoldVoucher::select('vouchers.title','sold_voucher.voucher_code','sold_voucher.end_date','vouchers.name')->leftJoin('vouchers', 'vouchers.id', '=', 'sold_voucher.voucher_id')->where('sold_voucher.voucher_id', $VOUCHER_ID)->where('sold_voucher.invoice_id', $invoiceId)->where('sold_voucher.user_id', $AdminId)->orderBy('sold_voucher.id', 'desc')->get()->toArray();
							
							if(!empty($Voucher)){
								foreach($Voucher as $VoucherData){
									$CurrentStaff = Staff::getStaffDetailByID($InvoiceItemDetail['staff_id']);
						
									$staffFirstName = ($CurrentStaff) ? $CurrentStaff->first_name : '';
									$staffLastName  = ($CurrentStaff) ? $CurrentStaff->last_name : '';
									
									$tempItemDetail['main_title'] = ($VoucherData['name']) ? $VoucherData['name'] : '';
									$tempItemDetail['staff_name'] = 'Code: '.$VoucherData['voucher_code'].', expires on '.date("d M Y",strtotime($VoucherData['end_date'])).', '.$staffFirstName.' '.$staffLastName;
									
									$tempItemDetail['quantity']      = 1;
									$tempItemDetail['item_og_price'] = $InvoiceItemDetail['item_og_price'];
									$tempItemDetail['item_price']    = $InvoiceItemDetail['item_price'];
									array_push($InvoiceItemsInfo,$tempItemDetail);
								}
							}
						} else if($InvoiceItemDetail['item_type'] == 'paidplan'){
							$PAIDPLAN_ID = $InvoiceItemDetail['item_id'];
							
							$PaidPlan = SoldPaidPlan::select('paid_plan.name','sold_paidplan.end_date')->leftJoin('paid_plan', 'paid_plan.id', '=', 'sold_paidplan.paidplan_id')->where('sold_paidplan.paidplan_id', $PAIDPLAN_ID)->where('sold_paidplan.invoice_id', $invoiceId)->where('sold_paidplan.user_id', $AdminId)->get()->first()->toArray();
							
							$PlanName = ($PaidPlan) ? $PaidPlan['name'] : 'N/A';
							$ExpireOn = ($PaidPlan) ? date("d M Y",strtotime($PaidPlan['end_date'])) : 'N/A';
							
							$tempItemDetail['main_title'] = $PlanName;
							$tempItemDetail['staff_name'] = 'Plan: '.$PlanName.', expires on '.$ExpireOn;
							
							$tempItemDetail['quantity']      = $InvoiceItemDetail['quantity'];
							$tempItemDetail['item_og_price'] = ($InvoiceItemDetail['item_og_price'] * $InvoiceItemDetail['quantity']);
							$tempItemDetail['item_price']    = ($InvoiceItemDetail['item_price'] * $InvoiceItemDetail['quantity']);
							array_push($InvoiceItemsInfo,$tempItemDetail);
						}
					}
				}
				
				$InvoiceTaxes = InvoiceTaxes::select('invoice_taxes.tax_rate','invoice_taxes.tax_amount','taxes.tax_name')->leftJoin('taxes', 'taxes.id', '=', 'invoice_taxes.tax_id')->where('invoice_taxes.invoice_id', $invoiceId)->get()->toArray();
				
				$TotalStaffTip = StaffTip::select(DB::raw('SUM(tip_amount) as total_tip'))->where('staff_tip.inovice_id', $invoiceId)->get()->toArray();
				
				$LocationInfo = array();
				if($Invoice['location_id'] != 0){
					$LocationInfo = Location::select('location_name','location_address','country_code','location_phone')->where(['id'=>$Invoice['location_id']])->orderBy('id', 'ASC')->get()->first()->toArray();
				}
				
				$ClientInfo = array();
				if($Invoice['client_id'] != '' && $Invoice['client_id'] != 0){
					$ClientInfo = Clients::getClientbyID($Invoice['client_id']);
				}
				
				$pdfData = array();
				$pdfData['Invoice']          = $Invoice;
				$pdfData['InvoiceItemsInfo'] = $InvoiceItemsInfo;
				$pdfData['InvoiceTemplate']  = $InvoiceTemplate;
				$pdfData['InvoiceTaxes']     = $InvoiceTaxes;
				$pdfData['TotalStaffTip']    = $TotalStaffTip;
				$pdfData['LocationInfo']     = $LocationInfo;
				$pdfData['ClientInfo']       = $ClientInfo;
				
				return PDF::loadView('pdfTemplates.invoiceOrder',$pdfData)->setPaper('a4')->download('Invoice-'.$fileName.'.pdf');
			} catch (DecryptException $e) {
				return redirect()->route('/');
			}
		}
    }
	
	public function printInvoice(Request $request){
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
			
			$invoiceId = ($request->invoice_id) ? $request->invoice_id : '';
			$invoiceId = Crypt::decryptString($invoiceId);
			
			$fileName = ($invoiceId) ? $invoiceId : 0;
			
			$Invoice = Invoice::select('*')->where('id',$invoiceId)->where('user_id', $AdminId)->orderBy('id', 'desc')->get()->first()->toArray();
			$InvoiceItems = InvoiceItems::select('*')->where('invoice_id',$invoiceId)->orderBy('id', 'desc')->get()->toArray();
			
			$InvoiceTemplate = InvoiceTemplate::where('user_id',$AdminId)->first();
			
			$InvoiceItemsInfo = array();
			if(!empty($InvoiceItems)){
				foreach($InvoiceItems as $InvoiceItemDetail){
					if($InvoiceItemDetail['item_type'] == 'services'){
						
						$SERVICE_ID = $InvoiceItemDetail['item_id'];
						
						$singleService = Services::select('id','service_name')->where('id', $SERVICE_ID)->where('user_id', $AdminId)->orderBy('order_id', 'asc')->get()->first();
						
						$tempItemDetail['main_title'] = ($singleService) ? $singleService->service_name : '';
						
						$CurrentStaff = Staff::getStaffDetailByID($InvoiceItemDetail['staff_id']);
					
						$staffFirstName = ($CurrentStaff) ? $CurrentStaff->first_name : '';
						$staffLastName  = ($CurrentStaff) ? $CurrentStaff->last_name : '';
						$tempItemDetail['staff_name']    = 'With '.$staffFirstName.' '.$staffLastName;
						
						$tempItemDetail['quantity']      = $InvoiceItemDetail['quantity'];
						$tempItemDetail['item_og_price'] = ($InvoiceItemDetail['item_og_price'] * $InvoiceItemDetail['quantity']);
						$tempItemDetail['item_price']    = ($InvoiceItemDetail['item_price'] * $InvoiceItemDetail['quantity']);
						array_push($InvoiceItemsInfo,$tempItemDetail);
					} else if($InvoiceItemDetail['item_type'] == 'product'){
						
						$PRODUCT_ID = $InvoiceItemDetail['item_id'];
						
						$InventoryProducts = InventoryProducts::select('id','product_name')->where('id', $PRODUCT_ID)->where('user_id', $AdminId)->orderBy('id', 'desc')->get()->first();
						
						$tempItemDetail['main_title'] = ($InventoryProducts) ? $InventoryProducts->product_name : '';
						
						$CurrentStaff = Staff::getStaffDetailByID($InvoiceItemDetail['staff_id']);
					
						$staffFirstName = ($CurrentStaff) ? $CurrentStaff->first_name : '';
						$staffLastName  = ($CurrentStaff) ? $CurrentStaff->last_name : '';
						$tempItemDetail['staff_name']    = $staffFirstName.' '.$staffLastName;
						
						$tempItemDetail['quantity']      = $InvoiceItemDetail['quantity'];
						$tempItemDetail['item_og_price'] = ($InvoiceItemDetail['item_og_price'] * $InvoiceItemDetail['quantity']);
						$tempItemDetail['item_price']    = ($InvoiceItemDetail['item_price'] * $InvoiceItemDetail['quantity']);
						array_push($InvoiceItemsInfo,$tempItemDetail);
					} else if($InvoiceItemDetail['item_type'] == 'voucher'){
						
						$VOUCHER_ID = $InvoiceItemDetail['item_id'];
						
						$Voucher = SoldVoucher::select('vouchers.title','sold_voucher.voucher_code','sold_voucher.end_date','vouchers.name')->leftJoin('vouchers', 'vouchers.id', '=', 'sold_voucher.voucher_id')->where('sold_voucher.voucher_id', $VOUCHER_ID)->where('sold_voucher.invoice_id', $invoiceId)->where('sold_voucher.user_id', $AdminId)->orderBy('sold_voucher.id', 'desc')->get()->toArray();
						
						if(!empty($Voucher)){
							foreach($Voucher as $VoucherData){
								$CurrentStaff = Staff::getStaffDetailByID($InvoiceItemDetail['staff_id']);
					
								$staffFirstName = ($CurrentStaff) ? $CurrentStaff->first_name : '';
								$staffLastName  = ($CurrentStaff) ? $CurrentStaff->last_name : '';
								
								$tempItemDetail['main_title'] = ($VoucherData['name']) ? $VoucherData['name'] : '';
								$tempItemDetail['staff_name'] = 'Code: '.$VoucherData['voucher_code'].', expires on '.date("d M Y",strtotime($VoucherData['end_date'])).', '.$staffFirstName.' '.$staffLastName;
								
								$tempItemDetail['quantity']      = 1;
								$tempItemDetail['item_og_price'] = $InvoiceItemDetail['item_og_price'];
								$tempItemDetail['item_price']    = $InvoiceItemDetail['item_price'];
								array_push($InvoiceItemsInfo,$tempItemDetail);
							}
						}
					} else if($InvoiceItemDetail['item_type'] == 'paidplan'){
						$PAIDPLAN_ID = $InvoiceItemDetail['item_id'];
						
						$PaidPlan = SoldPaidPlan::select('paid_plan.name','sold_paidplan.end_date')->leftJoin('paid_plan', 'paid_plan.id', '=', 'sold_paidplan.paidplan_id')->where('sold_paidplan.paidplan_id', $PAIDPLAN_ID)->where('sold_paidplan.invoice_id', $invoiceId)->where('sold_paidplan.user_id', $AdminId)->get()->first()->toArray();
						
						$PlanName = ($PaidPlan) ? $PaidPlan['name'] : 'N/A';
						$ExpireOn = ($PaidPlan) ? date("d M Y",strtotime($PaidPlan['end_date'])) : 'N/A';
						
						$tempItemDetail['main_title'] = $PlanName;
						$tempItemDetail['staff_name'] = 'Plan: '.$PlanName.', expires on '.$ExpireOn;
						
						$tempItemDetail['quantity']      = $InvoiceItemDetail['quantity'];
						$tempItemDetail['item_og_price'] = ($InvoiceItemDetail['item_og_price'] * $InvoiceItemDetail['quantity']);
						$tempItemDetail['item_price']    = ($InvoiceItemDetail['item_price'] * $InvoiceItemDetail['quantity']);
						array_push($InvoiceItemsInfo,$tempItemDetail);
					}
				}
			}
			
			$InvoiceTaxes = InvoiceTaxes::select('invoice_taxes.tax_rate','invoice_taxes.tax_amount','taxes.tax_name')->leftJoin('taxes', 'taxes.id', '=', 'invoice_taxes.tax_id')->where('invoice_taxes.invoice_id', $invoiceId)->get()->toArray();
			
			$TotalStaffTip = StaffTip::select(DB::raw('SUM(tip_amount) as total_tip'))->where('staff_tip.inovice_id', $invoiceId)->get()->toArray();
			
			$LocationInfo = array();
			if($Invoice['location_id'] != 0){
				$LocationInfo = Location::select('location_name','location_address','country_code','location_phone')->where(['id'=>$Invoice['location_id']])->orderBy('id', 'ASC')->get()->first()->toArray();
			}
			
			$ClientInfo = array();
			if($Invoice['client_id'] != '' && $Invoice['client_id'] != 0){
				$ClientInfo = Clients::getClientbyID($Invoice['client_id']);
			}
			
			return view('appointments.printInvoice',compact('invoiceId','Invoice','InvoiceItemsInfo','ClientInfo','InvoiceTaxes','TotalStaffTip','LocationInfo','InvoiceTemplate'))->render();
		}
    }
	
	public function sendVoucherEmail($id = null){
		if($id != '')
		{
			try {
				$invoiceId = Crypt::encryptString($id);	
				$invoiceId = Crypt::decryptString($invoiceId);
				
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
				
				$Invoice = Invoice::select('*')->where('id',$invoiceId)->where('user_id', $AdminId)->orderBy('id', 'desc')->get()->first()->toArray();
				
				$VoucherSold = SoldVoucher::select('locations.location_name','locations.location_address','locations.location_image','vouchers.title','sold_voucher.voucher_code','sold_voucher.end_date','vouchers.name','sold_voucher.price')->leftJoin('vouchers', 'vouchers.id', '=', 'sold_voucher.voucher_id')->leftJoin('locations', 'locations.id', '=', 'sold_voucher.location_id')->where('sold_voucher.invoice_id', $invoiceId)->where('sold_voucher.user_id', $AdminId)->orderBy('sold_voucher.id', 'desc')->get()->toArray();
				
				$LocationInfo = array();
				if($Invoice['location_id'] != 0){
					$LocationInfo = Location::select('location_name','location_address','country_code','location_phone')->where(['id'=>$Invoice['location_id']])->orderBy('id', 'ASC')->get()->first()->toArray();
				}
				
				$ClientInfo = array();
				if($Invoice['client_id'] != '' && $Invoice['client_id'] != 0){
					$ClientInfo = Clients::getClientbyID($Invoice['client_id']);
				}
				
				return view('appointments.sendEmailVoucher',compact('invoiceId','Invoice','ClientInfo','LocationInfo','VoucherSold'));
			} catch (DecryptException $e) {
				return redirect()->route('/');
			}
		}
	}
	
	public function sendVoucherInEmail(Request $request){
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
			
			$InvoiceID                    = ($request->invoiceID) ? $request->invoiceID : '';
			$encryptedInvoiceId           = Crypt::encryptString($InvoiceID);	
			$RecipientFirstName           = ($request->recipient_first_name) ? $request->recipient_first_name : '';	
			$RecipientLastName            = ($request->recipient_last_name) ? $request->recipient_last_name : '';	
			$recipient_email              = ($request->recipient_email) ? $request->recipient_email : '';	
			$RecipientPersonalizedEmail   = ($request->recipient_personalized_email) ? $request->recipient_personalized_email : '';	
			
			$Invoice = Invoice::select('*')->where('id',$InvoiceID)->where('user_id', $AdminId)->orderBy('id', 'desc')->get()->first()->toArray();
				
			$VoucherSold = SoldVoucher::select('locations.location_name','locations.location_address','locations.location_image','vouchers.title','sold_voucher.voucher_code','sold_voucher.end_date','vouchers.name','sold_voucher.price')->leftJoin('vouchers', 'vouchers.id', '=', 'sold_voucher.voucher_id')->leftJoin('locations', 'locations.id', '=', 'sold_voucher.location_id')->where('sold_voucher.invoice_id', $InvoiceID)->where('sold_voucher.user_id', $AdminId)->orderBy('sold_voucher.id', 'desc')->get()->toArray();
			
			$LocationInfo = array();
			if($Invoice['location_id'] != 0){
				$LocationInfo = Location::select('location_name','location_address','country_code','location_phone')->where(['id'=>$Invoice['location_id']])->orderBy('id', 'ASC')->get()->first()->toArray();
			}
			
			$ClientInfo = array();
			if($Invoice['client_id'] != '' && $Invoice['client_id'] != 0){
				$ClientInfo = Clients::getClientbyID($Invoice['client_id']);
			}
			
			$Success = 0;	 
			if(!empty($VoucherSold)){
				foreach($VoucherSold as $VoucherSoldData){
					
					$VoucherName         = ($VoucherSoldData['name']) ? $VoucherSoldData['name'] : '';
					$VoucherLocationName = ($VoucherSoldData['location_name']) ? $VoucherSoldData['location_name'] : '';
					
					$FROM_EMAIL     = env("MAIL_FROM_ADDRESS", "info@ikotel.ca");
					$FROM_NAME      = 'Scheduledown';
					$TO_EMAIL       = $recipient_email;
					$SUBJECT        = $VoucherName.' from '.$VoucherLocationName;
					$MESSAGE        = 'Hi  Please see attached invoice order Have a great day! ';
					 
					$SendMail = Mail::to($TO_EMAIL)->send(new VoucherEmail($FROM_EMAIL,$FROM_NAME,$SUBJECT,$MESSAGE,$VoucherSoldData,$LocationInfo,$ClientInfo,$RecipientFirstName,$RecipientLastName,$RecipientPersonalizedEmail));

					$Success = 1;	 		
				}
			}
			
			if($Success == 1){
				$data["status"] = true;
				$data["message"] = "Voucher has been sent.";	
				Session::flash('message', 'Voucher has been sent.');
				$data["redirect"] = route('viewInvoice',['id' => $InvoiceID]);
			} else {
				$data["status"] = true;
				$data["message"] = "Something went wrong!";		
			}
			
            return JsonReturn::success($data);
        }
	}
	
	public function printDownloadVoucher($id = null){
		if($id != '')
		{
			try {
				$invoiceId = Crypt::encryptString($id);	
				$invoiceId = Crypt::decryptString($invoiceId);
				
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
				
				$Invoice = Invoice::select('*')->where('id',$invoiceId)->where('user_id', $AdminId)->orderBy('id', 'desc')->get()->first()->toArray();
				
				$VoucherSold = SoldVoucher::select('locations.location_name','locations.location_address','locations.location_image','vouchers.title','sold_voucher.voucher_code','sold_voucher.end_date','vouchers.name','sold_voucher.price')->leftJoin('vouchers', 'vouchers.id', '=', 'sold_voucher.voucher_id')->leftJoin('locations', 'locations.id', '=', 'sold_voucher.location_id')->where('sold_voucher.invoice_id', $invoiceId)->where('sold_voucher.user_id', $AdminId)->orderBy('sold_voucher.id', 'desc')->get()->toArray();
				
				$LocationInfo = array();
				if($Invoice['location_id'] != 0){
					$LocationInfo = Location::select('location_name','location_address','country_code','location_phone')->where(['id'=>$Invoice['location_id']])->orderBy('id', 'ASC')->get()->first()->toArray();
				}
				
				$ClientInfo = array();
				if($Invoice['client_id'] != '' && $Invoice['client_id'] != 0){
					$ClientInfo = Clients::getClientbyID($Invoice['client_id']);
				}
				
				return view('appointments.printDownloadVoucher',compact('invoiceId','Invoice','ClientInfo','LocationInfo','VoucherSold'));
			} catch (DecryptException $e) {
				return redirect()->route('/');
			}
		}
	}
	
	public function printDownloadedVoucher(Request $request){
		if ($request->ajax()){
			$CurrentUser = auth::user();
			$is_admin = $CurrentUser->is_admin;
			
			if($is_admin == 1){
				$CurrentStaff = Staff::select('*')->where('staff_user_id',$CurrentUser->id)->first();
				$AdminId = $CurrentStaff->user_id;
				$UserId  = Auth::id();
			} else {
				$AdminId = Auth::id();
				$UserId  = Auth::id();
			}
			
			$InvoiceID                    = ($request->invoiceID) ? $request->invoiceID : '';
			$encryptedInvoiceId           = Crypt::encryptString($InvoiceID);	
			$RecipientFirstName           = ($request->recipient_first_name) ? $request->recipient_first_name : '';	
			$RecipientLastName            = ($request->recipient_last_name) ? $request->recipient_last_name : '';	
			$RecipientPersonalizedEmail   = ($request->recipient_personalized_email) ? $request->recipient_personalized_email : '';	
			
			$Invoice = Invoice::select('*')->where('id',$InvoiceID)->where('user_id', $AdminId)->orderBy('id', 'desc')->get()->first()->toArray();
				
			$VoucherSold = SoldVoucher::select('locations.location_name','locations.location_address','locations.location_image','vouchers.title','sold_voucher.voucher_code','sold_voucher.end_date','vouchers.name','sold_voucher.price')->leftJoin('vouchers', 'vouchers.id', '=', 'sold_voucher.voucher_id')->leftJoin('locations', 'locations.id', '=', 'sold_voucher.location_id')->where('sold_voucher.invoice_id', $InvoiceID)->where('sold_voucher.user_id', $AdminId)->orderBy('sold_voucher.id', 'desc')->get()->toArray();
			
			$LocationInfo = array();
			if($Invoice['location_id'] != 0){
				$LocationInfo = Location::select('location_name','location_address','country_code','location_phone')->where(['id'=>$Invoice['location_id']])->orderBy('id', 'ASC')->get()->first()->toArray();
			}
			
			$ClientInfo = array();
			if($Invoice['client_id'] != '' && $Invoice['client_id'] != 0){
				$ClientInfo = Clients::getClientbyID($Invoice['client_id']);
			}
			
			$pdfData = array();
			$pdfData['Invoice']                    = $Invoice;
			$pdfData['VoucherSold']                = $VoucherSold;
			$pdfData['LocationInfo']               = $LocationInfo;
			$pdfData['ClientInfo']                 = $ClientInfo;
			$pdfData['RecipientFirstName']         = $RecipientFirstName;
			$pdfData['RecipientLastName']          = $RecipientLastName;
			$pdfData['RecipientPersonalizedEmail'] = $RecipientPersonalizedEmail;
			return PDF::loadView('pdfTemplates.voucherPdfPrint',$pdfData)->setPaper('a4')->download('Voucher.pdf');
        }
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
