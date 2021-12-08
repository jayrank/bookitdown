<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Appointments;
use App\Models\Invoice;
use App\Models\AppointmentServices;
use App\Models\Clients;
use App\Models\User;
use App\Models\Staff;
use App\JsonReturn;
use DataTables;
use Carbon\Carbon;
use Session;
use DB;
use date;
use File;
use Crypt;
use App\Http\Middleware\PreventBackHistory;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['autologin']]);
		$this->middleware('PreventBackHistory');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
	
	public function autologin($id, $email)
    {
		$userId = base64_decode($id);
		$email = base64_decode($email);
		
		$checkUser = User::select('id')->where('id',$userId)->where('email',$email)->first();
		
		if(!empty($checkUser)) {
			Auth::loginUsingId($checkUser->id);
			return redirect()->route('home');
		} else {
			echo "<h2>It's seems your credentials are not matching with our platform. <br>Try again after resetting your password.</h2>";
		}		
	}	
	
    public function index()
    {
		if (Auth::user()->can('home')) 
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
			
			$today = Carbon::now()->firstOfMonth()->subMonth(1);
			$prmonth = $today->format('m');

			// $ap = Appointments::where('user_id',$AdminId)->where('appointment_status',0)->with('apservice')->orderBy('id','desc')->take(10)->paginate(10);

			$ap = AppointmentServices::leftJoin('appointments', 'appointments.id', 'appointment_services.appointment_id')
									->leftJoin('services_price', 'services_price.id', 'appointment_services.service_price_id')
									->leftJoin('services', 'services.id', 'services_price.service_id')
									->select('appointment_services.*', 'services.service_name')
									->where('appointments.user_id',$AdminId)
									->where('appointments.appointment_status',0)
									->orderBy('appointments.appointment_date','desc')
									->take(10)
									->paginate(10);
			

			$ser_ap = AppointmentServices::where('appointment_services.user_id',$AdminId)
						->leftJoin('services_price', 'services_price.id', 'appointment_services.service_price_id')
						->leftJoin('services', 'services.id', 'services_price.service_id')
						->select(DB::raw('appointment_services.service_price_id, count(appointment_services.service_price_id) as total, services.service_name'))
						->groupBy('appointment_services.service_price_id')
						->whereMonth('appointment_services.created_at', date('m'))
						->orderBy(DB::raw('count(appointment_services.service_price_id)'), 'DESC')
						->limit(5)
						->get()
						->toArray();

			if(is_array($ser_ap) && !empty($ser_ap)) {
				$servicePriceId = array_column($ser_ap, 'service_price_id');

				$ser_prap = AppointmentServices::where('appointment_services.user_id',$AdminId)
							->leftJoin('services_price', 'services_price.id', 'appointment_services.service_price_id')
							->leftJoin('services', 'services.id', 'services_price.service_id')
							->select(DB::raw('appointment_services.service_price_id, count(appointment_services.service_price_id) as total, services.service_name'))
							->groupBy('appointment_services.service_price_id')
							->whereMonth('appointment_services.created_at', $prmonth)
							->whereIn('appointment_services.service_price_id', $servicePriceId)
							->orderBy(DB::raw('FIELD(appointment_services.service_price_id, '.implode(',', $servicePriceId).' )'))
							->limit(5)
							->get()
							->toArray();
			} else {
				$ser_prap = [];
			}

			$thismstaff = AppointmentServices::where('user_id',$AdminId)
						->leftJoin('users', 'users.id', 'appointment_services.staff_user_id')
						->select(DB::raw('appointment_services.staff_user_id, count(appointment_services.staff_user_id) as total, sum(appointment_services.special_price) as cashtotal, users.first_name, users.last_name'))
						->groupBy('appointment_services.staff_user_id')
						->orderBy(DB::raw('sum(appointment_services.special_price)'), 'DESC')
						->whereMonth('appointment_services.created_at', date('m'))
						->limit(5)
						->get()
						->toArray();

			if(is_array($thismstaff) && !empty($thismstaff)) {
				$staffUserId = array_column($thismstaff, 'staff_user_id');

				$prmstaff = AppointmentServices::where('user_id',$AdminId)
								->leftJoin('users', 'users.id', 'appointment_services.staff_user_id')
								->select(DB::raw('appointment_services.staff_user_id, count(appointment_services.staff_user_id) as total, sum(appointment_services.special_price) as cashtotal, users.first_name, users.last_name'))
								->groupBy('appointment_services.staff_user_id')
								->whereIn('appointment_services.staff_user_id', $staffUserId)
								->orderBy(DB::raw('FIELD(appointment_services.staff_user_id, '.implode(',', $staffUserId).' )'))
								->whereMonth('appointment_services.created_at', $prmonth)
								->limit(5)
								->get()
								->toArray();
			} else {
				$prmstaff = [];
			}

			//end
			//income
			$from = Carbon::today()->subDays(7);
			$to = Carbon::today();
			//last week
			$toDay = Carbon::today();
			$startdate = $toDay->startOfWeek()->subDays(7);
			$currentWeekDate = [];
			for ($i=0; $i <7 ; $i++) {
				$currentWeekDate[$i]['weekdate'] = $startdate->addDay(1)->format('d-m-Y');
				
			}
			//
			//day total
			$currentWeekdaytotal = [];
			$toDay = Carbon::today();
			$startdatetotal = $toDay->subDays(7);//startOfWeek()->
			for ($i=0; $i <7 ; $i++) {
				$currentWeekdaytotal[$i]['weekdate'] = $startdatetotal->addDay(1)->format('Y-m-d');
				$currentWeekdaytotal[$i]['invoce'] = Invoice::where('user_id',$AdminId)->where('invoice_status',1)->whereDate('payment_date', '=', $currentWeekdaytotal[$i]['weekdate'])->select(DB::raw('payment_date, sum(inovice_final_total) as cashtotal'))->groupBy('payment_date')->get('cashtotal');
				$currentWeekdaytotal[$i]['appointment'] = AppointmentServices::where('user_id',$AdminId)->select(DB::raw('sum(special_price) as sum_special_price'))->whereDate('appointment_date', '=', $currentWeekdaytotal[$i]['weekdate'])->get('appointment_total'); //->where('appointment_services.user_id', $AdminId)
			}
			//
			$invoce = Invoice::where('user_id',$AdminId)->where('invoice_status',1)->whereBetween('payment_date', [$from,$to])->select(DB::raw('payment_date, sum(inovice_final_total) as cashtotal'))->groupBy('payment_date')->get('cashtotal');
			$intotal = $invoce->sum('cashtotal');
			//end
			
			//client total
			$from1 = Carbon::today()->subDays(7)->format('Y-m-d');
			$to1 = Carbon::today()->format('Y-m-d');
			$client = DB::table('clients')->where('user_id',$AdminId)->where('is_deleted',0)->select(DB::raw('DATE(created_at) day'))->get();//
			$new = $client->whereBetween('day', [$from1,$to1]);

			$clienttotalday = [];
			$toDay = Carbon::today();
			$startclienttotal = $toDay->subDays(7);//startOfWeek()->
			for ($i=0; $i <7 ; $i++) {
				$clienttotalday[$i]['weekdate'] = $startclienttotal->addDay(1)->format('Y-m-d');
				$clienttotalday[$i]['client'] = DB::table('clients')->select(DB::raw('DATE(created_at) day'))->where('user_id',$AdminId)->where('is_deleted',0)->whereDate('created_at', '=', $clienttotalday[$i]['weekdate'])->select(DB::raw('created_at, count(created_at) as clienttotal'))->groupBy('created_at')->get();
			}
			$clienttotal = $new->count();
			//end
			//appointments
			$toDayapp = Carbon::today()->format('Y-m-d');
			$dateAfter7Days = Carbon::today()->addDays(7);

			// $appo = Appointments::where('user_id',$AdminId)->where('appointment_status',0)->with('apservice')->orderBy('id','desc')->whereDate('appointment_date', '=', $toDayapp)->take(10)->get();

			$appo = AppointmentServices::leftJoin('appointments', 'appointments.id', 'appointment_services.appointment_id')
									->leftJoin('services_price', 'services_price.id', 'appointment_services.service_price_id')
									->leftJoin('services', 'services.id', 'services_price.service_id')
									->select('appointment_services.*', 'services.service_name')
									->where('appointments.user_id',$AdminId)
									->where('appointments.appointment_status',0)
									->whereDate('appointments.appointment_date', '=', $toDayapp)
									->whereTime('appointment_services.start_time', '>', date('H:i:s'))
									->orderBy('appointments.id','desc')
									->take(10)
									->get();

			/*echo "<pre>";
			print_r($appo);
			exit();*/
			$upcomingAppointmentsTemp = Appointments::where('user_id',$AdminId)
									// ->where('appointment_status',0)
									->whereDate('appointment_date', '>', $toDayapp)
									->whereDate('appointment_date', '<=', $dateAfter7Days)
									->groupBy('appointment_date')
									->select('appointment_date',DB::raw('sum(appointments.is_cancelled = 0) as confirmed_appointments'),DB::raw('sum(appointments.is_cancelled = 1) as cancelled_appointments'))
									->get()
									->toArray();

			$tempDate = $toDayapp;//$toDayapp;

			$upcomingAppointments = [];
			$totalAppointments = 0;
			if(!empty($upcomingAppointmentsTemp)) {
				$count = 0;
				while($count < 7) {
					$count++;

					$tempDate = date('Y-m-d', strtotime($tempDate.' +1 day'));

					$tempKey = array_search($tempDate, array_column($upcomingAppointmentsTemp, 'appointment_date'));

					if($tempKey !== FALSE) {
						$upcomingAppointments[] = [
							'appointment_date' => date('D d', strtotime($tempDate)),
							'confirmed_appointments' => $upcomingAppointmentsTemp[ $tempKey ]['confirmed_appointments'],
							'cancelled_appointments' => $upcomingAppointmentsTemp[ $tempKey ]['cancelled_appointments']
						];

						$totalAppointments += ($upcomingAppointmentsTemp[ $tempKey ]['confirmed_appointments'] + $upcomingAppointmentsTemp[ $tempKey ]['cancelled_appointments']);
					} else {
						$upcomingAppointments[] = [
							'appointment_date' => date('D d', strtotime($tempDate)),
							'confirmed_appointments' => 0,
							'cancelled_appointments' => 0
						];
					}
				}
			}

			//end
			return view('index',compact('ap','appo','ser_ap','ser_prap','thismstaff', 'prmstaff','currentWeekdaytotal','intotal','currentWeekDate','clienttotal','clienttotalday', 'upcomingAppointments', 'totalAppointments'));
		} else {
			return redirect(route('calander'));
		}
    }

	public function my_profile()
    {
		$CurrentUser = auth::user();
		$is_admin = $CurrentUser->is_admin;
		$userData = User::find(Auth::id());
		
		return view('myprofile', compact('userData'));
	}	
    
	public function updateMyProfile(Request $request)
	{
        $rules = [
            'first_name' => 'required',
			'last_name' => 'required',
			'mobile_number' => 'required',
			'current_password' => 'required',
			'new_password' => 'required',
			'confirm_password' => 'required|same:new_password'
        ];

        $input = $request->only(
            'first_name',
            'last_name',
            'mobile_number',
            'current_password',
            'new_password',
            'confirm_password'
        );

        $validator = Validator::make($input, $rules);
        if ($validator->fails()) 
        {
			return JsonReturn::error($validator->messages());
        }
		else
		{
			$userData = User::find(Auth::id());
			
			if (!Hash::check($request->current_password, $userData->password)) {
				return JsonReturn::error(array("messages" => array("Current password does not match!")));
			} else {	
				$userData->first_name = $request->first_name;
				$userData->last_name = $request->last_name;
				$userData->phone_number = $request->mobile_number;
				$userData->password = Hash::make($request->new_password);
				$userData->save();
				
				Session::flash('message', 'Profile has been updated succesfully.');
				$data["status"] = true;
				$data["redirect"] = route('my_profile');
				return JsonReturn::success($data);
			}	
		}		
	}
	
	public function updateProfileImage(Request $request)
	{
		if ($request->ajax()) 
		{
			$folderPath = public_path('uploads/profilepic/');
			$image_parts = explode(";base64,", $request->image);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $fileName = time() . '.png';
            $file = $folderPath . $fileName;
            file_put_contents($file, $image_base64);
			
			$userData = User::find(Auth::id());

			if(file_exists(public_path('uploads/profilepic/'.$userData->profile_pic))){
				unlink(public_path('uploads/profilepic/'.$userData->profile_pic));
			}	
			
			$userData->profile_pic = $fileName;
			$userData->save();
			
			Session::flash('message', 'Profile photo has been updated succesfully.');
			$data["status"] = true;
			$data["msg"] = $fileName;
			return JsonReturn::success($data);
		}
		
	}	

    public function appointmentSearch(Request $request)
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
		
        $searchText = isset($request->searchText) ? trim($request->searchText) : '';

        $appointments = AppointmentServices::where('appointments.user_id',$AdminId)
                        ->leftJoin('appointments', 'appointments.id', 'appointment_services.appointment_id')
                        ->leftJoin('services_price', 'services_price.id', 'appointment_services.service_price_id')
                        ->leftJoin('services', 'services.id', 'services_price.service_id')
                        ->leftJoin('users', 'users.id', 'appointment_services.staff_user_id')
                        ->whereDate('appointment_services.appointment_date', date('Y-m-d'))
                        ->select('appointments.id', 'appointments.appointment_date', 'appointment_services.start_time', 'appointments.appointment_status', 'services.service_name', 'appointment_services.duration', 'appointment_services.extra_time_duration', 'appointment_services.special_price', 'users.first_name', 'users.last_name' )
                        ->orderBy('appointment_services.start_time','asc');

        if($searchText != '') {
            $appointments->where('appointment_services.appointment_id', 'like', '%'.$searchText.'%');
        }

        $appointments = $appointments->get()
                        ->toArray();

        if(!empty($appointments)) {
            foreach ($appointments as $key => $value) {
                $appointments[$key]['encrypt_id'] = Crypt::encryptString($value['id']);
            }
        }

        $data['data'] = $appointments;                       
        $data["status"] = true;
        $data["msg"] = 'Success';
        return JsonReturn::success($data);
    }

    public function clientSearch(Request $request)
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
		
        $searchText = isset($request->searchText) ? trim($request->searchText) : '';

        $clients = Clients::where('clients.user_id', $AdminId)
                                ->select('clients.firstname', 'clients.lastname', 'clients.mobileno', 'clients.email', 'clients.id')
                                ->orderBy('clients.id', 'DESC');
                        // ->groupBy('appointment_services.appointment_id');

        if($searchText != '') {
            $clients->where(function($query) use ($searchText){
                 $query->where('firstname', 'like', $searchText.'%');
                 $query->orwhere('lastname', 'like', $searchText.'%');
                 $query->orwhere('mobileno', 'like', $searchText.'%');
                 $query->orwhere('email', 'like', $searchText.'%');
             });
        }

        $clients = $clients->get()
                        ->toArray();

        $data['data'] = $clients;                       
        $data["status"] = true;
        $data["msg"] = 'Success';
        return JsonReturn::success($data);
    }
}
