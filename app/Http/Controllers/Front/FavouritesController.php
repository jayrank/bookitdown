<?php
namespace App\Http\Controllers\Front;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\JsonReturn;
use App\Models\frontUser;
use App\Models\fuserFavourites;
use App\Models\Location_image;
use Session;
  
class FavouritesController extends Controller
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

    public function index()
    {
        $LocationData = fuserFavourites::where([
                            'fuser_favourites.fuser_id' => Auth::guard('fuser')->user()->id,
                            'locations.is_deleted' => 0
                        ])
                        ->leftJoin('locations', 'locations.id', 'fuser_favourites.location_id')
                        ->select('locations.*')
                        ->get();
                        
        foreach ($LocationData as $key => $value) {
            $LocationImage = Location_image::where('location_id',$value->id)->get();
            $LocationData[$key]->images = $LocationImage;
        }
        return view('frontend/favourites',compact('LocationData'));
    }

    public function toggleFavourite(Request $request)
    {
        $data = [];
        $data["status"] = false;
        $data["message"] = "Something went wrong!";
        $data['marked_favourite'] = false;

        if(!empty($request->location_id)) {

            $fuserId = Auth::guard('fuser')->user()->id;

            if(fuserFavourites::where(['fuser_id' => $fuserId, 'location_id' => $request->location_id])->exists()) {
                fuserFavourites::where(['fuser_id' => $fuserId, 'location_id' => $request->location_id])->delete();

                $data["status"] = true;
                $data["message"] = 'Removed from favourites.';
                $data['marked_favourite'] = false;
            } else {
                fuserFavourites::create([
                    'fuser_id' => $fuserId,
                    'location_id' => $request->location_id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

                $data["status"] = true;
                $data["message"] = 'Added in favourites.';
                $data['marked_favourite'] = true;
            }

        } else {
            $data["status"] = false;
            $data["message"] = 'Received empty request.';
            $data['marked_favourite'] = false;
        }

        return JsonReturn::success($data);

    }
}
