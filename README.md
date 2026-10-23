# Data Entry Application

<<<<<<< HEAD
A web-based data entry application built with Laravel, Vue.js, and PostgreSQL. The application provides an interface for managing data through a Vue.js frontend connected to a Laravel REST API.
=======
A web-based data management application built with **Laravel, Vue.js, and PostgreSQL**. This project provides a user interface for managing store and product data through a Laravel backend API.
>>>>>>> bdf7af4978ae267f769e831eeb7accb79a8528e3

## Tech Stack

- **Backend:** Laravel, PHP
- **Frontend:** Vue.js, Vite
- **Database:** PostgreSQL
- **API:** REST API

## Prerequisites

<<<<<<< HEAD
Make sure the following tools are installed:

- PHP and Composer
=======
Make sure the following tools are installed on your computer:

- PHP
- Composer
>>>>>>> bdf7af4978ae267f769e831eeb7accb79a8528e3
- Node.js and npm
- PostgreSQL
- Git

<<<<<<< HEAD
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
=======
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

Create the environment configuration file by copying `.env.example` to `.env`.

On Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

Generate the application key:

```bash
php artisan key:generate
```

### 3. Set Up the Database

Make sure PostgreSQL is running.

Create a PostgreSQL database named `data_entry_db` using pgAdmin or the PostgreSQL command line.

Configure the database connection in the backend `.env` file:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=data_entry_db
DB_USERNAME=postgres
DB_PASSWORD="admin"
```

Replace the username and password with your own PostgreSQL credentials.

Run the database migrations:

```bash
php artisan migrate
```

### 4. Start the Backend Server

From the project root directory, run:

```bash
php artisan serve
```

The Laravel backend will be available at:

`http://127.0.0.1:8000`

Keep this terminal running.

### 5. Set Up the Frontend

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

- Keep backend and frontend dependencies installed before running the application.
- Do not commit `.env` files or database credentials to the repository.
- The database schema is created using Laravel migrations.
- Ensure the backend API URL and frontend configuration match your local environment.
>>>>>>> bdf7af4978ae267f769e831eeb7accb79a8528e3

```bash
php artisan key:generate
```

<<<<<<< HEAD
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
=======
This project was developed for learning and demonstration purposes.
>>>>>>> bdf7af4978ae267f769e831eeb7accb79a8528e3
