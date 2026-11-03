<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Configuration;
use App\Models\Payment;
use App\Models\PaymentMode;
use App\Models\Project;
use App\Models\User;
use App\Models\Incentive;
use App\Models\Enquiry;
use App\Models\EnquiryStatus;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // All users with client_access permission can see all clients
        if(auth()->user()->hasRole(['Admin'])) {
            $clients = Client::latest()->paginate(15);
        } else {
            $clients = Client::where('closed_by', auth()->user()->id)->latest()->paginate(15);
        }

        return view('clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $payment_modes = PaymentMode::all();
        $projects = Project::all();
        $configurations = Configuration::all();
		$users = User::orderBy('name', 'ASC')->get();
		//echo "<pre>";print_r($users);echo "</pre>";exit;
        return view('clients.create', compact('payment_modes', 'users','projects','configurations'));
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
            'contact_no' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:6|exists:enquiries,contact_no',
        ]);
		
        DB::beginTransaction();
        try {
			$attachment = '';
			if ($request->hasFile('attachment')){
				$image = $request->file('attachment');
				//echo "<pre>";print_r($image);exit;
				$profile_photo = time().'.'.$image->getClientOriginalExtension();
				$destinationPath = public_path('/storage/galeryImages/');
				$image->move($destinationPath, $profile_photo);
				$attachment = $profile_photo;
            }
            $client = Client::create([
                'name' => ucwords($request->input('name')),
                'business_name' => $request->input('business_name'),
                'email' => $request->input('email'),
                'contact_no' => $request->input('contact_no'),
                'subject' => $request->input('subject'),
                'carpet_area' => $request->input('carpet_area'),
                'agreement_value' => $request->input('agreement_value'),
                'booking_amount' => $request->input('booking_amount'),
                'remark' => $request->input('remark'),
                'lead_id' => $request->input('lead_id') ?? 0,
                'rating' => $request->input('rating'),
                'floor_no' => $request->input('floor_no'),
                'unit_no' => $request->input('unit_no'),
                'tower_no' => $request->input('tower_no'),
                'actual_amount' => $request->input('actual_amount'),
                'cheque_no' => $request->input('cheque_no'),
                'payment_plan' => $request->input('payment_plan'),
                'pass_on_to_customer' => is_numeric($request->input('pass_on_to_customer')) ? $request->input('pass_on_to_customer') : 0,
                'attachment' => $attachment,
                'closed_by_team1' => $request->input('closed_by_team1'),
                'closed_by_team2' => $request->input('closed_by_team2'),
            ]);

            $project = Project::where('id', $request->input('project'))->first();
            $client->project()->associate($project);

            $configuration = Configuration::where('id', $request->input('configuration'))->first();
            $client->configuration()->associate($configuration);

            $payment_mode = PaymentMode::where('id', $request->input('payment_mode'))->first();
            $client->payment_mode()->associate($payment_mode);

            if ($request->filled('closed_by')) {
                $closedByVal = $request->input('closed_by');
                if (is_numeric($closedByVal)) {
                    $client->closed_by = $closedByVal;
                    $client->closed_by_name = null;
                } else {
                    $client->closed_by = null;
                    $client->closed_by_name = $closedByVal;
                }
            } else {
                $client->closedBy()->associate(auth()->user());
            }

            $client->save();
			if($request->input('due_payment_mode')){
            $payment_mode = PaymentMode::findorfail($request->input('due_payment_mode'));

            $payment = Payment::create([
                'amount' => $request->input('brokerage_amount'),
                'due_date' => $request->input('due_date'),
                'remark' => $request->input('brokerage_remark'),
                'payer' => $project->name,
            ]);

            $payment->payment_mode()->associate($payment_mode);
			$payment->client()->associate($client);
            $payment->createdBy()->associate(auth()->user());
			$payment->saveQuietly();
			}
			if($request->has('incentive_percent') || $request->has('incentive_amount')){
                $cp = $request->input('incentive_percent'); $cp = ($cp === null || $cp === '') ? 0 : $cp;
                $ca = $request->input('incentive_amount'); $ca = ($ca === null || $ca === '') ? 0 : $ca;
				DB::table('incentive')
				->insert(
					['lead_id' => $request->input('lead_id') ?: 0,'user_id'=>1,'client_id'=>$client->id, 'incentive_percentage'=>$cp,'incentive_amount'=>$ca,'remarks'=>$request->input('incentive_remark') ?? '', ]
				);
				
			}
			if($request->input('emp_incentive')){
				$emp_incentive = $request->input('emp_incentive');
				foreach($emp_incentive as $inc){
                    if(empty($inc['user_id'])) { continue; }
                    $ep = $inc['incentive_employee_percent'] ?? null;
                    $ea = $inc['incentive_employee_amount'] ?? null;
                    if(($ep === null || $ep === '') && ($ea === null || $ea === '')) { continue; }
                    $ep = ($ep === null || $ep === '') ? 0 : $ep;
                    $ea = ($ea === null || $ea === '') ? 0 : $ea;
                    $tp = $inc['tds_percent'] ?? 0; $tp = ($tp === null || $tp === '') ? 0 : $tp;
                    $ta = $inc['tds_amount'] ?? 0; $ta = ($ta === null || $ta === '') ? 0 : $ta;
						DB::table('incentive')
						->insert(
							['lead_id' => $request->input('lead_id') ?: 0,'user_id'=>$inc['user_id'],'client_id'=>$client->id, 'incentive_percentage'=>$ep,'incentive_amount'=>$ea, 'tds_percent' => $tp, 'tds_amount' => $ta, 'remarks'=>' ' ]
						);
				}
			}
			
           
            
        } catch (Exception $e) {
            DB::rollBack();
            dd('ERROR SUBMITTING FORM: ' . $e->getMessage());
            return redirect()->back()->withErrors([
                'db_error' => $e->getMessage(),
            ]);
        }
        DB::commit();

        $actor = auth()->user()->name;
        $now = now()->format('d M Y, h:i A');
        $clientName = ucwords($request->name);
        DB::table('notifications')->insert([
            'user_id' => 1,
            'notification_text' => "😊 {$actor} added a new Happy Client — Client: {$clientName} | Contact: {$request->contact_no} — {$now}",
            'notification_type' => 'client',
            'status' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect(route('clients.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $client = Client::findorfail($id);

        return view('clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $client = Client::findorfail($id);
        $projects = Project::all();
        $payment_modes = PaymentMode::all();
        $configurations = Configuration::all();
        $incentive = DB::table('incentive')->where([['client_id',$id],['user_id',1]])->first();
		$emp_incentive = DB::table('incentive')->where([['client_id',$id],['user_id','!=',1]])->get();
		//echo "<pre>";print_r($emp_incentive);echo "</pre>";exit;
		$users = User::orderBy('name', 'ASC')->get();
        return view('clients.edit', compact('client', 'projects', 'payment_modes', 'configurations','incentive','emp_incentive','users'));
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
            'contact_no' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:6|exists:enquiries,contact_no',
           // 'subject' => 'required',
        ]);
		//print_r($request->input('emp_incentive'));
		//print_r($request->input('incentive_employee_percent'));exit;
        DB::beginTransaction();
        try {
			$attachment = '';
			if ($request->hasFile('attachment')) {
				//  echo "has file";exit;
				$image = $request->file('attachment');
				//echo "<pre>";print_r($image);exit;
				$profile_photo = time().'.'.$image->getClientOriginalExtension();
				$destinationPath = public_path('/storage/galeryImages/');
				$image->move($destinationPath, $profile_photo);
				$attachment = $profile_photo;
            }
			//echo $attachment;exit;
            $client = Client::findorfail($id);
            $client->update([
                'name' => ucwords($request->input('name')),
                'business_name' => $request->input('business_name'),
                'email' => $request->input('email'),
                'contact_no' => $request->input('contact_no'),
                'subject' => $request->input('subject'),
                'carpet_area' => $request->input('carpet_area'),
                'agreement_value' => $request->input('agreement_value'),
                'booking_amount' => $request->input('booking_amount'),
                'remark' => $request->input('remark'),
                'rating' => $request->input('rating'),
                'attachment' => $attachment,
				'floor_no' => $request->input('floor_no'),
                'unit_no' => $request->input('unit_no'),
                'tower_no' => $request->input('tower_no'),
                'actual_amount' => $request->input('actual_amount'),
                'cheque_no' => $request->input('cheque_no'),
                'payment_plan' => $request->input('payment_plan'),
                'pass_on_to_customer' => is_numeric($request->input('pass_on_to_customer')) ? $request->input('pass_on_to_customer') : 0,
                'closed_by_team1' => $request->input('closed_by_team1'),
                'closed_by_team2' => $request->input('closed_by_team2'),
            ]);

            if ($request->filled('closed_by')) {
                $closedByVal = $request->input('closed_by');
                if (is_numeric($closedByVal)) {
                    $client->closed_by = $closedByVal;
                    $client->closed_by_name = null;
                } else {
                    $client->closed_by = null;
                    $client->closed_by_name = $closedByVal;
                }
            }

            $project = Project::where('id', $request->input('project'))->first();
            $client->project()->associate($project);

            $configuration = Configuration::where('id', $request->input('configuration'))->first();
            $client->configuration()->associate($configuration);

            $payment_mode = PaymentMode::where('id', $request->input('payment_mode'))->first();
            $client->payment_mode()->associate($payment_mode);
			
			if($request->has('incentive_percent') || $request->has('incentive_amount')){
                $cp = $request->input('incentive_percent'); $cp = ($cp === null || $cp === '') ? 0 : $cp;
                $ca = $request->input('incentive_amount'); $ca = ($ca === null || $ca === '') ? 0 : $ca;
				$client_exists = DB::table('incentive')->where([['client_id',$client->id],['user_id',1]])->first();
				if($client_exists ){
					DB::table('incentive')->where([['client_id',$client->id],['user_id',1]])
					->update(
						['lead_id' => $client->lead_id ?: 0,'user_id'=>1,'client_id'=>$client->id, 'incentive_percentage'=>$cp,'incentive_amount'=>$ca,'remarks'=>$request->input('incentive_remark') ?? '', ]
					);
				}
				else{
					DB::table('incentive')
					->insert(
						['lead_id' => $client->lead_id ?: 0,'user_id'=>1,'client_id'=>$client->id, 'incentive_percentage'=>$cp,'incentive_amount'=>$ca,'remarks'=>$request->input('incentive_remark') ?? '', ]
					);
				}
				
			}
			if($request->input('emp_incentive')){
				$emp_incentive = $request->input('emp_incentive');
				foreach($emp_incentive as $inc){
                    if(empty($inc['user_id'])) { continue; }
                    $ep = $inc['incentive_employee_percent'] ?? null;
                    $ea = $inc['incentive_employee_amount'] ?? null;
                    if(($ep === null || $ep === '') && ($ea === null || $ea === '')) { continue; }
                    $ep = ($ep === null || $ep === '') ? 0 : $ep;
                    $ea = ($ea === null || $ea === '') ? 0 : $ea;
                    $tp = $inc['tds_percent'] ?? 0; $tp = ($tp === null || $tp === '') ? 0 : $tp;
                    $ta = $inc['tds_amount'] ?? 0; $ta = ($ta === null || $ta === '') ? 0 : $ta;
					$incentive_exists = DB::table('incentive')->where([['client_id',$client->id],['user_id',$inc['user_id']]])->first();
					if($incentive_exists){
						DB::table('incentive')->where([['client_id',$client->id],['user_id',$inc['user_id']]])
					->update(
						['lead_id' => $client->lead_id ?: 0,'user_id'=>$inc['user_id'],'client_id'=>$client->id, 'incentive_percentage'=>$ep,'incentive_amount'=>$ea, 'tds_percent' => $tp, 'tds_amount' => $ta, 'remarks'=>' ']
					);
					}else{
						DB::table('incentive')
						->insert(
							['lead_id' => $client->lead_id ?: 0,'user_id'=>$inc['user_id'],'client_id'=>$client->id, 'incentive_percentage'=>$ep,'incentive_amount'=>$ea, 'tds_percent' => $tp, 'tds_amount' => $ta, 'remarks'=>' ' ]
						);
					}
				}
			}
				
			

            if($request->has('is_active')) {
                $client->is_active = true;
                $client->save();
            } else {
                $client->is_active = false;
                $client->save();
            }
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors([
                'db_error' => $e->getMessage(),
            ]);
        }
        DB::commit();

        $actor = auth()->user()->name;
        $now = now()->format('d M Y, h:i A');
        $clientName = ucwords($request->name);
        DB::table('notifications')->insert([
            'user_id' => 1,
            'notification_text' => "✏️ {$actor} updated Client — Client: {$clientName} | Contact: {$request->contact_no} — {$now}",
            'notification_type' => 'client',
            'status' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect(route('clients.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $client = Client::findorfail($id);
        $client->deletedBy()->associate(auth()->user());
        $client->saveQuietly();
        $client->delete();

        return back();
    }

    public function convertToLead($id)
    {
        $client = Client::findOrFail($id);
        
        DB::beginTransaction();
        try {
            if ($client->lead_id) {
                // If it originated from a Lead/Enquiry, let's restore that lead
                $enquiry = Enquiry::find($client->lead_id);
                if ($enquiry) {
                    // Set status back to "Active" (id 1)
                    $status = EnquiryStatus::where('id', 1)->first();
                    $enquiry->enquiry_status()->associate($status);
                    
                    // Increment the assigned user's counter if there's an assignee, since we reduced it upon closing
                    if ($enquiry->assigned_to) {
                        $assignee = User::find($enquiry->assigned_to);
                        if ($assignee) {
                            $assignee->update([
                                'no_of_enquiries_assigned' => ($assignee->no_of_enquiries_assigned + 1),
                            ]);
                        }
                    }
                    $enquiry->save();
                    
                    // Track Activity
                    DB::table('activities')->insert([
                        'user' => auth()->user()->id,
                        'activity_text' => "Closed Client reverted back to Active Lead for " . $enquiry->name
                    ]);
                }
            } else {
                // If there's no original lead, let's create a new one so data is not lost
                $enquiry = Enquiry::create([
                    'name' => $client->name,
                    'email' => $client->email,
                    'contact_no' => $client->contact_no,
                    'address' => '', 
                    'budget_range' => '', 
                ]);

                $status = EnquiryStatus::where('id', 1)->first();
                if ($status) $enquiry->enquiry_status()->associate($status);
                
                if ($client->project_id) {
                    $project = Project::find($client->project_id);
                    if ($project) $enquiry->project()->associate($project);
                }
                
                if ($client->configuration_id) {
                    $config = Configuration::find($client->configuration_id);
                    if ($config) $enquiry->configuration()->associate($config);
                }
                
                // Assign to logged-in user
                $enquiry->assignedTo()->associate(auth()->user());
                $enquiry->save();
                
                auth()->user()->update([
                    'no_of_enquiries_assigned' => (auth()->user()->no_of_enquiries_assigned + 1),
                ]);
            }
            
            // Delete payments tied to this client
            Payment::where('client_id', $client->id)->delete();
            
            // Delete incentives tied to this client
            DB::table('incentive')->where('client_id', $client->id)->delete();
            
            // Logically delete the client record as it's being moved back to lead
            $client->deletedBy()->associate(auth()->user());
            $client->saveQuietly();
            $client->delete();

            DB::commit();
            return redirect(route('enquiries.index'))->with('success', 'Client converted back to Lead successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error("Convert to Lead Failed: " . $e->getMessage() . " at " . $e->getFile() . ":" . $e->getLine());
            return redirect()->back()->withErrors(['error' => 'Failed to convert back to Lead: ' . $e->getMessage()]);
        }
    }
}
