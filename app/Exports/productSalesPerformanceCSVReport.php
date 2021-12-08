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

class productSalesPerformanceCSVReport implements  ShouldAutoSize, FromCollection, WithHeadings
{
    private $getProductSalesPerformance;

    public function __construct($getProductSalesPerformance)
    {
        $this->getProductSalesPerformance = $getProductSalesPerformance;
    }
    
    public function collection()
    {
        $sheets = [];

        /** Discounts */
        foreach($this->getProductSalesPerformance['data'] as $dKey => $dValue){
            $sheets[] = [
                $dValue['product_name'],
                $dValue['stock_on_hand'],
                $dValue['qty_sold'],
                $dValue['cost_of_goods_sold'],
                $dValue['net_sales'],
                $dValue['average_net_price'],
                $dValue['margin'],
                $dValue['total_margin'],
            ];
        }
        $sheets[] = [""];
        $sheets[] = [
            "Total",
            $this->getProductSalesPerformance['getProductSalesPerformanceTableFooterStockOnHand'],
            $this->getProductSalesPerformance['getProductSalesPerformanceTableFooterQtySold'],
            $this->getProductSalesPerformance['getProductSalesPerformanceTableFooterCostOfGoodsSold'],
            $this->getProductSalesPerformance['getProductSalesPerformanceTableFooterNetSales'],
            $this->getProductSalesPerformance['getProductSalesPerformanceTableFooterAverageNetPrice'],
            $this->getProductSalesPerformance['getProductSalesPerformanceTableFooterMargin'],
            $this->getProductSalesPerformance['getProductSalesPerformanceTableFooterTotalMargin'],
        ];

        return collect($sheets);
    }

    public function headings(): array
    {
        return ['Product Name','Stock On Hand','Qty Sold','Cost of Goods Sold','Net Sales','Average Net Price','Margin','Total Margin'];
    }
}