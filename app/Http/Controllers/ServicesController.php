<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\ServiceCategory;
use App\Exports\ServicesExport;
use App\Exports\serviceexport;
use App\Models\ServicesPrice;
use App\Models\StaffServices;
use Illuminate\Http\Request;
use App\Models\JsonReturn;
use App\Models\Services;
use App\Models\Location;
use App\Models\PaidPlan;
use App\Models\User;
use App\Models\Staff;
use App\Models\Taxes;
use DataTables;
use Session;
use Excel;
use PDF;

class ServicesController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }
	
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
		
        $page_title = 'Services';
        $page_description = 'Some description for the page';

        $category = ServiceCategory::where('is_deleted',0)->where('user_id', $AdminId)->orderBy('order_id','ASC')->get();
        $staff = Staff::where('user_id', $AdminId)->where('is_appointment_booked', 1)->with('user')->get();
		
        $location = Location::select('id','location_name','location_address')->where('user_id','=',$AdminId)->get()->toArray();

        $taxes = Taxes::where('user_id', $AdminId)->where('is_deleted', 0)->get();

        return view('services.index', compact('page_title', 'page_description','category','staff','location', 'taxes', 'AdminId'));
    }
	
	public function catorder(Request $request)
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
		
		$category = ServiceCategory::where('is_deleted',0)->where('user_id', $AdminId)->get();

        foreach ($category as $post) {
            foreach ($request->order as $order) {
                if ($order['id'] == $post->id) {
                    $post->update(['order_id' => $order['position']]);
                }
            }
        }

		$data["status"] = "success";
		return JsonReturn::success($data);
	}

	public function serviceorder(Request $request)
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
		
		foreach ($request->serOrder as $order) {
			$service = Services::where('is_deleted',0)->where('user_id', $AdminId)->where('id',$order['id'])->first();
			if ($service) {
				$service->update([
					'order_id' => $order['position'],
					'service_category' => $order['catId']
				]);
				$data["status"] = "success";
			} else {
				$data["status"] = "fails";
			}
		}
		return JsonReturn::success($data);
	}

    public function add()
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
		
        $page_title = 'Add Service';
        $category = ServiceCategory::select('*')->where('user_id', $AdminId)->get();
        $staff = Staff::where('user_id', $AdminId)->where('is_appointment_booked', 1)->get();

        return view('services.add', compact('page_title', 'category', 'staff'));
    }
	
   
	public function ajaxAdd(Request $request)
	{	
		$rules = [
			'service_name' => 'required',
			'treatment_type' => 'required',
			'service_category' => 'required'
		];

		$input = $request->only(
			'service_name',
			'treatment_type',
			'service_category'
		);
		
		$validator = Validator::make($input, $rules);
		if ($validator->fails()) {
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

		$service = Services::where('is_deleted',0)->where('user_id', $AdminId)->where('service_name',$request->service_name)->first();
		/*echo "<pre>";
		print_r($service);
		exit();*/
		if(empty($service))
		{
			
			$voucher_day = null;
			$voucher_mon = null;
			if($request->is_voucher_enable == 1) {
				$voucher_expir = explode("_", $request->voucher_expiry);
				$voucher_day = $voucher_expir[1];
				$voucher_mon = $voucher_expir[0];
			}	
			
			$insServices = Services::create([
				'user_id' => $AdminId,
				'service_name' => $request->service_name,
				'location_id' => (!empty($request->location_id)) ? implode(",",$request->location_id) : '',
				'treatment_type' => $request->treatment_type,
				'service_category' => $request->service_category,
				'service_description' => $request->service_description,
				'available_for' => $request->available_for,
				'is_online_booking' => ($request->is_online_booking) ? 1 : 0,
				'staff_ids' => implode(",", $request->staff_id),
				'is_staff_commision_enable' => ($request->is_staff_commision_enable) ? 1 : 0,
				'is_extra_time' => ($request->is_extra_time) ? 1 : 0,
				'extra_time' => ($request->extra_time) ? 1 : 0,
				'extra_time_duration' => $request->extra_time_duration,
				'tax_id' => $request->tax_id,
				'is_voucher_enable' => ($request->is_voucher_enable) ? 1 : 0,
				'voucher_expiry_day' => $voucher_day,
				'voucher_expiry_month' => $voucher_mon
			]);
			
			$last_service_id = $insServices->id;
			
			$total_price = $request->total_pricing;	
			$selectedStaffId = ($_REQUEST['staff_id']) ? $_REQUEST['staff_id'] : array();
			for($i = 1; $i <= $total_price; $i++)	
			{
				$duration      = $_REQUEST['duration'.$i];
				$price_type    = $_REQUEST['price_type'.$i];
				$price         = ($_REQUEST['price'.$i] > 0) ? $_REQUEST['price'.$i] : 0;
				$special_price = ($_REQUEST['special_price'.$i] > 0) ? $_REQUEST['special_price'.$i] : 0;
				$pricing_name  = ($_REQUEST['pricing_name'.$i] != "") ? $_REQUEST['pricing_name'.$i] : NULL;
				
				
				$staff_data = array();
				
				$is_staff_price = 0; $lowest_price = $prev_lowest_price = 0;
				
				if(isset($_REQUEST['staff_duration'.$i]) && $_REQUEST['staff_duration'.$i] != ''){
					foreach($_REQUEST['staff_duration'.$i] as $key => $val){
						$staff_id            = $_REQUEST['staff_ids'.$i][$key];
						$staff_duration      = $_REQUEST['staff_duration'.$i][$key];
						$staff_price_type    = ($_REQUEST['staff_price_type'.$i][$key] != "") ? $_REQUEST['staff_price_type'.$i][$key] : $price_type;
						$staff_price         = (isset($_REQUEST['staff_price'.$i][$key]) && $_REQUEST['staff_price'.$i][$key] > 0) ? $_REQUEST['staff_price'.$i][$key] : $price;
						$staff_special_price = (isset($_REQUEST['staff_special_price'.$i][$key]) && $_REQUEST['staff_special_price'.$i][$key] > 0) ? $_REQUEST['staff_special_price'.$i][$key] : $special_price;
						
						if($duration != $staff_duration || $price != $staff_price) {
							$is_staff_price = 1;
						} 
						
						if($prev_lowest_price == 0) {
							$prev_lowest_price = $staff_price;
						}	
						
						if($staff_price <= $prev_lowest_price) {
							$lowest_price = $staff_price;
						}	
						
						$tmp_array = array(
							'staff_id' => $staff_id,
							'staff_duration' => $staff_duration,
							'staff_price_type' => $staff_price_type,
							'staff_price' => $staff_price,
							'staff_special_price' => $staff_special_price
						);
						array_push($staff_data, $tmp_array);

						$checkForStaffServices = StaffServices::where('service_id',$last_service_id)->where('staff_user_id',$staff_id)->get()->toArray();
						if(empty($checkForStaffServices)) {
							
							$CurrentStaffService = Staff::where('staff_user_id',$staff_id)->get()->first();
							if(!empty($CurrentStaffService))
							{
								if(in_array($CurrentStaffService->id,$selectedStaffId)){
									$addStaffService = new StaffServices();
									$addStaffService->staff_id      = $CurrentStaffService->id;
									$addStaffService->staff_user_id = $staff_id;
									$addStaffService->service_id    = $last_service_id;
									$addStaffService->status        = 1;
									$addStaffService->created_at    = date("Y-m-d H:i:s");
									$addStaffService->updated_at    = date("Y-m-d H:i:s");
									$addStaffService->save();
								}
							}
						}		
					}	
				}
				
				if($price < $lowest_price){
					$lowest_price = $price;
				}
				if($special_price != '' && $special_price < $lowest_price){
					$lowest_price = $special_price;
				}
				
				$insServicePrice = ServicesPrice::create([
					'user_id' => $AdminId,
					'service_id' => $last_service_id,
					'duration' => $duration,
					'price_type' => $price_type,
					'price' => $price,
					'special_price' => $special_price,
					'pricing_name' => $pricing_name,
					'is_staff_price' => $is_staff_price,
					'lowest_price' => $lowest_price,
					'staff_prices' => (!empty($staff_data)) ? json_encode($staff_data) : ''
				]);
			}
			
			$data["message"] = "Services user created succesfully.";
			$data["redirect"] = url("partners/service");
			return JsonReturn::success($data);
		}
		else
		{
			$data["message"] = "Service is already exists.";
			return JsonReturn::error($data);
		}	
	}

	public function getservice($id)
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
		
		$service = Services::where('id', $id)->where('user_id', $AdminId)->first();
		$taxes = Taxes::where('is_deleted', 0)->get();
		
		if(!empty($service)) {
			$category = ServiceCategory::where('is_deleted',0)->get();
			$staff = Staff::where('user_id', $AdminId)->where('is_appointment_booked', 1)->with('user')->get();
			$location = Location::select('id','location_name','location_address')->where('is_deleted',0)->where('user_id','=',$AdminId)->get()->toArray();
			$servicPrice = ServicesPrice::where('user_id', $AdminId)->where('service_id',$id)->get();
			// return JsonReturn::success($data);
			return view('services.edit_single_service',compact('service','category','staff','location','servicPrice', 'taxes'));
		} else {
			return redirect(route('service'));
		}		
	}

	public function editservice(Request $request){
		
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
		
		$service = Services::where('id', $request->id)->first();
		
		// $servicPrice = ServicesPrice::where('user_id', $AdminId)->where('service_id',$request->id);
		// $servicPrice->delete();

		$rules = [
			// 'service_name' => 'required|unique:services,service_name,'.$request->id,
			'service_name' => ['required',Rule::unique('services')
								->where(function ($query) use ($request, $AdminId){
								return $query->where('is_deleted', 0)
											->where('user_id', $AdminId)
											->where('id', '!=', $request->id);
								})],
			'treatment_type' => 'required',
			'service_category' => 'required'
		];

		$input = $request->only(
			'service_name',
			'treatment_type',
			'service_category'
		);
		
		$validator = Validator::make($input, $rules);
		if ($validator->fails()) {
			return JsonReturn::error($validator->messages());
		}
		
		$voucher_day = 0;
		$voucher_mon = 0;
		if($request->is_voucher_enable == 1) {
			$voucher_expir = explode("_", $request->voucher_expiry);
			$voucher_day = $voucher_expir[1];
			$voucher_mon = $voucher_expir[0];
		}	
		
		$service->update([
			'user_id' => $AdminId,
			'service_name' => $request->service_name,
			'location_id' => (!empty($request->location_id)) ? implode(",",$request->location_id) : '',
			'treatment_type' => $request->treatment_type,
			'service_category' => $request->service_category,
			'service_description' => $request->service_description,
			'available_for' => $request->available_for,
			'is_online_booking' => ($request->is_online_booking) ? 1 : 0,
			'staff_ids' => implode(",", $request->staff_id),
			'is_staff_commision_enable' => ($request->is_staff_commision_enable) ? 1 : 0,
			'is_extra_time' => ($request->is_extra_time) ? 1 : 0,
			'extra_time' => ($request->extra_time) ? 1 : 0,
			'extra_time_duration' => $request->extra_time_duration,
			'tax_id' => $request->tax_id,
			'is_voucher_enable' => ($request->is_voucher_enable) ? 1 : 0,
			'voucher_expiry_day' => $voucher_day,
			'voucher_expiry_month' => $voucher_mon
		]);
		
		$last_service_id = $request->id;
		
		$selectedStaffId = ($_REQUEST['staff_id']) ? $_REQUEST['staff_id'] : array();
		
		$total_price = $request->total_pricing;	
		for($i = 1; $i <= $total_price; $i++)	
		{
			$servicePriceId = (isset($_REQUEST['servicePriceId'.$i])) ? $_REQUEST['servicePriceId'.$i] : '';
			
			if($servicePriceId != '') {
				
				$ServicesPrice = ServicesPrice::find($servicePriceId);
				
				if(!empty($ServicesPrice))
				{
					$duration       = $_REQUEST['duration'.$i];
					$price_type     = $_REQUEST['price_type'.$i];
					$price          = ($_REQUEST['price'.$i] > 0) ? $_REQUEST['price'.$i] : 0;
					$special_price  = ($_REQUEST['special_price'.$i] > 0) ? $_REQUEST['special_price'.$i] : NULL;
					$pricing_name   = ($_REQUEST['pricing_name'.$i] != "") ? $_REQUEST['pricing_name'.$i] : NULL;
					
					$staff_data = array();
					$is_staff_price = 0; $lowest_price = $prev_lowest_price = 0;
					foreach($_REQUEST['staff_duration'.$i] as $key => $val)
					{
						$staff_id = $_REQUEST['staff_ids'.$i][$key];
						$staff_duration = $_REQUEST['staff_duration'.$i][$key];
						$staff_price_type = ($_REQUEST['staff_price_type'.$i][$key] != "") ? $_REQUEST['staff_price_type'.$i][$key] : $price_type;

						if($staff_price_type == 'free') {
							$staff_price = '';
							$staff_special_price = '';
						} else {
							$staff_price = ($_REQUEST['staff_price'.$i][$key] > 0) ? $_REQUEST['staff_price'.$i][$key] : $price;
							$staff_special_price = ($_REQUEST['staff_special_price'.$i][$key] > 0) ? $_REQUEST['staff_special_price'.$i][$key] : $special_price;
						}
							
						if($duration != $staff_duration || $price != $staff_price) {
							$is_staff_price = 1;
						} 
						
						if($prev_lowest_price == 0) {
							$prev_lowest_price = $staff_price;
						}	
						
						if($staff_price != '' && $staff_price <= $prev_lowest_price) {
							$lowest_price = $staff_price;
						}		
						if($staff_special_price != '' && $staff_special_price <= $prev_lowest_price) {
							$lowest_price = $staff_special_price;
						}		
						
						$tmp_array = array(
							'staff_id' => $staff_id,
							'staff_duration' => $staff_duration,
							'staff_price_type' => $staff_price_type,
							'staff_price' => $staff_price,
							'staff_special_price' => $staff_special_price
						);
						
						array_push($staff_data, $tmp_array);
						
						$checkForStaffServices = StaffServices::where('service_id',$last_service_id)->where('staff_id',$staff_id)->get()->toArray();
						
						if(empty($checkForStaffServices)) {
							
							$CurrentStaffService = Staff::where('id',$staff_id)->get()->first();
							
							if(in_array($CurrentStaffService->id,$selectedStaffId)){
								$addStaffService = new StaffServices();
								$addStaffService->staff_id      = $CurrentStaffService->id;
								$addStaffService->staff_user_id = $CurrentStaffService->staff_user_id;
								$addStaffService->service_id    = $last_service_id;
								$addStaffService->status        = 1;
								$addStaffService->created_at    = date("Y-m-d H:i:s");
								$addStaffService->updated_at    = date("Y-m-d H:i:s");
								$addStaffService->save();
							}
						} else {
							$CurrentStaffService = Staff::where('id',$staff_id)->get()->first();
							
							$StaffServices = StaffServices::find($checkForStaffServices[0]['id']);
							
							if(in_array($CurrentStaffService->id,$selectedStaffId)){
								$StaffServices->staff_id      = $CurrentStaffService->id;
								$StaffServices->staff_user_id = $CurrentStaffService->staff_user_id;
								$StaffServices->service_id    = $last_service_id;
								$StaffServices->status        = 1;
								$StaffServices->updated_at    = date("Y-m-d H:i:s");
								$StaffServices->save();
							} else if(!empty($StaffServices)) {
								$StaffServices->status        = 0;
								$StaffServices->updated_at    = date("Y-m-d H:i:s");
								$StaffServices->save();
							}
						}
					}	
					
					if($price < $lowest_price){
						$lowest_price = $price;
					}
					if($special_price != '' && !is_null($special_price) && $special_price < $lowest_price){
						$lowest_price = $special_price;
					}
					
					if(trim($lowest_price) == '') {
						$lowest_price = 0;
					}
					
					$ServicesPrice->duration       = $duration;
					$ServicesPrice->price_type     = $price_type;
					$ServicesPrice->price          = $price;
					$ServicesPrice->special_price  = $special_price;
					$ServicesPrice->pricing_name   = $pricing_name;
					$ServicesPrice->is_staff_price = $is_staff_price;
					$ServicesPrice->lowest_price   = $lowest_price;
					$ServicesPrice->staff_prices   = (!empty($staff_data)) ? json_encode($staff_data) : '';
					$ServicesPrice->save();	
				}
			} else {
				$duration       = $_REQUEST['duration'.$i];
				$price_type     = $_REQUEST['price_type'.$i];
				$price          = ($_REQUEST['price'.$i] > 0) ? $_REQUEST['price'.$i] : 0;
				$special_price  = ($_REQUEST['special_price'.$i] > 0) ? $_REQUEST['special_price'.$i] : NULL;
				$pricing_name   = ($_REQUEST['pricing_name'.$i] != "") ? $_REQUEST['pricing_name'.$i] : NULL;
				
				$staff_data = array();
				$is_staff_price = 0; $lowest_price = $prev_lowest_price = 0;
				foreach($_REQUEST['staff_duration'.$i] as $key => $val)
				{
					$staff_id = $_REQUEST['staff_ids'.$i][$key];
					$staff_duration = $_REQUEST['staff_duration'.$i][$key];
					$staff_price_type = ($_REQUEST['staff_price_type'.$i][$key] != "") ? $_REQUEST['staff_price_type'.$i][$key] : $price_type;


					if($staff_price_type == 'free') {
						$staff_price = '';
						$staff_special_price = '';
					} else {
						$staff_price = ($_REQUEST['staff_price'.$i][$key] > 0) ? $_REQUEST['staff_price'.$i][$key] : $price;
						$staff_special_price = ($_REQUEST['staff_special_price'.$i][$key] > 0) ? $_REQUEST['staff_special_price'.$i][$key] : $special_price;
					}
						
					if($duration != $staff_duration || $price != $staff_price) {
						$is_staff_price = 1;
					} 
					
					if($prev_lowest_price == 0) {
						$prev_lowest_price = $staff_price;
					}	
					
					if($staff_price <= $prev_lowest_price) {
						$lowest_price = $staff_price;
					}	

					if(trim($lowest_price) == '') {
						$lowest_price = 0;
					}
					
					$tmp_array = array(
						'staff_id' => $staff_id,
						'staff_duration' => $staff_duration,
						'staff_price_type' => $staff_price_type,
						'staff_price' => $staff_price,
						'staff_special_price' => $staff_special_price
					);
					
					array_push($staff_data, $tmp_array);
					
					$checkForStaffServices = StaffServices::where('service_id',$last_service_id)->where('staff_id',$staff_id)->get()->toArray();
					
					if(empty($checkForStaffServices)) {
						
						$CurrentStaffService = Staff::where('id',$staff_id)->get()->first();
						
						if(in_array($CurrentStaffService->id,$selectedStaffId)){
							$addStaffService = new StaffServices();
							$addStaffService->staff_id      = $CurrentStaffService->id;
							$addStaffService->staff_user_id = $CurrentStaffService->staff_user_id;
							$addStaffService->service_id    = $last_service_id;
							$addStaffService->status        = 1;
							$addStaffService->created_at    = date("Y-m-d H:i:s");
							$addStaffService->updated_at    = date("Y-m-d H:i:s");
							$addStaffService->save();
						}
					} else {
						$CurrentStaffService = Staff::where('id',$staff_id)->get()->first();
						
						$StaffServices = StaffServices::find($checkForStaffServices[0]['id']);
						
						if(in_array($CurrentStaffService->id,$selectedStaffId)){
							$StaffServices->staff_id      = $CurrentStaffService->id;
							$StaffServices->staff_user_id = $CurrentStaffService->staff_user_id;
							$StaffServices->service_id    = $last_service_id;
							$StaffServices->status        = 1;
							$StaffServices->updated_at    = date("Y-m-d H:i:s");
							$StaffServices->save();
						} else if(!empty($StaffServices)) {
							$StaffServices->status        = 0;
							$StaffServices->updated_at    = date("Y-m-d H:i:s");
							$StaffServices->save();
						}
					}
				}	
				
				if($price < $lowest_price){
					$lowest_price = $price;
				}
				if($special_price != '' && !is_null($special_price) && $special_price < $lowest_price){
					$lowest_price = $special_price;
				}

				if(trim($lowest_price) == '') {
					$lowest_price = 0;
				}
				
				$insServicePrice = ServicesPrice::create([
					'user_id' => $AdminId,
					'service_id' => $last_service_id,
					'duration' => $duration,
					'price_type' => $price_type,
					'price' => $price,
					'special_price' => $special_price,
					'pricing_name' => $pricing_name,
					'is_staff_price' => $is_staff_price,
					'lowest_price' => $lowest_price,
					'staff_prices' => (!empty($staff_data)) ? json_encode($staff_data) : ''
				]);
			}
		}
		
		$data["message"] = "Services update succesfully.";
		$data["redirect"] = url("partners/service");
		return JsonReturn::success($data);

	}
 
	public function deleteServicePrice(Request $request)
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
	
			$rules = [
				'deleteServicePriceId'    => 'required'
			];
			
            $input = $request->only(
				'deleteServicePriceId'
            );

            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return JsonReturn::error($validator->messages());
            }
			
			$ServicesPrice = ServicesPrice::find($request->deleteServicePriceId);
			
			if(!empty($ServicesPrice)){
				if($ServicesPrice->delete()){
					Session::flash('message', 'Service price deleted succesfully.');
					$data["status"] = true;
					$data["message"] = "Service price deleted succesfully.";	
				} else {
					$data["status"] = false;
					$data["message"] = array("Sorry somethig went wrong.");
				}
			} else {
				$data["status"] = false;
				$data["message"] = array("Sorry somethig went wrong.");
			}
			
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

	public function deleteService($id)
	{
		$PaidPlan = Services::where('id',$id)->first();

		if (!empty($PaidPlan)) {
			
			$PaidPlan->update([
				'is_deleted' => '1',
			]);

			if ($PaidPlan) {
				$data["status"] = true;
				$data["message"] = "Service has been deleted succesfully.";
			} else {
				$data["status"] = false;
				$data["message"] = "Sorry! Unable to delete Service.";
			}
		} else {
			$data["status"] = false;
			$data["message"] = "Sorry! Unable to find Service.";
		}
		$data["redirect"] = route('service');
		return JsonReturn::success($data);

	}

	public function addcat(Request $request){

		// return $request->all();

		// $rules = [
		// 	'appointment_color' => 'required',
		// 	'category_title' => 'required|unique:service_category',
		// ];
		
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

		$rules = [
			'appointment_color' => 'required',
			'category_title' => ['required',Rule::unique('service_category')
								->where(function ($query) use ($request, $AdminId){
								return $query->where('is_deleted', 0)
											->where('user_id', $AdminId);
								})],
		];

		$input = $request->only(
			'appointment_color',
			'category_title'
		);
		
		$validator = Validator::make($input, $rules);
		if ($validator->fails()) {
			return JsonReturn::error($validator->messages());
		}
		

		$category = ServiceCategory::create([
			'user_id' => $AdminId,
			'category_description' => $request->category_description,
			'appointment_color' => $request->appointment_color,
			'category_title' => $request->category_title,
		]);

		$data["message"] = "Category created succesfully.";
		$data["redirect"] = url("partners/service");
		return JsonReturn::success($data);
	}
	
	public function getcat($id){

		$cat = ServiceCategory::where('id',$id)->first();
		$data["cat"] = $cat;

		return JsonReturn::success($data);
	}

	public function editcat(Request $request)
	{	
		if($request->ajax())
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

			$rules = [
				'appointment_color' => 'required',
				// 'category_title' => 'required|unique:service_category,category_title,'.$request->catid,
				'category_title' => ['required',Rule::unique('service_category')
								->where(function ($query) use ($request, $AdminId){
								return $query->where('is_deleted', 0)
											->where('user_id', $AdminId)
											->where('id', '!=', $request->catid);
								})],
			];
	
			$input = $request->only(
				'appointment_color',
				'category_title'
			);
			
			$validator = Validator::make($input, $rules);
			if ($validator->fails()) {
				return JsonReturn::error($validator->messages());
			}

			$cat = ServiceCategory::where('id',$request->catid)->first();
	
			$cat->update([
				'user_id' => $AdminId,
				'category_description' => $request->category_description,
				'appointment_color' => $request->appointment_color,
				'category_title' => $request->category_title,
			]);
	
			$data["message"] = "Category update succesfully.";
			$data["redirect"] = url("partners/service");
			return JsonReturn::success($data);
		}
	}

	public function deletecat($id)
	{
		$cat = ServiceCategory::where('id',$id)->first();

		$cat_services = Services::where('is_deleted',0)->where('service_category', '=', $id)->count();

		if($cat_services > 0) {

			$data["status"] = false;
			$data["message"] = "Remove existing services first to be able to delete a group.";
			return JsonReturn::success($data);
		}

		if (!empty($cat)) 
		{
			$cat->update([
				'is_deleted' => '1',
			]);

			if ($cat) {
				$data["status"] = true;
				$data["message"] = "Category has been deleted succesfully.";
			} else {
				$data["status"] = false;
				$data["message"] = "Sorry! Unable to delete Location.";
			}
		} else {
			$data["status"] = false;
			$data["message"] = "Sorry! Unable to find Category.";
		}
		return JsonReturn::success($data);
	}

	public function getStaffList(Request $request)
	{
		$Staff =  Staff::orderBy('id', 'DESC')->get();
		
		$data = array();
		
		foreach($Services as $ServicesDetail){
			$tempData = array(
				'',
				$ServicesDetail->firstname.' '.$ServicesDetail->lastname,
				$ServicesDetail->mobileno,
				$ServicesDetail->email,
				$ServicesDetail->email,
				$ServicesDetail->email,
				$ServicesDetail->email
			);
			
			array_push($data,$tempData);
		}
		
		$json_data = array(
			"draw"            => intval( $request->draw ),
			"recordsTotal"    => intval( count($data) ),
			"recordsFiltered" => intval( count($data) ),
			"data"            => $data
		);
		return JsonReturn::success($json_data);	
	}

    public function orders()
    {
		$page_title = 'Services';
		$page_description = 'Some description for the page';

		return view('services.orders', compact('page_title', 'page_description'));
	}

	public function plans()
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
		
		$plan = PaidPlan::where('is_deleted',0)->where('user_id',$AdminId)->get();
		return view('services.services_paidplans',compact('plan'));
	}

	public function addPlans()
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
		
		$cat = ServiceCategory::where('is_deleted',0)->where('user_id', $AdminId)->with('service.servicePrice')->get();
		
		$tax_arr = array();
		$taxes = Taxes::where('is_deleted',0)->where('user_id', $AdminId)->get();
		
		foreach($taxes as $key => $val) {
			if($val->is_group == 1) {
				$tax_ids = explode(",", str_replace(" ", "", $val->tax_rates));
				$tax_nm = Taxes::select('taxes.id','taxes.tax_name','taxes.tax_rates')->whereIn('id', $tax_ids)->get();
				$rates = "";		
				foreach($tax_nm as $tax) {
					$rates .= $tax->tax_rates."%, ";
				}	
				$rates = rtrim($rates, ", ");
				$tmp_arr = array(
					'id' => $val->id,
					'title' => $val->tax_name." (".$rates.")"
				);
				array_push($tax_arr, $tmp_arr);
			} else {	
				$tmp_arr = array(
					'id' => $val->id,
					'title' => $val->tax_name." ".$val->tax_rates
				);	
				array_push($tax_arr, $tmp_arr);
			}	
		}
		
		return view('services.add_paidplan',compact('cat','tax_arr'));
	}

	public function savePlans(Request $request)
	{
		if($request->ajax())
		{
			$rules = [
				'name' => 'required',
				'sessions' => 'required',
				'price' => 'required',
				'valid_for' => 'required',
				'tax' => 'required',
				'color' => 'required',
				// 'online_sales' => 'required',
			];
	
			$input = $request->only(
				'name',
				'sessions',
				'price',
				'valid_for',
				'tax',
				'color',
				// 'online_sales',
			);
			
			$validator = Validator::make($input, $rules);
			if ($validator->fails()) {
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
			
			PaidPlan::create([
				'user_id' => $AdminId,
				'name' => $request->name,
				'description' => $request->description,
				'services_ids' => implode(",", $request->value_checkbox),
				'sessions' => $request->sessions,
				'sessions_num' => ($request->sessions_num > 0) ? $request->sessions_num : NULL,
				'price' => $request->price,
				'valid_for' => $request->valid_for,
				'tax' => $request->tax,
				'color' => $request->color,
				'online_sales' => $request->online_sales =='on' ? 1 : 0,
			]);
	
			$data["message"] = "Paid Plan created succesfully.";
			$data["redirect"] = route("plans");
			return JsonReturn::success($data);
		}
	}

	public function editPlans($id)
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
		
		$plan = PaidPlan::findOrfail($id);
		$cat = ServiceCategory::where('user_id', $AdminId)->with('service.servicePrice')->get();
		$serviceCategory = array();
        $serviceCategory = ServiceCategory::where(['user_id' => $AdminId, 'is_deleted' => 0])->get()->toArray();
        foreach ($serviceCategory as $key => $serviceCat) 
        {
            $services = Services::where(['service_category'=>$serviceCat['id'],'is_deleted'=>0])->get()->toArray();
            foreach ($services as $serviceKey => $service)
            {
                $servicesPrice = ServicesPrice::where(['service_id'=>$service['id']])->get()->toArray();
                $services[$serviceKey]['service_price'] = $servicesPrice;
            }
            $serviceCategory[$key]['service'] = $services;
        }
		
		$tax_arr = array();
		$taxes = Taxes::where('is_deleted',0)->where('user_id', $AdminId)->get();
		
		foreach($taxes as $key => $val) {
			if($val->is_group == 1) {
				$tax_ids = explode(",", str_replace(" ", "", $val->tax_rates));
				$tax_nm = Taxes::select('taxes.id','taxes.tax_name','taxes.tax_rates')->whereIn('id', $tax_ids)->get();
				$rates = "";		
				foreach($tax_nm as $tax) {
					$rates .= $tax->tax_rates."%, ";
				}	
				$rates = rtrim($rates, ", ");
				$tmp_arr = array(
					'id' => $val->id,
					'title' => $val->tax_name." (".$rates.")"
				);
				array_push($tax_arr, $tmp_arr);
			} else {	
				$tmp_arr = array(
					'id' => $val->id,
					'title' => $val->tax_name." ".$val->tax_rates
				);	
				array_push($tax_arr, $tmp_arr);
			}	
		}
		
		return view('services.edit_paidplan',compact('plan','cat', 'serviceCategory', 'tax_arr'));
	}


	public function updatePlans(Request $request)
	{
		if($request->ajax())
		{
			$rules = [
				'name' => 'required',
				'sessions' => 'required',
				'price' => 'required',
				'valid_for' => 'required',
				'tax' => 'required',
				'color' => 'required',
			];
	
			$input = $request->only(
				'name',
				'sessions',
				'price',
				'valid_for',
				'tax',
				'color',
			);
			
			$validator = Validator::make($input, $rules);
			if ($validator->fails()) {
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
			
			
			$plan = PaidPlan::findOrfail($request->id);
			$plan->update([
				'name' => $request->name,
				'description' => $request->description,
				'services_ids' => implode(",", $request->value_checkbox),
				'sessions' => $request->sessions,
				'sessions_num' => ($request->sessions_num > 0) ? $request->sessions_num : NULL,
				'price' => $request->price,
				'valid_for' => $request->valid_for,
				'tax' => $request->tax,
				'color' => $request->color,
				'online_sales' => $request->online_sales =='on' ? 1 : 0,
			]);
	
			$data["message"] = "Paid Plan Update succesfully.";
			$data["redirect"] = route('plans');
			return JsonReturn::success($data);
		}
	}

	public function deletePlans($id){

		$PaidPlan = PaidPlan::where('id',$id)->first();

		if (!empty($PaidPlan)) {
			
			$PaidPlan->update([
				'is_deleted' => '1',
			]);

			if ($PaidPlan) {
				$data["status"] = true;
				$data["message"] = "Paid Plan has been deleted succesfully.";
			} else {
				$data["status"] = false;
				$data["message"] = "Sorry! Unable to delete Paid Plan.";
			}
		} else {
			$data["status"] = false;
			$data["message"] = "Sorry! Unable to find Paid Plan.";
		}
		return JsonReturn::success($data);

	}

	public function serviceinfoexcel(){

        return Excel::download(new ServicesExport(), 'Services.xls');
    }

	public function serviceinfocsv(){

        return Excel::download(new ServicesExport(), 'Services.csv');
    }

	public function serviceinfopdf()
	{
        $CurrentUser = auth::user();
        $is_admin = $CurrentUser->is_admin;
        
        if($is_admin == 1){
            $CurrentStaff = Staff::find(Auth::id());
            $AdminId = $CurrentStaff->user_id;
            $UserId  = Auth::id();
        } else {
            $AdminId = Auth::id();
            $UserId  = Auth::id();
        }
        
        $ServicesPrice = ServicesPrice::where('services_price.user_id', $AdminId)
                        ->where('services_price.deleted_at', NULL)
                        ->leftjoin('services', 'services.id', 'services_price.service_id')
                        ->leftjoin('service_category', 'service_category.id', 'services.service_category')
                        ->leftjoin('taxes', 'taxes.id', 'services.tax_id', 'taxes.id')
                        ->select('services.service_name', 'services_price.price', 'services_price.special_price', 'services_price.duration', 'services.is_extra_time', 'services.extra_time_duration', 'taxes.tax_name', 'services.service_description', 'service_category.category_title', 'services.treatment_type', 'services.is_online_booking', 'services.available_for', 'services.is_voucher_enable', 'services.is_staff_commision_enable', 'services.id')
                        ->get();
		
		$FileName = 'services.pdf';

		return PDF::loadView('pdfTemplates.servicesPdfReport',['ServicesPrice' => $ServicesPrice])->setPaper('a3')->download($FileName);
    }
}
