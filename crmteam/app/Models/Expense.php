<?php

namespace App\Models;

use App\Lancer\Utilities;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use SoftDeletes;

    protected $table = 'expenses';

    protected $fillable = [
        'payee',
        'amount_paid',
        'date_of_payment',
        'expense_category',
        'bill_no',
        'bill_attachment',
        'status',
        'address',
        'cheque_no',
        'paid_date',
        'rejected_date',
        'status_by_admin',
        'admin_paid_date',
        'admin_rejected_date',
    ];

    protected $casts = [
        'amount_paid'         => 'double',
        'date_of_payment'     => 'date',
        'admin_paid_date'     => 'datetime',
        'admin_rejected_date' => 'datetime',
    ];

    protected static function booted()
    {
        static::created(function ($expense) {
            $user = auth()->user();
            $category = $expense->expense_category ?? 'N/A';
            $payee = $expense->payee ?? 'N/A';
            $status = $expense->status ?? 'Pending';
            $message = $user->name . " created expense (ID: {$expense->id}) | Amount: " . Utilities::CURRENCY_SYMBOL . $expense->amount_paid . " | Payee: {$payee} | Category: {$category} | User Status: {$status}";
            
            $transaction = Transaction::create([
                'details' => $message,
            ]);
            $transaction->createdBy()->associate($user);
            $transaction->save();
        });

        static::updating(function ($expense) {
            $user = auth()->user();
            $changes = [];
            
            if ($expense->isDirty('amount_paid')) {
                $changes[] = "Amount (₹{$expense->getOriginal('amount_paid')} → ₹{$expense->amount_paid})";
            }
            if ($expense->isDirty('payee')) {
                $changes[] = "Payee ('{$expense->getOriginal('payee')}' → '{$expense->payee}')";
            }
            if ($expense->isDirty('expense_category')) {
                $changes[] = "Category ('{$expense->getOriginal('expense_category')}' → '{$expense->expense_category}')";
            }
            if ($expense->isDirty('status')) {
                $changes[] = "User Status ('{$expense->getOriginal('status')}' → '{$expense->status}')";
            }
            if ($expense->isDirty('status_by_admin')) {
                $changes[] = "Admin Status ('{$expense->getOriginal('status_by_admin')}' → '{$expense->status_by_admin}')";
            }
            if ($expense->isDirty('cheque_no')) {
                $changes[] = "Cheque No ('{$expense->getOriginal('cheque_no')}' → '{$expense->cheque_no}')";
            }
            
            $changeStr = empty($changes) ? "Minor details updated" : implode(', ', $changes);
            $message = $user->name . " updated expense (ID: {$expense->id}) | Changes: " . $changeStr;
            
            $transaction = Transaction::create([
                'details' => $message,
            ]);
            $transaction->createdBy()->associate($user);
            $transaction->save();
        });
    }

    public function payment_mode()
    {
        return $this->belongsTo(PaymentMode::class);
    }

    public function expense_category()
    {
        return $this->belongsTo(ExpenseCategory::class);
    }

    // public function digital_details()
    // {
    //     return $this->hasOne(DigitalDetail::class);
    // }

    // public function cheque_details()
    // {
    //     return $this->hasOne(ChequeDetail::class);
    // }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }


    public function lastEditedBy()
    {
        return $this->belongsTo(User::class, 'last_edited_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
