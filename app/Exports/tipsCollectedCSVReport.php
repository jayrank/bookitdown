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

class tipsCollectedCSVReport implements  ShouldAutoSize, FromCollection, WithHeadings
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
}