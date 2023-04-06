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
        Schema::create('demands', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('supervisor_email');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('duration');
            $table->string('status');
            $table->date('date');
            $table->text('rejection_motive')->nullable();
            $table->string('title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demands');
    }
};