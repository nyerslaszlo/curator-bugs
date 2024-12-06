<?php

namespace App\Models;

use App\Traits\Model\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Person extends Model
{

    use SoftDeletes, BelongsToTenant;

    public function timelines()
    {
        return $this->morphMany(Timeline::class, 'owner');
    }

    public function lifepath(): MorphOne
    {
        return $this->morphOne(Timeline::class, 'owner')->where('type', 'person-lifepath');
    }

    public function avatar(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'avatar_media_id');
    }
}
