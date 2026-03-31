-- Run this file using psql after running Laravel migrations.
-- Example:
-- psql -U postgres -d nic_db -h 127.0.0.1 -p 5432 -f "D:/NIC/mahapp/database/sql/load_lgd_administrative.sql"

BEGIN;

TRUNCATE TABLE lgd_blocks, lgd_subdistricts, lgd_districts, lgd_states RESTART IDENTITY CASCADE;

COMMIT;

-- Update these file paths if your CSV location is different.
\copy lgd_states (serial_no, state_code, state_version, state_name, state_name_alt, census_2001_code, census_2011_code, state_or_ut)
FROM 'D:/Downloads/lgd/india-local-government-directory-main/administrative/1-state.csv'
WITH (FORMAT csv, HEADER true, DELIMITER ',');

\copy lgd_districts (state_code, state_name, district_code, district_name, census_2001_code, census_2011_code)
FROM 'D:/Downloads/lgd/india-local-government-directory-main/administrative/2-district.csv'
WITH (FORMAT csv, HEADER true, DELIMITER ',');

\copy lgd_subdistricts (serial_no, state_code, state_name, district_code, district_name, subdistrict_code, subdistrict_version, subdistrict_name, census_2001_code, census_2011_code)
FROM 'D:/Downloads/lgd/india-local-government-directory-main/administrative/3-subdistrict.csv'
WITH (FORMAT csv, HEADER true, DELIMITER ',');

\copy lgd_blocks (serial_no, state_code, state_name, district_code, district_name, block_code, block_version, block_name, block_name_alt)
FROM 'D:/Downloads/lgd/india-local-government-directory-main/administrative/blocks.csv'
WITH (FORMAT csv, HEADER true, DELIMITER ',');

-- Optional checks
SELECT COUNT(*) AS states FROM lgd_states;
SELECT COUNT(*) AS districts FROM lgd_districts;
SELECT COUNT(*) AS subdistricts FROM lgd_subdistricts;
SELECT COUNT(*) AS blocks FROM lgd_blocks;
