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
        // Create church_groups table
        Schema::create('church_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Create pivot table for many-to-many relationship
        Schema::create('church_group_member', function (Blueprint $table) {
            $table->id();
            $table->foreignId('church_group_id')->constrained()->cascadeOnDelete();
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            $table->date('joined_date')->nullable();
            $table->timestamps();
            
            // Ensure unique combinations
            $table->unique(['church_group_id', 'member_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('church_group_member');
        Schema::dropIfExists('church_groups');
    }
};
