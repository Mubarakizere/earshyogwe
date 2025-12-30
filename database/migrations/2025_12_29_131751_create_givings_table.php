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
        Schema::create('givings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('church_id')->constrained()->onDelete('cascade');
            $table->foreignId('giving_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('giving_sub_type_id')->nullable()->constrained()->onDelete('set null');
            
            $table->decimal('amount', 15, 2);
            $table->date('date');
            $table->integer('week')->nullable(); // Week number of the year
            $table->integer('month'); // Month number (1-12)
            $table->integer('year'); // Year
            
            // Diocese transfer tracking
            $table->boolean('sent_to_diocese')->default(false);
            $table->date('diocese_sent_date')->nullable();
            $table->decimal('diocese_amount', 15, 2)->nullable();
            
            $table->text('notes')->nullable();
            $table->foreignId('entered_by')->constrained('users')->onDelete('cascade');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for better query performance
            $table->index(['church_id', 'date']);
            $table->index(['church_id', 'year', 'month']);
            $table->index(['giving_type_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('givings');
    }
};
