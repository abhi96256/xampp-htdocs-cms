<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class commision extends Model
{
    use SoftDeletes;

    protected $table = 'invoice_commision';

    protected $fillable = [
         'invoice_id', 'name', 'amount', 'tds_deduction', 'amount_paid', 'cheque_no', 'created_at', 'updated_at'
    ];

}
