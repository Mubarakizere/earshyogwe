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
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn('service_type');
            $table->foreignId('service_type_id')->nullable()->after('church_id')->constrained('service_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            if (Schema::hasColumn('attendances', 'service_type_id')) {
                $table->dropForeign(['service_type_id']);
                $table->dropColumn('service_type_id');
            }
            if (!Schema::hasColumn('attendances', 'service_type')) {
                $table->string('service_type')->nullable();
            }
        });
    }
};
