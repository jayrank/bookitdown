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

class staffCommissionCSVReport implements  ShouldAutoSize, FromCollection, WithHeadings
{
    private $getStaffCommission;

    public function __construct($getStaffCommission)
    {
        $this->getStaffCommission = $getStaffCommission;
    }
    
    public function collection()
    {
        $sheets = [];

        /** Discounts */
        foreach($this->getStaffCommission['data'] as $dKey => $dValue){
            $sheets[] = [
                $dValue['staff_member'],
                number_format($dValue['service_sales_total'], 2, '.', ''),
                number_format($dValue['service_commission'], 2, '.', ''),
                number_format($dValue['product_sales_total'], 2, '.', ''),
                number_format($dValue['product_commission'], 2, '.', ''),
                number_format($dValue['voucher_sales_total'], 2, '.', ''),
                number_format($dValue['voucher_commission'], 2, '.', ''),
                number_format($dValue['commission_total'], 2, '.', ''),
            ];
        }
        $sheets[] = [
            ''
        ];
        $sheets[] = [
            'Total',
            number_format($this->getStaffCommission['serviceSalesTotal'], 2, '.', ''),
            number_format($this->getStaffCommission['serviceCommissionTotal'], 2, '.', ''),
            number_format($this->getStaffCommission['productSalesTotal'], 2, '.', ''),
            number_format($this->getStaffCommission['productCommissionTotal'], 2, '.', ''),
            number_format($this->getStaffCommission['voucherSalesTotal'], 2, '.', ''),
            number_format($this->getStaffCommission['voucherCommissionTotal'], 2, '.', ''),
            number_format($this->getStaffCommission['commissionTotal'], 2, '.', ''),
        ];

        return collect($sheets);
    }

    public function headings(): array
    {
        return ['Staff Member','Service Sales Total','Service Commission','Product Sales Total','Product Commission','Voucher Sales Total','Voucher Commission','Commission Total'];
    }
}