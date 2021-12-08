<?php

namespace App\Exports;

use App\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\Staff;
use App\Models\Invoice;
use App\Models\Clients;
use App\Models\StaffTip;
use Auth;
use DB;


class invoicesexport implements FromCollection, WithHeadings , ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $location;
    protected $start_date;
    protected $end_date;

    function __construct($location, $start_date, $end_date) {
        $this->location = $location;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
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
        
        $start_date  = $this->start_date;
        $end_date    = $this->end_date;
        $location_id = $this->location;
        
        if($start_date != '' && $end_date != ''){
            if(!empty($location_id)){
                $locationWhere = array('invoice.location_id' => $location_id);
            } else {
                $locationWhere = array();
            }
            
            $Invoice = Invoice::select('invoice.*','locations.location_name')->join('locations','locations.id','=','invoice.location_id')->where('invoice.user_id', $AdminId)->where($locationWhere)->whereDate('invoice.created_at','>=',$start_date)->whereDate('invoice.created_at','<=',$end_date)->orderBy('invoice.id', 'desc')->get()->toArray();
        } else {
            if(!empty($location_id)){
                $locationWhere = array('invoice.location_id' => $location_id);
            } else {
                $locationWhere = array();
            }
            
            $Invoice = Invoice::select('invoice.*','locations.location_name')->join('locations','locations.id','=','invoice.location_id')->where('invoice.user_id', $AdminId)->where($locationWhere)->orderBy('invoice.id', 'desc')->get()->toArray();
        }
        
        $invoices = array();	
        if(!empty($Invoice)){
            foreach($Invoice as $InvoiceData){
                
                // get client name
                if($InvoiceData['client_id'] == 0){
                    $ClientName = 'Walk-In';
                } else {
                    $ClientInfo = Clients::getClientbyID($InvoiceData['client_id']);	
                    if(!empty($ClientInfo)){
                        $ClientName = $ClientInfo->firstname.' '.$ClientInfo->lastname;	
                    } else {
                        $ClientName = 'Walk-In';
                    }
                }
                
                $TotalStaffTip = StaffTip::select(DB::raw('SUM(tip_amount) as total_tip'))->where('staff_tip.inovice_id', $InvoiceData['id'])->get()->toArray();
                
                // $tempInvoice['id']    		   = $InvoiceData['id'];
                $tempInvoice['invoice_id']     = $InvoiceData['invoice_prefix']." ".$InvoiceData['invoice_id'];
                // $tempInvoice['client_id']      = $InvoiceData['client_id'];
                $tempInvoice['client_name']    = $ClientName;
                $tempInvoice['invoice_status'] = $InvoiceData['invoice_status'];
                $tempInvoice['invoice_date']   = ($InvoiceData['created_at']) ? date("d M Y, h:ia",strtotime($InvoiceData['created_at'])) : 'N/A';
                $tempInvoice['due_date']   = '';
                $tempInvoice['billing_name']   = ($InvoiceData['location_name']) ? $InvoiceData['location_name'] : 'N/A';
                $tempInvoice['location_name']  = ($InvoiceData['location_name']) ? $InvoiceData['location_name'] : 'N/A';
                $tempInvoice['tips']           = (!empty($TotalStaffTip) && $TotalStaffTip[0]['total_tip'] > 0) ? $TotalStaffTip[0]['total_tip'] : 0;
                $tempInvoice['gross_total']    = ($InvoiceData['inovice_final_total']) ? $InvoiceData['inovice_final_total'] : '0';
                array_push($invoices,$tempInvoice);
            }
        }
        return collect($invoices);
    }

    public function headings(): array
    {
        return ['Invoice#', 'Client', 'Status', 'Invoice date', 'Due date', 'Billing name','Location','Tips','Gross total',];
    }
}
