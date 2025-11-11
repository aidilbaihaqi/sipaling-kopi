# Cafe Management System

## Overview

This is a comprehensive Cafe Management System designed to streamline cafe operations, from menu management to order processing and payment. The system is built with a robust backend using Laravel 12 and a REST API architecture, ensuring scalability and maintainability.

## Tech Stack

- **Framework**: Laravel 12
- **Architecture**: REST API
- **Authentication**: Sanctum
- **API Security**: API Key Gateway

## Features

### Role-Based Access Control (RBAC)

- **Admin**:
  - Full CRUD (Create, Read, Update, Delete) access to master data (menus, users, reports, categories).
  - Complete access to all system features.
- **Kitchen Manager/Cook**:
  - Update menu availability (`AVAILABLE`/`UNAVAILABLE`).
  - Change order status from `IN_PROGRESS` to `READY`.
- **Cashier**:
  - Create new orders (`dine_in`/`takeaway`).
  - Record payments and manage transactions.

### System Flow

1.  **Menu Management**:
    - The kitchen staff updates the availability of menu items.
    - Validation ensures that only available items (`is_available = true`) can be ordered.
2.  **Order Processing**:
    - The cashier creates an order, specifying the type (`dine_in` or `takeaway`).
    - For `dine_in` orders, a `table_no` is required.
    - Once confirmed, the order is sent to the kitchen.
    - The kitchen updates the item status to `READY` upon completion.
    - The cashier finalizes the order and records the payment.
    - A unique `requestId` prevents double payments.
    - Orders with `IN_PROGRESS` status cannot be canceled.

### Additional Features

- **Reporting and Analytics**:
  - Admins can view sales statistics and daily/monthly transaction reports.
- **Stock Management**:
  - Inventory is automatically updated when an order is confirmed.

## API Security

- All frontend clients must use a valid `API_KEY` to interact with the API.
- The `api_keys` table stores and validates API keys.
- A dedicated middleware validates the `API_KEY` for all protected endpoints, including `auth/login`.

## Database Models

- **Menu**: `id`, `name`, `category_id`, `price`, `is_available`, `stock`
- **Orders**: `id`, `type`, `table_no`, `status`, `total_price`
- **OrderItems**: `id`, `order_id`, `menu_id`, `quantity`, `status`
- **Payments**: `id`, `order_id`, `amount`, `payment_method`, `requestId`
- **ApiKeys**: `api_key`, `app_name`, `status`, `created_at`

## Getting Started

1.  **Clone the repository**:
    ```bash
    git clone https://github.com/aidilbaihaqi/sipaling-kopi
    cd sipaling-kopi
    ```
2.  **Install dependencies**:
    ```bash
    composer install
    npm install
    ```
3.  **Set up your environment**:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
4.  **Configure your database** in the `.env` file.
5.  **Run migrations and seed**:
    ```bash
    php artisan migrate --seed
    ```
6.  **Start the development server**:
    
    Open 2 terminals:
    
    **Terminal 1 - Laravel Server:**
    ```bash
    php artisan serve
    ```
    
    **Terminal 2 - Vite Dev Server:**
    ```bash
    npm run dev
    ```

## Admin Login Credentials

After running the seeder, you can access the admin panel at `http://localhost:8000/login` with the following credentials:

- **Email**: `admin@sipalingkopi.com`
- **Password**: `GakNgopiGakGacor123`

For detailed admin panel documentation, see [ADMIN_README.md](ADMIN_README.md)

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
