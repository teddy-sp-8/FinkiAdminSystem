<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function requests()
    {
        return $this->hasMany(AdministrativeRequest::class);
    }

    public function getIndexNumberAttribute(): ?string
    {
        if ($this->is_admin) {
            return null;
        }

        $year = $this->created_at ? $this->created_at->format('y') : date('y');
        $id = $this->id;
        return $year . str_pad($id, 5, '0', STR_PAD_LEFT);
    }


}
