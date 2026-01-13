<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('institutions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', [
                'diocese',
                'health_center',
                'health_post',
                'primary_school',
                'secondary_school',
                'university',
                'rw_project'
            ]);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('type');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institutions');
    }
};
