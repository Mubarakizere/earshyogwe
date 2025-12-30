<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('worker_id')->constrained()->onDelete('cascade');
            
            $table->enum('contract_type', ['permanent', 'temporary', 'contract'])->default('contract');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            
            $table->decimal('salary', 15, 2)->nullable();
            $table->string('contract_document_path')->nullable();
            
            $table->enum('status', ['active', 'expired', 'renewed', 'terminated'])->default('active');
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['worker_id', 'status']);
            $table->index(['end_date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
