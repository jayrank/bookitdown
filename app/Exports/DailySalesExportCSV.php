<?php

namespace App\Exports;

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

class DailySalesExportCSV implements  ShouldAutoSize, FromCollection, WithHeadings
{
    private $start_date;
    private $end_date;
    private $location_id;

    public function __construct($start_date, $end_date, $location_id) 
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->location_id = $location_id;
    }
    
    public function collection()
    {
        $CurrentUser = auth::user();
                
        $is_admin = $CurrentUser->is_admin;
        
        if($is_admin == 1){
            $CurrentStaff = Staff::select('user_id')->where('staff_user_id',$CurrentUser->id)->first();
            $AdminId = $CurrentStaff->user_id;
            $UserId  = Auth::id();
        } else {
            $AdminId = Auth::id();
            $UserId  = Auth::id();
        }

        $whereArray = [];
        if($this->start_date != '') {
            $whereArray[] = [
                DB::raw('DATE(invoice.created_at)'),'>=',$this->start_date
            ];
        }
        if($this->end_date != '') {
            $whereArray[] = [
                DB::raw('DATE(invoice.created_at)'),'<=',$this->end_date
            ];
        }
        if($this->location_id != ''){
            $whereArray[] = [
                'invoice.location_id', '=', $this->location_id
            ];
        }
            
        $ServiceTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_services'),DB::raw('SUM(invoice_items.item_price) as total_item_price'))->join('invoice','invoice.id','=','invoice_items.invoice_id')->where(['invoice.user_id'=>$AdminId])->where('invoice_items.item_type','services')->where('invoice.invoice_status','!=','2')->where($whereArray)->orderBy('invoice_items.id', 'ASC')->get()->toArray();
        
        $RefundServiceTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_services'),DB::raw('SUM(invoice_items.item_price) as total_item_price'))->join('invoice','invoice.id','=','invoice_items.invoice_id')->where(['invoice.user_id'=>$AdminId])->where('invoice_items.item_type','services')->where('invoice.invoice_status','2')->where($whereArray)->orderBy('invoice_items.id', 'ASC')->get()->toArray();   
        
        $PrductTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_products'),DB::raw('SUM(invoice_items.item_price) as total_item_price'))->join('invoice','invoice.id','=','invoice_items.invoice_id')->where(['invoice.user_id'=>$AdminId])->where('invoice_items.item_type','product')->where('invoice.invoice_status','!=','2')->where($whereArray)->orderBy('invoice_items.id', 'ASC')->get()->toArray();
        
        $PrductRefundTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_products'),DB::raw('SUM(invoice_items.item_price) as total_item_price'))->join('invoice','invoice.id','=','invoice_items.invoice_id')->where(['invoice.user_id'=>$AdminId])->where('invoice_items.item_type','product')->where('invoice.invoice_status','2')->where($whereArray)->orderBy('invoice_items.id', 'ASC')->get()->toArray();

        $VoucherTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_vouchers'),DB::raw('SUM(invoice_items.item_price) as total_item_price'))->join('invoice','invoice.id','=','invoice_items.invoice_id')->where(['invoice.user_id'=>$AdminId])->where('invoice_items.item_type','voucher')->where('invoice.invoice_status','!=','2')->where($whereArray)->orderBy('invoice_items.id', 'ASC')->get()->toArray();
        
        $VoucherRefundTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_vouchers'),DB::raw('SUM(invoice_items.item_price) as total_item_price'))->join('invoice','invoice.id','=','invoice_items.invoice_id')->where(['invoice.user_id'=>$AdminId])->where('invoice_items.item_type','voucher')->where('invoice.invoice_status','2')->where($whereArray)->orderBy('invoice_items.id', 'ASC')->get()->toArray();

        $PaidplaTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_paidplan'),DB::raw('SUM(invoice_items.item_price) as total_item_price'))->join('invoice','invoice.id','=','invoice_items.invoice_id')->where(['invoice.user_id'=>$AdminId])->where('invoice_items.item_type','paidplan')->where('invoice.invoice_status','!=','2')->where($whereArray)->orderBy('invoice_items.id', 'ASC')->get()->toArray();

        $PaidplaRefundTotal = InvoiceItems::select(DB::raw('COUNT(invoice_items.id) as total_paidplan'),DB::raw('SUM(invoice_items.item_price) as total_item_price'))->join('invoice','invoice.id','=','invoice_items.invoice_id')->where(['invoice.user_id'=>$AdminId])->where('invoice_items.item_type','paidplan')->where('invoice.invoice_status','2')->where($whereArray)->orderBy('invoice_items.id', 'ASC')->get()->toArray();   
        
        $TotalAllThing  = 0;
        $TotalAllAmount = 0;
        
        $TotalServices       = ($ServiceTotal[0]['total_services']) ? $ServiceTotal[0]['total_services'] : 0;
        $TotalServicesAmount = ($ServiceTotal[0]['total_item_price']) ? $ServiceTotal[0]['total_item_price'] : 0;
        
        $TotalRefundServices       = ($RefundServiceTotal[0]['total_services']) ? $RefundServiceTotal[0]['total_services'] : 0;
        $TotalRefundServicesAmount = ($RefundServiceTotal[0]['total_item_price']) ? $RefundServiceTotal[0]['total_item_price'] : 0;
        
        $TotalServicesAmount = $TotalServicesAmount - $TotalRefundServicesAmount;
        
        $TotalProducts       = ($PrductTotal[0]['total_products']) ? $PrductTotal[0]['total_products'] : 0;
        $TotalProductsAmount = ($PrductTotal[0]['total_item_price']) ? $PrductTotal[0]['total_item_price'] : 0;
        
        $TotalRefundProducts       = ($PrductRefundTotal[0]['total_products']) ? $PrductRefundTotal[0]['total_products'] : 0;
        $TotalRefundProductsAmount = ($PrductRefundTotal[0]['total_item_price']) ? $PrductRefundTotal[0]['total_item_price'] : 0;
        
        $TotalProductsAmount = $TotalProductsAmount - $TotalRefundProductsAmount;
        
        $TotalVouchers       = ($VoucherTotal[0]['total_vouchers']) ? $VoucherTotal[0]['total_vouchers'] : 0;
        $TotalVouchersAmount = ($VoucherTotal[0]['total_item_price']) ? $VoucherTotal[0]['total_item_price'] : 0;
        
        $TotalRefundVouchers       = ($VoucherRefundTotal[0]['total_vouchers']) ? $VoucherRefundTotal[0]['total_vouchers'] : 0;
        $TotalRefundVouchersAmount = ($VoucherRefundTotal[0]['total_item_price']) ? $VoucherRefundTotal[0]['total_item_price'] : 0;
        
        $TotalVouchersAmount = $TotalVouchersAmount - $TotalRefundVouchersAmount;
        
        $TotalPaidplan       = ($PaidplaTotal[0]['total_paidplan']) ? $PaidplaTotal[0]['total_paidplan'] : 0;
        $TotalPaidplanAmount = ($PaidplaTotal[0]['total_item_price']) ? $PaidplaTotal[0]['total_item_price'] : 0;
        
        $TotalRefundPaidplan       = ($PaidplaRefundTotal[0]['total_paidplan']) ? $PaidplaRefundTotal[0]['total_paidplan'] : 0;
        $TotalRefundPaidplanAmount = ($PaidplaRefundTotal[0]['total_item_price']) ? $PaidplaRefundTotal[0]['total_item_price'] : 0;

        $TotalPaidplanAmount = $TotalPaidplanAmount - $TotalRefundPaidplanAmount;   
        
        $TotalAllThing       = $TotalServices + $TotalProducts + $TotalVouchers + $TotalPaidplan;
        $TotalAllRefundThing = $TotalRefundServices + $TotalRefundProducts + $TotalRefundVouchers + $TotalRefundPaidplan;
        $TotalAllAmount      = $TotalServicesAmount + $TotalProductsAmount + $TotalVouchersAmount + $TotalPaidplanAmount;
        

        $sheets = [
            [
                'Services',
                ($TotalServices) ? $TotalServices : '0',
                ($TotalRefundServices) ? $TotalRefundServices : '0',
                'CA $'.($TotalServicesAmount) ? number_format($TotalServicesAmount,2) : '0'
            ],
            [
                'Products',
                ($TotalProducts) ? $TotalProducts : '0',
                ($TotalRefundProducts) ? $TotalRefundProducts : '0',
                'CA $'.($TotalProductsAmount) ? number_format($TotalProductsAmount,2) : '0'
            ],
            [
                'Vouchers',
                ($TotalVouchers) ? $TotalVouchers : '0',
                ($TotalRefundVouchers) ? $TotalRefundVouchers : '0',
                'CA $'.($TotalVouchersAmount) ? number_format($TotalVouchersAmount,2) : '0'
            ],
            [
                'Paid Plans',
                ($TotalPaidplan) ? $TotalPaidplan : '0',
                ($TotalRefundPaidplan) ? $TotalRefundPaidplan : '0',
                'CA $'.($TotalPaidplanAmount) ? number_format($TotalPaidplanAmount,2) : '0'
            ],
            [
                'Total',
                ($TotalAllThing) ? $TotalAllThing : '0',
                ($TotalAllRefundThing) ? $TotalAllRefundThing : '0',
                'CA $'.number_format($TotalAllAmount,2)
            ]
        ];


        /*
        * Cash Movement Summary Export
        */
        // get payment methods
        $paymentTypes = paymentType::select('id','payment_type')->where('user_id', $AdminId)->orderBy('order_id', 'asc')->get()->toArray();

        $paymentByTotal = array();
        $totalVoucherRedemption = 0;
        $totalRefundVoucherRedemption = 0;
        $totalTips = 0;
        $totalRefundedTips = 0;
        
        $totalPaymentCollected = 0;
        $totalPaymentRefunded  = 0;
        
        if(!empty($paymentTypes)) {
            foreach($paymentTypes as $paymentTypeData) {
                // get invoices
                $getSoldInvoice = Invoice::select('invoice.*')->where('invoice.user_id',$AdminId)->where('invoice.invoice_status',1)->where('invoice.payment_id',$paymentTypeData['id'])->where($whereArray)->orderBy('invoice.id','desc')->get()->toArray();
                
                $total_collected   = 0;
                $total_voucher_red = 0;
                
                if(!empty($getSoldInvoice)){
                    foreach($getSoldInvoice as $getSoldInvoiceData){
                        //get applied vouchers 
                        
                        $getAppliedVoucher = InvoiceVoucher::select(DB::raw('SUM(voucher_amount) as total_redemption'))->where('invoice_id', $getSoldInvoiceData['id'])->orderBy('id', 'desc')->get()->toArray();
                        
                        $redemptionValue = 0;
                        if(!empty($getAppliedVoucher)){
                            $redemptionValue = $getAppliedVoucher[0]['total_redemption'];
                            $totalVoucherRedemption = $totalVoucherRedemption + $getAppliedVoucher[0]['total_redemption'];
                            $total_voucher_red = $total_voucher_red + $getAppliedVoucher[0]['total_redemption'];
                        }
                        
                        $invoiceMainTotal = ($getSoldInvoiceData['invoice_total'] - $redemptionValue);
                        $total_collected  = $total_collected + $invoiceMainTotal;
                        
                        $getStaffTips = StaffTip::select(DB::raw('SUM(tip_amount) as total_tip'))->where('inovice_id', $getSoldInvoiceData['id'])->orderBy('id', 'desc')->get()->toArray();
                        if(!empty($getStaffTips)){
                            $totalTips = $totalTips + $getStaffTips[0]['total_tip'];
                        }
                    }
                }
                
                $totalPaymentCollected = $totalPaymentCollected + $total_collected + $total_voucher_red;
                
                // get invoices
                $getRefundInvoice = Invoice::select('invoice.*')->where('invoice.user_id',$AdminId)->where('invoice.invoice_status',2)->where('invoice.payment_id',$paymentTypeData['id'])->where($whereArray)->orderBy('invoice.id','desc')->get()->toArray();
                $total_refunded = 0;
                $total_refunded_voucher = 0;
                
                if(!empty($getRefundInvoice)){
                    foreach($getRefundInvoice as $getRefundInvoiceData){
                        //get applied vouchers 
                        
                        $getAppliedVoucher = InvoiceVoucher::select(DB::raw('SUM(voucher_amount) as total_redemption'))->where('invoice_id', $getRefundInvoiceData['id'])->orderBy('id', 'desc')->get()->toArray();
                        
                        $redemptionValue = 0;
                        if(!empty($getAppliedVoucher)){
                            $redemptionValue = $getAppliedVoucher[0]['total_redemption'];
                            $totalRefundVoucherRedemption = $totalRefundVoucherRedemption + $getAppliedVoucher[0]['total_redemption'];
                            $total_refunded_voucher = $total_refunded_voucher + $getAppliedVoucher[0]['total_redemption'];
                        }
                        
                        $invoiceMainTotal = ($getRefundInvoiceData['invoice_total'] - $redemptionValue);
                        $total_refunded  = $total_refunded + $invoiceMainTotal;
                        
                        $getStaffTips = StaffTip::select(DB::raw('SUM(tip_amount) as total_tip'))->where('inovice_id', $getRefundInvoiceData['id'])->orderBy('id', 'desc')->get()->toArray();
                        if(!empty($getStaffTips)){
                            $totalRefundedTips = $totalRefundedTips + $getStaffTips[0]['total_tip'];
                        }
                    }
                }
                
                $totalPaymentRefunded = $totalPaymentRefunded + $total_refunded + $total_refunded_voucher;
                
                $tempPaymentTotal['payment_method']  = $paymentTypeData['payment_type'];
                $tempPaymentTotal['total_collected'] = $total_collected;
                $tempPaymentTotal['total_refunded']  = $total_refunded;
                array_push($paymentByTotal,$tempPaymentTotal);
            }
        }

        $sheets[] = [
            '',
            '',
            ''
        ];

        $sheets[] = [
            'Payment type',
            'Payments Collected',
            'Refunds Paid'
        ];

        $sheets[] = [
            '',
            '',
            ''
        ];

        if(!empty($paymentByTotal)) {
            foreach($paymentByTotal as $paymentByTotalData) {
                $sheets[] = [
                    $paymentByTotalData['payment_method'],
                    'CA $'.number_format($paymentByTotalData['total_collected'],2),
                    'CA $'.number_format($paymentByTotalData['total_refunded'],2)
                ];
            }
        }

        $sheets[] = [
            'Voucher Redemptions',
            'CA $'.number_format($totalVoucherRedemption,2),
            'CA $'.number_format($totalRefundVoucherRedemption,2)
        ];
        $sheets[] = [
            'Payments collected',
            'CA $'.number_format($totalPaymentCollected,2),
            'CA $'.number_format($totalPaymentRefunded,2)
        ];
        $sheets[] = [
            'Of which tips',
            'CA $'.number_format($totalTips,2),
            'CA $'.number_format($totalRefundedTips,2)
        ];
        /*
        * End Cash Movement Summary Export
        */

        return collect($sheets);
    }

    public function headings(): array
    {
        return ['Item Type', 'Sales Qty', 'Refund Qty', 'Gross Total'];
    }
}
