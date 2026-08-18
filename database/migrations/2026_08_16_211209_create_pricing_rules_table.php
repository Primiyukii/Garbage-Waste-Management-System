<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pricing_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->foreignId('waste_type_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('rule_type', ['additional_weight', 'surcharge', 'discount']);
            $table->decimal('rate', 10, 2);
            $table->enum('unit', ['per_kg', 'flat', 'percentage']);
            $table->date('effective_from');
            $table->date('effective_to')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->index(['service_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pricing_rules');
    }
};
