<?php

namespace App\Http\Controllers;


use App\Lancer\Utilities;
use App\Lancer\PhpXlsxGenerator;
use App\Lancer\fpdf\PDF;
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


class EnquiriesController extends Controller
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
    public function index(Request $request, $filter=null)
    {
        // Reference to the Utilities class
        $utilities = $this->utilities;

        if($filter === null) {
            if($request->session()->has('filter')) {
                $filter = session('filter');
            }else{
				$filter = 'all';
			}
        } 
		$enquiries = '';
        $user = auth()->user();
        switch ($filter) {
            case 'active':
                session(['filter' => $filter]);

                if($user->hasRole(['Admin', 'Telecaller'])) {
                    $enquiries = Enquiry::with(['assignedTo', 'enquiry_status', 'tl1', 'tl2', 'tl3', 'tl4', 'tl5'])->where('is_lost', false)->latest()->paginate(20);
                } elseif($user->hasRole('Team Lead')) {
                    $enquiries = Enquiry::with(['assignedTo', 'enquiry_status', 'tl1', 'tl2', 'tl3', 'tl4', 'tl5'])->where('is_lost', false)
                        ->where(function($query) use ($user) {
                            $query->where('assigned_to', $user->id)
                                ->orWhere('tl_1', $user->id)
                                ->orWhere('tl_2', $user->id)
                                ->orWhere('tl_3', $user->id)
                                ->orWhere('tl_4', $user->id)
                                ->orWhere('tl_5', $user->id);
                        })->latest()->paginate(20);
                } else {
                    $enquiries = Enquiry::with(['assignedTo', 'enquiry_status', 'tl1', 'tl2', 'tl3', 'tl4', 'tl5'])->where('is_lost', false)->where('assigned_to', $user->id)->latest()->paginate(20);
                }

                break;

            case 'lost':
                session(['filter' => $filter]);

                if($user->hasRole(['Admin', 'Telecaller'])) {
                    $enquiries = Enquiry::where('is_lost', true)->latest()->paginate(20);
                } elseif($user->hasRole('Team Lead')) {
                    $enquiries = Enquiry::where('is_lost', true)
                        ->where(function($query) use ($user) {
                            $query->where('assigned_to', $user->id)
                                ->orWhere('tl_1', $user->id)
                                ->orWhere('tl_2', $user->id)
                                ->orWhere('tl_3', $user->id)
                                ->orWhere('tl_4', $user->id)
                                ->orWhere('tl_5', $user->id);
                        })->latest()->paginate(20);
                } else {
                    $enquiries = Enquiry::where('is_lost', true)->where('assigned_to', $user->id)->latest()->paginate(20);
                }

                break;

            default:
                $filter = 'all';
                session(['filter' => $filter]);

                if($user->hasRole(['Admin', 'Telecaller'])) {
                    $enquiries = Enquiry::with(['assignedTo', 'enquiry_status', 'tl1', 'tl2', 'tl3', 'tl4', 'tl5'])->latest()->paginate(20);
                } elseif($user->hasRole('Team Lead')) {
                    $enquiries = Enquiry::with(['assignedTo', 'enquiry_status', 'tl1', 'tl2', 'tl3', 'tl4', 'tl5'])
                        ->where(function($query) use ($user) {
                            $query->where('assigned_to', $user->id)
                                ->orWhere('tl_1', $user->id)
                                ->orWhere('tl_2', $user->id)
                                ->orWhere('tl_3', $user->id)
                                ->orWhere('tl_4', $user->id)
                                ->orWhere('tl_5', $user->id);
                        })->latest()->paginate(20);
                } else {
                    $enquiries = Enquiry::with(['assignedTo', 'enquiry_status', 'tl1', 'tl2', 'tl3', 'tl4', 'tl5'])->where('assigned_to', $user->id)->latest()->paginate(20);
                }

                break;
        }
		if($request->user_id && $request->status == 'closed'){
			$enquiries = Enquiry::where([['assigned_to', $request->user_id],['enquiry_status_id', 5]])->latest()->paginate(20);
		}else if($request->user_id ){
			$enquiries = Enquiry::where('assigned_to', $request->user_id)->latest()->paginate(20);
		}
		if($request->assigned_to){
			$leads = explode(",",$request->selected_leads);
			foreach($leads as $l){
				$enquiry = Enquiry::where('id', $l)->first();
				$assignee = User::where('id', $request->assigned_to)->first();
				$enquiry->assignedTo()->associate($assignee);
				
                // Copy hierarchy from assignee to enquiry
                $enquiry->tl_1 = $assignee->tl_1;
                $enquiry->tl_2 = $assignee->tl_2;
                $enquiry->tl_3 = $assignee->tl_3;
                $enquiry->tl_4 = $assignee->tl_4;
                $enquiry->tl_5 = $assignee->tl_5;

				$assignee->update([
					'no_of_enquiries_assigned' => ($assignee->no_of_enquiries_assigned + 1),
				]);
				
				if($request->assigned_to){
					$insert_update  = DB::table('notifications')
					->Insert(
						['user_id' => $request->assigned_to,'notification_text' => "New Lead has been assigned to you","status"=>0],
					);
					
				}
				if($request->assigned_to){
					$insert_update  = DB::table('activities')
					->Insert(
						['user' => $request->assigned_to,'activity_text' => "New Lead(s) has been assigned to ".$assignee->name],
					);
					
				}

				$enquiry->save();
				
			}
			$users = User::role(['Team Lead' , 'Executive'])->orderby('no_of_enquiries_assigned', 'ASC')->get();
				 return redirect(route('enquiries.index', ['enquiries' => $enquiries,
				 'utilities'=> $utilities, 'filter'=>$filter,'users'=>$users]));
		}
		DB::enableQueryLog();

		if($request->daterange ) {
			$date = explode(" - ",$request->daterange);
			// All users can search by date range
			$enquiries = Enquiry::whereBetween('created_at', [$date[0]." 00:00:00", $date[1]." 23:59:59"])->latest()->paginate(20);
        }
		if($request->advance) {
			DB::enableQueryLog();

			$data = $request->advance;
			
			//$enq = Enquiry::where('name', 'like',  $data['name'] );
			$where = [];
			if(!empty($data['name'])){
				$where[] = ['name','like','%'.$data['name'].'%'];
			}
			if(!empty($data['business_name'])){
				$where[] = ['business_name','like','%'.$data['business_name'].'%'];;
			}
			if(!empty($data['email'])){
				$where[] = ['email','like','%'.$data['email'].'%'];
			}
			if(!empty($data['contact_no'])){
				$where[] = ['contact_no','like','%'.$data['contact_no'].'%'];
			}
			if(!empty($data['assigned_to'])){
				$where['assigned_to'] = $data['assigned_to'];
			}
			if(!empty($data['project'])){
				$where['project_id'] = $data['project'];
			}
			if(!empty($data['configuration'])){
				$where['configuration_id'] = $data['configuration'];
			}
			if(!empty($data['budget_range'])){
				$where['budget_range_id'] = $data['budget_range'];
			}
			$enq =Enquiry::where($where );
			$enquiries = $enq->latest()->paginate(20);
			//echo "<pre>";print_r(DB::getQueryLog());exit;
        }
		if(empty($enquiries)){
			$enquiries = Enquiry::latest()->paginate(20);
		}
		//dd(DB::getQueryLog());

		$users = User::role(['Team Lead' , 'Executive'])->orderby('no_of_enquiries_assigned', 'ASC')->get();
		$projects = Project::all();
        $configurations = Configuration::all();
        $duplicate_counts = Enquiry::select(DB::raw('trim(contact_no) as contact_no'), DB::raw('count(*) as total'))
            ->groupBy(DB::raw('trim(contact_no)'))
            ->having('total', '>', 1)
            ->pluck('total', 'contact_no')
            ->toArray();

        $current_contact_nos = $enquiries->pluck('contact_no')->map(function($val) { return trim($val); })->unique()->toArray();
        $dup_contact_nos = array_filter($current_contact_nos, function($c) use ($duplicate_counts) {
            return isset($duplicate_counts[$c]);
        });

        $duplicate_ranks = [];
        if (!empty($dup_contact_nos)) {
            $dup_records = Enquiry::select('id', DB::raw('trim(contact_no) as c_no'))
                ->whereIn(DB::raw('trim(contact_no)'), $dup_contact_nos)
                ->orderBy('created_at', 'ASC')
                ->orderBy('id', 'ASC')
                ->get();
                
            $running_counts = [];
            foreach ($dup_records as $rec) {
                $c = $rec->c_no;
                if (!isset($running_counts[$c])) {
                    $running_counts[$c] = 0;
                }
                $running_counts[$c]++;
                $duplicate_ranks[$rec->id] = $running_counts[$c];
            }
        }

        return view('enquiries.index', compact('enquiries', 'utilities','projects', 'configurations', 'filter','users', 'duplicate_counts', 'duplicate_ranks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $enquiry_statuses = EnquiryStatus::all();
        $projects = Project::all();
        $configurations = Configuration::all();
        $budget_ranges = BudgetRange::all();
        $users = User::role(['Team Lead' , 'Executive'])->get();

        return view('enquiries.create', compact('enquiry_statuses', 'projects', 'configurations', 'budget_ranges', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'contact_no' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:6',
            'enquiry_status' => 'required',
            // 'address' => 'required',
        ]);

        if ($request->input('email')) {
            $this->validate($request, [
                'email' => 'email',
            ]);
        }

        DB::beginTransaction();
        $dup_count = Enquiry::where('contact_no', $request->input('contact_no'))->count();
        if ($dup_count >= 5) {
            return redirect()->back()->withErrors([
                'db_error' => "Maximum limit of 5 leads reached for this Phone number",
            ]);
        }
        try {
            $enquiry = Enquiry::create([
                'created_by' => auth()->id(),
                'name' => ucwords($request->input('name')),
                'a_contact_no' => $request->input('a_contact_no'),
                'email' => $request->input('email'),
                'contact_no' => $request->input('contact_no'),
               // 'subject' => $request->input('subject'),
                'address' => $request->input('address'),
                'budget_range' => $request->input('budget_range') ?? '',
                'other_config' => $request->input('other_config'),
                'reference_by' => $request->input('reference_by'),
                'tl_1' => $request->input('tl_1'),
                'tl_2' => $request->input('tl_2'),
                'tl_3' => $request->input('tl_3'),
                'tl_4' => $request->input('tl_4'),
                'tl_5' => $request->input('tl_5'),
            ]);

            $status = EnquiryStatus::where('id', $request->input('enquiry_status'))->first();
            $enquiry->enquiry_status()->associate($status);

            $project = Project::where('id', $request->input('project'))->first();
            $enquiry->project()->associate($project);

            $project1 = Project::where('id', $request->input('project_1'))->first();
            $enquiry->project_1()->associate($project1);

            $project2 = Project::where('id', $request->input('project_2'))->first();
            $enquiry->project_2()->associate($project2);

            $project3 = Project::where('id', $request->input('project_3'))->first();
            $enquiry->project_3()->associate($project3);

            $project4 = Project::where('id', $request->input('project_4'))->first();
            $enquiry->project_4()->associate($project4);

            $configuration = Configuration::where('id', $request->input('configuration'))->first();
            $enquiry->configuration()->associate($configuration);

            
            // If assigned_to is not selected or user is not admin, auto-assign to the creator
            $assigned_to_id = $request->input('assigned_to') ?: auth()->user()->id;

			if($assigned_to_id){
                $assignee = User::where('id', $assigned_to_id)->first();
                $enquiry->assignedTo()->associate($assignee);
                
                // Copy hierarchy from assignee to enquiry
                $enquiry->tl_1 = $assignee->tl_1;
                $enquiry->tl_2 = $assignee->tl_2;
                $enquiry->tl_3 = $assignee->tl_3;
                $enquiry->tl_4 = $assignee->tl_4;
                $enquiry->tl_5 = $assignee->tl_5;

                $assignee->update([
                    'no_of_enquiries_assigned' => ($assignee->no_of_enquiries_assigned + 1),
                ]);
			
				$insert_update  = DB::table('notifications')
				->Insert(
					['user_id' => $assigned_to_id,'notification_text' => "New Lead has been assigned to you","status"=>0],
				);
				$insert_update  = DB::table('leads_transfer')
				->Insert(
					['lead_id' => $enquiry->id,'previous_user'=>0,'new_user'=>$assigned_to_id],
				);
				
			}
			$insert_update  = DB::table('activities')
					->Insert(
						['user' => $assigned_to_id,'activity_text' => "New Lead(s) has been created by ".auth()->user()->name],
					);
			if($assigned_to_id){
					$insert_update  = DB::table('activities')
					->Insert(
						['user' => $assigned_to_id,'activity_text' => "New Lead(s) has been assigned to ".$assignee->name],
					);
					
				}
			
			if($request->input('subject')){
				$insert_update  = DB::table('remarks')
				->Insert(
					['user_id' => auth()->user()->id,'lead_id' => $enquiry->id,"remarks"=>$request->input('subject'),'created_by'=>NOW(),'updated_by'=>NOW()],
				);
				DB::table('follow_ups')
				->Insert(
					['enquiry_id' => $enquiry->id, 'date_time' => NOW(), 'remark' => $request->input('subject'), 'outcome' => $request->input('subject'), 'created_at' => NOW(), 'updated_at' => NOW()]
				);
			}
            $enquiry->save();
			
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors([
                'db_error' => $e->getMessage(),
            ]);
        }
        DB::commit();

                return redirect(route('enquiries.index'));

    }

    public function storeFbLead(Request $request)
    {
        if($request->isMethod('get')) {
            $challenge = $request->hub_challenge;
            $verify_token = $request->hub_verify_token;

            if ($verify_token === env('FB_WEBHOOK_VERIFY_TOKEN')) {
                echo $challenge;
            }
        } else {
            $input = json_decode($request->getContent());

            $entry = $input->entry[0];
            // $entry_id = $entry->id;
            $change = $entry->changes[0]->value;

            // $form_id = $change->form_id;
            // $page_id = $change->page_id;
            $leadgen_id = $change->leadgen_id;

            DB::beginTransaction();
            try {
                $lead_response = Http::get(Utilities::getLeadDetailsEndpoint($leadgen_id));
                $lead_data = $lead_response->object();
                // $created_time = $lead_data->created_time;
                $field_data = $lead_data->field_data;

                $name = '';
                $contact_no = '';

                foreach ($field_data as $lead) {
                    if($lead->name === 'full_name') {
                        $name = $lead->values[0];
                    }

                    if($lead->name === 'phone_number') {
                        $contact_no = $lead->values[0];
                    }
                }

                $enquiry = Enquiry::create([
                    'name' => ucwords($name),
                    'contact_no' => $contact_no,
                    'subject' => 'FB Lead',
                ]);

                $status = EnquiryStatus::where('id', 1)->first();
                $enquiry->enquiry_status()->associate($status);
                $enquiry->saveQuietly();

                // Send email to sales team regarding the new lead
                $data = [
                    'enquiry' => $enquiry,
                ];
                Mail::send('emails.newfblead', $data, function($message) use ($enquiry) {
                    $message->to(Utilities::SALES_EMAIL, Utilities::SALES_RECEIVER_NAME)->subject('New lead from facebook ad campaign');
                });
            } catch (Exception $e) {
                DB::rollBack();
                Log::error(print_r($e, true));
            }
            DB::commit();

        }
    }

    public function storePpcLead(Request $request)
    {
        $redirectUrl = $request->input('redirect');
        DB::beginTransaction();
        try {
            $enquiry = Enquiry::create([
                'created_by' => auth()->id(),
                'name' => ucwords($request->input('name')),
                'contact_no' => $request->input('contact_no'),
                'subject' => $request->input('subject'),
            ]);

            $status = EnquiryStatus::where('id', 1)->first();
            $enquiry->enquiry_status()->associate($status);

            $assignee = User::role(['Team Lead' , 'Executive'])->orderby('no_of_enquiries_assigned', 'ASC')->first();
            $enquiry->assignedTo()->associate($assignee);
            $assignee->update([
                'no_of_enquiries_assigned' => ($assignee->no_of_enquiries_assigned + 1),
            ]);
            $enquiry->saveQuietly();

            // Send email to sales team regarding the new lead
            $data = [
                'enquiry' => $enquiry,
            ];

            Mail::send('emails.newppclead', $data, function($message) use ($assignee, $request) {
                $message->to($assignee->email, $assignee->name)->subject($request->input('subject'));
            });

        } catch (Exception $e) {
            DB::rollBack();
            Log::error(print_r($e, true));
        }
        DB::commit();

        return redirect($redirectUrl);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $utilities = $this->utilities;
        $enquiry = Enquiry::where('id', $id)->first();

        // if($enquiry->enquiry_status->id === 4) {
        //     return redirect(route('enquiries.index'));
        // }

        $followups = $enquiry->follow_ups;
        $projects = Project::all();
        $enquiry_statuses = EnquiryStatus::paginate(3);
		$remarks = DB::table('remarks')->leftJoin('users', 'remarks.user_id', '=', 'users.id')->where('lead_id',$id)->first();
		//echo "<pre>";print_r($remarks->remarks);exit;
        return view('enquiries.show', compact('enquiry', 'followups', 'projects', 'enquiry_statuses', 'utilities','remarks'));
    }
	 public function import(Request $request)
    {
             return view('enquiries.import');
       
    }
	 public function store_import(Request $request){
           if ($request->hasFile('import_file')) {
			   $file = $request->file('import_file');
			   $filename =  $file->getClientOriginalName();
			 //  $profile_photo = $image->getClientOriginalName();
				$destinationPath = public_path('/storage/galeryImages/');
				$file->move($destinationPath, $filename);
			    $file = fopen(public_path('/storage/galeryImages/').'/'.$filename, "r");
				$i = 0;
          while (($getData = fgetcsv($file, 10000, ",")) !== FALSE)
           {
			   if($i>0){
				   $dup_count = Enquiry::where('contact_no', $getData[4])->count();
					if($dup_count < 5){
						
              $enquiry = Enquiry::create([
                'created_by' => auth()->id(),
                'name' => ucwords($getData[1]),
                'business_name' => $getData[2],
                'email' => $getData[3],
                'contact_no' => $getData[4],
               // 'subject' => $getData[10],
                'address' => $getData[9],
            ]);

            $status = EnquiryStatus::where('status',ucwords( $getData[8]))->first();
            $enquiry->enquiry_status()->associate($status);

            $project = Project::where('name', $getData[5])->first();
            $enquiry->project()->associate($project);

            $configuration = Configuration::where('name', $getData[5])->first();
            $enquiry->configuration()->associate($configuration);

            $budget_range = BudgetRange::where('range', $getData[7])->first();
            $enquiry->budget_range()->associate($budget_range);
			if($getData[10]){
				$insert_update  = DB::table('remarks')
				->Insert(
					['user_id' => 1,'lead_id' => $enquiry->id,"remarks"=>$getData[10],'created_by'=>NOW(),'updated_by'=>NOW()],
				);
				
			}

            $enquiry->save();
			   }
			 
           }
		   $i++;
		   }
      
           fclose($file);  
		   }
		   return redirect(route('enquiries.index'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $enquiry = Enquiry::where('id', $id)->first();

        // if($enquiry->enquiry_status->id === 4) {
        //     return redirect(route('enquiries.index'));
        // }

        $enquiry_statuses = EnquiryStatus::paginate(3);
        $projects = Project::all();
        $configurations = Configuration::all();
        //$budget_ranges = BudgetRange::all();
        $users = User::role(['Team Lead' , 'Executive'])->get();
        $remarks = DB::table('remarks')->select('remarks.*', 'users.name')->leftJoin('users', 'remarks.user_id', '=', 'users.id')->where('lead_id',$id)->orderby('id','desc')->get();
		//echo "<pre>"; print_r($remarks);exit;
        return view('enquiries.edit', compact('enquiry', 'enquiry_statuses', 'projects', 'configurations',  'users','remarks'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         $this->validate($request, [
            'name' => 'required',
            'contact_no' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:6',
            'enquiry_status' => 'required',
            // 'address' => 'required',
        ]); 

        DB::beginTransaction();
        try {
			
			$enquiry = Enquiry::where('id', $id)->first();
			//echo "<pre>";print_r($enquiry);exit;

			if(!empty($enquiry->assignedTo->id)){
				$previous_assignee = User::where('id', $enquiry->assignedTo->id)->first();
				$previous_assignee->update([
					'no_of_enquiries_assigned' => ($previous_assignee->no_of_enquiries_assigned - 1),
				]);
			}
            $enquiry->update([
                'name' => ucwords($request->input('name')),
                'a_contact_no' => $request->input('a_contact_no'),
                'email' => $request->input('email'),
                'contact_no' => $request->input('contact_no'),
               // 'subject' => $request->input('subject'),
                'address' => $request->input('address'),
                'budget_range' => $request->input('budget_range') ?? '',
                'other_config' => $request->input('other_config'),
                'reference_by' => $request->input('reference_by'),
                'tl_1' => $request->input('tl_1'),
                'tl_2' => $request->input('tl_2'),
                'tl_3' => $request->input('tl_3'),
                'tl_4' => $request->input('tl_4'),
                'tl_5' => $request->input('tl_5'),
            ]);
			
            $status = EnquiryStatus::where('id', $request->input('enquiry_status'))->first();
            $enquiry->enquiry_status()->associate($status);
			if($request->input('project')){
				$project = Project::where('id', $request->input('project'))->first();
				$enquiry->project()->associate($project);
			}

			if($request->input('project_1')){
				$project1 = Project::where('id', $request->input('project_1'))->first();
				$enquiry->project_1()->associate($project1);
			}

			if($request->input('project_2')){
				$project2 = Project::where('id', $request->input('project_2'))->first();
				$enquiry->project_2()->associate($project2);
			}

			if($request->input('project_3')){
				$project3 = Project::where('id', $request->input('project_3'))->first();
				$enquiry->project_3()->associate($project3);
			}

			if($request->input('project_4')){
				$project4 = Project::where('id', $request->input('project_4'))->first();
				$enquiry->project_4()->associate($project4);
			}
			
			if($request->input('configuration')){
				$configuration = Configuration::where('id', $request->input('configuration'))->first();
				$enquiry->configuration()->associate($configuration);
			}
			
			
			if($request->input('assigned_to')){ 
			    $previous_assignee = User::where('id', $enquiry->assignedTo->id)->first();
				$assignee = User::where('id', $request->input('assigned_to'))->first();
				$enquiry->assignedTo()->associate($assignee);

                // Copy hierarchy from assignee to enquiry
                $enquiry->tl_1 = $assignee->tl_1;
                $enquiry->tl_2 = $assignee->tl_2;
                $enquiry->tl_3 = $assignee->tl_3;
                $enquiry->tl_4 = $assignee->tl_4;
                $enquiry->tl_5 = $assignee->tl_5;

				$assignee->update([
					'no_of_enquiries_assigned' => ($assignee->no_of_enquiries_assigned + 1),
				]);
				if($request->input('assigned_to') != $enquiry->assigned_to){
				$insert_update  = DB::table('notifications')
				->Insert(
					['user_id' => $request->input('assigned_to'),'notification_text' => "New Lead has been assigned to you","status"=>0],
				);
				
				$insert_update  = DB::table('activities')
					->Insert(
						['user' => $request->assigned_to,'activity_text' => "New Lead(s) has been assigned to ".$assignee->name],
					);
				}	
				$insert_update  = DB::table('leads_transfer')
				->Insert(
					['lead_id' => $enquiry->id,'previous_user'=>$previous_assignee->id,'new_user'=>$request->input('assigned_to')],
				);
				
			}
			if($request->input('subject')){
				$user =user::findorfail(auth()->user()->id);
				$insert_update  = DB::table('remarks')
				->Insert(
					['user_id' => auth()->user()->id,'lead_id' => $id,"remarks"=>$request->input('subject'),'created_by'=>NOW(),'updated_by'=>NOW()],
				);
				DB::table('follow_ups')
				->Insert(
					['enquiry_id' => $id, 'date_time' => NOW(), 'remark' => $request->input('subject'), 'outcome' => $request->input('subject'), 'created_at' => NOW(), 'updated_at' => NOW()]
				);
				$insert_update  = DB::table('notifications')
				->Insert(
					['user_id' => 1,'notification_text' => 'New Remark " '.$request->input('subject').' " has been added  by '.$user->name,"status"=>0]
				);
				$insert_update  = DB::table('activities')
					->Insert(
						['user' => $request->assigned_to,'activity_text' => "New Remark has been added by ".$user->name],
					);
				
			}

            $enquiry->save();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors([
                'db_error' => $e->getMessage(),
            ]);
        }
        DB::commit();

        return redirect(route('enquiries.index'));
    }

    public function updateStatus(Request $request, $id)
    {
        $this->validate($request, [
            'project' => 'required',
            'enquiry_status' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $status = EnquiryStatus::where('id', $request->input('enquiry_status'))->first();
            $project = Project::where('id', $request->input('project'))->first();
            $enquiry = Enquiry::findorfail($id);
            $enquiry->enquiry_status()->associate($status);
            $enquiry->project()->associate($project);
            $enquiry->save();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect(route('enquiries.show', ['id' => $id]))->withErrors([
                'db_error' => $e->getMessage(),
            ]);
        }
        DB::commit();

        return redirect(route('enquiries.show', ['id' => $id]));
    }

    public function close($id)
    {
        $enquiry = Enquiry::findorfail($id);

        $status = EnquiryStatus::where('id', 5)->first();
        $enquiry->enquiry_status()->associate($status);
        $enquiry->save();
		if(isset($enquiry->assignedTo->id)){
        $previous_assignee = User::where('id', $enquiry->assignedTo->id)->first();
        $previous_assignee->update([
            'no_of_enquiries_assigned' => ($previous_assignee->no_of_enquiries_assigned - 1),
        ]);
		$insert_update  = DB::table('activities')
		->Insert(
			['user' =>  $enquiry->assignedTo->id,'activity_text' => "Lead(s) has been Closed by ".$previous_assignee->name],
		);
		}else{
		$insert_update  = DB::table('activities')
		->Insert(
			['user' =>  1,'activity_text' => "Lead(s) has been Closed by ".'Admin'],
		);
		}		
				

        $projects = Project::all();
        $payment_modes = PaymentMode::all();
        $configurations = Configuration::all();
		$users = User::orderBy('name', 'ASC')->get();
        return view('clients.create', compact('enquiry', 'projects', 'payment_modes', 'configurations','users'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $data = json_decode($request->getContent());

        $enquiry = Enquiry::findorfail($id);
        $status = EnquiryStatus::where('id', 4)->first();
        $enquiry->update([
            'is_lost' => true,
        ]);
        
        if ($enquiry->assignedTo) {
            $previous_assignee = User::where('id', $enquiry->assignedTo->id)->first();
            if ($previous_assignee) {
                $previous_assignee->update([
                    'no_of_enquiries_assigned' => ($previous_assignee->no_of_enquiries_assigned - 1),
                ]);
            }
        }
        
        $enquiry->enquiry_status()->associate($status);
        $enquiry->lost_remark = $data->lost_remark ?? '';
        $enquiry->save();

        return back();
    }
	public function export(Request $request) {
		
		if(auth()->user()->id != 1 && isset($request->code) == false ) {
			$code = rand(100000,999999);
			$code_admin = rand(100000,999999);
			$file_type = $request->file_type ;
			$insert_update  = DB::table('user_codes')
				->updateOrInsert(
					['user_id' => Auth::user()->id],
					['code' => $code,'admin_code'=>$code_admin]
				);
			$user = User::findorfail(Auth::user()->id);
			/* */
			$info = array(
						'name' => $user->name,
						'code' => $code
					);
			$info_1 = array(
						'name' => 'Admin',
						'username' => $user->name,
						'code' => $code_admin
					);
				Mail::send( 'mail_lead', $info, function ($message)
				{
					$message->to(Auth::user()->email, 'A2P Realtech Pvt. Ltd.')
						->subject('2 Factor Authentication for export Leads');
					$message->from('a2prealtechpvtltd@gmail.com', 'A2P Realtech Pvt. Ltd.');
				});
				/* $admin = User::findorfail(1);
				$a_email= $admin->email; */
				Mail::send( 'mail_leads_admin', $info_1, function ($message)
				{
					$message->to('adminrealtech@yopmail.com', 'A2P Realtech Pvt. Ltd.')
						->subject('2 Factor Authentication for Export Leads');
					$message->from('a2prealtechpvtltd@gmail.com', 'A2P Realtech Pvt. Ltd.');
				});
				
				return view('enquiries.2fa',compact('file_type'));

		}else{
		if($request->code && $request->admin_code){
			$data = DB::select('select * from user_codes where user_id ="'.Auth::user()->id.'" and code = "'.$request->code.'" and admin_code ="'.$request->admin_code.'"');
			if($data){
	
				 // return view('enquiries.index');
			}else{
				return back()->withErrors([
					'code' => 'The provided verification code do not match our records.',
				]);
			}
		}	
		if($request->file_type == 'CSV'){
			
        $fileName = 'exported_leads.csv';

        header('Content-Type: application/csv; charset=UTF-8');
		header('Content-Disposition: attachment; filename="' . $fileName . '";');


        $columns = array('Name', 'Business Name', 'Email', 'Mobile','Project', 'Project 1', 'Project 2', 'Project 3', 'Project 4', 'Configuration', 'Budget range','Status','Address','Remarks');
		if(auth()->user()->hasRole(['Admin'])) {
			$data = DB::select('select enquiries.name as username,business_name,email,contact_no, projects.name as project_name , p1.name as p1_name, p2.name as p2_name, p3.name as p3_name, p4.name as p4_name, configurations.name as configuration_name , budget_ranges.range as price_range, enquiry_statuses.status,enquiries.address, enquiries.subject as remarks from enquiries 
			Left Join projects on enquiries.project_id = projects.id 
            Left Join projects as p1 on enquiries.project_id_1 = p1.id
            Left Join projects as p2 on enquiries.project_id_2 = p2.id
            Left Join projects as p3 on enquiries.project_id_3 = p3.id
            Left Join projects as p4 on enquiries.project_id_4 = p4.id
			LEFT JOIN configurations on enquiries.configuration_id = configurations.id 
			LEFT JOIN budget_ranges on enquiries.budget_range_id = budget_ranges.id
			LEFT JOIN enquiry_statuses on enquiries.enquiry_status_id = enquiry_statuses.id
			');
		}else{
			
			$data = DB::select('select enquiries.name as username,business_name,email,contact_no, projects.name as project_name , p1.name as p1_name, p2.name as p2_name, p3.name as p3_name, p4.name as p4_name, configurations.name as configuration_name , budget_ranges.range as price_range, enquiry_statuses.status,enquiries.address, enquiries.subject as remarks from enquiries 
			Left Join projects on enquiries.project_id = projects.id 
            Left Join projects as p1 on enquiries.project_id_1 = p1.id
            Left Join projects as p2 on enquiries.project_id_2 = p2.id
            Left Join projects as p3 on enquiries.project_id_3 = p3.id
            Left Join projects as p4 on enquiries.project_id_4 = p4.id
			LEFT JOIN configurations on enquiries.configuration_id = configurations.id 
			LEFT JOIN budget_ranges on enquiries.budget_range_id = budget_ranges.id
			LEFT JOIN enquiry_statuses on enquiries.enquiry_status_id = enquiry_statuses.id where enquiries.assigned_to = "'.auth()->user()->id.'"
			');

		}
		/*LEFT JOIN budget_ranges on enquiries.budget_range_id = budget_ranges.id */
		    // ob_end_clean();

            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($data as $d) {

                fputcsv($file, array($d->username, $d->business_name,$d->email, $d->contact_no,$d->project_name, $d->p1_name, $d->p2_name, $d->p3_name, $d->p4_name, $d->configuration_name,$d->price_range,$d->status,$d->address,$d->remarks));
            }

            fclose($file);
			   // ob_flush();
    
    // use exit to get rid of unexpected output afterward
			exit();
		}elseif($request->file_type == 'Excel'){
			//echo public_path().'/library/PhpXlsxGenerator.php';
			// Include XLSX generator library 
			require_once public_path().'/library/PhpXlsxGenerator.php'; 
			 
			// Excel file name for download 
			$fileName = "Exported_leads_" . date('Y-m-d') . ".xlsx"; 
			 
			// Define column names 
			$excelData[] = array('Name', 'Business Name', 'Email', 'Mobile', 'Project', 'Project 1', 'Project 2', 'Project 3', 'Project 4', 'Configuration', 'Budget range','Status','Address','Remarks'); 
			 
			// Fetch records from database and store in an array 
			if(auth()->user()->hasRole(['Admin'])) {
			$data = DB::select('select enquiries.name as username,business_name,email,contact_no, projects.name as project_name , p1.name as p1_name, p2.name as p2_name, p3.name as p3_name, p4.name as p4_name, configurations.name as configuration_name , budget_ranges.range as price_range, enquiry_statuses.status,enquiries.address, enquiries.subject as remarks from enquiries 
			Left Join projects on enquiries.project_id = projects.id 
            Left Join projects as p1 on enquiries.project_id_1 = p1.id
            Left Join projects as p2 on enquiries.project_id_2 = p2.id
            Left Join projects as p3 on enquiries.project_id_3 = p3.id
            Left Join projects as p4 on enquiries.project_id_4 = p4.id
			LEFT JOIN configurations on enquiries.configuration_id = configurations.id 
			LEFT JOIN budget_ranges on enquiries.budget_range_id = budget_ranges.id
			LEFT JOIN enquiry_statuses on enquiries.enquiry_status_id = enquiry_statuses.id
			');
			}else{
			
			$data = DB::select('select enquiries.name as username,business_name,email,contact_no, projects.name as project_name , p1.name as p1_name, p2.name as p2_name, p3.name as p3_name, p4.name as p4_name, configurations.name as configuration_name , budget_ranges.range as price_range, enquiry_statuses.status,enquiries.address, enquiries.subject as remarks from enquiries 
			Left Join projects on enquiries.project_id = projects.id 
            Left Join projects as p1 on enquiries.project_id_1 = p1.id
            Left Join projects as p2 on enquiries.project_id_2 = p2.id
            Left Join projects as p3 on enquiries.project_id_3 = p3.id
            Left Join projects as p4 on enquiries.project_id_4 = p4.id
			LEFT JOIN configurations on enquiries.configuration_id = configurations.id 
			LEFT JOIN budget_ranges on enquiries.budget_range_id = budget_ranges.id
			LEFT JOIN enquiry_statuses on enquiries.enquiry_status_id = enquiry_statuses.id where enquiries.assigned_to = "'.auth()->user()->id.'"
			');

		}
			foreach ($data as $d) {

                $lineData = array($d->username, $d->business_name,$d->email, $d->contact_no,$d->project_name, $d->p1_name, $d->p2_name, $d->p3_name, $d->p4_name, $d->configuration_name,$d->price_range,$d->status,$d->address,$d->remarks);
				$excelData[] = $lineData; 
            }
			 
			// Export data to excel and download as xlsx file 
			$xlsx = PhpXlsxGenerator::fromArray( $excelData ); 
			$xlsx->downloadAs($fileName); 
			 
			exit; 
		}else{
			require app_path().'/Lancer/fpdf/html_table.php';
			$pdf=new PDF();
			$pdf->AddPage("L","A3");
			$pdf->SetFont('Arial','',8);
			$html='<table border="1"><tr>
			<td width="150">Name</td>
			<td width="150">Business Name</td>
			<td width="150">Email</td>
			<td width="150">Mobile</td>
			<td width="100">Project</td>
            <td width="100">Project 1</td>
            <td width="100">Project 2</td>
            <td width="100">Project 3</td>
            <td width="100">Project 4</td>
			<td width="150">Configuration</td>
			<td width="150">Budget range</td>
			<td width="150">Status</td>
			<td width="200">Address</td>
			<td width="150">Remarks</td>
			</tr>';
			 
			// Fetch records from database and store in an array 
			if(auth()->user()->hasRole(['Admin'])) {
			$data = DB::select('select enquiries.name as username,business_name,email,contact_no, projects.name as project_name , p1.name as p1_name, p2.name as p2_name, p3.name as p3_name, p4.name as p4_name, configurations.name as configuration_name , budget_ranges.range as price_range, enquiry_statuses.status,enquiries.address, enquiries.subject as remarks from enquiries 
			Left Join projects on enquiries.project_id = projects.id 
            Left Join projects as p1 on enquiries.project_id_1 = p1.id
            Left Join projects as p2 on enquiries.project_id_2 = p2.id
            Left Join projects as p3 on enquiries.project_id_3 = p3.id
            Left Join projects as p4 on enquiries.project_id_4 = p4.id
			LEFT JOIN configurations on enquiries.configuration_id = configurations.id 
			LEFT JOIN budget_ranges on enquiries.budget_range_id = budget_ranges.id
			LEFT JOIN enquiry_statuses on enquiries.enquiry_status_id = enquiry_statuses.id
			');
			}else{
			
			$data = DB::select('select enquiries.name as username,business_name,email,contact_no, projects.name as project_name , p1.name as p1_name, p2.name as p2_name, p3.name as p3_name, p4.name as p4_name, configurations.name as configuration_name , budget_ranges.range as price_range, enquiry_statuses.status,enquiries.address, enquiries.subject as remarks from enquiries 
			Left Join projects on enquiries.project_id = projects.id 
            Left Join projects as p1 on enquiries.project_id_1 = p1.id
            Left Join projects as p2 on enquiries.project_id_2 = p2.id
            Left Join projects as p3 on enquiries.project_id_3 = p3.id
            Left Join projects as p4 on enquiries.project_id_4 = p4.id
			LEFT JOIN configurations on enquiries.configuration_id = configurations.id 
			LEFT JOIN budget_ranges on enquiries.budget_range_id = budget_ranges.id
			LEFT JOIN enquiry_statuses on enquiries.enquiry_status_id = enquiry_statuses.id where enquiries.assigned_to = "'.auth()->user()->id.'"
			');

		}
			foreach ($data as $d) {
				$html .= "<tr><td width='150' style='border:1px solid #000;border-collapse:collapse'> ".$d->username." </td><td width='150' style='border:1px solid #000;border-collapse:collapse'> ";
				if(!empty($d->business_name)){
				$html .= $d->business_name;
				}else{
					$html .= "N/A";
					
				}
				$html .=" </td><td width='150' style='border:1px solid #000;border-collapse:collapse'> ";
				if(!empty($d->email)){
					$html .= $d->email;
				}else{
					$html .= "N/A";
					
				}
				$html .=" </td><td width='150' style='border:1px solid #000;border-collapse:collapse'> ".$d->contact_no." </td><td width='100' style='border:1px solid #000;border-collapse:collapse'> ";
				if(!empty($d->project_name)){
					$html .= $d->project_name;
				}else{
					$html .= "N/A";
					
				}
                $html .=" </td><td width='100' style='border:1px solid #000;border-collapse:collapse'> ";
				if(!empty($d->p1_name)){
					$html .= $d->p1_name;
				}else{
					$html .= "N/A";
					
				}
                $html .=" </td><td width='100' style='border:1px solid #000;border-collapse:collapse'> ";
				if(!empty($d->p2_name)){
					$html .= $d->p2_name;
				}else{
					$html .= "N/A";
					
				}
                $html .=" </td><td width='100' style='border:1px solid #000;border-collapse:collapse'> ";
				if(!empty($d->p3_name)){
					$html .= $d->p3_name;
				}else{
					$html .= "N/A";
					
				}
                $html .=" </td><td width='100' style='border:1px solid #000;border-collapse:collapse'> ";
				if(!empty($d->p4_name)){
					$html .= $d->p4_name;
				}else{
					$html .= "N/A";
					
				}
				$html .=" </td><td width='150' style='border:1px solid #000;border-collapse:collapse'> ";
				if(!empty($d->configuration_name)){
					$html .= $d->configuration_name;
				}else{
					$html .= "N/A";
					
				}
				$html .="</td><td width='150' style='border:1px solid #000;border-collapse:collapse'> ";
				if(!empty($d->price_range)){
					$html .= $d->price_range;
				}else{
					$html .= "N/A";
					
				}
				$html .=" </td><td width='150' style='border:1px solid #000;border-collapse:collapse'> ".$d->status." </td><td width='200' style='border:1px solid #000;border-collapse:collapse'> ";
				if(!empty($d->address)){
					$html .= $d->address;
				}else{
					$html .= "N/A";
					
				}
				$html .=" </td><td width='150' style='border:1px solid #000;border-collapse:collapse'> ";
				if(!empty($d->remarks)){
					$html .= $d->remarks;
				}else{
					$html .= "N/A";
					
				}
				$html .=" </td></tr>";
            }

			$html.='</table>';

			$pdf->WriteHTML($html);
			$pdf->Output('D','exported_leads.pdf');

		}

	}
    }

    public function import_template()
    {
        require_once public_path().'/library/PhpXlsxGenerator.php';

        $fileName = 'leads_template.xlsx';
        $columns = array(
            '<style color="#ff0000">Name</style>', 
            'Business Name', 
            'Email', 
            '<style color="#ff0000">Mobile</style>', 
            'Project', 
            'Project 1', 
            'Project 2', 
            'Project 3', 
            'Project 4', 
            'Configuration', 
            'Budget range', 
            '<style color="#ff0000">Status</style>', 
            'Address', 
            'Remarks',
            'Assign To',
            'Reference By',
            'Date'
        );

        $excelData = array($columns);

        $xlsx = \PhpXlsxGenerator::fromArray($excelData); 
        $xlsx->downloadAs($fileName); 
        exit();
    }
}
