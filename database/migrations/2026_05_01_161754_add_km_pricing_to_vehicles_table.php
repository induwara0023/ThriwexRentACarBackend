<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            if (!Schema::hasColumn('vehicles', 'km_limit_per_day')) {
                $table->integer('km_limit_per_day')->default(0)->after('daily_rate');
            }
            if (!Schema::hasColumn('vehicles', 'extra_km_rate')) {
                $table->decimal('extra_km_rate', 10, 2)->default(0)->after('km_limit_per_day');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn(['km_limit_per_day', 'extra_km_rate']);
        });
    }
};
