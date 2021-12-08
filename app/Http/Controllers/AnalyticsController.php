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
use App\Models\StaffWorkingHours;
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
use App\Models\InventoryProducts;
use App\Models\InventoryOrderLogs;
use App\Models\Inventory_supplier;
use App\Models\Inventory_brand;
use App\Models\Inventory_category;
use App\Models\PaidPlan;
use App\Models\SoldPaidPlan;
use App\Models\Voucher;
use App\Models\InvoiceVoucher;
use App\Models\SoldVoucher;
use App\Models\Taxes;
use App\Models\InvoiceTaxes;
use App\Models\StaffTip;
use App\Models\StaffBlockedTime;
use App\Mail\InvoiceMail;
use App\Mail\VoucherEmail;
use App\Models\taxFormula;
use App\Models\paymentType;
use App\Exports\paymentSummaryCSVReport;
use App\Exports\discountSummaryCSVReport;
use App\Exports\outstandingInvoiceCSVReport;
use App\Exports\outstandingInvoiceExcelReport;
use App\Exports\appointmentCancellationsCSVReport;
use App\Exports\appointmentCancellationsExcelReport;
use App\Exports\paymentLogCSVReport;
use App\Exports\discountSummaryExcelReport;
use App\Exports\paymentSummaryExcelReport;
use App\Exports\salesByItemCSVReport;
use App\Exports\salesByItemExcelReport;
use App\Exports\salesByServiceCSVReport;
use App\Exports\salesByServiceExcelReport;
use App\Exports\salesByProductCSVReport;
use App\Exports\salesByProductExcelReport;
use App\Exports\salesByLocationCSVReport;
use App\Exports\salesByLocationExcelReport;
use App\Exports\salesByClientCSVReport;
use App\Exports\salesByClientExcelReport;
use App\Exports\salesByStaffCSVReport;
use App\Exports\salesByStaffExcelReport;
use App\Exports\salesByDayCSVReport;
use App\Exports\salesByDayExcelReport;
use App\Exports\salesByMonthCSVReport;
use App\Exports\salesByMonthExcelReport;
use App\Exports\salesByYearCSVReport;
use App\Exports\salesByYearExcelReport;
use App\Exports\clientListCSVReport;
use App\Exports\clientListExcelReport;
use App\Exports\clientRetentionCSVReport;
use App\Exports\clientRetentionExcelReport;
use App\Exports\staffWorkingHoursCSVReport;
use App\Exports\staffWorkingHoursExcelReport;
use App\Exports\tipsByStaffCSVReport;
use App\Exports\tipsByStaffExcelReport;
use App\Exports\staffCommissionCSVReport;
use App\Exports\staffCommissionExcelReport;
use App\Exports\staffCommissionDetailedCSVReport;
use App\Exports\staffCommissionDetailedExcelReport;
use App\Exports\stockOnHandCSVReport;
use App\Exports\stockOnHandExcelReport;
use App\Exports\productSalesPerformanceCSVReport;
use App\Exports\productSalesPerformanceExcelReport;
use App\Exports\stockMovementLogCSVReport;
use App\Exports\stockMovementLogExcelReport;
use App\Exports\productConsumptionCSVReport;
use App\Exports\productConsumptionExcelReport;
use App\Exports\tipsCollectedCSVReport;
use App\Exports\tipsCollectedExcelReport;
use App\Exports\financesSummaryCSVReport;
use App\Exports\financesSummaryExcelReport;
use App\Exports\taxesSummaryCSVReport;
use App\Exports\taxesSummaryExcelReport;
use DataTables;
use Session;
use PDF;
use Mail;
use Crypt;
use DB;
use DateTime;
use Excel;
use stdClass;
  
class AnalyticsController extends Controller
{
    public function __construct()
    {
		$currentRoute = Route::currentRouteName();
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
		
		// get all locations		
		$Locations = Location::select('id','location_name','location_address','country_code','location_phone')
		->where(['user_id'=>$AdminId])
		->where('is_deleted',0)
		->orderBy('id', 'ASC')->get()->toArray();	
		
		// Get all staff
		$Staff = Staff::select('staff.staff_user_id','users.first_name','users.last_name')->where(['user_id'=>$AdminId])->join('users','users.id','=','staff.staff_user_id')->orderBy('staff.id', 'ASC')->get()->toArray();	
		
		// get total appointments
		$TotalAppointments = AppointmentServices::join('appointments', 'appointments.id', 'appointment_services.appointment_id')->select('appointments.id')->where('appointments.user_id', $AdminId)->orderBy('appointments.id', 'desc')->get()->toArray();
		$TotalAppoCounter = count($TotalAppointments);
		
		// get total completed appointments
		$TotalCompletedAppointments = AppointmentServices::join('appointments', 'appointments.id', 'appointment_services.appointment_id')->select('appointments.id')->where('appointments.user_id', $AdminId)->where('appointments.appointment_status',4)->orderBy('appointments.id', 'desc')->get()->toArray();
		
		$TotalCompletedPer = 0;
		$CompletedAppoCounter = count($TotalCompletedAppointments);
		
		if($TotalAppoCounter != 0){
			$TotalCompletedPer = round((($CompletedAppoCounter * 100) / $TotalAppoCounter));
		}
		 
		// get total not completed appointments
		$TotalNotCompletedAppointments = AppointmentServices::join('appointments', 'appointments.id', 'appointment_services.appointment_id')->select('appointments.id')->where('appointments.user_id', $AdminId)->where('appointments.appointment_status','!=',4)->where('appointments.is_cancelled','!=',1)->where('appointments.is_noshow','!=',1)->orderBy('appointments.id', 'desc')->get()->toArray();
		
		$TotalNotCompletedPer = 0;
		$NotCompletedAppoCounter = count($TotalNotCompletedAppointments);
		
		if($TotalAppoCounter != 0){
			$TotalNotCompletedPer = round((($NotCompletedAppoCounter * 100) / $TotalAppoCounter));
		}
		
		// get total cancelled appointments
		$TotalCancelledAppointments = AppointmentServices::join('appointments', 'appointments.id', 'appointment_services.appointment_id')->select('appointments.id')->where('appointments.user_id', $AdminId)->where('appointments.is_cancelled',1)->orderBy('appointments.id', 'desc')->get()->toArray();
		
		$TotalCancelledPer = 0;
		$CancelledAppoCounter = count($TotalCancelledAppointments);
		
		if($TotalAppoCounter != 0){
			$TotalCancelledPer = round((($CancelledAppoCounter * 100) / $TotalAppoCounter));
		}
		
		// get total cancelled appointments
		$TotalNoshowdAppointments = AppointmentServices::join('appointments', 'appointments.id', 'appointment_services.appointment_id')->select('appointments.id')->where('appointments.user_id', $AdminId)->where('appointments.is_noshow',1)->orderBy('appointments.id', 'desc')->get()->toArray();
		
		$TotalNoshowPer = 0;
		$NoshowAppoCounter = count($TotalNoshowdAppointments);
		
		if($TotalAppoCounter != 0){
			$TotalNoshowPer = round((($NoshowAppoCounter * 100) / $TotalAppoCounter));
		}
		
		// get total sales
		// $getInvoices = Invoice::select(DB::raw('COUNT(id) as total_invoices'),DB::raw('SUM(invoice.inovice_final_total) as total_sale'))->where('invoice.user_id', $AdminId)->where('invoice.invoice_status','!=','2')->where('invoice.invoice_status','!=','3')->orderBy('invoice.id', 'desc')->get()->toArray();
		$getInvoices = InvoiceItems::join('invoice','invoice.id','=','invoice_items.invoice_id')->select(DB::raw('COUNT(DISTINCT(invoice.id)) as total_invoices'),DB::raw('SUM(invoice_items.item_price) as total_sale'))->where('invoice.user_id', $AdminId)->where('invoice.invoice_status','!=','2')->where('invoice.invoice_status','!=','3')->where('invoice_items.item_type', '!=', 'voucher')->orderBy('invoice.id', 'desc')->get()->toArray();

		$getRefundInvoices = InvoiceItems::join('invoice','invoice.id','=','invoice_items.invoice_id')->select(DB::raw('COUNT(DISTINCT(invoice.id)) as total_invoices'),DB::raw('SUM(invoice_items.item_price) as total_sale'))->where('invoice.user_id', $AdminId)->where('invoice.invoice_status','2')->where('invoice_items.item_type', '!=', 'voucher')->orderBy('invoice.id', 'desc')->get()->toArray();
		
		$TotalInvoices = ($getInvoices[0]['total_invoices']) ? $getInvoices[0]['total_invoices'] : 0;
		$TotalSale     = ($getInvoices[0]['total_sale']) ? $getInvoices[0]['total_sale'] : 0;

		$TotalRefundInvoices = ($getRefundInvoices[0]['total_invoices']) ? $getRefundInvoices[0]['total_invoices'] : 0;
		$TotalRefundSale     = ($getRefundInvoices[0]['total_sale']) ? $getRefundInvoices[0]['total_sale'] : 0;

		$TotalInvoices += $TotalRefundInvoices;
		$TotalSale -= $TotalRefundSale;
		
		$AvgTotalSale = 0;
		if($TotalSale != 0 && $TotalInvoices != 0){
			$AvgTotalSale = number_format(($TotalSale / $TotalInvoices),2);
		}
		
		// get total service sale
		$ServiceTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_services'),DB::raw('SUM(invoice_items.item_price) as total_sale'))->join('invoice','invoice.id','=','invoice_items.invoice_id')->where(['invoice.user_id'=>$AdminId])->where('invoice_items.item_type','services')->where('invoice.invoice_status','!=','2')->where('invoice.invoice_status','!=','3')->orderBy('invoice_items.id', 'ASC')->get()->toArray();
		
		$TotalService = ($ServiceTotal[0]['total_services']) ? $ServiceTotal[0]['total_services'] : 0;
		$TotalServiceSale = ($ServiceTotal[0]['total_sale']) ? $ServiceTotal[0]['total_sale'] : 0;
		
		$TotalServiceSalePer = 0;
		if($TotalSale != 0){
			$TotalServiceSalePer = round(($TotalServiceSale * 100) / $TotalSale);
		}
		
		$AvgServiceSale = 0;
		if($TotalService != 0 && $TotalServiceSale != 0){
			$AvgServiceSale = number_format(($TotalServiceSale / $TotalService),2);
		}
		
		// get total product sales
		$PrductTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_products'),DB::raw('SUM(invoice_items.item_price) as total_sale'))->join('invoice','invoice.id','=','invoice_items.invoice_id')->where(['invoice.user_id'=>$AdminId])->where('invoice_items.item_type','product')->where('invoice.invoice_status','!=','2')->where('invoice.invoice_status','!=','3')->orderBy('invoice_items.id', 'ASC')->get()->toArray();
		
		$TotalProducts = ($PrductTotal[0]['total_products']) ? $PrductTotal[0]['total_products'] : 0;
		$TotalProductSale = ($PrductTotal[0]['total_sale']) ? $PrductTotal[0]['total_sale'] : 0;
		
		$TotalProductSalePer = 0;
		if($TotalSale != 0){
			$TotalProductSalePer = round(($TotalProductSale * 100) / $TotalSale);
		}
		
		$AvgProductSale = 0;
		if($TotalProducts != 0 && $TotalProductSale != 0){
			$AvgProductSale = number_format(($TotalProductSale / $TotalProducts),2);
		}
		
		// get late cancellation fees
		$TotalLateCancellationFees = 0;
		$TotalLateCancellationPer = 0;
		if($TotalSale != 0){
			$TotalLateCancellationPer = round(($TotalLateCancellationFees * 100) / $TotalSale);
		}
		
		// get no show fees
		$TotalNoShowFees = 0;
		$TotalNoShowFeesPer = 0;
		if($TotalSale != 0){
			$TotalNoShowFeesPer = round(($TotalNoShowFees * 100) / $TotalSale);
		}
		
		// online appointment data
		$TotalOnlineAppointments = AppointmentServices::join('appointments', 'appointments.id', 'appointment_services.appointment_id')->select('appointments.id')->where('appointments.user_id', $AdminId)->where('appointments.is_online_appointment',1)->orderBy('appointments.id', 'desc')->get()->toArray();
		$TotalOnlineAppoCounter = count($TotalOnlineAppointments);

		// get total completed appointments
		$TotalOnlineCompletedAppointments = AppointmentServices::join('appointments', 'appointments.id', 'appointment_services.appointment_id')->select('appointments.id')->where('appointments.user_id', $AdminId)->where('appointments.is_online_appointment',1)->where('appointments.appointment_status',4)->orderBy('appointments.id', 'desc')->get()->toArray();

		$TotalOnlineCompletedPer = 0;
		$CompletedOnlineAppoCounter = count($TotalOnlineCompletedAppointments);

		if($TotalOnlineAppoCounter != 0){
			$TotalOnlineCompletedPer = round((($CompletedOnlineAppoCounter * 100) / $TotalOnlineAppoCounter));
		}
		 
		// get total not completed appointments
		$TotalOnlineNotCompletedAppointments = AppointmentServices::join('appointments', 'appointments.id', 'appointment_services.appointment_id')->select('appointments.id')->where('appointments.user_id', $AdminId)->where('appointments.appointment_status','!=',4)->where('appointments.is_online_appointment',1)->where('appointments.is_cancelled','!=',1)->where('appointments.is_noshow','!=',1)->orderBy('appointments.id', 'desc')->get()->toArray();

		$TotalOnlineNotCompletedPer = 0;
		$NotCompletedOnlineAppoCounter = count($TotalOnlineNotCompletedAppointments);

		if($TotalOnlineAppoCounter != 0){
			$TotalOnlineNotCompletedPer = round((($NotCompletedOnlineAppoCounter * 100) / $TotalOnlineAppoCounter));
		}

		// get total cancelled appointments
		$TotalOnlineCancelledAppointments = AppointmentServices::join('appointments', 'appointments.id', 'appointment_services.appointment_id')->select('appointments.id')->where('appointments.user_id', $AdminId)->where('appointments.is_online_appointment',1)->where('appointments.is_cancelled',1)->orderBy('appointments.id', 'desc')->get()->toArray();

		$TotalOnlineCancelledPer = 0;
		$CancelledOnlineAppoCounter = count($TotalOnlineCancelledAppointments);

		if($TotalOnlineAppoCounter != 0){
			$TotalOnlineCancelledPer = round((($CancelledOnlineAppoCounter * 100) / $TotalOnlineAppoCounter));
		}

		// get total cancelled appointments
		$TotalOnlineNoshowdAppointments = AppointmentServices::join('appointments', 'appointments.id', 'appointment_services.appointment_id')->select('appointments.id')->where('appointments.user_id', $AdminId)->where('appointments.is_online_appointment',1)->where('appointments.is_noshow',1)->orderBy('appointments.id', 'desc')->get()->toArray();

		$TotalOnlineNoshowPer = 0;
		$NoshowOnlineAppoCounter = count($TotalOnlineNoshowdAppointments);

		if($TotalOnlineAppoCounter != 0){
			$TotalOnlineNoshowPer = round((($NoshowOnlineAppoCounter * 100) / $TotalOnlineAppoCounter));
		}
		
		$OnlineAppointmentPercentage = 0;
		
		if($TotalAppoCounter != 0 && $TotalOnlineAppoCounter != 0){
			$OnlineAppointmentPercentage = round((($TotalOnlineAppoCounter * 100) / $TotalAppoCounter));
		}
		
		$TotalSale = is_numeric($TotalSale) ? number_format($TotalSale, 2) : $TotalSale;
		$TotalServiceSale = is_numeric($TotalServiceSale) ? number_format($TotalServiceSale, 2) : $TotalServiceSale;
		$TotalProductSale = is_numeric($TotalProductSale) ? number_format($TotalProductSale, 2) : $TotalProductSale;
		return view('analytics.index',compact('Locations','Staff','TotalAppointments','TotalCompletedAppointments','TotalNotCompletedAppointments','TotalCancelledAppointments','TotalNoshowdAppointments','TotalCompletedPer','TotalNotCompletedPer','TotalCancelledPer','TotalNoshowPer','TotalSale','TotalServiceSale','TotalServiceSalePer','TotalProductSale','TotalProductSalePer','TotalLateCancellationFees','TotalLateCancellationPer','TotalNoShowFees','TotalNoShowFeesPer','AvgTotalSale','AvgServiceSale','AvgProductSale','TotalInvoices','TotalOnlineAppoCounter','CompletedOnlineAppoCounter','NotCompletedOnlineAppoCounter','CancelledOnlineAppoCounter','NoshowOnlineAppoCounter','TotalOnlineCompletedPer','TotalOnlineNotCompletedPer','TotalOnlineCancelledPer','TotalOnlineNoshowPer','OnlineAppointmentPercentage'));
	}
	

	public function getAnalyticsTotalAppointments(Request $request){
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
			
			$returnData = array();
			
			$start_date  = ($request->start_date) ? date("Y-m-d",strtotime($request->start_date)) : '';
			$end_date    = ($request->end_date) ? date("Y-m-d",strtotime($request->end_date)) : '';
			$location_id = ($request->location_id) ? $request->location_id : '';
			$staff_id    = ($request->staff_id) ? $request->staff_id : '';

			$staffIdArray = [];
			if(!empty($location_id)) {
				$staffIdArray = StaffLocations::where('location_id', $location_id)->pluck('staff_id')->toArray();
			}


			if($staff_id != '')  {
				$StaffUserInfo = Staff::select('staff.id')->where(['staff_user_id'=>$staff_id])->get()->toArray();
				
				$STAFFID = (!empty($StaffUserInfo)) ? $StaffUserInfo[0]['id'] : '';
				$staffWhere = array('invoice_items.staff_id' => $STAFFID);
				$staffAppo = array('appointment_services.staff_user_id' => $staff_id);
			} else {
				$STAFFID = '';
				$staffWhere = array();
				$staffAppo = array();
			}

			if($location_id != ''){
				$locationAppo  = array('appointments.location_id' => $location_id);
				$locationWhere = array('invoice.location_id' => $location_id);
			} else {
				$locationAppo  = array();
				$locationWhere = array();
			}
			
			// get total appointments
			$TotalAppointments = AppointmentServices::join('appointments', 'appointments.id', 'appointment_services.appointment_id')
								->select('appointments.id')
								->where('appointments.user_id', $AdminId)
								->when($start_date, function ($query) use ($start_date) {
									return $query->whereDate('appointments.appointment_date','>=',$start_date);
								})
								->when($end_date, function ($query) use ($end_date) {
									return $query->whereDate('appointments.appointment_date','<=',$end_date);
								})
								->where($locationAppo)
								->where($staffAppo)
								->orderBy('appointments.id', 'desc')->get()->toArray();
			
			$TotalAppoCounter  = count($TotalAppointments);
			
			// get total completed appointments
			$TotalCompletedAppointments = AppointmentServices::join('appointments', 'appointments.id', 'appointment_services.appointment_id')
										->select('appointments.id')
										->where('appointments.user_id', $AdminId)
										->when($start_date, function ($query) use ($start_date) {
											return $query->whereDate('appointments.appointment_date','>=',$start_date);
										})
										->when($end_date, function ($query) use ($end_date) {
											return $query->whereDate('appointments.appointment_date','<=',$end_date);
										})
										->where($locationAppo)
										->where($staffAppo)
										->where('appointments.appointment_status',4)
										->orderBy('appointments.id', 'desc')->get()->toArray();
			
			$TotalCompletedPer = 0;
			$CompletedAppoCounter = count($TotalCompletedAppointments);
			
			if($TotalAppoCounter != 0){
				$TotalCompletedPer = round((($CompletedAppoCounter * 100) / $TotalAppoCounter));
			}
			
			// get total not completed appointments
			$TotalNotCompletedAppointments = AppointmentServices::join('appointments', 'appointments.id', 'appointment_services.appointment_id')
											->select('appointments.id')
											->where('appointments.user_id', $AdminId)
											->when($start_date, function ($query) use ($start_date) {
												return $query->whereDate('appointments.appointment_date','>=',$start_date);
											})
											->when($end_date, function ($query) use ($end_date) {
												return $query->whereDate('appointments.appointment_date','<=',$end_date);
											})
											->where($locationAppo)
											->where($staffAppo)
											->where('appointments.appointment_status','!=',4)
											->where('appointments.is_cancelled','!=',1)
											->where('appointments.is_noshow','!=',1)
											->orderBy('appointments.id', 'desc')->get()->toArray();
			
			$TotalNotCompletedPer = 0;
			$NotCompletedAppoCounter = count($TotalNotCompletedAppointments);
			
			if($TotalAppoCounter != 0){
				$TotalNotCompletedPer = round((($NotCompletedAppoCounter * 100) / $TotalAppoCounter));
			}
			
			// get total cancelled appointments
			$TotalCancelledAppointments = AppointmentServices::join('appointments', 'appointments.id', 'appointment_services.appointment_id')
										->select('appointments.id')
										->where('appointments.user_id', $AdminId)
										->when($start_date, function ($query) use ($start_date) {
											return $query->whereDate('appointments.appointment_date','>=',$start_date);
										})
										->when($end_date, function ($query) use ($end_date) {
											return $query->whereDate('appointments.appointment_date','<=',$end_date);
										})
										->where($locationAppo)
										->where($staffAppo)
										->where('appointments.is_cancelled',1)
										->orderBy('appointments.id', 'desc')->get()->toArray();
			
			$TotalCancelledPer = 0;
			$CancelledAppoCounter = count($TotalCancelledAppointments);
			
			if($TotalAppoCounter != 0){
				$TotalCancelledPer = round((($CancelledAppoCounter * 100) / $TotalAppoCounter));
			}
			
			// get total cancelled appointments
			$TotalNoshowdAppointments = AppointmentServices::join('appointments', 'appointments.id', 'appointment_services.appointment_id')
										->select('appointments.id')
										->where('appointments.user_id', $AdminId)
										->when($start_date, function ($query) use ($start_date) {
											return $query->whereDate('appointments.appointment_date','>=',$start_date);
										})
										->when($end_date, function ($query) use ($end_date) {
											return $query->whereDate('appointments.appointment_date','<=',$end_date);
										})
										->where($locationAppo)
										->where($staffAppo)
										->where('appointments.is_noshow',1)
										->orderBy('appointments.id', 'desc')->get()->toArray();
			
			$TotalNoshowPer = 0;
			$NoshowAppoCounter = count($TotalNoshowdAppointments);
			
			if($TotalAppoCounter != 0){
				$TotalNoshowPer = round((($NoshowAppoCounter * 100) / $TotalAppoCounter));
			}
			
			// online appointment data
			$TotalOnlineAppointments = AppointmentServices::join('appointments', 'appointments.id', 'appointment_services.appointment_id')
										->select('appointments.id')
										->where('appointments.user_id', $AdminId)
										->when($start_date, function ($query) use ($start_date) {
											return $query->whereDate('appointments.appointment_date','>=',$start_date);
										})
										->when($end_date, function ($query) use ($end_date) {
											return $query->whereDate('appointments.appointment_date','<=',$end_date);
										})
										->where($locationAppo)
										->where($staffAppo)
										->where('appointments.is_online_appointment',1)
										->orderBy('appointments.id', 'desc')->get()->toArray();


			$TotalOnlineAppoCounter = count($TotalOnlineAppointments);

			// get total completed appointments
			$TotalOnlineCompletedAppointments = AppointmentServices::join('appointments', 'appointments.id', 'appointment_services.appointment_id')
												->select('appointments.id')
												->where('appointments.user_id', $AdminId)
												->when($start_date, function ($query) use ($start_date) {
													return $query->whereDate('appointments.appointment_date','>=',$start_date);
												})
												->when($end_date, function ($query) use ($end_date) {
													return $query->whereDate('appointments.appointment_date','<=',$end_date);
												})
												->where($locationAppo)
												->where($staffAppo)
												->where('appointments.is_online_appointment',1)
												->where('appointments.appointment_status',4)
												->orderBy('appointments.id', 'desc')->get()->toArray();

			$TotalOnlineCompletedPer = 0;
			$CompletedOnlineAppoCounter = count($TotalOnlineCompletedAppointments);

			if($TotalOnlineAppoCounter != 0){
				$TotalOnlineCompletedPer = round((($CompletedOnlineAppoCounter * 100) / $TotalOnlineAppoCounter));
			}
			
			// get total not completed appointments
			$TotalOnlineNotCompletedAppointments = AppointmentServices::join('appointments', 'appointments.id', 'appointment_services.appointment_id')
													->select('appointments.id')
													->where('appointments.user_id', $AdminId)
													->when($start_date, function ($query) use ($start_date) {
														return $query->whereDate('appointments.appointment_date','>=',$start_date);
													})
													->when($end_date, function ($query) use ($end_date) {
														return $query->whereDate('appointments.appointment_date','<=',$end_date);
													})
													->where($locationAppo)
													->where($staffAppo)
													->where('appointments.appointment_status','!=',4)
													->where('appointments.is_online_appointment',1)
													->where('appointments.is_cancelled','!=',1)
													->where('appointments.is_noshow','!=',1)
													->orderBy('appointments.id', 'desc')->get()->toArray();

			$TotalOnlineNotCompletedPer = 0;
			$NotCompletedOnlineAppoCounter = count($TotalOnlineNotCompletedAppointments);

			if($TotalOnlineAppoCounter != 0){
				$TotalOnlineNotCompletedPer = round((($NotCompletedOnlineAppoCounter * 100) / $TotalOnlineAppoCounter));
			}

			// get total cancelled appointments
			$TotalOnlineCancelledAppointments = AppointmentServices::join('appointments', 'appointments.id', 'appointment_services.appointment_id')
												->select('appointments.id')
												->where('appointments.user_id', $AdminId)
												->when($start_date, function ($query) use ($start_date) {
													return $query->whereDate('appointments.appointment_date','>=',$start_date);
												})
												->when($end_date, function ($query) use ($end_date) {
													return $query->whereDate('appointments.appointment_date','<=',$end_date);
												})
												->where($locationAppo)
												->where($staffAppo)
												->where('appointments.is_online_appointment',1)
												->where('appointments.is_cancelled',1)
												->orderBy('appointments.id', 'desc')->get()->toArray();

			$TotalOnlineCancelledPer = 0;
			$CancelledOnlineAppoCounter = count($TotalOnlineCancelledAppointments);

			if($TotalOnlineAppoCounter != 0){
				$TotalOnlineCancelledPer = round((($CancelledOnlineAppoCounter * 100) / $TotalOnlineAppoCounter));
			}

			// get total cancelled appointments
			$TotalOnlineNoshowdAppointments = AppointmentServices::join('appointments', 'appointments.id', 'appointment_services.appointment_id')
											->select('appointments.id')
											->where('appointments.user_id', $AdminId)
											->when($start_date, function ($query) use ($start_date) {
												return $query->whereDate('appointments.appointment_date','>=',$start_date);
											})
											->when($end_date, function ($query) use ($end_date) {
												return $query->whereDate('appointments.appointment_date','<=',$end_date);
											})
											->where($locationAppo)
											->where($staffAppo)

											->where('appointments.is_online_appointment',1)
											->where('appointments.is_noshow',1)
											->orderBy('id', 'desc')->get()->toArray();

			$TotalOnlineNoshowPer = 0;
			$NoshowOnlineAppoCounter = count($TotalOnlineNoshowdAppointments);

			if($TotalOnlineAppoCounter != 0){
				$TotalOnlineNoshowPer = round((($NoshowOnlineAppoCounter * 100) / $TotalOnlineAppoCounter));
			}
			
			$OnlineAppointmentPercentage = 0;
			
			if($TotalAppoCounter != 0 && $TotalOnlineAppoCounter != 0){
				$OnlineAppointmentPercentage = round((($TotalOnlineAppoCounter * 100) / $TotalAppoCounter));
			}

			$returnData['TotalAppoCounter']        = $TotalAppoCounter;
			$returnData['CompletedAppoCounter']      = $CompletedAppoCounter;
			$returnData['TotalCompletedPer']         = $TotalCompletedPer;
			$returnData['NotCompletedAppoCounter']   = $NotCompletedAppoCounter;
			$returnData['TotalNotCompletedPer']      = $TotalNotCompletedPer;
			$returnData['CancelledAppoCounter']      = $CancelledAppoCounter;
			$returnData['TotalCancelledPer']         = $TotalCancelledPer;
			$returnData['NoshowAppoCounter']         = $NoshowAppoCounter;
			$returnData['TotalNoshowPer']            = $TotalNoshowPer;
			
			
			$returnData['TotalOnlineAppoCounter']        = $TotalOnlineAppoCounter;
			$returnData['CompletedOnlineAppoCounter']    = $CompletedOnlineAppoCounter;
			$returnData['NotCompletedOnlineAppoCounter'] = $NotCompletedOnlineAppoCounter;
			$returnData['CancelledOnlineAppoCounter']    = $CancelledOnlineAppoCounter;
			$returnData['NoshowOnlineAppoCounter']       = $NoshowOnlineAppoCounter;
			$returnData['TotalOnlineCompletedPer']       = $TotalOnlineCompletedPer;
			$returnData['TotalOnlineNotCompletedPer']    = $TotalOnlineNotCompletedPer;
			$returnData['TotalOnlineCancelledPer']       = $TotalOnlineCancelledPer;
			$returnData['TotalOnlineNoshowPer']          = $TotalOnlineNoshowPer;
			$returnData['OnlineAppointmentPercentage']   = $OnlineAppointmentPercentage;
			
			$data["status"]     = true;
			$data["returnData"] = $returnData;
			return JsonReturn::success($data);

		}
	}
	public function getAnalyticsOccupancy(Request $request){
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
			
			$returnData = array();
			
			$start_date  = ($request->start_date) ? date("Y-m-d",strtotime($request->start_date)) : '';
			$end_date    = ($request->end_date) ? date("Y-m-d",strtotime($request->end_date)) : '';
			$location_id = ($request->location_id) ? $request->location_id : '';
			$staff_id    = ($request->staff_id) ? $request->staff_id : '';

			$staffIdArray = [];
			if(!empty($location_id)) {
				$staffIdArray = StaffLocations::where('location_id', $location_id)->pluck('staff_id')->toArray();
			}
			if(!empty($staff_id)){
				$staff_id = Staff::select('id')->where("staff_user_id",$staff_id)->where("user_id",$AdminId)->first();
			}

			$Time = StaffWorkingHours::where('user_id', $AdminId)
			->when($staffIdArray, function($c_query) use ($staffIdArray) {
				$c_query->whereIn('staff_id', $staffIdArray);
			})
			->when($staff_id, function($c_query) use($staff_id) {
				$c_query->where('staff_id', $staff_id->id);
			})
			->where(function($row) use ($start_date,$end_date){
				//find data where repeats = 0, and date >= start_date, and date <= end_date
				$row->where(function($query) use ($start_date,$end_date){
					$query->where('repeats', 0);
					$query->when($start_date, function ($query2) use ($start_date) {
						return $query2->whereDate('date','>=',$start_date);
					});
					$query->when($end_date, function ($query2) use ($end_date) {
						return $query2->whereDate('date','<=',$end_date);
					});
				});
				//find data where repeats = 1
				$row->orWhere(function($query) use ($start_date,$end_date){

					//where repeats = 1, and endrepeat = 0 ( ongoing ), and date <= end_date
					$query->where(function($query2) use ($start_date,$end_date) {
						$query2->where('repeats', 1);
						$query2->where('endrepeat', 0);
						$query2->when($end_date, function ($query3) use ($end_date) {
							$query3->whereDate('date', '<=', $end_date);
						});
					});

					//where repeats = 1, and endrepeat = specific date, and date >= start_date
					$query->orWhere(function($query2) use ($start_date,$end_date) {
						$query2->where('repeats', 1);
						$query2->where('endrepeat', '!=', 0);
						$query2->when($start_date, function ($query3) use ($start_date) {
							$query3->whereDate('date', '>=', $start_date);
						});
					});
				});
			})
			->where(function($row) use ($start_date,$end_date){

				//where remove_date = specific date, and remove_type = 1 ( remove upcoming dates ), and remove_date >= start_date
				$row->where(function($query) use ($start_date,$end_date) {
					$query->where('remove_date', '!=', 0);
					$query->where('remove_type', 1); //remove upcoming dates
					$query->whereDate('remove_date', '>=', $start_date);
				});

				//where remote_date = 0 (Not removed)
				$row->orWhere(function($query) use ($start_date, $end_date) {
					$query->where('remove_date', 0);
				});

				//where remove_date = specific date, and remove_type = 2 ( remove this day/date only )
				$row->orWhere(function($query) use ($start_date, $end_date) {
					$query->where('remove_date', '!=', 0);
					$query->where('remove_type', 2); //remove single date
				});
			})->get();

			$finalTime = 0;
			
			if(!empty($Time)){
				foreach($Time as $timeKey => $timeValue){

					//Initializing loop start date
					$customStartDate = $start_date;
					if(!$timeValue->repeats) {
						$customEndDate = date('Y-m-d', strtotime($timeValue->date));

						//if not repeat than will loop for only single day
						$customStartDate = $timeValue->date;
					} else {
						if($timeValue->endrepeat != 0) {
							if(($timeValue->remove_date != 0) && ($timeValue->remove_type == 1) && (strtotime($timeValue->remove_date) < strtotime($timeValue->endrepeat))){
								$customEndDate = date('Y-m-d', strtotime($timeValue->remove_date));

								//if database date is greater than the start date of given date range than the loop will start from database date
								if(strtotime($timeValue->date) > strtotime($customStartDate)){
									$customStartDate = $timeValue->date;
								}
							}else{
								$customEndDate = date('Y-m-d', strtotime($timeValue->endrepeat));

								//if database date is greater than the start date of given date range than the loop will start from database date
								if(strtotime($timeValue->date) > strtotime($customStartDate)){
									$customStartDate = $timeValue->date;
								}
							}
						} else {
							// $customEndDate = date('Y-m-d', strtotime($end_date));
							if(($timeValue->remove_date != 0) && ($timeValue->remove_type == 1)){
								$customEndDate = date('Y-m-d', strtotime($timeValue->remove_date));

								//if database date is greater than the start date of given date range than the loop will start from database date
								if(strtotime($timeValue->date) > strtotime($customStartDate)){
									$customStartDate = $timeValue->date;
								}
							}else{
								$customEndDate = date('Y-m-d', strtotime($end_date));

								//if database date is greater than the start date of given date range than the loop will start from database date
								if(strtotime($timeValue->date) > strtotime($customStartDate)){
									$customStartDate = $timeValue->date;
								}
							}
						}
					}

					$remove_date = strtotime($timeValue->remove_date);
					
					$date = $customStartDate;
					
					while(strtotime($date) <= strtotime($customEndDate)){
						if(strtotime($date) == $remove_date){
							$date = date("Y-m-d", strtotime($date." +7 days"));
							continue;
						}
						$date = date("Y-m-d", strtotime($date." +7 days"));
						
						$start_times = json_decode($timeValue->start_time,true);
						$end_times = json_decode($timeValue->end_time,true);

						$start_time_in_seconds = strtotime($start_times[0]);
						$end_time_in_seconds = strtotime($end_times[0]);
						$time = $end_time_in_seconds - $start_time_in_seconds;
						$finalTime += $time;

						$start_time_in_seconds1 = strtotime($start_times[1]);
						$end_time_in_seconds1 = strtotime($end_times[1]);
						$time1 = $end_time_in_seconds1 - $start_time_in_seconds1;
						$finalTime += $time1;
					}

					// $start_times = json_decode($Time[$timeKey]->start_time,true);
					// $end_times = json_decode($Time[$timeKey]->end_time,true);

					// $start_time_in_seconds = strtotime($start_times[0]);
					// $end_time_in_seconds = strtotime($end_times[0]);
					// $time = $end_time_in_seconds - $start_time_in_seconds;
					// $finalTime += $time;

					// $start_time_in_seconds1 = strtotime($start_times[1]);
					// $end_time_in_seconds1 = strtotime($end_times[1]);
					// $time1 = $end_time_in_seconds1 - $start_time_in_seconds1;
					// $finalTime += $time1;
				}
			}


			$finalWorkingHours = $finalTime;
			$finalTime = $this->formatTime($finalTime);

			/* Get Total Booked Hours */

			$getBookedHours = AppointmentServices::where('user_id',$AdminId)
			->when($staffIdArray, function($c_query) use ($staffIdArray) {
				$c_query->whereIn('staff_user_id', $staffIdArray);
			})
			->when($staff_id, function($c_query) use($staff_id) {
				$c_query->where('staff_user_id', $staff_id);
			})
			->whereDate('appointment_date','>=', $start_date)
			->whereDate('appointment_date','<=', $end_date)
			->get()->toArray();

			$totalBookedHours = 0;
			if(!empty($getBookedHours)){
				foreach($getBookedHours as $getBookedHoursKey => $getBookedHoursValue){

					$totalBookedHours += $getBookedHoursValue['duration'];

					if(( $getBookedHoursValue['is_extra_time'] == 1 ) && ( $getBookedHoursValue['extra_time'] == 1 )){
						$totalBookedHours += $getBookedHoursValue['extra_time_duration'];
					}
				}
			}

			$finalBookedHours = $totalBookedHours * 60;
			$totalBookedHoursPercentage = 0;
			if(!empty($finalBookedHours)){
				$totalBookedHoursPercentage = round( ( ($finalBookedHours *100) / ($finalWorkingHours ? $finalWorkingHours : 1 )));
			}
			$occupancyPercentage = $totalBookedHoursPercentage;
			$totalBookedHours  = $this->formatTime($finalBookedHours);

			/* Get Total Blocked Hours */

			$getBlockedHours = StaffBlockedTime::where('user_id',$AdminId)
			->when($staffIdArray, function($c_query) use ($staffIdArray) {
				$c_query->whereIn('staff_user_id', $staffIdArray);
			})
			->when($staff_id, function($c_query) use($staff_id) {
				$c_query->where('staff_user_id', $staff_id);
			})
			->whereDate('date','>=', $start_date)
			->whereDate('date','<=', $end_date)
			->get()->toArray();
			
			$totalBlockedHours = 0;

			if(!empty($getBlockedHours)){
				foreach($getBlockedHours as $getBlockedHoursKey => $getBlockedHoursValue){
					$totalBlockedHours += strtotime( $getBlockedHoursValue['end_time'] ) - strtotime($getBlockedHoursValue['start_time']);
				}
			}

			$finalBlockedHours = $totalBlockedHours;
			$totalBlockedHoursPercentage = 0;
			if(!empty($finalBlockedHours)){
				$totalBlockedHoursPercentage = round( ( ($finalBlockedHours * 100) / ($finalWorkingHours ? $finalWorkingHours : 1 ) ));
			}
			$totalBlockedHours = $this->formatTime($totalBlockedHours);


			/* Get Total Unbooked Hours */

			$totalUnBookedHours = ( $finalWorkingHours - $finalBookedHours - $finalBlockedHours);
			$totalUnBookedHoursPercentage = 0;
			if(!empty($totalUnBookedHours)){
				$totalUnBookedHoursPercentage = round( ( ($totalUnBookedHours * 100) / ($finalWorkingHours ? $finalWorkingHours : 1 ) ));	
			}
			$totalUnBookedHours = $this->formatTime($totalUnBookedHours);

			$returnData['finalTime'] 					 = $finalTime;
			$returnData['totalBookedHours']				 = $totalBookedHours;
			$returnData['totalBlockedHours']			 = $totalBlockedHours;
			$returnData['totalUnBookedHours']			 = $totalUnBookedHours;
			
			$returnData['totalBookedHoursPercentage']	 = $totalBookedHoursPercentage;
			$returnData['totalBlockedHoursPercentage']	 = $totalBlockedHoursPercentage;
			$returnData['totalUnBookedHoursPercentage']	 = $totalUnBookedHoursPercentage;
			$returnData['occupancyPercentage']	 		 = $occupancyPercentage;
			
			$data["status"]     = true;
			$data["returnData"] = $returnData;
			return JsonReturn::success($data);
			
		}
	}
	public function getAnalyticsTotalSales(Request $request){
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
			
			$returnData = array();
			
			$start_date  = ($request->start_date) ? date("Y-m-d",strtotime($request->start_date)) : '';
			$end_date    = ($request->end_date) ? date("Y-m-d",strtotime($request->end_date)) : '';
			$location_id = ($request->location_id) ? $request->location_id : '';
			$staff_id    = ($request->staff_id) ? $request->staff_id : '';


			if($staff_id != '')  {
				$StaffUserInfo = Staff::select('staff.id')->where(['staff_user_id'=>$staff_id])->get()->toArray();
				
				$STAFFID = (!empty($StaffUserInfo)) ? $StaffUserInfo[0]['id'] : '';
				$staffWhere = array('invoice_items.staff_id' => $STAFFID);
				$staffAppo = array('appointment_services.staff_user_id' => $staff_id);
			} else {
				$STAFFID = '';
				$staffWhere = array();
				$staffAppo = array();
			}

			if($location_id != ''){
				$locationAppo  = array('appointments.location_id' => $location_id);
				$locationWhere = array('invoice.location_id' => $location_id);
			} else {
				$locationAppo  = array();
				$locationWhere = array();
			}
			
			// get total sales
			/*$getInvoices = Invoice::select(DB::raw('COUNT(id) as total_invoices'),DB::raw('SUM(invoice.inovice_final_total) as total_sale'))
							->where('invoice.user_id', $AdminId)
							->where('invoice.invoice_status','!=','2')
							->where('invoice.invoice_status','!=','3')
							->when($start_date, function ($query) use ($start_date) {
								return $query->whereDate('invoice.created_at','>=',$start_date);
							})
							->when($end_date, function ($query) use ($end_date) {
								return $query->whereDate('invoice.created_at','<=',$end_date);
							})
							->where($locationWhere)
							->when($STAFFID, function($query) use ($STAFFID) {

								$query->whereIn('invoice.id', function($query2) use ($STAFFID){
									$query2->select('invoice_id')
									->from('invoice_items')
									->where('staff_id',$STAFFID)
									->groupBy('invoice_id');
								});
							})
							->orderBy('invoice.id', 'desc')->get()->toArray();*/


			$getInvoices = InvoiceItems::join('invoice','invoice.id','=','invoice_items.invoice_id')
							->select(DB::raw('COUNT(DISTINCT(invoice.id)) as total_invoices'),DB::raw('SUM(invoice_items.item_price) as total_sale'),'invoice.id')
							->where('invoice.user_id', $AdminId)
							->where('invoice.invoice_status','!=','2')
							->where('invoice.invoice_status','!=','3')
							->where('invoice_items.item_type', '!=', 'voucher')							
							->where('invoice_items.item_type', '!=', 'paidplan')
							->when($start_date, function ($query) use ($start_date) {
								return $query->whereDate('invoice.created_at','>=',$start_date);
							})
							->when($end_date, function ($query) use ($end_date) {
								return $query->whereDate('invoice.created_at','<=',$end_date);
							})
							->where($locationWhere)
							->when($STAFFID, function($query) use ($STAFFID) {

								$query->where('invoice_items.staff_id', $STAFFID);
							})
							->orderBy('invoice.id', 'desc')->get()->toArray();
			
			$TotalRefundInvoices = 0;
			$TotalRefundSale     = 0;
			$TotalVoidSale     	 = 0;
			$TotalVoidInvoices	 = 0;

			if(!empty($getInvoices)){
				foreach($getInvoices as $getInvoicesKey => $getInvoicesValue){
					$RefundInvoices = InvoiceItems::join('invoice','invoice.id','=','invoice_items.invoice_id')
										->select(DB::raw('COUNT(DISTINCT(invoice.id)) as total_invoices'),DB::raw('SUM(invoice_items.item_price) as total_sale'),'invoice.id')
										->where('invoice.original_invoice_id', $getInvoicesValue['id'])
										->where('invoice.invoice_status','2')
										->first();
					
	
					if(!empty($RefundInvoices)){
						$TotalRefundInvoices += $RefundInvoices->total_invoices;
						$TotalRefundSale     += $RefundInvoices->total_sale;
					}
				}
				foreach($getInvoices as $getInvoicesKey => $getInvoicesValue){
					$voidedInvoices = InvoiceItems::join('invoice','invoice.id','=','invoice_items.invoice_id')
										->select(DB::raw('COUNT(DISTINCT(invoice.id)) as total_invoices'),DB::raw('SUM(invoice_items.item_price) as total_sale'),'invoice.id')
										->where('invoice.original_invoice_id', $getInvoicesValue['id'])
										->where('invoice.invoice_status','3')
										->first();
					
	
					if(!empty($voidedInvoices)){
						$TotalVoidInvoices += $voidedInvoices->total_invoices;
						$TotalVoidSale     += $voidedInvoices->total_sale;
					}
				}
			}

			// $getRefundInvoices = InvoiceItems::join('invoice','invoice.id','=','invoice_items.invoice_id')
			// 					->select(DB::raw('COUNT(DISTINCT(invoice.id)) as total_invoices'),DB::raw('SUM(invoice_items.item_price) as total_sale'),'invoice.id')
			// 					->where('invoice.user_id', $AdminId)
			// 					->where('invoice.invoice_status','2')
			// 					->where('invoice.original_invoice_id','!=','0')
			// 					->where('invoice_items.item_type', '!=', 'voucher')
			// 					->where('invoice_items.item_type', '!=', 'paidplan')
			// 					->when($start_date, function ($query) use ($start_date) {
			// 						return $query->whereDate('invoice.created_at','>=',$start_date);
			// 					})
			// 					->when($end_date, function ($query) use ($end_date) {
			// 						return $query->whereDate('invoice.created_at','<=',$end_date);
			// 					})
			// 					->where($locationWhere)
			// 					->when($STAFFID, function($query) use ($STAFFID) {

			// 						$query->where('invoice_items.staff_id', $STAFFID);
			// 					})
			// 					->orderBy('invoice.id', 'desc')->get()->toArray();
			// 					echo "<br> GetRefundInvoices->total_sales :- "; print_r($getRefundInvoices);
			
			$TotalInvoices = ($getInvoices[0]['total_invoices']) ? $getInvoices[0]['total_invoices'] : 0;
			$TotalSale     = ($getInvoices[0]['total_sale']) ? $getInvoices[0]['total_sale'] : 0;

			$TotalInvoices += $TotalRefundInvoices;
			$TotalInvoices += $TotalVoidInvoices;
			$TotalSale -= $TotalRefundSale;
			$TotalSale -= $TotalVoidSale;
			
			$AvgTotalSale = 0;
			if($TotalSale != 0 && $TotalInvoices != 0){
				$AvgTotalSale = number_format(($TotalSale / $TotalInvoices),2);
			}

			// get total service sale
			$ServiceTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_services'),DB::raw('SUM(invoice_items.item_price) as total_sale'))
							->join('invoice','invoice.id','=','invoice_items.invoice_id')
							->where(['invoice.user_id'=>$AdminId])
							->where('invoice_items.item_type','services')
							->where('invoice.invoice_status','!=','2')
							->where('invoice.invoice_status','!=','3')
							->when($start_date, function ($query) use ($start_date) {
								return $query->whereDate('invoice.created_at','>=',$start_date);
							})
							->when($end_date, function ($query) use ($end_date) {
								return $query->whereDate('invoice.created_at','<=',$end_date);
							})
							->where($locationWhere)
							->where($staffWhere)
							->orderBy('invoice_items.id', 'ASC')->get()->toArray();

			// $ServiceTotalRefund = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_services'),DB::raw('SUM(invoice_items.item_price) as total_sale'),'invoice.id')
			// 				->join('invoice','invoice.id','=','invoice_items.invoice_id')
			// 				->where(['invoice.user_id'=>$AdminId])
			// 				->where('invoice_items.item_type','services')
			// 				->where('invoice.invoice_status','2')
			// 				->where('invoice.original_invoice_id','!=','0')
			// 				->when($start_date, function ($query) use ($start_date) {
			// 					return $query->whereDate('invoice.created_at','>=',$start_date);
			// 				})
			// 				->when($end_date, function ($query) use ($end_date) {
			// 					return $query->whereDate('invoice.created_at','<=',$end_date);
			// 				})
			// 				->where($locationWhere)
			// 				->where($staffWhere)
			// 				->orderBy('invoice_items.id', 'ASC')->get()->toArray();

			// $ServiceTotalVoid = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_services'),DB::raw('SUM(invoice_items.item_price) as total_sale'))
			// 				->join('invoice','invoice.id','=','invoice_items.invoice_id')
			// 				->where(['invoice.user_id'=>$AdminId])
			// 				->where('invoice_items.item_type','services')
			// 				->where('invoice.invoice_status','3')
			// 				->where('invoice.original_invoice_id','!=','0')
			// 				->when($start_date, function ($query) use ($start_date) {
			// 					return $query->whereDate('invoice.created_at','>=',$start_date);
			// 				})
			// 				->when($end_date, function ($query) use ($end_date) {
			// 					return $query->whereDate('invoice.created_at','<=',$end_date);
			// 				})
			// 				->where($locationWhere)
			// 				->where($staffWhere)
			// 				->orderBy('invoice_items.id', 'ASC')->get()->toArray();
							
			// echo "<pre>"; echo "GetVoidInvoices :-";print_r($ServiceTotalVoid);echo "GetRefundedInvoices :-";print_r($ServiceTotalRefund);die;
			
			$TotalService = ($ServiceTotal[0]['total_services']) ? $ServiceTotal[0]['total_services'] : 0;
			$TotalServiceSale = ($ServiceTotal[0]['total_sale']) ? $ServiceTotal[0]['total_sale'] : 0;
			// $TotalServiceSale -= ($ServiceTotalRefund[0]['total_sale']) ? $ServiceTotalRefund[0]['total_sale'] : 0;
			// $TotalServiceSale -= ($ServiceTotalVoid[0]['total_sale']) ? $ServiceTotalVoid[0]['total_sale'] : 0;
			
			$TotalServiceSalePer = 0;
			if($TotalSale != 0){
				$TotalServiceSalePer = round(($TotalServiceSale * 100) / $TotalSale);
			}
			
			$AvgServiceSale = 0;
			if($TotalService != 0 && $TotalServiceSale != 0){
				$AvgServiceSale = number_format(($TotalServiceSale / $TotalService),2);
			}
			
			// get total product sales
			$PrductTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_products'),DB::raw('SUM(invoice_items.item_price) as total_sale'))
							->join('invoice','invoice.id','=','invoice_items.invoice_id')
							->where(['invoice.user_id'=>$AdminId])
							->where('invoice_items.item_type','product')
							->where('invoice.invoice_status','!=','2')
							->where('invoice.invoice_status','!=','3')
							->when($start_date, function ($query) use ($start_date) {
								return $query->whereDate('invoice.created_at','>=',$start_date);
							})
							->when($end_date, function ($query) use ($end_date) {
								return $query->whereDate('invoice.created_at','<=',$end_date);
							})
							->where($locationWhere)
							->where($staffWhere)
							->orderBy('invoice_items.id', 'ASC')->get()->toArray();

			// $PrductTotalRefund = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_products'),DB::raw('SUM(invoice_items.item_price) as total_sale'))
			// 				->join('invoice','invoice.id','=','invoice_items.invoice_id')
			// 				->where(['invoice.user_id'=>$AdminId])
			// 				->where('invoice_items.item_type','product')
			// 				->where('invoice.invoice_status','2')
			// 				->where('invoice.original_invoice_id','!=','0')
			// 				->when($start_date, function ($query) use ($start_date) {
			// 					return $query->whereDate('invoice.created_at','>=',$start_date);
			// 				})
			// 				->when($end_date, function ($query) use ($end_date) {
			// 					return $query->whereDate('invoice.created_at','<=',$end_date);
			// 				})
			// 				->where($locationWhere)
			// 				->where($staffWhere)
			// 				->orderBy('invoice_items.id', 'ASC')->get()->toArray();

			// $PrductTotalVoid = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_products'),DB::raw('SUM(invoice_items.item_price) as total_sale'))
			// 				->join('invoice','invoice.id','=','invoice_items.invoice_id')
			// 				->where(['invoice.user_id'=>$AdminId])
			// 				->where('invoice_items.item_type','product')
			// 				->where('invoice.invoice_status','3')
			// 				->where('invoice.original_invoice_id','!=','0')
			// 				->when($start_date, function ($query) use ($start_date) {
			// 					return $query->whereDate('invoice.created_at','>=',$start_date);
			// 				})
			// 				->when($end_date, function ($query) use ($end_date) {
			// 					return $query->whereDate('invoice.created_at','<=',$end_date);
			// 				})
			// 				->where($locationWhere)
			// 				->where($staffWhere)
			// 				->orderBy('invoice_items.id', 'ASC')->get()->toArray();
			
			$TotalProducts = ($PrductTotal[0]['total_products']) ? $PrductTotal[0]['total_products'] : 0;
			$TotalProductSale = ($PrductTotal[0]['total_sale']) ? $PrductTotal[0]['total_sale'] : 0;
			// $TotalProductSale -= ($PrductTotalRefund[0]['total_sale']) ? $PrductTotalRefund[0]['total_sale'] : 0;
			// $TotalProductSale -= ($PrductTotalVoid[0]['total_sale']) ? $PrductTotalVoid[0]['total_sale'] : 0;
			
			$TotalProductSalePer = 0;
			if($TotalSale != 0){
				$TotalProductSalePer = round(($TotalProductSale * 100) / $TotalSale);
			}
			
			$AvgProductSale = 0;
			if($TotalProducts != 0 && $TotalProductSale != 0){
				$AvgProductSale = number_format(($TotalProductSale / $TotalProducts),2);
			}
			
			// get late cancellation fees
			$TotalLateCancellationFees = 0;
			$TotalLateCancellationPer = 0;
			if($TotalSale != 0){
				$TotalLateCancellationPer = round(($TotalLateCancellationFees * 100) / $TotalSale);
			}
			
			// get no show fees
			$TotalNoShowFees = 0;
			$TotalNoShowFeesPer = 0;
			if($TotalSale != 0){
				$TotalNoShowFeesPer = round(($TotalNoShowFees * 100) / $TotalSale);
			}

			
			
			/* ------------------------------------------------------------------ */
			

			$returnData['AvgTotalSale']              = $AvgTotalSale;
			$returnData['TotalInvoices']             = $TotalInvoices;
			$returnData['AvgServiceSale']            = $AvgServiceSale;
			$returnData['AvgProductSale']            = $AvgProductSale;
													
			$returnData['TotalSale']                 = is_numeric($TotalSale) ? number_format($TotalSale, 2) : $TotalSale;
			$returnData['TotalServiceSale']          = is_numeric($TotalServiceSale) ? number_format($TotalServiceSale, 2) : $TotalServiceSale;
			$returnData['TotalServiceSalePer']       = $TotalServiceSalePer;
			$returnData['TotalProductSale']          = is_numeric($TotalProductSale) ? number_format($TotalProductSale, 2) : $TotalProductSale;
			$returnData['TotalProductSalePer']       = $TotalProductSalePer;
			$returnData['TotalLateCancellationFees'] = $TotalLateCancellationFees;
			$returnData['TotalLateCancellationPer']  = $TotalLateCancellationPer;
			$returnData['TotalNoShowFees']           = $TotalNoShowFees;
			$returnData['TotalNoShowFeesPer']        = $TotalNoShowFeesPer;
			
			$data["status"]     = true;
			$data["returnData"] = $returnData;
			return JsonReturn::success($data);
			
		}
	}
	public function getAnalyticsClientRetention(Request $request){
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
			
			$returnData = array();
			
			$start_date  = ($request->start_date) ? date("Y-m-d",strtotime($request->start_date)) : '';
			$end_date    = ($request->end_date) ? date("Y-m-d",strtotime($request->end_date)) : '';
			$location_id = ($request->location_id) ? $request->location_id : '';
			$staff_id    = ($request->staff_id) ? $request->staff_id : '';


			$staffIdArray = [];
			if(!empty($location_id)) {
				$staffIdArray = StaffLocations::where('location_id', $location_id)->pluck('staff_id')->toArray();
			}

			if(!empty($staff_id)){
				$staff_id = Staff::select('id')->where("staff_user_id",$staff_id)->where("user_id",$AdminId)->first();
			}

			// echo "<pre>"; echo "Staff_id :- ".$staff_id->id;echo "<br>";

			/* Client Retention */

			$clientRetentionData = InvoiceItems::select("invoice_items.*",DB::raw("(invoice_items.item_price * invoice_items.quantity) as sum_item_price"),"invoice.*")
			->Join('invoice','invoice.id','invoice_items.invoice_id')
			->where("invoice.user_id",$AdminId)
			->where("invoice_items.item_type","!=","voucher")
			->where("invoice_items.item_type","!=","paidplan")
			->where("invoice.invoice_status",1)
			->when($staffIdArray, function($c_query) use ($staffIdArray) {
				$c_query->whereIn('invoice_items.staff_id', $staffIdArray);
			})
			->when($staff_id, function($c_query) use ($staff_id) {
				$c_query->where('invoice_items.staff_id', $staff_id->id);
			})
			->when($start_date, function ($query) use ($start_date) {
				return $query->whereDate('invoice.created_at','>=',$start_date);
			})
			->when($end_date, function ($query) use ($end_date) {
				return $query->whereDate('invoice.created_at','<=',$end_date);
			})
			->groupBy("invoice_items.invoice_id")
			->get()->toArray();

			// echo "<pre>"; echo "Client Retention Data :- "; print_r($clientRetentionData);die;
	
			$returningClient = 0;
			$newClient = 0;
			$walk_in = 0;
			$iTotal = 0;
			$subTotal = 0;
	
			$returningClient_tax = 0;
			$newClient_tax = 0;
			$walk_in_tax = 0;
	
			if(!empty($clientRetentionData)){
				foreach($clientRetentionData as $clientRetentionDataKey => $clientRetentionDataValue){
					$subTotal += $clientRetentionDataValue['invoice_sub_total'];
					if($clientRetentionDataValue['client_id'] == 0 ){
						$refunded = Invoice::where('invoice_status', 2)
						->where('original_invoice_id',$clientRetentionDataValue['id'])
						->first();
	
						$voided = Invoice::where('invoice_status', 3)
						->where('original_invoice_id',$clientRetentionDataValue['id'])
						->first();
	
						if($refunded){
	
						}elseif($voided){
	
						}else{
							$walk_in_tax += $clientRetentionDataValue['invoice_total'] - $clientRetentionDataValue['invoice_sub_total'];
							
							$walk_in_voucherTotal = InvoiceItems::where('item_type', '=', 'voucher')
							->where("invoice_id",$clientRetentionDataValue['id'])
							->select( DB::raw('SUM(item_price) as t'))
							->groupBy("invoice_id")
							->first();
	
							if(!empty($walk_in_voucherTotal->t)){
								$iTotal += ($clientRetentionDataValue['invoice_total'] - $walk_in_voucherTotal->t);
								$walk_in += ($clientRetentionDataValue['invoice_sub_total'] - $walk_in_voucherTotal->t);
							}else{
								$iTotal += $clientRetentionDataValue['invoice_total'];
								$walk_in += $clientRetentionDataValue['invoice_sub_total'];
							}
						}
	
					}else{
						$checkReturningClient = Invoice::where("client_id",$clientRetentionDataValue['client_id'])
						->where("user_id",$AdminId)
						->when($start_date, function ($query) use ($start_date) {
							return $query->whereDate('created_at','<',$start_date);
						})->first();
	
						if($checkReturningClient){
							$refunded = Invoice::where('invoice_status', 2)
							->where('original_invoice_id',$clientRetentionDataValue['id'])
							->first();
	
							$voided = Invoice::where('invoice_status', 3)
							->where('original_invoice_id',$clientRetentionDataValue['id'])
							->first();
	
							if($refunded){
	
							}elseif($voided){
	
							}else{							
								$returningClient_tax += $clientRetentionDataValue['invoice_total'] - $clientRetentionDataValue['invoice_sub_total'];
	
								$returningClient_voucherTotal = InvoiceItems::where('item_type', '=', 'voucher')
								// ->orWhere('item_type', '=', 'paidplan')
								->where("invoice_id",$clientRetentionDataValue['id'])
								->select( DB::raw('SUM(item_price) as t'))
								->first();
								// echo $returningClient_voucherTotal->t;
								if(!empty($returningClient_voucherTotal->t)){
									// $returningClient_tax += floatval($clientRetentionDataValue['invoice_total']) - (floatval($clientRetentionDataValue['invoice_sub_total']) - $returningClient_voucherTotal->t);
									$iTotal += $clientRetentionDataValue['invoice_total'] - $returningClient_voucherTotal->t;
									$returningClient += $clientRetentionDataValue['invoice_sub_total'] - $returningClient_voucherTotal->t;
								}else{
									$iTotal += $clientRetentionDataValue['invoice_total'];
									$returningClient += $clientRetentionDataValue['invoice_sub_total'];
								}
							}
						}else{
							$refunded = Invoice::where('invoice_status', 2)
							->where('original_invoice_id',$clientRetentionDataValue['id'])
							->first();
	
							$voided = Invoice::where('invoice_status', 3)
							->where('original_invoice_id',$clientRetentionDataValue['id'])
							->first();
	
							if($refunded){
	
							}elseif($voided){
	
							}else{
								$newClient_tax += $clientRetentionDataValue['invoice_total'] - $clientRetentionDataValue['invoice_sub_total'];
	
								$newClient_voucherTotal = InvoiceItems::where('item_type', '=', 'voucher')
								// ->orWhere('item_type', '=', 'paidplan')
								->where("invoice_id",$clientRetentionDataValue['id'])
								->select( DB::raw('SUM(item_price) as t'))
								->first();
								// echo $newClient_voucherTotal->t;
								if(!empty($newClient_voucherTotal->t)){
									// $newClient_tax += floatval($clientRetentionDataValue['invoice_total']) - (floatval($clientRetentionDataValue['invoice_sub_total']) - $newClient_voucherTotal->t);
									$iTotal += $clientRetentionDataValue['invoice_total'] - $newClient_voucherTotal->t;
									$newClient += $clientRetentionDataValue['invoice_sub_total'] - $newClient_voucherTotal->t;
								}else{
									$iTotal += $clientRetentionDataValue['invoice_total'];
									$newClient += $clientRetentionDataValue['invoice_sub_total'];
								}
							}
						}
					}
				}
			}
	
			$returningClient_Total = $iTotal - ($newClient + $newClient_tax) - ($walk_in + $walk_in_tax);
			$newClient_Total = $iTotal - ($walk_in + $walk_in_tax) - ($returningClient + $returningClient_tax);
			$walk_in_Total = $iTotal - ($newClient + $newClient_tax) - ($returningClient + $returningClient_tax);
	
			/** Client Retention Percentage */
			// $totalBlockedHoursPercentage = round( ( ($finalBlockedHours * 100) / $finalWorkingHours ));
	
			$returningClient_Percentage = round((($returningClient_Total * 100) / ($iTotal ? $iTotal: 1)));
			$newClient_Percentage = round((($newClient_Total * 100) / ($iTotal ? $iTotal: 1)));
			$walk_in_Percentage = round((($walk_in_Total * 100) / ($iTotal ? $iTotal: 1)));
	
	
			$returningClient_Total = number_format($returningClient_Total, 2,".","");
			$newClient_Total = number_format($newClient_Total, 2,".","");
			$walk_in_Total = number_format($walk_in_Total, 2,".","");

			$returnData['returningClient_Total'] 		 = $returningClient_Total;
			$returnData['newClient_Total']		 		 = $newClient_Total;
			$returnData['walk_in_Total']		 		 = $walk_in_Total;

			$returnData['returningClient_Percentage']	 = $returningClient_Percentage;
			$returnData['newClient_Percentage']	 		 = $newClient_Percentage;
			$returnData['walk_in_Percentage']		 	 = $walk_in_Percentage;
			
			$data["status"]     = true;
			$data["returnData"] = $returnData;
			return JsonReturn::success($data);
			
		}
	}
	
	/*public function getAnalyticsReportFilter(Request $request){
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
			
			$returnData = array();
			
			$start_date  = ($request->start_date) ? date("Y-m-d",strtotime($request->start_date)) : '';
			$end_date    = ($request->end_date) ? date("Y-m-d",strtotime($request->end_date)) : '';
			$location_id = ($request->location_id) ? $request->location_id : '';
			$staff_id    = ($request->staff_id) ? $request->staff_id : '';
			
			if($start_date != '' && $end_date != ''){
				if($location_id != ''){
					$locationAppo  = array('location_id' => $location_id);
					$locationWhere = array('invoice.location_id' => $location_id);
				} else {
					$locationAppo  = array();
					$locationWhere = array();
				}
				
				if($staff_id != ''){
					$StaffUserInfo = Staff::select('staff.id')->where(['staff_user_id'=>$staff_id])->get()->toArray();
					
					$STAFFID = (!empty($StaffUserInfo)) ? $StaffUserInfo[0]['id'] : 0;
					$staffWhere = array('invoice_items.staff_id' => $STAFFID);
					
					// get total appointments
					$TotalAppointments = Appointments::select('id')->where('user_id', $AdminId)->whereDate('appointment_date','>=',$start_date)->whereDate('appointment_date','<=',$end_date)->where($locationAppo)->whereIn('id', function($query) use ($staff_id){
						$query->select('appointment_id')
						->from('appointment_services')
						->where('staff_user_id', $staff_id)
						->groupBy('appointment_id');
					})->orderBy('id', 'desc')->get()->toArray();
					
					$TotalAppoCounter  = count($TotalAppointments);
					
					// get total completed appointments
					$TotalCompletedAppointments = Appointments::select('id')->where('user_id', $AdminId)->whereDate('appointment_date','>=',$start_date)->whereDate('appointment_date','<=',$end_date)->where($locationAppo)->where('appointment_status',4)->whereIn('id', function($query) use ($staff_id){
						$query->select('appointment_id')
						->from('appointment_services')
						->where('staff_user_id', $staff_id)
						->groupBy('appointment_id');
					})->orderBy('id', 'desc')->get()->toArray();
					
					$TotalCompletedPer = 0;
					$CompletedAppoCounter = count($TotalCompletedAppointments);
					
					if($TotalAppoCounter != 0){
						$TotalCompletedPer = round((($CompletedAppoCounter * 100) / $TotalAppoCounter));
					}
					
					// get total not completed appointments
					$TotalNotCompletedAppointments = Appointments::select('id')->where('user_id', $AdminId)->whereDate('appointment_date','>=',$start_date)->whereDate('appointment_date','<=',$end_date)->where($locationAppo)->where('appointment_status','!=',4)->where('is_cancelled','!=',1)->where('is_noshow','!=',1)->whereIn('id', function($query) use ($staff_id){
						$query->select('appointment_id')
						->from('appointment_services')
						->where('staff_user_id', $staff_id)
						->groupBy('appointment_id');
					})->orderBy('id', 'desc')->get()->toArray();
					
					$TotalNotCompletedPer = 0;
					$NotCompletedAppoCounter = count($TotalNotCompletedAppointments);
					
					if($TotalAppoCounter != 0){
						$TotalNotCompletedPer = round((($NotCompletedAppoCounter * 100) / $TotalAppoCounter));
					}
					
					// get total cancelled appointments
					$TotalCancelledAppointments = Appointments::select('id')->where('user_id', $AdminId)->whereDate('appointment_date','>=',$start_date)->whereDate('appointment_date','<=',$end_date)->where($locationAppo)->where('is_cancelled',1)->whereIn('id', function($query) use ($staff_id){
						$query->select('appointment_id')
						->from('appointment_services')
						->where('staff_user_id', $staff_id)
						->groupBy('appointment_id');
					})->orderBy('id', 'desc')->get()->toArray();
					
					$TotalCancelledPer = 0;
					$CancelledAppoCounter = count($TotalCancelledAppointments);
					
					if($TotalAppoCounter != 0){
						$TotalCancelledPer = round((($CancelledAppoCounter * 100) / $TotalAppoCounter));
					}
					
					// get total cancelled appointments
					$TotalNoshowdAppointments = Appointments::select('id')->where('user_id', $AdminId)->whereDate('appointment_date','>=',$start_date)->whereDate('appointment_date','<=',$end_date)->where($locationAppo)->where('is_noshow',1)->whereIn('id', function($query) use ($staff_id){
						$query->select('appointment_id')
						->from('appointment_services')
						->where('staff_user_id', $staff_id)
						->groupBy('appointment_id');
					})->orderBy('id', 'desc')->get()->toArray();
					
					$TotalNoshowPer = 0;
					$NoshowAppoCounter = count($TotalNoshowdAppointments);
					
					if($TotalAppoCounter != 0){
						$TotalNoshowPer = round((($NoshowAppoCounter * 100) / $TotalAppoCounter));
					}
					
					// get total sales
					$getInvoices = Invoice::select(DB::raw('COUNT(id) as total_invoices'),DB::raw('SUM(invoice.inovice_final_total) as total_sale'))->where('invoice.user_id', $AdminId)->where('invoice.invoice_status','!=','2')->whereDate('invoice.created_at','>=',$start_date)->whereDate('invoice.created_at','<=',$end_date)->where($locationWhere)->whereIn('invoice.id', function($query) use ($STAFFID){
						$query->select('invoice_id')
						->from('invoice_items')
						->where('staff_id',$STAFFID)
						->groupBy('invoice_id');
					})->orderBy('invoice.id', 'desc')->get()->toArray();
					
					$TotalInvoices = ($getInvoices[0]['total_invoices']) ? $getInvoices[0]['total_invoices'] : 0;
					$TotalSale     = ($getInvoices[0]['total_sale']) ? $getInvoices[0]['total_sale'] : 0;
					
					$AvgTotalSale = 0;
					if($TotalSale != 0 && $TotalInvoices != 0){
						$AvgTotalSale = number_format(($TotalSale / $TotalInvoices),2);
					}
					
					// online appointment data
					$TotalOnlineAppointments = Appointments::select('id')->where('user_id', $AdminId)->where('is_online_appointment',1)->whereDate('appointment_date','>=',$start_date)->whereDate('appointment_date','<=',$end_date)->where($locationAppo)->whereIn('id', function($query) use ($staff_id){
						$query->select('appointment_id')
						->from('appointment_services')
						->where('staff_user_id', $staff_id)
						->groupBy('appointment_id');
					})->orderBy('id', 'desc')->get()->toArray();
					$TotalOnlineAppoCounter = count($TotalOnlineAppointments);

					// get total completed appointments
					$TotalOnlineCompletedAppointments = Appointments::select('id')->where('user_id', $AdminId)->where('is_online_appointment',1)->where('appointment_status',4)->whereDate('appointment_date','>=',$start_date)->whereDate('appointment_date','<=',$end_date)->where($locationAppo)->whereIn('id', function($query) use ($staff_id){
						$query->select('appointment_id')
						->from('appointment_services')
						->where('staff_user_id', $staff_id)
						->groupBy('appointment_id');
					})->orderBy('id', 'desc')->get()->toArray();

					$TotalOnlineCompletedPer = 0;
					$CompletedOnlineAppoCounter = count($TotalOnlineCompletedAppointments);

					if($TotalOnlineAppoCounter != 0){
						$TotalOnlineCompletedPer = round((($CompletedOnlineAppoCounter * 100) / $TotalOnlineAppoCounter));
					}
					 
					// get total not completed appointments
					$TotalOnlineNotCompletedAppointments = Appointments::select('id')->where('user_id', $AdminId)->where('appointment_status','!=',4)->where('is_online_appointment',1)->where('is_cancelled','!=',1)->where('is_noshow','!=',1)->whereDate('appointment_date','>=',$start_date)->whereDate('appointment_date','<=',$end_date)->where($locationAppo)->whereIn('id', function($query) use ($staff_id){
						$query->select('appointment_id')
						->from('appointment_services')
						->where('staff_user_id', $staff_id)
						->groupBy('appointment_id');
					})->orderBy('id', 'desc')->get()->toArray();

					$TotalOnlineNotCompletedPer = 0;
					$NotCompletedOnlineAppoCounter = count($TotalOnlineNotCompletedAppointments);

					if($TotalOnlineAppoCounter != 0){
						$TotalOnlineNotCompletedPer = round((($NotCompletedOnlineAppoCounter * 100) / $TotalOnlineAppoCounter));
					}

					// get total cancelled appointments
					$TotalOnlineCancelledAppointments = Appointments::select('id')->where('user_id', $AdminId)->where('is_online_appointment',1)->where('is_cancelled',1)->whereDate('appointment_date','>=',$start_date)->whereDate('appointment_date','<=',$end_date)->where($locationAppo)->whereIn('id', function($query) use ($staff_id){
						$query->select('appointment_id')
						->from('appointment_services')
						->where('staff_user_id', $staff_id)
						->groupBy('appointment_id');
					})->orderBy('id', 'desc')->get()->toArray();

					$TotalOnlineCancelledPer = 0;
					$CancelledOnlineAppoCounter = count($TotalOnlineCancelledAppointments);

					if($TotalOnlineAppoCounter != 0){
						$TotalOnlineCancelledPer = round((($CancelledOnlineAppoCounter * 100) / $TotalOnlineAppoCounter));
					}

					// get total cancelled appointments
					$TotalOnlineNoshowdAppointments = Appointments::select('id')->where('user_id', $AdminId)->where('is_online_appointment',1)->where('is_noshow',1)->whereDate('appointment_date','>=',$start_date)->whereDate('appointment_date','<=',$end_date)->where($locationAppo)->whereIn('id', function($query) use ($staff_id){
						$query->select('appointment_id')
						->from('appointment_services')
						->where('staff_user_id', $staff_id)
						->groupBy('appointment_id');
					})->orderBy('id', 'desc')->get()->toArray();

					$TotalOnlineNoshowPer = 0;
					$NoshowOnlineAppoCounter = count($TotalOnlineNoshowdAppointments);

					if($TotalOnlineAppoCounter != 0){
						$TotalOnlineNoshowPer = round((($NoshowOnlineAppoCounter * 100) / $TotalOnlineAppoCounter));
					}
					
					$OnlineAppointmentPercentage = 0;
					
					if($TotalAppoCounter != 0 && $TotalOnlineAppoCounter != 0){
						$OnlineAppointmentPercentage = round((($TotalOnlineAppoCounter * 100) / $TotalAppoCounter));
					}
					
				} else {
					$staffWhere = array();
					
					// get total appointments
					$TotalAppointments = Appointments::select('id')->where('user_id', $AdminId)->whereDate('appointment_date','>=',$start_date)->whereDate('appointment_date','<=',$end_date)->where($locationAppo)->orderBy('id', 'desc')->get()->toArray();
					
					$TotalAppoCounter  = count($TotalAppointments);
					
					// get total completed appointments
					$TotalCompletedAppointments = Appointments::select('id')->where('user_id', $AdminId)->whereDate('appointment_date','>=',$start_date)->whereDate('appointment_date','<=',$end_date)->where($locationAppo)->where('appointment_status',4)->orderBy('id', 'desc')->get()->toArray();
					
					$TotalCompletedPer = 0;
					$CompletedAppoCounter = count($TotalCompletedAppointments);
					
					if($TotalAppoCounter != 0){
						$TotalCompletedPer = round((($CompletedAppoCounter * 100) / $TotalAppoCounter));
					}
					
					// get total not completed appointments
					$TotalNotCompletedAppointments = Appointments::select('id')->where('user_id', $AdminId)->whereDate('appointment_date','>=',$start_date)->whereDate('appointment_date','<=',$end_date)->where($locationAppo)->where('appointment_status','!=',4)->where('is_cancelled','!=',1)->where('is_noshow','!=',1)->orderBy('id', 'desc')->get()->toArray();
					
					$TotalNotCompletedPer = 0;
					$NotCompletedAppoCounter = count($TotalNotCompletedAppointments);
					
					if($TotalAppoCounter != 0){
						$TotalNotCompletedPer = round((($NotCompletedAppoCounter * 100) / $TotalAppoCounter));
					}
					
					// get total cancelled appointments
					$TotalCancelledAppointments = Appointments::select('id')->where('user_id', $AdminId)->whereDate('appointment_date','>=',$start_date)->whereDate('appointment_date','<=',$end_date)->where($locationAppo)->where('is_cancelled',1)->orderBy('id', 'desc')->get()->toArray();
					
					$TotalCancelledPer = 0;
					$CancelledAppoCounter = count($TotalCancelledAppointments);
					
					if($TotalAppoCounter != 0){
						$TotalCancelledPer = round((($CancelledAppoCounter * 100) / $TotalAppoCounter));
					}
					
					// get total cancelled appointments
					$TotalNoshowdAppointments = Appointments::select('id')->where('user_id', $AdminId)->whereDate('appointment_date','>=',$start_date)->whereDate('appointment_date','<=',$end_date)->where($locationAppo)->where('is_noshow',1)->orderBy('id', 'desc')->get()->toArray();
					
					$TotalNoshowPer = 0;
					$NoshowAppoCounter = count($TotalNoshowdAppointments);
					
					if($TotalAppoCounter != 0){
						$TotalNoshowPer = round((($NoshowAppoCounter * 100) / $TotalAppoCounter));
					}
					
					// get total sales
					$getInvoices = Invoice::select(DB::raw('COUNT(id) as total_invoices'),DB::raw('SUM(invoice.inovice_final_total) as total_sale'))->where('invoice.user_id', $AdminId)->where('invoice.invoice_status','!=','2')->whereDate('invoice.created_at','>=',$start_date)->whereDate('invoice.created_at','<=',$end_date)->where($locationWhere)->orderBy('invoice.id', 'desc')->get()->toArray();
					
					$TotalInvoices = ($getInvoices[0]['total_invoices']) ? $getInvoices[0]['total_invoices'] : 0;
					$TotalSale     = ($getInvoices[0]['total_sale']) ? $getInvoices[0]['total_sale'] : 0;
					
					$AvgTotalSale = 0;
					if($TotalSale != 0 && $TotalInvoices != 0){
						$AvgTotalSale = number_format(($TotalSale / $TotalInvoices),2);
					}
					
					// online appointment data
					$TotalOnlineAppointments = Appointments::select('id')->where('user_id', $AdminId)->where('is_online_appointment',1)->whereDate('appointment_date','>=',$start_date)->whereDate('appointment_date','<=',$end_date)->where($locationAppo)->orderBy('id', 'desc')->get()->toArray();
					$TotalOnlineAppoCounter = count($TotalOnlineAppointments);

					// get total completed appointments
					$TotalOnlineCompletedAppointments = Appointments::select('id')->where('user_id', $AdminId)->where('is_online_appointment',1)->where('appointment_status',4)->whereDate('appointment_date','>=',$start_date)->whereDate('appointment_date','<=',$end_date)->where($locationAppo)->orderBy('id', 'desc')->get()->toArray();

					$TotalOnlineCompletedPer = 0;
					$CompletedOnlineAppoCounter = count($TotalOnlineCompletedAppointments);

					if($TotalOnlineAppoCounter != 0){
						$TotalOnlineCompletedPer = round((($CompletedOnlineAppoCounter * 100) / $TotalOnlineAppoCounter));
					}
					 
					// get total not completed appointments
					$TotalOnlineNotCompletedAppointments = Appointments::select('id')->where('user_id', $AdminId)->where('appointment_status','!=',4)->where('is_online_appointment',1)->where('is_cancelled','!=',1)->where('is_noshow','!=',1)->whereDate('appointment_date','>=',$start_date)->whereDate('appointment_date','<=',$end_date)->where($locationAppo)->orderBy('id', 'desc')->get()->toArray();

					$TotalOnlineNotCompletedPer = 0;
					$NotCompletedOnlineAppoCounter = count($TotalOnlineNotCompletedAppointments);

					if($TotalOnlineAppoCounter != 0){
						$TotalOnlineNotCompletedPer = round((($NotCompletedOnlineAppoCounter * 100) / $TotalOnlineAppoCounter));
					}

					// get total cancelled appointments
					$TotalOnlineCancelledAppointments = Appointments::select('id')->where('user_id', $AdminId)->where('is_online_appointment',1)->where('is_cancelled',1)->whereDate('appointment_date','>=',$start_date)->whereDate('appointment_date','<=',$end_date)->where($locationAppo)->orderBy('id', 'desc')->get()->toArray();

					$TotalOnlineCancelledPer = 0;
					$CancelledOnlineAppoCounter = count($TotalOnlineCancelledAppointments);

					if($TotalOnlineAppoCounter != 0){
						$TotalOnlineCancelledPer = round((($CancelledOnlineAppoCounter * 100) / $TotalOnlineAppoCounter));
					}

					// get total cancelled appointments
					$TotalOnlineNoshowdAppointments = Appointments::select('id')->where('user_id', $AdminId)->where('is_online_appointment',1)->where('is_noshow',1)->whereDate('appointment_date','>=',$start_date)->whereDate('appointment_date','<=',$end_date)->where($locationAppo)->orderBy('id', 'desc')->get()->toArray();

					$TotalOnlineNoshowPer = 0;
					$NoshowOnlineAppoCounter = count($TotalOnlineNoshowdAppointments);

					if($TotalOnlineAppoCounter != 0){
						$TotalOnlineNoshowPer = round((($NoshowOnlineAppoCounter * 100) / $TotalOnlineAppoCounter));
					}
					
					$OnlineAppointmentPercentage = 0;
					
					if($TotalAppoCounter != 0 && $TotalOnlineAppoCounter != 0){
						$OnlineAppointmentPercentage = round((($TotalOnlineAppoCounter * 100) / $TotalAppoCounter));
					}
				}
				
				// get total service sale
				$ServiceTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_services'),DB::raw('SUM(invoice_items.item_price) as total_sale'))->join('invoice','invoice.id','=','invoice_items.invoice_id')->where(['invoice.user_id'=>$AdminId])->where('invoice_items.item_type','services')->where('invoice.invoice_status','!=','2')->whereDate('invoice.created_at','>=',$start_date)->whereDate('invoice.created_at','<=',$end_date)->where($locationWhere)->where($staffWhere)->orderBy('invoice_items.id', 'ASC')->get()->toArray();
				
				$TotalService = ($ServiceTotal[0]['total_services']) ? $ServiceTotal[0]['total_services'] : 0;
				$TotalServiceSale = ($ServiceTotal[0]['total_sale']) ? $ServiceTotal[0]['total_sale'] : 0;
				
				$TotalServiceSalePer = 0;
				if($TotalSale != 0){
					$TotalServiceSalePer = round(($TotalServiceSale * 100) / $TotalSale);
				}
				
				$AvgServiceSale = 0;
				if($TotalService != 0 && $TotalServiceSale != 0){
					$AvgServiceSale = number_format(($TotalServiceSale / $TotalService),2);
				}
				
				// get total product sales
				$PrductTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_products'),DB::raw('SUM(invoice_items.item_price) as total_sale'))->join('invoice','invoice.id','=','invoice_items.invoice_id')->where(['invoice.user_id'=>$AdminId])->where('invoice_items.item_type','product')->where('invoice.invoice_status','!=','2')->whereDate('invoice.created_at','>=',$start_date)->whereDate('invoice.created_at','<=',$end_date)->where($locationWhere)->where($staffWhere)->orderBy('invoice_items.id', 'ASC')->get()->toArray();
				
				$TotalProducts = ($PrductTotal[0]['total_products']) ? $PrductTotal[0]['total_products'] : 0;
				$TotalProductSale = ($PrductTotal[0]['total_sale']) ? $PrductTotal[0]['total_sale'] : 0;
				
				$TotalProductSalePer = 0;
				if($TotalSale != 0){
					$TotalProductSalePer = round(($TotalProductSale * 100) / $TotalSale);
				}
				
				$AvgProductSale = 0;
				if($TotalProducts != 0 && $TotalProductSale != 0){
					$AvgProductSale = number_format(($TotalProductSale / $TotalProducts),2);
				}
				
				// get late cancellation fees
				$TotalLateCancellationFees = 0;
				$TotalLateCancellationPer = 0;
				if($TotalSale != 0){
					$TotalLateCancellationPer = round(($TotalLateCancellationFees * 100) / $TotalSale);
				}
				
				// get no show fees
				$TotalNoShowFees = 0;
				$TotalNoShowFeesPer = 0;
				if($TotalSale != 0){
					$TotalNoShowFeesPer = round(($TotalNoShowFees * 100) / $TotalSale);
				}
				
			} else {
				if($location_id != ''){
					$locationAppo  = array('location_id' => $location_id);
					$locationWhere = array('invoice.location_id' => $location_id);
				} else {
					$locationAppo  = array();
					$locationWhere = array();
				}
				
				if($staff_id != ''){
					$StaffUserInfo = Staff::select('staff.id')->where(['staff_user_id'=>$staff_id])->get()->toArray();
					
					$STAFFID = (!empty($StaffUserInfo)) ? $StaffUserInfo[0]['id'] : 0;
					$staffWhere = array('invoice_items.staff_id' => $STAFFID);
					
					// get total appointments
					$TotalAppointments = Appointments::select('id')->where('user_id', $AdminId)->where($locationAppo)->whereIn('id', function($query) use ($staff_id){
						$query->select('appointment_id')
						->from('appointment_services')
						->where('staff_user_id', $staff_id)
						->groupBy('appointment_id');
					})->orderBy('id', 'desc')->get()->toArray();
					
					$TotalAppoCounter  = count($TotalAppointments);
					
					// get total completed appointments
					$TotalCompletedAppointments = Appointments::select('id')->where('user_id', $AdminId)->where($locationAppo)->where('appointment_status',4)->whereIn('id', function($query) use ($staff_id){
						$query->select('appointment_id')
						->from('appointment_services')
						->where('staff_user_id', $staff_id)
						->groupBy('appointment_id');
					})->orderBy('id', 'desc')->get()->toArray();
					
					$TotalCompletedPer = 0;
					$CompletedAppoCounter = count($TotalCompletedAppointments);
					
					if($TotalAppoCounter != 0){
						$TotalCompletedPer = round((($CompletedAppoCounter * 100) / $TotalAppoCounter));
					}
					
					// get total not completed appointments
					$TotalNotCompletedAppointments = Appointments::select('id')->where('user_id', $AdminId)->where($locationAppo)->where('appointment_status',0)->where('is_cancelled','!=',1)->where('is_noshow','!=',1)->whereIn('id', function($query) use ($staff_id){
						$query->select('appointment_id')
						->from('appointment_services')
						->where('staff_user_id', $staff_id)
						->groupBy('appointment_id');
					})->orderBy('id', 'desc')->get()->toArray();
					
					$TotalNotCompletedPer = 0;
					$NotCompletedAppoCounter = count($TotalNotCompletedAppointments);
					
					if($TotalAppoCounter != 0){
						$TotalNotCompletedPer = round((($NotCompletedAppoCounter * 100) / $TotalAppoCounter));
					}
					
					// get total cancelled appointments
					$TotalCancelledAppointments = Appointments::select('id')->where('user_id', $AdminId)->where($locationAppo)->where('is_cancelled',1)->whereIn('id', function($query) use ($staff_id){
						$query->select('appointment_id')
						->from('appointment_services')
						->where('staff_user_id', $staff_id)
						->groupBy('appointment_id');
					})->orderBy('id', 'desc')->get()->toArray();
					
					$TotalCancelledPer = 0;
					$CancelledAppoCounter = count($TotalCancelledAppointments);
					
					if($TotalAppoCounter != 0){
						$TotalCancelledPer = round((($CancelledAppoCounter * 100) / $TotalAppoCounter));
					}
					
					// get total cancelled appointments
					$TotalNoshowdAppointments = Appointments::select('id')->where('user_id', $AdminId)->where($locationAppo)->where('is_noshow',1)->whereIn('id', function($query) use ($staff_id){
						$query->select('appointment_id')
						->from('appointment_services')
						->where('staff_user_id', $staff_id)
						->groupBy('appointment_id');
					})->orderBy('id', 'desc')->get()->toArray();
					
					$TotalNoshowPer = 0;
					$NoshowAppoCounter = count($TotalNoshowdAppointments);
					
					if($TotalAppoCounter != 0){
						$TotalNoshowPer = round((($NoshowAppoCounter * 100) / $TotalAppoCounter));
					}
					
					// get total sales
					$getInvoices = Invoice::select(DB::raw('COUNT(id) as total_invoices'),DB::raw('SUM(invoice.inovice_final_total) as total_sale'))->where('invoice.user_id', $AdminId)->where('invoice.invoice_status','!=','2')->where($locationWhere)->whereIn('invoice.id', function($query) use ($STAFFID){
						$query->select('invoice_id')
						->from('invoice_items')
						->where('staff_id',$STAFFID)
						->groupBy('invoice_id');
					})->orderBy('invoice.id', 'desc')->get()->toArray();
					
					$TotalInvoices = ($getInvoices[0]['total_invoices']) ? $getInvoices[0]['total_invoices'] : 0;
					$TotalSale     = ($getInvoices[0]['total_sale']) ? $getInvoices[0]['total_sale'] : 0;
					
					$AvgTotalSale = 0;
					if($TotalSale != 0 && $TotalInvoices != 0){
						$AvgTotalSale = number_format(($TotalSale / $TotalInvoices),2);
					}
					
					// online appointment data
					$TotalOnlineAppointments = Appointments::select('id')->where('user_id', $AdminId)->where('is_online_appointment',1)->where($locationAppo)->whereIn('id', function($query) use ($staff_id){
						$query->select('appointment_id')
						->from('appointment_services')
						->where('staff_user_id', $staff_id)
						->groupBy('appointment_id');
					})->orderBy('id', 'desc')->get()->toArray();
					$TotalOnlineAppoCounter = count($TotalOnlineAppointments);

					// get total completed appointments
					$TotalOnlineCompletedAppointments = Appointments::select('id')->where('user_id', $AdminId)->where('is_online_appointment',1)->where('appointment_status',4)->where($locationAppo)->whereIn('id', function($query) use ($staff_id){
						$query->select('appointment_id')
						->from('appointment_services')
						->where('staff_user_id', $staff_id)
						->groupBy('appointment_id');
					})->orderBy('id', 'desc')->get()->toArray();

					$TotalOnlineCompletedPer = 0;
					$CompletedOnlineAppoCounter = count($TotalOnlineCompletedAppointments);

					if($TotalOnlineAppoCounter != 0){
						$TotalOnlineCompletedPer = round((($CompletedOnlineAppoCounter * 100) / $TotalOnlineAppoCounter));
					}
					 
					// get total not completed appointments
					$TotalOnlineNotCompletedAppointments = Appointments::select('id')->where('user_id', $AdminId)->where('appointment_status','!=',4)->where('is_online_appointment',1)->where('is_cancelled','!=',1)->where('is_noshow','!=',1)->where($locationAppo)->whereIn('id', function($query) use ($staff_id){
						$query->select('appointment_id')
						->from('appointment_services')
						->where('staff_user_id', $staff_id)
						->groupBy('appointment_id');
					})->orderBy('id', 'desc')->get()->toArray();

					$TotalOnlineNotCompletedPer = 0;
					$NotCompletedOnlineAppoCounter = count($TotalOnlineNotCompletedAppointments);

					if($TotalOnlineAppoCounter != 0){
						$TotalOnlineNotCompletedPer = round((($NotCompletedOnlineAppoCounter * 100) / $TotalOnlineAppoCounter));
					}

					// get total cancelled appointments
					$TotalOnlineCancelledAppointments = Appointments::select('id')->where('user_id', $AdminId)->where('is_online_appointment',1)->where('is_cancelled',1)->where($locationAppo)->whereIn('id', function($query) use ($staff_id){
						$query->select('appointment_id')
						->from('appointment_services')
						->where('staff_user_id', $staff_id)
						->groupBy('appointment_id');
					})->orderBy('id', 'desc')->get()->toArray();

					$TotalOnlineCancelledPer = 0;
					$CancelledOnlineAppoCounter = count($TotalOnlineCancelledAppointments);

					if($TotalOnlineAppoCounter != 0){
						$TotalOnlineCancelledPer = round((($CancelledOnlineAppoCounter * 100) / $TotalOnlineAppoCounter));
					}

					// get total cancelled appointments
					$TotalOnlineNoshowdAppointments = Appointments::select('id')->where('user_id', $AdminId)->where('is_online_appointment',1)->where('is_noshow',1)->where($locationAppo)->whereIn('id', function($query) use ($staff_id){
						$query->select('appointment_id')
						->from('appointment_services')
						->where('staff_user_id', $staff_id)
						->groupBy('appointment_id');
					})->orderBy('id', 'desc')->get()->toArray();

					$TotalOnlineNoshowPer = 0;
					$NoshowOnlineAppoCounter = count($TotalOnlineNoshowdAppointments);

					if($TotalOnlineAppoCounter != 0){
						$TotalOnlineNoshowPer = round((($NoshowOnlineAppoCounter * 100) / $TotalOnlineAppoCounter));
					}
					
					$OnlineAppointmentPercentage = 0;
					
					if($TotalAppoCounter != 0 && $TotalOnlineAppoCounter != 0){
						$OnlineAppointmentPercentage = round((($TotalOnlineAppoCounter * 100) / $TotalAppoCounter));
					}
					
				} else {
					$staffWhere = array();
					
					// get total appointments
					$TotalAppointments = Appointments::select('id')->where('user_id', $AdminId)->where($locationAppo)->orderBy('id', 'desc')->get()->toArray();
					
					$TotalAppoCounter  = count($TotalAppointments);
					
					// get total completed appointments
					$TotalCompletedAppointments = Appointments::select('id')->where('user_id', $AdminId)->where($locationAppo)->where('appointment_status',4)->orderBy('id', 'desc')->get()->toArray();
					
					$TotalCompletedPer = 0;
					$CompletedAppoCounter = count($TotalCompletedAppointments);
					
					if($TotalAppoCounter != 0){
						$TotalCompletedPer = round((($CompletedAppoCounter * 100) / $TotalAppoCounter));
					}
					
					// get total not completed appointments
					$TotalNotCompletedAppointments = Appointments::select('id')->where('user_id', $AdminId)->where($locationAppo)->where('appointment_status',0)->where('is_cancelled','!=',1)->where('is_noshow','!=',1)->orderBy('id', 'desc')->get()->toArray();
					
					$TotalNotCompletedPer = 0;
					$NotCompletedAppoCounter = count($TotalNotCompletedAppointments);
					
					if($TotalAppoCounter != 0){
						$TotalNotCompletedPer = round((($NotCompletedAppoCounter * 100) / $TotalAppoCounter));
					}
					
					// get total cancelled appointments
					$TotalCancelledAppointments = Appointments::select('id')->where('user_id', $AdminId)->where($locationAppo)->where('is_cancelled',1)->orderBy('id', 'desc')->get()->toArray();
					
					$TotalCancelledPer = 0;
					$CancelledAppoCounter = count($TotalCancelledAppointments);
					
					if($TotalAppoCounter != 0){
						$TotalCancelledPer = round((($CancelledAppoCounter * 100) / $TotalAppoCounter));
					}
					
					// get total cancelled appointments
					$TotalNoshowdAppointments = Appointments::select('id')->where('user_id', $AdminId)->where($locationAppo)->where('is_noshow',1)->orderBy('id', 'desc')->get()->toArray();
					
					$TotalNoshowPer = 0;
					$NoshowAppoCounter = count($TotalNoshowdAppointments);
					
					if($TotalAppoCounter != 0){
						$TotalNoshowPer = round((($NoshowAppoCounter * 100) / $TotalAppoCounter));
					}
					
					// get total sales
					$getInvoices = Invoice::select(DB::raw('COUNT(id) as total_invoices'),DB::raw('SUM(invoice.inovice_final_total) as total_sale'))->where('invoice.user_id', $AdminId)->where('invoice.invoice_status','!=','2')->where($locationWhere)->orderBy('invoice.id', 'desc')->get()->toArray();
					
					$TotalInvoices = ($getInvoices[0]['total_invoices']) ? $getInvoices[0]['total_invoices'] : 0;
					$TotalSale     = ($getInvoices[0]['total_sale']) ? $getInvoices[0]['total_sale'] : 0;
					
					$AvgTotalSale = 0;
					if($TotalSale != 0 && $TotalInvoices != 0){
						$AvgTotalSale = number_format(($TotalSale / $TotalInvoices),2);
					}
					
					// online appointment data
					$TotalOnlineAppointments = Appointments::select('id')->where('user_id', $AdminId)->where('is_online_appointment',1)->where($locationAppo)->orderBy('id', 'desc')->get()->toArray();
					$TotalOnlineAppoCounter = count($TotalOnlineAppointments);

					// get total completed appointments
					$TotalOnlineCompletedAppointments = Appointments::select('id')->where('user_id', $AdminId)->where('is_online_appointment',1)->where('appointment_status',4)->where($locationAppo)->orderBy('id', 'desc')->get()->toArray();

					$TotalOnlineCompletedPer = 0;
					$CompletedOnlineAppoCounter = count($TotalOnlineCompletedAppointments);

					if($TotalOnlineAppoCounter != 0){
						$TotalOnlineCompletedPer = round((($CompletedOnlineAppoCounter * 100) / $TotalOnlineAppoCounter));
					}
					 
					// get total not completed appointments
					$TotalOnlineNotCompletedAppointments = Appointments::select('id')->where('user_id', $AdminId)->where('appointment_status','!=',4)->where('is_online_appointment',1)->where('is_cancelled','!=',1)->where('is_noshow','!=',1)->where($locationAppo)->orderBy('id', 'desc')->get()->toArray();

					$TotalOnlineNotCompletedPer = 0;
					$NotCompletedOnlineAppoCounter = count($TotalOnlineNotCompletedAppointments);

					if($TotalOnlineAppoCounter != 0){
						$TotalOnlineNotCompletedPer = round((($NotCompletedOnlineAppoCounter * 100) / $TotalOnlineAppoCounter));
					}

					// get total cancelled appointments
					$TotalOnlineCancelledAppointments = Appointments::select('id')->where('user_id', $AdminId)->where('is_online_appointment',1)->where('is_cancelled',1)->where($locationAppo)->orderBy('id', 'desc')->get()->toArray();

					$TotalOnlineCancelledPer = 0;
					$CancelledOnlineAppoCounter = count($TotalOnlineCancelledAppointments);

					if($TotalOnlineAppoCounter != 0){
						$TotalOnlineCancelledPer = round((($CancelledOnlineAppoCounter * 100) / $TotalOnlineAppoCounter));
					}

					// get total cancelled appointments
					$TotalOnlineNoshowdAppointments = Appointments::select('id')->where('user_id', $AdminId)->where('is_online_appointment',1)->where('is_noshow',1)->where($locationAppo)->orderBy('id', 'desc')->get()->toArray();

					$TotalOnlineNoshowPer = 0;
					$NoshowOnlineAppoCounter = count($TotalOnlineNoshowdAppointments);

					if($TotalOnlineAppoCounter != 0){
						$TotalOnlineNoshowPer = round((($NoshowOnlineAppoCounter * 100) / $TotalOnlineAppoCounter));
					}
					
					$OnlineAppointmentPercentage = 0;
					
					if($TotalAppoCounter != 0 && $TotalOnlineAppoCounter != 0){
						$OnlineAppointmentPercentage = round((($TotalOnlineAppoCounter * 100) / $TotalAppoCounter));
					}
				}
				
				// get total service sale
				$ServiceTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_services'),DB::raw('SUM(invoice_items.item_price) as total_sale'))->join('invoice','invoice.id','=','invoice_items.invoice_id')->where(['invoice.user_id'=>$AdminId])->where('invoice_items.item_type','services')->where('invoice.invoice_status','!=','2')->where($locationWhere)->where($staffWhere)->orderBy('invoice_items.id', 'ASC')->get()->toArray();
				
				$TotalService = ($ServiceTotal[0]['total_services']) ? $ServiceTotal[0]['total_services'] : 0;
				$TotalServiceSale = ($ServiceTotal[0]['total_sale']) ? $ServiceTotal[0]['total_sale'] : 0;
				
				$TotalServiceSalePer = 0;
				if($TotalSale != 0){
					$TotalServiceSalePer = round(($TotalServiceSale * 100) / $TotalSale);
				}
				
				$AvgServiceSale = 0;
				if($TotalService != 0 && $TotalServiceSale != 0){
					$AvgServiceSale = number_format(($TotalServiceSale / $TotalService),2);
				}
				
				// get total product sales
				$PrductTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_products'),DB::raw('SUM(invoice_items.item_price) as total_sale'))->join('invoice','invoice.id','=','invoice_items.invoice_id')->where(['invoice.user_id'=>$AdminId])->where('invoice_items.item_type','product')->where('invoice.invoice_status','!=','2')->where($locationWhere)->where($staffWhere)->orderBy('invoice_items.id', 'ASC')->get()->toArray();
				
				$TotalProducts = ($PrductTotal[0]['total_products']) ? $PrductTotal[0]['total_products'] : 0;
				$TotalProductSale = ($PrductTotal[0]['total_sale']) ? $PrductTotal[0]['total_sale'] : 0;
				
				$TotalProductSalePer = 0;
				if($TotalSale != 0){
					$TotalProductSalePer = round(($TotalProductSale * 100) / $TotalSale);
				}
				
				$AvgProductSale = 0;
				if($TotalProducts != 0 && $TotalProductSale != 0){
					$AvgProductSale = number_format(($TotalProductSale / $TotalProducts),2);
				}
				
				// get late cancellation fees
				$TotalLateCancellationFees = 0;
				$TotalLateCancellationPer = 0;
				if($TotalSale != 0){
					$TotalLateCancellationPer = round(($TotalLateCancellationFees * 100) / $TotalSale);
				}
				
				// get no show fees
				$TotalNoShowFees = 0;
				$TotalNoShowFeesPer = 0;
				if($TotalSale != 0){
					$TotalNoShowFeesPer = round(($TotalNoShowFees * 100) / $TotalSale);
				}
			}
			
			$returnData['TotalAppoCounter']        = $TotalAppoCounter;
			$returnData['CompletedAppoCounter']      = $CompletedAppoCounter;
			$returnData['TotalCompletedPer']         = $TotalCompletedPer;
			$returnData['NotCompletedAppoCounter']   = $NotCompletedAppoCounter;
			$returnData['TotalNotCompletedPer']      = $TotalNotCompletedPer;
			$returnData['CancelledAppoCounter']      = $CancelledAppoCounter;
			$returnData['TotalCancelledPer']         = $TotalCancelledPer;
			$returnData['NoshowAppoCounter']         = $NoshowAppoCounter;
			$returnData['TotalNoshowPer']            = $TotalNoshowPer;
												     
			$returnData['TotalSale']                 = $TotalSale;
			$returnData['TotalServiceSale']          = $TotalServiceSale;
			$returnData['TotalServiceSalePer']       = $TotalServiceSalePer;
			$returnData['TotalProductSale']          = $TotalProductSale;
			$returnData['TotalProductSalePer']       = $TotalProductSalePer;
			$returnData['TotalLateCancellationFees'] = $TotalLateCancellationFees;
			$returnData['TotalLateCancellationPer']  = $TotalLateCancellationPer;
			$returnData['TotalNoShowFees']           = $TotalNoShowFees;
			$returnData['TotalNoShowFeesPer']        = $TotalNoShowFeesPer;
			
			$returnData['AvgTotalSale']              = $AvgTotalSale;
			$returnData['TotalInvoices']             = $TotalInvoices;
			$returnData['AvgServiceSale']            = $AvgServiceSale;
			$returnData['AvgProductSale']            = $AvgProductSale;
			
			$returnData['TotalOnlineAppoCounter']        = $TotalOnlineAppoCounter;
			$returnData['CompletedOnlineAppoCounter']    = $CompletedOnlineAppoCounter;
			$returnData['NotCompletedOnlineAppoCounter'] = $NotCompletedOnlineAppoCounter;
			$returnData['CancelledOnlineAppoCounter']    = $CancelledOnlineAppoCounter;
			$returnData['NoshowOnlineAppoCounter']       = $NoshowOnlineAppoCounter;
			$returnData['TotalOnlineCompletedPer']       = $TotalOnlineCompletedPer;
			$returnData['TotalOnlineNotCompletedPer']    = $TotalOnlineNotCompletedPer;
			$returnData['TotalOnlineCancelledPer']       = $TotalOnlineCancelledPer;
			$returnData['TotalOnlineNoshowPer']          = $TotalOnlineNoshowPer;
			$returnData['OnlineAppointmentPercentage']   = $OnlineAppointmentPercentage;
			
			$data["status"]     = true;
			$data["returnData"] = $returnData;
			return JsonReturn::success($data);
		}
	}*/
	
	public function reportList(){
		return view('analytics.reportlist');
	}
	// public function paymentsSummary(){
	// 	return view('analytics.payment_summary');
	// }
	
	public function financesSummary()
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
		
		return view('analytics.finances_summary',compact('Locations','Staff'));
	}

	public function getFinancesSummary(Request $request)
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

		$start_date = !empty($request->start_date) ? date('Y-m-d', strtotime($request->start_date)) : NULL;
		$end_date = !empty($request->end_date) ? date('Y-m-d', strtotime($request->end_date)) : NULL;
		$location_id = !empty($request->location_id) ? $request->location_id : NULL;

		$whereArray = [];

		if(!empty($start_date)) {
			$whereArray[] = ['invoice.payment_date', '>=', $start_date];
		}
		if(!empty($end_date)) {
			$whereArray[] = ['invoice.payment_date', '<=', $end_date];
		}
		if(!empty($location_id)) {
			$whereArray[] = ['invoice.location_id', '=', $location_id];
		}

		$salesData = $this->financeSummarySalesData($whereArray);
		$paymentData = $this->financeSummaryPaymentData($whereArray);
		$totalTips = $this->getTipsCollected($request);
		$totalTips = $totalTips['total'];

		$voucherSale = InvoiceItems::select(DB::raw("SUM(invoice_items.quantity * invoice_items.item_price) voucher_sale"))->leftJoin('invoice', 'invoice.id', 'invoice_items.invoice_id')->where('invoice.user_id', $AdminId)->where('invoice.invoice_status', 1)->where($whereArray)->first();

		$voucherSaleRefund = InvoiceItems::select(DB::raw("SUM(invoice_items.quantity * invoice_items.item_price) voucher_sale"))->leftJoin('invoice', 'invoice.id', 'invoice_items.invoice_id')->where('invoice.user_id', $AdminId)->where('invoice.invoice_status', 2)->where($whereArray)->first();

		$invoiceIdArray = Invoice::where('invoice.user_id', $AdminId)->whereIn('invoice.invoice_status', [1,2])->where($whereArray)->pluck("invoice.id as invoice_id");
		// dd($invoiceIdArray);

		if(!empty($invoiceIdArray)){
			$voucherRedemption = InvoiceVoucher::select(DB::raw("SUM(voucher_amount) as voucher_redemption"))->whereIn("invoice_id", $invoiceIdArray)->first();
			$voucherRedemption = $voucherRedemption->voucher_redemption;
		}else{
			$voucherRedemption = null;
		}

		// dd($voucherRedemption);

		$totalVoucherSale = number_format($voucherSale->voucher_sale - $voucherSaleRefund->voucher_sale, 2);

		//Voucher Outstanding Balance
		$total_voucher_sale = Invoice::select(DB::raw("SUM(sold_voucher.total_value) as total_voucher_sale"))->leftJoin("sold_voucher", "sold_voucher.invoice_id", "invoice.id")->whereIn("invoice.invoice_status", [0, 1])->where("invoice.user_id", $AdminId)->first();

		$total_redeemed = InvoiceVoucher::select(DB::raw("SUM(invoice_voucher.voucher_amount) as total_redeemed"))->leftJoin("invoice", "invoice.id", "invoice_voucher.invoice_id")->whereIn("invoice.invoice_status", [0, 1])->where("invoice.user_id", $AdminId)->first();

		$total_expired_voucher_amount = SoldVoucher::select(DB::raw("SUM(sold_voucher.total_value - sold_voucher.redeemed) as total_expired_voucher_amount"))->where("sold_voucher.end_date", "<", date('Y-m-d'))->where("sold_voucher.user_id", $AdminId)->first();

		// dd($total_redeemed);

		$voucherOutstandingBalance = number_format($total_voucher_sale->total_voucher_sale - $total_redeemed->total_redeemed - $total_expired_voucher_amount->total_expired_voucher_amount , 2);



		$data = [];
		$data["status"] = true;
		$data["message"] = 'Data fetched successfully.';
		$data["data"] = array_merge($salesData, $paymentData);
		$data["totalVoucherSale"] = $totalVoucherSale;
		$data["voucherRedemption"] = number_format($voucherRedemption, 2);
		$data["totalTip"] = $totalTips;
		if($voucherOutstandingBalance < 0){
			$data["totalVoucherOutstanding"] = "-CA $". number_format(abs($voucherOutstandingBalance), 2);
		}else{
			$data["totalVoucherOutstanding"] = "CA $". number_format($voucherOutstandingBalance, 2);
		}
		// dd($data);
		return JsonReturn::success($data);
	}

	function financeSummarySalesData($whereArray)
	{
		$invoices = Invoice::leftJoin('invoice_items', 'invoice_items.invoice_id', 'invoice.id')
					->where('invoice.invoice_status', '!=', '3')
					->where('invoice.invoice_status', '!=', '2')
					->where($whereArray);

		$grossTotal = $invoices->sum(DB::raw('quantity * invoice_items.item_og_price'));
		$totalDiscount = $invoices->sum(DB::raw('quantity * (invoice_items.item_og_price - invoice_items.item_price)'));

		$totalRefunds = Invoice::where('invoice_status', '2')
						->where($whereArray)
						->sum('inovice_final_total');

		$netSales = $grossTotal - $totalDiscount - $totalRefunds;
		$totalTaxes = $invoices->sum(DB::raw('invoice.invoice_total - invoice.invoice_sub_total'));
		// $totalSales = $netSales + $totalTaxes;
		$totalSales = $invoices->sum('invoice_total') - $totalRefunds;

		return [
			'grossTotal' => number_format($grossTotal, 2),
			'totalDiscount' => number_format($totalDiscount, 2),
			'totalRefunds' => number_format($totalRefunds, 2),
			'netSales' => number_format($netSales, 2),
			'totalTaxes' => number_format($totalTaxes, 2),
			'totalSales' => number_format($totalSales, 2)
		];
	}

	function financeSummaryPaymentData($whereArray)
	{
		$invoices = Invoice::leftJoin('payment_type', 'payment_type.id', 'invoice.payment_id')
					->where($whereArray)
					->groupBy('invoice.payment_id');

		$paymentWiseTotal = $invoices->where('invoice.invoice_status', '!=', '3')
							->where('invoice.invoice_status', '!=', '2')
							->select(DB::raw('SUM(invoice.inovice_final_total) as total'), 'invoice.payment_id', 'payment_type.payment_type')
							->get()
							->toArray();

		$paymentWiseRefundTotal = $invoices->where('invoice.invoice_status', '2')
								->select(DB::raw('SUM(invoice.inovice_final_total) as total'), 'invoice.payment_id', 'payment_type.payment_type')
								->get()
								->toArray();

		if(!empty($paymentWiseTotal)) {
			foreach($paymentWiseTotal as $key => $value) {
				$refundKey = array_search($value['payment_id'], array_column($paymentWiseRefundTotal, 'payment_id'));

				if(isset($paymentWiseRefundTotal[ $refundKey ])) {
					$paymentWiseTotal[ $key ]['total'] -= $paymentWiseRefundTotal[ $refundKey ]['total'];
				}

				$paymentWiseTotal[ $key ]['total'] = $paymentWiseTotal[ $key ]['total'];
			}
		}

		return [
			'paymentWiseTotal' => $paymentWiseTotal
		];
	}

	public function getFinancesSummaryPDF(Request $request){
		$getFinancesSummary = $this->getFinancesSummary($request);

		$location_id = (!empty($request->location_id)) ? $request->location_id : null;
		$start_date = (!empty($request->start_date)) ? $request->start_date : null;
		$end_date = (!empty($request->end_date)) ? $request->end_date : null;

		if(!empty($location_id)){
			$temp = Location::select("location_name as location")->where('id',$location_id)->first();

			$location_name = $temp->location;
		}else{
			$location_name = "All Locations";
		}

		// dd($getFinancesSummary);

		return PDF::loadView('pdfTemplates.financesSummaryPDFReport', compact('getFinancesSummary','location_name', 'start_date', 'end_date'))->setPaper('a4')->download("finances_summary.pdf");
	}
	
	public function getFinancesSummaryCSV(Request $request){
		$getFinancesSummary = $this->getFinancesSummary($request);

		// dd($data);
		return Excel::download(new financesSummaryCSVReport($getFinancesSummary), 'finances_summary.csv');
	}

	public function getFinancesSummaryExcel(Request $request){
		$getFinancesSummary = $this->getFinancesSummary($request);

		return Excel::download(new financesSummaryExcelReport($getFinancesSummary), 'finances_summary.xlsx');
	}
	
	public function paymentsSummary(){
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
		
		return view('analytics.payment_summary',compact('Locations','Staff'));
		// return view('analytics.payment_summary');
	}
	public function getPaymentSummary(Request $request){
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

		$start_date = (!empty($request->start_date)) ? date('Y-m-d', strtotime($request->start_date)) : NULL;
		$end_date = (!empty($request->end_date)) ? date('Y-m-d', strtotime($request->end_date)) : NULL;
		$location_id = (!empty($request->location_id)) ? $request->location_id : NULL;
		$staff_id = (!empty($request->staff_id)) ? $request->staff_id : NULL;

		// echo "StartDate :- ". $start_date . "<br> EndDate :- ". $end_date;

		$whereArray = [];

		if(!empty($start_date)) {
			$whereArray[] = ['invoice.payment_date', '>=', $start_date];
		}
		if(!empty($end_date)) {
			$whereArray[] = ['invoice.payment_date', '<=', $end_date];
		}
		if(!empty($location_id)) {
			$whereArray[] = ['invoice.location_id', '=', $location_id];
		}
		if(!empty($staff_id)) {
			$whereArray[] = ['invoice.created_by', '=', $staff_id];
		}
		$invoiceId = Invoice::where('user_id',$AdminId)
		->where($whereArray)
		->where('invoice_status', '1')->pluck('id');

		// echo "Invoice Id's :- <pre>"; print_r($invoiceId);

		// print_r($whereArray);

		// $paymentSummaryData = Invoice::where('user_id',$AdminId)
		// ->where($whereArray)
		// ->where('invoice_status','1')
		// ->get()->toArray();

		$netPaymentSummaryData = Invoice::join('payment_type','payment_type.id','invoice.payment_id')
		->select(DB::raw("SUM(invoice.inovice_final_total) as gross_total"),"payment_type.payment_type","payment_type.id",DB::raw("COUNT(invoice.inovice_final_total) as row_count"))
		->where('invoice.user_id',$AdminId)
		->where($whereArray)
		->where('invoice.invoice_status','1')
		->groupBy('invoice.payment_id')->get()->toArray();

		$completedPaymentSummaryData = $netPaymentSummaryData;
		
		$TotalPaymentSummaryData = Invoice::select(DB::raw("SUM(inovice_final_total) as gross_total"), 'invoice.*',DB::raw("COUNT(inovice_final_total) as row_count"))
		->where('user_id',$AdminId)
		->where($whereArray)
		->where('invoice_status','1')->first();
		$TotalPaymentSummaryTransaction = $TotalPaymentSummaryData->row_count;

		$refundPaymentSummaryData = Invoice::select('invoice.*')
		->where('invoice.user_id',$AdminId)
		->where($whereArray)
		->where('invoice.invoice_status','2')
		->whereIn('invoice.original_invoice_id',$invoiceId)->get()->toArray();
		$refundPaymentSummaryTransaction = 0;
		$totalRefundPaymentSummaryData = 0;

		// echo "<pre> Refund Payment Data :-"; print_r($refundPaymentSummaryData);

		// echo "<pre>"; print_r($netPaymentSummaryData);

		if(!empty($refundPaymentSummaryData)){
			foreach($refundPaymentSummaryData as $refundPaymentSummaryDataKey => $refundPaymentSummaryDataValue){
				$refundPaymentSummaryTransaction += 1;
				$totalRefundPaymentSummaryData += $refundPaymentSummaryDataValue['inovice_final_total'];
				foreach($netPaymentSummaryData as $netPaymentSummaryDatakey => $netPaymentSummaryDataValue){
					if($netPaymentSummaryDataValue['id'] == $refundPaymentSummaryDataValue['payment_id']){
						$netPaymentSummaryData[$netPaymentSummaryDatakey]['gross_total'] -= $refundPaymentSummaryDataValue['inovice_final_total'];
						$completedPaymentSummaryData[$netPaymentSummaryDatakey]['row_count'] += 1;
					}
				}
			}
		}

		$TotalPaymentSummaryTransaction += $refundPaymentSummaryTransaction;

		$LocationName = Location::select('location_name')->where('id',$location_id)->first();

		// echo "Invoice Gross Total :- ". $temp;

		// $tra = 0;
		// foreach($paymentSummaryDataDemo as $p){
		// 	$tra += 1; 
		// }

		// echo "<pre>";print_r($refundPaymentSummaryData);
		// echo "-----------------------------------------------";
		// echo "<pre>"; print_r($completedPaymentSummaryData);
		// echo "<br> Transactions :- ". $tra;
		// die;

		// $grossTotal = 0;
		// $refunded = 0;
		// $netSales = 0;
		// $transactions = 0;
		// $cashTransaction = 0;
		// $otherTransactions = 0;
		// // $paymentType = paymentType::where

		// if(!empty($paymentSummaryData)){
		// 	foreach($paymentSummaryData as $paymentSummaryDataKey => $paymentSummaryDataValue){
		// 		$isVoid = Invoice::where('original_invoice_id', $paymentSummaryDataValue['id'])
		// 		->where('invoice_status','3')->first();
		// 		if($isVoid){

		// 		}else{

		// 		}
		// 	}
		// }
		// dd($netPaymentSummaryData, $completedPaymentSummaryData, $TotalPaymentSummaryData, $refundPaymentSummaryData, $TotalPaymentSummaryTransaction);
		$data = [];
		$data["status"] = true;
		$data["message"] = 'Data fetched successfully.';
		$data["data"] = array('netPaymentSummaryData' => $netPaymentSummaryData, 'completedPaymentSummaryData' => $completedPaymentSummaryData, 'TotalPaymentSummaryData' => $TotalPaymentSummaryData, 'totalRefundPaymentSummaryData' => $totalRefundPaymentSummaryData, 'TotalPaymentSummaryTransaction' => $TotalPaymentSummaryTransaction, 'LocationName' => $LocationName);
		return JsonReturn::success($data);
		
	}

	public function getPaymentSummaryPDF(Request $request){
		if($request->ajax()){
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

			$start_date = (!empty($request->start_date)) ? date('Y-m-d', strtotime($request->start_date)) : NULL;
			$end_date = (!empty($request->end_date)) ? date('Y-m-d', strtotime($request->end_date)) : NULL;
			$location_id = (!empty($request->location_id)) ? $request->location_id : NULL;
			$staff_id = (!empty($request->staff_id)) ? $request->staff_id : NULL;

			$whereArray = [];

			if(!empty($start_date)) {
				$whereArray[] = ['invoice.payment_date', '>=', $start_date];
			}
			if(!empty($end_date)) {
				$whereArray[] = ['invoice.payment_date', '<=', $end_date];
			}
			if(!empty($location_id)) {
				$whereArray[] = ['invoice.location_id', '=', $location_id];
			}
			if(!empty($staff_id)) {
				$whereArray[] = ['invoice.created_by', '=', $staff_id];
			}
			$invoiceId = Invoice::where('user_id',$AdminId)
			->where($whereArray)
			->where('invoice_status', '1')->pluck('id');

			$netPaymentSummaryData = Invoice::join('payment_type','payment_type.id','invoice.payment_id')
			->select(DB::raw("SUM(invoice.inovice_final_total) as gross_total"),"payment_type.payment_type","payment_type.id",DB::raw("COUNT(invoice.inovice_final_total) as row_count"))
			->where('invoice.user_id',$AdminId)
			->where($whereArray)
			->where('invoice.invoice_status','1')
			->groupBy('invoice.payment_id')->get()->toArray();

			$completedPaymentSummaryData = $netPaymentSummaryData;
			
			$TotalPaymentSummaryData = Invoice::select(DB::raw("SUM(inovice_final_total) as gross_total"), 'invoice.*',DB::raw("COUNT(inovice_final_total) as row_count"))
			->where('user_id',$AdminId)
			->where($whereArray)
			->where('invoice_status','1')->first();
			$TotalPaymentSummaryTransaction = $TotalPaymentSummaryData->row_count;

			$refundPaymentSummaryData = Invoice::select('invoice.*')
			->where('invoice.user_id',$AdminId)
			->where($whereArray)
			->where('invoice.invoice_status','2')
			->whereIn('invoice.original_invoice_id',$invoiceId)->get()->toArray();
			$refundPaymentSummaryTransaction = 0;
			$totalRefundPaymentSummaryData = 0;

			if(!empty($refundPaymentSummaryData)){
				foreach($refundPaymentSummaryData as $refundPaymentSummaryDataKey => $refundPaymentSummaryDataValue){
					$refundPaymentSummaryTransaction += 1;
					$totalRefundPaymentSummaryData += $refundPaymentSummaryDataValue['inovice_final_total'];
					foreach($netPaymentSummaryData as $netPaymentSummaryDatakey => $netPaymentSummaryDataValue){
						if($netPaymentSummaryDataValue['id'] == $refundPaymentSummaryDataValue['payment_id']){
							$netPaymentSummaryData[$netPaymentSummaryDatakey]['gross_total'] -= $refundPaymentSummaryDataValue['inovice_final_total'];
							$completedPaymentSummaryData[$netPaymentSummaryDatakey]['row_count'] += 1;
						}
					}
				}
			}

			$TotalPaymentSummaryTransaction += $refundPaymentSummaryTransaction;

			$LocationName = Location::select('location_name')->where('id',$location_id)->first();

			$data = [];
			$data["status"] = true;
			$data["message"] = 'Data fetched successfully.';
			$data['LocationName'] = $LocationName;
			$data['netPaymentSummaryData']  = $netPaymentSummaryData;
			$data['completedPaymentSummaryData'] = $completedPaymentSummaryData;
			$data['TotalPaymentSummaryData'] = $TotalPaymentSummaryData;
			$data['totalRefundPaymentSummaryData'] = $totalRefundPaymentSummaryData;
			$data['TotalPaymentSummaryTransaction'] = $TotalPaymentSummaryTransaction;

			if($start_date != '' && $end_date != ''){
				$FileName = 'payment_summary_'.$start_date.'_'.$end_date.'.pdf';
			} else {
				$FileName = 'payment_summary.pdf';
			}

			return PDF::loadView('pdfTemplates.paymentSummaryPDFReport',$data)->setPaper('a4')->download($FileName);
		}
	}
	
	public function getPaymentSummaryCSV(Request $request){
		$start_date = !empty($request->start_date) ? date('Y-m-d', strtotime($request->start_date)) : NULL;
		$end_date = !empty($request->end_date) ? date('Y-m-d', strtotime($request->end_date)) : NULL;
		$location_id = !empty($request->location_id) ? $request->location_id : NULL;

		return Excel::download(new paymentSummaryCSVReport($start_date, $end_date, $location_id), 'payment_summary.csv');
	}

	public function getPaymentSummaryExcel(Request $request){
		$start_date = !empty($request->start_date) ? date('Y-m-d', strtotime($request->start_date)) : NULL;
		$end_date = !empty($request->end_date) ? date('Y-m-d', strtotime($request->end_date)) : NULL;
		$location_id = !empty($request->location_id) ? $request->location_id : NULL;

		return Excel::download(new paymentSummaryExcelReport($start_date, $end_date, $location_id), 'payment_summary.xlsx');
	}
	
	public function paymentsLog(){
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
		
		return view('analytics.payment_log',compact('Locations','Staff'));
	}
	public function getPaymentLog(Request $request){
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

		$start_date = (!empty($request->start_date)) ? date('Y-m-d', strtotime($request->start_date)) : NULL;
		$end_date = (!empty($request->end_date)) ? date('Y-m-d', strtotime($request->end_date)) : NULL;
		$location_id = (!empty($request->location_id)) ? $request->location_id : NULL;
		$staff_id = (!empty($request->staff_id)) ? $request->staff_id : NULL;
		$selectVouchers = (!empty($request->selectVouchers)) ? $request->selectVouchers : NULL;

		$whereArray = [];

		if(!empty($start_date)) {
			$whereArray[] = ['invoice.payment_date', '>=', $start_date];
		}
		if(!empty($end_date)) {
			$whereArray[] = ['invoice.payment_date', '<=', $end_date];
		}
		if(!empty($location_id)) {
			$whereArray[] = ['invoice.location_id', '=', $location_id];
		}
		if(!empty($staff_id)) {
			$whereArray[] = ['invoice.created_by', '=', $staff_id];
		}

		$total = 0;
		$staffName = [];

		$invoiceData = Invoice::select('invoice.*','invoice.payment_type as method','locations.location_name','clients.firstname','clients.lastname','clients.id as client_id','users.first_name','users.last_name')->leftJoin('locations','locations.id','invoice.location_id')->leftJoin('clients','clients.id','invoice.client_id')->leftJoin('users','users.id','invoice.created_by')->where('invoice.user_id',$AdminId)->where($whereArray)->whereIn('invoice.invoice_status',['1','2']);

		$invoiceSum = Invoice::select(DB::raw("SUM(invoice.inovice_final_total) as total"))->leftJoin('locations','locations.id','invoice.location_id')->leftJoin('clients','clients.id','invoice.client_id')->leftJoin('users','users.id','invoice.created_by')->where('invoice.user_id',$AdminId)->where($whereArray)->whereIn('invoice.invoice_status',['1','2'])->first();

		$total = $invoiceSum->total;

		// $tmp = '';
		// foreach($invoiceData as $invoiceDataKey => $invoiceDataValue){
		// 	$tmp .= "staff_user_id :- ".$invoiceDataValue['created_by']."<br>";
		// }
		// echo $tmp;die;

		if(!empty($selectVouchers) && $selectVouchers == 'include'){			
			foreach($invoiceData as $invoiceDataKey => $invoiceDataValue){
				
				// $total += $invoiceDataValue['inovice_final_total'];

				$voucherData = InvoiceVoucher::where('invoice_id',$invoiceDataValue['id'])->first();
				if(!empty($voucherData)){
					$voucherDataValue = $invoiceDataValue;

					$voucherDataValue['inovice_final_total'] = $voucherData->voucher_amount;
					$voucherDataValue['payment_type'] = "Voucher ". $voucherData->voucher_code;

					$staff = User::where('id',$invoiceDataValue['staff_user_id'])->first();
					if(!empty($staff)){
						$staffName[] = $staff->first_name.' '.$staff->last_name;
					}else{
						$staffName[] = '';
					}
					$total += $voucherDataValue['inovice_final_total'];
					$invoiceData[] = $voucherDataValue;

				}
				
				$staff = User::where('id',$invoiceDataValue['staff_user_id'])->first();
				if(!empty($staff)){
					$staffName[] = $staff->first_name.' '.$staff->last_name;
				}else{
					$staffName[] = '';
				}

			}
		}else{			
			foreach($invoiceData as $invoiceDataKey => $invoiceDataValue){

				// $total += $invoiceDataValue['inovice_final_total'];
				$staff = User::where('id',$invoiceDataValue['staff_user_id'])->first();
				if(!empty($staff)){
					$staffName[] = $staff->first_name.' '.$staff->last_name;
				}else{
					$staffName[] = '';
				}
			}
		}

		
		// print_r($staffName);
		// echo "<pre> Count :- ". $count ."<br>CountStaff :- ". $countstaff."<br>CountStaffNotFound :- ". $countstaffNotFound;die;
		// dd($staffName);
		$response = Datatables::of($invoiceData)
			->addColumn('staff', function($row){
				if(!empty($row->first_name) && !empty($row->last_name)){
					$staff_name = "<td>". $row->first_name ." ". $row->last_name."</td>";
					return $staff_name;
				}else{
					$staff_name = "<td></td>";
					return $staff_name;
				}
			})
			->editColumn('transaction', function($row){
				if($row->invoice_status == 1){
					$transaction = "<td>Sale</td>";
					return $transaction;
				}else{
					$transaction = "<td>Refund</td>";
					return $transaction;
				}
			})
			->editColumn('inovice_final_total', function($row){
				return "CA $".number_format($row['inovice_final_total'], 2);
			})
			->editColumn('client', function($row){
				if(!empty($row->firstname) && !empty($row->lastname)){
					$client = "<td>". (!empty($row->firstname) ? $row->firstname :"") ." ". (!empty($row->lastname) ? $row->lastname :"") ."</td>";
					// $client = "<td>". $row->firstname ." ". $row->lastname ."</td>";
					return $client;
				}else{
					$client = "<td>Walk-In</td>";
					return $client;
				}
			})
			->rawColumns(['staff','transaction','client'])
			->make(true);

		$data = $response->getData(true);

		// echo "Total :- ". $total; die;

		$data['total'] = "CA $".number_format($total, 2, '.','');

		return $data;
	}
	public function getPaymentLogCSV(Request $request){
		$getPaymentLogCSV = $this->getPaymentLog($request);
		
		return Excel::download(new paymentLogCSVReport($getPaymentLogCSV['data']), 'payment_log.csv');
	}
	
	// public function taxesSummary(){
	// }
	
	public function tipCollections(){
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
		
		return view('analytics.tips_collected',compact('Locations','Staff'));
	}

	public function getTipsCollected(Request $request){
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

		$start_date = (!empty($request->start_date)) ? date('Y-m-d', strtotime($request->start_date)) : NULL;
		$end_date = (!empty($request->end_date)) ? date('Y-m-d', strtotime($request->end_date)) : NULL;
		$location_id = (!empty($request->location_id)) ? $request->location_id : NULL;
		$staff_id = (!empty($request->staff_id)) ? $request->staff_id : NULL;

		$whereArray = [];

		$whereArray[] = ['invoice.user_id', $AdminId];

		if(!empty($start_date)) {
			$whereArray[] = ['invoice.payment_date', '>=', $start_date];
		}
		if(!empty($end_date)) {
			$whereArray[] = ['invoice.payment_date', '<=', $end_date];
		}
		if(!empty($location_id)) {
			$whereArray[] = ['invoice.location_id', '=', $location_id];
		}
		if(!empty($staff_id)) {
			$whereArray[] = ['invoice.created_by', '=', $staff_id];
		}

		$tipsData = StaffTip::select('invoice.payment_date as date','invoice.invoice_id','locations.location_name',DB::raw("CONCAT(users.first_name,' ',users.last_name) as staff"),'staff_tip.tip_amount as tips_collected')
		->leftJoin('locations','locations.id','staff_tip.location_id')
		->leftJoin('users','users.id','staff_tip.staff_id')
		->leftJoin('invoice','invoice.id','staff_tip.inovice_id')
		->where($whereArray);
		
		$Total = StaffTip::select(DB::raw("SUM(staff_tip.tip_amount) as total_tips_collected"))
		->leftJoin('locations','locations.id','staff_tip.location_id')
		->leftJoin('users','users.id','staff_tip.staff_id')
		->leftJoin('invoice','invoice.id','staff_tip.inovice_id')
		->where($whereArray)
		->first();

		$response = Datatables::of($tipsData)
			// ->editColumn('staff', function($row){
				
			// 	$staff = ((!empty($row->staff_firstname)) ? $row->staff_firstname :'') ." ". ((!empty($row->staff_lastname)) ? $row->staff_firstname :'');

			// 	return $staff;
			// })
			->editColumn('tips_collected', function($row){
				return "CA $". number_format($row->tips_collected, 2);
			})
			->rawColumns(['staff'])
			->make(true);
		$data = $response->getData(true);
		$data['total'] = "CA $".number_format($Total->total_tips_collected, 2);
		return $data;
	}

	public function getTipsCollectedPDF(Request $request){
		$getTipsCollected = $this->getTipsCollected($request);

		$location_id = (!empty($request->location_id)) ? $request->location_id : null;
		$staff_id = (!empty($request->staff_id)) ? $request->staff_id : null;
		$start_date = (!empty($request->start_date)) ? $request->start_date : null;
		$end_date = (!empty($request->end_date)) ? $request->end_date : null;

		if(!empty($location_id)){
			$temp = Location::select("location_name as location")->where('id',$location_id)->first();

			$location_name = $temp->location;
		}else{
			$location_name = "All Locations";
		}

		if(!empty($staff_id)){
			$temp = User::select(DB::raw("CONCAT(users.first_name,' ',users.last_name) as staff_name"))->where("users.id", $staff_id)->first();
			$staff_name = $temp->staff_name;
		}else{
			$staff_name = "All Staff";
		}

		// dd($getTipsCollected);
		$FileName = "outstanding_invoice.pdf";

		return PDF::loadView('pdfTemplates.tipsCollectedPDFReport', compact('getTipsCollected','location_name','staff_name','start_date','end_date'))->setPaper('a4')->download($FileName);
	}
	
	public function getTipsCollectedCSV(Request $request){
		$getTipsCollected = $this->getTipsCollected($request);

		return Excel::download(new tipsCollectedCSVReport($getTipsCollected), 'tips_collected.csv');
	}

	public function getTipsCollectedExcel(Request $request){
		$getTipsCollected = $this->getTipsCollected($request);

		return Excel::download(new tipsCollectedExcelReport($getTipsCollected), 'tips_collected.xlsx');
	}
	
	public function discountsSummary(){
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
		
		return view('analytics.discount_summary',compact('Locations','Staff'));
	}

	public function getDiscountSummary(Request $request){
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

		$start_date = (!empty($request->start_date)) ? date('Y-m-d', strtotime($request->start_date)) : NULL;
		$end_date = (!empty($request->end_date)) ? date('Y-m-d', strtotime($request->end_date)) : NULL;
		$location_id = (!empty($request->location_id)) ? $request->location_id : NULL;
		$staff_id = (!empty($request->staff_id)) ? $request->staff_id : NULL;

		// echo "StartDate :- ". $start_date . "<br> EndDate :- ". $end_date;

		$whereArray = [];
		$whereArrayStaff = [];

		if(!empty($start_date)) {
			$whereArray[] = ['invoice.payment_date', '>=', $start_date];
		}
		if(!empty($end_date)) {
			$whereArray[] = ['invoice.payment_date', '<=', $end_date];
		}
		if(!empty($location_id)) {
			$whereArray[] = ['invoice.location_id', '=', $location_id];
		}
		if(!empty($staff_id)) {
			$whereArrayStaff[] = ['users.id', '=', $staff_id];
		}

		$discountsCompleted = InvoiceItems::select("discount.name as discount_name",DB::raw("SUM(invoice_items.item_og_price) as og_price_sum"),DB::raw("SUM(invoice_items.item_og_price - invoice_items.item_price) as discount_price"),DB::raw("COUNT(invoice_items.item_og_price) as count"),DB::raw("SUM(invoice_items.item_price) as price_sum"))
		->leftJoin('invoice','invoice.id','invoice_items.invoice_id')
		->leftJoin('staff','staff.id','invoice_items.staff_id')
		->leftJoin('users','users.id','staff.staff_user_id')
		->leftJoin('discount','discount.id','invoice_items.item_discount_id')
		->where('invoice.user_id',$AdminId)
		->where($whereArray)
		->where($whereArrayStaff)
		->where('invoice.invoice_status','1')
		->groupBy('invoice_items.item_discount_id')->get()->toArray();
		
		$invoiceCompletedDataIds = InvoiceItems::leftJoin('invoice','invoice.id','invoice_items.invoice_id')
		->leftJoin('staff','staff.id','invoice_items.staff_id')
		->leftJoin('users','users.id','staff.staff_user_id')
		->leftJoin('discount','discount.id','invoice_items.item_discount_id')
		->where('invoice.user_id',$AdminId)
		->where($whereArray)
		->where($whereArrayStaff)
		->where('invoice.invoice_status','1')
		->pluck('invoice.id');

		$discountsCompletedTotal = 0;

		foreach($discountsCompleted as $discountsCompletedKey => $discountsCompletedValue){
			$discountsCompletedTotal += $discountsCompletedValue['discount_price'];
			if($discountsCompletedValue['discount_name'] === null){
				$discountsCompleted[$discountsCompletedKey]['discount_name'] = "Special Price";
			}
		}

		$discountsRefund = InvoiceItems::select("invoice_items.item_discount_text as discount_name",DB::raw("SUM(invoice_items.item_og_price) as refund_og_price_sum"),DB::raw("SUM(invoice_items.item_og_price - invoice_items.item_price) as refund_discount_price"),DB::raw("COUNT(invoice_items.item_og_price) as refund_count"),DB::raw("SUM(invoice_items.item_price) as refund_price_sum"))
		->leftJoin('invoice','invoice.id','invoice_items.invoice_id')
		->leftJoin('discount','discount.id','invoice_items.item_discount_id')
		->whereIn('invoice_items.invoice_id',$invoiceCompletedDataIds)
		->where('invoice.invoice_status','2')
		->where('invoice.original_invoice_id','!=','0')
		->groupBy('invoice_items.item_discount_text')->get()->toArray();

		$discountsRefundTotal = 0;

		if(!empty($discountsRefund)){
			foreach($discountsRefund as $discountsRefundKey => $discountsRefundValue){
				$discountsRefundTotal += $discountsRefundValue['refund_discount_price'];
				if($discountsRefundValue['discount_name'] == null){
					$discountsRefund[$discountsRefundKey]['discount_name'] = "Special Price";
				}
			}
		}

		// dd($discountsRefund);

		$data = [];
		$data["status"] = true;
		$data["message"] = 'Data fetched successfully.';
		$data["discountComplete"] = $discountsCompleted;
		$data["discountRefund"] = $discountsRefund;
		$data["discountsCompletedTotal"] = $discountsCompletedTotal;
		$data["discountsRefundTotal"] = $discountsRefundTotal;
		return JsonReturn::success($data);
	}
	public function getDiscountSummaryByServices(Request $request){
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

		$start_date = (!empty($request->start_date)) ? date('Y-m-d', strtotime($request->start_date)) : NULL;
		$end_date = (!empty($request->end_date)) ? date('Y-m-d', strtotime($request->end_date)) : NULL;
		$location_id = (!empty($request->location_id)) ? $request->location_id : NULL;
		$staff_id = (!empty($request->staff_id)) ? $request->staff_id : NULL;

		// echo "StartDate :- ". $start_date . "<br> EndDate :- ". $end_date;

		$whereArray = [];
		$whereArrayStaff = [];

		if(!empty($start_date)) {
			$whereArray[] = ['invoice.payment_date', '>=', $start_date];
		}
		if(!empty($end_date)) {
			$whereArray[] = ['invoice.payment_date', '<=', $end_date];
		}
		if(!empty($location_id)) {
			$whereArray[] = ['invoice.location_id', '=', $location_id];
		}
		if(!empty($staff_id)) {
			$whereArrayStaff[] = ['users.id', '=', $staff_id];
		}

		$invoiceCompletedData = InvoiceItems::select('services.service_name',DB::raw("COUNT(invoice_items.invoice_id) as count"), DB::raw("SUM(invoice_items.item_og_price) as og_price_sum"),DB::raw("SUM(invoice_items.item_price) as price_sum"),'invoice_items.item_id', DB::raw("SUM(invoice_items.item_og_price - invoice_items.item_price) as discount_price"))
		->leftJoin('invoice','invoice.id','invoice_items.invoice_id')
		->leftJoin('services','services.id','invoice_items.item_id')
		->leftJoin('staff','staff.id','invoice_items.staff_id')
		->leftJoin('users','users.id','staff.staff_user_id')
		->where($whereArray)
		->where($whereArrayStaff)
		->where('invoice_items.item_og_price','>','invoice_items.item_price')
		->where('invoice.user_id',$AdminId)
		->where('invoice_items.item_type','services')
		->where('invoice.invoice_status','1')
		->groupBy('invoice_items.item_id')->get()->toArray();

		$invoiceCompletedDataIds = Invoice::where('user_id',$AdminId)
			->where($whereArray)
			->where('invoice.invoice_status','1')
			->pluck('id');


		$discountsCompletedTotal = 0;
		$discountsRefundTotal = 0;

		foreach($invoiceCompletedData as $invoiceCompletedDataKey => $invoiceCompletedDataValue){
			$discountsCompletedTotal += $invoiceCompletedDataValue['discount_price'];
		}

		$invoiceRefundData = Invoice::select('services.service_name',DB::raw("COUNT(invoice_items.invoice_id) as refund_count"), DB::raw("SUM(invoice_items.item_og_price) as refund_og_price_sum"),DB::raw("SUM(invoice_items.item_price) as refund_price_sum"),'invoice_items.item_id', DB::raw("SUM(invoice_items.item_og_price - invoice_items.item_price) as refund_discount_price"))
		->leftJoin('invoice_items','invoice_items.invoice_id','invoice.id')
		->leftJoin('services','services.id','invoice_items.item_id')
		->leftJoin('staff','staff.id','invoice_items.staff_id')
		->leftJoin('users','users.id','staff.staff_user_id')
		->where('invoice_items.item_type','services')
		->where('invoice.invoice_status','2')
		->whereIn('invoice.original_invoice_id',$invoiceCompletedDataIds)
		->groupBy('invoice_items.item_id')->get()->toArray();

		foreach($invoiceRefundData as $invoiceRefundDataKey => $invoiceRefundDataValue){
			$discountsRefundTotal += $invoiceRefundDataValue['refund_discount_price'];
		}


		// echo "<pre>";print_r($invoiceCompletedData);die;

		$data = [];
		$data["status"] = true;
		$data["message"] = 'Data fetched successfully.';
		$data["discountComplete"] = $invoiceCompletedData;
		$data["discountRefund"] = $invoiceRefundData;
		$data["discountsCompletedTotal"] = $discountsCompletedTotal;
		$data["discountsRefundTotal"] = $discountsRefundTotal;
		return JsonReturn::success($data);
	}
	public function getDiscountSummaryByProduct(Request $request){
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

		$start_date = (!empty($request->start_date)) ? date('Y-m-d', strtotime($request->start_date)) : NULL;
		$end_date = (!empty($request->end_date)) ? date('Y-m-d', strtotime($request->end_date)) : NULL;
		$location_id = (!empty($request->location_id)) ? $request->location_id : NULL;
		$staff_id = (!empty($request->staff_id)) ? $request->staff_id : NULL;

		// echo "StartDate :- ". $start_date . "<br> EndDate :- ". $end_date;

		$whereArray = [];
		$whereArrayStaff = [];

		if(!empty($start_date)) {
			$whereArray[] = ['invoice.payment_date', '>=', $start_date];
		}
		if(!empty($end_date)) {
			$whereArray[] = ['invoice.payment_date', '<=', $end_date];
		}
		if(!empty($location_id)) {
			$whereArray[] = ['invoice.location_id', '=', $location_id];
		}
		if(!empty($staff_id)) {
			$whereArrayStaff[] = ['users.id', '=', $staff_id];
		}

		$invoiceCompletedData = Invoice::select('inventory_products.product_name',DB::raw("COUNT(invoice_items.invoice_id) as count"), DB::raw("SUM(invoice_items.item_og_price) as og_price_sum"),DB::raw("SUM(invoice_items.item_price) as price_sum"), DB::raw("SUM(invoice_items.item_og_price - invoice_items.item_price) as discount_price"))
		->leftJoin('invoice_items','invoice_items.invoice_id','invoice.id')
		->leftJoin('inventory_products','inventory_products.id','invoice_items.item_id')
		->leftJoin('staff','staff.id','invoice_items.staff_id')
		->leftJoin('users','users.id','staff.staff_user_id')
		->where($whereArray)
		->where($whereArrayStaff)
		->where('invoice_items.item_og_price','>','invoice_items.item_price')
		->where('invoice.user_id',$AdminId)
		->where('invoice_items.item_type','product')
		->where('invoice.invoice_status','1')
		->groupBy('invoice_items.item_id')->get()->toArray();

		$invoiceCompletedDataIds = Invoice::where('user_id',$AdminId)
			->where($whereArray)
			->where('invoice.invoice_status','1')
			->pluck('id');

			// echo "<pre>"; print_r($invoiceCompletedData);echo "<br> Invoice Id's :- ";print_r($invoiceCompletedDataIds);die;


		$discountsCompletedTotal = 0;
		$discountsRefundTotal = 0;

		foreach($invoiceCompletedData as $invoiceCompletedDataKey => $invoiceCompletedDataValue){
			$discountsCompletedTotal += $invoiceCompletedDataValue['discount_price'];
		}

		$invoiceRefundData = Invoice::select('inventory_products.product_name',DB::raw("COUNT(invoice_items.invoice_id) as refund_count"), DB::raw("SUM(invoice_items.item_og_price) as refund_og_price_sum"),DB::raw("SUM(invoice_items.item_price) as refund_price_sum"), DB::raw("SUM(invoice_items.item_og_price - invoice_items.item_price) as refund_discount_price"))
		->leftJoin('invoice_items','invoice_items.invoice_id','invoice.id')
		->leftJoin('inventory_products','inventory_products.id','invoice_items.item_id')
		->leftJoin('staff','staff.id','invoice_items.staff_id')
		->leftJoin('users','users.id','staff.staff_user_id')
		->where('invoice_items.item_type','product')
		->where('invoice.invoice_status','2')
		->whereIn('invoice.original_invoice_id',$invoiceCompletedDataIds)
		->groupBy('invoice_items.item_id')->get()->toArray();

		foreach($invoiceRefundData as $invoiceRefundDataKey => $invoiceRefundDataValue){
			$discountsRefundTotal += $invoiceRefundDataValue['refund_discount_price'];
		}


		// echo "<pre>";print_r($invoiceCompletedData);die;

		$data = [];
		$data["status"] = true;
		$data["message"] = 'Data fetched successfully.';
		$data["discountComplete"] = $invoiceCompletedData;
		$data["discountRefund"] = $invoiceRefundData;
		$data["discountsCompletedTotal"] = $discountsCompletedTotal;
		$data["discountsRefundTotal"] = $discountsRefundTotal;
		return JsonReturn::success($data);
	}
	public function getDiscountSummaryByStaff(Request $request){
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

		$start_date = (!empty($request->start_date)) ? date('Y-m-d', strtotime($request->start_date)) : NULL;
		$end_date = (!empty($request->end_date)) ? date('Y-m-d', strtotime($request->end_date)) : NULL;
		$location_id = (!empty($request->location_id)) ? $request->location_id : NULL;
		$staff_id = (!empty($request->staff_id)) ? $request->staff_id : NULL;

		// echo "StartDate :- ". $start_date . "<br> EndDate :- ". $end_date;

		$whereArray = [];
		$whereArrayStaff = [];

		if(!empty($start_date)) {
			$whereArray[] = ['invoice.payment_date', '>=', $start_date];
		}
		if(!empty($end_date)) {
			$whereArray[] = ['invoice.payment_date', '<=', $end_date];
		}
		if(!empty($location_id)) {
			$whereArray[] = ['invoice.location_id', '=', $location_id];
		}
		if(!empty($staff_id)) {
			$whereArrayStaff[] = ['users.id', '=', $staff_id];
		}

		$invoiceCompletedData = Invoice::select(DB::raw('CONCAT(users.first_name," ", users.last_name) as staff_name'),'users.last_name as staff_lastname',DB::raw("COUNT(invoice_items.invoice_id) as count"), DB::raw("SUM(invoice_items.item_og_price) as og_price_sum"),DB::raw("SUM(invoice_items.item_price) as price_sum"), DB::raw("SUM(invoice_items.item_og_price - invoice_items.item_price) as discount_price"), "invoice_items.staff_id")
			->leftJoin('invoice_items','invoice_items.invoice_id','invoice.id')
			->leftJoin('staff','staff.id','invoice_items.staff_id')
			->leftJoin('users','users.id','staff.staff_user_id')
			->where($whereArray)
			->where($whereArrayStaff)
			->where('invoice_items.item_og_price','>','invoice_items.item_price')
			->where('invoice.user_id',$AdminId)
			->where('invoice.invoice_status','1')
			->groupBy('invoice_items.staff_id')->get()->toArray();

			// dd($invoiceCompletedData);

		$invoiceCompletedDataIds = Invoice::where('user_id',$AdminId)
			->where($whereArray)
			->where('invoice.invoice_status','1')
			->pluck('id');

			// echo "<pre>"; print_r($invoiceCompletedDataIds);die;

		// echo "<pre>";print_r($invoiceCompletedData);die;
		$discountsCompletedTotal = 0;
		$discountsRefundTotal = 0;

		foreach($invoiceCompletedData as $invoiceCompletedDataKey => $invoiceCompletedDataValue){
			$discountsCompletedTotal += $invoiceCompletedDataValue['discount_price'];
		}

		$invoiceRefundData = Invoice::select(DB::raw("COUNT(invoice_items.invoice_id) as refund_count"), DB::raw("SUM(invoice_items.item_og_price) as refund_og_price_sum"),DB::raw("SUM(invoice_items.item_price) as refund_price_sum"), DB::raw("SUM(invoice_items.item_og_price - invoice_items.item_price) as refund_discount_price"), "invoice_items.staff_id")
		->leftJoin('invoice_items','invoice_items.invoice_id','invoice.id')
		->leftJoin('staff','staff.id','invoice_items.staff_id')
		->leftJoin('users','users.id','staff.staff_user_id')
		->where('invoice.invoice_status','2')
		->whereIn('invoice.original_invoice_id',$invoiceCompletedDataIds)
		->groupBy('invoice_items.staff_id')->get()->toArray();

		foreach($invoiceRefundData as $invoiceRefundDataKey => $invoiceRefundDataValue){
			$discountsRefundTotal += $invoiceRefundDataValue['refund_discount_price'];
		}

		// dd($invoiceRefundData);

		// echo "<pre>";print_r($invoiceCompletedData);die;

		$data = [];
		$data["status"] = true;
		$data["message"] = 'Data fetched successfully.';
		$data["discountComplete"] = $invoiceCompletedData;
		$data["discountRefund"] = $invoiceRefundData;
		$data["discountsCompletedTotal"] = $discountsCompletedTotal;
		$data["discountsRefundTotal"] = $discountsRefundTotal;
		return JsonReturn::success($data);
	}
	public function getDiscountSummaryByType(Request $request){
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

		$start_date = (!empty($request->start_date)) ? date('Y-m-d', strtotime($request->start_date)) : NULL;
		$end_date = (!empty($request->end_date)) ? date('Y-m-d', strtotime($request->end_date)) : NULL;
		$location_id = (!empty($request->location_id)) ? $request->location_id : NULL;
		$staff_id = (!empty($request->staff_id)) ? $request->staff_id : NULL;

		// echo "StartDate :- ". $start_date . "<br> EndDate :- ". $end_date;

		$whereArray = [];
		$whereArrayStaff = [];

		if(!empty($start_date)) {
			$whereArray[] = ['invoice.payment_date', '>=', $start_date];
		}
		if(!empty($end_date)) {
			$whereArray[] = ['invoice.payment_date', '<=', $end_date];
		}
		if(!empty($location_id)) {
			$whereArray[] = ['invoice.location_id', '=', $location_id];
		}
		if(!empty($staff_id)) {
			$whereArrayStaff[] = ['users.id', '=', $staff_id];
		}

		$invoiceCompletedData = Invoice::select(DB::raw('invoice_items.item_type as type'),DB::raw("COUNT(invoice_items.invoice_id) as count"), DB::raw("SUM(invoice_items.item_og_price) as og_price_sum"),DB::raw("SUM(invoice_items.item_price) as price_sum"), DB::raw("SUM(invoice_items.item_og_price - invoice_items.item_price) as discount_price"))
			->leftJoin('invoice_items','invoice_items.invoice_id','invoice.id')
			->leftJoin('staff','staff.id','invoice_items.staff_id')
			->leftJoin('users','users.id','staff.staff_user_id')
			->where($whereArray)
			->where($whereArrayStaff)
			->where('invoice_items.item_og_price','>','invoice_items.item_price')
			->where('invoice.user_id',$AdminId)
			->where('invoice.invoice_status','1')
			->whereIn('invoice_items.item_type',['product','services'])
			->groupBy('invoice_items.item_type')->get()->toArray();

			// dd($invoiceCompletedData);

		$invoiceCompletedDataIds = Invoice::where('user_id',$AdminId)
			->where($whereArray)
			->where('invoice.invoice_status','1')
			->pluck('id');

			// echo "<pre>"; print_r($invoiceCompletedDataIds);die;

		// echo "<pre>";print_r($invoiceCompletedData);die;
		$discountsCompletedTotal = 0;
		$discountsRefundTotal = 0;

		foreach($invoiceCompletedData as $invoiceCompletedDataKey => $invoiceCompletedDataValue){
			$discountsCompletedTotal += $invoiceCompletedDataValue['discount_price'];
		}

		$invoiceRefundData = Invoice::select(DB::raw('invoice_items.item_type as type'),DB::raw("COUNT(invoice_items.invoice_id) as refund_count"), DB::raw("SUM(invoice_items.item_og_price) as refund_og_price_sum"),DB::raw("SUM(invoice_items.item_price) as refund_price_sum"), DB::raw("SUM(invoice_items.item_og_price - invoice_items.item_price) as refund_discount_price"))
		->leftJoin('invoice_items','invoice_items.invoice_id','invoice.id')
		->leftJoin('staff','staff.id','invoice_items.staff_id')
		->leftJoin('users','users.id','staff.staff_user_id')
		->where('invoice.invoice_status','2')
		->whereIn('invoice_items.item_type',['product','services'])
		->whereIn('invoice.original_invoice_id',$invoiceCompletedDataIds)
		->groupBy('invoice_items.item_type')->get()->toArray();

		// dd($invoiceRefundData);

		foreach($invoiceRefundData as $invoiceRefundDataKey => $invoiceRefundDataValue){
			$discountsRefundTotal += $invoiceRefundDataValue['refund_discount_price'];
		}

		// dd($invoiceRefundData);

		// echo "<pre>";print_r($invoiceCompletedData);die;

		$data = [];
		$data["status"] = true;
		$data["message"] = 'Data fetched successfully.';
		$data["discountComplete"] = $invoiceCompletedData;
		$data["discountRefund"] = $invoiceRefundData;
		$data["discountsCompletedTotal"] = $discountsCompletedTotal;
		$data["discountsRefundTotal"] = $discountsRefundTotal;
		return JsonReturn::success($data);
	}

	public function getDiscountSummaryPDF(Request $request){

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

		$start_date = (!empty($request->start_date)) ? date('Y-m-d', strtotime($request->start_date)) : NULL;
		$end_date = (!empty($request->end_date)) ? date('Y-m-d', strtotime($request->end_date)) : NULL;
		$location_id = (!empty($request->location_id)) ? $request->location_id : NULL;
		$staff_id = (!empty($request->staff_id)) ? $request->staff_id : NULL;


		$getDiscountSummary = $this->getDiscountSummary($request);
		$getDiscountSummaryByServices = $this->getDiscountSummaryByServices($request);
		$getDiscountSummaryByProduct = $this->getDiscountSummaryByProduct($request);
		$getDiscountSummaryByStaff = $this->getDiscountSummaryByStaff($request);
		$getDiscountSummaryByType = $this->getDiscountSummaryByType($request);
		
		$LocationName = Location::select('location_name')->where('id',$location_id)->first();
		$StaffName = User::select(DB::raw("CONCAT(first_name, ' ', last_name) as staffName"))->where('id',$staff_id)->first();

		$FileName = "Discount_Summary.pdf";

		return PDF::loadView('pdfTemplates.discountSummaryPDFReport', compact('getDiscountSummary','getDiscountSummaryByServices','getDiscountSummaryByProduct','getDiscountSummaryByStaff','getDiscountSummaryByType','LocationName','StaffName'))->setPaper('a4')->download($FileName);
	}

	public function getDiscountSummaryCSV(Request $request){
		$getDiscountSummary = $this->getDiscountSummary($request);
		$getDiscountSummaryByServices = $this->getDiscountSummaryByServices($request);
		$getDiscountSummaryByProduct = $this->getDiscountSummaryByProduct($request);
		$getDiscountSummaryByStaff = $this->getDiscountSummaryByStaff($request);
		$getDiscountSummaryByType = $this->getDiscountSummaryByType($request);

		return Excel::download(new discountSummaryCSVReport($getDiscountSummary->original, $getDiscountSummaryByServices->original, $getDiscountSummaryByProduct->original, $getDiscountSummaryByStaff->original,$getDiscountSummaryByType->original), 'discount_summary.csv');
	}

	public function getDiscountSummaryExcel(Request $request){
		$getDiscountSummary = $this->getDiscountSummary($request);
		$getDiscountSummaryByServices = $this->getDiscountSummaryByServices($request);
		$getDiscountSummaryByProduct = $this->getDiscountSummaryByProduct($request);
		$getDiscountSummaryByStaff = $this->getDiscountSummaryByStaff($request);
		$getDiscountSummaryByType = $this->getDiscountSummaryByType($request);

		return Excel::download(new discountSummaryExcelReport($getDiscountSummary->original, $getDiscountSummaryByServices->original, $getDiscountSummaryByProduct->original, $getDiscountSummaryByStaff->original,$getDiscountSummaryByType->original), 'discount_summary.xlsx');
	}
	
	public function outstandingInvoices(){
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
		
		return view('analytics.outstanding_invoice',compact('Locations'));
	}

	public function getOutstandingInvoices(Request $request){

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

		$location_id = (!empty($request->location_id)) ? $request->location_id : null;
		
		$whereArray = [];

		if(!empty($location_id)){
			$whereArray[] = ['invoice.location_id' , '=' , $location_id];
		}

		$outstandingInvoiceData = Invoice::select('invoice.invoice_id','locations.location_name','invoice.invoice_status','invoice.created_at as invoice_date','invoice.created_at as due_date',DB::raw("CONCAT(clients.firstname,' ',clients.lastname) as customer"), 'invoice.inovice_final_total as gross_total', 'invoice.inovice_final_total as amount_due')
		->leftJoin('locations','locations.id','invoice.location_id')
		->leftJoin('clients','clients.id','invoice.client_id')
		->where($whereArray)
		->where('invoice.user_id',$AdminId)
		->where('invoice.invoice_status','0');

		$total = Invoice::select(DB::raw('SUM(invoice.inovice_final_total) as total'))
		->where($whereArray)
		->where('invoice.user_id',$AdminId)
		->where('invoice.invoice_status','0')->first();

		// echo $total->total;die;

		$response = DataTables::of($outstandingInvoiceData)
		->editColumn('status', function($raw){
			return "<span class='status unpaid'>Unpaid</span>";
		})
		->editColumn('due_date', function($raw){
			$due_date = strtotime($raw->due_date);
			$due_date = date('Y-m-d',$due_date);
			return $due_date;
		})
		->editColumn('gross_total', function($raw){
			return "CA $".number_format($raw->gross_total, 2, '.', '');
		})
		->editColumn('amount_due', function($raw){
			return "CA $".number_format($raw->amount_due, 2, '.', '');
		})
		->editColumn('customer', function($raw){
			if(empty($raw->customer)){
				return 'Walk-In';
			}else{
				return $raw->customer;
			}
		})
		->addColumn('Overdue', function($raw){
			$date = date("Y-m-d");
			$today = strtotime($date);
			$invoiceDate = strtotime($raw->invoice_date);
			$overdueTime = intval( ($today - $invoiceDate) / 86400);

			return $overdueTime. " days ";
		})
		->rawColumns(['status','Overdue'])
		->make(true);

		$data = $response->getData(true);
		$data['total'] = "CA $".number_format($total->total, 2, '.', '');

		return $data;
	}

	public function getOutstandingInvoicePDF(Request $request){
		$tempData = $this->getOutstandingInvoices($request);

		$location_id = (!empty($request->location_id)) ? $request->location_id : null;

		if(!empty($location_id)){
			$temp = Location::select("location_name as location")->where('id',$location_id)->first();

			$location_name = $temp->location;
		}else{
			$location_name = "All Locations";
		}

		// dd($tempData);
		$data = $tempData['data'];
		$total = $tempData['total'];
		$FileName = "outstanding_invoice.pdf";

		return PDF::loadView('pdfTemplates.outstandingInvoicePDFReport', compact('data','location_name','total'))->setPaper('a4')->download($FileName);
	}
	
	public function getOutstandingInvoiceCSV(Request $request){
		$tempData = $this->getOutstandingInvoices($request);

		$data = $tempData['data'];
		$total = $tempData['total'];

		// dd($data);
		return Excel::download(new outstandingInvoiceCSVReport($data,$total), 'outstanding_invoice.csv');
	}

	public function getOutstandingInvoiceExcel(Request $request){
		$tempData = $this->getOutstandingInvoices($request);

		$data = $tempData['data'];
		$total = $tempData['total'];

		return Excel::download(new outstandingInvoiceExcelReport($data,$total), 'outstanding_invoice.xlsx');
	}
	
	// public function productSalesPerformance(){
	// }
	
	// public function stockMovementLog(){
	// }
	
	public function stockMovementSummary(){
	}
	
	// public function productConsumption(){
	// }
	
	public function appointmentsSummary(){
	}
	
	public function appointmentCancellations(){
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
		
		return view('analytics.appointment_cancellations',compact('Locations','Staff'));
	}

	public function getAppointmentCancellations(Request $request){
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

		$start_date = (!empty($request->start_date)) ? date('Y-m-d', strtotime($request->start_date)) : NULL;
		$end_date = (!empty($request->end_date)) ? date('Y-m-d', strtotime($request->end_date)) : NULL;
		$location_id = (!empty($request->location_id)) ? $request->location_id : NULL;
		$staff_id = (!empty($request->staff_id)) ? $request->staff_id : NULL;

		$whereArray = [];

		if(!empty($start_date)){
			$whereArray[] = ['appointments.created_at', '>=', $start_date];
		}
		if(!empty($end_date)){
			$whereArray[] = ['appointments.created_at', '<=', $end_date];
		}
		if(!empty($location_id)){
			$whereArray[] = ['appointments.location_id', '=', $location_id];
		}
		if(!empty($staff_id)){
			$whereArray[] = ['appointments.staff_user_id', '=', $staff_id];
		}

		$data = AppointmentServices::select('appointment_services.appointment_id as ref', DB::raw("CONCAT(clients.firstname,' ',clients.lastname) as client"), 'services.service_name as service', 'appointments.created_at as scheduled_date', 'appointments.updated_at as 	
		cancelled_date', DB::raw("CONCAT(users.first_name,' ',users.last_name) as cancelled_by"), 'appointments.cancellation_reason as reason', 'appointment_services.special_price as price')
		->leftJoin('services_price','services_price.id','appointment_services.service_price_id')
		->leftJoin('services','services.id','services_price.service_id')
		->leftJoin('appointments','appointments.id','appointment_services.appointment_id')
		->leftJoin('clients','clients.id','appointments.client_id')
		->leftJoin('users','users.id','appointments.staff_user_id')
		->where($whereArray)
		->where('appointments.is_cancelled','1')
		->where('appointments.user_id',$AdminId)->get()->toArray();		

		// print_r($data);die;

		return DataTables::of($data)
		->editColumn('client',function($raw){
			if(empty($raw['client'])){
				return "Walk-In";
			}else{
				return $raw['client'];
			}
		})
		->editColumn('reason',function($raw){
			if(empty($raw['reason'])){
				return "No reason";
			}else{
				return $raw['reason'];
			}
		})
		->editColumn('cancelled_date',function($raw){
			$cancelled_date = strtotime($raw['cancelled_date']);
			$cancelled_date = date('Y-m-d',$cancelled_date);
			return $cancelled_date;
		})
		->editColumn('price',function($raw){
			return "CA $".number_format($raw['price'], 2);
		})
		->make(true);
		$due_date = strtotime($raw->due_date);
			$due_date = date('Y-m-d',$due_date);
			return $due_date;
	}

	public function getAppointmentCancellationsSummary(Request $request){
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

		$start_date = (!empty($request->start_date)) ? date('Y-m-d', strtotime($request->start_date)) : NULL;
		$end_date = (!empty($request->end_date)) ? date('Y-m-d', strtotime($request->end_date)) : NULL;
		$location_id = (!empty($request->location_id)) ? $request->location_id : NULL;
		$staff_id = (!empty($request->staff_id)) ? $request->staff_id : NULL;

		$whereArray = [];

		if(!empty($start_date)){
			$whereArray[] = ['appointments.created_at', '>=', $start_date];
		}
		if(!empty($end_date)){
			$whereArray[] = ['appointments.created_at', '<=', $end_date];
		}
		if(!empty($location_id)){
			$whereArray[] = ['appointments.location_id', '=', $location_id];
		}
		if(!empty($staff_id)){
			$whereArray[] = ['appointments.staff_user_id', '=', $staff_id];
		}

		$Summary = AppointmentServices::select(DB::raw("COUNT(appointment_services.id) as reason_count"), 'appointments.cancellation_reason as reason')
		->leftJoin('appointments','appointments.id','appointment_services.appointment_id')
		->where($whereArray)
		->where('appointments.is_cancelled','1')
		->where('appointments.user_id',$AdminId)
		->groupBy('appointments.cancellation_reason')
		->get()->toArray();

		$data = [];
		$data["status"] = true;
		$data["message"] = 'Data fetched successfully.';
		$data["summary"] = $Summary;
		return JsonReturn::success($data);
	}

	public function getAppointmentCancellationsPDF(Request $request){
		$getAppointmentCancellationsTemp = $this->getAppointmentCancellations($request);
		$getAppointmentCancellationsSummaryTemp = $this->getAppointmentCancellationsSummary($request);

		$getAppointmentCancellations = $getAppointmentCancellationsTemp->original['data'];
		$getAppointmentCancellationsSummary = $getAppointmentCancellationsSummaryTemp->original['summary'];

		// dd($getAppointmentCancellations);
		$location_id = (!empty($request->location_id)) ? $request->location_id : null;
		$staff_id = (!empty($request->staff_id)) ? $request->staff_id : null;

		if(!empty($location_id)){
			$temp = Location::select("location_name as location")->where('id',$location_id)->first();

			$location_name = $temp->location;
		}else{
			$location_name = "All Locations";
		}

		if(!empty($staff_id)){
			$tempStaff = User::select(DB::raw('CONCAT(first_name," ",last_name) as staff'))->where('id',$staff_id)->first();

			$staff_name = $tempStaff->staff;
		}else{
			$staff_name = "All Staff";
		}

		// dd($tempData);
		$FileName = "appointment_cancellations.pdf";

		return PDF::loadView('pdfTemplates.appointmentCancellationPDFReport', compact('getAppointmentCancellations','getAppointmentCancellationsSummary','location_name','staff_name'))->setPaper('a4')->download($FileName);

	}

	public function getAppointmentCancellationsCSV(Request $request){
		$getAppointmentCancellationsTemp = $this->getAppointmentCancellations($request);
		$getAppointmentCancellationsSummaryTemp = $this->getAppointmentCancellationsSummary($request);

		$getAppointmentCancellations = $getAppointmentCancellationsTemp->original['data'];
		$getAppointmentCancellationsSummary = $getAppointmentCancellationsSummaryTemp->original['summary'];
		
		// $FileName = "appointment_cancellations.csv";

		return Excel::download(new appointmentCancellationsCSVReport($getAppointmentCancellations,$getAppointmentCancellationsSummary), 'appointment_cancellations.csv');
	}

	public function getAppointmentCancellationsExcel(Request $request){
		$getAppointmentCancellationsTemp = $this->getAppointmentCancellations($request);
		$getAppointmentCancellationsSummaryTemp = $this->getAppointmentCancellationsSummary($request);

		$getAppointmentCancellations = $getAppointmentCancellationsTemp->original['data'];
		$getAppointmentCancellationsSummary = $getAppointmentCancellationsSummaryTemp->original['summary'];

		return Excel::download(new appointmentCancellationsExcelReport($getAppointmentCancellations,$getAppointmentCancellationsSummary), 'appointment_cancellations.xlsx');
	}
	
	public function clientsList(){
		return view('analytics.client_list');
	}

	public function getClientList(Request $request){
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

		$start_date = (!empty($request->start_date)) ? date('Y-m-d', strtotime($request->start_date)) : NULL;
		$end_date = (!empty($request->end_date)) ? date('Y-m-d', strtotime($request->end_date)) : NULL;

		$whereArray = [];

		if(!empty($start_date)){
			$whereArray[] = ['clients.created_at', '>=', $start_date];
		}
		if(!empty($end_date)){
			$whereArray[] = ['clients.created_at', '<=', $end_date];
		}

		$data = Appointments::select(DB::raw('CONCAT(clients.firstname, " ",clients.lastname) as name'), 'clients.is_blocked as blocked', DB::raw('COUNT(appointments.id) as appointments'), DB::raw('SUM(invoice.inovice_final_total) as total_sales'),'clients.gender as gender', 'clients.created_at as added','appointments.client_id', 'appointments.created_at as last_appointment', 'locations.location_name as last_location','appointments.location_id')
		->leftJoin('clients', 'clients.id', 'appointments.client_id')
		->leftJoin('invoice', 'invoice.id', 'appointments.invoice_id')
		->leftJoin('locations', 'locations.id', 'appointments.location_id')
		->where($whereArray)
		->where('appointments.user_id',$AdminId)
		->where('appointments.is_noshow','0')
		->where('appointments.is_cancelled','0')
		->whereIn('invoice.invoice_status', ['0','1'])
		->groupBy('name')
		->get()->toArray();

		$client_id = [];
		foreach($data as $dataKey => $dataValue){
			$client_id[] = $dataValue['client_id'];
		}

		
		// dd($client_id);

		$no_show = Appointments::select(DB::raw('CONCAT(clients.firstname, " ",clients.lastname) as name'), DB::raw('COUNT(appointments.id) as no_show'))
		->leftJoin('clients','clients.id','appointments.client_id')
		->where($whereArray)
		->where('appointments.user_id',$AdminId)
		->where('appointments.is_noshow','1')
		->groupBy('name')
		->get()->toArray();

		$invoice_id = Invoice::where('user_id',$AdminId)->whereIn('invoice.client_id',$client_id)->whereIn('invoice.invoice_status',['0','1'])->pluck('id');

		$refundData = Invoice::select(DB::raw('CONCAT(clients.firstname, " ",clients.lastname) as name'), DB::raw('SUM(invoice.inovice_final_total) as total_sales'))
		->leftJoin('clients', 'clients.id', 'invoice.client_id')
		->where($whereArray)
		->where('invoice.user_id',$AdminId)
		->where('invoice.invoice_status', '2')
		->whereIn('invoice.original_invoice_id', $invoice_id)
		->groupBy('name')
		->get()->toArray();

		$outstandingData = Invoice::select(DB::raw('CONCAT(clients.firstname, " ",clients.lastname) as name'), DB::raw('SUM(invoice.inovice_final_total) as total_sales'))
		->leftJoin('clients', 'clients.id', 'invoice.client_id')
		->where($whereArray)
		->where('invoice.user_id',$AdminId)
		->where('invoice.invoice_status', '0')
		->groupBy('name')
		->get()->toArray();

		foreach($data as $data_Key => $data_Value){

			//Appending No_show
			$noShowFlag = true;
			foreach($no_show as $no_show_key => $no_show_value){
				if($data_Value['name'] == $no_show_value['name']){
					$noShowFlag = false;
					$data[$data_Key]['no_show'] = $no_show_value['no_show'];
				}
			}
			if($noShowFlag){
				$data[$data_Key]['no_show'] = 0;
			}

			//substract refund
			foreach($refundData as $refundDataKey => $refundDataValue){
				if($data_Value['name'] == $refundDataValue['name']){
					$data[$data_Key]['total_sales'] -= $refundDataValue['total_sales'];
				}
			}

			//Adding outstanding data
			$outstandingDataFlag = true;
			foreach($outstandingData as $outstandingDataKey => $outstandingDataValue){
				if($data_Value['name'] == $outstandingDataValue['name']){
					$outstandingDataFlag = false;
					$data[$data_Key]['outstanding'] = $outstandingDataValue['total_sales'];
				}
			}
			if($outstandingDataFlag){
				$data[$data_Key]['outstanding'] = 0;
			}

			//Adding last appointment and last location_name
			$last_appointment = Appointments::select('created_at','location_id')->where('client_id', $data_Value['client_id'])->orderBy('created_at','DESC')->first();
			$last_location = Location::select('location_name')->where('id', $last_appointment->location_id)->first();

			$data[$data_Key]['last_appointment'] = $last_appointment->created_at;
			$data[$data_Key]['last_location'] = $last_location->location_name;
		}

		return DataTables::of($data)
		->editColumn('blocked', function($raw){
			if($raw['blocked'] === 0){
				return "No";
			}
			return "Yes";
		})
		->editColumn('total_sales', function($raw){
			return number_format($raw['total_sales'], 2, '.', '');
		})
		->editColumn('added', function($raw){
			$added = date('Y-m-d', strtotime($raw['added']));
			return $added;
		})
		->editColumn('last_appointment', function($raw){
			$last_appointment = date('Y-m-d', strtotime($raw['last_appointment']));
			return $last_appointment;
		})
		->editColumn('outstanding', function($raw){
			return number_format($raw['outstanding'], 2, '.', '');
		})
		->editColumn('name', function($raw){
			if(empty($raw['name'])){
				return "Walk-In";
			}
			return $raw['name'];
		})
		->rawColumns(['blocked','total_sales','added','last_appointment','outstanding'])
		->make(true);

		// dd($data);

		// echo $invoice_id;	
	}

	public function getClientListPDF(Request $request){
		$getClientList = $this->getClientList($request);
		$data = $getClientList->original['data'];
		$start_date = ($getClientList->original['input']['start_date']) ? $getClientList->original['input']['start_date'] : date('Y-m-d');
		$end_date = ($getClientList->original['input']['end_date']) ? $getClientList->original['input']['end_date'] : date('Y-m-d');
		// dd($getClientList->original['input']['start_date']);

		return PDF::loadView('pdfTemplates.clientListPDFReport', compact('data','start_date', 'end_date'))->setPaper('a4')->download('client_list.pdf');
	}

	public function getClientListCSV(Request $request){
		$getClientList = $this->getClientList($request);
		$data = $getClientList->original['data'];

		return Excel::download(new clientListCSVReport($data), 'client_list.csv');
	}

	public function getClientListExcel(Request $request){
		$getClientList = $this->getClientList($request);
		$data = $getClientList->original['data'];

		return Excel::download(new clientListExcelReport($data), 'client_list.xlsx');
	}
	
	public function clientRetention(){
		return view('analytics.client_retention');
	}

	public function getClientRetention(Request $request){
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

		$start_date = (!empty($request->start_date)) ? date('Y-m-d', strtotime($request->start_date)) : NULL;
		$end_date = (!empty($request->end_date)) ? date('Y-m-d', strtotime($request->end_date)) : NULL;

		$whereArray = [];

		if(!empty($start_date)){
			$whereArray[] = ['clients.created_at', '<=', $start_date];
		}
		// if(!empty($end_date)){
		// 	$whereArray[] = ['clients.created_at', '<=', $end_date];
		// }

		$data = Appointments::select(DB::raw('CONCAT(clients.firstname, " ",clients.lastname) as name'),'clients.mobileno as mobile_no', 'clients.email', DB::raw('SUM(invoice.inovice_final_total) as total_sales'),'appointments.client_id','appointments.location_id','appointments.staff_user_id as staff_id')
		->leftJoin('clients', 'clients.id', 'appointments.client_id')
		->leftJoin('invoice', 'invoice.id', 'appointments.invoice_id')
		->where($whereArray)
		->where('appointments.user_id',$AdminId)
		->where('appointments.is_cancelled','0')
		->whereIn('invoice.invoice_status', ['0','1'])
		->groupBy('name')
		->get()->toArray();

		$client_id = [];
		foreach($data as $dataKey => $dataValue){
			$client_id[] = $dataValue['client_id'];
		}

		
		// dd($client_id);

		$invoice_id = Invoice::where('user_id',$AdminId)->whereIn('invoice.client_id',$client_id)->whereIn('invoice.invoice_status',['0','1'])->pluck('id');

		$refundData = Invoice::select(DB::raw('CONCAT(clients.firstname, " ",clients.lastname) as name'), DB::raw('SUM(invoice.inovice_final_total) as total_sales'))
		->leftJoin('clients', 'clients.id', 'invoice.client_id')
		->where($whereArray)
		->where('invoice.user_id',$AdminId)
		->where('invoice.invoice_status', '2')
		->whereIn('invoice.original_invoice_id', $invoice_id)
		->groupBy('name')
		->get()->toArray();

		foreach($data as $data_Key => $data_Value){

			//substract refund
			foreach($refundData as $refundDataKey => $refundDataValue){
				if($data_Value['name'] == $refundDataValue['name']){
					$data[$data_Key]['total_sales'] -= $refundDataValue['total_sales'];
				}
			}

			//Adding last appointment and last location_name
			$last_appointment = Appointments::select('created_at','location_id')->where('client_id', $data_Value['client_id'])->orderBy('created_at','DESC')->first();
			$last_visit_sales = Invoice::select('inovice_final_total')->where('id', $last_appointment->location_id)->first();
			$staff = User::select(DB::raw("CONCAT(first_name,' ',last_name) as staff"))->where('id', $data_Value['staff_id'])->first();

			$temp = (strtotime(date('Y-m-d')) - strtotime($last_appointment->created_at));

			$days_absent = round($temp  / 60 / 60 / 24);


			$data[$data_Key]['last_appointment'] = $last_appointment->created_at;
			$data[$data_Key]['last_visit_sales'] = $last_visit_sales->inovice_final_total;
			$data[$data_Key]['staff'] = ($staff->staff) ? $staff->staff : '';
			$data[$data_Key]['days_absent'] = $days_absent;
		}
		// dd($data);

		return DataTables::of($data)
		->editColumn('total_sales', function($raw){
			return 'CA $'.number_format($raw['total_sales'], 2, '.', '');
		})
		->editColumn('last_visit_sales', function($raw){
			return 'CA $'.number_format($raw['last_visit_sales'], 2, '.', '');
		})
		->editColumn('last_appointment', function($raw){
			$last_appointment = date('Y-m-d', strtotime($raw['last_appointment']));
			return $last_appointment;
		})
		->editColumn('days_absent', function($raw){
			return number_format($raw['days_absent'], 0, '','');
		})
		->editColumn('name', function($raw){
			if(empty($raw['name'])){
				return "Walk-In";
			}
			return $raw['name'];
		})
		->rawColumns(['total_sales','last_visit_sales','last_appointment','days_absent','name'])
		->make(true);

		// dd($data);

		// echo $invoice_id;	
	}

	public function getClientRetentionPDF(Request $request){
		$getClientList = $this->getClientRetention($request);
		
		$data = $getClientList->original['data'];
		$start_date = ($getClientList->original['input']['start_date']) ? $getClientList->original['input']['start_date'] : date('Y-m-d');
		$end_date = ($getClientList->original['input']['end_date']) ? $getClientList->original['input']['end_date'] : date('Y-m-d');
		// dd($getClientList->original['input']['start_date']);

		return PDF::loadView('pdfTemplates.clientRetentionPDFReport', compact('data','start_date', 'end_date'))->setPaper('a4')->download('client_retention.pdf');
	}

	public function getClientRetentionCSV(Request $request){
		$getClientList = $this->getClientRetention($request);
		$data = $getClientList->original['data'];

		return Excel::download(new clientRetentionCSVReport($data), 'client_retention.csv');
	}

	public function getClientRetentionExcel(Request $request){
		$getClientList = $this->getClientRetention($request);
		$data = $getClientList->original['data'];

		return Excel::download(new clientRetentionExcelReport($data), 'client_retention.xlsx');
	}

	public function staffWorkingHours(){
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
		
		return view('analytics.staff_working_hours', compact('Locations','Staff'));
	}

	public function getStaffWorkingHoursReport(Request $request){
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

		$start_date = (!empty($request->start_date)) ? date('Y-m-d', strtotime($request->start_date)) : NULL;
		$end_date = (!empty($request->end_date)) ? date('Y-m-d', strtotime($request->end_date)) : NULL;
		$location_id = (!empty($request->location_id)) ? $request->location_id : NULL;
		$staff_id = (!empty($request->staff_id)) ? $request->staff_id : NULL;

		// $whereArray = [];

		// if(!empty($staff_id)){
		// 	$whereArray[] = ['staff.staff_user_id', '=', $staff_id];
		// }
		if(!empty($location_id)){
			// $whereArray[] = ['staff_locations.location_id', '=', $location_id];
			$staffIds = StaffLocations::where('location_id', $location_id)->pluck('staff_user_id')->toArray();
		}
		// if(!empty($end_date)){
		// 	$whereArray[] = ['clients.created_at', '<=', $end_date];
		// }
		$data = [];
		$finalTime = 0;
		$tempKey = 0;
		$isStaff = true;

		// print_r($staffIds);die;

		if(!empty($staffIds)){
			if(in_array($staff_id, $staffIds)){
				$tempData = StaffWorkingHours::select('staff_working_hours.date', 'staff_working_hours.start_time', 'staff_working_hours.end_time', 'staff_working_hours.repeats', 'staff_working_hours.endrepeat','staff_working_hours.remove_date', 'staff_working_hours.remove_type','staff_working_hours.staff_id','staff.staff_user_id', DB::raw("CONCAT(users.first_name,' ',users.last_name) as staff_name"))->leftJoin('staff','staff.id','staff_working_hours.staff_id')->leftJoin('users','users.id','staff.staff_user_id')->where('staff_working_hours.user_id',$AdminId)->where('staff.staff_user_id',$staff_id)->get()->toArray();

				foreach($tempData as $tempDataKey => $tempDataValue){
					if($tempDataValue['repeats'] == 1){
						if($tempDataValue['endrepeat'] == 0){
							if($tempDataValue['remove_date'] != 0){
								if($tempDataValue['remove_type'] == 1){
									//remove all after this date
									if(strtotime($tempDataValue['date']) <= strtotime($end_date)){
										if(strtotime($tempDataValue['date']) >= strtotime($start_date)){
											//start from database date
											$customStartDate = $tempDataValue['date'];
											$customEndDate = $end_date;
											if(strtotime($tempDataValue['remove_date']) <= strtotime($end_date)){
												$customEndDate = $tempDataValue['remove_date'];
											}
											
											while(strtotime($customStartDate) <= strtotime($customEndDate)){
												$start_times = json_decode($tempDataValue['start_time'],true);
												$end_times = json_decode($tempDataValue['end_time'],true);
			
												$start_time_in_seconds = strtotime($start_times[0]);
												$end_time_in_seconds = strtotime($end_times[0]);
												$duration = $end_time_in_seconds - $start_time_in_seconds;
												$finalTime += $duration;
			
												$data[$tempKey]['start_time'] = $start_times[0];
												$data[$tempKey]['end_time'] = $end_times[0];
												$data[$tempKey]['duration'] = $this->formatTime($duration);
												$data[$tempKey]['date'] = $customStartDate;
												$data[$tempKey]['staff_name'] = $tempDataValue['staff_name'];
			
												$tempKey++;
			
												if(!empty($start_times[1]) && !empty($end_times[1])){
													$start_time_in_seconds1 = strtotime($start_times[1]);
													$end_time_in_seconds1 = strtotime($end_times[1]);
													$duration1 = $end_time_in_seconds1 - $start_time_in_seconds1;
													$finalTime += $duration1;
													
													$data[$tempKey]['start_time'] = $start_times[1];
													$data[$tempKey]['end_time'] = $end_times[1];
													$data[$tempKey]['duration'] = $this->formatTime($duration1);
													$data[$tempKey]['date'] = $customStartDate;
													$data[$tempKey]['staff_name'] = $tempDataValue['staff_name'];
													$tempKey++;
												}
												$customStartDate = date('Y-m-d', strtotime($customStartDate . " +7 days"));
											}
			
										}else{
											//start fom custom range start date
											$customStartDate = $start_date;
											$customEndDate = $end_date;
											if(strtotime($tempDataValue['remove_date']) <= strtotime($end_date)){
												$customEndDate = $tempDataValue['remove_date'];
											}

											while(strtotime($customStartDate) <= strtotime($customEndDate)){
												$start_times = json_decode($tempDataValue['start_time'],true);
												$end_times = json_decode($tempDataValue['end_time'],true);
			
												$start_time_in_seconds = strtotime($start_times[0]);
												$end_time_in_seconds = strtotime($end_times[0]);
												$duration = $end_time_in_seconds - $start_time_in_seconds;
												$finalTime += $duration;
			
												$data[$tempKey]['start_time'] = $start_times[0];
												$data[$tempKey]['end_time'] = $end_times[0];
												$data[$tempKey]['duration'] = $this->formatTime($duration);
												$data[$tempKey]['date'] = $customStartDate;
												$data[$tempKey]['staff_name'] = $tempDataValue['staff_name'];
			
												$tempKey++;
			
												if(!empty($start_times[1]) && !empty($end_times[1])){
													$start_time_in_seconds1 = strtotime($start_times[1]);
													$end_time_in_seconds1 = strtotime($end_times[1]);
													$duration1 = $end_time_in_seconds1 - $start_time_in_seconds1;
													$finalTime += $duration1;
													
													$data[$tempKey]['start_time'] = $start_times[1];
													$data[$tempKey]['end_time'] = $end_times[1];
													$data[$tempKey]['duration'] = $this->formatTime($duration1);
													$data[$tempKey]['date'] = $customStartDate;
													$data[$tempKey]['staff_name'] = $tempDataValue['staff_name'];
													$tempKey++;
												}
												$customStartDate = date('Y-m-d', strtotime($customStartDate . " +7 days"));
											}
										}
									}
								}elseif($tempDataValue['remove_type'] == 2){
									//remove only this date
									if(strtotime($tempDataValue['date']) <= strtotime($end_date)){
										if(strtotime($tempDataValue['date']) >= strtotime($start_date)){
											//start from database date
											$customStartDate = $tempDataValue['date'];
											while(strtotime($customStartDate) <= strtotime($end_date)){

												if(strtotime($customStartDate) == strtotime($tempDataValue['remove_date'])){
													continue;
												}
												$start_times = json_decode($tempDataValue['start_time'],true);
												$end_times = json_decode($tempDataValue['end_time'],true);
			
												$start_time_in_seconds = strtotime($start_times[0]);
												$end_time_in_seconds = strtotime($end_times[0]);
												$duration = $end_time_in_seconds - $start_time_in_seconds;
												$finalTime += $duration;
			
												$data[$tempKey]['start_time'] = $start_times[0];
												$data[$tempKey]['end_time'] = $end_times[0];
												$data[$tempKey]['duration'] = $this->formatTime($duration);
												$data[$tempKey]['date'] = $customStartDate;
												$data[$tempKey]['staff_name'] = $tempDataValue['staff_name'];
			
												$tempKey++;
			
												if(!empty($start_times[1]) && !empty($end_times[1])){
													$start_time_in_seconds1 = strtotime($start_times[1]);
													$end_time_in_seconds1 = strtotime($end_times[1]);
													$duration1 = $end_time_in_seconds1 - $start_time_in_seconds1;
													$finalTime += $duration1;
													
													$data[$tempKey]['start_time'] = $start_times[1];
													$data[$tempKey]['end_time'] = $end_times[1];
													$data[$tempKey]['duration'] = $this->formatTime($duration1);
													$data[$tempKey]['date'] = $customStartDate;
													$data[$tempKey]['staff_name'] = $tempDataValue['staff_name'];
													$tempKey++;
												}
												$customStartDate = date('Y-m-d', strtotime($customStartDate . " +7 days"));
											}
			
										}else{
											//start fom custom range start date
											$customStartDate = $start_date;

											while(strtotime($customStartDate) <= strtotime($end_date)){
												if(strtotime($customStartDate) == strtotime($tempDataValue['remove_date'])){
													continue;
												}
												$start_times = json_decode($tempDataValue['start_time'],true);
												$end_times = json_decode($tempDataValue['end_time'],true);
			
												$start_time_in_seconds = strtotime($start_times[0]);
												$end_time_in_seconds = strtotime($end_times[0]);
												$duration = $end_time_in_seconds - $start_time_in_seconds;
												$finalTime += $duration;
			
												$data[$tempKey]['start_time'] = $start_times[0];
												$data[$tempKey]['end_time'] = $end_times[0];
												$data[$tempKey]['duration'] = $this->formatTime($duration);
												$data[$tempKey]['date'] = $customStartDate;
												$data[$tempKey]['staff_name'] = $tempDataValue['staff_name'];
			
												$tempKey++;
			
												if(!empty($start_times[1]) && !empty($end_times[1])){
													$start_time_in_seconds1 = strtotime($start_times[1]);
													$end_time_in_seconds1 = strtotime($end_times[1]);
													$duration1 = $end_time_in_seconds1 - $start_time_in_seconds1;
													$finalTime += $duration1;
													
													$data[$tempKey]['start_time'] = $start_times[1];
													$data[$tempKey]['end_time'] = $end_times[1];
													$data[$tempKey]['duration'] = $this->formatTime($duration1);
													$data[$tempKey]['date'] = $customStartDate;
													$data[$tempKey]['staff_name'] = $tempDataValue['staff_name'];
													$tempKey++;
												}
												$customStartDate = date('Y-m-d', strtotime($customStartDate . " +7 days"));
											}
										}
									}
								}else{

								}
							}else{
								if(strtotime($tempDataValue['date']) <= strtotime($end_date)){
									if(strtotime($tempDataValue['date']) >= strtotime($start_date)){
										//start from database date
										$customStartDate = $tempDataValue['date'];
										while(strtotime($customStartDate) <= strtotime($end_date)){
											$start_times = json_decode($tempDataValue['start_time'],true);
											$end_times = json_decode($tempDataValue['end_time'],true);

											$start_time_in_seconds = strtotime($start_times[0]);
											$end_time_in_seconds = strtotime($end_times[0]);
											$duration = $end_time_in_seconds - $start_time_in_seconds;
											$finalTime += $duration;

											$data[$tempKey]['start_time'] = $start_times[0];
											$data[$tempKey]['end_time'] = $end_times[0];
											$data[$tempKey]['duration'] = $this->formatTime($duration);
											$data[$tempKey]['date'] = $customStartDate;
											$data[$tempKey]['staff_name'] = $tempDataValue['staff_name'];

											$tempKey++;

											if(!empty($start_times[1]) && !empty($end_times[1])){
												$start_time_in_seconds1 = strtotime($start_times[1]);
												$end_time_in_seconds1 = strtotime($end_times[1]);
												$duration1 = $end_time_in_seconds1 - $start_time_in_seconds1;
												$finalTime += $duration1;
												
												$data[$tempKey]['start_time'] = $start_times[1];
												$data[$tempKey]['end_time'] = $end_times[1];
												$data[$tempKey]['duration'] = $this->formatTime($duration1);
												$data[$tempKey]['date'] = $customStartDate;
												$data[$tempKey]['staff_name'] = $tempDataValue['staff_name'];
												$tempKey++;
											}
											$customStartDate = date('Y-m-d', strtotime($customStartDate . " +7 days"));
										}

									}else{
										//start fom custom range start date
										$customStartDate = $start_date;
										while(strtotime($customStartDate) <= strtotime($end_date)){
											$start_times = json_decode($tempDataValue['start_time'],true);
											$end_times = json_decode($tempDataValue['end_time'],true);

											$start_time_in_seconds = strtotime($start_times[0]);
											$end_time_in_seconds = strtotime($end_times[0]);
											$duration = $end_time_in_seconds - $start_time_in_seconds;
											$finalTime += $duration;

											$data[$tempKey]['start_time'] = $start_times[0];
											$data[$tempKey]['end_time'] = $end_times[0];
											$data[$tempKey]['duration'] = $this->formatTime($duration);
											$data[$tempKey]['date'] = $customStartDate;
											$data[$tempKey]['staff_name'] = $tempDataValue['staff_name'];

											$tempKey++;

											if(!empty($start_times[1]) && !empty($end_times[1])){
												$start_time_in_seconds1 = strtotime($start_times[1]);
												$end_time_in_seconds1 = strtotime($end_times[1]);
												$duration1 = $end_time_in_seconds1 - $start_time_in_seconds1;
												$finalTime += $duration1;
												
												$data[$tempKey]['start_time'] = $start_times[1];
												$data[$tempKey]['end_time'] = $end_times[1];
												$data[$tempKey]['duration'] = $this->formatTime($duration1);
												$data[$tempKey]['date'] = $customStartDate;
												$data[$tempKey]['staff_name'] = $tempDataValue['staff_name'];
												$tempKey++;
											}
											$customStartDate = date('Y-m-d', strtotime($customStartDate . " +7 days"));
										}
									}
								}
							}
						}else{
							if(strtotime($tempDataValue['endrepeat']) >= strtotime($start_date)){
								if($tempDataValue['remove_date'] != 0){
									if($tempDataValue['remove_type'] == 1){
										//remove all after this date
										if(strtotime($tempDataValue['date']) <= strtotime($end_date)){
											if(strtotime($tempDataValue['date']) >= strtotime($start_date)){
												//start from database date
												$customStartDate = $tempDataValue['date'];
												$customEndDate = $end_date;
												if(strtotime($tempDataValue['remove_date']) <= strtotime($end_date)){
													$customEndDate = $tempDataValue['remove_date'];
												}
												
												while(strtotime($customStartDate) <= strtotime($customEndDate)){
													$start_times = json_decode($tempDataValue['start_time'],true);
													$end_times = json_decode($tempDataValue['end_time'],true);
				
													$start_time_in_seconds = strtotime($start_times[0]);
													$end_time_in_seconds = strtotime($end_times[0]);
													$duration = $end_time_in_seconds - $start_time_in_seconds;
													$finalTime += $duration;
				
													$data[$tempKey]['start_time'] = $start_times[0];
													$data[$tempKey]['end_time'] = $end_times[0];
													$data[$tempKey]['duration'] = $this->formatTime($duration);
													$data[$tempKey]['date'] = $customStartDate;
													$data[$tempKey]['staff_name'] = $tempDataValue['staff_name'];
				
													$tempKey++;
				
													if(!empty($start_times[1]) && !empty($end_times[1])){
														$start_time_in_seconds1 = strtotime($start_times[1]);
														$end_time_in_seconds1 = strtotime($end_times[1]);
														$duration1 = $end_time_in_seconds1 - $start_time_in_seconds1;
														$finalTime += $duration1;
														
														$data[$tempKey]['start_time'] = $start_times[1];
														$data[$tempKey]['end_time'] = $end_times[1];
														$data[$tempKey]['duration'] = $this->formatTime($duration1);
														$data[$tempKey]['date'] = $customStartDate;
														$data[$tempKey]['staff_name'] = $tempDataValue['staff_name'];
														$tempKey++;
													}
													$customStartDate = date('Y-m-d', strtotime($customStartDate . " +7 days"));
												}
				
											}else{
												//start fom custom range start date
												$customStartDate = $start_date;
												$customEndDate = $end_date;
												if(strtotime($tempDataValue['remove_date']) <= strtotime($end_date)){
													$customEndDate = $tempDataValue['remove_date'];
												}
			
												while(strtotime($customStartDate) <= strtotime($customEndDate)){
													$start_times = json_decode($tempDataValue['start_time'],true);
													$end_times = json_decode($tempDataValue['end_time'],true);
				
													$start_time_in_seconds = strtotime($start_times[0]);
													$end_time_in_seconds = strtotime($end_times[0]);
													$duration = $end_time_in_seconds - $start_time_in_seconds;
													$finalTime += $duration;
				
													$data[$tempKey]['start_time'] = $start_times[0];
													$data[$tempKey]['end_time'] = $end_times[0];
													$data[$tempKey]['duration'] = $this->formatTime($duration);
													$data[$tempKey]['date'] = $customStartDate;
													$data[$tempKey]['staff_name'] = $tempDataValue['staff_name'];
				
													$tempKey++;
				
													if(!empty($start_times[1]) && !empty($end_times[1])){
														$start_time_in_seconds1 = strtotime($start_times[1]);
														$end_time_in_seconds1 = strtotime($end_times[1]);
														$duration1 = $end_time_in_seconds1 - $start_time_in_seconds1;
														$finalTime += $duration1;
														
														$data[$tempKey]['start_time'] = $start_times[1];
														$data[$tempKey]['end_time'] = $end_times[1];
														$data[$tempKey]['duration'] = $this->formatTime($duration1);
														$data[$tempKey]['date'] = $customStartDate;
														$data[$tempKey]['staff_name'] = $tempDataValue['staff_name'];
														$tempKey++;
													}
													$customStartDate = date('Y-m-d', strtotime($customStartDate . " +7 days"));
												}
											}
										}
									}elseif($tempDataValue['remove_type'] == 2){
										//remove only this date
										if(strtotime($tempDataValue['date']) <= strtotime($end_date)){
											if(strtotime($tempDataValue['date']) >= strtotime($start_date)){
												//start from database date
												$customStartDate = $tempDataValue['date'];
												$customEndDate = $end_date;
												if(strtotime($tempDataValue['endrepeat']) <= strtotime($end_date)){
													$customEndDate = $tempDataValue['endrepeat'];
												}
												while(strtotime($customStartDate) <= strtotime($customEndDate)){
			
													if(strtotime($customStartDate) == strtotime($tempDataValue['remove_date'])){
														continue;
													}
													$start_times = json_decode($tempDataValue['start_time'],true);
													$end_times = json_decode($tempDataValue['end_time'],true);
				
													$start_time_in_seconds = strtotime($start_times[0]);
													$end_time_in_seconds = strtotime($end_times[0]);
													$duration = $end_time_in_seconds - $start_time_in_seconds;
													$finalTime += $duration;
				
													$data[$tempKey]['start_time'] = $start_times[0];
													$data[$tempKey]['end_time'] = $end_times[0];
													$data[$tempKey]['duration'] = $this->formatTime($duration);
													$data[$tempKey]['date'] = $customStartDate;
													$data[$tempKey]['staff_name'] = $tempDataValue['staff_name'];
				
													$tempKey++;
				
													if(!empty($start_times[1]) && !empty($end_times[1])){
														$start_time_in_seconds1 = strtotime($start_times[1]);
														$end_time_in_seconds1 = strtotime($end_times[1]);
														$duration1 = $end_time_in_seconds1 - $start_time_in_seconds1;
														$finalTime += $duration1;
														
														$data[$tempKey]['start_time'] = $start_times[1];
														$data[$tempKey]['end_time'] = $end_times[1];
														$data[$tempKey]['duration'] = $this->formatTime($duration1);
														$data[$tempKey]['date'] = $customStartDate;
														$data[$tempKey]['staff_name'] = $tempDataValue['staff_name'];
														$tempKey++;
													}
													$customStartDate = date('Y-m-d', strtotime($customStartDate . " +7 days"));
												}
				
											}else{
												//start fom custom range start date
												$customStartDate = $start_date;
			
												while(strtotime($customStartDate) <= strtotime($end_date)){
													if(strtotime($customStartDate) == strtotime($tempDataValue['remove_date'])){
														continue;
													}
													$start_times = json_decode($tempDataValue['start_time'],true);
													$end_times = json_decode($tempDataValue['end_time'],true);
				
													$start_time_in_seconds = strtotime($start_times[0]);
													$end_time_in_seconds = strtotime($end_times[0]);
													$duration = $end_time_in_seconds - $start_time_in_seconds;
													$finalTime += $duration;
				
													$data[$tempKey]['start_time'] = $start_times[0];
													$data[$tempKey]['end_time'] = $end_times[0];
													$data[$tempKey]['duration'] = $this->formatTime($duration);
													$data[$tempKey]['date'] = $customStartDate;
													$data[$tempKey]['staff_name'] = $tempDataValue['staff_name'];
				
													$tempKey++;
				
													if(!empty($start_times[1]) && !empty($end_times[1])){
														$start_time_in_seconds1 = strtotime($start_times[1]);
														$end_time_in_seconds1 = strtotime($end_times[1]);
														$duration1 = $end_time_in_seconds1 - $start_time_in_seconds1;
														$finalTime += $duration1;
														
														$data[$tempKey]['start_time'] = $start_times[1];
														$data[$tempKey]['end_time'] = $end_times[1];
														$data[$tempKey]['duration'] = $this->formatTime($duration1);
														$data[$tempKey]['date'] = $customStartDate;
														$data[$tempKey]['staff_name'] = $tempDataValue['staff_name'];
														$tempKey++;
													}
													$customStartDate = date('Y-m-d', strtotime($customStartDate . " +7 days"));
												}
											}
										}
									}else{
			
									}
								}else{
									
								}
							}else{

							}
						}
					}else{
						if(( strtotime($tempDataValue['date']) >= strtotime($start_date) ) && ( strtotime($tempDataValue['date']) <= strtotime($end_date) )){
							$start_times = json_decode($tempDataValue['start_time'],true);
							$end_times = json_decode($tempDataValue['end_time'],true);

							$start_time_in_seconds = strtotime($start_times[0]);
							$end_time_in_seconds = strtotime($end_times[0]);
							$duration = $end_time_in_seconds - $start_time_in_seconds;
							$finalTime += $duration;

							$data[$tempKey]['start_time'] = $start_times[0];
							$data[$tempKey]['end_time'] = $end_times[0];
							$data[$tempKey]['duration'] = $this->formatTime($duration);
							$data[$tempKey]['date'] = $tempDataValue['date'];
							$data[$tempKey]['staff_name'] = $tempDataValue['staff_name'];

							$tempKey++;

							if(!empty($start_times[1]) && !empty($end_times[1])){
								$start_time_in_seconds1 = strtotime($start_times[1]);
								$end_time_in_seconds1 = strtotime($end_times[1]);
								$duration1 = $end_time_in_seconds1 - $start_time_in_seconds1;
								$finalTime += $duration1;
								
								$data[$tempKey]['start_time'] = $start_times[1];
								$data[$tempKey]['end_time'] = $end_times[1];
								$data[$tempKey]['duration'] = $this->formatTime($duration1);
								$data[$tempKey]['date'] = $tempDataValue['date'];
								$data[$tempKey]['staff_name'] = $tempDataValue['staff_name'];
								$tempKey++;

							}

						}
					}
				}
			}else{
				$isStaff = false;
			}
		}else{
			$tempData = StaffWorkingHours::select('staff_working_hours.date', 'staff_working_hours.start_time', 'staff_working_hours.end_time', 'staff_working_hours.repeats', 'staff_working_hours.endrepeat','staff_working_hours.remove_date', 'staff_working_hours.remove_type','staff_working_hours.staff_id','staff.staff_user_id', DB::raw("CONCAT(users.first_name,' ',users.last_name) as staff_name"))->leftJoin('staff','staff.id','staff_working_hours.staff_id')->leftJoin('users','users.id','staff.staff_user_id')->where('staff_working_hours.user_id',$AdminId)->where('staff.staff_user_id',$staff_id)->get()->toArray();

		// print_r($data);die;

			foreach($tempData as $tempDataKey => $tempDataValue){
				if($tempDataValue['repeats'] == 1){
					if($tempDataValue['endrepeat'] == 0){
						if($tempDataValue['remove_date'] != 0){
							if($tempDataValue['remove_type'] == 1){
								//remove all after this date
								if(strtotime($tempDataValue['date']) <= strtotime($end_date)){
									if(strtotime($tempDataValue['date']) >= strtotime($start_date)){
										//start from database date
										$customStartDate = $tempDataValue['date'];
										$customEndDate = $end_date;
										if(strtotime($tempDataValue['remove_date']) <= strtotime($end_date)){
											$customEndDate = $tempDataValue['remove_date'];
										}
										
										while(strtotime($customStartDate) <= strtotime($customEndDate)){
											$start_times = json_decode($tempDataValue['start_time'],true);
											$end_times = json_decode($tempDataValue['end_time'],true);
		
											$start_time_in_seconds = strtotime($start_times[0]);
											$end_time_in_seconds = strtotime($end_times[0]);
											$duration = $end_time_in_seconds - $start_time_in_seconds;
											$finalTime += $duration;
		
											$data[$tempKey]['start_time'] = $start_times[0];
											$data[$tempKey]['end_time'] = $end_times[0];
											$data[$tempKey]['duration'] = $this->formatTime($duration);
											$data[$tempKey]['date'] = $customStartDate;
											$data[$tempKey]['staff_name'] = $tempDataValue['staff_name'];
		
											$tempKey++;
		
											if(!empty($start_times[1]) && !empty($end_times[1])){
												$start_time_in_seconds1 = strtotime($start_times[1]);
												$end_time_in_seconds1 = strtotime($end_times[1]);
												$duration1 = $end_time_in_seconds1 - $start_time_in_seconds1;
												$finalTime += $duration1;
												
												$data[$tempKey]['start_time'] = $start_times[1];
												$data[$tempKey]['end_time'] = $end_times[1];
												$data[$tempKey]['duration'] = $this->formatTime($duration1);
												$data[$tempKey]['date'] = $customStartDate;
												$data[$tempKey]['staff_name'] = $tempDataValue['staff_name'];
												$tempKey++;
											}
											$customStartDate = date('Y-m-d', strtotime($customStartDate . " +7 days"));
										}
		
									}else{
										//start fom custom range start date
										$customStartDate = $start_date;
										$customEndDate = $end_date;
										if(strtotime($tempDataValue['remove_date']) <= strtotime($end_date)){
											$customEndDate = $tempDataValue['remove_date'];
										}

										while(strtotime($customStartDate) <= strtotime($customEndDate)){
											$start_times = json_decode($tempDataValue['start_time'],true);
											$end_times = json_decode($tempDataValue['end_time'],true);
		
											$start_time_in_seconds = strtotime($start_times[0]);
											$end_time_in_seconds = strtotime($end_times[0]);
											$duration = $end_time_in_seconds - $start_time_in_seconds;
											$finalTime += $duration;
		
											$data[$tempKey]['start_time'] = $start_times[0];
											$data[$tempKey]['end_time'] = $end_times[0];
											$data[$tempKey]['duration'] = $this->formatTime($duration);
											$data[$tempKey]['date'] = $customStartDate;
											$data[$tempKey]['staff_name'] = $tempDataValue['staff_name'];
		
											$tempKey++;
		
											if(!empty($start_times[1]) && !empty($end_times[1])){
												$start_time_in_seconds1 = strtotime($start_times[1]);
												$end_time_in_seconds1 = strtotime($end_times[1]);
												$duration1 = $end_time_in_seconds1 - $start_time_in_seconds1;
												$finalTime += $duration1;
												
												$data[$tempKey]['start_time'] = $start_times[1];
												$data[$tempKey]['end_time'] = $end_times[1];
												$data[$tempKey]['duration'] = $this->formatTime($duration1);
												$data[$tempKey]['date'] = $customStartDate;
												$data[$tempKey]['staff_name'] = $tempDataValue['staff_name'];
												$tempKey++;
											}
											$customStartDate = date('Y-m-d', strtotime($customStartDate . " +7 days"));
										}
									}
								}
							}elseif($tempDataValue['remove_type'] == 2){
								//remove only this date
								if(strtotime($tempDataValue['date']) <= strtotime($end_date)){
									if(strtotime($tempDataValue['date']) >= strtotime($start_date)){
										//start from database date
										$customStartDate = $tempDataValue['date'];
										while(strtotime($customStartDate) <= strtotime($end_date)){

											if(strtotime($customStartDate) == strtotime($tempDataValue['remove_date'])){
												continue;
											}
											$start_times = json_decode($tempDataValue['start_time'],true);
											$end_times = json_decode($tempDataValue['end_time'],true);
		
											$start_time_in_seconds = strtotime($start_times[0]);
											$end_time_in_seconds = strtotime($end_times[0]);
											$duration = $end_time_in_seconds - $start_time_in_seconds;
											$finalTime += $duration;
		
											$data[$tempKey]['start_time'] = $start_times[0];
											$data[$tempKey]['end_time'] = $end_times[0];
											$data[$tempKey]['duration'] = $this->formatTime($duration);
											$data[$tempKey]['date'] = $customStartDate;
											$data[$tempKey]['staff_name'] = $tempDataValue['staff_name'];
		
											$tempKey++;
		
											if(!empty($start_times[1]) && !empty($end_times[1])){
												$start_time_in_seconds1 = strtotime($start_times[1]);
												$end_time_in_seconds1 = strtotime($end_times[1]);
												$duration1 = $end_time_in_seconds1 - $start_time_in_seconds1;
												$finalTime += $duration1;
												
												$data[$tempKey]['start_time'] = $start_times[1];
												$data[$tempKey]['end_time'] = $end_times[1];
												$data[$tempKey]['duration'] = $this->formatTime($duration1);
												$data[$tempKey]['date'] = $customStartDate;
												$data[$tempKey]['staff_name'] = $tempDataValue['staff_name'];
												$tempKey++;
											}
											$customStartDate = date('Y-m-d', strtotime($customStartDate . " +7 days"));
										}
		
									}else{
										//start fom custom range start date
										$customStartDate = $start_date;

										while(strtotime($customStartDate) <= strtotime($end_date)){
											if(strtotime($customStartDate) == strtotime($tempDataValue['remove_date'])){
												continue;
											}
											$start_times = json_decode($tempDataValue['start_time'],true);
											$end_times = json_decode($tempDataValue['end_time'],true);
		
											$start_time_in_seconds = strtotime($start_times[0]);
											$end_time_in_seconds = strtotime($end_times[0]);
											$duration = $end_time_in_seconds - $start_time_in_seconds;
											$finalTime += $duration;
		
											$data[$tempKey]['start_time'] = $start_times[0];
											$data[$tempKey]['end_time'] = $end_times[0];
											$data[$tempKey]['duration'] = $this->formatTime($duration);
											$data[$tempKey]['date'] = $customStartDate;
											$data[$tempKey]['staff_name'] = $tempDataValue['staff_name'];
		
											$tempKey++;
		
											if(!empty($start_times[1]) && !empty($end_times[1])){
												$start_time_in_seconds1 = strtotime($start_times[1]);
												$end_time_in_seconds1 = strtotime($end_times[1]);
												$duration1 = $end_time_in_seconds1 - $start_time_in_seconds1;
												$finalTime += $duration1;
												
												$data[$tempKey]['start_time'] = $start_times[1];
												$data[$tempKey]['end_time'] = $end_times[1];
												$data[$tempKey]['duration'] = $this->formatTime($duration1);
												$data[$tempKey]['date'] = $customStartDate;
												$data[$tempKey]['staff_name'] = $tempDataValue['staff_name'];
												$tempKey++;
											}
											$customStartDate = date('Y-m-d', strtotime($customStartDate . " +7 days"));
										}
									}
								}
							}else{

							}
						}else{
							if(strtotime($tempDataValue['date']) <= strtotime($end_date)){
								if(strtotime($tempDataValue['date']) >= strtotime($start_date)){
									//start from database date
									$customStartDate = $tempDataValue['date'];
									while(strtotime($customStartDate) <= strtotime($end_date)){
										$start_times = json_decode($tempDataValue['start_time'],true);
										$end_times = json_decode($tempDataValue['end_time'],true);

										$start_time_in_seconds = strtotime($start_times[0]);
										$end_time_in_seconds = strtotime($end_times[0]);
										$duration = $end_time_in_seconds - $start_time_in_seconds;
										$finalTime += $duration;

										$data[$tempKey]['start_time'] = $start_times[0];
										$data[$tempKey]['end_time'] = $end_times[0];
										$data[$tempKey]['duration'] = $this->formatTime($duration);
										$data[$tempKey]['date'] = $customStartDate;
										$data[$tempKey]['staff_name'] = $tempDataValue['staff_name'];

										$tempKey++;

										if(!empty($start_times[1]) && !empty($end_times[1])){
											$start_time_in_seconds1 = strtotime($start_times[1]);
											$end_time_in_seconds1 = strtotime($end_times[1]);
											$duration1 = $end_time_in_seconds1 - $start_time_in_seconds1;
											$finalTime += $duration1;
											
											$data[$tempKey]['start_time'] = $start_times[1];
											$data[$tempKey]['end_time'] = $end_times[1];
											$data[$tempKey]['duration'] = $this->formatTime($duration1);
											$data[$tempKey]['date'] = $customStartDate;
											$data[$tempKey]['staff_name'] = $tempDataValue['staff_name'];
											$tempKey++;
										}
										$customStartDate = date('Y-m-d', strtotime($customStartDate . " +7 days"));
									}

								}else{
									//start fom custom range start date
									$customStartDate = $start_date;
									while(strtotime($customStartDate) <= strtotime($end_date)){
										$start_times = json_decode($tempDataValue['start_time'],true);
										$end_times = json_decode($tempDataValue['end_time'],true);

										$start_time_in_seconds = strtotime($start_times[0]);
										$end_time_in_seconds = strtotime($end_times[0]);
										$duration = $end_time_in_seconds - $start_time_in_seconds;
										$finalTime += $duration;

										$data[$tempKey]['start_time'] = $start_times[0];
										$data[$tempKey]['end_time'] = $end_times[0];
										$data[$tempKey]['duration'] = $this->formatTime($duration);
										$data[$tempKey]['date'] = $customStartDate;
										$data[$tempKey]['staff_name'] = $tempDataValue['staff_name'];

										$tempKey++;

										if(!empty($start_times[1]) && !empty($end_times[1])){
											$start_time_in_seconds1 = strtotime($start_times[1]);
											$end_time_in_seconds1 = strtotime($end_times[1]);
											$duration1 = $end_time_in_seconds1 - $start_time_in_seconds1;
											$finalTime += $duration1;
											
											$data[$tempKey]['start_time'] = $start_times[1];
											$data[$tempKey]['end_time'] = $end_times[1];
											$data[$tempKey]['duration'] = $this->formatTime($duration1);
											$data[$tempKey]['date'] = $customStartDate;
											$data[$tempKey]['staff_name'] = $tempDataValue['staff_name'];
											$tempKey++;
										}
										$customStartDate = date('Y-m-d', strtotime($customStartDate . " +7 days"));
									}
								}
							}
						}
					}else{
						if(strtotime($tempDataValue['endrepeat']) >= strtotime($start_date)){
							if($tempDataValue['remove_date'] != 0){
								if($tempDataValue['remove_type'] == 1){
									//remove all after this date
									if(strtotime($tempDataValue['date']) <= strtotime($end_date)){
										if(strtotime($tempDataValue['date']) >= strtotime($start_date)){
											//start from database date
											$customStartDate = $tempDataValue['date'];
											$customEndDate = $end_date;
											if(strtotime($tempDataValue['remove_date']) <= strtotime($end_date)){
												$customEndDate = $tempDataValue['remove_date'];
											}
											
											while(strtotime($customStartDate) <= strtotime($customEndDate)){
												$start_times = json_decode($tempDataValue['start_time'],true);
												$end_times = json_decode($tempDataValue['end_time'],true);
			
												$start_time_in_seconds = strtotime($start_times[0]);
												$end_time_in_seconds = strtotime($end_times[0]);
												$duration = $end_time_in_seconds - $start_time_in_seconds;
												$finalTime += $duration;
			
												$data[$tempKey]['start_time'] = $start_times[0];
												$data[$tempKey]['end_time'] = $end_times[0];
												$data[$tempKey]['duration'] = $this->formatTime($duration);
												$data[$tempKey]['date'] = $customStartDate;
												$data[$tempKey]['staff_name'] = $tempDataValue['staff_name'];
			
												$tempKey++;
			
												if(!empty($start_times[1]) && !empty($end_times[1])){
													$start_time_in_seconds1 = strtotime($start_times[1]);
													$end_time_in_seconds1 = strtotime($end_times[1]);
													$duration1 = $end_time_in_seconds1 - $start_time_in_seconds1;
													$finalTime += $duration1;
													
													$data[$tempKey]['start_time'] = $start_times[1];
													$data[$tempKey]['end_time'] = $end_times[1];
													$data[$tempKey]['duration'] = $this->formatTime($duration1);
													$data[$tempKey]['date'] = $customStartDate;
													$data[$tempKey]['staff_name'] = $tempDataValue['staff_name'];
													$tempKey++;
												}
												$customStartDate = date('Y-m-d', strtotime($customStartDate . " +7 days"));
											}
			
										}else{
											//start fom custom range start date
											$customStartDate = $start_date;
											$customEndDate = $end_date;
											if(strtotime($tempDataValue['remove_date']) <= strtotime($end_date)){
												$customEndDate = $tempDataValue['remove_date'];
											}
		
											while(strtotime($customStartDate) <= strtotime($customEndDate)){
												$start_times = json_decode($tempDataValue['start_time'],true);
												$end_times = json_decode($tempDataValue['end_time'],true);
			
												$start_time_in_seconds = strtotime($start_times[0]);
												$end_time_in_seconds = strtotime($end_times[0]);
												$duration = $end_time_in_seconds - $start_time_in_seconds;
												$finalTime += $duration;
			
												$data[$tempKey]['start_time'] = $start_times[0];
												$data[$tempKey]['end_time'] = $end_times[0];
												$data[$tempKey]['duration'] = $this->formatTime($duration);
												$data[$tempKey]['date'] = $customStartDate;
												$data[$tempKey]['staff_name'] = $tempDataValue['staff_name'];
			
												$tempKey++;
			
												if(!empty($start_times[1]) && !empty($end_times[1])){
													$start_time_in_seconds1 = strtotime($start_times[1]);
													$end_time_in_seconds1 = strtotime($end_times[1]);
													$duration1 = $end_time_in_seconds1 - $start_time_in_seconds1;
													$finalTime += $duration1;
													
													$data[$tempKey]['start_time'] = $start_times[1];
													$data[$tempKey]['end_time'] = $end_times[1];
													$data[$tempKey]['duration'] = $this->formatTime($duration1);
													$data[$tempKey]['date'] = $customStartDate;
													$data[$tempKey]['staff_name'] = $tempDataValue['staff_name'];
													$tempKey++;
												}
												$customStartDate = date('Y-m-d', strtotime($customStartDate . " +7 days"));
											}
										}
									}
								}elseif($tempDataValue['remove_type'] == 2){
									//remove only this date
									if(strtotime($tempDataValue['date']) <= strtotime($end_date)){
										if(strtotime($tempDataValue['date']) >= strtotime($start_date)){
											//start from database date
											$customStartDate = $tempDataValue['date'];
											$customEndDate = $end_date;
											if(strtotime($tempDataValue['endrepeat']) <= strtotime($end_date)){
												$customEndDate = $tempDataValue['endrepeat'];
											}
											while(strtotime($customStartDate) <= strtotime($customEndDate)){
		
												if(strtotime($customStartDate) == strtotime($tempDataValue['remove_date'])){
													continue;
												}
												$start_times = json_decode($tempDataValue['start_time'],true);
												$end_times = json_decode($tempDataValue['end_time'],true);
			
												$start_time_in_seconds = strtotime($start_times[0]);
												$end_time_in_seconds = strtotime($end_times[0]);
												$duration = $end_time_in_seconds - $start_time_in_seconds;
												$finalTime += $duration;
			
												$data[$tempKey]['start_time'] = $start_times[0];
												$data[$tempKey]['end_time'] = $end_times[0];
												$data[$tempKey]['duration'] = $this->formatTime($duration);
												$data[$tempKey]['date'] = $customStartDate;
												$data[$tempKey]['staff_name'] = $tempDataValue['staff_name'];
			
												$tempKey++;
			
												if(!empty($start_times[1]) && !empty($end_times[1])){
													$start_time_in_seconds1 = strtotime($start_times[1]);
													$end_time_in_seconds1 = strtotime($end_times[1]);
													$duration1 = $end_time_in_seconds1 - $start_time_in_seconds1;
													$finalTime += $duration1;
													
													$data[$tempKey]['start_time'] = $start_times[1];
													$data[$tempKey]['end_time'] = $end_times[1];
													$data[$tempKey]['duration'] = $this->formatTime($duration1);
													$data[$tempKey]['date'] = $customStartDate;
													$data[$tempKey]['staff_name'] = $tempDataValue['staff_name'];
													$tempKey++;
												}
												$customStartDate = date('Y-m-d', strtotime($customStartDate . " +7 days"));
											}
			
										}else{
											//start fom custom range start date
											$customStartDate = $start_date;
		
											while(strtotime($customStartDate) <= strtotime($end_date)){
												if(strtotime($customStartDate) == strtotime($tempDataValue['remove_date'])){
													continue;
												}
												$start_times = json_decode($tempDataValue['start_time'],true);
												$end_times = json_decode($tempDataValue['end_time'],true);
			
												$start_time_in_seconds = strtotime($start_times[0]);
												$end_time_in_seconds = strtotime($end_times[0]);
												$duration = $end_time_in_seconds - $start_time_in_seconds;
												$finalTime += $duration;
			
												$data[$tempKey]['start_time'] = $start_times[0];
												$data[$tempKey]['end_time'] = $end_times[0];
												$data[$tempKey]['duration'] = $this->formatTime($duration);
												$data[$tempKey]['date'] = $customStartDate;
												$data[$tempKey]['staff_name'] = $tempDataValue['staff_name'];
			
												$tempKey++;
			
												if(!empty($start_times[1]) && !empty($end_times[1])){
													$start_time_in_seconds1 = strtotime($start_times[1]);
													$end_time_in_seconds1 = strtotime($end_times[1]);
													$duration1 = $end_time_in_seconds1 - $start_time_in_seconds1;
													$finalTime += $duration1;
													
													$data[$tempKey]['start_time'] = $start_times[1];
													$data[$tempKey]['end_time'] = $end_times[1];
													$data[$tempKey]['duration'] = $this->formatTime($duration1);
													$data[$tempKey]['date'] = $customStartDate;
													$data[$tempKey]['staff_name'] = $tempDataValue['staff_name'];
													$tempKey++;
												}
												$customStartDate = date('Y-m-d', strtotime($customStartDate . " +7 days"));
											}
										}
									}
								}else{
		
								}
							}else{
								
							}
						}else{

						}
					}
				}else{
					if(( strtotime($tempDataValue['date']) >= strtotime($start_date) ) && ( strtotime($tempDataValue['date']) <= strtotime($end_date) )){
						$start_times = json_decode($tempDataValue['start_time'],true);
						$end_times = json_decode($tempDataValue['end_time'],true);

						$start_time_in_seconds = strtotime($start_times[0]);
						$end_time_in_seconds = strtotime($end_times[0]);
						$duration = $end_time_in_seconds - $start_time_in_seconds;
						$finalTime += $duration;

						$data[$tempKey]['start_time'] = $start_times[0];
						$data[$tempKey]['end_time'] = $end_times[0];
						$data[$tempKey]['duration'] = $this->formatTime($duration);
						$data[$tempKey]['date'] = $tempDataValue['date'];
						$data[$tempKey]['staff_name'] = $tempDataValue['staff_name'];

						$tempKey++;

						if(!empty($start_times[1]) && !empty($end_times[1])){
							$start_time_in_seconds1 = strtotime($start_times[1]);
							$end_time_in_seconds1 = strtotime($end_times[1]);
							$duration1 = $end_time_in_seconds1 - $start_time_in_seconds1;
							$finalTime += $duration1;
							
							$data[$tempKey]['start_time'] = $start_times[1];
							$data[$tempKey]['end_time'] = $end_times[1];
							$data[$tempKey]['duration'] = $this->formatTime($duration1);
							$data[$tempKey]['date'] = $tempDataValue['date'];
							$data[$tempKey]['staff_name'] = $tempDataValue['staff_name'];
							$tempKey++;

						}

					}
				}
			}
		}


		if(!empty($data)){
			usort($data, function($element1, $element2) {
				$datetime1 = strtotime($element1['date']);
				$datetime2 = strtotime($element2['date']);
				return $datetime1 - $datetime2;
			});
		}

		// dd($this->formatTime($finalTime));


		$response = DataTables::of($data)
					->make(true);

		$responseData = $response->getData(true);

		$staff_name = User::select(DB::raw("CONCAT(first_name,' ',last_name) as staff_name"))->where('id',$staff_id)->first();
		$responseData['total'] = $this->formatTime($finalTime);
		$responseData['staff_name'] = $staff_name->staff_name;
		$responseData['isStaff'] = $isStaff;

		return $responseData;
	}

	public function getStaffWorkingHoursPDF(Request $request){

		// dd($request);
		$data = [];
		foreach($request['staff_id'] as $staff_id){
			$temp = new Request();
			$temp->location_id = $request->location_id;
			$temp->start_date = $request->start_date;
			$temp->end_date = $request->end_date;
			$temp->staff_id = $staff_id;
			
			// echo $temp->location_id;
			$getClientList = $this->getStaffWorkingHoursReport($temp);

			$data[] = $getClientList;
		}
		// dd($data);
		
		// $data = $getClientList->original['data'];
		$start_date = ($request->start_date) ? $request->start_date : date('Y-m-d');
		$end_date = ($request->end_date) ? $request->end_date : date('Y-m-d');
		// dd($getClientList->original['input']['start_date']);

		return PDF::loadView('pdfTemplates.staffWorkingHoursPDFReport', compact('data','start_date', 'end_date'))->setPaper('a4')->download('staff_working_hours.pdf');
	}

	public function getStaffWorkingHoursCSV(Request $request){
		$data = [];
		foreach($request['staff_id'] as $staff_id){
			$temp = new Request();
			$temp->location_id = $request->location_id;
			$temp->start_date = $request->start_date;
			$temp->end_date = $request->end_date;
			$temp->staff_id = $staff_id;
			
			// echo $temp->location_id;
			$getClientList = $this->getStaffWorkingHoursReport($temp);

			$data[] = $getClientList;
		}

		return Excel::download(new staffWorkingHoursCSVReport($data), 'staff_working_hours.csv');
	}

	public function getStaffWorkingHoursExcel(Request $request){
		$data = [];
		foreach($request['staff_id'] as $staff_id){
			$temp = new Request();
			$temp->location_id = $request->location_id;
			$temp->start_date = $request->start_date;
			$temp->end_date = $request->end_date;
			$temp->staff_id = $staff_id;
			
			// echo $temp->location_id;
			$getClientList = $this->getStaffWorkingHoursReport($temp);

			$data[] = $getClientList;
		}

		return Excel::download(new staffWorkingHoursExcelReport($data), 'staff_working_hours.xlsx');
	}

	public function tipsByStaff(){
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
		$Locations = Location::select('id','location_name')->where(['user_id'=>$AdminId])->orderBy('id', 'ASC')->get()->toArray();	
		
		// Get all staff
		$Staff = Staff::select('staff.staff_user_id','users.first_name','users.last_name')->where(['staff.user_id'=>$AdminId])->join('users','users.id','staff.staff_user_id')->orderBy('staff.id', 'ASC')->get()->toArray();	
		
		return view('analytics.tips_by_staff', compact('Locations','Staff'));
	}
	
	public function getTipsByStaff(Request $request){
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

		$start_date = (!empty($request->start_date)) ? date('Y-m-d', strtotime($request->start_date)) : NULL;
		$end_date = (!empty($request->end_date)) ? date('Y-m-d', strtotime($request->end_date)) : NULL;
		$location_id = (!empty($request->location_id)) ? $request->location_id : NULL;
		$staff_id = (!empty($request->staff_id)) ? $request->staff_id : NULL;

		$whereArray = [];

		$finalTotalTips = 0 ;
		$refundedTipsTotal = 0 ;
		$collectedTipsTotal = 0 ;
		$averageTipsTotal = 0 ;
		$totalTipCount = 0;
		
		if(!empty($start_date)){
			$whereArray[] = ['staff_tip.created_at', '>=', $start_date];
		}
		if(!empty($end_date)){
			$whereArray[] = ['staff_tip.created_at', '<=', $end_date];
		}
		if(!empty($location_id)){
			$whereArray[] = ['staff_tip.location_id', '=', $location_id];
		}
		if(!empty($staff_id)){
			$whereArray[] = ['staff_tip.staff_id', '=', $staff_id];
		}

		$staffUsers = [];
		if(empty($location_id) && empty($staff_id)){
			$staffUsers = Staff::where("user_id", $AdminId)->pluck('staff_user_id')->toArray();
		}

		if(!empty($staffUsers)){
			$data = StaffTip::select(DB::raw("CONCAT(users.first_name,' ',users.last_name) as employee_name"),DB::raw("SUM(staff_tip.tip_amount) as collected_tip"), DB::raw("COUNT(staff_tip.id) as tip_count"))->leftJoin("users", "users.id", "staff_tip.staff_id")->where($whereArray)->whereIn('staff_tip.staff_id',$staffUsers)->groupBy("staff_tip.staff_id")->get()->toArray();

			$refundedTips = StaffTip::select(DB::raw("CONCAT(users.first_name,' ',users.last_name) as employee_name"),DB::raw("SUM(staff_tip.tip_amount) as collected_tip"), DB::raw("COUNT(staff_tip.id) as tip_count"))->leftJoin("users", "users.id", "staff_tip.staff_id")->where($whereArray)->where('staff_tip.status', 1)->whereIn('staff_tip.staff_id',$staffUsers)->groupBy("staff_tip.staff_id")->get()->toArray();
		}else{
			$data = StaffTip::select(DB::raw("CONCAT(users.first_name,' ',users.last_name) as employee_name"),DB::raw("SUM(staff_tip.tip_amount) as collected_tip"), DB::raw("COUNT(staff_tip.id) as tip_count"))->leftJoin("users", "users.id", "staff_tip.staff_id")->where($whereArray)->groupBy("staff_tip.staff_id")->get()->toArray();

			$refundedTips = StaffTip::select(DB::raw("CONCAT(users.first_name,' ',users.last_name) as employee_name"),DB::raw("SUM(staff_tip.tip_amount) as collected_tip"), DB::raw("COUNT(staff_tip.id) as tip_count"))->leftJoin("users", "users.id", "staff_tip.staff_id")->where($whereArray)->where('staff_tip.status', 1)->groupBy("staff_tip.staff_id")->get()->toArray();
		}

		foreach($data as $dataKey => $dataValue){
			$flag = true;

			
			foreach($refundedTips as $refundedTipsKey => $refundedTipsValue){
				if($dataValue['employee_name'] == $refundedTipsValue['employee_name']){
					$flag = false;
					$totalTips = (!empty($dataValue['collected_tip']) ? $dataValue['collected_tip'] : 0) - (!empty($refundedTipsValue['collected_tip']) ? $refundedTipsValue['collected_tip'] : 0);

					$tip_count = (!empty($dataValue['tip_count']) ? $dataValue['tip_count'] : 0) - (!empty($refundedTipsValue['tip_count']) ? $refundedTipsValue['tip_count'] : 0);

					if($tip_count !== 0 && $totalTips !== 0){
						$averageTips = $totalTips / $tip_count;
						$data[$dataKey]['average_tips'] = $averageTips;
						$averageTipsTotal +=  $averageTips;
					}else{
						$data[$dataKey]['average_tips'] = 0;
						$averageTipsTotal +=  0;
					}
					
					
					$data[$dataKey]['refunded_tips'] = (!empty($refundedTipsValue['collected_tip']) ? $refundedTipsValue['collected_tip'] : 0);
					$data[$dataKey]['total_tips'] = $totalTips;

					$finalTotalTips += $totalTips ;
					$refundedTipsTotal +=  (!empty($refundedTipsValue['collected_tip']) ? $refundedTipsValue['collected_tip'] : 0);;
					$collectedTipsTotal +=  (!empty($dataValue['collected_tip']) ? $dataValue['collected_tip'] : 0);
				}
			}

			if($flag){
				$totalTips = (!empty($dataValue['collected_tip']) ? $dataValue['collected_tip'] : 0);
				$data[$dataKey]['refunded_tips'] = 0;
				$data[$dataKey]['total_tips'] = $totalTips;
				
				$tip_count = (!empty($dataValue['tip_count']) ? $dataValue['tip_count'] : 0) - (!empty($refundedTipsValue['tip_count']) ? $refundedTipsValue['tip_count'] : 0);
				$totalTipCount += $tip_count;
				
				if($tip_count !== 0 && $totalTips !== 0){
					$averageTips = $totalTips / $tip_count;
					$data[$dataKey]['average_tips'] = $averageTips;
					// $averageTipsTotal +=  $averageTips;
				}else{
					$data[$dataKey]['average_tips'] = 0;
					$averageTipsTotal +=  0;
				}

				$finalTotalTips += $totalTips ;
				$collectedTipsTotal +=  (!empty($dataValue['collected_tip']) ? $dataValue['collected_tip'] : 0);
			}
		}

		$averageTipsTotal = $finalTotalTips / ($totalTipCount != 0 ? $totalTipCount : 1);
		// dd($temptemp);

		$response = DataTables::of($data)
		->editColumn('collected_tip', function($raw){
			if(empty($raw['collected_tip'])){
				return 'CA $0.00';
			}else{
				return "CA $".number_format($raw['collected_tip'],2,".","");
			}
		})
		->editColumn('refunded_tips', function($raw){
			return "CA $".number_format($raw['refunded_tips'],2,".","");
		})
		->editColumn('total_tips', function($raw){
			return "CA $".number_format($raw['total_tips'],2,".","");
		})
		->editColumn('average_tips', function($raw){
			return "CA $".number_format($raw['average_tips'],2,".","");
		})
		->rawColumns(['collected_tip','refunded_tips','total_tips','average_tips'])
		->make(true);


		$data = $response->getData(true);
		$data['totalTips'] = "CA $".number_format($finalTotalTips, 2, '.', '');
		$data['refundedTipsTotal'] = "CA $".number_format($refundedTipsTotal, 2, '.', '');
		$data['collectedTipsTotal'] = "CA $".number_format($collectedTipsTotal, 2, '.', '');
		$data['averageTipsTotal'] = "CA $".number_format($averageTipsTotal, 2, '.', '');

		return $data;
		
	}

	public function getTipsByStaffPDF(Request $request){
		$getTipsByStaff = $this->getTipsByStaff($request);

		return PDF::loadView('pdfTemplates.tipsByStaffPDFReport', compact('getTipsByStaff'))->setPaper('a4')->download('tips_by_staff.pdf');
	}

	public function getTipsByStaffCSV(Request $request){
		$getTipsByStaff = $this->getTipsByStaff($request);

		return Excel::download(new tipsByStaffCSVReport($getTipsByStaff), 'tips_by_staff.csv');
	}

	public function getTipsByStaffExcel(Request $request){
		$getTipsByStaff = $this->getTipsByStaff($request);

		return Excel::download(new tipsByStaffExcelReport($getTipsByStaff), 'tips_by_staff.xlsx');
	}

	public function staffCommission(){
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
		$Locations = Location::select('id','location_name')->where(['user_id'=>$AdminId])->orderBy('id', 'ASC')->get()->toArray();	
		
		// Get all staff
		$Staff = Staff::select('staff.staff_user_id','users.first_name','users.last_name')->where(['staff.user_id'=>$AdminId])->join('users','users.id','staff.staff_user_id')->orderBy('staff.id', 'ASC')->get()->toArray();	
		
		return view('analytics.staff_commission_summary', compact('Locations','Staff'));
	}
	
	public function getStaffCommission(Request $request){
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

		$start_date = (!empty($request->start_date)) ? date('Y-m-d', strtotime($request->start_date)) : NULL;
		$end_date = (!empty($request->end_date)) ? date('Y-m-d', strtotime($request->end_date)) : NULL;
		$location_id = (!empty($request->location_id)) ? $request->location_id : NULL;
		$staff_id = (!empty($request->staff_id)) ? $request->staff_id : NULL;

		$whereArray = [];

		$serviceSalesTotal = 0 ;
		$serviceCommissionTotal = 0 ;
		$productSalesTotal = 0 ;
		$productCommissionTotal = 0 ;
		$voucherSalesTotal = 0 ;
		$voucherCommissionTotal = 0 ;
		$commissionTotal = 0 ;
		
		if(!empty($start_date)){
			$whereArray[] = [DB::raw('DATE(invoice.created_at)'), '>=', $start_date];
		}
		if(!empty($end_date)){
			$whereArray[] = [DB::raw('DATE(invoice.created_at)'), '<=', $end_date];
		}
		if(!empty($location_id)){
			$whereArray[] = ['invoice.location_id', '=', $location_id];
		}
		if(!empty($staff_id)){
			$whereArray[] = ['users.id', '=', $staff_id];
		}

		$data = [];

		$invoiceIds = Invoice::where("invoice.user_id",$AdminId)->where("invoice.created_at",">=", $start_date)->where("invoice.created_at","<=",$end_date)->where("invoice.invoice_status", 1)->pluck("invoice.id")->toArray();

		$temp = InvoiceItems::select(DB::raw("CONCAT(users.first_name,' ',users.last_name) as staff_member"), DB::raw("SUM(invoice_items.item_price) as service_sales_total"))->leftJoin("invoice","invoice.id","invoice_items.invoice_id")->leftJoin("staff","staff.id","invoice_items.staff_id")->leftJoin("users","users.id","staff.staff_user_id")->where($whereArray)->whereIn("invoice.invoice_status",['0','1'])->where("invoice.user_id",$AdminId)->whereIn("invoice_items.item_type",["services","product","voucher"])->groupBy("invoice_items.staff_id")->get()->toArray();

		$ServiceData = InvoiceItems::select(DB::raw("CONCAT(users.first_name,' ',users.last_name) as staff_member"), DB::raw("SUM(invoice_items.item_price) as service_sales_total"), "staff.service_commission as service_commission")->leftJoin("invoice","invoice.id","invoice_items.invoice_id")->leftJoin("staff","staff.id","invoice_items.staff_id")->leftJoin("users","users.id","staff.staff_user_id")->where($whereArray)->whereIn("invoice.invoice_status",['0','1'])->where("invoice.user_id",$AdminId)->where("invoice_items.item_type","services")->groupBy("invoice_items.staff_id")->get()->toArray();
		
		$ProductData = InvoiceItems::select(DB::raw("CONCAT(users.first_name,' ',users.last_name) as staff_member"), DB::raw("SUM(invoice_items.item_price) as product_sales_total"), "staff.product_commission as product_commission")->leftJoin("invoice","invoice.id","invoice_items.invoice_id")->leftJoin("staff","staff.id","invoice_items.staff_id")->leftJoin("users","users.id","staff.staff_user_id")->where($whereArray)->whereIn("invoice.invoice_status",['0','1'])->where("invoice.user_id",$AdminId)->where("invoice_items.item_type","product")->groupBy("invoice_items.staff_id")->get()->toArray();

		// dd($ProductData);
		
		$VoucherData = InvoiceItems::select(DB::raw("CONCAT(users.first_name,' ',users.last_name) as staff_member"), DB::raw("SUM(invoice_items.item_price) as voucher_sales_total"), "staff.voucher_commission as voucher_commission")->leftJoin("invoice","invoice.id","invoice_items.invoice_id")->leftJoin("staff","staff.id","invoice_items.staff_id")->leftJoin("users","users.id","staff.staff_user_id")->where($whereArray)->whereIn("invoice.invoice_status",['0','1'])->where("invoice.user_id",$AdminId)->where("invoice_items.item_type","voucher")->groupBy("invoice_items.staff_id")->get()->toArray();

		//Refund Data
		$ServiceDataRefund = InvoiceItems::select(DB::raw("CONCAT(users.first_name,' ',users.last_name) as staff_member"), DB::raw("SUM(invoice_items.item_price) as service_sales_total"), "staff.service_commission as service_commission")->leftJoin("invoice","invoice.id","invoice_items.invoice_id")->leftJoin("staff","staff.id","invoice_items.staff_id")->leftJoin("users","users.id","staff.staff_user_id")->where($whereArray)->where("invoice.invoice_status", 2)->where("invoice.user_id",$AdminId)->where("invoice_items.item_type","services")->whereIn("invoice.original_invoice_id", $invoiceIds)->groupBy("invoice_items.staff_id")->get()->toArray();
		
		$ProductDataRefund = InvoiceItems::select(DB::raw("CONCAT(users.first_name,' ',users.last_name) as staff_member"), DB::raw("SUM(invoice_items.item_price) as product_sales_total"), "staff.product_commission as product_commission")->leftJoin("invoice","invoice.id","invoice_items.invoice_id")->leftJoin("staff","staff.id","invoice_items.staff_id")->leftJoin("users","users.id","staff.staff_user_id")->where($whereArray)->where("invoice.invoice_status",2)->where("invoice.user_id",$AdminId)->where("invoice_items.item_type","product")->whereIn("invoice.original_invoice_id", $invoiceIds)->groupBy("invoice_items.staff_id")->get()->toArray();
		
		$VoucherDataRefund = InvoiceItems::select(DB::raw("CONCAT(users.first_name,' ',users.last_name) as staff_member"), DB::raw("SUM(invoice_items.item_price) as voucher_sales_total"), "staff.voucher_commission as voucher_commission")->leftJoin("invoice","invoice.id","invoice_items.invoice_id")->leftJoin("staff","staff.id","invoice_items.staff_id")->leftJoin("users","users.id","staff.staff_user_id")->where($whereArray)->where("invoice.invoice_status",2)->where("invoice.user_id",$AdminId)->where("invoice_items.item_type","voucher")->whereIn("invoice.original_invoice_id", $invoiceIds)->groupBy("invoice_items.staff_id")->get()->toArray();

		foreach($temp as $tempKey => $tempValue){
			$flag = true;
			$tempCommissionTotal = 0;
			
			foreach($ServiceData as $ServiceDataKey => $ServiceDataValue){
				if($tempValue['staff_member'] == $ServiceDataValue['staff_member']){
					if($ServiceDataValue['service_sales_total'] > 0){
						$flag = false;
	
						$data[$tempKey]['staff_member'] = $tempValue['staff_member'];
						$data[$tempKey]['service_sales_total'] = $ServiceDataValue['service_sales_total'];

						$serviceSalesTotal += $ServiceDataValue['service_sales_total'];
	
						if($ServiceDataValue['service_commission'] > 0){
							$service_commission_value = $ServiceDataValue['service_sales_total'] * $ServiceDataValue['service_commission'] / 100;
							$data[$tempKey]['service_commission'] = $service_commission_value;
							$tempCommissionTotal += $service_commission_value;
							$serviceCommissionTotal += $service_commission_value;
						}else{
							$data[$tempKey]['service_commission'] = 0;
						}
					}
				}
			}
			if($flag){
				$data[$tempKey]['staff_member'] = $tempValue['staff_member'];
				$data[$tempKey]['service_sales_total'] = 0;
				$data[$tempKey]['service_commission'] = 0;
			}

			$flag = true;

			foreach($ProductData as $ProductDataKey => $ProductDataValue){
				if($tempValue['staff_member'] == $ProductDataValue['staff_member']){
					if($ProductDataValue['product_sales_total'] > 0){
						$flag = false;
						$data[$tempKey]['product_sales_total'] = $ProductDataValue['product_sales_total'];
						
						$productSalesTotal += $ProductDataValue['product_sales_total'];
						if($ProductDataValue['product_commission'] > 0){
							$product_commission_value = $ProductDataValue['product_sales_total'] * $ProductDataValue['product_commission'] / 100;
							$data[$tempKey]['product_commission'] = $product_commission_value;
							$tempCommissionTotal += $product_commission_value;
							$productCommissionTotal += $product_commission_value;
						}else{
							$data[$tempKey]['product_commission'] = 0;
						}
					}
				}
			}
			if($flag){
				$data[$tempKey]['product_sales_total'] = 0;
				$data[$tempKey]['product_commission'] = 0;
			}

			$flag = true;

			foreach($VoucherData as $VoucherDataKey => $VoucherDataValue){
				if($tempValue['staff_member'] == $VoucherDataValue['staff_member']){
					if($VoucherDataValue['voucher_sales_total'] > 0){
						$flag = false;
	
						$data[$tempKey]['voucher_sales_total'] = $VoucherDataValue['voucher_sales_total'];
						$voucherSalesTotal += $VoucherDataValue['voucher_sales_total'];
						if($VoucherDataValue['voucher_commission'] > 0){
							$voucher_commission_value = $VoucherDataValue['voucher_sales_total'] * $VoucherDataValue['voucher_commission'] / 100;
							$data[$tempKey]['voucher_commission'] = $voucher_commission_value;

							$tempCommissionTotal += $voucher_commission_value;
							$voucherCommissionTotal += $voucher_commission_value;
						}else{
							$data[$tempKey]['voucher_commission'] = 0;
						}
					}
				}
			}
			if($flag){
				$data[$tempKey]['voucher_sales_total'] = 0;
				$data[$tempKey]['voucher_commission'] = 0;
			}
			$data[$tempKey]['commission_total'] = $tempCommissionTotal;
			$commissionTotal += $tempCommissionTotal;
		}
		
		if(!empty($data)){
			foreach($data as $dataKey => $dataValue){
				$flag = true;
			
				foreach($ServiceDataRefund as $ServiceDataRefundKey => $ServiceDataRefundValue){
					if($dataValue['staff_member'] == $ServiceDataRefundValue['staff_member']){
						$flag = false;

						$data[$dataKey]['service_sales_total'] -= $ServiceDataRefundValue['service_sales_total'];
						$serviceSalesTotal -= $ServiceDataRefundValue['service_sales_total'];

						if($ServiceDataRefundValue['service_commission'] > 0){
							$service_commission_value = $ServiceDataRefundValue['service_sales_total'] * $ServiceDataRefundValue['service_commission'] / 100;

							$data[$dataKey]['service_commission'] -= $service_commission_value;
							$data[$dataKey]['commission_total'] -= $service_commission_value;
							$commissionTotal -= $service_commission_valuel;
						}else{
							// $data[$dataKey]['service_commission'] = 0;
						}
					}
				}
				if($flag){
					// $data[$dataKey]['service_sales_total'] = 0;
					// $data[$dataKey]['service_commission'] = 0;
				}

				$flag = true;

				foreach($ProductDataRefund as $ProductDataRefundKey => $ProductDataRefundValue){
					if($tempValue['staff_member'] == $ProductDataRefundValue['staff_member']){
						if($ProductDataRefundValue['product_sales_total'] > 0){
							$flag = false;
							$data[$tempKey]['product_sales_total'] -= $ProductDataRefundValue['product_sales_total'];

							$productSalesTotal -= $ProductDataRefundValue['product_sales_total'];

							if($ProductDataRefundValue['product_commission'] > 0){
								$product_commission_value = $ProductDataRefundValue['product_sales_total'] * $ProductDataRefundValue['product_commission'] / 100;
								$data[$tempKey]['product_commission'] -= $product_commission_value;
								$data[$tempKey]['commission_total'] -= $product_commission_value;
								$commissionTotal -= $product_commission_value;
							}else{
								// $data[$tempKey]['product_commission'] = 0;
							}
						}
					}
				}
				if($flag){
					// $data[$tempKey]['product_sales_total'] = 0;
					// $data[$tempKey]['product_commission'] = 0;
				}

				$flag = true;

				foreach($VoucherDataRefund as $VoucherDataRefundKey => $VoucherDataRefundValue){
					if($tempValue['staff_member'] == $VoucherDataRefundValue['staff_member']){
						if($VoucherDataRefundValue['voucher_sales_total'] > 0){
							$flag = false;
		
							$data[$tempKey]['voucher_sales_total'] -= $VoucherDataRefundValue['voucher_sales_total'];
							$voucherSalesTotal -= $VoucherDataRefundValue['voucher_sales_total'];
							if($VoucherDataRefundValue['voucher_commission'] > 0){
								$voucher_commission_value = $VoucherDataRefundValue['voucher_sales_total'] * $VoucherDataRefundValue['voucher_commission'] / 100;
								$data[$tempKey]['voucher_commission'] -= $voucher_commission_value;
								$data[$tempKey]['commission'] -= $voucher_commission_value;
								$commissionTotal -= $voucher_commission_value;
							}else{
								// $data[$tempKey]['voucher_commission'] = 0;
							}
						}
					}
				}
				if($flag){
					// $data[$tempKey]['voucher_sales_total'] = 0;
					// $data[$tempKey]['voucher_commission'] = 0;
				}
			}
		}

		$data = array_filter($data, function($value){
			return !is_null($value['staff_member']) && $value['staff_member'] !== '';
		});
		$serviceSalesTotal = 0 ;
		$serviceCommissionTotal = 0 ;
		$productSalesTotal = 0 ;
		$productCommissionTotal = 0 ;
		$voucherSalesTotal = 0 ;
		$voucherCommissionTotal = 0 ;
		$commissionTotal = 0 ;

		foreach($data as $dKey => $dValue){
			$serviceSalesTotal += $dValue['service_sales_total'] ;
			$serviceCommissionTotal += $dValue['service_commission'] ;
			$productSalesTotal += $dValue['product_sales_total'] ;
			$productCommissionTotal += $dValue['product_commission'] ;
			$voucherSalesTotal += $dValue['voucher_sales_total'] ;
			$voucherCommissionTotal += $dValue['voucher_commission'] ;
			$commissionTotal += $dValue['commission_total'] ;
		}


		// print_r(array_filter($data, function($value){
		// 	return !is_null($value['staff_member']) && $value['staff_member'] !== '';
		// }));die;

		// dd($data);

		$response = DataTables::of($data)
		->editColumn('service_sales_total', function($raw){
			if(empty($raw['service_sales_total'])){
				return 'CA $0.00';
			}else{
				return "CA $".number_format($raw['service_sales_total'],2,".","");
			}
		})
		->editColumn('service_commission', function($raw){
			return "CA $".number_format($raw['service_commission'],2,".","");
		})
		->editColumn('product_sales_total', function($raw){
			return "CA $".number_format($raw['product_sales_total'],2,".","");
		})
		->editColumn('product_commission', function($raw){
			return "CA $".number_format($raw['product_commission'],2,".","");
		})
		->editColumn('voucher_sales_total', function($raw){
			return "CA $".number_format($raw['voucher_sales_total'],2,".","");
		})
		->editColumn('voucher_commission', function($raw){
			return "CA $".number_format($raw['voucher_commission'],2,".","");
		})
		->editColumn('commission_total', function($raw){
			return "CA $".number_format($raw['commission_total'],2,".","");
		})
		->rawColumns(['service_sales_total','service_commission','product_sales_total','product_commission','voucher_sales_total','voucher_commission','commission_total'])
		->make(true);


		$data = $response->getData(true);
		$data['serviceSalesTotal'] = "CA $".number_format($serviceSalesTotal, 2, '.', '');
		$data['serviceCommissionTotal'] = "CA $".number_format($serviceCommissionTotal, 2, '.', '');
		$data['productSalesTotal'] = "CA $".number_format($productSalesTotal, 2, '.', '');
		$data['productCommissionTotal'] = "CA $".number_format($productCommissionTotal, 2, '.', '');
		$data['voucherSalesTotal'] = "CA $".number_format($voucherSalesTotal, 2, '.', '');
		$data['voucherCommissionTotal'] = "CA $".number_format($voucherCommissionTotal, 2, '.', '');
		$data['commissionTotal'] = "CA $".number_format($commissionTotal, 2, '.', '');
		// dd($data);

		return $data;
		
	}

	public function getStaffCommissionPDF(Request $request){
		$getStaffCommission = $this->getStaffCommission($request);

		// dd($getStaffCommission);
		return PDF::loadView('pdfTemplates.staffCommissionPDFReport', compact('getStaffCommission'))->setPaper('a4')->download('staff_commission.pdf');
	}

	public function getStaffCommissionCSV(Request $request){
		$getStaffCommission = $this->getStaffCommission($request);

		return Excel::download(new staffCommissionCSVReport($getStaffCommission), 'staff_commission.csv');
	}

	public function getStaffCommissionExcel(Request $request){
		$getStaffCommission = $this->getStaffCommission($request);

		return Excel::download(new staffCommissionExcelReport($getStaffCommission), 'staff_commission.xlsx');
	}

	public function staffCommissionDetailed(){
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
		$Locations = Location::select('id','location_name')->where(['user_id'=>$AdminId])->orderBy('id', 'ASC')->get()->toArray();	
		
		// Get all staff
		$Staff = Staff::select('staff.staff_user_id','users.first_name','users.last_name')->where(['staff.user_id'=>$AdminId])->join('users','users.id','staff.staff_user_id')->orderBy('staff.id', 'ASC')->get()->toArray();	
		
		return view('analytics.staff_commission_detailed', compact('Locations','Staff'));
	}
	
	public function getStaffCommissionDetailed(Request $request){
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

		$start_date = (!empty($request->start_date)) ? date('Y-m-d', strtotime($request->start_date)) : NULL;
		$end_date = (!empty($request->end_date)) ? date('Y-m-d', strtotime($request->end_date)) : NULL;
		$location_id = (!empty($request->location_id)) ? $request->location_id : NULL;
		$staff_id = (!empty($request->staff_id)) ? $request->staff_id : NULL;

		$whereArray = [];
		
		if(!empty($start_date)){
			$whereArray[] = [DB::raw('DATE(invoice.created_at)'), '>=', $start_date];
		}
		if(!empty($end_date)){
			$whereArray[] = [DB::raw('DATE(invoice.created_at)'), '<=', $end_date];
		}
		if(!empty($location_id)){
			$whereArray[] = ['invoice.location_id', '=', $location_id];
		}
		if(!empty($staff_id)){
			$whereArray[] = ['users.id', '=', $staff_id];
		}

		$data = InvoiceItems::select(DB::raw("DATE(invoice_items.created_at) as invoice_date"), "invoice.invoice_id as invoice_no", DB::raw("CONCAT(users.first_name,' ',last_name) as staff_member"), "inventory_products.product_name as item_sold", "invoice_items.quantity", "invoice_items.item_price as sale_value", "staff.product_commission as commission_rate", "invoice_items.item_type", "invoice.invoice_status")
		->leftJoin("invoice", "invoice.id", "invoice_items.invoice_id")
		->leftJoin("staff", "staff.id", "invoice_items.staff_id")
		->leftJoin("users", "users.id", "staff.staff_user_id")
		->leftJoin("inventory_products", "inventory_products.id", "invoice_items.item_id")
		->where($whereArray)
		->where("invoice.user_id", $AdminId)
		->whereIn("invoice.invoice_status", ['0','1','2'])
		->where("invoice_items.item_type","product")
		->groupBy("invoice_items.id")
		->get()->toArray();

		$serviceData = InvoiceItems::select(DB::raw("DATE(invoice_items.created_at) as invoice_date"), "invoice.invoice_id as invoice_no", DB::raw("CONCAT(users.first_name,' ',last_name) as staff_member"), "services.service_name as item_sold", "invoice_items.quantity", "invoice_items.item_price as sale_value", "staff.product_commission as commission_rate", "invoice_items.item_type", "invoice.invoice_status")
		->leftJoin("invoice", "invoice.id", "invoice_items.invoice_id")
		->leftJoin("staff", "staff.id", "invoice_items.staff_id")
		->leftJoin("users", "users.id", "staff.staff_user_id")
		->leftJoin("services", "services.id", "invoice_items.item_id")
		->where($whereArray)
		->where("invoice.user_id", $AdminId)
		->whereIn("invoice.invoice_status", ['0','1','2'])
		->where("invoice_items.item_type","services")
		->groupBy("invoice_items.id")
		->get()->toArray();
		
		$voucherData = InvoiceItems::select(DB::raw("DATE(invoice_items.created_at) as invoice_date"), "invoice.invoice_id as invoice_no", DB::raw("CONCAT(users.first_name,' ',last_name) as staff_member"), "vouchers.name as item_sold", "invoice_items.quantity", "invoice_items.item_price as sale_value", "staff.product_commission as commission_rate", "invoice_items.item_type", "invoice.invoice_status")
		->leftJoin("invoice", "invoice.id", "invoice_items.invoice_id")
		->leftJoin("staff", "staff.id", "invoice_items.staff_id")
		->leftJoin("users", "users.id", "staff.staff_user_id")
		->leftJoin("vouchers", "vouchers.id", "invoice_items.item_id")
		->where($whereArray)
		->where("invoice.user_id", $AdminId)
		->whereIn("invoice.invoice_status", ['0','1','2'])
		->where("invoice_items.item_type","voucher")
		->groupBy("invoice_items.id")
		->get()->toArray();

		foreach($serviceData as $serviceDataKey => $serviceDataValue){
			$temp = [];
			$temp['invoice_date'] = $serviceDataValue['invoice_date'];
			$temp['invoice_no'] = $serviceDataValue['invoice_no'];
			$temp['staff_member'] = $serviceDataValue['staff_member'];
			$temp['item_sold'] = $serviceDataValue['item_sold'];
			$temp['quantity'] = $serviceDataValue['quantity'];
			$temp['sale_value'] = $serviceDataValue['sale_value'];
			$temp['commission_rate'] = $serviceDataValue['commission_rate'];
			$temp['item_type'] = $serviceDataValue['item_type'];
			$temp['invoice_status'] = $serviceDataValue['invoice_status'];
			array_push($data, $temp);
		}
		foreach($voucherData as $voucherDataKey => $voucherDataValue){
			$temp = [];
			$temp['invoice_date'] = $voucherDataValue['invoice_date'];
			$temp['invoice_no'] = $voucherDataValue['invoice_no'];
			$temp['staff_member'] = $voucherDataValue['staff_member'];
			$temp['item_sold'] = $voucherDataValue['item_sold'];
			$temp['quantity'] = $voucherDataValue['quantity'];
			$temp['sale_value'] = $voucherDataValue['sale_value'];
			$temp['commission_rate'] = $voucherDataValue['commission_rate'];
			$temp['item_type'] = $voucherDataValue['item_type'];
			$temp['invoice_status'] = $voucherDataValue['invoice_status'];
			array_push($data, $temp);
		}

		foreach($data as $dKey => $dValue){
			if(!empty($dValue['commission_rate']) && $dValue['commission_rate'] !== 0){
				$commission_amount = $dValue['sale_value'] * $dValue['commission_rate'] / 100;
				$data[$dKey]['commission_amount'] = $commission_amount;
			}else{
				$data[$dKey]['commission_amount'] = 0;
			}
		}

		if(!empty($data)){
			usort($data, function($element1, $element2) {
				// $datetime1 = strtotime($element1['invoice_date']);
				// $datetime2 = strtotime($element2['invoice_date']);
				$datetime1 = $element1['invoice_no'];
				$datetime2 = $element2['invoice_no'];
				return $datetime1 - $datetime2;
			});
		}

		// dd($data);

		$response = DataTables::of($data)
		->editColumn('quantity', function($raw){
			if($raw['invoice_status'] == 2){
				return "-".$raw['quantity'];
			}else{
				return $raw['quantity'];
			}
		})
		->editColumn('sale_value', function($raw){
			if($raw['invoice_status'] == 2){
				return "-CA $".number_format($raw['sale_value'],2,".","");
			}else{
				return "CA $".number_format($raw['sale_value'],2,".","");;
			}
		})
		->editColumn('commission_rate', function($raw){
			if(empty($raw['commission_rate'])){
				return "0%";
			}else{
				return $raw['commission_rate']."%";
			}
		})
		->editColumn('commission_amount', function($raw){
			if($raw['invoice_status'] == 2){
				return "-CA $".number_format($raw['commission_amount'],2,".","");
			}else{
				return "CA $".number_format($raw['commission_amount'],2,".","");;
			}
		})
		->editColumn('item_type', function($raw){
			// return number_format($raw['product_commission'],2,".","");
			if($raw['item_type'] == "product"){
				return "Product";
			}
			if($raw['item_type'] == "services"){
				return "Services";
			}
			if($raw['item_type'] == "voucher"){
				return "Voucher";
			}
			return $raw['item_type'];
		})
		->rawColumns(['quantity','sale_value','commission_rate','item_type'])
		->make(true);


		$data = $response->getData(true);
		// dd($data);

		return $data;
		
	}

	public function getStaffCommissionDetailedPDF(Request $request){
		$getStaffCommissionDetailed = $this->getStaffCommissionDetailed($request);

		// dd($getStaffCommission);
		return PDF::loadView('pdfTemplates.staffCommissionDetailedPDFReport', compact('getStaffCommissionDetailed'))->setPaper('a4')->download('staff_commission_detailed.pdf');
	}

	public function getStaffCommissionDetailedCSV(Request $request){
		$getStaffCommissionDetailed = $this->getStaffCommissionDetailed($request);

		return Excel::download(new staffCommissionDetailedCSVReport($getStaffCommissionDetailed), 'staff_commission_detailed.csv');
	}

	public function getStaffCommissionDetailedExcel(Request $request){
		$getStaffCommissionDetailed = $this->getStaffCommissionDetailed($request);

		return Excel::download(new staffCommissionDetailedExcelReport($getStaffCommissionDetailed), 'staff_commission_detailed.xlsx');
	}

	public function stockOnHand(){
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
		// $Locations = Location::select('id','location_name')->where(['user_id'=>$AdminId])->orderBy('id', 'ASC')->get()->toArray();	
		
		// Get all staff
		// $Staff = Staff::select('staff.staff_user_id','users.first_name','users.last_name')->where(['staff.user_id'=>$AdminId])->join('users','users.id','staff.staff_user_id')->orderBy('staff.id', 'ASC')->get()->toArray();	

		//Get All Suppliers
		$Suppliers = Inventory_supplier::select("supplier_name", "id")->where("user_id", $AdminId)->where("is_deleted", 0)->get()->toArray();

		//Get All Brands
		$Brands = Inventory_brand::select("brand_name", "id")->where("user_id", $AdminId)->where("is_deleted", 0)->get()->toArray();

		//Get All Categories
		$Categories = Inventory_category::select("category_name", "id")->where("user_id", $AdminId)->where("is_deleted", 0)->get()->toArray();
		
		return view('analytics.stock_on_hand', compact('Suppliers','Brands','Categories'));
	}
	
	public function getStockOnHand(Request $request){
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

		// $start_date = (!empty($request->start_date)) ? date('Y-m-d', strtotime($request->start_date)) : NULL;
		// $location_id = (!empty($request->location_id)) ? $request->location_id : NULL;
		$supplierID = (!empty($request->supplierID)) ? $request->supplierID : NULL;
		$stockBrand = (!empty($request->stockBrand)) ? $request->stockBrand : NULL;
		$stockCategory = (!empty($request->stockCategory)) ? $request->stockCategory : NULL;

		$date = (!empty($request->start_date)) ? $request->start_date : NULL;
		if(!is_null($date)){
			$datetime = DateTime::createFromFormat('l, d/m/Y', $date);
			$start_date = $datetime->format('Y/m/d');
			$nextDay = $datetime->modify("+1 day");
			$nextDay = $nextDay->format('Y/m/d');
		}else{
			$start_date = null;
		}
		// dd($start_date);

		$whereArray = [];

		// dd($nextDay);
		
		if(!empty($start_date)){
			$whereArray[] = [DB::raw('DATE(inventory_products.created_at)'), '>=', $start_date];
			$whereArray[] = [DB::raw('DATE(inventory_products.created_at)'), '<', $nextDay];
		}
		// if(!empty($location_id)){
		// 	$whereArray[] = ['inventory_products.location_id', '=', $location_id];
		// }
		if(!empty($supplierID)){
			$whereArray[] = ["inventory_products.supplier_id", '=', $supplierID];
		}
		if(!empty($stockBrand)){
			$whereArray[] = ['inventory_products.brand_id', '=', $stockBrand];
		}
		if(!empty($stockCategory)){
			$whereArray[] = ['inventory_products.category_id', '=', $stockCategory];
		}

		// dd($whereArray);

		$inventoryProductsData = InventoryProducts::select("inventory_products.product_name as product", "inventory_products.initial_stock as stock_on_hand", DB::raw("inventory_products.initial_stock * inventory_products.average_price as total_cost"), "inventory_products.average_price as average_cost", DB::raw("inventory_products.initial_stock * inventory_products.special_rate as total_retail_value"), "inventory_products.special_rate as retail_price", "inventory_products.reorder_point", "inventory_products.reorder_qty as reorder_amount")
		->where("inventory_products.user_id", $AdminId)
		->where($whereArray)
		->where("inventory_products.is_deleted", 0)
		->groupBy("inventory_products.id")
		->get()
		->toArray();

		$inventoryProductsDataTotal = InventoryProducts::select(DB::raw("SUM(inventory_products.initial_stock) as stock_on_hand"), DB::raw("SUM(inventory_products.initial_stock * inventory_products.average_price) as total_cost"), DB::raw("SUM(inventory_products.average_price) as average_cost"), DB::raw("SUM(inventory_products.initial_stock * inventory_products.special_rate) as total_retail_value"))
		->where("inventory_products.user_id", $AdminId)
		->where($whereArray)
		->where("inventory_products.is_deleted", 0)
		->get()
		->toArray();

		$response = DataTables::of($inventoryProductsData)
		->editColumn("total_cost", function($raw){
			return "CA $".number_format($raw['total_cost'], 2);
		})
		->editColumn("average_cost", function($raw){
			return "CA $".number_format($raw['average_cost'], 2);
		})
		->editColumn("total_retail_value", function($raw){
			return "CA $".number_format($raw['total_retail_value'], 2);
		})
		->editColumn("retail_price", function($raw){
			return "CA $".number_format($raw['retail_price'], 2);
		})
		->editColumn("reorder_point", function($raw){
			if($raw['reorder_point'] === 0){
				return "";
			}else{
				return $raw['reorder_point'];
			}
		})
		->editColumn("reorder_amount", function($raw){
			if($raw['reorder_amount'] === 0){
				return "";
			}else{
				return $raw['reorder_amount'];
			}
		})
		->rawColumns(['total_cost', 'average_cost','total_retail_value','retail_price','reorder_point','reorder_amount'])
		->make(true);

		// dd($inventoryProductsDataTotal[0]['stock_on_hand']);

		$data = $response->getData(true);
		if(!empty($inventoryProductsDataTotal)){
			$data['stock_on_hand'] 		= $inventoryProductsDataTotal[0]['stock_on_hand'];
			$data['total_cost']    		= "CA $".number_format($inventoryProductsDataTotal[0]['total_cost'], 2);
			$data['average_cost']  		= "CA $".number_format($inventoryProductsDataTotal[0]['average_cost'], 2);
			$data['total_retail_value'] = "CA $".number_format($inventoryProductsDataTotal[0]['total_retail_value'], 2);
		}else{
			$data['stock_on_hand'] 		= "CA $0.00";
			$data['total_cost']    		= "CA $0.00";
			$data['average_cost']  		= "CA $0.00";
			$data['total_retail_value'] = "CA $0.00";
		}

		// dd($data);

		return $data;
		
	}

	public function getStockOnHandPDF(Request $request){
		$getStockOnHand = $this->getStockOnHand($request);

		// dd($getStockOnHand);

		return PDF::loadView('pdfTemplates.stockOnHandPDFReport', compact('getStockOnHand'))->setPaper('a4')->download('stock_on_hand.pdf');
	}

	public function getStockOnHandCSV(Request $request){
		$getStockOnHand = $this->getStockOnHand($request);

		return Excel::download(new stockOnHandCSVReport($getStockOnHand), 'stock_on_hand.csv');
	}

	public function getStockOnHandExcel(Request $request){
		$getStockOnHand = $this->getStockOnHand($request);

		return Excel::download(new stockOnHandExcelReport($getStockOnHand), 'stock_on_hand.xlsx');
	}

	public function productSalesPerformance(){
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
		// $Locations = Location::select('id','location_name')->where(['user_id'=>$AdminId])->orderBy('id', 'ASC')->get()->toArray();	
		
		// Get all staff
		$Staff = Staff::select('staff.staff_user_id','users.first_name','users.last_name')->where(['staff.user_id'=>$AdminId])->join('users','users.id','staff.staff_user_id')->orderBy('staff.id', 'ASC')->get()->toArray();	

		//Get All Suppliers
		$Suppliers = Inventory_supplier::select("supplier_name", "id")->where("user_id", $AdminId)->where("is_deleted", 0)->get()->toArray();

		//Get All Brands
		$Brands = Inventory_brand::select("brand_name", "id")->where("user_id", $AdminId)->where("is_deleted", 0)->get()->toArray();

		//Get All Categories
		$Categories = Inventory_category::select("category_name", "id")->where("user_id", $AdminId)->where("is_deleted", 0)->get()->toArray();
		
		return view('analytics.product_sales_performance', compact('Staff','Suppliers','Brands','Categories'));
	}
	
	public function getProductSalesPerformance(Request $request){
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

		$start_date = (!empty($request->start_date)) ? date('Y-m-d', strtotime($request->start_date)) : NULL;
		$staff_id = (!empty($request->staff_id)) ? $request->staff_id : NULL;
		$end_date = (!empty($request->end_date)) ? $request->end_date : NULL;
		$supplierID = (!empty($request->supplierID)) ? $request->supplierID : NULL;
		$stockBrand = (!empty($request->stockBrand)) ? $request->stockBrand : NULL;
		$stockCategory = (!empty($request->stockCategory)) ? $request->stockCategory : NULL;

		$whereArray = [];
		
		if(!empty($start_date)){
			$whereArray[] = [DB::raw('DATE(inventory_products.created_at)'), '>=', $start_date];
		}
		if(!empty($end_date)){
			$whereArray[] = [DB::raw('DATE(inventory_products.created_at)'), '<=', $end_date];
		}
		if(!empty($supplierID)){
			$whereArray[] = ["inventory_products.supplier_id", '=', $supplierID];
		}
		if(!empty($stockBrand)){
			$whereArray[] = ['inventory_products.brand_id', '=', $stockBrand];
		}
		if(!empty($stockCategory)){
			$whereArray[] = ['inventory_products.category_id', '=', $stockCategory];
		}
		if(!empty($staff_id)){
			$staffId = Staff::where("staff_user_id", $staff_id)->pluck('id')->first();
			$whereArray[] = ['invoice_items.staff_id', "=", $staffId];
		}
		$whereArray[] = ['inventory_products.is_deleted', "=", 0];

		$productSalesPerformance = InventoryProducts::select("inventory_products.product_name", "inventory_products.initial_stock as stock_on_hand", DB::raw("SUM(invoice_items.quantity) as qty_sold"), DB::raw("ABS(SUM(inventory_order_logs.qty_adjusted * inventory_order_logs.average_price)) as cost_of_goods_sold"), DB::raw("(SUM(invoice_items.quantity) * invoice_items.item_price) as net_sales"), DB::raw("((SUM(invoice_items.quantity) * invoice_items.item_price) / SUM(invoice_items.quantity)) as average_net_price"), DB::raw("(((ABS(SUM(inventory_order_logs.qty_adjusted * inventory_order_logs.average_price)) / (SUM(invoice_items.quantity) * invoice_items.item_price)) * 100) - 100) as margin"), DB::raw("((SUM(invoice_items.quantity) * invoice_items.item_price) - ABS(SUM(inventory_order_logs.qty_adjusted * inventory_order_logs.average_price))) as total_margin"))
		->leftJoin("invoice_items", function($leftJoin){
            $leftJoin->on("invoice_items.item_id", "=", "inventory_products.id");
			$leftJoin->where("invoice_items.item_type", "=", "product");
        }) 
		->leftJoin("inventory_order_logs", "inventory_order_logs.invoice_id", "invoice_items.invoice_id")
		->where($whereArray)
        ->where(function($query) use ($start_date, $end_date){
            $query->where(function($InerQuery) use ($start_date, $end_date){
                $InerQuery->where(DB::raw('DATE(invoice_items.created_at)'), '>=', $start_date);
                $InerQuery->where(DB::raw('DATE(invoice_items.created_at)'), '<=', $end_date);
                $InerQuery->where(DB::raw('DATE(invoice_items.is_void)'), '=', 0);
            });
            $query->orWhere(function($InerQuery) use ($start_date, $end_date){
                $InerQuery->where(DB::raw('DATE(invoice_items.created_at)'), '=', NULL);
                $InerQuery->where(DB::raw('DATE(invoice_items.created_at)'), '=', NULL);
                $InerQuery->where(DB::raw('DATE(invoice_items.is_void)'), '=', NULL);
            });

        })
		->where("inventory_products.user_id", $AdminId)
		->groupBy("inventory_products.id")
		->get()
		->toArray();

		if(!empty($productSalesPerformance)){
			$totalStockOnHand = array_sum(array_column($productSalesPerformance,'stock_on_hand'));
		}else{
			$totalStockOnHand = 0;
		}

		$productSalesPerformanceTotal = InventoryProducts::select(DB::raw("SUM(invoice_items.quantity) as qty_sold"), DB::raw("ABS(SUM(inventory_order_logs.qty_adjusted * inventory_order_logs.average_price)) as cost_of_goods_sold"), DB::raw("(SUM(invoice_items.quantity) * invoice_items.item_price) as net_sales"), DB::raw("((SUM(invoice_items.quantity) * invoice_items.item_price) / SUM(invoice_items.quantity)) as average_net_price"), DB::raw("(((ABS(SUM(inventory_order_logs.qty_adjusted * inventory_order_logs.average_price)) / (SUM(invoice_items.quantity) * invoice_items.item_price)) * 100) - 100) as margin"), DB::raw("((SUM(invoice_items.quantity) * invoice_items.item_price) - ABS(SUM(inventory_order_logs.qty_adjusted * inventory_order_logs.average_price))) as total_margin"))
		->leftJoin("invoice_items", function($leftJoin){
            $leftJoin->on("invoice_items.item_id", "=", "inventory_products.id");
			$leftJoin->where("invoice_items.item_type", "=", "product");
        }) 
		->leftJoin("inventory_order_logs", "inventory_order_logs.invoice_id", "invoice_items.invoice_id")
		->where($whereArray)
        ->where(function($query) use ($start_date, $end_date){
            $query->where(function($InerQuery) use ($start_date, $end_date){
                $InerQuery->where(DB::raw('DATE(invoice_items.created_at)'), '>=', $start_date);
                $InerQuery->where(DB::raw('DATE(invoice_items.created_at)'), '<=', $end_date);
                $InerQuery->where(DB::raw('DATE(invoice_items.is_void)'), '=', 0);
            });
            $query->orWhere(function($InerQuery) use ($start_date, $end_date){
                $InerQuery->where(DB::raw('DATE(invoice_items.created_at)'), '=', NULL);
                $InerQuery->where(DB::raw('DATE(invoice_items.created_at)'), '=', NULL);
                $InerQuery->where(DB::raw('DATE(invoice_items.is_void)'), '=', NULL);
            });

		})
		->where("inventory_products.user_id", $AdminId)
		->first()
		->toArray();

		$response = DataTables::of($productSalesPerformance)
		->editColumn("cost_of_goods_sold", function($raw){
			if($raw['cost_of_goods_sold'] == null){
				return "CA $0.00";
			}else{
				return "CA $".number_format($raw['cost_of_goods_sold'], 2);
			}
		})
		->editColumn("net_sales", function($raw){
			if($raw['net_sales'] == null){
				return "CA $0.00";
			}else{
				return "CA $".number_format($raw['net_sales'], 2);
			}
		})
		->editColumn("average_net_price", function($raw){
			if($raw['average_net_price'] == null){
				return "CA $0.00";
			}else{
				return "CA $".number_format($raw['average_net_price'], 2);
			}
		})
		->editColumn("total_margin", function($raw){
			if($raw['total_margin'] == null){
				return "CA $0.00";
			}else{
				if($raw['total_margin'] < 0){
					return "-CA $".number_format(abs($raw['total_margin']), 2);
				}else{
					return "CA $".number_format($raw['total_margin'], 2);
				}
			}
		})
		->editColumn("qty_sold", function($raw){
			if($raw['qty_sold'] == null){
				return "0";
			}else{
				return $raw['qty_sold'];
			}
		})
		->editColumn("stock_on_hand", function($raw){
			if($raw['stock_on_hand'] == null){
				return "0";
			}else{
				return $raw['stock_on_hand'];
			}
		})
		->editColumn("margin", function($raw){
			if($raw['margin'] == null && $raw['cost_of_goods_sold'] == null){
				return "0.00%";
			}elseif($raw['cost_of_goods_sold'] > $raw['net_sales']){
				return "-".number_format($raw['margin'], 2)."%";
			}elseif($raw['cost_of_goods_sold'] == 0){
				return "100.00%";
			}else{
				return number_format($raw['margin'], 2)."%";
			}
		})
		->rawColumns(['total_cost', 'average_cost','total_retail_value','retail_price','reorder_point','reorder_amount'])
		->make(true);

		if($productSalesPerformanceTotal['margin'] == null && $productSalesPerformanceTotal['cost_of_goods_sold'] == null){
			$Margin = "0.00%";
		}elseif($productSalesPerformanceTotal['cost_of_goods_sold'] > $productSalesPerformanceTotal['net_sales']){
			$Margin = "-".number_format($productSalesPerformanceTotal['margin'], 2)."%";
		}elseif($productSalesPerformanceTotal['cost_of_goods_sold'] == 0){
			$Margin = "100.00%";
		}else{
			$Margin = number_format($productSalesPerformanceTotal['margin'], 2)."%";
		}

		if($productSalesPerformanceTotal['total_margin'] == null){
			$totalMargin = "CA $0.00";
		}else{
			if($productSalesPerformanceTotal['total_margin'] < 0){
				$totalMargin = "-CA $".number_format(abs($productSalesPerformanceTotal['total_margin']), 2);
			}else{
				$totalMargin = "CA $".number_format($productSalesPerformanceTotal['total_margin'], 2);
			}
		}

		$data = $response->getData(true);

		$data['getProductSalesPerformanceTableFooterStockOnHand']		= $totalStockOnHand;
		$data['getProductSalesPerformanceTableFooterQtySold']			= $productSalesPerformanceTotal['qty_sold'] ? $productSalesPerformanceTotal['qty_sold'] : 0 ;
		$data['getProductSalesPerformanceTableFooterCostOfGoodsSold']	= ($productSalesPerformanceTotal['cost_of_goods_sold'] == null) ? "CA $0.00" : "CA $".number_format($productSalesPerformanceTotal['cost_of_goods_sold'], 2);
		$data['getProductSalesPerformanceTableFooterNetSales']			= ($productSalesPerformanceTotal['net_sales'] == null) ? "CA $0.00" : "CA $".number_format($productSalesPerformanceTotal['net_sales'], 2);
		$data['getProductSalesPerformanceTableFooterAverageNetPrice']	= ($productSalesPerformanceTotal['average_net_price'] == null) ? "CA $0.00" : "CA $".number_format($productSalesPerformanceTotal['average_net_price'], 2);
		$data['getProductSalesPerformanceTableFooterMargin']			= $Margin;
		$data['getProductSalesPerformanceTableFooterTotalMargin']		= $totalMargin;

		return $data;
	}

	public function getProductSalesPerformancePDF(Request $request){
		$getProductSalesPerformance = $this->getProductSalesPerformance($request);

		if(!empty($getProductSalesPerformance['input']['staff_id'])){
			$staffName = User::where('id', $getProductSalesPerformance['input']['staff_id'])->select(DB::raw("CONCAT(users.first_name,' ', users.last_name) as eName"))->first();
			$staffName = $staffName->eName;
		}else{
			$staffName = "All Employees";
		}

		// dd($getProductSalesPerformance);

		return PDF::loadView('pdfTemplates.productSalesPerformancePDFReport', compact('getProductSalesPerformance', 'staffName'))->setPaper('a4')->download('product_sales_performance.pdf');
	}

	public function getProductSalesPerformanceCSV(Request $request){
		$getProductSalesPerformance = $this->getProductSalesPerformance($request);

		return Excel::download(new productSalesPerformanceCSVReport($getProductSalesPerformance), 'product_sales_performance.csv');
	}

	public function getProductSalesPerformanceExcel(Request $request){
		$getProductSalesPerformance = $this->getProductSalesPerformance($request);

		return Excel::download(new productSalesPerformanceExcelReport($getProductSalesPerformance), 'product_sales_performance.xlsx');
	}

	public function stockMovementLog(){
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
		$Locations = Location::select('id','location_name')->where(['user_id'=>$AdminId])->orderBy('id', 'ASC')->get()->toArray();	
		
		return view('analytics.stock_movement_log', compact('Locations'));
	}
	
	public function getStockMovementLog(Request $request){
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

		$start_date = (!empty($request->start_date)) ? date('Y-m-d', strtotime($request->start_date)) : NULL;
		$end_date = (!empty($request->end_date)) ? $request->end_date : NULL;
		// $Locations = (!empty($request->Locations)) ? $request->Locations : NULL;

		$whereArray = [];
		
		if(!empty($start_date)){
			$whereArray[] = [DB::raw('DATE(inventory_order_logs.created_at)'), '>=', $start_date];
		}
		if(!empty($end_date)){
			$whereArray[] = [DB::raw('DATE(inventory_order_logs.created_at)'), '<=', $end_date];
		}
		// if(!empty($Locations)){
		// 	$whereArray[] = ["inventory_order_logs.locations_id", '=', $Locations];
		// }
		$whereArray[] = ['inventory_order_logs.is_void_invoice', "=", 0];

		$stockMovementLog = InventoryOrderLogs::select("inventory_order_logs.created_at as time_and_date", "inventory_products.product_name as product","inventory_products.barcode", DB::raw("CONCAT(users.first_name,' ',users.last_name) as staff"), "inventory_order_logs.order_action as action", "inventory_order_logs.qty_adjusted as adjustment", "inventory_order_logs.average_price as cost_price", "inventory_order_logs.stock_on_hand as on_hand")
		->leftJoin("inventory_products", "inventory_products.id", "inventory_order_logs.item_id")
		// ->leftJoin("invoice_items", function($leftJoin){
        //     $leftJoin->on("invoice_items.invoice_id", "=", "inventory_order_logs.invoice_id");
		// 	$leftJoin->where("invoice_items.item_type", "=", "product");
        // }) 
		->leftJoin("invoice_items", "invoice_items.invoice_id", "inventory_order_logs.invoice_id")
		->leftJoin("staff", "staff.id", "invoice_items.staff_id")
		->leftJoin("users", "users.id", "staff.staff_user_id")
		->where($whereArray)
		->where('inventory_order_logs.received_by', $AdminId)
		->where('inventory_order_logs.is_void_invoice', 0)
		->orderBy('inventory_order_logs.created_at', "DESC")
		->get()
		->toArray();

		// dd($stockMovementLog);

		return DataTables::of($stockMovementLog)
		->editColumn("cost_price", function($raw){
			if($raw['cost_price'] == null){
				return "CA $0.00";
			}elseif($raw['cost_price'] < 0){
				return "-CA $".number_format($raw['cost_price'], 2);
			}else{
				return "CA $".number_format($raw['cost_price'], 2);
			}
		})
		->rawColumns(['cost_price'])
		->make(true);
	}

	public function getStockMovementLogPDF(Request $request){
		$getStockMovementLog = $this->getStockMovementLog($request);

		// dd($getStockMovementLog);

		return PDF::loadView('pdfTemplates.stockMovementLogPDFReport', compact('getStockMovementLog'))->setPaper('a4')->download('stock_movement_log.pdf');
	}

	public function getStockMovementLogCSV(Request $request){
		$getStockMovementLog = $this->getStockMovementLog($request);

		return Excel::download(new stockMovementLogCSVReport($getStockMovementLog), 'stock_movement_log.csv');
	}

	public function getStockMovementLogExcel(Request $request){
		$getStockMovementLog = $this->getStockMovementLog($request);

		return Excel::download(new stockMovementLogExcelReport($getStockMovementLog), 'stock_movement_log.xlsx');
	}

	public function productConsumption(){
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
		// $Locations = Location::select('id','location_name')->where(['user_id'=>$AdminId])->orderBy('id', 'ASC')->get()->toArray();	
		
		// Get all staff
		$Staff = Staff::select('staff.staff_user_id','users.first_name','users.last_name')->where(['staff.user_id'=>$AdminId])->join('users','users.id','staff.staff_user_id')->orderBy('staff.id', 'ASC')->get()->toArray();	
		return view('analytics.product_consumption', compact('Staff'));
	}
	
	public function getProductConsumption(Request $request){
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

		$start_date = (!empty($request->start_date)) ? date('Y-m-d', strtotime($request->start_date)) : NULL;
		$end_date = (!empty($request->end_date)) ? $request->end_date : NULL;
		$staff_id = (!empty($request->staff_id)) ? $request->staff_id : NULL;
		$order_action = (!empty($request->order_action)) ? $request->order_action : NULL;

		$whereArray = [];
		
		if(!empty($start_date)){
			$whereArray[] = [DB::raw('DATE(inventory_order_logs.created_at)'), '>=', $start_date];
		}
		if(!empty($end_date)){
			$whereArray[] = [DB::raw('DATE(inventory_order_logs.created_at)'), '<=', $end_date];
		}
		if(!empty($staff_id)){
			$whereArray[] = ["inventory_order_logs.received_by", '=', $staff_id];
		}
		if(!empty($order_action)){
			if($order_action == "getProductConsumptionInternalUse"){
				$whereArray[] = ["inventory_order_logs.order_action", '=', "Internal Use"];
			}elseif($order_action == "getProductConsumptionDamaged"){
				$whereArray[] = ["inventory_order_logs.order_action", '=', "Damaged"];
			}elseif($order_action == "getProductConsumptionLost"){
				$whereArray[] = ["inventory_order_logs.order_action", '=', "Lost"];
			}elseif($order_action == "getProductConsumptionOutOfDate"){
				$whereArray[] = ["inventory_order_logs.order_action", '=', "Out Of Date"];
			}elseif($order_action == "getProductConsumptionAdjustment"){
				$whereArray[] = ["inventory_order_logs.order_action", '=', "Adjustment"];
			}elseif($order_action == "getProductConsumptionOther"){
				$whereArray[] = ["inventory_order_logs.order_action", '=', "Other"];
			}
		}
		// if(!empty($Locations)){
		// 	$whereArray[] = ["inventory_order_logs.location_id", '=', $Locations];
		// }
		$whereArray[] = ['inventory_order_logs.is_void_invoice', "=", 0];

		$productConsumption = InventoryOrderLogs::select("inventory_products.product_name", DB::raw("ABS(SUM(inventory_order_logs.qty_adjusted)) as quantity_used"), "inventory_order_logs.average_price as average_cost_price")
		->leftJoin("inventory_products", "inventory_products.id", "inventory_order_logs.item_id")
		->where($whereArray)
		->groupBy("inventory_order_logs.item_id")
		->get()
		->toArray();

		// if(!empty($productConsumption)){
		// 	$qtySum = array_sum(array_column($productConsumption,'quantity_used * average_cost_price'));
		// }else{
		// 	$qtySum = 0;
		// }
		// echo $qtySum; die;
		// dd($productConsumption);

		$response = DataTables::of($productConsumption)
		->editColumn("average_cost_price", function($raw){
			if($raw['average_cost_price'] == null || $raw['average_cost_price'] == 0){
				return "CA $0.00";
			}else{
				return "CA $".number_format($raw['average_cost_price'], 2);
			}
		})
		->addColumn("total_cost", function($raw){
			if(($raw['quantity_used'] == null || $raw['quantity_used'] == 0) || ($raw['average_cost_price'] == null || $raw['average_cost_price'] == null)){
				return "CA $0.00";
			}else{
				$average_cost_price = number_format($raw['average_cost_price'], 2);
				return "CA $".number_format(($raw['quantity_used'] * $average_cost_price), 2);
			}
		})
		->addColumn("demo_total_cost", function($raw){
			if(($raw['quantity_used'] == null || $raw['quantity_used'] == 0) || ($raw['average_cost_price'] == null || $raw['average_cost_price'] == null)){
				return 0;
			}else{
				$average_cost_price = number_format($raw['average_cost_price'], 2);
				return number_format(($raw['quantity_used'] * $average_cost_price), 2);
			}
		})
		->rawColumns(['cost_price'])
		->make(true);

		$data = $response->getData(true);

		// $totalCost = array_sum($data['data'], 'demo_total_cost');
		if(!empty($productConsumption)){
			$qtySum = array_sum(array_column($data['data'],'quantity_used'));
			$totalCost = array_sum(array_column($data['data'],'demo_total_cost'));
		}else{
			$qtySum = 0;
			$totalCost = 0;
		}
		$data['totalCost'] = "CA $".number_format($totalCost, 2);
		$data['totalQuantity'] = $qtySum;

		// echo $totalCost;die;

		// dd($data);
		return $data;
	}

	public function getProductConsumptionPDF(Request $request){
		// $getStockMovementLog = $this->getStockMovementLog($request);

		$start_date = (!empty($request->start_date)) ? date('Y-m-d', strtotime($request->start_date)) : NULL;
		$end_date = (!empty($request->end_date)) ? $request->end_date : NULL;
		$staff_id = (!empty($request->staff_id)) ? $request->staff_id : NULL;
		$order_action = (!empty($request->order_action)) ? $request->order_action : NULL;

		$getProductConsumption = [];

		if(!empty($order_action)){
			foreach($order_action as $order_action_key => $order_action_value){
				$temp = new Request;
				$temp->start_date = $start_date;
				$temp->end_date = $end_date;
				$temp->staff_id = $staff_id;
				$temp->order_action = $order_action_value;

				$tempData = $this->getProductConsumption($temp);
				$getProductConsumption[] = $tempData;
				// $getProductConsumption[$order_action_key]['temp'] = $order_action_value;
				if($order_action_value == "getProductConsumptionInternalUse"){
					$getProductConsumption[$order_action_key]['heading'] = "Internal Use";
				}elseif($order_action_value == "getProductConsumptionDamaged"){
					$getProductConsumption[$order_action_key]['heading'] = "Damaged";
				}elseif($order_action_value == "getProductConsumptionLost"){
					$getProductConsumption[$order_action_key]['heading'] = "Lost";
				}elseif($order_action_value == "getProductConsumptionOutOfDate"){
					$getProductConsumption[$order_action_key]['heading'] = "Out Of Date";
				}elseif($order_action_value == "getProductConsumptionAdjustment"){
					$getProductConsumption[$order_action_key]['heading'] = "Adjustment";
				}elseif($order_action_value == "getProductConsumptionOther"){
					$getProductConsumption[$order_action_key]['heading'] = "Other";
				}
			}
		}
		if(!empty($staff_id)){
			$temp = User::select(DB::raw("CONCAT(users.first_name,' ',users.last_name) as staff_name"))->where("users.id", $staff_id)->first();
			$staff_name = $temp->staff_name;
		}else{
			$staff_name = "All Staff";
		}
		

		// dd($getProductConsumption);

		return PDF::loadView('pdfTemplates.productConsumptionPDFReport', compact('getProductConsumption', 'staff_name', 'start_date', 'end_date'))->setPaper('a4')->download('product_consumption.pdf');
	}

	public function getProductConsumptionCSV(Request $request){
		$start_date = (!empty($request->start_date)) ? date('Y-m-d', strtotime($request->start_date)) : NULL;
		$end_date = (!empty($request->end_date)) ? $request->end_date : NULL;
		$staff_id = (!empty($request->staff_id)) ? $request->staff_id : NULL;
		$order_action = (!empty($request->order_action)) ? $request->order_action : NULL;

		$getProductConsumption = [];

		if(!empty($order_action)){
			foreach($order_action as $order_action_key => $order_action_value){
				$temp = new Request;
				$temp->start_date = $start_date;
				$temp->end_date = $end_date;
				$temp->staff_id = $staff_id;
				$temp->order_action = $order_action_value;

				$tempData = $this->getProductConsumption($temp);
				$getProductConsumption[] = $tempData;
				// $getProductConsumption[$order_action_key]['temp'] = $order_action_value;
				if($order_action_value == "getProductConsumptionInternalUse"){
					$getProductConsumption[$order_action_key]['heading'] = "Internal Use";
				}elseif($order_action_value == "getProductConsumptionDamaged"){
					$getProductConsumption[$order_action_key]['heading'] = "Damaged";
				}elseif($order_action_value == "getProductConsumptionLost"){
					$getProductConsumption[$order_action_key]['heading'] = "Lost";
				}elseif($order_action_value == "getProductConsumptionOutOfDate"){
					$getProductConsumption[$order_action_key]['heading'] = "Out Of Date";
				}elseif($order_action_value == "getProductConsumptionAdjustment"){
					$getProductConsumption[$order_action_key]['heading'] = "Adjustment";
				}elseif($order_action_value == "getProductConsumptionOther"){
					$getProductConsumption[$order_action_key]['heading'] = "Other";
				}
			}
		}
		// dd($getProductConsumption);

		return Excel::download(new productConsumptionCSVReport($getProductConsumption), 'product_consumption.csv');
	}

	public function getProductConsumptionExcel(Request $request){
		$start_date = (!empty($request->start_date)) ? date('Y-m-d', strtotime($request->start_date)) : NULL;
		$end_date = (!empty($request->end_date)) ? $request->end_date : NULL;
		$staff_id = (!empty($request->staff_id)) ? $request->staff_id : NULL;
		$order_action = (!empty($request->order_action)) ? $request->order_action : NULL;

		$getProductConsumption = [];

		if(!empty($order_action)){
			foreach($order_action as $order_action_key => $order_action_value){
				$temp = new Request;
				$temp->start_date = $start_date;
				$temp->end_date = $end_date;
				$temp->staff_id = $staff_id;
				$temp->order_action = $order_action_value;

				$tempData = $this->getProductConsumption($temp);
				$getProductConsumption[] = $tempData;
				// $getProductConsumption[$order_action_key]['temp'] = $order_action_value;
				if($order_action_value == "getProductConsumptionInternalUse"){
					$getProductConsumption[$order_action_key]['heading'] = "Internal Use";
				}elseif($order_action_value == "getProductConsumptionDamaged"){
					$getProductConsumption[$order_action_key]['heading'] = "Damaged";
				}elseif($order_action_value == "getProductConsumptionLost"){
					$getProductConsumption[$order_action_key]['heading'] = "Lost";
				}elseif($order_action_value == "getProductConsumptionOutOfDate"){
					$getProductConsumption[$order_action_key]['heading'] = "Out Of Date";
				}elseif($order_action_value == "getProductConsumptionAdjustment"){
					$getProductConsumption[$order_action_key]['heading'] = "Adjustment";
				}elseif($order_action_value == "getProductConsumptionOther"){
					$getProductConsumption[$order_action_key]['heading'] = "Other";
				}
			}
		}

		// dd($getProductConsumption);

		return Excel::download(new productConsumptionExcelReport($getProductConsumption), 'product_consumption.xlsx');
	}

	public function taxesSummary(){
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
		$Locations = Location::select('id','location_name')->where(['user_id'=>$AdminId])->orderBy('id', 'ASC')->get()->toArray();	
		
		// Get all staff
		// $Staff = Staff::select('staff.staff_user_id','users.first_name','users.last_name')->where(['staff.user_id'=>$AdminId])->join('users','users.id','staff.staff_user_id')->orderBy('staff.id', 'ASC')->get()->toArray();	
		return view('analytics.taxes_summary', compact('Locations'));
	}
	
	public function getTaxesSummary(Request $request){
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

		$start_date = (!empty($request->start_date)) ? date('Y-m-d', strtotime($request->start_date)) : NULL;
		$end_date = (!empty($request->end_date)) ? $request->end_date : NULL;
		$location_id = (!empty($request->location_id)) ? $request->location_id : NULL;

		$whereArray = [];
		
		if(!empty($start_date)){
			$whereArray[] = [DB::raw('DATE(invoice.created_at)'), '>=', $start_date];
		}
		if(!empty($end_date)){
			$whereArray[] = [DB::raw('DATE(invoice.created_at)'), '<=', $end_date];
		}
		// if(!empty($staff_id)){
		// 	$whereArray[] = ["inventory_order_logs.received_by", '=', $staff_id];
		// }
		if(!empty($location_id)){
			$whereArray[] = ["invoice.location_id", '=', $location_id];
		}
		// $whereArray[] = ['inventory_order_logs.is_void_invoice', "=", 0];

		$data = [];

		if(empty($location_id)){
			$Locations = Location::select("locations.id")->where("locations.user_id", $AdminId)->get()->toArray();
			// print_r($Locations['id']);die;
			$invoiceIds = Invoice::where("invoice.user_id", $AdminId)->where($whereArray)->where("invoice.invoice_status", 1)->pluck("invoice.id");
			$refundInvoiceIds = Invoice::where("user_id", $AdminId)->where($whereArray)->where("invoice.invoice_status", 2)->whereIn("invoice.original_invoice_id", $invoiceIds)->pluck("invoice.original_invoice_id");
			// dd($refundInvoiceIds);
			
			foreach($Locations as $LocationsKey => $LocationsValue){

				$withoutGroupTaxes = InvoiceItems::select("invoice.id", "taxes.tax_name as tax", "locations.location_name as location", DB::raw("SUM(invoice_items.quantity) as item_sales"), "taxes.tax_rates", "invoice_items.item_tax_amount as amount", "taxes.id as tax_id")
					->Join("invoice", "invoice.id", "invoice_items.invoice_id")
					->Join("taxes", "taxes.id", "invoice_items.item_tax_id")
					->Join("locations", "locations.id", "invoice.location_id")
					->where("taxes.is_group", 0)
					->whereIn("invoice.invoice_status", ['0', '1'])
					->where("invoice.location_id", $LocationsValue['id'])
					->where("invoice.user_id", $AdminId)
					->where($whereArray)
					->get()->toArray();

					$flag2 = true;
					if(!empty($withoutGroupTaxes[0]['tax'])){
						$flag2 = false;
					}

				$withGroupTaxes = InvoiceItems::select("invoice.id", "taxes.tax_name as tax", "locations.location_name as location", DB::raw("SUM(invoice_items.quantity) as item_sales"), "taxes.tax_rates", DB::raw("SUM(invoice_items.item_tax_amount) as amount"), "taxes.id as tax_id")
					->Join("invoice", "invoice.id", "invoice_items.invoice_id")
					->Join("taxes", "taxes.id", "invoice_items.item_tax_id")
					->Join("locations", "locations.id", "invoice.location_id")
					->where("taxes.is_group", 1)
					->whereIn("invoice.invoice_status", ['0', '1'])
					->where("invoice.location_id", $LocationsValue['id'])
					->where("invoice.user_id", $AdminId)
					->where($whereArray)
					->groupBy("taxes.tax_rates")
					->get()->toArray();

					$flag3 = true;
					if(!empty($withGroupTaxes[0]['tax'])){
						$flag3 = false;
					}

				$withoutGroupTaxesRefund = InvoiceItems::select("invoice.id", "taxes.tax_name as tax", "locations.location_name as location", DB::raw("SUM(invoice_items.quantity) as item_sales"), "taxes.tax_rates as rate", "invoice_items.item_tax_amount as amount", "taxes.id as tax_id")
					->Join("invoice", "invoice.id", "invoice_items.invoice_id")
					->Join("taxes", "taxes.id", "invoice_items.item_tax_id")
					->Join("locations", "locations.id", "invoice.location_id")
					->where("taxes.is_group", 0)
					->whereIn("invoice.original_invoice_id", $refundInvoiceIds)
					->where("invoice.invoice_status", 2)
					->where("invoice.location_id", $LocationsValue['id'])
					->where("invoice.user_id", $AdminId)
					->where($whereArray)
					->get()->toArray();

				$withGroupTaxesRefund = InvoiceItems::select("invoice.id", "taxes.tax_name as tax", "locations.location_name as location", DB::raw("SUM(invoice_items.quantity) as item_sales"), "taxes.tax_rates", DB::raw("SUM(invoice_items.item_tax_amount) as amount"), "taxes.id as tax_id")
					->Join("invoice", "invoice.id", "invoice_items.invoice_id")
					->Join("taxes", "taxes.id", "invoice_items.item_tax_id")
					->Join("locations", "locations.id", "invoice.location_id")
					->where("taxes.is_group", 1)
					->whereIn("invoice.original_invoice_id", $refundInvoiceIds)
					->where("invoice.invoice_status", 2)
					->where("invoice.location_id", $LocationsValue['id'])
					->where("invoice.user_id", $AdminId)
					->where($whereArray)
					->groupBy("taxes.tax_rates")
					->get()->toArray();
					// dd($withGroupTaxesRefund);


				foreach($withGroupTaxes as $withGroupTaxesKey => $withGroupTaxesValue){
					$tax_id_array = explode(', ', $withGroupTaxesValue['tax_rates']);
					$total_tax_percent = Taxes::whereIn("id", $tax_id_array)->select(DB::raw("SUM(tax_rates) as tax_per"))->first();
					foreach($tax_id_array as $key => $value){
						$flag = true;
						if(!empty($value)){
							foreach($withoutGroupTaxes as $withoutGroupTaxesKey => $withoutGroupTaxesValue){
								if(!empty($withoutGroupTaxesValue['tax_id']) && $withoutGroupTaxesValue['tax_id'] == $value && $withoutGroupTaxesValue['location'] = $withGroupTaxesValue['location']){
									$flag = false;
									$taxRate = Taxes::where("id", $value)->select("tax_rates", "tax_name")->first();

									$withoutGroupTaxes[ $withoutGroupTaxesKey ]['amount'] += ($taxRate->tax_rates * $withGroupTaxesValue['amount'] / $total_tax_percent->tax_per);

									$withoutGroupTaxes[ $withoutGroupTaxesKey ]['item_sales'] += $withGroupTaxesValue['item_sales'];
								}
							}
							if($flag == true){
								// echo "tax id:- ". $value . "<br>";
								$temp = [];
								$taxRate = Taxes::where("id", $value)->select("tax_rates", "tax_name")->first();
								$temp['tax'] = $taxRate['tax_name'];
								$temp['location'] = $withGroupTaxesValue['location'];
								$temp['item_sales'] = $withGroupTaxesValue['item_sales'];
								$temp['tax_rates'] = $taxRate->tax_rates;
								$temp['amount'] = ((!empty($taxRate->tax_rates) ? $taxRate->tax_rates : 1) * $withGroupTaxesValue['amount']) / $total_tax_percent->tax_per;
								$temp['tax_id'] = $value;
	
								array_push($withoutGroupTaxes, $temp);
							}
						}
					}
				}
				
				foreach($withGroupTaxesRefund as $withGroupTaxesRefundKey => $withGroupTaxesRefundValue){
					$tax_id_array = explode(', ', $withGroupTaxesRefundValue['tax_rates']);
					$total_tax_percent = Taxes::whereIn("id", $tax_id_array)->select(DB::raw("SUM(tax_rates) as tax_per"))->first();
					foreach($tax_id_array as $key => $value){
						$flag = true;
						if(!empty($value)){
							foreach($withoutGroupTaxesRefund as $withoutGroupTaxesRefundKey => $withoutGroupTaxesRefundValue){
								if(!empty($withoutGroupTaxesRefundValue['tax_id']) && $withoutGroupTaxesRefundValue['tax_id'] == $value && $withoutGroupTaxesRefundValue['location'] == $withGroupTaxesRefundValue['location']){
									$flag = false;
									$taxRate = Taxes::where("id", $value)->select("tax_rates", "tax_name")->first();

									$withoutGroupTaxesRefund[ $withoutGroupTaxesRefundKey ]['amount'] += ($taxRate->tax_rates * $withGroupTaxesRefundValue['amount'] / $total_tax_percent->tax_per);

									$withoutGroupTaxesRefund[ $withoutGroupTaxesRefundKey ]['item_sales'] += $withGroupTaxesRefundValue['item_sales'];
								}
							}
							if($flag == true){
								// echo "tax id:- ". $value . "<br>";
								$temp = [];
								$taxRate = Taxes::where("id", $value)->select("tax_rates", "tax_name")->first();
								$temp['tax'] = $taxRate['tax_name'];
								$temp['location'] = $withGroupTaxesRefundValue['location'];
								$temp['item_sales'] = $withGroupTaxesRefundValue['item_sales'];
								$temp['tax_rates'] = $taxRate->tax_rates;
								$temp['amount'] = ((!empty($taxRate->tax_rates) ? $taxRate->tax_rates : 1) * $withGroupTaxesRefundValue['amount']) / $total_tax_percent->tax_per;
								$temp['tax_id'] = $value;
	
								array_push($withoutGroupTaxesRefund, $temp);
							}
						}
					}
				}
				
				// echo "Before :-<br> "; print_r($withoutGroupTaxes)."<br>";
				
				foreach($withoutGroupTaxesRefund as $wrKey => $wrValue){
					foreach($withoutGroupTaxes as $wKey => $wValue){
						if($wValue['tax_id'] == $wrValue['tax_id'] && $wValue['location'] == $wrValue['location']){
							$withoutGroupTaxes[ $wKey ]['amount'] -= $wrValue['amount'];
							$withoutGroupTaxes[ $wKey ]['item_sales'] -= $wrValue['item_sales'];
						}
					}
				}
				// echo "After :-<br> ";
				// dd($withoutGroupTaxes);

				
				if($flag2 == false || $flag3 == false){
					$data = array_merge($data, $withoutGroupTaxes);
				}	
				foreach($data as $dKey => $dValue){
					if($dValue['tax_id'] == null || $dValue['tax_id'] == ''){
						// echo "<pre> Before :- <br>";
						// print_r($data);
						unset($data[$dKey]);
						// echo "<br>After :- <br>";
						// dd($data);
					}
				}
			}
		}else{

			$invoiceIds = Invoice::where("invoice.user_id", $AdminId)->where($whereArray)->where("invoice.invoice_status", 1)->pluck("invoice.id");
			$refundInvoiceIds = Invoice::where("user_id", $AdminId)->where($whereArray)->where("invoice.invoice_status", 2)->whereIn("invoice.original_invoice_id", $invoiceIds)->pluck("invoice.original_invoice_id");
			// dd($refundInvoiceIds);
			
			$withoutGroupTaxes = InvoiceItems::select("invoice.id", "taxes.tax_name as tax", "locations.location_name as location", DB::raw("SUM(invoice_items.quantity) as item_sales"), "taxes.tax_rates", "invoice_items.item_tax_amount as amount", "taxes.id as tax_id")
				->Join("invoice", "invoice.id", "invoice_items.invoice_id")
				->Join("taxes", "taxes.id", "invoice_items.item_tax_id")
				->Join("locations", "locations.id", "invoice.location_id")
				->where("taxes.is_group", 0)
				->whereIn("invoice.invoice_status", ['0', '1'])
				->where("invoice.user_id", $AdminId)
				->where($whereArray)
				->get()->toArray();

				$flag2 = true;
				if(!empty($withoutGroupTaxes[0]['tax'])){
					$flag2 = false;
				}

			$withGroupTaxes = InvoiceItems::select("invoice.id", "taxes.tax_name as tax", "locations.location_name as location", DB::raw("SUM(invoice_items.quantity) as item_sales"), "taxes.tax_rates", DB::raw("SUM(invoice_items.item_tax_amount) as amount"), "taxes.id as tax_id")
				->Join("invoice", "invoice.id", "invoice_items.invoice_id")
				->Join("taxes", "taxes.id", "invoice_items.item_tax_id")
				->Join("locations", "locations.id", "invoice.location_id")
				->where("taxes.is_group", 1)
				->whereIn("invoice.invoice_status", ['0', '1'])
				->where("invoice.user_id", $AdminId)
				->where($whereArray)
				->groupBy("taxes.tax_rates")
				->get()->toArray();

				$flag3 = true;
				if(!empty($withGroupTaxes[0]['tax'])){
					$flag3 = false;
				}

			$withoutGroupTaxesRefund = InvoiceItems::select("invoice.id", "taxes.tax_name as tax", "locations.location_name as location", DB::raw("SUM(invoice_items.quantity) as item_sales"), "taxes.tax_rates as rate", "invoice_items.item_tax_amount as amount", "taxes.id as tax_id")
				->Join("invoice", "invoice.id", "invoice_items.invoice_id")
				->Join("taxes", "taxes.id", "invoice_items.item_tax_id")
				->Join("locations", "locations.id", "invoice.location_id")
				->where("taxes.is_group", 0)
				->whereIn("invoice.original_invoice_id", $refundInvoiceIds)
				->where("invoice.invoice_status", 2)
				->where("invoice.user_id", $AdminId)
				->where($whereArray)
				->get()->toArray();

			$withGroupTaxesRefund = InvoiceItems::select("invoice.id", "taxes.tax_name as tax", "locations.location_name as location", DB::raw("SUM(invoice_items.quantity) as item_sales"), "taxes.tax_rates", DB::raw("SUM(invoice_items.item_tax_amount) as amount"), "taxes.id as tax_id")
				->Join("invoice", "invoice.id", "invoice_items.invoice_id")
				->Join("taxes", "taxes.id", "invoice_items.item_tax_id")
				->Join("locations", "locations.id", "invoice.location_id")
				->where("taxes.is_group", 1)
				->whereIn("invoice.original_invoice_id", $refundInvoiceIds)
				->where("invoice.invoice_status", 2)
				->where("invoice.user_id", $AdminId)
				->where($whereArray)
				->groupBy("taxes.tax_rates")
				->get()->toArray();
				// dd($withGroupTaxesRefund);


			foreach($withGroupTaxes as $withGroupTaxesKey => $withGroupTaxesValue){
				$tax_id_array = explode(', ', $withGroupTaxesValue['tax_rates']);
				$total_tax_percent = Taxes::whereIn("id", $tax_id_array)->select(DB::raw("SUM(tax_rates) as tax_per"))->first();
				foreach($tax_id_array as $key => $value){
					$flag = true;
					if(!empty($value)){
						foreach($withoutGroupTaxes as $withoutGroupTaxesKey => $withoutGroupTaxesValue){
							if(!empty($withoutGroupTaxesValue['tax_id']) && $withoutGroupTaxesValue['tax_id'] == $value && $withoutGroupTaxesValue['location'] = $withGroupTaxesValue['location']){
								$flag = false;
								$taxRate = Taxes::where("id", $value)->select("tax_rates", "tax_name")->first();

								$withoutGroupTaxes[ $withoutGroupTaxesKey ]['amount'] += ($taxRate->tax_rates * $withGroupTaxesValue['amount'] / $total_tax_percent->tax_per);

								$withoutGroupTaxes[ $withoutGroupTaxesKey ]['item_sales'] += $withGroupTaxesValue['item_sales'];
							}
						}
						if($flag == true){
							// echo "tax id:- ". $value . "<br>";
							$temp = [];
							$taxRate = Taxes::where("id", $value)->select("tax_rates", "tax_name")->first();
							$temp['tax'] = $taxRate['tax_name'];
							$temp['location'] = $withGroupTaxesValue['location'];
							$temp['item_sales'] = $withGroupTaxesValue['item_sales'];
							$temp['tax_rates'] = $taxRate->tax_rates;
							$temp['amount'] = ((!empty($taxRate->tax_rates) ? $taxRate->tax_rates : 1) * $withGroupTaxesValue['amount']) / $total_tax_percent->tax_per;
							$temp['tax_id'] = $value;

							array_push($withoutGroupTaxes, $temp);
						}
					}
				}
			}
			
			foreach($withGroupTaxesRefund as $withGroupTaxesRefundKey => $withGroupTaxesRefundValue){
				$tax_id_array = explode(', ', $withGroupTaxesRefundValue['tax_rates']);
				$total_tax_percent = Taxes::whereIn("id", $tax_id_array)->select(DB::raw("SUM(tax_rates) as tax_per"))->first();
				foreach($tax_id_array as $key => $value){
					$flag = true;
					if(!empty($value)){
						foreach($withoutGroupTaxesRefund as $withoutGroupTaxesRefundKey => $withoutGroupTaxesRefundValue){
							if(!empty($withoutGroupTaxesRefundValue['tax_id']) && $withoutGroupTaxesRefundValue['tax_id'] == $value && $withoutGroupTaxesRefundValue['location'] == $withGroupTaxesRefundValue['location']){
								$flag = false;
								$taxRate = Taxes::where("id", $value)->select("tax_rates", "tax_name")->first();

								$withoutGroupTaxesRefund[ $withoutGroupTaxesRefundKey ]['amount'] += ($taxRate->tax_rates * $withGroupTaxesRefundValue['amount'] / $total_tax_percent->tax_per);

								$withoutGroupTaxesRefund[ $withoutGroupTaxesRefundKey ]['item_sales'] += $withGroupTaxesRefundValue['item_sales'];
							}
						}
						if($flag == true){
							// echo "tax id:- ". $value . "<br>";
							$temp = [];
							$taxRate = Taxes::where("id", $value)->select("tax_rates", "tax_name")->first();
							$temp['tax'] = $taxRate['tax_name'];
							$temp['location'] = $withGroupTaxesRefundValue['location'];
							$temp['item_sales'] = $withGroupTaxesRefundValue['item_sales'];
							$temp['tax_rates'] = $taxRate->tax_rates;
							$temp['amount'] = ((!empty($taxRate->tax_rates) ? $taxRate->tax_rates : 1) * $withGroupTaxesRefundValue['amount']) / $total_tax_percent->tax_per;
							$temp['tax_id'] = $value;

							array_push($withoutGroupTaxesRefund, $temp);
						}
					}
				}
			}
			
			// echo "Before :-<br> "; print_r($withoutGroupTaxes)."<br>";
			
			foreach($withoutGroupTaxesRefund as $wrKey => $wrValue){
				foreach($withoutGroupTaxes as $wKey => $wValue){
					if($wValue['tax_id'] == $wrValue['tax_id'] && $wValue['location'] == $wrValue['location']){
						$withoutGroupTaxes[ $wKey ]['amount'] -= $wrValue['amount'];
						$withoutGroupTaxes[ $wKey ]['item_sales'] -= $wrValue['item_sales'];
					}
				}
			}

			if($flag2 == false || $flag3 == false){
				$data = array_merge($data, $withoutGroupTaxes);
			}

			foreach($data as $dKey => $dValue){
				if($dValue['tax_id'] == null || $dValue['tax_id'] == ''){
					// echo "<pre> Before :- <br>";
					// print_r($data);
					unset($data[$dKey]);
					// echo "<br>After :- <br>";
					// dd($data);
				}
			}

		}
		
		usort($data, function($element1, $element2) {
			$datetime1 = $element1['tax_id'];
			$datetime2 = $element2['tax_id'];
			return $datetime1 - $datetime2;
		});
		// dd($data);

		$response = DataTables::of($data)
		->editColumn("amount", function($raw){
			return "CA $".number_format($raw['amount'], 2);
		})
		->editColumn("tax_rates", function($raw){
			return $raw['tax_rates']."%";
		})
		->addColumn("tempAmount", function($raw){
			return number_format($raw['amount'], 2);
		})
		->rawColumns(['amount', 'tax_rates', 'tempAmount'])
		->make(true);

		$finalData = $response->getData(true);


		// $totalCost = array_sum($data['data'], 'demo_total_cost');
		if(!empty($data)){
			$total = "CA $".number_format(array_sum(array_column($finalData['data'],'tempAmount')), 2);
		}else{
			$total = "CA $0.00";
		}

		$finalData['total'] = $total;
		
		// dd($finalData);
		// echo $totalCost;die;

		// dd($data);
		return $finalData;
	}

	public function getTaxesSummaryPDF(Request $request){
		$getTaxesSummary = $this->getTaxesSummary($request);

		$location_id = (!empty($request->location_id)) ? $request->location_id : null;
		$start_date = (!empty($request->start_date)) ? $request->start_date : null;
		$end_date = (!empty($request->end_date)) ? $request->end_date : null;

		if(!empty($location_id)){
			$temp = Location::select("location_name as location")->where('id',$location_id)->first();

			$location_name = $temp->location;
		}else{
			$location_name = "All Locations";
		}

		// dd($getFinancesSummary);

		return PDF::loadView('pdfTemplates.taxesSummaryPDFReport', compact('getTaxesSummary','location_name', 'start_date', 'end_date'))->setPaper('a4')->download("taxes_summary.pdf");
	}
	
	public function getTaxesSummaryCSV(Request $request){
		$getTaxesSummary = $this->getTaxesSummary($request);

		// dd($data);
		return Excel::download(new taxesSummaryCSVReport($getTaxesSummary), 'taxes_summary.csv');
	}

	public function getTaxesSummaryExcel(Request $request){
		$getTaxesSummary = $this->getTaxesSummary($request);

		return Excel::download(new taxesSummaryExcelReport($getTaxesSummary), 'taxes_summary.xlsx');
	}

	public function salesByItem(){
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
		
		return view('analytics.sales_by_item',compact('Locations','Staff'));
	}

	public function getSalesByItem(Request $request){
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

		$start_date = (!empty($request->start_date)) ? date('Y-m-d', strtotime($request->start_date)) : NULL;
		$end_date = (!empty($request->end_date)) ? date('Y-m-d', strtotime($request->end_date)) : NULL;
		$location_id = (!empty($request->location_id)) ? $request->location_id : NULL;
		$staff_id = (!empty($request->staff_id)) ? $request->staff_id : NULL;

		$whereArray = [];

		if(!empty($start_date)){
			$whereArray[] = ['invoice.payment_date', '>=', $start_date];
		}
		if(!empty($end_date)){
			$whereArray[] = ['invoice.payment_date', '<=', $end_date];
		}
		if(!empty($location_id)){
			$whereArray[] = ['invoice.location_id', '=', $location_id];
		}
		if(!empty($staff_id)){
			$whereArray[] = ['users.id', '=', $staff_id];
		}

		$data = InvoiceItems::select(DB::raw("COUNT(invoice_items.id) as items_sold"), DB::raw("SUM(invoice_items.item_og_price) as gross_sales"), DB::raw("SUM(invoice_items.item_og_price - invoice_items.item_price) as discounts"), DB::raw("SUM(invoice_items.item_tax_amount) as tax"), 'invoice_items.item_id', 'invoice_items.item_type')
		// ->leftJoin('inventory_products','inventory_products.id','invoice_items.item_id')
		->leftJoin('invoice','invoice.id','invoice_items.invoice_id')
		->leftJoin('staff','staff.id','invoice_items.staff_id')
		->leftJoin('users','users.id','staff.staff_user_id')
		->where($whereArray)
		->where('invoice.user_id',$AdminId)
		->whereIn('invoice.invoice_status',['0','1','2'])
		->whereIn('invoice_items.item_type',['product','services'])
		->groupBy('invoice_items.item_id')
		->get()->toArray();

		// $dataTotal = InvoiceItems::select(DB::raw("COUNT(invoice_items.id) as items_sold"), DB::raw("SUM(invoice_items.item_og_price) as gross_sales"), DB::raw("SUM(invoice_items.item_og_price - invoice_items.item_price) as discounts"), DB::raw("SUM(invoice_items.item_tax_amount) as tax"))
		// ->leftJoin('invoice','invoice.id','invoice_items.invoice_id')
		// ->leftJoin('staff','staff.id','invoice_items.staff_id')
		// ->leftJoin('users','users.id','staff.staff_user_id')
		// ->where($whereArray)
		// ->where('invoice.user_id',$AdminId)
		// ->whereIn('invoice.invoice_status',['0','1','2'])
		// ->whereIn('invoice_items.item_type',['product','services'])
		// ->first();

		// echo "<pre>"; echo $refund;die;

		$refundData = InvoiceItems::select(DB::raw("SUM(invoice_items.item_price) as gross_sales"), 'invoice_items.item_id', DB::raw("SUM(invoice_items.item_tax_amount) as tax"))
		->leftJoin('invoice','invoice.id','invoice_items.invoice_id')
		->leftJoin('staff','staff.id','invoice_items.staff_id')
		->leftJoin('users','users.id','staff.staff_user_id')
		->where($whereArray)
		->where('invoice.user_id',$AdminId)
		->where('invoice.invoice_status',2)
		->whereIn('invoice_items.item_type',['product','services'])
		->groupBy('invoice_items.item_id')
		->get()
		->toArray();

		$refundDataSum = 0;

		if(!empty($refundData)){
			// echo "<script>alert('Entered into');</script>";
			foreach($refundData as $refundDataKey => $refundDataValue){
				$refundDataSum += $refundDataValue['gross_sales'];
				$flag = [];
				foreach($data as $dataKey => $dataValue){
					if($dataValue['item_type'] == 'product'){
						if($refundDataValue['item_id'] == $dataValue['item_id']){
							// $flag = 'flag';
							$item = InventoryProducts::select('product_name as item')->where('id',$dataValue['item_id'])->first();
							$data[$dataKey]['refunds'] = number_format($refundDataValue['gross_sales'],2,'.','');
							$data[$dataKey]['tax'] -= number_format($refundDataValue['tax'],2,'.','');
							$data[$dataKey]['item'] = $item['item'];
							$data[$dataKey]['net_sales'] = ($dataValue['gross_sales'] - $dataValue['discounts'] - $refundDataValue['gross_sales']);
						}else{
							$flag[] = $dataKey;
						}
					}else{
						if($refundDataValue['item_id'] == $dataValue['item_id']){
							// $flag = 'flag';
							$item = Services::select('service_name as item')->where('id', $dataValue['item_id'])->first();
							$data[$dataKey]['item'] = $item['item'];
							$data[$dataKey]['net_sales'] = ($dataValue['gross_sales'] - $dataValue['discounts'] - $refundDataValue['gross_sales']);
							$data[$dataKey]['refunds'] = number_format($refundDataValue['gross_sales'],2,'.','');
							$data[$dataKey]['tax'] -= number_format($refundDataValue['tax'],2,'.','');
						}else{
							$flag[] = $dataKey;
						}
					}
				}
				foreach($flag as $flagKey){
					if(isset($data[$flagKey]['refunds'])){
	
					}else{
						if($data[$flagKey]['item_type'] == 'product'){
							$item = InventoryProducts::select('product_name as item')->where('id',$data[$flagKey]['item_id'])->first();
							$data[$flagKey]['refunds'] = '0.00';
							$data[$flagKey]['item'] = $item['item'];
							$data[$flagKey]['net_sales'] = ($data[$flagKey]['gross_sales'] - $data[$flagKey]['discounts']);
						}else{
							$item = Services::select('service_name as item')->where('id', $data[$flagKey]['item_id'])->first();
							$data[$flagKey]['item'] = $item['item'];
							$data[$flagKey]['net_sales'] = ($data[$flagKey]['gross_sales'] - $data[$flagKey]['discounts']);
							$data[$flagKey]['refunds'] = '0.00';
						}
					}
				}
			}
		}else{
			foreach($data as $dataK => $dataV){
				if($dataV['item_type'] == 'product'){
					// echo "<script>alert('Entered');</script>";
					$item2 = InventoryProducts::select('product_name as item')->where('id',$dataV['item_id'])->first();
					$data[$dataK]['refunds'] = '0.00';
					$data[$dataK]['item'] = $item2['item'];
					$data[$dataK]['net_sales'] = ($dataV['gross_sales'] - $dataV['discounts']);
				}else{
					$item2 = Services::select('service_name as item')->where('id', $dataV['item_id'])->first();
					$data[$dataK]['item'] = $item2['item'];
					$data[$dataK]['net_sales'] = ($dataV['gross_sales'] - $dataV['discounts']);
					$data[$dataK]['refunds'] = '0.00';
				}
			}
		}

		$dataTotal = [];
		$dataTotal['items_sold'] = 0;
		$dataTotal['gross_sales'] = 0;
		$dataTotal['discounts'] = 0;
		$dataTotal['tax'] = 0;

		foreach($data as $data_Key => $data_Value){
			$dataTotal['items_sold'] += !empty($data_Value['items_sold']) ? $data_Value['items_sold'] : 0;
			$dataTotal['gross_sales'] += !empty($data_Value['gross_sales']) ? $data_Value['gross_sales'] : 0;
			$dataTotal['discounts'] += !empty($data_Value['discounts']) ? $data_Value['discounts'] : 0;
			$dataTotal['tax'] += !empty($data_Value['tax']) ? $data_Value['tax'] : 0;
		}


		// echo "<pre>";print_r($data);die;

		$response = DataTables::of($data)
		->addColumn('total_sales', function($raw){
			$total_sales = ($raw['net_sales'] + $raw['tax']);
			return "CA $".number_format($total_sales,2,'.','');
		})
		->editColumn('tax', function($raw){
			if(empty($raw['tax'])){
				return 'CA $0.00';
			}else{
				return "CA $".number_format($raw['tax'],2,".","");
			}
		})
		->editColumn('net_sales', function($raw){
			return "CA $".number_format($raw['net_sales'],2,".","");
		})
		->editColumn('discounts', function($raw){
			return "CA $".number_format($raw['discounts'],2,".","");
		})
		->editColumn('gross_sales', function($raw){
			return "CA $".number_format($raw['gross_sales'],2,".","");
		})
		->editColumn('refunds', function($raw){
			return "CA $".number_format($raw['refunds'],2,".","");
		})
		->rawColumns(['net_sales','total_sales','gross_sales','discounts','tax'])
		->make(true);

		$total_total_sales = ($dataTotal['gross_sales'] - $dataTotal['discounts'] - $refundDataSum) + $dataTotal['tax'];

		$data = $response->getData(true);
		$data['total_items_sold'] = $dataTotal['items_sold'];
		$data['total_gross_sales'] = "CA $".number_format($dataTotal['gross_sales'], 2, '.', '');
		$data['total_discounts'] = "CA $".number_format($dataTotal['discounts'], 2, '.', '');
		$data['total_tax'] = "CA $".number_format($dataTotal['tax'], 2, '.', '');
		$data['total_refunds'] = "CA $".number_format($refundDataSum, 2, '.', '');
		$data['total_net_sales'] = "CA $".number_format(($dataTotal['gross_sales'] - $dataTotal['discounts'] - $refundDataSum), 2, '.', '');
		$data['total_total_sales'] = "CA $".number_format($total_total_sales, 2, '.', '');

		return $data;
		
	}

	public function getSalesByItemPDF(Request $request){
		$getSalesByItem = $this->getSalesByItem($request);

		$data = $getSalesByItem['data'];
		$total_items_sold = $getSalesByItem['total_items_sold'];
		$total_gross_sales = $getSalesByItem['total_gross_sales'];
		$total_discounts = $getSalesByItem['total_discounts'];
		$total_tax = $getSalesByItem['total_tax'];
		$total_refunds = $getSalesByItem['total_refunds'];
		$total_net_sales = $getSalesByItem['total_net_sales'];
		$total_total_sales = $getSalesByItem['total_total_sales'];

		$location_id = (!empty($request->location_id)) ? $request->location_id : null;
		$staff_id = (!empty($request->staff_id)) ? $request->staff_id : null;

		if(!empty($location_id)){
			$temp = Location::select("location_name as location")->where('id',$location_id)->first();

			$location_name = $temp->location;
		}else{
			$location_name = "All Locations";
		}
		if(!empty($staff_id)){
			$temp = User::select(DB::raw("CONCAT(first_name,' ',last_name) as staff"))->where('id',$staff_id)->first();

			$staff_name = $temp->staff;
		}else{
			$staff_name = "All Staff";
		}

		// dd($getSalesByItem);
		return PDF::loadView('pdfTemplates.salesByItemPDFReport', compact('data','total_items_sold','total_gross_sales','total_discounts','total_refunds','total_net_sales','total_tax','total_total_sales','location_name','staff_name'))->setPaper('a4')->download('sales_by_item.pdf');
	}

	public function getSalesByItemCSV(Request $request){
		$getSalesByItem = $this->getSalesByItem($request);

		$data = $getSalesByItem['data'];
		$total_items_sold = $getSalesByItem['total_items_sold'];
		$total_gross_sales = $getSalesByItem['total_gross_sales'];
		$total_discounts = $getSalesByItem['total_discounts'];
		$total_tax = $getSalesByItem['total_tax'];
		$total_refunds = $getSalesByItem['total_refunds'];
		$total_net_sales = $getSalesByItem['total_net_sales'];
		$total_total_sales = $getSalesByItem['total_total_sales'];

		return Excel::download(new salesByItemCSVReport($data, $total_items_sold, $total_gross_sales, $total_discounts, $total_tax, $total_refunds, $total_net_sales, $total_total_sales), 'sales_by_item.csv');
	}

	public function getSalesByItemExcel(Request $request){
		$getSalesByItem = $this->getSalesByItem($request);

		$data = $getSalesByItem['data'];
		$total_items_sold = $getSalesByItem['total_items_sold'];
		$total_gross_sales = $getSalesByItem['total_gross_sales'];
		$total_discounts = $getSalesByItem['total_discounts'];
		$total_tax = $getSalesByItem['total_tax'];
		$total_refunds = $getSalesByItem['total_refunds'];
		$total_net_sales = $getSalesByItem['total_net_sales'];
		$total_total_sales = $getSalesByItem['total_total_sales'];

		return Excel::download(new salesByItemExcelReport($data, $total_items_sold, $total_gross_sales, $total_discounts, $total_tax, $total_refunds, $total_net_sales, $total_total_sales), 'sales_by_item.xlsx');
	}
	
	public function salesByType(){
	}
	
	public function salesByService(){
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
		
		return view('analytics.sales_by_service',compact('Locations','Staff'));
	}

	public function getSalesByService(Request $request){
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

		$start_date = (!empty($request->start_date)) ? date('Y-m-d', strtotime($request->start_date)) : NULL;
		$end_date = (!empty($request->end_date)) ? date('Y-m-d', strtotime($request->end_date)) : NULL;
		$location_id = (!empty($request->location_id)) ? $request->location_id : NULL;
		$staff_id = (!empty($request->staff_id)) ? $request->staff_id : NULL;

		$whereArray = [];

		if(!empty($start_date)){
			$whereArray[] = ['invoice.payment_date', '>=', $start_date];
		}
		if(!empty($end_date)){
			$whereArray[] = ['invoice.payment_date', '<=', $end_date];
		}
		if(!empty($location_id)){
			$whereArray[] = ['invoice.location_id', '=', $location_id];
		}
		if(!empty($staff_id)){
			$whereArray[] = ['users.id', '=', $staff_id];
		}

		$data = InvoiceItems::select(DB::raw("COUNT(invoice_items.id) as items_sold"), DB::raw("SUM(invoice_items.item_og_price) as gross_sales"), DB::raw("SUM(invoice_items.item_og_price - invoice_items.item_price) as discounts"), DB::raw("SUM(invoice_items.item_tax_amount) as tax"), 'invoice_items.item_id', 'invoice_items.item_type')
		// ->leftJoin('inventory_products','inventory_products.id','invoice_items.item_id')
		->leftJoin('invoice','invoice.id','invoice_items.invoice_id')
		->leftJoin('staff','staff.id','invoice_items.staff_id')
		->leftJoin('users','users.id','staff.staff_user_id')
		->where($whereArray)
		->where('invoice.user_id',$AdminId)
		->whereIn('invoice.invoice_status',['0','1','2'])
		->where('invoice_items.item_type','services')
		->groupBy('invoice_items.item_id')
		->get()->toArray();

		// $dataTotal = InvoiceItems::select(DB::raw("COUNT(invoice_items.id) as items_sold"), DB::raw("SUM(invoice_items.item_og_price) as gross_sales"), DB::raw("SUM(invoice_items.item_og_price - invoice_items.item_price) as discounts"), DB::raw("SUM(invoice_items.item_tax_amount) as tax"))
		// ->leftJoin('invoice','invoice.id','invoice_items.invoice_id')
		// ->leftJoin('staff','staff.id','invoice_items.staff_id')
		// ->leftJoin('users','users.id','staff.staff_user_id')
		// ->where($whereArray)
		// ->where('invoice.user_id',$AdminId)
		// ->whereIn('invoice.invoice_status',['0','1','2'])
		// ->where('invoice_items.item_type','services')
		// ->first();

		// echo "<pre>"; echo $refund;die;

		$refundData = InvoiceItems::select(DB::raw("SUM(invoice_items.item_price) as gross_sales"), 'invoice_items.item_id', DB::raw("SUM(invoice_items.item_tax_amount) as tax"))
		->leftJoin('invoice','invoice.id','invoice_items.invoice_id')
		->leftJoin('staff','staff.id','invoice_items.staff_id')
		->leftJoin('users','users.id','staff.staff_user_id')
		->where($whereArray)
		->where('invoice.user_id',$AdminId)
		->where('invoice.invoice_status',2)
		->where('invoice_items.item_type','services')
		->groupBy('invoice_items.item_id')
		->get()
		->toArray();

		$refundDataSum = 0;

		if(!empty($refundData)){
			// echo "<script>alert('Entered into');</script>";
			foreach($refundData as $refundDataKey => $refundDataValue){
				$refundDataSum += $refundDataValue['gross_sales'];
				$flag = [];
				foreach($data as $dataKey => $dataValue){
					if($refundDataValue['item_id'] == $dataValue['item_id']){
						// $flag = 'flag';
						$item = Services::select('service_name as item')->where('id', $dataValue['item_id'])->first();
						$data[$dataKey]['item'] = $item['item'];
						$data[$dataKey]['net_sales'] = ($dataValue['gross_sales'] - $dataValue['discounts'] - $refundDataValue['gross_sales']);
						$data[$dataKey]['refunds'] = number_format($refundDataValue['gross_sales'],2,'.','');
						$data[$dataKey]['tax'] -= number_format($refundDataValue['tax'],2,'.','');
					}else{
						$flag[] = $dataKey;
					}
				}
				foreach($flag as $flagKey){
					if(isset($data[$flagKey]['refunds'])){
	
					}else{
						$item = Services::select('service_name as item')->where('id', $data[$flagKey]['item_id'])->first();
						$data[$flagKey]['item'] = $item['item'];
						$data[$flagKey]['net_sales'] = ($data[$flagKey]['gross_sales'] - $data[$flagKey]['discounts']);
						$data[$flagKey]['refunds'] = '0.00';
					}
				}
			}
		}else{
			foreach($data as $dataK => $dataV){
				$item2 = Services::select('service_name as item')->where('id',$dataV['item_id'])->first();
				$data[$dataK]['refunds'] = '0.00';
				$data[$dataK]['item'] = $item2['item'];
				$data[$dataK]['net_sales'] = ($dataV['gross_sales'] - $dataV['discounts']);
			}
		}

		$dataTotal = [];
		$dataTotal['items_sold'] = 0;
		$dataTotal['gross_sales'] = 0;
		$dataTotal['discounts'] = 0;
		$dataTotal['tax'] = 0;

		foreach($data as $data_Key => $data_Value){
			$dataTotal['items_sold'] += !empty($data_Value['items_sold']) ? $data_Value['items_sold'] : 0;
			$dataTotal['gross_sales'] += !empty($data_Value['gross_sales']) ? $data_Value['gross_sales'] : 0;
			$dataTotal['discounts'] += !empty($data_Value['discounts']) ? $data_Value['discounts'] : 0;
			$dataTotal['tax'] += !empty($data_Value['tax']) ? $data_Value['tax'] : 0;
		}


		// echo "<pre>";print_r($data);die;

		$response = DataTables::of($data)
		->addColumn('total_sales', function($raw){
			$total_sales = ($raw['net_sales'] + $raw['tax']);
			return "CA $".number_format($total_sales,2,'.','');
		})
		->editColumn('tax', function($raw){
			if(empty($raw['tax'])){
				return 'CA $0.00';
			}else{
				return "CA $".number_format($raw['tax'],2,".","");
			}
		})
		->editColumn('net_sales', function($raw){
			return "CA $".number_format($raw['net_sales'],2,".","");
		})
		->editColumn('discounts', function($raw){
			return "CA $".number_format($raw['discounts'],2,".","");
		})
		->editColumn('gross_sales', function($raw){
			return "CA $".number_format($raw['gross_sales'],2,".","");
		})
		->editColumn('refunds', function($raw){
			return "CA $".number_format($raw['refunds'],2,".","");
		})
		->rawColumns(['net_sales','total_sales','gross_sales','discounts','tax'])
		->make(true);

		$total_total_sales = ($dataTotal['gross_sales'] - $dataTotal['discounts'] - $refundDataSum) + $dataTotal['tax'];

		$data = $response->getData(true);
		$data['total_items_sold'] = $dataTotal['items_sold'];
		$data['total_gross_sales'] = "CA $".number_format($dataTotal['gross_sales'], 2, '.', '');
		$data['total_discounts'] = "CA $".number_format($dataTotal['discounts'], 2, '.', '');
		$data['total_tax'] = "CA $".number_format($dataTotal['tax'], 2, '.', '');
		$data['total_refunds'] = "CA $".number_format($refundDataSum, 2, '.', '');
		$data['total_net_sales'] = "CA $".number_format(($dataTotal['gross_sales'] - $dataTotal['discounts'] - $refundDataSum), 2, '.', '');
		$data['total_total_sales'] = "CA $".number_format($total_total_sales, 2, '.', '');

		return $data;
		
	}

	public function getSalesByServicePDF(Request $request){
		$getSalesByItem = $this->getSalesByService($request);

		$data = $getSalesByItem['data'];
		$total_items_sold = $getSalesByItem['total_items_sold'];
		$total_gross_sales = $getSalesByItem['total_gross_sales'];
		$total_discounts = $getSalesByItem['total_discounts'];
		$total_tax = $getSalesByItem['total_tax'];
		$total_refunds = $getSalesByItem['total_refunds'];
		$total_net_sales = $getSalesByItem['total_net_sales'];
		$total_total_sales = $getSalesByItem['total_total_sales'];

		$location_id = (!empty($request->location_id)) ? $request->location_id : null;
		$staff_id = (!empty($request->staff_id)) ? $request->staff_id : null;

		if(!empty($location_id)){
			$temp = Location::select("location_name as location")->where('id',$location_id)->first();

			$location_name = $temp->location;
		}else{
			$location_name = "All Locations";
		}
		if(!empty($staff_id)){
			$temp = User::select(DB::raw("CONCAT(first_name,' ',last_name) as staff"))->where('id',$staff_id)->first();

			$staff_name = $temp->staff;
		}else{
			$staff_name = "All Staff";
		}

		// dd($getSalesByItem);
		return PDF::loadView('pdfTemplates.salesByServicePDFReport', compact('data','total_items_sold','total_gross_sales','total_discounts','total_refunds','total_net_sales','total_tax','total_total_sales','location_name','staff_name'))->setPaper('a4')->download('sales_by_service.pdf');
	}

	public function getSalesByServiceCSV(Request $request){
		$getSalesByItem = $this->getSalesByService($request);

		$data = $getSalesByItem['data'];
		$total_items_sold = $getSalesByItem['total_items_sold'];
		$total_gross_sales = $getSalesByItem['total_gross_sales'];
		$total_discounts = $getSalesByItem['total_discounts'];
		$total_tax = $getSalesByItem['total_tax'];
		$total_refunds = $getSalesByItem['total_refunds'];
		$total_net_sales = $getSalesByItem['total_net_sales'];
		$total_total_sales = $getSalesByItem['total_total_sales'];

		return Excel::download(new salesByServiceCSVReport($data, $total_items_sold, $total_gross_sales, $total_discounts, $total_tax, $total_refunds, $total_net_sales, $total_total_sales), 'sales_by_service.csv');
	}

	public function getSalesByServiceExcel(Request $request){
		$getSalesByItem = $this->getSalesByService($request);

		$data = $getSalesByItem['data'];
		$total_items_sold = $getSalesByItem['total_items_sold'];
		$total_gross_sales = $getSalesByItem['total_gross_sales'];
		$total_discounts = $getSalesByItem['total_discounts'];
		$total_tax = $getSalesByItem['total_tax'];
		$total_refunds = $getSalesByItem['total_refunds'];
		$total_net_sales = $getSalesByItem['total_net_sales'];
		$total_total_sales = $getSalesByItem['total_total_sales'];

		return Excel::download(new salesByServiceExcelReport($data, $total_items_sold, $total_gross_sales, $total_discounts, $total_tax, $total_refunds, $total_net_sales, $total_total_sales), 'sales_by_service.xlsx');
	}
	
	public function salesByProduct(){
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
		
		return view('analytics.sales_by_product',compact('Locations','Staff'));
	}

	public function getSalesByProduct(Request $request){
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

		$start_date = (!empty($request->start_date)) ? date('Y-m-d', strtotime($request->start_date)) : NULL;
		$end_date = (!empty($request->end_date)) ? date('Y-m-d', strtotime($request->end_date)) : NULL;
		$location_id = (!empty($request->location_id)) ? $request->location_id : NULL;
		$staff_id = (!empty($request->staff_id)) ? $request->staff_id : NULL;

		$whereArray = [];

		if(!empty($start_date)){
			$whereArray[] = ['invoice.payment_date', '>=', $start_date];
		}
		if(!empty($end_date)){
			$whereArray[] = ['invoice.payment_date', '<=', $end_date];
		}
		if(!empty($location_id)){
			$whereArray[] = ['invoice.location_id', '=', $location_id];
		}
		if(!empty($staff_id)){
			$whereArray[] = ['users.id', '=', $staff_id];
		}

		$data = InvoiceItems::select(DB::raw("COUNT(invoice_items.id) as items_sold"), DB::raw("SUM(invoice_items.item_og_price) as gross_sales"), DB::raw("SUM(invoice_items.item_og_price - invoice_items.item_price) as discounts"), DB::raw("SUM(invoice_items.item_tax_amount) as tax"), 'invoice_items.item_id', 'invoice_items.item_type')
		// ->leftJoin('inventory_products','inventory_products.id','invoice_items.item_id')
		->leftJoin('invoice','invoice.id','invoice_items.invoice_id')
		->leftJoin('staff','staff.id','invoice_items.staff_id')
		->leftJoin('users','users.id','staff.staff_user_id')
		->where($whereArray)
		->where('invoice.user_id',$AdminId)
		->whereIn('invoice.invoice_status',['0','1','2'])
		->where('invoice_items.item_type','product')
		->groupBy('invoice_items.item_id')
		->get()->toArray();

		// $dataTotal = InvoiceItems::select(DB::raw("COUNT(invoice_items.id) as items_sold"), DB::raw("SUM(invoice_items.item_og_price) as gross_sales"), DB::raw("SUM(invoice_items.item_og_price - invoice_items.item_price) as discounts"), DB::raw("SUM(invoice_items.item_tax_amount) as tax"))
		// ->leftJoin('invoice','invoice.id','invoice_items.invoice_id')
		// ->leftJoin('staff','staff.id','invoice_items.staff_id')
		// ->leftJoin('users','users.id','staff.staff_user_id')
		// ->where($whereArray)
		// ->where('invoice.user_id',$AdminId)
		// ->whereIn('invoice.invoice_status',['0','1','2'])
		// ->where('invoice_items.item_type','product')
		// ->first();

		// echo "<pre>"; echo $refund;die;

		$refundData = InvoiceItems::select(DB::raw("SUM(invoice_items.item_price) as gross_sales"), 'invoice_items.item_id', DB::raw("SUM(invoice_items.item_tax_amount) as tax"))
		->leftJoin('invoice','invoice.id','invoice_items.invoice_id')
		->leftJoin('staff','staff.id','invoice_items.staff_id')
		->leftJoin('users','users.id','staff.staff_user_id')
		->where($whereArray)
		->where('invoice.user_id',$AdminId)
		->where('invoice.invoice_status',2)
		->where('invoice_items.item_type','product')
		->groupBy('invoice_items.item_id')
		->get()
		->toArray();

		$refundDataSum = 0;

		if(!empty($refundData)){
			// echo "<script>alert('Entered into');</script>";
			foreach($refundData as $refundDataKey => $refundDataValue){
				$refundDataSum += $refundDataValue['gross_sales'];
				$flag = [];
				foreach($data as $dataKey => $dataValue){
					if($refundDataValue['item_id'] == $dataValue['item_id']){
						// $flag = 'flag';
						$item = InventoryProducts::select('product_name as item')->where('id',$dataValue['item_id'])->first();
						$data[$dataKey]['refunds'] = number_format($refundDataValue['gross_sales'],2,'.','');
						$data[$dataKey]['tax'] -= number_format($refundDataValue['tax'],2,'.','');
						$data[$dataKey]['item'] = $item['item'];
						$data[$dataKey]['net_sales'] = ($dataValue['gross_sales'] - $dataValue['discounts'] - $refundDataValue['gross_sales']);
					}else{
						$flag[] = $dataKey;
					}
				}
				foreach($flag as $flagKey){
					if(isset($data[$flagKey]['refunds'])){
	
					}else{
						$item = InventoryProducts::select('product_name as item')->where('id',$data[$flagKey]['item_id'])->first();
						$data[$flagKey]['refunds'] = '0.00';
						$data[$flagKey]['item'] = $item['item'];
						$data[$flagKey]['net_sales'] = ($data[$flagKey]['gross_sales'] - $data[$flagKey]['discounts']);
					}
				}
			}
		}else{
			foreach($data as $dataK => $dataV){
				$item2 = InventoryProducts::select('product_name as item')->where('id',$dataV['item_id'])->first();
				$data[$dataK]['refunds'] = '0.00';
				$data[$dataK]['item'] = $item2['item'];
				$data[$dataK]['net_sales'] = ($dataV['gross_sales'] - $dataV['discounts']);
			}
		}
		$dataTotal = [];
		$dataTotal['items_sold'] = 0;
		$dataTotal['gross_sales'] = 0;
		$dataTotal['discounts'] = 0;
		$dataTotal['tax'] = 0;

		foreach($data as $data_Key => $data_Value){
			$dataTotal['items_sold'] += !empty($data_Value['items_sold']) ? $data_Value['items_sold'] : 0;
			$dataTotal['gross_sales'] += !empty($data_Value['gross_sales']) ? $data_Value['gross_sales'] : 0;
			$dataTotal['discounts'] += !empty($data_Value['discounts']) ? $data_Value['discounts'] : 0;
			$dataTotal['tax'] += !empty($data_Value['tax']) ? $data_Value['tax'] : 0;
		}


		// echo "<pre>";print_r($data);die;

		$response = DataTables::of($data)
		->addColumn('total_sales', function($raw){
			$total_sales = ($raw['net_sales'] + $raw['tax']);
			return "CA $".number_format($total_sales,2,'.','');
		})
		->editColumn('tax', function($raw){
			if(empty($raw['tax'])){
				return 'CA $0.00';
			}else{
				return "CA $".number_format($raw['tax'],2,".","");
			}
		})
		->editColumn('net_sales', function($raw){
			return "CA $".number_format($raw['net_sales'],2,".","");
		})
		->editColumn('discounts', function($raw){
			return "CA $".number_format($raw['discounts'],2,".","");
		})
		->editColumn('gross_sales', function($raw){
			return "CA $".number_format($raw['gross_sales'],2,".","");
		})
		->editColumn('refunds', function($raw){
			return "CA $".number_format($raw['refunds'],2,".","");
		})
		->rawColumns(['net_sales','total_sales','gross_sales','discounts','tax'])
		->make(true);

		$total_total_sales = ($dataTotal['gross_sales'] - $dataTotal['discounts'] - $refundDataSum) + $dataTotal['tax'];

		$data = $response->getData(true);
		$data['total_items_sold'] = $dataTotal['items_sold'];
		$data['total_gross_sales'] = "CA $".number_format($dataTotal['gross_sales'], 2, '.', '');
		$data['total_discounts'] = "CA $".number_format($dataTotal['discounts'], 2, '.', '');
		$data['total_tax'] = "CA $".number_format($dataTotal['tax'], 2, '.', '');
		$data['total_refunds'] = "CA $".number_format($refundDataSum, 2, '.', '');
		$data['total_net_sales'] = "CA $".number_format(($dataTotal['gross_sales'] - $dataTotal['discounts'] - $refundDataSum), 2, '.', '');
		$data['total_total_sales'] = "CA $".number_format($total_total_sales, 2, '.', '');

		return $data;
		
	}

	public function getSalesByProductPDF(Request $request){
		$getSalesByItem = $this->getSalesByProduct($request);

		$data = $getSalesByItem['data'];
		$total_items_sold = $getSalesByItem['total_items_sold'];
		$total_gross_sales = $getSalesByItem['total_gross_sales'];
		$total_discounts = $getSalesByItem['total_discounts'];
		$total_tax = $getSalesByItem['total_tax'];
		$total_refunds = $getSalesByItem['total_refunds'];
		$total_net_sales = $getSalesByItem['total_net_sales'];
		$total_total_sales = $getSalesByItem['total_total_sales'];

		$location_id = (!empty($request->location_id)) ? $request->location_id : null;
		$staff_id = (!empty($request->staff_id)) ? $request->staff_id : null;

		if(!empty($location_id)){
			$temp = Location::select("location_name as location")->where('id',$location_id)->first();

			$location_name = $temp->location;
		}else{
			$location_name = "All Locations";
		}
		if(!empty($staff_id)){
			$temp = User::select(DB::raw("CONCAT(first_name,' ',last_name) as staff"))->where('id',$staff_id)->first();

			$staff_name = $temp->staff;
		}else{
			$staff_name = "All Staff";
		}

		// dd($getSalesByItem);
		return PDF::loadView('pdfTemplates.salesByProductPDFReport', compact('data','total_items_sold','total_gross_sales','total_discounts','total_refunds','total_net_sales','total_tax','total_total_sales','location_name','staff_name'))->setPaper('a4')->download('sales_by_product.pdf');
	}

	public function getSalesByProductCSV(Request $request){
		$getSalesByItem = $this->getSalesByProduct($request);

		$data = $getSalesByItem['data'];
		$total_items_sold = $getSalesByItem['total_items_sold'];
		$total_gross_sales = $getSalesByItem['total_gross_sales'];
		$total_discounts = $getSalesByItem['total_discounts'];
		$total_tax = $getSalesByItem['total_tax'];
		$total_refunds = $getSalesByItem['total_refunds'];
		$total_net_sales = $getSalesByItem['total_net_sales'];
		$total_total_sales = $getSalesByItem['total_total_sales'];

		return Excel::download(new salesByProductCSVReport($data, $total_items_sold, $total_gross_sales, $total_discounts, $total_tax, $total_refunds, $total_net_sales, $total_total_sales), 'sales_by_product.csv');
	}

	public function getSalesByProductExcel(Request $request){
		$getSalesByItem = $this->getSalesByProduct($request);

		$data = $getSalesByItem['data'];
		$total_items_sold = $getSalesByItem['total_items_sold'];
		$total_gross_sales = $getSalesByItem['total_gross_sales'];
		$total_discounts = $getSalesByItem['total_discounts'];
		$total_tax = $getSalesByItem['total_tax'];
		$total_refunds = $getSalesByItem['total_refunds'];
		$total_net_sales = $getSalesByItem['total_net_sales'];
		$total_total_sales = $getSalesByItem['total_total_sales'];

		return Excel::download(new salesByProductExcelReport($data, $total_items_sold, $total_gross_sales, $total_discounts, $total_tax, $total_refunds, $total_net_sales, $total_total_sales), 'sales_by_product.xlsx');
	}
	
	public function salesByLocation(){
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
		
		return view('analytics.sales_by_location',compact('Locations','Staff'));
	}

	public function getSalesByLocation(Request $request){
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

		$start_date = (!empty($request->start_date)) ? date('Y-m-d', strtotime($request->start_date)) : NULL;
		$end_date = (!empty($request->end_date)) ? date('Y-m-d', strtotime($request->end_date)) : NULL;
		$location_id = (!empty($request->location_id)) ? $request->location_id : NULL;
		$staff_id = (!empty($request->staff_id)) ? $request->staff_id : NULL;

		$whereArray = [];

		if(!empty($start_date)){
			$whereArray[] = ['invoice.payment_date', '>=', $start_date];
		}
		if(!empty($end_date)){
			$whereArray[] = ['invoice.payment_date', '<=', $end_date];
		}
		if(!empty($location_id)){
			$whereArray[] = ['invoice.location_id', '=', $location_id];
		}
		if(!empty($staff_id)){
			$whereArray[] = ['users.id', '=', $staff_id];
		}

		$data = InvoiceItems::select(DB::raw("COUNT(invoice_items.id) as items_sold"), DB::raw("SUM(invoice_items.item_og_price) as gross_sales"), DB::raw("SUM(invoice_items.item_og_price - invoice_items.item_price) as discounts"), DB::raw("SUM(invoice_items.item_tax_amount) as tax"), 'invoice.location_id', 'invoice_items.item_type','locations.location_name as item')
		// ->leftJoin('inventory_products','inventory_products.id','invoice_items.item_id')
		->leftJoin('invoice','invoice.id','invoice_items.invoice_id')
		->leftJoin('locations','locations.id','invoice.location_id')
		->leftJoin('staff','staff.id','invoice_items.staff_id')
		->leftJoin('users','users.id','staff.staff_user_id')
		->where($whereArray)
		->where('invoice.user_id',$AdminId)
		->whereIn('invoice.invoice_status',['0','1','2'])
		->whereIn('invoice_items.item_type',['product','services'])
		->groupBy('invoice.location_id')
		->get()->toArray();

		// $dataTotal = InvoiceItems::select(DB::raw("COUNT(invoice_items.id) as items_sold"), DB::raw("SUM(invoice_items.item_og_price) as gross_sales"), DB::raw("SUM(invoice_items.item_og_price - invoice_items.item_price) as discounts"), DB::raw("SUM(invoice_items.item_tax_amount) as tax"))
		// ->leftJoin('invoice','invoice.id','invoice_items.invoice_id')
		// ->leftJoin('staff','staff.id','invoice_items.staff_id')
		// ->leftJoin('users','users.id','staff.staff_user_id')
		// ->where($whereArray)
		// ->where('invoice.user_id',$AdminId)
		// ->whereIn('invoice.invoice_status',['0','1','2'])
		// ->whereIn('invoice_items.item_type',['product','services'])
		// ->first();

		// echo "<pre>"; echo $refund;die;

		$refundData = InvoiceItems::select(DB::raw("SUM(invoice_items.item_price) as gross_sales"), 'invoice.location_id', DB::raw("SUM(invoice_items.item_tax_amount) as tax"))
		->leftJoin('invoice','invoice.id','invoice_items.invoice_id')
		->leftJoin('staff','staff.id','invoice_items.staff_id')
		->leftJoin('users','users.id','staff.staff_user_id')
		->where($whereArray)
		->where('invoice.user_id',$AdminId)
		->where('invoice.invoice_status',2)
		->whereIn('invoice_items.item_type',['product','services'])
		->groupBy('invoice.location_id')
		->get()
		->toArray();

		$refundDataSum = 0;

		if(!empty($refundData)){
			// echo "<script>alert('Entered into');</script>";
			foreach($refundData as $refundDataKey => $refundDataValue){
				$refundDataSum += $refundDataValue['gross_sales'];
				$flag = [];
				foreach($data as $dataKey => $dataValue){
					if($refundDataValue['location_id'] == $dataValue['location_id']){

						$data[$dataKey]['refunds'] = number_format($refundDataValue['gross_sales'],2,'.','');
						$data[$dataKey]['tax'] -= number_format($refundDataValue['tax'],2,'.','');
						$data[$dataKey]['net_sales'] = ($dataValue['gross_sales'] - $dataValue['discounts'] - $refundDataValue['gross_sales']);
					}else{
						$flag[] = $dataKey;
					}
				}
				foreach($flag as $flagKey){
					if(isset($data[$flagKey]['refunds'])){
	
					}else{
						$data[$flagKey]['refunds'] = '0.00';
						$data[$flagKey]['net_sales'] = ($data[$flagKey]['gross_sales'] - $data[$flagKey]['discounts']);
					}
				}
			}
		}else{
			foreach($data as $dataK => $dataV){
				$data[$dataK]['refunds'] = '0.00';
				$data[$dataK]['net_sales'] = ($dataV['gross_sales'] - $dataV['discounts']);
			}
		}

		$dataTotal = [];
		$dataTotal['items_sold'] = 0;
		$dataTotal['gross_sales'] = 0;
		$dataTotal['discounts'] = 0;
		$dataTotal['tax'] = 0;

		foreach($data as $data_Key => $data_Value){
			$dataTotal['items_sold'] += !empty($data_Value['items_sold']) ? $data_Value['items_sold'] : 0;
			$dataTotal['gross_sales'] += !empty($data_Value['gross_sales']) ? $data_Value['gross_sales'] : 0;
			$dataTotal['discounts'] += !empty($data_Value['discounts']) ? $data_Value['discounts'] : 0;
			$dataTotal['tax'] += !empty($data_Value['tax']) ? $data_Value['tax'] : 0;
		}


		// echo "<pre>";print_r($data);die;

		$response = DataTables::of($data)
		->addColumn('total_sales', function($raw){
			$total_sales = ($raw['net_sales'] + $raw['tax']);
			return "CA $".number_format($total_sales,2,'.','');
		})
		->editColumn('tax', function($raw){
			if(empty($raw['tax'])){
				return 'CA $0.00';
			}else{
				return "CA $".number_format($raw['tax'],2,".","");
			}
		})
		->editColumn('net_sales', function($raw){
			return "CA $".number_format($raw['net_sales'],2,".","");
		})
		->editColumn('discounts', function($raw){
			return "CA $".number_format($raw['discounts'],2,".","");
		})
		->editColumn('gross_sales', function($raw){
			return "CA $".number_format($raw['gross_sales'],2,".","");
		})
		->editColumn('refunds', function($raw){
			return "CA $".number_format($raw['refunds'],2,".","");
		})
		->rawColumns(['net_sales','total_sales','gross_sales','discounts','tax'])
		->make(true);

		$total_total_sales = ($dataTotal['gross_sales'] - $dataTotal['discounts'] - $refundDataSum) + $dataTotal['tax'];

		$data = $response->getData(true);
		$data['total_items_sold'] = $dataTotal['items_sold'];
		$data['total_gross_sales'] = "CA $".number_format($dataTotal['gross_sales'], 2, '.', '');
		$data['total_discounts'] = "CA $".number_format($dataTotal['discounts'], 2, '.', '');
		$data['total_tax'] = "CA $".number_format($dataTotal['tax'], 2, '.', '');
		$data['total_refunds'] = "CA $".number_format($refundDataSum, 2, '.', '');
		$data['total_net_sales'] = "CA $".number_format(($dataTotal['gross_sales'] - $dataTotal['discounts'] - $refundDataSum), 2, '.', '');
		$data['total_total_sales'] = "CA $".number_format($total_total_sales, 2, '.', '');

		return $data;
		
	}

	public function getSalesByLocationPDF(Request $request){
		$getSalesByItem = $this->getSalesByLocation($request);

		$data = $getSalesByItem['data'];
		$total_items_sold = $getSalesByItem['total_items_sold'];
		$total_gross_sales = $getSalesByItem['total_gross_sales'];
		$total_discounts = $getSalesByItem['total_discounts'];
		$total_tax = $getSalesByItem['total_tax'];
		$total_refunds = $getSalesByItem['total_refunds'];
		$total_net_sales = $getSalesByItem['total_net_sales'];
		$total_total_sales = $getSalesByItem['total_total_sales'];

		$location_id = (!empty($request->location_id)) ? $request->location_id : null;
		$staff_id = (!empty($request->staff_id)) ? $request->staff_id : null;

		if(!empty($location_id)){
			$temp = Location::select("location_name as location")->where('id',$location_id)->first();

			$location_name = $temp->location;
		}else{
			$location_name = "All Locations";
		}
		if(!empty($staff_id)){
			$temp = User::select(DB::raw("CONCAT(first_name,' ',last_name) as staff"))->where('id',$staff_id)->first();

			$staff_name = $temp->staff;
		}else{
			$staff_name = "All Staff";
		}

		return PDF::loadView('pdfTemplates.salesByLocationPDFReport', compact('data','total_items_sold','total_gross_sales','total_discounts','total_refunds','total_net_sales','total_tax','total_total_sales','location_name','staff_name'))->setPaper('a4')->download('sales_by_location.pdf');
	}

	public function getSalesByLocationCSV(Request $request){
		$getSalesByItem = $this->getSalesByLocation($request);

		$data = $getSalesByItem['data'];
		$total_items_sold = $getSalesByItem['total_items_sold'];
		$total_gross_sales = $getSalesByItem['total_gross_sales'];
		$total_discounts = $getSalesByItem['total_discounts'];
		$total_tax = $getSalesByItem['total_tax'];
		$total_refunds = $getSalesByItem['total_refunds'];
		$total_net_sales = $getSalesByItem['total_net_sales'];
		$total_total_sales = $getSalesByItem['total_total_sales'];

		return Excel::download(new salesByLocationCSVReport($data, $total_items_sold, $total_gross_sales, $total_discounts, $total_tax, $total_refunds, $total_net_sales, $total_total_sales), 'sales_by_location.csv');
	}

	public function getSalesByLocationExcel(Request $request){
		$getSalesByItem = $this->getSalesByLocation($request);

		$data = $getSalesByItem['data'];
		$total_items_sold = $getSalesByItem['total_items_sold'];
		$total_gross_sales = $getSalesByItem['total_gross_sales'];
		$total_discounts = $getSalesByItem['total_discounts'];
		$total_tax = $getSalesByItem['total_tax'];
		$total_refunds = $getSalesByItem['total_refunds'];
		$total_net_sales = $getSalesByItem['total_net_sales'];
		$total_total_sales = $getSalesByItem['total_total_sales'];

		return Excel::download(new salesByLocationExcelReport($data, $total_items_sold, $total_gross_sales, $total_discounts, $total_tax, $total_refunds, $total_net_sales, $total_total_sales), 'sales_by_location.xlsx');
	}
	
	public function salesByChannel(){
	}
	
	public function salesByClient(){
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
		
		return view('analytics.sales_by_client',compact('Locations','Staff'));
	}

	public function getSalesByClient(Request $request){
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

		$start_date = (!empty($request->start_date)) ? date('Y-m-d', strtotime($request->start_date)) : NULL;
		$end_date = (!empty($request->end_date)) ? date('Y-m-d', strtotime($request->end_date)) : NULL;
		$location_id = (!empty($request->location_id)) ? $request->location_id : NULL;
		$staff_id = (!empty($request->staff_id)) ? $request->staff_id : NULL;

		$whereArray = [];

		if(!empty($start_date)){
			$whereArray[] = ['invoice.payment_date', '>=', $start_date];
		}
		if(!empty($end_date)){
			$whereArray[] = ['invoice.payment_date', '<=', $end_date];
		}
		if(!empty($location_id)){
			$whereArray[] = ['invoice.location_id', '=', $location_id];
		}
		if(!empty($staff_id)){
			$whereArray[] = ['users.id', '=', $staff_id];
		}

		$data = InvoiceItems::select(DB::raw("COUNT(invoice_items.id) as items_sold"), DB::raw("SUM(invoice_items.item_og_price) as gross_sales"), DB::raw("SUM(invoice_items.item_og_price - invoice_items.item_price) as discounts"), DB::raw("SUM(invoice_items.item_tax_amount) as tax"), 'invoice.client_id', 'invoice_items.item_type',DB::raw('CONCAT(clients.firstname," ",clients.lastname) as item'))
		// ->leftJoin('inventory_products','inventory_products.id','invoice_items.item_id')
		->leftJoin('invoice','invoice.id','invoice_items.invoice_id')
		->leftJoin('locations','locations.id','invoice.location_id')
		->leftJoin('clients','clients.id','invoice.client_id')
		->leftJoin('staff','staff.id','invoice_items.staff_id')
		->leftJoin('users','users.id','staff.staff_user_id')
		->where($whereArray)
		->where('invoice.user_id',$AdminId)
		->whereIn('invoice.invoice_status',['0','1','2'])
		->whereIn('invoice_items.item_type',['product','services'])
		->groupBy('invoice.client_id')
		->get()->toArray();

		// $dataTotal = InvoiceItems::select(DB::raw("COUNT(invoice_items.id) as items_sold"), DB::raw("SUM(invoice_items.item_og_price) as gross_sales"), DB::raw("SUM(invoice_items.item_og_price - invoice_items.item_price) as discounts"), DB::raw("SUM(invoice_items.item_tax_amount) as tax"))
		// ->leftJoin('invoice','invoice.id','invoice_items.invoice_id')
		// ->leftJoin('staff','staff.id','invoice_items.staff_id')
		// ->leftJoin('users','users.id','staff.staff_user_id')
		// ->where($whereArray)
		// ->where('invoice.user_id',$AdminId)
		// ->whereIn('invoice.invoice_status',['0','1','2'])
		// ->whereIn('invoice_items.item_type',['product','services'])
		// ->first();

		// echo "<pre>"; echo $refund;die;

		$refundData = InvoiceItems::select(DB::raw("SUM(invoice_items.item_price) as gross_sales"), 'invoice.client_id', DB::raw("SUM(invoice_items.item_tax_amount) as tax"))
		->leftJoin('invoice','invoice.id','invoice_items.invoice_id')
		->leftJoin('staff','staff.id','invoice_items.staff_id')
		->leftJoin('users','users.id','staff.staff_user_id')
		->where($whereArray)
		->where('invoice.user_id',$AdminId)
		->where('invoice.invoice_status',2)
		->whereIn('invoice_items.item_type',['product','services'])
		->groupBy('invoice.client_id')
		->get()
		->toArray();

		$refundDataSum = 0;

		if(!empty($refundData)){
			// echo "<script>alert('Entered into');</script>";
			foreach($refundData as $refundDataKey => $refundDataValue){
				$refundDataSum += $refundDataValue['gross_sales'];
				$flag = [];
				foreach($data as $dataKey => $dataValue){
					if($refundDataValue['client_id'] == $dataValue['client_id']){

						$data[$dataKey]['refunds'] = number_format($refundDataValue['gross_sales'],2,'.','');
						$data[$dataKey]['tax'] -= number_format($refundDataValue['tax'],2,'.','');
						$data[$dataKey]['net_sales'] = ($dataValue['gross_sales'] - $dataValue['discounts'] - $refundDataValue['gross_sales']);
					}else{
						$flag[] = $dataKey;
					}
				}
				foreach($flag as $flagKey){
					if(isset($data[$flagKey]['refunds'])){
	
					}else{
						$data[$flagKey]['refunds'] = '0.00';
						$data[$flagKey]['net_sales'] = ($data[$flagKey]['gross_sales'] - $data[$flagKey]['discounts']);
					}
				}
			}
		}else{
			foreach($data as $dataK => $dataV){
				$data[$dataK]['refunds'] = '0.00';
				$data[$dataK]['net_sales'] = ($dataV['gross_sales'] - $dataV['discounts']);
			}
		}

		$dataTotal = [];
		$dataTotal['items_sold'] = 0;
		$dataTotal['gross_sales'] = 0;
		$dataTotal['discounts'] = 0;
		$dataTotal['tax'] = 0;

		foreach($data as $data_Key => $data_Value){
			$dataTotal['items_sold'] += !empty($data_Value['items_sold']) ? $data_Value['items_sold'] : 0;
			$dataTotal['gross_sales'] += !empty($data_Value['gross_sales']) ? $data_Value['gross_sales'] : 0;
			$dataTotal['discounts'] += !empty($data_Value['discounts']) ? $data_Value['discounts'] : 0;
			$dataTotal['tax'] += !empty($data_Value['tax']) ? $data_Value['tax'] : 0;
		}


		// echo "<pre>";print_r($data);die;

		$response = DataTables::of($data)
		->addColumn('total_sales', function($raw){
			$total_sales = ($raw['net_sales'] + $raw['tax']);
			return "CA $".number_format($total_sales,2,'.','');
		})
		->editColumn('tax', function($raw){
			if(empty($raw['tax'])){
				return 'CA $0.00';
			}else{
				return "CA $".number_format($raw['tax'],2,".","");
			}
		})
		->editColumn('net_sales', function($raw){
			return "CA $".number_format($raw['net_sales'],2,".","");
		})
		->editColumn('discounts', function($raw){
			return "CA $".number_format($raw['discounts'],2,".","");
		})
		->editColumn('gross_sales', function($raw){
			return "CA $".number_format($raw['gross_sales'],2,".","");
		})
		->editColumn('refunds', function($raw){
			return "CA $".number_format($raw['refunds'],2,".","");
		})
		->editColumn('item', function($raw){
			if(empty($raw['item'])){
				return "Walk-In";
			}else{
				return $raw['item'];
			}
		})
		->rawColumns(['net_sales','total_sales','gross_sales','discounts','tax'])
		->make(true);

		$total_total_sales = ($dataTotal['gross_sales'] - $dataTotal['discounts'] - $refundDataSum) + $dataTotal['tax'];

		$data = $response->getData(true);
		$data['total_items_sold'] = $dataTotal['items_sold'];
		$data['total_gross_sales'] = "CA $".number_format($dataTotal['gross_sales'], 2, '.', '');
		$data['total_discounts'] = "CA $".number_format($dataTotal['discounts'], 2, '.', '');
		$data['total_tax'] = "CA $".number_format($dataTotal['tax'], 2, '.', '');
		$data['total_refunds'] = "CA $".number_format($refundDataSum, 2, '.', '');
		$data['total_net_sales'] = "CA $".number_format(($dataTotal['gross_sales'] - $dataTotal['discounts'] - $refundDataSum), 2, '.', '');
		$data['total_total_sales'] = "CA $".number_format($total_total_sales, 2, '.', '');

		return $data;
		
	}

	public function getSalesByClientPDF(Request $request){
		$getSalesByItem = $this->getSalesByClient($request);

		$data = $getSalesByItem['data'];
		$total_items_sold = $getSalesByItem['total_items_sold'];
		$total_gross_sales = $getSalesByItem['total_gross_sales'];
		$total_discounts = $getSalesByItem['total_discounts'];
		$total_tax = $getSalesByItem['total_tax'];
		$total_refunds = $getSalesByItem['total_refunds'];
		$total_net_sales = $getSalesByItem['total_net_sales'];
		$total_total_sales = $getSalesByItem['total_total_sales'];

		$location_id = (!empty($request->location_id)) ? $request->location_id : null;
		$staff_id = (!empty($request->staff_id)) ? $request->staff_id : null;

		if(!empty($location_id)){
			$temp = Location::select("location_name as location")->where('id',$location_id)->first();

			$location_name = $temp->location;
		}else{
			$location_name = "All Locations";
		}
		if(!empty($staff_id)){
			$temp = User::select(DB::raw("CONCAT(first_name,' ',last_name) as staff"))->where('id',$staff_id)->first();

			$staff_name = $temp->staff;
		}else{
			$staff_name = "All Staff";
		}

		return PDF::loadView('pdfTemplates.salesByClientPDFReport', compact('data','total_items_sold','total_gross_sales','total_discounts','total_refunds','total_net_sales','total_tax','total_total_sales','location_name','staff_name'))->setPaper('a4')->download('sales_by_client.pdf');
	}

	public function getSalesByClientCSV(Request $request){
		$getSalesByItem = $this->getSalesByClient($request);

		$data = $getSalesByItem['data'];
		$total_items_sold = $getSalesByItem['total_items_sold'];
		$total_gross_sales = $getSalesByItem['total_gross_sales'];
		$total_discounts = $getSalesByItem['total_discounts'];
		$total_tax = $getSalesByItem['total_tax'];
		$total_refunds = $getSalesByItem['total_refunds'];
		$total_net_sales = $getSalesByItem['total_net_sales'];
		$total_total_sales = $getSalesByItem['total_total_sales'];

		return Excel::download(new salesByClientCSVReport($data, $total_items_sold, $total_gross_sales, $total_discounts, $total_tax, $total_refunds, $total_net_sales, $total_total_sales), 'sales_by_client.csv');
	}

	public function getSalesByClientExcel(Request $request){
		$getSalesByItem = $this->getSalesByClient($request);

		$data = $getSalesByItem['data'];
		$total_items_sold = $getSalesByItem['total_items_sold'];
		$total_gross_sales = $getSalesByItem['total_gross_sales'];
		$total_discounts = $getSalesByItem['total_discounts'];
		$total_tax = $getSalesByItem['total_tax'];
		$total_refunds = $getSalesByItem['total_refunds'];
		$total_net_sales = $getSalesByItem['total_net_sales'];
		$total_total_sales = $getSalesByItem['total_total_sales'];

		return Excel::download(new salesByClientExcelReport($data, $total_items_sold, $total_gross_sales, $total_discounts, $total_tax, $total_refunds, $total_net_sales, $total_total_sales), 'sales_by_client.xlsx');
	}
	
	public function salesByStaffBreakdown(){
	}
	
	public function salesByStaff(){
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
		
		return view('analytics.sales_by_staff',compact('Locations','Staff'));
	}
	
	public function getSalesByStaff(Request $request){
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

		$start_date = (!empty($request->start_date)) ? date('Y-m-d', strtotime($request->start_date)) : NULL;
		$end_date = (!empty($request->end_date)) ? date('Y-m-d', strtotime($request->end_date)) : NULL;
		$location_id = (!empty($request->location_id)) ? $request->location_id : NULL;
		$staff_id = (!empty($request->staff_id)) ? $request->staff_id : NULL;

		$whereArray = [];

		if(!empty($start_date)){
			$whereArray[] = ['invoice.created_at', '>=', $start_date];
		}
		if(!empty($end_date)){
			$whereArray[] = ['invoice.created_at', '<=', $end_date];
		}
		if(!empty($location_id)){
			$whereArray[] = ['invoice.location_id', '=', $location_id];
		}
		if(!empty($staff_id)){
			$whereArray[] = ['users.id', '=', $staff_id];
		}

		$invoiceId = Invoice::select('invoice.id')->leftJoin('invoice_items','invoice_items.invoice_id','invoice.id')->leftJoin('staff','staff.id','invoice_items.staff_id')->leftJoin('users','users.id','staff.staff_user_id')->where($whereArray)->whereIn('invoice.invoice_status',['0','1'])->where('invoice.user_id',$AdminId)->get()->toArray();

		// print_r($invoiceId); die;

		$data = InvoiceItems::select(DB::raw("COUNT(invoice_items.id) as items_sold"), DB::raw("SUM(invoice_items.item_og_price) as gross_sales"), DB::raw("SUM(invoice_items.item_og_price - invoice_items.item_price) as discounts"), DB::raw("SUM(invoice_items.item_tax_amount) as tax"), 'invoice_items.staff_id', 'invoice_items.item_type',DB::raw('CONCAT(users.first_name," ",users.last_name) as item'))
		->leftJoin('invoice','invoice.id','invoice_items.invoice_id')
		->leftJoin('locations','locations.id','invoice.location_id')
		->leftJoin('staff','staff.id','invoice_items.staff_id')
		->leftJoin('users','users.id','staff.staff_user_id')
		->where($whereArray)
		->where('invoice.user_id',$AdminId)
		->whereIn('invoice.invoice_status',['0','1','2'])
		->whereIn('invoice_items.item_type',['product','services'])
		->groupBy('invoice_items.staff_id')
		->get()->toArray();

		$refundData = InvoiceItems::select(DB::raw("SUM(invoice_items.item_price) as gross_sales"), DB::raw("SUM(invoice_items.item_tax_amount) as tax"),'invoice_items.staff_id')
		->leftJoin('invoice','invoice.id','invoice_items.invoice_id')
		->leftJoin('staff','staff.id','invoice_items.staff_id')
		->leftJoin('users','users.id','staff.staff_user_id')
		->where($whereArray)
		->where('invoice.user_id',$AdminId)
		->where('invoice.invoice_status',2)
		->whereIn('invoice_items.item_type',['product','services'])
		->groupBy('invoice_items.staff_id')
		->get()
		->toArray();

		$refundDataSum = 0;

		if(!empty($refundData)){
			foreach($refundData as $refundDataKey => $refundDataValue){
				$refundDataSum += $refundDataValue['gross_sales'];
				$flag = [];
				foreach($data as $dataKey => $dataValue){
					if($refundDataValue['staff_id'] == $dataValue['staff_id']){

						$data[$dataKey]['refunds'] = number_format($refundDataValue['gross_sales'],2,'.','');
						$data[$dataKey]['tax'] -= number_format($refundDataValue['tax'],2,'.','');
						$data[$dataKey]['net_sales'] = ($dataValue['gross_sales'] - $dataValue['discounts'] - $refundDataValue['gross_sales']);
					}else{
						$flag[] = $dataKey;
					}
				}
				foreach($flag as $flagKey){
					if(isset($data[$flagKey]['refunds'])){
	
					}else{
						$data[$flagKey]['refunds'] = '0.00';
						$data[$flagKey]['net_sales'] = ($data[$flagKey]['gross_sales'] - $data[$flagKey]['discounts']);
					}
				}
			}
		}else{
			foreach($data as $dataK => $dataV){
				$data[$dataK]['refunds'] = '0.00';
				$data[$dataK]['net_sales'] = ($dataV['gross_sales'] - $dataV['discounts']);
			}
		}
		$dataTotal = [];
		$dataTotal['items_sold'] = 0;
		$dataTotal['gross_sales'] = 0;
		$dataTotal['discounts'] = 0;
		$dataTotal['tax'] = 0;

		foreach($data as $data_Key => $data_Value){
			$dataTotal['items_sold'] += !empty($data_Value['items_sold']) ? $data_Value['items_sold'] : 0;
			$dataTotal['gross_sales'] += !empty($data_Value['gross_sales']) ? $data_Value['gross_sales'] : 0;
			$dataTotal['discounts'] += !empty($data_Value['discounts']) ? $data_Value['discounts'] : 0;
			$dataTotal['tax'] += !empty($data_Value['tax']) ? $data_Value['tax'] : 0;
		}


		// echo "<pre>";print_r($data);die;

		$response = DataTables::of($data)
		->addColumn('total_sales', function($raw){
			$total_sales = ($raw['net_sales'] + $raw['tax']);
			return "CA $".number_format($total_sales,2,'.','');
		})
		->editColumn('tax', function($raw){
			if(empty($raw['tax'])){
				return 'CA $0.00';
			}else{
				return "CA $".number_format($raw['tax'],2,".","");
			}
		})
		->editColumn('net_sales', function($raw){
			return "CA $".number_format($raw['net_sales'],2,".","");
		})
		->editColumn('discounts', function($raw){
			return "CA $".number_format($raw['discounts'],2,".","");
		})
		->editColumn('gross_sales', function($raw){
			return "CA $".number_format($raw['gross_sales'],2,".","");
		})
		->editColumn('refunds', function($raw){
			return "CA $".number_format($raw['refunds'],2,".","");
		})
		->editColumn('item', function($raw){
			if(empty($raw['item'])){
				return "Walk-In";
			}else{
				return $raw['item'];
			}
		})
		->rawColumns(['net_sales','total_sales','gross_sales','discounts','tax'])
		->make(true);

		$total_total_sales = ($dataTotal['gross_sales'] - $dataTotal['discounts'] - $refundDataSum) + $dataTotal['tax'];

		$data = $response->getData(true);
		$data['total_items_sold'] = $dataTotal['items_sold'];
		$data['total_gross_sales'] = "CA $".number_format($dataTotal['gross_sales'], 2, '.', '');
		$data['total_discounts'] = "CA $".number_format($dataTotal['discounts'], 2, '.', '');
		$data['total_tax'] = "CA $".number_format($dataTotal['tax'], 2, '.', '');
		$data['total_refunds'] = "CA $".number_format($refundDataSum, 2, '.', '');
		$data['total_net_sales'] = "CA $".number_format(($dataTotal['gross_sales'] - $dataTotal['discounts'] - $refundDataSum), 2, '.', '');
		$data['total_total_sales'] = "CA $".number_format($total_total_sales, 2, '.', '');

		return $data;
		
	}

	public function getSalesByStaffPDF(Request $request){
		$getSalesByItem = $this->getSalesByStaff($request);

		$data = $getSalesByItem['data'];
		$total_items_sold = $getSalesByItem['total_items_sold'];
		$total_gross_sales = $getSalesByItem['total_gross_sales'];
		$total_discounts = $getSalesByItem['total_discounts'];
		$total_tax = $getSalesByItem['total_tax'];
		$total_refunds = $getSalesByItem['total_refunds'];
		$total_net_sales = $getSalesByItem['total_net_sales'];
		$total_total_sales = $getSalesByItem['total_total_sales'];

		$location_id = (!empty($request->location_id)) ? $request->location_id : null;
		$staff_id = (!empty($request->staff_id)) ? $request->staff_id : null;

		if(!empty($location_id)){
			$temp = Location::select("location_name as location")->where('id',$location_id)->first();

			$location_name = $temp->location;
		}else{
			$location_name = "All Locations";
		}
		if(!empty($staff_id)){
			$temp = User::select(DB::raw("CONCAT(first_name,' ',last_name) as staff"))->where('id',$staff_id)->first();

			$staff_name = $temp->staff;
		}else{
			$staff_name = "All Staff";
		}

		return PDF::loadView('pdfTemplates.salesByStaffPDFReport', compact('data','total_items_sold','total_gross_sales','total_discounts','total_refunds','total_net_sales','total_tax','total_total_sales','location_name','staff_name'))->setPaper('a4')->download('sales_by_staff.pdf');
	}

	public function getSalesByStaffCSV(Request $request){
		$getSalesByItem = $this->getSalesByStaff($request);

		$data = $getSalesByItem['data'];
		$total_items_sold = $getSalesByItem['total_items_sold'];
		$total_gross_sales = $getSalesByItem['total_gross_sales'];
		$total_discounts = $getSalesByItem['total_discounts'];
		$total_tax = $getSalesByItem['total_tax'];
		$total_refunds = $getSalesByItem['total_refunds'];
		$total_net_sales = $getSalesByItem['total_net_sales'];
		$total_total_sales = $getSalesByItem['total_total_sales'];

		return Excel::download(new salesByStaffCSVReport($data, $total_items_sold, $total_gross_sales, $total_discounts, $total_tax, $total_refunds, $total_net_sales, $total_total_sales), 'sales_by_staff.csv');
	}

	public function getSalesByStaffExcel(Request $request){
		$getSalesByItem = $this->getSalesByStaff($request);

		$data = $getSalesByItem['data'];
		$total_items_sold = $getSalesByItem['total_items_sold'];
		$total_gross_sales = $getSalesByItem['total_gross_sales'];
		$total_discounts = $getSalesByItem['total_discounts'];
		$total_tax = $getSalesByItem['total_tax'];
		$total_refunds = $getSalesByItem['total_refunds'];
		$total_net_sales = $getSalesByItem['total_net_sales'];
		$total_total_sales = $getSalesByItem['total_total_sales'];

		return Excel::download(new salesByStaffExcelReport($data, $total_items_sold, $total_gross_sales, $total_discounts, $total_tax, $total_refunds, $total_net_sales, $total_total_sales), 'sales_by_staff.xlsx');
	}
	
	public function salesByHour(){
	}
	
	public function salesByHourOfDay(){
	}
	
	public function salesByDay(){
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
		
		return view('analytics.sales_by_day',compact('Locations','Staff'));
	}

	public function getSalesByDay(Request $request){
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

		$start_date = (!empty($request->start_date)) ? date('Y-m-d', strtotime($request->start_date)) : NULL;
		$end_date = (!empty($request->end_date)) ? date('Y-m-d', strtotime($request->end_date)) : NULL;
		$location_id = (!empty($request->location_id)) ? $request->location_id : NULL;
		$staff_id = (!empty($request->staff_id)) ? $request->staff_id : NULL;

		$whereArray = [];

		if(!empty($start_date)){
			$whereArray[] = ['invoice.created_at', '>=', $start_date];
		}
		if(!empty($end_date)){
			$whereArray[] = ['invoice.created_at', '<=', $end_date];
		}
		if(!empty($location_id)){
			$whereArray[] = ['invoice.location_id', '=', $location_id];
		}
		if(!empty($staff_id)){
			$whereArray[] = ['users.id', '=', $staff_id];
		}

		$data = InvoiceItems::select(DB::raw("COUNT(invoice_items.id) as items_sold"), DB::raw("SUM(invoice_items.item_og_price) as gross_sales"), DB::raw("SUM(invoice_items.item_og_price - invoice_items.item_price) as discounts"), DB::raw("SUM(invoice_items.item_tax_amount) as tax"), 'invoice_items.staff_id', 'invoice_items.item_type',DB::raw('DATE(invoice_items.created_at) as item'))
		->leftJoin('invoice','invoice.id','invoice_items.invoice_id')
		->leftJoin('locations','locations.id','invoice.location_id')
		->leftJoin('staff','staff.id','invoice_items.staff_id')
		->leftJoin('users','users.id','staff.staff_user_id')
		->where($whereArray)
		->where('invoice.user_id',$AdminId)
		->whereIn('invoice.invoice_status',['0','1','2'])
		->whereIn('invoice_items.item_type',['product','services'])
		->groupBy(DB::raw('DATE(invoice_items.created_at)'))
		->orderBy('item','ASC')
		->get()->keyBy('item')->toArray();

		// echo "";print_r($data); die;

		$refundData = InvoiceItems::select(DB::raw("SUM(invoice_items.item_price) as gross_sales"), DB::raw("SUM(invoice_items.item_tax_amount) as tax"),DB::raw('DATE(invoice_items.created_at) as item'))
		->leftJoin('invoice','invoice.id','invoice_items.invoice_id')
		->leftJoin('staff','staff.id','invoice_items.staff_id')
		->leftJoin('users','users.id','staff.staff_user_id')
		->where($whereArray)
		->where('invoice.user_id',$AdminId)
		->where('invoice.invoice_status',2)
		->whereIn('invoice_items.item_type',['product','services'])
		->groupBy(DB::raw('DATE(invoice_items.created_at)'))
		->orderBy('item','ASC')
		->get()->keyBy('item')
		->toArray();

		$refundDataSum = 0;

		if(!empty($refundData)){
			foreach($refundData as $refundDataKey => $refundDataValue){
				$refundDataSum += $refundDataValue['gross_sales'];
				$flag = [];
				foreach($data as $dataKey => $dataValue){
					if($refundDataValue['item'] == $dataValue['item']){

						$data[$dataKey]['refunds'] = number_format($refundDataValue['gross_sales'],2,'.','');
						$data[$dataKey]['tax'] -= number_format($refundDataValue['tax'],2,'.','');
						$data[$dataKey]['net_sales'] = ($dataValue['gross_sales'] - $dataValue['discounts'] - $refundDataValue['gross_sales']);
					}else{
						$flag[] = $dataKey;
					}
				}
				foreach($flag as $flagKey){
					if(isset($data[$flagKey]['refunds'])){
	
					}else{
						$data[$flagKey]['refunds'] = '0.00';
						$data[$flagKey]['net_sales'] = ($data[$flagKey]['gross_sales'] - $data[$flagKey]['discounts']);
					}
				}
			}
		}else{
			foreach($data as $dataK => $dataV){
				$data[$dataK]['refunds'] = '0.00';
				$data[$dataK]['net_sales'] = ($dataV['gross_sales'] - $dataV['discounts']);
			}
		}
		$dataTotal = [];
		$dataTotal['items_sold'] = 0;
		$dataTotal['gross_sales'] = 0;
		$dataTotal['discounts'] = 0;
		$dataTotal['tax'] = 0;

		foreach($data as $data_Key => $data_Value){
			$dataTotal['items_sold'] += !empty($data_Value['items_sold']) ? $data_Value['items_sold'] : 0;
			$dataTotal['gross_sales'] += !empty($data_Value['gross_sales']) ? $data_Value['gross_sales'] : 0;
			$dataTotal['discounts'] += !empty($data_Value['discounts']) ? $data_Value['discounts'] : 0;
			$dataTotal['tax'] += !empty($data_Value['tax']) ? $data_Value['tax'] : 0;
		}

		if(empty($start_date) && !empty($data)) {
			$start_date = date('Y-m-d', strtotime(array_key_first($data)));
		}

		if(empty($end_date) && !empty($data)) {
			$end_date = date('Y-m-d', strtotime(array_key_last($data)));
		}

		$current_date = $start_date;

		while(strtotime($current_date) <= strtotime($end_date)) {

			if(!array_key_exists($current_date, $data)) {
				$data[$current_date] = [
					'items_sold' => '0',
					'gross_sales' => '0.00',
					'discounts' => '0.00',
					'tax' => '0.00',
					'staff_id' => '0.00',
					'item_type' => '',
					'item' => $current_date,
					'net_sales' => '0.00',
					'refunds' => '0.00',
					'total_sales' => '0.00',
				];
			}

			$current_date = date('Y-m-d', strtotime($current_date.' +1 day'));
		}
		  
		// Sort the array 
		usort($data, function($element1, $element2) {
			$datetime1 = strtotime($element1['item']);
			$datetime2 = strtotime($element2['item']);
			return $datetime1 - $datetime2;
		});
		// echo "<pre>";print_r($data);die;

		$response = DataTables::of($data)
		->addColumn('total_sales', function($raw){
			$total_sales = ($raw['net_sales'] + $raw['tax']);
			return "CA $".number_format($total_sales,2,'.','');
		})
		->editColumn('tax', function($raw){
			if(empty($raw['tax'])){
				return 'CA $0.00';
			}else{
				return "CA $".number_format($raw['tax'],2,".","");
			}
		})
		->editColumn('net_sales', function($raw){
			return "CA $".number_format($raw['net_sales'],2,".","");
		})
		->editColumn('discounts', function($raw){
			return "CA $".number_format($raw['discounts'],2,".","");
		})
		->editColumn('gross_sales', function($raw){
			return "CA $".number_format($raw['gross_sales'],2,".","");
		})
		->editColumn('refunds', function($raw){
			return "CA $".number_format($raw['refunds'],2,".","");
		})
		->editColumn('item', function($raw){
			if(empty($raw['item'])){
				return "Walk-In";
			}else{
				return $raw['item'];
			}
		})
		->rawColumns(['net_sales','total_sales','gross_sales','discounts','tax'])
		->make(true);

		$total_total_sales = ($dataTotal['gross_sales'] - $dataTotal['discounts'] - $refundDataSum) + $dataTotal['tax'];

		$data = $response->getData(true);
		$data['total_items_sold'] = $dataTotal['items_sold'];
		$data['total_gross_sales'] = "CA $".number_format($dataTotal['gross_sales'], 2, '.', '');
		$data['total_discounts'] = "CA $".number_format($dataTotal['discounts'], 2, '.', '');
		$data['total_tax'] = "CA $".number_format($dataTotal['tax'], 2, '.', '');
		$data['total_refunds'] = "CA $".number_format($refundDataSum, 2, '.', '');
		$data['total_net_sales'] = "CA $".number_format(($dataTotal['gross_sales'] - $dataTotal['discounts'] - $refundDataSum), 2, '.', '');
		$data['total_total_sales'] = "CA $".number_format($total_total_sales, 2, '.', '');

		return $data;
		
	}

	public function getSalesByDayPDF(Request $request){
		$getSalesByItem = $this->getSalesByDay($request);

		$data = $getSalesByItem['data'];
		$total_items_sold = $getSalesByItem['total_items_sold'];
		$total_gross_sales = $getSalesByItem['total_gross_sales'];
		$total_discounts = $getSalesByItem['total_discounts'];
		$total_tax = $getSalesByItem['total_tax'];
		$total_refunds = $getSalesByItem['total_refunds'];
		$total_net_sales = $getSalesByItem['total_net_sales'];
		$total_total_sales = $getSalesByItem['total_total_sales'];

		$location_id = (!empty($request->location_id)) ? $request->location_id : null;
		$staff_id = (!empty($request->staff_id)) ? $request->staff_id : null;

		if(!empty($location_id)){
			$temp = Location::select("location_name as location")->where('id',$location_id)->first();

			$location_name = $temp->location;
		}else{
			$location_name = "All Locations";
		}
		if(!empty($staff_id)){
			$temp = User::select(DB::raw("CONCAT(first_name,' ',last_name) as staff"))->where('id',$staff_id)->first();

			$staff_name = $temp->staff;
		}else{
			$staff_name = "All Staff";
		}

		return PDF::loadView('pdfTemplates.salesByDayPDFReport', compact('data','total_items_sold','total_gross_sales','total_discounts','total_refunds','total_net_sales','total_tax','total_total_sales','location_name','staff_name'))->setPaper('a4')->download('sales_by_day.pdf');
	}

	public function getSalesByDayCSV(Request $request){
		$getSalesByItem = $this->getSalesByDay($request);

		$data = $getSalesByItem['data'];
		$total_items_sold = $getSalesByItem['total_items_sold'];
		$total_gross_sales = $getSalesByItem['total_gross_sales'];
		$total_discounts = $getSalesByItem['total_discounts'];
		$total_tax = $getSalesByItem['total_tax'];
		$total_refunds = $getSalesByItem['total_refunds'];
		$total_net_sales = $getSalesByItem['total_net_sales'];
		$total_total_sales = $getSalesByItem['total_total_sales'];

		return Excel::download(new salesByDayCSVReport($data, $total_items_sold, $total_gross_sales, $total_discounts, $total_tax, $total_refunds, $total_net_sales, $total_total_sales), 'sales_by_client.csv');
	}

	public function getSalesByDayExcel(Request $request){
		$getSalesByItem = $this->getSalesByDay($request);

		$data = $getSalesByItem['data'];
		$total_items_sold = $getSalesByItem['total_items_sold'];
		$total_gross_sales = $getSalesByItem['total_gross_sales'];
		$total_discounts = $getSalesByItem['total_discounts'];
		$total_tax = $getSalesByItem['total_tax'];
		$total_refunds = $getSalesByItem['total_refunds'];
		$total_net_sales = $getSalesByItem['total_net_sales'];
		$total_total_sales = $getSalesByItem['total_total_sales'];

		return Excel::download(new salesByDayExcelReport($data, $total_items_sold, $total_gross_sales, $total_discounts, $total_tax, $total_refunds, $total_net_sales, $total_total_sales), 'sales_by_day.xlsx');
	}
	
	public function salesByMonth(){
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
		
		return view('analytics.sales_by_month',compact('Locations','Staff'));
	}
	
	public function getSalesByMonth(Request $request){
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

		$start_date = (!empty($request->start_date)) ? date('Y-m-d', strtotime($request->start_date)) : NULL;
		$end_date = (!empty($request->end_date)) ? date('Y-m-d', strtotime($request->end_date)) : NULL;
		$location_id = (!empty($request->location_id)) ? $request->location_id : NULL;
		$staff_id = (!empty($request->staff_id)) ? $request->staff_id : NULL;

		$whereArray = [];

		if(!empty($start_date)){
			$whereArray[] = ['invoice.created_at', '>=', $start_date];
		}
		if(!empty($end_date)){
			$whereArray[] = ['invoice.created_at', '<=', $end_date];
		}
		if(!empty($location_id)){
			$whereArray[] = ['invoice.location_id', '=', $location_id];
		}
		if(!empty($staff_id)){
			$whereArray[] = ['users.id', '=', $staff_id];
		}

		$data = InvoiceItems::select(DB::raw("COUNT(invoice_items.id) as items_sold"), DB::raw("SUM(invoice_items.item_og_price) as gross_sales"), DB::raw("SUM(invoice_items.item_og_price - invoice_items.item_price) as discounts"), DB::raw("SUM(invoice_items.item_tax_amount) as tax"), 'invoice_items.staff_id', 'invoice_items.item_type',DB::raw('MONTH(invoice_items.created_at) as item'),DB::raw('YEAR(invoice_items.created_at) as year'))
		->leftJoin('invoice','invoice.id','invoice_items.invoice_id')
		->leftJoin('locations','locations.id','invoice.location_id')
		->leftJoin('staff','staff.id','invoice_items.staff_id')
		->leftJoin('users','users.id','staff.staff_user_id')
		->where($whereArray)
		->where('invoice.user_id',$AdminId)
		->whereIn('invoice.invoice_status',['0','1','2'])
		->whereIn('invoice_items.item_type',['product','services'])
		->groupBy('item')
		->orderBy('item','ASC')
		->get()->keyBy('item')->toArray();

		// echo "";print_r($data); die;

		$refundData = InvoiceItems::select(DB::raw("SUM(invoice_items.item_price) as gross_sales"), DB::raw("SUM(invoice_items.item_tax_amount) as tax"),DB::raw('MONTH(invoice_items.created_at) as item'),DB::raw('YEAR(invoice_items.created_at) as year'))
		->leftJoin('invoice','invoice.id','invoice_items.invoice_id')
		->leftJoin('staff','staff.id','invoice_items.staff_id')
		->leftJoin('users','users.id','staff.staff_user_id')
		->where($whereArray)
		->where('invoice.user_id',$AdminId)
		->where('invoice.invoice_status',2)
		->whereIn('invoice_items.item_type',['product','services'])
		->groupBy('item')
		->orderBy('item','ASC')
		->get()->keyBy('item')
		->toArray();

		$refundDataSum = 0;

		if(!empty($refundData)){
			foreach($refundData as $refundDataKey => $refundDataValue){
				$refundDataSum += $refundDataValue['gross_sales'];
				$flag = [];
				foreach($data as $dataKey => $dataValue){
					if($refundDataValue['item'] == $dataValue['item']){

						$data[$dataKey]['refunds'] = number_format($refundDataValue['gross_sales'],2,'.','');
						$data[$dataKey]['tax'] -= number_format($refundDataValue['tax'],2,'.','');
						$data[$dataKey]['net_sales'] = ($dataValue['gross_sales'] - $dataValue['discounts'] - $refundDataValue['gross_sales']);
					}else{
						$flag[] = $dataKey;
					}
				}
				foreach($flag as $flagKey){
					if(isset($data[$flagKey]['refunds'])){
	
					}else{
						$data[$flagKey]['refunds'] = '0.00';
						$data[$flagKey]['net_sales'] = ($data[$flagKey]['gross_sales'] - $data[$flagKey]['discounts']);
					}
				}
			}
		}else{
			foreach($data as $dataK => $dataV){
				$data[$dataK]['refunds'] = '0.00';
				$data[$dataK]['net_sales'] = ($dataV['gross_sales'] - $dataV['discounts']);
			}
		}
		$dataTotal = [];
		$dataTotal['items_sold'] = 0;
		$dataTotal['gross_sales'] = 0;
		$dataTotal['discounts'] = 0;
		$dataTotal['tax'] = 0;

		foreach($data as $data_Key => $data_Value){
			$dataTotal['items_sold'] += !empty($data_Value['items_sold']) ? $data_Value['items_sold'] : 0;
			$dataTotal['gross_sales'] += !empty($data_Value['gross_sales']) ? $data_Value['gross_sales'] : 0;
			$dataTotal['discounts'] += !empty($data_Value['discounts']) ? $data_Value['discounts'] : 0;
			$dataTotal['tax'] += !empty($data_Value['tax']) ? $data_Value['tax'] : 0;
		}

		if(empty($start_date) && !empty($data)) {
			$start_date = date('Y-m-d', strtotime(array_key_first($data)));
		}

		if(empty($end_date) && !empty($data)) {
			$end_date = date('Y-m-d', strtotime(array_key_last($data)));
		}

		$current_date = $start_date;
		$checkMonth = '';

		while(strtotime($current_date) <= strtotime($end_date)) {
			
			$dateObj   = date('n', strtotime($current_date));
			
			$checkMonth .= $dateObj.', ';
			if(!array_key_exists($dateObj, $data)) {
				$data[$dateObj] = [
					'items_sold' => '0',
					'gross_sales' => '0.00',
					'discounts' => '0.00',
					'tax' => '0.00',
					'staff_id' => '0.00',
					'item_type' => '',
					'item' => (int)$dateObj,
					'net_sales' => '0.00',
					'refunds' => '0.00',
					'total_sales' => '0.00',
					'year' => date('Y', strtotime($current_date))
				];
			}

			$current_date = date('Y-m-d', strtotime($current_date.' +29 days'));
		}
		// Sort the array 
		usort($data, function($element1, $element2) {
			$datetime1 = $element1['item'];
			$datetime2 = $element2['item'];
			return $datetime1 - $datetime2;
		});

		$response = DataTables::of($data)
		->addColumn('total_sales', function($raw){
			$total_sales = ($raw['net_sales'] + $raw['tax']);
			return "CA $".number_format($total_sales,2,'.','');
		})
		->editColumn('tax', function($raw){
			if(empty($raw['tax'])){
				return 'CA $0.00';
			}else{
				return "CA $".number_format($raw['tax'],2,".","");
			}
		})
		->editColumn('net_sales', function($raw){
			return "CA $".number_format($raw['net_sales'],2,".","");
		})
		->editColumn('discounts', function($raw){
			return "CA $".number_format($raw['discounts'],2,".","");
		})
		->editColumn('gross_sales', function($raw){
			return "CA $".number_format($raw['gross_sales'],2,".","");
		})
		->editColumn('refunds', function($raw){
			return "CA $".number_format($raw['refunds'],2,".","");
		})
		->editColumn('item', function($raw){
			if(!empty($raw['year'])){
				$dateObj   = DateTime::createFromFormat('!m', $raw['item']);
				$monthName = $dateObj->format('F');
				return $monthName." ".$raw['year'];
			}
			return '';
		})
		->rawColumns(['net_sales','total_sales','gross_sales','discounts','tax'])
		->make(true);

		$total_total_sales = ($dataTotal['gross_sales'] - $dataTotal['discounts'] - $refundDataSum) + $dataTotal['tax'];

		$data = $response->getData(true);
		$data['total_items_sold'] = $dataTotal['items_sold'];
		$data['total_gross_sales'] = "CA $".number_format($dataTotal['gross_sales'], 2, '.', '');
		$data['total_discounts'] = "CA $".number_format($dataTotal['discounts'], 2, '.', '');
		$data['total_tax'] = "CA $".number_format($dataTotal['tax'], 2, '.', '');
		$data['total_refunds'] = "CA $".number_format($refundDataSum, 2, '.', '');
		$data['total_net_sales'] = "CA $".number_format(($dataTotal['gross_sales'] - $dataTotal['discounts'] - $refundDataSum), 2, '.', '');
		$data['total_total_sales'] = "CA $".number_format($total_total_sales, 2, '.', '');

		return $data;
		
	}

	public function getSalesByMonthPDF(Request $request){
		$getSalesByItem = $this->getSalesByMonth($request);

		$data = $getSalesByItem['data'];
		$total_items_sold = $getSalesByItem['total_items_sold'];
		$total_gross_sales = $getSalesByItem['total_gross_sales'];
		$total_discounts = $getSalesByItem['total_discounts'];
		$total_tax = $getSalesByItem['total_tax'];
		$total_refunds = $getSalesByItem['total_refunds'];
		$total_net_sales = $getSalesByItem['total_net_sales'];
		$total_total_sales = $getSalesByItem['total_total_sales'];

		$location_id = (!empty($request->location_id)) ? $request->location_id : null;
		$staff_id = (!empty($request->staff_id)) ? $request->staff_id : null;

		if(!empty($location_id)){
			$temp = Location::select("location_name as location")->where('id',$location_id)->first();

			$location_name = $temp->location;
		}else{
			$location_name = "All Locations";
		}
		if(!empty($staff_id)){
			$temp = User::select(DB::raw("CONCAT(first_name,' ',last_name) as staff"))->where('id',$staff_id)->first();

			$staff_name = $temp->staff;
		}else{
			$staff_name = "All Staff";
		}

		return PDF::loadView('pdfTemplates.salesByMonthPDFReport', compact('data','total_items_sold','total_gross_sales','total_discounts','total_refunds','total_net_sales','total_tax','total_total_sales','location_name','staff_name'))->setPaper('a4')->download('sales_by_month.pdf');
	}

	public function getSalesByMonthCSV(Request $request){
		$getSalesByItem = $this->getSalesByMonth($request);

		$data = $getSalesByItem['data'];
		$total_items_sold = $getSalesByItem['total_items_sold'];
		$total_gross_sales = $getSalesByItem['total_gross_sales'];
		$total_discounts = $getSalesByItem['total_discounts'];
		$total_tax = $getSalesByItem['total_tax'];
		$total_refunds = $getSalesByItem['total_refunds'];
		$total_net_sales = $getSalesByItem['total_net_sales'];
		$total_total_sales = $getSalesByItem['total_total_sales'];

		return Excel::download(new salesByMonthCSVReport($data, $total_items_sold, $total_gross_sales, $total_discounts, $total_tax, $total_refunds, $total_net_sales, $total_total_sales), 'sales_by_month.csv');
	}

	public function getSalesByMonthExcel(Request $request){
		$getSalesByItem = $this->getSalesByMonth($request);

		$data = $getSalesByItem['data'];
		$total_items_sold = $getSalesByItem['total_items_sold'];
		$total_gross_sales = $getSalesByItem['total_gross_sales'];
		$total_discounts = $getSalesByItem['total_discounts'];
		$total_tax = $getSalesByItem['total_tax'];
		$total_refunds = $getSalesByItem['total_refunds'];
		$total_net_sales = $getSalesByItem['total_net_sales'];
		$total_total_sales = $getSalesByItem['total_total_sales'];

		return Excel::download(new salesByMonthExcelReport($data, $total_items_sold, $total_gross_sales, $total_discounts, $total_tax, $total_refunds, $total_net_sales, $total_total_sales), 'sales_by_month.xlsx');
	}
	
	public function salesByQuarter(){
	}
	
	public function salesByYear(){
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
		
		return view('analytics.sales_by_year',compact('Locations','Staff'));
	}

	public function getSalesByYear(Request $request){
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

		$start_date = (!empty($request->start_date)) ? date('Y-m-d', strtotime($request->start_date)) : NULL;
		$end_date = (!empty($request->end_date)) ? date('Y-m-d', strtotime($request->end_date)) : NULL;
		$location_id = (!empty($request->location_id)) ? $request->location_id : NULL;
		$staff_id = (!empty($request->staff_id)) ? $request->staff_id : NULL;

		$whereArray = [];

		if(!empty($start_date)){
			$whereArray[] = ['invoice.created_at', '>=', $start_date];
		}
		if(!empty($end_date)){
			$whereArray[] = ['invoice.created_at', '<=', $end_date];
		}
		if(!empty($location_id)){
			$whereArray[] = ['invoice.location_id', '=', $location_id];
		}
		if(!empty($staff_id)){
			$whereArray[] = ['users.id', '=', $staff_id];
		}

		$data = InvoiceItems::select(DB::raw("COUNT(invoice_items.id) as items_sold"), DB::raw("SUM(invoice_items.item_og_price) as gross_sales"), DB::raw("SUM(invoice_items.item_og_price - invoice_items.item_price) as discounts"), DB::raw("SUM(invoice_items.item_tax_amount) as tax"), 'invoice_items.staff_id', 'invoice_items.item_type',DB::raw('YEAR(invoice_items.created_at) as item'))
		->leftJoin('invoice','invoice.id','invoice_items.invoice_id')
		->leftJoin('locations','locations.id','invoice.location_id')
		->leftJoin('staff','staff.id','invoice_items.staff_id')
		->leftJoin('users','users.id','staff.staff_user_id')
		->where($whereArray)
		->where('invoice.user_id',$AdminId)
		->whereIn('invoice.invoice_status',['0','1','2'])
		->whereIn('invoice_items.item_type',['product','services'])
		->groupBy('item')
		->orderBy('item','ASC')
		->get()->keyBy('item')->toArray();

		// echo "";print_r($data); die;

		$refundData = InvoiceItems::select(DB::raw("SUM(invoice_items.item_price) as gross_sales"), DB::raw("SUM(invoice_items.item_tax_amount) as tax"),DB::raw('YEAR(invoice_items.created_at) as item'))
		->leftJoin('invoice','invoice.id','invoice_items.invoice_id')
		->leftJoin('staff','staff.id','invoice_items.staff_id')
		->leftJoin('users','users.id','staff.staff_user_id')
		->where($whereArray)
		->where('invoice.user_id',$AdminId)
		->where('invoice.invoice_status',2)
		->whereIn('invoice_items.item_type',['product','services'])
		->groupBy('item')
		->orderBy('item','ASC')
		->get()->keyBy('item')
		->toArray();

		$refundDataSum = 0;

		if(!empty($refundData)){
			foreach($refundData as $refundDataKey => $refundDataValue){
				$refundDataSum += $refundDataValue['gross_sales'];
				$flag = [];
				foreach($data as $dataKey => $dataValue){
					if($refundDataValue['item'] == $dataValue['item']){

						$data[$dataKey]['refunds'] = number_format($refundDataValue['gross_sales'],2,'.','');
						$data[$dataKey]['tax'] -= number_format($refundDataValue['tax'],2,'.','');
						$data[$dataKey]['net_sales'] = ($dataValue['gross_sales'] - $dataValue['discounts'] - $refundDataValue['gross_sales']);
					}else{
						$flag[] = $dataKey;
					}
				}
				foreach($flag as $flagKey){
					if(isset($data[$flagKey]['refunds'])){
	
					}else{
						$data[$flagKey]['refunds'] = '0.00';
						$data[$flagKey]['net_sales'] = ($data[$flagKey]['gross_sales'] - $data[$flagKey]['discounts']);
					}
				}
			}
		}else{
			foreach($data as $dataK => $dataV){
				$data[$dataK]['refunds'] = '0.00';
				$data[$dataK]['net_sales'] = ($dataV['gross_sales'] - $dataV['discounts']);
			}
		}
		$dataTotal = [];
		$dataTotal['items_sold'] = 0;
		$dataTotal['gross_sales'] = 0;
		$dataTotal['discounts'] = 0;
		$dataTotal['tax'] = 0;

		foreach($data as $data_Key => $data_Value){
			$dataTotal['items_sold'] += !empty($data_Value['items_sold']) ? $data_Value['items_sold'] : 0;
			$dataTotal['gross_sales'] += !empty($data_Value['gross_sales']) ? $data_Value['gross_sales'] : 0;
			$dataTotal['discounts'] += !empty($data_Value['discounts']) ? $data_Value['discounts'] : 0;
			$dataTotal['tax'] += !empty($data_Value['tax']) ? $data_Value['tax'] : 0;
		}

		if(empty($start_date) && !empty($data)) {
			$start_date = date('Y-m-d', strtotime(array_key_first($data)));
		}

		if(empty($end_date) && !empty($data)) {
			$end_date = date('Y-m-d', strtotime(array_key_last($data)));
		}

		$current_date = $start_date;
		$checkMonth = '';

		while(strtotime($current_date) <= strtotime($end_date)) {
			
			$dateObj   = date('Y', strtotime($current_date));
			
			$checkMonth .= $dateObj.', ';
			if(!array_key_exists($dateObj, $data)) {
				$data[$dateObj] = [
					'items_sold' => '0',
					'gross_sales' => '0.00',
					'discounts' => '0.00',
					'tax' => '0.00',
					'staff_id' => '0.00',
					'item_type' => '',
					'item' => date('Y', strtotime($current_date)),
					'net_sales' => '0.00',
					'refunds' => '0.00',
					'total_sales' => '0.00'
				];
			}

			$current_date = date('Y-m-d', strtotime($current_date.' +1 year'));
		}
		// Sort the array 
		usort($data, function($element1, $element2) {
			$datetime1 = $element1['item'];
			$datetime2 = $element2['item'];
			return $datetime1 - $datetime2;
		});

		$response = DataTables::of($data)
		->addColumn('total_sales', function($raw){
			$total_sales = ($raw['net_sales'] + $raw['tax']);
			return "CA $".number_format($total_sales,2,'.','');
		})
		->editColumn('tax', function($raw){
			if(empty($raw['tax'])){
				return 'CA $0.00';
			}else{
				return "CA $".number_format($raw['tax'],2,".","");
			}
		})
		->editColumn('net_sales', function($raw){
			return "CA $".number_format($raw['net_sales'],2,".","");
		})
		->editColumn('discounts', function($raw){
			return "CA $".number_format($raw['discounts'],2,".","");
		})
		->editColumn('gross_sales', function($raw){
			return "CA $".number_format($raw['gross_sales'],2,".","");
		})
		->editColumn('refunds', function($raw){
			return "CA $".number_format($raw['refunds'],2,".","");
		})
		->editColumn('item', function($raw){
			if(!empty($raw['item'])){
				return $raw['item'];
			}
			return '';
		})
		->rawColumns(['net_sales','total_sales','gross_sales','discounts','tax'])
		->make(true);

		$total_total_sales = ($dataTotal['gross_sales'] - $dataTotal['discounts'] - $refundDataSum) + $dataTotal['tax'];

		$data = $response->getData(true);
		$data['total_items_sold'] = $dataTotal['items_sold'];
		$data['total_gross_sales'] = "CA $".number_format($dataTotal['gross_sales'], 2, '.', '');
		$data['total_discounts'] = "CA $".number_format($dataTotal['discounts'], 2, '.', '');
		$data['total_tax'] = "CA $".number_format($dataTotal['tax'], 2, '.', '');
		$data['total_refunds'] = "CA $".number_format($refundDataSum, 2, '.', '');
		$data['total_net_sales'] = "CA $".number_format(($dataTotal['gross_sales'] - $dataTotal['discounts'] - $refundDataSum), 2, '.', '');
		$data['total_total_sales'] = "CA $".number_format($total_total_sales, 2, '.', '');

		return $data;
		
	}

	public function getSalesByYearPDF(Request $request){
		$getSalesByItem = $this->getSalesByYear($request);

		$data = $getSalesByItem['data'];
		$total_items_sold = $getSalesByItem['total_items_sold'];
		$total_gross_sales = $getSalesByItem['total_gross_sales'];
		$total_discounts = $getSalesByItem['total_discounts'];
		$total_tax = $getSalesByItem['total_tax'];
		$total_refunds = $getSalesByItem['total_refunds'];
		$total_net_sales = $getSalesByItem['total_net_sales'];
		$total_total_sales = $getSalesByItem['total_total_sales'];

		$location_id = (!empty($request->location_id)) ? $request->location_id : null;
		$staff_id = (!empty($request->staff_id)) ? $request->staff_id : null;

		if(!empty($location_id)){
			$temp = Location::select("location_name as location")->where('id',$location_id)->first();

			$location_name = $temp->location;
		}else{
			$location_name = "All Locations";
		}
		if(!empty($staff_id)){
			$temp = User::select(DB::raw("CONCAT(first_name,' ',last_name) as staff"))->where('id',$staff_id)->first();

			$staff_name = $temp->staff;
		}else{
			$staff_name = "All Staff";
		}

		return PDF::loadView('pdfTemplates.salesByYearPDFReport', compact('data','total_items_sold','total_gross_sales','total_discounts','total_refunds','total_net_sales','total_tax','total_total_sales','location_name','staff_name'))->setPaper('a4')->download('sales_by_year.pdf');
	}

	public function getSalesByYearCSV(Request $request){
		$getSalesByItem = $this->getSalesByYear($request);

		$data = $getSalesByItem['data'];
		$total_items_sold = $getSalesByItem['total_items_sold'];
		$total_gross_sales = $getSalesByItem['total_gross_sales'];
		$total_discounts = $getSalesByItem['total_discounts'];
		$total_tax = $getSalesByItem['total_tax'];
		$total_refunds = $getSalesByItem['total_refunds'];
		$total_net_sales = $getSalesByItem['total_net_sales'];
		$total_total_sales = $getSalesByItem['total_total_sales'];

		return Excel::download(new salesByYearCSVReport($data, $total_items_sold, $total_gross_sales, $total_discounts, $total_tax, $total_refunds, $total_net_sales, $total_total_sales), 'sales_by_year.csv');
	}

	public function getSalesByYearExcel(Request $request){
		$getSalesByItem = $this->getSalesByYear($request);

		$data = $getSalesByItem['data'];
		$total_items_sold = $getSalesByItem['total_items_sold'];
		$total_gross_sales = $getSalesByItem['total_gross_sales'];
		$total_discounts = $getSalesByItem['total_discounts'];
		$total_tax = $getSalesByItem['total_tax'];
		$total_refunds = $getSalesByItem['total_refunds'];
		$total_net_sales = $getSalesByItem['total_net_sales'];
		$total_total_sales = $getSalesByItem['total_total_sales'];

		return Excel::download(new salesByYearExcelReport($data, $total_items_sold, $total_gross_sales, $total_discounts, $total_tax, $total_refunds, $total_net_sales, $total_total_sales), 'sales_by_year.xlsx');
	}
	
	public function salesLog(){
	}
	
	public function vouchersOutstandingBalance(){
	}
	
	public function voucherSales(){
	}
	
	public function voucherRedemptions(){
	}
	
	public function roster(){
	}
	
	// public function tips(){
	// }
	
	public function commissionSummary(){
	}
	
	public function commissionDetailed(){
	}	

	public function formatTime($seconds)
	{
		$hours = floor($seconds / 3600);
		$minutes = floor(($seconds / 60) % 60);
		$seconds = $seconds % 60;

		if($hours > 0 ){
			return "{$hours}h {$minutes}min";
		}else{
			return "{$minutes}min";
		}
	}
}
