<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Timeline extends Model
{
	use SoftDeletes;

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function owner(): MorphTo
    {
        return $this->morphTo('owner');
    }

    public function timelineEntries(): HasMany
    {
        return $this->hasMany(TimelineEntry::class)->orderBy('date', 'desc');
    }
}
