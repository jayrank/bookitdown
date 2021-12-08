<?php
namespace App\Http\Controllers;

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
use App\Models\EmailLog;
use App\Models\TippingNotification;
use App\Models\InvoiceTemplate;
use DataTables;
use Session;
use Crypt;
use App\Mail\thankyouAppointment;
use App\Mail\forTippingNotification;
use App\Models\thankyouNotification;
use Mail;
use App\Repositories\NotificationRepositorie;
use DB;



class CheckoutController extends Controller
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
		$currentRoute = Route::currentRouteName();
		$this->middleware('auth');

		$this->notificationRepositorie = $notificationRepositorie;
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
			
			$encryptAppId = 0;
			$decryptAppId = 0;
			
			if($appointmentId != ''){
				$encryptAppId = $appointmentId;
				$decryptAppId = Crypt::decryptString($appointmentId);	
			}
			
			$serviceCategory = ServiceCategory::select('id','category_title')->where('user_id', $AdminId)->orderBy('order_id', 'asc')->get();
			$productCategory = Inventory_category::select('id','category_name')->where('user_id', $AdminId)->orderBy('id', 'desc')->get();
			
			$paymentTypes = paymentType::select('id','payment_type')->where('user_id', $AdminId)->orderBy('order_id', 'asc')->get();
			$clientLists = Clients::select('id','firstname','lastname','email','mo_country_code','mobileno')->where('is_deleted',0)->where('user_id', $AdminId)->orderBy('id', 'desc')->limit(25)->get();
			
			$userData = User::select('users.id','users.first_name','users.last_name')->where('id', $AdminId)->first();
			$taxFormulaData = taxFormula::select('tax_formula.tax_formula')->where('user_id', $AdminId)->first();
			
			$allServiceListing = ServiceCategory::where('is_deleted',0)->where('user_id', Auth::id())->with('service.servicePrice')->get();
			
			if(!empty($taxFormulaData)) {
				$taxFormula = $taxFormulaData->tax_formula;	
			} else {
				$taxFormula = 0;
			}			
			
			$staffLists = Staff::select('users.id','users.first_name','users.last_name')->leftJoin('users', 'users.id', '=', 'staff.staff_user_id')->where('staff.user_id', $AdminId)->orderBy('staff.order_id', 'asc')->get()->toArray();
			
			$serTaxes = LocTax::select('loc_taxes.id','loc_taxes.service_default_tax','taxes.tax_name','taxes.tax_rates','taxes.is_group')->leftJoin('taxes', 'taxes.id', '=', 'loc_taxes.service_default_tax')->where('loc_taxes.service_default_tax', '>' , 0)->where('loc_taxes.user_id', $AdminId)->where('loc_taxes.loc_id', $locationId)->first();
			
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
			
			$prodTaxes = LocTax::select('loc_taxes.id','loc_taxes.poducts_default_tax','taxes.tax_name','taxes.tax_rates','taxes.is_group')->leftJoin('taxes', 'taxes.id', '=', 'loc_taxes.poducts_default_tax')->where('loc_taxes.poducts_default_tax', '>' , 0)->where('loc_taxes.user_id', $AdminId)->where('loc_taxes.loc_id', $locationId)->first();
			
			$productTaxes = array();
			if(!empty($prodTaxes)) {
				if($prodTaxes->is_group == 1) {
					
					$taxes = explode(",", str_replace(" ", "", $prodTaxes->tax_rates));
					$prodTaxes = Taxes::select('taxes.id','taxes.tax_name','taxes.tax_rates','taxes.is_group')->whereIn('id', $taxes)->get();
					
					foreach($prodTaxes as $tax) {
						$tmp_arr = array(
							'id' => $tax->id,
							'poducts_default_tax' => $tax->id,
							'tax_name' => $tax->tax_name,
							'tax_rates' => $tax->tax_rates,
							'is_group' => $tax->is_group
						);
						array_push($productTaxes, $tmp_arr);
					}	
				} else {
					$tmp_arr = array(
						'id' => $prodTaxes->id,
						'poducts_default_tax' => $prodTaxes->poducts_default_tax,
						'tax_name' => $prodTaxes->tax_name,
						'tax_rates' => $prodTaxes->tax_rates,
						'is_group' => $prodTaxes->is_group
					);
					array_push($productTaxes, $tmp_arr);
				}		
			}
			
			$tmp_arr = array(
				'id' => $userData->id,
				'first_name' => $userData->first_name,
				'last_name' => $userData->last_name
			);
			array_push($staffLists, $tmp_arr);
			
			$paidPlanLists = PaidPlan::select('id','name','services_ids','sessions','sessions_num','price','valid_for')->where('user_id', $AdminId)->orderBy('id', 'desc')->get();
			
			$voucherLists = Voucher::select('id','name','services_ids','value','retailprice','validfor','enable_sale_limit','numberofsales')->where('user_id', $AdminId)->orderBy('id', 'desc')->get();
			
			$ClientInfo = array();
			
			if($type == "appointment" && $appointmentId != '')
			{			
				$appId = Crypt::decryptString($appointmentId);
				
				// get all clients	
				$Clients = Clients::select('id','firstname','lastname','email','mo_country_code','mobileno')->where('is_deleted',0)->where('user_id', $AdminId)->orderBy('id', 'desc')->limit(25)->get();	
				
				$Appointment = Appointments::select('*')->where('id',$appId)->where('user_id', $AdminId)->orderBy('id', 'desc')->get()->first()->toArray();
				
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
						
						$Services = ServicesPrice::select('services_price.id as service_price_id','services_price.service_id','services_price.duration','services_price.price_type','services_price.price','services_price.special_price','services_price.pricing_name','services_price.lowest_price','services_price.is_staff_price','services_price.staff_prices','services.id as service_id','services.service_name','services.extra_time','services.is_extra_time','services.extra_time_duration')->join('services','services.id','=','services_price.service_id')->where('services_price.id',$service_price_id)->where('services.user_id',$AdminId)->orderBy('services_price.id', 'ASC')->get()->first()->toArray();
						
						$staffPrices = json_decode($Services['staff_prices'], true);
						
						$staffPriceArr = array();
						$staffData = StaffLocations::select('staff_locations.id','staff_locations.staff_id','staff_locations.staff_user_id','users.first_name','users.last_name')->leftJoin('users', 'staff_locations.staff_user_id', '=', 'users.id')->where('staff_locations.location_id', $locationId)->get();
						
						$discountData = Discount::select('discount.id','discount.name','discount.value','discount.prType')->where('discount.user_id', $AdminId)->where('discount.is_service', 1)->get();
						
						$service_price = $service_special_price = 0;
						$service_duration = '';
						
						foreach($staffData as $key => $staff)
						{
							if($Services['is_staff_price'] == 1)
							{	
								$index = "";
								
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
									$staffData[$key]->staff_duration = $this->convertDurationText($Services['duration']);
									$staffData[$key]->staff_price = $Services['price'];
									$staffData[$key]->staff_special_price = $Services['special_price'];
								}	
							} else {
								$staffData[$key]->staff_duration = $this->convertDurationText($Services['duration']);
								$staffData[$key]->staff_price = $Services['price'];
								$staffData[$key]->staff_special_price = $Services['special_price'];
							}		
							
							if($AppointmentServiceData['staff_user_id'] == $staff->staff_user_id) {
								$service_duration = $this->convertDurationText($Services['duration']);
								$service_price = $Services['price'];
								$service_special_price = $Services['special_price'];
							}	
						}	
						
						$getUser = User::getUserbyID($AppointmentServiceData['staff_user_id']);
						$uniqid = $this->quickRandom();
						
						$tempClientServices['uniqid'] = $uniqid;
						$tempClientServices['appointment_service_id'] = $AppointmentServiceData['id'];
						$tempClientServices['service_id'] 			  = $Services['service_id'];
						$tempClientServices['duration']               = ($service_duration) ? $service_duration : $this->hoursandmins($AppointmentServiceData['duration']);
						$tempClientServices['service_price']          = ($service_price) ? $service_price : $AppointmentServiceData['special_price'];
						$tempClientServices['special_price']          = ($service_special_price) ? $service_special_price : $AppointmentServiceData['special_price'];
						$tempClientServices['staff_user_id']          = $AppointmentServiceData['staff_user_id'];
						$tempClientServices['staff_name']             = $getUser->first_name.' '.$getUser->last_name;
						$tempClientServices['service_name']           = ($Services['service_name']) ? $Services['service_name'] : '';
						$tempClientServices['service_pricing_name']   = ($Services['pricing_name']) ? $Services['pricing_name'] : '';
						$tempClientServices['staff_data']   		  = json_decode($staffData, true);
						$tempClientServices['discount_data']   		  = json_decode($discountData, true);
						array_push($ClientServices,$tempClientServices);
					}
				}
				
				// Get all staff
				$Staff = Staff::select('staff.staff_user_id','users.first_name','users.last_name')->where(['user_id'=>$AdminId])->join('users','users.id','=','staff.staff_user_id')->orderBy('staff.id', 'ASC')->get()->toArray();
				
				// get previous history
				$PreviousAppointment = array();
				$PreviousAppointmentServices = array();
				$PreviousServices = array();
				$TotalSpend = 0;
				
				$soldProduct     = array();
				$ClientProducts  = '';
				$clientInvoices  = array();
				$ClientInovices  = '';
				
				if($Appointment['client_id'] != 0)
				{
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

				return view('appointments.checkout',compact('Appointment','ClientServices','Staff','ClientInfo','Clients','serviceCategory','locationId','paymentTypes','clientLists','staffLists','serviceTaxes','productTaxes','taxFormula','productCategory','paidPlanLists','voucherLists','allServiceListing','encryptAppId','decryptAppId','PreviousAppointment','PreviousServices','TotalSpend','appointmentId','soldProduct','ClientProducts','clientInvoices','ClientInovices'));
			}
				
			if($type == "voucher" && $appointmentId != '')
			{			
				$appId = Crypt::decryptString($appointmentId);
				
				$staff_data = StaffLocations::select('staff_locations.id','staff_locations.staff_id','users.first_name','users.last_name')->leftJoin('users', 'staff_locations.staff_user_id', '=', 'users.id')->where('staff_locations.location_id', $locationId)->get();
				$voucherData = Voucher::select('id','name','value','retailprice','validfor')->where('id', $appId)->where('user_id', $AdminId)->first();
				
				$voucherData->uniqid = $uniqid = $this->quickRandom();
				
				return view('appointments.checkout',compact('voucherData','staff_data','serviceCategory','locationId','paymentTypes','clientLists','staffLists','serviceTaxes','productTaxes','taxFormula','productCategory','paidPlanLists','voucherLists','allServiceListing','encryptAppId','decryptAppId'));
			}
						
			return view('appointments.checkout', compact('serviceCategory', 'locationId', 'paymentTypes', 'clientLists', 'staffLists', 'serviceTaxes', 'productTaxes', 'taxFormula', 'productCategory', 'paidPlanLists', 'voucherLists', 'allServiceListing','encryptAppId','decryptAppId'));
		} else {
			return redirect()->route('calander');
		}		
	}
	
	function refundInvoice($locationId = null,$type = null,$invoiceId = null)
	{		
		if($locationId > 0) {
			
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
			
			$invoiceId = Crypt::decryptString($invoiceId);			
			
			$invoiceData = Invoice::select('*')->where('id',$invoiceId)->where('user_id', $AdminId)->orderBy('id', 'desc')->get()->first();
			
			$clientId = $invoiceData->client_id;
			
			$ClientInfo = Clients::getClientbyID($clientId);
			
			$staffLists = Staff::select('users.id','users.first_name','users.last_name')->leftJoin('users', 'users.id', '=', 'staff.staff_user_id')->where('staff.user_id', $AdminId)->orderBy('staff.order_id', 'asc')->get()->toArray();
			$userData = User::select('users.id','users.first_name','users.last_name')->where('id', $AdminId)->first();
			
			$tmp_arr = array(
				'id' => $userData->id,
				'first_name' => $userData->first_name,
				'last_name' => $userData->last_name
			);
			array_push($staffLists, $tmp_arr);
			
			$serTaxes = LocTax::select('loc_taxes.id','loc_taxes.service_default_tax','taxes.tax_name','taxes.tax_rates','taxes.is_group')->leftJoin('taxes', 'taxes.id', '=', 'loc_taxes.service_default_tax')->where('loc_taxes.service_default_tax', '>' , 0)->where('loc_taxes.user_id', $AdminId)->where('loc_taxes.loc_id', $locationId)->first();
			
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
			
			$prodTaxes = LocTax::select('loc_taxes.id','loc_taxes.poducts_default_tax','taxes.tax_name','taxes.tax_rates','taxes.is_group')->leftJoin('taxes', 'taxes.id', '=', 'loc_taxes.poducts_default_tax')->where('loc_taxes.poducts_default_tax', '>' , 0)->where('loc_taxes.user_id', $AdminId)->where('loc_taxes.loc_id', $locationId)->first();
			
			$productTaxes = array();
			if(!empty($prodTaxes)) {
				if($prodTaxes->is_group == 1) {
					
					$taxes = explode(",", str_replace(" ", "", $prodTaxes->tax_rates));
					$prodTaxes = Taxes::select('taxes.id','taxes.tax_name','taxes.tax_rates','taxes.is_group')->whereIn('id', $taxes)->get();
					
					foreach($prodTaxes as $tax) {
						$tmp_arr = array(
							'id' => $tax->id,
							'poducts_default_tax' => $tax->id,
							'tax_name' => $tax->tax_name,
							'tax_rates' => $tax->tax_rates,
							'is_group' => $tax->is_group
						);
						array_push($productTaxes, $tmp_arr);
					}	
				} else {
					$tmp_arr = array(
						'id' => $prodTaxes->id,
						'poducts_default_tax' => $prodTaxes->poducts_default_tax,
						'tax_name' => $prodTaxes->tax_name,
						'tax_rates' => $prodTaxes->tax_rates,
						'is_group' => $prodTaxes->is_group
					);
					array_push($productTaxes, $tmp_arr);
				}		
			}
			
			if(!empty($invoiceData)) {
				$itemArr = array();
				$inoviceItemData = InvoiceItems::select('*')->where('invoice_id',$invoiceId)->get();
				
				foreach($inoviceItemData as $itemData) 
				{
					$uniqid = $this->quickRandom();
					// $getUser = User::getUserbyID($itemData['staff_id']);
					$getUser = Staff::getStaffDetailByStaffID($itemData['staff_id']);
					$item_name = "";
					
					if($itemData['item_type'] == "services") {
						$serviceData = Services::select('id','service_name')->where('id',$itemData['item_id'])->first();
						$item_name = $serviceData->service_name;
					}	
	
					if($itemData['item_type'] == "product") {
						$productData = InventoryProducts::select('id','product_name')->where('id',$itemData['item_id'])->first();
						$item_name = $productData->product_name;
					}	
	
					if($itemData['item_type'] == "paidplan") {
						$planData = PaidPlan::select('id','name')->where('id',$itemData['item_id'])->first();
						$item_name = $planData->name;
					}	
	
					if($itemData['item_type'] == "voucher") {
						$voucherData = Voucher::select('id','name')->where('id',$itemData['item_id'])->first();
						$item_name = $voucherData->name;
					}	
	
					$tempArr['uniqid'] 			= $uniqid;
					$tempArr['item_id'] 		= $itemData['item_id'];
					$tempArr['item_type'] 		= $itemData['item_type'];
					$tempArr['quantity'] 		= $itemData['quantity'];
					$tempArr['duration']        = "";
					$tempArr['item_og_price']   = $itemData['item_og_price'];
					$tempArr['item_price']      = $itemData['item_price'];
					$tempArr['item_tax_rate']      = $itemData['item_tax_rate'];
					$tempArr['item_tax_amount']      = $itemData['item_tax_amount'];
					$tempArr['staff_name']      = !empty($getUser) ? $getUser->first_name.' '.$getUser->last_name : '';
					$tempArr['item_name']       = $item_name;
					array_push($itemArr, $tempArr);
				}	
			}	
			
			$taxFormulaData = taxFormula::select('tax_formula.tax_formula')->where('user_id', $AdminId)->first();
			if(!empty($taxFormulaData)) {
				$taxFormula = $taxFormulaData->tax_formula;	
			} else {
				$taxFormula = 0;
			}
			$paymentTypes = paymentType::select('id','payment_type')->where('user_id', $AdminId)->orderBy('order_id', 'asc')->get();
			$staffTip = StaffTip::select('id','staff_id','tip_amount')->where('inovice_id', $invoiceId)->sum('tip_amount');

			$InvoiceTaxes = InvoiceTaxes::select('invoice_taxes.tax_rate','invoice_taxes.tax_amount','taxes.tax_name')->leftJoin('taxes', 'taxes.id', '=', 'invoice_taxes.tax_id')->where('invoice_taxes.invoice_id', $invoiceId)->get()->toArray();

			$Invoice = Invoice::select('*')->where('id',$invoiceId)->where('user_id', $AdminId)->orderBy('id', 'desc')->get()->first()->toArray();
			
			return view('appointments.refundInvoice', compact('locationId','clientId','staffLists','itemArr','serviceTaxes','productTaxes','taxFormula','paymentTypes','invoiceId','staffTip','ClientInfo','InvoiceTaxes','Invoice'));
		}
	}
	
	public function payUnpaidInvoice(Request $request){
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
			
			$InvoiceID             = ($request->invoiceId) ? $request->invoiceId : '';
			
			$isUnpaid              = ($request->isUnpaid) ? $request->isUnpaid : 0;
			$paymentId             = ($request->paymentId) ? $request->paymentId : 0;
			$paymentType           = ($request->paymentType) ? $request->paymentType : '';
			$paymentReceivedBy     = ($request->paymenyReceivedyByField) ? $request->paymenyReceivedyByField : 0;
			$paymenyReceivedyNotes = ($request->paymenyReceivedyNotes) ? $request->paymenyReceivedyNotes : 0;
			
			$is_voucher_apply = 0;
			$apply_voucher_code = $request->voucher_code;
			
			if(!empty($apply_voucher_code)) {
				$is_voucher_apply = 1;
			}	
			

			if($InvoiceID != ''){
				$InvoiceDetail = Invoice::find($InvoiceID);
				
				if(isset($InvoiceDetail) && !empty($InvoiceDetail)){
					
					if($isUnpaid == 1){
						$InvoiceDetail->invoice_status = 0;	
						$InvoiceDetail->updated_at     = date("Y-m-d H:i:s");	
						$InvoiceDetail->updated_by     = $UserId;	
						if($InvoiceDetail->save()){
							$data["status"] = true;
							$data["message"] = "Invoice has been marked as unpaid succesfully.";	
							Session::flash('message', 'Invoice has been marked as unpaid succesfully.');
							$data["redirect"] = route('viewInvoice',['id' => $InvoiceID]);
						} else {
							$data["status"] = false;
							$data["message"] = "Something went wrong!";	
						}
					} else {
						$InvoiceDetail->invoice_status = 1;	
						$InvoiceDetail->is_voucher_apply = $is_voucher_apply;	
						$InvoiceDetail->payment_id     = $paymentId;	
						$InvoiceDetail->payment_type   = $paymentType;	
						$InvoiceDetail->notes          = $paymenyReceivedyNotes;	
						$InvoiceDetail->created_by     = $paymentReceivedBy;	
						$InvoiceDetail->payment_date   = date("Y-m-d");	
						$InvoiceDetail->updated_at     = date("Y-m-d H:i:s");	
						$InvoiceDetail->updated_by     = $UserId;	
						if($InvoiceDetail->save()){
							
							$invoiceDetails = Invoice::select('*')->where('id', $InvoiceID)->get()->first()->toArray();							
							if(!empty($invoiceDetails))
							{
								if($invoiceDetails['appointment_id'] != 0)
								{
									$AppointmentFind = Appointments::find($invoiceDetails['appointment_id']);   
									if(!empty($AppointmentFind)){
										$AppointmentFind->client_id          = ($invoiceDetails['client_id']) ? $invoiceDetails['client_id'] : 0;
										$AppointmentFind->invoice_id         = ($invoiceDetails['id']) ? $invoiceDetails['id'] : 0;
										$AppointmentFind->appointment_status = 4;
										$AppointmentFind->updated_at         = date("Y-m-d H:i:s");
										$AppointmentFind->save();
									}		
								}
							}


							$apply_payment_amt = $request->payment_amt;
							
							if(!empty($apply_voucher_code)) {
								foreach($apply_voucher_code as $vkey => $val) 
								{
									$total_redeem = 0;
									$getVoucherId = SoldVoucher::select('id', 'voucher_id', 'redeemed')->where('voucher_code', $val)->where('user_id', $AdminId)->first();
									$total_redeem = $getVoucherId->redeemed + $apply_payment_amt[$vkey];
									
									$getVoucherId->redeemed = $total_redeem;
									$getVoucherId->save();
									
									$insInvVoucher = InvoiceVoucher::create([
										'invoice_id'	=> $InvoiceID,
										'location_id'   => $InvoiceDetail->location_id,
										'voucher_id'	=> $getVoucherId->voucher_id,
										'voucher_code'  => $val,
										'voucher_amount'=> $apply_payment_amt[$vkey],
										'created_at'    => date("Y-m-d H:i:s"),
										'updated_at'    => date("Y-m-d H:i:s")
									]);	
								}	
							}
							
							
							$data["status"] = true;
							$data["message"] = "Invoice has been marked as completed succesfully.";	
							Session::flash('message', 'Invoice has been marked as completed succesfully.');
							$data["redirect"] = route('viewInvoice',['id' => $InvoiceID]);
						} else {
							$data["status"] = false;
							$data["message"] = "Something went wrong!";	
						}
					}
				} else {
					$data["status"] = false;
					$data["message"] = "Something went wrong!";	
				}
			} else {
				$data["status"] = false;
				$data["message"] = "Something went wrong!";	
			}
			
			return JsonReturn::success($data);
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
			$serviceLists = Services::select('id','service_name')->where('service_category', $catid)->where('is_deleted', 0)->where('user_id', $AdminId)->orderBy('order_id', 'asc')->get();
			
			$servicehtml = "";
			foreach($serviceLists as $key => $service)
			{
				$pricearr = array();
				$servicePrices = ServicesPrice::select('id', 'duration', 'price_type', 'lowest_price', 'price', 'special_price')->where('service_id', $service->id)->where('user_id', $AdminId)->orderBy('id', 'asc')->get();
				
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
						
					if($servprice->price_type == "free") {
						$servicehtml .= '<li class="d-flex justify-content-between align-items-center text-primary list-group-item list-group-item-action additemcheckout" data-type="service" data-id="'.$servprice->id.'" >
								<span>
									<p class="m-0">'.$service->service_name.'</p>
									<p class="m-0">'.$pr." ".$duration.'</p>
								</span>
								<p class="font-weight-bolder">Free</p>
							</li>';
					} else {		
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
			}	
            $data["data"] = $servicehtml;
            return JsonReturn::success($data);
		}		
	}	
	
	public function getServiceByProduct(Request $request)
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
			$productLists = InventoryProducts::select('id','product_name','initial_stock','retail_price','special_rate')->where('category_id', $catid)->where('is_deleted', 0)->where('user_id', $AdminId)->orderBy('id', 'asc')->get();
			
			$producthtml = "";
			if(count($productLists) > 0) {
				foreach($productLists as $key => $product)
				{				
					$producthtml .= '<li class="d-flex justify-content-between align-items-center text-primary list-group-item list-group-item-action additemcheckout" data-type="product" data-id="'.$product->id.'" >
						<span>
							<p class="m-0">'.$product->product_name.'</p>
							<p class="m-0">'.$product->initial_stock.' in stock</p>
						</span>';
						if($product->special_rate > 0)
						{
							$producthtml .= '<p class="font-weight-bolder specialprice-txt">CA '.$product->retail_price.'</p>';
						}	
						$producthtml .= '<p class="font-weight-bolder">CA '.$product->special_rate.'</p>
					</li>';
				}	
			} else {
				$producthtml = '<li class="d-flex justify-content-between align-items-center text-primary list-group-item list-group-item-action" >No products or services found</li>';
			}		
            $data["data"] = $producthtml;
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

			if($type == "service")
			{
				$serviceData = ServicesPrice::select('services_price.id', 'services_price.service_id', 'services_price.duration', 'services_price.lowest_price', 'services_price.price', 'services_price.special_price','services_price.is_staff_price','services_price.staff_prices','services_price.price_type','services.service_name','services.tax_id')->leftJoin('services', 'services_price.service_id', '=', 'services.id')->where('services_price.id', $id)->first();

				$sHtml = '';
				if($serviceData->tax_id === 0){

				}elseif($serviceData->tax_id == null){
					$servTaxes = LocTax::select('loc_taxes.service_default_tax','tax_formula.tax_formula','taxes.id','taxes.tax_name','taxes.tax_rates','taxes.is_group')->leftJoin('taxes','taxes.id','=','loc_taxes.poducts_default_tax')->leftJoin('tax_formula','tax_formula.user_id','=','loc_taxes.user_id')->where('loc_taxes.user_id',$AdminId)->where('loc_taxes.loc_id',$locationId)->first()->toArray();

					$sHtml .= "<input type='hidden' name='servTaxFormula' class='servTaxFormula' value='".((!empty($servTaxes)) ? $servTaxes['tax_formula'] : '') ."'>";
					if($servTaxes['is_group'] == 1 ){
						$arrayTaxes = explode(',', $servTaxes['tax_rates']);
						foreach($arrayTaxes as $taxId){
							$t = Taxes::select('*')->where('taxes.id',$taxId)->first()->toArray();
							$sHtml .= "<div class='taxes-list'><input type='hidden' class='serviceTax' value='".((!empty($t)) ? $t['tax_rates'] : '') ."' data-id='".((!empty($t)) ? $t['id'] : '' )."' data-name='".((!empty($t)) ? $t['tax_name'] : '' )."'></div>";
						}
					}else{
						$sHtml .= "<div class='taxes-list'><input type='hidden' class='serviceTax' value='".((!empty($servTaxes)) ? $servTaxes['tax_rates'] : '') ."' data-id='".((!empty($servTaxes)) ? $servTaxes['id'] : '' )."' data-name='".((!empty($servTaxes)) ? $servTaxes['tax_name'] : '' )."'></div>";
					}
				}else{
					$servTaxes = Taxes::select('taxes.*','tax_formula.tax_formula')->leftJoin('tax_formula','tax_formula.user_id','=','taxes.user_id')->where('taxes.id',$serviceData->tax_id)->first()->toArray();

					$sHtml .= "<input type='hidden' name='servTaxFormula' class='servTaxFormula' value='".((!empty($servTaxes)) ? $servTaxes['tax_formula'] : '') ."'>";
					if($servTaxes['is_group'] == 1 ){
						$arrayTaxes = explode(',', $servTaxes['tax_rates']);
						foreach($arrayTaxes as $taxId){
							$t = Taxes::select('*')->where('taxes.id',$taxId)->first()->toArray();
							$sHtml .= "<div class='taxes-list'><input type='hidden' class='serviceTax' value='".((!empty($t)) ? $t['tax_rates'] : '') ."' data-id='".((!empty($t)) ? $t['id'] : '' )."' data-name='".((!empty($t)) ? $t['tax_name'] : '' )."'></div>";
						}
					}else{
						$sHtml .= "<div class='taxes-list'><input type='hidden' class='serviceTax' value='".((!empty($servTaxes)) ? $servTaxes['tax_rates'] : '') ."' data-id='".((!empty($servTaxes)) ? $servTaxes['id'] : '' )."' data-name='".((!empty($servTaxes)) ? $servTaxes['tax_name'] : '' )."'></div>";
					}
				}
				
				$staffPrices = json_decode($serviceData->staff_prices, true);
				
				$staffPriceArr = array();
				$staffData = StaffLocations::select('staff_locations.id','staff_locations.staff_id','users.first_name','users.last_name')->leftJoin('users', 'staff_locations.staff_user_id', '=', 'users.id')->where('staff_locations.location_id', $locationId)->get();
				
				$discountData = Discount::select('discount.id','discount.name','discount.value','discount.prType')->where('discount.user_id', $UserId)->where('discount.is_service', 1)->get();
				
				foreach($staffData as $key => $staff)
				{
					if($serviceData->is_staff_price == 1)
					{	
						$index = "";
						
						$search = ['staff_id' => $staff->staff_id];
						$keys = array_keys(array_filter($staffPrices,function ($v) use ($search) { return $v['staff_id'] == $search['staff_id']; } ) );
						if(isset($keys[0])){
							$index = $keys[0];
							$staff_duration = $staffPrices[$index]['staff_duration'];
							
							if($staffPrices[$index]['staff_price_type'] == "free") {
								$staff_price = 0;
								$staff_special_price = 0;
							} else {
								$staff_price = ($staffPrices[$index]['staff_price']) ? $staffPrices[$index]['staff_price'] : 0;
								$staff_special_price = ($staffPrices[$index]['staff_special_price']) ? $staffPrices[$index]['staff_special_price'] : 0;
							}
							
							$staffData[$key]->staff_duration = $this->convertDurationText($staff_duration);
							$staffData[$key]->staff_price = $staff_price;
							$staffData[$key]->staff_special_price = $staff_special_price;
						} else {
							$staffData[$key]->staff_duration = $this->convertDurationText($serviceData->duration);
							
							// if($staffPrices[$key]['staff_price_type'] == "free") {
							if($serviceData->price_type == "free") {
								$staffData[$key]->staff_price = 0;
								$staffData[$key]->staff_special_price = 0;
							} else {
								$staffData[$key]->staff_price = ($serviceData->price) ? $serviceData->price : 0;
								$staffData[$key]->staff_special_price = ($serviceData->special_price) ? $serviceData->special_price : 0;
							}	
						}	
					} else {
						$staffData[$key]->staff_duration = $this->convertDurationText($serviceData->duration);
						if($serviceData->price_type == "free") {
							$staffData[$key]->staff_price = 0;
							$staffData[$key]->staff_special_price = 0;
						} else {
							$staffData[$key]->staff_price = ($serviceData->price) ? $serviceData->price : 0;
							$staffData[$key]->staff_special_price = ($serviceData->special_price) ? $serviceData->special_price : 0;
						}	
					}		
				}	
				
				$serviceduration = $staffData[0]->staff_duration." with ".$staffData[0]->first_name." ".$staffData[0]->last_name;
				
				if($serviceData->price_type == "free") {
					$staffSpecialPrice = 0;
					$staffPrice = 0;
				} else {	
					$staffSpecialPrice = ($staffData[0]->staff_special_price) ? $staffData[0]->staff_special_price : 0;
					$staffPrice = ($staffData[0]->staff_price) ? $staffData[0]->staff_price : 0;
				}
				
				$uniqid = $this->quickRandom();
				
				$servicehtml = '<div class="card serviceItm cardId'.$uniqid.'" data-id="'.$uniqid.'">
							<div class="card-body border-left border-primary border-3">
								<div class="row flex-wrap justify-content-between servicesHiddenValues">
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
									<i class="fa fa-times cursor-pointer text-danger fa-1x mt-2 ml-3 removeItem" data-uid="'.$uniqid.'"></i>
								</div>';		
								$servicehtml .= '
									<input type="hidden" name="item_id[]" class="itm_id" value="'.$serviceData->service_id.'">
									<input type="hidden" name="item_type[]" class="itemtype'.$uniqid.'" value="services">
									<input type="hidden" name="item_og_price[]" class="itemogprice'.$uniqid.'" value="'.$staffPrice.'">
									<input type="hidden" name="item_price[]" class="itmpr-hd itemprice'.$uniqid.'" data-id="'.$uniqid.'" value="'.$itempr.'">
									<input type="hidden" name="appointment_services_id[]" value="0">
									<input type="hidden" name="item_discount_price[]" class="itemdiscprice'.$uniqid.'" value="0">
									<input type="hidden" name="item_discount_text[]" class="itemdisctxt'.$uniqid.'" value="">
								</div> 
								<div class="row px-8">
									<div class="col-lg-2 col-sm-6">
										<div class="form-group">
											<label class="font-weight-bolder">Quantity</label>
											<input class="form-control qtinpt'.$uniqid.' allow_only_numbers" readonly value="1" name="quantity[]" type="text">
										</div>
									</div>
									<div class="col-lg-3 col-sm-6" id="pricing-type">
										<div class="form-group">
											<label class="font-weight-bolder">Price</label>
											<div class="input-group">
												<div class="input-group-prepend">
													<span class="input-group-text">CA $</span>
												</div>';
												$servicehtml .= '<input type="text" '.$txtreadonly.' class="form-control itmpr_inpt itmpr-inpt'.$uniqid.'" value="'.$itempr.'" placeholder="0.00" data-uid="'.$uniqid.'">';
											$servicehtml .= '</div>
										</div>
									</div>
									<div class="col-lg-3 col-sm-6">
										<div class="form-group">
											<label class="font-weight-bolder">Staff</label>
											<select name="staff_id[]" class="form-control staff-list stflist'.$uniqid.'" data-uid="'.$uniqid.'" >';
												$selected = "";	
												foreach($staffData as $key => $staff) {
													if($key == 0) { $selected = "selected"; }
													$servicehtml .= '<option '.$selected.' value="'.$staff->staff_id.'" data-pr="'.$staff->staff_price.'" data-spr="'.$staff->staff_special_price.'" data-dur="'.$staff->staff_duration.'">'.$staff->first_name.' '.$staff->last_name.'</option>';
												}	 
											$servicehtml .= '</select>
										</div>
									</div>
									<div class="col-lg-4 col-sm-6">
										<div class="form-group">
											<label class="font-weight-bolder">Discount</label>
											<select class="form-control item-discount disclist'.$uniqid.'" name="discount_id[]" data-uid="'.$uniqid.'">
												<option value="">No Discount</option>';
												$selected1 = "";	
												foreach($discountData as $key1 => $val) {
													if($val->prType == 0) {
														$servicehtml .= '<option value="'.$val->id.'" data-type="'.$val->prType.'" data-amt="'.$val->value.'" >'.$val->name.' '.$val->value.'% off</option>';
													} else {
														$servicehtml .= '<option value="'.$val->id.'" data-type="'.$val->prType.'" data-amt="'.$val->value.'">'.$val->name.' CA $'.$val->value.' off</option>';
													}
												}	 
											$servicehtml .= '</select>
										</div>
									</div>
								</div>
							</div>';
							$servicehtml .= $sHtml;
							$servicehtml .= '</div>';
						
				$data["data"] = $servicehtml;
				return JsonReturn::success($data);		
			}	
			else if($type == "product")
			{
				$productData = InventoryProducts::select('id','product_name','initial_stock','retail_price','special_rate', 'tax_id')->where('id', $id)->where('user_id', $AdminId)->first();

				$pHtml = '';

				// $productTaxes = array();
				if($productData->tax_id === 0){
					// $prodTaxes = array('tax_rates'=>'0','tax_name'=>'No Tax','is_group'=>'0','id'=>'','tax_formula'=>'0');
					// $productTaxes = $prodTaxes;
				}elseif($productData->tax_id == null){
					$prodTaxes = LocTax::select('loc_taxes.poducts_default_tax','tax_formula.tax_formula','taxes.id','taxes.tax_name','taxes.tax_rates','taxes.is_group')->leftJoin('taxes','taxes.id','=','loc_taxes.poducts_default_tax')->leftJoin('tax_formula','tax_formula.user_id','=','loc_taxes.user_id')->where('loc_taxes.user_id',$AdminId)->where('loc_taxes.loc_id',$locationId)->first()->toArray();

					// $pHtml .= "<div class='taxes-list'>";
					$pHtml .= "<input type='hidden' name='prodTaxFormula' class='prodTaxFormula' value='".((!empty($prodTaxes)) ? $prodTaxes['tax_formula'] : '') ."'>";
					if($prodTaxes['is_group'] == 1 ){
						$arrayTaxes = explode(',', $prodTaxes['tax_rates']);
						foreach($arrayTaxes as $taxId){
							$t = Taxes::select('*')->where('taxes.id',$taxId)->first()->toArray();
							$pHtml .= "<div class='taxes-list'><input type='hidden' class='productTax' value='".((!empty($t)) ? $t['tax_rates'] : '') ."' data-id='".((!empty($t)) ? $t['id'] : '' )."' data-name='".((!empty($t)) ? $t['tax_name'] : '' )."'></div>";
						}
					}else{
						$pHtml .= "<div class='taxes-list'><input type='hidden' class='productTax' value='".((!empty($prodTaxes)) ? $prodTaxes['tax_rates'] : '') ."' data-id='".((!empty($prodTaxes)) ? $prodTaxes['id'] : '' )."' data-name='".((!empty($prodTaxes)) ? $prodTaxes['tax_name'] : '' )."'></div>";
					}
					// $pHtml .= "</div>";
				}else{
					$prodTaxes = Taxes::select('taxes.*','tax_formula.tax_formula')->leftJoin('tax_formula','tax_formula.user_id','=','taxes.user_id')->where('taxes.id',$productData->tax_id)->first()->toArray();

					// $pHtml .= "<div class='taxes-list'>";
					$pHtml .= "<input type='hidden' name='prodTaxFormula' class='prodTaxFormula' value='".((!empty($prodTaxes)) ? $prodTaxes['tax_formula'] : '') ."'>";
					if($prodTaxes['is_group'] == 1 ){
						$arrayTaxes = explode(',', $prodTaxes['tax_rates']);
						foreach($arrayTaxes as $taxId){
							$t = Taxes::select('*')->where('taxes.id',$taxId)->first()->toArray();
							$pHtml .= "<div class='taxes-list'><input type='hidden' class='productTax' value='".((!empty($t)) ? $t['tax_rates'] : '') ."' data-id='".((!empty($t)) ? $t['id'] : '' )."' data-name='".((!empty($t)) ? $t['tax_name'] : '' )."'></div>";
						}
					}else{
						$pHtml .= "<div class='taxes-list'><input type='hidden' class='productTax' value='".((!empty($prodTaxes)) ? $prodTaxes['tax_rates'] : '') ."' data-id='".((!empty($prodTaxes)) ? $prodTaxes['id'] : '' )."' data-name='".((!empty($prodTaxes)) ? $prodTaxes['tax_name'] : '' )."'></div>";
					}
					// $pHtml .= "</div>"; 
					// $prodTaxes["tax_rates"] = $t;
					// echo "<pre>"; print_r($prodTaxes);die;

					// $productTaxes = $prodTaxes;
					// dd($prodTaxes);
				}
				$staffPriceArr = array();
				$staffData = StaffLocations::select('staff_locations.id','staff_locations.staff_id','users.first_name','users.last_name')->leftJoin('users', 'staff_locations.staff_user_id', '=', 'users.id')->where('staff_locations.location_id', $locationId)->get();
				
				$discountData = Discount::select('discount.id','discount.name','discount.value','discount.prType')->where('discount.user_id', $AdminId)->where('discount.is_product', 1)->get();
				
				$uniqid = $this->quickRandom();
				
				$specialPrice = $productData->special_rate;
				$price = $productData->retail_price;
				
				$producthtml = '<div class="card productItm cardId'.$uniqid.'" data-id="'.$uniqid.'">
							<div class="card-body border-left border-primary border-3">
								<div class="row flex-wrap justify-content-between productHiddenValues">
									<div class="d-flex">
										<h4 class="m-1 p-4 fonter-weight-bolder">1</h4>
										<div>
											<h3 class="m-0">'.$productData->product_name.'</h3>
											<p class="text-dark-50">'.$productData->initial_stock.' in stock</p>
										</div>
									</div>';
									
								$itempr = ""; $displacls = ""; $txtreadonly = "";
								if($specialPrice > 0) {
									$itempr = $specialPrice;
									$txtreadonly = "readonly";
								} else {
									$displacls = "d-none";
									$itempr = $price;
								}		
									
								$producthtml .= '<div class="d-flex flex-wrap">
									<div>
										<h3 class="m-0 itmpr-txt'.$uniqid.'">CA $<span>'.$itempr.'</span></h3>
										<h5 class="m-0 text-dark-50 itmspr-txt'.$uniqid.' '.$displacls.'"><s>CA $<span>'.$price.'</span></s></h5>
									</div>
									<i class="fa fa-times cursor-pointer text-danger fa-1x mt-2 ml-3 removeItem" data-uid="'.$uniqid.'"></i>
								</div>';		
								$producthtml .= '
									<input type="hidden" name="item_id[]" class="itm_id" value="'.$productData->id.'">
									<input type="hidden" name="item_type[]" class="itemtype'.$uniqid.'" value="product">
									<input type="hidden" name="item_og_price[]" class="itemogprice'.$uniqid.'" value="'.$price.'">
									<input type="hidden" name="item_price[]" class="itmpr-hd itemprice'.$uniqid.'" data-id="'.$uniqid.'" value="'.$itempr.'">
									<input type="hidden" name="appointment_services_id[]" value="0">
									<input type="hidden" name="item_discount_price[]" class="itemdiscprice'.$uniqid.'" value="0">
									<input type="hidden" name="item_discount_text[]" class="itemdisctxt'.$uniqid.'" value="">
								</div> 
								<div class="row px-8">
									<div class="col-lg-2 col-sm-6">
										<div class="form-group">
											<label class="font-weight-bolder">Quantity</label>
											<input class="form-control qty_inpt qtinpt'.$uniqid.' allow_only_numbers" value="1" data-uid="'.$uniqid.'" name="quantity[]" type="text">
										</div>
									</div>
									<div class="col-lg-3 col-sm-6" id="pricing-type">
										<div class="form-group">
											<label class="font-weight-bolder">Price</label>
											<div class="input-group">
												<div class="input-group-prepend">
													<span class="input-group-text">CA $</span>
												</div>';
												$producthtml .= '<input type="text" '.$txtreadonly.' class="form-control itmpr_inpt itmpr-inpt'.$uniqid.'" value="'.$itempr.'" placeholder="0.00" data-uid="'.$uniqid.'">';
											$producthtml .= '</div>
										</div>
									</div>
									<div class="col-lg-3 col-sm-6">
										<div class="form-group">
											<label class="font-weight-bolder">Staff</label>
											<select name="staff_id[]" class="form-control" >';
												$selected = "";	
												foreach($staffData as $key => $staff) {
													if($key == 0) { $selected = "selected"; }
													$producthtml .= '<option '.$selected.' value="'.$staff->staff_id.'">'.$staff->first_name.' '.$staff->last_name.'</option>';
												}	 
											$producthtml .= '</select>
										</div>
									</div>
									<div class="col-lg-4 col-sm-6">
										<div class="form-group">
											<label class="font-weight-bolder">Discount</label>
											<select class="form-control item-discount disclist'.$uniqid.'" name="discount_id[]" data-uid="'.$uniqid.'">
												<option value="">No Discount</option>';
												$selected1 = "";	
												foreach($discountData as $key1 => $val) {
													if($val->prType == 0) {
														$producthtml .= '<option value="'.$val->id.'" data-type="'.$val->prType.'" data-amt="'.$val->value.'" >'.$val->name.' '.$val->value.'% off</option>';
													} else {
														$producthtml .= '<option value="'.$val->id.'" data-type="'.$val->prType.'" data-amt="'.$val->value.'">'.$val->name.' CA $'.$val->value.' off</option>';
													}		
												}	
											$producthtml .= '</select>
										</div>
									</div>
								</div>
							</div>';
							$producthtml .= $pHtml;
							$producthtml .= '</div>';
						
				$data["data"] = $producthtml;
				return JsonReturn::success($data);
			}	
			else if($type == "paidplan")
			{
				$paidPlanData = PaidPlan::select('id','name','services_ids','sessions','sessions_num','price','valid_for','tax')->where('id', $id)->where('user_id', $AdminId)->first();
				
				$staffData = StaffLocations::select('staff_locations.id','staff_locations.staff_id','users.first_name','users.last_name')->leftJoin('users', 'staff_locations.staff_user_id', '=', 'users.id')->where('staff_locations.location_id', $locationId)->get();
				
				$paidPlanTax = array();
				if($paidPlanData->tax > 0) {
					$planTaxes = Taxes::select('taxes.id','taxes.tax_name','taxes.tax_rates','taxes.is_group')->where('id', $paidPlanData->tax)->first();
					
					if($planTaxes->is_group == 1) {
						$taxes = explode(",", str_replace(" ", "", $planTaxes->tax_rates));
						$paidplanTaxes = Taxes::select('taxes.id','taxes.tax_name','taxes.tax_rates','taxes.is_group')->whereIn('id', $taxes)->get();
						
						foreach($paidplanTaxes as $tax) {
							$tmp_arr = array(
								'id' => $tax->id,
								'tax_name' => $tax->tax_name,
								'tax_rates' => $tax->tax_rates
							);
							array_push($paidPlanTax, $tmp_arr);
						}		
					} else {
						$tmp_arr = array(
							'id' => $planTaxes->id,
							'tax_name' => $planTaxes->tax_name,
							'tax_rates' => $planTaxes->tax_rates
						);
						array_push($paidPlanTax, $tmp_arr);
					}		
				}	
				
				$discountData = Discount::select('discount.id','discount.name','discount.value','discount.prType')->where('discount.user_id', $AdminId)->where('discount.is_plan', 1)->get();
				
				$uniqid = $this->quickRandom();
				$itempr = $paidPlanData->price;
				
				$no_of_session = ($paidPlanData->sessions == 0) ? $paidPlanData->sessions_num : "Unlimited";
				$producthtml = '<div class="card paidplanItm cardId'.$uniqid.'" data-id="'.$uniqid.'">
							<div class="card-body border-left border-primary border-3">
								<div class="row flex-wrap justify-content-between">
									<div class="d-flex">
										<h4 class="m-1 p-4 fonter-weight-bolder">1</h4>
										<div>
											<h3 class="m-0">'.$paidPlanData->name.'</h3>
											<p class="text-dark-50">'.count(explode(",",$paidPlanData->services_ids)).' services ('.$no_of_session.' sessions), '.$paidPlanData->valid_for.' plan </p>
										</div>
									</div>';
								
								$producthtml .= '<div class="d-flex flex-wrap">
									<div>
										<h3 class="m-0 itmpr-txt'.$uniqid.'">CA $<span>'.$itempr.'</span></h3>
										<h5 class="m-0 text-dark-50 itmspr-txt'.$uniqid.' d-none"><s>CA $<span>'.$itempr.'</span></s></h5>
									</div>
									<i class="fa fa-times cursor-pointer text-danger fa-1x mt-2 ml-3 removeItem" data-uid="'.$uniqid.'"></i>
								</div>';		
								$producthtml .= '
									<input type="hidden" name="item_id[]" value="'.$paidPlanData->id.'">
									<input type="hidden" name="item_type[]" class="itemtype'.$uniqid.'" value="paidplan">
									<input type="hidden" name="item_og_price[]" class="itemogprice'.$uniqid.'" value="'.$itempr.'">
									<input type="hidden" name="item_price[]" class="itmpr-hd itemprice'.$uniqid.'" data-id="'.$uniqid.'" value="'.$itempr.'">
									<input type="hidden" name="appointment_services_id[]" value="0">
									<input type="hidden" name="item_discount_price[]" class="itemdiscprice'.$uniqid.'" value="0">
									<input type="hidden" name="item_discount_text[]" class="itemdisctxt'.$uniqid.'" value="">
								</div> 
								<div class="row px-8">
									<div class="col-md-1 col-sm-6">
										<div class="form-group">
											<label class="font-weight-bolder">Quantity</label>
											<input class="form-control qty_inpt qtinpt'.$uniqid.' allow_only_numbers" readonly value="1" data-uid="'.$uniqid.'" name="quantity[]" type="text">
										</div>
									</div>
									<div class="col-md-3 col-sm-6" id="pricing-type">
										<div class="form-group">
											<label class="font-weight-bolder">Price</label>
											<div class="input-group">
												<div class="input-group-prepend">
													<span class="input-group-text">CA $</span>
												</div>';
												$producthtml .= '<input type="text" class="form-control itmpr_inpt itmpr-inpt'.$uniqid.'" value="'.$itempr.'" placeholder="0.00" data-uid="'.$uniqid.'">';
											$producthtml .= '</div>
										</div>
									</div>
									<div class="col-md-4 col-sm-6">
										<div class="form-group">
											<label class="font-weight-bolder">Staff</label>
											<select name="staff_id[]" class="form-control" >';
												$selected = "";	
												foreach($staffData as $key => $staff) {
													if($key == 0) { $selected = "selected"; }
													$producthtml .= '<option '.$selected.' value="'.$staff->staff_id.'">'.$staff->first_name.' '.$staff->last_name.'</option>';
												}	 
											$producthtml .= '</select>
										</div>
									</div>
									<div class="col-md-4 col-sm-6">
										<div class="form-group">
											<label class="font-weight-bolder">Discount</label>
											<select class="form-control item-discount disclist'.$uniqid.'" name="discount_id[]" data-uid="'.$uniqid.'">
												<option value="">No Discount</option>';
												$selected1 = "";	
												foreach($discountData as $key1 => $val) {
													if($val->prType == 0) {
														$producthtml .= '<option value="'.$val->id.'" data-type="'.$val->prType.'" data-amt="'.$val->value.'" >'.$val->name.' '.$val->value.'% off</option>';
													} else {
														$producthtml .= '<option value="'.$val->id.'" data-type="'.$val->prType.'" data-amt="'.$val->value.'">'.$val->name.' CA $'.$val->value.' off</option>';
													}		
												}	
											$producthtml .= '</select>
										</div>
									</div>
								</div>
							</div>
						</div>';
						
				$data["data"] = $producthtml;
				$data["planTax"] = json_encode($paidPlanTax);
				return JsonReturn::success($data);
			}
			else if($type == "voucher")
			{
				$voucherData = Voucher::select('id','name','value','retailprice','validfor')->where('id', $id)->where('user_id', $AdminId)->first();
				
				$staffData = StaffLocations::select('staff_locations.id','staff_locations.staff_id','users.first_name','users.last_name')->leftJoin('users', 'staff_locations.staff_user_id', '=', 'users.id')->where('staff_locations.location_id', $locationId)->get();
				
				$uniqid = $this->quickRandom();
				$itempr = $voucherData->retailprice;
				
				$valid_on = date("d M, Y", strtotime($voucherData->validfor));
				
				$voucherhtml = '<div class="card voucherItm cardId'.$uniqid.'" data-id="'.$uniqid.'">
							<div class="card-body border-left border-primary border-3">
								<div class="row flex-wrap justify-content-between">
									<div class="d-flex">
										<h4 class="m-1 p-4 fonter-weight-bolder">1</h4>
										<div>
											<h3 class="m-0">CA $'.$voucherData->value.' - '.$voucherData->name.'</h3>
											<p class="text-dark-50">Expires on '.$valid_on.'</p>
										</div>
									</div>';
								$voucherhtml .= '<div class="d-flex flex-wrap">
									<div>
										<h3 class="m-0 itmpr-txt'.$uniqid.'">CA $<span>'.$itempr.'</span></h3>
										<h5 class="m-0 text-dark-50 itmspr-txt'.$uniqid.' d-none"><s>CA $<span>'.$itempr.'</span></s></h5>
									</div>
									<i class="fa fa-times cursor-pointer text-danger fa-1x mt-2 ml-3 removeItem" data-uid="'.$uniqid.'"></i>
								</div>';		
								$voucherhtml .= '
									<input type="hidden" name="item_id[]" value="'.$voucherData->id.'">
									<input type="hidden" name="item_type[]" class="itemtype'.$uniqid.'" value="voucher">
									<input type="hidden" name="item_og_price[]" class="itemogprice'.$uniqid.'" value="'.$itempr.'">
									<input type="hidden" name="item_price[]" class="itmpr-hd itemprice'.$uniqid.'" data-id="'.$uniqid.'" value="'.$itempr.'">
									<input type="hidden" name="appointment_services_id[]" value="0">
									<input type="hidden" name="item_discount_price[]" class="itemdiscprice'.$uniqid.'" value="0">
									<input type="hidden" name="item_discount_text[]" class="itemdisctxt'.$uniqid.'" value="">
								</div> 
								<div class="row px-8">
									<div class="col-md-2 col-sm-6">
										<div class="form-group">
											<label class="font-weight-bolder">Quantity</label>
											<input class="form-control qty_inpt qtinpt'.$uniqid.' allow_only_numbers" value="1" data-uid="'.$uniqid.'" name="quantity[]" type="text">
										</div>
									</div>
									<div class="col-md-3 col-sm-6" id="pricing-type">
										<div class="form-group">
											<label class="font-weight-bolder">Price</label>
											<div class="input-group">
												<div class="input-group-prepend">
													<span class="input-group-text">CA $</span>
												</div>';
												$voucherhtml .= '<input type="text" class="form-control itmpr_inpt itmpr-inpt'.$uniqid.'" value="'.$itempr.'" placeholder="0.00" data-uid="'.$uniqid.'">';
											$voucherhtml .= '</div>
										</div>
									</div>
									<div class="col-md-3 col-sm-6">
										<div class="form-group">
											<label class="font-weight-bolder">Staff</label>
											<select name="staff_id[]" class="form-control" >';
												$selected = "";	
												foreach($staffData as $key => $staff) {
													if($key == 0) { $selected = "selected"; }
													$voucherhtml .= '<option '.$selected.' value="'.$staff->staff_id.'">'.$staff->first_name.' '.$staff->last_name.'</option>';
												}	 
											$voucherhtml .= '</select>
										</div>
									</div>
									<div class="col-md-4 col-sm-6">
										<div class="form-group">
											<label class="font-weight-bolder">Discount</label>
											<select class="form-control item-discount disclist'.$uniqid.'" name="discount_id[]" readonly data-uid="'.$uniqid.'">
												<option value="">No Discount</option>
											</select>
										</div>
									</div>
								</div>
							</div>
						</div>';
						
				$data["data"] = $voucherhtml;
				return JsonReturn::success($data);
			}	
		}	
	}

	public function getCustomerInformation(Request $request) {
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
			
			$HTML = '<div class="symbol symbol-60 symbol-xxl-100 mr-5 align-self-start align-self-xxl-center">
						<div class="symbol-label rounded-circle" style="background-image:url(\''.asset('assets/media/users/300_13.jpg').'\')"></div>
					</div>
					<div>
						<a href="#" class="font-weight-bold font-size-h5 text-dark-75 text-hover-primary">'.$ClientInfo->firstname.' '.$ClientInfo->lastname.'<span class="fonter-weight-bolder">*</span></a>
						<div class="text-muted">+'.$ClientInfo->mo_country_code.' '.$ClientInfo->mobileno.' <span class="font-weight-bolder">*</span></div>
						<div class="text-muted">'.$ClientInfo->email.'</div>
					</div>
					<i class="text-dark fa fa-chevron-right ml-auto"></i>';
					
			$historyhtml = "";
			
			$AppointmentServices = AppointmentServices::select('appointment_services.*')->join('appointments','appointments.id','=','appointment_services.appointment_id')->where('appointments.client_id',$client_id)->where('appointments.user_id', $AdminId)->orderBy('id', 'desc')->get()->toArray();
			
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
						$staff_name = $StaffDetails['first_name'].' '.$StaffDetails['last_name'];
					} else {
						$staff_name = 'N/A';
					}
					
					$servicePrices = ServicesPrice::select('pricing_name')->where('id',$AppointmentServiceData['service_price_id'])->orderBy('id', 'asc')->get()->first();
					
					$serviceName = '';
					if(!empty($servicePrices)){
						$serviceName = $servicePrices->pricing_name;
					} else {
						$serviceName = 'N/A';
					}
					
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
			
			$historyhtml = '<div class="card-body p-1">
							<div class="total-appoinment-data justify-content-around d-flex">
								<div class="text-center w-100 data pt-1 p-42">
									<h3 class="price font-weight-bolder text-center text-dark">'.count($AppointmentServices).'</h3>
									<p class="title text-muted">Total Booking</p>
								</div>
								<div class=" text-center w-100 data pt-1 p-2">
									<h3 class="price font-weight-bolder text-center text-dark">CA $'.$TotalSales.'</h3>
									<p class="title text-muted">Total Sales</p>
								</div>
							</div>
							<ul class="nav nav-tabs nav-tabs-line nav-tabs-primary nav-tabs-line-2x" role="tablist">
								<li class="nav-item">
									<a style="margin:0px;padding:0 6px;font-size: 12px;" class="nav-link active"
										data-toggle="tab" href="#appointments">Appointments
										('.count($AppointmentServices).')</a>
								</li>
								<li class="nav-item">
									<a style="margin:0px;padding:0 6px;font-size: 12px;" class="nav-link" data-toggle="tab" href="#products">Products ('.count($soldProduct).')</a>
								</li>
								<li class="nav-item">
									<a style="margin:0px;padding:0 6px;font-size: 12px;" class="nav-link"
										data-toggle="tab" href="#invoices">Invoices ('.count($clientInvoices).')</a>
								</li>
								<li class="nav-item">
									<a style="margin:0px;padding:0 6px;font-size: 12px;" class="nav-link" data-toggle="tab" href="#info">Info</a>
								</li>
							</ul>
							<div class="tab-content mt-5" id="myTabContent">
								<div class="tab-pane fade show active" id="appointments" role="tabpanel"
									aria-labelledby="appointments">
									<div class="row">
										<div class="card-body py-2 px-8">
											'.$ClientServices.'
										</div>
									</div>
								</div>
								<div class="tab-pane fade" id="products" role="tabpanel" aria-labelledby="products">'.$ClientProducts.'</div>
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
											<tbody>'.$ClientInovices.'</tbody>
										</table>
									</div>
								</div>
								<div class="tab-pane fade" id="info" role="tabpanel" aria-labelledby="info">
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
						</div>';
			
			$data["status"] = true;
			$data["customerData"] = $HTML;
			$data["customerHistory"] = $historyhtml;
            return JsonReturn::success($data);
        }
	}

	function searchVoucherCode(Request $request)
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
			
			$searchCode = ($request->code) ? strtoupper($request->code) : '';
			$HTML = '';
			$voucherData = SoldVoucher::select('sold_voucher.id','sold_voucher.voucher_id','sold_voucher.service_id','sold_voucher.total_value','sold_voucher.redeemed','sold_voucher.start_date','sold_voucher.end_date','sold_voucher.voucher_type','invoice.payment_id')->join('invoice','invoice.id','=','sold_voucher.invoice_id')->where('sold_voucher.voucher_code', $searchCode)->where('sold_voucher.user_id', $AdminId)->where('sold_voucher.status', 0)->whereIn('invoice.invoice_status', [0,1])->orderBy('sold_voucher.id', 'desc')->first();
			
			if(!empty($voucherData)) {
				
				$voucherData->service_id = json_encode(explode(",", $voucherData->service_id));
				$voucherData->start_date = date("l, d M Y", strtotime($voucherData->start_date));
				$voucherData->end_date = date("l, d M Y", strtotime($voucherData->end_date));
				
				$data["status"] = true;
				$data["data"] = $voucherData;
			} else {
				$HTML = '<div class="d-flex flex-column justify-content-center align-items-center my-20"><h6 class="font-weight-bold my-3">No vouchers found</h6></div>';
								
				$data["status"] = false; 
				$data["html"] = $HTML;
			}	
            return JsonReturn::success($data);
        }
	}	
	
	public function createSaleVoucher(Request $request)
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
			
			$service_ids = $request->service_ids;
			$voucher_value = $request->crt_voucher_value;
			$voucher_price = $request->crt_voucher_price;
			$maxNumberOfSales = $request->maxNumberOfSales;
			$voucher_name = $request->crt_voucher_name;
			$totalService = $request->totalService;
			
			$voucher_type = 0;
			$totalservice = $request->totalservice;
			if($totalservice > count(explode(",", $service_ids))) {
				$voucher_type = 1;
			}
			
			$insVoucher = Voucher::create([
				'user_id' => $AdminId,
				'value' => $voucher_value,
				'title' => $voucher_name,
				'name' => $voucher_name,
				'retailprice' => $voucher_price,
				'validfor' => $maxNumberOfSales,
				'enable_sale_limit' => 1,
				'numberofsales' => 1,
				'services_ids' => $service_ids,
				'description' => NULL,
				'color' => $request->color,
				'button' => 0,
				'is_online' => 0,
				'note' => NULL,
				'voucher_type' => $voucher_type,
				'created_from' => 0,
				'created_from' => 1
			]);
			
			$data["status"] = true;
			$data["id"] = $insVoucher->id;
            return JsonReturn::success($data);
		}		
	}	
	
	function searchCustomers(Request $request)
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
						<div class="d-flex align-items-center border-bottom p-6 customer-data selectclient" data-cid="'.$Clientdata['id'].'" >
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
	
	public function saveCheckoutItem(Request $request)
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
			
			$locationId = $request->locationId;	
			$paymentId = ($request->paymentId) ? $request->paymentId : 0;
			$paymentType = ($request->paymentType) ? $request->paymentType : NULL;
			$clientId = ($request->clientId) ? $request->clientId : 0;	
			$itemsubtotal = $request->itemsubtotal;	
			$itemtotal = $request->itemtotal;	
			$itemfinaltotal = $request->itemfinaltotal;	
			$paymenyReceivedyBy = $request->paymenyReceivedyBy;	
			$notes = $request->notes;	
			
			$item_ids = $request->item_id;
			$item_type = $request->item_type;
			$item_og_price = $request->item_og_price;
			$item_price = $request->item_price;
			$discount_id = $request->discount_id;
			$discount_text = $request->item_discount_text;
			$quantity = $request->quantity;
			$staff_id = $request->staff_id;
			$remove_tip = $request->remove_tip;

			foreach($quantity as $key=>$value){
				if(empty(trim($quantity[$key]))){
					return JsonReturn::error(array("messages" => array("Please enter quantity.")));
				}
			}
			
			$encryptAppId = ($request->encryptAppId) ? $request->encryptAppId : 0;	
			$decryptAppId = ($request->decryptAppId) ? $request->decryptAppId : 0;	
			
			$invoice_status = 0;
			if($paymentId > 0 || !empty($request->voucher_code)) {
				$invoice_status = 1;
			}		
			
			$voucher_id_arr = array();
			foreach($item_type as $key1 => $val) {
				if($val == 'voucher') {
					$voucher_id = $item_ids[$key1];
					$voucher_qty = $quantity[$key1];
					
					$tmp_arr = array(
						'id' => $voucher_id,
						'qty' => $voucher_qty
					);
					
					$search = ['id' => $voucher_id];
					$keys = array_keys(array_filter($voucher_id_arr,function ($v) use ($search) { return $v['id'] == $search['id']; } ) );
					if(isset($keys[0])){
						$index = $keys[0];
						$old1 = $voucher_id_arr[$index]['qty'];
						$voucher_id_arr[$index]['qty'] = $old1 + $voucher_qty;	
					} else {
						array_push($voucher_id_arr, $tmp_arr);
					}
				}		
			}
			
			if(!empty($voucher_id_arr)) {
				foreach($voucher_id_arr as $val) {
					$checkVoucherQty = Voucher::select('id', 'numberofsales')->where('enable_sale_limit', 1)->where('id', $val['id'])->where('user_id', $AdminId)->first();
					
					if(!empty($checkVoucherQty)) {
						$totalSold = SoldVoucher::select('id')->where('voucher_id', $val['id'])->where('user_id', $AdminId)->count();
						$checkStock = $totalSold + $val['qty'];
						$checkVoucherQty->numberofsales;
						if($checkStock > $checkVoucherQty->numberofsales) {
							return JsonReturn::error(array("messages" => array("Quantity entered is higher than sell limit.")));
						}	
					}
				}		
			}	
			
			$is_voucher_apply = 0;
			$apply_voucher_code = $request->voucher_code;
			
			if(!empty($apply_voucher_code)) {
				$is_voucher_apply = 1;
			}	
			
			if($remove_tip == 1) {
				$staff_tips = $request->tipAmount;
				$totalTipAmt = 0;
				if(!empty($staff_tips)) {
					foreach($staff_tips as $key3 => $val) {
						$totalTipAmt = $totalTipAmt + $val;
					}
				}
				
				$itemfinaltotal = $itemfinaltotal - $totalTipAmt;
			}	
			
			$invoiceSequence = InvoiceSequencing::getInvoiceSequence($AdminId, $locationId);
			
			$invoicePrefix = $invoiceSequence['prefix'];
			$invoiceNo = $invoiceSequence['invoiceNo'];
			
			$Invoice = Invoice::create([
				'invoice_prefix'	  => $invoicePrefix,
				'invoice_id'      	  => $invoiceNo,
				'appointment_id'      => $decryptAppId,
				'location_id'         => $locationId,
				'is_voucher_apply'	  => $is_voucher_apply,
				'payment_id'   		  => $paymentId,
				'payment_type'  	  => $paymentType,
				'client_id'           => $clientId,
				'invoice_sub_total'	  => $itemsubtotal,
				'invoice_total'       => $itemtotal,
				'inovice_final_total' => $itemfinaltotal,
				'invoice_status'      => $invoice_status,
				'notes'         	  => $notes,
				'user_id'         	  => $AdminId,
				'created_by'          => $paymenyReceivedyBy,
				'payment_date'        => date("Y-m-d H:i:s"),
				'created_at'          => date("Y-m-d H:i:s"),
				'updated_at'          => date("Y-m-d H:i:s")
			]);
			
			$lastInvoiceId = $Invoice->id;
			
			if($lastInvoiceId > 0) {

				$AppointmentFind = Appointments::find($decryptAppId);
				if(!empty($AppointmentFind)){
					$AppointmentFind->client_id          = $clientId;
					$AppointmentFind->invoice_id         = $lastInvoiceId;
					
					if($invoice_status == 1){
						$AppointmentFind->appointment_status = 4;
					}
					
					$AppointmentFind->updated_at         = date("Y-m-d H:i:s");
					$AppointmentFind->save();
				}

				
				// dd($item_ids);
				foreach($item_ids as $key => $val) {
					$qty = $quantity[$key];
					$itemType = $item_type[$key];

					$item_tax_rate = $request->{'item_tax_rate'.$val};
					$item_tax_amount = $request->{'item_tax_amount'.$val};
					// $item_tax_id = $request->{'item_tax_id'.$val};

					if($itemType == "product"){
						$tempTaxId = InventoryProducts::select('tax_id')->where('id', $val)->first();
						$item_tax_id = $tempTaxId->tax_id;
					}elseif($itemType == 'services'){
						$tempTaxId = Services::select('tax_id')->where('id', $val)->first();
						$item_tax_id = $tempTaxId->tax_id;
					}else{
						$item_tax_id = 0;
					}

					// echo "item_tax_rate :- ".$temp; die;
	
					$Invoice = InvoiceItems::create([
						'invoice_id'      	=> $lastInvoiceId,
						'item_id'       	=> $val,
						'client_id'       	=> $clientId,
						'item_type'   		=> $itemType,
						'quantity'  		=> $qty,
						'item_og_price'     => $item_og_price[$key],
						'item_price' 		=> $item_price[$key],
						'staff_id'        	=> ($staff_id[$key]) ? $staff_id[$key] : 0,
						'item_discount_id'  => ($discount_id[$key]) ? $discount_id[$key] : 0,
						'item_discount_text'=> ($discount_text[$key]) ? $discount_text[$key] : "",
						'appointment_services_id' => ($request->appointment_services_id[$key]) ? $request->appointment_services_id[$key] : 0,
						'item_tax_id' => $item_tax_id ? $item_tax_id : 0,
						'item_tax_rate' => $item_tax_rate ? $item_tax_rate : 0,
						'item_tax_amount' => $item_tax_amount ? $item_tax_amount : 0,
						'created_at'        => date("Y-m-d H:i:s"),
						'updated_at'        => date("Y-m-d H:i:s")
					]);	
					
					if($itemType == 'voucher') 
					{
						$qty = $quantity[$key];
						$voucherData = Voucher::select('id', 'services_ids', 'value', 'validfor', 'voucher_type', 'created_from')->where('id', $val)->where('user_id', $AdminId)->first();
						
						$start_date = date("Y-m-d");
						$end_date = date("Y-m-d", strtotime($voucherData->validfor));
						
						for($i = 1; $i <= $qty; $i++) 
						{
							$voucher_code = $this->quickRandom(7);
							
							$insVoucher = SoldVoucher::create([
								'user_id'      	=> $AdminId,
								'location_id'   => $locationId,
								'invoice_id'   	=> $lastInvoiceId,
								'client_id'  	=> $clientId,
								'service_id'    => $voucherData->services_ids,
								'voucher_id' 	=> $val,
								'voucher_code'	=> $voucher_code,
								'total_value'  	=> $voucherData->value,
								'redeemed'		=> 0,
								'price'			=> $item_price[$key],
								'validfor'		=> $voucherData->validfor,
								'start_date'	=> $start_date,
								'end_date'		=> $end_date,
								'voucher_type'	=> $voucherData->voucher_type,
								'created_from'	=> $voucherData->created_from,
								'created_at'    => date("Y-m-d H:i:s"),
								'updated_at'    => date("Y-m-d H:i:s")
							]);	
						}	
					}	
				 	
					if($itemType == 'paidplan') 
					{
						$paidPlanData = PaidPlan::select('id','name','services_ids','sessions','sessions_num','price','valid_for')->where('id', $val)->first();
						
						$start_date = date("Y-m-d");
						$end_date = date("Y-m-d", strtotime($paidPlanData->valid_for));
						
						$insPaidPlan = SoldPaidPlan::create([
							'user_id' 		=> $AdminId,
							'location_id'   => $locationId,
							'invoice_id' 	=> $lastInvoiceId,
							'client_id'  	=> $clientId,
							'paidplan_id' 	=> $val,
							'service_id' 	=> $paidPlanData->services_ids,
							'price' 		=> $item_price[$key],
							'valid_for'		=> $paidPlanData->valid_for,
							'sessions'		=> $paidPlanData->sessions,
							'no_of_sessions'=> ($paidPlanData->sessions == 0) ? $paidPlanData->sessions_num : 0,
							'used_sessions' => 0,
							'start_date' 	=> $start_date,
							'end_date' 		=> $end_date,
							'created_at' 	=> date("Y-m-d H:i:s"),
							'updated_at' 	=> date("Y-m-d H:i:s")
						]); 
					}	
					
					if($itemType == 'product') 
					{
						$itemprice = $item_price[$key];
						$stockData = InventoryProducts::select('id','enable_stock_control','initial_stock')->where('id', $val)->where('user_id', $AdminId)->first();
						
						if(!empty($stockData)) 
						{
							$stock_on_hand = 0;
							if($stockData->enable_stock_control == 1) {
								$stock_on_hand = $stockData->initial_stock - $qty;
								
								$stockData->initial_stock = $stock_on_hand;
								$stockData->save();
							}
							
							$PrevAveragePrice = InventoryOrderLogs::select("average_price")
							->where('item_id',$val)
							->orderBy('id', "DESC")
							->first();
							
							$Invoice = InventoryOrderLogs::create([
								'item_id'      			=> $val,
								'received_date'       	=> date("Y-m-d H:i:s"),
								'received_by'   		=> $AdminId,
								'supplier_id'  			=> 0,
								'location_id'     		=> $locationId,
								'invoice_id' 			=> $lastInvoiceId,
								'order_type'        	=> 1,
								'order_action'  		=> "Invoice",
								'order_status'			=> 1,
								'qty_adjusted'			=> "-".$qty,
								'cost_price'			=> $itemprice,
								'stock_on_hand'			=> $stock_on_hand,
								'average_price'			=> $PrevAveragePrice->average_price,
								'enable_stock_control'	=> $stockData->enable_stock_control,
								'created_at'        => date("Y-m-d H:i:s"),
								'updated_at'        => date("Y-m-d H:i:s")
							]);
						}	
					}	
				}
				
				$apply_payment_amt = $request->payment_amt;
			
				if(!empty($apply_voucher_code)) {
					foreach($apply_voucher_code as $vkey => $val) 
					{
						$total_redeem = 0;
						$getVoucherId = SoldVoucher::select('id', 'voucher_id', 'redeemed')->where('voucher_code', $val)->where('user_id', $AdminId)->first();
						$total_redeem = $getVoucherId->redeemed + $apply_payment_amt[$vkey];
						
						$getVoucherId->redeemed = $total_redeem;
						$getVoucherId->save();
						
						$insInvVoucher = InvoiceVoucher::create([
							'invoice_id'	=> $lastInvoiceId,
							'location_id'   => $locationId,
							'voucher_id'	=> $getVoucherId->voucher_id,
							'voucher_code'  => $val,
							'voucher_amount'=> $apply_payment_amt[$vkey],
							'created_at'    => date("Y-m-d H:i:s"),
							'updated_at'    => date("Y-m-d H:i:s")
						]);	
					}	
				}
				
				$inovicetaxes = $request->invoice_tax_amount;
				if(!empty($inovicetaxes)) {
					foreach($inovicetaxes as $key2 => $val) {
						$inoviceTax = InvoiceTaxes::create([
							'invoice_id'	=> $lastInvoiceId,
							'location_id'   => $locationId,
							'tax_id'		=> $request->invoice_tax_id[$key2],
							'tax_rate'    	=> $request->invoice_tax_rate[$key2],
							'tax_amount' 	=> $val,
							'created_at'    => date("Y-m-d H:i:s"),
							'updated_at'    => date("Y-m-d H:i:s")
						]);	
					}	
				}	
				
				if($remove_tip == 0) {
					$staff_tips = $request->tipAmount;
					if(!empty($staff_tips)) {
						foreach($staff_tips as $key3 => $val) {
							$staffTips = StaffTip::create([
								'inovice_id'	=> $lastInvoiceId,
								'staff_id'      => $request->tipToStaff[$key3],
								'location_id'   => $locationId,
								'tip_percentage'=> $request->tipVal[$key3],
								'tip_amount'    => $val,
								'type' 			=> $request->tipType[$key3],
								'status'        => 0,
								'created_at'    => date("Y-m-d H:i:s"),
								'updated_at'    => date("Y-m-d H:i:s")
							]);	
						}	
						// send email 
						$client = Clients::where('id',$clientId)->first();//clientId
						$getUser = User::getUserbyID($AdminId);
						$firstName = $getUser->first_name;

						$TotalStaffTip = StaffTip::select(DB::raw('SUM(tip_amount) as total_tip'))->where('staff_tip.inovice_id', $lastInvoiceId)->get()->toArray();

						$Invoice = Invoice::select('*')->where('id',$lastInvoiceId)->where('user_id', $AdminId)->orderBy('id', 'desc')->get()->first();

						$InvoiceTaxes = InvoiceTaxes::select('invoice_taxes.tax_rate','invoice_taxes.tax_amount','taxes.tax_name')->leftJoin('taxes', 'taxes.id', '=', 'invoice_taxes.tax_id')->where('invoice_taxes.invoice_id', $lastInvoiceId)->get()->toArray();

						$InvoiceItems = InvoiceItems::select('*')->where('invoice_id',$lastInvoiceId)->orderBy('id', 'desc')->get()->toArray();
						
						$InvoiceItemsInfo = array();
						if(!empty($InvoiceItems)){
							foreach($InvoiceItems as $InvoiceItemDetail){
								if($InvoiceItemDetail['item_type'] == 'services'){
									
									$SERVICE_ID = $InvoiceItemDetail['item_id'];
									
									$singleService = Services::select('id','service_name')->where('id', $SERVICE_ID)->where('user_id', $AdminId)->orderBy('order_id', 'asc')->get()->first();
									
									$tempItemDetail['main_title'] = ($singleService) ? $singleService->service_name : '';
									
									// $CurrentStaff = Staff::getStaffDetailByID($InvoiceItemDetail['staff_id']);
									$CurrentStaff = Staff::getStaffDetailByStaffID($InvoiceItemDetail['staff_id']);
								
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
									
									$Voucher = SoldVoucher::select('vouchers.title','sold_voucher.voucher_code','sold_voucher.end_date','vouchers.name')->leftJoin('vouchers', 'vouchers.id', '=', 'sold_voucher.voucher_id')->where('sold_voucher.voucher_id', $VOUCHER_ID)->where('sold_voucher.invoice_id', $lastInvoiceId)->where('sold_voucher.user_id', $AdminId)->orderBy('sold_voucher.id', 'desc')->get()->toArray();
									
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
									
									$PaidPlan = SoldPaidPlan::select('paid_plan.name','sold_paidplan.end_date')->leftJoin('paid_plan', 'paid_plan.id', '=', 'sold_paidplan.paidplan_id')->where('sold_paidplan.paidplan_id', $PAIDPLAN_ID)->where('sold_paidplan.invoice_id', $lastInvoiceId)->where('sold_paidplan.user_id', $AdminId)->get()->first()->toArray();
									
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

						// $invoice = Invoice::where('id',$lastInvoiceId)->first();
						// $invoiceItem = InvoiceItems::where('invoice_id', $invoice->id);
						$locationData = Location::select('locations.id', 'locations.location_name', 'locations.location_image', 'locations.location_address')->where('user_id', $AdminId)->orderBy('id', 'ASC')->first();
						

						$Appointment = Appointments::select('*')->where('user_id',$AdminId)->where('invoice_id',$lastInvoiceId)->first();
						if(!empty($Appointment)){
							$AppointmentServices = AppointmentServices::select('*')->where('appointment_id',$Appointment->id)->first();
						}
						// $serPrice = ServicesPrice::where('id',$AppointmentServices->service_price_id)->first();
						// $service = Services::where('id',$serPrice->service_id)->first();
						$remidernoti = TippingNotification::where('user_id',$AdminId)->first();
						$is_price_view = $remidernoti->is_displayServicePrice;
						$note = $remidernoti->important_info;

						if(!empty($client) && !empty($remidernoti)){
							$email = $client->email;
							$FROM_EMAIL     = env("MAIL_FROM_ADDRESS", "info@ikotel.ca");
							$FROM_NAME      = 'Scheduledown';
							$TO_EMAIL       = $email;
							$CC_EMAIL       = 'tjcloudtest2@gmail.com';
							$SUBJECT        = 'Appointment';
							
							$UniqueId       = $this->unique_code(30).time();
							
							$SendMail = Mail::to($TO_EMAIL)->cc([$CC_EMAIL])->send(new forTippingNotification($FROM_EMAIL,$FROM_NAME,$SUBJECT,null,null,$UniqueId,$client,null,$remidernoti,$firstName,$is_price_view,$note,$locationData,$InvoiceItemsInfo, $Invoice, $InvoiceTaxes, $TotalStaffTip));

							// $SendMail = Mail::to($TO_EMAIL)->cc([$CC_EMAIL])->send(new forTippingNotification($FROM_EMAIL,$FROM_NAME,$SUBJECT,$UniqueId,$client,$firstName,$is_price_view,$note,$locationData));
							
							EmailLog::create([
								'user_id' => $AdminId,
								'client_id' => $client->id,
                                // 'appointment_id' => $Appointment->id,
								'unique_id' => $UniqueId,
								'from_email' => $FROM_EMAIL,
								'to_email' => $TO_EMAIL,
								'module_type_text' => 'Tipping Appointment Email',
                                'created_at'       => date("Y-m-d H:i:s")
							]);
						}
						//end

						// Notification AppointmentFind
						$staffUser = User::find($request->tipToStaff[$key3]);
						$staffUserName = isset($staffUser->first_name) ? $staffUser->first_name : '';
						$locationData = Location::find($locationId);
						$locationName = isset($locationData->location_name) ? $locationData->location_name : '';

						if($AppointmentFind != ''){
							$title = 'Tip to '.$staffUserName;
							$description = 'Tip of '.$val.' to '.$staffUserName.' by '.$client->firstname.' at '.$locationName;

							$this->notificationRepositorie->sendNotification($UserId, $client->id, 'appointment', $AppointmentFind->id, $title, $description, $AppointmentFind->location_id, 'tips', false); 
						}
					}	
				}	
			}

			// send email 
			
			if($decryptAppId != '' && $decryptAppId != 0){
				$client = Clients::where('id',$clientId)->first();
				$AppointmentServices = AppointmentServices::where('appointment_id',$AppointmentFind->id)->first();
				$serPrice = ServicesPrice::where('id',$AppointmentServices->service_price_id)->first();
				$service = Services::where('id',$serPrice->service_id)->first();
				$appointment = $AppointmentFind;
				$remidernoti = thankyouNotification::where('user_id',$AdminId)->first();
				
				if(!empty($client) && !empty($remidernoti)){
					$email = $client->email;
					$FROM_EMAIL     = env("MAIL_FROM_ADDRESS", "info@ikotel.ca");
					$FROM_NAME      = 'Scheduledown';
					$TO_EMAIL       = $email;
					$CC_EMAIL       = 'tjcloudtest2@gmail.com';
					$SUBJECT        = 'Appointment';
					
					$UniqueId       = $this->unique_code(30).time();
					
					$SendMail = Mail::to($TO_EMAIL)->cc([$CC_EMAIL])->send(new thankyouAppointment($FROM_EMAIL,$FROM_NAME,$SUBJECT,$AppointmentServices,$appointment,$UniqueId,$client,$service,$remidernoti));	
					
					EmailLog::create([
						'user_id' => $AdminId,
						'client_id' => $client->id,
						'appointment_id' => $appointment->id,
						'unique_id' => $UniqueId,
						'from_email' => $FROM_EMAIL,
						'to_email' => $TO_EMAIL,
						'module_type_text' => 'Thank you Appointment Email',
						'created_at'       => date("Y-m-d H:i:s")
					]);
				}
				//end	
			}
			
			Session::flash('message', 'Invoice has been created succesfully.');
			$data["status"] = true;
			$data["redirect"] = route('calander');
			return JsonReturn::success($data);
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
	
	public function createRefundInvoice(Request $request)
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
			
			$locationId = $request->locationId;	
			$original_invoice_id = $request->invoiceId;	
			$paymentId = ($request->paymentId) ? $request->paymentId : 0;
			$paymentType = ($request->paymentType) ? $request->paymentType : NULL;
			$clientId = ($request->clientId) ? $request->clientId : 0;	
			$itemsubtotal = $request->itemsubtotal;	
			$itemtotal = $request->itemtotal;	
			$itemfinaltotal = $request->itemfinaltotal;	
			$paymenyReceivedyBy = $request->paymenyReceivedyBy;	
			$notes = $request->notes;	
			
			$item_ids = $request->item_id;
			$item_type = $request->item_type;
			$item_og_price = $request->item_og_price;
			$item_price = $request->item_price;
			$discount_id = $request->discount_id;
			$discount_text = $request->item_discount_text;
			$quantity = $request->quantity;
			$staff_id = $request->staff_id;			
			$invoice_status = 2;
			$is_voucher_apply = 0;
			
			$invoiceSequence = InvoiceSequencing::getInvoiceSequence($AdminId, $locationId);
			
			$invoicePrefix = $invoiceSequence['prefix'];
			$invoiceNo = $invoiceSequence['invoiceNo'];
			
			$Invoice = Invoice::create([
				'invoice_prefix'	=> $invoicePrefix,
				'invoice_id'      	=> $invoiceNo,
				'location_id'       => $locationId,
				'is_voucher_apply'	=> $is_voucher_apply,
				'payment_id'   		=> $paymentId,
				'payment_type'  	=> $paymentType,
				'client_id'         => $clientId,
				'invoice_sub_total'	=> $itemsubtotal,
				'invoice_total'     => $itemtotal,
				'inovice_final_total' => $itemfinaltotal,
				'invoice_status'    => $invoice_status,
				'notes'         	=> $notes,
				'original_invoice_id'=> $original_invoice_id,
				'user_id'         	=> $AdminId,
				'created_by'        => $paymenyReceivedyBy,
				'payment_date'      => date("Y-m-d H:i:s"),
				'created_at'        => date("Y-m-d H:i:s"),
				'updated_at'        => date("Y-m-d H:i:s")
			]);
			
			$lastInvoiceId = $Invoice->id;
			
			if($lastInvoiceId > 0) {		
				foreach($item_ids as $key => $val) {
					$qty = $quantity[$key];
					$itemType = $item_type[$key];

					if($itemType == "product"){
						$tempTaxId = InventoryProducts::select('tax_id')->where('id', $val)->first();
						$item_tax_id = $tempTaxId->tax_id;
					}elseif($itemType == 'services'){
						$tempTaxId = Services::select('tax_id')->where('id', $val)->first();
						$item_tax_id = $tempTaxId->tax_id;
					}else{
						$item_tax_id = 0;
					}

					$item_tax_rate = $request->{'item_tax_rate'.$val};
					$item_tax_amount = $request->{'item_tax_amount'.$val};
					// $item_tax_id = $request->{'item_tax_id'.$val};
	
					$Invoice = InvoiceItems::create([
						'invoice_id'      	=> $lastInvoiceId,
						'item_id'       	=> $val,
						'client_id'       	=> $clientId,
						'item_type'   		=> $itemType,
						'quantity'  		=> $qty,
						'item_og_price'     => $item_og_price[$key],
						'item_price' 		=> $item_price[$key],
						'staff_id'        	=> isset($staff_id[$key]) ? $staff_id[$key] : 0,
						'item_discount_id'  => isset($discount_id[$key]) ? $discount_id[$key] : 0,
						'item_discount_text'=> isset($discount_text[$key]) ? $discount_text[$key] : "",
						'appointment_services_id' => isset($request->appointment_services_id[$key]) ? $request->appointment_services_id[$key] : 0,
						'item_tax_id' => $item_tax_id ? $item_tax_id : 0,
						'item_tax_rate' => $item_tax_rate ? $item_tax_rate : 0,
						'item_tax_amount' => $item_tax_amount ? $item_tax_amount : 0,
						'created_at'        => date("Y-m-d H:i:s"),
						'updated_at'        => date("Y-m-d H:i:s")
					]);	
					
					if($itemType == 'voucher') 
					{
						$voucherData = SoldVoucher::select('*')->where('voucher_id', $val)->where('invoice_id', $original_invoice_id)->first();
						
						if(!empty($voucherData)) {
							$voucherData->status = 1;
							$voucherData->save();
							
							$insVoucher = SoldVoucher::create([
								'user_id'      	=> $voucherData->user_id,
								'location_id'   => $voucherData->location_id,
								'invoice_id'   	=> $lastInvoiceId,
								'client_id'  	=> $voucherData->client_id,
								'service_id'    => $voucherData->service_id,
								'voucher_id' 	=> $voucherData->voucher_id,
								'voucher_code'	=> $voucherData->voucher_code,
								'total_value'  	=> $voucherData->total_value,
								'redeemed'		=> $voucherData->redeemed,
								'price'			=> $voucherData->price,
								'validfor'		=> $voucherData->validfor,
								'start_date'	=> $voucherData->start_date,
								'end_date'		=> $voucherData->end_date,
								'voucher_type'	=> $voucherData->voucher_type,
								'created_from'	=> $voucherData->created_from,
								'status'		=> 1,
								'created_at'    => date("Y-m-d H:i:s"),
								'updated_at'    => date("Y-m-d H:i:s")
							]);	
						}	
					}
				 	
					if($itemType == 'paidplan') 
					{
						$paidPlanData = SoldPaidPlan::select('*')->where('paidplan_id', $val)->where('invoice_id', $original_invoice_id)->first();
						
						if(!empty($paidPlanData)) {
							$paidPlanData->status = 1;
							$paidPlanData->save();
						}	
					}
					
					if($itemType == 'product') 
					{
						$itemprice = $item_price[$key];
						$stockData = InventoryProducts::select('id','enable_stock_control','initial_stock')->where('id', $val)->where('user_id', $AdminId)->first();
						
						if(!empty($stockData)) 
						{
							$stock_on_hand = 0;
							if($stockData->enable_stock_control == 1) {
								$stock_on_hand = $stockData->initial_stock + $qty;
								
								$stockData->initial_stock = $stock_on_hand;
								$stockData->save();
							}
							$InventoryOrderLogs = InventoryOrderLogs::select(DB::raw('SUM(qty_adjusted * cost_price) as TotalStockCost'), DB::raw('SUM(cost_price) as TotalCostPrice'), DB::raw('SUM(qty_adjusted) as TotalQtyAdjusted') )
							->where('item_id',$val)
							->first();	
							
							if(!empty($InventoryOrderLogs))
							{
								$newStock1 = $qty * $itemprice;
								// $TotalCostPrice = $InventoryOrderLogs->TotalCostPrice;
								$TotalStockCost = $InventoryOrderLogs->TotalStockCost;
								$TotalStockCost += $newStock1;
								$TotalQtyAdjusted = $InventoryOrderLogs->TotalQtyAdjusted;
								$TotalQtyAdjusted += $qty;

								if($TotalStockCost != 0 && $TotalQtyAdjusted != 0){
									$AvgStockCost = ceil(round($TotalStockCost / $TotalQtyAdjusted));
								}
							}
							
							$Invoice = InventoryOrderLogs::create([
								'item_id'      			=> $val,
								'received_date'       	=> date("Y-m-d H:i:s"),
								'received_by'   		=> $AdminId,
								'supplier_id'  			=> 0,
								'location_id'     		=> $locationId,
								'invoice_id' 			=> $lastInvoiceId,
								'order_type'        	=> 1,
								'order_action'  		=> "Return: Invoice",
								'order_status'			=> 1,
								'qty_adjusted'			=> "+".$qty,
								'cost_price'			=> $itemprice,
								'stock_on_hand'			=> $stock_on_hand,
								'average_price'			=> $AvgStockCost,
								'enable_stock_control'	=> $stockData->enable_stock_control,
								'created_at'        => date("Y-m-d H:i:s"),
								'updated_at'        => date("Y-m-d H:i:s")
							]);
						}	
					}	
				}
				
				$inovicetaxes = $request->invoice_tax_amount;
				if(!empty($inovicetaxes)) {
					foreach($inovicetaxes as $key2 => $val) {
						$inoviceTax = InvoiceTaxes::create([
							'invoice_id'	=> $lastInvoiceId,
							'location_id'   => $locationId,
							'tax_id'		=> $request->invoice_tax_id[$key2],
							'tax_rate'    	=> $request->invoice_tax_rate[$key2],
							'tax_amount' 	=> $val,
							'status' 		=> 1,
							'created_at'    => date("Y-m-d H:i:s"),
							'updated_at'    => date("Y-m-d H:i:s")
						]);	
					}	
				}	
				
				$staff_tips = $request->tipAmount;
				if(!empty($staff_tips)) 
				{
					$staffTip = StaffTip::select('*')->where('inovice_id', $original_invoice_id)->get();
					if(!empty($staffTip)) 
					{	
						foreach($staffTip as $key3 => $val) {
							$staffTips = StaffTip::create([
								'inovice_id'	=> $lastInvoiceId,
								'staff_id'      => $val->staff_id,
								'location_id'   => $val->location_id,
								'tip_percentage'=> $val->tip_percentage,
								'tip_amount'    => $val->tip_amount,
								'type' 			=> $val->type,
								'status'        => 1,
								'created_at'    => date("Y-m-d H:i:s"),
								'updated_at'    => date("Y-m-d H:i:s")
							]);	
						}	
					}	
				}	
			}
			
			Session::flash('message', 'Invoice has been created succesfully.');
			$data["status"] = true;
			$data["redirect"] = route('calander');
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
	
	function hoursandmins($time, $format = '%02d:%02d')
	{
		if ($time < 1) {
			return;
		}
		$hours = floor($time / 60);
		$minutes = ($time % 60);
		return $hours.'h '.$minutes.'min';
	}

}
