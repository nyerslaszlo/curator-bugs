<?php

namespace App\Traits\Model;

use App\Models\Tenant;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Schema;

trait BelongsToTenant
{
    public static function bootBelongsToTenant(): void
    {
        //We are filling out 'tenant_id' with the user's currently active tenant.
        static::saving(function (Model $model) {
            if ( !Schema::hasColumn($model->getTable(), 'tenant_id')) {
                throw new \RuntimeException("$model has no tenant_id column");
            }
            if ($model->tenant_id === null) {
                $model->tenant_id =
                    auth()->user()->current_tenant_id
                    ?? Filament::getTenant()?->id
                    ?? Tenant::getMainTenant()->id;
            }
        });

    }

    public function initializeBelongsToTenant(): void
    {
        $this->hidden[] = 'tenant_id';
        $this->fillable[] = 'tenant_id';

    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
