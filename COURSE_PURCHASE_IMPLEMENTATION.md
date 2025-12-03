# Course Purchase Implementation - Complete Documentation

## Overview
This document describes the complete implementation of course purchasing functionality integrated into the existing e-commerce checkout system. The system now supports both physical products and courses, with strict separation between the two types of items.

## Key Requirements Implemented

1. ✅ **Separation of Products and Courses**: Products and courses cannot be mixed in the same cart
2. ✅ **Login Requirement**: Users must be logged in to purchase courses (no guest checkout for courses)
3. ✅ **No Shipping for Courses**: Course checkout skips shipping information collection
4. ✅ **No COD for Courses**: Cash on Delivery (COD) is not available for course purchases
5. ✅ **Same Checkout Flow**: Courses use the same checkout infrastructure but with different conditions

---

## Database Changes

### 1. Carts Table (`carts`)
**Migration**: `2025_12_01_124642_add_course_fields_to_carts_table.php`

**New Fields Added**:
- `course_id` (unsignedBigInteger, nullable) - Foreign key to `courses.id`
- `course_schedule_id` (unsignedBigInteger, nullable) - Foreign key to `course_schedules.id`
- `item_type` (string, default: 'product') - Distinguishes between 'product' and 'course' items

**Foreign Keys**:
- `carts.course_id` → `courses.id` (onDelete: cascade)
- `carts.course_schedule_id` → `course_schedules.id` (onDelete: cascade)

### 2. Order Details Table (`order_details`)
**Migration**: `2025_12_01_124735_add_course_fields_to_order_details_table.php`

**New Fields Added**:
- `course_id` (unsignedBigInteger, nullable) - Foreign key to `courses.id`
- `course_schedule_id` (unsignedBigInteger, nullable) - Foreign key to `course_schedules.id`
- `course_metadata` (json, nullable) - Stores course-specific data (selected_date, selected_time, selected_level)
- `item_type` (string, default: 'product') - Distinguishes between 'product' and 'course' items

**Foreign Keys**:
- `order_details.course_id` → `courses.id` (onDelete: set null)
- `order_details.course_schedule_id` → `course_schedules.id` (onDelete: set null)

### 3. Course Purchases Table (`course_purchases`)
**Migration**: `2025_12_01_124821_create_course_purchases_table.php`

**Table Structure**:
- `id` (primary key)
- `user_id` (unsignedInteger) - Foreign key to `users.id`
- `course_id` (unsignedBigInteger) - Foreign key to `courses.id`
- `course_schedule_id` (unsignedBigInteger, nullable) - Foreign key to `course_schedules.id`
- `order_id` (unsignedInteger, nullable) - Foreign key to `orders.id`
- `order_detail_id` (unsignedBigInteger, nullable) - Foreign key to `order_details.id`
- `payment_method` (string, nullable)
- `payment_status` (string, default: 'pending') - Values: 'pending', 'completed', 'failed'
- `amount` (decimal 10,2)
- `payment_details` (text, nullable) - JSON for payment gateway response
- `transaction_id` (string, nullable)
- `selected_date` (date, nullable)
- `selected_time` (time, nullable)
- `selected_level` (string, nullable)
- `code` (string, unique, nullable) - Unique purchase code (format: CP-YYYYMMDD-UNIQUEID)
- `timestamps` (created_at, updated_at)

**Foreign Keys**:
- `course_purchases.user_id` → `users.id` (onDelete: cascade)
- `course_purchases.course_id` → `courses.id` (onDelete: cascade)
- `course_purchases.course_schedule_id` → `course_schedules.id` (onDelete: set null)
- `course_purchases.order_id` → `orders.id` (onDelete: set null)
- `course_purchases.order_detail_id` → `order_details.id` (onDelete: set null)

---

## Model Updates

### 1. Cart Model (`app/Models/Cart.php`)
**Changes**:
- Added `course_id`, `course_schedule_id`, `item_type` to `$fillable` array
- Added `course()` relationship: `belongsTo(Course::class)`
- Added `courseSchedule()` relationship: `belongsTo(CourseSchedule::class)`

### 2. OrderDetail Model (`app/Models/OrderDetail.php`)
**Changes**:
- Added `course()` relationship: `belongsTo(Course::class)`
- Added `courseSchedule()` relationship: `belongsTo(CourseSchedule::class)`
- Added `coursePurchase()` relationship: `hasOne(CoursePurchase::class)`

### 3. CoursePurchase Model (`app/Models/CoursePurchase.php`)
**Created/Updated**:
- Complete model with all relationships
- `$fillable` includes all course purchase fields
- `$casts` for proper data type handling
- Relationships:
  - `user()` - belongsTo User
  - `course()` - belongsTo Course
  - `courseSchedule()` - belongsTo CourseSchedule
  - `order()` - belongsTo Order
  - `orderDetail()` - belongsTo OrderDetail

---

## Controller Updates

### 1. CartController (`app/Http/Controllers/CartController.php`)

#### Changes to `index()` method:
- Added eager loading for course relationships: `with(['course', 'courseSchedule', 'course.institute'])`

#### Changes to `addToCart()` method:
- Added check to prevent adding products if cart contains courses
- Returns error modal if user tries to mix products with courses

#### New Method: `addCourseToCart()`
**Purpose**: Handles adding courses to the cart
**Requirements**:
- User must be logged in (returns redirect to login if not)
- Prevents adding courses if cart contains products
- Validates course_id, course_schedule_id, selected_date, selected_time, selected_level
- Stores course metadata in `variation` field as JSON
- Creates cart item with `item_type = 'course'`
- Sets `shipping_cost = 0` and `tax = 0` (adjustable)

**Route**: `POST /cart/add-course-to-cart` (named: `cart.addCourseToCart`)

### 2. CheckoutController (`app/Http/Controllers/CheckoutController.php`)

#### Changes to `get_shipping_info()` method:
- Checks if cart contains courses
- If courses found:
  - Requires user to be logged in (redirects to login if not)
  - Skips shipping information collection
  - Calculates totals (subtotal + tax, no shipping)
  - Creates dummy shipping info object
  - Redirects directly to payment selection page

#### Changes to `store_shipping_info()` method:
- Added course check at the beginning
- If courses found, same behavior as `get_shipping_info()` (skip shipping, go to payment)

#### Changes to `store_delivery_info()` method:
- Added course check at the beginning
- If courses found, same behavior (skip delivery, go to payment)

#### Changes to `checkout()` method:
- Determines payment type based on cart contents:
  - `course_payment` if cart contains courses
  - `cart_payment` if cart contains only products

#### New Method: `course_purchase_done()`
**Purpose**: Handles course purchase completion after payment
**Actions**:
- Updates `CoursePurchase` records with payment information
- Sets `payment_status = 'completed'`
- Stores payment method and transaction details
- Updates order payment status
- Clears cart
- Redirects to order confirmation page

### 3. OrderController (`app/Http/Controllers/OrderController.php`)

#### Changes to `store()` method:
**Major Refactoring**:
1. **Separation Logic**: Separates cart items into `$courseItems` and `$productItems` based on `item_type`

2. **Course Processing** (if courses exist):
   - Requires user to be logged in
   - Gets admin user (or first user as fallback) for course orders
   - Creates a single order for all courses (associated with admin/system)
   - For each course item:
     - Creates `OrderDetail` with:
       - `item_type = 'course'`
       - `course_id`, `course_schedule_id`
       - `course_metadata` (JSON with selected_date, selected_time, selected_level)
       - `shipping_type = 'digital'`
       - `shipping_cost = 0`
     - Creates `CoursePurchase` record linked to `OrderDetail`
     - Generates unique purchase code
   - Updates combined order total

3. **Product Processing** (existing logic):
   - Groups products by seller
   - Creates orders per seller
   - Processes stock management
   - Creates order details for products

**Key Points**:
- Course items skip stock management
- Course items don't have product_id
- Course orders are associated with admin/system user
- Course purchases are created immediately after order creation

### 4. Payment Controllers

#### StripeController (`app/Http/Controllers/Payment/StripeController.php`)
**Changes**:
- `create_checkout_session()`: Added handling for `course_payment` type
- `success()`: Added call to `course_purchase_done()` for `course_payment` type

#### PaypalController (`app/Http/Controllers/Payment/PaypalController.php`)
**Changes**:
- `pay()`: Added handling for `course_payment` type
- `getDone()`: Added call to `course_purchase_done()` for `course_payment` type

---

## View Updates

### 1. Cart Details View (`resources/views/frontend/classic/partials/cart_details.blade.php`)

**Changes**:
- Added `item_type` check in the loop
- For course items:
  - Displays course image, name, institute
  - Shows selected date, time, level
  - Quantity is always 1 and read-only
  - No quantity adjustment buttons
- For product items:
  - Existing logic remains unchanged
- "Continue to Shipping" button text changes to "Continue to Checkout" if cart contains courses

### 2. Payment Select View (`resources/views/frontend/payment_select.blade.php`)

**Changes**:
- Updated COD (Cash on Delivery) check:
  - Checks `item_type` for each cart item
  - If `item_type === 'course'`, sets `$digital = 1` and `$cod_on = 0`
  - This prevents COD option from showing for courses
- Handles both object and array access for cart items

### 3. Cart Summary View (`resources/views/frontend/classic/partials/cart_summary.blade.php`)

**Changes**:
- Added null checks for products
- Handles both object and array access
- Safely accesses product properties

### 4. Course Booking Page (`resources/views/frontend/courses/booking.blade.php`)

**Changes**:
- Updated "Select Date" button to "Add to Cart"
- Changed click handler to call `addCourseToCart` via AJAX
- Sends course_id, course_schedule_id, selected_date, selected_time, selected_level
- Shows loading state during AJAX call
- Redirects to cart on success
- Redirects to login if user not authenticated

### 5. Delivery Info View (`resources/views/frontend/delivery_info.blade.php`)

**Changes**:
- Added null checks for products
- Handles both object and array access
- Safely accesses product properties

---

## Routes Added

**File**: `routes/web.php`

```php
Route::post('/cart/add-course-to-cart', 'addCourseToCart')->name('cart.addCourseToCart');
```

---

## Flow Diagrams

### Course Purchase Flow

```
1. User visits course booking page
   ↓
2. User selects date, level, and time
   ↓
3. User clicks "Add to Cart"
   ↓
4. System checks:
   - User logged in? → If not, redirect to login
   - Cart has products? → If yes, show error
   ↓
5. Course added to cart with item_type='course'
   ↓
6. User goes to cart page
   ↓
7. User clicks "Continue to Checkout"
   ↓
8. System detects courses in cart:
   - Requires login (if not logged in)
   - Skips shipping information
   - Goes directly to payment selection
   ↓
9. User selects payment method (COD not available)
   ↓
10. Payment processed
    ↓
11. Order created:
    - Order with admin/system as seller
    - OrderDetail with course information
    - CoursePurchase record created
    ↓
12. Payment success:
    - CoursePurchase updated with payment info
    - Order marked as paid
    - Cart cleared
    ↓
13. Redirect to order confirmation page
```

### Product Purchase Flow (Unchanged)

```
1. User adds product to cart
   ↓
2. User goes to cart
   ↓
3. User clicks "Continue to Shipping"
   ↓
4. User provides shipping information
   ↓
5. User provides delivery information
   ↓
6. User selects payment method
   ↓
7. Payment processed
   ↓
8. Order created (existing logic)
   ↓
9. Order confirmation
```

---

## Key Features

### 1. Cart Separation
- **Prevention Logic**: 
  - `addToCart()` checks if cart has courses → prevents adding products
  - `addCourseToCart()` checks if cart has products → prevents adding courses
- **User Experience**: Shows appropriate error messages when trying to mix items

### 2. Login Requirement for Courses
- **Enforcement Points**:
  - `addCourseToCart()` - Returns redirect to login if not authenticated
  - `get_shipping_info()` - Redirects to login if courses in cart
  - `store_shipping_info()` - Redirects to login if courses in cart
  - `store_delivery_info()` - Redirects to login if courses in cart
  - `OrderController::store()` - Redirects to login if courses in cart

### 3. Shipping Skip for Courses
- **Implementation**: 
  - All checkout methods check for courses
  - If courses found, create dummy shipping info and redirect to payment
  - No shipping address collection for courses
  - No delivery method selection for courses

### 4. No COD for Courses
- **Implementation**:
  - `payment_select.blade.php` checks `item_type`
  - If any cart item is a course, COD option is hidden
  - Only online payment methods available for courses

### 5. Order Creation for Courses
- **Structure**:
  - Single order for all courses (not grouped by seller)
  - Order associated with admin/system user
  - Each course gets its own `OrderDetail`
  - Each `OrderDetail` gets a linked `CoursePurchase` record
  - Course metadata stored in `course_metadata` JSON field

---

## Testing Checklist

### Cart Functionality
- [ ] Add product to cart → Success
- [ ] Try to add course when product in cart → Error shown
- [ ] Remove product, add course → Success
- [ ] Try to add product when course in cart → Error shown
- [ ] Guest user tries to add course → Redirected to login
- [ ] Logged-in user adds course → Success

### Checkout Flow
- [ ] Cart with products → Goes through shipping → Delivery → Payment
- [ ] Cart with courses → Skips shipping → Goes directly to payment
- [ ] Guest with courses → Redirected to login
- [ ] COD option not shown for courses
- [ ] COD option shown for products (if applicable)

### Order Creation
- [ ] Course order created with correct structure
- [ ] CoursePurchase record created and linked
- [ ] OrderDetail has correct course metadata
- [ ] Payment updates CoursePurchase correctly
- [ ] Order confirmation shows course information

### Payment Integration
- [ ] Stripe payment works for courses
- [ ] PayPal payment works for courses
- [ ] Payment success updates CoursePurchase
- [ ] Cart cleared after successful payment

---

## Important Notes

1. **Course Metadata Storage**: Course-specific data (selected_date, selected_time, selected_level) is stored in:
   - Cart: `variation` field as JSON
   - OrderDetail: `course_metadata` field as JSON
   - CoursePurchase: Individual fields (selected_date, selected_time, selected_level)

2. **Price Determination**: 
   - If `course_schedule_id` exists, uses schedule price
   - Otherwise, uses course base price

3. **Tax and Shipping**: 
   - Courses have `tax = 0` by default (adjustable in `addCourseToCart`)
   - Courses have `shipping_cost = 0` (no shipping for digital products)

4. **Order Seller**: 
   - Course orders are associated with admin/system user
   - This is different from product orders which are grouped by seller

5. **Stock Management**: 
   - Courses skip stock management in OrderController
   - No quantity checks for courses (always quantity 1)

6. **Payment Types**: 
   - `course_payment` - For course purchases
   - `cart_payment` - For product purchases
   - Payment controllers handle both types

---

## Migration Instructions

To apply all database changes, run:

```bash
php artisan migrate --path=database/migrations/2025_12_01_124642_add_course_fields_to_carts_table.php
php artisan migrate --path=database/migrations/2025_12_01_124735_add_course_fields_to_order_details_table.php
php artisan migrate --path=database/migrations/2025_12_01_124821_create_course_purchases_table.php
```

Or run all pending migrations:
```bash
php artisan migrate
```

---

## Files Modified/Created

### Migrations
- ✅ `database/migrations/2025_12_01_124642_add_course_fields_to_carts_table.php` (Created)
- ✅ `database/migrations/2025_12_01_124735_add_course_fields_to_order_details_table.php` (Created)
- ✅ `database/migrations/2025_12_01_124821_create_course_purchases_table.php` (Created)

### Models
- ✅ `app/Models/Cart.php` (Updated)
- ✅ `app/Models/OrderDetail.php` (Updated)
- ✅ `app/Models/CoursePurchase.php` (Created/Updated)

### Controllers
- ✅ `app/Http/Controllers/CartController.php` (Updated)
- ✅ `app/Http/Controllers/CheckoutController.php` (Updated)
- ✅ `app/Http/Controllers/OrderController.php` (Updated)
- ✅ `app/Http/Controllers/Payment/StripeController.php` (Updated)
- ✅ `app/Http/Controllers/Payment/PaypalController.php` (Updated)

### Views
- ✅ `resources/views/frontend/classic/partials/cart_details.blade.php` (Updated)
- ✅ `resources/views/frontend/classic/partials/cart_summary.blade.php` (Updated)
- ✅ `resources/views/frontend/payment_select.blade.php` (Updated)
- ✅ `resources/views/frontend/delivery_info.blade.php` (Updated)
- ✅ `resources/views/frontend/courses/booking.blade.php` (Updated)

### Routes
- ✅ `routes/web.php` (Updated)

---

## Future Enhancements (Optional)

1. **Course Availability Check**: Verify course schedule availability before adding to cart
2. **Course Capacity Management**: Track and limit course enrollments
3. **Course Purchase History**: Display course purchases in user dashboard
4. **Course Certificates**: Generate certificates after course completion
5. **Course Refunds**: Implement refund system for courses
6. **Bulk Course Purchase**: Allow purchasing multiple courses at once
7. **Course Coupons**: Apply discount coupons to course purchases
8. **Course Reviews**: Allow users to review purchased courses

---

## Support

For any issues or questions regarding this implementation, please refer to:
- Code comments in the modified files
- This documentation
- Database schema in migrations
- Model relationships in respective model files

---

**Implementation Date**: December 1, 2025
**Version**: 1.0
**Status**: Complete and Ready for Testing

