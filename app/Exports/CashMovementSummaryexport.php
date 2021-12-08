<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
// use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
// use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

use DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Staff;
use Illuminate\Contracts\View\View;
use App\Models\paymentType;
use App\Models\Invoice;
use App\Models\InvoiceVoucher;
use App\Models\StaffTip;

class CashMovementSummaryexport implements WithHeadings , ShouldAutoSize, FromCollection, WithTitle, WithStyles
{
    private $start_date;
    private $end_date;
    private $location_id;

    public function __construct($start_date, $end_date, $location_id) 
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->location_id = $location_id;
    }

    public function title(): string
    {
        return 'Cash Movement Summary';
    }
    public function collection()
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

        $whereArray = [];
        if($this->start_date != '') {
            $whereArray[] = [
                DB::raw('DATE(invoice.created_at)'),'>=',$this->start_date
            ];
        }
        if($this->end_date != '') {
            $whereArray[] = [
                DB::raw('DATE(invoice.created_at)'),'<=',$this->end_date
            ];
        }
        if($this->location_id != ''){
            $whereArray[] = [
                'invoice.location_id', '=', $this->location_id
            ];
        }
            
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
                $getSoldInvoice = Invoice::select('invoice.*')->where('invoice.user_id',$AdminId)->where('invoice.invoice_status',1)->where('invoice.payment_id',$paymentTypeData['id'])->where($whereArray)->orderBy('invoice.id','desc')->get()->toArray();
                
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
                $getRefundInvoice = Invoice::select('invoice.*')->where('invoice.user_id',$AdminId)->where('invoice.invoice_status',2)->where('invoice.payment_id',$paymentTypeData['id'])->where($whereArray)->orderBy('invoice.id','desc')->get()->toArray();
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

        $sheets = [];
        if(!empty($paymentByTotal)) {
            foreach($paymentByTotal as $paymentByTotalData) {
                $sheets[] = [
                    $paymentByTotalData['payment_method'],
                    'CA $'.number_format($paymentByTotalData['total_collected'],2),
                    'CA $'.number_format($paymentByTotalData['total_refunded'],2)
                ];
            }
        }

        $sheets[] = [
            'Voucher Redemptions',
            'CA $'.number_format($totalVoucherRedemption,2),
            'CA $'.number_format($totalRefundVoucherRedemption,2)
        ];
        $sheets[] = [
            'Payments collected',
            'CA $'.number_format($totalPaymentCollected,2),
            'CA $'.number_format($totalPaymentRefunded,2)
        ];
        $sheets[] = [
            'Of which tips',
            'CA $'.number_format($totalTips,2),
            'CA $'.number_format($totalRefundedTips,2)
        ];

        return collect($sheets);
    }

    public function headings(): array
    {
        return ['Payment type', 'Payments Collected', 'Refunds Paid'];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:D7')->getFont()->setSize(12);
        $sheet->getStyle('A1:D1')->getFont()->setBold(true);
        $sheet->getStyle('A6:D6')->getFont()->setBold(true);
        $sheet->getStyle('A7:D7')->getFont()->setBold(true);
    }
}
