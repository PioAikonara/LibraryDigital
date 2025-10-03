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
        Schema::table('borrows', function (Blueprint $table) {
            $table->timestamp('return_requested_at')->nullable();
            $table->foreignId('validated_by')->nullable()->constrained('users');
        });

        // Update enum values for status column
        DB::statement("ALTER TABLE borrows MODIFY COLUMN status ENUM('pending', 'borrowed', 'pending_return', 'returned', 'overdue') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('borrows', function (Blueprint $table) {
            $table->dropForeign(['validated_by']);
            $table->dropColumn(['return_requested_at', 'validated_by']);
        });

        DB::statement("ALTER TABLE borrows MODIFY COLUMN status ENUM('pending', 'borrowed', 'returned', 'overdue') NOT NULL DEFAULT 'pending'");
    }
};