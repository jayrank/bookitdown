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
use App\Models\ConsForm;
use App\Models\conFormClientDetails;
use App\Models\conFormCustomSection;
use App\Models\ClientConsultationForm;
use App\Models\ClientConsultationFormField;
use App\Models\EmailLog;
use App\Models\Online_setting;
use App\Models\StaffServices;
use App\Models\StaffBlockedTime;
use App\Mail\ConsultationFormReminder;
use App\Mail\AppointmentNotification;
use DataTables;
use Session;
use Crypt;
use Hash;
use Mail;

use App\Repositories\NotificationRepositorie;

class BookingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * @var notificationRepositorie
     */
    private $notificationRepositorie;

    public function __construct(
    	NotificationRepositorie $notificationRepositorie
    )
    {
		$this->notificationRepositorie = $notificationRepositorie;
    }
	
	public function index($lId = null, Request $request) 
	{
		$loggedUser = Auth::guard('fuser')->user();
		
		/* $TO_EMAIL = "tjcloudtest2@gmail.com";
		$FROM_EMAIL = env("MAIL_FROM_ADDRESS", "info@ikotel.ca");
		$FROM_NAME = "Schedule";
		$SUBJECT = "Appointment";
		$MESSAGE = "";
		
		echo $SendMail = Mail::to($TO_EMAIL)->send(new AppointmentNotification($FROM_EMAIL,$FROM_NAME,$SUBJECT,$MESSAGE));	
		die; */
		
		$loggedUserId = "";
		if(!empty($loggedUser)) {
			$loggedUserId = $loggedUser->id;
		}
		
		$locationID = Crypt::decryptString($lId);
		$LocationData = Location::select('id','user_id','location_name','location_address','location_image')->where('id', $locationID)->first();
		$locationUserId = $LocationData->user_id;
		
		$taxFormulaData = taxFormula::select('tax_formula.tax_formula')->where('user_id', $locationUserId)->first();
		
		if(!empty($taxFormulaData)) {
			$taxFormula = $taxFormulaData->tax_formula;	
		} else {
			$taxFormula = 0;
		}
		
		$onlineSettingData = Online_setting::select('*')->where('online_setting.user_id', $locationUserId)->first();
		
		$serTaxes = LocTax::select('loc_taxes.id','loc_taxes.service_default_tax','taxes.tax_name','taxes.tax_rates','taxes.is_group')->leftJoin('taxes', 'taxes.id', '=', 'loc_taxes.service_default_tax')->where('loc_taxes.service_default_tax', '>' , 0)->where('loc_taxes.user_id', $locationUserId)->where('loc_taxes.loc_id', $locationID)->first();
		
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
			
		if($request->staffId > 0) {
			
			$staff_id = $request->staffId;
			$getCates = Services::select('service_category')->whereRaw('FIND_IN_SET('.$staff_id.',staff_ids)')->where('is_deleted', 0)->where('user_id', $locationUserId)->distinct()->get()->toArray();
			
			$catIDs = array();
			foreach($getCates as $cat) {
				$catIDs[] = $cat['service_category'];
			}	
			
			$serviceCategory = ServiceCategory::select('id','category_title')->whereIn('id', $catIDs)->where('user_id', $locationUserId)->where('is_deleted', 0)->orderBy('order_id', 'asc')->get();
			
			foreach($serviceCategory as $cateKey => $servCat)
			{
				$serviceLists = Services::select('id','service_name','service_description')->whereRaw('FIND_IN_SET('.$staff_id.',staff_ids)')->where('service_category', $servCat->id)->where('is_deleted', 0)->where('is_online_booking', 1)->where('user_id', $locationUserId)->whereRaw("FIND_IN_SET(?,location_id)", [$locationID])->orderBy('order_id', 'asc')->get();
				
				if($serviceLists->isEmpty()) {
					unset($serviceCategory[$cateKey]);
				} else {

					$servCat['services'] = $serviceLists;
					$servicehtml = "";
					foreach($serviceLists as $key => $service)
					{
						$pricearr = array();
						$servicePrices = ServicesPrice::select('id', 'duration', 'lowest_price', 'price', 'special_price', 'is_staff_price')->where('service_id', $service->id)->where('user_id', $locationUserId)->orderBy('id', 'asc')->get();
						
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
								$pr = "pr".(++$key2);	
							}	
							
							if($servprice->price != $sprice) {
								$service_price = $servprice->price;
								$service_price_special = $sprice;
							} else {
								$service_price = $sprice;
								$service_price_special = $sprice;
							}
							$uniqid = $this->quickRandom();
							
							$tmpArr = array(
								'service_price_id' => $servprice->id,
								'service_price_uid' => $uniqid,
								'service_price_name' => $pr,
								'service_price_duration' => $servprice->duration,
								'service_price_duration_txt' => $duration,
								'is_staff_price' => $servprice->is_staff_price,
								'service_price_amount' => $service_price,
								'service_price_special_amount' => $service_price_special,
							);	
							
							array_push($pricearr, $tmpArr);
						}
						
						$min_duration = min(array_column($pricearr, 'service_price_duration'));	
						$max_duration = max(array_column($pricearr, 'service_price_duration'));	
						$service['serviceDuration'] = $this->convertDurationText($min_duration)." - ".$this->convertDurationText($max_duration);
						$service['servicePrice'] = $pricearr;
					}
				}
			}
			
		} else {
			$staff_id = 0;
			$serviceCategory = ServiceCategory::select('id','category_title')->where('user_id', $locationUserId)->where('is_deleted', 0)->orderBy('order_id', 'asc')->get();
			
			foreach($serviceCategory as $cateKey => $servCat)
			{
				$serviceLists = Services::select('id','service_name','service_description','is_extra_time','extra_time','extra_time_duration')->where('service_category', $servCat->id)->where('is_deleted', 0)->where('is_online_booking', 1)->where('user_id', $locationUserId)->whereRaw("FIND_IN_SET(?,location_id)", [$locationID])->orderBy('order_id', 'asc')->get();
				
				// dd($serviceLists);

				if($serviceLists->isEmpty()) {
					unset($serviceCategory[$cateKey]);
				} else {
					$servCat['services'] = $serviceLists;
					
					$servicehtml = "";
					foreach($serviceLists as $key => $service)
					{
						$pricearr = array();
						$servicePrices = ServicesPrice::select('id', 'duration', 'price_type', 'lowest_price', 'price', 'special_price', 'is_staff_price')->where('service_id', $service->id)->where('user_id', $locationUserId)->orderBy('id', 'asc')->get();
						
						foreach($servicePrices as $key2 => $servprice)
						{
							$sprice = $servprice->lowest_price;
							$duration = "";
							$timeDuration = $servprice->duration;

							if($service->is_extra_time == 1){
								if($service->extra_time == 0){
									$timeDuration += $service->extra_time_duration;
								}
							}
							
							if($servprice->duration <= 0) 
							{
								$duration = '00h 00min';
							}
							else 
							{  
								if(sprintf("%02d",floor($timeDuration / 60)) > 0)
								{
									$duration .= sprintf("%02d",floor($timeDuration / 60)).'h ';
								} 
									
								if(sprintf("%02d",str_pad(($timeDuration % 60), 2, "0", STR_PAD_LEFT)) > 0)
								{
									$duration .= " ".sprintf("%02d",str_pad(($timeDuration % 60), 2, "0", STR_PAD_LEFT)). "min";
								}
							}
							
							$pr = "";
							if(count($servicePrices) > 1) {
								$pr = "pr".(++$key2);	
							}	
							
							if($servprice->price_type == "free") {
								$service_price = 0;
								$service_price_special = 0;
							} else {	
								if($servprice->price != $sprice) {
									$service_price = $servprice->price;
									$service_price_special = $sprice;
								} else {
									$service_price = $sprice;
									$service_price_special = $sprice;
								}
							}	
							$uniqid = $this->quickRandom();
							
							$tmpArr = array(
								'service_price_id' => $servprice->id,
								'service_price_uid' => $uniqid,
								'service_price_name' => $pr,
								'service_price_duration' => $servprice->duration,
								'service_price_duration_txt' => $duration,
								// 'service_price_duration_txt2' => 'Ramiz',
								'is_staff_price' => $servprice->is_staff_price,
								'service_price_amount' => $service_price,
								'service_price_special_amount' => $service_price_special,
							);	
							
							array_push($pricearr, $tmpArr);
						}
						
						$min_duration = min(array_column($pricearr, 'service_price_duration'));	
						$max_duration = max(array_column($pricearr, 'service_price_duration'));	
						$service['serviceDuration'] = $this->convertDurationText($min_duration)." - ".$this->convertDurationText($max_duration);
						$service['servicePrice'] = $pricearr;
						// dd($service);
					}
				}
			}
		}
		return view('frontend.booking_service', compact('locationID','locationUserId','serviceCategory','serviceTaxes','taxFormula','loggedUserId','LocationData','staff_id','onlineSettingData'));
	}
	
	public function getStaffData(Request $request) 
	{
		if ($request->ajax())
        {
			$staffId = $request->staffId;
			$locationID = $request->locationID;
			$service_ids = $request->service_ids;
			if(empty($service_ids)) {
				$data["status"] = false;
				$data["data"] = [];
				$data["servicedata"] = [];
				$data["availability"] = []; 
				$data["is_skip_staff"] = 0;
				
				return JsonReturn::success($data);
			}

			$userId = $request->userId;
			$total_service = count($service_ids);	
			
			$onlineSettingData = Online_setting::select('is_allowed_staff_selection', 'time_slot_interval')->where('user_id', $userId)->first();
			if(!empty($onlineSettingData)) {
				$is_allowed_staff_selection = $onlineSettingData->is_allowed_staff_selection;
			} else {
				$is_allowed_staff_selection = 0;
			}
			
			$timeSlotInterval = 15;
			if(!empty($onlineSettingData)) {
				$timeSlotInterval = ($onlineSettingData->time_slot_interval > 0) ? $onlineSettingData->time_slot_interval : 15;
			}
			
			$servicePrices = ServicesPrice::select('services_price.*','services.service_name','services.staff_ids')->join('services','services.id','=','services_price.service_id')->where('services_price.user_id', $userId)->whereIn('services_price.id', $service_ids)->get();

			$staffPriceArr = array(); $staffServCnt = array(); $staffServArr = array(); $is_skip_staff = 0;
			
			if($staffId > 0) 
			{
				$staffServArr[] = $staffId;
			} else {
				foreach($servicePrices as $servPr)
				{
					$expl_staff = explode(",",$servPr->staff_ids);
					
					foreach($expl_staff as $staff_id) 
					{
						$tmp_arr = array(
							'staff_id' => $staff_id,
							'service_cnt' => 1
						);	
						
						$stsearch = ['staff_id' => $staff_id];
						$stkeys = array_keys(array_filter($staffServCnt,function ($v) use ($stsearch) { return $v['staff_id'] == $stsearch['staff_id']; } ) );
						
						if(isset($stkeys[0])) {
							$old = $staffServCnt[$stkeys[0]]['service_cnt'];
							$staffServCnt[$stkeys[0]]['service_cnt'] = $old + 1;
						} else {
							array_push($staffServCnt, $tmp_arr);
						}		
					}
				}
				
				$staffList = $findKey = $this->search_revisions($staffServCnt,$total_service, 'service_cnt');
				
				foreach($staffList as $key => $val) {
					$staffServArr[] = $staffServCnt[$val]['staff_id'];	
				}	
			}
		
			foreach($servicePrices as $servPr)
			{
				$expl_staff = $staffServArr;
				
				if($servPr->is_staff_price == 1)
				{
					$staffPrices = json_decode($servPr->staff_prices, true);

					foreach($expl_staff as $staff_id) 
					{
						if(!StaffServices::where(['staff_id' => $staff_id, 'status' => 1, 'service_id' => $servPr->service_id])->exists()) {
							continue;
						}
						if(!StaffLocations::where(['staff_id' => $staff_id, 'location_id' => $locationID])->exists()) {
							continue;
						}
						$staffData = Staff::select('users.id','users.first_name','users.last_name')->leftJoin('users', 'users.id', '=', 'staff.staff_user_id')->where('staff.id', $staff_id)->orderBy('staff.order_id', 'asc')->first();

						$search = ['staff_id' => $staff_id];
						$keys = array_keys(array_filter($staffPrices,function ($v) use ($search) { return $v['staff_id'] == $search['staff_id']; } ) );
						
						if(isset($keys[0]))
						{
							$index = $keys[0];
							$search1 = ['staff_id' => $staff_id];
							$keys1 = array_keys(array_filter($staffPriceArr,function ($v) use ($search1) { return $v['staff_id'] == $search1['staff_id']; } ) );

							$serviceArr1 = array(
								"service_id" => $servPr->service_id,
								"service_name" => $servPr->service_name,
								"service_duration" => $staffPrices[$index]['staff_duration'],
								"service_price" => $staffPrices[$index]['staff_price'],
								"service_spprice" => ($staffPrices[$index]['staff_special_price'] > 0) ? $staffPrices[$index]['staff_special_price'] : $staffPrices[$index]['staff_price']
							);
							
							if(isset($keys1[0]))
							{
								$staffInd = $keys1[0];
								if($servPr->price_type == 'free') {
									$oldAmt = $staffPriceArr[$staffInd]['total_amount'];
									$staffPriceArr[$staffInd]['total_amount'] = $oldAmt;
									
									$oldSpAmt = $staffPriceArr[$staffInd]['total_special_amount'];
									$staffPriceArr[$staffInd]['total_special_amount'] = $oldSpAmt;
								} else {
									$oldAmt = $staffPriceArr[$staffInd]['total_amount'];
									$staffPriceArr[$staffInd]['total_amount'] = $oldAmt + $staffPrices[$index]['staff_price'];
									
									$oldSpAmt = $staffPriceArr[$staffInd]['total_special_amount'];
									$staffPriceArr[$staffInd]['total_special_amount'] = $oldSpAmt + $staffPrices[$index]['staff_special_price'];
								}		
								//array_push($staffPriceArr[$staffInd]['servicesData'], $serviceArr1);
							} else {
								if($servPr->price_type == 'free') {
									$tmp_arr = array(
										'staff_id' => $staff_id,
										'staff_name' => $staffData->first_name." ".$staffData->last_name,
										'total_amount' => 0,
										'total_special_amount' => 0,
										/* 'servicesData' => array(
											$serviceArr1
										) */
									);
								} else {	
									$tmp_arr = array(
										'staff_id' => $staff_id,
										'staff_name' => $staffData->first_name." ".$staffData->last_name,
										'total_amount' => $staffPrices[$index]['staff_price'],
										'total_special_amount' => ($staffPrices[$index]['staff_special_price'] > 0) ? $staffPrices[$index]['staff_special_price'] : $staffPrices[$index]['staff_price'],
										/* 'servicesData' => array(
											$serviceArr1
										) */
									);
								}	
								array_push($staffPriceArr, $tmp_arr);
							}
						}
					}
				}
				else
				{
					foreach($expl_staff as $staff_id) 
					{
						if(!StaffServices::where(['staff_id' => $staff_id, 'status' => 1, 'service_id' => $servPr->service_id])->exists()) {
							continue;
						}
						if(!StaffLocations::where(['staff_id' => $staff_id, 'location_id' => $locationID])->exists()) {
							continue;
						}
						
						$serviceArr1 = array(
							"service_id" => $servPr->service_id,
							"service_name" => $servPr->service_name,
							"service_duration" => $servPr->duration,
							"service_ogprice" => $servPr->price,
							"service_spprice" => $servPr->lowest_price
						);

						$staffData = Staff::select('users.id','users.first_name','users.last_name')->leftJoin('users', 'users.id', '=', 'staff.staff_user_id')->where('staff.id', $staff_id)->orderBy('staff.order_id', 'asc')->first();

						$search1 = ['staff_id' => $staff_id];
						$keys = array_keys(array_filter($staffPriceArr,function ($v) use ($search1) { return $v['staff_id'] == $search1['staff_id']; } ) );
						
						if(isset($keys[0]))
						{
							$staffInd = $keys[0];
							
							if($servPr->price_type == 'free') {
								$oldAmt = $staffPriceArr[$staffInd]['total_amount'];
								$staffPriceArr[$staffInd]['total_amount'] = $oldAmt;
								
								$oldSpAmt = $staffPriceArr[$staffInd]['total_special_amount'];
								$staffPriceArr[$staffInd]['total_special_amount'] = $oldSpAmt;
							} else {
								$oldAmt = $staffPriceArr[$staffInd]['total_amount'];
								$staffPriceArr[$staffInd]['total_amount'] = $oldAmt + $servPr->price;
								
								$oldSpAmt = $staffPriceArr[$staffInd]['total_special_amount'];
								$staffPriceArr[$staffInd]['total_special_amount'] = $oldSpAmt + $servPr->lowest_price;
							}		
							//array_push($staffPriceArr[$staffInd]['servicesData'], $serviceArr1);
						} else {
							if($servPr->price_type == 'free') {
								$tmp_arr = array(
									'staff_id' => $staff_id,
									'staff_name' => $staffData->first_name." ".$staffData->last_name,
									'total_amount' => 0,
									'total_special_amount' => 0,
									/* 'servicesData' => array(
										$serviceArr1
									) */
								);
							} else {	
								$tmp_arr = array(
									'staff_id' => $staff_id,
									'staff_name' => $staffData->first_name." ".$staffData->last_name,
									'total_amount' => $servPr->price,
									'total_special_amount' => $servPr->lowest_price,
									/* 'servicesData' => array(
										$serviceArr1
									) */
								);
							}	
							array_push($staffPriceArr, $tmp_arr);
						}
					}
				}
			}	
			
			if(empty($staffPriceArr)) {
				$data["status"] = false;
				$data["data"] = [];
				$data["servicedata"] = [];
				$data["availability"] = []; 
				$data["is_skip_staff"] = 0;
				
				return JsonReturn::success($data);
			}
			/* echo "<pre>";
			print_r($staffPriceArr);
			die; */
			
			$sort = array();
			foreach($staffPriceArr as $k=>$v) {
				$sort['total_special_amount'][$k] = $v['total_special_amount'];
			}

			if(!empty($sort['total_special_amount'])) {
				array_multisort($sort['total_special_amount'], SORT_ASC,$staffPriceArr);
			}
			
			$timeSlotArr = $serviceArr = array();
			if(count($staffPriceArr) == 1 || $is_allowed_staff_selection == 0) 
			{
				$is_skip_staff = 1;	
				$userId = $request->userId;
				$staffId = $staffPriceArr[0]['staff_id'];
			
				$servicePrices = ServicesPrice::select('services_price.*','services.service_name','services.staff_ids')->join('services','services.id','=','services_price.service_id')->where('services_price.user_id', $userId)->whereIn('services_price.id', $service_ids)->get();
			
				$totaServiceDuration = 0;
				foreach($servicePrices as $servPr)
				{
					$expl_staff = explode(",",$servPr->staff_ids);

					if($servPr->is_staff_price == 1)
					{
						$staffPrices = json_decode($servPr->staff_prices, true);

						$search = ['staff_id' => $staffId];
						$keys = array_keys(array_filter($staffPrices,function ($v) use ($search) { return $v['staff_id'] == $search['staff_id']; } ) );
						
						if(isset($keys[0]))
						{
							$index = $keys[0];
							$totaServiceDuration = $totaServiceDuration + $staffPrices[$index]['staff_duration'];
							
							if($servPr->price_type == 'free') {
								$tmp_arr = array(
									"service_id" => $servPr->service_id,
									"service_price_id" => $servPr->id,
									"service_name" => $servPr->service_name,
									"service_duration" => $staffPrices[$index]['staff_duration'],
									"service_duration_txt" => $this->convertDurationText($staffPrices[$index]['staff_duration']),
									"service_price" => 0,
									"service_spprice" => 0
								);
							} else {	
								$tmp_arr = array(
									"service_id" => $servPr->service_id,
									"service_price_id" => $servPr->id,
									"service_name" => $servPr->service_name,
									"service_duration" => $staffPrices[$index]['staff_duration'],
									"service_duration_txt" => $this->convertDurationText($staffPrices[$index]['staff_duration']),
									"service_price" => 0,
									"service_spprice" => 0
								);
							}	
							array_push($serviceArr, $tmp_arr);
						}	
					}
					else
					{
						$totaServiceDuration = $totaServiceDuration + $servPr->duration;
						
						if($servPr->price_type == 'free') {
							$tmp_arr = array(
								"service_id" => $servPr->service_id,
								"service_price_id" => $servPr->id,
								"service_name" => $servPr->service_name,
								"service_duration" => $servPr->duration,
								"service_duration_txt" => $this->convertDurationText($servPr->duration),
								"service_ogprice" => 0,
								"service_spprice" => 0
							);
						} else {	
							$tmp_arr = array(
								"service_id" => $servPr->service_id,
								"service_price_id" => $servPr->id,
								"service_name" => $servPr->service_name,
								"service_duration" => $servPr->duration,
								"service_duration_txt" => $this->convertDurationText($servPr->duration),
								"service_ogprice" => $servPr->price,
								"service_spprice" => $servPr->lowest_price
							);
						}	
						array_push($serviceArr, $tmp_arr);
					}
				}	
				
				$curr_date = date("Y-m-d");
				$curr_day = date("N");
				
				$workinghours = StaffWorkingHours::where('staff_id',$staffId)->where('day',$curr_day)->first(); 
				
				if(!empty($workinghours)) {
					$start_time = json_decode($workinghours['start_time'],true);
					$end_time = json_decode($workinghours['end_time'],true);
					
					foreach($start_time as $key => $val)
					{
						if($val != 0) {
							$endTime = $end_time[$key];
							$endTime = date("H:i", strtotime("- ".$totaServiceDuration." minutes", strtotime($endTime)));
							$times = $this->getServiceScheduleSlots($timeSlotInterval, $val, $endTime);
							array_push($timeSlotArr, $times);
						}		
					}
				}
				
				$staffDetails = Staff::getStaffDetailByStaffID($staffId);
				$staffMainUserId = $staffDetails->user_id;
				$this->getBookedSlots($timeSlotArr, $curr_date, $staffMainUserId);
				$this->removeBlockedTime($timeSlotArr, $curr_date, $staffMainUserId);
			}	
			

			$data["status"] = true;
			$data["data"] = $staffPriceArr;
			$data["servicedata"] = $serviceArr;
			$data["availability"] = $timeSlotArr; 
			$data["is_skip_staff"] = $is_skip_staff;
            return JsonReturn::success($data);
		}		
	}	
	
	function sortByAge($a, $b) {
		return $a['total_special_amount'] > $b['total_special_amount'];
	}
	
	function search_revisions($dataArray, $search_value, $key_to_search, $other_matching_value = null, $other_matching_key = null) {
		// This function will search the revisions for a certain value
		// related to the associative key you are looking for.
		$keys = array();
		foreach ($dataArray as $key => $cur_value) {
			if ($cur_value[$key_to_search] == $search_value) {
				if (isset($other_matching_key) && isset($other_matching_value)) {
					if ($cur_value[$other_matching_key] == $other_matching_value) {
						$keys[] = $key;
					}
				} else {
					// I must keep in mind that some searches may have multiple
					// matches and others would not, so leave it open with no continues.
					$keys[] = $key;
				}
			}
		}
		return $keys;
	}
	
	/* function multi_intersect($arr) {
		$return = array();
		foreach ($arr as $key => $a) {
			foreach ($arr as $key1 => $b) {
				if ($a === $b) continue;
				$return = array_merge($return, array_intersect($a, $b));
			}
		}
		return array_unique($return);
	} */
	
	public function getStaffTime(Request $request) 
	{
		if ($request->ajax())
        {
			$service_ids = $request->service_ids;
			$userId = $request->userId;
			$staffId = $request->staffId;
			$book_date = $request->book_date;
			$locationID = $request->locationID;

			$servicePrices = ServicesPrice::select('services_price.*','services.service_name','services.staff_ids')->join('services','services.id','=','services_price.service_id')->where('services_price.user_id', $userId)->whereIn('services_price.id', $service_ids)->get();
			
			$onlineSettingData = Online_setting::select('online_setting.time_slot_interval')->where('online_setting.user_id', $userId)->first();
			
			$timeSlotInterval = 15;
			if(!empty($onlineSettingData)) {
				$timeSlotInterval = ($onlineSettingData->time_slot_interval > 0) ? $onlineSettingData->time_slot_interval : 15;
			}	
			
			$serviceArr = array(); $totaServiceDuration = 0;
			foreach($servicePrices as $servPr)
			{
				$expl_staff = explode(",",$servPr->staff_ids);

				if($servPr->is_staff_price == 1)
				{
					$staffPrices = json_decode($servPr->staff_prices, true);

					$search = ['staff_id' => $staffId];
					$keys = array_keys(array_filter($staffPrices,function ($v) use ($search) { return $v['staff_id'] == $search['staff_id']; } ) );
					
					if(isset($keys[0]))
					{
						$index = $keys[0];
						$totaServiceDuration = $totaServiceDuration + $staffPrices[$index]['staff_duration'];
						$tmp_arr = array(
							"service_id" => $servPr->service_id,
							"service_price_id" => $servPr->id,
							"service_name" => $servPr->service_name,
							"service_duration" => $staffPrices[$index]['staff_duration'],
							"service_duration_txt" => $this->convertDurationText($staffPrices[$index]['staff_duration']),
							"service_price" => $staffPrices[$index]['staff_price'],
							"service_spprice" => ($staffPrices[$index]['staff_special_price'] > 0) ? $staffPrices[$index]['staff_special_price'] : $staffPrices[$index]['staff_price']
						);
						array_push($serviceArr, $tmp_arr);
					}	
				}
				else
				{
					$totaServiceDuration = $totaServiceDuration + $servPr->duration;
					
					if($servPr->price_type == 'free') {
						$tmp_arr = array(
							"service_id" => $servPr->service_id,
							"service_price_id" => $servPr->id,
							"service_name" => $servPr->service_name,
							"service_duration_txt" => $this->convertDurationText($servPr->duration),
							"service_duration" => $servPr->duration,
							"service_ogprice" => 0,
							"service_spprice" => 0
						);
					} else {	
						$tmp_arr = array(
							"service_id" => $servPr->service_id,
							"service_price_id" => $servPr->id,
							"service_name" => $servPr->service_name,
							"service_duration_txt" => $this->convertDurationText($servPr->duration),
							"service_duration" => $servPr->duration,
							"service_ogprice" => $servPr->price,
							"service_spprice" => $servPr->lowest_price
						);
					}
					array_push($serviceArr, $tmp_arr);
				}
			}	
			
			if($book_date != "") {	
				$curr_date = date("Y-m-d", strtotime($book_date));
				$curr_day = date("N", strtotime($curr_date));
			} else {	
				$curr_date = date("Y-m-d");
				$curr_day = date("N", strtotime($curr_date));
			}	
				
			$getClosedDate = Staff_closedDate::select('closed_dates.id')->where('user_id',$userId)->whereRaw('FIND_IN_SET('.$locationID.', location_id)')->where('start_date', '<=', $curr_date)->where('end_date', '>=', $curr_date)->first();

			if(!empty($getClosedDate)) {
				$data['closed'] = true;

				$temp_curr_date = date('Y-m-d', strtotime($curr_date.' +1 day'));

				$countLoop = 0;
				$nextOpenDate = false;
				while(!$nextOpenDate && ($countLoop < 30)) {
					$tempGetClosedDate = Staff_closedDate::select('closed_dates.id')->where('user_id',$userId)->whereRaw('FIND_IN_SET('.$locationID.', location_id)')->where('start_date', '<=', $temp_curr_date)->where('end_date', '>=', $temp_curr_date)->first();
					if(empty($tempGetClosedDate)) {
						$nextOpenDate = date('d M', strtotime($temp_curr_date));
						break;
					}
					$temp_curr_date = date('Y-m-d', strtotime($temp_curr_date.' +1 day'));
					$countLoop++;
				}

				$locationRow = Location::find($locationID);
				$locationName = !empty($locationRow->location_name) ? $locationRow->location_name : 'shop';
				$data['closedMessage'] = '<h4>The '.$locationName.' is closed on this day.</h4>';

				if(!empty($nextOpenDate)) {
					$data['closedMessage'] .= '<br><h6 class="text-muted">but you can book for '.date('d M', strtotime($nextOpenDate)).'</h6>';
				}
			} else {
				$data['closed'] = false;
				$data['closedMessage'] = '';
			}
			
			$workinghours = StaffWorkingHours::where('staff_id',$staffId)->where('day',$curr_day)->first(); 
			
			$staffDetails = Staff::getStaffDetailByStaffID($staffId);
			$staffMainUserId = $staffDetails->user_id;
			
			$timeSlotArr = array();
			$is_closed = 0;
			
			if(!empty($workinghours)) 
			{
				if(isset($day['is_closed']) == 1){
					$is_closed = 1;	
				} else {
					if($workinghours->repeats == 0 && $workinghours->date == $curr_date) {
						$start_time = json_decode($workinghours['start_time'],true);
						$end_time = json_decode($workinghours['end_time'],true);
						
						foreach($start_time as $key => $val)
						{
							if($val != 0) {
								$endTime = $end_time[$key];
								$endTime = date("H:i", strtotime("- ".$totaServiceDuration." minutes", strtotime($endTime)));
								$times = $this->getServiceScheduleSlots($timeSlotInterval, $val, $endTime);
								array_push($timeSlotArr, $times);
							}		
						}
					} else if($workinghours->repeats == 1 && $workinghours->endrepeat != 0) {
						$lastDate = date("Y-m-d", strtotime($workinghours->endrepeat));
						
						if(strtotime($lastDate) > strtotime($curr_date)) {
							$start_time = json_decode($workinghours['start_time'],true);
							$end_time = json_decode($workinghours['end_time'],true);
							
							foreach($start_time as $key => $val)
							{
								if($val != 0) {
									$endTime = $end_time[$key];
									$endTime = date("H:i", strtotime("- ".$totaServiceDuration." minutes", strtotime($endTime)));
									$times = $this->getServiceScheduleSlots($timeSlotInterval, $val, $endTime);
									array_push($timeSlotArr, $times);
								}		
							}
						}	
					} else if($workinghours->repeats == 1) {
						$start_time = json_decode($workinghours['start_time'],true);
						$end_time = json_decode($workinghours['end_time'],true);
						
						foreach($start_time as $key => $val)
						{
							if($val != 0) {
								$endTime = $end_time[$key];
								$endTime = date("H:i", strtotime("- ".$totaServiceDuration." minutes", strtotime($endTime)));
								$times = $this->getServiceScheduleSlots($timeSlotInterval, $val, $endTime);
								array_push($timeSlotArr, $times);
							}		
						}
					}	
				}	
			}
			
			$staffDetails = Staff::getStaffDetailByStaffID($staffId);
			$staffMainUserId = $staffDetails->user_id;
			$this->getBookedSlots($timeSlotArr, $curr_date, $staffMainUserId);
			$this->removeBlockedTime($timeSlotArr, $curr_date, $staffMainUserId);
			
			$data["status"] = true;
			$data["data"] = $serviceArr; 
			$data["availability"] = $timeSlotArr; 
            return JsonReturn::success($data);
		}		
	}	
	
	public function saveBookingAppointment(Request $request)
	{
		if ($request->ajax())
        {
			$loggedUserId = $request->loggedUserId;
			
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
			$taxAmount = 0;
			
			if($taxFormula == 0) {
				$itemsubtotal = $inoviceTotal - $taxAmount;
			} else {
				$itemsubtotal = $inoviceTotal;
			}
			$appointmentAmount = $itemsubtotal;
			
			$fUserData = frontUser::select('id','email','name','last_name','mobile')->where('id', $loggedUserId)->first();
			$checkClient = Clients::select('id')->where('user_id', $userId)->where('email', $fUserData->email)->first();
			$onlineSettingData = Online_setting::select('*')->where('online_setting.user_id', $userId)->first();
			$locationData = Location::select('*')->where('locations.id', $locationId)->first();
			
			if(empty($checkClient)) {
				
				$insClient = Clients::create([
					'fuser_id'           => $loggedUserId,
					'user_id'            => $userId,
					'firstname'      	 => $fUserData->name,
					'lastname'        	 => $fUserData->last_name,
					'mobileno'   		 => $fUserData->mobile,
					'email'  			 => $fUserData->email,
					'created_at'         => date("Y-m-d H:i:s"),
					'updated_at'         => date("Y-m-d H:i:s")
				]);
				
				$client_id = $insClient->id;
			} else { 
				$checkClient->fuser_id = $fUserData->id;
				$checkClient->save();
				
				$client_id = $checkClient->id;
			}		
			
			$clientData = Clients::select('*')->where('id', $client_id)->first();
			$getStaffData = Staff::select('staff.id','staff.staff_user_id','users.first_name','users.last_name','users.email')->leftJoin('users','users.id','staff.staff_user_id')->where('staff.id', $staffId)->first();
			
			$Appointments = Appointments::create([
				'user_id'               => $userId,
				'staff_user_id'         => $getStaffData->staff_user_id,
				'location_id'           => $locationId,
				'appointment_date'      => $appointment_date,
				'appointment_notes'     => $appointment_notes,
				'total_amount'  	    => $appointmentAmount,
				'final_total'  		    => $appointmentAmount,
				'client_id'             => $client_id,
				'appointment_status'    => 0,
				'is_paid'               => 0,
				'is_online_appointment' => 1,
				'created_by'            => 0,
				'created_at'            => date("Y-m-d H:i:s"),
				'updated_at'            => date("Y-m-d H:i:s")
			]);
			$AppointmentId = $Appointments->id;
				
			if($AppointmentId > 0) 
			{	
				$appStartFrom = $bookingTime;
				foreach($itemPrId as $key => $val) 
				{	
					$item_type = $itemType[$key];
					$item_id = $val;
					$serviceDuration = $itemDur[$key];
					$AppointmentServicesId = 0;

					$getItemId = ServicesPrice::select('services_price.id','services_price.service_id')->where('services_price.id', $item_id)->first();
					/*echo "<pre>";
					print_r($getItemId);
					exit;*/
					$item_id = $getItemId ? $getItemId->service_id : '';
					
					$serviceData = Services::select('id','is_extra_time','extra_time','extra_time_duration')->where('id', $item_id)->first();
					if(isset($serviceData)){
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
							'appointment_id'      => $AppointmentId,
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
				
				// if($onlineSettingData->is_send_booked_staff == 1 || $onlineSettingData->is_specific_email == 1)
				// {	
				// 	$appointmentData = AppointmentServices::select('appointment_services.id', 'appointment_services.appointment_date', 'appointment_services.start_time', 'services.service_name')->leftJoin('services_price','services_price.id','appointment_services.service_price_id')->leftJoin('services','services.id','services_price.service_id')->where('appointment_services.appointment_id', $AppointmentId)->get();
					
				// 	$serviceArr = array();
					
				// 	foreach($appointmentData as $appData) {
				// 		$tmp_arr = array(
				// 			'name' => $appData->service_name." with ".$getStaffData->first_name." ".$getStaffData->last_name,
				// 			'time' => date("l, d M Y", strtotime($appData->appointment_date))." at ".date("h:ia", strtotime($appData->start_time))
				// 		);
				// 		array_push($serviceArr, $tmp_arr);
				// 	}	
					
				// 	if($onlineSettingData->is_send_booked_staff == 1)
				// 	{
				// 		echo $TO_EMAIL = $getStaffData->email;
				// 		$FROM_EMAIL = env("MAIL_FROM_ADDRESS", "info@ikotel.ca");
				// 		$FROM_NAME = $locationData->location_name;
				// 		$SUBJECT = "New appointment ";
				// 		$MESSAGE = $serviceArr;
				// 		$LOCATION = $locationData->location_name." <br> ".$locationData->location_address;
				// 		$CUSTOMER = $clientData->firstname." ".$clientData->lastname." <br> ".$clientData->email." <br> ".$clientData->mo_country_code."".$clientData->mobileno;
						
				// 		echo $SendMail = Mail::to($TO_EMAIL)->send(new AppointmentNotification($FROM_EMAIL,$FROM_NAME,$SUBJECT,$MESSAGE,$LOCATION,$CUSTOMER));
				// 		echo "asd";
						
				// 	}
				// } 
			}	
			//die;
			// get consultation form data
			$ConsForm = ConsForm::select('*')->with('client')->with('qna')->where('user_id', $userId)->where('status',1)->get()->toArray();
			
			if(!empty($ConsForm)) {
				
				Session::put('isConsultationForm', 'Yes');
				
				foreach($ConsForm as $ConsFormData) {
					$AskCientsToComplete = $ConsFormData['complete'];
					if($AskCientsToComplete == 1) {
						$ClientConsultationFormGet = ClientConsultationForm::select('id')->where('user_id', $userId)->where('fuser_id',$loggedUserId)->where('consultation_from_id',$ConsFormData['id'])->get()->toArray();
						if(empty($ClientConsultationFormGet)) {
							
							$fUserData = frontUser::select('id','email','name','last_name','mobile')->where('id', $loggedUserId)->first();
							
							$ClientConsultationForm = ClientConsultationForm::create([
								"fuser_id"                           => $loggedUserId,
								"client_id"                          => $client_id,
								"user_id"                            => $userId,
								"appointment_id"                     => $AppointmentId,
								"location_id"                        => $locationId,
								"complete_before"                    => $appointment_date.' '.$bookingTime,
								"consultation_from_id"               => $ConsFormData['id'],
								"consultation_from_client_detail_id" => $ConsFormData['client']['id'],
								"is_first_name"                      => $ConsFormData['client']['first_name'],
								"client_first_name"                  => $fUserData->name,
								"is_last_name"                       => $ConsFormData['client']['last_name'],
								"client_last_name"                   => $fUserData->last_name,
								"is_email"                           => $ConsFormData['client']['email'],
								"client_email"                       => $fUserData->email,
								"is_birthday"                        => $ConsFormData['client']['birthday'],
								"client_birthday"                    => null,
								"is_mobile"                          => $ConsFormData['client']['mobile'],
								"country_code"                       => $ConsFormData['client']['country_code'],
								"client_mobile"                      => $fUserData->mobile,
								"is_gender"                          => $ConsFormData['client']['gender'],
								"client_gender"                      => null,
								"is_address"                         => $ConsFormData['client']['address'],
								"client_address"                     => null,
								"status"                             => 0,
								"is_signature"                       => $ConsFormData['signature'],
								"created_at"                         => date("Y-m-d H:i:s")
							]);
							
							$ClientConsultationFormId = $ClientConsultationForm->id;
							
							if(!empty($ConsFormData['qna'])){
								foreach($ConsFormData['qna'] as $QuestionAnswersData) {
									
									$field_values = '';
									if($QuestionAnswersData['answer_type'] == 'shortAnswer') {
										$field_values = $QuestionAnswersData['1ans'];
									} else if($QuestionAnswersData['answer_type'] == 'longAnswer') {
										$field_values = $QuestionAnswersData['2ans'];
									} else if($QuestionAnswersData['answer_type'] == 'singleAnswer') {
										$field_values = $QuestionAnswersData['3ans'];
									} else if($QuestionAnswersData['answer_type'] == 'singleCheckbox') {
										$field_values = $QuestionAnswersData['4ans'];
									} else if($QuestionAnswersData['answer_type'] == 'multipleCheckbox') {
										$field_values = $QuestionAnswersData['5ans'];
									} else if($QuestionAnswersData['answer_type'] == 'dropdown') {
										$field_values = $QuestionAnswersData['6ans'];
									} else if($QuestionAnswersData['answer_type'] == 'yesOrNo') {
										$field_values = $QuestionAnswersData['7ans'];
									} else if($QuestionAnswersData['answer_type'] == 'des') {
										$field_values = $QuestionAnswersData['8ans'];
									}
									
									$ClientConsultationFormField = ClientConsultationFormField::create([
										"client_consultation_form_id" => $ClientConsultationFormId,
										"fuser_id"                    => $loggedUserId,
										"client_id"                   => $client_id,
										"user_id"                     => $userId,
										"appointment_id"              => $AppointmentId,
										"location_id"                 => $locationId,
										"consultation_from_field_id"  => $QuestionAnswersData['id'],
										"section_id"                  => $QuestionAnswersData['section_id'],
										"section_title"               => $QuestionAnswersData['title'],
										"section_description"         => $QuestionAnswersData['des'],
										"question"                    => $QuestionAnswersData['question'],
										"is_required"                 => $QuestionAnswersData['required'],
										"field_type"                  => $QuestionAnswersData['answer_type'],
										"field_values"                => $field_values,
										"client_answer"               => null,
										"created_at"                  => date("Y-m-d H:i:s"),
									]);
									
								}
							}
							
							// get generated consultation forms
							$ClientConsultationFormGet = ClientConsultationForm::select('client_consultation_form.id','client_consultation_form.client_id','client_consultation_form.user_id','client_consultation_form.user_id','client_consultation_form.location_id','client_consultation_form.appointment_id','client_consultation_form.complete_before','clients.firstname','clients.lastname','clients.email','locations.location_name','locations.location_address','locations.location_latitude','locations.location_longitude','locations.location_image')->leftJoin('clients','clients.id','client_consultation_form.client_id')->leftJoin('locations','locations.id','client_consultation_form.location_id')->where('client_consultation_form.id',$ClientConsultationFormId)->get()->first()->toArray();	
							
							// get appointment details
							$Appointment = Appointments::select('*')->where('id',$ClientConsultationFormGet['appointment_id'])->orderBy('id', 'desc')->get()->first()->toArray();
							
							// get appointment services
							$AppointmentServices = AppointmentServices::select('*')->where('appointment_id',$ClientConsultationFormGet['appointment_id'])->orderBy('id', 'desc')->get()->toArray();
							
							$ClientServices = array();
							
							if(!empty($AppointmentServices))
							{
								foreach($AppointmentServices as $AppointmentServiceData)
								{
									$service_price_id = $AppointmentServiceData['service_price_id'];
									
									$Services = ServicesPrice::select('services_price.id as service_price_id','services_price.duration','services_price.price_type','services_price.price','services_price.special_price','services_price.pricing_name','services_price.lowest_price','services_price.staff_prices','services.id as service_id','services.service_name','services.extra_time','services.is_extra_time','services.extra_time_duration')->join('services','services.id','=','services_price.service_id')->where('services_price.id',$service_price_id)->orderBy('services_price.id', 'ASC')->get()->first()->toArray();
									
									$getUser = User::getUserbyID($AppointmentServiceData['staff_user_id']);
									
									$tempClientServices['appointment_service_id'] = $AppointmentServiceData['id'];
									$tempClientServices['duration']               = $this->hoursandmins($AppointmentServiceData['duration']);
									$tempClientServices['price']                  = ($Services['price']) ? $Services['price'] : 0;
									$tempClientServices['special_price']          = $AppointmentServiceData['special_price'];
									$tempClientServices['staff_user_id']          = $AppointmentServiceData['staff_user_id'];
									$tempClientServices['staff_name']             = $getUser->first_name.' '.$getUser->last_name;
									$tempClientServices['service_name']           = ($Services['service_name']) ? $Services['service_name'] : '';
									$tempClientServices['service_pricing_name']   = ($Services['pricing_name']) ? $Services['pricing_name'] : '';
									$tempClientServices['start_time']             = ($AppointmentServiceData['start_time']) ? date("h:ia",strtotime($AppointmentServiceData['start_time'])) : '';
									
									array_push($ClientServices,$tempClientServices);
								}
							}
							
							$FROM_EMAIL     = env("MAIL_FROM_ADDRESS", "info@ikotel.ca");
							$FROM_NAME      = ($ClientConsultationFormGet) ? $ClientConsultationFormGet['location_name'] : 'Location';
							$TO_EMAIL       = ($ClientConsultationFormGet) ? $ClientConsultationFormGet['email'] : '';
							$SUBJECT        = 'We need some details before your appointment '.date("l, M d",strtotime($ClientConsultationFormGet['complete_before'])).' at '.date("h:i a",strtotime($ClientConsultationFormGet['complete_before']));
							$MESSAGE        = 'Appointment';
							$UniqueId       = $this->unique_code(30).time();
							
							$SendMail = Mail::to($TO_EMAIL)->send(new ConsultationFormReminder($FROM_EMAIL,$FROM_NAME,$SUBJECT,$MESSAGE,$UniqueId,$ClientConsultationFormGet,$Appointment,$ClientServices));	

							EmailLog::create([
								'user_id'          => ($ClientConsultationFormGet) ? $ClientConsultationFormGet['user_id'] : '',
								'client_id'        => ($ClientConsultationFormGet) ? $ClientConsultationFormGet['client_id'] : '',
								'appointment_id'   => ($ClientConsultationFormGet) ? $ClientConsultationFormGet['appointment_id'] : '',	
								'from_email'       => $FROM_EMAIL,	
								'to_email'         => $TO_EMAIL,	
								'module_type'      => 3,	
								'module_type_text' => 'Consultation Form Reminder',
								'unique_id'        => $UniqueId,
								'created_at'       => date("Y-m-d H:i:s")
							]);
						}
					} else {
						$fUserData = frontUser::select('id','email','name','last_name','mobile')->where('id', $loggedUserId)->first();
							
						$ClientConsultationForm = ClientConsultationForm::create([
							"fuser_id"                           => $loggedUserId,
							"client_id"                          => $client_id,
							"user_id"                            => $userId,
							"appointment_id"                     => $AppointmentId,
							"location_id"                        => $locationId,
							"complete_before"                    => $appointment_date.' '.$bookingTime,
							"consultation_from_id"               => $ConsFormData['id'],
							"consultation_from_client_detail_id" => $ConsFormData['client']['id'],
							"is_first_name"                      => $ConsFormData['client']['first_name'],
							"client_first_name"                  => $fUserData->name,
							"is_last_name"                       => $ConsFormData['client']['last_name'],
							"client_last_name"                   => $fUserData->last_name,
							"is_email"                           => $ConsFormData['client']['email'],
							"client_email"                       => $fUserData->email,
							"is_birthday"                        => $ConsFormData['client']['birthday'],
							"client_birthday"                    => null,
							"is_mobile"                          => $ConsFormData['client']['mobile'],
							"country_code"                       => $ConsFormData['client']['country_code'],
							"client_mobile"                      => $fUserData->mobile,
							"is_gender"                          => $ConsFormData['client']['gender'],
							"client_gender"                      => null,
							"is_address"                         => $ConsFormData['client']['address'],
							"client_address"                     => null,
							"status"                             => 0,
							"is_signature"                       => $ConsFormData['signature'],
							"created_at"                         => date("Y-m-d H:i:s")
						]);
						
						$ClientConsultationFormId = $ClientConsultationForm->id;
						
						if(!empty($ConsFormData['qna'])){
							foreach($ConsFormData['qna'] as $QuestionAnswersData) {
								
								$field_values = '';
								if($QuestionAnswersData['answer_type'] == 'shortAnswer') {
									$field_values = $QuestionAnswersData['1ans'];
								} else if($QuestionAnswersData['answer_type'] == 'longAnswer') {
									$field_values = $QuestionAnswersData['2ans'];
								} else if($QuestionAnswersData['answer_type'] == 'singleAnswer') {
									$field_values = $QuestionAnswersData['3ans'];
								} else if($QuestionAnswersData['answer_type'] == 'singleCheckbox') {
									$field_values = $QuestionAnswersData['4ans'];
								} else if($QuestionAnswersData['answer_type'] == 'multipleCheckbox') {
									$field_values = $QuestionAnswersData['5ans'];
								} else if($QuestionAnswersData['answer_type'] == 'dropdown') {
									$field_values = $QuestionAnswersData['6ans'];
								} else if($QuestionAnswersData['answer_type'] == 'yesOrNo') {
									$field_values = $QuestionAnswersData['7ans'];
								} else if($QuestionAnswersData['answer_type'] == 'des') {
									$field_values = $QuestionAnswersData['8ans'];
								}
								
								$ClientConsultationFormField = ClientConsultationFormField::create([
									"client_consultation_form_id" => $ClientConsultationFormId,
									"fuser_id"                    => $loggedUserId,
									"client_id"                   => $client_id,
									"user_id"                     => $userId,
									"appointment_id"              => $AppointmentId,
									"location_id"                 => $locationId,
									"consultation_from_field_id"  => $QuestionAnswersData['id'],
									"section_id"                  => $QuestionAnswersData['section_id'],
									"section_title"               => $QuestionAnswersData['title'],
									"section_description"         => $QuestionAnswersData['des'],
									"question"                    => $QuestionAnswersData['question'],
									"is_required"                 => $QuestionAnswersData['required'],
									"field_type"                  => $QuestionAnswersData['answer_type'],
									"field_values"                => $field_values,
									"client_answer"               => null,
									"created_at"                  => date("Y-m-d H:i:s"),
								]);
								
							}
						}
						
						// get generated consultation forms
						$ClientConsultationFormGet = ClientConsultationForm::select('client_consultation_form.id','client_consultation_form.client_id','client_consultation_form.user_id','client_consultation_form.user_id','client_consultation_form.location_id','client_consultation_form.appointment_id','client_consultation_form.complete_before','clients.firstname','clients.lastname','clients.email','locations.location_name','locations.location_address','locations.location_latitude','locations.location_longitude','locations.location_image')->leftJoin('clients','clients.id','client_consultation_form.client_id')->leftJoin('locations','locations.id','client_consultation_form.location_id')->where('client_consultation_form.id',$ClientConsultationFormId)->get()->first()->toArray();	
						
						// get appointment details
						$Appointment = Appointments::select('*')->where('id',$ClientConsultationFormGet['appointment_id'])->orderBy('id', 'desc')->get()->first()->toArray();
						
						// get appointment services
						$AppointmentServices = AppointmentServices::select('*')->where('appointment_id',$ClientConsultationFormGet['appointment_id'])->orderBy('id', 'desc')->get()->toArray();
						
						$ClientServices = array();
						
						if(!empty($AppointmentServices))
						{
							foreach($AppointmentServices as $AppointmentServiceData)
							{
								$service_price_id = $AppointmentServiceData['service_price_id'];
								
								$Services = ServicesPrice::select('services_price.id as service_price_id','services_price.duration','services_price.price_type','services_price.price','services_price.special_price','services_price.pricing_name','services_price.lowest_price','services_price.staff_prices','services.id as service_id','services.service_name','services.extra_time','services.is_extra_time','services.extra_time_duration')->join('services','services.id','=','services_price.service_id')->where('services_price.id',$service_price_id)->orderBy('services_price.id', 'ASC')->get()->first()->toArray();
								
								$getUser = User::getUserbyID($AppointmentServiceData['staff_user_id']);
								
								$tempClientServices['appointment_service_id'] = $AppointmentServiceData['id'];
								$tempClientServices['duration']               = $this->hoursandmins($AppointmentServiceData['duration']);
								$tempClientServices['price']                  = ($Services['price']) ? $Services['price'] : 0;
								$tempClientServices['special_price']          = $AppointmentServiceData['special_price'];
								$tempClientServices['staff_user_id']          = $AppointmentServiceData['staff_user_id'];
								$tempClientServices['staff_name']             = $getUser->first_name.' '.$getUser->last_name;
								$tempClientServices['service_name']           = ($Services['service_name']) ? $Services['service_name'] : '';
								$tempClientServices['service_pricing_name']   = ($Services['pricing_name']) ? $Services['pricing_name'] : '';
								$tempClientServices['start_time']             = ($AppointmentServiceData['start_time']) ? date("h:ia",strtotime($AppointmentServiceData['start_time'])) : '';
								
								array_push($ClientServices,$tempClientServices);
							}
						}
						
						$FROM_EMAIL     = env("MAIL_FROM_ADDRESS", "info@ikotel.ca");
						$FROM_NAME      = ($ClientConsultationFormGet) ? $ClientConsultationFormGet['location_name'] : 'Location';
						$TO_EMAIL       = ($ClientConsultationFormGet) ? $ClientConsultationFormGet['email'] : '';
						$SUBJECT        = 'We need some details before your appointment '.date("l, M d",strtotime($ClientConsultationFormGet['complete_before'])).' at '.date("h:i a",strtotime($ClientConsultationFormGet['complete_before']));
						$MESSAGE        = 'Appointment';
						$UniqueId       = $this->unique_code(30).time();
						
						$SendMail = Mail::to($TO_EMAIL)->send(new ConsultationFormReminder($FROM_EMAIL,$FROM_NAME,$SUBJECT,$MESSAGE,$UniqueId,$ClientConsultationFormGet,$Appointment,$ClientServices));	
						
						EmailLog::create([
							'user_id'          => ($ClientConsultationFormGet) ? $ClientConsultationFormGet['user_id'] : '',
							'client_id'        => ($ClientConsultationFormGet) ? $ClientConsultationFormGet['client_id'] : '',
							'appointment_id'   => ($ClientConsultationFormGet) ? $ClientConsultationFormGet['appointment_id'] : '',	
							'from_email'       => $FROM_EMAIL,	
							'to_email'         => $TO_EMAIL,	
							'module_type'      => 3,	
							'module_type_text' => 'Consultation Form Reminder',
							'unique_id'        => $UniqueId,
							'created_at'       => date("Y-m-d H:i:s")
						]);
					}
				}
			}

			$fuserName = isset($fUserData->name) ? $fUserData->name : '';
			$totalServices = is_array($itemPrId) ? count($itemPrId) : '';
			# Send notification
			$title = $fuserName.' booked online '.$appointmentAmount;
			$description = date('D d M h:ia', strtotime($appointment_date.' '.$bookingTime)).' for '.$totalServices.' services booked on Book Now link with '.$fuserName.' at '.(isset($locationData->location_name) ? $locationData->location_name : '');
			
			/*$notificationParams = [
				'user_id'           => $userId,
				'client_id'         => $client_id,
				'type'              => 'appointment',
				'type_id'           => $AppointmentId,
				'title'				=> $title,
				'description' 		=> $description
			];

			$this->notificationRepositorie->storeNotification($notificationParams);*/
			$staffUserId = !empty($getStaffData) ? $getStaffData->staff_user_id : '';
			$this->notificationRepositorie->sendNotification($staffUserId, $client_id, 'appointment', $AppointmentId, $title, $description, $locationId, 'new_appointment');

			$data["status"] = true;
			$data["redirect"] = route('myAppointments',['appointmentId' => base64_encode($AppointmentId)]);
			$data["message"] = "Appointment has been booked successfully.";
			return JsonReturn::success($data);

		}
	}
	
	function getServiceScheduleSlots($duration, $start,$end)
	{
		$start_time = date('H:i', strtotime($start));
		$end_time = date('H:i', strtotime($end));
		$i=0;
		$time = array();
		while(strtotime($start_time) <= strtotime($end_time)) {
			$start = $start_time;
			$end = date('H:i',strtotime('+'.$duration.' minutes',strtotime($start_time)));
			$start_time = date('H:i',strtotime('+'.$duration.' minutes',strtotime($start_time)));
			$i++;
			if(strtotime($start_time) <= strtotime($end_time)){
				$tmpArr = array(
					'start' => date("h:i A",strtotime($start)),
					'end' => date("h:i A",strtotime($end))
				);
				array_push($time, $tmpArr);
			}
		}
		return $time;
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
		return $hours.'h '.$minutes.'min';
	}
	
	public function bookingFlowSignup(Request $request){
		if ($request->ajax()) {
			$rules = [
				'front_name'         => 'required',
				'front_lastname'     => 'required',
				'front_countrycode'  => 'required',
				'front_mobilenumber' => 'required',
				'front_email'        => 'required|email',
				'front_password'     => 'required|min:8'
			];

			$input = $request->only(
				'front_name',
				'front_lastname',
				'front_countrycode',
				'front_mobilenumber',
				'front_email',
				'front_password'
			);

			$validator = Validator::make($input, $rules);
			if ($validator->fails()) {
				return JsonReturn::error($validator->messages());
			}
			
			$front_email  = ($request->front_email) ? $request->front_email : '';
			$MobileNumber = ($request->front_mobilenumber) ? $request->front_mobilenumber : '';
			$CountryCode  = ($request->front_countrycode) ? $request->front_countrycode : '';
			$fullMoNumber = $CountryCode.$MobileNumber;
			
			$frontUserCheck = frontUser::select('*')->where(function($query) use ($front_email,$fullMoNumber){ $query->where('fuser.email',$front_email)->orWhere('fuser.mobile',$fullMoNumber); })->get()->first();
			
			if(!empty($frontUserCheck)){
				$data["status"]  = false;
				$data["message"] = "User with provided email address or phone number is already exist!";	
				return JsonReturn::success($data);
			}
			
			$frontUser = frontUser::create([
				'name'       => ($request->front_name) ? $request->front_name : '',
				'last_name'  => ($request->front_lastname) ? $request->front_lastname : '',
				'email'      => ($request->front_email) ? $request->front_email : '',
				'mobile'     => $fullMoNumber,
				'password'   => ($request->front_password) ? Hash::make($request->front_password) : '',
				'created_at' => date("Y-m-d H:i:s")
			]);
			$UserId = $frontUser->id;
			
			if($frontUser){
				
				 if (Auth::guard('fuser')->attempt(['email' => $request->front_email, 'password' => $request->front_password])) {
					$user = Auth::guard('fuser')->user();
				 }
				
				$data["status"] = true;
				$data["message"] = "Congrtulations your account has been created successfully.";	
				$data["USERID"] = $UserId;	
			} else {
				$data["status"] = false;
				$data["message"] = "Something went wrong!";		
			}
            return JsonReturn::success($data);
        }
	}
	
	public function bookingFlowLogin(Request $request){
		if ($request->ajax()) {
			$rules = [
				'front_login_email'        => 'required|email',
				'front_login_password'     => 'required'
			];

			$input = $request->only(
				'front_login_email',
				'front_login_password'
			);

			$validator = Validator::make($input, $rules);
			if ($validator->fails()) {
				return JsonReturn::error($validator->messages());
			}
			
			$front_login_email    = ($request->front_login_email) ? $request->front_login_email : '';
			$front_login_password = ($request->front_login_password) ? Hash::make($request->front_login_password) : '';
			
			if (Auth::guard('fuser')->attempt(['email' => $front_login_email, 'password' => $request->front_login_password])) {
				
				$frontUserCheck = frontUser::select('id')->where('email',$front_login_email)->get()->first();
				
				$data["status"] = true;
				$data["message"] = "Login successfully.";	
				$data["USERID"] = $frontUserCheck->id;	
			} else {
				$data["status"] = false;
				$data["message"] = "Provided email or password is wrong!";		
			}
			
            return JsonReturn::success($data);
        }
	}

	public function getBookedSlots(&$timeSlotArr, $curr_date, $staffId)
	{
		// FETCHING APPOINTMENTS OF THE BOOKING DATE
		$bookingDateAppointment = AppointmentServices::join('appointments', 'appointments.id', 'appointment_services.appointment_id')
								->where('appointment_services.appointment_date', $curr_date)
								->where('appointments.is_cancelled', '0')
								->where('appointment_services.staff_user_id', $staffId)
								->select('appointment_services.start_time', 'appointment_services.end_time', 'appointment_services.is_extra_time', 'appointment_services.extra_time_duration')
								->get();

		// LOOPING THROUGH SHIFTS ( MORNING SHIFT, EVENING SHIFT, ...)
		if(!empty($timeSlotArr) && is_array($timeSlotArr)) {
			foreach($timeSlotArr as $key => $shifts) {

				// LOOPING THROUGH SLOTS
				if(!empty($shifts) && is_array($shifts)) {
					foreach($shifts as $subKey => $slots) {

						// DEFAULT - APPOINTMENT IS NOT BOOKED FOR THE SLOT
						$timeSlotArr[ $key ][ $subKey ]['booked'] = FALSE;

						// LOOP THROUGH BOOKING DATE'S APPOINTMENTS
						if($bookingDateAppointment->isNotEmpty()) {
							foreach ($bookingDateAppointment as $taKey => $appointmentRow) {

								// IF THERE IS EXTRA TIME MENTIONED IN APPOINTMENT, THEN ADD THAT TIME TO END TIME OF APPOINTMENT
								if($appointmentRow->is_extra_time) {
									$appointmentRow->end_time = date('H:i:s', strtotime($appointmentRow->end_time) + ($appointmentRow->is_extra_time * 60));
								}

								if( strtotime($slots['start']) > strtotime($appointmentRow->start_time) && strtotime($slots['start']) < strtotime($appointmentRow->end_time) ) {
									// CHECK IF SLOT START TIME IS IN BETWEEN APPOINTMENT START AND END TIME

									$timeSlotArr[ $key ][ $subKey ]['booked'] = true;
								} elseif( strtotime($slots['end']) > strtotime($appointmentRow->start_time) && strtotime($slots['end']) < strtotime($appointmentRow->end_time) ) {
									// CHECK IF SLOT END TIME IS IN BETWEEN APPOINTMENT START AND END TIME

									$timeSlotArr[ $key ][ $subKey ]['booked'] = true;
								}

							}
						}
					}
				}
			}
		}
	}

	public function removeBlockedTime(&$timeSlotArr, $curr_date, $staffId)
	{
		$staffBlockedTime = StaffBlockedTime::where('date', $curr_date)
						->where('staff_user_id', $staffId)
						->where('allow_online_booking', 0)
						->get();

		// LOOPING THROUGH SHIFTS ( MORNING SHIFT, EVENING SHIFT, ...)
		if(!empty($timeSlotArr) && is_array($timeSlotArr)) {
			foreach($timeSlotArr as $key => $shifts) {

				// LOOPING THROUGH SLOTS
				if(!empty($shifts) && is_array($shifts)) {
					foreach($shifts as $subKey => $slots) {

						// LOOP THROUGH BLOCKED TIME
						if($staffBlockedTime->isNotEmpty()) {
							foreach ($staffBlockedTime as $taKey => $blockedTimeRow) {

								if( strtotime($slots['start']) > strtotime($blockedTimeRow->start_time) && strtotime($slots['start']) < strtotime($blockedTimeRow->end_time) ) {
									// CHECK IF SLOT START TIME IS IN BETWEEN BLOCKED TIME START AND END TIME

									unset($timeSlotArr[ $key ][ $subKey ]);
								} elseif( strtotime($slots['end']) > strtotime($blockedTimeRow->start_time) && strtotime($slots['end']) < strtotime($blockedTimeRow->end_time) ) {
									// CHECK IF SLOT END TIME IS IN BETWEEN BLOCKED TIME START AND END TIME

									unset($timeSlotArr[ $key ][ $subKey ]);
								}
							}
						}
					}
				}
			}
		}
	}
	
	function unique_code($digits)
    {
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
    }
	
}
