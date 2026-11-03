<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Leads extends Model
{
    use SoftDeletes;

    protected $table = 'leads';

    protected $fillable = [
        'name',
        'date',
        'mobile',
        'email',
        'address',
        'collection_mode',
        'reference',
        'assigned_to',
		'status'
    ];

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
