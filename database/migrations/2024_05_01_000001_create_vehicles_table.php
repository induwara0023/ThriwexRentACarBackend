<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('plate_no')->unique();
            $table->string('model');
            $table->enum('type', ['Car', 'Van', 'SUV']);
            $table->enum('transmission', ['Auto', 'Manual']);
            $table->integer('current_km')->default(0);
            $table->integer('next_service_km')->nullable();
            $table->date('insurance_expiry')->nullable();
            $table->date('license_expiry')->nullable();
            $table->enum('status', ['available', 'rented', 'maintenance'])->default('available');
            $table->decimal('daily_rate', 10, 2);
            $table->integer('km_limit_per_day')->default(0);
            $table->decimal('extra_km_rate', 10, 2)->default(0);
            $table->string('image_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
