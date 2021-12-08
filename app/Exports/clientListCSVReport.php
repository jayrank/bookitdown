<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
// use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
// use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;

class clientListCSVReport implements  ShouldAutoSize, FromCollection, WithHeadings
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
                $dataValue['no_show'],
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
}