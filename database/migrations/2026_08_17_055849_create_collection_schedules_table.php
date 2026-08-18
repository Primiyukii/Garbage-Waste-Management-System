<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('collection_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('collector_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('service_id')->constrained()->restrictOnDelete();
            $table->foreignId('waste_type_id')->constrained()->restrictOnDelete();
            $table->foreignId('barangay_id')->constrained()->restrictOnDelete();
            $table->date('scheduled_date');
            $table->time('scheduled_time')->nullable();
            $table->enum('frequency', ['daily', 'weekly', 'twice_weekly', 'monthly']);
            $table->enum('status', [
                'scheduled', 'assigned', 'in_progress', 'completed', 'missed', 'cancelled',
            ])->default('scheduled');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['scheduled_date', 'status']);
            $table->index(['collector_id', 'scheduled_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('collection_schedules');
    }
};
