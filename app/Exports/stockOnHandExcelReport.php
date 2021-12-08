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

class stockOnHandExcelReport implements  ShouldAutoSize, FromCollection, WithHeadings, WithStyles
{
    private $getStockOnHand;

    public function __construct($getStockOnHand)
    {
        $this->getStockOnHand = $getStockOnHand;
    }
    
    public function collection()
    {
        $sheets = [];

        /** Discounts */
        foreach($this->getStockOnHand['data'] as $dKey => $dValue){
            $sheets[] = [
                $dValue['product'],
                $dValue['stock_on_hand'],
                $dValue['total_cost'],
                $dValue['average_cost'],
                $dValue['total_retail_value'],
                $dValue['retail_price'],
                $dValue['reorder_point'],
                $dValue['reorder_amount'],
            ];
        }
        $sheets[] = [""];
        $sheets[] = [
            "Total",
            $this->getStockOnHand['stock_on_hand'],
            $this->getStockOnHand['total_cost'],
            $this->getStockOnHand['average_cost'],
            $this->getStockOnHand['total_retail_value'],
            "",
            "",
            "",
        ];

        return collect($sheets);
    }

    public function headings(): array
    {
        return ['Product','Stock On Hand','Total Cost','Average Cost','Total Retail Value','Retail Price','Reorder Point','Reorder Amount'];
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