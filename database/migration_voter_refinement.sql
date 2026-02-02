-- Migration: Voter Refinement
-- Splits full_name into first_name and last_name
-- Adds dob column

SET FOREIGN_KEY_CHECKS=0;

-- 1. Add new columns
ALTER TABLE users 
ADD COLUMN first_name VARCHAR(50) AFTER id,
ADD COLUMN last_name VARCHAR(50) AFTER first_name;

-- 2. Migrate existing full_name data
-- Simple split: First part is first_name, rest is last_name
UPDATE users 
SET 
first_name = SUBSTRING_INDEX(full_name, ' ', 1),
last_name = SUBSTRING(full_name, LOCATE(' ', full_name) + 1);

-- Handle cases where there is no space (single name)
UPDATE users 
SET last_name = '' 
WHERE last_name = first_name;

-- 3. Modify dob column (ensure it exists and is correct type if needed)
-- Note: 'dob' was in original schema (Step 18), but let's make sure it's usable.
ALTER TABLE users MODIFY COLUMN dob DATE;

-- 4. Drop full_name column (Optional, but let's keep it clean or we can keep it for backup)
-- For now, let's keep it but make it nullable just in case.
ALTER TABLE users MODIFY COLUMN full_name VARCHAR(100) NULL;

SET FOREIGN_KEY_CHECKS=1;
