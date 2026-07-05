<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'google_id',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ──────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    // ──────────────────────────────────────────
    // Scopes
    // ──────────────────────────────────────────

    /**
     * Scope query untuk hanya admin.
     */
    public function scopeAdmin($query)
    {
        return $query->whereIn('role', ['admin', 'super_admin']);
    }

    /**
     * Scope query untuk super admin saja.
     */
    public function scopeSuperAdmin($query)
    {
        return $query->where('role', 'super_admin');
    }

    // ──────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────

    /**
     * Cek apakah user adalah admin.
     */
    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'super_admin']);
    }

    /**
     * Cek apakah user adalah super admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Mendapatkan inisial nama untuk avatar.
     */
    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->name);
        $initials = '';

        foreach (array_slice($words, 0, 2) as $word) {
            $initials .= strtoupper(mb_substr($word, 0, 1));
        }

        return $initials;
    }
}
