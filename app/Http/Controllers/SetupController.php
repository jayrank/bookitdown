<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\JsonReturn;
use App\Models\Staff;
use App\Models\Location;
use App\Models\Resources;
use App\Models\Businesstype;
use App\Models\Location_business_type;
use App\Models\Staff_closedDate;
use App\Models\User;
use App\Models\Permission;
use App\Models\Role;
use App\Models\AccountSetting;
use App\Models\InvoiceTemplate;
use App\Models\InvoiceSequencing;
use App\Models\Taxes;
use App\Models\LocTax;
use App\Models\taxFormula;
use App\Models\paymentType;
use App\Models\Discount;
use App\Models\salesSetting;
use App\Models\referralSources;
use App\Models\CancellationReasons;
use DataTables;
use DB;
use Session;

class SetupController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('setup.index_');
    }
	
    public function get_location()
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
		
        $data = Location::select('locations.*')->where('locations.user_id', '=', $AdminId)->where('is_deleted','0')->orderBy('position','asc')->get();
        return view('setup.locations', compact('data'));
    }
	
    public function add_location()
    {
        $main = Businesstype::select('business_type.*')->get();
        return view('setup.add_location', compact('main'));
    }
	
    public function location_detail($id)
    { 
        $row = Location::select('locations.*')->where('locations.id', '=', $id)->first();
        $sec = Businesstype::select('business_type.name')->join('location_business_type', 'business_type.id', '=', 'location_business_type.business_type_id')->where('location_business_type.location_id', '=', $id)->where('location_business_type.is_main_business_type', '=', '0')->get();
		
        $main = Businesstype::select('business_type.name', 'location_business_type.is_main_business_type')->join('location_business_type', 'business_type.id', '=', 'location_business_type.business_type_id')->where('location_business_type.location_id', '=', $id)->where('location_business_type.is_main_business_type', '=', '1')->get();

        return view('setup.location_detail', compact('row', 'sec', 'main'));
    }
	
    public function addlocation(Request $request)
    {
        $rules =
		[
			'location_name' => 'required',
			'country_code' => 'required',
			'location_phone' => 'required',//|regex:/^\d{3}-\d{3}-\d{4}$/
			'location_email' => 'required|email',
			'main_business' => 'required'
		];
		
        $input = $request->only(
            'location_name',
            'country_code',
            'location_phone',
            'location_email',
            'main_business'
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
		
        $loc = Location::create([
            'id'                    => $request->id,
            'user_id'               => $AdminId,
            'location_name'         => $request->location_name,
            'country_code'          => $request->country_code,
            'location_phone'        => $request->location_phone,
            'location_email'        => $request->location_email,
            'location_address'      => $request->location_address,
            'location_latitude'     => $request->lat,
            'location_longitude'    => $request->lng,
            'no_business_address'   => ($request->no_business_address) ? 1 : 0,
            'loc_address'      		=> $request->loc_address,
            'loc_apt'      			=> $request->loc_apt,
            'loc_district'      	=> $request->loc_district,
            'loc_city'      		=> $request->loc_city,
            'loc_region'      		=> $request->loc_region,
            'loc_county'      		=> $request->loc_county,
            'loc_postcode'      	=> $request->loc_postcode,
            'loc_country'      		=> $request->loc_country,
            'is_same_billing_addr'  => $request->is_same_billing_addr,
            'billing_company_name'  => $request->billing_company_name,
            'billing_address'      	=> $request->billing_address,
            'billing_apt'      		=> $request->billing_apt,
            'billing_city'      	=> $request->billing_city,
            'billing_region'      	=> $request->billing_region,
            'billing_postcode'      => $request->billing_postcode,
            'billing_notes'      	=> $request->billing_notes
        ]);

        $main_bus = Businesstype::select('business_type.id')->where('business_type.name', '=', $request->main_business)->get();

        $main = Location_business_type::create([
            'user_id'               => $AdminId,
            'location_id'           => $loc->id,
            'business_type_id'      => $main_bus[0]->id,
            'is_main_business_type' => '1'
        ]);
		
        if(isset($request->secondary_business))
		{
            $secondary_bus = new Location_business_type;
            foreach ($request->get('secondary_business') as $secondary_bus) 
			{
                $sec_bus[] = [
					'user_id'                       => $AdminId,
					'location_id'                   => $loc->id,
					'business_type_id'              => $secondary_bus,
					'is_main_business_type'         => '0',
					'created_at'                    => date("Y-m-d H:i:s"),
					'updated_at'                    => date("Y-m-d H:i:s")
				];
            }
            $sec_bus_type = Location_business_type::insert($sec_bus);
        }
        
        $data["message"] = "Location has been Added succesfully.";
        $data["status"] = true;
        $data["redirect"] = route('get_location');
        return JsonReturn::success($data);
    }
	
    public function editlocation(Request $request,$id)
    {
        if ($request->ajax()) 
        {
            $location = Location::find($request->editlocationID);
			$location->no_business_address = ($request->no_business_address) ? $request->no_business_address : 0;
			
			if($location->no_business_address == 1)
			{
				$location->location_address = NULL;
				$location->location_latitude = NULL;
				$location->location_longitude = NULL;
				$location->loc_address = NULL;
				$location->loc_apt = NULL;
				$location->loc_district = NULL;
				$location->loc_city = NULL;
				$location->loc_region = NULL;
				$location->loc_county = NULL;
				$location->loc_postcode = NULL;
				$location->loc_country = NULL;
			} else {
				
				$location->location_address = $request->location_address;
				$location->location_latitude = $request->lat;
				$location->location_longitude = $request->lng;
				$location->loc_address = $request->loc_address;
				$location->loc_apt = $request->loc_apt;
				$location->loc_district = $request->loc_district;
				$location->loc_city = $request->loc_city;
				$location->loc_region = $request->loc_region;
				$location->loc_county = $request->loc_county;
				$location->loc_postcode = $request->loc_postcode;
				$location->loc_country = $request->loc_country;
			}		
			
            $location->is_same_billing_addr = $request->is_same_billing_addr;
            $location->billing_company_name = $request->billing_company_name;
            $location->billing_address = $request->billing_address;
            $location->billing_apt = $request->billing_apt;
            $location->billing_city = $request->billing_city;
            $location->billing_region = $request->billing_region;
            $location->billing_postcode = $request->billing_postcode;
            $location->billing_notes = $request->billing_notes;
            
            if($location->save())
            {
                $data["message"] = "Location has been updated succesfully.";
                $data["status"] = true;
                $data["redirect"] = route('location_detail', $request->editlocationID);
                return JsonReturn::success($data);
            }
            else
            {
                $data["status"] = true;
                $data["message"] = array("Something went wrong.");
                $data["redirect"] = route('location_detail', $request->editlocationID);
                return JsonReturn::success($data);
            }
        } 
        else 
        {
            $locationData = Location::select('locations.*')->where('locations.id', '=', $id)->first();
            
            return view('setup.edit_location', compact('locationData'));
        }
    }
	
	public function locationOrder()
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
		
		$Locations = Location::select('*')->where('user_id', '=', $AdminId)->orderBy('position','asc')->get()->toArray();
		
		return view('setup.changeLocationOrder',compact('Locations'));
	}
	
	public function updateLocationOrder(Request $request)
	{
		if ($request->isMethod('post')) 
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
			
			if(!empty($request->location_id)){
				foreach($request->location_id as $key => $locationId){
					$Locations = Location::find($locationId);
					
					$Locations->position = $key;
					$Locations->updated_at = date("Y-m-d H:i:s");
					$Locations->save();
				}
				
				Session::flash('message', 'Location order updated succesfully.');
				return redirect()->route('get_location');
			}
			
			Session::flash('error', 'Something went wrong.');
			return redirect()->route('get_location');
		}
	}
	
    public function editbusinesstypes(Request $request,$id)
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
			
			$location_businesstype_main = Location_business_type::where('location_id', '=', $request->editlocationID)->where('is_main_business_type','=','1')->first();
            $main_bus = Businesstype::select('business_type.id')->where('business_type.name', '=', $request->main_business)->first();
            $location_businesstype_main->business_type_id = ($main_bus->id) ? $main_bus->id : '';
            
			$sub_business_type = array();
            $location_business_type_sub = Location_business_type::where('location_id', $request->editlocationID)->where('is_main_business_type',0)->get();
			
            if(!empty($location_business_type_sub))
			{
                foreach($location_business_type_sub as $location_business_type_sub_data){
                    $sub_business_type[] = $location_business_type_sub_data->business_type_id;
                }
            }
            
            $postRequestSubBusinessTypeId = $request->secondary_business;
            
			if(!empty($postRequestSubBusinessTypeId))
			{
				foreach($sub_business_type as $sub_business_type_id_value)
				{
					if(!in_array($sub_business_type_id_value,$postRequestSubBusinessTypeId)){
						$deletedata = Location_business_type::where('business_type_id', $sub_business_type_id_value)->where('is_main_business_type',0)->delete();
					}	
				}
				
				foreach($postRequestSubBusinessTypeId as $postRequestSubTypeId)
				{
					if(!in_array($postRequestSubTypeId,$sub_business_type))
					{
						$sec_bus[] = [
							'user_id'                       => $AdminId,
							'location_id'                   => $request->editlocationID,
							'business_type_id'              => $postRequestSubTypeId,
							'is_main_business_type'         => '0',
							'created_at'                    => date("Y-m-d H:i:s"),
							'updated_at'                    => date("Y-m-d H:i:s")
						];
					}
				}
			} else {
				$deletedata = Location_business_type::where('location_id', $request->editlocationID)->where('is_main_business_type',0)->delete();	
			}	
			
            if($location_businesstype_main->save())
            {
				if(!empty($sec_bus)) {
					$sec_bus_type = Location_business_type::insert($sec_bus);
				}	
            
                $data["message"] = "Location has been updated succesfully.";
                $data["status"] = true;
                $data["redirect"] = route('location_detail', $request->editlocationID);
                return JsonReturn::success($data);
            }
            else
            {
                $data["status"] = true;
                $data["message"] = array("Something went wrong.");
                $data["redirect"] = route('location_detail', $request->editlocationID);
                return JsonReturn::success($data);
            }
        } 
        else 
        {
            $edit_id = $id;
            $main = Location_business_type::select('location_business_type.business_type_id', 'business_type.name', 'business_type.id')
                ->join('business_type', 'business_type.id', '=', 'location_business_type.business_type_id')
                ->where('location_id', '=', $id)
                ->where('location_business_type.is_main_business_type', '=', '1')
                ->first();
		
            $sec = Location_business_type::select('location_business_type.business_type_id')
                ->where('location_id', '=', $id)->where('location_business_type.is_main_business_type', '=', '0')
                ->get();

			$ids = array();
			foreach($sec as $val)
			{
				array_push($ids, $val->business_type_id);
			}
			
            $bus_type = Businesstype::select('business_type.*')->get();
			
            $common['edit_id'] = $edit_id;
            $common['ids'] = $ids;
            $common['main'] = $main;
            $common['bus_type'] = $bus_type;
            
            return view('setup.edit_businesstypes', $common);
        }
    }
	
    public function updateContactDetails(Request $request, $id)
    {
		if ($request->ajax()) 
        {
			$rules =
            [
                'location_name' => 'required',
                'country_code' => 'required',
                'location_phone' => 'required',
                'location_email' => 'required|email',
            ];
			
			$input = $request->only(
				'location_name',
				'country_code',
				'location_phone',
				'location_email',
			);

			$validator = Validator::make($input, $rules);
			if ($validator->fails()) {
				return JsonReturn::error($validator->messages());
			}
			else
			{
				$edit_id = $request->editlocationID;
				$location = Location::find($edit_id);
				
				$location->location_name = ($request->location_name) ? $request->location_name : '';
				if($location->is_same_billing_addr == 0)
				{
					$location->billing_company_name = $request->location_name;
				}
				
				$location->country_code = ($request->country_code) ? $request->country_code : '+91';
				$location->location_phone = ($request->location_phone) ? $request->location_phone : '';
				$location->location_email = ($request->location_email) ? $request->location_email : '';
				if($location->save())
				{
					$data["status"] = true;
					$data["message"] = "Location has been updated succesfully.";
					$data["redirect"] = route('location_detail',$edit_id);
					return JsonReturn::success($data);
				}
				else
				{
					$data["status"] = false;
					$data["error"] = array("Something went wrong.");
					$data["redirect"] = route('location_detail', $edit_id);
					return JsonReturn::error($data);
				}
			}
		}
		else
		{
			$locationData = Location::select('id','location_name','country_code','location_phone','location_email')->where('locations.id', '=', $id)->first();
			return view('setup.contact_details', compact('locationData'));
		}		
	}	
	
    public function deletelocation(Request $request)
    {
        if ($request->ajax()) 
		{
            $location = Location::find($request->deleteID);

            if (!empty($location)) 
			{
                $deletebus_type = Location_business_type::where('location_id', '=', $request->deleteID)->get()->first();
				
				$deleBusType = Location_business_type::find($deletebus_type->id);
                $deleBusType->is_deleted = '1';
                $deleBusType->save();
				
                $deletedata = Location::find($request->deleteID);
                $deletedata->is_deleted = '1';
                $deletedata->save();

                if ($deletedata) {
                    $data["status"] = true;
                    $data["message"] = "Location has been deleted succesfully.";
                } else {
                    $data["status"] = false;
                    $data["message"] = "Sorry! Unable to delete Location.";
                }
            } else {
                $data["status"] = false;
                $data["message"] = "Sorry! Unable to find selected Location.";
            }
            return JsonReturn::success($data);
        }
    }

    public function resources_index()
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
		
        $data = Location::select('locations.*')->where('locations.user_id', '=', $AdminId)->get();
        return view('setup.resources', compact('data'));
    }

    public function get_resources(Request $request)
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
			
            $resources = Resources::select('resources.*')->where('user_id', $AdminId)->orderBy('id', 'desc')->get();

            $data_arr = array();
            foreach($resources as $val)
            {
                $tempData = array(
                    'id' => $val->id,
                    'name' => $val->name,
                    'description' => $val->description
                );
                array_push($data_arr, $tempData);
            }
         
            return Datatables::of($data_arr)
                ->editColumn('services_assign', function ($row) {
                    $html = '<td>0</td>';
                    return $html;
                })
                ->rawColumns(['services_assign'])
                ->setRowAttr([
                    'data-id' => function($row) {
                        return $row['id'];
                    },
                    'data-name' => function($row) {
                        return $row['name'];
                    },
                    'data-description' => function($row) {
                        return $row['description'];
                    },
                    'class' => function($row) {
                        return "editResourceModal";
                    },
                ])
                ->make(true);
        }   
    }

    public function add_resources(Request $request)
    {
        if($request->ajax())
        { 
            $rules=
            [
                'name' => 'required',
                'description' => 'required'
            ];
			
            $input = $request->only(
                'name',
                'location_id',
                'description',
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
			
            $resource = Resources::select('resources.*')->where('resources.user_id','=',$AdminId)->first();
            
            if($request->editResource == "")
            {
                $addcloseddate = Resources::create([
                    'user_id'     => $AdminId,
                    'name'        => ($request->name) ? $request->name : '',
                    'location_id' => ($request->location_id) ? $request->location_id : '',
                    'services_id' => '3',
                    'description'       => ($request->description) ? $request->description : '',
                ]);
                $data["status"] = true;
                $data["message"] = array("Resource has been created succesfully.");
                return JsonReturn::success($data);
            }
            else if($request->editResource != "")
            {
                $resource_obj = Resources::find($request->editResource);
                $resource_obj->name = $request->name ? $request->name : "";
                $resource_obj->description = $request->description ? $request->description : "";
                $resource_obj->save();
                if($resource_obj->save())
                {
                    $data["status"] = true;
                    $data["message"] = array("Resource has been updated succesfully.");
                    return JsonReturn::success($data);
                }
                else
                {
                    $data["status"] = false;
                    $data["message"] = array("Resource update operation is failed.");
                    return JsonReturn::success($data);
                }
            }
        }
        else
        {
            $data["status"] = false;
            $data["message"] = array("Sorry somethig went wrong.");
            $data["message_code"] = array("Out of ajax condition.");
            return JsonReturn::success($data);   
        }
    }

    public function delete_resources(Request $request)
    {
        if ($request->ajax()) 
        {
            $resource = Resources::find($request->deleteResource);

            if(!empty($resource))
            {
                $deletedata = Resources::where('id', $request->deleteResource)->delete();
                
                if($deletedata){
                    $data["status"] = true;
                    $data["message"] = "Resource has been deleted succesfully.";
                } else {
                    $data["status"] = false;
                    $data["message"] = "Something went wrong! Please try again.";
                }       
                
            } else {
                $data["status"] = false;
                $data["message"] = "Something went wrong! Please try again.";
            }   
            
            return JsonReturn::success($data);
        }
    }

    public function account(){
		
		$timezones = array ( '(UTC-11:00) Midway Island' => 'Pacific/Midway', '(UTC-11:00) Samoa' => 'Pacific/Samoa', '(UTC-10:00) Hawaii' => 'Pacific/Honolulu', '(UTC-09:00) Alaska' => 'US/Alaska', '(UTC-08:00) Pacific Time (US &amp; Canada)' => 'America/Los_Angeles', '(UTC-08:00) Tijuana' => 'America/Tijuana', '(UTC-07:00) Arizona' => 'US/Arizona', '(UTC-07:00) Chihuahua' => 'America/Chihuahua', '(UTC-07:00) La Paz' => 'America/Chihuahua', '(UTC-07:00) Mazatlan' => 'America/Mazatlan', '(UTC-07:00) Mountain Time (US &amp; Canada)' => 'US/Mountain', '(UTC-06:00) Central America' => 'America/Managua', '(UTC-06:00) Central Time (US &amp; Canada)' => 'US/Central', '(UTC-06:00) Guadalajara' => 'America/Mexico_City', '(UTC-06:00) Mexico City' => 'America/Mexico_City', '(UTC-06:00) Monterrey' => 'America/Monterrey', '(UTC-06:00) Saskatchewan' => 'Canada/Saskatchewan', '(UTC-05:00) Bogota' => 'America/Bogota', '(UTC-05:00) Eastern Time (US &amp; Canada)' => 'US/Eastern', '(UTC-05:00) Indiana (East)' => 'US/East-Indiana', '(UTC-05:00) Lima' => 'America/Lima', '(UTC-05:00) Quito' => 'America/Bogota', '(UTC-04:00) Atlantic Time (Canada)' => 'Canada/Atlantic', '(UTC-04:30) Caracas' => 'America/Caracas', '(UTC-04:00) La Paz' => 'America/La_Paz', '(UTC-04:00) Santiago' => 'America/Santiago', '(UTC-03:30) Newfoundland' => 'Canada/Newfoundland', '(UTC-03:00) Brasilia' => 'America/Sao_Paulo', '(UTC-03:00) Buenos Aires' => 'America/Argentina/Buenos_Aires', '(UTC-03:00) Georgetown' => 'America/Argentina/Buenos_Aires', '(UTC-03:00) Greenland' => 'America/Godthab', '(UTC-02:00) Mid-Atlantic' => 'America/Noronha', '(UTC-01:00) Azores' => 'Atlantic/Azores', '(UTC-01:00) Cape Verde Is.' => 'Atlantic/Cape_Verde', '(UTC+00:00) Casablanca' => 'Africa/Casablanca', '(UTC+00:00) Edinburgh' => 'Europe/London', '(UTC+00:00) Greenwich Mean Time : Dublin' => 'Etc/Greenwich', '(UTC+00:00) Lisbon' => 'Europe/Lisbon', '(UTC+00:00) London' => 'Europe/London', '(UTC+00:00) Monrovia' => 'Africa/Monrovia', '(UTC+00:00) UTC' => 'UTC', '(UTC+01:00) Amsterdam' => 'Europe/Amsterdam', '(UTC+01:00) Belgrade' => 'Europe/Belgrade', '(UTC+01:00) Berlin' => 'Europe/Berlin', '(UTC+01:00) Bern' => 'Europe/Berlin', '(UTC+01:00) Bratislava' => 'Europe/Bratislava', '(UTC+01:00) Brussels' => 'Europe/Brussels', '(UTC+01:00) Budapest' => 'Europe/Budapest', '(UTC+01:00) Copenhagen' => 'Europe/Copenhagen', '(UTC+01:00) Ljubljana' => 'Europe/Ljubljana', '(UTC+01:00) Madrid' => 'Europe/Madrid', '(UTC+01:00) Paris' => 'Europe/Paris', '(UTC+01:00) Prague' => 'Europe/Prague', '(UTC+01:00) Rome' => 'Europe/Rome', '(UTC+01:00) Sarajevo' => 'Europe/Sarajevo', '(UTC+01:00) Skopje' => 'Europe/Skopje', '(UTC+01:00) Stockholm' => 'Europe/Stockholm', '(UTC+01:00) Vienna' => 'Europe/Vienna', '(UTC+01:00) Warsaw' => 'Europe/Warsaw', '(UTC+01:00) West Central Africa' => 'Africa/Lagos', '(UTC+01:00) Zagreb' => 'Europe/Zagreb', '(UTC+02:00) Athens' => 'Europe/Athens', '(UTC+02:00) Bucharest' => 'Europe/Bucharest', '(UTC+02:00) Cairo' => 'Africa/Cairo', '(UTC+02:00) Harare' => 'Africa/Harare', '(UTC+02:00) Helsinki' => 'Europe/Helsinki', '(UTC+02:00) Istanbul' => 'Europe/Istanbul', '(UTC+02:00) Jerusalem' => 'Asia/Jerusalem', '(UTC+02:00) Kyiv' => 'Europe/Helsinki', '(UTC+02:00) Pretoria' => 'Africa/Johannesburg', '(UTC+02:00) Riga' => 'Europe/Riga', '(UTC+02:00) Sofia' => 'Europe/Sofia', '(UTC+02:00) Tallinn' => 'Europe/Tallinn', '(UTC+02:00) Vilnius' => 'Europe/Vilnius', '(UTC+03:00) Baghdad' => 'Asia/Baghdad', '(UTC+03:00) Kuwait' => 'Asia/Kuwait', '(UTC+03:00) Minsk' => 'Europe/Minsk', '(UTC+03:00) Nairobi' => 'Africa/Nairobi', '(UTC+03:00) Riyadh' => 'Asia/Riyadh', '(UTC+03:00) Volgograd' => 'Europe/Volgograd', '(UTC+03:30) Tehran' => 'Asia/Tehran', '(UTC+04:00) Abu Dhabi' => 'Asia/Muscat', '(UTC+04:00) Baku' => 'Asia/Baku', '(UTC+04:00) Moscow' => 'Europe/Moscow', '(UTC+04:00) Muscat' => 'Asia/Muscat', '(UTC+04:00) St. Petersburg' => 'Europe/Moscow', '(UTC+04:00) Tbilisi' => 'Asia/Tbilisi', '(UTC+04:00) Yerevan' => 'Asia/Yerevan', '(UTC+04:30) Kabul' => 'Asia/Kabul', '(UTC+05:00) Islamabad' => 'Asia/Karachi', '(UTC+05:00) Karachi' => 'Asia/Karachi', '(UTC+05:00) Tashkent' => 'Asia/Tashkent', '(UTC+05:30) Chennai' => 'Asia/Calcutta', '(UTC+05:30) Kolkata' => 'Asia/Kolkata', '(UTC+05:30) Mumbai' => 'Asia/Calcutta', '(UTC+05:30) New Delhi' => 'Asia/Calcutta', '(UTC+05:30) Sri Jayawardenepura' => 'Asia/Calcutta', '(UTC+05:45) Kathmandu' => 'Asia/Katmandu', '(UTC+06:00) Almaty' => 'Asia/Almaty', '(UTC+06:00) Astana' => 'Asia/Dhaka', '(UTC+06:00) Dhaka' => 'Asia/Dhaka', '(UTC+06:00) Ekaterinburg' => 'Asia/Yekaterinburg', '(UTC+06:30) Rangoon' => 'Asia/Rangoon', '(UTC+07:00) Bangkok' => 'Asia/Bangkok', '(UTC+07:00) Hanoi' => 'Asia/Bangkok', '(UTC+07:00) Jakarta' => 'Asia/Jakarta', '(UTC+07:00) Novosibirsk' => 'Asia/Novosibirsk', '(UTC+08:00) Beijing' => 'Asia/Hong_Kong', '(UTC+08:00) Chongqing' => 'Asia/Chongqing', '(UTC+08:00) Hong Kong' => 'Asia/Hong_Kong', '(UTC+08:00) Krasnoyarsk' => 'Asia/Krasnoyarsk', '(UTC+08:00) Kuala Lumpur' => 'Asia/Kuala_Lumpur', '(UTC+08:00) Perth' => 'Australia/Perth', '(UTC+08:00) Singapore' => 'Asia/Singapore', '(UTC+08:00) Taipei' => 'Asia/Taipei', '(UTC+08:00) Ulaan Bataar' => 'Asia/Ulan_Bator', '(UTC+08:00) Urumqi' => 'Asia/Urumqi', '(UTC+09:00) Irkutsk' => 'Asia/Irkutsk', '(UTC+09:00) Osaka' => 'Asia/Tokyo', '(UTC+09:00) Sapporo' => 'Asia/Tokyo', '(UTC+09:00) Seoul' => 'Asia/Seoul', '(UTC+09:00) Tokyo' => 'Asia/Tokyo', '(UTC+09:30) Adelaide' => 'Australia/Adelaide', '(UTC+09:30) Darwin' => 'Australia/Darwin', '(UTC+10:00) Brisbane' => 'Australia/Brisbane', '(UTC+10:00) Canberra' => 'Australia/Canberra', '(UTC+10:00) Guam' => 'Pacific/Guam', '(UTC+10:00) Hobart' => 'Australia/Hobart', '(UTC+10:00) Melbourne' => 'Australia/Melbourne', '(UTC+10:00) Port Moresby' => 'Pacific/Port_Moresby', '(UTC+10:00) Sydney' => 'Australia/Sydney', '(UTC+10:00) Yakutsk' => 'Asia/Yakutsk', '(UTC+11:00) Vladivostok' => 'Asia/Vladivostok', '(UTC+12:00) Auckland' => 'Pacific/Auckland', '(UTC+12:00) Fiji' => 'Pacific/Fiji', '(UTC+12:00) International Date Line West' => 'Pacific/Kwajalein', '(UTC+12:00) Kamchatka' => 'Asia/Kamchatka', '(UTC+12:00) Magadan' => 'Asia/Magadan', '(UTC+12:00) Marshall Is.' => 'Pacific/Fiji', '(UTC+12:00) New Caledonia' => 'Asia/Magadan', '(UTC+12:00) Solomon Is.' => 'Asia/Magadan', '(UTC+12:00) Wellington' => 'Pacific/Auckland', '(UTC+13:00) Nuku\'alofa' => 'Pacific/Tongatapu' );
		
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
		
        $ac = AccountSetting::where('user_id',$AdminId)->first();

        return view('setup.account_settings',compact('ac','timezones'));
    }

    public function accountSetting(Request $request)
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
		
        $ac = AccountSetting::where('user_id',$AdminId)->first();
        if($ac)
		{
            $ac->update([
                'business_name'                 => $request->business_name,
                'timezone'                      => $request->timezone,
                'timeformat'                    => $request->timeformat,
                'weekStart'                     => $request->weekStart,
                'appointmentColorSource'        => $request->appointmentColorSource,
                'communicationLanguageCode'     => !empty($request->communicationLanguageCode) ? $request->communicationLanguageCode : NULL,
                'employeeLanguageCode'          => !empty($request->employeeLanguageCode) ? $request->employeeLanguageCode : NULL,
                'website'                       => $request->website,
                'facebook'                      => $request->facebook,
                'Instagram'                     => $request->Instagram
            ]);
            $data["status"] = true;
            $data["message"] = "Account Setting update succesfully.";
        } else {
            $ac = AccountSetting::create([
                'user_id'                       => $AdminId,
                'timezone'                      => $request->timezone,
                'business_name'                 => $request->business_name,
                'timeformat'                    => $request->timeformat,
                'weekStart'                     => $request->weekStart,
                'appointmentColorSource'        => $request->appointmentColorSource,
                'communicationLanguageCode'     => !empty($request->communicationLanguageCode) ? $request->communicationLanguageCode : NULL,
                'employeeLanguageCode'          => !empty($request->employeeLanguageCode) ? $request->employeeLanguageCode : NULL,
                'website'                       => $request->website,
                'facebook'                      => $request->facebook,
                'Instagram'                     => $request->Instagram
            ]);
            $data["status"] = true;
            $data["message"] = "Account Setting Details added succesfully.";
        }
		
        $data["redirect"] = route('setup');
        return JsonReturn::success($data);
    }

    public function invoiceTemplate()
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
		
        $it = InvoiceTemplate::where('user_id',$AdminId)->first();
        return view('setup.invoice_template',compact('it'));
    }

    public function invoice_template(Request $request)
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
		
        $it = InvoiceTemplate::where('user_id',$AdminId)->first();
        
		if($it)
		{
            $it->update([
                'user_id'        => $AdminId,
                'autoPrint'      => $request->autoPrint=='on' ? 1 : 0,
                'showMobile'     => $request->showMobile=='on' ? 1 : 0,
                'showAddress'    => $request->showAddress=='on' ? 1 : 0,
                'title'          => $request->title,
                'receiptLine1'   => $request->receiptLine1,
                'receiptLine2'   => $request->receiptLine2,
                'receiptfooter'  => $request->receiptfooter
            ]);
            $data["status"] = true;
            $data["message"] = "Invoice Template update succesfully.";
        } else {
            $it = InvoiceTemplate::create([
                'user_id'        => $AdminId,
                'autoPrint'      => $request->autoPrint=='on' ? 1 : 0,
                'showMobile'     => $request->showMobile=='on' ? 1 : 0,
                'showAddress'    => $request->showAddress=='on' ? 1 : 0,
                'title'          => $request->title,
                'receiptLine1'   => $request->receiptLine1,
                'receiptLine2'   => $request->receiptLine2,
                'receiptfooter'  => $request->receiptfooter
            ]);
            $data["status"] = true;
            $data["message"] = "Invoice Template Details added succesfully.";
        }
        $data["redirect"] = route('setup');
        return JsonReturn::success($data);
    }

    public function InvoiceSequencing()
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
		
        $loc = Location::where('user_id',$AdminId)->where('is_deleted','0')->get();
        $is = InvoiceSequencing::where('user_id',$AdminId)->get();
        return view('setup.invoice_sequencing',compact('is','loc'));
    }
    
    public function Invoice_sequencing(Request $request)
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
		
        $is = InvoiceSequencing::where('user_id',$AdminId)->where('location_id',$request->id)->first();

        if($is)
		{
            $is->update([
                'user_id'        => $AdminId,
                'location_id'      => $request->id,
                'invoice_no_prefix'     => $request->invoice_no_prefix,
                'next_invoice_number'    => $request->next_invoice_number       
            ]);
            $data["status"] = true;
            $data["message"] = "Invoice Sequencing update succesfully.";
        } else {
            $is = InvoiceSequencing::create([
                'user_id'        => $AdminId,
                'location_id'      => $request->id,
                'invoice_no_prefix'     => $request->invoice_no_prefix,
                'next_invoice_number'    => $request->next_invoice_number
            ]);
            $data["status"] = true;
            $data["message"] = "Invoice Sequencing Details added succesfully.";
        }
        return JsonReturn::success($data);
    }

    public function taxes() 
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
		
        $tax = Taxes::where('user_id',$AdminId)->where('is_deleted','0')->get();
        $taxs = Taxes::where('user_id',$AdminId)->where('is_deleted','0')->get();
        $formula = taxFormula::where('user_id',$AdminId)->where('is_deleted','0')->first();
        return view('setup.setup_taxes',compact('tax','formula','taxs'));
    }

    public function taxTates(Request $request)
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
			
            $tax = Taxes::where('user_id',$AdminId)->where('is_deleted','0')->get();
            //dd($resources);
            $data_arr = array();
            foreach($tax as $val)
            {
                if ($val->is_group=='1') {
                    $expgames_ids = explode(',', $val['tax_rates']);
                    $tax_arr = array();
                    foreach($expgames_ids as $taxids){

                        $tax = Taxes::where('user_id',$AdminId)->where('id',$taxids)->where('is_deleted','0')->first();
                        array_push($tax_arr, $tax['tax_rates']);
                    }
                }
				
                $tempData = array(
                    'id' => $val->id,
                    'user_id' => $val->user_id,
                    'tax_name' => $val->tax_name,
                    'is_group' => $val->is_group,
                    'tax_rates' => $val->is_group=='1' ? implode('%, ', $tax_arr) : $val->tax_rates,
                );
                array_push($data_arr, $tempData);
            }
         
            return Datatables::of($data_arr)
                ->editColumn('tax_name', function ($row) {
                    $html = '<td data-toggle="modal" data-target="#editNewTaxModal"><h4><b>'.$row['tax_name'].'</b></h4><br><h6>'.$row['tax_rates'].'%</h6></td>';
                    return $html;
                })
                ->rawColumns(['tax_name'])
                ->setRowAttr([
                    'data-id' => function($row) {
                        return $row['id'];
                    },
                    'data-name' => function($row) {
                        return $row['tax_name'];
                    },
                    'data-rates' => function($row) {
                        return $row['tax_rates'];
                    },
                    'class' => function($row) {
                        if($row['is_group']=='1'){
                            return "editgrouptaxes";
                        } else {
                            return "edittaxes";
                        }
                    },
                ])
                ->make(true);
        }
    }

    public function locTates(Request $request)
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
			
            $loc = Location::where('user_id',$AdminId)->where('is_deleted','0')->get();

            $data_arr = array();
            foreach($loc as $val)
            {
                $loctax = LocTax::where('user_id',$AdminId)->where('loc_id',$val->id)->where('is_deleted','0')->first();
                if($loctax){
                    $protax = Taxes::where('id',$loctax->poducts_default_tax)->where('is_deleted','0')->first();
                    $sertax = Taxes::where('id',$loctax->service_default_tax)->where('is_deleted','0')->first();
                }

                $tempData = array(
                    'id' => $val->id,
                    'user_id' => $val->user_id,
                    'location_name' => $val->location_name,
                    'productTax' => isset($protax->tax_name) ? $protax->tax_name : 'No tax',
                    'serviceTax' => isset($sertax->tax_name) ? $sertax->tax_name : 'No tax',
                    'productTaxid' => isset($protax->id) ? $protax->id : 'No tax',
                    'serviceTaxid' => isset($sertax->id) ? $sertax->id : 'No tax',
                );
                array_push($data_arr, $tempData);
            }
         
            return Datatables::of($data_arr)
                ->editColumn('location_name', function ($row) {
                    $html = '<td data-toggle="modal" data-target="#editNewTaxModal"><h4><b>'.$row['location_name'].'</b></h4><br><h6>Products Default: '.$row['productTax'].' ・ Services Default: '.$row['serviceTax'].'</h6></td>';
                    return $html;
                })
                ->rawColumns(['location_name'])
                ->setRowAttr([
                    'data-id' => function($row) {
                        return $row['id'];
                    },
                    'data-pro' => function($row) {
                        return $row['productTaxid'];
                    },
                    'data-ser' => function($row) {
                        return $row['serviceTaxid'];
                    },
                    'class' => function($row) {
                        return "editloctaxes";
                    },
                ])
                ->make(true);
        }
    }
	
    public function editGroupTaxes(Request $request)
	{
    	if($request->id != "")
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
			
	    	$taxGroup = Taxes::where('user_id',$AdminId)->where('is_group',1)->where('id',$request->id)->where('is_deleted',0)->first();
	    	if(!empty($taxGroup))
	    	{
	    		$tax = Taxes::where('user_id',$AdminId)->where('is_deleted',0)->where('is_group',0)->get();
	    		$output = '';
		        $row= 1;
		        $selectedTaxes = explode(',',$taxGroup->tax_rates);
		       
		        foreach($tax as $taxes){
                    $taxOptionSelected = false;
		            $output .='
		            <label class="font-weight-bolder">Tax '.$row.'</label>
		            <div class="input-group">
		                <div class="input-group-prepend">
		                    <span class="input-group-text bg-white"><i class="fa fa-percent"></i></span>
		                </div>
		                <select name="tax_rates[]" id="servicetax" class="form-control remove" >
		                    <option disabled selected >Select another tax </option>';
		                    
		                    foreach($tax as $value){
                                if(in_array($value->id, $selectedTaxes) && !$taxOptionSelected){
                                    $isSelected = "selected='selected'";
                                    $selectedKey = array_keys($selectedTaxes, $value->id);
                                    unset($selectedTaxes[$selectedKey[0]]);
                                    $taxOptionSelected = true;
                                } else {
                                    $isSelected = '';
                                }
		                        $output .='<option value="'. $value->id .'" '.$isSelected.'>'. $value->tax_name.' ('. $value->tax_rates .'%)</option>';
		                        // $isSelected = "";
		                    }
		                    $output .='</select>
		            </div>
		            ';
		            $row++;
		        }
		        echo $output;
	    	}
	    	// echo "<pre>";
	        // print_r($selectedTaxes);
	        exit;
    	}
    }
	
    public function saveTaxes(Request $request)
	{
        $rules=
        [
            'tax_name' => 'required',
            'tax_rates' => 'required'
        ];
        $input = $request->only(
            'tax_name',
            'tax_rates'
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

        $tax = Taxes::create([
            'user_id'      => $AdminId,
            'tax_name'     => $request->tax_name,
            'tax_rates'    => $request->tax_rates
        ]);
        $data["status"] = true;
        $data["message"] = "Taxes Details added succesfully.";
        return JsonReturn::success($data);
    }

    public function getgrouptax()
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
		
        $tax = Taxes::where('user_id',$AdminId)->where('is_group',0)->where('is_deleted',0)->get();

        $output = '';
        $row= 1;
        foreach($tax as $taxes){
            $output .='
            <label class="font-weight-bolder">Tax '.$row.'</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-white"><i class="fa fa-percent"></i></span>
                </div>
                <select name="tax_rates[]" id="servicetax" class="form-control remove" >
                    <option disabled selected >Select another tax </option>';
                    foreach($tax as $value){
                        $output .='<option value="'. $value->id .'">'. $value->tax_name.' ('. $value->tax_rates .'%)</option>';
                    }
                    $output .='</select>
            </div>
            ';
            $row++;
        }
        echo $output;
    }

    public function saveGroupTax(Request $request)
	{
        $rules=
        [
            'tax_name' => 'required',
            'tax_rates' => 'required'
        ];
        $input = $request->only(
            'tax_name',
            'tax_rates'
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
		
        // echo "<pre>"; echo $request->tax_name; die;

        $tax = Taxes::create([
            'user_id'      => $AdminId,
            'tax_name'     => $request->tax_name,
            'tax_rates'    => implode(', ', $request->tax_rates),
            'tax_ids'      => implode(', ', $request->tax_rates),
            'is_group'     => 1,

        ]);
        $data["status"] = true;
        $data["message"] = "Taxes Group Details added succesfully.";
        return JsonReturn::success($data);
    }

    public function updateGroupTax(Request $request)
	{
        $rules=
        [
            'tax_name' => 'required',
            'tax_rates' => 'required'
        ];
        $input = $request->only(
            'tax_name',
            'tax_rates'
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
		
		$tax = Taxes::find($request->id);
		
        $tax->update([
            'user_id'      => $AdminId,
            'tax_name'     => $request->tax_name,
            'tax_rates'    => implode(', ', $request->tax_rates),
            'is_group'     => 1,

        ]);
        $data["status"] = true;
        $data["message"] = "Taxes Group Details update succesfully.";
        return JsonReturn::success($data);
    }

    public function updatetax(Request $request)
	{
        $rules=
        [
            'tax_name' => 'required',
            'tax_rates' => 'required'
        ];
        $input = $request->only(
            'tax_name',
            'tax_rates'
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
		
        $tax = Taxes::find($request->id);
        $tax->update([
            'user_id'      => $AdminId,
            'tax_name'     => $request->tax_name,
            'tax_rates'    => $request->tax_rates
        ]);
        $data["status"] = true;
        $data["message"] = "Taxes Details Update succesfully.";
        return JsonReturn::success($data);
    }

    public function deletetax(Request $request)
	{
        $rules=
        [
            'id' => 'required',
        ];
        $input = $request->only(
            'id',
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) 
        {
            return JsonReturn::error($validator->messages());
        }
        $tax = Taxes::find($request->id);
        $tax->update([
            'is_deleted'     => '1',
        ]);
        $data["status"] = true;
        $data["message"] = "Tax Delete succesfully.";

        return JsonReturn::success($data);
    }

    public function saveLocTax(Request $request)
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
		
        $tax = LocTax::where('user_id',$AdminId)->where('loc_id',$request->locid)->where('is_deleted','0')->first();

        if($tax){
            $tax->update([
                'user_id'               => $AdminId,
                'loc_id'                => $request->locid,
                'poducts_default_tax'   => $request->poducts_default_tax,
                'service_default_tax'   => $request->service_default_tax
            ]);
            $data["status"] = true;
            $data["message"] = "Tax Defaults Details update succesfully.";
        } else {
            $tax = LocTax::create([
                'user_id'              => $AdminId,
                'loc_id'               => $request->locid,
                'poducts_default_tax'  => $request->poducts_default_tax,
                'service_default_tax'  => $request->service_default_tax
            ]);
            $data["status"] = true;
            $data["message"] = "Tax Defaults Details added succesfully.";
        }
        return JsonReturn::success($data);
    }

    public function savetaxformula(Request $request)
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
		
        $formula = taxFormula::where('user_id',$AdminId)->where('is_deleted','0')->first();

        if($formula) {
            $formula->update([
                'user_id'        => $AdminId,
                'tax_formula'    => $request->formula
            ]);
            $data["status"] = true;
            $data["message"] = "Tax Formula Details update succesfully.";
        } else {
            $formula = taxFormula::create([
                'user_id'       => $AdminId,
                'tax_formula'   => $request->formula
            ]);
            $data["status"] = true;
            $data["message"] = "Tax Formula Details added succesfully.";
        }
        return JsonReturn::success($data);
    }

    public function paymentType()
	{
        return view('setup.setup_payment_type');
    }

    public function getpaymentdata(Request $request)
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
			
            $typePay = paymentType::where('user_id',$AdminId)->where('is_deleted','0')->orderBy('order_id','ASC')->get();

            $data_arr = array();
            foreach($typePay as $val)
            {
                $tempData = array(
                    'id' => $val->id,
                    'user_id' => $val->user_id,
                    'payment_type' => $val->payment_type,
                    'order_id' => $val->order_id,
                );
                array_push($data_arr, $tempData);
            }
         
            return Datatables::of($data_arr)
                ->editColumn('user_id', function ($row) {
                    $html = '<i class="fa fa-bars"></i>';
                    return $html;
                })
                ->editColumn('payment_type', function ($row) {
                    $html = '<td>'.$row['payment_type'].'</td>';
                    return $html;
                })
                ->editColumn('order_id', function ($row) {
                   
                    if($row['payment_type']=='Cash'){
                        return '<i class="fa fa-lock"></i>';
                    } else {
                        return "";
                    }
                })
                ->rawColumns(['user_id','payment_type','order_id'])
                ->setRowAttr([
                    'data-id' => function($row) {
                        return $row['id'];
                    },
                    'data-type' => function($row) {
                        return $row['payment_type'];
                    },
                    'class' => function($row) {
                        if($row['payment_type']=='Cash'){
                            return "sortindex";
                        } else {
                            return "editPaymentType  sortindex";
                        }
                    },
                ])
                ->make(true);
        }
    }

    public function savePaymentType(Request $request)
	{
        $rules=
        [
            'payment_type' => 'required',
        ];
        
		$input = $request->only(
            'payment_type',
        );
		
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) 
        {
            return JsonReturn::error($validator->messages());
        }
		
		$CurrentUser = auth::user();
		$is_admin = $CurrentUser->is_admin;
		
		if($is_admin == 1) {
			$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
			$AdminId = $CurrentStaff->user_id;
			$UserId  = Auth::id();
		} else {
			$AdminId = Auth::id();
			$UserId  = Auth::id();
		}
		
        $tax = paymentType::create([
            'user_id'        => $AdminId,
            'payment_type'     => $request->payment_type,
        ]);
        $data["status"] = true;
        $data["message"] = "Payment Type Details added succesfully.";
        return JsonReturn::success($data);
    }

    public function updatePayType(Request $request)
	{
        $rules=
        [
            'payment_type' => 'required',
        ];
        $input = $request->only(
            'payment_type',
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) 
        {
            return JsonReturn::error($validator->messages());
        }

        $typePay = paymentType::where('id',$request->id)->where('is_deleted','0')->first();

        $typePay->update([
            'payment_type'     => $request->payment_type,
        ]);
        $data["status"] = true;
        $data["message"] = "Payment Type Details update succesfully.";
        return JsonReturn::success($data);
    }

    public function deletePayType(Request $request)
	{
        $rules=
        [
            'id' => 'required',
        ];
        $input = $request->only(
            'id',
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) 
        {
            return JsonReturn::error($validator->messages());
        }
		
        $tax = paymentType::find($request->id);
        $tax->update([
            'is_deleted'     => '1',
        ]);
		
        $data["status"] = true;
        $data["message"] = "Payment Type Delete succesfully.";
        return JsonReturn::success($data);
    }

    public function payTypeorder(Request $request)
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
		
		foreach ($request->order as $order) 
		{
			$service = paymentType::where('is_deleted',0)->where('user_id', $AdminId)->where('id',$order['id'])->first();
			if ($service) {
				$service->update(['order_id' => $order['position']]);
				$data["status"] = "success";
			} else {
				$data["status"] = "fails";
			}
		}
		return JsonReturn::success($data);
	}

    public function getdiscount()
	{
        return view('setup.setup_discount_type');
    }

    public function getDisData(Request $request)
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
			
            $dis = Discount::where('user_id',$AdminId)->where('is_deleted','0')->get();

            $data_arr = array();
            foreach($dis as $val)
            {
                $tempData = array(
                    'id' => $val->id,
                    'user_id' => $val->user_id,
                    'name' => $val->name,
                    'value' => $val->value,
                    'prType' => $val->prType=='0' ? '%' : ' CAD off',
                    'date' => $val->created_at,
                );
                array_push($data_arr, $tempData);
            }
         
            return Datatables::of($data_arr)
                ->editColumn('name', function ($row) {
                    $html = '<div class="d-flex align-items-center justify-content-between flex-wrap">
                                <div>
                                    <h4>'.$row['name'].'</h4>
                                    <h6 class="text-dark-50">
                                        Value: '.$row["name"].' '.$row['value'].''.$row['prType'].'
                                        <i class="fa fa-dot"></i>
                                        Added: '.date("l, d F Y",strtotime($row['date'])).'
                                    </h6>
                                </div>
                                <div>
                                    <div class="dropdown dropdown-inline mr-4">
                                        <button type="button"
                                            class="btn btn-light-primary btn-icon btn-sm"
                                            data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            <i class="ki ki-bold-more-hor"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item editNewDiscountModal"
                                                data-id="'.$row['id'].'"
                                                >Edit</a>
                                            <a class="dropdown-item text-danger" data-id="'.$row['id'].'"
                                                data-target="#deleteModal" id="disdelete"
                                                data-toggle="modal">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </div>';
                    return $html;
                })
                ->rawColumns(['name'])
                ->setRowAttr([
                    'data-id' => function($row) {
                        return $row['id'];
                    },
                    
                    'class' => function($row) {
                       return 'editNewDiscountModal';
                    },
                ])
                ->make(true);
        }
    }

    public function saveDiscount(Request $request)
	{
        $rules=
        [
            'name' => 'required',
            'value' => 'required',
        ];
        $input = $request->only(
            'name',
            'value',
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

        $dis = Discount::create([
            'user_id'        => $AdminId,
            'name'     => $request->name,
            'value'     => $request->value,
            'prType'     => $request->prType=='percent' ? 0 :1,
            'is_service'     => $request->is_service=='on' ? 1 : 0 ,
            'is_product'     => $request->is_product=='on' ? 1 : 0 ,
            'is_voucher'     => $request->is_voucher=='on' ? 1 : 0 ,
            'is_plan'   => $request->is_plan=='on' ? 1 : 0 ,
        ]);
        $data["status"] = true;
        $data["message"] = "Discount Details added succesfully.";
        return JsonReturn::success($data);
    }

    public function getdiscountdata(Request $request)
	{
        $dis = Discount::find($request->id);
        return JsonReturn::success($dis);
    }

    public function updateDiscount(Request $request)
	{
        $rules=
        [
            'name' => 'required',
            'value' => 'required',
        ];
        $input = $request->only(
            'name',
            'value',
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
		
        $dis = Discount::find($request->id);

        $dis->update([
            'user_id'        => $AdminId,
            'name'     => $request->name,
            'value'     => $request->value,
            'prType'     => $request->prType=='percent' ? 0 :1,
            'is_service'     => $request->is_service=='on' ? 1 : 0 ,
            'is_product'     => $request->is_product=='on' ? 1 : 0 ,
            'is_voucher'     => $request->is_voucher=='on' ? 1 : 0 ,
            'is_plan'   => $request->is_plan=='on' ? 1 : 0 ,
        ]);
        $data["status"] = true;
        $data["message"] = "Discount Details Update succesfully.";
        return JsonReturn::success($data);
    }

    public function deleteDisco(Request $request)
	{    
        $tax = Discount::find($request->id);
        $tax->update([
            'is_deleted'     => '1',
        ]);
        $data["status"] = true;
        $data["message"] = "Discount Details Delete succesfully.";
        return JsonReturn::success($data);
    }

    public function getsales()
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
		
        $sales = salesSetting::where('user_id',$AdminId)->first();
        return view('setup.setup_sales_settings',compact('sales'));
    }

    public function saveSalesSetting(Request $request)
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
		
        $dis = salesSetting::find($request->id);

        if($dis)
		{
            $dis->update([
                'user_id'        => $AdminId,
                'salePriceBeforeDis'     => $request->salePriceBeforeDis=='on' ? 1 : 0 ,
                'salePriceIncludTax'     => $request->salePriceIncludTax=='on' ? 1 : 0 ,
                'servicePriceBeforePaidPlanDis'     => $request->servicePriceBeforePaidPlanDis=='on' ? 1 : 0 ,
                'expiryPeriod'     => $request->expiryPeriod,
            ]);
            $data["status"] = true;
            $data["message"] = "Sales Setting Update succesfully.";

        } else {
            $dis = salesSetting::create([
                'user_id'        => $AdminId,
                'salePriceBeforeDis'     => $request->salePriceBeforeDis=='on' ? 1 : 0,
                'salePriceIncludTax'     => $request->salePriceIncludTax=='on' ? 1 : 0 ,
                'servicePriceBeforePaidPlanDis'     => $request->servicePriceBeforePaidPlanDis=='on' ? 1 : 0 ,
                'expiryPeriod'     => $request->expiryPeriod,
            ]);
            $data["status"] = true;
            $data["message"] = "Sales Setting Add succesfully.";
        }
        return JsonReturn::success($data);
    }

    public function showreferral()
	{
        return view('setup.setup_referral_sources');
    }

    public function getreferral(Request $request)
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
			
            $dis = referralSources::where('user_id',$AdminId)->where('is_deleted','0')->orderBy('order_id','ASC')->get();

            $data_arr = array();
            foreach($dis as $val)
            {
                $tempData = array(
                    'id' => $val->id,
                    'user_id' => $val->user_id,
                    'name' => $val->name,
                    'date' => $val->created_at,
                    'is_active' => $val->is_active,
                );
                array_push($data_arr, $tempData);
            }
         
            return Datatables::of($data_arr)
                ->editColumn('user_id', function ($row) {
                    $html = '<td><i class="fa fa-bars"></i></td>';
                    return $html;
                })
                ->editColumn('name', function ($row) {
                    $html = '<td>'.$row['name'].'</td>';
                    return $html;
                })
                ->editColumn('date', function ($row) {
                    $html = '<td>'.date("l, d F Y",strtotime($row['date'])).'</td>';
                    return $html;
                })
                ->editColumn('is_active', function ($row) {
                    if($row['is_active']==1){
                        $html = '<td><span class="badge badge-success">Active</span></td>';
                    } else {
                        $html = '<td><span class="badge badge-default">INACTIVE</span></td>';
                    }
                    
                    return $html;
                })
                ->editColumn('id', function ($row) {
                    if($row['name']=='Walk-In'){
                        $html = '<td><i class="fa fa-lock"></i></td>';
                    } else {
                        $html = '';
                    }
                    return $html;
                })
                ->rawColumns(['user_id','name','date','is_active','id'])
                ->setRowAttr([
                    'data-id' => function($row) {
                        return $row['id'];
                    },
                    
                    'class' => function($row) {
                        if($row['name']=='Walk-In'){
                            return 'sortindex';
                        } else {
                            return 'editReferralSources  sortindex';
                        }
                    },
                ])
                ->make(true);
        }
    }

    public function saveReferral(Request $request)
	{
        $rules=
        [
            'name' => 'required',
        ];
		
        $input = $request->only(
            'name',
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

        $dis = referralSources::create([
            'user_id'        => $AdminId,
            'name'     => $request->name,
            'is_active'     => $request->is_active=='on' ? 1 : 0 ,
        ]);
        $data["status"] = true;
        $data["message"] = "Referral Sources added succesfully.";
        return JsonReturn::success($data);
    }

    public function getEditReferralData(Request $request){

        $dis = referralSources::find($request->id);
        return JsonReturn::success($dis);
    }

    public function updateReferral(Request $request)
	{
        $rules=
        [
            'name' => 'required',
        ];
        $input = $request->only(
            'name',
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) 
        {
            return JsonReturn::error($validator->messages());
        }
		
        $dis = referralSources::find($request->id);

        $dis->update([
            'name'     => $request->name,
            'is_active'     => $request->is_active=='on' ? 1 : 0 ,
        ]);
        $data["status"] = true;
        $data["message"] = "Referral Sources Update succesfully.";
        return JsonReturn::success($data);
    }

    public function referralorder(Request $request)
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
		
		foreach ($request->order as $order) {
			$service = referralSources::where('is_deleted',0)->where('user_id', $AdminId)->where('id',$order['id'])->first();
			if ($service) {
				$service->update(['order_id' => $order['position']]);
				$data["status"] = "success";
			} else {
				$data["status"] = "fails";
			}
		}
		return JsonReturn::success($data);
	}

    public function deleteRefe(Request $request)
	{
        $tax = referralSources::find($request->id);
        $tax->update([
            'is_deleted'     => '1',
        ]);
		
        $data["status"] = true;
        $data["message"] = "Referral Sources Details Delete succesfully.";
        return JsonReturn::success($data);
    }
        
	public function cancellationReasons()
	{
		return view('setup.cancellation-reasons');
	}	
	
	public function cancellationReasonList(Request $request)
	{
		if ($request->ajax()) 
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
			
			$CancellationReasons = CancellationReasons::where('user_id',$AdminId)->orderBy('position','ASC')->get();

            $CancellationArray = array();
            foreach($CancellationReasons as $CancellationReasonsData)
            {
                $tempData = array(
                    'id'         => $CancellationReasonsData->id,
                    'reason'     => $CancellationReasonsData->reason,
                    'created_at' => date("l, d M Y",strtotime($CancellationReasonsData->created_at)),
					'is_default' => $CancellationReasonsData->is_default,
                );
                array_push($CancellationArray,$tempData);
            }
			
            return Datatables::of($CancellationArray)
				->editColumn('is_default', function ($row) {
					
					if($row['is_default'] == 1) {
						$is_default = '<td><i class="fa fa-lock" title="This item cannot be deleted."></i></td>';
					} else {
						$is_default = '<td>&nbsp;</td>';
					} 
					
					return $is_default;
				})
                ->rawColumns(['is_default'])
				->setRowAttr([
                    'data-reasonId' => function($row) {
                        return $row['id'];
                    },
					'data-reason' => function($row) {
                        return $row['reason'];
                    },
					'data-is_default' => function($row) {
                        return $row['is_default'];
                    },
                    'class' => function($row) {
                        return "editCancellationReason";
                    },
                ])
				->make(true);
        } 
	}
	
	public function addCancellationReason(Request $request)
	{
		if ($request->ajax()) 
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
	
			$rules = [
				'reason' => 'required'
			];
			
            $input = $request->only(
                'reason'
            );

            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return JsonReturn::error($validator->messages());
            }
			
			$CancellationReasons = CancellationReasons::create([
				'user_id'    => $AdminId,
				'reason'     => $request->reason,
				'is_default' => 0,
				'position'   => 0,
				'created_at' => date("Y-m-d H:i:s")
			]); 
			
			if($CancellationReasons){
				Session::flash('message', 'Cancellation reason added succesfully.');
				$data["status"] = true;
				$data["message"] = "Cancellation reason added succesfully.";	
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
	
	public function editCancellationReason(Request $request)
	{
		if ($request->ajax()) 
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
	
			$rules = [
				'reasonId'    => 'required',
				'editReason'  => 'required'
			];
			
            $input = $request->only(
				'reasonId',
                'editReason'
            );

            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return JsonReturn::error($validator->messages());
            }
			
			$CancellationReasons = CancellationReasons::find($request->reasonId);
			
			if(!empty($CancellationReasons)){
				$CancellationReasons->reason     = $request->editReason;
				$CancellationReasons->updated_at = date("Y-m-d H:i:s");
				if($CancellationReasons->save()){
					Session::flash('message', 'Cancellation reason updated succesfully.');
					$data["status"] = true;
					$data["message"] = "Cancellation reason updated succesfully.";	
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
	
	public function deleteCancellationReason(Request $request)
	{
		if ($request->ajax()) 
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
	
			$rules = [
				'deleteReasonId'    => 'required'
			];
			
            $input = $request->only(
				'deleteReasonId'
            );

            $validator = Validator::make($input, $rules);
            if ($validator->fails()) {
                return JsonReturn::error($validator->messages());
            }
			
			$CancellationReasons = CancellationReasons::find($request->deleteReasonId);
			
			if(!empty($CancellationReasons))
			{
				if($CancellationReasons->delete())
				{
					Session::flash('message', 'Cancellation reason deleted succesfully.');
					$data["status"] = true;
					$data["message"] = "Cancellation reason deleted succesfully.";	
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
}
