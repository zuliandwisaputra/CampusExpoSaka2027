<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLES = [
        'super_admin' => 'Super admin',
        'koordinator' => 'Koordinator divisi',
    ];

    protected $fillable = ['name', 'email', 'password', 'role', 'divisi_id', 'aktif'];

    /** Nilai bawaan sama dengan default kolom di database. */
    protected $attributes = [
        'role' => 'koordinator',
        'aktif' => true,
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'aktif' => 'boolean',
        ];
    }

    public function divisi(): BelongsTo
    {
        return $this->belongsTo(Divisi::class);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isKoordinator(): bool
    {
        return $this->role === 'koordinator';
    }

    public function getRoleLabelAttribute(): string
    {
        return self::ROLES[$this->role] ?? $this->role;
    }
}
