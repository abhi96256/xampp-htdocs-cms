<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'team_user')
            ->withPivot('role_id', 'parent_id')
            ->withTimestamps();
    }

    public function members()
    {
        return $this->hasMany(TeamMember::class);
    }
}
