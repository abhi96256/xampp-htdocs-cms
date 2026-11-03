<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class TeamMember extends Model
{
    use HasFactory;

    protected $table = 'team_user';

    protected $fillable = ['team_id', 'user_id', 'role_id', 'parent_id'];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(TeamMember::class, 'parent_id', 'user_id')
            ->where('team_id', $this->team_id);
    }
}
