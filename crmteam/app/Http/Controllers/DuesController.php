<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Payment;
use App\Models\PaymentMode;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DuesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->hasRole(['Admin']) || auth()->user()->can('dues_access') || auth()->user()->can('dues_show')) {
            // Fetch Expenses that are marked as Pending or Rejected by Admin
            $dues = \App\Models\Expense::whereIn('status_by_admin', ['Pending', 'Rejected'])->orderBy('updated_at', 'desc')->get();
            return view('dues.index', compact('dues'));
        } else {
            // As per flow, users have nothing to do with this module
            abort(403, 'Unauthorized Access. Only Admin can view Dues.');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $payment_modes = PaymentMode::all();

        return view('dues.create', compact('payment_modes'));
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
            'payer' => 'required',
            'amount' => 'required',
            'due_date' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $payment_mode = PaymentMode::findorfail($request->input('payment_mode'));

            $due = Payment::create([
                'payer' => $request->input('payer'),
                'amount' => $request->input('amount'),
                'remark' => $request->input('remark'),
                'due_date' => $request->input('due_date'),
                'cheque_no' => $request->input('cheque_no'),
            ]);
            $due->payment_mode()->associate($payment_mode);
            $due->createdBy()->associate(auth()->user());
            $due->saveQuietly();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors([
                'db_error' => $e->getMessage(),
            ]);
        }
        DB::commit();

        $actor = auth()->user()->name;
        $now = now()->format('d M Y, h:i A');
        DB::table('notifications')->insert([
            'user_id' => 1,
            'notification_text' => "🆕 {$actor} added a new Due — Payer: {$request->input('payer')}, Amount: ₹{$request->input('amount')} — {$now}",
            'status' => 0,
            'created_at' => now(), 'updated_at' => now()
        ]);

        return redirect(route('dues.index'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $due = Payment::findorfail($id);
        $payment_modes = PaymentMode::all();

        return view('dues.edit', compact('due', 'payment_modes'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function pay($id)
    {
        $due = Payment::findorfail($id);
        $due->update([
            'date_of_payment' => Carbon::now(),
        ]);

        $actor = auth()->user()->name;
        $now = now()->format('d M Y, h:i A');
        DB::table('notifications')->insert([
            'user_id' => 1,
            'notification_text' => "💳 {$actor} marked Due (ID: {$id}) as 'Paid' — {$now}",
            'status' => 0,
            'created_at' => now(), 'updated_at' => now()
        ]);

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
        $this->validate($request, [
            'payer' => 'required',
            'amount' => 'required',
            'due_date' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $payment_mode = PaymentMode::findorfail($request->input('payment_mode'));
            $due = Payment::findorfail($id);
            $due->update([
                'payer' => $request->input('payer'),
                'amount' => $request->input('amount'),
                'remark' => $request->input('remark'),
                'due_date' => $request->input('due_date'),
				'cheque_no' => $request->input('cheque_no'),
            ]);
            $due->payment_mode()->associate($payment_mode);
            $due->lastEditedBy()->associate(auth()->user());
            $due->saveQuietly();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors([
                'db_error' => $e->getMessage(),
            ]);
        }
        DB::commit();

        $actor = auth()->user()->name;
        $now = now()->format('d M Y, h:i A');
        DB::table('notifications')->insert([
            'user_id' => 1,
            'notification_text' => "✏️ {$actor} updated Due (ID: {$id}) — Amount: ₹{$request->input('amount')} — {$now}",
            'status' => 0,
            'created_at' => now(), 'updated_at' => now()
        ]);

        return redirect(route('dues.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $due = Payment::findorfail($id);
        $due->deletedBy()->associate(auth()->user());
        $due->saveQuietly();
        $due->delete();

        $actor = auth()->user()->name;
        $now = now()->format('d M Y, h:i A');
        DB::table('notifications')->insert([
            'user_id' => 1,
            'notification_text' => "🗑️ {$actor} deleted Due (ID: {$id}) — {$now}",
            'status' => 0,
            'created_at' => now(), 'updated_at' => now()
        ]);

        return back();
    }
}
