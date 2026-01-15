<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_progress_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained()->onDelete('cascade');
            $table->foreignId('logged_by')->constrained('users')->onDelete('cascade');
            
            $table->date('log_date');
            $table->integer('progress_value')->default(0);
            $table->decimal('progress_percentage', 5, 2)->nullable();
            $table->text('notes')->nullable();
            $table->json('photos')->nullable(); // Array of photo file paths
            
            $table->timestamps();
            
            // Indexes
            $table->index(['activity_id', 'log_date']);
            $table->index('logged_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_progress_logs');
    }
};
