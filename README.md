# Takeaway App Server

This is the backend service for a takeaway application, built with the Laravel framework. The project adopts a **feature-based modular structure** for better scalability and maintainability. It provides user authentication, product and category management, order processing, media handling, address management, and system settings. The service supports RESTful APIs, captcha generation, token-based authentication, role-based access control, and more.

---

## Project Structure Highlights

- `app/Features/`: All business modules (Product, Order, Customer, Media, Setting, etc.) are organized independently, each containing Controllers, Models, Views, Providers, and routes.

---

## Features Overview

### 1. User Authentication & Authorization
- Email registration, login, and captcha verification
- Token-based authentication (Laravel Sanctum)
- Role-based access control (admin, owner, customer)
- User profile management

### 2. Product & Category Management
- CRUD for products and images
- CRUD for product categories, including drag-and-drop sorting
- Product-category association

### 3. Order & Payment
- Place orders and query order status
- Payment integration
- Order association with addresses and users

### 4. Address & Delivery Area
- User address management
- Delivery area (postcode) management

### 5. Media Management
- Media upload, selection, and product association

### 6. System Settings
- Language switching (English/Chinese)
- Theme switching (light/dark/auto)

### 7. Admin Panel
- Role-based access control for admin features
- Custom rate limiting
- Queue monitoring with Laravel Horizon

---

## Tech Stack

- **Framework**: Laravel 11+
- **Database**: MySQL
- **Authentication**: Laravel Sanctum
- **Authorization**: spatie/laravel-permission
- **Queue/Cache**: Redis
- **Queue Monitoring**: Laravel Horizon
- **API**: RESTful, with Swagger documentation support
- **Frontend**: Blade + Bootstrap 5

---

## Installation and Setup

### 1. Clone the Repository
```bash
git clone https://github.com/LinWenhao5/takeaway_app_server.git
cd takeaway_app_server
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Configure Environment Variables
Copy `.env.example` to `.env` and configure your database, mail, and Redis settings.

### 4. Run Migrations and Seeders
```bash
php artisan migrate --seed
```

### 5. Start Required Services
Make sure MySQL and Redis are running.

### 6. Start Laravel Horizon (for queue monitoring)
```bash
php artisan horizon
```
Access the Horizon dashboard at `http://your-app-url/horizon`.

### 7. Start the Development Server
```bash
php artisan serve
```

---

## API Routes

### User
- **POST** `/customer/register`: Register a new user
- **POST** `/customer/login`: User login
- **POST** `/customer/generate-captcha`: Generate captcha

### Product
- **GET** `/products`: List products
- **GET** `/products/search`: Search products
- **GET** `/products/{product}`: Product details

### Category
- **GET** `/product-categories`: List categories
- **GET** `/product-categories/full`: Categories with products

### Order
- **POST** `/orders`: Create order (requires authentication)
- **POST** `/orders/payment-webhook`: Payment webhook

### Address
- **GET** `/addresses`: List user addresses (requires authentication)
- **POST** `/addresses`: Add address (requires authentication)

### Admin (requires admin/owner role)
- **GET** `/admin/products`: Manage products
- **GET** `/admin/product-categories`: Manage categories
- **GET** `/admin/allowed-postcodes`: Manage delivery areas
- **GET** `/admin/settings`: System settings

---

## Testing

- Tests are organized by feature module under `tests/Feature/` and `tests/Unit/`.
- Run all tests:
    ```bash
    php artisan test
    ```

---

## Contribution

Contributions are welcome! Please submit a Pull Request or open an issue.

---