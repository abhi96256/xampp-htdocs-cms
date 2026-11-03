<?php

namespace App\Http\Controllers;


use App\Lancer\Utilities;
use App\Models\BudgetRange;
use App\Models\Configuration;
use App\Models\Enquiry;
use App\Models\EnquiryStatus;
use App\Models\PaymentMode;
use App\Models\Project;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;


class ActivitiesController extends Controller
{
    protected $utilities;

    public function __construct(Utilities $utilities)
    {
        // For referencing the Utilities class from our blade templates
        $this->utilities = $utilities;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	 
	 public function index(Request $request){
		  $activities = DB::table('activities')->orderby('id','desc')->get();
		  return view('activities.index',compact('activities'));
	 }
	 
    /* */
}

?>
