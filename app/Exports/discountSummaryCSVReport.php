<?php

namespace App\Exports;
// require_once '../Http/Controllers/AnalyticsController.php';

use App\Models\InvoiceItems;
use Maatwebsite\Excel\Concerns\FromCollection;
// use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
// use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

use DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Staff;
use Illuminate\Contracts\View\View;
use App\Models\paymentType;
use App\Models\Invoice;
use App\Models\InvoiceVoucher;
use App\Models\StaffTip;

class discountSummaryCSVReport implements  ShouldAutoSize, FromCollection, WithHeadings
{
    // private $start_date;
    // private $end_date;
    // private $location_id;
    // private $staff_id;

    private $getDiscountSummary;
    private $getDiscountSummaryByServices;
    private $getDiscountSummaryByProduct;
    private $getDiscountSummaryByStaff;
    private $getDiscountSummaryByType;

    public function __construct($getDiscountSummary, $getDiscountSummaryByServices, $getDiscountSummaryByProduct, $getDiscountSummaryByStaff, $getDiscountSummaryByType)
    {
        $this->getDiscountSummary = $getDiscountSummary;
        $this->getDiscountSummaryByServices = $getDiscountSummaryByServices;
        $this->getDiscountSummaryByProduct = $getDiscountSummaryByProduct;
        $this->getDiscountSummaryByStaff = $getDiscountSummaryByStaff;
        $this->getDiscountSummaryByType = $getDiscountSummaryByType;
    }
    
    public function collection()
    {
        $sheets = [];

        /** Discounts */

        $sheets[] = [
            '',
            '',
            '',
            '',
            '',
            ''
        ];

        $sheets[] = [
            'Discount',
            'Items Discounted',
            'Items Value',
            'Discount Amount',
            'Discount Refunds',
            'Net Discounts'
        ];

        $totalItemDiscounted = 0; 
        $totalItemValue = 0;
        $totalDiscountAmount = 0;
        $totalDiscountRefund = 0;
        $totalNetDiscount = 0;

        foreach($this->getDiscountSummary['discountComplete'] as $getDiscountSummaryKey => $getDiscountSummaryValue){

            $totalItemDiscounted += ($getDiscountSummaryValue['count'] - (!empty($this->getDiscountSummary[$getDiscountSummaryKey]['discountRefund']) ? (($this->getDiscountSummary[$getDiscountSummaryKey]['discountRefund']['refund_discount_name'] == $getDiscountSummaryValue['discount_name']) ? $this->getDiscountSummary[$getDiscountSummaryKey]['discountRefund']['refund_count'] : 0 ) : 0) );

            $totalItemValue += $getDiscountSummaryValue['og_price_sum'] - ( !empty($this->getDiscountSummary[$getDiscountSummaryKey]['discountRefund']) ? ( ( $this->getDiscountSummary[$getDiscountSummaryKey]['discountRefund']['refund_discount_name'] ==  $getDiscountSummaryValue['discount_name']) ? $this->getDiscountSummary[$getDiscountSummaryKey]['discountRefund']['refund_og_price_sum'] : 0 ) : 0);

            $totalDiscountAmount += $getDiscountSummaryValue['discount_price'];

            $totalDiscountRefund += ( !empty($this->getDiscountSummary[$getDiscountSummaryKey]['discountRefund']) ? ( ( $this->getDiscountSummary[$getDiscountSummaryKey]['discountRefund']['refund_discount_name'] ==  $getDiscountSummaryValue['discount_name']) ? $this->getDiscountSummary[$getDiscountSummaryKey]['discountRefund']['refund_discount_price'] : 0 ) : 0);

            $totalNetDiscount += $getDiscountSummaryValue['discount_price'] - ( !empty($this->getDiscountSummary[$getDiscountSummaryKey]['discountRefund']) ? ( ( $this->getDiscountSummary[$getDiscountSummaryKey]['discountRefund']['refund_discount_name'] ==  $getDiscountSummaryValue['discount_name']) ? $this->getDiscountSummary[$getDiscountSummaryKey]['discountRefund']['refund_discount_price'] : 0 ) : 0);

            $sheets[] = [
                $getDiscountSummaryValue['discount_name'],

                ($getDiscountSummaryValue['count'] - (!empty($this->getDiscountSummary[$getDiscountSummaryKey]['discountRefund']) ? (($this->getDiscountSummary[$getDiscountSummaryKey]['discountRefund']['refund_discount_name'] == $getDiscountSummaryValue['discount_name']) ? $this->getDiscountSummary[$getDiscountSummaryKey]['discountRefund']['refund_count'] : 0 ) : 0) ),

                'CA $'. number_format($getDiscountSummaryValue['og_price_sum'] - ( !empty($this->getDiscountSummary[$getDiscountSummaryKey]['discountRefund']) ? ( ( $this->getDiscountSummary[$getDiscountSummaryKey]['discountRefund']['refund_discount_name'] ==  $getDiscountSummaryValue['discount_name']) ? $this->getDiscountSummary[$getDiscountSummaryKey]['discountRefund']['refund_og_price_sum'] : 0 ) : 0),2,'.',''),

                'CA $'. number_format($getDiscountSummaryValue['discount_price'],2,'.',''),

                'CA $'. number_format(( !empty($this->getDiscountSummary[$getDiscountSummaryKey]['discountRefund']) ? ( ( $this->getDiscountSummary[$getDiscountSummaryKey]['discountRefund']['refund_discount_name'] ==  $getDiscountSummaryValue['discount_name']) ? $this->getDiscountSummary[$getDiscountSummaryKey]['discountRefund']['refund_discount_price'] : 0 ) : 0),2,'.',''),

                'CA $'. number_format($getDiscountSummaryValue['discount_price'] - ( !empty($this->getDiscountSummary[$getDiscountSummaryKey]['discountRefund']) ? ( ( $this->getDiscountSummary[$getDiscountSummaryKey]['discountRefund']['refund_discount_name'] ==  $getDiscountSummaryValue['discount_name']) ? $this->getDiscountSummary[$getDiscountSummaryKey]['discountRefund']['refund_discount_price'] : 0 ) : 0),2,'.','')
            ];
        }
        $sheets[] = [
            'Total',
            $totalItemDiscounted,
            'CA $'. number_format($totalItemValue, 2,'.',''),
            'CA $'. number_format($totalDiscountAmount, 2,'.',''),
            'CA $'. number_format($totalDiscountRefund, 2,'.',''),
            'CA $'. number_format($totalNetDiscount, 2,'.','')
        ];

        /** Discounts By Services */

        $sheets[] = [
            '',
            '',
            '',
            '',
            '',
            ''
        ];
        $sheets[] = [
            'Discounts By Services'
        ];
        $sheets[] = [
            '',
            '',
            '',
            '',
            '',
            ''
        ];
        $sheets[] = [
            'Service Name',
            'Items Discounted',
            'Items Value',
            'Discount Amount',
            'Discount Refunds',
            'Net Discounts'
        ];

        $totalItemDiscounted = 0; 
        $totalItemValue = 0;
        $totalDiscountAmount = 0;
        $totalDiscountRefund = 0;
        $totalNetDiscount = 0;

        foreach($this->getDiscountSummaryByServices['discountComplete'] as $getDiscountSummaryKey => $getDiscountSummaryValue){

            $totalItemDiscounted += ($getDiscountSummaryValue['count'] - (!empty($this->getDiscountSummaryByServices[$getDiscountSummaryKey]['discountRefund']) ? (($this->getDiscountSummaryByServices[$getDiscountSummaryKey]['discountRefund']['service_name'] == $getDiscountSummaryValue['service_name']) ? $this->getDiscountSummaryByServices[$getDiscountSummaryKey]['discountRefund']['refund_count'] : 0 ) : 0) );

            $totalItemValue += $getDiscountSummaryValue['og_price_sum'] - ( !empty($this->getDiscountSummaryByServices[$getDiscountSummaryKey]['discountRefund']) ? ( ( $this->getDiscountSummaryByServices[$getDiscountSummaryKey]['discountRefund']['service_name'] ==  $getDiscountSummaryValue['service_name']) ? $this->getDiscountSummaryByServices[$getDiscountSummaryKey]['discountRefund']['refund_og_price_sum'] : 0 ) : 0);

            $totalDiscountAmount += $getDiscountSummaryValue['discount_price'];

            $totalDiscountRefund += ( !empty($this->getDiscountSummaryByServices[$getDiscountSummaryKey]['discountRefund']) ? ( ( $this->getDiscountSummaryByServices[$getDiscountSummaryKey]['discountRefund']['service_name'] ==  $getDiscountSummaryValue['service_name']) ? $this->getDiscountSummaryByServices[$getDiscountSummaryKey]['discountRefund']['refund_discount_price'] : 0 ) : 0);

            $totalNetDiscount += $getDiscountSummaryValue['discount_price'] - ( !empty($this->getDiscountSummaryByServices[$getDiscountSummaryKey]['discountRefund']) ? ( ( $this->getDiscountSummaryByServices[$getDiscountSummaryKey]['discountRefund']['service_name'] ==  $getDiscountSummaryValue['service_name']) ? $this->getDiscountSummaryByServices[$getDiscountSummaryKey]['discountRefund']['refund_discount_price'] : 0 ) : 0);

            $sheets[] = [
                $getDiscountSummaryValue['service_name'],

                ($getDiscountSummaryValue['count'] - (!empty($this->getDiscountSummaryByServices[$getDiscountSummaryKey]['discountRefund']) ? (($this->getDiscountSummaryByServices[$getDiscountSummaryKey]['discountRefund']['service_name'] == $getDiscountSummaryValue['service_name']) ? $this->getDiscountSummaryByServices[$getDiscountSummaryKey]['discountRefund']['refund_count'] : 0 ) : 0) ),

                'CA $'. number_format($getDiscountSummaryValue['og_price_sum'] - ( !empty($this->getDiscountSummaryByServices[$getDiscountSummaryKey]['discountRefund']) ? ( ( $this->getDiscountSummaryByServices[$getDiscountSummaryKey]['discountRefund']['service_name'] ==  $getDiscountSummaryValue['service_name']) ? $this->getDiscountSummaryByServices[$getDiscountSummaryKey]['discountRefund']['refund_og_price_sum'] : 0 ) : 0),2,'.',''),

                'CA $'. number_format($getDiscountSummaryValue['discount_price'],2,'.',''),

                'CA $'. number_format(( !empty($this->getDiscountSummaryByServices[$getDiscountSummaryKey]['discountRefund']) ? ( ( $this->getDiscountSummaryByServices[$getDiscountSummaryKey]['discountRefund']['service_name'] ==  $getDiscountSummaryValue['service_name']) ? $this->getDiscountSummaryByServices[$getDiscountSummaryKey]['discountRefund']['refund_discount_price'] : 0 ) : 0),2,'.',''),

                'CA $'. number_format($getDiscountSummaryValue['discount_price'] - ( !empty($this->getDiscountSummaryByServices[$getDiscountSummaryKey]['discountRefund']) ? ( ( $this->getDiscountSummaryByServices[$getDiscountSummaryKey]['discountRefund']['service_name'] ==  $getDiscountSummaryValue['service_name']) ? $this->getDiscountSummaryByServices[$getDiscountSummaryKey]['discountRefund']['refund_discount_price'] : 0 ) : 0),2,'.','')
            ];
        }
        $sheets[] = [
            'Total',
            $totalItemDiscounted,
            'CA $'. number_format($totalItemValue, 2,'.',''),
            'CA $'. number_format($totalDiscountAmount, 2,'.',''),
            'CA $'. number_format($totalDiscountRefund, 2,'.',''),
            'CA $'. number_format($totalNetDiscount, 2,'.','')
        ];

        /** Discounts By Product */

        $sheets[] = [
            '',
            '',
            '',
            '',
            '',
            ''
        ];
        $sheets[] = [
            'Discounts By Product'
        ];
        $sheets[] = [
            '',
            '',
            '',
            '',
            '',
            ''
        ];
        $sheets[] = [
            'Product Name',
            'Items Discounted',
            'Items Value',
            'Discount Amount',
            'Discount Refunds',
            'Net Discounts'
        ];

        $totalItemDiscounted = 0; 
        $totalItemValue = 0;
        $totalDiscountAmount = 0;
        $totalDiscountRefund = 0;
        $totalNetDiscount = 0;

        foreach($this->getDiscountSummaryByProduct['discountComplete'] as $getDiscountSummaryKey => $getDiscountSummaryValue){

            $totalItemDiscounted += ($getDiscountSummaryValue['count'] - (!empty($this->getDiscountSummaryByProduct[$getDiscountSummaryKey]['discountRefund']) ? (($this->getDiscountSummaryByProduct[$getDiscountSummaryKey]['discountRefund']['product_name'] == $getDiscountSummaryValue['product_name']) ? $this->getDiscountSummaryByProduct[$getDiscountSummaryKey]['discountRefund']['refund_count'] : 0 ) : 0) );

            $totalItemValue += $getDiscountSummaryValue['og_price_sum'] - ( !empty($this->getDiscountSummaryByProduct[$getDiscountSummaryKey]['discountRefund']) ? ( ( $this->getDiscountSummaryByProduct[$getDiscountSummaryKey]['discountRefund']['product_name'] ==  $getDiscountSummaryValue['product_name']) ? $this->getDiscountSummaryByProduct[$getDiscountSummaryKey]['discountRefund']['refund_og_price_sum'] : 0 ) : 0);

            $totalDiscountAmount += $getDiscountSummaryValue['discount_price'];

            $totalDiscountRefund += ( !empty($this->getDiscountSummaryByProduct[$getDiscountSummaryKey]['discountRefund']) ? ( ( $this->getDiscountSummaryByProduct[$getDiscountSummaryKey]['discountRefund']['product_name'] ==  $getDiscountSummaryValue['product_name']) ? $this->getDiscountSummaryByProduct[$getDiscountSummaryKey]['discountRefund']['refund_discount_price'] : 0 ) : 0);

            $totalNetDiscount += $getDiscountSummaryValue['discount_price'] - ( !empty($this->getDiscountSummaryByProduct[$getDiscountSummaryKey]['discountRefund']) ? ( ( $this->getDiscountSummaryByProduct[$getDiscountSummaryKey]['discountRefund']['product_name'] ==  $getDiscountSummaryValue['product_name']) ? $this->getDiscountSummaryByProduct[$getDiscountSummaryKey]['discountRefund']['refund_discount_price'] : 0 ) : 0);

            $sheets[] = [
                $getDiscountSummaryValue['product_name'],

                ($getDiscountSummaryValue['count'] - (!empty($this->getDiscountSummaryByProduct[$getDiscountSummaryKey]['discountRefund']) ? (($this->getDiscountSummaryByProduct[$getDiscountSummaryKey]['discountRefund']['product_name'] == $getDiscountSummaryValue['product_name']) ? $this->getDiscountSummaryByProduct[$getDiscountSummaryKey]['discountRefund']['refund_count'] : 0 ) : 0) ),

                'CA $'. number_format($getDiscountSummaryValue['og_price_sum'] - ( !empty($this->getDiscountSummaryByProduct[$getDiscountSummaryKey]['discountRefund']) ? ( ( $this->getDiscountSummaryByProduct[$getDiscountSummaryKey]['discountRefund']['product_name'] ==  $getDiscountSummaryValue['product_name']) ? $this->getDiscountSummaryByProduct[$getDiscountSummaryKey]['discountRefund']['refund_og_price_sum'] : 0 ) : 0),2,'.',''),

                'CA $'. number_format($getDiscountSummaryValue['discount_price'],2,'.',''),

                'CA $'. number_format(( !empty($this->getDiscountSummaryByProduct[$getDiscountSummaryKey]['discountRefund']) ? ( ( $this->getDiscountSummaryByProduct[$getDiscountSummaryKey]['discountRefund']['product_name'] ==  $getDiscountSummaryValue['product_name']) ? $this->getDiscountSummaryByProduct[$getDiscountSummaryKey]['discountRefund']['refund_discount_price'] : 0 ) : 0),2,'.',''),

                'CA $'. number_format($getDiscountSummaryValue['discount_price'] - ( !empty($this->getDiscountSummaryByProduct[$getDiscountSummaryKey]['discountRefund']) ? ( ( $this->getDiscountSummaryByProduct[$getDiscountSummaryKey]['discountRefund']['product_name'] ==  $getDiscountSummaryValue['product_name']) ? $this->getDiscountSummaryByProduct[$getDiscountSummaryKey]['discountRefund']['refund_discount_price'] : 0 ) : 0),2,'.','')
            ];
        }
        $sheets[] = [
            'Total',
            $totalItemDiscounted,
            'CA $'. number_format($totalItemValue, 2,'.',''),
            'CA $'. number_format($totalDiscountAmount, 2,'.',''),
            'CA $'. number_format($totalDiscountRefund, 2,'.',''),
            'CA $'. number_format($totalNetDiscount, 2,'.','')
        ];

        /** Discounts By Staff */

        $sheets[] = [
            '',
            '',
            '',
            '',
            '',
            ''
        ];
        $sheets[] = [
            'Discounts By Staff'
        ];
        $sheets[] = [
            '',
            '',
            '',
            '',
            '',
            ''
        ];
        $sheets[] = [
            'Staff Name',
            'Items Discounted',
            'Items Value',
            'Discount Amount',
            'Discount Refunds',
            'Net Discounts'
        ];

        $totalItemDiscounted = 0; 
        $totalItemValue = 0;
        $totalDiscountAmount = 0;
        $totalDiscountRefund = 0;
        $totalNetDiscount = 0;

        foreach($this->getDiscountSummaryByStaff['discountComplete'] as $getDiscountSummaryKey => $getDiscountSummaryValue){

            $totalItemDiscounted += ($getDiscountSummaryValue['count'] - (!empty($this->getDiscountSummaryByStaff[$getDiscountSummaryKey]['discountRefund']) ? (($this->getDiscountSummaryByStaff[$getDiscountSummaryKey]['discountRefund']['staff_id'] == $getDiscountSummaryValue['staff_id']) ? $this->getDiscountSummaryByStaff[$getDiscountSummaryKey]['discountRefund']['refund_count'] : 0 ) : 0) );

            $totalItemValue += $getDiscountSummaryValue['og_price_sum'] - ( !empty($this->getDiscountSummaryByStaff[$getDiscountSummaryKey]['discountRefund']) ? ( ( $this->getDiscountSummaryByStaff[$getDiscountSummaryKey]['discountRefund']['staff_id'] ==  $getDiscountSummaryValue['staff_id']) ? $this->getDiscountSummaryByStaff[$getDiscountSummaryKey]['discountRefund']['refund_og_price_sum'] : 0 ) : 0);

            $totalDiscountAmount += $getDiscountSummaryValue['discount_price'];

            $totalDiscountRefund += ( !empty($this->getDiscountSummaryByStaff[$getDiscountSummaryKey]['discountRefund']) ? ( ( $this->getDiscountSummaryByStaff[$getDiscountSummaryKey]['discountRefund']['staff_id'] ==  $getDiscountSummaryValue['staff_id']) ? $this->getDiscountSummaryByStaff[$getDiscountSummaryKey]['discountRefund']['refund_discount_price'] : 0 ) : 0);

            $totalNetDiscount += $getDiscountSummaryValue['discount_price'] - ( !empty($this->getDiscountSummaryByStaff[$getDiscountSummaryKey]['discountRefund']) ? ( ( $this->getDiscountSummaryByStaff[$getDiscountSummaryKey]['discountRefund']['staff_id'] ==  $getDiscountSummaryValue['staff_id']) ? $this->getDiscountSummaryByStaff[$getDiscountSummaryKey]['discountRefund']['refund_discount_price'] : 0 ) : 0);

            $sheets[] = [
                $getDiscountSummaryValue['staff_name'],

                ($getDiscountSummaryValue['count'] - (!empty($this->getDiscountSummaryByStaff[$getDiscountSummaryKey]['discountRefund']) ? (($this->getDiscountSummaryByStaff[$getDiscountSummaryKey]['discountRefund']['staff_id'] == $getDiscountSummaryValue['staff_id']) ? $this->getDiscountSummaryByStaff[$getDiscountSummaryKey]['discountRefund']['refund_count'] : 0 ) : 0) ),

                'CA $'. number_format($getDiscountSummaryValue['og_price_sum'] - ( !empty($this->getDiscountSummaryByStaff[$getDiscountSummaryKey]['discountRefund']) ? ( ( $this->getDiscountSummaryByStaff[$getDiscountSummaryKey]['discountRefund']['staff_id'] ==  $getDiscountSummaryValue['staff_id']) ? $this->getDiscountSummaryByStaff[$getDiscountSummaryKey]['discountRefund']['refund_og_price_sum'] : 0 ) : 0),2,'.',''),

                'CA $'. number_format($getDiscountSummaryValue['discount_price'],2,'.',''),

                'CA $'. number_format(( !empty($this->getDiscountSummaryByStaff[$getDiscountSummaryKey]['discountRefund']) ? ( ( $this->getDiscountSummaryByStaff[$getDiscountSummaryKey]['discountRefund']['staff_id'] ==  $getDiscountSummaryValue['staff_id']) ? $this->getDiscountSummaryByStaff[$getDiscountSummaryKey]['discountRefund']['refund_discount_price'] : 0 ) : 0),2,'.',''),

                'CA $'. number_format($getDiscountSummaryValue['discount_price'] - ( !empty($this->getDiscountSummaryByStaff[$getDiscountSummaryKey]['discountRefund']) ? ( ( $this->getDiscountSummaryByStaff[$getDiscountSummaryKey]['discountRefund']['staff_id'] ==  $getDiscountSummaryValue['staff_id']) ? $this->getDiscountSummaryByStaff[$getDiscountSummaryKey]['discountRefund']['refund_discount_price'] : 0 ) : 0),2,'.','')
            ];
        }
        $sheets[] = [
            'Total',
            $totalItemDiscounted,
            'CA $'. number_format($totalItemValue, 2,'.',''),
            'CA $'. number_format($totalDiscountAmount, 2,'.',''),
            'CA $'. number_format($totalDiscountRefund, 2,'.',''),
            'CA $'. number_format($totalNetDiscount, 2,'.','')
        ];

        /** Discounts By Type */

        $sheets[] = [
            '',
            '',
            '',
            '',
            '',
            ''
        ];
        $sheets[] = [
            'Discounts By Type'
        ];
        $sheets[] = [
            '',
            '',
            '',
            '',
            '',
            ''
        ];
        $sheets[] = [
            'Type',
            'Items Discounted',
            'Items Value',
            'Discount Amount',
            'Discount Refunds',
            'Net Discounts'
        ];

        $totalItemDiscounted = 0; 
        $totalItemValue = 0;
        $totalDiscountAmount = 0;
        $totalDiscountRefund = 0;
        $totalNetDiscount = 0;

        foreach($this->getDiscountSummaryByType['discountComplete'] as $getDiscountSummaryKey => $getDiscountSummaryValue){

            $totalItemDiscounted += ($getDiscountSummaryValue['count'] - (!empty($this->getDiscountSummaryByType[$getDiscountSummaryKey]['discountRefund']) ? (($this->getDiscountSummaryByType[$getDiscountSummaryKey]['discountRefund']['type'] == $getDiscountSummaryValue['type']) ? $this->getDiscountSummaryByType[$getDiscountSummaryKey]['discountRefund']['refund_count'] : 0 ) : 0) );

            $totalItemValue += $getDiscountSummaryValue['og_price_sum'] - ( !empty($this->getDiscountSummaryByType[$getDiscountSummaryKey]['discountRefund']) ? ( ( $this->getDiscountSummaryByType[$getDiscountSummaryKey]['discountRefund']['type'] ==  $getDiscountSummaryValue['type']) ? $this->getDiscountSummaryByType[$getDiscountSummaryKey]['discountRefund']['refund_og_price_sum'] : 0 ) : 0);

            $totalDiscountAmount += $getDiscountSummaryValue['discount_price'];

            $totalDiscountRefund += ( !empty($this->getDiscountSummaryByType[$getDiscountSummaryKey]['discountRefund']) ? ( ( $this->getDiscountSummaryByType[$getDiscountSummaryKey]['discountRefund']['type'] ==  $getDiscountSummaryValue['type']) ? $this->getDiscountSummaryByType[$getDiscountSummaryKey]['discountRefund']['refund_discount_price'] : 0 ) : 0);

            $totalNetDiscount += $getDiscountSummaryValue['discount_price'] - ( !empty($this->getDiscountSummaryByType[$getDiscountSummaryKey]['discountRefund']) ? ( ( $this->getDiscountSummaryByType[$getDiscountSummaryKey]['discountRefund']['type'] ==  $getDiscountSummaryValue['type']) ? $this->getDiscountSummaryByType[$getDiscountSummaryKey]['discountRefund']['refund_discount_price'] : 0 ) : 0);

            $sheets[] = [
                $getDiscountSummaryValue['type'],

                ($getDiscountSummaryValue['count'] - (!empty($this->getDiscountSummaryByType[$getDiscountSummaryKey]['discountRefund']) ? (($this->getDiscountSummaryByType[$getDiscountSummaryKey]['discountRefund']['type'] == $getDiscountSummaryValue['type']) ? $this->getDiscountSummaryByType[$getDiscountSummaryKey]['discountRefund']['refund_count'] : 0 ) : 0) ),

                'CA $'. number_format($getDiscountSummaryValue['og_price_sum'] - ( !empty($this->getDiscountSummaryByType[$getDiscountSummaryKey]['discountRefund']) ? ( ( $this->getDiscountSummaryByType[$getDiscountSummaryKey]['discountRefund']['type'] ==  $getDiscountSummaryValue['type']) ? $this->getDiscountSummaryByType[$getDiscountSummaryKey]['discountRefund']['refund_og_price_sum'] : 0 ) : 0),2,'.',''),

                'CA $'. number_format($getDiscountSummaryValue['discount_price'],2,'.',''),

                'CA $'. number_format(( !empty($this->getDiscountSummaryByType[$getDiscountSummaryKey]['discountRefund']) ? ( ( $this->getDiscountSummaryByType[$getDiscountSummaryKey]['discountRefund']['type'] ==  $getDiscountSummaryValue['type']) ? $this->getDiscountSummaryByType[$getDiscountSummaryKey]['discountRefund']['refund_discount_price'] : 0 ) : 0),2,'.',''),

                'CA $'. number_format($getDiscountSummaryValue['discount_price'] - ( !empty($this->getDiscountSummaryByType[$getDiscountSummaryKey]['discountRefund']) ? ( ( $this->getDiscountSummaryByType[$getDiscountSummaryKey]['discountRefund']['type'] ==  $getDiscountSummaryValue['type']) ? $this->getDiscountSummaryByType[$getDiscountSummaryKey]['discountRefund']['refund_discount_price'] : 0 ) : 0),2,'.','')
            ];
        }
        $sheets[] = [
            'Total',
            $totalItemDiscounted,
            'CA $'. number_format($totalItemValue, 2,'.',''),
            'CA $'. number_format($totalDiscountAmount, 2,'.',''),
            'CA $'. number_format($totalDiscountRefund, 2,'.',''),
            'CA $'. number_format($totalNetDiscount, 2,'.','')
        ];

        return collect($sheets);
    }

    public function headings(): array
    {
        return ['Discounts'];
    }
}