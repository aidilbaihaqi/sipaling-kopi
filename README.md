# Sipaling Kopi - API Documentation

A Laravel-based REST API for a coffee shop management system.

## Features

- Category management
- Menu management
- Order management
- Order item management
- Payment management

## API Endpoints

### Categories
- `GET /api/v1/categories` - Get all categories
- `POST /api/v1/categories` - Create a new category
- `GET /api/v1/categories/{id}` - Get a specific category
- `PUT /api/v1/categories/{id}` - Update a category
- `DELETE /api/v1/categories/{id}` - Delete a category

### Menus
- `GET /api/v1/menus` - Get all menus
- `POST /api/v1/menus` - Create a new menu
- `GET /api/v1/menus/{id}` - Get a specific menu
- `PUT /api/v1/menus/{id}` - Update a menu
- `DELETE /api/v1/menus/{id}` - Delete a menu

### Orders
- `GET /api/v1/orders` - Get all orders
- `POST /api/v1/orders` - Create a new order
- `GET /api/v1/orders/{id}` - Get a specific order
- `PUT /api/v1/orders/{id}` - Update an order
- `DELETE /api/v1/orders/{id}` - Delete an order

### Order Items
- `GET /api/v1/order-items` - Get all order items
- `POST /api/v1/order-items` - Create a new order item
- `GET /api/v1/order-items/{id}` - Get a specific order item
- `PUT /api/v1/order-items/{id}` - Update an order item
- `DELETE /api/v1/order-items/{id}` - Delete an order item

### Payments
- `GET /api/v1/payments` - Get all payments
- `POST /api/v1/payments` - Create a new payment
- `GET /api/v1/payments/{id}` - Get a specific payment
- `PUT /api/v1/payments/{id}` - Update a payment
- `DELETE /api/v1/payments/{id}` - Delete a payment

## Testing the API

You can test the API using tools like Postman, Insomnia, or curl. The API is available at `http://localhost:8000/api/v1/`.

## Response Format

All responses are in JSON format. Successful operations return appropriate HTTP status codes (200, 201, 204). Error responses include error details.