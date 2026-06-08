<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Provider extends Authenticatable implements FilamentUser
{
    use HasFactory, HasRoles, Notifiable;

    protected $guard_name = 'provider';

    protected $fillable = [
        'name',
        'email',
        'password',
        'company_name',
        'phone',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'website',
        'description',
        'logo_url',
        'is_active',
        'is_verified',
        'verified_at',
        'tax_id',
        'contact_person',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'is_verified' => 'boolean',
            'verified_at' => 'datetime',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $panel->getId() === 'provider' && $this->is_active;
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
