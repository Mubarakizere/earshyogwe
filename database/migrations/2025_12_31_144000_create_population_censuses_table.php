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
        Schema::create('population_censuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('church_id')->constrained()->onDelete('cascade');
            $table->integer('year');
            $table->string('period')->default('annual'); // annual, q1, q2, etc.
            
            // Demographics
            $table->integer('men_count')->default(0);
            $table->integer('women_count')->default(0);
            $table->integer('youth_count')->default(0);
            $table->integer('children_count')->default(0);
            $table->integer('infants_count')->default(0);
            
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
            
            // Prevent duplicate census for same church/year/period
            $table->unique(['church_id', 'year', 'period']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('population_censuses');
    }
};
