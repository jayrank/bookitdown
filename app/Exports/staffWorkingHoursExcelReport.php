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

class staffWorkingHoursExcelReport implements  ShouldAutoSize, FromCollection, WithHeadings, WithStyles
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

        foreach($this->data as $dataKey => $dataValue){
            $sheets[] = [''];
            $sheets[] = [''];
            $sheets[] = [$dataValue['staff_name']];
            $sheets[] = [''];
            $sheets[] = ['Date','Start', 'End', 'Duration'];
            foreach($dataValue['data'] as $dKey => $dValue){
                $sheets[] = [
                    $dValue['date'],
                    $dValue['start_time'],
                    $dValue['end_time'],
                    $dValue['duration'],
                ];
            }
            $sheets[] = ['Total','','',$dataValue['total']];
        }
        $sheets[] = [
            '',
            '',
            '',
            '',
            '',
            ''
        ];

        return collect($sheets);
    }

    public function headings(): array
    {
        return ['Staff Working Hours'];
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
