# QUICK FIX: Display Photos in UI

## The Problem
Photos exist in `storage/app/public/photos/` but aren't showing because database `photo` fields are missing/incorrect.

## Immediate Fix

Run this command:

```bash
php artisan photos:update-refs --team="BRITISH ROYALS"
```

This will:
1. ✅ Scan all photo files in storage
2. ✅ Update database `photo` fields to match filenames
3. ✅ Make photos display in UI immediately

## Alternative: Direct SQL (if you have DB access)

```sql
-- For MySQL/MariaDB
UPDATE people 
SET photo = CONCAT(id, '_001_demo') 
WHERE team_id = 15 
  AND (photo IS NULL OR photo = '');

-- For person 3 who has multiple photos
UPDATE people 
SET photo = '3_002_demo' 
WHERE id = 3 AND team_id = 15;
```

## Verification

After running, check the UI - all photos should display.

To verify in code:
```php
// In tinker or controller
$person = Person::find(1);
echo $person->photo; // Should output: "1_001_demo"
echo Storage::disk('photos')->url("15/1/{$person->photo}_medium.webp");
```

## Files Location

Photos are stored at:
- Storage: `storage/app/public/photos/{team_id}/{person_id}/`
- URL: `{APP_URL}/storage/photos/{team_id}/{person_id}/{photo}_medium.webp`

Database needs:
- `people.photo` = filename without extension (e.g., `1_001_demo`)

