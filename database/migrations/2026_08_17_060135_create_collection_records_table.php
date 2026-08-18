<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('collection_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_schedule_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained()->restrictOnDelete();
            $table->foreignId('collector_id')->constrained()->restrictOnDelete();
            $table->foreignId('waste_type_id')->constrained()->restrictOnDelete();
            $table->decimal('weight_kg', 8, 2)->nullable();
            $table->date('collection_date');
            $table->time('collection_time')->nullable();
            $table->string('photo_path')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['completed', 'missed']);
            $table->timestamp('recorded_at');
            $table->timestamps();

            $table->index(['customer_id', 'collection_date']);
            $table->index(['collector_id', 'collection_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('collection_records');
    }
};
