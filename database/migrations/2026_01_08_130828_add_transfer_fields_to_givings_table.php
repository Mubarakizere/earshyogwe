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
            $table->string('receipt_status')->default('pending')->after('diocese_amount'); // pending, verified, rejected
            $table->string('transfer_reference')->nullable()->after('receipt_status');
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null')->after('transfer_reference');
            $table->timestamp('verified_at')->nullable()->after('verified_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('givings', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropColumn(['receipt_status', 'transfer_reference', 'verified_by', 'verified_at']);
        });
    }
};
