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
        Schema::create('lgd_states', function (Blueprint $table) {
            $table->integer('serial_no')->nullable();
            $table->integer('state_code')->primary();
            $table->integer('state_version')->nullable();
            $table->string('state_name');
            $table->string('state_name_alt')->nullable();
            $table->string('census_2001_code', 20)->nullable();
            $table->string('census_2011_code', 20)->nullable();
            $table->string('state_or_ut', 5)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lgd_states');
    }
};
