<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Staff;
use App\Models\Clients;
use App\Models\Location;	
use App\Models\Permission;
use App\Models\Country;
use App\Models\Appointments;
use App\Models\AppointmentServices;
use App\Models\Services;
use App\Models\ServicesPrice;
use App\Models\ServiceCategory;
use App\Models\StaffLocations;
use App\Models\Invoice;
use App\Models\InvoiceItems;
use App\Models\InvoiceTemplate;
use App\Models\InventoryProducts;
use App\Models\InventoryOrderLogs;
use App\Models\PaidPlan;
use App\Models\SoldPaidPlan;
use App\Models\Voucher;
use App\Models\SoldVoucher;
use App\Models\Taxes;
use App\Models\InvoiceTaxes;
use App\Models\StaffTip;
use App\Models\referralSources;
use App\Models\ClientConsultationForm;
use App\JsonReturn;
use DataTables;
use DB;
use Crypt;
use App\Exports\clientexport;
use Excel;


class ClientsController extends Controller
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

		$referralSources = referralSources::where('user_id', $AdminId)->where('is_deleted', 0)->where('is_active', 1)->get()->toArray();
		// dd($referralSources);

        return view('clients.index', compact('referralSources'));
    }
	
	public function getClientlist(Request $request)
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
			
            $clients = Clients::select('id', 'firstname', 'lastname','mo_country_code','mobileno', 'email', 'gender')->where('user_id', $AdminId)->where('is_deleted', '0')->orderBy('id', 'desc')->get();
			
			$data_arr = array();
			foreach($clients as $val)
			{
				if (Auth::user()->can('can_see_client_contact_info')) {
					$email = $val->email;
					$mobileno = $val->mo_country_code.' '.$val->mobileno;
				} else {
					$email = $this->hideEmail($val->email);
					$mobileno = preg_replace('~[+\d-](?=[\d-]{4})~', '*', $val->mo_country_code.$val->mobileno);
				}		
					
				$tempData = array(
					'id' => $val->id,
					'profile' => strtoupper(substr($val->firstname,0,1)),
					'name' => $val->firstname.' '.$val->lastname,
					'mobileno' => $mobileno,
					'email' => $email,
					'gender' => $val->gender
				);
				array_push($data_arr, $tempData);
			}
			
            return Datatables::of($data_arr)
                ->addIndexColumn()
				->editColumn('profile', function ($row) {
					$html = '<td><span>'.$row['profile'].'</span></td>';
					return $html;
				})
				->editColumn('name', function ($row) {
					$clientDetail = url("partners/view/".$row['id'].'/client');
					$html2 = '<td><a href="'.$clientDetail.'">'.$row['name'].'</a></td>';
					return $html2;
				})
				->setRowAttr([
					'data-id' => function($row) {
                        return $row['id'];
                    },
                    'class' => function($row) {
                        return "viewclient";
                    },
                ])
                ->rawColumns(['profile','name'])
                ->make(true);
        }	
	}
	
	function hideEmail($email)
	{
		$mail_parts = explode("@", $email);
		$length = strlen($mail_parts[0]);
		$show = floor($length/2);
		$hide = $length - $show;
		$replace = str_repeat("*", $hide);

		return substr_replace ( $mail_parts[0] , $replace , $show, $hide );
	}

	public function clientdownloadExcel(){

        return Excel::download(new clientexport(), 'Client.xls');
    }

	public function clientdownloadcsv(){

        return Excel::download(new clientexport(), 'Client.csv');
    }
	
	public function add(Request $request)
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
				'firstname' => 'required',
				'mobileno[main]' => 'max:10',
				'telephoneno[main]' => 'max:10'
			];

			$input = $request->only(
				'firstname'
			);

			$validator = Validator::make($input, $rules);
			if ($validator->fails()) {
				$data["status"] = true;
				$data["message"] = $validator->messages();
				return JsonReturn::error($data);
				// return JsonReturn::error($validator->messages());
			}

			if(!empty($request->date_['year']) && !empty($request->date_['month']) && !empty($request->date_['day']) ) {
				$birthdate = date('Y-m-d', strtotime( $request->date_['year'].'-'.$request->date_['month'].'-'.$request->date_['day'] ));
			} else {
				$birthdate = NULL;
			}
			$addClient = Clients::create([
				'user_id'                       => $AdminId,
				'firstname'                     => ($request->firstname) ? $request->firstname : '',
				'lastname'                      => ($request->lastname) ? $request->lastname : '',
				'mo_country_code'               => ($request->mo_country_code) ? $request->mo_country_code : '',
				'mobileno'                      => ($request->mobileno['main']) ? $request->mobileno['main'] : '',
				'tel_country_code'              => ($request->tel_country_code) ? $request->tel_country_code : '',
				'telephoneno'                   => ($request->telephoneno['main']) ? $request->telephoneno['main'] : '',
				'email'                         => ($request->email) ? $request->email : '',
				'send_notification_by'          => ($request->send_notification_by) ? $request->send_notification_by : '',
				'preferred_language'            => ($request->preferred_language) ? $request->preferred_language : '',
				'accept_marketing_notification' => ($request->accept_marketing_notification=='on') ? 1 : 0,
				'gender'                        => ($request->gender) ? $request->gender : '',
				'referral_source'               => ($request->referral_source) ? $request->referral_source : '',
				'birthdate'                     => $birthdate,
				'client_notes'                  => ($request->client_notes) ? $request->client_notes : '',
				'display_on_all_bookings'       => ($request->display_on_all_bookings== 'on') ? 1 : 0,
				'address'                       => ($request->address) ? $request->address : '',
				'suburb'                        => ($request->suburb) ? $request->suburb : '',
				'city'                          => ($request->city) ? $request->city : '',
				'state'                         => ($request->state) ? $request->state : '',
				'zipcode'                       => ($request->zipcode) ? $request->zipcode : '',
				'created_at'                    => date("Y-m-d H:i:s"),
				'updated_at'                    => date("Y-m-d H:i:s")
			]);	

			$data["status"] = true;
			$data["message"] = array("Client has been created succesfully.");
			return JsonReturn::success($data);	
		}
	}
	
	public function viewclient($id = null)
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
	
		$client_information = array();
		$client_information = Clients::where('id', $id)->get();
		
		$TotalSpend       = 0;
		
		// get previous appo
		$PreviousAppointment = Appointments::select('*')->where('client_id',$id)->where('user_id', $AdminId)->orderBy('id', 'desc')->get()->toArray();
		
		// get completed appo
		$CompletedAppointment = AppointmentServices::select('appointment_services.*')->join('appointments','appointments.id','=','appointment_services.appointment_id')->where('appointments.client_id',$id)->where('appointments.user_id', $AdminId)->where('appointments.appointment_status',1)->orderBy('id', 'desc')->get()->toArray();

		// get cancelled appo
		$CancelledAppointment = AppointmentServices::select('appointment_services.*')->join('appointments','appointments.id','=','appointment_services.appointment_id')->where('appointments.client_id',$id)->where('appointments.user_id', $AdminId)->where('appointments.is_cancelled',1)->orderBy('id', 'desc')->get()->toArray();
		
		// get noshow appo
		$NoshowAppointment = AppointmentServices::select('appointment_services.*')->join('appointments','appointments.id','=','appointment_services.appointment_id')->where('appointments.client_id',$id)->where('appointments.user_id', $AdminId)->where('appointments.is_noshow',1)->orderBy('id', 'desc')->get()->toArray();
		
		$PreviousAppointmentServices = AppointmentServices::select('appointment_services.*', 'appointments.is_cancelled', 'appointments.appointment_status', 'appointments.is_noshow')->join('appointments','appointments.id','=','appointment_services.appointment_id')->where('appointments.client_id',$id)->where('appointments.user_id', $AdminId)->orderBy('id', 'desc')->get()->toArray();
		$CountPreviousAppointmentServices = count($PreviousAppointmentServices);
		/*echo "<pre>";
		print_r($PreviousAppointmentServices);
		exit;*/
		
		// get sold products
		$soldProduct = InvoiceItems::select('invoice_items.quantity','invoice_items.item_price','invoice_items.created_at','inventory_products.product_name')->join('inventory_products','inventory_products.id','=','invoice_items.item_id')->where('invoice_items.client_id',$id)->where('invoice_items.item_type', 'product')->orderBy('invoice_items.id', 'desc')->get();
		
		// get outstanding amount
		$outStandingInvoices = Invoice::select(DB::raw('SUM(invoice.inovice_final_total) as total_outstanding'))->where('invoice.client_id',$id)->where('invoice.invoice_status',0)->orderBy('invoice.id', 'desc')->get();
		
		// get invoices
		$ClientInovices = array();
		$TotalSales     = 0;
		
		$clientInvoices = Invoice::select('invoice.id','invoice.inovice_final_total','invoice.payment_date','invoice.invoice_status')->where('invoice.client_id',$id)->orderBy('invoice.id', 'desc')->get();
		
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
			
				$TotalSales += $inv->inovice_final_total;
				
				$tempInvoices['invoice_status']       = $stats;
				$tempInvoices['invoice_id']           = $inv->id;
				$tempInvoices['invoice_payment_date'] = date("d M Y", strtotime($inv->payment_date));
				$tempInvoices['invoice_final_total']  = $inv->inovice_final_total;
				array_push($ClientInovices,$tempInvoices);
			}	
		} 	
		
		return view('clients.view',compact('client_information','TotalSales','CountPreviousAppointmentServices','soldProduct','ClientInovices','CompletedAppointment','CancelledAppointment','NoshowAppointment','outStandingInvoices'));
	}

	public function updateClient(Request $request){
		$rules = [
			'firstname' => 'required'
		];

		$input = $request->only(
			'firstname'
		);

		$validator = Validator::make($input, $rules);
		if ($validator->fails()) {
			return JsonReturn::error($validator->messages());
		}

		$client = Clients::find($request->id);

		if(!empty($request->date_['year']) && !empty($request->date_['month']) && !empty($request->date_['day']) ) {
			$birthdate = date('Y-m-d', strtotime( $request->date_['year'].'-'.$request->date_['month'].'-'.$request->date_['day'] ));
		} else {
			$birthdate = NULL;
		}
		$client->update([
			'firstname'                     => ($request->firstname) ? $request->firstname : '',
			'lastname'                      => ($request->lastname) ? $request->lastname : '',
			'mo_country_code'               => ($request->mo_country_code) ? $request->mo_country_code : '',
			'mobileno'                      => ($request->mobileno['main']) ? $request->mobileno['main'] : '',
			'tel_country_code'              => ($request->tel_country_code) ? $request->tel_country_code : '',
			'telephoneno'                   => ($request->telephoneno['main']) ? $request->telephoneno['main'] : '',
			'email'                         => ($request->email) ? $request->email : '',
			'send_notification_by'          => ($request->send_notification_by) ? $request->send_notification_by : '',
			'preferred_language'            => ($request->preferred_language) ? $request->preferred_language : '',
			'accept_marketing_notification' => ($request->accept_marketing_notification=='on') ? 1 : 0,
			'gender'                        => ($request->gender) ? $request->gender : '',
			'referral_source'               => ($request->referral_source) ? $request->referral_source : '',
			'birthdate'                     => $birthdate,
			'client_notes'                  => ($request->client_notes) ? $request->client_notes : '',
			'display_on_all_bookings'       => ($request->display_on_all_bookings== 'on') ? 1 : 0,
			'address'                       => ($request->address) ? $request->address : '',
			'suburb'                        => ($request->suburb) ? $request->suburb : '',
			'city'                          => ($request->city) ? $request->city : '',
			'state'                         => ($request->state) ? $request->state : '',
			'zipcode'                       => ($request->zipcode) ? $request->zipcode : '',
			'created_at'                    => date("Y-m-d H:i:s"),
			'updated_at'                    => date("Y-m-d H:i:s")
		]);	

		$data["status"] = true;
		$data["message"] = array("Client has been Update succesfully.");
		return JsonReturn::success($data);
	}
	
	public function delete($id)
	{
		$client = Clients::find($id);
		
		if(!empty($client))
		{
			$client->update([
				'is_deleted' => '1',
			]);
			
			if($client){
				$data["status"] = true;
				$data["message"] = "Client has been deleted succesfully.";
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
	
	function hoursandmins($time, $format = '%02d:%02d'){
		if ($time < 1) {
			return;
		}
		$hours = floor($time / 60);
		$minutes = ($time % 60);
		
		$returnText = '';
		if($hours == 0){
			$returnText = $minutes.'min';
		} else {
			$returnText = $hours.'h '.$minutes.'min';
		}
		
		return $returnText;
	}

	public function clientDetail($id)
	{
		return view('clients/client_detail');
	}

	public function getAppointments(Request $request)
	{

		$data = [];
		$data['data'] = [];
		$data['hideMoreButton'] = false;
		$data["status"] = false;
		$data["message"] = "Something went wrong!";

		if(empty($request->clientId)) {
		    $data['status'] = false;
		    $data['message'] = 'Something went wrong. Please reload and try again.';

		} else {

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
		    $id = $request->clientId;

			$PreviousServices = array();
			
			$PreviousAppointmentServices = AppointmentServices::select('appointment_services.*', 'appointments.is_cancelled', 'appointments.appointment_status', 'appointments.is_noshow')
											->join('appointments','appointments.id','=','appointment_services.appointment_id')
											->where('appointments.client_id',$id)
											->where('appointments.user_id', $AdminId)											
											->orderBy('appointment_services.id', 'desc');

			if(!empty($request->lastId)) {
				$PreviousAppointmentServices->where('appointment_services.id', '<', $request->lastId);
			}

			$totalRecords = $PreviousAppointmentServices->count();

			$PreviousAppointmentServices = $PreviousAppointmentServices->limit(5)
											->get()
											->toArray();
			/*echo "<pre>";
			print_r($PreviousAppointmentServices);
			exit;*/
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
						$serviceName = !empty($servicePrices->pricing_name) ? $servicePrices->pricing_name : '';
					} else {
						$serviceName = 'N/A';
					}
					
					$tempServices['id'] = $AppointmentServiceData['id'];
					$tempServices['appointment_date_month'] = date("d M",strtotime($appointment_date));
					$tempServices['appointment_date_hours'] = date("D",strtotime($appointment_date)).' '.date('h:ia',strtotime($start_time));
					$tempServices['serviceName']            = $serviceName;
					$tempServices['duration']               = $duration;
					$tempServices['staff_name']             = $staff_name;
					$tempServices['special_price']          = $special_price;
					$tempServices['is_cancelled']          	= $AppointmentServiceData['is_cancelled'];
					$tempServices['is_noshow']          	= $AppointmentServiceData['is_noshow'];
					$tempServices['appointment_status']     = $AppointmentServiceData['appointment_status'];
					$tempServices['appointment_id']         = $AppointmentServiceData['appointment_id'];
					$tempServices['appointment_link']       = route('viewAppointment', ['id' => Crypt::encryptString($AppointmentServiceData['appointment_id']) ]);
					array_push($PreviousServices,$tempServices);
				}
			}


		    if($totalRecords <= 5) {
		        $data['hideMoreButton'] = true;
		    }

		    $data['data'] = $PreviousServices;
		    $data['status'] = true;
		    $data['message'] = 'Appointments fetched successfully.';
		}

		return JsonReturn::success($data);
	}

	public function getClientProducts(Request $request)
	{
		$data = [];
		$data['data'] = [];
		$data['hideMoreButton'] = false;
		$data["status"] = false;
		$data["message"] = "Something went wrong!";

		if(empty($request->clientId)) {
		    $data['status'] = false;
		    $data['message'] = 'Something went wrong. Please reload and try again.';

		} else {

		    $id = $request->clientId;

			// get sold products
			$soldProduct = InvoiceItems::select('invoice_items.id','invoice_items.quantity','invoice_items.item_price','invoice_items.created_at','inventory_products.product_name')->join('inventory_products','inventory_products.id','=','invoice_items.item_id')->where('invoice_items.client_id',$id)->where('invoice_items.item_type', 'product')->orderBy('invoice_items.id', 'desc');


			if(!empty($request->lastId)) {
				$soldProduct->where('invoice_items.id', '<', $request->lastId);
			}

			$totalRecords = $soldProduct->count();

			$soldProduct = $soldProduct->limit(5)->get();

			$ClientProducts = array();			
			
			if(!empty($soldProduct)) {
				foreach($soldProduct as $key => $product) {	
					$tempProduct['id']       		  = $product->id; 	
					$tempProduct['product_qty']       = $product->quantity; 	
					$tempProduct['product_name']      = $product->product_name; 	
					$tempProduct['product_createdat'] = date("D, d M Y", strtotime($product->created_at)); 	
					$tempProduct['product_price']     = ($product->quantity * $product->item_price); 	
					array_push($ClientProducts,$tempProduct);
				}	
			}

		    if($totalRecords <= 5) {
		        $data['hideMoreButton'] = true;
		    }

		    $data['data'] = $ClientProducts;
		    $data['status'] = true;
		    $data['message'] = 'Client products fetched successfully.';
		}

		return JsonReturn::success($data);
	}

	public function getClientInvoices(Request $request)
	{
		$data = [];
		$data['data'] = [];
		$data['hideMoreButton'] = false;
		$data["status"] = false;
		$data["message"] = "Something went wrong!";

		if(empty($request->clientId)) {
		    $data['status'] = false;
		    $data['message'] = 'Something went wrong. Please reload and try again.';

		} else {

		    $id = $request->clientId;

			$ClientInovices = array();
			
			$clientInvoices = Invoice::select('invoice.id','invoice.inovice_final_total','invoice.payment_date','invoice.invoice_status')->where('invoice.client_id',$id)->orderBy('invoice.id', 'desc');

			if(!empty($request->lastId)) {
				$clientInvoices->where('invoice.id', '<', $request->lastId);
			}

			$totalRecords = $clientInvoices->count();

			$clientInvoices = $clientInvoices->limit(5)->get();
			
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
				
					$tempInvoices['invoice_status']       = $stats;
					$tempInvoices['invoice_id']           = $inv->id;
					$tempInvoices['invoice_payment_date'] = date("d M Y", strtotime($inv->payment_date));
					$tempInvoices['invoice_final_total']  = $inv->inovice_final_total;
					$tempInvoices['invoice_link']  = route('viewInvoice',['id' => $inv->id]);
					array_push($ClientInovices,$tempInvoices);
				}	
			}

		    if($totalRecords <= 5) {
		        $data['hideMoreButton'] = true;
		    }

		    $data['data'] = $ClientInovices;
		    $data['status'] = true;
		    $data['message'] = 'Client invoices fetched successfully.';
		}

		return JsonReturn::success($data);
	}

	public function getToBeCompletedConForm(Request $request)
	{

		$data = [];
		$data['data'] = [];
		$data['hideMoreButton'] = false;
		$data["status"] = false;
		$data["message"] = "Something went wrong!";

		if(empty($request->clientId)) {
		    $data['status'] = false;
		    $data['message'] = 'Something went wrong. Please reload and try again.';

		} else {

		    $id = $request->clientId;

			
			// total sent consultation form
			// $totalSentConsultationForm = ClientConsultationForm::select('client_consultation_form.id')->where('client_consultation_form.consultation_from_id',$id)->get()->count();
			
			// total completed consultation form
			// $totalCompletedConsultationForm = ClientConsultationForm::select('client_consultation_form.id')->where('client_consultation_form.consultation_from_id',$id)->where('client_consultation_form.status',1)->get()->toArray();
			
			// to be completed consultation form
		    $responseData = [
		    	'toBeConsultationForm' => [
		    		'data' => [],
		    		'totalRecords' => 0,
		    		'hideMoreButton' => false
		    	],
		    	'completedConsultationForm' => [
		    		'data' => [],
		    		'totalRecords' => 0,
		    		'hideMoreButton' => false
		    	],
		    ];

			$CurrentDate = date("Y-m-d");
			$responseData['toBeConsultationForm']['data'] = ClientConsultationForm::select('client_consultation_form.id','client_consultation_form.complete_before', 'consultation_form.name', 'consultation_from_id')
															->leftJoin('consultation_form', 'consultation_form.id', 'client_consultation_form.consultation_from_id')
															->where('client_consultation_form.client_id',$id)
															->whereDate('client_consultation_form.complete_before','>=',$CurrentDate)
															->where('client_consultation_form.status',0)
															->orderBy('client_consultation_form.id', 'desc');

			if(!empty($request->lastId)) {
				$responseData['toBeConsultationForm']['data']->where('client_consultation_form.id', '<', $request->lastId);
			}
			$responseData['toBeConsultationForm']['totalRecords'] = $responseData['toBeConsultationForm']['data']->count();
			$responseData['toBeConsultationForm']['data'] = $responseData['toBeConsultationForm']['data']->limit(5)->get()->toArray();

			if($responseData['toBeConsultationForm']['totalRecords'] <= 5) {
			    $responseData['toBeConsultationForm']['hideMoreButton'] = true;
			}

			if(!empty($responseData['toBeConsultationForm']['data']) && is_array($responseData['toBeConsultationForm']['data'])) {
				foreach($responseData['toBeConsultationForm']['data'] as $key => $value) {
					$responseData['toBeConsultationForm']['data'][$key]['encrypted_id'] = Crypt::encryptString($value['id']);
				}
			}

			// completed consultation form
			$responseData['completedConsultationForm']['data'] = ClientConsultationForm::select('client_consultation_form.id','client_consultation_form.completed_at', 'consultation_form.name')
																->leftJoin('consultation_form', 'consultation_form.id', 'client_consultation_form.consultation_from_id')
																->where('client_consultation_form.client_id',$id)
																->where('client_consultation_form.status',1)
																->orderBy('client_consultation_form.id', 'desc');

			if(!empty($request->lastId)) {
				$responseData['completedConsultationForm']['data']->where('client_consultation_form.id', '<', $request->lastId);
			}

			$responseData['completedConsultationForm']['totalRecords'] = $responseData['completedConsultationForm']['data']->count();

			$responseData['completedConsultationForm']['data'] = $responseData['completedConsultationForm']['data']->limit(5)->get()->toArray();

			if($responseData['completedConsultationForm']['totalRecords'] <= 5) {
			    $responseData['completedConsultationForm']['hideMoreButton'] = true;
			}

			if(!empty($responseData['completedConsultationForm']['data']) && is_array($responseData['completedConsultationForm']['data'])) {
				foreach($responseData['completedConsultationForm']['data'] as $key => $value) {
					$responseData['completedConsultationForm']['data'][$key]['encrypted_id'] = Crypt::encryptString($value['id']);
				}
			}
			
			// $totalNotCompletedConsultationForm = ClientConsultationForm::select('client_consultation_form.id')->where('client_consultation_form.consultation_from_id',$id)->whereDate('client_consultation_form.complete_before','<',$CurrentDate)->where('client_consultation_form.status',0)->get()->toArray();


		    $data['data'] = $responseData;
		    $data['totalRecords'] = $responseData['toBeConsultationForm']['totalRecords'] + $responseData['completedConsultationForm']['totalRecords'];
		    $data['status'] = true;
		    $data['message'] = 'Client invoices fetched successfully.';
		}

		return JsonReturn::success($data);
		
	}
}
