<?php

namespace App\Exports;

use App\Models\InventoryOrderLogs;
use App\Models\Location;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Auth;

class prstockexport implements FromCollection, WithMapping, WithHeadings , ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $id;

    function __construct($id) {
        $this->id = $id;
    }

    public function collection()
    {
        $InventoryOrderLogs = InventoryOrderLogs::select('inventory_order_logs.*','users.first_name','users.last_name')->join('users','users.id','=','inventory_order_logs.received_by')->where('item_id',$this->id)->orderBy('inventory_order_logs.id', 'desc')->get();	

        return $InventoryOrderLogs;
    }

    public function map($history): array
    {  
           
        if($history->location_id != 0){
            $getLocation = Location::getLocationByID($history->location_id);	
            $LocationName = $getLocation->location_name;
        } else {
            $getLocation = Location::getLocationByID($history->location_id);
            $LocationName = '';
        }
            
        return [
            'created_at' => ($history->created_at) ? date("d M Y, H:ia",strtotime($history->created_at)) : '',
            'received_by'  => $history->first_name.' '.$history->last_name,
            'location_name'  => $LocationName,
            // 'order_id'       => $history->order_id,
            // 'invoice_id'     => $history->invoice_id,
            // 'order_type'     => $history->order_type,
            'order_action'   => $history->order_action,
            'qty_adjusted'   => $history->qty_adjusted,
            'cost_price'     => 'CA $'.$history->cost_price,
            'stock_on_hand'  => ($history->enable_stock_control == 1) ? $history->stock_on_hand : 'Unlimited',
            // 'is_void_invoice'=> $history->is_void_invoice
        ];
    }

    public function headings(): array
    {
        return ['Time & Date', 'Staff', 'Location', 'Action','Qty. Adjusted', 'Cost Price','Stock On Hand',];
    }
}
