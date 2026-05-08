<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('nic_front')->nullable()->after('status');
            $table->string('nic_back')->nullable()->after('nic_front');
            $table->string('license_front')->nullable()->after('nic_back');
            $table->string('license_back')->nullable()->after('license_front');
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['nic_front', 'nic_back', 'license_front', 'license_back']);
        });
    }
};
