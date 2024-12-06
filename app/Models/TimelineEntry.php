<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TimelineEntry extends Model
{

	use SoftDeletes;

    protected $casts = [
        'date' => 'datetime',
    ];

    public function timeline(): BelongsTo
    {
        return $this->belongsTo(Timeline::class);
    }

    public function media(): MorphToMany
    {
        return $this->morphToMany(Media::class, 'model', 'model_has_media')->withPivot([
            'order',
            'hidden'
        ])->orderBy('order')->withTimestamps();
    }
}
