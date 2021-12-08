<?php  
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Staff;   

class Shortener extends Model
{
	protected $table = 'short_urls';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
		'shortname',
		'is_generate_shortlink',
		'long_url',
		'short_code',
		'hits',
		'created_at',
		'updated_at'
    ];
	
	protected $hidden = [];
	
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];
	
	protected static $chars = "abcdfghjkmnpqrstvwxyz|ABCDFGHJKLMNPQRSTVWXYZ|0123456789";
    protected static $checkUrlExists = false;
    protected static $codeLength = 7;
	
	public static function urlToShortCode($url){
		
        $shortCode = self::urlExistsInDB($url);
        if($shortCode == false){
            $shortCode = self::createShortCode($url);
        }
        return $shortCode;
    } 
	
	protected function validateUrlFormat($url){
        return filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED);
    }
	
	protected function verifyUrlExists($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch,  CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        $response = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return (!empty($response) && $response != 404);
    }
	
	protected static function urlExistsInDB($url){
		$Shortener = Shortener::select('short_code')->where('long_url', $url)->first();
        return (empty($Shortener)) ? false : $Shortener->short_code;
    }
	
	protected static function createShortCode($url){
        $shortCode = self::generateRandomString(Shortener::$codeLength);
        $id = self::insertUrlInDB($url, $shortCode);
        return $shortCode;
    }
	
	protected static function generateRandomString($length = 6){
        $sets = explode('|', Shortener::$chars);
        $all = '';
        $randString = '';
        foreach($sets as $set){
            $randString .= $set[array_rand(str_split($set))];
            $all .= $set;
        }
        $all = str_split($all);
        for($i = 0; $i < $length - count($sets); $i++){
            $randString .= $all[array_rand($all)];
        }
        $randString = str_shuffle($randString);
        return $randString;
    }
	
	protected static function insertUrlInDB($url, $code){
		
		$CurrentUser = Auth::user();
		$is_admin = $CurrentUser->is_admin;
		
		if($is_admin == 1){
			$CurrentStaff = Staff::find(Auth::id());
			$AdminId = $CurrentStaff->user_id;
			$UserId  = Auth::id();
		} else {
			$AdminId = Auth::id();
			$UserId  = Auth::id();
		}
		
		$Shortener = Shortener::create([
			'user_id'    => $AdminId,
			'long_url'   => $url,
			'short_code' => $code,
			'created_at' => date("Y-m-d H:i:s"),
		]);
		
        return $Shortener->id;
    }
	
	public function shortCodeToUrl($code, $increment = true){
        if(empty($code)) {
            throw new Exception("No short code was supplied.");
        }

        if(self::validateShortCode($code) == false){
            throw new Exception("Short code does not have a valid format.");
        }

        $urlRow = self::getUrlFromDB($code);
        if(empty($urlRow)){
            throw new Exception("Short code does not appear to exist.");
        }

        if($increment == true){
            self::incrementCounter($urlRow["id"]);
        }

        return $urlRow["long_url"];
    }
	
	protected function validateShortCode($code){
        $rawChars = str_replace('|', '', Shortener::$chars);
        return preg_match("|[".$rawChars."]+|", $code);
    }
	
	protected function getUrlFromDB($code){
		$Shortener = Shortener::select('id','long_url')->where('short_code', $code)->first()->toArray();
        return (empty($Shortener)) ? false : $Shortener;
    }
	
	protected function incrementCounter($id){
		$Shortener = Shortener::find($id);
		$Shortener->hits = $Shortener->hits + 1;
		$Shortener->save();
    }
}