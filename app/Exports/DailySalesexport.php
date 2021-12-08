<?php

namespace App\Exports;

use App\Models\InvoiceItems;
use Maatwebsite\Excel\Concerns\FromCollection;
// use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
// use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

use DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Staff;
use Illuminate\Contracts\View\View;
use App\Models\paymentType;
use App\Models\Invoice;
use App\Models\InvoiceVoucher;
use App\Models\StaffTip;

class DailySalesexport implements  ShouldAutoSize, WithMultipleSheets
{
    private $start_date;
    private $end_date;
    private $location_id;

    public function __construct($start_date, $end_date, $location_id) 
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->location_id = $location_id;
    }

    public function sheets():array
    {

        $sheets = [
            new TransactionSummaryexport($this->start_date, $this->end_date, $this->location_id),
            new CashMovementSummaryexport($this->start_date, $this->end_date, $this->location_id)
        ];

        return $sheets;
    }
}
