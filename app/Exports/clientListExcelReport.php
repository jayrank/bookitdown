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

use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;

class clientListExcelReport implements  ShouldAutoSize, FromCollection, WithHeadings, WithStyles
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
                $dataValue['blocked'],
                $dataValue['appointments'],
                ($dataValue['no_show']) ? $dataValue['no_show'] : '0',
                'CA $'.number_format($dataValue['total_sales'],2),
                'CA $'.number_format($dataValue['outstanding'],2),
                $dataValue['gender'],
                $dataValue['added'],
                $dataValue['last_appointment'],
                $dataValue['last_location'],
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
        return ['Name','Blocked', 'Appointments', 'No Show', 'Total Sales', 'Outstanding', 'Gender', 'Added', 'Last Appointment', 'Last Location'];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:K1000')->getFont()->setSize(12);
        $sheet->getStyle('A1:K1')->getFont()->setBold(true);
    }
}
