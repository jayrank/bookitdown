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
use App\Mail\ConsultationFormReminder;
use App\Mail\AppointmentNotification;
use DataTables;
use Session;
use Crypt;
use Hash;
use Mail;

use App\Repositories\NotificationRepositorie;

class onlineBookingController extends Controller
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
	
	public function index($uid = null, Request $request) 
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
		
		$locationID = Crypt::decryptString($uid);
		$LocationData = Location::where('user_id', $locationID)->get();

		return view('frontend.online_booking', compact('LocationData'));

	}
	
	public function getdatabyloc(Request $request){
		$rules = ([
            'selectLoc' => 'required',
        ]);
        
        $message = ([
            "selectLoc.required" => "Please select loction",
        ]);
        
        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            $messages = $validator->messages();
            return redirect()->back()->withErrors($messages);
        }
		$enuserid = Crypt::encryptString($request->selectLoc);
		return redirect()->route('frontBooking',$enuserid);

	}

		
}
