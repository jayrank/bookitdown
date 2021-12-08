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

class financesSummaryCSVReport implements  ShouldAutoSize, FromCollection, WithHeadings
{
    private $getFinancesSummary;

    public function __construct($getFinancesSummary)
    {
        $this->getFinancesSummary = $getFinancesSummary;
    }
    
    public function collection()
    {
        $sheets = [];
        $sheets[] = ["Payment Method", "Amount"];
        $totalPayment = 0;

        /** Discounts */
        foreach($this->getFinancesSummary->original['data']['paymentWiseTotal'] as $dKey => $dValue){
            $sheets[] = [
                $dValue['payment_type'],
                "CA $". number_format($dValue['total'], 2),
            ];
            $totalPayment += $dValue['total'];
        }
        $sheets[] = ["Total Payments", "CA $". number_format($totalPayment, 2)];
        $sheets[] = [""];
        $sheets[] = [""];
        $sheets[] = ["Tips"];
        $sheets[] = ["Tips", "Amount"];
        $sheets[] = ["Tips Collected", $this->getFinancesSummary->original['totalTip']];
        $sheets[] = [""];
        $sheets[] = [""];

        $sheets[] = ["Vouchers"];
        $sheets[] = ["Type", "Amount"];
        $sheets[] = ["Voucher sales", "CA $". $this->getFinancesSummary->original['totalVoucherSale']];
        $sheets[] = ["Voucher redemptions", "CA $". $this->getFinancesSummary->original['voucherRedemption']];
        $sheets[] = ["Voucher outstanding balance", $this->getFinancesSummary->original['totalVoucherOutstanding']];
        $sheets[] = [''];
        $sheets[] = [''];

        $sheets[] = ['Sales'];
        $sheets[] = ['Type', 'Amount'];
        $sheets[] = ['Gross sales', "CA $". $this->getFinancesSummary->original['data']['grossTotal']];
        $sheets[] = ['Discounts', "CA $". $this->getFinancesSummary->original['data']['totalDiscount']];
        $sheets[] = ['Refunds', "CA $". $this->getFinancesSummary->original['data']['totalRefunds']];
        $sheets[] = ['Net Sales', "CA $". $this->getFinancesSummary->original['data']['netSales']];
        $sheets[] = ['Taxes', "CA $". $this->getFinancesSummary->original['data']['totalTaxes']];
        $sheets[] = ['Total Sales', "CA $". $this->getFinancesSummary->original['data']['totalSales']];

        return collect($sheets);
    }

    public function headings(): array
    {
        return ['Payments'];
    }
}