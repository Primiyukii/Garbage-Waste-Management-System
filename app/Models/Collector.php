<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Collector extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'employee_id',
        'date_hired',
        'photo',
    ];

    protected function casts(): array
    {
        return [
            'date_hired' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function areas(): BelongsToMany
    {
        return $this->belongsToMany(Barangay::class, 'collector_areas');
    }
    public function collectionSchedules(): HasMany
    {
        return $this->hasMany(CollectionSchedule::class);
    }

    public function collectionRecords(): HasMany
    {
        return $this->hasMany(CollectionRecord::class);
    }
    // collectionSchedules(), collectionRecords() will be added later in Phase 3
}
