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

class clientRetentionExcelReport implements  ShouldAutoSize, FromCollection, WithHeadings, WithStyles
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
            $sheets[] = [
                $dataValue['name'],
                $dataValue['mobile_no'],
                $dataValue['email'],
                $dataValue['last_appointment'],
                $dataValue['days_absent'],
                $dataValue['staff'],
                $dataValue['last_visit_sales'],
                $dataValue['total_sales'],
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

        return collect($sheets);
    }

    public function headings(): array
    {
        return ['Name', 'Mobile Number', 'Email', 'Last Appointment', 'Days Absent', 'Staff', 'Last Visit Sales', 'Total Sales'];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:H1000')->getFont()->setSize(12);
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);
        // $sheet->setAlignment('right');
        // $sheet->cells('A1:H1000', function ($cells) {
        //     // $cells->setAlignment('right');
        //     $cells->setValignment('center');
        // });
        $sheet->getStyle('A1:H48')->getAlignment()->setHorizontal('left');
        // $sheet->setWidth('A', 5);

    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $event->sheet->styleCells(
                    'B2:B1000',
                    [
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                        ],
                    ]
                );
            },
        ];
    }
}
