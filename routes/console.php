<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('lgd:import-administrative {basePath?}', function (?string $basePath = null) {
    $basePath = $basePath ?: 'D:/Downloads/lgd/india-local-government-directory-main/administrative';

    $files = [
        'states' => $basePath.'/1-state.csv',
        'districts' => $basePath.'/2-district.csv',
        'subdistricts' => $basePath.'/3-subdistrict.csv',
        'blocks' => $basePath.'/blocks.csv',
    ];

    foreach ($files as $name => $path) {
        if (!is_file($path)) {
            $this->error("Missing {$name} file: {$path}");
            return 1;
        }
    }

    $this->info('Truncating LGD tables...');
    DB::statement('TRUNCATE TABLE lgd_blocks, lgd_subdistricts, lgd_districts, lgd_states RESTART IDENTITY CASCADE');

    $normalize = static function ($value) {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);
        return $value === '' ? null : $value;
    };

    $importCsv = function (
        string $filePath,
        string $table,
        callable $mapRow,
        array $uniqueBy,
        array $updateColumns
    ) {
        $handle = fopen($filePath, 'r');
        if ($handle === false) {
            throw new \RuntimeException("Unable to open {$filePath}");
        }

        fgetcsv($handle);
        $buffer = [];
        $now = now();
        $count = 0;

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) === 1 && trim((string) $row[0]) === '') {
                continue;
            }

            $mapped = $mapRow($row);
            $mapped['created_at'] = $now;
            $mapped['updated_at'] = $now;
            $buffer[] = $mapped;
            $count++;

            if (count($buffer) >= 1000) {
                DB::table($table)->upsert($buffer, $uniqueBy, $updateColumns);
                $buffer = [];
            }
        }

        if (!empty($buffer)) {
            DB::table($table)->upsert($buffer, $uniqueBy, $updateColumns);
        }

        fclose($handle);
        return $count;
    };

    $this->info('Importing states...');
    $stateCount = $importCsv(
        $files['states'],
        'lgd_states',
        function (array $row) use ($normalize) {
            return [
                'serial_no' => $normalize($row[0]),
                'state_code' => $normalize($row[1]),
                'state_version' => $normalize($row[2]),
                'state_name' => $normalize($row[3]),
                'state_name_alt' => $normalize($row[4]),
                'census_2001_code' => $normalize($row[5]),
                'census_2011_code' => $normalize($row[6]),
                'state_or_ut' => $normalize($row[7]),
            ];
        },
        ['state_code'],
        ['serial_no', 'state_version', 'state_name', 'state_name_alt', 'census_2001_code', 'census_2011_code', 'state_or_ut', 'updated_at']
    );

    $this->info('Importing districts...');
    $districtCount = $importCsv(
        $files['districts'],
        'lgd_districts',
        function (array $row) use ($normalize) {
            return [
                'state_code' => $normalize($row[0]),
                'state_name' => $normalize($row[1]),
                'district_code' => $normalize($row[2]),
                'district_name' => $normalize($row[3]),
                'census_2001_code' => $normalize($row[4]),
                'census_2011_code' => $normalize($row[5]),
            ];
        },
        ['district_code'],
        ['state_code', 'state_name', 'district_name', 'census_2001_code', 'census_2011_code', 'updated_at']
    );

    $this->info('Importing subdistricts...');
    $subdistrictCount = $importCsv(
        $files['subdistricts'],
        'lgd_subdistricts',
        function (array $row) use ($normalize) {
            return [
                'serial_no' => $normalize($row[0]),
                'state_code' => $normalize($row[1]),
                'state_name' => $normalize($row[2]),
                'district_code' => $normalize($row[3]),
                'district_name' => $normalize($row[4]),
                'subdistrict_code' => $normalize($row[5]),
                'subdistrict_version' => $normalize($row[6]),
                'subdistrict_name' => $normalize($row[7]),
                'census_2001_code' => $normalize($row[8]),
                'census_2011_code' => $normalize($row[9]),
            ];
        },
        ['subdistrict_code'],
        ['serial_no', 'state_code', 'state_name', 'district_code', 'district_name', 'subdistrict_version', 'subdistrict_name', 'census_2001_code', 'census_2011_code', 'updated_at']
    );

    $this->info('Importing blocks...');
    $blockHandle = fopen($files['blocks'], 'r');
    if ($blockHandle === false) {
        throw new \RuntimeException("Unable to open {$files['blocks']}");
    }

    fgetcsv($blockHandle);
    $blockCount = 0;
    $now = now();

    while (($row = fgetcsv($blockHandle)) !== false) {
        if (count($row) === 1 && trim((string) $row[0]) === '') {
            continue;
        }

        $districtCode = $normalize($row[3]);
        $blockCode = $normalize($row[5]);

        if ($districtCode === null || $blockCode === null) {
            continue;
        }

        DB::table('lgd_blocks')->updateOrInsert(
            [
                'district_code' => (int) $districtCode,
                'block_code' => (int) $blockCode,
            ],
            [
                'serial_no' => $normalize($row[0]),
                'state_code' => $normalize($row[1]),
                'state_name' => $normalize($row[2]),
                'district_name' => $normalize($row[4]),
                'block_version' => $normalize($row[6]),
                'block_name' => $normalize($row[7]),
                'block_name_alt' => $normalize($row[8]),
                'updated_at' => $now,
                'created_at' => $now,
            ]
        );

        $blockCount++;
    }

    fclose($blockHandle);

    $this->newLine();
    $this->info("Imported states: {$stateCount}");
    $this->info("Imported districts: {$districtCount}");
    $this->info("Imported subdistricts: {$subdistrictCount}");
    $this->info("Imported blocks: {$blockCount}");

    return 0;
})->purpose('Import LGD administrative CSV files into PostgreSQL tables');
