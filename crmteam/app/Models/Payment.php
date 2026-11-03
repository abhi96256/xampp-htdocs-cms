<?php

namespace App\Models;

use App\Lancer\Utilities;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use SoftDeletes;

    protected $table = 'payments';

    protected $fillable = [
        'payer',
        'amount',
        'remark',
        'attachment',
        'due_date',
        'date_of_payment',
		'cheque_no'
    ];

    protected $casts = [
        'amount' => 'double',
        'due_date' => 'date',
        'date_of_payment' => 'date',
    ];

    protected static function booted()
    {
        static::created(function ($payment) {
            $user = auth()->user();
            $type = $payment->date_of_payment ? 'Office Payment' : 'Due';
            $payer = $payment->payer ?? 'N/A';
            
            $message = $user->name . " created {$type} (ID: {$payment->id}) | Amount: " . Utilities::CURRENCY_SYMBOL . $payment->amount . " | Payer/Payee: {$payer}";
            
            $transaction = Transaction::create([
                'details' => $message,
            ]);
            $transaction->createdBy()->associate($user);
            $transaction->save();
        });

        static::updating(function ($payment) {
            $user = auth()->user();
            $type = $payment->date_of_payment ? 'Office Payment' : 'Due';
            $changes = [];
            
            if ($payment->isDirty('amount')) {
                $changes[] = "Amount (₹{$payment->getOriginal('amount')} → ₹{$payment->amount})";
            }
            if ($payment->isDirty('payer')) {
                $changes[] = "Payer/Payee ('{$payment->getOriginal('payer')}' → '{$payment->payer}')";
            }
            if ($payment->isDirty('date_of_payment')) {
                $changes[] = "Payment Date ('{$payment->getOriginal('date_of_payment')}' → '{$payment->date_of_payment}')";
            }
            if ($payment->isDirty('due_date')) {
                $changes[] = "Due Date ('{$payment->getOriginal('due_date')}' → '{$payment->due_date}')";
            }
            if ($payment->isDirty('cheque_no')) {
                $changes[] = "Cheque No ('{$payment->getOriginal('cheque_no')}' → '{$payment->cheque_no}')";
            }
            
            $changeStr = empty($changes) ? "Minor details updated" : implode(', ', $changes);
            $message = $user->name . " updated {$type} (ID: {$payment->id}) | Changes: " . $changeStr;
            
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

    public function client()
    {
        return $this->belongsTo(Client::class);
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
