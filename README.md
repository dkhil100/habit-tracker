# 🎯 Habit Tracker Web Application

A full-stack, gamified habit-tracking web application built with **PHP 8** and **Symfony**.

Habit Tracker helps users build consistency, monitor daily progress, and stay motivated through personalized dashboards, habit scheduling, streak tracking, and progress insights.

## 🚀 Features

* **User Authentication** — Secure registration, login, and profile management using Symfony Security.
* **Habit Management** — Create, edit, and track daily or recurring habits.
* **Progress Dashboard** — View current streaks, completion history, and performance metrics.
* **Habit Scheduling** — Define custom schedules for recurring habits.
* **Modern Asset Pipeline** — Uses Symfony AssetMapper and `importmap.php` for frontend assets without requiring Node.js.
* **Relational Data Model** — Uses Doctrine ORM to manage users, habits, schedules, and habit logs.

## 🛠️ Tech Stack

| Category         | Technology                    |
| ---------------- | ----------------------------- |
| Backend          | PHP 8, Symfony                |
| ORM              | Doctrine ORM                  |
| Database         | MySQL / SQLite                |
| Frontend         | Twig, HTML5, CSS3, JavaScript |
| Asset Management | Symfony AssetMapper           |
| Architecture     | MVC                           |

## ⚙️ Requirements

Before installing the application, make sure you have:

* **PHP** `8.1` or higher
* **Composer**
* **Symfony CLI** — recommended for local development
* **MySQL** or **SQLite**

## 📦 Installation

### 1. Clone the repository

```bash
git clone https://github.com/dkhil100/habit-tracker.git
cd habit-tracker
```

### 2. Install dependencies

```bash
composer install
```

### 3. Configure the environment

Create a local environment file:

```bash
cp .env .env.local
```

Then update the `DATABASE_URL` value in `.env.local`.

For MySQL, for example:

```env
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/habit_tracker_db?serverVersion=8.0"
```

> **Note:** Adjust the database username, password, database name, and MySQL version to match your local environment.

### 4. Create and migrate the database

Create the database:

```bash
php bin/console doctrine:database:create
```

Run the database migrations:

```bash
php bin/console doctrine:migrations:migrate
```

### 5. Start the development server

Using Symfony CLI:

```bash
symfony server:start
```

Alternatively, you can use PHP's built-in development server:

```bash
php -S 127.0.0.1:8000 -t public
```

### 6. Open the application

Once the server is running, open:

**http://127.0.0.1:8000**

## 📁 Project Structure

The project follows Symfony's standard MVC structure:

```text
habit-tracker/
├── assets/          # Frontend assets
├── config/          # Symfony configuration
├── migrations/      # Doctrine database migrations
├── public/          # Public web root
├── src/             # Application source code
│   ├── Controller/  # Application controllers
│   ├── Entity/      # Doctrine entities
│   └── ...
├── templates/       # Twig templates
├── tests/            # Automated tests
├── .env             # Environment configuration
├── .env.local       # Local environment configuration
├── composer.json    # PHP dependencies
└── importmap.php    # AssetMapper configuration
```

## 🗄️ Database

The application uses **Doctrine ORM** for database management.

Supported databases include:

* MySQL
* SQLite

After configuring `DATABASE_URL`, run the migrations to create the required database schema.

## 🔐 Environment Configuration

Local configuration should be stored in `.env.local`.

Do **not** commit sensitive credentials such as database passwords, API keys, or other secrets to the repository.

## 🧪 Testing

If tests are configured for the project, run them with:

```bash
php bin/phpunit
```

## 🤝 Contributing

Contributions are welcome.

1. Fork the repository.

2. Create a new branch:

   ```bash
   git checkout -b feature/my-feature
   ```

3. Make your changes.

4. Commit your changes:

   ```bash
   git commit -m "Add my feature"
   ```

5. Push the branch:

   ```bash
   git push origin feature/my-feature
   ```

6. Open a pull request.


Built with **PHP**, **Symfony**, **Doctrine**, and **Twig**. 🎯
