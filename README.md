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
    git clone https://github.com/hlaingwinphyoe/social_backend_api.git
    cd social_api
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

## Features Test

I only test for comment and reaction feature during this time.

- Post Comment Test
- Post Reaction Test

## API Documentation

All routes (except `/api/register` and `/api/login`) require the **Authorization** header with a Bearer Token:
`Authorization: Bearer <access_token>`

---

## Authentication

| Method | Endpoint        | Description                                    | Request Body                                               |
| :----- | :-------------- | :--------------------------------------------- | :--------------------------------------------------------- |
| `POST` | `/api/register` | Create a new user account                      | `{ "name", "email", "password", "password_confirmation" }` |
| `POST` | `/api/login`    | Authenticate an existing user                  | `{ "email", "password" }`                                  |
| `POST` | `/api/logout`   | Revoke current access token                    | _(none)_ Required: `Bearer <token>`                        |
| `GET`  | `/api/profile`  | Retrieve authenticated user profile with stats | _(none)_ Required: `Bearer <token>`                        |

---

## Posts

| Method      | Endpoint          | Description                                | Query / Request Body Parameters                                             |
| :---------- | :---------------- | :----------------------------------------- | :-------------------------------------------------------------------------- |
| `GET`       | `/api/posts`      | List posts table with pagination           | **Query Params**: `q` (search title/content string), `limit` (max returns)  |
| `POST`      | `/api/posts`      | Create a new Feed post                     | **Body Form-Data**: `title`, `content`, `image` (File: jpg/png/mp4, <=10MB) |
| `PUT/PATCH` | `/api/posts/{id}` | Update existing Feed details for Author    | **Body Form-Data**: `title`, `content`, `image` (File)                      |
| `DELETE`    | `/api/posts/{id}` | Delete post record & attached file uploads | _(none)_ Required: Author permissions                                       |
| `GET`       | `/api/my-posts`   | List strictly feed content created by you  | _(none)_                                                                    |

---

## Comments

| Method | Endpoint                       | Description                                    | Query / Request Body Parameters                          |
| :----- | :----------------------------- | :--------------------------------------------- | :------------------------------------------------------- |
| `GET`  | `/api/posts/{postId}/comments` | List comments attached on a specific feed Item | **Query Params**: `limit` (pagination page size returns) |
| `POST` | `/api/posts/{postId}/comments` | Create feedback comment strings under post     | **Body**: `{ "content" }` (max: 500 chars)               |

---

## Reactions

| Method | Endpoint                       | Description                | Request Body                                      |
| :----- | :----------------------------- | :------------------------- | :------------------------------------------------ |
| `POST` | `/api/posts/{postId}/reaction` | Toggle Add/Remove Reaction | `{ "type" }` (Supported values: `like`, `unlike`) |

---

## Sample Request Structure example

**POST `/api/posts/{postId}/reaction`**

```json
{
    "type": "like"
}
```

**Response 200 OK Sample**

```json
{
    "message": "Reaction updated successfully",
    "status": "added",
    "count": 1,
    "reaction_type": "like"
}
```

> [!TIP]
> You can also import `Social Api.postman_collection.json` file in postman or https://social-api.futureadvicebycharm.com/docs/api.
