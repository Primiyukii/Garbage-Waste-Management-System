<?php

namespace App\Services;

use App\Models\CollectionRecord;
use App\Models\CollectionSchedule;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class CollectionService
{
    public function start(CollectionSchedule $schedule): CollectionSchedule
    {
        $schedule->update(['status' => 'in_progress']);

        return $schedule;
    }

    public function complete(CollectionSchedule $schedule, array $data, ?UploadedFile $photo = null): CollectionRecord
    {
        return DB::transaction(function () use ($schedule, $data, $photo) {
            $photoPath = $photo ? $photo->store('collection-photos', 'public') : null;

            $record = CollectionRecord::create([
                'collection_schedule_id' => $schedule->id,
                'customer_id' => $schedule->customer_id,
                'collector_id' => $schedule->collector_id,
                'waste_type_id' => $data['waste_type_id'],
                'weight_kg' => $data['weight_kg'],
                'collection_date' => now(),
                'collection_time' => now()->format('H:i:s'),
                'photo_path' => $photoPath,
                'notes' => $data['notes'] ?? null,
                'status' => 'completed',
                'recorded_at' => now(),
            ]);

            $schedule->update(['status' => 'completed']);

            return $record;
        });
    }

    public function reportMissed(CollectionSchedule $schedule, ?string $notes = null): CollectionRecord
    {
        return DB::transaction(function () use ($schedule, $notes) {
            $record = CollectionRecord::create([
                'collection_schedule_id' => $schedule->id,
                'customer_id' => $schedule->customer_id,
                'collector_id' => $schedule->collector_id,
                'waste_type_id' => $schedule->waste_type_id,
                'weight_kg' => null,
                'collection_date' => now(),
                'notes' => $notes,
                'status' => 'missed',
                'recorded_at' => now(),
            ]);

            $schedule->update(['status' => 'missed']);

            return $record;
        });
    }
}
