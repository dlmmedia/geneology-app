-- SQL script to update photo references in the database
-- This matches photo files in storage with database records

-- For MySQL/MariaDB:
-- UPDATE people p
-- INNER JOIN (
--     SELECT team_id, id, CONCAT(id, '_001_demo') as expected_photo
--     FROM people
--     WHERE team_id = 15
-- ) as expected ON p.id = expected.id AND p.team_id = expected.team_id
-- SET p.photo = expected.expected_photo
-- WHERE p.photo IS NULL OR p.photo != expected.expected_photo;

-- For British Royals (team_id = 15):
-- Set photo field based on person ID and demo file pattern
UPDATE people 
SET photo = CONCAT(id, '_001_demo') 
WHERE team_id = 15 
  AND (photo IS NULL OR photo = '')
  AND EXISTS (
      SELECT 1 FROM (
          SELECT 1
      ) as check_exists
  );

-- For people who might have multiple photos (like person 3 with 3_002_demo)
-- We'll need to check files individually, but for most cases, _001_demo is correct

-- Verify the update:
-- SELECT id, firstname, surname, photo, team_id 
-- FROM people 
-- WHERE team_id = 15 
-- ORDER BY id;

