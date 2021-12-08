<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
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
use App\Models\Invoice;
use App\Models\InvoiceItems;
use App\Models\InvoiceTemplate;
use App\Models\InvoiceVoucher;
use App\Models\InventoryProducts;
use App\Models\InventoryOrderLogs;
use App\Models\PaidPlan;
use App\Models\SoldPaidPlan;
use App\Models\Voucher;
use App\Models\SoldVoucher;
use App\Models\Taxes;
use App\Models\InvoiceTaxes;
use App\Models\StaffTip;
use App\Models\paymentType;
use DataTables;
use Session;
use Crypt;
use DB;
use PDF;
use App\Exports\DailySalesexport;
use App\Exports\DailySalesExportCSV;
use App\Exports\Appointmentexport;
use App\Exports\invoicesexport;
use App\Exports\vouchersexport;
use Excel;
  
class SalesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
		$currentRoute = Route::currentRouteName();
		$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
	public function dailySale()
    {
		if (Auth::user()->can('daily_sales')) {
			
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
				
			$ServiceTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_services'),DB::raw('SUM(invoice_items.item_price) as total_item_price'))->join('invoice','invoice.id','=','invoice_items.invoice_id')->where(['invoice.user_id'=>$AdminId])->where('invoice_items.item_type','services')->where('invoice.invoice_status','!=','3')->where('invoice.invoice_status','!=','2')->orderBy('invoice_items.id', 'ASC')->get()->toArray();
			
			$RefundServiceTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_services'),DB::raw('SUM(invoice_items.item_price) as total_item_price'))->join('invoice','invoice.id','=','invoice_items.invoice_id')->where(['invoice.user_id'=>$AdminId])->where('invoice_items.item_type','services')->where('invoice.invoice_status','2')->orderBy('invoice_items.id', 'ASC')->get()->toArray();	
			
			$PrductTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_products'),DB::raw('SUM(invoice_items.item_price) as total_item_price'))->join('invoice','invoice.id','=','invoice_items.invoice_id')->where(['invoice.user_id'=>$AdminId])->where('invoice_items.item_type','product')->where('invoice.invoice_status','!=','3')->where('invoice.invoice_status','!=','2')->orderBy('invoice_items.id', 'ASC')->get()->toArray();
			
			$PrductRefundTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_products'),DB::raw('SUM(invoice_items.item_price) as total_item_price'))->join('invoice','invoice.id','=','invoice_items.invoice_id')->where(['invoice.user_id'=>$AdminId])->where('invoice_items.item_type','product')->where('invoice.invoice_status','2')->orderBy('invoice_items.id', 'ASC')->get()->toArray();

			$VoucherTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_vouchers'),DB::raw('SUM(invoice_items.item_price) as total_item_price'))->join('invoice','invoice.id','=','invoice_items.invoice_id')->where(['invoice.user_id'=>$AdminId])->where('invoice_items.item_type','voucher')->where('invoice.invoice_status','!=','3')->where('invoice.invoice_status','!=','2')->orderBy('invoice_items.id', 'ASC')->get()->toArray();
			
			$VoucherRefundTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_vouchers'),DB::raw('SUM(invoice_items.item_price) as total_item_price'))->join('invoice','invoice.id','=','invoice_items.invoice_id')->where(['invoice.user_id'=>$AdminId])->where('invoice_items.item_type','voucher')->where('invoice.invoice_status','2')->orderBy('invoice_items.id', 'ASC')->get()->toArray();

			$PaidplaTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_paidplan'),DB::raw('SUM(invoice_items.item_price) as total_item_price'))->join('invoice','invoice.id','=','invoice_items.invoice_id')->where(['invoice.user_id'=>$AdminId])->where('invoice_items.item_type','paidplan')->where('invoice.invoice_status','!=','3')->where('invoice.invoice_status','!=','2')->orderBy('invoice_items.id', 'ASC')->get()->toArray();

			$PaidplaRefundTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_paidplan'),DB::raw('SUM(invoice_items.item_price) as total_item_price'))->join('invoice','invoice.id','=','invoice_items.invoice_id')->where(['invoice.user_id'=>$AdminId])->where('invoice_items.item_type','paidplan')->where('invoice.invoice_status','2')->orderBy('invoice_items.id', 'ASC')->get()->toArray();	
			
			$TotalAllThing  = 0;
			$TotalAllAmount = 0;
			
			$TotalServices       = ($ServiceTotal[0]['total_services']) ? $ServiceTotal[0]['total_services'] : 0;
			$TotalServicesAmount = ($ServiceTotal[0]['total_item_price']) ? $ServiceTotal[0]['total_item_price'] : 0;
			
			$TotalRefundServices       = ($RefundServiceTotal[0]['total_services']) ? $RefundServiceTotal[0]['total_services'] : 0;
			$TotalRefundServicesAmount = ($RefundServiceTotal[0]['total_item_price']) ? $RefundServiceTotal[0]['total_item_price'] : 0;
			
			$TotalServicesAmount = $TotalServicesAmount - $TotalRefundServicesAmount;
			
			$TotalProducts       = ($PrductTotal[0]['total_products']) ? $PrductTotal[0]['total_products'] : 0;
			$TotalProductsAmount = ($PrductTotal[0]['total_item_price']) ? $PrductTotal[0]['total_item_price'] : 0;
			
			$TotalRefundProducts       = ($PrductRefundTotal[0]['total_products']) ? $PrductRefundTotal[0]['total_products'] : 0;
			$TotalRefundProductsAmount = ($PrductRefundTotal[0]['total_item_price']) ? $PrductRefundTotal[0]['total_item_price'] : 0;
			
			$TotalProductsAmount = $TotalProductsAmount - $TotalRefundProductsAmount;
			
			$TotalVouchers       = ($VoucherTotal[0]['total_vouchers']) ? $VoucherTotal[0]['total_vouchers'] : 0;
			$TotalVouchersAmount = ($VoucherTotal[0]['total_item_price']) ? $VoucherTotal[0]['total_item_price'] : 0;
			
			$TotalRefundVouchers       = ($VoucherRefundTotal[0]['total_vouchers']) ? $VoucherRefundTotal[0]['total_vouchers'] : 0;
			$TotalRefundVouchersAmount = ($VoucherRefundTotal[0]['total_item_price']) ? $VoucherRefundTotal[0]['total_item_price'] : 0;
			
			$TotalVouchersAmount = $TotalVouchersAmount - $TotalRefundVouchersAmount;
			
			$TotalPaidplan       = ($PaidplaTotal[0]['total_paidplan']) ? $PaidplaTotal[0]['total_paidplan'] : 0;
			$TotalPaidplanAmount = ($PaidplaTotal[0]['total_item_price']) ? $PaidplaTotal[0]['total_item_price'] : 0;
			
			$TotalRefundPaidplan       = ($PaidplaRefundTotal[0]['total_paidplan']) ? $PaidplaRefundTotal[0]['total_paidplan'] : 0;
			$TotalRefundPaidplanAmount = ($PaidplaRefundTotal[0]['total_item_price']) ? $PaidplaRefundTotal[0]['total_item_price'] : 0;

			$TotalPaidplanAmount = $TotalPaidplanAmount - $TotalRefundPaidplanAmount;	
			
			$TotalAllThing       = $TotalServices + $TotalProducts + $TotalVouchers + $TotalPaidplan;
			$TotalAllRefundThing = $TotalRefundServices + $TotalRefundProducts + $TotalRefundVouchers + $TotalRefundPaidplan;
			$TotalAllAmount      = $TotalServicesAmount + $TotalProductsAmount + $TotalVouchersAmount + $TotalPaidplanAmount;		
			
			// get payment methods
			$paymentTypes = paymentType::select('id','payment_type')->where('user_id', $AdminId)->orderBy('order_id', 'asc')->get()->toArray();

			$paymentByTotal = array();
			$totalVoucherRedemption = 0;
			$totalRefundVoucherRedemption = 0;
			$totalTips = 0;
			$totalRefundedTips = 0;
			
			$totalPaymentCollected = 0;
			$totalPaymentRefunded  = 0;
			
			if(!empty($paymentTypes)) {
				foreach($paymentTypes as $paymentTypeData) {
					// get invoices
					$getSoldInvoice = Invoice::select('invoice.*')->where('invoice.user_id',$AdminId)->where('invoice.invoice_status',1)->where('invoice.payment_id',$paymentTypeData['id'])->orderBy('invoice.id','desc')->get()->toArray();
					
					$total_collected   = 0;
					$total_voucher_red = 0;
					
					if(!empty($getSoldInvoice)){
						foreach($getSoldInvoice as $getSoldInvoiceData){
							//get applied vouchers 
							
							$getAppliedVoucher = InvoiceVoucher::select(DB::raw('SUM(voucher_amount) as total_redemption'))->where('invoice_id', $getSoldInvoiceData['id'])->orderBy('id', 'desc')->get()->toArray();
							
							$redemptionValue = 0;
							if(!empty($getAppliedVoucher)){
								$redemptionValue = $getAppliedVoucher[0]['total_redemption'];
								$totalVoucherRedemption = $totalVoucherRedemption + $getAppliedVoucher[0]['total_redemption'];
								$total_voucher_red = $total_voucher_red + $getAppliedVoucher[0]['total_redemption'];
							}
							
							$invoiceMainTotal = ($getSoldInvoiceData['invoice_total'] - $redemptionValue);
							$total_collected  = $total_collected + $invoiceMainTotal;
							
							$getStaffTips = StaffTip::select(DB::raw('SUM(tip_amount) as total_tip'))->where('inovice_id', $getSoldInvoiceData['id'])->orderBy('id', 'desc')->get()->toArray();
							if(!empty($getStaffTips)){
								$totalTips = $totalTips + $getStaffTips[0]['total_tip'];
							}
						}
					}
					
					$totalPaymentCollected = $totalPaymentCollected + $total_collected + $total_voucher_red;
					
					// get invoices
					$getRefundInvoice = Invoice::select('invoice.*')->where('invoice.user_id',$AdminId)->where('invoice.invoice_status',2)->where('invoice.payment_id',$paymentTypeData['id'])->orderBy('invoice.id','desc')->get()->toArray();
					$total_refunded = 0;
					$total_refunded_voucher = 0;
					
					if(!empty($getRefundInvoice)){
						foreach($getRefundInvoice as $getRefundInvoiceData){
							//get applied vouchers 
							
							$getAppliedVoucher = InvoiceVoucher::select(DB::raw('SUM(voucher_amount) as total_redemption'))->where('invoice_id', $getRefundInvoiceData['id'])->orderBy('id', 'desc')->get()->toArray();
							
							$redemptionValue = 0;
							if(!empty($getAppliedVoucher)){
								$redemptionValue = $getAppliedVoucher[0]['total_redemption'];
								$totalRefundVoucherRedemption = $totalRefundVoucherRedemption + $getAppliedVoucher[0]['total_redemption'];
								$total_refunded_voucher = $total_refunded_voucher + $getAppliedVoucher[0]['total_redemption'];
							}
							
							$invoiceMainTotal = ($getRefundInvoiceData['invoice_total'] - $redemptionValue);
							$total_refunded  = $total_refunded + $invoiceMainTotal;
							
							$getStaffTips = StaffTip::select(DB::raw('SUM(tip_amount) as total_tip'))->where('inovice_id', $getRefundInvoiceData['id'])->orderBy('id', 'desc')->get()->toArray();
							if(!empty($getStaffTips)){
								$totalRefundedTips = $totalRefundedTips + $getStaffTips[0]['total_tip'];
							}
						}
					}
					
					$totalPaymentRefunded = $totalPaymentRefunded + $total_refunded + $total_refunded_voucher;
					
					$tempPaymentTotal['payment_method']  = $paymentTypeData['payment_type'];
					$tempPaymentTotal['total_collected'] = $total_collected;
					$tempPaymentTotal['total_refunded']  = $total_refunded;
					array_push($paymentByTotal,$tempPaymentTotal);
				}
			}
			
			// get all locations		
			$Locations = Location::select('id','location_name','location_address','country_code','location_phone')->where(['user_id'=>$AdminId])->orderBy('id', 'ASC')->get()->toArray();	
			
			return view('sales.daily-sales',compact('TotalServices','TotalRefundServices','TotalServicesAmount','TotalProducts','TotalRefundProducts','TotalProductsAmount','TotalVouchers','TotalRefundVouchers','TotalVouchersAmount','TotalPaidplan','TotalRefundPaidplan','TotalPaidplanAmount','TotalAllThing','TotalAllRefundThing','TotalAllAmount','paymentByTotal','totalVoucherRedemption','totalRefundVoucherRedemption','totalPaymentCollected','totalPaymentRefunded','Locations','totalTips','totalRefundedTips'));	
		} else if (Auth::user()->can('sales_appointments')) {
			return redirect()->route('appointmentsList');
		} else if (Auth::user()->can('sales_invoices')) {
			return redirect()->route('salesList');
		} else if (Auth::user()->can('sales_vouchers')) {
			return redirect()->route('vouchers');
		} else {
			return redirect()->route('calander');
		}		
    } 
	
	public function getDailysaleFilter(Request $request){
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
			
			$data_arr = array();
			
			$start_date  = ($request->start_date) ? date("Y-m-d",strtotime($request->start_date)) : '';
			$end_date    = ($request->end_date) ? date("Y-m-d",strtotime($request->end_date)) : '';
			$location_id = ($request->location_id) ? $request->location_id : '';
			
			if($start_date != '' && $end_date != ''){
				if($location_id != ''){
					$locationWhere = array('invoice.location_id' => $location_id);
				} else {
					$locationWhere = array();
				}
					
				$ServiceTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_services'),DB::raw('SUM(invoice_items.item_price) as total_item_price'))->join('invoice','invoice.id','=','invoice_items.invoice_id')->where(['invoice.user_id'=>$AdminId])->where('invoice_items.item_type','services')->whereDate('invoice.created_at','>=',$start_date)->whereDate('invoice.created_at','<=',$end_date)->where('invoice.invoice_status','!=','3')->where('invoice.invoice_status','!=','2')->where($locationWhere)->orderBy('invoice_items.id', 'ASC')->get()->toArray();	
				
				$RefundServiceTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_services'),DB::raw('SUM(invoice_items.item_price) as total_item_price'))->join('invoice','invoice.id','=','invoice_items.invoice_id')->where(['invoice.user_id'=>$AdminId])->where('invoice_items.item_type','services')->whereDate('invoice.created_at','>=',$start_date)->whereDate('invoice.created_at','<=',$end_date)->where('invoice.invoice_status','2')->where($locationWhere)->orderBy('invoice_items.id', 'ASC')->get()->toArray();	
				
				$PrductTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_products'),DB::raw('SUM(invoice_items.item_price) as total_item_price'))->join('invoice','invoice.id','=','invoice_items.invoice_id')->where(['invoice.user_id'=>$AdminId])->where('invoice_items.item_type','product')->whereDate('invoice.created_at','>=',$start_date)->whereDate('invoice.created_at','<=',$end_date)->where('invoice.invoice_status','!=','3')->where('invoice.invoice_status','!=','2')->where($locationWhere)->orderBy('invoice_items.id', 'ASC')->get()->toArray();
				
				$PrductRefundTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_products'),DB::raw('SUM(invoice_items.item_price) as total_item_price'))->join('invoice','invoice.id','=','invoice_items.invoice_id')->where(['invoice.user_id'=>$AdminId])->where('invoice_items.item_type','product')->whereDate('invoice.created_at','>=',$start_date)->whereDate('invoice.created_at','<=',$end_date)->where('invoice.invoice_status','2')->where($locationWhere)->orderBy('invoice_items.id', 'ASC')->get()->toArray();

				$VoucherTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_vouchers'),DB::raw('SUM(invoice_items.item_price) as total_item_price'))->join('invoice','invoice.id','=','invoice_items.invoice_id')->where(['invoice.user_id'=>$AdminId])->where('invoice_items.item_type','voucher')->whereDate('invoice.created_at','>=',$start_date)->whereDate('invoice.created_at','<=',$end_date)->where('invoice.invoice_status','!=','3')->where('invoice.invoice_status','!=','2')->where($locationWhere)->orderBy('invoice_items.id', 'ASC')->get()->toArray();
				
				$VoucherRefundTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_vouchers'),DB::raw('SUM(invoice_items.item_price) as total_item_price'))->join('invoice','invoice.id','=','invoice_items.invoice_id')->where(['invoice.user_id'=>$AdminId])->where('invoice_items.item_type','voucher')->whereDate('invoice.created_at','>=',$start_date)->whereDate('invoice.created_at','<=',$end_date)->where('invoice.invoice_status','2')->where($locationWhere)->orderBy('invoice_items.id', 'ASC')->get()->toArray();

				$PaidplaTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_paidplan'),DB::raw('SUM(invoice_items.item_price) as total_item_price'))->join('invoice','invoice.id','=','invoice_items.invoice_id')->where(['invoice.user_id'=>$AdminId])->where('invoice_items.item_type','paidplan')->whereDate('invoice.created_at','>=',$start_date)->whereDate('invoice.created_at','<=',$end_date)->where('invoice.invoice_status','!=','3')->where('invoice.invoice_status','!=','2')->where($locationWhere)->orderBy('invoice_items.id', 'ASC')->get()->toArray();	

				$PaidplaRefundTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_paidplan'),DB::raw('SUM(invoice_items.item_price) as total_item_price'))->join('invoice','invoice.id','=','invoice_items.invoice_id')->where(['invoice.user_id'=>$AdminId])->where('invoice_items.item_type','paidplan')->whereDate('invoice.created_at','>=',$start_date)->whereDate('invoice.created_at','<=',$end_date)->where('invoice.invoice_status','2')->where($locationWhere)->orderBy('invoice_items.id', 'ASC')->get()->toArray();		
					
			} else {
				if($location_id != ''){
					$locationWhere = array('invoice.location_id' => $location_id);
				} else {
					$locationWhere = array();
				}
				
				$ServiceTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_services'),DB::raw('SUM(invoice_items.item_price) as total_item_price'))->join('invoice','invoice.id','=','invoice_items.invoice_id')->where(['invoice.user_id'=>$AdminId])->where('invoice_items.item_type','services')->where('invoice.invoice_status','!=','3')->where('invoice.invoice_status','!=','2')->where($locationWhere)->orderBy('invoice_items.id', 'ASC')->get()->toArray();	
				
				$RefundServiceTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_services'),DB::raw('SUM(invoice_items.item_price) as total_item_price'))->join('invoice','invoice.id','=','invoice_items.invoice_id')->where(['invoice.user_id'=>$AdminId])->where('invoice_items.item_type','services')->where('invoice.invoice_status','2')->where($locationWhere)->orderBy('invoice_items.id', 'ASC')->get()->toArray();	
		
				$PrductTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_products'),DB::raw('SUM(invoice_items.item_price) as total_item_price'))->join('invoice','invoice.id','=','invoice_items.invoice_id')->where(['invoice.user_id'=>$AdminId])->where('invoice_items.item_type','product')->where('invoice.invoice_status','!=','3')->where('invoice.invoice_status','!=','2')->where($locationWhere)->orderBy('invoice_items.id', 'ASC')->get()->toArray();
				
				$PrductRefundTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_products'),DB::raw('SUM(invoice_items.item_price) as total_item_price'))->join('invoice','invoice.id','=','invoice_items.invoice_id')->where(['invoice.user_id'=>$AdminId])->where('invoice_items.item_type','product')->where('invoice.invoice_status','2')->where($locationWhere)->orderBy('invoice_items.id', 'ASC')->get()->toArray();

				$VoucherTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_vouchers'),DB::raw('SUM(invoice_items.item_price) as total_item_price'))->join('invoice','invoice.id','=','invoice_items.invoice_id')->where(['invoice.user_id'=>$AdminId])->where('invoice_items.item_type','voucher')->where('invoice.invoice_status','!=','3')->where('invoice.invoice_status','!=','2')->where($locationWhere)->orderBy('invoice_items.id', 'ASC')->get()->toArray();
				
				$VoucherRefundTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_vouchers'),DB::raw('SUM(invoice_items.item_price) as total_item_price'))->join('invoice','invoice.id','=','invoice_items.invoice_id')->where(['invoice.user_id'=>$AdminId])->where('invoice_items.item_type','voucher')->where('invoice.invoice_status','2')->where($locationWhere)->orderBy('invoice_items.id', 'ASC')->get()->toArray();

				$PaidplaTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_paidplan'),DB::raw('SUM(invoice_items.item_price) as total_item_price'))->join('invoice','invoice.id','=','invoice_items.invoice_id')->where(['invoice.user_id'=>$AdminId])->where('invoice_items.item_type','paidplan')->where('invoice.invoice_status','!=','3')->where('invoice.invoice_status','!=','2')->where($locationWhere)->orderBy('invoice_items.id', 'ASC')->get()->toArray();
				
				$PaidplaRefundTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_paidplan'),DB::raw('SUM(invoice_items.item_price) as total_item_price'))->join('invoice','invoice.id','=','invoice_items.invoice_id')->where(['invoice.user_id'=>$AdminId])->where('invoice_items.item_type','paidplan')->where('invoice.invoice_status','2')->where($locationWhere)->orderBy('invoice_items.id', 'ASC')->get()->toArray();
			}
			
			$TotalAllThing  = 0;
			$TotalAllAmount = 0;
			
			$TotalServices       = ($ServiceTotal[0]['total_services']) ? $ServiceTotal[0]['total_services'] : 0;
			$TotalServicesAmount = ($ServiceTotal[0]['total_item_price']) ? $ServiceTotal[0]['total_item_price'] : 0;
			
			$TotalRefundServices       = ($RefundServiceTotal[0]['total_services']) ? $RefundServiceTotal[0]['total_services'] : 0;
			$TotalRefundServicesAmount = ($RefundServiceTotal[0]['total_item_price']) ? $RefundServiceTotal[0]['total_item_price'] : 0;
			
			$TotalServicesAmount = $TotalServicesAmount - $TotalRefundServicesAmount;
			
			$TotalProducts       = ($PrductTotal[0]['total_products']) ? $PrductTotal[0]['total_products'] : 0;
			$TotalProductsAmount = ($PrductTotal[0]['total_item_price']) ? $PrductTotal[0]['total_item_price'] : 0;
			
			$TotalRefundProducts       = ($PrductRefundTotal[0]['total_products']) ? $PrductRefundTotal[0]['total_products'] : 0;
			$TotalRefundProductsAmount = ($PrductRefundTotal[0]['total_item_price']) ? $PrductRefundTotal[0]['total_item_price'] : 0;
			
			$TotalProductsAmount = $TotalProductsAmount - $TotalRefundProductsAmount;
			
			$TotalVouchers       = ($VoucherTotal[0]['total_vouchers']) ? $VoucherTotal[0]['total_vouchers'] : 0;
			$TotalVouchersAmount = ($VoucherTotal[0]['total_item_price']) ? $VoucherTotal[0]['total_item_price'] : 0;
			
			$TotalRefundVouchers       = ($VoucherRefundTotal[0]['total_vouchers']) ? $VoucherRefundTotal[0]['total_vouchers'] : 0;
			$TotalRefundVouchersAmount = ($VoucherRefundTotal[0]['total_item_price']) ? $VoucherRefundTotal[0]['total_item_price'] : 0;
			
			$TotalVouchersAmount = $TotalVouchersAmount - $TotalRefundVouchersAmount;
			
			$TotalPaidplan       = ($PaidplaTotal[0]['total_paidplan']) ? $PaidplaTotal[0]['total_paidplan'] : 0;
			$TotalPaidplanAmount = ($PaidplaTotal[0]['total_item_price']) ? $PaidplaTotal[0]['total_item_price'] : 0;
				
			$TotalRefundPaidplan       = ($PaidplaRefundTotal[0]['total_paidplan']) ? $PaidplaRefundTotal[0]['total_paidplan'] : 0;
			$TotalRefundPaidplanAmount = ($PaidplaRefundTotal[0]['total_item_price']) ? $PaidplaRefundTotal[0]['total_item_price'] : 0;

			$TotalPaidplanAmount = $TotalPaidplanAmount - $TotalRefundPaidplanAmount;		
				
			$TotalAllThing = $TotalServices + $TotalProducts + $TotalVouchers + $TotalPaidplan;
			$TotalAllRefundThing = $TotalRefundServices + $TotalRefundProducts + $TotalRefundVouchers + $TotalRefundPaidplan;
			$TotalAllAmount = $TotalServicesAmount + $TotalProductsAmount + $TotalVouchersAmount + $TotalPaidplanAmount;
			
			// get payment methods
			$paymentTypes = paymentType::select('id','payment_type')->where('user_id', $AdminId)->orderBy('order_id', 'asc')->get()->toArray();

			$paymentByTotal = array();
			$totalVoucherRedemption = 0;
			$totalRefundVoucherRedemption = 0;
			$totalTips = 0;
			$totalRefundedTips = 0;
			
			$totalPaymentCollected = 0;
			$totalPaymentRefunded  = 0;
			
			if(!empty($paymentTypes)) {
				foreach($paymentTypes as $paymentTypeData) {
					// get invoices
					if($start_date != '' && $end_date != ''){
						if($location_id != ''){
							$locationWhere = array('invoice.location_id' => $location_id);
						} else {
							$locationWhere = array();
						}
						
						$getSoldInvoice = Invoice::select('invoice.*')->where('invoice.user_id',$AdminId)->where('invoice.invoice_status',1)->where('invoice.payment_id',$paymentTypeData['id'])->whereDate('invoice.created_at','>=',$start_date)->whereDate('invoice.created_at','<=',$end_date)->where($locationWhere)->orderBy('invoice.id','desc')->get()->toArray();
						
						$getRefundedInvoice = Invoice::select('invoice.*')->where('invoice.user_id',$AdminId)->where('invoice.invoice_status',2)->where('invoice.payment_id',$paymentTypeData['id'])->whereDate('invoice.created_at','>=',$start_date)->whereDate('invoice.created_at','<=',$end_date)->where($locationWhere)->orderBy('invoice.id','desc')->get()->toArray();
						
					} else {
						if($location_id != ''){
							$locationWhere = array('invoice.location_id' => $location_id);
						} else {
							$locationWhere = array();
						}
						
						$getSoldInvoice = Invoice::select('invoice.*')->where('invoice.user_id',$AdminId)->where('invoice.invoice_status',1)->where('invoice.payment_id',$paymentTypeData['id'])->where($locationWhere)->orderBy('invoice.id','desc')->get()->toArray();
						
						$getRefundedInvoice = Invoice::select('invoice.*')->where('invoice.user_id',$AdminId)->where('invoice.invoice_status',2)->where('invoice.payment_id',$paymentTypeData['id'])->where($locationWhere)->orderBy('invoice.id','desc')->get()->toArray();
					}
					
					$total_collected   = 0;
					$total_voucher_red = 0;
					
					if(!empty($getSoldInvoice)){
						foreach($getSoldInvoice as $getSoldInvoiceData){
							//get applied vouchers 
							
							$getAppliedVoucher = InvoiceVoucher::select(DB::raw('SUM(voucher_amount) as total_redemption'))->where('invoice_id', $getSoldInvoiceData['id'])->orderBy('id', 'desc')->get()->toArray();
							
							$redemptionValue = 0;
							if(!empty($getAppliedVoucher)){
								$redemptionValue = $getAppliedVoucher[0]['total_redemption'];
								$totalVoucherRedemption = $totalVoucherRedemption + $getAppliedVoucher[0]['total_redemption'];
								$total_voucher_red = $total_voucher_red + $getAppliedVoucher[0]['total_redemption'];
							}
							
							$invoiceMainTotal = ($getSoldInvoiceData['invoice_total'] - $redemptionValue);
							$total_collected  = $total_collected + $invoiceMainTotal;
							
							$getStaffTips = StaffTip::select(DB::raw('SUM(tip_amount) as total_tip'))->where('inovice_id', $getSoldInvoiceData['id'])->orderBy('id', 'desc')->get()->toArray();
							if(!empty($getStaffTips)){
								$totalTips = $totalTips + $getStaffTips[0]['total_tip'];
							}
						}
					}
					
					$totalPaymentCollected = $totalPaymentCollected + $total_collected + $total_voucher_red;
					
					$total_refunded = 0;
					$total_refunded_voucher = 0;
					
					if(!empty($getRefundedInvoice)){
						foreach($getRefundedInvoice as $getRefundedInvoiceData){
							//get applied vouchers 
							
							$getAppliedVoucher = InvoiceVoucher::select(DB::raw('SUM(voucher_amount) as total_redemption'))->where('invoice_id', $getRefundedInvoiceData['id'])->orderBy('id', 'desc')->get()->toArray();
							
							$redemptionValue = 0;
							if(!empty($getAppliedVoucher)){
								$redemptionValue = $getAppliedVoucher[0]['total_redemption'];
								$totalRefundVoucherRedemption = $totalRefundVoucherRedemption + $getAppliedVoucher[0]['total_redemption'];
								$total_refunded_voucher = $total_refunded_voucher + $getAppliedVoucher[0]['total_redemption'];
							}
							
							$invoiceMainTotal = ($getRefundedInvoiceData['invoice_total'] - $redemptionValue);
							$total_refunded  = $total_refunded + $invoiceMainTotal;
							
							$getStaffTips = StaffTip::select(DB::raw('SUM(tip_amount) as total_tip'))->where('inovice_id', $getRefundedInvoiceData['id'])->orderBy('id', 'desc')->get()->toArray();
							if(!empty($getStaffTips)){
								$totalRefundedTips = $totalRefundedTips + $getStaffTips[0]['total_tip'];
							}
						}
					}
					
					$totalPaymentRefunded = $totalPaymentRefunded + $total_refunded + $total_refunded_voucher;
					
					$tempPaymentTotal['payment_method']  = $paymentTypeData['payment_type'];
					$tempPaymentTotal['total_collected'] = $total_collected;
					$tempPaymentTotal['total_refunded']  = $total_refunded;
					array_push($paymentByTotal,$tempPaymentTotal);
				}
			}
			
			$total_services      = ($ServiceTotal[0]['total_services']) ? $ServiceTotal[0]['total_services'] : 0;
			$total_service_price = ($ServiceTotal[0]['total_item_price']) ? number_format($ServiceTotal[0]['total_item_price'],2) : 0;
			
			$total_products      = ($PrductTotal[0]['total_products']) ? $PrductTotal[0]['total_products'] : 0;
			$total_product_price = ($PrductTotal[0]['total_item_price']) ? number_format($PrductTotal[0]['total_item_price'],2) : 0;
			
			$total_vouchers       = ($VoucherTotal[0]['total_vouchers']) ? $VoucherTotal[0]['total_vouchers'] : 0;
			$total_vouchers_price = ($VoucherTotal[0]['total_item_price']) ? number_format($VoucherTotal[0]['total_item_price'],2) : 0;
			
			$total_paidplan       = ($PaidplaTotal[0]['total_paidplan']) ? $PaidplaTotal[0]['total_paidplan'] : 0;
			$total_paidplan_price = ($PaidplaTotal[0]['total_item_price']) ? number_format($PaidplaTotal[0]['total_item_price'],2) : 0;
			
			$HTML = '
			<div class="col-lg-6 col-xxl-4 order-1 order-xxl-2">
				<div class="card card-custom card-stretch gutter-b">
					<div class="card-header border-0 px-5">
						<h3 class="card-title font-weight-bolder text-dark">Transaction Summary
						</h3>
					</div>
					<div class=" pt-2">
						<div class="d-flex align-items-center p-2">
							<table class="table">
								<thead>
									<tr>
										<th scope="col">Item type</th>
										<th scope="col">Sales qty</th>
										<th scope="col">Refund qty</th>
										<th scope="col">Gross total</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Services</td>
										<td>'.$total_services.'</td>
										<td>'.$TotalRefundServices.'</td>
										<td>CA $'.number_format($TotalServicesAmount,2).'</td>
									</tr>
									<tr>
										<td>Products</td>
										<td>'.$total_products.'</td>
										<td>'.$TotalRefundProducts.'</td>
										<td>CA $'.number_format($TotalProductsAmount,2).'</td>
									</tr>
									<tr>
										<td>Vouchers</td>
										<td>'.$total_vouchers.'</td>
										<td>'.$TotalRefundVouchers.'</td>
										<td>CA $'.number_format($TotalVouchersAmount,2).'</td>
									</tr>
									<tr>
										<td>Paid Plans</td>
										<td>'.$total_paidplan.'</td>
										<td>'.$TotalRefundPaidplan.'</td>
										<td>CA $'.number_format($TotalPaidplanAmount,2).'</td>
									</tr>
									<tr class="font-weight-bold">
										<td>Total</td>
										<td>'.$TotalAllThing.'</td>
										<td>'.$TotalAllRefundThing.'</td>
										<td>CA $'.number_format($TotalAllAmount,2).'</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-6 col-xxl-4 order-1 order-xxl-2">
				<div class="card card-custom card-stretch gutter-b">
					<div class="card-header border-0 px-5">
						<h3 class="card-title font-weight-bolder text-dark">Cash Movement Summary</h3>
					</div>
					<div class="pt-2">
						<div class="d-flex align-items-center p-2">
							<table class="table">
								<thead>
									<tr>
										<th scope="col">Payment type</th>
										<th scope="col">Payments collected </th>
										<th scope="col">Refunds paid</th>
									</tr>
								</thead>
								<tbody>';
									if(!empty($paymentByTotal)){
										foreach($paymentByTotal as $paymentByTotalData){
											$HTML .= '
											<tr>
												<td>'.$paymentByTotalData['payment_method'].'</td>
												<td>CA $'.number_format($paymentByTotalData['total_collected'],2).'</td>
												<td>CA $'.number_format($paymentByTotalData['total_refunded'],2).'</td>
											</tr>';
										}
									}
									$HTML .= '
									<tr>
										<td>Voucher Redemptions</td>
										<td>CA $'.number_format($totalVoucherRedemption,2).'</td>
										<td>CA $'.number_format($totalRefundVoucherRedemption,2).'</td>
									</tr>
									<tr class="font-weight-bold">
										<td>Payments collected</td>
										<td>CA $'.number_format($totalPaymentCollected,2).'</td>
										<td>CA $'.number_format($totalPaymentRefunded,2).'</td>
									</tr>
									<tr class="font-weight-bold">
										<td>Of which tips</td>
										<td>CA $'.number_format($totalTips,2).'</td>
										<td>CA $'.number_format($totalRefundedTips,2).'</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>';
			
			$data["status"]   = true;
			$data["htmldata"] = $HTML;
			return JsonReturn::success($data);
		}
	}

	public function dailySalesExcelExport(Request $request)
	{
		$datefilter  = ($request->datefilter) ? $request->datefilter : '';

		$start_date = '';
		$end_date = '';
		if(!empty($request->datefilter)) {
			$dateExplode = explode('-', $request->datefilter);

			if(!empty($dateExplode[0])) {
				$start_date = date('Y-m-d', strtotime($dateExplode[0]));
			}
			if(!empty($dateExplode[1])) {
				$end_date = date('Y-m-d', strtotime($dateExplode[1]));
			}
		}

		$location_id = ($request->location_id) ? $request->location_id : '';

		return Excel::download(new DailySalesexport($start_date, $end_date, $location_id), 'Daily_sales.xls');
    }

	public function dailySalesCsvExport(Request $request){

		$datefilter  = ($request->datefilter) ? $request->datefilter : '';

		$start_date = '';
		$end_date = '';
		if(!empty($request->datefilter)) {
			$dateExplode = explode('-', $request->datefilter);

			if(!empty($dateExplode[0])) {
				$start_date = date('Y-m-d', strtotime($dateExplode[0]));
			}
			if(!empty($dateExplode[1])) {
				$end_date = date('Y-m-d', strtotime($dateExplode[1]));
			}
		}

		$location_id = ($request->location_id) ? $request->location_id : '';

		return Excel::download(new DailySalesExportCSV($start_date, $end_date, $location_id), 'Daily_sales.csv');
    }

	public function getDailySalesPDF(Request $request){
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
			
			$data_arr = array();
			
			$datefilter = $request->datefilter;
			$start_date = '';
			$end_date   = '';
			
			if($datefilter != ''){
				$expDate = explode("-",$datefilter);
				
				$start_date  = ($expDate[0]) ? date("Y-m-d",strtotime(trim($expDate[0]))) : '';
				$end_date    = ($expDate[1]) ? date("Y-m-d",strtotime(trim($expDate[1]))) : '';	
			}
			
			$location_id = ($request->location_id) ? $request->location_id : '';
			
			if($start_date != '' && $end_date != ''){
				if($location_id != ''){
					$locationWhere = array('invoice.location_id' => $location_id);
				} else {
					$locationWhere = array();
				}
					
				$ServiceTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_services'),DB::raw('SUM(invoice_items.item_price) as total_item_price'))->join('invoice','invoice.id','=','invoice_items.invoice_id')->where(['invoice.user_id'=>$AdminId])->where('invoice_items.item_type','services')->whereDate('invoice.created_at','>=',$start_date)->whereDate('invoice.created_at','<=',$end_date)->where('invoice.invoice_status','!=','3')->where('invoice.invoice_status','!=','2')->where($locationWhere)->orderBy('invoice_items.id', 'ASC')->get()->toArray();	
				
				$RefundServiceTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_services'),DB::raw('SUM(invoice_items.item_price) as total_item_price'))->join('invoice','invoice.id','=','invoice_items.invoice_id')->where(['invoice.user_id'=>$AdminId])->where('invoice_items.item_type','services')->whereDate('invoice.created_at','>=',$start_date)->whereDate('invoice.created_at','<=',$end_date)->where('invoice.invoice_status','2')->where($locationWhere)->orderBy('invoice_items.id', 'ASC')->get()->toArray();	
		
				$PrductTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_products'),DB::raw('SUM(invoice_items.item_price) as total_item_price'))->join('invoice','invoice.id','=','invoice_items.invoice_id')->where(['invoice.user_id'=>$AdminId])->where('invoice_items.item_type','product')->whereDate('invoice.created_at','>=',$start_date)->whereDate('invoice.created_at','<=',$end_date)->where('invoice.invoice_status','!=','3')->where('invoice.invoice_status','!=','2')->where($locationWhere)->orderBy('invoice_items.id', 'ASC')->get()->toArray();

				$PrductRefundTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_products'),DB::raw('SUM(invoice_items.item_price) as total_item_price'))->join('invoice','invoice.id','=','invoice_items.invoice_id')->where(['invoice.user_id'=>$AdminId])->where('invoice_items.item_type','product')->whereDate('invoice.created_at','>=',$start_date)->whereDate('invoice.created_at','<=',$end_date)->where('invoice.invoice_status','2')->where($locationWhere)->orderBy('invoice_items.id', 'ASC')->get()->toArray();

				$VoucherTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_vouchers'),DB::raw('SUM(invoice_items.item_price) as total_item_price'))->join('invoice','invoice.id','=','invoice_items.invoice_id')->where(['invoice.user_id'=>$AdminId])->where('invoice_items.item_type','voucher')->whereDate('invoice.created_at','>=',$start_date)->whereDate('invoice.created_at','<=',$end_date)->where('invoice.invoice_status','!=','3')->where('invoice.invoice_status','!=','2')->where($locationWhere)->orderBy('invoice_items.id', 'ASC')->get()->toArray();

				$VoucherRefundTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_vouchers'),DB::raw('SUM(invoice_items.item_price) as total_item_price'))->join('invoice','invoice.id','=','invoice_items.invoice_id')->where(['invoice.user_id'=>$AdminId])->where('invoice_items.item_type','voucher')->whereDate('invoice.created_at','>=',$start_date)->whereDate('invoice.created_at','<=',$end_date)->where('invoice.invoice_status','2')->where($locationWhere)->orderBy('invoice_items.id', 'ASC')->get()->toArray();

				$PaidplaTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_paidplan'),DB::raw('SUM(invoice_items.item_price) as total_item_price'))->join('invoice','invoice.id','=','invoice_items.invoice_id')->where(['invoice.user_id'=>$AdminId])->where('invoice_items.item_type','paidplan')->whereDate('invoice.created_at','>=',$start_date)->whereDate('invoice.created_at','<=',$end_date)->where('invoice.invoice_status','!=','3')->where('invoice.invoice_status','!=','2')->where($locationWhere)->orderBy('invoice_items.id', 'ASC')->get()->toArray();		

				$PaidplaRefundTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_paidplan'),DB::raw('SUM(invoice_items.item_price) as total_item_price'))->join('invoice','invoice.id','=','invoice_items.invoice_id')->where(['invoice.user_id'=>$AdminId])->where('invoice_items.item_type','paidplan')->whereDate('invoice.created_at','>=',$start_date)->whereDate('invoice.created_at','<=',$end_date)->where('invoice.invoice_status','2')->where($locationWhere)->orderBy('invoice_items.id', 'ASC')->get()->toArray();
			} else {
				if($location_id != ''){
					$locationWhere = array('invoice.location_id' => $location_id);
				} else {
					$locationWhere = array();
				}
				
				$ServiceTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_services'),DB::raw('SUM(invoice_items.item_price) as total_item_price'))->join('invoice','invoice.id','=','invoice_items.invoice_id')->where(['invoice.user_id'=>$AdminId])->where('invoice_items.item_type','services')->where('invoice.invoice_status','!=','3')->where('invoice.invoice_status','!=','2')->where($locationWhere)->orderBy('invoice_items.id', 'ASC')->get()->toArray();

				$RefundServiceTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_services'),DB::raw('SUM(invoice_items.item_price) as total_item_price'))->join('invoice','invoice.id','=','invoice_items.invoice_id')->where(['invoice.user_id'=>$AdminId])->where('invoice_items.item_type','services')->where('invoice.invoice_status','2')->where($locationWhere)->orderBy('invoice_items.id', 'ASC')->get()->toArray();		
		
				$PrductTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_products'),DB::raw('SUM(invoice_items.item_price) as total_item_price'))->join('invoice','invoice.id','=','invoice_items.invoice_id')->where(['invoice.user_id'=>$AdminId])->where('invoice_items.item_type','product')->where('invoice.invoice_status','!=','3')->where('invoice.invoice_status','!=','2')->where($locationWhere)->orderBy('invoice_items.id', 'ASC')->get()->toArray();

				$PrductRefundTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_products'),DB::raw('SUM(invoice_items.item_price) as total_item_price'))->join('invoice','invoice.id','=','invoice_items.invoice_id')->where(['invoice.user_id'=>$AdminId])->where('invoice_items.item_type','product')->where('invoice.invoice_status','2')->where($locationWhere)->orderBy('invoice_items.id', 'ASC')->get()->toArray();

				$VoucherTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_vouchers'),DB::raw('SUM(invoice_items.item_price) as total_item_price'))->join('invoice','invoice.id','=','invoice_items.invoice_id')->where(['invoice.user_id'=>$AdminId])->where('invoice_items.item_type','voucher')->where('invoice.invoice_status','!=','3')->where('invoice.invoice_status','!=','2')->where($locationWhere)->orderBy('invoice_items.id', 'ASC')->get()->toArray();

				$VoucherRefundTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_vouchers'),DB::raw('SUM(invoice_items.item_price) as total_item_price'))->join('invoice','invoice.id','=','invoice_items.invoice_id')->where(['invoice.user_id'=>$AdminId])->where('invoice_items.item_type','voucher')->where('invoice.invoice_status','2')->where($locationWhere)->orderBy('invoice_items.id', 'ASC')->get()->toArray();

				$PaidplaTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_paidplan'),DB::raw('SUM(invoice_items.item_price) as total_item_price'))->join('invoice','invoice.id','=','invoice_items.invoice_id')->where(['invoice.user_id'=>$AdminId])->where('invoice_items.item_type','paidplan')->where('invoice.invoice_status','!=','3')->where('invoice.invoice_status','!=','2')->where($locationWhere)->orderBy('invoice_items.id', 'ASC')->get()->toArray();

				$PaidplaRefundTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_paidplan'),DB::raw('SUM(invoice_items.item_price) as total_item_price'))->join('invoice','invoice.id','=','invoice_items.invoice_id')->where(['invoice.user_id'=>$AdminId])->where('invoice_items.item_type','paidplan')->where('invoice.invoice_status','2')->where($locationWhere)->orderBy('invoice_items.id', 'ASC')->get()->toArray();
			}
			
			$TotalAllThing  = 0;
			$TotalAllAmount = 0;

			$TotalServices       = ($ServiceTotal[0]['total_services']) ? $ServiceTotal[0]['total_services'] : 0;
			$TotalServicesAmount = ($ServiceTotal[0]['total_item_price']) ? $ServiceTotal[0]['total_item_price'] : 0;
			
			$TotalRefundServices       = ($RefundServiceTotal[0]['total_services']) ? $RefundServiceTotal[0]['total_services'] : 0;
			$TotalRefundServicesAmount = ($RefundServiceTotal[0]['total_item_price']) ? $RefundServiceTotal[0]['total_item_price'] : 0;

			$TotalServicesAmount = $TotalServicesAmount - $TotalRefundServicesAmount;
			
			$TotalProducts       = ($PrductTotal[0]['total_products']) ? $PrductTotal[0]['total_products'] : 0;
			$TotalProductsAmount = ($PrductTotal[0]['total_item_price']) ? $PrductTotal[0]['total_item_price'] : 0;
			
			$TotalRefundProducts       = ($PrductRefundTotal[0]['total_products']) ? $PrductRefundTotal[0]['total_products'] : 0;
			$TotalRefundProductsAmount = ($PrductRefundTotal[0]['total_item_price']) ? $PrductRefundTotal[0]['total_item_price'] : 0;

			$TotalProductsAmount = $TotalProductsAmount - $TotalRefundProductsAmount;
			
			$TotalVouchers       = ($VoucherTotal[0]['total_vouchers']) ? $VoucherTotal[0]['total_vouchers'] : 0;
			$TotalVouchersAmount = ($VoucherTotal[0]['total_item_price']) ? $VoucherTotal[0]['total_item_price'] : 0;
			
			$TotalRefundVouchers       = ($VoucherRefundTotal[0]['total_vouchers']) ? $VoucherRefundTotal[0]['total_vouchers'] : 0;
			$TotalRefundVouchersAmount = ($VoucherRefundTotal[0]['total_item_price']) ? $VoucherRefundTotal[0]['total_item_price'] : 0;

			$TotalVouchersAmount = $TotalVouchersAmount - $TotalRefundVouchersAmount;
			
			$TotalPaidplan       = ($PaidplaTotal[0]['total_paidplan']) ? $PaidplaTotal[0]['total_paidplan'] : 0;
			$TotalPaidplanAmount = ($PaidplaTotal[0]['total_item_price']) ? $PaidplaTotal[0]['total_item_price'] : 0;
				
			$TotalRefundPaidplan       = ($PaidplaRefundTotal[0]['total_paidplan']) ? $PaidplaRefundTotal[0]['total_paidplan'] : 0;
			$TotalRefundPaidplanAmount = ($PaidplaRefundTotal[0]['total_item_price']) ? $PaidplaRefundTotal[0]['total_item_price'] : 0;

			$TotalPaidplanAmount = $TotalPaidplanAmount - $TotalRefundPaidplanAmount;
				
			$TotalAllThing = $TotalServices + $TotalProducts + $TotalVouchers + $TotalPaidplan;
			$TotalAllRefundThing = $TotalRefundServices + $TotalRefundProducts + $TotalRefundVouchers + $TotalRefundPaidplan;
			$TotalAllAmount = $TotalServicesAmount + $TotalProductsAmount + $TotalVouchersAmount + $TotalPaidplanAmount;
			
			// get payment methods
			$paymentTypes = paymentType::select('id','payment_type')->where('user_id', $AdminId)->orderBy('order_id', 'asc')->get()->toArray();

			$paymentByTotal = array();
			$totalVoucherRedemption = 0;
			$totalRefundVoucherRedemption = 0;
			$totalTips = 0;
			$totalRefundedTips = 0;
			
			$totalPaymentCollected = 0;
			$totalPaymentRefunded  = 0;
			
			if(!empty($paymentTypes)) {
				foreach($paymentTypes as $paymentTypeData) {
					// get invoices
					if($start_date != '' && $end_date != ''){
						if($location_id != ''){
							$locationWhere = array('invoice.location_id' => $location_id);
						} else {
							$locationWhere = array();
						}
						
						$getSoldInvoice = Invoice::select('invoice.*')->where('invoice.user_id',$AdminId)->where('invoice.invoice_status',1)->where('invoice.payment_id',$paymentTypeData['id'])->whereDate('invoice.created_at','>=',$start_date)->whereDate('invoice.created_at','<=',$end_date)->where($locationWhere)->orderBy('invoice.id','desc')->get()->toArray();
					} else {
						if($location_id != ''){
							$locationWhere = array('invoice.location_id' => $location_id);
						} else {
							$locationWhere = array();
						}
						
						$getSoldInvoice = Invoice::select('invoice.*')->where('invoice.user_id',$AdminId)->where('invoice.invoice_status',1)->where('invoice.payment_id',$paymentTypeData['id'])->where($locationWhere)->orderBy('invoice.id','desc')->get()->toArray();
					}
					
					$total_collected   = 0;
					$total_refunded    = 0;
					$total_voucher_red = 0;
					
					if(!empty($getSoldInvoice)){
						foreach($getSoldInvoice as $getSoldInvoiceData){
							//get applied vouchers 
							
							$getAppliedVoucher = InvoiceVoucher::select(DB::raw('SUM(voucher_amount) as total_redemption'))->where('invoice_id', $getSoldInvoiceData['id'])->orderBy('id', 'desc')->get()->toArray();
							
							$redemptionValue = 0;
							if(!empty($getAppliedVoucher)){
								$redemptionValue = $getAppliedVoucher[0]['total_redemption'];
								$totalVoucherRedemption = $totalVoucherRedemption + $getAppliedVoucher[0]['total_redemption'];
								$total_voucher_red = $total_voucher_red + $getAppliedVoucher[0]['total_redemption'];
							}
							
							$invoiceMainTotal = ($getSoldInvoiceData['invoice_total'] - $redemptionValue);
							$total_collected  = $total_collected + $invoiceMainTotal;
							
							$getStaffTips = StaffTip::select(DB::raw('SUM(tip_amount) as total_tip'))->where('inovice_id', $getSoldInvoiceData['id'])->orderBy('id', 'desc')->get()->toArray();
							if(!empty($getStaffTips)){
								$totalTips = $totalTips + $getStaffTips[0]['total_tip'];
							}
						}
					}
					
					$totalPaymentCollected = $totalPaymentCollected + $total_collected + $total_voucher_red;
					
					$tempPaymentTotal['payment_method']  = $paymentTypeData['payment_type'];
					$tempPaymentTotal['total_collected'] = $total_collected;
					$tempPaymentTotal['total_refunded']  = 0;
					array_push($paymentByTotal,$tempPaymentTotal);
				}
			}
			
			/*$TotalServices       = ($ServiceTotal[0]['total_services']) ? $ServiceTotal[0]['total_services'] : 0;
			$TotalServicesAmount = ($ServiceTotal[0]['total_item_price']) ? $ServiceTotal[0]['total_item_price'] : 0;
			
			$TotalProducts       = ($PrductTotal[0]['total_products']) ? $PrductTotal[0]['total_products'] : 0;
			$TotalProductsAmount = ($PrductTotal[0]['total_item_price']) ? $PrductTotal[0]['total_item_price'] : 0;
			
			$TotalVouchers       = ($VoucherTotal[0]['total_vouchers']) ? $VoucherTotal[0]['total_vouchers'] : 0;
			$TotalVouchersAmount = ($VoucherTotal[0]['total_item_price']) ? $VoucherTotal[0]['total_item_price'] : 0;
			
			$TotalPaidplan       = ($PaidplaTotal[0]['total_paidplan']) ? $PaidplaTotal[0]['total_paidplan'] : 0;
			$TotalPaidplanAmount = ($PaidplaTotal[0]['total_item_price']) ? $PaidplaTotal[0]['total_item_price'] : 0;
				
			$TotalAllThing = $TotalServices + $TotalProducts + $TotalVouchers + $TotalPaidplan;
			$TotalAllAmount = $TotalServicesAmount + $TotalProductsAmount + $TotalVouchersAmount + $TotalPaidplanAmount;*/
			
			if($location_id != ''){
				$LocationInfo = Location::find($location_id);

				$LocationName = ($LocationInfo) ? $LocationInfo->location_name : '';	
			} else {
				$LocationName = 'All locations';
			}
			
			$pdfData = array();

			$pdfData['LocationName']        = $LocationName;
			$pdfData['TotalServices']       = $TotalServices;
			$pdfData['TotalRefundServices'] = $TotalRefundServices;
			$pdfData['TotalServicesAmount'] = $TotalServicesAmount;
			$pdfData['TotalProducts']       = $TotalProducts;
			$pdfData['TotalRefundProducts'] = $TotalRefundProducts;
			$pdfData['TotalProductsAmount'] = $TotalProductsAmount;
			$pdfData['TotalVouchers']       = $TotalVouchers;
			$pdfData['TotalRefundVouchers'] = $TotalRefundVouchers;
			$pdfData['TotalVouchersAmount'] = $TotalVouchersAmount;
			$pdfData['TotalPaidplan']       = $TotalPaidplan;
			$pdfData['TotalRefundPaidplan'] = $TotalRefundPaidplan;
			$pdfData['TotalPaidplanAmount'] = $TotalPaidplanAmount;
			$pdfData['TotalAllThing']       = $TotalAllThing;
			$pdfData['TotalAllRefundThing']       = $TotalAllRefundThing;
			$pdfData['TotalAllAmount']      = $TotalAllAmount;
			
			$pdfData['paymentByTotal']               = $paymentByTotal;
			$pdfData['totalVoucherRedemption']       = $totalVoucherRedemption;
			$pdfData['totalRefundVoucherRedemption'] = $totalRefundVoucherRedemption;
			$pdfData['totalPaymentCollected']        = $totalPaymentCollected;
			$pdfData['totalPaymentRefunded']         = $totalPaymentRefunded;
			$pdfData['totalTips']                    = $totalTips;
			$pdfData['totalRefundedTips']            = $totalRefundedTips;
			
			if($start_date != '' && $end_date != ''){
				$FileName = 'daily_sale_'.$start_date.'_'.$end_date.'.pdf';
			} else {
				$FileName = 'daily_sale.pdf';
			}

			return PDF::loadView('pdfTemplates.dailySalepdfReport',$pdfData)->setPaper('a4')->download($FileName);
		}
	}

    public function appointmentsList()
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
		
		// get all locations		
		$Locations = Location::select('id','location_name','location_address','country_code','location_phone')->where(['user_id'=>$AdminId])->orderBy('id', 'ASC')->get()->toArray();	
			
		// Get all staff
		$Staff = Staff::select('staff.staff_user_id','users.first_name','users.last_name')->where(['user_id'=>$AdminId])->join('users','users.id','=','staff.staff_user_id')->orderBy('staff.id', 'ASC')->get()->toArray();	
			
		return view('sales.appointments-list',compact('Locations','Staff'));	
    }
	
	public function getSalesAppointmentList(Request $request)
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
			
			$data_arr = array();
			
			$start_date  = ($request->start_date) ? date("Y-m-d",strtotime($request->start_date)) : '';
			$end_date    = ($request->end_date) ? date("Y-m-d",strtotime($request->end_date)) : '';
			$location_id = ($request->location_id) ? $request->location_id : '';
			$staff_id    = ($request->staff_id) ? $request->staff_id : '';
			
			if($start_date != '' && $end_date != ''){
				if($location_id != ''){
					$locationWhere = array('appointments.location_id' => $location_id);
				} else {
					$locationWhere = array();
				}
				
				if($staff_id != ''){
					$staffWhere = array('appointment_services.staff_user_id' => $staff_id);
				} else {
					$staffWhere = array();
				}
				
				// get all appointment services
				$AppointmentServices = AppointmentServices::select('appointment_services.*','users.first_name','users.last_name','appointments.client_id','appointments.location_id','appointments.appointment_status','appointments.is_cancelled','appointments.is_reschedule','appointments.is_noshow')->leftJoin('appointments','appointments.id','=','appointment_services.appointment_id')->leftJoin('users','users.id','=','appointment_services.staff_user_id')->where('appointment_services.user_id', $AdminId)->where($locationWhere)->where($staffWhere)->whereDate('appointment_services.appointment_date','>=', $start_date)->whereDate('appointment_services.appointment_date','<=', $end_date)->orderBy('appointment_services.created_at', 'DESC')->get()->toArray();	
			} else {
				if($location_id != ''){
					$locationWhere = array('appointments.location_id' => $location_id);
				} else {
					$locationWhere = array();
				}
				
				if($staff_id != ''){
					$staffWhere = array('appointment_services.staff_user_id' => $staff_id);
				} else {
					$staffWhere = array();
				}
				
				// get all appointment services
				$AppointmentServices = AppointmentServices::select('appointment_services.*','users.first_name','users.last_name','appointments.client_id','appointments.location_id','appointments.appointment_status','appointments.is_cancelled','appointments.is_reschedule','appointments.is_noshow')->leftJoin('appointments','appointments.id','=','appointment_services.appointment_id')->leftJoin('users','users.id','=','appointment_services.staff_user_id')->where('appointment_services.user_id', $AdminId)->where($locationWhere)->where($staffWhere)->orderBy('appointment_services.created_at', 'DESC')->get()->toArray();
			}
			
			$appointmentEvents = array();
			if(!empty($AppointmentServices)){
				foreach($AppointmentServices as $AppointmentServicesData) {
					
					// get client name
					if($AppointmentServicesData['client_id'] == 0){
						$ClientName = 'Walk-In';
					} else {
						$ClientInfo = Clients::getClientbyID($AppointmentServicesData['client_id']);	
						if(!empty($ClientInfo)){
							$ClientName = $ClientInfo->firstname.' '.$ClientInfo->lastname;	
						} else {
							$ClientName = 'Walk-In';
						}
					}
					
					// get service name
					$servicePrices = ServicesPrice::select('services_price.pricing_name','services.service_name')->leftJoin('services','services.id','=','services_price.service_id')->where('services_price.id',$AppointmentServicesData['service_price_id'])->orderBy('services_price.id', 'asc')->get()->first();
					
					$serviceName = '';
					if(!empty($servicePrices)){
						$serviceName = $servicePrices->service_name.' - '.$servicePrices->pricing_name;
					} else {
						$serviceName = 'N/A';
					}
					
					// get location name
					$getLocationName = Location::select('location_name')->where('id',$AppointmentServicesData['location_id'])->get()->first();
					
					$locationName = '';
					if(!empty($getLocationName)){
						$locationName = $getLocationName->location_name;
					} else {
						$locationName = 'N/A';
					}
					
					// get staff details
					$staffName = '';
					$getUser = User::getUserbyID($AppointmentServicesData['staff_user_id']);
					if(!empty($getUser)){
						$staffName = $getUser->first_name.' '.$getUser->last_name;
					} else {
						$staffName = '';
					}
					
					$Status = 0;
					if($AppointmentServicesData['appointment_status'] == 0){
						$Status = 0;
					} else if($AppointmentServicesData['appointment_status'] == 1){
						$Status = 1;
					} else if($AppointmentServicesData['appointment_status'] == 2){
						$Status = 2;
					} else if($AppointmentServicesData['appointment_status'] == 3){
						$Status = 3;
					} else if($AppointmentServicesData['appointment_status'] == 4){
						$Status = 4;
					}
					
					if($AppointmentServicesData['is_cancelled'] == 1){
						$Status = 7;
					}
					
					if($AppointmentServicesData['is_reschedule'] == 1){
						$Status = 5;
					}
					
					if($AppointmentServicesData['is_noshow'] == 1){
						$Status = 6;
					}
					
					$tempdata['ref_no']           = ($AppointmentServicesData['appointment_id']) ? $AppointmentServicesData['appointment_id'] : 'N/A';
					$tempdata['client_id']        = $AppointmentServicesData['client_id'];
					$tempdata['client_name']      = $ClientName;
					$tempdata['service_name']     = $serviceName;
					$tempdata['appointment_date'] = ($AppointmentServicesData['appointment_date']) ? date("d M Y",strtotime($AppointmentServicesData['appointment_date'])) : 'N/A';
					$tempdata['appointment_time'] = ($AppointmentServicesData['start_time']) ? date("h:i A",strtotime($AppointmentServicesData['start_time'])) : 'N/A';
					$tempdata['duration']         = $this->hoursandmins($AppointmentServicesData['duration']);
					$tempdata['location_name']    = $locationName;
					$tempdata['staff_name']       = $staffName;
					$tempdata['price']            = ($AppointmentServicesData['special_price']) ? $AppointmentServicesData['special_price'] : '0';
					$tempdata['status']           = $Status;
					array_push($appointmentEvents,$tempdata);
				}
			}
         
            return Datatables::of($appointmentEvents)
				->editColumn('ref_no', function ($row) {
					
					$ref_no = '<td><a href="'.route('viewAppointment',['id' => Crypt::encryptString($row['ref_no'])]).'" class="text-blue cursor-pointer" target="_blank">#'.$row['ref_no'].'</a></td>';
					return $ref_no;
				})
				->editColumn('client_name', function ($row) {
					if($row['client_name'] == 'Walk-In'){
						$client_html = '<td>Walk-In</td>';
					} else {
						$client_html = '<td><a href="'.route('viewClient',['id' => $row['client_id']]).'" class="text-blue cursor-pointer" target="_blank">'.$row['client_name'].'</a></td>';
					}
					return $client_html;
				})
				->editColumn('status', function ($row) {
					
					if($row['status'] == 0) {
						$status = '<td><span class="status new">New</span></td>';
					} else if($row['status'] == 1) {
						$status = '<td><span class="status new">Confirmed</span></td>';
					} else if($row['status'] == 2) {
						$status = '<td><span class="status completed">Arrived</span></td>';
					} else if($row['status'] == 3) {
						$status = '<td><span class="status started">Started</span></td>';
					} else if($row['status'] == 4) {
						$status = '<td><span class="status cancelled">Completed</span></td>';
					} else if($row['status'] == 5) {
						$status = '<td><span class="status started">Reschedule</span></td>';
					} else if($row['status'] == 6) {
						$status = '<td><span class="status cancelled">No Show</span></td>';
					} else if($row['status'] == 7) {
						$status = '<td><span class="status cancelled">Cancelled</span></td>';
					}
					
					return $status;
				})
                ->rawColumns(['ref_no','client_name','status'])
				->make(true);
        } 
    }

	public function appointmentdownloadExcel(Request $request){
		// $location = $request->location_id;
		// $staff  = $request->staff_id;

		$datefilter  = ($request->datefilter) ? $request->datefilter : '';

		$start_date = '';
		$end_date = '';
		if(!empty($request->datefilter)) {
			$dateExplode = explode('-', $request->datefilter);

			if(!empty($dateExplode[0])) {
				$start_date = date('Y-m-d', strtotime($dateExplode[0]));
			}
			if(!empty($dateExplode[1])) {
				$end_date = date('Y-m-d', strtotime($dateExplode[1]));
			}
		}
		$location = ($request->location_id) ? $request->location_id : '';
		$staff    = ($request->staff_id) ? $request->staff_id : '';

        return Excel::download(new Appointmentexport($location,$staff,$start_date,$end_date), 'Appointment.xls');
    }

	public function appointmentdownloadcsv(Request $request){
		/*$location = $request->location_id;
		$staff  = $request->staff_id;*/

		$datefilter  = ($request->datefilter) ? $request->datefilter : '';

		$start_date = '';
		$end_date = '';
		if(!empty($request->datefilter)) {
			$dateExplode = explode('-', $request->datefilter);

			if(!empty($dateExplode[0])) {
				$start_date = date('Y-m-d', strtotime($dateExplode[0]));
			}
			if(!empty($dateExplode[1])) {
				$end_date = date('Y-m-d', strtotime($dateExplode[1]));
			}
		}
		$location = ($request->location_id) ? $request->location_id : '';
		$staff    = ($request->staff_id) ? $request->staff_id : '';

        return Excel::download(new Appointmentexport($location,$staff,$start_date,$end_date), 'Appointment.csv');
    }

	public function appointmentdownloadpdf(Request $request){
		
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
        
        $data_arr = array();

        $datefilter  = ($request->datefilter) ? $request->datefilter : '';

		$start_date = '';
		$end_date = '';
		if(!empty($request->datefilter)) {
			$dateExplode = explode('-', $request->datefilter);

			if(!empty($dateExplode[0])) {
				$start_date = date('Y-m-d', strtotime($dateExplode[0]));
			}
			if(!empty($dateExplode[1])) {
				$end_date = date('Y-m-d', strtotime($dateExplode[1]));
			}
		}
		$location_id = ($request->location_id) ? $request->location_id : '';
		$staff_id    = ($request->staff_id) ? $request->staff_id : '';

        if($start_date != '' && $end_date != ''){
            if($location_id != ''){
                $locationWhere = array('appointments.location_id' => $location_id);
            } else {
                $locationWhere = array();
            }
            
            if($staff_id != ''){
                $staffWhere = array('appointment_services.staff_user_id' => $staff_id);
            } else {
                $staffWhere = array();
            }
            
            // get all appointment services
            $AppointmentServices = AppointmentServices::select('appointment_services.*','users.first_name','users.last_name','appointments.client_id','appointments.location_id','appointments.appointment_status','appointments.is_cancelled','appointments.is_reschedule','appointments.is_noshow', 'appointments.is_online_appointment', 'appointments.created_at', 'u2.first_name as u2_first_name', 'u2.last_name as u2_last_name')->leftJoin('appointments','appointments.id','=','appointment_services.appointment_id')->leftJoin('users','users.id','=','appointment_services.staff_user_id')->leftJoin('users as u2','u2.id','=','appointments.created_by')->where('appointment_services.user_id', $AdminId)->where($locationWhere)->where($staffWhere)->whereDate('appointment_services.appointment_date','>=', $start_date)->whereDate('appointment_services.appointment_date','<=', $end_date)->orderBy('appointment_services.created_at', 'asc')->get()->toArray();	
        } else {
            if($location_id != ''){
                $locationWhere = array('appointments.location_id' => $location_id);
            } else {
                $locationWhere = array();
            }
            
            if($staff_id != ''){
                $staffWhere = array('appointment_services.staff_user_id' => $staff_id);
            } else {
                $staffWhere = array();
            }
            
            // get all appointment services
            $AppointmentServices = AppointmentServices::select('appointment_services.*','users.first_name','users.last_name','appointments.client_id','appointments.location_id','appointments.appointment_status','appointments.is_cancelled','appointments.is_reschedule','appointments.is_noshow', 'appointments.is_online_appointment', 'appointments.created_at', 'u2.first_name as u2_first_name', 'u2.last_name as u2_last_name')->leftJoin('appointments','appointments.id','=','appointment_services.appointment_id')->leftJoin('users','users.id','=','appointment_services.staff_user_id')->leftJoin('users as u2','u2.id','=','appointments.created_by')->where('appointment_services.user_id', $AdminId)->where($locationWhere)->where($staffWhere)->orderBy('appointment_services.created_at', 'asc')->get()->toArray();
        }
        /*$AppointmentServices = AppointmentServices::select('appointment_services.*','users.first_name','users.last_name','appointments.client_id','appointments.location_id','appointments.appointment_status','appointments.is_cancelled','appointments.is_reschedule','appointments.is_noshow', 'appointments.is_online_appointment', 'appointments.created_at', 'u2.first_name as u2_first_name', 'u2.last_name as u2_last_name')->leftJoin('appointments','appointments.id','=','appointment_services.appointment_id')->leftJoin('users','users.id','=','appointment_services.staff_user_id')->leftJoin('users as u2','u2.id','=','appointments.created_by')->where('appointment_services.user_id', $AdminId)->where($locationWhere)->where($staffWhere)->orderBy('appointment_services.created_at', 'asc')->get()->toArray();*/
        
        $appointmentEvents = array();
        if(!empty($AppointmentServices)){
            foreach($AppointmentServices as $AppointmentServicesData) {
                
                // get client name
                if($AppointmentServicesData['client_id'] == 0){
                    $ClientName = 'Walk-In';
                } else {
                    $ClientInfo = Clients::getClientbyID($AppointmentServicesData['client_id']);	
                    if(!empty($ClientInfo)){
                        $ClientName = $ClientInfo->firstname.' '.$ClientInfo->lastname;	
                    } else {
                        $ClientName = 'Walk-In';
                    }
                }
                
                // get service name
                $servicePrices = ServicesPrice::select('services_price.pricing_name','services.service_name')->leftJoin('services','services.id','=','services_price.service_id')->where('services_price.id',$AppointmentServicesData['service_price_id'])->orderBy('services_price.id', 'asc')->get()->first();
                
                $serviceName = '';
                if(!empty($servicePrices)){
                    $serviceName = $servicePrices->service_name.' - '.$servicePrices->pricing_name;
                } else {
                    $serviceName = 'N/A';
                }
                
                // get location name
                $getLocationName = Location::select('location_name')->where('id',$AppointmentServicesData['location_id'])->get()->first();
                
                $locationName = '';
                if(!empty($getLocationName)){
                    $locationName = $getLocationName->location_name;
                } else {
                    $locationName = 'N/A';
                }
                
                // get staff details
                $staffName = '';
                $getUser = User::getUserbyID($AppointmentServicesData['staff_user_id']);
                if(!empty($getUser)){
                    $staffName = $getUser->first_name.' '.$getUser->last_name;
                } else {
                    $staffName = '';
                }
                
                $Status = 0;
                $StatusName = '';
                if($AppointmentServicesData['appointment_status'] == 0){
                    $Status = 0;
                    $StatusName = 'New Appointment';
                } else if($AppointmentServicesData['appointment_status'] == 1){
                    $Status = 1;
                    $StatusName = 'Confirmed';
                } else if($AppointmentServicesData['appointment_status'] == 2){
                    $Status = 2;
                    $StatusName = 'Arrived';
                } else if($AppointmentServicesData['appointment_status'] == 3){
                    $Status = 3;
                    $StatusName = 'Started';
                } else if($AppointmentServicesData['appointment_status'] == 4){
                    $Status = 4;
                    $StatusName = 'Completed';
                }
                
                if($AppointmentServicesData['is_cancelled'] == 1){
                    $Status = 7;
                }
                
                if($AppointmentServicesData['is_reschedule'] == 1){
                    $Status = 5;
                }
                
                if($AppointmentServicesData['is_noshow'] == 1){
                    $Status = 6;
                }

                $created_by = ($AppointmentServicesData['u2_first_name']) ? $AppointmentServicesData['u2_first_name'] : '';;
                $created_by .= ' '.($AppointmentServicesData['u2_last_name']) ? $AppointmentServicesData['u2_last_name'] : '';;
                
                $tempdata['ref_no']           = ($AppointmentServicesData['appointment_id']) ? $AppointmentServicesData['appointment_id'] : 'N/A';
                $tempdata['is_online_appointment'] = ($AppointmentServicesData['is_online_appointment'] == 1) ? 'Book now link' : 'Offline';
                // $tempdata['client_id']        = $AppointmentServicesData['client_id'];
                $tempdata['created_at']           = ($AppointmentServicesData['created_at']) ? date('Y-m-d H:i:s', strtotime($AppointmentServicesData['created_at'])) : '';
                $tempdata['created_by']           = $created_by;
                $tempdata['client_name']      = $ClientName;
                $tempdata['service_name']     = $serviceName;
                $tempdata['appointment_date'] = ($AppointmentServicesData['appointment_date']) ? date("d M Y",strtotime($AppointmentServicesData['appointment_date'])) : 'N/A';
                $tempdata['appointment_time'] = ($AppointmentServicesData['start_time']) ? date("h:i A",strtotime($AppointmentServicesData['start_time'])) : 'N/A';
                $tempdata['location_name']    = $locationName;
                $tempdata['duration']         = $this->hoursandmins($AppointmentServicesData['duration']);
                $tempdata['staff_name']       = $staffName;
                $tempdata['price']            = ($AppointmentServicesData['special_price']) ? $AppointmentServicesData['special_price'] : '0';
                $tempdata['status']           = $StatusName;
                array_push($appointmentEvents,$tempdata);
            }
            
        }
     
		if($start_date != '' && $end_date != ''){
			$FileName = 'appointment_'.$start_date.'_'.$end_date.'.pdf';
		} else {
			$FileName = 'appointment.pdf';
		}

		return PDF::loadView('pdfTemplates.appointmentSalespdfReport',['appointmentEvents' => $appointmentEvents])->setPaper('a3')->download($FileName);
    }

	public function invoicedownloadpdf(Request $request){
		
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
        
        $data_arr = array();

        $datefilter  = ($request->datefilter) ? $request->datefilter : '';

		$start_date = '';
		$end_date = '';
		if(!empty($request->datefilter)) {
			$dateExplode = explode('-', $request->datefilter);

			if(!empty($dateExplode[0])) {
				$start_date = date('Y-m-d', strtotime($dateExplode[0]));
			}
			if(!empty($dateExplode[1])) {
				$end_date = date('Y-m-d', strtotime($dateExplode[1]));
			}
		}
		$location_id = ($request->location_id) ? $request->location_id : '';
		$staff_id    = ($request->staff_id) ? $request->staff_id : '';

        if($start_date != '' && $end_date != ''){
            if(!empty($location_id)){
                $locationWhere = array('invoice.location_id' => $location_id);
            } else {
                $locationWhere = array();
            }
            
            $Invoice = Invoice::select('invoice.*','locations.location_name')->join('locations','locations.id','=','invoice.location_id')->where('invoice.user_id', $AdminId)->where($locationWhere)->whereDate('invoice.created_at','>=',$start_date)->whereDate('invoice.created_at','<=',$end_date)->orderBy('invoice.id', 'desc')->get()->toArray();
        } else {
            if(!empty($location_id)){
                $locationWhere = array('invoice.location_id' => $location_id);
            } else {
                $locationWhere = array();
            }
            
            $Invoice = Invoice::select('invoice.*','locations.location_name')->join('locations','locations.id','=','invoice.location_id')->where('invoice.user_id', $AdminId)->where($locationWhere)->orderBy('invoice.id', 'desc')->get()->toArray();
        }
        
        $invoices = array();	
        if(!empty($Invoice)){
            foreach($Invoice as $InvoiceData){
                
                // get client name
                if($InvoiceData['client_id'] == 0){
                    $ClientName = 'Walk-In';
                } else {
                    $ClientInfo = Clients::getClientbyID($InvoiceData['client_id']);	
                    if(!empty($ClientInfo)){
                        $ClientName = $ClientInfo->firstname.' '.$ClientInfo->lastname;	
                    } else {
                        $ClientName = 'Walk-In';
                    }
                }
                
                $TotalStaffTip = StaffTip::select(DB::raw('SUM(tip_amount) as total_tip'))->where('staff_tip.inovice_id', $InvoiceData['id'])->get()->toArray();
                
                // $tempInvoice['id']    		   = $InvoiceData['id'];
                $tempInvoice['invoice_id']     = $InvoiceData['invoice_prefix']." ".$InvoiceData['invoice_id'];
                // $tempInvoice['client_id']      = $InvoiceData['client_id'];
                $tempInvoice['client_name']    = $ClientName;
                $tempInvoice['invoice_status'] = $InvoiceData['invoice_status'];
                $tempInvoice['invoice_date']   = ($InvoiceData['created_at']) ? date("d M Y, h:ia",strtotime($InvoiceData['created_at'])) : 'N/A';
                $tempInvoice['due_date']   = '';
                $tempInvoice['billing_name']   = ($InvoiceData['location_name']) ? $InvoiceData['location_name'] : 'N/A';
                $tempInvoice['location_name']  = ($InvoiceData['location_name']) ? $InvoiceData['location_name'] : 'N/A';
                $tempInvoice['tips']           = (!empty($TotalStaffTip) && $TotalStaffTip[0]['total_tip'] > 0) ? $TotalStaffTip[0]['total_tip'] : 0;
                $tempInvoice['gross_total']    = ($InvoiceData['inovice_final_total']) ? $InvoiceData['inovice_final_total'] : '0';
                array_push($invoices,$tempInvoice);
            }
        }
     
		if($start_date != '' && $end_date != ''){
			$FileName = 'invoice_'.$start_date.'_'.$end_date.'.pdf';
		} else {
			$FileName = 'invoice.pdf';
		}

		return PDF::loadView('pdfTemplates.invoicePdfReport',['invoices' => $invoices])->setPaper('a3')->download($FileName);
    }
	
	public function salesList()
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
			
		// get all locations		
		$Locations = Location::select('id','location_name','location_address','country_code','location_phone')->where(['user_id'=>$AdminId])->orderBy('id', 'ASC')->get()->toArray();		
			
		return view('sales.sales-list',compact('Locations'));	
    }

	public function invoicesdownloadExcel(Request $request){
		$location = $request->location_id;
		
		$datefilter  = ($request->datefilter) ? $request->datefilter : '';

		$start_date = '';
		$end_date = '';
		if(!empty($request->datefilter)) {
			$dateExplode = explode('-', $request->datefilter);

			if(!empty($dateExplode[0])) {
				$start_date = date('Y-m-d', strtotime($dateExplode[0]));
			}
			if(!empty($dateExplode[1])) {
				$end_date = date('Y-m-d', strtotime($dateExplode[1]));
			}
		}

        return Excel::download(new invoicesexport($location, $start_date, $end_date), 'Invoices.xls');
    }

	public function invoicesdownloadcsv(Request $request)
	{
		$location = $request->location_id;
		$datefilter  = ($request->datefilter) ? $request->datefilter : '';

		$start_date = '';
		$end_date = '';
		if(!empty($request->datefilter)) {
			$dateExplode = explode('-', $request->datefilter);

			if(!empty($dateExplode[0])) {
				$start_date = date('Y-m-d', strtotime($dateExplode[0]));
			}
			if(!empty($dateExplode[1])) {
				$end_date = date('Y-m-d', strtotime($dateExplode[1]));
			}
		}

        return Excel::download(new invoicesexport($location, $start_date, $end_date), 'Invoices.csv');
    }
	
	public function getSalesInvoiceList(Request $request)
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
			
			$data_arr = array();
			
			$start_date  = ($request->start_date) ? date("Y-m-d",strtotime($request->start_date)) : '';
			$end_date    = ($request->end_date) ? date("Y-m-d",strtotime($request->end_date)) : '';
			$location_id = ($request->location_id) ? $request->location_id : '';
			
			if($start_date != '' && $end_date != ''){
				if($location_id != ''){
					$locationWhere = array('invoice.location_id' => $location_id);
				} else {
					$locationWhere = array();
				}
				
				$Invoice = Invoice::select('invoice.*','locations.location_name')->join('locations','locations.id','=','invoice.location_id')->where('invoice.user_id', $AdminId)->where($locationWhere)->whereDate('invoice.created_at','>=',$start_date)->whereDate('invoice.created_at','<=',$end_date)->orderBy('invoice.id', 'desc')->get()->toArray();
			} else {
				if($location_id != ''){
					$locationWhere = array('invoice.location_id' => $location_id);
				} else {
					$locationWhere = array();
				}
				
				$Invoice = Invoice::select('invoice.*','locations.location_name')->join('locations','locations.id','=','invoice.location_id')->where('invoice.user_id', $AdminId)->where($locationWhere)->orderBy('invoice.id', 'desc')->get()->toArray();
			}
			
			$invoices = array();	
			if(!empty($Invoice)){
				foreach($Invoice as $InvoiceData){
					
					// get client name
					if($InvoiceData['client_id'] == 0){
						$ClientName = 'Walk-In';
					} else {
						$ClientInfo = Clients::getClientbyID($InvoiceData['client_id']);	
						if(!empty($ClientInfo)){
							$ClientName = $ClientInfo->firstname.' '.$ClientInfo->lastname;	
						} else {
							$ClientName = 'Walk-In';
						}
					}
					
					$TotalStaffTip = StaffTip::select(DB::raw('SUM(tip_amount) as total_tip'))->where('staff_tip.inovice_id', $InvoiceData['id'])->get()->toArray();
					
					$tempInvoice['id']    		   = $InvoiceData['id'];
					$tempInvoice['invoice_id']     = $InvoiceData['invoice_prefix']." ".$InvoiceData['invoice_id'];
					$tempInvoice['client_id']      = $InvoiceData['client_id'];
					$tempInvoice['client_name']    = $ClientName;
					$tempInvoice['invoice_status'] = $InvoiceData['invoice_status'];
					$tempInvoice['invoice_date']   = ($InvoiceData['created_at']) ? date("d M Y, h:ia",strtotime($InvoiceData['created_at'])) : 'N/A';
					$tempInvoice['billing_name']   = ($InvoiceData['location_name']) ? $InvoiceData['location_name'] : 'N/A';
					$tempInvoice['location_name']  = ($InvoiceData['location_name']) ? $InvoiceData['location_name'] : 'N/A';
					$tempInvoice['tips']           = (!empty($TotalStaffTip) && $TotalStaffTip[0]['total_tip'] > 0) ? $TotalStaffTip[0]['total_tip'] : 0;
					$tempInvoice['gross_total']    = ($InvoiceData['inovice_final_total']) ? $InvoiceData['inovice_final_total'] : '0';
					array_push($invoices,$tempInvoice);
				}
			}
			
            return Datatables::of($invoices)
				->editColumn('invoice_id', function ($row) {
					$invoice_id = '<td><a href="'.route('viewInvoice',$row['id']).'" class="text-blue cursor-pointer">'.$row['invoice_id'].'</a></td>';
					return $invoice_id;
				})
				->editColumn('client_name', function ($row) {
					if($row['client_name'] == 'Walk-In'){
						$client_html = '<td>Walk-In</td>';
					} else {
						$client_html = '<td><a href="'.route('viewClient',$row['client_id'],'client').'" class="text-blue cursor-pointer" target="_blank">'.$row['client_name'].'</a></td>';
					}
					return $client_html;
				})
				->editColumn('invoice_status', function ($row) {
					
					if($row['invoice_status'] == 0) {
						$invoice_status = '<td><span class="status unpaid">Unpaid</span></td>';
					} else if($row['invoice_status'] == 1) {
						$invoice_status = '<td><span class="status completed">Completed</span></td>';
					} else if($row['invoice_status'] == 2) {
						$invoice_status = '<td><span class="status refund">Refund</span></td>';
					} else if($row['invoice_status'] == 3) {
						$invoice_status = '<td><span class="status void">Void</span></td>';
					} 
					
					return $invoice_status;
				})
                ->rawColumns(['invoice_id','client_name','invoice_status'])
				->make(true);
        } 
    }
	
	public function vouchers()
    {
		$CurrentUser = auth::user();
		$is_admin = $CurrentUser->is_admin;
		
		if($is_admin == 1){
			$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
			$UserId = $CurrentStaff->user_id;
		} else {
			$UserId = Auth::id();
		}
			
		return view('sales.vouchers');	
    }

	public function vouchersdownloadExcel(Request $request){
		$voucher = $request->vouchers;

        return Excel::download(new vouchersexport($voucher), 'Vouchers.xls');
    }

	public function vouchersdownloadcsv(Request $request){
		$voucher = $request->vouchers;

        return Excel::download(new vouchersexport($voucher), 'Vouchers.csv');
    }

	public function vouchersdownloadpdf(Request $request)
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
        
        $data_arr = array();
        
        $voucher_status = $request->vouchers ? $request->vouchers : '';
        
        $VoucherSold = SoldVoucher::select('sold_voucher.*','locations.location_name','locations.location_address','locations.location_image','vouchers.title','vouchers.name','invoice.invoice_status','invoice.invoice_prefix','invoice.invoice_id as invid')->leftJoin('vouchers', 'vouchers.id', '=', 'sold_voucher.voucher_id')->leftJoin('locations', 'locations.id', '=', 'sold_voucher.location_id')->leftJoin('invoice', 'invoice.id', '=', 'sold_voucher.invoice_id')->where('sold_voucher.user_id', $AdminId)->orderBy('sold_voucher.id', 'desc')->get()->toArray();
        
        $soldVoucher = array();	
            
        if(!empty($VoucherSold))
		{
            foreach($VoucherSold as $VoucherSoldData)
			{   
                // get client name
                if($VoucherSoldData['client_id'] == 0){
                    $ClientName = 'Walk-In';
                } else {
                    $ClientInfo = Clients::getClientbyID($VoucherSoldData['client_id']);	
                    if(!empty($ClientInfo)){
                        $ClientName = $ClientInfo->firstname.' '.$ClientInfo->lastname;	
                    } else {
                        $ClientName = 'Walk-In';
                    }
                }
                
                $RemainingAmount = ($VoucherSoldData['total_value'] - $VoucherSoldData['redeemed']);
                
                $CurrentDate = date("Y-m-d H:i:s");
                $StartDate   = date("Y-m-d H:i:s",strtotime($VoucherSoldData['start_date']));
                $EndDate     = date("Y-m-d H:i:s",strtotime($VoucherSoldData['end_date']));
                
                $IsExpired = 0;
                if($CurrentDate > $EndDate){
                    $IsExpired = 1;
                }
                
                // $tempVoucher['id']               = ($VoucherSoldData['id']) ? $VoucherSoldData['id'] : 0;
                $tempVoucher['issue_date']       = ($VoucherSoldData['start_date']) ? date("d M Y, h:ia",strtotime($VoucherSoldData['start_date'])) : 'N/A';
                $tempVoucher['expiry_date']      = ($VoucherSoldData['end_date']) ? date("d M Y",strtotime($VoucherSoldData['end_date'])) : 'N/A';
                // $tempVoucher['invoice_id']       = ($VoucherSoldData['invoice_id']) ? $VoucherSoldData['invoice_id'] : 0;
                $tempVoucher['invoice_no']       = $VoucherSoldData['invoice_prefix']." ".$VoucherSoldData['invid'];
                $tempVoucher['client_id']        = ($VoucherSoldData['client_id']) ? $VoucherSoldData['client_id'] : 0;
                $tempVoucher['client_name']      = $ClientName;

                $VoucherType = 'N/A';
                if($VoucherSoldData['voucher_type'] == 0) {
                    $VoucherType = 'GIFT VOUCHER';
                } elseif($VoucherSoldData['voucher_type'] == 1) {
                    $VoucherType = 'SERVICE VOUCHER';
                }
                $tempVoucher['voucher_type']     = $VoucherType;

                $invoice_status = 'N/A';
                if($VoucherSoldData['invoice_status'] == 0) {
                    $invoice_status = 'UNPAID';
                } else if($VoucherSoldData['invoice_status'] == 1) {
                    $invoice_status = 'VALID';
                } else if($VoucherSoldData['invoice_status'] == 2) {
                    $invoice_status = 'REFUND';
                } else if($VoucherSoldData['invoice_status'] == 3) {
                    $invoice_status = 'VOID';
                }
                
                if($IsExpired == 1){
                    $invoice_status = 'EXPIRED';
                }
                
                if($RemainingAmount == 0){
                    $invoice_status = 'REDEEMED';
                }
                
                $tempVoucher['invoice_status'] = $invoice_status;
                $tempVoucher['voucher_code']     = ($VoucherSoldData['voucher_code']) ? $VoucherSoldData['voucher_code'] : 'N/A';

                $tempVoucher['voucher_total']    = ($VoucherSoldData['total_value']) ? $VoucherSoldData['total_value'] : '0';
                $tempVoucher['redeemed_amount']  = ($VoucherSoldData['redeemed']) ? $VoucherSoldData['redeemed'] : '0';
                $tempVoucher['remaining_amount'] = $RemainingAmount;
                
                
                if($voucher_status != ''){
                    if($voucher_status == 'unpaid' && $invoice_status == 'UNPAID'){
                        array_push($soldVoucher,$tempVoucher);
                    } else if($voucher_status == 'valid' && $invoice_status == 'VALID'){
                        array_push($soldVoucher,$tempVoucher);
                    } else if($voucher_status == 'expired' && $invoice_status == 'EXPIRED'){
                        array_push($soldVoucher,$tempVoucher);
                    } else if($voucher_status == 'redeemed' && $invoice_status == 'REDEEMED'){
                        array_push($soldVoucher,$tempVoucher);
                    } else if($voucher_status == 'refundedinvoice' && $invoice_status == 'REFUND'){
                        array_push($soldVoucher,$tempVoucher);
                    } else if($voucher_status == 'voidedinvoice' && $invoice_status == 'VOID'){
                        array_push($soldVoucher,$tempVoucher);
                    } else if($voucher_status == 'all') {
                        array_push($soldVoucher,$tempVoucher);
                    }
                } else if($voucher_status == '') {
                    array_push($soldVoucher,$tempVoucher);	
                }
            }
        }
		
		$FileName = 'voucher.pdf';

		$status = $invoice_status;
		if($voucher_status  == 'all') {
			$status = 'All';
		}

		return PDF::loadView('pdfTemplates.voucherPdfReport',['soldVoucher' => $soldVoucher, 'status' => $status])->setPaper('a3')->download($FileName);
    }
	
	public function getSalesVoucherList(Request $request)
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
			
			$data_arr = array();
			
			$voucher_status = ($request->voucher_status) ? $request->voucher_status : '';
			
			$VoucherSold = SoldVoucher::select('sold_voucher.*','locations.location_name','locations.location_address','locations.location_image','vouchers.title','vouchers.name','invoice.invoice_status','invoice.invoice_prefix','invoice.invoice_id as invid')->leftJoin('vouchers', 'vouchers.id', '=', 'sold_voucher.voucher_id')->leftJoin('locations', 'locations.id', '=', 'sold_voucher.location_id')->leftJoin('invoice', 'invoice.id', '=', 'sold_voucher.invoice_id')->where('sold_voucher.user_id', $AdminId)->orderBy('sold_voucher.id', 'desc')->get()->toArray();
			
			$soldVoucher = array();	
				
			if(!empty($VoucherSold)){
				foreach($VoucherSold as $VoucherSoldData){
					
					// get client name
					if($VoucherSoldData['client_id'] == 0){
						$ClientName = 'Walk-In';
					} else {
						$ClientInfo = Clients::getClientbyID($VoucherSoldData['client_id']);	
						if(!empty($ClientInfo)){
							$ClientName = $ClientInfo->firstname.' '.$ClientInfo->lastname;	
						} else {
							$ClientName = 'Walk-In';
						}
					}
					
					$RemainingAmount = ($VoucherSoldData['total_value'] - $VoucherSoldData['redeemed']);
					
					$CurrentDate = date("Y-m-d H:i:s");
					$StartDate   = date("Y-m-d H:i:s",strtotime($VoucherSoldData['start_date']));
					$EndDate     = date("Y-m-d H:i:s",strtotime($VoucherSoldData['end_date']));
					
					$IsExpired = 0;
					if($CurrentDate > $EndDate){
						$IsExpired = 1;
					}
					
					$tempVoucher['id']               = ($VoucherSoldData['id']) ? $VoucherSoldData['id'] : 0;
					$tempVoucher['issue_date']       = ($VoucherSoldData['start_date']) ? date("d M Y, h:ia",strtotime($VoucherSoldData['start_date'])) : 'N/A';
					$tempVoucher['expiry_date']      = ($VoucherSoldData['end_date']) ? date("d M Y",strtotime($VoucherSoldData['end_date'])) : 'N/A';
					$tempVoucher['invoice_id']       = ($VoucherSoldData['invoice_id']) ? $VoucherSoldData['invoice_id'] : 0;
					$tempVoucher['invoice_no']       = $VoucherSoldData['invoice_prefix']." ".$VoucherSoldData['invid'];
					$tempVoucher['client_id']        = ($VoucherSoldData['client_id']) ? $VoucherSoldData['client_id'] : 0;
					$tempVoucher['client_name']      = $ClientName;
					$tempVoucher['voucher_type']     = ($VoucherSoldData['voucher_type']) ? $VoucherSoldData['voucher_type'] : '';
					$tempVoucher['voucher_code']     = ($VoucherSoldData['voucher_code']) ? $VoucherSoldData['voucher_code'] : 'N/A';
					$tempVoucher['voucher_total']    = ($VoucherSoldData['total_value']) ? $VoucherSoldData['total_value'] : '0';
					$tempVoucher['redeemed_amount']  = ($VoucherSoldData['redeemed']) ? $VoucherSoldData['redeemed'] : '0';
					$tempVoucher['remaining_amount'] = $RemainingAmount;
					
					$invoice_status = 'N/A';
					if($VoucherSoldData['invoice_status'] == 0) {
						$invoice_status = 'UNPAID';
					} else if($VoucherSoldData['invoice_status'] == 1) {
						$invoice_status = 'VALID';
					} else if($VoucherSoldData['invoice_status'] == 2) {
						$invoice_status = 'REFUND';
					} else if($VoucherSoldData['invoice_status'] == 3) {
						$invoice_status = 'VOID';
					}
					
					if($IsExpired == 1){
						$invoice_status = 'EXPIRED';
					}
					
					if($RemainingAmount == 0){
						$invoice_status = 'REDEEMED';
					}
					
					$tempVoucher['invoice_status'] = $invoice_status;
					
					if($voucher_status != ''){
						if($voucher_status == 'unpaid' && $invoice_status == 'UNPAID'){
							array_push($soldVoucher,$tempVoucher);
						} else if($voucher_status == 'valid' && $invoice_status == 'VALID'){
							array_push($soldVoucher,$tempVoucher);
						} else if($voucher_status == 'expired' && $invoice_status == 'EXPIRED'){
							array_push($soldVoucher,$tempVoucher);
						} else if($voucher_status == 'redeemed' && $invoice_status == 'REDEEMED'){
							array_push($soldVoucher,$tempVoucher);
						} else if($voucher_status == 'refundedinvoice' && $invoice_status == 'REFUND'){
							array_push($soldVoucher,$tempVoucher);
						} else if($voucher_status == 'voidedinvoice' && $invoice_status == 'VOID'){
							array_push($soldVoucher,$tempVoucher);
						} else if($voucher_status == 'all') {
							array_push($soldVoucher,$tempVoucher);
						}
					} else if($voucher_status == '') {
						array_push($soldVoucher,$tempVoucher);	
					}
				}
			}	
				
            return Datatables::of($soldVoucher)
				->editColumn('invoice_no', function ($row) {
					$invoice_no = '<td><a href="'.route('viewInvoice',['id' => $row['invoice_id']]).'" class="text-blue cursor-pointer" target="_blank">#'.$row['invoice_no'].'</a></td>';
					return $invoice_no;
				})
				->editColumn('client_name', function ($row) {
					if($row['client_name'] == 'Walk-In'){
						$client_html = '<td>Walk-In</td>';
					} else {
						$client_html = '<td><a href="'.route('viewClient',$row['client_id'],'client').'" class="text-blue cursor-pointer" target="_blank">'.$row['client_name'].'</a></td>';
					}
					return $client_html;
				})
				->editColumn('voucher_type', function ($row) {
					if($row['voucher_type'] == 0){
						$voucher_type = '<td>Gift Voucher</td>';
					} else {
						$voucher_type = '<td><a href="javascript:;" class="getVoucherService" data-voucherID="'.$row['id'].'">Service Voucher</a></td>';
					}
					return $voucher_type;
				})
				->editColumn('voucher_status', function ($row) {
					
					$invoice_status = 'N/A';
					if($row['invoice_status'] == 'UNPAID') {
						$invoice_status = '<td><span class="status unpaid">UNPAID</span></td>';
					} else if($row['invoice_status'] == 'VALID') {
						$invoice_status = '<td><span class="status valid">VALID</span></td>';
					} else if($row['invoice_status'] == 'REFUND') {
						$invoice_status = '<td><span class="status refund">REFUND</span></td>';
					} else if($row['invoice_status'] == 'VOID') {
						$invoice_status = '<td><span class="status void">VOID</span></td>';
					} else if($row['invoice_status'] == 'EXPIRED') {
						$invoice_status = '<td><span class="status expired">EXPIRED</span></td>';
					} else if($row['invoice_status'] == 'REDEEMED') {
						$invoice_status = '<td><span class="status redeemed">REDEEMED</span></td>';
					}
				
					return $invoice_status;
				})
                ->rawColumns(['invoice_no','client_name','voucher_type','voucher_status'])
				->make(true);
        } 
    }
	
	public function getVoucherServices(Request $request)
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
			
			$soldVoucherId = ($request->voucherId) ? $request->voucherId : '';
			
			$soldVoucherRequest = SoldVoucher::select('service_id')->where('user_id', $AdminId)->where('id', $soldVoucherId)->orderBy('id', 'desc')->get()->first()->toArray();
			
			if(!empty($soldVoucherRequest)){
				$servicesId = explode(",",$soldVoucherRequest['service_id']);
				
				if(!empty($servicesId)){
					$getServiceCategory = Services::select('service_category')->whereIn('id', $servicesId)->groupBy('service_category')->get()->toArray();
					
					$categoryArray = array();
					if(!empty($getServiceCategory)){
						foreach($getServiceCategory as $getServiceCategoryData){
							
							$category_id = $getServiceCategoryData['service_category'];
							
							$getCategoryData = ServiceCategory::select('service_category.category_title')->where('service_category.id',$category_id)->get()->first()->toArray();
							
							$tempdata['category_title'] = $getCategoryData['category_title'];
							
							$getServiceData = Services::select('id','service_name')->where('service_category',$category_id)->whereIn('id',$servicesId)->orderBy('id', 'desc')->get()->toArray();
							
							$services = array();
							if(!empty($getServiceData)){
								foreach($getServiceData as $getServices){
									$ServiceId     = $getServices['id'];
									$getFirstPrice = ServicesPrice::select('price','special_price','duration')->where('service_id',$ServiceId)->orderBy('id', 'desc')->get()->first()->toArray();
									
									$tempService['title']         = $getServices['service_name'];
									$tempService['price']         = $getFirstPrice['price'];
									$tempService['special_price'] = $getFirstPrice['special_price'];
									$tempService['duration']      = $this->hoursandmins($getFirstPrice['duration']);
									array_push($services,$tempService);
								}
							}
							
							$tempdata['services'] = $services;
							array_push($categoryArray,$tempdata);
						}
					}
				}
			}
			
			$htmldata = '';
			if(!empty($categoryArray)) {
				$htmldata .= '<div class="m-auto">
								<div class="card-body">';	
								
				foreach($categoryArray as $categoryArrayData) {
					$htmldata .= '<h3 class="font-weight-bolder">'.$categoryArrayData['category_title'].'</h3>';
					if(!empty($categoryArrayData['services'])) {
						foreach($categoryArrayData['services'] as $services) {
							$Price = $services['price'];
							if($services['special_price'] > 0){
								$Price = $services['special_price'];
							}
							
							$htmldata .= '
							<div class="mt-4 border-bottom py-3 order-total d-flex align-items-center justify-content-between">
								<span>
									<p class="mb-0 text-dark font-size-lg font-weight-bolder">'.$services['title'].'</p>
									<p class="mb-0 text-muted font-size-lg">'.$services['duration'].'</p>
								</span>
								<h4>Ca $'.$Price.'</h4>
							</div>';
						}
					}
				}
				
				$htmldata .= '
						</div>
					</div>';
			}
			
			$data["htmldata"] = $htmldata;	
            return JsonReturn::success($data);
        }
	}
	
	public function paidPlans()
    {
		$CurrentUser = auth::user();
		$is_admin = $CurrentUser->is_admin;
		
		if($is_admin == 1){
			$CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
			$UserId = $CurrentStaff->user_id;
		} else {
			$UserId = Auth::id();
		}
			
		return view('sales.paid-plans');	
    }
	
	public function getPaidplanList(Request $request){
		if ($request->ajax()) 
        {
			// after discusson with sir left it as it is this is not completed functionality to view paid plan
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
			
			$data_arr = array();
			
			//$voucher_status = ($request->voucher_status) ? $request->voucher_status : '';
			
			$PaidPlan = SoldPaidPlan::select('sold_paidplan.*','paid_plan.name','clients.firstname','clients.lastname')->leftJoin('paid_plan', 'paid_plan.id', '=', 'sold_paidplan.paidplan_id')->leftJoin('clients', 'clients.id', '=', 'sold_paidplan.client_id')->where('sold_paidplan.user_id', $AdminId)->get()->toArray();
				
			$paidPlanArray = array();	
			if(!empty($PaidPlan)){
				foreach($PaidPlan as $PaidPlanData){
					$temp['plan_name']     = $PaidPlanData['name'];
					$temp['client_id']     = $PaidPlanData['client_id'];
					$temp['client_name']   = $PaidPlanData['firstname'].' '.$PaidPlanData['lastname'];
					$temp['plan_type']     = 'One Time';
					$temp['start_date']    = ($PaidPlanData['start_date']) ? date("d M Y",strtotime($PaidPlanData['start_date'])) : 'N/A';
					$temp['end_date']      = ($PaidPlanData['end_date']) ? date("d M Y",strtotime($PaidPlanData['end_date'])) : 'N/A';
					$temp['plan_status']   = 'N/A';
					$temp['total_charged'] = ($PaidPlanData['price']) ? $PaidPlanData['price'] : '0';
					array_push($paidPlanArray,$temp);
				}		
			}
				
            return Datatables::of($paidPlanArray)
				->make(true);
        } 
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
}
