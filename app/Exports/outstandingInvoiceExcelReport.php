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

class outstandingInvoiceExcelReport implements  ShouldAutoSize, FromCollection, WithHeadings, WithStyles
{
    private $getOutstandingInvoiceCSV;
    private $total;

    public function __construct($getOutstandingInvoiceCSV, $total)
    {
        $this->getOutstandingInvoiceCSV = $getOutstandingInvoiceCSV;
        $this->total = $total;
    }
    
    public function collection()
    {
        $sheets = [];

        /** Discounts */

        foreach($this->getOutstandingInvoiceCSV as $getOutstandingInvoiceCSVKey => $getOutstandingInvoiceCSVValue){
            $sheets[] = [
                $getOutstandingInvoiceCSVValue['invoice_id'],
                $getOutstandingInvoiceCSVValue['location_name'],
                strip_tags($getOutstandingInvoiceCSVValue['status']),
                $getOutstandingInvoiceCSVValue['invoice_date'],
                $getOutstandingInvoiceCSVValue['due_date'],
                $getOutstandingInvoiceCSVValue['Overdue'],
                $getOutstandingInvoiceCSVValue['customer'],
                $getOutstandingInvoiceCSVValue['gross_total'],
                $getOutstandingInvoiceCSVValue['amount_due'],
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
            $this->total,
            $this->total,
        ];

        return collect($sheets);
    }

    public function headings(): array
    {
        return ['Invoice#','Location', 'Status', 'Invoice Date', 'Due Date', 'Overdue', 'Customer', 'Gross Total','Amount Due'];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:I1000')->getFont()->setSize(12);
        $sheet->getStyle('A1:I1')->getFont()->setBold(true);
    }
}
