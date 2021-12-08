<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
// use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
// use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;

class staffCommissionDetailedCSVReport implements  ShouldAutoSize, FromCollection, WithHeadings
{
    private $getStaffCommissionDetailed;

    public function __construct($getStaffCommissionDetailed)
    {
        $this->getStaffCommissionDetailed = $getStaffCommissionDetailed;
    }
    
    public function collection()
    {
        $sheets = [];

        /** Discounts */
        foreach($this->getStaffCommissionDetailed['data'] as $dKey => $dValue){
            $sheets[] = [
                $dValue['invoice_date'],
                $dValue['invoice_no'],
                $dValue['staff_member'],
                $dValue['item_sold'],
                $dValue['quantity'],
                $dValue['sale_value'],
                $dValue['commission_rate'],
                $dValue['commission_amount'],
                $dValue['item_type'],
            ];
        }

        return collect($sheets);
    }

    public function headings(): array
    {
        return ['Invoice Date','Invoice No','Staff Member','Item Sold','Quantity','Sale Value','Commission Rate','Commission Amount','Item Type'];
    }
}