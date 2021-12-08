<?php

namespace App\Exports;

use App\Models\InventoryProducts;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Auth;

class productexport implements FromCollection, WithMapping, WithHeadings , ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $InventoryProducts = InventoryProducts::select('id','product_name','barcode','retail_price','special_rate','initial_stock','enable_stock_control','updated_at')->where('is_deleted','=','0')->where('user_id', Auth::id())->orderBy('id', 'desc')->get();

        return $InventoryProducts;
    }

    public function map($product): array
    {   
        return [
            'product_name'         => $product->product_name,
            'barcode'              => $product->barcode,
            'retail_price'         => $product->retail_price,
            'special_rate'         => $product->special_rate,
            'initial_stock'        => $product->initial_stock,
            'updated_at'           => date('d M Y, H:ma',strtotime($product->updated_at)) 
        ];
    }

    public function headings(): array
    {
        return ['Product name', 'Barcode', 'Retail price', 'Special rate','Stock On Hand', 'Updated',];
    }
}
