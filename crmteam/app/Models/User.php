<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use SoftDeletes, HasFactory, Notifiable;
    
    use HasRoles {
        hasPermissionTo as traitHasPermissionTo;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'visible_password',
        'photo_url',
        'no_of_enquiries_assigned',
        'phone',
        'address',
        'pancard',
        'aadhar',
        'aadhar_front',
        'aadhar_back',
        'pan_card_front',
        'pan_card_back',
        'resume',
        'status',
        'export',
        'location',
        'salary',
        'tl_1',
        'tl_2',
        'tl_3',
        'tl_4',
        'tl_5',
        'police_verification',
        'police_verification_no',
        'user_verification',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function firstName()
    {
        return explode(' ', $this->name)[0];
    }

    public function displayName()
    {
        return $this->name;
    }

    public function getPhotoUrlAttribute($value)
    {
        if (!$value) return null;
        
        $paths = ['storage/galeryImages/', 'storage/profile_picture/'];
        foreach ($paths as $path) {
            if (file_exists(public_path($path . $value))) {
                return asset($path . $value);
            }
        }
        return asset('storage/galeryImages/' . $value); // Default fallback
    }

    public function getAadharAttribute($value)
    {
        if (!$value) return null;
        
        $paths = ['storage/galeryImages/', 'storage/profile_picture/'];
        foreach ($paths as $path) {
            if (file_exists(public_path($path . $value))) {
                return asset($path . $value);
            }
        }
        return asset('storage/galeryImages/' . $value);
    }

    public function getPancardAttribute($value)
    {
        if (!$value) return null;
        
        $paths = ['storage/galeryImages/', 'storage/profile_picture/'];
        foreach ($paths as $path) {
            if (file_exists(public_path($path . $value))) {
                return asset($path . $value);
            }
        }
        return asset('storage/galeryImages/' . $value);
    }

    // New Accessors
    public function getAadharFrontAttribute($value)
    {
        if (!$value) return null;
        $paths = ['storage/galeryImages/', 'storage/profile_picture/'];
        foreach ($paths as $path) {
            if (file_exists(public_path($path . $value))) return asset($path . $value);
        }
        return asset('storage/galeryImages/' . $value);
    }

    public function getAadharBackAttribute($value)
    {
        if (!$value) return null;
        $paths = ['storage/galeryImages/', 'storage/profile_picture/'];
        foreach ($paths as $path) {
            if (file_exists(public_path($path . $value))) return asset($path . $value);
        }
        return asset('storage/galeryImages/' . $value);
    }

    public function getPanCardFrontAttribute($value)
    {
        if (!$value) return null;
        $paths = ['storage/galeryImages/', 'storage/profile_picture/'];
        foreach ($paths as $path) {
            if (file_exists(public_path($path . $value))) return asset($path . $value);
        }
        return asset('storage/galeryImages/' . $value);
    }

    public function getPanCardBackAttribute($value)
    {
        if (!$value) return null;
        $paths = ['storage/galeryImages/', 'storage/profile_picture/'];
        foreach ($paths as $path) {
            if (file_exists(public_path($path . $value))) return asset($path . $value);
        }
        return asset('storage/galeryImages/' . $value);
    }

    public function getResumeAttribute($value)
    {
        if (!$value) return null;
        $paths = ['storage/galeryImages/', 'storage/profile_picture/'];
        foreach ($paths as $path) {
            if (file_exists(public_path($path . $value))) return asset($path . $value);
        }
        return asset('storage/galeryImages/' . $value);
    }

    public function getPoliceVerificationAttribute($value)
    {
        if (!$value) return null;
        
        $paths = ['storage/galeryImages/', 'storage/profile_picture/'];
        foreach ($paths as $path) {
            if (file_exists(public_path($path . $value))) {
                return asset($path . $value);
            }
        }
        return asset('storage/galeryImages/' . $value);
    }

    public function getUserVerificationAttribute($value)
    {
        if (!$value) return null;
        
        $paths = ['storage/galeryImages/', 'storage/profile_picture/'];
        foreach ($paths as $path) {
            if (file_exists(public_path($path . $value))) {
                return asset($path . $value);
            }
        }
        return asset('storage/galeryImages/' . $value);
    }

    public function adminlte_image()
    {
        return $this->photo_url ?? asset('vendor/adminlte/dist/img/user2-160x160.jpg');
    }

    // mutator for hashing password
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
        $this->attributes['visible_password'] = $password;
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function tl1() { return $this->belongsTo(User::class, 'tl_1'); }
    public function tl2() { return $this->belongsTo(User::class, 'tl_2'); }
    public function tl3() { return $this->belongsTo(User::class, 'tl_3'); }
    public function tl4() { return $this->belongsTo(User::class, 'tl_4'); }
    public function tl5() { return $this->belongsTo(User::class, 'tl_5'); }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_user')
            ->withPivot('role_id', 'parent_id')
            ->withTimestamps();
    }

    /**
     * Override Spatie's hasPermissionTo to explicitly deny revoked permissions.
     */
    public function hasPermissionTo($permission, $guardName = null): bool
    {
        $permName = is_string($permission) ? $permission : $permission->name;
        
        $revoked = $this->revoked_permissions ? json_decode($this->revoked_permissions, true) : [];
        if (in_array($permName, $revoked)) {
            return false;
        }

        return $this->traitHasPermissionTo($permission, $guardName);
    }
}
