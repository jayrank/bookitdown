<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Location;
use Session;
use App\JsonReturn;
use File;
use DB;
use Crypt;


class OverviewController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
        // date_default_timezone_set('Asia/Kolkata');
    }

	public function index()
	{
		return view('overview.index');
	}

	public function wallet()
	{
		return view('overview.wallet');
	}
}