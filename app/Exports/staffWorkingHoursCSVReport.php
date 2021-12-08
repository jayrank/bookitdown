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

class staffWorkingHoursCSVReport implements  ShouldAutoSize, FromCollection, WithHeadings
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
}