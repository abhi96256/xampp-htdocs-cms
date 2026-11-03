<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dpr extends Model
{
    protected $table = 'dprs';

    protected $fillable = [
        'user_id',
        'date',
        'around_the_clock',
        'start_time',
        'end_time',
        'flier_distributed',
        'location',
        'no_of_person_called',
        'success_call',
        'no_blog_update_upload',
        'developer_site_update_upload',
        'update_upload_facebook',
        'update_upload_instagram',
        'update_upload_linkedin',
        'nos_of_lead_generated_location',
        'nos_of_developer',
        'developer_name'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
