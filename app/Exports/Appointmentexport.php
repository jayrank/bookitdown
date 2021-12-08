<?php

namespace App\Exports;

use App\Models\AppointmentServices;
use App\Models\Staff;
use App\Models\Clients;
use App\Models\ServicesPrice;
use App\Models\Location;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Auth;

class Appointmentexport implements FromCollection, WithHeadings , ShouldAutoSize
{
    /** WithMapping
    * @return \Illuminate\Support\Collection
    */

    protected $location,$staff;

    function __construct($location,$staff,$start_date,$end_date) {
        $this->location = $location;
        $this->staff = $staff;
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
        $staff_id    = $this->staff;
        
        if($start_date != '' && $end_date != ''){
            if($location_id != ''){
                $locationWhere = array('appointments.location_id' => $location_id);
            } else {
                $locationWhere = array();
            }
            
            if($staff_id != ''){
                $staffWhere = array('appointment_services.staff_user_id' => $staff_id);
            } else {
                $staffWhere = array();
            }
            
            // get all appointment services
            $AppointmentServices = AppointmentServices::select('appointment_services.*','users.first_name','users.last_name','appointments.client_id','appointments.location_id','appointments.appointment_status','appointments.is_cancelled','appointments.is_reschedule','appointments.is_noshow', 'appointments.is_online_appointment', 'appointments.created_at', 'u2.first_name as u2_first_name', 'u2.last_name as u2_last_name')->leftJoin('appointments','appointments.id','=','appointment_services.appointment_id')->leftJoin('users','users.id','=','appointment_services.staff_user_id')->leftJoin('users as u2','u2.id','=','appointments.created_by')->where('appointment_services.user_id', $AdminId)->where($locationWhere)->where($staffWhere)->whereDate('appointment_services.appointment_date','>=', $start_date)->whereDate('appointment_services.appointment_date','<=', $end_date)->orderBy('appointment_services.created_at', 'asc')->get()->toArray();	
        } else {
            if($location_id != ''){
                $locationWhere = array('appointments.location_id' => $location_id);
            } else {
                $locationWhere = array();
            }
            
            if($staff_id != ''){
                $staffWhere = array('appointment_services.staff_user_id' => $staff_id);
            } else {
                $staffWhere = array();
            }
            
            // get all appointment services
            $AppointmentServices = AppointmentServices::select('appointment_services.*','users.first_name','users.last_name','appointments.client_id','appointments.location_id','appointments.appointment_status','appointments.is_cancelled','appointments.is_reschedule','appointments.is_noshow', 'appointments.is_online_appointment', 'appointments.created_at', 'u2.first_name as u2_first_name', 'u2.last_name as u2_last_name')->leftJoin('appointments','appointments.id','=','appointment_services.appointment_id')->leftJoin('users','users.id','=','appointment_services.staff_user_id')->leftJoin('users as u2','u2.id','=','appointments.created_by')->where('appointment_services.user_id', $AdminId)->where($locationWhere)->where($staffWhere)->orderBy('appointment_services.created_at', 'asc')->get()->toArray();
        }
        /*$AppointmentServices = AppointmentServices::select('appointment_services.*','users.first_name','users.last_name','appointments.client_id','appointments.location_id','appointments.appointment_status','appointments.is_cancelled','appointments.is_reschedule','appointments.is_noshow', 'appointments.is_online_appointment', 'appointments.created_at', 'u2.first_name as u2_first_name', 'u2.last_name as u2_last_name')->leftJoin('appointments','appointments.id','=','appointment_services.appointment_id')->leftJoin('users','users.id','=','appointment_services.staff_user_id')->leftJoin('users as u2','u2.id','=','appointments.created_by')->where('appointment_services.user_id', $AdminId)->where($locationWhere)->where($staffWhere)->orderBy('appointment_services.created_at', 'asc')->get()->toArray();*/
        
        $appointmentEvents = array();
        if(!empty($AppointmentServices)){
            foreach($AppointmentServices as $AppointmentServicesData) {
                
                // get client name
                if($AppointmentServicesData['client_id'] == 0){
                    $ClientName = 'Walk-In';
                } else {
                    $ClientInfo = Clients::getClientbyID($AppointmentServicesData['client_id']);	
                    if(!empty($ClientInfo)){
                        $ClientName = $ClientInfo->firstname.' '.$ClientInfo->lastname;	
                    } else {
                        $ClientName = 'Walk-In';
                    }
                }
                
                // get service name
                $servicePrices = ServicesPrice::select('services_price.pricing_name','services.service_name')->leftJoin('services','services.id','=','services_price.service_id')->where('services_price.id',$AppointmentServicesData['service_price_id'])->orderBy('services_price.id', 'asc')->get()->first();
                
                $serviceName = '';
                if(!empty($servicePrices)){
                    $serviceName = $servicePrices->service_name.' - '.$servicePrices->pricing_name;
                } else {
                    $serviceName = 'N/A';
                }
                
                // get location name
                $getLocationName = Location::select('location_name')->where('id',$AppointmentServicesData['location_id'])->get()->first();
                
                $locationName = '';
                if(!empty($getLocationName)){
                    $locationName = $getLocationName->location_name;
                } else {
                    $locationName = 'N/A';
                }
                
                // get staff details
                $staffName = '';
                $getUser = User::getUserbyID($AppointmentServicesData['staff_user_id']);
                if(!empty($getUser)){
                    $staffName = $getUser->first_name.' '.$getUser->last_name;
                } else {
                    $staffName = '';
                }
                
                $Status = 0;
                $StatusName = '';
                if($AppointmentServicesData['appointment_status'] == 0){
                    $Status = 0;
                    $StatusName = 'New Appointment';
                } else if($AppointmentServicesData['appointment_status'] == 1){
                    $Status = 1;
                    $StatusName = 'Confirmed';
                } else if($AppointmentServicesData['appointment_status'] == 2){
                    $Status = 2;
                    $StatusName = 'Arrived';
                } else if($AppointmentServicesData['appointment_status'] == 3){
                    $Status = 3;
                    $StatusName = 'Started';
                } else if($AppointmentServicesData['appointment_status'] == 4){
                    $Status = 4;
                    $StatusName = 'Completed';
                }
                
                if($AppointmentServicesData['is_cancelled'] == 1){
                    $Status = 7;
                }
                
                if($AppointmentServicesData['is_reschedule'] == 1){
                    $Status = 5;
                }
                
                if($AppointmentServicesData['is_noshow'] == 1){
                    $Status = 6;
                }

                $created_by = ($AppointmentServicesData['u2_first_name']) ? $AppointmentServicesData['u2_first_name'] : '';;
                $created_by .= ' '.($AppointmentServicesData['u2_last_name']) ? $AppointmentServicesData['u2_last_name'] : '';;
                
                $tempdata['ref_no']           = ($AppointmentServicesData['appointment_id']) ? $AppointmentServicesData['appointment_id'] : 'N/A';
                $tempdata['is_online_appointment'] = ($AppointmentServicesData['is_online_appointment'] == 1) ? 'Book now link' : 'Offline';
                // $tempdata['client_id']        = $AppointmentServicesData['client_id'];
                $tempdata['created_at']           = ($AppointmentServicesData['created_at']) ? date('Y-m-d H:i:s', strtotime($AppointmentServicesData['created_at'])) : '';
                $tempdata['created_by']           = $created_by;
                $tempdata['client_name']      = $ClientName;
                $tempdata['service_name']     = $serviceName;
                $tempdata['appointment_date'] = ($AppointmentServicesData['appointment_date']) ? date("d M Y",strtotime($AppointmentServicesData['appointment_date'])) : 'N/A';
                $tempdata['appointment_time'] = ($AppointmentServicesData['start_time']) ? date("h:i A",strtotime($AppointmentServicesData['start_time'])) : 'N/A';
                $tempdata['location_name']    = $locationName;
                $tempdata['duration']         = $this->hoursandmins($AppointmentServicesData['duration']);
                $tempdata['staff_name']       = $staffName;
                $tempdata['price']            = ($AppointmentServicesData['special_price']) ? $AppointmentServicesData['special_price'] : '0';
                $tempdata['status']           = $StatusName;
                array_push($appointmentEvents,$tempdata);
            }
            
        }
        return collect($appointmentEvents);
    }

    public function headings(): array
    {
        return ['Ref#', 'Channel', 'Created Date', 'Created By', 'Client', 'Service', 'Schedule Date', 'Time','Location','Duration','Staff','Price','Status',];
    }

    function hoursandmins($time, $format = '%02d:%02d'){
        if ($time < 1) {
            return;
        }
        $hours = floor($time / 60);
        $minutes = ($time % 60);
        $returnText = '';
        if($hours == 0){
            $returnText = $minutes.'min';
        } else {
            $returnText = $hours.'h '.$minutes.'min';
        }
        return $returnText;
    }
}
