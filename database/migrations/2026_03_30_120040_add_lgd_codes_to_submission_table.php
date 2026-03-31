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
        Schema::table('submission', function (Blueprint $table) {
            $table->integer('state_code')->nullable();
            $table->integer('district_code')->nullable();
            $table->integer('subdistrict_code')->nullable();
            $table->integer('block_code')->nullable();

            $table->foreign('state_code')
                ->references('state_code')
                ->on('lgd_states')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->foreign('district_code')
                ->references('district_code')
                ->on('lgd_districts')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->foreign('subdistrict_code')
                ->references('subdistrict_code')
                ->on('lgd_subdistricts')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->foreign('block_code')
                ->references('block_code')
                ->on('lgd_blocks')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('submission', function (Blueprint $table) {
            $table->dropForeign(['state_code']);
            $table->dropForeign(['district_code']);
            $table->dropForeign(['subdistrict_code']);
            $table->dropForeign(['block_code']);

            $table->dropColumn(['state_code', 'district_code', 'subdistrict_code', 'block_code']);
        });
    }
};
