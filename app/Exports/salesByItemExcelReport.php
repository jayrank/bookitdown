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
// use App\Models\InvoiceVoucher;
// use App\Models\StaffTip;

class salesByItemExcelReport implements  ShouldAutoSize, FromCollection, WithHeadings, WithStyles
{
    private $data;
    private $total_items_sold;
    private $total_gross_sales;
    private $total_discounts;
    private $total_tax;
    private $total_refunds;
    private $total_net_sales;
    private $total_total_sales;

    public function __construct($data, $total_items_sold, $total_gross_sales, $total_discounts, $total_tax, $total_refunds, $total_net_sales, $total_total_sales)
    {
        $this->data = $data;
        $this->total_items_sold = $total_items_sold;
        $this->total_gross_sales = $total_gross_sales;
        $this->total_discounts = $total_discounts;
        $this->total_tax = $total_tax;
        $this->total_refunds = $total_refunds;
        $this->total_net_sales = $total_net_sales;
        $this->total_total_sales = $total_total_sales;
    }
    
    public function collection()
    {
        $sheets = [];

        /** Discounts */

        foreach($this->data as $dataKey => $dataValue){
            $sheets[] = [
                $dataValue['item'],
                'CA $'.number_format($dataValue['items_sold'],2),
                'CA $'.number_format($dataValue['gross_sales'],2),
                'CA $'.number_format($dataValue['discounts'],2),
                'CA $'.number_format($dataValue['refunds'],2),
                'CA $'.number_format($dataValue['net_sales'],2),
                'CA $'.number_format($dataValue['tax'],2),
                'CA $'.number_format($dataValue['total_sales'],2),
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
            'CA $'.number_format($this->total_items_sold,2),
            'CA $'.number_format($this->total_gross_sales,2),
            'CA $'.number_format($this->total_discounts,2),
            'CA $'.number_format($this->total_refunds,2),
            'CA $'.number_format($this->total_net_sales,2),
            'CA $'.number_format($this->total_tax,2),
            'CA $'.number_format($this->total_total_sales,2),
        ];

        return collect($sheets);
    }

    public function headings(): array
    {
        return ['Item','Items Sold', 'Gross Sales', 'Discounts', 'Refunds', 'Net Sales', 'Tax', 'Total Sales'];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:I1000')->getFont()->setSize(12);
        $sheet->getStyle('A1:I1')->getFont()->setBold(true);
    }
}
