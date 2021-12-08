<?php

namespace App\Exports;

use App\Models\Staff;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Auth;

class staffexport implements FromCollection, WithMapping, WithHeadings , ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
	public function styles(Worksheet $sheet)
    {
		$sheet->getStyle('1')->getFont()->setBold(true);
    }
	
    public function collection()
    {
        $staff = Staff::select('staff.id','staff.order_id','staff.employment_start_date','staff.employment_end_date','staff.staff_notes','staff.service_commission','staff.product_commission','staff.voucher_commission', 'users.first_name', 'users.last_name', 'users.country_code','users.phone_number', 'users.email', 'staff.appointment_color', 'staff.is_appointment_booked', 'roles.name')->join('users', 'staff.staff_user_id', '=', 'users.id')->leftJoin('roles', 'roles.id', '=', 'staff.user_permission')->where('staff.user_id', Auth::id())->orderBy('staff.order_id','ASC')->get();

        return collect($staff);
    }

    public function map($staff): array
    {
        if($staff->is_appointment_booked == 1) {
            $can_appointment = "Enabled";
            $color_appointment = $staff->appointment_color;
        } else {
            $can_appointment = "Disabled";	
            $color_appointment = "";
        }
        
        $phone = "";
        
        if($staff->phone_number != '') {
            $phone = '+'.$staff->country_code.' '.$staff->phone_number;
        }	
        
        return [
            'first_name'        => ($staff->first_name) ? $staff->first_name : '',
			'last_name'         => ($staff->last_name) ? $staff->last_name : '',
			'mobile_no'         =>  ($phone) ? $phone : '',
			'email'             => ($staff->email) ? $staff->email : '',
			'is_appointment_en' => ($can_appointment) ? $can_appointment : '',
			'user_permission'   => ($staff->name) ? $staff->name : '',
			'start_date'        => ($staff->employment_start_date) ? date("d-m-Y",strtotime($staff->employment_start_date)) : '',
			'end_date'          => ($staff->employment_end_date) ? date("d-m-Y",strtotime($staff->employment_end_date)) : '',
			'notes'             => ($staff->staff_notes) ? $staff->staff_notes : '',
			'service_commision' => ($staff->service_commission) ? $staff->service_commission : 0,
			'product_commision' => ($staff->product_commission) ? $staff->product_commission : 0,
			'voucher_commision' => ($staff->voucher_commission) ? $staff->voucher_commission : 0
        ];
    }

    public function headings(): array
    {
        return ['First Name','Last Name','Mobile Number','Email','Appointments','User Permission','Start Date','End Date','Notes','Service Commission','Product Commission','Voucher Commission'];
    }
}
