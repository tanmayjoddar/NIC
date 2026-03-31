<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('submission', function ($table) {
            $table->dropForeign(['block_code']);
        });

        DB::statement('ALTER TABLE lgd_blocks DROP CONSTRAINT lgd_blocks_pkey');
        DB::statement('ALTER TABLE lgd_blocks ADD COLUMN id BIGSERIAL PRIMARY KEY');
        DB::statement('CREATE INDEX IF NOT EXISTS lgd_blocks_block_code_idx ON lgd_blocks (block_code)');
        DB::statement('CREATE UNIQUE INDEX IF NOT EXISTS lgd_blocks_district_block_unique ON lgd_blocks (district_code, block_code)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS lgd_blocks_district_block_unique');
        DB::statement('DROP INDEX IF EXISTS lgd_blocks_block_code_idx');
        DB::statement('ALTER TABLE lgd_blocks DROP COLUMN id');
        DB::statement('ALTER TABLE lgd_blocks ADD PRIMARY KEY (block_code)');

        Schema::table('submission', function ($table) {
            $table->foreign('block_code')
                ->references('block_code')
                ->on('lgd_blocks')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }
};
