# NatureCAN

**NatureCAN: An Automated NLP and LLM-Driven Framework for Systematic Compilation of Indian Traditional Medicinal Plants Studied in Cancer**

A curated, searchable database of published evidence linking Indian traditional medicinal plants to cancer research — plant names, study titles, PubMed IDs, model systems, experimental techniques, reported toxicity, cancer types and study types.

The website is a PHP (Apache) application backed by MariaDB, packaged with Docker Compose so that the full stack — web app, database, and phpMyAdmin — comes up with a single command.

---

## Contents

- [Stack](#stack)
- [Repository layout](#repository-layout)
- [Prerequisites](#prerequisites)
- [Launching the website locally](#launching-the-website-locally)
- [Accessing the database locally](#accessing-the-database-locally)
- [Data model](#data-model)
- [Common tasks](#common-tasks)
- [Troubleshooting](#troubleshooting)
- [License](#license)

---

## Stack

| Component | Version / image | Notes |
|---|---|---|
| Web app | `php:8.2-apache` (built from `Dockerfile`) | `pdo`, `pdo_mysql`, `mysqli`, `zip`; `mod_rewrite`, `mod_headers` |
| Database | `mariadb:10.11` (LTS) | InnoDB tuned for large tables via `db/my.cnf` |
| DB admin UI | `phpmyadmin:latest` | Reachable only over the internal Docker network |
| Frontend | Bootstrap + jQuery + Font Awesome (CDN) | Versions and SRI hashes pinned in `app/src/constants.php` |

---

## Repository layout

```
NatureCAN/
├── docker-compose.yml          # Production stack: app + db + phpMyAdmin
├── docker-compose.dev.yml      # Dev overrides: live-mounted source, DB port exposed
├── Dockerfile                  # Multi-stage build for the PHP/Apache app
├── .env.example                # Template for the environment file (copy to .env)
│
├── app/
│   ├── php.ini                 # PHP settings (memory, sessions, OPcache, security)
│   └── src/                    # Document root served at /var/www/html
│       ├── index.php           # Redirects to homepage.php
│       ├── homepage.php        # Landing page
│       ├── nc_trial_table.php  # Main browsable/searchable data table
│       ├── search_4.php        # Search interface
│       ├── statistics.php      # Summary statistics
│       ├── visualization.php   # Charts and figures
│       ├── plants_data.php     # Per-plant listings
│       ├── cancer_types.php    # Cancer-type listings
│       ├── about.php           # Project / credits page
│       ├── get_plant_stats.php # JSON endpoint used by the UI
│       ├── download_csv.php    # CSV export endpoint
│       ├── config.php          # PDO connection (reads DB_* env vars)
│       ├── db_config_test.php  # MySQLi connection + CSRF token bootstrap (legacy)
│       ├── constants.php       # Table name, CDN versions, SRI hashes
│       ├── header.php / header_navbar.php / footer.php
│       ├── style_nc_table.css
│       ├── images/             # Figures and institutional logos
│       └── tables/             # Static CSV/XLSX reference tables
│
├── config/
│   ├── apache-config.conf      # VirtualHost, security headers, request limits
│   └── security.conf           # Additional Apache hardening
│
├── db/
│   └── my.cnf                  # MariaDB/InnoDB tuning
│
├── sql/                        # Auto-loaded on first database start, in name order
│   ├── 00_schema.sql           # Baseline schema
│   ├── 01_merged_output_d_20260122.sql   # Main dataset (~13,250 rows)
│   └── 02_add_indexes.sql      # Indexes on the main table (idempotent)
│
└── DATASET/                    # Source dataset + derivation notes
    ├── merged_output_d_20260122.sql
    ├── summary.txt             # Per-PMID counts
    └── commands.txt            # Command used to derive summary.txt
```

---

## Prerequisites

- **Docker Desktop** (Windows/macOS) or **Docker Engine + Compose v2** (Linux)
- ~4 GB free RAM (the app and DB containers are each capped at 2 GB) and ~2 GB disk
- Free local ports: **8017** (website), **8018** (phpMyAdmin), and **3311** (MariaDB, dev mode only)

Verify Docker is available:

```bash
docker --version
docker compose version
```

---

## Launching the website locally

### 1. Clone the repository

```bash
git clone https://github.com/<your-org>/NatureCAN.git
cd NatureCAN
```

### 2. Create the environment file

The stack reads all database credentials from a `.env` file in the repository root. It is **not** committed — create it from the template:

```bash
# Linux / macOS
cp .env.example .env
```

```powershell
# Windows PowerShell
Copy-Item .env.example .env
```

Then edit `.env` and replace the placeholder passwords:

```ini
MARIADB_DATABASE=naturecan
MARIADB_USER=naturecan
MARIADB_PASSWORD=<choose a strong password>
MARIADB_ROOT_PASSWORD=<choose a different strong password>

PHP_MEMORY_LIMIT=1024M
```

> `.env` is listed in `.gitignore`. Never commit it. For a public deployment use long, random passwords (32+ characters).

### 3. Start the stack

**Development** (recommended locally — mounts `app/src` into the container so PHP edits appear on refresh with no rebuild, and exposes the database on port 3311):

```bash
docker compose -f docker-compose.yml -f docker-compose.dev.yml up -d --build
```

**Production-style** (source code baked into the image, database port not published):

```bash
docker compose up -d --build
```

The first start takes a few minutes: Docker builds the PHP image, then MariaDB imports everything in `sql/` — including the ~7.7 MB dataset — before it reports healthy. The `app` container waits for the database's healthcheck, so this ordering is automatic.

### 4. Open the website

| Service | URL |
|---|---|
| NatureCAN website | <http://localhost:8017> |
| phpMyAdmin | <http://localhost:8018> |

`http://localhost:8017/` redirects to `homepage.php`.

### 5. Watch progress / verify

```bash
docker compose ps                  # container state and health
docker compose logs -f db          # follow the SQL import on first start
docker compose logs -f app         # Apache/PHP errors
```

### 6. Stop the stack

```bash
docker compose down                # stop containers, keep the database volume
docker compose down -v             # ALSO delete the database volume (full reset)
```

> Data lives in the named volume `db_data`. `docker compose down -v` erases it, and the next start re-imports `sql/` from scratch.

---

## Accessing the database locally

The application never hardcodes credentials; `app/src/config.php` (PDO) and `app/src/db_config_test.php` (MySQLi) read `DB_HOST`, `DB_NAME`, `DB_USER` and `DB_PASS`, which `docker-compose.yml` derives from your `.env`. Inside the Docker network the database host is simply **`db`**.

### Option A — phpMyAdmin (easiest)

1. Open <http://localhost:8018>
2. Log in with the credentials from `.env`:
   - **Server:** pre-set to `db` (arbitrary hosts are disabled)
   - **Username:** `naturecan` (`MARIADB_USER`) — or `root` for full access
   - **Password:** the matching `MARIADB_PASSWORD` / `MARIADB_ROOT_PASSWORD`
3. Select the **`naturecan`** database.

Upload limit is 512 MB, which is ample for re-importing the dataset dump.

### Option B — MySQL shell inside the container

Works in both dev and production modes, no exposed port needed:

```bash
# Application user
docker compose exec db mariadb -u naturecan -p naturecan

# Root
docker compose exec db mariadb -u root -p
```

Enter the password from `.env` when prompted. Quick sanity check:

```sql
SHOW TABLES;
SELECT COUNT(*) FROM merged_output_d_20260122;
SELECT plant_name, COUNT(*) AS studies
FROM merged_output_d_20260122
GROUP BY plant_name
ORDER BY studies DESC
LIMIT 10;
```

### Option C — External client (DBeaver, HeidiSQL, MySQL Workbench, `mysql` CLI)

Only available in **development** mode, which publishes the database port:

| Setting | Value |
|---|---|
| Host | `127.0.0.1` |
| Port | **3311** |
| Database | `naturecan` |
| User | `naturecan` (or `root`) |
| Password | from `.env` |

```bash
mysql -h 127.0.0.1 -P 3311 -u naturecan -p naturecan
```

If you started the stack with plain `docker compose up`, port 3311 is **not** published — restart with the dev override, or use Option A/B.

### Backup and restore

```bash
# Dump the whole database to a file on the host
docker compose exec db mariadb-dump -u root -p naturecan > naturecan_backup.sql

# Restore a dump
docker compose exec -T db mariadb -u root -p naturecan < naturecan_backup.sql
```

---

## Data model

The main table is **`merged_output_d_20260122`** (~13,250 rows), one row per plant–publication pairing:

| Column | Type | Description |
|---|---|---|
| `sr_no` | `int` | Serial number |
| `plant_name` | `varchar(29)` | Botanical name of the medicinal plant |
| `title` | `varchar(456)` | Title of the publication |
| `pmid` | `int` | PubMed identifier |
| `model_system` | `varchar(1683)` | Cell lines, animal models, tissue used |
| `experimental_techniques` | `varchar(2895)` | Assays and methods reported |
| `toxicity_and_side_effects` | `varchar(3642)` | Reported toxicity (`-` when none stated) |
| `cancer_types` | `varchar(283)` | Comma-separated cancer types |
| `study_types` | `varchar(47)` | `In Vitro`, `In Vivo`, `In Silico`, `Review`, … |

`sql/02_add_indexes.sql` adds single-column indexes on `plant_name`, `title`, `cancer_types`, `study_types`, `model_system`, `experimental_techniques`, `toxicity_and_side_effects` and `pmid`. It is idempotent and skips silently if the table has not been imported yet.

`sql/00_schema.sql` also creates a small generic `records` table used by the pagination demo scaffolding.

The table name is referenced from PHP through a single constant — `NATURECAN_TABLE` in `app/src/constants.php`. Update it there when importing a newer dataset.

---

## Common tasks

**Edit a PHP page and see the change**
In dev mode `app/src` is bind-mounted to `/var/www/html`, so save the file and refresh the browser. No rebuild required.

**Rebuild after changing `Dockerfile`, `php.ini`, or the Apache config**

```bash
docker compose -f docker-compose.yml -f docker-compose.dev.yml up -d --build
```

**Import a newer dataset**

1. Place the new dump in `sql/` with a name that sorts after `00_schema.sql`.
2. Update `NATURECAN_TABLE` in `app/src/constants.php` and the table name in `sql/02_add_indexes.sql`.
3. Recreate the database volume so the init scripts run again:

```bash
docker compose down -v
docker compose -f docker-compose.yml -f docker-compose.dev.yml up -d --build
```

Alternatively import into the running database without wiping it:

```bash
docker compose exec -T db mariadb -u root -p naturecan < sql/<new_dump>.sql
```

> Scripts in `sql/` run **only** when the database volume is empty. Adding a file to `sql/` has no effect on an already-initialised volume.

**Open a shell in a container**

```bash
docker compose exec app bash
docker compose exec db bash
```

---

## Troubleshooting

| Symptom | Cause / fix |
|---|---|
| `Database configuration error. Please contact administrator.` | `.env` is missing or a `MARIADB_*` value is empty. Create/fix `.env`, then `docker compose up -d` again. |
| `Database connection error. Please contact administrator.` | The `db` container is not healthy yet. Check `docker compose logs db`; on first start the dataset import takes a while. |
| `Bind for 0.0.0.0:8017 failed: port is already allocated` | Another process holds the port. Free it, or change the host side of the mapping in `docker-compose.yml` (e.g. `"8117:80"`). |
| Cannot connect on port 3311 | You started the stack without `docker-compose.dev.yml`. Only the dev override publishes the DB port. |
| Website loads but tables are empty | The dataset never imported. Check `docker compose logs db` for import errors, or reset with `docker compose down -v` and start again. |
| Data changes did not appear after editing `sql/` | Init scripts only run on an empty volume. Use `docker compose down -v`, or import manually with `docker compose exec -T db mariadb …`. |
| PHP errors are not visible | `display_errors` is `Off` by design. Read them with `docker compose logs app`, or inside the container at `/var/log/apache2/php_errors.log`. |
| Slow queries on the main table | Confirm the indexes were created: `SHOW INDEX FROM merged_output_d_20260122;` If missing, run `sql/02_add_indexes.sql` manually. |

---

## Notes on security

- `.env`, keys, certificates and logs are excluded via `.gitignore` — keep it that way.
- `app/php.ini` disables `exec`/`system`-family functions, `allow_url_fopen`/`allow_url_include`, and error display; sessions are HTTP-only, `SameSite=Lax`, with strict mode.
- Security headers and a Content-Security-Policy are set in `config/apache-config.conf` and `app/src/.htaccess`; `config/security.conf` hides the server signature, disables `TRACE`, and blocks direct access to dotfiles and to `config.php` / `db_config*.php` / `constants.php`.
- `session.cookie_secure` is `Off` so the app works over plain HTTP locally. **Turn it on when serving over HTTPS.**
- phpMyAdmin is convenient for local work but should not be exposed publicly; drop the `pma` service (or firewall port 8018) in a real deployment.

---

## License

See [LICENSE](LICENSE).
