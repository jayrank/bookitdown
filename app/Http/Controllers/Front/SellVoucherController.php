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
use App\Models\Setting;
use App\Models\Payment_response;
use App\Mail\SellVoucherEmail;
use DataTables;
use Stripe;
use Session;
use Crypt;
use Mail;
use PDF;

class SellVoucherController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
		
    }
	
	public function index($locationId = null, Request $request)
	{
		$encryptLocationId = $locationId;
		$locationId = Crypt::decryptString($locationId);
		$voucherId = ($request->voucher_id) ? $request->voucher_id : 0;
		
		$LocationData = Location::select('id','user_id','location_name','location_address','location_image')->where('id', $locationId)->first();
		$locationUserId = $LocationData->user_id;
		
		$loggedUser = Auth::guard('fuser')->user();
		$loggedUserId = "";
		if(!empty($loggedUser)) {
			$loggedUserId = $loggedUser->id;
		}
		
		$voucherData = Voucher::select('id','services_ids','color','value','name','retailprice','voucher_type','enable_sale_limit','numberofsales','validfor')->where('user_id', $locationUserId)->where('id', $voucherId)->first();

		if(!empty($voucherData)) {
			$totalSold = SoldVoucher::select('id')->where('voucher_id', $voucherData->id)->count();
			$voucherData->totalSold = $totalSold;
		}
		
		$serviceCategory = [];
		if($voucherId > 0) 
		{	
			$service_id_array = explode(',',$voucherData->services_ids);
			
			$serviceLists = Services::select('services.id','services.service_name','services.service_description', 'services.service_category', 'service_category.category_title')
							->leftJoin('service_category', 'service_category.id', 'services.service_category')
							->whereIn('services.id', $service_id_array)
							->orderBy('services.order_id', 'asc')
							->get();
			
			foreach($serviceLists as $key => $service)
			{
				$pricearr = array();
				$servicePrices = ServicesPrice::select('id', 'duration', 'lowest_price', 'price', 'special_price', 'is_staff_price')
								->where('service_id', $service->id)
								->orderBy('id', 'asc')
								->get();
				
				$service_price_special_amount = '';
				$service_price_amount = '';
				$is_staff_price = '';
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

					if(empty($service_price_special_amount)) {
						$service_price_special_amount = $service_price_special;
						$service_price_amount = $service_price;
						$is_staff_price = $servprice->is_staff_price;
					} elseif( $service_price_special < $service_price_special_amount ) {
						$service_price_special_amount = $service_price_special;
						$service_price_amount = $service_price;
						$is_staff_price = $servprice->is_staff_price;
					}
				}
				
				$min_duration = $this->convertDurationText(min(array_column($pricearr, 'service_price_duration'))); 
				$max_duration = $this->convertDurationText(max(array_column($pricearr, 'service_price_duration'))); 
				$service['serviceDuration'] = ($min_duration != $max_duration) ? $min_duration." - ".$max_duration : $min_duration;
				$service['servicePrice'] = $pricearr;

				$service['service_price_special_amount'] = $service_price_special;
				$service['service_price_amount'] = $service_price_amount;
				$service['is_staff_price'] = $is_staff_price;

				if( !isset($serviceCategory[ $service->service_category ]) ) {
					$serviceCategory[ $service->service_category ] = [];
				}

				$serviceCategory[ $service->service_category ][] = $service;
			}
		}
		
		$voucherLists = Voucher::select('id','services_ids','color','value','name','retailprice','voucher_type','enable_sale_limit','numberofsales','validfor')->where('user_id', $locationUserId)->where('is_online', 1)->where('created_from',0)->get();
		
		$setting = Setting::first();
		
		foreach($voucherLists as $key => $val) {
			$totalSold = SoldVoucher::select('id')->where('voucher_id', $val->id)->count();
			$val->uniId = $this->quickRandom();
			$val->totalSold = $totalSold;
		}
		
		return view('frontend.sell_voucher', compact('locationId','locationUserId','loggedUserId','LocationData','voucherLists','setting','voucherId','voucherData','encryptLocationId','serviceCategory'));
	} 

	function saveSellVoucherData(Request $request)
	{
		if ($request->ajax())
        {
			$setting = Setting::first();
			
			$loggedUserId = $request->loggedUserId;
			$locationId = $request->locationID;
			$userId = $request->userId;
			$invoiceTotal = $request->invoiceTotal;
			$voucherId = $request->voucherId;
			$voucherPrs = $request->voucherPrs;
			$invoiceTotal = $request->invoiceTotal;
			$product_qty = $request->product_qty;
			$recipient_as = $request->recipient_as;
			
			if($product_qty < 1) {
				return JsonReturn::error(array("messages" => array("Quantity should be atleast 1.")));
			}

			$checkVoucherQty = Voucher::select('id', 'numberofsales')->where('enable_sale_limit', 1)->where('id', $voucherId)->where('user_id', $userId)->first();
			
			if(!empty($checkVoucherQty)) {
				$totalSold = SoldVoucher::select('id')->where('voucher_id', $voucherId)->where('user_id', $userId)->count();
				
				$checkStock = $totalSold + $product_qty;
				$checkVoucherQty->numberofsales;
				if($checkStock > $checkVoucherQty->numberofsales) {
					return JsonReturn::error(array("messages" => array("Quantity entered is higher than sell limit.")));
				}
			}
			
			$response = array();
            $erro_message = "";
            Stripe\Stripe::setApiKey($setting->stripe_api_key);
			
			$amount = $invoiceTotal * 100;
            $currency = "CAD";
			
            try {
                $response = Stripe\Charge::create ([
                    "amount" => $amount,
                    "currency" => $currency,
                    "source" => $request->stripeToken,
                    "description" => "Voucher Purchase" 
                ]);
            } catch(Stripe\Exception\CardException $e) {
                $erro_message = $e->getError()->message;
            } catch (Stripe\Exception\RateLimitException $e) {
                $erro_message = $e->getError()->message;
            } catch (Stripe\Exception\InvalidRequestException $e) {
                $erro_message = $e->getError()->message;
            } catch (Stripe\Exception\AuthenticationException $e) {                    
                $erro_message = $e->getError()->message;
            } catch (Stripe\Exception\ApiConnectionException $e) {
                $erro_message = $e->getError()->message;
            } catch (Stripe\Exception\ApiErrorException $e) {
                $erro_message = $e->getError()->message;    
            } catch (Exception $e) {
                $erro_message = $e->getError()->message;
            }
            if(!empty($response))
            {
                if($response->id != '' && $response->paid == true)
                {
					$fUserData = frontUser::select('id','email','name','last_name','mobile')->where('id', $loggedUserId)->first();
					$checkClient = Clients::select('id')->where('user_id', $userId)->where('email', $fUserData->email)->first();
					
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
						$checkClient->fuser_id;
						$checkClient->save();
						$client_id = $checkClient->id;
					}
					
					$invoiceSequence = InvoiceSequencing::getInvoiceSequence($userId, $locationId);
					
					$invoicePrefix = $invoiceSequence['prefix'];
					$invoiceNo = $invoiceSequence['invoiceNo'];
					
					$Invoice = Invoice::create([
						'invoice_prefix'	  => $invoicePrefix,
						'invoice_id'      	  => $invoiceNo,
						'location_id'         => $locationId,
						'payment_id'   		  => 0,
						'payment_type'  	  => "Card",
						'client_id'           => $client_id,
						'invoice_sub_total'	  => $invoiceTotal,
						'invoice_total'       => $invoiceTotal,
						'inovice_final_total' => $invoiceTotal,
						'invoice_status'      => 0,
						'user_id'         	  => $userId,
						'payment_date'        => date("Y-m-d H:i:s"),
						'created_at'          => date("Y-m-d H:i:s"),
						'updated_at'          => date("Y-m-d H:i:s")
					]);
					
					$lastInvoiceId = $Invoice->id;
					
					$Invoice = InvoiceItems::create([
						'invoice_id'      	=> $lastInvoiceId,
						'item_id'       	=> $voucherId,
						'client_id'       	=> $client_id,
						'item_type'   		=> "voucher",
						'quantity'  		=> $product_qty,
						'item_og_price'     => $invoiceTotal,
						'item_price' 		=> $invoiceTotal,
						'staff_id'        	=> 0,
						'item_discount_id'  => 0,
						'item_discount_text'=> NULL,
						'created_at'        => date("Y-m-d H:i:s"),
						'updated_at'        => date("Y-m-d H:i:s")
					]);
					
					$voucherData = Voucher::select('id', 'name', 'services_ids', 'value', 'validfor', 'voucher_type', 'retailprice', 'created_from')->where('id', $voucherId)->where('user_id', $userId)->first();
					
					$locationData = Location::select('id', 'location_name', 'location_address')->where('id', $locationId)->first();
					
					$start_date = date("Y-m-d");
					$end_date = date("Y-m-d", strtotime($voucherData->validfor));
					
					for($i = 1; $i <= $product_qty; $i++) 
					{
						$voucher_code = $this->quickRandom(7);
						
						if($recipient_as == 2) 
						{
							$ClientInfo = Clients::getClientbyID($client_id);
							$RecipientFirstName = ($request->email_first_name) ? $request->email_first_name : '';	
							$RecipientLastName = ($request->email_last_name) ? $request->email_last_name : '';	
							
							$VoucherSoldData = array();
							$VoucherSoldData['message'] 	= ($request->email_msg) ? $request->email_msg : "";
							$VoucherSoldData['price'] 		= $voucherData->value;
							$VoucherSoldData['voucher_code']= $voucher_code;
							$VoucherSoldData['end_date'] 	= $end_date;
							
							if($voucherData->voucher_type == 0) {
								$VoucherSoldData['redeem_on'] = "all services";
							} else {
								$VoucherSoldData['redeem_on'] = count(explode(",", $voucherData->services_ids))." services";
							}		
							
							$LocationInfo = array();
							$LocationInfo['location_name'] = $locationData->location_name;
							$LocationInfo['location_address'] = $locationData->location_address;
							$LocationInfo['locationEncrytId'] = Crypt::encryptString($locationData->id);
							
							$VoucherName = ($voucherData->name) ? $voucherData->name : '';
							$FROM_EMAIL  = env("MAIL_FROM_ADDRESS", "info@ikotel.ca");
							$FROM_NAME   = 'Scheduledown';
							$TO_EMAIL    = $request->email_address;
							$SUBJECT     = $VoucherName.' from '.$fUserData->name.' '.$fUserData->last_name;
							$MESSAGE     = 'Hi Please see attached voucher, Have a great day! ';
							
							$SendMail = Mail::to($TO_EMAIL)->send(new SellVoucherEmail($FROM_EMAIL,$FROM_NAME,$SUBJECT,$MESSAGE,$VoucherSoldData,$LocationInfo,$ClientInfo,$RecipientFirstName,$RecipientLastName));
						}
						
						if($recipient_as == 2) {
							$firstname = ($request->email_first_name) ? $request->email_first_name : NULL;
							$lastname = ($request->email_last_name) ? $request->email_last_name : NULL;
							$voucher_messge = ($request->email_msg) ? $request->email_msg : NULL;
						} else if($recipient_as == 1) {
							$firstname = ($request->print_first_name) ? $request->print_first_name : NULL;
							$lastname = ($request->print_last_name) ? $request->print_last_name : NULL;
							$voucher_messge = ($request->print_message) ? $request->print_message : NULL;
						} else {
							$firstname = NULL;
							$lastname = NULL;
							$voucher_messge = NULL;
						}		
						
						$insVoucher = SoldVoucher::create([
							'user_id'      	=> $userId,
							'location_id'   => $locationId,
							'invoice_id'   	=> $lastInvoiceId,
							'client_id'  	=> $client_id,
							'service_id'    => $voucherData->services_ids,
							'voucher_id' 	=> $voucherId,
							'voucher_code'	=> $voucher_code,
							'total_value'  	=> $voucherData->value,
							'redeemed'		=> 0,
							'price'			=> $voucherData->retailprice,
							'validfor'		=> $voucherData->validfor,
							'start_date'	=> $start_date,
							'end_date'		=> $end_date,
							'voucher_type'	=> $voucherData->voucher_type,
							'created_from'	=> $voucherData->created_from,
							'recipient_as'	=> $recipient_as,
							'first_name'	=> $firstname,
							'last_name'		=> $lastname,
							'message'		=> $voucher_messge,
							'email'			=> ($request->email_address) ? $request->email_address : NULL,
							'created_at'    => date("Y-m-d H:i:s"),
							'updated_at'    => date("Y-m-d H:i:s")
						]);	
					}
					
					$payment_response = Payment_response::create([
    					'invoice_id'        => $lastInvoiceId,
    					'user_id'           => $userId,
    					'type'      		=> 'Voucher Purchase',
    					'charge_id'      	=> $response->id,
    					'currency'			=> 'CAD',
    					'amount'          	=> $invoiceTotal,
    					'transaction_id'    => $response->balance_transaction,
    					'card_id'        	=> $response->payment_method,
    					'module_type'     	=> '2',
    					'module_text'      	=> 'Voucher Purchase Payment',
    					'status'     		=> $response->paid,
    					'status_text'       => ($response->paid == 1) ? 'PAID' : 'UNPAID',
    					'payment_response'  => json_encode($response),
    					'created_at'        => date("Y-m-d H:i:s")
    				]);
					
					$data["status"] = true;
					$data["inoviceId"] = $lastInvoiceId;
					$data["redirect"] = route('myVouchers');
					return JsonReturn::success($data);
				}
                else if(!empty($erro_message))
                {
                    $data["status"] = false;
					$data["message"] = $erro_message;
					return JsonReturn::success($data);
                }
                else
                {
                    $data["status"] = false;
					$data["message"] = $erro_message;
					return JsonReturn::success($data);
                }
            }
            else if(!empty($erro_message))
            {
                $data["status"] = false;
				$data["message"] = $erro_message;
				return JsonReturn::success($data);
            }
		}	
	}	
	
	public function getVoucherService(Request $request)
	{
		if ($request->ajax())
		{
			$voucherId = $request->voucher_id;
			$userId = $request->userId;
			
			$serviceCategory = [];
			if($voucherId > 0) 
			{	
				$voucherData = Voucher::select('id','services_ids','color','value','name','retailprice','voucher_type','enable_sale_limit','validfor')->where('user_id', $userId)->where('id', $voucherId)->first();
			
				$service_id_array = explode(',',$voucherData->services_ids);
				
				$serviceLists = Services::select('services.id','services.service_name','services.service_description', 'services.service_category', 'service_category.category_title')
								->leftJoin('service_category', 'service_category.id', 'services.service_category')
								->whereIn('services.id', $service_id_array)
								->orderBy('services.order_id', 'asc')
								->get();
				
				foreach($serviceLists as $key => $service)
				{
					$pricearr = array();
					$servicePrices = ServicesPrice::select('id', 'duration', 'lowest_price', 'price', 'special_price', 'is_staff_price')
									->where('service_id', $service->id)
									->orderBy('id', 'asc')
									->get();
					
					$service_price_special_amount = '';
					$service_price_amount = '';
					$is_staff_price = '';
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

						if(empty($service_price_special_amount)) {
							$service_price_special_amount = $service_price_special;
							$service_price_amount = $service_price;
							$is_staff_price = $servprice->is_staff_price;
						} elseif( $service_price_special < $service_price_special_amount ) {
							$service_price_special_amount = $service_price_special;
							$service_price_amount = $service_price;
							$is_staff_price = $servprice->is_staff_price;
						}
					}
					
					$min_duration = $this->convertDurationText(min(array_column($pricearr, 'service_price_duration'))); 
					$max_duration = $this->convertDurationText(max(array_column($pricearr, 'service_price_duration'))); 
					$service['serviceDuration'] = ($min_duration != $max_duration) ? $min_duration." - ".$max_duration : $min_duration;
					$service['servicePrice'] = $pricearr;

					$service['service_price_special_amount'] = $service_price_special;
					$service['service_price_amount'] = $service_price_amount;
					$service['is_staff_price'] = $is_staff_price;

					if( !isset($serviceCategory[ $service->service_category ]) ) {
						$serviceCategory[ $service->service_category ] = [];
					}

					$serviceCategory[ $service->service_category ][] = $service;
				}
				
				$ser_html = "";
				if(!empty($serviceCategory)) 
				{
					foreach($serviceCategory as $key => $service) 
					{
						$ser_html .= "<div>";
							if(!empty($service)) 
							{
								foreach($service as $sKey => $sValue) 
								{
									if($sKey == 0) { 
										$ser_html .= '<h4 class="font-weight-bolder mb-4 mt-3 category-header">'.$sValue->category_title.'</h4>';
									}
									$ser_html .= '<div class="border-bottom mb-3 pb-2">';
										$ser_html .= '<span class="title d-flex justify-content-between">';
											$ser_html .= '<h6 class="font-weight-bolder mb-1">'.$sValue->service_name.'</h6>';
											$ser_html .= '<h6 class="text-muted mb-1">From</h6>';
										$ser_html .= '</span>';
										$ser_html .= '<span class="title d-flex justify-content-between">';
											$ser_html .= '<h6 class="text-muted">'.$sValue->serviceDuration.'</h6>';
											$ser_html .= '<h6 class="font-weight-bolder">&#8377;'.$sValue->service_price_special_amount.'</h6>';
										$ser_html .= '</span>';

										if($sValue->service_price_special_amount < $sValue->service_price_amount) {
											$ser_html .= '<span class="title d-flex justify-content-end">';
												$ser_html .= '<h6 class="text-muted"><strike>&#8377;'.$sValue->service_price_amount.'</strike></h6>';
											$ser_html .= '</span>';
										}
									$ser_html .= '</div>';
								}
							}
						$ser_html .= '</div>';
					}
				}
				echo $ser_html;
				die;
			}
		}	
	}	

	public function printSellVoucherData(Request $request)
	{
		if ($request->ajax())
		{
			$InvoiceID = ($request->invoice_id) ? $request->invoice_id : '';
			$userId = ($request->userId) ? $request->userId : '';
			
			$Invoice = Invoice::select('*')->where('id',$InvoiceID)->where('user_id', $userId)->orderBy('id', 'desc')->get()->first()->toArray();
				
			$VoucherSold = SoldVoucher::select('locations.location_name','locations.location_address','locations.location_image','vouchers.title','sold_voucher.voucher_code','sold_voucher.end_date','vouchers.name','sold_voucher.price','sold_voucher.first_name','sold_voucher.last_name','sold_voucher.message')->leftJoin('vouchers', 'vouchers.id', '=', 'sold_voucher.voucher_id')->leftJoin('locations', 'locations.id', '=', 'sold_voucher.location_id')->where('sold_voucher.invoice_id', $InvoiceID)->where('sold_voucher.user_id', $userId)->orderBy('sold_voucher.id', 'desc')->get()->toArray();
			
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
			return PDF::loadView('pdfTemplates.sellVoucherPdfPrint',$pdfData)->setPaper('a4')->download('Voucher.pdf');
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