<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Location;
use App\Models\ServiceCategory;
use App\Models\Businesstype;
use App\Models\Location_image;
use App\Models\Store_timetable;
use App\Models\Taxes;
use App\Models\Appointments;
use App\Models\Services;
use App\Models\Staff;
use App\Models\taxFormula;
use App\Models\LocTax;
use App\Models\ServicesPrice;
use App\Models\User;
use App\Models\Voucher;
use App\Models\fuserFavourites;
use App\Models\FuserLocationReview;
use App\Models\Online_setting;
use App\Models\StaffServices;
use App\JsonReturn;
use Crypt;
use Session;
use DB;
use url;

class DashboardController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }
	
    public function index()
    {
        $cat = Businesstype::get();
        $ap = Appointments::get();
        $salon = User::where('is_admin',1)->get();
        $staff = User::where('is_admin',0)->get();
        $locationData = Location::where('is_deleted',0)->where('is_online', 1)->get();
        $this->getLocationByIp();
        return view('frontend/dashboard',compact('cat','ap','salon','staff','locationData'));
    }
	
    public function search($category = null)
    {
        $categoryID = "";
        $coun = explode(',', Session::get('country'));
        $string = str_replace(' ', '', $coun);
        /*echo "<pre>";
        print_r($coun);
        print_r($string);
        exit;*/
        $country = $string[0];

		if($category != ''){
            $categoryID = Crypt::decryptString($category);
			$LocationData = Location::where(['is_deleted'=>0, 'is_online' => 1, 'loc_country'=>$country])->whereIn('id', function($query) use ($categoryID){
								$query->select('location_id')
								->from('location_business_type')
								->where('business_type_id', $categoryID);
							})->get();
		} else {
            
			$LocationData = Location::where('is_deleted',0)->where('is_online', 1)->where('loc_country',$country)->get();	
		}
        
        foreach ($LocationData as $key => $value) {
            $LocationImage = Location_image::where('location_id',$value->id)->get();
            // $locationRating = FuserLocationReview::where('location_id',$value->id)->pluck();
            $LocationData[$key]->images = $LocationImage;
        }

        $requestSearch = request()->location;
        $requestCity = request()->city;
        $requestCountry = request()->country;
		
        return view('frontend/search',compact('LocationData','categoryID', 'requestSearch', 'requestCity', 'requestCountry'));
    }
	
    public function getallsalon(){
        $LocationData = Location::where('is_deleted',0)->where('is_online', 1)->get();
        foreach ($LocationData as $key => $value) {
            $LocationImage = Location_image::where('location_id',$value->id)->get();
            $LocationData[$key]->images = $LocationImage;
        }
        return view('frontend/search',compact('LocationData'));
        
    }

    public function offers(){
        // $LocationData = Location::where('is_deleted',0)->where('is_online', 1)->get();
        // foreach ($LocationData as $key => $value) {
        //     $LocationImage = Location_image::where('location_id',$value->id)->get();
        //     $LocationData[$key]->images = $LocationImage;
        // }
        return view('frontend/offer');
        
    }

    public function search_detail($id = null)
    {
        if($id != "")
        {
            $serviceCategory = "";
            $locationID = Crypt::decryptString($id);
            $StoreTiming = Store_timetable::where('location_id',$locationID)->first();
            $LocationData = Location::select('id','user_id','location_name','location_address','location_image','location_latitude','location_longitude','location_description','available_for')->where('id',$locationID)->first();
            $encrptLocationId = Crypt::encryptString($LocationData->id);
            $locationUserId = $LocationData->user_id;
            $voucherData = Voucher::select('id','services_ids','retailprice','name','validfor','validfor','is_online', 'color')->where(['user_id'=>$locationUserId,'isdelete'=>"0"])->where('is_online', 1)->get();

            $staffLists = Staff::select('staff.id','staff.staff_user_id','users.first_name','users.last_name','staff.staff_title')->leftJoin('users', 'users.id', '=', 'staff.staff_user_id')->where('staff.user_id', $locationUserId)->where('staff.is_appointment_booked', 1)->orderBy('staff.order_id', 'asc')->get();

            if($staffLists->isNotEmpty()) {
                foreach($staffLists as $key => $value) {
                    if(!StaffServices::where(['staff_id' => $value->id, 'status' => 1])->exists()) {
                        unset($staffLists[$key]);
                    }
                }
            }

            $serviceCategory = ServiceCategory::select('id','category_title')->where('user_id', $locationUserId)->where('is_deleted', 0)->orderBy('order_id', 'asc')->get();
            $taxFormulaData = taxFormula::select('tax_formula.tax_formula')->where('user_id', $locationUserId)->first();
            $lat = $LocationData->location_latitude;
            $lng = $LocationData->location_longitude;
            $dist = 10;
            $nearestLocations = "";
            if($lat != "" && $lng != "")
            {
                // $nearestLocations = DB::select("SELECT *, 3956 * 2 * ASIN(SQRT( POWER(SIN(($lat - abs(location_latitude))*pi()/180/2),2)+COS($lat*pi()/180 )*COS(abs(location_latitude)*pi()/180)*POWER(SIN(($lng-location_longitude)*pi()/180/2),2))) as distance FROM `locations` WHERE location_longitude between ($lng-$dist/abs(cos(radians($lat))*69)) and ($lng+$dist/abs(cos(radians($lat))*69)) and location_latitude between ($lat-($dist/69)) and ($lat+($dist/69)) having distance < $dist ORDER BY distance limit 4");

                $nearestLocations = DB::select("SELECT * , (3956 * 2 * ASIN(SQRT( POWER(SIN(( $lat - location_latitude) *  pi()/180 / 2), 2) +COS( $lat * pi()/180) * COS(location_latitude * pi()/180) * POWER(SIN(( $lng - location_longitude) * pi()/180 / 2), 2) ))) as distance  
                                from locations 
                                where id != ".$locationID." 
                                having  distance <= 100 
                                order by distance limit 4");
            }
            /*echo "<pre>";
            print_r($nearestLocations);
            exit();*/
            $Location_image = Location_image::select('image_path')->where('location_id',$locationID)->get();
            
            if(!empty($taxFormulaData)) {
                $taxFormula = $taxFormulaData->tax_formula; 
            } else {
                $taxFormula = 0;
            }
            
            $serTaxes = LocTax::select('loc_taxes.id','loc_taxes.service_default_tax','taxes.tax_name','taxes.tax_rates','taxes.is_group')->leftJoin('taxes', 'taxes.id', '=', 'loc_taxes.service_default_tax')->where('loc_taxes.service_default_tax', '>' , 0)->where('loc_taxes.user_id', $locationUserId)->where('loc_taxes.loc_id', $locationID)->first();
            
            $serviceTaxes = array();
            if(!empty($serTaxes)) {
                if($serTaxes->is_group == 1) {
                    
                    $taxes = explode(",", str_replace(" ", "", $serTaxes->tax_rates));
                    $serTaxes = Taxes::select('taxes.id','taxes.tax_name','taxes.tax_rates','taxes.is_group')->whereIn('id', $taxes)->get();
                    
                    foreach($serTaxes as $tax) {
                        $tmp_arr = array(
                            'id' => $tax->id,
                            'service_default_tax' => $tax->id,
                            'tax_name' => $tax->tax_name,
                            'tax_rates' => $tax->tax_rates,
                            'is_group' => $tax->is_group
                        );
                        
                        array_push($serviceTaxes, $tmp_arr);
                    }
                } else {
                    $tmp_arr = array(
                        'id' => $serTaxes->id,
                        'service_default_tax' => $serTaxes->service_default_tax,
                        'tax_name' => $serTaxes->tax_name,
                        'tax_rates' => $serTaxes->tax_rates,
                        'is_group' => $serTaxes->is_group
                    );
                    
                    array_push($serviceTaxes, $tmp_arr);
                }       
            }
            $services = array();
            foreach($serviceCategory as $cateKey => $servCat)
            {
                $serviceLists = Services::select('id','service_name','service_description')->where('service_category', $servCat->id)->where('is_deleted', 0)->where('user_id', $locationUserId)->orderBy('order_id', 'asc')->get();
                $services[$cateKey]['category'] = $servCat;
                $servCat['services'] = $serviceLists;
                $servicehtml = "";
                foreach($serviceLists as $key => $service)
                {
                    $pricearr = array();
                    $servicePrices = ServicesPrice::select('id', 'duration', 'lowest_price', 'price', 'special_price', 'is_staff_price')->where('service_id', $service->id)->where('user_id', $locationUserId)->orderBy('id', 'asc')->get();
                    
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
                    }
                    // return min(array_column($pricearr, 'service_price_duration'));
                    $min_duration = !empty($pricearr) ? min(array_column($pricearr, 'service_price_duration')) : '';
                    $max_duration = !empty($pricearr) ? max(array_column($pricearr, 'service_price_duration')) : ''; 
                    $services[$cateKey]['category']['serviceDuration'] = $this->convertDurationText($min_duration)." - ".$this->convertDurationText($max_duration);
                    $services[$cateKey]['category']['servicePrice'] = $pricearr;
                }
            }
            /*echo "<pre>";
            print_r($services);
            exit;*/
            // Favourites Configuration
            $markFavourite = false;

            if(Auth::guard('fuser')->check()) {
                $fuserId = Auth::guard('fuser')->user()->id;
                
                if(fuserFavourites::where(['fuser_id' => $fuserId, 'location_id' => $locationID])->exists()) {
                    $markFavourite = true;
                }
            }
            // End Favourite Configuration

            // Review Configuration
            $locationReview = FuserLocationReview::where([
                'location_id' => $locationID,
                'status' => 'publish'
            ])->pluck('rating')->toArray();

            $locationRating = 0;
            $starWiseRating = [
                '5starRating' => 0,
                '4starRating' => 0,
                '3starRating' => 0,
                '2starRating' => 0,
                '1starRating' => 0
            ];

            $ratingWisePercentage = [
                '5starRating' => 0,
                '4starRating' => 0,
                '3starRating' => 0,
                '2starRating' => 0,
                '1starRating' => 0
            ];
            if(is_array($locationReview)) {
                $totalRatings = count($locationReview);

                foreach ($locationReview as $key => $value) {
                    if($value == 5) {
                        $starWiseRating['5starRating']++;
                    } elseif($value == 4) {
                        $starWiseRating['4starRating']++;
                    } elseif($value == 3) {
                        $starWiseRating['3starRating']++;
                    } elseif($value == 2) {
                        $starWiseRating['2starRating']++;
                    } elseif($value == 1) {
                        $starWiseRating['1starRating']++;
                    }
                }

                $locationReview = array_filter($locationReview);
                if($totalRatings) {
                    $locationRating = array_sum($locationReview)/$totalRatings;


                    $ratingWisePercentage = [
                        '5starRating' => ($starWiseRating['5starRating'] / $totalRatings) * 100,
                        '4starRating' => ($starWiseRating['4starRating'] / $totalRatings) * 100,
                        '3starRating' => ($starWiseRating['3starRating'] / $totalRatings) * 100,
                        '2starRating' => ($starWiseRating['2starRating'] / $totalRatings) * 100,
                        '1starRating' => ($starWiseRating['1starRating'] / $totalRatings) * 100
                    ];
                }
            } else {
                $totalRatings = 0;
            }
            $locationRating = round($locationRating,1);

            // End Review Configuration


            $onlineSettingData = Online_setting::select('is_allowed_staff_selection')->where('user_id', $locationUserId)->first();
            if(!empty($onlineSettingData)) {
                $is_allowed_staff_selection = $onlineSettingData->is_allowed_staff_selection;
            } else {
                $is_allowed_staff_selection = 0;
            }

            return view('frontend/search_detail',compact('LocationData','encrptLocationId','StoreTiming','locationUserId','serviceCategory','staffLists','serviceTaxes','taxFormula','Location_image','voucherData','nearestLocations','markFavourite', 'locationRating', 'totalRatings','starWiseRating','ratingWisePercentage','services','is_allowed_staff_selection'));
        }
        else
        {
            return redirect(route('search'));
        }
    }
	
    public static function quickRandom($length = 5)
    {
        $pool = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
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
	
    public function searchFilter(Request $request)
    {
        if($request->ajax())
        {
            $whereRaw = "";
            $locationIds = array();
            $lat = Session::get('user_latitude');
            $lng = Session::get('user_longitude');
  
            if($request->available == 0)
            {
                $whereRaw = "(locations.available_for = 1 OR locations.available_for = 2 OR locations.available_for = 0 OR locations.available_for IS NULL)";
            }else if($request->available != ""){
                $whereRaw = "(locations.available_for = ".$request->available.")";
            }

            $whereArray = [
                'locations.is_deleted' => 0,
                'locations.is_online' => 1
            ];

            // $availableForData = Services::where(['is_deleted'=>0])->whereRaw($whereRaw)->where('location_id','!=',"")->pluck('location_id')->toArray();

            $availableForData = Location::where($whereArray)->whereRaw($whereRaw);
            if(!empty($request->loc_city)) {
                $availableForData->where(function ($query) use ($request) {
                    $query->where('locations.loc_city','like','%'.$request->loc_city.'%')
                          ->orWhere('locations.location_address','like','%'.$request->loc_city.'%');
                });
            }
            if(!empty($request->loc_country)) {
                $availableForData->where('locations.loc_country', $request->loc_country);
            }

            if(!empty($request->openNowFilter)) {
                $availableForData->join('store_timetable', function ($join) {
                    $join->on('locations.id', '=', 'store_timetable.location_id')
                        ->where('is_open_'.strtolower(date('l')), 1)
                        ->whereRaw('STR_TO_DATE(store_timetable.'.strtolower(date('l')).'_open_time, "%l:%i %p" ) < "'.date('H:i:s').'"')
                        ->whereRaw('STR_TO_DATE(store_timetable.'.strtolower(date('l')).'_close_time, "%l:%i %p" ) > "'.date('H:i:s').'"')
                         ->limit(1);
                });
            }

            if(!empty($request->minRange) || !empty($request->maxRange)) {
                $availableForData->join('services', function($join){
                                   $join->whereRaw(DB::raw("find_in_set(locations.id, services.location_id) > 0"))
                                   ->limit(1);
                                })
                                ->join('services_price', function ($join) use ($request) {
                                    $join->on('services.id', '=', 'services_price.service_id')
                                        ->whereRaw('services_price.special_price >= '.$request->minRange.' AND services_price.special_price <= '.$request->maxRange)
                                        ->limit(1);
                                });
            }

            if(!empty($request->service_category_id)) {
                $availableForData->whereIn('locations.id',function($query) use ($request) {
                                   $query->select('location_id')
                                ->from('location_business_type')
                                ->where('business_type_id', $request->service_category_id);
                                });
            }
            $availableForData = $availableForData->pluck('locations.id')->toArray();
            /*foreach ($availableForData as $key => $value) {
                $expVal = explode(',', $value);
                foreach ($expVal as $exp) {
                    if(!in_array($exp, $locationIds) && $exp != ""){
                        array_push($locationIds, $exp);
                    }
                }
            }*/
            $locationIds = $availableForData;
            
            if($request->nearest_filter == 'Newest'){
                $locationOrderBy = 'created_at DESC';
                $LocationData = Location::whereIn('id',$locationIds)->orderBy('created_at','desc')->get();
            }else if($request->nearest_filter == 'Nearest'){
                $dist = 50;
                $locationIdString = (is_array($locationIds) && !empty($locationIds)) ? implode(',', $locationIds) : '-1';
                $LocationData = DB::select("SELECT *, 3956 * 2 * ASIN(SQRT( POWER(SIN(($lat - abs(location_latitude))*pi()/180/2),2)+COS($lat*pi()/180 )*COS(abs(location_latitude)*pi()/180)*POWER(SIN(($lng-location_longitude)*pi()/180/2),2))) as distance FROM `locations` WHERE location_longitude between ($lng-$dist/abs(cos(radians($lat))*69)) and ($lng+$dist/abs(cos(radians($lat))*69)) and location_latitude between ($lat-($dist/69)) and ($lat+($dist/69)) and `id` IN ($locationIdString) having distance < $dist ORDER BY distance");
            }else if($request->nearest_filter == 'Lowest'){
                $TempLocationData = Location::leftJoin('services', function($join){
                                                   $join->whereRaw(DB::raw("find_in_set(locations.id, services.location_id) > 0"));
                                                })
                                                ->leftJoin('services_price', 'services_price.service_id', 'services.id')
                                                ->whereIn('locations.id', $locationIds)
                                                ->orderBy(DB::raw('-services_price.lowest_price'),'DESC')
                                                ->select('locations.*', 'services_price.lowest_price')
                                                ->get();
                $uniqueLocationId = [];
                $LocationData = [];

                foreach($TempLocationData as $key => $value) {
                    if(!in_array($value->id, $uniqueLocationId)) {
                        $uniqueLocationId[] = $value->id;
                        $LocationData[] = $value;
                    } else {
                        unset($TempLocationData[$key]);
                    }
                }

            }else if($request->nearest_filter == 'Highest'){
                $TempLocationData = Location::leftJoin('services', function($join){
                                                   $join->whereRaw(DB::raw("find_in_set(locations.id, services.location_id) > 0"));
                                                })
                                                ->leftJoin('services_price', 'services_price.service_id', 'services.id')
                                                ->whereIn('locations.id', $locationIds)
                                                ->orderBy(DB::raw('GREATEST(services_price.price, services_price.special_price)'),'DESC')
                                                ->select('locations.*', DB::raw('GREATEST(services_price.price, services_price.special_price) as highest_price'))
                                                ->get();
                $uniqueLocationId = [];
                $LocationData = [];

                foreach($TempLocationData as $key => $value) {
                    if(!in_array($value->id, $uniqueLocationId)) {
                        $uniqueLocationId[] = $value->id;
                        $LocationData[] = $value;
                    } else {
                        unset($TempLocationData[$key]);
                    }
                }

            }else if($request->nearest_filter == 'topRated' || $request->nearest_filter == 'Popular'){

                $LocationData = Location::leftJoin('fuser_location_review', 'fuser_location_review.location_id', 'locations.id')
                                                ->whereIn('locations.id', $locationIds)
                                                // ->where('fuser_location_review.status','publish')
                                                ->select(DB::raw('AVG(fuser_location_review.rating) as avg_rating'), 'locations.*')
                                                ->orderBy('avg_rating', 'DESC')
                                                ->groupBy('locations.id')
                                                ->get();

            }


            $html = "";
            if(!empty($LocationData))
            {
                foreach ($LocationData as $key => $location) 
                {
                    $LocationImage = Location_image::where('location_id',$location->id)->get();
                    $html .= '<div class="col-12 col-md-4 mb-3">';
                        $html .= '<div class="list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm">';
                            $html .= '<div class="list-card-image">';
                                $html .= '<div class="popular-slider">';
                                if(!$LocationImage->isEmpty())
                                {
                                    foreach ($LocationImage as $key => $image) 
                                    {
                                        $html .= '<div class="osahan-slider-item">';
                                            $html .= '<a href="'.route('search_detail',['id' => Crypt::encryptString($location->id)]).'">';
                                            $html .= '<img alt="#" src="'.url($image->image_path).'" class="img-fluid item-img w-100 rounded">';
                                            $html .= '</a></div>';
                                    }
                                }else{
                                    $html .= '<div class="osahan-slider-item">';
                                        $html .= '<a href="'.route('search_detail',['id' => Crypt::encryptString($location->id)]).'">';
                                        $html .= '<img alt="#" src="'.asset('frontend/img/featured1.jpg').'" class="img-fluid item-img w-100 rounded">';
                                        $html .= '</a></div>';
                                }
                                $html .= '</div>';
                            $html .= '</div>';
                            $html .= '<div class="p-3 position-relative">';
                                $html .= '<div class="list-card-body">';
                                    $html .= '<h6 class="mb-1"><a href="'.route('search_detail',['id' => Crypt::encryptString($location->id)]).'" class="text-black">'.$location->location_name.'</a>';
                                    $html .= '</h6>';
                                    $html .= '<p class="text-gray mb-1 small">'.$location->location_address.'</p>';
                                    $html .= '<p class="text-gray mb-1 rating">';
                                    $html .= '</p>';

                                    $rating = FuserLocationReview::where(['location_id' => $location->id, 'status' => 'publish'])->selectRaw('SUM(rating)/COUNT(location_id) AS avg_rating')->first()->avg_rating;
                                    // $ratcount = \DB::table('fuser_location_review')->where('location_id',$location->id)->selectRaw('COUNT(location_id) AS count')->first()->count;
                                    $rat = round($rating,1);

                                    $html .= '<ul class="rating-stars list-unstyled">';
                                        $html .= '<li>';
                                            $html .= '<i class="feather-star '.(($rat >= 1) ? 'star_active' : '').'"></i>';
                                            $html .= '<i class="feather-star '.(($rat >= 2) ? 'star_active' : '').'"></i>';
                                            $html .= '<i class="feather-star '.(($rat >= 3) ? 'star_active' : '').'"></i>';
                                            $html .= '<i class="feather-star '.(($rat >= 4) ? 'star_active' : '').'"></i>';
                                            $html .= '<i class="feather-star '.(($rat >= 5) ? 'star_active' : '').'"></i>';
                                        $html .= '</li>';
                                    $html .= '</ul>';
                                    $html .= '<p></p>';
                                $html .= '</div>';
                                $html .= '<div class="list-card-badge">';
                                    // $html .= '<span class="badge badge-danger">OFFER</span> <small>65% OSAHAN50</small>';
                                $html .= '</div>';
                            $html .= '</div>';
                        $html .= '</div>';
                    $html .= '</div>';
                }
                $data['status'] = true;
                $data['html'] = $html;
                $data['total_records'] = (count($LocationData) != 0) ? count($LocationData) : "No ";
                return JsonReturn::success($data);
            }
            else
            {
                $data['status'] = false;
                $data['html'] = "";
                $data['total_records'] = "No ";
                return JsonReturn::success($data);
            }
        }
    }
    public function categorySearchFilter(Request $request)
    {
        $html = "";
        $lochtml = "";
        if($request->ajax())
        {
            if($request->type != "")
            {
                $getCategoryFilteredData = Businesstype::where('name','like','%'.$request->type.'%')->get();
                $LocationData = Location::where('is_deleted',0)
                                ->where('is_online',1)
                                ->where(function($query) use ($request) {
                                    $query->where('location_name','like','%'.$request->type.'%');
                                    $query->orWhere('location_address','like','%'.$request->type.'%');
                                })
                                ->get();
                if(!$getCategoryFilteredData->isEmpty())
                {
                    foreach ($getCategoryFilteredData as $catKey => $catFilter) 
                    {
                        $html .= '<li><a href="'.route('search',['category' => $catFilter->id]).'" class="bussinessTypes" data-type-id="'.$catFilter->id.'" data-title="'.$catFilter->name.'">'.$catFilter->name.'</a></li>';
                    }
                }
                if(!$LocationData->isEmpty())
                {
                    foreach ($LocationData as $locKey => $locFilter) 
                    {
						$routeRedirect = route('search_detail',['id' => Crypt::encryptString($locFilter->id)]);
						
                        $lochtml .= '<li onclick="window.location.href=\''.$routeRedirect.'\'">';
                            $lochtml .= '<div class="staff-flexbox">';
                                $lochtml .= '<div class="venue-img">';
                                    if(!empty($locFilter->location_image))
                                    {
                                        $lochtml .= '<img src="'.url($locFilter->location_image).'">';
                                    }
                                $lochtml .= '</div>';
                            $lochtml .= '</div>';
                            $lochtml .= '<div class="staff-flexbox">';
                                $lochtml .= '<div class="venue-info">';
                                    $lochtml .= '<p>'.$locFilter->location_name.'</p>';
                                    $lochtml .= '<p class="address">'.$locFilter->location_address.'</p>';
                                $lochtml .= '</div>';
                            $lochtml .= '</div>';
                        $lochtml .= '</li>';
                    }
                }
            }
            else
            {
                $getCategoryFilteredData = Businesstype::get();
                $LocationData = Location::where('is_deleted',0)->where('is_online', 1)->get();
                if(!$getCategoryFilteredData->isEmpty())
                {
                    foreach ($getCategoryFilteredData as $catKey => $catFilter) 
                    {
                        $html .= '<li><a href="'.route('search',['category' => $catFilter->id]).'" class="bussinessTypes" data-type-id="'.$catFilter->id.'" data-title="'.$catFilter->name.'">'.$catFilter->name.'</a></li>';
                    }
                }
                if(!$LocationData->isEmpty())
                {
                    foreach ($LocationData as $locKey => $locFilter) 
                    {
						$routeRedirect = route('search_detail',['id' => Crypt::encryptString($locFilter->id)]);
						
                        $lochtml .= '<li onclick="window.location.href=\''.$routeRedirect.'\'">';
                            $lochtml .= '<div class="staff-flexbox">';
                                $lochtml .= '<div class="venue-img">';
                                    if(!empty($locFilter->location_image))
                                    {
                                        $lochtml .= '<img src="'.url($locFilter->location_image).'">';
                                    }
                                    else
                                    {
                                        $lochtml .= '<svg class="" width="56" height="56"><path fill="#037AFF" d="M25.715 14.545c6.168 0 11.17 5 11.17 11.17 0 3.015-1.196 5.752-3.138 7.761l7.272 7.272a.78.78 0 01-1.016 1.178l-.087-.075-7.33-7.33a11.122 11.122 0 01-6.871 2.363c-6.17 0-11.17-5-11.17-11.17 0-6.168 5-11.17 11.17-11.17zm0 1.56a9.61 9.61 0 100 19.22 9.61 9.61 0 000-19.22zm0 2.336a.78.78 0 010 1.56A5.714 5.714 0 0020 25.715a.78.78 0 11-1.56 0 7.274 7.274 0 017.274-7.274z"></path></svg>';
                                    }
                                $lochtml .= '</div>';
                            $lochtml .= '</div>';
                            $lochtml .= '<div class="staff-flexbox">';
                                $lochtml .= '<div class="venue-info">';
                                    $lochtml .= '<p>'.$locFilter->location_name.'</p>';
                                    $lochtml .= '<p class="address">'.$locFilter->location_address.'</p>';
                                $lochtml .= '</div>';
                            $lochtml .= '</div>';
                        $lochtml .= '</li>';
                    }
                }
            }
            $data['status'] = true;
            $data['cat_html'] = $html;
            $data['loc_html'] = $lochtml;
            $data['total_records'] = (count($LocationData) != 0) ? count($LocationData) : "No ";
            return JsonReturn::success($data);
        }
    }
    
    public function searchLocation(Request $request)
    {
        $html = "";
        if($request->ajax())
        {
            $LocationData = Location::where('is_deleted',0)->where('loc_city','like','%'.$request->city.'%')->orWhere('location_address','like','%'.$request->city.'%')->get();

            if(!$LocationData->isEmpty())
            {
                foreach ($LocationData as $key => $location) 
                {
                    $LocationImage = Location_image::where('location_id',$location->id)->get();
                    $html .= '<div class="col-12 col-md-4 mb-3">';
                        $html .= '<div class="list-card bg-white h-100 rounded overflow-hidden position-relative shadow-sm">';
                            $html .= '<div class="list-card-image">';
                                $html .= '<div class="popular-slider">';
                                if(!$LocationImage->isEmpty())
                                {
                                    foreach ($LocationImage as $key => $image) 
                                    {
                                        $html .= '<div class="osahan-slider-item">';
                                            $html .= '<a href="'.url("search_detail/".Crypt::encryptString($location->id)).'">';
                                            $html .= '<img alt="#" src="'.url($image->image_path).'" class="img-fluid item-img w-100 rounded">';
                                            $html .= '</a></div>';
                                    }
                                }else{
                                    $html .= '<div class="osahan-slider-item">';
                                        $html .= '<a href="'.url('search_detail/'.Crypt::encryptString($location->id)).'">';
                                        $html .= '<img alt="#" src="'.asset("frontend/img/featured1.jpg").'" class="img-fluid item-img w-100 rounded">';
                                    $html .= '</a></div>';
                                }
                                $html .= '</div>';
                            $html .= '</div>';
                            $html .= '<div class="p-3 position-relative">';
                                $html .= '<div class="list-card-body">';
                                    $html .= '<h6 class="mb-1"><a href="salon_detail.html" class="text-black">'.$location->location_name.'</a>';
                                    $html .= '</h6>';
                                    $html .= '<p class="text-gray mb-1 small">'.$location->location_address.'</p>';
                                    $html .= '<p class="text-gray mb-1 rating">';
                                    $html .= '</p>';
                                    $html .= '<ul class="rating-stars list-unstyled">';
                                        $html .= '<li>';
                                            $html .= '<i class="feather-star star_active"></i>';
                                            $html .= '<i class="feather-star star_active"></i>';
                                            $html .= '<i class="feather-star star_active"></i>';
                                            $html .= '<i class="feather-star star_active"></i>';
                                            $html .= '<i class="feather-star"></i>';
                                        $html .= '</li>';
                                    $html .= '</ul>';
                                    $html .= '<p></p>';
                                $html .= '</div>';
                                $html .= '<div class="list-card-badge">';
                                    $html .= '<span class="badge badge-danger">OFFER</span> <small>65% OSAHAN50</small>';
                                $html .= '</div>';
                            $html .= '</div>';
                        $html .= '</div>';
                    $html .= '</div>';
                }
                $data['status'] = true;
                $data['html'] = $html;
                $data['total_records'] = (count($LocationData) != 0) ? count($LocationData) : "No ";
                return JsonReturn::success($data);
            }
            else
            {
                $data['status'] = false;
                $data['html'] = "";
                $data['total_records'] = "No ";
                return JsonReturn::success($data);
            }
        }
        else
        {

        }
    }

    public function fetchReviews(Request $request)
    {
        $data = [];
        $data['data'] = [];
        $data['hideMoreButton'] = false;
        $data["status"] = false;
        $data["message"] = "Something went wrong!";

        if(empty($request->locationId)) {
            $data['status'] = false;
            $data['message'] = 'Something went wrong. Please reload and try again.';

        } else {

            $FuserLocationReview = FuserLocationReview::with(['fuser'])
                            ->where('location_id', $request->locationId)
                            ->where('fuser_location_review.status', 'publish')
                            ->orderBy('updated_at', 'DESC')
                            ->limit(5);

            if(!empty($request->lastId)) {
                $FuserLocationReview->where('id', '<', $request->lastId);


                $totalRecords = FuserLocationReview::where('location_id', $request->locationId)
                                ->where('fuser_location_review.status', 'publish')
                                ->where('id', '<', $request->lastId)
                                ->count();
            } else {
                $totalRecords = FuserLocationReview::where('location_id', $request->locationId)
                                ->where('fuser_location_review.status', 'publish')
                                ->count();
            }


            if($totalRecords <= 5) {
                $data['hideMoreButton'] = true;
            } 
            
            $FuserLocationReview = $FuserLocationReview->get();


            $data['data'] = $FuserLocationReview;
            $data['status'] = true;
            $data['message'] = 'Reviews fetched successfully.';
        }

        return JsonReturn::success($data);
    }
    public function getLocationByIp(){
        // $ip = '49.34.174.52';
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        // Use JSON encoded string and converts
        // it into a PHP variable
        $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
           
        if($ipdat->geoplugin_countryName){
            Session::put('country',$ipdat->geoplugin_countryName);
            Session::put('user_latitude',$ipdat->geoplugin_latitude);
            Session::put('user_longitude',$ipdat->geoplugin_longitude);
        }
        /*echo 'Country Name: ' . $ipdat->geoplugin_countryName . "\n";
        echo "<br>";
        echo 'City Name: ' . $ipdat->geoplugin_city . "\n";
        echo "<br>";
        echo 'Continent Name: ' . $ipdat->geoplugin_continentName . "\n";
        echo "<br>";
        echo 'Latitude: ' . $ipdat->geoplugin_latitude . "\n";
        echo "<br>";
        echo 'Longitude: ' . $ipdat->geoplugin_longitude . "\n";
        echo "<br>";
        echo 'Currency Symbol: ' . $ipdat->geoplugin_currencySymbol . "\n";
        echo "<br>";
        echo 'Currency Code: ' . $ipdat->geoplugin_currencyCode . "\n";
        echo "<br>";
        echo 'Timezone: ' . $ipdat->geoplugin_timezone;
        echo "<br>";*/
    }
    public function UserCurrentLocation(Request $request){
        if($request->country != ""){
            Session::put('country',$request->country);
            Session::put('user_latitude',$request->lat);
            Session::put('user_longitude',$request->lng);
        }else{
            // $this->getLocationByIp();
        }
    }
}
