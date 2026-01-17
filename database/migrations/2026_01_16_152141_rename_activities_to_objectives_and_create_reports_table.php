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
        // 1. Rename activities table to objectives
        Schema::rename('activities', 'objectives');

        // 2. Clean up objectives table (Remove columns that are now part of the report or unused)
        Schema::table('objectives', function (Blueprint $table) {
            // Drop columns related to execution/tracking that are now in reports
            // Checking if columns exist before dropping to be safe, though strict schema knowledge implies they do.
            // Based on previous files, these are the columns to drop:
            $table->dropColumn([
                'current_progress',
                'financial_spent',
                'completion_summary',
                'responsible_person', // Responsible person is now in the report
                // 'location' related columns were recently dropped/added, checking status...
                // The user said "update columns to match image", dragging unused ones.
                // We keep 'target' and 'target_unit' as they are part of the Objective (Planned Goal).
                
                // Enhanced fields that might be report specific:
                // 'risk_assessment', 'mitigation_plan' -> meaningful for planning (Objective), keeping them.
                // 'tracking_frequency' -> meaningful for planning.
                
                // Recent additions that are likely report specific or redundant now:
                // 'activity_category', 'priority_level' -> Objective properties? Yes.
            ]);
        });

        // 3. Create objective_reports table
        Schema::create('objective_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('objective_id')->constrained('objectives')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users'); // The Pastor/Reporter
            
            $table->date('report_date'); // "Date" from image
            
            $table->text('activities_description'); // "Activities" box from image
            $table->text('results_outcome'); // "Results (Outcome)" box from image
            
            $table->decimal('quantity', 12, 2)->default(0); // "Quantity/Output" box
            
            // Location fields (Restoring "Location" concept here)
            $table->string('location')->nullable(); // "Location" box
            
            $table->decimal('budget_spent', 15, 2)->default(0); // "Budget" box
            $table->string('responsible_person')->nullable(); // "Responsible Person" box
            
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected'])->default('submitted');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('objective_reports');

        Schema::table('objectives', function (Blueprint $table) {
            $table->integer('current_progress')->default(0);
            $table->decimal('financial_spent', 15, 2)->default(0);
            $table->text('completion_summary')->nullable();
            $table->string('responsible_person')->nullable();
        });

        Schema::rename('objectives', 'activities');
    }
};
