<?php

namespace App\Models;

use Filament\Models\Contracts\HasCurrentTenantLabel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model implements HasCurrentTenantLabel
{

	use SoftDeletes;

    public function getCurrentTenantLabel(): string
    {
        return 'Active Tenant';
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'tenant_has_users');
    }

    public function people(): HasMany
    {
        return $this->hasMany(Person::class);
    }
}
