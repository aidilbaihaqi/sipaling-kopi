<?php

namespace App\Http\Swagger;

/**
 * @OA\Info(
 *     title="Sipaling Kopi API",
 *     version="1.0.0",
 *     description="API for Sipaling Kopi",
 *     @OA\Contact(
 *         email="[email protected]"
 *     )
 * )
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer"
 * )
 * @OA\Schema(
 *     schema="Category",
 *     title="Category",
 *     description="Category model",
 *     @OA\Property(property="id", type="string", format="uuid", description="ID of the category"),
 *     @OA\Property(property="name", type="string", description="Name of the category"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp")
 * )
 * @OA\Schema(
 *     schema="CategoryRequest",
 *     title="CategoryRequest",
 *     description="Category request model",
 *     required={"name"},
 *     @OA\Property(property="name", type="string", description="Name of the category")
 * )
 * @OA\Schema(
 *     schema="Menu",
 *     title="Menu",
 *     description="Menu model",
 *     @OA\Property(property="id", type="string", format="uuid", description="ID of the menu"),
 *     @OA\Property(property="name", type="string", description="Name of the menu"),
 *     @OA\Property(property="category_id", type="string", format="uuid", description="ID of the category"),
 *     @OA\Property(property="price", type="number", format="float", description="Price of the menu"),
 *     @OA\Property(property="is_available", type="boolean", description="Availability of the menu"),
 *     @OA\Property(property="stock", type="integer", description="Stock of the menu"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp")
 * )
 * @OA\Schema(
 *     schema="MenuRequest",
 *     title="MenuRequest",
 *     description="Menu request model",
 *     required={"name", "category_id", "price", "is_available", "stock"},
 *     @OA\Property(property="name", type="string", description="Name of the menu"),
 *     @OA\Property(property="category_id", type="string", format="uuid", description="ID of the category"),
 *     @OA\Property(property="price", type="number", format="float", description="Price of the menu"),
 *     @OA\Property(property="is_available", type="boolean", description="Availability of the menu"),
 *     @OA\Property(property="stock", type="integer", description="Stock of the menu")
 * )
 * @OA\Schema(
 *     schema="Order",
 *     title="Order",
 *     description="Order model",
 *     @OA\Property(property="id", type="string", format="uuid", description="ID of the order"),
 *     @OA\Property(property="type", type="string", enum={"dine-in", "takeaway"}, description="Type of the order"),
 *     @OA\Property(property="table_no", type="integer", description="Table number for dine-in orders"),
 *     @OA\Property(property="status", type="string", enum={"pending", "processing", "completed", "cancelled"}, description="Status of the order"),
 *     @OA\Property(property="total_price", type="number", format="float", description="Total price of the order"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp")
 * )
 * @OA\Schema(
 *     schema="OrderRequest",
 *     title="OrderRequest",
 *     description="Order request model",
 *     required={"type", "status", "total_price"},
 *     @OA\Property(property="type", type="string", enum={"dine-in", "takeaway"}, description="Type of the order"),
 *     @OA\Property(property="table_no", type="integer", description="Table number for dine-in orders"),
 *     @OA\Property(property="status", type="string", enum={"pending", "processing", "completed", "cancelled"}, description="Status of the order"),
 *     @OA\Property(property="total_price", type="number", format="float", description="Total price of the order")
 * )
 * @OA\Schema(
 *     schema="OrderItem",
 *     title="OrderItem",
 *     description="OrderItem model",
 *     @OA\Property(property="id", type="string", format="uuid", description="ID of the order item"),
 *     @OA\Property(property="order_id", type="string", format="uuid", description="ID of the order"),
 *     @OA\Property(property="menu_id", type="string", format="uuid", description="ID of the menu"),
 *     @OA\Property(property="quantity", type="integer", description="Quantity of the menu item"),
 *     @OA\Property(property="price", type="number", format="float", description="Price of the menu item"),
 *     @OA\Property(property="status", type="string", enum={"pending", "cooking", "ready", "served", "cancelled"}, description="Status of the order item"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp")
 * )
 * @OA\Schema(
 *     schema="OrderItemRequest",
 *     title="OrderItemRequest",
 *     description="OrderItem request model",
 *     required={"order_id", "menu_id", "quantity", "price", "status"},
 *     @OA\Property(property="order_id", type="string", format="uuid", description="ID of the order"),
 *     @OA\Property(property="menu_id", type="string", format="uuid", description="ID of the menu"),
 *     @OA\Property(property="quantity", type="integer", description="Quantity of the menu item"),
 *     @OA\Property(property="price", type="number", format="float", description="Price of the menu item"),
 *     @OA\Property(property="status", type="string", enum={"pending", "cooking", "ready", "served", "cancelled"}, description="Status of the order item")
 * )
 * @OA\Schema(
 *     schema="Payment",
 *     title="Payment",
 *     description="Payment model",
 *     @OA\Property(property="id", type="string", format="uuid", description="ID of the payment"),
 *     @OA\Property(property="order_id", type="string", format="uuid", description="ID of the order"),
 *     @OA\Property(property="amount", type="number", format="float", description="Amount of the payment"),
 *     @OA\Property(property="payment_method", type="string", enum={"cash", "credit_card", "debit_card", "online"}, description="Payment method"),
 *     @OA\Property(property="status", type="string", enum={"pending", "paid", "failed"}, description="Status of the payment"),
 *     @OA\Property(property="requestId", type="string", description="Request ID for online payments"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp")
 * )
 * @OA\Schema(
 *     schema="PaymentRequest",
 *     title="PaymentRequest",
 *     description="Payment request model",
 *     required={"order_id", "amount", "payment_method", "status"},
 *     @OA\Property(property="order_id", type="string", format="uuid", description="ID of the order"),
 *     @OA\Property(property="amount", type="number", format="float", description="Amount of the payment"),
 *     @OA\Property(property="payment_method", type="string", enum={"cash", "credit_card", "debit_card", "online"}, description="Payment method"),
 *     @OA\Property(property="status", type="string", enum={"pending", "paid", "failed"}, description="Status of the payment"),
 *     @OA\Property(property="requestId", type="string", description="Request ID for online payments")
 * )
 */
final class Swagger
{
    //
}
