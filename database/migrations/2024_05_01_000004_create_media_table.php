<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->enum('type', ['selfie', 'nic_front', 'nic_back', 'agreement', 'security_item']);
            $table->string('file_path');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_media');
    }
};
