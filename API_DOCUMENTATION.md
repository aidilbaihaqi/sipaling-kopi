# REST API Documentation - Sipaling Kopi

## Base URL
```
/api
```

## Authentication
Semua endpoint (kecuali login) memerlukan authentication menggunakan Laravel Sanctum.

### Headers Required
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
X-CSRF-TOKEN: {csrf_token}
```

---

## Auth Endpoints

### Login
```
POST /api/auth/login
```
**Body:**
```json
{
  "email": "admin@sipalingkopi.com",
  "password": "password123"
}
```
**Response:**
```json
{
  "status": "success",
  "message": "Login berhasil",
  "data": {
    "user": {...},
    "token": "1|abc123...",
    "redirect": "/admin/dashboard"
  }
}
```

### Logout
```
POST /api/auth/logout
```

### Get Current User
```
GET /api/auth/me
```

---

## Menu Endpoints

### Get All Menus
```
GET /api/menus
GET /api/menus?search=kopi&category_id=1&is_available=true
```

### Get Available Menus (for Cashier)
```
GET /api/menus/available
```

### Get Single Menu
```
GET /api/menus/{id}
```

### Create Menu
```
POST /api/menus
```
**Body:**
```json
{
  "category_id": 1,
  "name": "Espresso",
  "description": "Kopi hitam pekat",
  "price": 15000,
  "stock": 100,
  "is_available": true
}
```

### Update Menu
```
PUT /api/menus/{id}
```

### Delete Menu
```
DELETE /api/menus/{id}
```

---

## Category Endpoints

### Get All Categories
```
GET /api/categories
```

### Get Single Category
```
GET /api/categories/{id}
```

### Create Category
```
POST /api/categories
```
**Body:**
```json
{
  "name": "Kopi Panas",
  "description": "Berbagai macam kopi panas"
}
```

### Update Category
```
PUT /api/categories/{id}
```

### Delete Category
```
DELETE /api/categories/{id}
```

---

## User Endpoints

### Get All Users
```
GET /api/users
GET /api/users?role=admin&search=john
```

### Get Single User
```
GET /api/users/{id}
```

### Create User
```
POST /api/users
```
**Body:**
```json
{
  "name": "John Doe",
  "email": "john@sipalingkopi.com",
  "password": "password123",
  "role": "cashier"
}
```
**Roles:** `admin`, `cashier`, `kitchen`

### Update User
```
PUT /api/users/{id}
```

### Delete User
```
DELETE /api/users/{id}
```

---

## Order Endpoints

### Get All Orders
```
GET /api/orders
GET /api/orders?status=pending&today=true
GET /api/orders?start_date=2025-01-01&end_date=2025-01-31
```

### Get Kitchen Orders (pending, processing, ready)
```
GET /api/orders/kitchen
```

### Get Today's History (for Cashier)
```
GET /api/orders/history
```

### Get Single Order
```
GET /api/orders/{id}
```

### Create Order (Checkout)
```
POST /api/orders
```
**Body:**
```json
{
  "customer_name": "Budi",
  "type": "dine-in",
  "table_no": "5",
  "payment_method": "cash",
  "cart": [
    {"menu_id": 1, "qty": 2, "note": "tanpa gula"},
    {"menu_id": 3, "qty": 1, "note": ""}
  ],
  "payment_amount": 50000,
  "total_price": 45000
}
```
**Types:** `dine-in`, `takeaway`
**Payment Methods:** `cash`, `qris`, `transfer`

### Update Order Status (Kitchen)
```
PUT /api/orders/{id}/status
```
**Body:**
```json
{
  "status": "processing"
}
```
**Statuses:** `pending`, `processing`, `ready`, `completed`, `cancelled`

---

## Stock Endpoints (Kitchen)

### Get Stock List
```
GET /api/stock
GET /api/stock?search=kopi&category=1&availability=available
```

### Toggle Menu Availability
```
POST /api/stock/{menu_id}/toggle
```

### Update Stock
```
POST /api/stock/{menu_id}/update
```
**Body:**
```json
{
  "stock": 50
}
```

---

## Dashboard Endpoint (Admin)

### Get Dashboard Data
```
GET /api/dashboard
GET /api/dashboard?chart_days=7
GET /api/dashboard?start_date=2025-01-01&end_date=2025-01-07
```

---

## Report Endpoints (Admin)

### Get Report Data
```
GET /api/reports
GET /api/reports?start_date=2025-01-01&end_date=2025-01-31
```

### Export to Excel
```
GET /api/reports/export?start_date=2025-01-01&end_date=2025-01-31
```

---

## Response Format

### Success Response
```json
{
  "status": "success",
  "message": "Operation berhasil",
  "data": {...}
}
```

### Error Response
```json
{
  "status": "error",
  "message": "Error message"
}
```

### Validation Error (422)
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "field_name": ["Error message"]
  }
}
```

---

## Role-Based Access

| Role | Access |
|------|--------|
| Admin | Dashboard, Menu, Category, User, Report |
| Cashier | POS, Order History, Print Receipt |
| Kitchen | Order Queue, Stock Management |
