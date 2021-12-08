<?php
namespace App\Http\Controllers\Front;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
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
use App\Models\Discount;
use App\Models\paymentType;
use App\Models\Invoice;
use App\Models\InvoiceItems;
use App\Models\InvoiceSequencing;
use App\Models\StaffTip;
use App\Models\Taxes;
use App\Models\LocTax;
use App\Models\taxFormula;
use App\Models\InvoiceTaxes;
use App\Models\Inventory_category;
use App\Models\InventoryProducts;
use App\Models\InventoryOrderLogs;
use App\Models\PaidPlan;
use App\Models\SoldPaidPlan;
use App\Models\Voucher;
use App\Models\SoldVoucher;
use App\Models\InvoiceVoucher;
use App\Models\StaffWorkingHours;
use App\Models\Staff_closedDate;
use App\Models\frontUser;
use App\Models\RescheduleNotification;
use App\Models\Online_setting;
use DataTables;
use Session;
use Crypt;
use DB;
use App\Mail\rescheduleAppointment;

use App\Repositories\NotificationRepositorie;
  
class RescheduleAppointmentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
    	NotificationRepositorie $notificationRepositorie
    )
    {
		$this->notificationRepositorie = $notificationRepositorie;
    }
	
	public function rescheduleAppointment($locationId = null, $appointmentId = null)
	{
		if($appointmentId > 0) 
		{
			$locationId = Crypt::decryptString($locationId);
			
			$LocationData = Location::select('id','user_id','location_name','location_address','location_image')->where('id', $locationId)->first();
			$locationUserId = $LocationData->user_id;
			
			$onlineSettingData = Online_setting::select('appointment_cancel_time')->where('online_setting.user_id', $locationUserId)->first();
			
			$loggedUser = Auth::guard('fuser')->user();
			$loggedUserId = "";
			if(!empty($loggedUser)) {
				$loggedUserId = $loggedUser->id;
			}
			
			$taxFormulaData = taxFormula::select('tax_formula.tax_formula')->where('user_id', $locationUserId)->first();
			if(!empty($taxFormulaData)) {
				$taxFormula = $taxFormulaData->tax_formula;	
			} else {
				$taxFormula = 0;
			}
			
			$serTaxes = LocTax::select('loc_taxes.id','loc_taxes.service_default_tax','taxes.tax_name','taxes.tax_rates','taxes.is_group')->leftJoin('taxes', 'taxes.id', '=', 'loc_taxes.service_default_tax')->where('loc_taxes.service_default_tax', '>' , 0)->where('loc_taxes.user_id', $locationUserId)->where('loc_taxes.loc_id', $locationId)->first();
			
			$serviceTaxes = array();
			if(!empty($serTaxes)) {
				if($serTaxes->is_group == 1) {
					
					$taxes = explode(",", str_replace(" ", "", $serTaxes->tax_rates));
					$serTaxes = Taxes::select('taxes.id','taxes.tax_name','taxes.tax_rates','taxes.is_group')->whereIn('id', $taxes)->get();
					
					foreach($serTaxes as $tax) {
						$tmp_arr = array(
							'id' => $tax->id,
							'service_default_tax' => $tax->id,
							'tax_name' => $tax->tax_name,
							'tax_rates' => $tax->tax_rates,
							'is_group' => $tax->is_group
						);
						
						array_push($serviceTaxes, $tmp_arr);
					}
				} else {
					$tmp_arr = array(
						'id' => $serTaxes->id,
						'service_default_tax' => $serTaxes->service_default_tax,
						'tax_name' => $serTaxes->tax_name,
						'tax_rates' => $serTaxes->tax_rates,
						'is_group' => $serTaxes->is_group
					);
					
					array_push($serviceTaxes, $tmp_arr);
				}		
			}
			
			$getAppintmentData = Appointments::select('id','appointment_date')->where('user_id',$locationUserId)->where('id',$appointmentId)->first();
			// DB::enableQueryLog();
			$getAppintmentServices = AppointmentServices::select('appointment_services.*','services.service_name')->leftJoin('services_price', 'services_price.id', '=', 'appointment_services.service_price_id')->leftJoin('services', 'services.id', '=', 'services_price.service_id')->where('appointment_services.appointment_id',$appointmentId)->orderBy('appointment_services.id','asc')->get();
			/*$query = DB::getQueryLog();
			echo "<pre>";
			print_r($query);
			exit;*/
			if(!$getAppintmentServices->isEmpty())
			{
				foreach($getAppintmentServices as $key => $val) {
					$val->uniId = $this->quickRandom($val->duration);	
					$val->duration_txt = $this->convertDurationText($val->duration);	
				}	
				if(!empty($onlineSettingData)) {
					if($onlineSettingData->appointment_cancel_time > 0) {
						$cancel_time = $onlineSettingData->appointment_cancel_time;
						$app_start_datetime = date("Y-m-d H:i:s", strtotime($getAppintmentServices[0]->appointment_date." ".$getAppintmentServices[0]->start_time));
						$curr_Datetime = date("Y-m-d H:i:s");
							
						$newTime = date("Y-m-d H:i:s", strtotime("- ".$cancel_time." minutes", strtotime($app_start_datetime)));
						
						if(strtotime($curr_Datetime) > strtotime($newTime)) {
							return redirect()->route('rescheduleError', $appointmentId);
						}	
					}	
				}	
			}
			else 
			{
				Session::flash('message', 'Something went wrong.');
				return redirect(route('myAppointments'));
			}
			
			/* echo "<pre>";
			print_r(json_decode($getAppintmentServices));
			die; */
			
			return view('frontend.reschedule_booking', compact('locationId','locationUserId','serviceTaxes','taxFormula','loggedUserId','LocationData','getAppintmentData','getAppintmentServices','appointmentId'));
		}
	}
	
	function saveRescheduleAppointment(Request $request)
	{
		if ($request->ajax())
        {
			$loggedUserId = $request->loggedUserId;
			$appointmentId = $request->AppointmentId;
			$locationId = $request->locationID;
			$userId = $request->userId;
			$staffId = $request->staffId;
			$bookingTime = date("H:i", strtotime($request->bookingTime));
			$appointment_notes = $request->description;
			$itemPrId = $request->itemPrId;
			$itemPr = $request->itemPr;
			$itemDur = $request->itemDur;
			$itemType = $request->itemType;
			$invoice_tax_amount = $request->invoice_tax_amount;
			$invoice_tax_rate = $request->invoice_tax_rate;
			$invoice_tax_id = $request->invoice_tax_id;
			$taxFormula = $request->taxFormula;
			$itemtotal = $inoviceTotal = $request->inoviceTotal;
			
			$appointment_date = date("Y-m-d", strtotime($request->demo_book_date));
			
			if($taxFormula == 0) {
				$itemsubtotal = $inoviceTotal - $taxAmount;
			} else {
				$itemsubtotal = $inoviceTotal;
			}
			$appointmentAmount = $itemsubtotal;
				
			$getStaffData = Staff::select('id','staff_user_id')->where('id', $staffId)->first();
			
			$updateAppointment = Appointments::find($appointmentId);
			$updateAppointment->appointment_date 	= $appointment_date;
			$updateAppointment->appointment_notes  	= $appointment_notes;
			$updateAppointment->total_amount  	 	= $appointmentAmount;
			$updateAppointment->final_total  		= $appointmentAmount;
			$updateAppointment->appointment_status 	= 0;
			$updateAppointment->is_paid            	= 0;
			$updateAppointment->created_by         	= 0;
			$updateAppointment->updated_at         	= date("Y-m-d H:i:s");
			$updateAppointment->save();
				
			if($appointmentId > 0) 
			{	
				$deleteAppService = AppointmentServices::where('appointment_id',$appointmentId)->delete();
				
				$appStartFrom = $bookingTime;
				foreach($itemPrId as $key => $val) 
				{	
					$item_type = $itemType[$key];
					$item_id = $val;
					$serviceDuration = $itemDur[$key];
					$AppointmentServicesId = 0;
					
					$getItemId = ServicesPrice::select('services_price.id','services_price.service_id')->where('services_price.id', $item_id)->first();
					$item_id = $getItemId->service_id;
					
					$serviceData = Services::select('id','is_extra_time','extra_time','extra_time_duration','service_name')->where('id', $item_id)->first();


					if(!isset($firstService)) {
						$firstService = $serviceData;
					}
					
					if($serviceData->is_extra_time == 1) {
						$extraTime = $serviceData->extra_time_duration;
						$servStartTime = date("H:i", strtotime($appStartFrom));
						$servEndTime = date("H:i", strtotime("+ ".$serviceDuration." minutes", strtotime($appStartFrom)));
						$appStartFrom = date("H:i", strtotime("+ ".$extraTime." minutes", strtotime($servEndTime)));
					} else {
						$servStartTime = date("H:i", strtotime($appStartFrom));
						$servEndTime = date("H:i", strtotime("+ ".$serviceDuration." minutes", strtotime($appStartFrom)));
						$appStartFrom = $servEndTime;
					}
					
					$AppointmentServices = AppointmentServices::create([
						'appointment_id'      => $appointmentId,
						'user_id'             => $userId,
						'appointment_date'    => $appointment_date,
						'start_time'          => $servStartTime,
						'end_time'            => $servEndTime,
						'service_price_id'    => $val,
						'duration'            => $serviceDuration,
						'is_extra_time'       => $serviceData->is_extra_time,
						'extra_time'          => $serviceData->extra_time,
						'extra_time_duration' => $serviceData->extra_time_duration,
						'staff_user_id'       => $getStaffData->staff_user_id,
						'special_price'       => $itemPr[$key],
						'created_at'          => date("Y-m-d H:i:s"),
						'updated_at'          => date("Y-m-d H:i:s")
					]);
					$AppointmentServicesId = $AppointmentServices->id;
				}
			}	

			//send mail
			$client = Clients::where('id',$userId)->first();
			$serPrice = ServicesPrice::where('id',$AppointmentServices->service_price_id)->first();
			$service = Services::where('id',$serPrice->service_id)->first();
			$appointment = Appointments::where('id',$AppointmentServices->appointment_id)->first();
			$remidernoti = RescheduleNotification::where('user_id',$userId)->first();

			if($client){
				$email = $client->email;
				$FROM_EMAIL     = env("MAIL_FROM_ADDRESS", "info@ikotel.ca");
				$FROM_NAME      = 'Scheduledown';
				$TO_EMAIL       = $email;
				$CC_EMAIL       = 'tjcloudtest2@gmail.com';
				$SUBJECT        = 'Reschedule Appointment';
				$OrderId        = 0;
				
				$UniqueId       = $this->unique_code(30).time();
				
				$SendMail = Mail::to($TO_EMAIL)->cc([$CC_EMAIL])->send(new rescheduleAppointment($FROM_EMAIL,$FROM_NAME,$SUBJECT,$AppointmentServices,$appointment,$UniqueId,$client,$service,$remidernoti));	
				
				EmailLog::create([
					'user_id' => $AdminId,
					'client_id' => $client->id,
                    'appointment_id' => $appointment->id,
					'unique_id' => $UniqueId,
					'from_email' => $FROM_EMAIL,
					'to_email' => $TO_EMAIL,
					'module_type_text' => 'Reschedule Appointment Email',
					'created_at'       => date("Y-m-d H:i:s")
				]);
			}
			//end
			
			$fUserData = frontUser::select('id','email','name','last_name','mobile')->where('id', $loggedUserId)->first();
			$fuserName = isset($fUserData->name) ? $fUserData->name : '';
			$totalServices = is_array($itemPrId) ? count($itemPrId) : '';
			$locationData = Location::select('*')->where('locations.id', $locationId)->first();
			# Send notification
			 /*$notificationParams = [
				'user_id'           => $userId,
				'client_id'         => $updateAppointment->client_id,
				'type'              => 'appointment',
				'type_id'           => $appointmentId,
				'title'				=> $fuserName.' rescheduled online',
				'description' 		=> (isset($firstService) ? $firstService->service_name : '').' rescheduled to '.date('D d M h:ia', strtotime($appointment_date.' '.$bookingTime)).' with '.$fuserName.' at '.(isset($locationData->location_name) ? $locationData->location_name : '')
			];

			$this->notificationRepositorie->storeNotification($notificationParams); */

			$title = $fuserName.' rescheduled online';
			$description = (isset($firstService) ? $firstService->service_name : '').' rescheduled to '.date('D d M h:ia', strtotime($appointment_date.' '.$bookingTime)).' with '.$fuserName.' at '.(isset($locationData->location_name) ? $locationData->location_name : '');

			$this->notificationRepositorie->sendNotification($staffId, $updateAppointment->client_id, 'appointment', $appointmentId, $title, $description, $locationId, 'reschedules'); 

			$data["status"] = true;
			$data["redirect"] = route('myAppointments',['appointmentId' => base64_encode($appointmentId)]);
			$data["message"] = "Appointment has been reschedule successfully.";
			return JsonReturn::success($data);
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
}	