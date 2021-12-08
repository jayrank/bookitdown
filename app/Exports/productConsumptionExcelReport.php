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

class productConsumptionExcelReport implements  ShouldAutoSize, FromCollection, WithHeadings, WithStyles
{
    private $getProductConsumption;

    public function __construct($getProductConsumption)
    {
        $this->getProductConsumption = $getProductConsumption;
    }
    
    public function collection()
    {
        $sheets = [];

        // echo "<script> alert(' Hellooooo !!!!'); </script>";
        // dd($this->getProductConsumption);
        foreach($this->getProductConsumption as $getProductConsumptionkey => $getProductConsumptionValue){
            $sheets[] = [
                $getProductConsumptionValue['heading'],
                $getProductConsumptionValue['totalCost'],
            ];
        }
        foreach($this->getProductConsumption as $ProductConsumptionkey => $ProductConsumptionValue){

            // $sheets[] = [$ProductConsumptionValue['totalCost']];
            $sheets[] = [''];
            $sheets[] = [$ProductConsumptionValue['heading']];
            $sheets[] = [''];
            $sheets[] = ['Product Name', 'Quantity Used', 'Avg. Cost Price', 'Total Cost'];
            foreach($ProductConsumptionValue['data'] as $dKey => $dValue){
                $sheets[] = [
                    $dValue['product_name'],
                    $dValue['quantity_used'],
                    $dValue['average_cost_price'],
                    $dValue['total_cost'],
                ];
            }
            $sheets[] = [
                "Total",
                $ProductConsumptionValue['totalQuantity'],
                '',
                $ProductConsumptionValue['totalCost'],
            ];
            $sheets[] = [''];
        }

        return collect($sheets);
    }

    public function headings(): array
    {
        return ['Summary'];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:I1000')->getFont()->setSize(12);
        $sheet->getStyle('A1:I1')->getFont()->setBold(true);
        $sheet->getStyle('A1:I48')->getAlignment()->setHorizontal('left');

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