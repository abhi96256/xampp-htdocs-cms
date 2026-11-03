<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\PaymentMode;
use App\Models\Wallet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class ExpensesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $isAdmin = auth()->user()->hasRole(['Admin']);
        $userId  = auth()->user()->id;
        if ($isAdmin) {
            $expenses = Expense::orderBy('id', 'DESC')->get();
        } else {
            $expenses = Expense::where('created_by', $userId)->orderBy('id', 'DESC')->get();
        }
		if(auth()->user()->hasRole(['Admin'])) {
			$wallet = \App\Models\Wallet::sum('balance');
			// Build per-user wallet balances for admin
			$userWallets = DB::table('wallet')
				->join('users', 'users.id', '=', 'wallet.user_id')
				->select('wallet.user_id', 'users.name', DB::raw('SUM(wallet.balance) as total'))
				->groupBy('wallet.user_id', 'users.name')
				->get()
				->mapWithKeys(fn($row) => [strtolower($row->name) => ['name' => $row->name, 'balance' => $row->total, 'user_id' => $row->user_id]]);
		} else {
			$wallet = Wallet::where([['user_id', Auth::user()->id]])->sum('balance');
			$userWallets = collect();
		}
        return view('expenses.index', compact('expenses', 'wallet', 'userWallets'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //$expense_categories = ExpenseCategory::all();
        $payment_modes = PaymentMode::all();

        return view('expenses.create', compact('payment_modes'));
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
            'payment_mode' => 'required',
            'expense_category' => 'required',
            'payee' => 'required',
            'amount_paid' => 'required',
            'date_of_payment' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $profile_photo ='';
			 if ($request->hasFile('bill_attachment')) {
				$image = $request->file('bill_attachment');
				//echo "<pre>";print_r($image);exit;
				$profile_photo = time().'.'.$image->getClientOriginalExtension();
				$destinationPath = public_path('/storage/galeryImages/');
				$image->move($destinationPath, $profile_photo);
			 }
            $payment_mode = PaymentMode::findorfail($request->input('payment_mode'));

            $expense = Expense::create([
                'payee' => $request->input('payee'),
                'amount_paid' => $request->input('amount_paid'),
                'date_of_payment' => $request->input('date_of_payment'),
                'remark' => $request->input('remark'),
                'expense_category' => $request->input('expense_category'),
                'bill_no' => $request->input('bill_no'),
                'address' => $request->input('address'),
                'status' => $request->input('status'),
                'cheque_no' => $request->input('cheque_no'),
                'bill_attachment' => $profile_photo,
                'paid_date' => (strtolower($request->input('status')) === 'paid') ? now() : null,
                'rejected_date' => (strtolower($request->input('status')) === 'rejected') ? now() : null,
                'status_by_admin' => $request->input('status_by_admin') ?: null,
                'admin_paid_date' => (strtolower($request->input('status_by_admin')) === 'paid') ? now() : null,
                'admin_rejected_date' => (strtolower($request->input('status_by_admin')) === 'rejected') ? now() : null,
            ]);
          //  $expense->expense_category()->associate($expense_category);
            $expense->payment_mode()->associate($payment_mode);
            $expense->createdBy()->associate(auth()->user());
            $expense->saveQuietly();

            // ── Wallet: deduct based on USER status ──
            if (strtolower($request->input('status')) == 'paid') {
                $wallet = Wallet::where([['user_id', $expense->created_by]])->orderby('id','desc')->first();
                if (!$wallet) {
                    $wallet = Wallet::create([
                        'user_id'        => $expense->created_by,
                        'amount'         => 0,
                        'added_by'       => auth()->user()->id,
                        'balance'        => 0,
                        'payment_method' => 1,
                        'date'           => now()
                    ]);
                }
                if ($wallet) {
                    $wallet->update(['balance' => $wallet->balance - $request->input('amount_paid')]);
                    $wallet->save();
                }
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
        DB::table('notifications')->insert([
            'user_id' => 1,
            'notification_text' => "💸 {$actor} added a new Expense — Payee: {$request->input('payee')}, Amount: ₹{$request->input('amount_paid')} — {$now}",
            'status' => 0,
            'created_at' => now(), 'updated_at' => now()
        ]);

        return redirect(route('expenses.index'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $expense = Expense::findorfail($id);
       // $expense_categories = ExpenseCategory::all();
        $payment_modes = PaymentMode::all();

        return view('expenses.edit', compact('expense',  'payment_modes'));
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
            'payment_mode' => 'required',
            'expense_category' => 'required',
            'payee' => 'required',
            'amount_paid' => 'required',
            'date_of_payment' => 'required',
        ]);

        DB::beginTransaction();
        try {
            //$expense_category = ExpenseCategory::findorfail($request->input('expense_category'));

            $payment_mode = PaymentMode::findorfail($request->input('payment_mode'));
			
            $expense = Expense::findorfail($id);
            $old_status = $expense->status;
			$profile_photo = $expense->bill_attachment;
			if ($request->hasFile('bill_attachment')) {
				if($expense->bill_attachment !== null) {
                    $file_path = public_path('/storage/galeryImages/' . $expense->bill_attachment);
                    @unlink($file_path);
                }

				$image = $request->file('bill_attachment');
				$profile_photo = time().'.'.$image->getClientOriginalExtension();
				$destinationPath = public_path('/storage/galeryImages/');
				$image->move($destinationPath, $profile_photo);
			 }
            $update_data = [
                'payee' => $request->input('payee'),
                'amount_paid' => $request->input('amount_paid'),
                'date_of_payment' => $request->input('date_of_payment'),
                'remark' => $request->input('remark'),
                'expense_category' => $request->input('expense_category'),
				 'bill_no' => $request->input('bill_no'),
                'address' => $request->input('address'),
                'status' => $request->input('status'),
                'cheque_no' => $request->input('cheque_no'),
                'bill_attachment' => $profile_photo,
            ];
            
            $new_status = strtolower($request->input('status'));
            $old_status_lower = strtolower($old_status);

            if ($new_status == 'paid') {
                if ($old_status_lower != 'paid') {
                    $update_data['paid_date'] = now();
                }
                $update_data['rejected_date'] = null;
            } elseif ($new_status == 'rejected') {
                if ($old_status_lower != 'rejected') {
                    $update_data['rejected_date'] = now();
                }
                $update_data['paid_date'] = null;
            } else {
                // 'pending' or any other status
                $update_data['paid_date'] = null;
                $update_data['rejected_date'] = null;
            }

            // ── Admin Status Logic ──
            $new_admin_status = strtolower($request->input('status_by_admin'));
            $old_admin_status = strtolower($expense->status_by_admin ?? '');
            $update_data['status_by_admin'] = $request->input('status_by_admin') ?: null;
            if ($new_admin_status == 'paid') {
                if ($old_admin_status != 'paid') {
                    $update_data['admin_paid_date'] = now();
                }
                $update_data['admin_rejected_date'] = null;
            } elseif ($new_admin_status == 'rejected') {
                if ($old_admin_status != 'rejected') {
                    $update_data['admin_rejected_date'] = now();
                }
                $update_data['admin_paid_date'] = null;
            } else {
                $update_data['admin_paid_date'] = null;
                $update_data['admin_rejected_date'] = null;
            }
            
            $expense->update($update_data);
			
			
           // $expense->expense_category()->associate($expense_category);
            $expense->payment_mode()->associate($payment_mode);
            $expense->lastEditedBy()->associate(auth()->user());
            $expense->saveQuietly();

			// ── Wallet: deduct/refund based on USER status ──
			$walletUserId = $expense->created_by;
			$amt_paid = $request->input('amount_paid');

			// --- User Status Wallet Logic ---
			$user_was_paid_before = (strtolower($old_status) == 'paid');
			$user_is_paid_now     = ($new_status == 'paid');

			if ($user_is_paid_now && !$user_was_paid_before) {
				// User just marked Paid → deduct from wallet
				$wallet = Wallet::where('user_id', $walletUserId)->orderby('id','desc')->first();
				if (!$wallet) {
					$wallet = Wallet::create([
						'user_id'        => $walletUserId,
						'amount'         => 0,
						'added_by'       => auth()->user()->id,
						'balance'        => 0,
						'payment_method' => 1,
						'date'           => now()
					]);
				}
				if ($wallet) {
					$wallet->update(['balance' => $wallet->balance - $amt_paid]);
				}
			} elseif (!$user_is_paid_now && $user_was_paid_before && in_array($new_status, ['pending', 'rejected'])) {
				// User reverted from Paid → refund wallet
				$wallet = Wallet::where('user_id', $walletUserId)->orderby('id','desc')->first();
				if (!$wallet) {
					$wallet = Wallet::create([
						'user_id'        => $walletUserId,
						'amount'         => 0,
						'added_by'       => auth()->user()->id,
						'balance'        => 0,
						'payment_method' => 1,
						'date'           => now()
					]);
				}
				if ($wallet) {
					$wallet->update(['balance' => $wallet->balance + $amt_paid]);
				}
			}

			// --- Admin Status Wallet Logic (REMOVED: User requested only User Status to affect wallet) ---
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
            'notification_text' => "✏️ {$actor} updated Expense (ID: {$id}) — Amount: ₹{$request->input('amount_paid')} — {$now}",
            'status' => 0,
            'created_at' => now(), 'updated_at' => now()
        ]);

        return redirect(route('expenses.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $expense = Expense::findorfail($id);
        $expense->deletedBy()->associate(auth()->user());
        $expense->saveQuietly();
        $expense->delete();

        $actor = auth()->user()->name;
        $now = now()->format('d M Y, h:i A');
        DB::table('notifications')->insert([
            'user_id' => 1,
            'notification_text' => "🗑️ {$actor} deleted Expense (ID: {$id}) — {$now}",
            'status' => 0,
            'created_at' => now(), 'updated_at' => now()
        ]);

        return back();
    }
}
