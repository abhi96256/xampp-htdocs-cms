<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use SoftDeletes;

    protected $table = 'clients';

    protected $fillable = [
        'name',
        'business_name',
        'email',
        'contact_no',
        'subject',
        'carpet_area',
        'agreement_value',
        'booking_amount',
        'attachment',
        'rating',
        'remark',
        'is_active',
		'lead_id',
		'floor_no',
        'unit_no',
        'tower_no',
        'actual_amount',
        'cheque_no',
        'payment_plan',
        'pass_on_to_customer',
        'tl_1',
        'tl_2',
        'tl_3',
        'tl_4',
        'tl_5',
        'closed_by',
        'closed_by_name',
        'closed_by_team1',
        'closed_by_team2',
    ];

    protected $casts = [
        'carpet_area' => 'double',
        'agreement_value' => 'double',
        'booking_amount' => 'double',
        'is_active' => 'bool',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function configuration()
    {
        return $this->belongsTo(Configuration::class, 'configuration_id');
    }

    public function payment_mode()
    {
        return $this->belongsTo(PaymentMode::class, 'payment_mode_id');
    }

    public function closedBy()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
