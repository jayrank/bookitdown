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

class clientRetentionCSVReport implements  ShouldAutoSize, FromCollection, WithHeadings
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
        return ['Name','Mobile Number', 'Email', 'Last Appointment', 'Days Absent', 'Staff', 'Last Visit Sales', 'Total Sales'];
    }
}