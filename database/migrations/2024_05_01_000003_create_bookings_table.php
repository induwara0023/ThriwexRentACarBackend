<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles');
            $table->foreignId('customer_id')->constrained('customers');
            $table->dateTime('pickup_datetime');
            $table->dateTime('return_datetime')->nullable();
            $table->integer('pickup_km');
            $table->integer('return_km')->nullable();
            $table->decimal('advance_payment', 10, 2)->default(0);
            $table->decimal('total_price', 10, 2)->nullable();
            $table->text('security_item_description')->nullable();
            $table->enum('status', ['ongoing', 'completed'])->default('ongoing');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
