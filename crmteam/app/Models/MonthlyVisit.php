<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyVisit extends Model
{
    use HasFactory;

    protected $table = 'monthly_visits';

    protected $fillable = [
        'user_id',
        'client_name',
        'email_id',
        'contact_no',
        'date_of_visit',
        'site_visited',
        'room_name',
        'date_of_visit_2',
        'site_visited_2',
        'room_name_2',
        'date_of_visit_3',
        'site_visited_3',
        'room_name_3',
        'date_of_visit_4',
        'site_visited_4',
        'room_name_4',
        'date_of_visit_5',
        'site_visited_5',
        'room_name_5',
        'date_of_visit_6',
        'site_visited_6',
        'room_name_6',
        'interested_in',
        'remarks',
        'user_name'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
