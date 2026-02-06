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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->enum('type', ['lost', 'found']);
            $table->string('title');
            $table->string('category');
            $table->text('description');

            $table->string('location')->nullable();
            $table->date('date_occurred')->nullable();

            // optional later
            $table->string('image_path')->nullable();

            $table->enum('status', ['active', 'resolved'])->default('active');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
