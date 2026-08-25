# Deploying to Production

## Server

- Host: `52.45.249.242` (public DNS: `logbook.ecsaconm.org`)
- SSH user: `ubuntu`
- SSH key: `/home/davish/Downloads/keys/logbook/e_logbook.pem` (chmod 600 before use)
- App path: `/var/www/html/logbook`
- Web server: Apache (`logbook.conf` / `logbook-le-ssl.conf` in `/etc/apache2/sites-enabled/`), document root `public/`, SSL via Let's Encrypt
- PHP: 8.3 (CLI matches web)
- DB: MySQL, local to the instance (`DB_HOST=127.0.0.1`), database `logbook`, credentials in the server's `.env` (not in git)
- phpMyAdmin is aliased at `/phpmyadmin` on the same vhost

```
ssh -i /home/davish/Downloads/keys/logbook/e_logbook.pem ubuntu@52.45.249.242
```

## Known quirk: DB schema vs. migrations are out of sync

The prod `logbook` database was originally provisioned from a SQL dump, not
by running this repo's migrations. As a result:

- Tables like `training_programmes`, `rotations`, `objectives`, `operations`
  already exist, but the `migrations` table only has the original 8 records
  (batch 1) — `migrate:status` will show those four as "Pending" even though
  the tables are there and populated.
- **Never blindly run `php artisan migrate` on prod without checking
  `migrate:status` first.** If a migration is "Pending" for a table that
  already exists with data, running it will fail (`Base table already
  exists`) or, worse, could be misread as safe to re-run.
- If you add a *new* migration that only alters/extends one of these tables
  (like the `2026_08_25_000000_fix_training_programmes_auto_increment`
  migration), it's safe to run — just make sure the table-creation
  migrations it depends on are already marked as run in the `migrations`
  table (insert records manually with `batch` set higher than the existing
  max if needed — see git history for an example of this reconciliation).

## Standard deploy steps

1. **Check for uncommitted drift on the server first.** The server has
   previously accumulated uncommitted local edits that happened to match
   commits already pushed to `origin/main` — but don't assume that's always
   the case. Before resetting anything:
   ```
   cd /var/www/html/logbook
   git fetch origin
   git status --short
   # for any modified tracked file:
   git diff origin/main -- <file>
   ```
   If a file differs from `origin/main` in a way that isn't already covered
   by a commit you're about to deploy, stop and reconcile it manually
   (don't discard it) — it may be a live hotfix that was never committed.
   Untracked files (e.g. uploaded images in `public/images/`) are user
   content and are never touched by `git pull`/`reset`.

2. **Sync code:**
   ```
   git reset --hard origin/main   # only after confirming step 1
   ```

3. **Dependencies (if composer.lock/package.json changed):**
   ```
   composer install --no-dev --optimize-autoloader
   npm ci && npm run build   # only if frontend assets changed
   ```

4. **Database:**
   ```
   php artisan migrate:status   # inspect before running anything
   php artisan migrate --force  # only for migrations that are genuinely safe (see quirk above)
   ```

5. **Run any one-off data import commands** relevant to the release, e.g.:
   ```
   php artisan import:training-programmes --dry-run   # verify first
   php artisan import:training-programmes
   ```

6. **Clear/rebuild caches:**
   ```
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   php artisan route:clear
   ```

7. **Fix permissions if git reset touched ownership:**
   ```
   sudo chown -R ubuntu:www-data storage bootstrap/cache
   sudo chmod -R g+w storage bootstrap/cache
   ```

8. **Verify:**
   ```
   curl -s -o /dev/null -w "%{http_code}\n" https://logbook.ecsaconm.org/
   ```
   Expect `200`. Also spot-check a page that hits the DB.

## 2026-08-25 deploy log (for reference)

- Deployed commit `6047f94` ("Add new training programmes, rotations and
  objectives"), which added 5 new training programmes (Oncology Nursing,
  Advanced Neonatal Nursing, Nursing in Leadership and Management,
  Perioperative Care, Nurse Anaesthesia) with their full rotation/objective
  data, plus an empty Mental Health Care programme placeholder.
- Prod was 2 commits behind `origin/main` even before this release, with
  uncommitted local edits that turned out to be identical to what was
  already in those 2 commits — reconciled with `git reset --hard
  origin/main` after confirming zero diff against `origin/main` file by
  file.
- Discovered and fixed a real bug: `training_programmes.id` was missing
  `AUTO_INCREMENT` on both local and prod databases (an artifact of how the
  DB was originally dumped/restored), which blocked *any* new programme
  from being created — including through the existing "Add Programme" form,
  independent of this import. Fixed via migration
  `2026_08_25_000000_fix_training_programmes_auto_increment`.
- Removed a duplicate/broken `training_programmes` migration
  (`2025_07_24_115230_...`) that would have failed if `migrate` were ever
  run as-is.
- Ran `php artisan import:training-programmes` (idempotent, safe to re-run
  — matches on unique `objective_code`).
