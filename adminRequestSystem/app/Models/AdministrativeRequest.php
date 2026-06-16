<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdministrativeRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'request_type_id',
        'description',
        'student_attachment',
        'status',
        'ai_suggestion',
        'admin_note',
        'ai_feedback',
        'admin_feedback',
        'issued_document',
        'payment_status'
    ];
    protected $casts = [
        'requires_fields' => 'array', 'price' => 'float'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function requestType(): BelongsTo
    {
        return $this->belongsTo(RequestType::class);
    }
}
