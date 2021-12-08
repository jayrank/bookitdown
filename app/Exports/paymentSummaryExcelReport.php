<?php

namespace App\Exports;

// use App\Models\InvoiceItems;
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
use App\Models\Location;
// use App\Models\InvoiceVoucher;
// use App\Models\StaffTip;

class paymentSummaryExcelReport implements  ShouldAutoSize, FromCollection, WithHeadings, WithStyles
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

        // $start_date = (!empty($request->start_date)) ? date('Y-m-d', strtotime($request->start_date)) : NULL;
        // $end_date = (!empty($request->end_date)) ? date('Y-m-d', strtotime($request->end_date)) : NULL;
        // $location_id = (!empty($request->location_id)) ? $request->location_id : NULL;
        // $staff_id = (!empty($request->staff_id)) ? $request->staff_id : NULL;

        $whereArray = [];

        if(!empty($this->start_date)) {
            $whereArray[] = ['invoice.payment_date', '>=', $this->start_date];
        }
        if(!empty($this->end_date)) {
            $whereArray[] = ['invoice.payment_date', '<=', $this->end_date];
        }
        if(!empty($this->location_id)) {
            $whereArray[] = ['invoice.location_id', '=', $this->location_id];
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

        $LocationName = Location::select('location_name')->where('id',$this->location_id)->first();

        $sheets = [];

        if((!empty($completedPaymentSummaryData)) && (!empty($netPaymentSummaryData))){

            foreach($completedPaymentSummaryData as $completedPaymentSummaryDataKey => $completedPaymentSummaryDataValue)
            {
                $sheets[] = [
                    $completedPaymentSummaryDataValue['payment_type'],
                    $completedPaymentSummaryDataValue['row_count'],
                    'CA $'.number_format($completedPaymentSummaryDataValue['gross_total'],2),
                    'CA $'.number_format(($completedPaymentSummaryDataValue['gross_total'] - $netPaymentSummaryData[$completedPaymentSummaryDataKey]['gross_total']),2),
                    'CA $'.number_format($netPaymentSummaryData[$completedPaymentSummaryDataKey]['gross_total'],2)
                ];
            }
        }

        if($TotalPaymentSummaryData['gross_total'] > 0){
            $sheets[] = [
                'Total',
                $TotalPaymentSummaryTransaction,
                'CA $'.number_format($TotalPaymentSummaryData['gross_total'],2),
                'CA $'.number_format($totalRefundPaymentSummaryData,2),
                'CA $'.number_format(($TotalPaymentSummaryData['gross_total'] - $totalRefundPaymentSummaryData),2)
            ];   
        }else{
            $sheets[] = [
                'Total',
                '0',
                'CA $0.00',
                'CA $0.00',
                'CA $0.00'
            ];  
        }

        return collect($sheets);
    }

    public function headings(): array
    {
        return ['Payments', 'Transactions', 'Gross Payments', 'Refunds', 'Net Payments'];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:E7')->getFont()->setSize(12);
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);
        $sheet->getStyle('A6:E6')->getFont()->setBold(true);
        $sheet->getStyle('A7:E7')->getFont()->setBold(true);
    }
}
