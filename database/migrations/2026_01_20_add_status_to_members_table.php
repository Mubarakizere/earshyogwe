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
        Schema::table('members', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive', 'deceased'])->default('active')->after('extra_attributes');
            $table->text('inactive_reason')->nullable()->after('status');
            $table->date('inactive_date')->nullable()->after('inactive_reason');
            $table->date('deceased_date')->nullable()->after('inactive_date');
            $table->text('deceased_cause')->nullable()->after('deceased_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn([
                'status',
                'inactive_reason',
                'inactive_date',
                'deceased_date',
                'deceased_cause'
            ]);
        });
    }
};
