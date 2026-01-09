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
            $table->string('approval_status')->default('pending')->after('status'); // pending, approved, rejected
            $table->decimal('budget_estimate', 15, 2)->nullable()->after('target');
            $table->decimal('financial_spent', 15, 2)->nullable()->after('budget_estimate');
            $table->text('completion_summary')->nullable()->after('description');
            $table->integer('attendance_count')->nullable()->after('financial_spent');
            $table->integer('salvation_count')->nullable()->after('attendance_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            //
        });
    }
};
