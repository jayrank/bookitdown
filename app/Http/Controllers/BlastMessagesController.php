<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\JsonReturn;
use App\Models\Clients;
use DataTables;

class BlastMessagesController extends Controller
{
   
    public function __construct()
    {
        $this->middleware('auth');
    }

   
    public function index()
    {
        return view('blastMessages.marketing_blast_messages');
    }
	
	
}
