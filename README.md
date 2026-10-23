# Data Entry Application

A web-based data entry application built with Laravel, Vue.js, and PostgreSQL. The application provides an interface for managing data through a Vue.js frontend connected to a Laravel REST API.

## Tech Stack

- **Backend:** Laravel, PHP
- **Frontend:** Vue.js, Vite
- **Database:** PostgreSQL
- **API:** REST API

## Prerequisites

Make sure the following tools are installed:

- PHP and Composer
- Node.js and npm
- PostgreSQL
- Git

## 1. Clone the Repository

```bash
git clone https://github.com/YOUR_USERNAME/YOUR_REPOSITORY.git
cd data-entry-app
```

Replace `YOUR_USERNAME` and `YOUR_REPOSITORY` with your GitHub username and repository name.

## 2. Backend Setup

Install the PHP dependencies:

```bash
composer install
```

Create the environment configuration file:

```bash
cp .env.example .env
```

Generate the Laravel application key:

```bash
php artisan key:generate
```

## 3. Database Setup

Create a PostgreSQL database named `data_entry_db`.

Configure the database connection in the backend `.env` file:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=data_entry_db
DB_USERNAME=your_postgres_username
DB_PASSWORD=your_postgres_password
```

Replace the username and password with your local PostgreSQL credentials.

Run the database migrations:

```bash
php artisan migrate
```

If the project includes database seeders, run them when needed:

```bash
php artisan db:seed
```

## 4. Start the Backend Server

Start the Laravel development server:

```bash
php artisan serve
```

The backend will be available at:

`http://127.0.0.1:8000`

## 5. Frontend Setup

Open a new terminal and navigate to the frontend directory:

```bash
cd data-entry-frontend
```

Install the frontend dependencies:

```bash
npm install
```

Start the Vue.js development server:

```bash
npm run dev
```

Open the local URL displayed in the terminal, typically:

`http://localhost:5173`

## 6. API Configuration

Ensure the frontend API base URL points to the running Laravel backend.

For example, if the frontend uses an environment variable, configure the appropriate `.env` file in the frontend directory:

```env
VITE_API_BASE_URL=http://127.0.0.1:8000
```

Use the environment variable name and API configuration expected by the actual project code.

## Notes

- Run PostgreSQL before starting the backend.
- Keep the backend and frontend running in separate terminals.
- Never commit `.env` files or database credentials to GitHub.
- Database migrations create the required tables. Seeders are optional and should only be used if provided by the project.
