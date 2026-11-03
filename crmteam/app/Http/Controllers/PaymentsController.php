<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\PaymentMode;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->hasRole(['Admin'])) {
            $payments = Payment::whereNotNull('date_of_payment')->paginate(15);
        } else {
            $payments = Payment::whereNotNull('date_of_payment')->where('created_by', auth()->user()->id)->paginate(15);
        }

        return view('payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $payment_modes = PaymentMode::all();

        return view('payments.create', compact('payment_modes'));
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
            'date_of_payment' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $payment_mode = PaymentMode::findorfail($request->input('payment_mode'));
            $payment = Payment::create([
                'payer' => $request->input('payer'),
                'amount' => $request->input('amount'),
                'remark' => $request->input('remark'),
                'cheque_no' => $request->input('cheque_no'),
                'date_of_payment' => $request->input('date_of_payment'),
            ]);
            $payment->createdBy()->associate(auth()->user());
            $payment->payment_mode()->associate($payment_mode);
            $payment->saveQuietly();
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
            'notification_text' => "💰 {$actor} added a Payment of ₹{$request->amount} (Payer: {$request->payer}) — {$now}",
            'notification_type' => 'payment',
            'status' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect(route('payments.index'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $payment = Payment::findorfail($id);
        $payment_modes = PaymentMode::all();

        return view('payments.edit', compact('payment', 'payment_modes'));
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
            'date_of_payment' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $payment_mode = PaymentMode::findorfail($request->input('payment_mode'));
            $payment = Payment::findorfail($id);
            $payment->update([
                'payer' => $request->input('payer'),
                'amount' => $request->input('amount'),
                'remark' => $request->input('remark'),
				'cheque_no' => $request->input('cheque_no'),
                'date_of_payment' => $request->input('date_of_payment'),
            ]);
            $payment->payment_mode()->associate($payment_mode);
            $payment->lastEditedBy()->associate(auth()->user());
            $payment->saveQuietly();
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
            'notification_text' => "✏️ {$actor} updated Payment — Payer: {$request->payer}, Amount: ₹{$request->amount} — {$now}",
            'notification_type' => 'payment',
            'status' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect(route('payments.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $payment = Payment::findorfail($id);
        $payment->deletedBy()->associate(auth()->user());
        $payment->saveQuietly();
        $payment->delete();

        return back();
    }
}
