<?php

namespace App\Exports;

use App\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\ServicesPrice;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Auth;


class ServicesExport implements FromCollection, WithHeadings , ShouldAutoSize, WithMapping, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */


    function __construct() {

    }
    public function collection()
    {
        
        $CurrentUser = auth::user();
        $is_admin = $CurrentUser->is_admin;
        
        if($is_admin == 1){
            $CurrentStaff = Staff::find(Auth::id());
            $AdminId = $CurrentStaff->user_id;
            $UserId  = Auth::id();
        } else {
            $AdminId = Auth::id();
            $UserId  = Auth::id();
        }
        
        $ServicesPrice = ServicesPrice::where('services_price.user_id', $AdminId)
                        ->where('services_price.deleted_at', NULL)
                        ->leftjoin('services', 'services.id', 'services_price.service_id')
                        ->leftjoin('service_category', 'service_category.id', 'services.service_category')
                        ->leftjoin('taxes', 'taxes.id', 'services.tax_id', 'taxes.id')
                        ->select('services.service_name', 'services_price.price', 'services_price.special_price', 'services_price.duration', 'services.is_extra_time', 'services.extra_time_duration', 'taxes.tax_name', 'services.service_description', 'service_category.category_title', 'services.treatment_type', 'services.is_online_booking', 'services.available_for', 'services.is_voucher_enable', 'services.is_staff_commision_enable', 'services.id')
                        ->get();
        return collect($ServicesPrice);
    }

    public function map($ServicesPrice): array
    {
        if($ServicesPrice->available_for==0){ $ava = 'Everyone'; }elseif ($ServicesPrice->available_for==1) {$ava = 'Females';}else {$ava='Males';}
        return [
            'Service Name' => $ServicesPrice->service_name,
            'Retail Price' => $ServicesPrice->price,
            'Special Price' => $ServicesPrice->special_price,
            'Duration' => $ServicesPrice->duration,
            'Extra Time' => !empty($ServicesPrice->is_extra_time) ? $ServicesPrice->extra_time_duration : '',
            'Tax' => $ServicesPrice->tax_name,
            'Description' => $ServicesPrice->service_description,
            'Category Name' => $ServicesPrice->category_title,
            'Treatment Type' => $ServicesPrice->treatment_type,
            'Online Booking' => $ServicesPrice->is_online_booking==0 ? 'Disabled' : 'Enabled',
            'Available For' => $ava,
            'Voucher Sales' => $ServicesPrice->is_voucher_enable==0 ? 'Disabled' : 'Enabled',
            'Commissions' => $ServicesPrice->is_staff_commision_enable==0 ? '' : 'Enabled',
            'Service ID' => $ServicesPrice->id,
        ];
    }

    public function headings(): array
    {
        return ['Service Name', 'Retail Price', 'Special Price', 'Duration', 'Extra Time', 'Tax','Description','Category Name','Treatment Type','Online Booking', 'Available For', 'Voucher Sales', 'Commissions', 'Service ID'];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:N1')->getFont()->setSize(12);
        $sheet->getStyle('A1:N1')->getFont()->setBold(true);
    }
}
