<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('collector_areas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collector_id')->constrained()->cascadeOnDelete();
            $table->foreignId('barangay_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['collector_id', 'barangay_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('collector_areas');
    }
};
