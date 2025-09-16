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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('description');
            $table->longText('vision');
            $table->longText('goal');
            $table->string('logo_url');
            $table->date('founded_date');
            $table->string('address');
            $table->foreignId('creator_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
