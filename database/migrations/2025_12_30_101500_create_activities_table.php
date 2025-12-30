<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->foreignId('church_id')->constrained()->onDelete('cascade');
            
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('responsible_person')->nullable();
            
            $table->integer('target')->default(0);
            $table->integer('current_progress')->default(0);
            
            $table->date('start_date');
            $table->date('end_date')->nullable();
            
            $table->enum('status', ['planned', 'in_progress', 'completed', 'cancelled'])->default('planned');
            
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['department_id', 'status']);
            $table->index(['church_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
