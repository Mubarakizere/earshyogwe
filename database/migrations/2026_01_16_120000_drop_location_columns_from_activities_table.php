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
        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn([
                'location_name',
                'location_address',
                'location_latitude',
                'location_longitude',
                'location_region'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->string('location_name')->nullable()->after('mitigation_plan');
            $table->text('location_address')->nullable()->after('location_name');
            $table->decimal('location_latitude', 10, 8)->nullable()->after('location_address');
            $table->decimal('location_longitude', 11, 8)->nullable()->after('location_latitude');
            $table->string('location_region')->nullable()->after('location_longitude');
        });
    }
};
