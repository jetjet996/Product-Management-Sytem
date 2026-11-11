# Product Management System

A web-based Product Management System built with Laravel 13, PHP, MySQL, JavaScript, and Bootstrap 5.

## 1. Features

- User authentication using Laravel Sanctum.
- Product management (Create, Read, Update, Delete).
- Product filtering by category and enabled status.
- Product pagination.
- Bulk product deletion.
- Excel export.
- Role-based authorization for administrative operations.

## 2. Technology Stack

| Technology | Purpose |
|---|---|
| Laravel 13 | Backend framework |
| PHP 8.5 | Backend programming language |
| MySQL | Database |
| Laravel Sanctum | API authentication |
| JavaScript | Frontend functionality |
| Bootstrap 5 | UI styling |
| Laravel Excel 4.0 | Excel export |

## 3. Project Setup Instructions

### Requirements

- PHP 8.5 or compatible version
- Composer
- MySQL
- Node.js and npm (if frontend assets need to be built)

### installation steps

1. clone or download from [git hub ](https://github.com/jetjet996/Product-Management-Sytem.git)
2. php artisan migrate interminal 
3. php artisan db:seed interminal (to get user data)
4. php artisan serve 
5. http://127.0.0.1:8000/login
    username = user ,password = 12345678
    username = admin ,password = 12345678


### Authentication 

| Method | Endpoint | Description |
|---|---|---|
| POST | `/api/login` | User login |

### Categories

| Method | Endpoint | Description |
|---|---|---|
| GET | `/api/categories` | Retrieve categories |

### Products

| Method | Endpoint | Description |
|---|---|---|
| GET | `/api/products` | Retrieve products |
| POST | `/api/products` | Create a product |
| GET | `/api/products/{id}` | Retrieve a specific product |
| PUT/PATCH | `/api/products/{id}` | Update a product |
| DELETE | `/api/products/{id}` | Delete a product |
| DELETE | `/api/products/bulk-delete` | Delete multiple products |
| GET | `/api/products/export` | Export products to Excel |

### Product Filtering

The product listing endpoint supports query parameters:

```text
GET /api/products?category_id=1
GET /api/products?enabled=true
GET /api/products?page=2
```

Parameters can also be combined:

```text
GET /api/products?category_id=1&enabled=true&page=2
```
### Create Product Example

**POST** `/api/products`

```json
{
    "category_id": 1,
    "name": "Gaming Keyboard",
    "description": "Mechanical RGB keyboard",
    "price": "159.90",
    "stock": 15,
    "enabled": true
}
```

### Bulk Delete Example

**DELETE** `/api/products/bulk-delete`

```json
{
    "ids": [1, 2, 3]
}
```

## 4. Assumptions and Design Choices

1. **Authentication:** Laravel Sanctum is used to authenticate API requests through Bearer Tokens.

2. **Authorization:** Administrative operations are protected using backend authorization checks. Frontend controls alone are not trusted for access control.

3. **Database:** MySQL is used to store user, category, and product information.

4. **Frontend:** JavaScript Fetch API communicates with the Laravel backend. Bootstrap is used for responsive UI styling.

