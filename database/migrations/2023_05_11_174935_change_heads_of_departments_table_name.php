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
        //
        Schema::rename('heads_of_departments','head_of_departments');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
