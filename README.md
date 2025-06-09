# Takeaway App Server

This is the backend service for a takeaway application, built with the Laravel framework. It provides user authentication, product management, category management, and other features. The service supports RESTful APIs and includes functionality for captcha generation and token-based authentication.

---

## Features Overview

### 1. User Authentication
- **Registration**: Users can register using their email and verify with a captcha.
- **Login**: Supports email and password-based login, returning an authentication token.
- **Captcha Generation**: Sends a captcha to the user's email for registration or login verification.

### 2. Product Management
- **Product List**: Retrieve a list of all products.
- **Product Search**: Search for products by name or description.
- **Product Details**: View detailed information about a single product.

### 3. Category Management
- **Category List**: Retrieve a list of all product categories.
- **Categories with Products**: Retrieve categories along with their associated products.

### 4. Admin Features
- **Role-Based Access**: Admins can manage products and categories.
- **Rate Limiting**: Custom rate limits for admins and users.

---

## Tech Stack

- **Framework**: Laravel
- **Database**: MySQL
- **Authentication**: Laravel Sanctum
- **Queue**: Supports asynchronous tasks (e.g., sending captcha emails)
- **Cache**: Redis for caching and queue management
- **Queue Monitoring**: Laravel Horizon for managing and monitoring queues
- **API**: RESTful API

---

## Installation and Setup

### 1. Clone the Repository
```bash
git clone https://github.com/your-repo/takeaway_app_server.git
cd takeaway_app_server
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Configure Environment Variables
Copy the `.env.example` file and rename it to `.env`. Then configure the database, mail service, and Redis:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=takeaway_app
DB_USERNAME=root
DB_PASSWORD=your_password

MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=your_email@example.com
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email@example.com
MAIL_FROM_NAME="Takeaway App"

QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
REDIS_PASSWORD=null
```

### 4. Run Migrations
```bash
php artisan migrate
```

### 5. Start the MySQL Server
Ensure MySQL is installed and running:
```bash
sudo service mysql start
```

### 6. Start the Redis Server
Ensure Redis is installed and running:
```bash
redis-server
```

### 7. Start Laravel Horizon
Run the following command to start Horizon:
```bash
php artisan horizon
```

You can access the Horizon dashboard at `http://your-app-url/horizon`.

### 8. Start the Development Server
```bash
php artisan serve
```

---

## API Routes

### User Routes
- **POST** `/customer/register`: User registration
- **POST** `/customer/login`: User login
- **POST** `/customer/generate-captcha`: Generate captcha

### Product Routes
- **GET** `/products`: Retrieve product list
- **GET** `/products/search`: Search for products
- **GET** `/products/{product}`: Retrieve product details

### Category Routes
- **GET** `/product-categories`: Retrieve category list
- **GET** `/product-categories/full`: Retrieve categories with associated products

---

## Testing

### Unit Tests
Run the following command to execute unit tests:
```bash
php artisan test
```

---

## Contribution

Contributions are welcome! Please submit a Pull Request or report issues.

---

## License

This project is open-source and licensed under the MIT License.