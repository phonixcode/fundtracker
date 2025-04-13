# Fund Management CLI

A Laravel-based command-line tool for managing investment funds and their returns. Easily track, simulate, and backdate fund performance using the power of Laravel Artisan commands.

## Project Setup

### Requirements

- PHP >= 8.1
- Composer
- Laravel >= 10
- MySQL or other supported database

### Installation

1. **Clone the Repository**

```bash
git clone https://github.com/phonixcode/fundtracker.git
cd fundtracker
```

2. **Install Dependencies**

```bash
composer install
```

3. **Copy and Configure Environment**

```bash
cp .env.example .env
```
Open the `.env` file and set your database credentials:
   ```bash
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

4. **Generate App Key**
```bash
php artisan key:generate
```

5. **Run Fresh Migrations and Seeders**
```bash
php artisan migrate:fresh --seed
```
This will create the database tables and seed two example funds:

- Growth Fund (₦100,000)

- Equity Fund (₦50,000)

# Fund Management CLI

A Laravel-based CLI tool for managing investment funds and returns.

---

## Available Commands

> All commands begin with `php artisan`.

---

### Fund Management

| Command         | Description                              |
|----------------|------------------------------------------|
| `fund:add`      | Add a new fund                          |
| `fund:list-values`     | List all funds and their current values |

```bash
php artisan fund:add
php artisan fund:list-values
```
### Fund Returns

| Command         | Description                              |
|----------------|------------------------------------------|
| `fund:return`      | Apply a new return to a fund                         |
| `fund:returns-list`     | List all return entries for a fund |
| `fund:return:revert`     | Revert a return using its ID |
| `fund:value-at-date`     | Calculate value of a fund at a specific date |

```bash
php artisan fund:return
php artisan fund:returns-list
php artisan fund:return:revert
php artisan fund:value-at-date
```

### Return Simulation

| Command        | Description                              |
|----------------|------------------------------------------|
| `fund:simulate-returns`  | Simulate returns based on frequency (monthly, quarterly, yearly)             |
| `fund:simulate-return --preview`  | Preview only             |
| `fund:simulate-return --date=2025-01-31`  | Backdate a return            |
| `fund:simulate-return --preview --date=2025-03-31`  | Combine both             |

```bash
php artisan fund:simulate-returns
php artisan fund:simulate-return --preview
php artisan fund:simulate-return --date=2025-01-31
php artisan fund:simulate-return --preview --date=2025-03-31
```


## Tips
- All funds are identified by their unique IDs (F-XXXX)
- Compound and simple returns are supported
- Reverted returns are excluded from calculations

