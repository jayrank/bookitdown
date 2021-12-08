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

class tipsByStaffExcelReport implements  ShouldAutoSize, FromCollection, WithHeadings, WithStyles
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }
    
    public function collection()
    {
        $sheets = [];

        /** Discounts */
        foreach($this->data['data'] as $dKey => $dValue){
            $sheets[] = [
                $dValue['employee_name'],
                number_format($dValue['collected_tip'], 2, '.', ''),
                number_format($dValue['refunded_tips'], 2, '.', ''),
                number_format($dValue['total_tips'], 2, '.', ''),
                number_format($dValue['average_tips'], 2, '.', ''),
            ];
        }
        $sheets[] = [
            ''
        ];
        $sheets[] = [
            'Total',
            number_format($this->data['collectedTipsTotal'], 2, '.', ''),
            number_format($this->data['refundedTipsTotal'], 2, '.', ''),
            number_format($this->data['totalTips'], 2, '.', ''),
            number_format($this->data['averageTipsTotal'], 2, '.', ''),
        ];

        return collect($sheets);
    }

    public function headings(): array
    {
        return ['Employee Name','Collected Tips','Refunded Tips','Total Tips','Average Tips'];
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