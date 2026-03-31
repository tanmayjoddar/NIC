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
        Schema::create('lgd_blocks', function (Blueprint $table) {
            $table->integer('serial_no')->nullable();
            $table->integer('state_code');
            $table->string('state_name')->nullable();
            $table->integer('district_code');
            $table->string('district_name')->nullable();
            $table->integer('block_code')->primary();
            $table->integer('block_version')->nullable();
            $table->string('block_name');
            $table->string('block_name_alt')->nullable();
            $table->timestamps();

            $table->foreign('state_code')
                ->references('state_code')
                ->on('lgd_states')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreign('district_code')
                ->references('district_code')
                ->on('lgd_districts')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->index('state_code');
            $table->index('district_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lgd_blocks');
    }
};
