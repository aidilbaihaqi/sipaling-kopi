# Sipaling Kopi - API Documentation

A Laravel-based REST API for a coffee shop management system.

## Features

- Category management
- Menu management
- Order management
- Order item management
- Payment management
- Role-Based Access Control (Admin, Kitchen Manager, Cashier)

## API Documentation

The complete API documentation is available via Swagger UI. Once you have the application running, you can access it at:

[http://localhost:8000/api/documentation](http://localhost:8000/api/documentation)

The documentation provides detailed information about all available endpoints, including parameters, request bodies, and response schemas.

## Authentication

The API uses two authentication methods:

1.  **Laravel Sanctum**: For user authentication, a bearer token must be included in the `Authorization` header.
2.  **API Key**: For application-level access, an API key must be provided in the `X-API-KEY` header.

## Role-Based Access Control (RBAC)

The API implements role-based access control to restrict access to certain endpoints based on user roles. The available roles are:

- **Admin**: Full access to all resources.
- **Kitchen Manager**: Access to kitchen-related resources.
- **Cashier**: Access to cashier-related resources.

Specific role-based endpoints are available for testing:

- `GET /api/v1/admin`: Accessible only by users with the "admin" role.
- `GET /api/v1/kitchen`: Accessible only by users with the "kitchen" role.
- `GET /api/v1/cashier`: Accessible only by users with the "cashier" role.

## API Endpoints

For a complete and detailed list of all API endpoints, please refer to the [Swagger Documentation](#api-documentation). The base URL for the API is `http://localhost:8000/api/v1/`.

## Testing the API

You can test the API using tools like Postman, Insomnia, or by interacting with the Swagger UI.

## Response Format

All responses are in JSON format. Successful operations return appropriate HTTP status codes (200, 201, 204). Error responses include error details.