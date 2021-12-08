<?php

namespace App\Exports;

use App\Models\InventoryOrders;
use App\Models\Inventory_supplier;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Auth;

class orderinfoexport implements FromCollection, WithMapping, WithHeadings , ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function collection()
    {
        $InventoryOrders = InventoryOrders::select('id','created_at','supplier_id','order_status','order_total')->where('user_id', Auth::id())->orderBy('id', 'desc')->get();

        return $InventoryOrders;
    }

    public function map($orders): array
    {  
		$OrderSupplier = Inventory_supplier::getSupplierbyID($orders->supplier_id);
        if($orders->order_status == 1){
            $order_status = 'Ordered';
        } else if($orders->order_status == 2){
            $order_status = 'Received';
        } else if($orders->order_status == 3){
            $order_status = 'Cancelled';
        } else {
            $order_status = 'N/A';
        }
        return [
            'id'           => $orders->id,
            'created_at'   => date("d M Y",strtotime($orders->created_at)),
            'supplier_name'  => $OrderSupplier->supplier_name,
            'order_status' => $order_status,
            'order_total'  => 'CA $'.$orders->order_total
        ];
    }

    public function headings(): array
    {
        return ['Order No.', 'Created Date', 'Supplier', 'Status','Total Cost',];
    }
}
