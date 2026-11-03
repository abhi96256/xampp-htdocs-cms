<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'financial_year',
        'location',
        'cash_amount',
        'cheque_nos',
        'cheque_date',
        'cheque_amount',
        'online_amount',
        'utr_nos',
        'topology',
        'in_favour_of',
        'remarks',
    ];

    protected static function booted()
    {
        static::created(function ($payment) {
            $user = auth()->check() ? auth()->user() : (object)['name' => 'System'];
            $favour = $payment->in_favour_of ?? 'N/A';
            
            $amounts = [];
            if ($payment->cash_amount) $amounts[] = "Cash: ₹{$payment->cash_amount}";
            if ($payment->cheque_amount) $amounts[] = "Cheque: ₹{$payment->cheque_amount}";
            if ($payment->online_amount) $amounts[] = "Online: ₹{$payment->online_amount}";
            $amtStr = empty($amounts) ? '₹0' : implode(', ', $amounts);

            $message = $user->name . " created Office Payment (ID: {$payment->id}) | Amounts: {$amtStr} | In Favour Of: {$favour} | Location: " . ($payment->location ?? 'N/A');
            
            $transaction = \App\Models\Transaction::create([
                'details' => $message,
            ]);
            if (auth()->check()) {
                $transaction->createdBy()->associate($user);
                $transaction->save();
            }
        });

        static::updating(function ($payment) {
            $user = auth()->check() ? auth()->user() : (object)['name' => 'System'];
            $changes = [];
            
            $fields = [
                'cash_amount' => 'Cash',
                'cheque_amount' => 'Cheque',
                'online_amount' => 'Online',
                'in_favour_of' => 'In Favour Of',
                'location' => 'Location',
                'date' => 'Date'
            ];

            foreach ($fields as $key => $label) {
                if ($payment->isDirty($key)) {
                    $old = $payment->getOriginal($key) ?? 'N/A';
                    $new = $payment->$key ?? 'N/A';
                    $changes[] = "{$label} ('{$old}' → '{$new}')";
                }
            }
            
            $changeStr = empty($changes) ? "Minor details updated" : implode(', ', $changes);
            $message = $user->name . " updated Office Payment (ID: {$payment->id}) | Changes: " . $changeStr;
            
            $transaction = \App\Models\Transaction::create([
                'details' => $message,
            ]);
            if (auth()->check()) {
                $transaction->createdBy()->associate($user);
                $transaction->save();
            }
        });
    }
}
