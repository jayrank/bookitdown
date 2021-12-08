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

class taxesSummaryExcelReport implements  ShouldAutoSize, FromCollection, WithHeadings, WithStyles
{
    private $getTaxesSummary;

    public function __construct($getTaxesSummary)
    {
        $this->getTaxesSummary = $getTaxesSummary;
    }
    
    public function collection()
    {
        $sheets = [];

        /** Discounts */
        foreach($this->getTaxesSummary['data'] as $dKey => $dValue){
            $sheets[] = [
                $dValue['tax'],
                $dValue['location'],
                $dValue['item_sales'],
                $dValue['tax_rates'],
                $dValue['amount'],
            ];
        }
        $sheets[] = [
            ''
        ];
        $sheets[] = [
            'Total',
            '',
            '',
            '',
            $this->getTaxesSummary['total'],
        ];

        return collect($sheets);
    }

    public function headings(): array
    {
        return ['Tax','Location','Item Sale','Rate','Amount'];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:H1000')->getFont()->setSize(12);
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);
        $sheet->getStyle('A1:H48')->getAlignment()->setHorizontal('left');

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