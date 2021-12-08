<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Businesstype;
use App\Models\Permission;
use App\Models\RolePermission;
use App\Models\Role;
use App\Models\Location;
use App\Models\Location_business_type;
use App\Models\CancellationReasons;
use App\Models\StaffLocations;
use App\Models\Staff;
use App\Models\SalesClient;
use App\Models\SalesAgreement;
use App\Models\SalesUsers;
use App\Models\SalesDeal;
use App\Models\SalesUserDeal;
use App\Models\SalesPlan;
use App\Models\SalesClientPermissions;
use App\Models\SalesCompany;
use App\Models\HubUsers;
use App\Models\HubLocation;
use App\Models\AccountSetting;
use App\Models\SalesLocation;
use App\Models\SalesTimeline;
use App\Models\HubGroup;
use App\Models\HubTerms;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Mail\AssignDealNotification;
use Session;
use Mail;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    //protected $redirectTo = RouteServiceProvider::HOME;
    protected $redirectTo = "/partners/home";

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
	
	public function showRegistrationForm()
    {
        $page_title = 'Register';
		
		$businesstypes = Businesstype::select('*')->get();
		
        return view('auth.register', compact('page_title', 'businesstypes'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'business_type' => ['required'],
            'country' => ['required'],
            'timezone' => ['required'],
            'currency' => ['required'],
            'password' => ['required', 'string', 'min:8'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    /* protected function create(array $data)
    {
		$admin_role = Role::where('slug','admin')->first();
		$admin_permission = Permission::select('*')->get();
		
		$userInsert = new User();
		$userInsert->first_name = $data['first_name'];
		$userInsert->last_name = $data['last_name'];
		$userInsert->email = $data['email'];
		$userInsert->country_code = $data['country_code'];
		$userInsert->phone_number = $data['phone_number'];
		$userInsert->business_type = $data['business_type'];
		$userInsert->country = $data['country'];
		$userInsert->timezone = $data['timezone'];
		$userInsert->currency = $data['currency'];
		$userInsert->language = "en";
		$userInsert->password = Hash::make($data['password']);
		$userInsert->save();
		
		$INSERTED_USER_ID = $userInsert->id;
		
		$permissionLists = Permission::select('id','slug')->get();
		
		foreach($permissionLists as $key => $val) {
			
			$insRolePermission1 = new RolePermission();
			$insRolePermission1->user_id = $INSERTED_USER_ID;
			$insRolePermission1->role_id = 1;
			$insRolePermission1->permission_id = $val->id;
			$insRolePermission1->timestamps = false;
			$insRolePermission1->save();
			
			$insRolePermission2 = new RolePermission();
			$insRolePermission2->user_id = $INSERTED_USER_ID;
			$insRolePermission2->role_id = 2;
			$insRolePermission2->permission_id = $val->id;
			$insRolePermission2->timestamps = false;
			$insRolePermission2->save();
			
			$insRolePermission3 = new RolePermission();
			$insRolePermission3->user_id = $INSERTED_USER_ID;
			$insRolePermission3->role_id = 3;
			$insRolePermission3->permission_id = $val->id;
			$insRolePermission3->timestamps = false;
			$insRolePermission3->save();
		
			$insRolePermission4 = new RolePermission();
			$insRolePermission4->user_id = $INSERTED_USER_ID;
			$insRolePermission4->role_id = 4;
			$insRolePermission4->permission_id = $val->id;
			$insRolePermission4->timestamps = false;
			$insRolePermission4->save();
		
			$insRolePermission5 = new RolePermission();
			$insRolePermission5->user_id = $INSERTED_USER_ID;
			$insRolePermission5->role_id = 5;
			$insRolePermission5->permission_id = $val->id;
			$insRolePermission5->timestamps = false;
			$insRolePermission5->save();
		}
		
		$userInsert->roles()->attach($admin_role);
		$userInsert->permissions()->attach($admin_permission);
		
		$defaultLocation = new Location();
		$defaultLocation->user_id        = $INSERTED_USER_ID;
		$defaultLocation->location_name  = $data['first_name'].' '.$data['last_name'];
		$defaultLocation->country_code   = $data['country_code'];
		$defaultLocation->location_phone = $data['phone_number'];
		$defaultLocation->location_email = $data['email'];
		$defaultLocation->save();
		
		$INSERTED_LOCATION_ID = $defaultLocation->id;
		
		$defaultLocationBusinessType = new Location_business_type();
		$defaultLocationBusinessType->user_id               = $INSERTED_USER_ID; 
		$defaultLocationBusinessType->location_id           = $INSERTED_LOCATION_ID; 
		$defaultLocationBusinessType->business_type_id      = $data['business_type']; 
		$defaultLocationBusinessType->is_main_business_type = 1; 
		$defaultLocationBusinessType->created_at            = date("Y-m-d H:i:s"); 
		$defaultLocationBusinessType->save();
		
		$defaultCancellationReasons = new CancellationReasons();
		$defaultCancellationReasons->user_id    = $INSERTED_USER_ID;
		$defaultCancellationReasons->reason     = 'Appointment made by mistake';
		$defaultCancellationReasons->is_default = 1;
		$defaultCancellationReasons->created_at = date("Y-m-d H:i:s");
		$defaultCancellationReasons->save();
		
        $addStaff = new Staff();
        $addStaff->user_id = $INSERTED_USER_ID;
        $addStaff->staff_user_id = $INSERTED_USER_ID;
        $addStaff->user_permission = 5;
        $addStaff->is_appointment_booked = 1;
        $addStaff->created_at = date("Y-m-d H:i:s");
        $addStaff->updated_at = date("Y-m-d H:i:s");
		$addStaff->save();

		$addStaffLocation = new StaffLocations();
		$addStaffLocation->staff_id = $addStaff->id;
		$addStaffLocation->staff_user_id = $INSERTED_USER_ID;
		$addStaffLocation->location_id = $INSERTED_LOCATION_ID;
		$addStaffLocation->created_at = date("Y-m-d H:i:s");
		$addStaffLocation->updated_at = date("Y-m-d H:i:s");
		$addStaffLocation->save();

		Session::flash('success', 'Your account has been created successfully.');
		
		return $userInsert;
    } */
	
	public function register(Request $request)
    {
		$checkClient = SalesClient::select('id')->where('username', $request->email)->first();
		
		if(!empty($checkClient)) 
		{
			$arr = array(
				'email' => array("The email has already been taken.")
			);
			return response()->json(['success' => false, 'errors' => $arr], 422);
		}
		else 
		{
			$admin_role = Role::where('slug','admin')->first();
			$admin_permission = Permission::select('*')->get();
			
			$userInsert = new User();
			$userInsert->first_name = $request->first_name;
			$userInsert->last_name = $request->last_name;
			$userInsert->email = $request->email;
			$userInsert->country_code = $request->country_code;
			$userInsert->phone_number = $request->phone_number;
			$userInsert->business_type = $request->business_type;
			$userInsert->country = $request->loc_country;
			$userInsert->timezone = $request->timezone;
			$userInsert->currency = $request->currency;
			$userInsert->language = "en";
			$userInsert->is_active = 1;
			$userInsert->password = Hash::make($request->password);
			$userInsert->save();
			
			$INSERTED_USER_ID = $userInsert->id;
			
			$permissionLists = Permission::select('id','slug')->whereNotIn('id', [38,39,40,43,44,45])->get();
			
			foreach($permissionLists as $key => $val) 
			{	
				$insRolePermission1 = new RolePermission();
				$insRolePermission1->user_id = $INSERTED_USER_ID;
				$insRolePermission1->role_id = 1;
				$insRolePermission1->permission_id = $val->id;
				$insRolePermission1->timestamps = false;
				$insRolePermission1->save();
				
				$insRolePermission2 = new RolePermission();
				$insRolePermission2->user_id = $INSERTED_USER_ID;
				$insRolePermission2->role_id = 2;
				$insRolePermission2->permission_id = $val->id;
				$insRolePermission2->timestamps = false;
				$insRolePermission2->save();
				
				$insRolePermission3 = new RolePermission();
				$insRolePermission3->user_id = $INSERTED_USER_ID;
				$insRolePermission3->role_id = 3;
				$insRolePermission3->permission_id = $val->id;
				$insRolePermission3->timestamps = false;
				$insRolePermission3->save();
			
				$insRolePermission4 = new RolePermission();
				$insRolePermission4->user_id = $INSERTED_USER_ID;
				$insRolePermission4->role_id = 4;
				$insRolePermission4->permission_id = $val->id;
				$insRolePermission4->timestamps = false;
				$insRolePermission4->save();
			
				$insRolePermission5 = new RolePermission();
				$insRolePermission5->user_id = $INSERTED_USER_ID;
				$insRolePermission5->role_id = 5;
				$insRolePermission5->permission_id = $val->id;
				$insRolePermission5->timestamps = false;
				$insRolePermission5->save();
			}
			
			$userInsert->roles()->attach($admin_role);
			$userInsert->permissions()->attach($admin_permission);
			
			$insAccountSetting = new AccountSetting();
			$insAccountSetting->user_id = $INSERTED_USER_ID;
			$insAccountSetting->business_name = $request->company_name;
			$insAccountSetting->timezone = $request->timezone;
			$insAccountSetting->save();
			
			$unique_id = "LO".$this->unique_code(40).date("YmdHis");
			$defaultLocation = new Location();
			$defaultLocation->user_id        = $INSERTED_USER_ID;
			$defaultLocation->unique_id = $unique_id;
			$defaultLocation->location_name  = $request->company_name;
			$defaultLocation->location_address  = $request->address;
			$defaultLocation->location_latitude  = $request->lat;
			$defaultLocation->location_longitude  = $request->lng;
			$defaultLocation->loc_address  = $request->loc_address;
			$defaultLocation->loc_apt  = $request->loc_apt;
			$defaultLocation->loc_district  = $request->loc_district;
			$defaultLocation->loc_city  = $request->loc_city;
			$defaultLocation->loc_region  = $request->loc_region;
			$defaultLocation->loc_county  = $request->loc_county;
			$defaultLocation->loc_postcode  = $request->loc_postcode;
			$defaultLocation->loc_country  = $request->loc_country;
			$defaultLocation->country_code   = $request->country_code;
			$defaultLocation->location_phone = $request->phone_number;
			$defaultLocation->location_email = $request->email;
			$defaultLocation->save();
			$INSERTED_LOCATION_ID = $defaultLocation->id;
			
			$defaultCancellationReasons = new CancellationReasons();
			$defaultCancellationReasons->user_id    = $INSERTED_USER_ID;
			$defaultCancellationReasons->reason     = 'Appointment made by mistake';
			$defaultCancellationReasons->is_default = 1;
			$defaultCancellationReasons->created_at = date("Y-m-d H:i:s");
			$defaultCancellationReasons->save();
			
			$addStaff = new Staff();
			$addStaff->user_id = $INSERTED_USER_ID;
			$addStaff->staff_user_id = $INSERTED_USER_ID;
			$addStaff->user_permission = 1;
			$addStaff->is_appointment_booked = 1;
			$addStaff->created_at = date("Y-m-d H:i:s");
			$addStaff->updated_at = date("Y-m-d H:i:s");
			$addStaff->save();

			$addStaffLocation = new StaffLocations();
			$addStaffLocation->staff_id = $addStaff->id;
			$addStaffLocation->staff_user_id = $INSERTED_USER_ID;
			$addStaffLocation->location_id = $INSERTED_LOCATION_ID;
			$addStaffLocation->created_at = date("Y-m-d H:i:s");
			$addStaffLocation->updated_at = date("Y-m-d H:i:s");
			$addStaffLocation->save();
			
			// Create deal and assign to user
			$users = SalesUsers::select('id','first_name','lead_percentage','lead_current_weight','lead_count')->where('active', 1)->orderBy('id', 'desc')->get()->toArray();
			$hosts = array(); $total_lead = 0;
			
			foreach($users as $key => $val)
			{
				$tmp = array('weight' => $val['lead_percentage'], 'current_weight' => $val['lead_current_weight'], 'count' => $val['lead_count'], 'id' => $val['id']);
				$hosts[$val['first_name']] = $tmp;
				$total_lead += $val['lead_count'];
			}
			
			$result = array();
			$no_of_lead = 1;
			
			for($i = 0; $i < 1; $i++) {
				$this->Round_robin($hosts, $result);
			}
		
			$assign_name = $result[0];
			$assign_to = $hosts[$assign_name]['id'];
			
			foreach($hosts as $key => $val)
			{
				$salesUserUpd = SalesUsers::find($val['id']);
				$salesUserUpd->id = $val['id'];
				$salesUserUpd->lead_current_weight = $val['current_weight'];
				$salesUserUpd->lead_count = $val['count'];
				$salesUserUpd->save();
			}
			
			$insSalesDeal = new SalesDeal();
			$insSalesDeal->name = $request->first_name." ".$request->last_name;
			$insSalesDeal->email = $request->email;
			$insSalesDeal->country_code = $request->country_code;
			$insSalesDeal->phone = str_replace('-', '', $request->phone_number);
			$insSalesDeal->timezone = $request->timezone;
			$insSalesDeal->bussiness_country_code = $request->country_code;
			$insSalesDeal->business_phone = str_replace('-', '', $request->phone_number);
			$insSalesDeal->business_name = $request->company_name;
			$insSalesDeal->personal_email = $request->email;
			$insSalesDeal->user_id = $assign_to;
			$insSalesDeal->stage_id = 11;
			$insSalesDeal->pipeline_id = 2;
			$insSalesDeal->group_id = 1;
			$insSalesDeal->is_send_funnel = 1;
			$insSalesDeal->last_notification_sent_on = date("Y-m-d H:i:s");
			$insSalesDeal->created = date("Y-m-d H:i:s");
			$insSalesDeal->modified = date("Y-m-d H:i:s");
			$insSalesDeal->save();
			$dealId = $insSalesDeal->id;
			$userId = $assign_to;
			
			$assginData = SalesUsers::find($userId);
			
			$data_arr = array();
			$data_arr['first_name'] = $assginData->first_name;
			$data_arr['client_name'] = $request->first_name." ".$request->last_name;
			$data_arr['client_email'] = $request->email;
			$data_arr['client_phone'] = $request->country_code." ".str_replace('-', '', $request->phone_number);

			$FROM_EMAIL     = env("MAIL_FROM_ADDRESS", "info@ikotel.ca");
			$FROM_NAME      = 'Scheduledown';
			$TO_EMAIL       = $assginData->email;
			$CC_EMAIL       = 'tjcloudtest2@gmail.com';
			$SUBJECT        = 'Assign New Deal';
			$UniqueId       = $this->unique_code(30).time();
			
			$SendMail = Mail::to($TO_EMAIL)->cc([$CC_EMAIL])->send(new AssignDealNotification($FROM_EMAIL,$FROM_NAME,$SUBJECT,$UniqueId,$data_arr));
			
			$insSalesTimeline = new SalesTimeline();
			$insSalesTimeline->activity = $request->first_name." ".$request->last_name;
			$insSalesTimeline->module = "add_Deal";
			$insSalesTimeline->deal_id = $dealId;
			$insSalesTimeline->user_id = $userId;
			$insSalesTimeline->pipeline_id = 2;
			$insSalesTimeline->created = date("Y-m-d H:i:s");
			$insSalesTimeline->save();
			
			$insSalesUserDeal = new SalesUserDeal();
			$insSalesUserDeal->deal_id = $dealId;
			$insSalesUserDeal->user_id = $userId;
			$insSalesUserDeal->save();
			// End 
			
			// Create Sales Location
			$insSalesLocation = new SalesLocation();
			$insSalesLocation->unique_id = $unique_id;
			$insSalesLocation->deal_id = $dealId;
			$insSalesLocation->business_name = $request->company_name;
			$insSalesLocation->client_name = $request->first_name." ".$request->last_name;
			$insSalesLocation->mobile_number = str_replace('-', '', $request->phone_number);
			$insSalesLocation->email = $request->email;
			$insSalesLocation->price = 0;
			$insSalesLocation->price_with_tax = 0;
			$insSalesLocation->location_type = 1;
			$insSalesLocation->street_address = $request->address;
			$insSalesLocation->city = $request->loc_city;
			$insSalesLocation->postal_code = $request->loc_postcode;
			$insSalesLocation->lat = $request->lat;
			$insSalesLocation->longi = $request->lng;
			$insSalesLocation->created_at = date("Y-m-d H:i:s");
			$insSalesLocation->updated_at = date("Y-m-d H:i:s");
			$insSalesLocation->save();
			
			$defaultLocationBusinessType = new Location_business_type();
			$defaultLocationBusinessType->user_id               = $INSERTED_USER_ID; 
			$defaultLocationBusinessType->location_id           = $INSERTED_LOCATION_ID; 
			$defaultLocationBusinessType->business_type_id      = $request->business_type; 
			$defaultLocationBusinessType->is_main_business_type = 1; 
			$defaultLocationBusinessType->created_at            = date("Y-m-d H:i:s"); 
			$defaultLocationBusinessType->save();
			// end
			
			// Create agreement
			$insSalesAgreement = new SalesAgreement();
			$insSalesAgreement->deal_id = $dealId;
			$insSalesAgreement->user_id = $userId;
			$insSalesAgreement->sd_user_id = $INSERTED_USER_ID;
			$insSalesAgreement->holder_name = $request->first_name." ".$request->last_name;
			$insSalesAgreement->name = $request->first_name." ".$request->last_name;
			$insSalesAgreement->print_title = "Owner";
			$insSalesAgreement->billing_email = $request->email;
			$insSalesAgreement->personal_email = $request->email;
			$insSalesAgreement->billing_phone_country_code = $request->country_code;
			$insSalesAgreement->billing_phone = str_replace('-', '', $request->phone_number);
			$insSalesAgreement->billing_address = $request->address;
			$insSalesAgreement->lat = $request->lng;
			$insSalesAgreement->log = $request->lat;
			$insSalesAgreement->street_no = $request->loc_address;
			$insSalesAgreement->street_name = $request->loc_district;
			$insSalesAgreement->city = $request->loc_city;
			$insSalesAgreement->postal_code = $request->loc_postcode;
			$insSalesAgreement->providence = $request->loc_region;
			$insSalesAgreement->is_hub_access = 0;
			$insSalesAgreement->is_wifi_access = 0;
			$insSalesAgreement->is_signage_access = 0;
			$insSalesAgreement->is_menu_access = 0;
			$insSalesAgreement->is_scheduledown = 1;
			$insSalesAgreement->is_scheduledown_pro = 0;
			$insSalesAgreement->save();
			$lastAgreementId = $insSalesAgreement->id;
			//end
			
			// Create plan
			$insSalesPlan = new SalesPlan();
			$insSalesPlan->plan_title = $request->first_name." ".$request->last_name." plans";
			$insSalesPlan->plan_amount = 0;
			$insSalesPlan->deal_id = $dealId;
			$insSalesPlan->agreement_id = $lastAgreementId;
			$insSalesPlan->plan_type = 1;
			$insSalesPlan->created_date = date("Y-m-d H:i:s");
			$insSalesPlan->updated_date = date("Y-m-d H:i:s");
			$insSalesPlan->save();
			$lastPlanId = $insSalesPlan->id;
			// end
			
			//Create client
			$insSalesClient = new SalesClient();
			$insSalesClient->first_name = $request->first_name." ".$request->last_name;
			$insSalesClient->username = $request->email;
			$insSalesClient->email = $request->email;
			$insSalesClient->phone = str_replace('-', '', $request->phone_number);
			$insSalesClient->company_name = $request->company_name;
			$insSalesClient->password = md5($request->password);
			$insSalesClient->user_country = $request->loc_country;
			$insSalesClient->is_sendshipment = 0;
			$insSalesClient->is_hub_access = 0;
			$insSalesClient->is_wifi_access = 0;
			$insSalesClient->is_menu_access = 0;
			$insSalesClient->is_signage_access = 0;
			$insSalesClient->billing_type = 0;
			$insSalesClient->payment_currency = "cad";
			$insSalesClient->created = date("Y-m-d H:i:s");
			$insSalesClient->email_alerts = 0;
			$insSalesClient->email_alert_options = 0;
			$insSalesClient->register = 1;
			$insSalesClient->timezone = $request->timezone;
			$insSalesClient->api_type = 0;
			$insSalesClient->total_loc = 1;
			$insSalesClient->total_location = 1;
			$insSalesClient->is_email_verified = 1;
			$insSalesClient->is_otp_verified = 1;
			$insSalesClient->active = 1;
			$insSalesClient->is_payment_verified = 1;
			$insSalesClient->sd_user_id = $INSERTED_USER_ID;
			$insSalesClient->deal_id = $dealId;
			$insSalesClient->agreement_id = $lastAgreementId;
			$insSalesClient->save();
			$lastClientId = $insSalesClient->id;
			
			// Client permission
			$insSalesClientPermissions = new SalesClientPermissions();
			$insSalesClientPermissions->client_id = $lastClientId;
			$insSalesClientPermissions->plan_id = $lastPlanId;
			$insSalesClientPermissions->created_at = date("Y-m-d H:i:s");
			$insSalesClientPermissions->updated_at = date("Y-m-d H:i:s");
			$insSalesClientPermissions->save();
			
			// Create Company
			$insSalesCompany = new SalesCompany();
			$insSalesCompany->name = $request->company_name;
			$insSalesCompany->contact_person_name = $request->first_name." ".$request->last_name;
			$insSalesCompany->phone = str_replace('-', '', $request->phone_number);
			$insSalesCompany->phone_country_code = $request->country_code;
			$insSalesCompany->email = $request->email;
			$insSalesCompany->country = $request->loc_country;
			$insSalesCompany->address = $request->address;
			$insSalesCompany->billing_address = $request->address;
			$insSalesCompany->street_name = $request->loc_district;
			$insSalesCompany->street_no = $request->loc_address;
			$insSalesCompany->city = $request->loc_city;
			$insSalesCompany->state = $request->loc_region;
			$insSalesCompany->zip_code = $request->loc_postcode;
			$insSalesCompany->lat = $request->lat;
			$insSalesCompany->log = $request->lng;
			$insSalesCompany->status = 1;
			$insSalesCompany->created = date("Y-m-d H:i:s");
			$insSalesCompany->modified = date("Y-m-d H:i:s");
			$insSalesCompany->save();
			$lastCompanyId = $insSalesCompany->id;
			
			$salesDealUpd = SalesDeal::find($dealId);
			$salesDealUpd->id = $dealId;
			$salesDealUpd->company_id = $lastCompanyId;
			$salesDealUpd->is_company = 1;
			$salesDealUpd->save();
			
			// Create Hub User
			$insHubUser = new HubUsers();
			$insHubUser->id = $lastClientId;
			$insHubUser->first_name = $request->first_name;
			$insHubUser->username = $request->email;
			$insHubUser->email = $request->email;
			$insHubUser->phone = str_replace('-', '', $request->phone_number);
			$insHubUser->company_name = $request->company_name;
			$insHubUser->user_country = $request->loc_country;
			$insHubUser->password = md5($request->password);
			$insHubUser->register = 1;
			$insHubUser->timezone = $request->timezone;
			$insHubUser->api_type = 0;
			$insHubUser->total_loc = 1;
			$insHubUser->location = 1;
			$insHubUser->is_email_verified = 1;
			$insHubUser->is_otp_verified = 1;
			$insHubUser->active = 0;
			$insHubUser->package = $lastPlanId;
			$insHubUser->deal_id = $dealId;
			$insHubUser->agreement_id = $lastAgreementId;
			$insHubUser->created = date("Y-m-d H:i:s");
			$insHubUser->save(); 
			$lastHubUserId = $insHubUser->id; 
			
			$insHubLocation = new HubLocation();
			$insHubLocation->unique_id = $unique_id;
			$insHubLocation->user_id = $lastHubUserId;
			$insHubLocation->location_name = $request->company_name;
			$insHubLocation->location_address = $request->address;
			$insHubLocation->locationlat = $request->lat;
			$insHubLocation->locationlng = $request->lng;
			$insHubLocation->phone = str_replace('-', '', $request->phone_number);
			$insHubLocation->email_address = $request->email;
			$insHubLocation->create_date = date("Y-m-d H:i:s");
			$insHubLocation->save();
			$lastHubLocationId = $insHubLocation->id;
			
			$unique_id1 = $this->unique_code(40).time();
			$insHubGroup = new HubGroup();
			$insHubGroup->user_id = $lastHubUserId;
			$insHubGroup->group_type = '1';
			$insHubGroup->location = $lastHubLocationId;
			$insHubGroup->group_name = 'default group';
			$insHubGroup->keyword = $unique_id1;
			$insHubGroup->ifmember_message = 'You are already subscribed to this list';
			$insHubGroup->system_message = 'default message';
			$insHubGroup->auto_message = 'STOP to end. HELP for help. Msg&Data rates may apply';
			$insHubGroup->sms_type = 1;
			$insHubGroup->bithday_enable = 0;
			$insHubGroup->active = 1;
			$insHubGroup->notify_signup = 0;
			$insHubGroup->double_optin = 0;
			$insHubGroup->save();
			
			$terms_add = "Welcome 
				<br><br>
				Please accept the terms & conditions. By logging in to use one of the following systems
				<br><br>
				FREE WIFI,
				<br><br>
				Kiosk loyalty,
				<br><br>
				Reputation kiosk,
				<br><br>
				Waiver kiosk or any Kiosk,
				<br><br>
				Keyword opt-in,
				<br><br>
				Job application QR,
				<br><br>
				Poll and contest opt-in  or any of our other methods.
				<br><br>
				Terms &amp; Conditions
				<br><br>
				Connect
				<br><br>
				Read Terms &amp; Conditions
				<br><br>
				Terms and Conditions<br>
				TERMS, CONDITIONS AND NOTICES AGREEMENT (“Terms”)
				<br><br>
				BETWEEN CUSTOMER ANDNENTO, THE PROVIDER OF THE SERVICE ('Service')
				<br><br><br><br>

				REQUIRED DEVICES AND SERVICE AVAILABILITY
				<br><br>
				A laptop, pocket PC or handheld device ('your Device') with Wi-Fi 802.11b/g wireless capability is required to use the service. You need to ensure that your Device is compatible with 802.11b/g Wi-Fi service. Availability of the Service is subject to all memory, storage and other limitations of your Device.
				<br><br>
				Our Wi-Fi service is available to your Device only when it is in the operating range of our Wi-Fi hotspot location(s). The Service is subject to unavailability, including emergencies, third party service failures, transmission, equipment or network problems or limitations, interference, signal strength, and maintenance and repair. The Service may be interrupted, refused, limited or curtailed.
				<br><br>
				We are not responsible for data, messages or pages lost, not delivered or misdirected because of interruptions or performance issues with the Service, or the underlying network(s) and transmission equipment and systems. The accuracy and timeliness of data received is not guaranteed. Delays or omissions may occur. Actual network speed will vary based on your Device configuration, location, compression, network congestion and other factors.
				<br><br><br><br>

				PROHIBITED USE OF THE SERVICE / COMPLIANCE WITH LAW
				<br><br>
				Reproduction, retransmission, dissemination or resale of the Service, whether for profit or not, is prohibited without our express, advance, written permission. You may not share your IP address or ISP Internet connection with anyone, access the Service simultaneously through multiple units, or authorize any other individual or entity to use the Service.
				<br><br>
				You may not use the Service for any purpose that's unlawful, or in any manner that could damage our or others’ property or infringes with anyone’s intellectual property rights. You may not use the Service in any way that interferes with, harms or disrupts our system or other users.
				<br><br>
				We have the right, but not the obligation, to suspend or terminate your access and use of the Service, and to block or remove (in whole or in part) any communications and materials transmitted through ourService that we believe in our sole discretion may violate applicable law, this Agreement or a third party's rights, or that is otherwise inappropriate or unacceptable. We also have the right, but not the obligation, to monitor, intercept and disclose any transmissions over or using our facilities, and to provide subscriber billing, account, or use records, and related information under certain circumstances,such as response to lawful process, orders, warrants or subpoenas,or to protect our rights, property, and users.
				<br><br><br><br>

				The following are examples of inappropriate or unacceptable behaviour that may result in terminating your access to our Service:
				<br><br>
				Viewing/retrieving obscene or indecent speech or materials.
				Communicating defamatory or abusive language.<br>
				Using the Service to transmit, post, upload, or otherwise making available defamatory, harassing, abusive, or threatening material or language that encourages bodily harm, destruction of property or harasses another.<br>
				Forging or misrepresenting message headers, whether in whole or in part, to mask the originator of the message.
				Facilitating a Violation of these Terms.<br>
				Hacking.<br>
				Distribution of Internet viruses, Trojan horses, or other destructive activities.<br>
				Distributing information regarding the creation of and sending Internet viruses, worms, Trojan horses, pinging, flooding, mail-bombing, or denial of service attacks. Also, activities that disrupt the use of or interfere with the ability of others to effectively use the node or any connected network, system, service, or equipment.<br>
				Advertising, transmitting, or otherwise making available any software product, product, or service that is designed to violate these Terms of Use, which includes the facilitation of the means to spam, initiation of pinging, flooding, mail-bombing, denial of service attacks, and piracy of software.<br>
				The sale, transfer, or rental of the Service to customers, clients or other third parties, either directly or as part of a service or product created for resale.<br>
				Seeking information on passwords or data belonging to another user.
				Making unauthorized copies of proprietary software or offering unauthorized copies of proprietary software to others.
				Intercepting or examining the content of messages, files or communications in transit on a data network.<br>
				<br><br><br>

				CONTENT DISCLAIMER<br><br>

				The internet contains materials that you may find objectionable or offensive. We don't publish or control and are not responsible or liable for, any third-party information, content, services, products, software or other material that can be accessed through the Service. You are solely responsible for evaluating the accuracy, completeness, and usefulness of all services, products and other information, and the quality and merchantability, accuracy, timeliness or delivery of such services, products and other information. You're responsible for paying any charges that you incur from third parties through your use of the Service, and your personal information may be available to third parties that you access through the Service.<br><br><br><br>

				PRIVACY AND SECURITY<br><br>

				Wireless systems transmit voice and data communications over a complex network. The privacy and security of such voice and data transmissions cannot be guaranteed. You acknowledge that the Service is not inherently secure, and you understand that wireless communications can be intercepted by equipment and software designed for that purpose. We are not liable to you or any other party for any lack of privacy you experience while using the Service.<br><br><br><br>

				DISCLAIMER OF WARRANTIES
				<br><br>
				we are providing the Service on an'as is' and 'as available' basis, with no warranties whatsoever. In no event will we be liable for any direct, indirect, incidental, consequential, special, exemplary or any damages associated with your use of the Service. No advice was given by us or our representatives shall create a warranty. You assume all responsibility and risk associated with your use of the Service<br><br><br><br><br>

				NENTO PRIVACY POLICY<br><br><br><br>

				*Current as of September 19, 2019<br><br>

				We are strongly committed to protecting the privacy of your personal and confidential information. The policies below are applicable to public WiFi services (the “Services”) provided and maintained by Nento. (collectively, 'Nento,' the 'Company,''we,''us,' or 'our'). The use of information collected through our technology shall be limited to the purpose of providing the services for which Nento’s customers (each, a 'Client') have engaged in and to provide promotional information from the establishments in which you accessed our Services. We have established this privacy policy ('Privacy Policy') to let you know the kinds of personal information we may gather during your use of the Services, why we gather your information, what we use your personal information for, when we might disclose your personal information, and how you can manage your personal information. It also describes your choices regarding use, access and correction of your personal information.<br><br>

				Please be advised that the practices described in this Privacy Policy apply only to information gathered online by us when you access the Services, when you sign up to use our Services, and when you provide information or content to us. It does not apply to information that you may submit to us offline.<br><br>

				By using our Services, you are accepting the practices described in our Privacy Policy. If you do not agree to the terms of this Privacy Policy, please do not use the Services.<br><br>

				If you have any questions about this Privacy Policy or don’t see your concerns addressed here, you should contact us by email at privacy@nento.com.<br><br>

				What Information About Me Is Collected and Stored?<br><br>

				Nentoadheres to the applicable standards of ethical practices in all of our operations and is dedicated to protecting the privacy of all Clients. Our Privacy Policy is simple: Except as disclosed below, we don't sell, barter, give away, transfer or rent your personal information to any company, person or organization outside of Nento.
				<br><br>
				Personal Information<br><br>

				For purposes of registration and use of our Services, we collect identifiable information about you such as your name, age, gender, email address, telephone number, and any information provided by social media sites that you have used to sign-up for the Services (collectively referred to as 'Personal Information'). We also collect and store information that you that you provide through our help desk, customer service representatives, or requests for more information. We also may collect and store information about you that we receive from other sources, to enable us to update and correct the information contained in our database.<br><br>

				Passive Collection<br><br>

				As is true of most such Services, we gather certain information automatically and store it in log files. This information may include Internet protocol (IP) addresses, browser type, device type, Internet service provider (ISP), referring/exit pages, operating system, date/time stamp, Mac address and clickstream data. We may combine this automatically collected log information with other information we collect about you. We do this to improve the services we offer you, analytics, and functionality.<br><br>

				How We Use Your Information?<br><br>

				We use the information we learn from you to help us personalize and continually improve your experience with the Services. We use the information to fulfil support requests, deliver products and services, communicate with you about products, services, promotional offers, update our records and generally maintain your accounts with us. We also use this information to enable third parties to carry out technical, logistical or other functions on our behalf. Except as disclosed in this Privacy Policy, we do not use or disclose information about your individual visits/use of our Services or your Personal Information collected online to any companies not affiliated with us.<br><br>

				Customer Service and Feedback<br><br>

				We may use your Personal Information to fulfil support requests, provide customer service/support, track your compliance with the Company’s use policies, or for feedback purposes (to the extent that is explained when you provide the information).<br><br>

				Use of Passive Information<br><br>

				We use Passive Information to help us determine how people use the Services and who our users are so we can improve our Services and ensure that they are as appealing as we can make it for as many people as possible.<br><br>

				Third-Party Agents<br><br>

				We occasionally have third party agents, subsidiaries, affiliates and partners that perform functions on our behalfs, such as marketing, analytics, cloud storage and data processing, providing customer service/support, fraud protection, etc. These entities have access to the Personal Information needed to perform their functions and are contractually obligated to maintain the confidentiality and security of that Personal Information. They are restricted from using, selling, distributing or altering this data in any way other than to provide the requested services to Company.<br><br>

				Emergency Situations<br><br>

				In certain situations, we may be required to disclose personal data in response to lawful requests by public authorities, including to meet national security or law enforcement requirements. We may also use or disclose Personal Information if required to do so by law or in the good-faith belief that such action is necessary to (a) conform to applicable law or comply with legal process served on us or the Website; (b) protect and defend our rights or property, the Website or our users, and act under emergency circumstances to protect the personal safety of us, our affiliates, agents, or the users of the Website or the public, or respond to a government request. We do not sell your Personal Information to third parties.
				<br><br>
				What Steps Are Taken To Keep Personal Information Secure?<br><br>

				We are concerned with the security of the Personal Information associated with you. We have implemented commercially reasonable measures to prevent unauthorized access to your Personal Information. These measures include policies, procedures, employee training, physical access and technical measures relating to data access controls. In addition, we use standard security protocols and mechanisms such as secure socket layer technology (SSL) in the transmission of certain sensitive Personal Information, such as credit card information and login credentials.<br><br>

				While we try our best to safeguard your Personal Information once we receive it, no transmission of data over the Internet or any other public network can be guaranteed to be 100% secure. If you have any questions about security on our Web site, you can contact us at privacy@nento.com.<br><br>

				Data Retention<br><br>

				We comply with the following data retention policies with respect to your Personal Information: (1) we retain the information that is supplied when you sign-up for the Services; (2) we retain information collected passively when you access the Services; and (3) we may retain your support emails, web logs and accounting logs, though we reserve the right to destroy such information after a period of six months. The Company will retain Personal Information we process on behalf of our Clients for as long as needed to provide services to each Client. Also, we will retain and use your Personal Information as necessary to comply with our legal obligations, resolve disputes and enforce our agreements. If you wish to cancel your account or request that we no longer use your Personal Information to provide you services contact us at privacy@nento.com.
				<br><br>
				Your Obligations to Keep Your Access Rights Secure<br><br>

				You promise to: (a) provide true, accurate, current and complete information about yourself when you sign-up (the “Registration Data”) to use the Services and (b) maintain and promptly update the Registration Data to keep it true, accurate, current and complete. If you provide any information that is untrue, inaccurate, not current or incomplete, or the Company has reasonable grounds to suspect that such information is untrue, inaccurate, not current or incomplete, the Company has the right to suspend or terminate your account and refuse any and all current or future use of the Services. You are entirely responsible for the security and confidentiality of your password and account. Furthermore, you are entirely responsible for any and all activities that occur under your account. You agree to immediately notify us of any unauthorized use of your account or any other breach of security of which you become aware. You are responsible for taking precautions and providing security measures best suited for your situation and intended use of the Services. Please note that anyone able to provide your Personal Information may be able to access your account so you should take reasonable steps to protect this information.<br><br>

				Upon request, the Company will provide you with information about whether we hold any of your personal information. If your Personal Information changes or you wish to deactivate your account, you may correct, delete inaccuracies, or deactivate it by contacting us at privacy@nento.com. We will respond to your change request within a reasonable timeframe.<br><br>

				Service Provider, Sub-Processors/Onward Transfer<br><br>

				The Company may transfer Personal Information to companies that help us provide our service. We only transfer information to third parties that have appropriate technical safeguards and have committed to manage information according to applicable laws and regulations. Transfers to subsequent third parties are covered by the provisions in this Privacy Policy regarding notice and choice and the service agreements with our Clients.<br><br>

				What Happens When I Link To or From Another Web Site?<br><br>

				Our Services allow you to link to websites operated by affiliates of the Company or third parties. Please be advised that the practices described in this Privacy Policy for the Company do not apply to information gathered through these other websites. These other sites may also send their own cookies to you, collect your data or solicit your Personal Information. Always be aware of all websites you visit. We are not responsible for the actions and privacy policies of third parties and other websites. We encourage you to be aware of when you leave this Website and read the privacy policies of each and every website that you visit.<br><br>

				Testimonials<br><br>

				We display personal testimonials of satisfied customers on our site in addition to other endorsements. With your consent, we may post your testimonial along with your name. If you wish to update or delete your testimonial, you can contact us at privacy@nento.com.<br><br>

				Jurisdictions<br><br>

				We make every effort to protect the Personal Information of all users of the Website. We attempt to comply with all applicable data protection and consumer rights laws to the extent they may apply to our services, including complying with geographic restrictions on where Personal Information may be stored. If you are uncertain whether this Privacy Policy conflicts with the applicable local privacy laws where you are located, you should not submit your Personal Information to us or inquire with us at privacy@nento.com for more information.<br><br>

				We may change ownership or corporate organization while providing the Services. As a result, please be aware that we may transfer your information to another company that is affiliated with us or with which we have merged or by which we have been acquired. Under such circumstances, Company would to the extent possible require the acquiring party to follow the practices described in this Privacy Policy, as it may be amended from time to time. You will be notified via email and/or a prominent notice on our Website of any change in ownership or uses of your personal information, as well as any choices you may have regarding your Personal Information.<br><br>

				Changes to This Policy<br><br>

				We may update this Privacy Policy to reflect changes to our information practices. If we make any material changes we will notify you by email (sent to the email address specified in your account) or by means of a notice on this Website prior to the change becoming effective. We encourage you to periodically review this page for the latest information on our privacy practices.<br><br>

				How Do I Opt-Out?<br><br>

				You may always opt-out of receiving future email messages and newsletters from the Company. We provide you with the opportunity to opt-out of receiving communications from us by following the unsubscribe link located at the bottom of each communication.<br><br>

				TERMS, CONDITIONS AND NOTICES AGREEMENT (“Terms”)<br><br>

				BETWEEN CUSTOMER ANDNENTO, THE PROVIDER OF THE SERVICE ('Service')

				<br><br><br><br>

				REQUIRED DEVICES AND SERVICE AVAILABILITY<br><br>

				A laptop, pocket PC or handheld device ('your Device') with Wi-Fi 802.11b/g wireless capability is required to use the service. You need to ensure that your Device is compatible with 802.11b/g Wi-Fi service. Availability of the Service is subject to all memory, storage and other limitations of your Device.<br><br>

				Our Wi-Fi service is available to your Device only when it is in the operating range of our Wi-Fi hotspot location(s). The Service is subject to unavailability, including emergencies, third party service failures, transmission, equipment or network problems or limitations, interference, signal strength, and maintenance and repair. The Service may be interrupted, refused, limited or curtailed.	<br><br>

				We are not responsible for data, messages or pages lost, not delivered or misdirected because of interruptions or performance issues with the Service, or the underlying network(s) and transmission equipment and systems. The accuracy and timeliness of data received is not guaranteed. Delays or omissions may occur. Actual network speed will vary based on your Device configuration, location, compression, network congestion and other factors.<br><br>
				<br><br>

				PROHIBITED USE OF THE SERVICE / COMPLIANCE WITH LAW<br><br>

				Reproduction, retransmission, dissemination or resale of the Service, whether for profit or not, is prohibited without our express, advance, written permission. You may not share your IP address or ISP Internet connection with anyone, access the Service simultaneously through multiple units, or authorize any other individual or entity to use the Service.<br><br>

				You may not use the Service for any purpose that's unlawful, or in any manner that could damage our or others’ property or infringes with anyone’s intellectual property rights. You may not use the Service in any way that interferes with, harms or disrupts our system or other users.<br><br>

				We have the right, but not the obligation, to suspend or terminate your access and use of the Service, and to block or remove (in whole or in part) any communications and materials transmitted through our Service that we believe in our sole discretion may violate applicable law, this Agreement or a third party's rights, or that is otherwise inappropriate or unacceptable. We also have the right, but not the obligation, to monitor, intercept and disclose any transmissions over or using our facilities, and to provide subscriber billing, account, or use records, and related information under certain circumstances, such as response to lawful process, orders, warrants or subpoenas, or to protect our rights, property, and users.<br><br>

				The following are examples of inappropriate or unacceptable behaviour that may result in terminating your access to our Service:
				<br><br>
				Viewing/retrieving obscene or indecent speech or materials.
				Communicating defamatory or abusive language.<br>
				Using the Service to transmit, post, upload, or otherwise making available defamatory, harassing, abusive, or threatening material or language that encourages bodily harm, destruction of property or harasses another.<br>
				Forging or misrepresenting message headers, whether in whole or in part, to mask the originator of the message.
				Facilitating a Violation of these Terms.<br>
				Hacking.<br>
				Distribution of Internet viruses, Trojan horses, or other destructive activities.<br>
				Distributing information regarding the creation of and sending Internet viruses, worms, Trojan horses, pinging, flooding, mail-bombing, or denial of service attacks. Also, activities that disrupt the use of or interfere with the ability of others to effectively use the node or any connected network, system, service, or equipment.<br>
				Advertising, transmitting, or otherwise making available any software product, product, or service that is designed to violate these Terms of Use, which includes the facilitation of the means to spam, initiation of pinging, flooding, mail-bombing, denial of service attacks, and piracy of software.<br>
				The sale, transfer, or rental of the Service to customers, clients or other third parties, either directly or as part of a service or product created for resale.<br>
				Seeking information on passwords or data belonging to another user.
				Making unauthorized copies of proprietary software or offering unauthorized copies of proprietary software to others.
				Intercepting or examining the content of messages, files or communications in transit on a data network.<br><br><br>

				CONTENT DISCLAIMER<br><br>

				The internet contains materials that you may find objectionable or offensive. We don't publish or control and are not responsible or liable for, any third-party information, content, services, products, software or other material that can be accessed through the Service. You are solely responsible for evaluating the accuracy, completeness, and usefulness of all services, products and other information, and the quality and merchantability, accuracy, timeliness or delivery of such services, products and other information. You're responsible for paying any charges that you incur from third parties through your use of the Service, and your personal information may be available to third parties that you access through the Service.<br><br><br>

				PRIVACY AND SECURITY<br><br>

				Wireless systems transmit voice and data communications over a complex network. The privacy and security of such voice and data transmissions cannot be guaranteed. You acknowledge that the Service is not inherently secure, and you understand that wireless communications can be intercepted by equipment and software designed for that purpose. We are not liable to you or any other party for any lack of privacy you experience while using the Service.<br><br><br>

				DISCLAIMER OF WARRANTIES we are providing the Service on an 's is' and 'as available' basis, with no warranties whatsoever. In no event will we be liable for any direct, indirect, incidental, consequential, special, exemplary or any damages associated with your use of the Service. No advice was given by us or our representatives shall create a warranty. You assume all responsibility and risk associated with your use of the Service

				<br><br><br><br><br>

				NENTO PRIVACY POLICY<br><br><br><br>

				*Current as of September 19, 2019<br><br>

				We are strongly committed to protecting the privacy of your personal and confidential information. The policies below are applicable to public Wi-Fi services (the “Services”) provided and maintained by Nento. (Collectively, 'Nento,' the 'Company,''we,''us,' or 'our'). The use of information collected through our technology shall be limited to the purpose of providing the services for which Nento’s customers (each, a 'Client') have engaged in and to provide promotional information from the establishments in which you accessed our Services. We have established this privacy policy ('Privacy Policy') to let you know the kinds of personal information we may gather during your use of the Services, why we gather your information, what we use your personal information for, when we might disclose your personal information, and how you can manage your personal information. It also describes your choices regarding use, access and correction of your personal information.<br><br>

				Please be advised that the practices described in this Privacy Policy apply only to information gathered online by us when you access the Services, when you sign up to use our Services, and when you provide information or content to us. It does not apply to information that you may submit to us offline.<br><br>

				By using our Services, you are accepting the practices described in our Privacy Policy. If you do not agree to the terms of this Privacy Policy, please do not use the Services.<br><br>

				If you have any questions about this Privacy Policy or don’t see your concerns addressed here, you should contact us by email at privacy@nento.com.<br><br>

				What Information About Me Is Collected and Stored?<br><br>

				Nentoadheres to the applicable standards of ethical practices in all of our operations and is dedicated to protecting the privacy of all Clients. Our Privacy Policy is simple: Except as disclosed below, we don't sell, barter, give away, transfer or rent your personal information to any company, person or organization outside of Nento.
				<br><br>
				Personal Information<br><br>

				For purposes of registration and use of our Services, we collect identifiable information about you such as your name, age, gender, email address, telephone number, and any information provided by social media sites that you have used to sign-up for the Services (collectively referred to as 'Personal Information'). We also collect and store information that you that you provide through our help desk, customer service representatives, or requests for more information. We also may collect and store information about you that we receive from other sources, to enable us to update and correct the information contained in our database.<br><br>

				Passive Collection<br><br>

				As is true of most such Services, we gather certain information automatically and store it in log files. This information may include Internet protocol (IP) addresses, browser type, device type, Internet service provider (ISP), referring/exit pages, operating system, date/time stamp, Mac address and click stream data. We may combine this automatically collected log information with other information we collect about you. We do this to improve the services we offer you, analytics, and functionality.<br><br>

				How We Use Your Information?<br><br>

				We use the information we learn from you to help us personalize and continually improve your experience with the Services. We use the information to fulfil support requests, deliver products and services, communicate with you about products, services, promotional offers, update our records and generally maintain your accounts with us. We also use this information to enable third parties to carry out technical, logistical or other functions on our behalf. Except as disclosed in this Privacy Policy, we do not use or disclose information about your individual visits/use of our Services or your Personal Information collected online to any companies not affiliated with us.<br><br>

				Customer Service and Feedback<br><br>

				We may use your Personal Information to fulfil support requests, provide customer service/support, track your compliance with the Company’s use policies, or for feedback purposes (to the extent that is explained when you provide the information).<br><br>

				Use of Passive Information<br><br>

				We use Passive Information to help us determine how people use the Services and who our users are so we can improve our Services and ensure that they are as appealing as we can make it for as many people as possible.<br><br>

				Third-Party Agents<br><br>

				We occasionally have third party agents, subsidiaries, affiliates and partners that perform functions on our behalves, such as marketing, analytics, cloud storage and data processing, providing customer service/support, fraud protection, etc. These entities have access to the Personal Information needed to perform their functions and are contractually obligated to maintain the confidentiality and security of that Personal Information. They are restricted from using, selling, distributing or altering this data in any way other than to provide the requested services to Company.<br><br>

				Emergency Situations<br><br>

				In certain situations, we may be required to disclose personal data in response to lawful requests by public authorities, including to meet national security or law enforcement requirements. We may also use or disclose Personal Information if required to do so by law or in the good-faith belief that such action is necessary to (a) conform to applicable law or comply with legal process served on us or the Website; (b) protect and defend our rights or property, the Website or our users, and act under emergency circumstances to protect the personal safety of us, our affiliates, agents, or the users of the Website or the public, or respond to a government request. We do not sell your Personal Information to third parties.
				<br><br>
				What Steps Are Taken To Keep Personal Information Secure?<br><br>

				We are concerned with the security of the Personal Information associated with you. We have implemented commercially reasonable measures to prevent unauthorized access to your Personal Information. These measures include policies, procedures, employee training, physical access and technical measures relating to data access controls. In addition, we use standard security protocols and mechanisms such as secure socket layer technology (SSL) in the transmission of certain sensitive Personal Information, such as credit card information and login credentials.<br><br>

				While we try our best to safeguard your Personal Information once we receive it, no transmission of data over the Internet or any other public network can be guaranteed to be 100% secure. If you have any questions about security on our Web site, you can contact us at privacy@nento.com.<br><br>

				Data Retention<br><br>

				We comply with the following data retention policies with respect to your Personal Information: (1) we retain the information that is supplied when you sign-up for the Services; (2) we retain information collected passively when you access the Services; and (3) we may retain your support emails, web logs and accounting logs, though we reserve the right to destroy such information after a period of six months. The Company will retain Personal Information we process on behalf of our Clients for as long as needed to provide services to each Client. Also, we will retain and use your Personal Information as necessary to comply with our legal obligations, resolve disputes and enforce our agreements. If you wish to cancel your account or request that we no longer use your Personal Information to provide you services contact us at privacy@nento.com.
				<br><br>
				Your Obligations to Keep Your Access Rights Secure<br><br>

				You promise to: (a) provide true, accurate, current and complete information about yourself when you sign-up (the “Registration Data”) to use the Services and (b) maintain and promptly update the Registration Data to keep it true, accurate, current and complete. If you provide any information that is untrue, inaccurate, not current or incomplete or the Company has reasonable grounds to suspect that such information is untrue, inaccurate, not current or incomplete, the Company has the right to suspend or terminate your account and refuse any and all current or future use of the Services. You are entirely responsible for the security and confidentiality of your password and account. Furthermore, you are entirely responsible for any and all activities that occur under your account. You agree to immediately notify us of any unauthorized use of your account or any other breach of security of which you become aware. You are responsible for taking precautions and providing security measures best suited for your situation and intended use of the Services. Please note that anyone able to provide your Personal Information may be able to access your account so you should take reasonable steps to protect this information.<br><br>

				Upon request, the Company will provide you with information about whether we hold any of your personal information. If your Personal Information changes or you wish to deactivate your account, you may correct, delete inaccuracies, or deactivate it by contacting us at privacy@nento.com. We will respond to your change request within a reasonable timeframe.<br><br>

				Service Provider, Sub-Processors/Onward Transfer<br><br>

				The Company may transfer Personal Information to companies that help us provide our service. We only transfer information to third parties that have appropriate technical safeguards and have committed to manage information according to applicable laws and regulations. Transfers to subsequent third parties are covered by the provisions in this Privacy Policy regarding notice and choice and the service agreements with our Clients.<br><br>

				What Happens When I Link To or From Another Web Site?<br><br>

				Our Services allow you to link to websites operated by affiliates of the Company or third parties. Please be advised that the practices described in this Privacy Policy for the Company do not apply to information gathered through these other websites. These other sites may also send their own cookies to you, collect your data or solicit your Personal Information. Always be aware of all websites you visit. We are not responsible for the actions and privacy policies of third parties and other websites. We encourage you to be aware of when you leave this Website and read the privacy policies of each and every website that you visit.<br><br>

				Testimonials<br><br>

				We display personal testimonials of satisfied customers on our site in addition to other endorsements. With your consent, we may post your testimonial along with your name. If you wish to update or delete your testimonial, you can contact us at privacy@nento.com.<br><br>

				Jurisdictions<br><br>

				We make every effort to protect the Personal Information of all users of the Website. We attempt to comply with all applicable data protection and consumer rights laws to the extent they may apply to our services, including complying with geographic restrictions on where Personal Information may be stored. If you are uncertain whether this Privacy Policy conflicts with the applicable local privacy laws where you are located, you should not submit your Personal Information to us or inquire with us at privacy@nento.com for more information.<br><br>

				We may change ownership or corporate organization while providing the Services. As a result, please be aware that we may transfer your information to another company that is affiliated with us or with which we have merged or by which we have been acquired. Under such circumstances, Company would to the extent possible require the acquiring party to follow the practices described in this Privacy Policy, as it may be amended from time to time. You will be notified via email and/or a prominent notice on our Website of any change in ownership or uses of your personal information, as well as any choices you may have regarding your Personal Information.<br><br>

				Changes to This Policy<br><br>

				We may update this Privacy Policy to reflect changes to our information practices. If we make any material changes we will notify you by email (sent to the email address specified in your account) or by means of a notice on this Website prior to the change becoming effective. We encourage you to periodically review this page for the latest information on our privacy practices.<br><br>

				How Do I Opt-Out?<br><br>

				You may always opt-out of receiving future email messages and newsletters from the Company. We provide you with the opportunity to opt-out of receiving communications from us by following the unsubscribe link located at the bottom of each communication.<br><br>";
						
			$insHubTerms = new HubTerms();			
			$insHubTerms->user_id = $lastHubUserId;
			$insHubTerms->name = "Terms & Condition";
			$insHubTerms->terms_and_conditions = $terms_add;
			$insHubTerms->created_datetime = date("Y-m-d H:i:s");
			$insHubTerms->updated_datetime = date("Y-m-d H:i:s");
			$insHubTerms->save();
			
			Auth::loginUsingId($INSERTED_USER_ID);
			
			$data["status"] = true;
            $data["message"] = "Congratulation, register has been successfully.";
			return response()->json(['success' => true, 'message' => $data]);
		}
	}
	
	function round_robin(&$hosts, &$result)
	{
		$total = 0;
		$best = null;
		
		Foreach ($hosts as $key => $item) 
		{
			$current = &$hosts[$key];
			$weight = $current['weight'];

			$current['current_weight'] += $weight;
			$total += $weight;

			If ( ($best == null) || ($hosts[$best]['current_weight'] < $current['current_weight']))
			{
				$best = $key;
			}
		}

		$hosts[$best]['current_weight'] -= $total;
		$hosts[$best]['count']++;

		$result[] = $best;
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
