<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incentive extends Model
{

    protected $table = 'incentive';

    protected $fillable = [
        'lead_id',
        'user_id',
        'client_id',
        'remarks',
        'incentive_percentage',
        'incentive_amount',
       
    ];

   }
