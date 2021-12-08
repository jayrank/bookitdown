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
use App\Models\StaffWorkingHours;
use App\Models\StaffLocations;
use App\Models\Services;
use App\Models\StaffServices;
use App\Models\RolePermission;
use App\Models\UserPermission;
use App\Models\AccountSetting;
use App\Models\SalesClient;
use App\Mail\SendResetPassword;
use App\Mail\PartnerResetPassword;
use DataTables;
use DB;
use Session;
use DateInterval;
use DateTime;
use DatePeriod;
use Mail;
use Hash;
use Carbon;
use App\Exports\staffexport;
use Excel;
use Str;


class StaffController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        date_default_timezone_set('Asia/Kolkata');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
		if (Auth::user()->can('working_hours')) 
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
			
			$staffMembers = Staff::select('staff.id', 'users.first_name', 'users.last_name', 'users.country_code','users.phone_number', 'users.email', 'staff.appointment_color', 'staff.is_appointment_booked', 'roles.name')->join('users', 'staff.staff_user_id', '=', 'users.id')->leftJoin('roles', 'roles.id', '=', 'staff.user_permission')->where('staff.user_id', $AdminId)->orderBy('staff.id', 'desc')->get();
			$date = date("m/d/Y");
			
			$location = Location::select('id','location_name')->where('user_id','=',$AdminId)->get();

			$toDay = Carbon\Carbon::today();
			$startdate = $toDay->startOfWeek()->subDays(2);
			$currentWeekDate = [];
			for ($i=0; $i <7 ; $i++) {
				$currentWeekDate[$i]['weekdate'] = $startdate->addDay(1)->format('D d M');
				
				$clodate = Carbon\Carbon::parse($currentWeekDate[$i]['weekdate'])->format('Y-m-d');
				$closeddate = Staff_closedDate::select('closed_dates.start_date','closed_dates.end_date')->where('user_id','=',$AdminId)->where('start_date', '<=', $clodate)->where('end_date', '>=', $clodate)->first();//->where('start_date','<=',$clodate)->where('end_date','>=',$clodate)->first();

				if($closeddate)
				{
					$currentWeekDate[$i]['is_closed'] = 1;
				}

			}
			return view('staff.index1',compact('staffMembers','currentWeekDate','location'));
		} else if (Auth::user()->can('closed_dates')) 
		{
			return redirect()->route('staff_closed');
		} 
		else if (Auth::user()->can('staff_members')) 
		{
			return redirect()->route('staff_members');
		} 
		else if (Auth::user()->can('permission_levels')) 
		{
			return redirect()->route('getUserPermission');
		} 
		else 
		{
			return redirect()->route('calander');
		}		
    }

    function x_week_range($date) 
    {
        $ts = strtotime($date);
        $start = (date('w', $ts) == 0) ? $ts : strtotime('last sunday', $ts);
        if(date('l') == "Sunday")
        {
            $start = (date('w', $ts) == 0) ? $ts : strtotime('last sunday', $ts);
        }
        return array(date('Y-m-d', $start),
                     date('Y-m-d', strtotime('next saturday', $start)));
    }
	
    function getDatesFromRange($start, $end, $format = 'Y-m-d') 
    {
        $array = array(); 
        $interval = new DateInterval('P1D'); 
      
        $realEnd = new DateTime($end); 
        $realEnd->add($interval); 
      
        $period = new DatePeriod(new DateTime($start), $interval, $realEnd); 
      
        foreach($period as $key => $date) {                  
            $array[$key]['formated_date'] = $date->format($format);  
            $array[$key]['date'] = $date->format("Y-m-d");  
            $array[$key]['is_closed'] = 0;  
        }
        return $array; 
    }
	
    public function staff_members()
    {
        return view('staff.staff_members');   
    }

    public function add_staff_member($id = null)
    {
		$CurrentUser = auth::user();
		$is_admin = $CurrentUser->is_admin;
		
		if($is_admin == 1){
			$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
			$UserId = $CurrentStaff->user_id;
		} else {
			$UserId = Auth::id();
		}
		
        $staff = $staffLocations = $staffServices = array();
        if(!empty($id))
        {
			$staff = Staff::select('staff.*', 'users.first_name', 'users.last_name', 'users.country_code', 'users.phone_number', 'users.email')->join('users', 'staff.staff_user_id', '=', 'users.id')->where('staff.user_id', $UserId)->where('staff.id', $id)->orderBy('staff.id', 'desc')->first();
						
			$staffLocationsData = StaffLocations::select('location_id')->where('staff_id', $id)->get();
			foreach($staffLocationsData as $val) {
				array_push($staffLocations,$val->location_id);
			}
			
			$staffServiceData = StaffServices::select('service_id')->where('staff_id', $id)->where('status', 1)->get();
			foreach($staffServiceData as $val) {
				array_push($staffServices,$val->service_id);
			}
        }
		
		$services = Services::select('id','service_name')->where('is_deleted',0)->where('user_id', '=', $UserId)->get();
		$locations = Location::select('id','location_name')->where('is_deleted',0)->where('user_id', '=', $UserId)->get();
		$roles = Role::select('*')->where('id', '!=', 1)->get();
        return view('staff.add_staff_member',compact('staff','roles','locations','staffLocations','services','staffServices'));
    }
	
    public function getStafflist(Request $request)
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
			
            $staff = Staff::select('staff.id','staff.order_id', 'users.first_name', 'users.last_name', 'users.country_code','users.phone_number', 'users.email', 'staff.appointment_color', 'staff.is_appointment_booked', 'roles.name')->join('users', 'staff.staff_user_id', '=', 'users.id')->leftJoin('roles', 'roles.id', '=', 'staff.user_permission')->where('staff.user_id', $AdminId)->orderBy('staff.order_id','ASC')->get();
          
            $data_arr = array();
            foreach($staff as $val)
            {
				if($val->is_appointment_booked == 1) {
					$can_appointment = "Calendar bookings enabled";
					$color_appointment = $val->appointment_color;
				} else {
					$can_appointment = "Calendar disabled";	
					$color_appointment = "";
				}
				
				$phone = "";
				
				if($val->phone_number != '') {
					$phone = '+'.$val->country_code.' '.$val->phone_number;
				}	
				
				$tempData = array(
                    'id' => $val->id,
                    // 'order_id' => $val->order_id,
                    'profile' => $color_appointment,
                    'name' => $val->first_name.' '.$val->last_name,
                    'mobileno' => $phone,
                    'email' => $val->email,
                    'appointment' => $can_appointment,
                    'permission' => ($val->name) ? $val->name : "No Access"
                );
                array_push($data_arr, $tempData);
            }  
            
            return Datatables::of($data_arr)
                ->editColumn('profile', function ($row) {
                    
                    if($row['profile'] != '') {
                        $html = '<td><span class="dot blue" style="background-color:'.$row['profile'].'"></span></td>';
                    } else {
                        $html = '<td></td>';
                    }		
                    return $html;
                })
                ->setRowId(function ($row) {
                    return $row['id'];
                })
                ->setRowClass(function ($user) {
                    return "editable_row";
                })
                ->rawColumns(['profile','id'])
                ->make(true);
        }   
    }

	public function staffinfodownloadExcel(){

        return Excel::download(new staffexport(), 'Staff List.xls');
    }

	public function staffinfodownloadcsv(){

        return Excel::download(new staffexport(), 'Staff List.csv');
    }

	public function revokePermissionTo($permission)
    {
        $this->permissions()->detach($this->getStoredPermission($permission));
        $this->forgetCachedPermissions();
        $this->load('permissions');
        return $this;
    }
    
	public function addStaff(Request $request)
    {
		$staff_user_id = $request->staff_user_edit_id;
        $rules = [
            'firstname' => 'required',
			'email' => 'required|email|max:255|unique:users'. ($staff_user_id ? ",email,$staff_user_id" : '')
        ];

        $input = $request->only(
            'firstname',
            'email'
        );

        $validator = Validator::make($input, $rules);
        if ($validator->fails()) 
        {
			return JsonReturn::error($validator->messages());
        }

        if(empty($request->location_id)) {
        	return JsonReturn::error([['Select atleast one location.']]);
        }

        if(empty($request->service_id)) {
        	return JsonReturn::error([['Select at least one service.']]);
        }
		
        if($request->edit > 0 && $request->staff_user_edit_id > 0)
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
			
			$userUpd = User::find($staff_user_id);
			$userUpd->first_name = ($request->firstname) ? $request->firstname : '';
			$userUpd->last_name = ($request->lastname) ? $request->lastname : '';
			$userUpd->email = ($request->email) ? $request->email : '';
			$userUpd->country_code = ($request->tel_country_code) ? $request->tel_country_code : '1';
			$userUpd->phone_number = ($request->mobileno) ? str_replace(" ","", $request->mobileno) : '';
			$userUpd->updated_at = date("Y-m-d H:i:s");
			$userUpd->save();
			
			if($request->user_permission > 0)
			{
				$staff_role = Role::where('id', $request->user_permission)->first();
				$staff_permission = RolePermission::select('permission_id')->where('role_id',$request->user_permission)->where('user_id',$AdminId)->get()->toArray();
				
				$userUpd->roles()->sync($staff_role);

				UserPermission::where('user_id', $staff_user_id)->delete();
				$userUpd->permissions()->sync($staff_permission);
			} else {
				$premission = $staff_role = array();
				$userUpd->roles()->sync($staff_role);

				UserPermission::where('user_id', $staff_user_id)->delete();
				$userUpd->permissions()->sync($premission);
			}
			$staffid = $request->edit;
			
			$staffUpd = Staff::find($request->edit);
            $staffUpd->user_permission = ($request->user_permission) ? $request->user_permission : '0';
            $staffUpd->staff_title = ($request->staff_title) ? $request->staff_title : '';
            $staffUpd->staff_notes = ($request->staff_notes) ? $request->staff_notes : '';
            $staffUpd->is_appointment_booked = ($request->is_appointment_booked) ? $request->is_appointment_booked : '0';
            $staffUpd->appointment_color = ($request->is_appointment_booked) ? $request->appointment_color : '';
            $staffUpd->employment_start_date = ($request->employment_start_date) ? date("Y-m-d",strtotime($request->employment_start_date)) : null;
            $staffUpd->employment_end_date = ($request->employment_end_date) ? date("Y-m-d",strtotime($request->employment_end_date)) : null;
            $staffUpd->service_commission = ($request->service_commission > 0) ? $request->service_commission : '0';
            $staffUpd->product_commission = ($request->product_commission > 0) ? $request->product_commission : '0';
            $staffUpd->voucher_commission = ($request->voucher_commission > 0) ? $request->voucher_commission : '0';
            $staffUpd->paid_plan_commision = ($request->paid_plan_commision > 0) ? $request->paid_plan_commision : '0';
            $staffUpd->created_at = date("Y-m-d H:i:s");
            $staffUpd->updated_at = date("Y-m-d H:i:s");
			$staffUpd->save();
			
			$staffLocationIds = $request->location_id;
			
			if(!empty($staffLocationIds))
			{	
				$staffLocationsData = StaffLocations::select('location_id')->where('staff_id', $staffid)->get();
				$staffLocations = array();
				
				foreach($staffLocationsData as $val) {
					array_push($staffLocations,$val->location_id);
				}
				
				foreach($staffLocations as $val)
				{
					if(!in_array($val, $staffLocationIds)){
						$deletedata = StaffLocations::where('location_id', $val)->where('staff_id',$staffid)->delete();
					}	
				}
				
				foreach($staffLocationIds as $locationid)
				{
					if(!in_array($locationid,$staffLocations))
					{
						$addStaffLocation = new StaffLocations();
						$addStaffLocation->staff_id = $staffid;
						$addStaffLocation->staff_user_id = $staff_user_id;
						$addStaffLocation->location_id = $locationid;
						$addStaffLocation->created_at = date("Y-m-d H:i:s");
						$addStaffLocation->updated_at = date("Y-m-d H:i:s");
						$addStaffLocation->save();
					}
				}
			} else {
				$deletedata = StaffLocations::where('staff_id',$staffid)->delete();
			}		
			
			$staffServiceIds = $request->service_id;
			if(!empty($staffServiceIds))
			{
				$prevStaffServicesData = StaffServices::select('service_id')->where('staff_id', $staffid)->where('status', 1)->get();
				$staffServices = array();
				
				foreach($prevStaffServicesData as $val) {
					array_push($staffServices,$val->service_id);
				}
				
				foreach($staffServices as $val)
				{
					if(!in_array($val, $staffServiceIds)){
						$staffUpd = StaffServices::where('service_id', $val)->update(['status' => 0]);
					}	
				}
				
				foreach($staffServiceIds as $serviceid)
				{
					if(!in_array($serviceid,$staffServices))
					{
						$staffUpd = StaffServices::updateOrInsert(
							['staff_id' => $staffid, 'staff_user_id' => $staff_user_id, 'service_id' => $serviceid],
							['status' => 1, 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")]
						);
					}
				}
			}	
				
			Session::flash('message', 'Staff has been updated succesfully.');
			$data["status"] = true;
			$data["redirect"] = route('staff_members');
			return JsonReturn::success($data);
		}	
		else
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
			
			$userInsert = new User();
			$userInsert->first_name = ($request->firstname) ? $request->firstname : '';
			$userInsert->last_name = ($request->lastname) ? $request->lastname : '';
			$userInsert->email = ($request->email) ? $request->email : '';
			$userInsert->country_code = ($request->tel_country_code) ? $request->tel_country_code : '1';
			$userInsert->phone_number = ($request->mobileno) ? str_replace(" ","", $request->mobileno) : '';
			$userInsert->is_admin = 1;
			$userInsert->save();
				
			if($request->user_permission > 0) 
			{	
				$staff_role = Role::where('id', $request->user_permission)->first();
				$staff_permission = RolePermission::select('permission_id')->where('role_id',$request->user_permission)->where('user_id',$AdminId)->get()->toArray();
				
				$userInsert->roles()->attach($staff_role);
				$userInsert->permissions()->attach($staff_permission);
			}
			
            $addStaff = new Staff();
            $addStaff->user_id = $AdminId;
            $addStaff->staff_user_id = $userInsert->id;
            $addStaff->user_permission = ($request->user_permission) ? $request->user_permission : '0';
            $addStaff->staff_title = ($request->staff_title) ? $request->staff_title : '';
            $addStaff->staff_notes = ($request->staff_notes) ? $request->staff_notes : '';
            $addStaff->is_appointment_booked = ($request->is_appointment_booked) ? $request->is_appointment_booked : '0';
            $addStaff->appointment_color = ($request->is_appointment_booked) ? $request->appointment_color : '';
            $addStaff->employment_start_date = ($request->employment_start_date) ? date("Y-m-d",strtotime($request->employment_start_date)) : null;
            $addStaff->employment_end_date = ($request->employment_end_date) ? date("Y-m-d",strtotime($request->employment_end_date)) : null;
            $addStaff->service_commission = ($request->service_commission > 0) ? $request->service_commission : '0';
            $addStaff->product_commission = ($request->product_commission > 0) ? $request->product_commission : '0';
            $addStaff->voucher_commission = ($request->voucher_commission > 0) ? $request->voucher_commission : '0';
            $addStaff->paid_plan_commision = ($request->paid_plan_commision > 0) ? $request->paid_plan_commision : '0';
            $addStaff->created_at = date("Y-m-d H:i:s");
            $addStaff->updated_at = date("Y-m-d H:i:s");
			$addStaff->save();
			$lastInsertStaffId = $addStaff->id;
			
			$staffLocationIds = $request->location_id;
			$staffServiceIds = $request->service_id;
			
			if(!empty($staffLocationIds)) {
				foreach($staffLocationIds as $val) {
					$addStaffLocation = new StaffLocations();
					$addStaffLocation->staff_id = $lastInsertStaffId;
					$addStaffLocation->staff_user_id = $userInsert->id;
					$addStaffLocation->location_id = $val;
					$addStaffLocation->created_at = date("Y-m-d H:i:s");
					$addStaffLocation->updated_at = date("Y-m-d H:i:s");
					$addStaffLocation->save();
				}
			}
			
			if(!empty($staffServiceIds)) {
				foreach($staffServiceIds as $val) {
					$addStaffService = new StaffServices();
					$addStaffService->staff_id = $lastInsertStaffId;
					$addStaffService->staff_user_id = $userInsert->id;
					$addStaffService->service_id = $val;
					$addStaffService->status = 1;
					$addStaffService->created_at = date("Y-m-d H:i:s");
					$addStaffService->updated_at = date("Y-m-d H:i:s");
					$addStaffService->save();
				}
			}
			
			Session::flash('message', 'Staff has been created succesfully.');
			$data["status"] = true;
			$data["redirect"] = route('staff_members');
			return JsonReturn::success($data);	
        }
    }
	
    public function deleteStaffMember(Request $request)
    {
        if ($request->ajax()) 
        {
            $supplier = Staff::find($request->deleteID);   
            if(!empty($supplier))
            {
            	User::where('id', $supplier->staff_user_id)->delete();
                $deletedata = Staff::where('id', $request->deleteID)->delete();
                
                // if($deletedata){
                    $data["status"] = true;
                    $data["message"] = "Staff has been deleted succesfully.";
                /*} else {
                    $data["status"] = false;
                    $data["message"] = "Sorry! Unable to delete staff.";
                }*/       
            } else {
                $data["status"] = false;
                $data["message"] = "Sorry! Unable to find selected staff.";
            } 
            return JsonReturn::success($data);
        }
    }

    public function staff_closed(Request $request)
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
		
        $location = Location::select('id','location_name')->where('user_id','=',$AdminId)->get()->toArray();
        return view('staff.staff_closedate',compact('location')); 
    }
	
    public function getclosed_date(Request $request)
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
			
            $closeddate = Staff_closedDate::select('closed_dates.*')->where('user_id','=',$AdminId)->orderBy('id', 'desc')->get();
            
            $data_arr = array();
            foreach($closeddate as $val)
            {   
                $startdate = Carbon\Carbon::parse($val->start_date);
                $enddate = Carbon\Carbon::parse($val->end_date);
                $diff = $startdate->diffInDays($enddate)+1;
                
                $tempData = array(
                    'start_date' => $val->start_date,
                    'end_date' => $val->end_date,
					'DATE_RANGE' => $startdate->format('D j F Y').' - '.$enddate->format('D j F Y'),
					'NO_OF_DAYS' => $diff.' Day',
                    'description' => $val->description,
                    'id' => $val->id,
                    'all_permission' =>$val->all_location_permission,
                    'location_per' =>$val->location_id,
                );
                array_push($data_arr, $tempData);
            }
			
            return Datatables::of($data_arr)
                ->editColumn('location_id', function ($row) 
                {
                    if($row['all_permission'] == 1)
                    {
                        return "All Locations";
                    }
                    else
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
						
                        $location = Location::select('id','location_name')->where('user_id','=',$AdminId)->whereIn('id',explode(',',$row['location_per']))->get();
                        $locationName = array();
                        foreach ($location as $key => $value) 
                        {
                            array_push($locationName, $value->location_name);
                        }
                        $loc = implode(',', $locationName);
                        return $loc;
                    }
                })
                ->rawColumns(['location_id'])
                ->setRowAttr([
                    'data-id' => function($row) {
                        return $row['id'];
                    },
                    'data-DATE_RANGE' => function($row)
                    {
                        return $row['DATE_RANGE'];
                    },
                    'data-description' => function($row)
                    {
                        return $row['description'];
                    },
                    'data-start_date' => function($row)
                    {
                        return $row['start_date'];
                    },
                    'data-end_date' => function($row)
                    {
                        return $row['end_date'];
                    },
                    'data-all-location' => function($row)
                    {
                        return $row['all_permission'];
                    },
                    'data-location-id' => function($row)
                    {
                        return $row['location_per'];
                    },
                    'class' => function($row) {
                        return "editCloseDateModalCenter";
                    },
                ])
                ->make(true);
        } 
    }
	
    public function add_staff_closed(Request $request)
    {
        if($request->ajax())
        {
            $rules=
            [
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'description' => 'required'
            ];
            $input = $request->only(
                'start_date',
                'end_date',
                'description',
            );
            $validator = Validator::make($input, $rules);
            if ($validator->fails()) 
            {
    			return JsonReturn::error($validator->messages());
            }

            if(empty($request->permission)) {
            	return JsonReturn::error([["Select at least one location."]]);
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
			
            $location = Location::select('locations.*')->where('locations.user_id','=',$AdminId)->first();
            $loc = $location->id;
            
            if($request->editstaff == "")
            {
                $permission = !empty($request->permission) ? implode(',', $request->permission) : '';
                $addcloseddate = Staff_closedDate::create([
                    'user_id'           => $AdminId,
                    'start_date'        => ($request->start_date) ? $request->start_date : '',
                    'end_date'          => ($request->end_date) ? $request->end_date : '',
                    'location_id'       => $permission,
                    'all_location_permission'=> ($request->all_permission) ? $request->all_permission : 0,
                    'description'       => ($request->description) ? $request->description : '',
                ]);

                $data["status"] = true;
                $data["message"] = array("closed date has been created succesfully.");
                return JsonReturn::success($data);
            }
            else if($request->editstaff != "")
            {
                $permission = !empty($request->permission) ? implode(',', $request->permission) : '';
                $all_permission = isset($request->all_permission) ? $request->all_permission : "";

                $staff_closedobj = Staff_closedDate::find($request->editstaff);
                $staff_closedobj->start_date = $request->start_date ? $request->start_date : "";
                $staff_closedobj->end_date = $request->end_date ? $request->end_date : "";
                $staff_closedobj->location_id = $permission;
                $staff_closedobj->all_location_permission = ($all_permission) ? $all_permission: 0;
                $staff_closedobj->description = $request->description ? $request->description : "";
                if($staff_closedobj->save())
                {
                    $data["status"] = true;
                    $data["message"] = array("closed date has been updated succesfully.");
                    return JsonReturn::success($data);
                }
                else
                {
                    $data["status"] = false;
                    $data["message"] = array("closed date update operation is failed.");
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

    public function deleteStaff(Request $request)
    {
        if ($request->ajax()) 
        {
            $closed_date = Staff_closedDate::find($request->delstaff);
           
            if(!empty($closed_date))
            {
                $deletedata = Staff_closedDate::where('id', $request->delstaff)->delete();
                
                if($deletedata){
                    $data["status"] = true;
                    $data["message"] = "closed date has been deleted succesfully.";
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
    
    public function getStaffByLoc($id)
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
		
        $loc = Location::select('user_id')->where('id',$id)->first();

        $staffMembers = Staff::select('staff.id', 'users.first_name', 'users.last_name', 'users.country_code','users.phone_number', 'users.email', 'staff.appointment_color', 'staff.is_appointment_booked', 'roles.name')->join('users', 'staff.staff_user_id', '=', 'users.id')->leftJoin('roles', 'roles.id', '=', 'staff.user_permission')->where('staff.user_id', $loc->user_id)->orderBy('staff.id', 'desc')->get();
        $date = date("m/d/Y");
        
        $data["location"] = Location::select('id','location_name')->where('user_id','=',$AdminId)->get();

        $toDay = Carbon\Carbon::today();
        $startdate = $toDay->startOfWeek()->subDays(2);
        $currentWeekDate = [];
        for ($i=0; $i <7 ; $i++) {
            $currentWeekDate[$i]['weekdate'] = $startdate->addDay(1)->format('D d M y');
            
            $clodate = Carbon\Carbon::parse($currentWeekDate[$i]['weekdate'])->format('Y-m-d');
            $closeddate = Staff_closedDate::select('closed_dates.start_date','closed_dates.end_date')->where('user_id','=',$AdminId)->where('start_date', '<=', $clodate)->where('end_date', '>=', $clodate)->first();//->where('start_date','<=',$clodate)->where('end_date','>=',$clodate)->first();

            if($closeddate)
            {
                $currentWeekDate[$i]['is_closed'] = 1;
            }
        }

        return $this->tabledata($currentWeekDate,$staffMembers);
    }

    public function getHoursByStaff(Request $request)
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
		
        if($request->id =='all'){
            $staffMembers = Staff::select('staff.id', 'users.first_name', 'users.last_name', 'users.country_code','users.phone_number', 'users.email', 'staff.appointment_color', 'staff.is_appointment_booked', 'roles.name')->join('users', 'staff.staff_user_id', '=', 'users.id')->leftJoin('roles', 'roles.id', '=', 'staff.user_permission')->where('staff.user_id',$AdminId)->orderBy('staff.id', 'desc')->get();
        } else {
            $staffMembers = Staff::select('staff.id', 'users.first_name', 'users.last_name', 'users.country_code','users.phone_number', 'users.email', 'staff.appointment_color', 'staff.is_appointment_booked', 'roles.name')->join('users', 'staff.staff_user_id', '=', 'users.id')->leftJoin('roles', 'roles.id', '=', 'staff.user_permission')->where('staff.id',$request->id)->orderBy('staff.id', 'desc')->get();
        }
        $date = date("m/d/Y");
        
        $data["location"] = Location::select('id','location_name')->where('user_id','=',$AdminId)->get();

        $userDate = Carbon\Carbon::parse($request->date);

        $startdate = $userDate->startOfWeek()->subDays(2);
        $currentWeekDate = [];
        for ($i=0; $i <7 ; $i++) {
            $currentWeekDate[$i]['weekdate'] = $startdate->addDay(1)->format('D d M y');
            
            $clodate = Carbon\Carbon::parse($currentWeekDate[$i]['weekdate'])->format('Y-m-d');
            $closeddate = Staff_closedDate::select('closed_dates.start_date','closed_dates.end_date')->where('user_id','=',$AdminId)->where('start_date', '<=', $clodate)->where('end_date', '>=', $clodate)->first();//->where('start_date','<=',$clodate)->where('end_date','>=',$clodate)->first();

            if($closeddate)
            {
                $currentWeekDate[$i]['is_closed'] = 1;
            }
        }

        return $this->tabledata($currentWeekDate,$staffMembers);
    }

    public function getDateByStaff(Request $request){

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
		
            $now = Carbon\Carbon::parse($request->date);

            if($request->id =='all'){
                $staffMembers = Staff::select('staff.id', 'users.first_name', 'users.last_name', 'users.country_code','users.phone_number', 'users.email', 'staff.appointment_color', 'staff.is_appointment_booked', 'roles.name')->join('users', 'staff.staff_user_id', '=', 'users.id')->leftJoin('roles', 'roles.id', '=', 'staff.user_permission')->where('staff.user_id',$AdminId)->orderBy('staff.id', 'desc')->get();
            } else {
                $staffMembers = Staff::select('staff.id', 'users.first_name', 'users.last_name', 'users.country_code','users.phone_number', 'users.email', 'staff.appointment_color', 'staff.is_appointment_booked', 'roles.name')->join('users', 'staff.staff_user_id', '=', 'users.id')->leftJoin('roles', 'roles.id', '=', 'staff.user_permission')->where('staff.id',$request->id)->orderBy('staff.id', 'desc')->get();
            }
        
            $data["location"] = Location::select('id','location_name')->where('user_id','=',$AdminId)->get();

            $startdate = $now->startOfWeek()->subDays(2);
            $currentWeekDate = [];
            for ($i=0; $i <7 ; $i++) {
                $currentWeekDate[$i]['weekdate'] = $startdate->addDay(1)->format('D d M y');
                
                $clodate = Carbon\Carbon::parse($currentWeekDate[$i]['weekdate'])->format('Y-m-d');
                $closeddate = Staff_closedDate::select('closed_dates.start_date','closed_dates.end_date')->where('user_id','=',$AdminId)->where('start_date', '<=', $clodate)->where('end_date', '>=', $clodate)->first();//->where('start_date','<=',$clodate)->where('end_date','>=',$clodate)->first();

                if($closeddate)
                {
                    $currentWeekDate[$i]['is_closed'] = 1;
                }
            }
            return $this->tabledata($currentWeekDate,$staffMembers);
        }
    }

    public function tabledata($currentWeekDate,$staffMembers)
	{
        // table
        $output = '';
        $output .= '<thead>
            <tr class="border-0">
                <th class="border-0"></th>';
                foreach ($currentWeekDate as $key =>  $day){
                    if(isset($day['is_closed']) == 1){
                        $output .= '<th class="p-2 m-2 border-0 closed-lable text-light">Closed<span>&nbsp;&nbsp;&nbsp;<i class="text-light fa fa-comment" data-toggle="tooltip" data-placement="top" title="closed"></i></span></th>';
                    }else{
                        $output .= '<th class="border-0"></th>';
                    }
                }  
                $output .= '</tr>';
                $output .= '<tr>';
                $output .= '<th>Staff</th>';
                foreach ($currentWeekDate as $day){
                    $output .= '<th>'. $day['weekdate'] .'</th>';
                }
            $output .= '</tr>';
        $output .= '</thead>';
        $output .= '<tbody>';
            
            foreach ($staffMembers as $memberKey => $member){
                $time1=array(); $time2=array();
                $output .= '<tr>';
                    $output .= '<td class="font-weight-bold">'.$member->first_name.' '.$member->last_name.'<br/>';
                    $output .= '<span class="font-weight-normal" id="totalhours'.$member->id.'"></span>';
                    $output .= '</td>';
                    foreach ($currentWeekDate as $key =>  $day){
                        $currday = date("N",strtotime($day['weekdate'])); 
                        $workinghours = StaffWorkingHours::where('staff_id',$member->id)->where('day',$currday)->first();
                        if(isset($workinghours)){
                            $time = json_decode($workinghours['start_time'],true); 
                            $endtime = json_decode($workinghours['end_time'],true);
                            $startdate = $workinghours['date']; $enddate = $workinghours['remove_date']; $removedate = $workinghours['remove_date']; $currweekdate = Carbon\Carbon::parse($day['weekdate'])->format('Y-m-d'); $diff_in_minutes1 = Carbon\Carbon::parse($time[0])->diffInMinutes(Carbon\Carbon::parse($endtime[0])); $diff_in_minutes2 = Carbon\Carbon::parse($time[1])->diffInMinutes(Carbon\Carbon::parse($endtime[1]));
                        } 
                        
                        if(isset($day['is_closed']) == 1){
                            $output .= '<td class="closed" data-toggle="tooltip" data-placement="top" title="closed">';
                                
                            $output .= '</td>';
                        }else{
                            $output .= '<td>';
                                if(isset($workinghours) && $currweekdate >= $startdate){
                                    
                                    if($removedate != 0 && $workinghours['remove_type'] == 2){
                                        if($removedate == $currweekdate){
                                            $output .= '<a href="#" class="shift_time" data-date="'. date("l, d F Y",strtotime($day['weekdate'])) .'" data-id="'. $member->id .'" data-day="'.date("N",strtotime($day['weekdate'])) .'" data-name="'. $member->first_name." ".$member->last_name .'">';
                                            $output .= '<div class="time" style="padding:12%" title="Add shift time">';
                                            $output .= '</div>';
                                            $output .= '</a>';
                                        }else{
                                            $output .= '<a href="#" class="edit_shift_time" data-date="'. date("l, d F Y",strtotime($day['weekdate'])) .'" data-id="'. $workinghours['id'] .'" data-day="'. date("N",strtotime($day['weekdate'])) .'" data-name="'. $member->first_name.' '.$member->last_name .'">';
                                                $output .= '<div class="time" title="Edit shift time">'.$time[0].'  '.$endtime[0].'<br/>';
                                                    if($time[1]==0 && $endtime[1]==0){ $output .= ''; } else { $output .= $time[1].' '.$endtime[1]; }
                                                $output .= '</div>';
                                            $output .= '</a>';
                                            array_push($time1,$diff_in_minutes1);array_push($time2,$diff_in_minutes2);
                                        }
                                    }else{
                                        if($removedate != 0 && $workinghours['remove_type'] == 1){
                                            if($currweekdate >= $removedate){
                                                $output .= '<a href="#" class="shift_time" data-date="'. date("l, d F Y",strtotime($day['weekdate'])) .'" data-id="'. $member->id .'" data-day="'. date("N",strtotime($day['weekdate'])) .'" data-name="'. $member->first_name." ".$member->last_name .'">';
                                                $output .= '<div class="time" style="padding:12%" title="Add shift time">';
                                                $output .= '</div>';
                                                $output .= '</a>';
                                            }else{
                                                $output .= '<a href="#" class="edit_shift_time" data-date="'. date("l, d F Y",strtotime($day['weekdate'])) .'" data-id="'. $workinghours['id'] .'" data-day="'. date("N",strtotime($day['weekdate'])) .'" data-name="'. $member->first_name.' '.$member->last_name .'">';
                                                    $output .= '<div class="time" title="Edit shift time">'.$time[0].'  '.$endtime[0].'<br/>';
                                                        if($time[1]==0 && $endtime[1]==0){ $output .= ''; } else { $output .= $time[1].' '.$endtime[1]; }
                                                    $output .= '</div>';
                                                $output .= '</a>';
                                                array_push($time1,$diff_in_minutes1);array_push($time2,$diff_in_minutes2);
                                            }
                                        }else{
                                            if($workinghours['repeats'] == 0){
                                                if($currweekdate == $startdate){
                                                    $output .= '<a href="#" class="edit_shift_time" data-date="'. date("l, d F Y",strtotime($day['weekdate'])) .'" data-id="'. $workinghours['id'] .'" data-day="'. date("N",strtotime($day['weekdate'])) .'" data-name="'. $member->first_name.' '.$member->last_name .'">';
                                                    $output .= '<div class="time" title="Edit shift time">'.$time[0].'  '.$endtime[0].'<br/>';
                                                        if($time[1]==0 && $endtime[1]==0){ $output .= ''; } else { $output .= $time[1].' '.$endtime[1]; }
                                                    $output .= '</div>';
                                                    $output .= '</a>';
                                                    array_push($time1,$diff_in_minutes1);array_push($time2,$diff_in_minutes2);
                                                }else{
                                                    if($currweekdate > $startdate){
                                                        $output .= '<a href="#" class="shift_time" data-date="'. date("l, d F Y",strtotime($day['weekdate'])) .'" data-id="'. $member->id .'" data-day="'. date("N",strtotime($day['weekdate'])) .'" data-name="'. $member->first_name." ".$member->last_name .'">';
                                                        $output .= '<div class="time" style="padding:12%" title="Add shift time">';
                                                        $output .= '</div>';
                                                        $output .= '</a>';
                                                    }
                                                    elseif($currweekdate < $startdate){
                                                        $output .= '<a href="#" class="edit_shift_time" data-date="'. date("l, d F Y",strtotime($day['weekdate'])) .'" data-id="'. $workinghours['id'] .'" data-day="'. date("N",strtotime($day['weekdate'])) .'" data-name="'. $member->first_name.' '.$member->last_name .'">';
                                                        $output .= '<div class="time" title="Edit shift time">'.$time[0].'  '.$endtime[0].'<br/>';
                                                            if($time[1]==0 && $endtime[1]==0){ $output .= ''; } else { $output .= $time[1].' '.$endtime[1]; }
                                                        $output .= '</div>';
                                                        $output .= '</a>';
                                                        array_push($time1,$diff_in_minutes1);array_push($time2,$diff_in_minutes2);
                                                    }
                                                }
                                            }
                                            if($workinghours['repeats'] == 1){
                                                if($workinghours['endrepeat']==0){
                                                    $output .= '<a href="#" class="edit_shift_time" data-date="'. date("l, d F Y",strtotime($day['weekdate'])) .'" data-id="'. $workinghours['id'] .'" data-day="'. date("N",strtotime($day['weekdate'])) .'" data-name="'. $member->first_name.' '.$member->last_name .'">';
                                                    $output .= '<div class="time" title="Edit shift time">'.$time[0].'  '.$endtime[0].'<br/>';
                                                        if($time[1]==0 && $endtime[1]==0){ $output .= ''; } else { $output .= $time[1].' '.$endtime[1]; }
                                                    $output .= '</div>';
                                                    $output .= '</a>';
                                                    array_push($time1,$diff_in_minutes1);array_push($time2,$diff_in_minutes2);
                                                }
                                                if($workinghours['endrepeat'] != 0 && $currweekdate > $workinghours['endrepeat']){
                                                    $output .= '<a href="#" class="shift_time" data-date="'. date("l, d F Y",strtotime($day['weekdate'])) .'" data-id="'. $member->id .'" data-day="'. date("N",strtotime($day['weekdate'])) .'" data-name="'. $member->first_name." ".$member->last_name .'">';
                                                    $output .= '<div class="time" style="padding:12%" title="Add shift time">';
                                                    $output .= '</div>';
                                                    $output .= '</a>';
                                                }
                                                if($workinghours['endrepeat']!=0 && $currweekdate < $workinghours['endrepeat']) {
                                                    $output .= '<a href="#" class="edit_shift_time" data-date="'. date("l, d F Y",strtotime($day['weekdate'])) .'" data-id="'. $workinghours['id'] .'" data-day="'. date("N",strtotime($day['weekdate'])) .'" data-name="'. $member->first_name.' '.$member->last_name .'">';
                                                    $output .= '<div class="time" title="Edit shift time">'.$time[0].'  '.$endtime[0].'<br/>';
                                                        if($time[1]==0 && $endtime[1]==0){ $output .= ''; } else { $output .= $time[1].' '.$endtime[1]; }
                                                    $output .= '</div>';
                                                    $output .= '</a>';
                                                    array_push($time1,$diff_in_minutes1);array_push($time2,$diff_in_minutes2);
                                                }
                                            }
                                            
                                        }
                                    }

                                }else{
                                    $output .= '<a href="#" class="shift_time" data-date="'. date("l, d F Y",strtotime($day['weekdate'])).'" data-id="'. $member->id .'" data-day="'. date("N",strtotime($day['weekdate'])) .'" data-name="'. $member->first_name.' '.$member->last_name .'">';
                                    $output .= '<div class="time" style="padding:12%" title="Add shift time">';
                                    $output .= '</div>';
                                    $output .= '</a>';
                                }
                            $output .= '</td>';
                        }
                    }
                    $totalt1 = array_sum($time1); $totalt2 = array_sum($time2); $totalTime = $totalt1 + $totalt2;  if(sprintf("%02d",floor($totalTime / 60))>0){$hor = sprintf("%02d",floor($totalTime / 60)).'h ';}else {$hor = '';}if(sprintf("%02d",str_pad(($totalTime % 60), 2, "0", STR_PAD_LEFT))>0){$min =sprintf("%02d",str_pad(($totalTime % 60), 2, "0", STR_PAD_LEFT)). "min";}else{$min ='';}
                    $output .= '<input type="hidden" id="timeTotal'. $member->id .'" value="'.$hor.$min .'">';
                $output .= '</tr>';
            }
        $output .= '</tbody>';
        // end
        return response()->json(['data' => $output,'staff' => $staffMembers]);
    }

    public function addStaffWorkingHours(Request $request)
	{
        if($request->ajax())
        {
            $rules=
            [
                'start_time' => 'required',
                'end_time' => 'required',
                'repeats' => 'required',
                // 'spenddate' => 'required'
            ];
            $input = $request->only(
                'start_time',
                'end_time',
                'repeats',
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
			
            $date = Carbon\Carbon::parse($request->timedate)->format('Y-m-d');
            $endrepeat = $request->endrepeat == 1 ? Carbon\Carbon::parse($request->spenddate)->format('Y-m-d') : $request->endrepeat;

            StaffWorkingHours::create([
                'user_id' => $AdminId,
                'staff_id' => $request->staff_id,
                'date' => $date,
                'day' => $request->day,
                'start_time' => json_encode($request->start_time),
                'end_time' => json_encode($request->end_time),
                'repeats' => $request->repeats,
                'endrepeat' => $endrepeat,
            ]);
            
            $data["status"] = true;
            $data["message"] = "Staff working hours created succesfully.";
			return JsonReturn::success($data);	
        }
    }

    public function getStaffWorkingHours($id)
	{
        $staffhours = StaffWorkingHours::findOrfail($id);
        return $staffhours;
    }

    public function editStaffWorkingHours(Request $request)
	{
        if($request->ajax())
        {
            $rules=
            [
                'start_time' => 'required',
                'end_time' => 'required',
                'repeats' => 'required',
                // 'spenddate' => 'required'
            ];
            $input = $request->only(
                'start_time',
                'end_time',
                'repeats',
            );
            $validator = Validator::make($input, $rules);
            if ($validator->fails()) 
            {
    			return JsonReturn::error($validator->messages());
            }
            $date = Carbon\Carbon::parse($request->timedate)->format('Y-m-d');
            $endrepeat = $request->endrepeat == 1 ? Carbon\Carbon::parse($request->spenddate)->format('Y-m-d') : $request->endrepeat;
            $staffhours = StaffWorkingHours::findOrfail($request->id);

            $staffhours->update([
                'start_time' => json_encode($request->start_time),
                'end_time' => json_encode($request->end_time),
                'repeats' => $request->repeats,
                'endrepeat' => $endrepeat,
                'remove_date' => '0',
                'remove_type' => '0',
            ]);

            $data["status"] = true;
            $data["message"] = "Staff working hours Update succesfully.";

			return JsonReturn::success($data);	
        }
    }

    public function removeHours(Request $request)
	{
        if($request->ajax())
		{
            $staffhours = StaffWorkingHours::findOrfail($request->id);
            $date = Carbon\Carbon::parse($request->date)->format('Y-m-d');
            $endrepeat = $request->endrepeat == 1 ? Carbon\Carbon::parse($request->spenddate)->format('Y-m-d') : $request->endrepeat;

            $staffhours->update([
                'repeats' => $request->repeats,
                'endrepeat' => $endrepeat,
                'remove_date' => $date,
                'remove_type' => $request->type,
            ]);

            $data["status"] = true;
            $data["message"] = "Staff working hours Remove succesfully.";
			return JsonReturn::success($data);	
        }
    }

    public function setstafforder()
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
			
        $staff = Staff::select('staff.id', 'staff.order_id','users.first_name', 'users.last_name',)->join('users', 'staff.staff_user_id', '=', 'users.id')->where('staff.user_id', $AdminId)->orderBy('staff.order_id','ASC')->get();

        return view('staff.setOrderForStaffmember',compact('staff'));
    }

    public function stafftorder(Request $request)
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
			$service = Staff::where('user_id', $AdminId)->where('id',$order['id'])->first();
			if ($service) {
				$service->update(['order_id' => $order['position']]);
				$data["status"] = "success";
			} else {
				$data["status"] = "fails";
			}
		}
		return JsonReturn::success($data);
	}

    public function sendMail($id){

        $staff = User::where('id', $id)->first();

        /*$FROM_EMAIL     = env("MAIL_FROM_ADDRESS", "info@ikotel.ca");
        $FROM_NAME      = ($staff->first_name) ? $staff->first_name : 'New';
        $TO_EMAIL       = ($staff->email) ? $staff->email : '';
        // $CC_EMAIL       = 'testmail.com';
        $SUBJECT        = 'Reset Password';
        $MESSAGE        = 'Hi Reset Your Password';
        
        $SendMail = Mail::to($TO_EMAIL)->send(new SendResetPassword($FROM_EMAIL,$FROM_NAME,$SUBJECT,$MESSAGE));	*/

        $token = Str::random(64);

        $is_admin = $staff->is_admin;
        
        if($is_admin == 1){
            $CurrentStaff = Staff::select('user_id')->where('staff_user_id',$staff->id)->first();
            $AdminId = $CurrentStaff->user_id;
            $UserId  = $staff->id;
        } else {
            $AdminId = $staff->id;
            $UserId  = $staff->id;
        }

        $ac = AccountSetting::where('user_id',$AdminId)->first();
        if(!empty($ac)) {
            $locationName = $ac->business_name;
        } else {
            $locationName = '';
        }

        $staff->token = $token;
        $staff->save();

        $SendMail = Mail::to($staff->email)->send(new PartnerResetPassword($token, $staff->first_name, $staff->email, $locationName)); 
        // ->cc([$CC_EMAIL])
        $data["status"] = true;
        $data["message"] = "Password Reset Mail has been sent succesfully.";	
        return JsonReturn::success($data);
    }

    public function getUserPermission()
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
		
		$userEmail = User::select('id','email')->where('id', $AdminId)->first();
		$salesClient = SalesClient::select('*')->where('sd_user_id', $AdminId)->where('username', $userEmail->email)->first();
		
		$is_scheduledown_pro = 0;
		if(!empty($salesClient)) {
			$is_scheduledown_pro = ($salesClient->is_scheduledown_pro) ? $salesClient->is_scheduledown_pro : 0;
		}	
		
		$Role = Role::select('*')->get()->toArray();
		
		$admin_role = Role::select('id','name')->whereIn('slug',['basic','low','medium','high'])->get();
		
		$basicPermission = array();
		$basicPermissionData = RolePermission::select('permission_id')->where('role_id',2)->where('user_id',$AdminId)->get();		
		foreach($basicPermissionData as $val) {
			$basicPermission[] = $val->permission_id;
		}	
		
		$lowPermission = array();
		$lowPermissionData = RolePermission::select('permission_id')->where('role_id',3)->where('user_id',$AdminId)->get();
		foreach($lowPermissionData as $val) {
			$lowPermission[] = $val->permission_id;
		}
		
		$mediumPermission = array();
		$mediumPermissionData = RolePermission::select('permission_id')->where('role_id',4)->where('user_id',$AdminId)->get();
		foreach($mediumPermissionData as $val) {
			$mediumPermission[] = $val->permission_id;
		}
		
		$highPermission = array();
		$highPermissionData = RolePermission::select('permission_id')->where('role_id',5)->where('user_id',$AdminId)->get();
		foreach($highPermissionData as $val) {
			$highPermission[] = $val->permission_id;
		}
		
        return view('staff.staff_user_permission',compact('Role','basicPermission','lowPermission','mediumPermission','highPermission','is_scheduledown_pro'));
    }
    
	public function saveUserStaffPermission(Request $request)
	{
		if ($request->ajax()) 
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
			
			$userEmail = User::select('id','email')->where('id', $AdminId)->first();
			$salesClient = SalesClient::select('*')->where('sd_user_id', $AdminId)->where('username', $userEmail->email)->first();
			
			$is_scheduledown_pro = 0;
			if(!empty($salesClient)) {
				$is_scheduledown_pro = ($salesClient->is_scheduledown_pro) ? $salesClient->is_scheduledown_pro : 0;
			}	
			
			if($is_scheduledown_pro == 1) {
				$permissionLists = Permission::select('id','slug')->get();
			} else {
				$permissionLists = Permission::select('id','slug')->whereNotIn('id', [38,39,40,43,44,45])->get();
			}		
			$deleteData = RolePermission::where('user_id', $AdminId)->delete();
			
			foreach($permissionLists as $key => $val) {
				
				if(in_array($val->id, $request->basic_permission)) {
					$insRolePermission = new RolePermission();
					$insRolePermission->user_id = $AdminId;
					$insRolePermission->role_id = 2;
					$insRolePermission->permission_id = $val->id;
					$insRolePermission->timestamps = false;
					$insRolePermission->save();
				}
				if(in_array($val->id, $request->low_permission)) {
					$insRolePermission = new RolePermission();
					$insRolePermission->user_id = $AdminId;
					$insRolePermission->role_id = 3;
					$insRolePermission->permission_id = $val->id;
					$insRolePermission->timestamps = false;
					$insRolePermission->save();
				}
				if(in_array($val->id, $request->medium_permission)) {
					$insRolePermission = new RolePermission();
					$insRolePermission->user_id = $AdminId;
					$insRolePermission->role_id = 4;
					$insRolePermission->permission_id = $val->id;
					$insRolePermission->timestamps = false;
					$insRolePermission->save();
				}
				if(in_array($val->id, $request->high_permission)) {
					$insRolePermission = new RolePermission();
					$insRolePermission->user_id = $AdminId;
					$insRolePermission->role_id = 5;
					$insRolePermission->permission_id = $val->id;
					$insRolePermission->timestamps = false;
					$insRolePermission->save();
				}
			}	
			
			$userData = User::select('users.id','users.first_name','users_roles.role_id')->leftJoin('staff', 'users.id', '=', 'staff.staff_user_id')->where('staff.user_id',$AdminId)->leftJoin('users_roles', 'users.id', '=', 'users_roles.user_id')->where('users.is_admin',1)->get();
				
			foreach($userData as $key => $users) {
				if($users->role_id > 0) {
					$rolePermission = RolePermission::select('permission_id')->where('role_id',$users->role_id)->where('user_id',$AdminId)->get()->toArray();
					
					$userPerm = user::find($users->id);
					$userPerm->permissions()->detach();
					$userPerm->permissions()->attach($rolePermission);
				}
			}
			
			$data["status"] = true;
			$data["message"] = array("Permission has been saved succesfully.");
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