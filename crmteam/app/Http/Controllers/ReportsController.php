<?php

namespace App\Http\Controllers;

use App\Lancer\Utilities;
use App\Models\BudgetRange;
use App\Models\Configuration;
use App\Models\Enquiry;
use App\Models\Client;
use App\Models\Incentive;
use App\Models\EnquiryStatus;
use App\Models\PaymentMode;
use App\Models\Project;
use App\Models\Expense;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   public function index(Request $request, $filter=null)
    {
        $search = $request->input('search');
        $query = User::role(['Telecaller','Team Lead' , 'Executive']);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('phone', 'like', "%$search%");
            });
        }

		if(auth()->user()->hasRole(['Admin'])) {
		    $users = $query->orderby('no_of_enquiries_assigned', 'ASC')->get();
		    $data = [];
		    foreach($users as $user){
			    $data [] =[
			    'user_id' => $user->id,
			    'name'=> $user->name,
			    'total_leads'=>$this->getAssignedLeads($user->id),
			    'closed_leads' =>$this->getCloseLeads($user->id),
			    'lost_leads' =>$this->getLostLeads($user->id),
			    'running_leads' =>$this->getRunningLeads($user->id),
			    ];
		    }
		}else{
			$users = $query->where('id',auth()->user()->id)->orderby('no_of_enquiries_assigned', 'ASC')->get();
			$data = [];
			foreach($users as $user){
				$data [] =[
				'user_id' => $user->id,
				'name'=> $user->name,
				'total_leads'=>$this->getAssignedLeads($user->id),
				'closed_leads' =>$this->getCloseLeads($user->id),
				'lost_leads' =>$this->getLostLeads($user->id),
				'running_leads' =>$this->getRunningLeads($user->id),
				];
			}
		}
		
		return view('reports.index',compact('data', 'search'));
		
	}
	public function getAssignedLeads($userid){
		$enquiries = Enquiry::where('assigned_to', $userid)->get();
		$count = count($enquiries);
		return $count;
	}
	public function getLostLeads($userid){
		$enquiries = Enquiry::where('assigned_to', $userid)->where('enquiry_status_id',4)->get();
		$count = count($enquiries);  
		return $count;
	}
	public function getCloseLeads($userid){
		$enquiries = Enquiry::where('assigned_to', $userid)->where('enquiry_status_id',5)->get();
		$count = count($enquiries);  
		return $count;
	}
	
	public function getRunningLeads($userid){
		$enquiries = Enquiry::where('assigned_to', $userid)->where('enquiry_status_id','<>',5)->where('enquiry_status_id','<>',4)->get();
		$count = count($enquiries);
		return $count;
	} 
	public function sell(Request $request, $filter=null)
    {
		if(auth()->user()->hasRole(['Admin'])) {
		$clients = Client::orderby('created_at', 'DESC')->get();
		//echo "<pre>";print_r($clients);exit;
		$data = [];
		foreach($clients as $enq){
			$user = User::find($enq->closed_by);
            $closedByName = $user ? $user->name : ($enq->closed_by_name ?? 'N/A');
			$data [] =[
			'name' => $enq->name,
			'contact_no'=> $enq->contact_no,
			'agreement_value' =>$enq->agreement_value,
			'booking_amount' =>$enq->booking_amount,
			'closed_by' => $closedByName,
			'user_id' => $enq->closed_by,
			];
		}
		}else{
			$clients = Client::where('closed_by',auth()->user()->id)->orderby('created_at', 'DESC')->get();
		//echo "<pre>";print_r($clients);exit;
		$data = [];
		foreach($clients as $enq){
			$user = User::find($enq->closed_by);
            $closedByName = $user ? $user->name : ($enq->closed_by_name ?? 'N/A');
			$data [] =[
			'name' => $enq->name,
			'contact_no'=> $enq->contact_no,
			'agreement_value' =>$enq->agreement_value,
			'booking_amount' =>$enq->booking_amount,
			'closed_by' => $closedByName,
			'user_id' => $enq->closed_by,
			];
		}
		}
		return view('reports.sell',compact('data'));
		
	}
	public function pending(Request $request, $filter=null)
    {
        $assigned_to = $request->input('assigned_to');
        $users = User::role(['Telecaller','Team Lead' , 'Executive'])->get();

		if (auth()->user()->hasRole(['Admin'])) {
            $query = "
				SELECT r.*, 
					   u.name as username, 
					   DATEDIFF(NOW(), r.updated_at) as pending_days
				FROM enquiries r
				LEFT JOIN remarks o ON o.lead_id = r.id
				LEFT JOIN users u ON u.id = r.assigned_to
				WHERE o.lead_id IS NULL
				AND r.assigned_to IS NOT NULL
				AND r.updated_at <= NOW() - INTERVAL 15 DAY
			";
            if ($assigned_to) {
                $query .= " AND r.assigned_to = '$assigned_to'";
            }
			$pending = DB::select($query);
		} else {
			$pending = DB::select("
				SELECT r.*, 
					   u.name as username, 
					   DATEDIFF(NOW(), r.updated_at) as pending_days
				FROM enquiries r
				LEFT JOIN remarks o ON o.lead_id = r.id
				LEFT JOIN users u ON u.id = r.assigned_to
				WHERE o.lead_id IS NULL
				AND r.assigned_to = '".auth()->id()."'
				AND r.updated_at <= NOW() - INTERVAL 15 DAY
			");
		}


		return view('reports.pending',compact('pending', 'users', 'assigned_to'));
		
	}
	public function incentive(Request $request, $filter=null)
    {
		//echo "Hi";exit;
		if(auth()->user()->hasRole(['Admin'])) {
			$clients = DB::table('incentive')->where('user_id','!=',1)->get();
			//echo "<pre>";print_r($clients);exit;
			$data = [];
			foreach($clients as $enq){
				$user = User::find($enq->user_id);
				$client = Client::find($enq->client_id);
				$project = $client ? Project::find($client->project_id) : null;
				$data[] =[
				'lead_id' => $project ? $project->name : 'N/A',
				'client_name' =>$client ? $client->name : 'N/A',
				'user_name' =>$user ? $user->name : 'N/A',
                'closed_by' => $client ? ($client->closedBy->name ?? ($client->closed_by_name ?? 'N/A')) : 'N/A',
				'incentive_percentage' => $enq->incentive_percentage,
				'incentive_amount'=>$enq->incentive_amount,
				];
			}
		}else{
			$clients = DB::table('incentive')->where('user_id',auth()->user()->id)->get();

			$data = [];
			foreach($clients as $enq){
				$user = User::find($enq->user_id);
				$client = Client::find($enq->client_id);
				$project = $client ? Project::find($client->project_id) : null;
				$data[] =[
				'lead_id' => $project ? $project->name : 'N/A',
				'client_name' =>$client ? $client->name : 'N/A',
				'user_name' =>$user ? $user->name : 'N/A',
                'closed_by' => $client ? ($client->closedBy->name ?? ($client->closed_by_name ?? 'N/A')) : 'N/A',
				'incentive_percentage' => $enq->incentive_percentage,
				'incentive_amount'=>$enq->incentive_amount,
				];
			}

		}
		//echo "<pre>";print_r($data);exit;
		
		return view('reports.incentive',compact('data'));
		
	}public function cincentive(Request $request, $filter=null)
    {
		//echo "Hi";exit;
		if(auth()->user()->hasRole(['Admin'])) {
			$clients = DB::table('incentive')->where('user_id',1)->get();
			//echo "<pre>";print_r($clients);exit;
			$data = [];
			foreach($clients as $enq){
				$user = User::find($enq->user_id);
				$client = Client::find($enq->client_id);
				$project = $client ? Project::find($client->project_id) : null;
				$data[] =[
				'lead_id' => $project ? $project->name : 'N/A',
				'client_name' =>$client ? $client->name : 'N/A',
				'user_name' =>$user ? $user->name : 'N/A',
				'closed_by' => $client ? ($client->closedBy->name ?? ($client->closed_by_name ?? 'N/A')) : 'N/A',
				'incentive_percentage' => $enq->incentive_percentage,
				'incentive_amount'=>$enq->incentive_amount,
				];
			}
		}else{
			$clients = DB::table('incentive')->where('user_id',auth()->user()->id)->get();

			$data = [];
			foreach($clients as $enq){
				$user = User::find($enq->user_id);
				$client = Client::find($enq->client_id);
				$project = $client ? Project::find($client->project_id) : null;
				$data[] =[
				'lead_id' => $project ? $project->name : 'N/A',
				'client_name' =>$client ? $client->name : 'N/A',
				'user_name' =>$user ? $user->name : 'N/A',
				'closed_by' => $client ? ($client->closedBy->name ?? ($client->closed_by_name ?? 'N/A')) : 'N/A',
				'incentive_percentage' => $enq->incentive_percentage,
				'incentive_amount'=>$enq->incentive_amount,
				];
			}

		}
		//echo "<pre>";print_r($data);exit;
		
		return view('reports.cincentive',compact('data'));
		
	}public function expenses(Request $request, $filter=null)
    {
		//echo "Hi";exit;
		if(auth()->user()->hasRole(['Admin'])) {
			$clients = DB::table('expenses')->whereNull('deleted_at')->get();
			//echo "<pre>";print_r($clients);exit;
			$data = [];
			foreach($clients as $enq){
				if($enq->last_edited_by != ''){
					$user = User::find($enq->last_edited_by);
					$name = $user ? $user->name : 'N/A';
				}else{
					$name = '';
				}
				$user1 = User::find($enq->created_by);
				//$client = Client::find($enq->client_id);
				$data[] =[
				'id' => $enq->id,
				'payee' => $enq->payee,
				'generated_by' => $user1 ? $user1->name : 'N/A',
				'amount' =>$enq->amount_paid,
				'date_of_payment' =>$enq->date_of_payment,
				'bill_no' => $enq->bill_no,
				'status'=>$enq->status,
				'cleared_by'=> $name,
				'paid_date' => $enq->paid_date ?? null,
				'rejected_date' => $enq->rejected_date ?? null,
				];
			}
		}else{
			$clients = DB::table('expenses')->whereNull('deleted_at')->where('created_by',auth()->user()->id)->get();
			$data = [];
			foreach($clients as $enq){
				if($enq->last_edited_by != ''){
					$user = User::find($enq->last_edited_by);
					$name = $user ? $user->name : 'N/A';
				}else{
					$name = '';
				}
				$user1 = User::find($enq->created_by);
				//$client = Client::find($enq->client_id);
				$data[] =[
				'id' => $enq->id,
				'payee' => $enq->payee,
				'generated_by' => $user1 ? $user1->name : 'N/A',
				'amount' =>$enq->amount_paid,
				'date_of_payment' =>$enq->date_of_payment,
				'bill_no' => $enq->bill_no,
				'status'=>$enq->status,
				'cleared_by'=> $name,
				'paid_date' => $enq->paid_date ?? null,
				'rejected_date' => $enq->rejected_date ?? null,
				];
			}

		}
		//echo "<pre>";print_r($data);exit;
		
		return view('reports.expenses',compact('data'));
		
	}
	public function transfer(Request $request, $filter=null)
    {
		if(auth()->user()->hasRole(['Admin'])) {
		$transfer =  DB::table('leads_transfer')->orderBy('lead_id','desc')->get();
		$data = [];
		foreach($transfer as $enq){
				$p_user = User::find($enq->previous_user);
				if(empty($p_user)){
					$p_name = 'N/A';
				}else{
					$p_name = $p_user->name;
				}
				$n_user = User::find($enq->new_user);
				if(empty($n_user)){
					$n_name = 'N/A';
				}else{
					$n_name = $n_user->name;
				}
				$data[] =[
					'lead_id' => $enq->lead_id,
					'previous_user' =>$p_name,
					'new_user' =>$n_name,
					'assigned_on' => $enq->created_at,
				];
			}
		}else{
			$transfer =  DB::table('leads_transfer')->where('new_user',auth()->user()->id)->orderBy('lead_id','desc')->get();
			$data = [];
			foreach($transfer as $enq){
				$p_user = User::find($enq->previous_user);
				if(empty($p_user)){
					$p_name = 'N/A';
				}else{
					$p_name = $p_user->name;
				}
				$n_user = User::find($enq->new_user);
				if(empty($n_user)){
					$n_name = 'N/A';
				}else{
					$n_name = $n_user->name;
				}
				$data[] =[
					'lead_id' => $enq->lead_id,
					'previous_user' =>$p_name,
					'new_user' =>$n_name,
					'assigned_on' => $enq->created_at,
				];
			}
		}
		//echo "<pre>";print_r($data);exit;
		return view('reports.transfer',compact('data'));
	}
}
