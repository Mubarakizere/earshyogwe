<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('church_id')->constrained()->onDelete('cascade');
            $table->foreignId('department_id')->nullable()->constrained()->onDelete('set null');
            
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            
            $table->string('position');
            $table->date('employment_date');
            $table->date('birth_date')->nullable();
            $table->integer('retirement_age')->default(60);
            
            $table->enum('status', ['active', 'retired', 'terminated'])->default('active');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['church_id', 'status']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workers');
    }
};
