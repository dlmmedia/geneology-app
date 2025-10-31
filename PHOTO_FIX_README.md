# Photo Fix Instructions

## Problem
Photos exist in storage but aren't showing in the UI because database `photo` references are missing or incorrect.

## Quick Fix (SQL)

If you have database access, run this SQL to update all photo references:

```sql
-- For MySQL/MariaDB - Update British Royals photos
UPDATE people 
SET photo = CONCAT(id, '_001_demo') 
WHERE team_id = 15 
  AND (photo IS NULL OR photo = '' OR photo != CONCAT(id, '_001_demo'))
  AND EXISTS (
    SELECT 1 
    FROM (SELECT 1) as temp
  );
```

Note: Replace `15` with the actual team_id for BRITISH ROYALS if different.

## Complete Fix (Laravel Command)

Run the comprehensive fix command:

```bash
php artisan photos:ensure-all --team="BRITISH ROYALS"
```

This command will:
1. ✅ Fix all database photo references to match existing files
2. ✅ Download missing photos for people without image files
3. ✅ Verify everything is set up correctly

## Alternative: Manual Fix Script

If PHP is not in your PATH, you can use the direct PHP script:

```bash
php scripts/fix_photo_references.php
```

## What the Fix Does

1. **Scans storage**: Checks `storage/app/public/photos/{team_id}/{person_id}/` for photo files
2. **Finds original files**: Identifies files without `_medium`, `_large`, or `_small` suffixes
3. **Updates database**: Sets `people.photo` field to match the filename (without extension)
4. **Downloads missing**: Uses Wikimedia Commons API and Google Images to find and download missing photos
5. **Verifies**: Confirms all photos are properly linked

## File Structure Expected

Photos should be stored as:
```
storage/app/public/photos/
  {team_id}/
    {person_id}/
      {person_id}_{index}_{timestamp}_medium.webp
      {person_id}_{index}_{timestamp}_large.webp
      {person_id}_{index}_{timestamp}_small.webp
      {person_id}_{index}_{timestamp}.webp  (original)
```

Database should have:
```sql
people.photo = '{person_id}_{index}_{timestamp}'  (without extension)
```

## Verification

After running the fix, check the UI. All people with photos should now display their images.

To verify in database:
```sql
SELECT id, firstname, surname, photo, team_id 
FROM people 
WHERE team_id = 15 
ORDER BY id 
LIMIT 10;
```

All records should have a `photo` value matching the filename pattern in storage.

