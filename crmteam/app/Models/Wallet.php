<?php

namespace App\Models;

use App\Lancer\Utilities;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use SoftDeletes;

    protected $table = 'wallet';

    protected $fillable = [
       'id', 'user_id', 'added_by', 'payment_method', 'amount', 'balance', 'created_at', 'updated_at',
		'cheque_no', 'date', 'payee'
    ];

    
}
