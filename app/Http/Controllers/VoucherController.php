<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\JsonReturn;
use App\Models\Staff;
use App\Models\Location;
use App\Models\Staff_closedDate;
use App\Models\User;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Voucher;
use App\Models\Services;
use App\Models\ServicesPrice;
use App\Models\PaidPlan;
use App\Models\salesSetting;
use App\Models\ServiceCategory;
use App\Models\InventoryProducts;
use DataTables;
use DB;
use Session;
use DateInterval;
use DateTime;
use DatePeriod;
use Carbon;

class VoucherController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
        date_default_timezone_set('Asia/Kolkata');
    }

    public function index()
    {
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
		
		$locationId = Location::select('locations.id', 'locations.location_name')->where('user_id', $AdminId)->orderBy('id', 'ASC')->first();
        $voucher = Voucher::where('isdelete',0)->where('user_id',$AdminId)->where('created_from',0)->get();
		
    	return view('voucher.showVoucher',compact('voucher','locationId'));
    }
	public function getServicedata(Request $request)
	{
		$voucherRow = Voucher::find($request->serviceId);

   		$serviceCategory= $this->getallservices($voucherRow);
	    $content = view('voucher/serviceModal', compact('serviceCategory'))->render();

		return response()->json(['status' => true, 'content' => $content]);
	}

	function getallservices($selectedVoucher)
    {
        $serviceCategory = [];
        if(!empty($selectedVoucher)) {
            if(!empty($selectedVoucher->services_ids)) {
                $service_id_array = explode(',',$selectedVoucher->services_ids);
                $serviceLists = Services::select('services.id','services.service_name','services.service_description', 'services.service_category', 'service_category.category_title')
                                ->leftJoin('service_category', 'service_category.id', 'services.service_category')
                                ->whereIn('services.id', $service_id_array)
                                ->orderBy('services.order_id', 'asc')
                                ->get();

                foreach($serviceLists as $key => $service)
                {
                    $pricearr = array();
                    $servicePrices = ServicesPrice::select('id', 'duration', 'lowest_price', 'price', 'special_price', 'is_staff_price', 'price_type')
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
                            'is_staff_price' => $servprice->is_staff_price,
                            'service_price_amount' => $service_price,
                            'service_price_special_amount' => $service_price_special,
                        );  
                        
                        array_push($pricearr, $tmpArr);

                        if(empty($service_price_special_amount)) {
                            $service_price_special_amount = $service_price_special;
                            $service_price_amount = $service_price;
                            $is_staff_price = $servprice->is_staff_price;
                            $price_type = $servprice->price_type;
                        } elseif( $service_price_special < $service_price_special_amount ) {
                            $service_price_special_amount = $service_price_special;
                            $service_price_amount = $service_price;
                            $is_staff_price = $servprice->is_staff_price;
                            $price_type = $servprice->price_type;
                        }
                    }
                    
                    $min_duration = $this->convertDurationText(min(array_column($pricearr, 'service_price_duration'))); 
                    $max_duration = $this->convertDurationText(max(array_column($pricearr, 'service_price_duration'))); 
                    $service['serviceDuration'] = ($min_duration != $max_duration) ? $min_duration." - ".$max_duration : $min_duration;
                    $service['servicePrice'] = $pricearr;

                    $service['service_price_special_amount'] = $service_price_special;
                    $service['service_price_amount'] = $service_price_amount;
                    $service['is_staff_price'] = $is_staff_price;
                    $service['price_type'] = $price_type;

                    if( !isset($serviceCategory[ $service->service_category ]) ) {
                        $serviceCategory[ $service->service_category ] = [];
                    }

                    $serviceCategory[ $service->service_category ][] = $service;
                }
            }
        }

        return $serviceCategory;
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
    

    public function create()
    {
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
		$locationData = Location::where('user_id',$AdminId)->get()->toArray();
		/*echo "<pre>";
		print_r($locationData);
		exit();*/
		$cat = ServiceCategory::where('is_deleted',0)->where('user_id', $AdminId)->with('service.servicePrice')->get();
		$sales = salesSetting::where('user_id',$AdminId)->first();
    	return view('voucher.add_voucher',compact('cat','locationData','sales'));
    }

    public function createsub(Request $request)
	{
        $rules = [
			'name' => 'required|unique:vouchers',
			'title' => 'required',
			'value' => 'required',
			'retail' => 'required',
			'value_checkbox' => 'required'
		];

		$input = $request->only(
			'name',
			'title',
			'value',
			'retail',
			'value_checkbox'
		);

		$validationMessages = [
			'value.required' => 'Value must be 0.01 or higher',
			'value_checkbox.required' => 'Select at least 1 service.'

		];
		
		$validator = Validator::make($input, $rules, $validationMessages);
		if ($validator->fails()) 
		{
			return JsonReturn::error($validator->messages());
		}

		if($request->value < 0.1) {
			$data["message"] = "Voucher value must be greater than 0.1";
			return JsonReturn::error($data);
		}
		
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
			
		$voucher_type = 0;
		$totalservice = $request->totalservice;
		if($totalservice > count($request->value_checkbox)) {
			$voucher_type = 1;
		}
		
        $insServices = Voucher::create([
			'user_id' => $AdminId,
			'value' => $request->value,
			'title' => $request->title,
			'name' => $request->name,
			'retailprice' => $request->retail,
			'validfor' => $request->validfor,
			'enable_sale_limit' => ($request->enableSalesLimit) ? $request->enableSalesLimit : 0,
			'numberofsales' => $request->enableSalesLimit == 1 ? $request->numberofsales : 0 ,
			'services_ids' => implode(",", $request->value_checkbox),
			'description' => $request->description,
			'color' => $request->color,
			'button' => $request->addbutton=='on' ? 1 : 0 ,
			'is_online' => $request->online=='on' ? 1 : 0 ,
			'note' => $request->note,
			'voucher_type' => $voucher_type,
			'created_from' => 0
		]);

        $data["message"] = "Voucher created succesfully.";
        return JsonReturn::success($data);
    }

    public function sellVoucher($id)
	{
        $voucher = Voucher::where('id',$id)->first();
        $staff = Staff::where('staff_user_id',Auth::id())->with('user')->get();
        return view('voucher.checkout',compact('voucher','staff'));
    }

	public function findItems($id)
	{
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
		
		if($id == 'product')
		{
			$product = InventoryProducts::where('user_id',$AdminId)->where('is_deleted','0')->get();

			$output ='';
			foreach($product as $value) {
				$output .='
				<li type="button" data-dismiss="modal" data-id="'.$value->id.'" data-url="'.route('getitems').'" data-name="product"
				class="d-flex justify-content-between align-items-center text-primary list-group-item list-group-item-action  addNewItems">
					<span>
						<p class="m-0">'.$value->product_name.'</p>
						<p class="m-0">'.$value->initial_stock.' in stock</p>
					</span>
					<span>
						<p class="font-weight-bolder">CA $'.$value->special_rate.'</p>
						<p class="font-weight-bolder"><s>CA $'.$value->retail_price.'</s></p>
					</span>
				</li>
				';
			}
			echo $output;
		}

		if($id == 'service')
		{
			$service = Services::where('user_id',$AdminId)->where('is_deleted','0')->with('servicePrice')->get();

			$output ='';
			foreach($service as $value) {
				$output .='
				<li type="button" data-dismiss="modal" data-id="'.$value->id.'" data-url="'.route('getitems').'" data-name="service"
				class="d-flex justify-content-between align-items-center text-primary list-group-item list-group-item-action  addNewItems">
					<span>
						<p class="m-0">'.$value->service_name.'</p>';
						$output .='<p class="m-0">';
						if($value->servicePrice[0]->duration <= 0) { $output .='00h 00min';}
						else {  if(sprintf("%02d",floor($value->servicePrice[0]->duration / 60)) > 0){$output .= sprintf("%02d",floor($value->servicePrice[0]->duration / 60)).'h ';} if(sprintf("%02d",str_pad(($value->servicePrice[0]->duration % 60), 2, "0", STR_PAD_LEFT)) > 0){$output .= sprintf("%02d",str_pad(($value->servicePrice[0]->duration % 60), 2, "0", STR_PAD_LEFT)). "min";}}
						$output .='</p>';
						$output .='</span>';
						$output .='<span>
						<p class="font-weight-bolder">CA $'.$value->servicePrice[0]->special_price.'</p>
						<p class="font-weight-bolder"><s>CA $'.$value->servicePrice[0]->price.'</s></p>
					</span>
				</li>
				';
			}
			echo $output;
		}

		if($id == 'voucher')
		{
			$voucher = Voucher::where('user_id',$AdminId)->where('isdelete','0')->get();

			$output ='';
			foreach($voucher as $value){
				$output .='
						<div class="card voucher-card 	'.$value->color .' addNewItems" data-id="'.$value->id.'" data-url="'.route('getitems').'" data-name="voucher">
							<div class="card-body text-white p-6">
								<div class="my-3 text-center">
									<h6 class="font-weight-bold">Voucher value</h6>
									<h2 class="font-weight-bolder">CA $'. $value->value .'</h2>
								</div>
								<div
									class="mt-10 font-weight-bold d-flex justify-content-between">
									<div>
										<h5 class="font-weight-bolder">'. $value->name .'</h5>
										<h5>Redeem on '. count(json_decode($value->services_ids, true)) .' services</h5>
									</div>
									<div class="text-right">
										<h6 class="font-weight-bolder">CA $'. $value->retailprice .'</h6>
										<h5
											class="bagde badge-secondary p-1 rounded text-uppercase">
											Save '. round($value->retailprice*100/$value->value) .'%
										</h5>
										<h6 class="font-weight-bolder">Sold '. $value->numberofsales .'</h6>
									</div>
								</div>
							</div>
						</div>';
			}
			echo $output;
		}

		if($id == 'plans')
		{
			$plans = PaidPlan::where('user_id',$AdminId)->where('is_deleted','0')->get();

			$output ='';
			foreach($plans as $value){
				$output .='<div class="card '. $value->color .' addNewItems" data-id="'.$value->id.'" data-url="'.route('getitems').'" data-name="plans">
					<div class="card-body text-white p-6">
						<div class="d-flex my-3">
							<span>
								<i
									class="mr-2 fas fa-business-time icon-lg text-white"></i>
							</span>
							<h6>'. $value->valid_for .' plan</h6>
						</div>
						<h2 class="mb-6 font-weight-bolder">'.$value->name.'</h2>
						<div
							class="font-weight-bold d-flex justify-content-between">
							<div>
								<h5>'. $value->sessions_num .' session</h5>
								<h5>'. count(json_decode($value->services_ids, true)) .' service</h5>
							</div>
							<div>
								<h5>CA $'. $value->price .'</h5>
							</div>
						</div>
					</div>
				</div>';
			}
			echo $output;
		}
	}

	public function getitems(Request $request)
	{
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
		
		if($request->name == 'product')
		{
			$product = InventoryProducts::where('user_id',$AdminId)->where('id',$request->id)->where('is_deleted','0')->first();

			$id = $product->id;
			$name = $product->product_name;
			$use = $product->initial_stock.' in stock';
			$Rprice = $product->special_rate;
			$price = $product->retail_price;
		}

		if($request->name == 'service')
		{
			$service = Services::where('user_id',$AdminId)->where('is_deleted','0')->where('id',$request->id)->with('servicePrice')->first();

			$id = $service->id;
			$name = $service->service_name;
			if($service->servicePrice[0]->duration <= 0) { $use ='00h 00min';}
					else {  if(sprintf("%02d",floor($service->servicePrice[0]->duration / 60)) > 0){$use = sprintf("%02d",floor($service->servicePrice[0]->duration / 60)).'h ';} if(sprintf("%02d",str_pad(($service->servicePrice[0]->duration % 60), 2, "0", STR_PAD_LEFT)) > 0){$use = sprintf("%02d",str_pad(($service->servicePrice[0]->duration % 60), 2, "0", STR_PAD_LEFT)). "min";}}
			$Rprice = $service->servicePrice[0]->special_price;
			$price = $service->servicePrice[0]->price;
		}

		if($request->name == 'voucher'){
			$voucher = Voucher::where('isdelete','0')->where('id',$request->id)->first();

			$id = $voucher->id;
			$name = $voucher->name;
			$use = $voucher->validfor;
			$Rprice = $voucher->retailprice;
			$price = $voucher->value;
		}

		if($request->name == 'plans'){
			$plans = PaidPlan::where('is_deleted','0')->where('id',$request->id)->first();

			$id = $plans->id;
			$name = $plans->name;
			$use = count(json_decode($plans->services_ids, true)) .' service '.$plans->sessions_num .' session '.$plans->valid_for;
			$Rprice = '';
			$price = $plans->price;
		}
        $staff = Staff::where('user_id',$AdminId)->with('user')->get();
		
		$output ='';
			$output .='
				<div class="card addcard">
					<div class="card-body border-left border-primary border-3">
						<div class="row flex-wrap justify-content-between">
							<div class="d-flex">
								<h4 class="m-1 p-4 fonter-weight-bolder">1</h4>
								<div>
									<h3 class="m-0">'. $name .'</h3>
									<p class="text-dark-50">'. $use .'</p>
								</div>
							</div>
							<div class="d-flex flex-wrap">
								<div>';
									if($request->name == 'plans'){$output .='<h3 class="m-0 Tprice">CA $'.$price .'</h3>';} else{$output .='<h3 class="m-0 Tprice">CA $'.$Rprice .'</h3>'; $output .='<h5 class="m-0 text-dark-50"><s>CA $'. $price .'</s></h5>';}
									$output .='
								</div>
								<i class="fa fa-times cursor-pointer text-danger fa-1x mt-2 ml-3"></i>
							</div>
						</div>
						<div class="row px-8">
							<div class="col-md-2 col-sm-6">
								<div class="form-group">
									<label class="font-weight-bolder">Quantity</label>
									<input class="form-control" name="quantity" value="1" type="text">
								</div>
							</div>
							<div class="col-md-3 col-sm-6" id="pricing-type">
								<div class="form-group">
									<label class="font-weight-bolder">Price</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text">CA $</span>
										</div>
										<input type="text"  class="form-control" value="'.$Rprice .'"
											placeholder="0.00">
									</div>
								</div>
							</div>
							<div class="col-md-4 col-sm-6" id="staff">
								<div class="form-group">
									<label class="font-weight-bolder">Staff</label>
									<select class="form-control">';
										foreach($staff as $value){
											$output .='<option value="'. $value->id .'">'. $value->user->first_name .'</option>';
										}
									$output .='</select>
								</div>
							</div>
							<div class="col-md-4 col-sm-6" id="spc-discount">
								<div class="form-group">
									<label class="font-weight-bolder">Discount</label>
									<select class="form-control">
										<option value="no-discount">No Discount</option>
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
			';
		echo $output;
	}

	public function dupVoucher($id)
	{
		$voucher = Voucher::findOrfail($id);
		$newVoucher = $voucher->replicate();
		$newVoucher->save();
		
		$data["message"] = "Voucher created succesfully.";
        return JsonReturn::success($data);
	}

	public function editVoucher($id)
	{
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
		
		$voucher = Voucher::findOrfail($id);
		$locationData = Location::where('user_id',$AdminId)->get()->toArray();
		$cat = ServiceCategory::where('is_deleted',0)->where('user_id', $AdminId)->with('service.servicePrice')->get();
		/*echo "<pre>";
		print_r($cat->count());
		print_r($cat);
		exit();*/
        return view('voucher.edit_voucher',compact('cat','voucher','locationData'));
	}

	public function updateVoucher(Request $request)
	{
		$rules = [
			'name' => 'required',//'required|unique:vouchers,name,'.$request->id,
			'title' => 'required',
			'value' => 'required',
			'retail' => 'required'
		];

		$input = $request->only(
			'name',
			'title',
			'value',
			'retail'
		);
		
		$validator = Validator::make($input, $rules);
		
		if ($validator->fails()) {
			return JsonReturn::error($validator->messages());
		}
		if($request->value < 0.1) {
			$data["message"] = "Voucher value must be greater than 0.1";
			return JsonReturn::error($data);
		}

		$voucher = Voucher::findOrfail($request->id);
		
		$voucher_type = 0;
		$totalservice = $request->totalservice;
		if($totalservice > count($request->value_checkbox)) {
			$voucher_type = 1;
		}
		
		$voucher->update([
			'value' => $request->value,
			'title' => $request->title,
			'name' => $request->name,
			'retailprice' => $request->retail,
			'validfor' => $request->validfor,
			'enable_sale_limit' => ($request->enableSalesLimit) ? $request->enableSalesLimit : 0,
			'numberofsales' => $request->enableSalesLimit == 1 ? $request->numberofsales : 0 ,
			'services_ids' => implode(",", $request->value_checkbox),
			'description' => $request->description,
			'color' => $request->color,
			'button' => $request->addbutton=='on' ? 1 : 0 ,
			'is_online' => $request->online=='on' ? 1 : 0 ,
			'note' => $request->isnote== 'on' ? $request->note : null,
			'voucher_type' => $voucher_type,
			'created_from' => 0
		]);

        $data["message"] = "Voucher Update succesfully.";
        return JsonReturn::success($data);
	}

	public function deleteVoucher($id)
	{
		$voucher = Voucher::findOrfail($id);

		if (!empty($voucher)) 
		{	
			$voucher->update([
				'isdelete' => '1',
			]);

			if ($voucher) {
				$data["status"] = true;
				$data["message"] = "Voucher has been deleted succesfully.";
			} else {
				$data["status"] = false;
				$data["message"] = "Sorry! Unable to delete Voucher.";
			}
		} else {
			$data["status"] = false;
			$data["message"] = "Sorry! Unable to find Voucher.";
		}
		return JsonReturn::success($data);
	}
}