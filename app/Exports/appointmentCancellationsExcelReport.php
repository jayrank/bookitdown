<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
// use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
// use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;

class appointmentCancellationsExcelReport implements  ShouldAutoSize, FromCollection, WithHeadings, WithStyles
{
    private $getAppointmentCancellations;
    private $getAppointmentCancellationsSummary;

    public function __construct($getAppointmentCancellations, $getAppointmentCancellationsSummary)
    {
        $this->getAppointmentCancellations = $getAppointmentCancellations;
        $this->getAppointmentCancellationsSummary = $getAppointmentCancellationsSummary;
    }
    
    public function collection()
    {
        $sheets = [];

        /** Discounts */

        if(!empty($this->getAppointmentCancellationsSummary)){
            $nullCount = 0;
            $flag = 0;
            foreach($this->getAppointmentCancellationsSummary as $getAppointmentCancellationsSummaryKey => $getAppointmentCancellationsSummaryValue){
                if(empty($getAppointmentCancellationsSummaryValue['reason'])){
                    $nullCount = $getAppointmentCancellationsSummaryValue['reason_count'];
                }
            }

            foreach($this->getAppointmentCancellationsSummary as $getAppointmentCancellationsSummaryKey => $getAppointmentCancellationsSummaryValue){

                if($getAppointmentCancellationsSummaryValue['reason'] === "No reason"){
                    $flag = 1;
                    $sheets[] = [
                        $getAppointmentCancellationsSummaryValue['reason'],$getAppointmentCancellationsSummaryValue['reason_count'] + $nullCount,
                    ];
                    
                }elseif(!empty($getAppointmentCancellationsSummaryValue['reason'])){
                    $sheets[] = [
                        $getAppointmentCancellationsSummaryValue['reason'],$getAppointmentCancellationsSummaryValue['reason_count'],
                    ];
                }else{

                }

            }

            if($flag == 0 && $nullCount > 0){
                $sheets[] = [
                    'No reason',
                    $nullCount,
                ];
            }
        }
        $sheets[] = [''];

        $sheets[] = [
            'Ref#',
            'Client',
            'Service',
            'Scheduled Date',
            'Cancelled Date',
            'Cancelled By',
            'Reason',
            'Price',
        ];

        if(!empty($this->getAppointmentCancellations)){
            foreach($this->getAppointmentCancellations as $getAppointmentCancellationsKey => $getAppointmentCancellationsValue){
                $sheets[] = [
                    $getAppointmentCancellationsValue['ref'],
                    $getAppointmentCancellationsValue['client'],
                    $getAppointmentCancellationsValue['service'],
                    $getAppointmentCancellationsValue['scheduled_date'],
                    $getAppointmentCancellationsValue['cancelled_date'],
                    $getAppointmentCancellationsValue['cancelled_by'],
                    $getAppointmentCancellationsValue['reason'],
                    ($getAppointmentCancellationsValue['price'] ? $getAppointmentCancellationsValue['price'] : '0'),
                ];
            }
        }

        return collect($sheets);
    }

    public function headings(): array
    {
        return ['Summary'];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:I1000')->getFont()->setSize(12);
        $sheet->getStyle('A1:I1')->getFont()->setBold(true);
    }
}
