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

class tipsCollectedExcelReport implements  ShouldAutoSize, FromCollection, WithHeadings, WithStyles
{
    private $getTipsCollected;

    public function __construct($getTipsCollected)
    {
        $this->getTipsCollected = $getTipsCollected;
    }
    
    public function collection()
    {
        $sheets = [];

        /** Discounts */
        foreach($this->getTipsCollected['data'] as $dKey => $dValue){
            $sheets[] = [
                $dValue['date'],
                $dValue['invoice_id'],
                $dValue['location_name'],
                $dValue['staff'],
                $dValue['tips_collected'],
            ];
        }
        $sheets[] = [''];
        $sheets[] = [
            'Total',
            '',
            '',
            '',
            $this->getTipsCollected['total'],
        ];

        return collect($sheets);
    }

    public function headings(): array
    {
        return ['Date','Invoice No.','Location','Staff Name','Tips Collected'];
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