<?php
namespace App\Http\Controllers\Front;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\JsonReturn;
use App\Models\frontUser;
use App\Models\fuserAddress;
use App\Models\SoldPaidPlan;
use App\Models\Clients;
use App\Models\Services;
use App\Models\ServicesPrice;

use DB;
use Crypt;
use Session;
  
class PaidPlanController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
		$currentRoute = Route::currentRouteName();
		$this->middleware('FUser');
    }

    public function index($soldPaidPlanId = NULL)
    {
        $fuserId = Auth::guard('fuser')->user()->id;

        $clientIds = Clients::where('fuser_id', $fuserId)->pluck('id');
        $activePaidPlan = SoldPaidPlan::whereIn('sold_paidplan.client_id', $clientIds)
                    ->leftJoin('paid_plan', 'paid_plan.id', 'sold_paidplan.paidplan_id')
                    ->leftJoin('locations', 'locations.id', 'sold_paidplan.location_id')
                    ->where('start_date', '<=', date('Y-m-d'))
                    ->where('end_date', '>=', date('Y-m-d'))
                    ->select('sold_paidplan.*', 'locations.location_name', 'locations.location_image', 'locations.location_address', 'paid_plan.color', 'paid_plan.name', 'paid_plan.description')
                    ->get();


        $selectedPaidPlan = NULL;

        if(!empty($soldPaidPlanId)) {
            $selectedPaidPlan = SoldPaidPlan::whereIn('sold_paidplan.client_id', $clientIds)
                                    ->leftJoin('paid_plan', 'paid_plan.id', 'sold_paidplan.paidplan_id')
                                    ->leftJoin('locations', 'locations.id', 'sold_paidplan.location_id')
                                    ->where('sold_paidplan.id', $soldPaidPlanId)
                                    ->select('sold_paidplan.*', 'locations.location_name', 'locations.location_image', 'locations.location_address', 'paid_plan.color', 'paid_plan.name', 'paid_plan.description')
                                    ->first();
        } else {
            if(!$activePaidPlan->isEmpty()) {
                $selectedPaidPlan = $activePaidPlan[0];
            }
        }

        $serviceCategory = [];
        if(!empty($selectedPaidPlan)) {
            if(!empty($selectedPaidPlan->service_id)) {
                $service_id_array = explode(',',$selectedPaidPlan->service_id);

                $serviceLists = Services::select('services.id','services.service_name','services.service_description', 'services.service_category', 'service_category.category_title')
                                ->leftJoin('service_category', 'service_category.id', 'services.service_category')
                                ->whereIn('services.id', $service_id_array)
                                ->orderBy('services.order_id', 'asc')
                                ->get();
                
                foreach($serviceLists as $key => $service)
                {
                    $pricearr = array();
                    $servicePrices = ServicesPrice::select('id', 'duration', 'lowest_price', 'price', 'special_price', 'is_staff_price')
                                    ->where('service_id', $service->id)
                                    ->orderBy('id', 'asc')
                                    ->get();
                    
                    $service_price_special_amount = '';
                    $service_price_amount = '';
                    $is_staff_price = '';
                    foreach($servicePrices as $key2 => $servprice)
                    {
                        $sprice = $servprice->lowest_price;
                        $duration = "";
                        
                        if($servprice->duration <= 0) {
                            $duration = '00h 00min';
                        }
                        else 
                        {  
                            if(sprintf("%02d",floor($servprice->duration / 60)) > 0)
                            {
                                $duration .= sprintf("%02d",floor($servprice->duration / 60)).'h ';
                            } 
                                
                            if(sprintf("%02d",str_pad(($servprice->duration % 60), 2, "0", STR_PAD_LEFT)) > 0)
                            {
                                $duration .= " ".sprintf("%02d",str_pad(($servprice->duration % 60), 2, "0", STR_PAD_LEFT)). "min";
                            }
                        }
                        
                        $pr = "";
                        if(count($servicePrices) > 1) {
                            $pr = "pr".(++$key2);   
                        }   
                        
                        if($servprice->price != $sprice) {
                            $service_price = $servprice->price;
                            $service_price_special = $sprice;
                        } else {
                            $service_price = $sprice;
                            $service_price_special = $sprice;
                        }
                        $uniqid = $this->quickRandom();
                        
                        $tmpArr = array(
                            'service_price_id' => $servprice->id,
                            'service_price_uid' => $uniqid,
                            'service_price_name' => $pr,
                            'service_price_duration' => $servprice->duration,
                            'service_price_duration_txt' => $duration,
                            'is_staff_price' => $servprice->is_staff_price,
                            'service_price_amount' => $service_price,
                            'service_price_special_amount' => $service_price_special,
                        );  
                        
                        array_push($pricearr, $tmpArr);

                        if(empty($service_price_special_amount)) {
                            $service_price_special_amount = $service_price_special;
                            $service_price_amount = $service_price;
                            $is_staff_price = $servprice->is_staff_price;
                        } elseif( $service_price_special < $service_price_special_amount ) {
                            $service_price_special_amount = $service_price_special;
                            $service_price_amount = $service_price;
                            $is_staff_price = $servprice->is_staff_price;
                        }
                    }
                    
                    $min_duration = $this->convertDurationText(min(array_column($pricearr, 'service_price_duration'))); 
                    $max_duration = $this->convertDurationText(max(array_column($pricearr, 'service_price_duration'))); 
                    $service['serviceDuration'] = ($min_duration != $max_duration) ? $min_duration." - ".$max_duration : $min_duration;
                    $service['servicePrice'] = $pricearr;

                    $service['service_price_special_amount'] = $service_price_special;
                    $service['service_price_amount'] = $service_price_amount;
                    $service['is_staff_price'] = $is_staff_price;

                    if( !isset($serviceCategory[ $service->service_category ]) ) {
                        $serviceCategory[ $service->service_category ] = [];
                    }

                    $serviceCategory[ $service->service_category ][] = $service;
                }
            }
        }

        return view('frontend.paid_plan.index', compact('activePaidPlan', 'selectedPaidPlan', 'serviceCategory'));
    }
    
    function convertDurationText($duration)
    {
        $duration_txt = "";
        if($duration <= 0) {
            $duration_txt = '00h 00min';
        }
        else 
        {  
            if(sprintf("%02d",floor($duration / 60)) > 0)
            {
                $duration_txt .= sprintf("%02d",floor($duration / 60)).'h ';
            } 
                
            if(sprintf("%02d",str_pad(($duration % 60), 2, "0", STR_PAD_LEFT)) > 0)
            {
                $duration_txt .= " ".sprintf("%02d",str_pad(($duration % 60), 2, "0", STR_PAD_LEFT)). "min";
            }
        }
        return trim($duration_txt);
    }   
    
    public static function quickRandom($length = 5)
    {
        $pool = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
    } 
}
