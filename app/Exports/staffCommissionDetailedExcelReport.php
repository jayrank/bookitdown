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
use Maatwebsite\Excel\Events\AfterSheet;

use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;

class staffCommissionDetailedExcelReport implements  ShouldAutoSize, FromCollection, WithHeadings, WithStyles
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

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:I1000')->getFont()->setSize(12);
        $sheet->getStyle('A1:I1')->getFont()->setBold(true);
        $sheet->getStyle('A1:I48')->getAlignment()->setHorizontal('left');

    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $event->sheet->styleCells(
                    'A2:A1000',
                    [
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                        ],
                    ]
                );
            },
        ];
    }
}