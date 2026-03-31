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
        Schema::create('lgd_districts', function (Blueprint $table) {
            $table->integer('state_code');
            $table->string('state_name')->nullable();
            $table->integer('district_code')->primary();
            $table->string('district_name');
            $table->string('census_2001_code', 20)->nullable();
            $table->string('census_2011_code', 20)->nullable();
            $table->timestamps();

            $table->foreign('state_code')
                ->references('state_code')
                ->on('lgd_states')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->index('state_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lgd_districts');
    }
};
