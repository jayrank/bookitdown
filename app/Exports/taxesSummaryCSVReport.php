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

class taxesSummaryCSVReport implements  ShouldAutoSize, FromCollection, WithHeadings
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
}