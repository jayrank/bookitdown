<?php

namespace App\Exports;

use App\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\SoldVoucher;
use App\Models\Invoice;
use App\Models\Clients;
use App\Models\StaffTip;
use Auth;


class vouchersexport implements FromCollection, WithHeadings , ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $voucherStatus;

    function __construct($voucherStatus) {
        $this->voucherStatus = $voucherStatus;
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
        
        $data_arr = array();
        
        $voucher_status = $this->voucherStatus ? $this->voucherStatus : '';
        
        $VoucherSold = SoldVoucher::select('sold_voucher.*','locations.location_name','locations.location_address','locations.location_image','vouchers.title','vouchers.name','invoice.invoice_status','invoice.invoice_prefix','invoice.invoice_id as invid')->leftJoin('vouchers', 'vouchers.id', '=', 'sold_voucher.voucher_id')->leftJoin('locations', 'locations.id', '=', 'sold_voucher.location_id')->leftJoin('invoice', 'invoice.id', '=', 'sold_voucher.invoice_id')->where('sold_voucher.user_id', $AdminId)->orderBy('sold_voucher.id', 'desc')->get()->toArray();
        
        $soldVoucher = array();	
            
        if(!empty($VoucherSold)){
            foreach($VoucherSold as $VoucherSoldData){
                
                // get client name
                if($VoucherSoldData['client_id'] == 0){
                    $ClientName = 'Walk-In';
                } else {
                    $ClientInfo = Clients::getClientbyID($VoucherSoldData['client_id']);	
                    if(!empty($ClientInfo)){
                        $ClientName = $ClientInfo->firstname.' '.$ClientInfo->lastname;	
                    } else {
                        $ClientName = 'Walk-In';
                    }
                }
                
                $RemainingAmount = ($VoucherSoldData['total_value'] - $VoucherSoldData['redeemed']);
                
                $CurrentDate = date("Y-m-d H:i:s");
                $StartDate   = date("Y-m-d H:i:s",strtotime($VoucherSoldData['start_date']));
                $EndDate     = date("Y-m-d H:i:s",strtotime($VoucherSoldData['end_date']));
                
                $IsExpired = 0;
                if($CurrentDate > $EndDate){
                    $IsExpired = 1;
                }
                
                // $tempVoucher['id']               = ($VoucherSoldData['id']) ? $VoucherSoldData['id'] : 0;
                $tempVoucher['issue_date']       = ($VoucherSoldData['start_date']) ? date("d M Y, h:ia",strtotime($VoucherSoldData['start_date'])) : 'N/A';
                $tempVoucher['expiry_date']      = ($VoucherSoldData['end_date']) ? date("d M Y",strtotime($VoucherSoldData['end_date'])) : 'N/A';
                // $tempVoucher['invoice_id']       = ($VoucherSoldData['invoice_id']) ? $VoucherSoldData['invoice_id'] : 0;
                $tempVoucher['invoice_no']       = $VoucherSoldData['invoice_prefix']." ".$VoucherSoldData['invid'];
                $tempVoucher['client_id']        = ($VoucherSoldData['client_id']) ? $VoucherSoldData['client_id'] : 0;
                $tempVoucher['client_name']      = $ClientName;

                $VoucherType = 'N/A';
                if($VoucherSoldData['voucher_type'] == 0) {
                    $VoucherType = 'GIFT VOUCHER';
                } elseif($VoucherSoldData['voucher_type'] == 1) {
                    $VoucherType = 'SERVICE VOUCHER';
                }
                $tempVoucher['voucher_type']     = $VoucherType;

                $invoice_status = 'N/A';
                if($VoucherSoldData['invoice_status'] == 0) {
                    $invoice_status = 'UNPAID';
                } else if($VoucherSoldData['invoice_status'] == 1) {
                    $invoice_status = 'VALID';
                } else if($VoucherSoldData['invoice_status'] == 2) {
                    $invoice_status = 'REFUND';
                } else if($VoucherSoldData['invoice_status'] == 3) {
                    $invoice_status = 'VOID';
                }
                
                if($IsExpired == 1){
                    $invoice_status = 'EXPIRED';
                }
                
                if($RemainingAmount == 0){
                    $invoice_status = 'REDEEMED';
                }
                
                $tempVoucher['invoice_status'] = $invoice_status;
                $tempVoucher['voucher_code']     = ($VoucherSoldData['voucher_code']) ? $VoucherSoldData['voucher_code'] : 'N/A';

                $tempVoucher['voucher_total']    = ($VoucherSoldData['total_value']) ? $VoucherSoldData['total_value'] : '0';
                $tempVoucher['redeemed_amount']  = ($VoucherSoldData['redeemed']) ? $VoucherSoldData['redeemed'] : '0';
                $tempVoucher['remaining_amount'] = $RemainingAmount;
                
                
                if($voucher_status != ''){
                    if($voucher_status == 'unpaid' && $invoice_status == 'UNPAID'){
                        array_push($soldVoucher,$tempVoucher);
                    } else if($voucher_status == 'valid' && $invoice_status == 'VALID'){
                        array_push($soldVoucher,$tempVoucher);
                    } else if($voucher_status == 'expired' && $invoice_status == 'EXPIRED'){
                        array_push($soldVoucher,$tempVoucher);
                    } else if($voucher_status == 'redeemed' && $invoice_status == 'REDEEMED'){
                        array_push($soldVoucher,$tempVoucher);
                    } else if($voucher_status == 'refundedinvoice' && $invoice_status == 'REFUND'){
                        array_push($soldVoucher,$tempVoucher);
                    } else if($voucher_status == 'voidedinvoice' && $invoice_status == 'VOID'){
                        array_push($soldVoucher,$tempVoucher);
                    } else if($voucher_status == 'all') {
                        array_push($soldVoucher,$tempVoucher);
                    }
                } else if($voucher_status == '') {
                    array_push($soldVoucher,$tempVoucher);	
                }
            }
        }
        return collect($soldVoucher);
    }

    public function headings(): array
    {
        return ['Issue date', 'Expiry date', 'Invoice no.', 'Client ID', 'Client', 'Type','Status','Code','Total','Redeemed','Remaining',];
    }
}
