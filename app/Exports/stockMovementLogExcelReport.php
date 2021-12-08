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

class stockMovementLogExcelReport implements  ShouldAutoSize, FromCollection, WithHeadings, WithStyles
{
    private $getStockMovementLog;

    public function __construct($getStockMovementLog)
    {
        $this->getStockMovementLog = $getStockMovementLog;
    }
    
    public function collection()
    {
        $sheets = [];
        $sheets[] = [""];

        /** Discounts */
        foreach($this->getStockMovementLog->original['data'] as $dKey => $dValue){
            $sheets[] = [
                $dValue['time_and_date'],
                $dValue['product'],
                $dValue['barcode'],
                $dValue['staff'],
                $dValue['action'],
                $dValue['adjustment'],
                $dValue['cost_price'],
                $dValue['on_hand'],
            ];
        }

        return collect($sheets);
    }

    public function headings(): array
    {
        return ['Time & Date','Product','Barcode','Staff','Action','Adjustment','Cost Price','On Hand'];
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