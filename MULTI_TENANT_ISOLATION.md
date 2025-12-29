# Multi-Tenant Data Isolation System

## Overview

This system implements enterprise-grade multi-tenant data isolation where each subscription represents a distinct tenant with completely isolated data. All data entities (Products, Categories, Suppliers, Activity History) are scoped to a specific company/subscription.

## Architecture

### 1. Tenant Context (company_id)

Every tenant-scoped table includes a `company_id` foreign key:
- `products.company_id`
- `categories.company_id`
- `suppliers.company_id`
- `inventory_ins.company_id`
- `inventory_outs.company_id`
- `audit_logs.company_id`

### 2. Global Query Scopes

The `BelongsToCompany` trait automatically applies tenant filtering to all queries:

```php
// Automatically filters by current user's company_id
Product::all(); // Only returns products for user's tenant

// Works for all related queries
$company->products; // Only this company's products
```

### 3. Data Isolation Rules

#### Query-Level Isolation
All queries are automatically scoped through Laravel's global scopes:
- Non-admin users: `WHERE company_id = user.company_id`
- Admin users: Full access (can view all tenants)

#### API-Level Isolation
Controllers implicitly enforce tenant context through model scopes.

#### Database-Level Isolation
Foreign keys and indexes ensure referential integrity within tenant boundaries.

## Models

### BelongsToCompany Trait

```php
use App\Traits\BelongsToCompany;

class Product extends Model {
    use BelongsToCompany; // Automatic tenant scoping
}
```

**Features:**
- Automatic company_id assignment on creation
- Global query scoping by tenant
- Convenience methods: `scopeForCompany()`

### Models Using This Trait

1. **Product** - Represents inventory items
2. **Category** - Product classifications
3. **Supplier** - Vendor information
4. **InventoryIn** - Stock receipts
5. **InventoryOut** - Stock movements

## Access Control

### Middleware

#### TenantMiddleware
Apply to routes requiring tenant isolation:

```php
Route::middleware(['auth', TenantMiddleware::class])->group(function () {
    Route::resource('products', ProductController::class);
});
```

**Enforces:**
- User must belong to a company
- Company must not be suspended
- User cannot access other tenants' data

### Authorization Policies

Implement resource-level access:

```php
class ProductPolicy {
    public function view(User $user, Product $product) {
        return $user->company_id === $product->company_id;
    }
}
```

## Data Lifecycle

### Subscription Created
1. Company record created with subscription details
2. Tenant is ready to store data
3. Users assigned to company can create products, categories, suppliers

### Subscription Active
- All CRUD operations within tenant scope
- Data accessible only to tenant users
- Audit logs track all changes

### Subscription Suspended
- Access restricted via middleware
- Data remains in database (preserved for compliance)
- Can be reactivated with full history intact

### Subscription Expired/Deleted
- Option 1: Retain data for compliance/history
- Option 2: Soft delete for recovery
- Hard delete removes all tenant data (with cascade)

## Audit Logging

### AuditLog Model

Tracks all changes within tenant context:

```php
AuditLog::logAction(
    'Product',           // entity_type
    $product->id,        // entity_id
    'create',            // action
    ['name' => 'Widget'] // changes
);
```

**Fields:**
- `company_id` - Tenant context
- `user_id` - Who made the change
- `entity_type` - What was changed
- `action` - create, update, delete, view
- `changes` - JSON diff of changes
- `ip_address` - Source IP
- `timestamp` - When

### Query Audit Logs

```php
// View all changes in tenant
$logs = AuditLog::forCompany(auth()->user()->company_id)->get();

// View changes for specific entity
$logs = AuditLog::where('entity_type', 'Product')
    ->where('entity_id', 1)
    ->get();
```

## Security Features

### Cross-Tenant Attack Prevention

1. **Query-Level** - Global scopes prevent data leakage
2. **Application-Level** - Controllers inherit tenant context
3. **Authorization-Level** - Policies check tenant ownership
4. **Database-Level** - Foreign keys enforce boundaries

### Example Attack Prevention

```php
// Attack attempt: Access other tenant's product
$product = Product::find(999); // Returns null if not in user's tenant

// Attack attempt: Bulk data access
Product::all(); // Only returns current tenant's products

// Attack attempt: Direct API bypass
// Even with raw SQL, company_id acts as additional filter
```

## Implementation Guide

### 1. Add Migrations

Run all migrations in `database/migrations/2025_12_30_*`:

```bash
php artisan migrate
```

### 2. Update Models

All core models updated to use `BelongsToCompany` trait.

### 3. Apply Middleware

Register in `app/Http/Kernel.php`:

```php
protected $routeMiddleware = [
    'tenant' => \App\Http\Middleware\TenantMiddleware::class,
];
```

Apply to tenant-scoped routes:

```php
Route::middleware(['auth', 'tenant'])->group(function () {
    Route::resource('products', ProductController::class);
});
```

### 4. Use in Controllers

```php
class ProductController extends Controller {
    public function index() {
        // Automatically scoped to current user's company
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    public function store(Request $request) {
        // company_id automatically set
        $product = Product::create($request->validated());
        
        // Log action
        AuditLog::logAction('Product', $product->id, 'create');
    }
}
```

## Best Practices

### 1. Always Use Models

❌ **Avoid raw queries:**
```php
DB::table('products')->get();
```

✅ **Use models with scopes:**
```php
Product::all();
```

### 2. Log Critical Actions

```php
public function destroy(Product $product) {
    AuditLog::logAction('Product', $product->id, 'delete', [
        'name' => $product->name,
        'sku' => $product->sku
    ]);
    $product->delete();
}
```

### 3. Check Tenant Ownership in Policies

```php
public function update(User $user, Product $product) {
    return $user->company_id === $product->company_id;
}
```

### 4. Handle Super Admin Context

```php
// Super admin can view all tenant data
if (auth()->user()->isSuperAdmin()) {
    $products = Product::withoutGlobalScopes()->get();
}
```

## Testing

### Unit Test Example

```php
public function test_user_cannot_access_other_tenant_products() {
    $tenant1 = Company::factory()->create();
    $tenant2 = Company::factory()->create();
    
    $user1 = User::factory()->create(['company_id' => $tenant1->id]);
    $product2 = Product::factory()->create(['company_id' => $tenant2->id]);
    
    $this->actingAs($user1);
    
    // Should not find product from different tenant
    $this->assertNull(Product::find($product2->id));
}
```

## Compliance & Security Notes

- ✅ **Data Isolation** - Row-level isolation per subscription
- ✅ **Access Control** - Middleware + Policy enforcement
- ✅ **Audit Trail** - Complete change history per tenant
- ✅ **Data Retention** - Preserved on suspension
- ✅ **Encryption Ready** - Prepared for field-level encryption
- ✅ **GDPR Compliant** - Data ownership & deletion support

## Troubleshooting

### Users Seeing Other Tenant Data

Check:
1. Is `BelongsToCompany` trait applied to model?
2. Are queries using models (not raw SQL)?
3. Is TenantMiddleware applied to routes?

### Missing company_id on Create

Ensure:
1. User has valid `company_id`
2. Model has `BelongsToCompany` trait
3. User is authenticated

### Audit Logs Not Recording

Verify:
1. Call `AuditLog::logAction()` in controller
2. `company_id` is set on authenticated user
3. Check database for entries
