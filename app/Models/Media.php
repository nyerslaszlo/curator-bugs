<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Media extends \Awcodes\Curator\Models\Media
{

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function person(): HasMany
    {
        return $this->hasMany(Person::class);
    }

    public function timelineEntries(): MorphToMany
    {
        return $this->morphedByMany(TimelineEntry::class, 'model', 'model_has_media')->withPivot([
            'order',
            'hidden'
        ])->withTimestamps();
    }

}
