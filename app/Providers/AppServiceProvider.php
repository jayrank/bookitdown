<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\ServiceCategory;
use App\Models\Businesstype;
use App\Models\ServicesPrice;
use App\Models\Location;
use DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $bussinessType = Businesstype::get();
        // $servicePriceRange = ServicesPrice::select(DB::raw('MAX(GREATEST(price, special_price)) as service_highest_price, MIN(LEAST(price, special_price)) as service_lowest_price'))->first();
        $servicePriceRange = ServicesPrice::select(DB::raw('MAX(special_price) as service_highest_price, MIN(special_price) as service_lowest_price'))->first();
        /*$searchLocation = Location::select('loc_country')->where('is_deleted',0)->where('loc_country','!=',"")->groupBy('loc_country')->get();*/
        View::share('bussinessType', $bussinessType);
        View::share('servicePriceRange', $servicePriceRange);
        // View::share('searchLocation', $searchLocation);
    }
}
