<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\PaymentMode;
use App\Models\Wallet;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // due payments are the ones with `date_of_payment` set to null
        $wallets = Wallet::orderBy('id', 'desc')->get();
		$wallet = [];
		foreach($wallets as $w){
			$user = User::find($w->user_id);
			$data['name'] = $user ? $user->name : 'Unknown User';
			$data['user_id'] = $w->user_id;
			$data['id'] = $w->id;
			$data['balance'] = $w->balance;
			$data['created_at'] = $w->created_at;
			$data['amount'] = $w->amount;
			$data['date'] = $w->date;
			$data['payee'] = $w->payee;
			$wallet[] = $data;
			
			
		}
		// Total wallet balance
		if (auth()->user()->hasRole(['Admin'])) {
			$total_wallet_balance = \App\Models\Wallet::sum('balance');
		} else {
			$total_wallet_balance = Wallet::where('user_id', auth()->user()->id)->sum('balance');
		}

        $userWallets = [];
        $uniqueUserIds = Wallet::pluck('user_id')->unique();
        foreach($uniqueUserIds as $uid) {
            $u = User::find($uid);
            if($u) {
                $userSum = Wallet::where('user_id', $uid)->sum('balance');
                $userWallets[strtolower($u->name)] = [
                    'name' => $u->name,
                    'balance' => $userSum
                ];
            }
        }

        return view('wallet.index', compact('wallets', 'wallet', 'total_wallet_balance', 'userWallets'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $payment_modes = PaymentMode::all();
		$users = User::role(['Team Lead' , 'Executive'])->orderby('no_of_enquiries_assigned', 'ASC')->get();

        return view('wallet.create', compact('payment_modes','users'));
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
            'user_id' => 'required',
            'amount' => 'required',
            'payment_mode' => 'required',
        ]);

        DB::beginTransaction();
        try {

            $due = Wallet::create([
                'user_id' => $request->input('user_id'),
                'amount' => $request->input('amount'),
                'added_by' => auth()->user()->id,
                'balance' => $request->input('amount'),
                'payment_method' => $request->input('payment_mode'),
				'cheque_no' => $request->input('cheque_no'),
                'date' => $request->input('date'),
                'payee' => $request->input('payee')
            ]);
           
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
        $targetUser = User::find($request->user_id);
        $targetName = $targetUser ? $targetUser->name : 'Unknown';
        DB::table('notifications')->insert([
            'user_id' => 1,
            'notification_text' => "💳 {$actor} added ₹{$request->amount} to {$targetName}'s Wallet — {$now}",
            'notification_type' => 'wallet',
            'status' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        // Also notify the employee
        if($targetUser && $targetUser->id != 1) {
            DB::table('notifications')->insert([
                'user_id' => $targetUser->id,
                'notification_text' => "💳 ₹{$request->amount} has been added to your Wallet — {$now}",
                'notification_type' => 'wallet',
                'status' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect(route('wallet.index'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $due = Wallet::findorfail($id);
        $payment_modes = PaymentMode::all();
        $users = User::role(['Team Lead' , 'Executive'])->orderby('no_of_enquiries_assigned', 'ASC')->get();

        return view('wallet.edit', compact('due', 'payment_modes', 'users'));
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
            'amount' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $payment_mode = PaymentMode::findorfail($request->input('payment_mode'));
            $due = Wallet::findorfail($id);
            $due->update([
                'amount' => $request->input('amount'),
                'payment_method' => $request->input('payment_mode'),
				'cheque_no' => $request->input('cheque_no'),
                'date' => $request->input('date'),
                'payee' => $request->input('payee')
            ]);
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
            'notification_text' => "✏️ {$actor} updated Wallet record (ID: {$id}) — Amount: ₹{$request->amount} — {$now}",
            'notification_type' => 'wallet',
            'status' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect(route('wallet.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $due = Wallet::findorfail($id);
        $due->delete();

        $actor = auth()->user()->name;
        $now = now()->format('d M Y, h:i A');
        DB::table('notifications')->insert([
            'user_id' => 1,
            'notification_text' => "🗑️ {$actor} deleted Wallet entry (ID: {$id}) — {$now}",
            'status' => 0,
            'created_at' => now(), 'updated_at' => now()
        ]);

        return back();
    }
}
