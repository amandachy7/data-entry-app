# Data Entry Application

A full-stack web application built with **Laravel, Vue.js, and PostgreSQL**. It provides a data input form and a data table:

- **Data Input Form** with fields Name, Store, and Product.
- The **Product dropdown is dynamic**: it only shows products that match the tier of the selected Store, and it stays disabled until a Store is chosen.
- **Data Table** that lists all submitted entries (Name, Store, Product).

## Tech Stack

- **Backend:** PHP, Laravel (REST API)
- **Frontend:** Vue.js, Vite
- **Database:** PostgreSQL

## Database Design

The reference dataset is normalized into four tables:

```
tiers
  id, name

stores
  id, name, tier_id  ->  tiers.id

products
  id, name, tier_id  ->  tiers.id

entries
  id, name, store_id -> stores.id, product_id -> products.id, timestamps
```

A store belongs to one tier, and a product belongs to one tier. Therefore, the products available to a store are the products that share the store's tier.

## API Endpoints

Base URL: `http://127.0.0.1:8000/api`

| Method | Endpoint                    | Description                                          |
|--------|-----------------------------|------------------------------------------------------|
| GET    | `/stores`                   | List all stores                                      |
| GET    | `/stores/{id}/products`     | List products that match the tier of the given store |
| GET    | `/entries`                  | List all submitted entries                           |
| POST   | `/entries`                  | Save a new entry (`name`, `store_id`, `product_id`)  |

The backend also validates that the selected product matches the tier of the selected store.

## Prerequisites

Make sure the following tools are installed:

- PHP 8.2+ and Composer
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

Create the environment file.

macOS / Linux:

```bash
cp .env.example .env
```

Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

Generate the application key:

```bash
php artisan key:generate
```

### 3. Set Up the Database

Make sure PostgreSQL is running, then create a database named `data_entry_db`:

```bash
psql -U postgres -c "CREATE DATABASE data_entry_db;"
```

Open the `.env` file and set the database connection. Replace the username and password with your own PostgreSQL credentials:

```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=data_entry_db
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

Run the migrations and seed the initial data (tiers, stores, products, and sample entries):

```bash
php artisan migrate --seed
```

The seeders are located in `database/seeders/` (`TierSeeder`, `StoreSeeder`, `ProductSeeder`, `EntrySeeder`). They use `updateOrInsert`, so running them again will not create duplicate tier, store, or product records.

### 4. Start the Backend Server

```bash
php artisan serve
```

The API will be available at `http://127.0.0.1:8000`. Keep this terminal running.

### 5. Set Up the Frontend

Open a **new terminal**:

```bash
cd data-entry-frontend
npm install
```

Create the frontend environment file and make sure it points to the backend:

macOS / Linux:

```bash
cp .env.example .env
```

Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

```
VITE_API_URL=http://127.0.0.1:8000/api
```

Start the Vue development server:

```bash
npm run dev
```

Open the URL shown in the terminal, usually `http://localhost:5173`.

> **Note:** The frontend (port 5173) and the backend (port 8000) run on different origins. CORS is configured in the Laravel backend to allow `http://localhost:5173`. If you run the frontend on a different port, update the allowed origins in `config/cors.php`.

## Running the Application

1. Start PostgreSQL.
2. Start the backend: `php artisan serve` (in the project root).
3. Start the frontend: `npm run dev` (in `data-entry-frontend`).
4. Open `http://localhost:5173` in your browser.

## Quick API Check

To verify that the backend works, open this URL in your browser:

```
http://127.0.0.1:8000/api/stores/1/products
```

It should return a JSON list of products that match the tier of store 1.

> The API is served by Laravel on port **8000**. Port 5173 is only the Vue frontend.

## Troubleshooting

- **Cannot connect to the database:** check that PostgreSQL is running and that the `.env` credentials are correct.
- **Product dropdown stays empty:** check that the backend is running and that `VITE_API_URL` is correct.
- **CORS error in the browser console:** check the allowed origins in `config/cors.php`.
- **Route not found:** run `php artisan route:list` to confirm that the `/api` routes are registered.

## Notes

- Never commit `.env` files or real credentials to the repository.
- Database tables are created with Laravel migrations, and initial data is provided by Laravel seeders.
