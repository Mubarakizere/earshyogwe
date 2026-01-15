<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            // Activity categorization and priority
            $table->string('activity_category')->nullable()->after('description');
            $table->enum('priority_level', ['low', 'medium', 'high', 'critical'])->default('medium')->after('activity_category');
            
            // Enhanced objectives and outcomes
            $table->text('objectives')->nullable()->after('priority_level');
            $table->text('target_beneficiaries')->nullable()->after('target');
            $table->text('expected_outcomes')->nullable()->after('target_beneficiaries');
            
            // Target details
            $table->string('target_unit', 50)->nullable()->after('expected_outcomes'); // e.g., "people", "buildings", "RWF"
            
            // Team and responsibility
            $table->json('support_team')->nullable()->after('responsible_person'); // Array of user IDs
            
            // Financial details
            $table->string('funding_source')->nullable()->after('financial_spent'); // Church/Diocese/Donation/Grant
            
            // Progress tracking settings
            $table->enum('tracking_frequency', ['daily', 'weekly', 'biweekly', 'monthly'])->default('weekly')->after('current_progress');
            
            // Risk management
            $table->text('risk_assessment')->nullable()->after('tracking_frequency');
            $table->text('mitigation_plan')->nullable()->after('risk_assessment');
            
            // Location tracking
            $table->string('location_name')->nullable()->after('mitigation_plan');
            $table->text('location_address')->nullable()->after('location_name');
            $table->decimal('location_latitude', 10, 8)->nullable()->after('location_address');
            $table->decimal('location_longitude', 11, 8)->nullable()->after('location_latitude');
            $table->string('location_region')->nullable()->after('location_longitude'); // Province/District/Sector
            
            // Duration calculation (can be auto-calculated from dates)
            $table->integer('duration_days')->nullable()->after('end_date');
            
            // Indexes for better query performance
            $table->index('activity_category');
            $table->index('priority_level');
            $table->index('tracking_frequency');
        });
    }

    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn([
                'activity_category',
                'priority_level',
                'objectives',
                'target_beneficiaries',
                'expected_outcomes',
                'target_unit',
                'support_team',
                'funding_source',
                'tracking_frequency',
                'risk_assessment',
                'mitigation_plan',
                'location_name',
                'location_address',
                'location_latitude',
                'location_longitude',
                'location_region',
                'duration_days',
            ]);
        });
    }
};
