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
        Schema::disableForeignKeyConstraints();

        Schema::create('internship_offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('internship_supervisor_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('duration');
            $table->integer('available_spots');
            $table->text('description');
            $table->string('title');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internship_offers');
    }
};