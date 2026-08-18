<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('customer_code')->unique();
            $table->foreignId('barangay_id')->constrained()->restrictOnDelete();
            $table->string('street');
            $table->string('house_number');
            $table->string('postal_code')->nullable();
            $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete();
            $table->date('registered_at');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
