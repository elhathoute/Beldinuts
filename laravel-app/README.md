# BeldiNuts - Laravel E-commerce Application

E-commerce application for Moroccan nuts built with Laravel 10+, featuring multi-language support (FR/AR/EN), multi-role system (Admin/Client), and WhatsApp integration.

## 🚀 Features

- **Multi-language**: French, Arabic, English using `mcamara/laravel-localization`
- **Multi-role System**: Admin and Client roles with role-based access control
- **Docker Development**: Complete Docker setup with MySQL, Nginx, Redis
- **WhatsApp Integration**: Order notifications via WhatsApp
- **Real-time Currency Conversion**: DH, EUR, USD
- **Order Management**: Minimum order 100 DH (50g minimum)
- **Responsive Design**: TailwindCSS with existing design preserved

## 📋 Requirements

- Docker & Docker Compose
- PHP 8.2+
- Composer
- Node.js & NPM (for asset compilation, optional)

## 🛠️ Installation

### 1. Clone and Setup

```bash
cd laravel-app
cp .env.example .env
php artisan key:generate
```

### 2. Configure Environment

Edit `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=beldinuts
DB_USERNAME=beldinuts
DB_PASSWORD=password

APP_URL=http://localhost:8080
```

### 3. Docker Setup

```bash
docker-compose up -d
```

### 4. Install Dependencies & Migrate

```bash
# Enter the container
docker exec -it beldinuts_app bash

# Install dependencies
composer install

# Run migrations
php artisan migrate

# Seed database (optional)
php artisan db:seed
```

### 5. Access the Application

- **Application**: http://localhost:8080
- **Default Languages**: 
  - French: http://localhost:8080/fr
  - Arabic: http://localhost:8080/ar
  - English: http://localhost:8080/en

## 📁 Project Structure

```
laravel-app/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── HomeController.php
│   │   │   ├── ProductController.php
│   │   │   ├── OrderController.php
│   │   │   └── AdminController.php
│   │   └── Middleware/
│   │       └── AdminMiddleware.php
│   └── Models/
│       ├── User.php
│       ├── Product.php
│       ├── Order.php
│       ├── OrderItem.php
│       └── Review.php
├── database/
│   └── migrations/
│       ├── create_users_table.php
│       ├── create_products_table.php
│       ├── create_orders_table.php
│       ├── create_order_items_table.php
│       └── create_reviews_table.php
├── resources/
│   ├── lang/
│   │   ├── fr/messages.php
│   │   ├── ar/messages.php
│   │   └── en/messages.php
│   └── views/
│       ├── layouts/
│       ├── partials/
│       └── home.blade.php
├── routes/
│   ├── web.php
│   └── auth.php
└── docker-compose.yml
```

## 🔐 Authentication

### Create Admin User

```bash
php artisan tinker

# Create admin user
User::create([
    'name' => 'Admin',
    'email' => 'admin@beldinuts.ma',
    'password' => bcrypt('password'),
    'role' => 'admin'
]);
```

### Create Client User

```bash
User::create([
    'name' => 'Client',
    'email' => 'client@example.com',
    'password' => bcrypt('password'),
    'role' => 'client'
]);
```

## 🌍 Localization

Language files are located in `resources/lang/{locale}/messages.php`.

To add translations:

```php
// resources/lang/fr/messages.php
return [
    'key' => 'Translation',
];
```

Use in Blade templates:

```blade
{{ __('messages.key') }}
```

## 📦 Database Models

### User
- `id`, `name`, `email`, `role` (admin/client), `phone`, `address`

### Product
- `id`, `name`, `description`, `price_per_gram`, `stock`, `image`

### Order
- `id`, `user_id`, `total`, `status`, `tracking`, `phone`, `address`

### OrderItem
- `id`, `order_id`, `product_id`, `quantity_grams`, `unit_price`

### Review
- `id`, `user_id`, `product_id`, `rating`, `comment`

## 🛣️ Routes

All routes are prefixed with locale:

- `/fr/`, `/ar/`, `/en/`

### Public Routes
- `GET /` - Home page
- `GET /products` - Products listing
- `GET /commander` - Order page
- `POST /order` - Create order

### Protected Routes
- `GET /orders` - User orders (requires auth)
- `GET /admin/products` - Admin product management
- `GET /admin/orders` - Admin order management

## 🔧 Configuration

### Localization Config
`config/laravellocalization.php`

Supported locales: `fr`, `ar`, `en`

### WhatsApp Integration

Add to `.env`:

```env
WHATSAPP_PHONE=212615919437
```

## 📝 TODO / Remaining Tasks

1. ✅ Database migrations and models
2. ✅ Basic authentication setup
3. ✅ Localization configuration
4. ✅ Docker setup
5. ⏳ Complete OrderController with validation
6. ⏳ Complete ProductController
7. ⏳ Complete AdminController
8. ⏳ WhatsApp integration service
9. ⏳ Currency conversion service
10. ⏳ Order minimum validation (100 DH, 50g)
11. ⏳ Order tracking system
12. ⏳ Admin panel views
13. ⏳ Convert commander.html to Blade
14. ⏳ Cart functionality
15. ⏳ Product reviews system

## 🧪 Testing

```bash
php artisan test
```

## 📄 License

© 2024 BeldiNuts. All rights reserved.

## 🆘 Support

For issues or questions, contact: beldinuts@gmail.com
