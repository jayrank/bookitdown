<?php
namespace App\Http\Controllers\Front;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\JsonReturn;
use App\Models\frontUser;
use App\Models\fuserAddress;
use App\Models\SoldVoucher;
use App\Models\Clients;
use App\Models\Services;
use App\Models\ServicesPrice;
use App\Models\Invoice;
use App\Models\InvoiceItems;
use App\Models\InvoiceTemplate;
use App\Models\Staff;
use App\Models\SoldPaidPlan;
use App\Models\InvoiceTaxes;
use App\Models\StaffTip;
use App\Models\Location;
use App\Models\Appointments;
use App\Models\AppointmentServices;
use App\Models\User;
use App\Models\InventoryProducts;
use App\Mail\VoucherEmail;
use App\Mail\VoucherInvoiceEmail;
use DB;
use Crypt;
use Session;
use Mail;
use PDF;
  
class VouchersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
		$currentRoute = Route::currentRouteName();
		$this->middleware('FUser');
    }

    public function index($soldVoucherId = NULL)
    {
        $fuserId = Auth::guard('fuser')->user()->id;

        $clientIds = Clients::where('fuser_id', $fuserId)->pluck('id');
        $activeVouchers = SoldVoucher::whereIn('sold_voucher.client_id', $clientIds)
                    ->leftJoin('vouchers', 'vouchers.id', 'sold_voucher.voucher_id')
                    ->leftJoin('locations', 'locations.id', 'sold_voucher.location_id')
                    ->where('start_date', '<=', date('Y-m-d'))
                    ->where('end_date', '>=', date('Y-m-d'))
                    ->whereRaw('sold_voucher.total_value != sold_voucher.redeemed')
                    ->select('sold_voucher.*', 'locations.location_name', 'locations.location_image', 'locations.location_address', 'vouchers.color', 'vouchers.name', 'vouchers.description')
                    ->get();

        $usedVouchers = SoldVoucher::whereIn('sold_voucher.client_id', $clientIds)
                    ->leftJoin('vouchers', 'vouchers.id', 'sold_voucher.voucher_id')
                    ->leftJoin('locations', 'locations.id', 'sold_voucher.location_id')
                    ->whereColumn('sold_voucher.total_value', 'sold_voucher.redeemed')
                    ->select('sold_voucher.*', 'locations.location_name', 'locations.location_image', 'locations.location_address', 'vouchers.color', 'vouchers.name', 'vouchers.description')
                    ->get();


        $selectedVoucher = NULL;

        if(!empty($soldVoucherId)) {
            $selectedVoucher = SoldVoucher::whereIn('sold_voucher.client_id', $clientIds)
                                    ->leftJoin('vouchers', 'vouchers.id', 'sold_voucher.voucher_id')
                                    ->leftJoin('locations', 'locations.id', 'sold_voucher.location_id')
                                    ->where('sold_voucher.id', $soldVoucherId)
                                    ->select('sold_voucher.*', 'locations.location_name', 'locations.location_image', 'locations.location_address', 'vouchers.color', 'vouchers.name', 'vouchers.description')
                                    ->first();
        }else {
            if(!$activeVouchers->isEmpty()) {
                $selectedVoucher = $activeVouchers[0];
            } elseif(!$usedVouchers->isEmpty()) {
                $selectedVoucher = $usedVouchers[0];
            }
        }

        $serviceCategory = $this->getServiceCategory($selectedVoucher);
        
        return view('frontend.vouchers.index', compact('activeVouchers', 'usedVouchers', 'selectedVoucher','serviceCategory'));
    }

    public function viewVoucherInvoice($soldVoucherId = NULL)
    {
        if($soldVoucherId != '')
        {
            $soldVoucherId = Crypt::decryptString($soldVoucherId);

            $soldVoucher = SoldVoucher::find($soldVoucherId);
            $invID = $soldVoucher->invoice_id;

            $Invoice = Invoice::select('*')->where('id',$invID)->orderBy('id', 'desc')->get()->first()->toArray();
            $checkInvRefund = Invoice::select('id')->where('original_invoice_id',$invID)->first();
            
            $isRefunded = 0;
            if(!empty($checkInvRefund)) {
                $isRefunded = 1;    
            }   
            
            $InvoiceItems = InvoiceItems::select('*')->where('invoice_id',$invID)->orderBy('id', 'desc')->get()->toArray();
            
            // $InvoiceTemplate = InvoiceTemplate::where('user_id',$AdminId)->first();
            
            $InvoiceItemsInfo = array();
            if(!empty($InvoiceItems)){
                foreach($InvoiceItems as $InvoiceItemDetail){
                    if($InvoiceItemDetail['item_type'] == 'services'){
                        
                        $SERVICE_ID = $InvoiceItemDetail['item_id'];
                        
                        $singleService = Services::select('id','service_name')->where('id', $SERVICE_ID)->orderBy('order_id', 'asc')->get()->first();
                        
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
                        
                        $InventoryProducts = InventoryProducts::select('id','product_name')->where('id', $PRODUCT_ID)->orderBy('id', 'desc')->get()->first();
                        
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
                        
                        $Voucher = SoldVoucher::select('vouchers.title','sold_voucher.voucher_code','sold_voucher.end_date','vouchers.name')->leftJoin('vouchers', 'vouchers.id', '=', 'sold_voucher.voucher_id')->where('sold_voucher.voucher_id', $VOUCHER_ID)->where('sold_voucher.invoice_id', $invID)->orderBy('sold_voucher.id', 'desc')->get()->toArray();
                        
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
                        
                        $PaidPlan = SoldPaidPlan::select('paid_plan.name','sold_paidplan.end_date')->leftJoin('paid_plan', 'paid_plan.id', '=', 'sold_paidplan.paidplan_id')->where('sold_paidplan.paidplan_id', $PAIDPLAN_ID)->where('sold_paidplan.invoice_id', $invID)->get()->first()->toArray();
                        
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
            
            $VoucherSold = SoldVoucher::select('locations.location_name','locations.location_address','locations.location_image','vouchers.title','sold_voucher.voucher_code','sold_voucher.end_date','vouchers.name','sold_voucher.price')->leftJoin('vouchers', 'vouchers.id', '=', 'sold_voucher.voucher_id')->leftJoin('locations', 'locations.id', '=', 'sold_voucher.location_id')->where('sold_voucher.invoice_id', $invID)->orderBy('sold_voucher.id', 'desc')->get()->toArray();
            
            $PlanSold = SoldPaidPlan::select('locations.location_name','locations.location_address','locations.location_image','paid_plan.name','sold_paidplan.end_date','sold_paidplan.no_of_sessions','sold_paidplan.valid_for','sold_paidplan.end_date','sold_paidplan.price')->leftJoin('paid_plan', 'paid_plan.id', '=', 'sold_paidplan.paidplan_id')->leftJoin('locations', 'locations.id', '=', 'sold_paidplan.location_id')->where('sold_paidplan.invoice_id', $invID)->get()->toArray();
            
            $InvoiceTaxes = InvoiceTaxes::select('invoice_taxes.tax_rate','invoice_taxes.tax_amount','taxes.tax_name')->leftJoin('taxes', 'taxes.id', '=', 'invoice_taxes.tax_id')->where('invoice_taxes.invoice_id', $invID)->get()->toArray();
            
            $TotalStaffTip = StaffTip::select(DB::raw('SUM(tip_amount) as total_tip'))->where('staff_tip.inovice_id', $invID)->get()->toArray();
            
            $LocationInfo = array();
            if($Invoice['location_id'] != 0){
                $LocationInfo = Location::select('location_name')->where(['id'=>$Invoice['location_id']])->orderBy('id', 'ASC')->get()->first()->toArray();
            }
            
            $PaymentDoneBy = array();
            if($Invoice['created_by'] != 0){
                $PaymentDoneBy = Staff::select('staff.staff_user_id','users.first_name','users.last_name')->join('users','users.id','=','staff.staff_user_id')->where(['staff.staff_user_id'=>$Invoice['created_by']])->orderBy('staff.id', 'ASC')->get()->first()->toArray();  
            }
            
            // get previous history
            $PreviousAppointment = array();
            $PreviousAppointmentServices = array();
            $PreviousServices = array();
            $TotalSpend = 0;
            
            $ClientInfo      = array();
            $soldProduct     = array();
            $ClientProducts  = '';
            $clientInvoices  = array();
            $ClientInovices  = '';
            
            if($Invoice['client_id'] != 0)
            {
                $ClientInfo = Clients::getClientbyID($Invoice['client_id']);
                
                $PreviousAppointment = Appointments::select('*')->where('client_id',$Invoice['client_id'])->orderBy('id', 'desc')->get()->toArray();
                
                $PreviousAppointmentServices = AppointmentServices::select('appointment_services.*')->join('appointments','appointments.id','=','appointment_services.appointment_id')->where('appointments.client_id',$Invoice['client_id'])->orderBy('id', 'desc')->get()->toArray();
                
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
                
                $soldProduct = InvoiceItems::select('invoice_items.quantity','invoice_items.item_price','invoice_items.created_at','inventory_products.product_name')->join('inventory_products','inventory_products.id','=','invoice_items.item_id')->where('invoice_items.client_id',$Invoice['client_id'])->where('invoice_items.item_type', 'product')->orderBy('invoice_items.id', 'desc')->get();

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
                
                $clientInvoices = Invoice::select('invoice.id','invoice.inovice_final_total','invoice.payment_date','invoice.invoice_status')->where('invoice.client_id',$Invoice['client_id'])->orderBy('invoice.id', 'desc')->get();
                
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

            if(!empty($Invoice['user_id'])) {
                $UserInfo = User::find($Invoice['user_id']);
            } else {
                $UserInfo = NULL;
            }
            return view('frontend.vouchers.viewInvoice',compact('Invoice','InvoiceItemsInfo','ClientInfo','InvoiceTaxes','TotalStaffTip','LocationInfo','PaymentDoneBy','VoucherSold','PlanSold','isRefunded','PreviousAppointment','PreviousServices','TotalSpend','soldProduct','ClientProducts','clientInvoices','ClientInovices','UserInfo','soldVoucherId')); //,'InvoiceTemplate', invoiceId
        }
        
    }

    public function printVoucher($soldVoucherId = NULL)
    {
        if($soldVoucherId != '')
        {
            $soldVoucherId = Crypt::decryptString($soldVoucherId);
            $soldVoucher = SoldVoucher::where('sold_voucher.id', $soldVoucherId)
                                    ->leftJoin('vouchers', 'vouchers.id', 'sold_voucher.voucher_id')
                                    ->leftJoin('locations', 'locations.id', 'sold_voucher.location_id')
                                    ->where('sold_voucher.id', $soldVoucherId)
                                    ->select('sold_voucher.*', 'locations.location_name', 'locations.location_image', 'locations.location_address', 'vouchers.color', 'vouchers.name', 'vouchers.description')
                                    ->first();

            return view('frontend.vouchers.printVoucher', compact('soldVoucher'));
        }
    }

    public function emailVoucher($soldVoucherId = NULL)
    {
        if($soldVoucherId != '')
        {
            $soldVoucherId = Crypt::decryptString($soldVoucherId);
            $soldVoucher = SoldVoucher::where('sold_voucher.id', $soldVoucherId)
                                    ->leftJoin('vouchers', 'vouchers.id', 'sold_voucher.voucher_id')
                                    ->leftJoin('locations', 'locations.id', 'sold_voucher.location_id')
                                    ->where('sold_voucher.id', $soldVoucherId)
                                    ->select('sold_voucher.*', 'locations.location_name', 'locations.location_image', 'locations.location_address', 'vouchers.color', 'vouchers.name', 'vouchers.description')
                                    ->first();

            $serviceCategory = $this->getServiceCategory($soldVoucher);
            
            return view('frontend.vouchers.emailVoucher', compact('soldVoucher', 'serviceCategory'));
        }
    }

    public function sendVoucherByEmail(Request $request){
        if ($request->ajax()){
            
            $InvoiceID                    = ($request->invoiceID) ? $request->invoiceID : '';
            $encryptedInvoiceId           = Crypt::encryptString($InvoiceID);   
            $RecipientFirstName           = ($request->recipient_first_name) ? $request->recipient_first_name : ''; 
            $RecipientLastName            = ($request->recipient_last_name) ? $request->recipient_last_name : '';   
            $recipient_email              = ($request->recipient_email) ? $request->recipient_email : '';   
            $RecipientPersonalizedEmail   = ($request->recipient_personalized_email) ? $request->recipient_personalized_email : ''; 
            $soldVoucherID                = ($request->soldVoucherID) ? $request->soldVoucherID : ''; 
            
            $Invoice = Invoice::select('*')->where('id',$InvoiceID)->orderBy('id', 'desc')->get()->first()->toArray();
                
            $VoucherSold = SoldVoucher::select('locations.location_name','locations.location_address','locations.location_image','vouchers.title','sold_voucher.voucher_code','sold_voucher.end_date','vouchers.name','sold_voucher.price')->leftJoin('vouchers', 'vouchers.id', '=', 'sold_voucher.voucher_id')->leftJoin('locations', 'locations.id', '=', 'sold_voucher.location_id')->where('sold_voucher.invoice_id', $InvoiceID)->orderBy('sold_voucher.id', 'desc')->get()->toArray();
            
            $LocationInfo = array();
            if($Invoice['location_id'] != 0){
                $LocationInfo = Location::select('location_name','location_address','country_code','location_phone')->where(['id'=>$Invoice['location_id']])->orderBy('id', 'ASC')->get()->first()->toArray();
            }
            
            $ClientInfo = array();
            if($Invoice['client_id'] != '' && $Invoice['client_id'] != 0){
                $ClientInfo = Clients::getClientbyID($Invoice['client_id']);
            }
            
            $Success = 0;    
            if(!empty($VoucherSold)){
                foreach($VoucherSold as $VoucherSoldData){
                    
                    $VoucherName         = ($VoucherSoldData['name']) ? $VoucherSoldData['name'] : '';
                    $VoucherLocationName = ($VoucherSoldData['location_name']) ? $VoucherSoldData['location_name'] : '';
                    
                    $FROM_EMAIL     = env("MAIL_FROM_ADDRESS", "info@ikotel.ca");
                    $FROM_NAME      = 'Scheduledown';
                    $TO_EMAIL       = $recipient_email;
                    $SUBJECT        = $VoucherName.' from '.$VoucherLocationName;
                    $MESSAGE        = 'Hi  Please see attached invoice order Have a great day! ';
                     
                    $SendMail = Mail::to($TO_EMAIL)->send(new VoucherEmail($FROM_EMAIL,$FROM_NAME,$SUBJECT,$MESSAGE,$VoucherSoldData,$LocationInfo,$ClientInfo,$RecipientFirstName,$RecipientLastName,$RecipientPersonalizedEmail));

                    $Success = 1;           
                }
            }
            
            if($Success == 1){
                $data["status"] = true;
                $data["message"] = "Voucher has been sent.";    
                Session::flash('message', 'Voucher has been sent.');
                $data["redirect"] = route('viewVoucherInvoice',['soldVoucherId' => Crypt::encryptString($soldVoucherID)]);
            } else {
                $data["status"] = true;
                $data["message"] = "Something went wrong!";     
            }
            
            return JsonReturn::success($data);
        }
    }

    public function printVoucherInvoice(Request $request)
    {
        if ($request->ajax()){
           
            $InvoiceID                    = ($request->invoiceID) ? $request->invoiceID : '';
            $encryptedInvoiceId           = Crypt::encryptString($InvoiceID);   
            $RecipientFirstName           = ($request->recipient_first_name) ? $request->recipient_first_name : ''; 
            $RecipientLastName            = ($request->recipient_last_name) ? $request->recipient_last_name : '';   
            $RecipientPersonalizedEmail   = ($request->recipient_personalized_email) ? $request->recipient_personalized_email : ''; 
            
            $Invoice = Invoice::select('*')->where('id',$InvoiceID)->orderBy('id', 'desc')->get()->first()->toArray();
                
            $VoucherSold = SoldVoucher::select('locations.location_name','locations.location_address','locations.location_image','vouchers.title','sold_voucher.voucher_code','sold_voucher.end_date','vouchers.name','sold_voucher.price')->leftJoin('vouchers', 'vouchers.id', '=', 'sold_voucher.voucher_id')->leftJoin('locations', 'locations.id', '=', 'sold_voucher.location_id')->where('sold_voucher.invoice_id', $InvoiceID)->orderBy('sold_voucher.id', 'desc')->get()->toArray();
            
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
            $pdfData['RecipientFirstName']         = $RecipientFirstName;
            $pdfData['RecipientLastName']          = $RecipientLastName;
            $pdfData['RecipientPersonalizedEmail'] = $RecipientPersonalizedEmail;
            return PDF::loadView('pdfTemplates.voucherPdfPrint',$pdfData)->setPaper('a4')->download('Voucher.pdf');
        }
    }
    
    public function saveVoucherInvoicePdf($id = null){
        if($id != '')
        {
            try {
                $invoiceId = Crypt::decryptString($id);
                
                $fileName = ($invoiceId) ? $invoiceId : 0;
                
                $Invoice = Invoice::select('*')->where('id',$invoiceId)->orderBy('id', 'desc')->get()->first()->toArray();

                if(!empty($Invoice)) {
                    $AdminId = $Invoice['user_id'];
                } else {
                    $AdminId = NULL;
                }
                $InvoiceItems = InvoiceItems::select('*')->where('invoice_id',$invoiceId)->orderBy('id', 'desc')->get()->toArray();
                
                $InvoiceTemplate = InvoiceTemplate::where('user_id',$AdminId)->first();
                
                $InvoiceItemsInfo = array();
                if(!empty($InvoiceItems)){
                    foreach($InvoiceItems as $InvoiceItemDetail){
                        if($InvoiceItemDetail['item_type'] == 'services'){
                            
                            $SERVICE_ID = $InvoiceItemDetail['item_id'];
                            
                            $singleService = Services::select('id','service_name')->where('id', $SERVICE_ID)->orderBy('order_id', 'asc')->get()->first();
                            
                            $tempItemDetail['main_title'] = ($singleService) ? $singleService->service_name : '';
                            
                            $CurrentStaff = Staff::getStaffDetailByID($InvoiceItemDetail['staff_id']);
                        
                            $staffFirstName = ($CurrentStaff) ? $CurrentStaff->first_name : '';
                            $staffLastName  = ($CurrentStaff) ? $CurrentStaff->last_name : '';
                            $tempItemDetail['staff_name']    = 'With '.$staffFirstName.' '.$staffLastName;
                            
                            $tempItemDetail['quantity']      = $InvoiceItemDetail['quantity'];
                            $tempItemDetail['item_og_price'] = ($InvoiceItemDetail['item_og_price'] * $InvoiceItemDetail['quantity']);
                            $tempItemDetail['item_price']    = ($InvoiceItemDetail['item_price'] * $InvoiceItemDetail['quantity']);
                            array_push($InvoiceItemsInfo,$tempItemDetail);
                        } else if($InvoiceItemDetail['item_type'] == 'product'){
                            
                            $PRODUCT_ID = $InvoiceItemDetail['item_id'];
                            
                            $InventoryProducts = InventoryProducts::select('id','product_name')->where('id', $PRODUCT_ID)->orderBy('id', 'desc')->get()->first();
                            
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
                            
                            $Voucher = SoldVoucher::select('vouchers.title','sold_voucher.voucher_code','sold_voucher.end_date','vouchers.name')->leftJoin('vouchers', 'vouchers.id', '=', 'sold_voucher.voucher_id')->where('sold_voucher.voucher_id', $VOUCHER_ID)->where('sold_voucher.invoice_id', $invoiceId)->orderBy('sold_voucher.id', 'desc')->get()->toArray();
                            
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
                            
                            $PaidPlan = SoldPaidPlan::select('paid_plan.name','sold_paidplan.end_date')->leftJoin('paid_plan', 'paid_plan.id', '=', 'sold_paidplan.paidplan_id')->where('sold_paidplan.paidplan_id', $PAIDPLAN_ID)->where('sold_paidplan.invoice_id', $invoiceId)->get()->first()->toArray();
                            
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
                
                $InvoiceTaxes = InvoiceTaxes::select('invoice_taxes.tax_rate','invoice_taxes.tax_amount','taxes.tax_name')->leftJoin('taxes', 'taxes.id', '=', 'invoice_taxes.tax_id')->where('invoice_taxes.invoice_id', $invoiceId)->get()->toArray();
                
                $TotalStaffTip = StaffTip::select(DB::raw('SUM(tip_amount) as total_tip'))->where('staff_tip.inovice_id', $invoiceId)->get()->toArray();
                
                $LocationInfo = array();
                if($Invoice['location_id'] != 0){
                    $LocationInfo = Location::select('location_name','location_address','country_code','location_phone')->where(['id'=>$Invoice['location_id']])->orderBy('id', 'ASC')->get()->first()->toArray();
                }
                
                $ClientInfo = array();
                if($Invoice['client_id'] != '' && $Invoice['client_id'] != 0){
                    $ClientInfo = Clients::getClientbyID($Invoice['client_id']);
                }
                
                $pdfData = array();
                $pdfData['Invoice']          = $Invoice;
                $pdfData['InvoiceItemsInfo'] = $InvoiceItemsInfo;
                $pdfData['InvoiceTemplate']  = $InvoiceTemplate;
                $pdfData['InvoiceTaxes']     = $InvoiceTaxes;
                $pdfData['TotalStaffTip']    = $TotalStaffTip;
                $pdfData['LocationInfo']     = $LocationInfo;
                $pdfData['ClientInfo']       = $ClientInfo;
                
                return PDF::loadView('pdfTemplates.invoiceOrder',$pdfData)->setPaper('a4')->download('Invoice-'.$fileName.'.pdf');
            } catch (DecryptException $e) {
                return redirect()->route('myVouchers');
            }
        }
    }

    public function sendVoucherInvoiceCopy(Request $request)
    {
        $soldVoucherId = isset($request->soldVoucherId) ? $request->soldVoucherId : '';
        $soldVoucherId = Crypt::decryptString($soldVoucherId);
        $recipient_email = isset($request->email) ? $request->email : '';

        $soldVoucher = SoldVoucher::find($soldVoucherId);
        $invID = $soldVoucher->invoice_id;

        $Invoice = Invoice::select('*')->where('id',$invID)->orderBy('id', 'desc')->get()->first()->toArray();
        $checkInvRefund = Invoice::select('id')->where('original_invoice_id',$invID)->first();
        
        $isRefunded = 0;
        if(!empty($checkInvRefund)) {
            $isRefunded = 1;    
        }   
        
        $InvoiceItems = InvoiceItems::select('*')->where('invoice_id',$invID)->orderBy('id', 'desc')->get()->toArray();
        
        // $InvoiceTemplate = InvoiceTemplate::where('user_id',$AdminId)->first();
        
        $InvoiceItemsInfo = array();
        if(!empty($InvoiceItems)){
            foreach($InvoiceItems as $InvoiceItemDetail){
                if($InvoiceItemDetail['item_type'] == 'services'){
                    
                    $SERVICE_ID = $InvoiceItemDetail['item_id'];
                    
                    $singleService = Services::select('id','service_name')->where('id', $SERVICE_ID)->orderBy('order_id', 'asc')->get()->first();
                    
                    $tempItemDetail['main_title'] = ($singleService) ? $singleService->service_name : '';
                    
                    $CurrentStaff = Staff::getStaffDetailByID($InvoiceItemDetail['staff_id']);
                
                    $staffFirstName = ($CurrentStaff) ? $CurrentStaff->first_name : '';
                    $staffLastName  = ($CurrentStaff) ? $CurrentStaff->last_name : '';
                    $tempItemDetail['staff_name']    = 'With '.$staffFirstName.' '.$staffLastName;
                    
                    $tempItemDetail['quantity']      = $InvoiceItemDetail['quantity'];
                    $tempItemDetail['item_og_price'] = ($InvoiceItemDetail['item_og_price'] * $InvoiceItemDetail['quantity']);
                    $tempItemDetail['item_price']    = ($InvoiceItemDetail['item_price'] * $InvoiceItemDetail['quantity']);
                    array_push($InvoiceItemsInfo,$tempItemDetail);
                } else if($InvoiceItemDetail['item_type'] == 'product'){
                    
                    $PRODUCT_ID = $InvoiceItemDetail['item_id'];
                    
                    $InventoryProducts = InventoryProducts::select('id','product_name')->where('id', $PRODUCT_ID)->orderBy('id', 'desc')->get()->first();
                    
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
                    
                    $Voucher = SoldVoucher::select('vouchers.title','sold_voucher.voucher_code','sold_voucher.end_date','vouchers.name')->leftJoin('vouchers', 'vouchers.id', '=', 'sold_voucher.voucher_id')->where('sold_voucher.voucher_id', $VOUCHER_ID)->where('sold_voucher.invoice_id', $invID)->orderBy('sold_voucher.id', 'desc')->get()->toArray();
                    
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
                    
                    $PaidPlan = SoldPaidPlan::select('paid_plan.name','sold_paidplan.end_date')->leftJoin('paid_plan', 'paid_plan.id', '=', 'sold_paidplan.paidplan_id')->where('sold_paidplan.paidplan_id', $PAIDPLAN_ID)->where('sold_paidplan.invoice_id', $invID)->get()->first()->toArray();
                    
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
        
        $VoucherSold = SoldVoucher::select('locations.location_name','locations.location_address','locations.location_image','vouchers.title','sold_voucher.voucher_code','sold_voucher.end_date','vouchers.name','sold_voucher.price')->leftJoin('vouchers', 'vouchers.id', '=', 'sold_voucher.voucher_id')->leftJoin('locations', 'locations.id', '=', 'sold_voucher.location_id')->where('sold_voucher.invoice_id', $invID)->orderBy('sold_voucher.id', 'desc')->get()->toArray();
        
        $PlanSold = SoldPaidPlan::select('locations.location_name','locations.location_address','locations.location_image','paid_plan.name','sold_paidplan.end_date','sold_paidplan.no_of_sessions','sold_paidplan.valid_for','sold_paidplan.end_date','sold_paidplan.price')->leftJoin('paid_plan', 'paid_plan.id', '=', 'sold_paidplan.paidplan_id')->leftJoin('locations', 'locations.id', '=', 'sold_paidplan.location_id')->where('sold_paidplan.invoice_id', $invID)->get()->toArray();
        
        $InvoiceTaxes = InvoiceTaxes::select('invoice_taxes.tax_rate','invoice_taxes.tax_amount','taxes.tax_name')->leftJoin('taxes', 'taxes.id', '=', 'invoice_taxes.tax_id')->where('invoice_taxes.invoice_id', $invID)->get()->toArray();
        
        $TotalStaffTip = StaffTip::select(DB::raw('SUM(tip_amount) as total_tip'))->where('staff_tip.inovice_id', $invID)->get()->toArray();
        
        $LocationInfo = array();
        if($Invoice['location_id'] != 0){
            $LocationInfo = Location::select('location_name')->where(['id'=>$Invoice['location_id']])->orderBy('id', 'ASC')->get()->first()->toArray();
        }
        
        $PaymentDoneBy = array();
        if($Invoice['created_by'] != 0){
            $PaymentDoneBy = Staff::select('staff.staff_user_id','users.first_name','users.last_name')->join('users','users.id','=','staff.staff_user_id')->where(['staff.staff_user_id'=>$Invoice['created_by']])->orderBy('staff.id', 'ASC')->get()->first()->toArray();  
        }
        
        // get previous history
        $PreviousAppointment = array();
        $PreviousAppointmentServices = array();
        $PreviousServices = array();
        $TotalSpend = 0;
        
        $ClientInfo      = array();
        $soldProduct     = array();
        $ClientProducts  = '';
        $clientInvoices  = array();
        $ClientInovices  = '';
        
        if($Invoice['client_id'] != 0)
        {
            $ClientInfo = Clients::getClientbyID($Invoice['client_id']);
            
            $PreviousAppointment = Appointments::select('*')->where('client_id',$Invoice['client_id'])->orderBy('id', 'desc')->get()->toArray();
            
            $PreviousAppointmentServices = AppointmentServices::select('appointment_services.*')->join('appointments','appointments.id','=','appointment_services.appointment_id')->where('appointments.client_id',$Invoice['client_id'])->orderBy('id', 'desc')->get()->toArray();
            
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
            
            $soldProduct = InvoiceItems::select('invoice_items.quantity','invoice_items.item_price','invoice_items.created_at','inventory_products.product_name')->join('inventory_products','inventory_products.id','=','invoice_items.item_id')->where('invoice_items.client_id',$Invoice['client_id'])->where('invoice_items.item_type', 'product')->orderBy('invoice_items.id', 'desc')->get();

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
            
            $clientInvoices = Invoice::select('invoice.id','invoice.inovice_final_total','invoice.payment_date','invoice.invoice_status')->where('invoice.client_id',$Invoice['client_id'])->orderBy('invoice.id', 'desc')->get();
            
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

        if(!empty($Invoice['user_id'])) {
            $UserInfo = User::find($Invoice['user_id']);
        } else {
            $UserInfo = NULL;
        }


        $soldVoucher = SoldVoucher::leftJoin('vouchers', 'vouchers.id', 'sold_voucher.voucher_id')
                                    ->leftJoin('locations', 'locations.id', 'sold_voucher.location_id')
                                    ->select('sold_voucher.*', 'vouchers.name', 'locations.location_name')
                                    ->where('sold_voucher.id', $soldVoucherId)
                                    ->first();

        $VoucherName         = ($soldVoucher->name) ? $soldVoucher->name : '';
        $VoucherLocationName = ($soldVoucher->location_name) ? $soldVoucher->location_name : '';
        
        $FROM_EMAIL     = env("MAIL_FROM_ADDRESS", "info@ikotel.ca");
        $FROM_NAME      = 'Scheduledown';
        $TO_EMAIL       = $recipient_email;
        $SUBJECT        = $VoucherName.' from '.$VoucherLocationName;
        $MESSAGE        = 'Hi  Please see attached invoice order Have a great day! ';
         
        $SendMail = Mail::to($TO_EMAIL)->send(new VoucherInvoiceEmail($FROM_EMAIL,$FROM_NAME,$SUBJECT,$MESSAGE,$Invoice,$InvoiceItemsInfo,$ClientInfo,$InvoiceTaxes,$TotalStaffTip,$LocationInfo,$PaymentDoneBy,$VoucherSold,$PlanSold,$isRefunded,$PreviousAppointment,$PreviousServices,$TotalSpend,$soldProduct,$ClientProducts,$clientInvoices,$ClientInovices,$UserInfo));

        
        $data["status"] = true;
        $data["message"] = "Voucher has been sent.";    
        Session::flash('message', 'Voucher has been sent.');
        $data["redirect"] = route('viewVoucherInvoice',['soldVoucherId' => Crypt::encryptString($soldVoucherId)]);
        
        return JsonReturn::success($data);
    }

    function getServiceCategory($selectedVoucher)
    {
        $serviceCategory = [];
        if(!empty($selectedVoucher)) {
            if(!empty($selectedVoucher->service_id)) {
                $service_id_array = explode(',',$selectedVoucher->service_id);

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
    
    function hoursandmins($time, $format = '%02d:%02d')
    {
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
}
