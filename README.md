## Social Backend API

Building API for Social.

## Requirements

- php ^8.4
- laravel 12.0
- composer
- mysql

## Setup Projects

1. **Clone the repository**

    ```bash
    git clone <repository-url>
    cd <repository-name>
    ```

2. **Install PHP dependencies**

    ```bash
    composer install
    ```

3. **Environment setup**

    ```bash
    cp .env.example .env
    php artisan key:generate
    php artisan storage:link
    ```

4. **Configure database** in `.env`

    ```
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=social_api
    DB_USERNAME=root
    DB_PASSWORD=
    ```

5. **Run migrations and seeders**

    ```bash
    php artisan migrate --seed
    ```

6. **Start development server**

    ```bash
    php artisan serve
    ```

## Features

- **Authentication** - Manage users
- **Posts** - Manage posts
- **Comments** - Manage comments
- **Reactions** - Manage reactions

## API Docs

Access the api documentation at `/docs/api` Or import Social Api.postman_collection.json file
