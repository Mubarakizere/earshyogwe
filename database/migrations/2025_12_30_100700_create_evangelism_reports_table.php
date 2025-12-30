<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evangelism_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('church_id')->constrained()->onDelete('cascade');
            $table->date('report_date');
            $table->integer('month');
            $table->integer('year');
            
            // Discipleship & Growth
            $table->integer('bible_study_count')->default(0);
            $table->integer('mentorship_count')->default(0);
            $table->integer('leadership_count')->default(0);
            
            // Evangelism Impacts
            $table->integer('converts')->default(0);
            $table->integer('baptized')->default(0);
            $table->integer('confirmed')->default(0);
            $table->integer('new_members')->default(0);
            
            $table->text('notes')->nullable();
            $table->foreignId('submitted_by')->constrained('users')->onDelete('cascade');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['church_id', 'year', 'month']);
            $table->unique(['church_id', 'year', 'month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evangelism_reports');
    }
};
