<?php

namespace App\Exports;

use App\Models\InvoiceItems;
use Maatwebsite\Excel\Concerns\FromCollection;
// use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
// use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

use DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Staff;
use Illuminate\Contracts\View\View;
use App\Models\paymentType;
use App\Models\Invoice;
use App\Models\InvoiceVoucher;
use App\Models\StaffTip;

class paymentLogCSVReport implements  ShouldAutoSize, FromCollection, WithHeadings
{
    // private $start_date;
    // private $end_date;
    // private $location_id;
    // private $staff_id;

    private $getPaymentLogCSV;

    public function __construct($getPaymentLogCSV)
    {
        $this->getPaymentLogCSV = $getPaymentLogCSV;
    }
    
    public function collection()
    {
        $sheets = [];

        /** Discounts */

        $total = 0;

        foreach($this->getPaymentLogCSV as $getPaymentLogCSVKey => $getPaymentLogCSVValue){
            $total += $getPaymentLogCSVValue['inovice_final_total'];

            $sheets[] = [
                $getPaymentLogCSVValue['payment_date'],
                $getPaymentLogCSVValue['location_name'],
                $getPaymentLogCSVValue['invoice_id'],
                strip_tags($getPaymentLogCSVValue['client']),
                strip_tags($getPaymentLogCSVValue['staff']),
                strip_tags($getPaymentLogCSVValue['transaction']),
                $getPaymentLogCSVValue['method'],
                $getPaymentLogCSVValue['inovice_final_total'],
            ];
        }
        $sheets[] = [
            '',
            '',
            '',
            '',
            '',
            ''
        ];
        $sheets[] = [
            'Total',
            '',
            '',
            '',
            '',
            '',
            '',
            $total,
        ];

        return collect($sheets);
    }

    public function headings(): array
    {
        return ['Payment Date','Location', 'Invoice No.', 'Client', 'Staff', 'Transaction', 'Method', 'Amount'];
    }
}