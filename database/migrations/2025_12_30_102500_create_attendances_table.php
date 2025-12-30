<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('church_id')->constrained()->onDelete('cascade');
            
            $table->date('attendance_date');
            $table->enum('service_type', ['sunday_service', 'prayer_meeting', 'bible_study', 'youth_service', 'special_event', 'other'])->default('sunday_service');
            $table->string('service_name')->nullable(); // For special events
            
            // Demographics
            $table->integer('men_count')->default(0);
            $table->integer('women_count')->default(0);
            $table->integer('children_count')->default(0);
            $table->integer('total_count')->default(0);
            
            // Auto-calculated fields
            $table->integer('week')->nullable();
            $table->integer('month');
            $table->integer('year');
            
            $table->text('notes')->nullable();
            $table->foreignId('recorded_by')->constrained('users')->onDelete('cascade');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['church_id', 'attendance_date']);
            $table->index(['church_id', 'year', 'month']);
            $table->index(['service_type', 'attendance_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
