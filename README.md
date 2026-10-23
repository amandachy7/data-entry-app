# Data Entry Application

A web-based data entry application built with **Laravel, Vue.js, and PostgreSQL**. The application provides an interface for managing store and product data through a Laravel REST API.

## Tech Stack

- **Backend:** Laravel, PHP
- **Frontend:** Vue.js, Vite
- **Database:** PostgreSQL
- **API:** REST API

## Prerequisites

Make sure the following tools are installed on your computer:

- PHP
- Composer
- Node.js and npm
- PostgreSQL
- Git

## Getting Started

### 1. Clone the Repository

```bash
git clone https://github.com/amandachy7/data-entry-app.git
cd data-entry-app
```

### 2. Set Up the Backend

Install the PHP dependencies:

```bash
composer install
```

Create the environment configuration file.

On Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

Generate the Laravel application key:

```bash
php artisan key:generate
```

### 3. Set Up the Database

Make sure PostgreSQL is running, then create a database named `data_entry_db`.

Configure the database connection in the backend `.env` file:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=data_entry_db
DB_USERNAME=postgres
DB_PASSWORD="admin"
```

Run the database migrations to create the required tables:

```bash
php artisan migrate
```

### 4. Seed the Initial Data

Populate the database with the initial tier, store, product, and entry records:

```bash
php artisan db:seed
```

The seeders are located in `database/seeders/`:

- `TierSeeder.php`
- `StoreSeeder.php`
- `ProductSeeder.php`
- `EntrySeeder.php`

Run the seeders after migrations have completed successfully. The seeders use `updateOrInsert` for tier, store, and product records to reduce duplicate records when run repeatedly.

### 5. Start the Backend Server

From the project root directory, run:

```bash
php artisan serve
```

The Laravel backend will be available at:

`http://127.0.0.1:8000`

Keep this terminal running.

### 6. Set Up the Frontend

Open a new terminal and navigate to the frontend directory:

```bash
cd data-entry-frontend
```

Install the frontend dependencies:

```bash
npm install
```

Start the Vue development server:

```bash
npm run dev
```

Open the local URL displayed in the terminal, usually:

`http://localhost:5173`

Keep this terminal running as well.

## Running the Application

To use the application:

1. Start PostgreSQL.
2. Start the Laravel backend using `php artisan serve`.
3. Start the Vue frontend using `npm run dev`.
4. Open the frontend URL in your browser.

Make sure the frontend API configuration points to the correct Laravel backend URL.

## Notes

- Run PostgreSQL before starting the backend.
- Keep the backend and frontend running in separate terminals.
- Never commit `.env` files or database credentials to GitHub.
- Database tables are created using Laravel migrations.
- Initial application data is provided by Laravel seeders.
- Update the frontend API base URL if your local backend uses a different address.

This project was developed for learning and demonstration purposes.
