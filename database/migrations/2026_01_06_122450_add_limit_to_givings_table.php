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
        Schema::table('givings', function (Blueprint $table) {
            $table->boolean('diocese_received')->default(false)->after('sent_to_diocese');
            $table->date('diocese_received_date')->nullable()->after('diocese_received');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('givings', function (Blueprint $table) {
            $table->dropColumn(['diocese_received', 'diocese_received_date']);
        });
    }
};
