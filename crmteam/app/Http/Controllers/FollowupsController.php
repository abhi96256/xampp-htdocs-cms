<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use App\Models\FollowUp;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FollowupsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = json_decode($request->getContent());
        DB::beginTransaction();
        try {
			$user =user::findorfail(auth()->user()->id);
            $enquiry = Enquiry::findorfail($data->enquiry_id);
				$insert_update  = DB::table('follow_ups')
				->Insert(
					['date_time' => $data->date_time,'remark' => $data->remark,
                'outcome' => $data->outcome,'enquiry_id'=>$data->enquiry_id],
				);
				$insert_update  = DB::table('notifications')
				->Insert(
					['user_id' => 1,'notification_text' => "New followup has been created by ".$user->name,"status"=>0],
				);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors([
                'db_error' => $e->getMessage(),
            ]);
        }
        DB::commit();

        return back();
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
        $data = json_decode($request->getContent());

        DB::beginTransaction();
        try {
            $follow_up = FollowUp::findorfail($id);
            $follow_up->update([
                'date_time' => $data->date_time,
                'remark' => $data->remark,
                'outcome' => $data->outcome,
            ]);
            $follow_up->save();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors([
                'db_error' => $e->getMessage(),
            ]);
        }
        DB::commit();

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $follow_up = FollowUp::findorfail($id);
        $follow_up->delete();

        return back();
    }
}
