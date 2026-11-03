<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAgreement extends Model
{
    protected $table = 'user_agreements';
    
    protected $fillable = [
        'user_id', 
        'agreement_type', 
        'document_path',
        'uploaded_document',
        'upload_note',
        'document_sent_at',
        'otp_code', 
        'otp_expires_at', 
        'verification_token', 
        'status', 
        'ip_address', 
        'signature',
        'signed_at'
    ];
    
    protected $casts = [
        'otp_expires_at'   => 'datetime',
        'signed_at'        => 'datetime',
        'document_sent_at' => 'datetime',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
