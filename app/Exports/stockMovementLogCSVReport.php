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

class stockMovementLogCSVReport implements  ShouldAutoSize, FromCollection, WithHeadings
{
    private $getStockMovementLog;

    public function __construct($getStockMovementLog)
    {
        $this->getStockMovementLog = $getStockMovementLog;
    }
    
    public function collection()
    {
        $sheets = [];
        $sheets[] = [""];

        /** Discounts */
        foreach($this->getStockMovementLog->original['data'] as $dKey => $dValue){
            $sheets[] = [
                $dValue['time_and_date'],
                $dValue['product'],
                $dValue['barcode'],
                $dValue['staff'],
                $dValue['action'],
                $dValue['adjustment'],
                $dValue['cost_price'],
                $dValue['on_hand'],
            ];
        }

        return collect($sheets);
    }

    public function headings(): array
    {
        return ['Time & Date','Product','Barcode','Staff','Action','Adjustment','Cost Price','On Hand'];
    }
}