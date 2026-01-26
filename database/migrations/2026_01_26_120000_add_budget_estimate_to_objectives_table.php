<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('objectives', 'budget_estimate')) {
            Schema::table('objectives', function (Blueprint $table) {
                $table->decimal('budget_estimate', 15, 2)->nullable()->after('end_date');
            });
        }
    }

    public function down(): void
    {
        Schema::table('objectives', function (Blueprint $table) {
            $table->dropColumn('budget_estimate');
        });
    }
};
