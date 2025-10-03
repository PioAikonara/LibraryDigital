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
        // Add fines to borrows table
        Schema::table('borrows', function (Blueprint $table) {
            $table->decimal('fine_amount', 10, 2)->default(0);
            $table->boolean('fine_paid')->default(false);
            $table->timestamp('fine_paid_at')->nullable();
            $table->foreignId('fine_validated_by')->nullable()->constrained('users');
        });

        // Create book locations table
        Schema::create('book_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->string('rack_number');
            $table->string('shelf_number');
            $table->string('section')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Create complaints table
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('subject');
            $table->text('description');
            $table->enum('status', ['pending', 'in_progress', 'resolved'])->default('pending');
            $table->foreignId('handled_by')->nullable()->constrained('users');
            $table->text('response')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('borrows', function (Blueprint $table) {
            $table->dropForeign(['fine_validated_by']);
            $table->dropColumn(['fine_amount', 'fine_paid', 'fine_paid_at', 'fine_validated_by']);
        });

        Schema::dropIfExists('book_locations');
        Schema::dropIfExists('complaints');
    }
};