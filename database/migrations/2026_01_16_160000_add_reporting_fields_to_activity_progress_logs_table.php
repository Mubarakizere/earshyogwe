<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activity_progress_logs', function (Blueprint $table) {
            $table->text('activities_performed')->nullable()->after('notes');
            $table->text('results_outcome')->nullable()->after('activities_performed');
            $table->string('location')->nullable()->after('results_outcome');
            $table->decimal('financial_spent', 15, 2)->default(0)->after('progress_value');
        });
    }

    public function down(): void
    {
        Schema::table('activity_progress_logs', function (Blueprint $table) {
            $table->dropColumn([
                'activities_performed',
                'results_outcome',
                'location',
                'financial_spent'
            ]);
        });
    }
};
