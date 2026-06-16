<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'description'];
    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function administrativeRequests()
    {
        return $this->hasMany(AdministrativeRequest::class);
    }
}
