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
        Schema::table('users', function (Blueprint $table) {
            // First drop foreign key constraint
            $table->dropForeign(['company_id']);

            // Then drop the column
            $table->dropColumn('company_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add it back if migration is rolled back
            $table->unsignedBigInteger('company_id')->nullable();

            // Restore the foreign key if needed
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }
};
