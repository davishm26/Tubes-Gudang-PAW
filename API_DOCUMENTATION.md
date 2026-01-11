# REST API Documentation

## Base URL
```
http://localhost:8000/api
```

## Authentication
API menggunakan Laravel Sanctum untuk autentikasi. Setelah login, gunakan token yang diterima pada header request:
```
Authorization: Bearer {your-token}
```

Default akun hasil seeder:
- Email: `superadmin@example.com`
- Password: `password`

Jika akun belum ada, jalankan seeder:
`php artisan db:seed`

---

## Auth Endpoints

### 1. Login
**POST** `/api/login`

Request Body:

Query Parameters:
- `search` (optional): Search products by name, SKU, category, or supplier

Response:
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Product A",
      "sku": "PRD-001",
      "stock": 100,
      "category": {
        "id": 1,
        "name": "Category A"
      },
      "supplier": {
        "id": 1,
        "name": "Supplier A"
      }
    }
  ]
}
```

### Get Single Product
**GET** `/api/products/{id}`

### Create Product
**POST** `/api/products`

Request Body (multipart/form-data):
```json
{
  "name": "New Product",
  "sku": "PRD-002",
  "stock": 50,
  "category_id": 1,
  "supplier_id": 1,
  "image": "file"
}
```

### Update Product
**PUT/PATCH** `/api/products/{id}`

### Delete Product
**DELETE** `/api/products/{id}`

---

## Categories API

### Get All Categories
**GET** `/api/categories`

### Create Category
**POST** `/api/categories`

Request Body:
```json
{
  "name": "New Category"
}
```

### Update Category
**PUT/PATCH** `/api/categories/{id}`

### Delete Category
**DELETE** `/api/categories/{id}`

---

## Suppliers API

### Get All Suppliers
**GET** `/api/suppliers`

### Create Supplier
**POST** `/api/suppliers`

Request Body:
```json
{
  "name": "New Supplier",
  "contact": "081234567890",
  "address": "Supplier Address"
}
```

### Update Supplier
**PUT/PATCH** `/api/suppliers/{id}`

### Delete Supplier
**DELETE** `/api/suppliers/{id}`

---

## Inventory In API

### Get Inventory In Records
**GET** `/api/inventory-in`

### Create Inventory In
**POST** `/api/inventory-in`

Request Body:
```json
{
  "product_id": 1,
  "supplier_id": 1,
  "quantity": 50,
  "date": "2026-01-05",
  "description": "Stock masuk dari supplier"
}
```

### Get Inventory In History
**GET** `/api/inventory-in/history`

Query Parameters:
- `start_date` (optional)
- `end_date` (optional)

---

## Inventory Out API

### Get Inventory Out Records
**GET** `/api/inventory-out`

### Create Inventory Out
**POST** `/api/inventory-out`

Request Body:
```json
{
  "product_id": 1,
  "quantity": 20,
  "date": "2026-01-05",
  "description": "Penjualan produk"
}
```

### Get Inventory Out History
**GET** `/api/inventory-out/history`

Query Parameters:
- `start_date` (optional)
- `end_date` (optional)

---

## Users API (Admin Only)

### Get All Users
**GET** `/api/users`

### Create User
**POST** `/api/users`

Request Body:
```json
{
  "name": "Staf User",
  "email": "staf@example.com",
  "password": "password123",
  "role": "staf"
}
```

### Update User
**PUT/PATCH** `/api/users/{id}`

### Delete User
**DELETE** `/api/users/{id}`

---

## Testing dengan Postman/cURL

### Contoh Login:
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"superadmin@example.com","password":"password"}'
```

### Contoh Get Products:
```bash
curl -X GET http://localhost:8000/api/products \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### Contoh Create Product:
```bash
curl -X POST http://localhost:8000/api/products \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Product Test",
    "sku": "TST-001",
    "stock": 100,
    "category_id": 1,
    "supplier_id": 1
  }'
```

---

## Error Responses

### 401 Unauthorized
```json
{
  "message": "Unauthenticated."
}
```

### 403 Forbidden
```json
{
  "success": false,
  "message": "Unauthorized. Admin access required."
}
```

### 422 Validation Error
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The email field is required."]
  }
}
```

### 404 Not Found
```json
{
  "message": "No query results for model [App\\Models\\Product] 999"
}
```
