<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    use SoftDeletes;

    protected $table = 'enquiries';

    protected $fillable = [
        'created_by',
        'name',
        'a_contact_no',
        'email',
        'contact_no',
        'subject',
        'address',	
        'reference_by',	
		'budget_range',	
        'is_lost',
        'lost_remark',
        'other_config',
        'project_id_1',
        'project_id_2',
        'project_id_3',
        'project_id_4',
        'tl_1',
        'tl_2',
        'tl_3',
        'tl_4',
        'tl_5',
    ];

    protected $casts = [
        'is_lost' => 'boolean'
    ];

    public function follow_ups()
    {
        return $this->hasMany(FollowUp::class);
    }

    public function enquiry_status()
    {
        return $this->belongsTo(EnquiryStatus::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function project_1()
    {
        return $this->belongsTo(Project::class, 'project_id_1');
    }

    public function project_2()
    {
        return $this->belongsTo(Project::class, 'project_id_2');
    }

    public function project_3()
    {
        return $this->belongsTo(Project::class, 'project_id_3');
    }

    public function project_4()
    {
        return $this->belongsTo(Project::class, 'project_id_4');
    }

    public function configuration()
    {
        return $this->belongsTo(Configuration::class, 'configuration_id');
    }

    public function budget_range()
    {
        return $this->belongsTo(BudgetRange::class, 'budget_range_id');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function tl1()
    {
        return $this->belongsTo(User::class, 'tl_1');
    }

    public function tl2()
    {
        return $this->belongsTo(User::class, 'tl_2');
    }

    public function tl3()
    {
        return $this->belongsTo(User::class, 'tl_3');
    }

    public function tl4()
    {
        return $this->belongsTo(User::class, 'tl_4');
    }

    public function tl5()
    {
        return $this->belongsTo(User::class, 'tl_5');
    }
}
