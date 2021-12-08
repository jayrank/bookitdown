<?php

namespace App\Exports;

use App\Models\Clients;
use Maatwebsite\Excel\Concerns\FromCollection;
// use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Auth;

class clientexport implements FromCollection, WithMapping, WithHeadings , ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
         $clients = Clients::select('id', 'firstname', 'lastname','mo_country_code','mobileno', 'email', 'gender')->where('user_id', Auth::id())->where('is_deleted', '0')->orderBy('id', 'desc')->get();
         return collect($clients);
    }

    public function map($client): array
    {
        return [
            // 'profile' => $client['firstname'],
            'name' => $client['firstname'].' '.$client['lastname'],
            'mobileno' => $client['mo_country_code'].' '.$client['mobileno'],
            'email' => $client['email'],
            'gender' => $client['gender']
        ];
    }

    public function headings(): array
    {
        return ['Name', 'Mobile number', 'Email', 'Gender',];
    }
}
