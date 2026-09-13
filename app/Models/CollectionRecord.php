<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CollectionRecord extends Model
{
    use HasFactory;
    protected $fillable = [
        'collection_schedule_id', 'customer_id', 'collector_id', 'waste_type_id',
        'weight_kg', 'collection_date', 'collection_time', 'photo_path',
        'notes', 'status', 'recorded_at',
    ];

    protected function casts(): array
    {
        return [
            'weight_kg' => 'decimal:2',
            'collection_date' => 'date',
            'recorded_at' => 'datetime',
        ];
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(CollectionSchedule::class, 'collection_schedule_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function collector(): BelongsTo
    {
        return $this->belongsTo(Collector::class);
    }

    public function wasteType(): BelongsTo
    {
        return $this->belongsTo(WasteType::class);
    }
}
