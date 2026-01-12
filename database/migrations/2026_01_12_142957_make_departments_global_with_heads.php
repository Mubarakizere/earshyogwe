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
        // Make church_id nullable using raw SQL
        \DB::statement('ALTER TABLE departments MODIFY COLUMN church_id BIGINT UNSIGNED NULL');
        
        Schema::table('departments', function (Blueprint $table) {
            // Add head_id for department heads
            $table->foreignId('head_id')->nullable()->after('church_id')
                ->constrained('users')->nullOnDelete();
            
            // Add slug for permission naming
            $table->string('slug')->after('name')->nullable(); // Nullable first, we'll populate then make unique
        });
        
        // Generate slugs for existing departments
        \DB::statement("UPDATE departments SET slug = LOWER(REPLACE(REPLACE(name, ' ', '-'), '''', ''))");
        
        // Now make slug unique
        Schema::table('departments', function (Blueprint $table) {
            $table->string('slug')->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->foreignId('church_id')->nullable(false)->change();
            $table->dropForeign(['head_id']);
            $table->dropColumn(['head_id', 'slug']);
        });
    }
};
