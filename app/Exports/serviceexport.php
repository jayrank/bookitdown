<?php

namespace App\Exports;

use App\Models\Services;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Auth;

class serviceexport implements FromCollection, WithMapping, WithHeadings , ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $service = Services::where('is_deleted',0)->where('user_id', Auth::id())->with('cat','servicePrice')->get();

        return collect($service);
    }

    public function map($service): array
    {
        if($service->available_for==0){ $ava = 'everyone'; }elseif ($service->available_for==1) {$ava = 'females';}else {$ava='males';}
        return [
            'Service Name' => $service->service_name,
            'Retail Price' => $service->servicePrice[0]->lowest_price,
            'Special Price' => $service->servicePrice[0]->special_price,
            'Duration' => $service->servicePrice[0]->duration,
            'Tax' => $service->tax_id,
            'Description' => $service->service_description,
            'Treatment Type' => $service->treatment_type,
            'Online Booking' => $service->is_online_booking==0 ? 'not enable' : 'enable',
            'Available For' => $ava,
            'Voucher Sales' => $service->is_voucher_enable==0 ? 'not enable' : 'enable',
            'Commissions' => $service->is_staff_commision_enable==0 ? 'not enable' : 'enable',
        ];
    }

    public function headings(): array
    {
        return [
            'Service Name',
            'Retail Price',
            'Special Price',
            'Duration',
            'Tax',
            'Description',
            'Treatment Type',
            'Online Booking',
            'Available For',
            'Voucher Sales',
            'Commissions',
        ];
    }
}
