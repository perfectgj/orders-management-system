# Laravel Practical Test – Orders Management System  
Developed by: **Girish Jadeja**  
Experience: **3 Years (Laravel Developer)**

--- 

# 🚀 QUICK SETUP
Use this section for quick project installation:

## Practical Task - Orders & Customers (Laravel 12)

### Setup
1. Clone repo
2. `composer install`
3. Copy `.env.example` → `.env` and set database credentials
4. `php artisan key:generate`
5. Ensure in `.env`:
   ```
   FILESYSTEM_DRIVER=public
   ```
6. `php artisan storage:link`
7. `php artisan migrate --seed`
8. `php artisan serve`

### Login:
- **Admin:** admin@example.com / password  
- **Staff:** staff@example.com / password  

### Routes:
- `/login`  
- `/admin` (dashboard)  
- `/admin/customers`  
- `/admin/products`  
- `/admin/orders`  
- `/admin/profile`  
- `/staff/orders` (staff order module)

### Notes:
- Orders use **DB transactions** & **lockForUpdate()** for consistency and preventing overselling.
- Product images stored inside `storage/app/public/products` and served through `/storage/...`.
- Order total price is calculated **server-side only** for security.

---

# 📌 Overview
This project implements a complete **Order Management System** following real-world development standards.  
It demonstrates:

- Clean Laravel architecture  
- Role-based access control (Admin / Staff)  
- Order creation with dynamic product rows  
- AJAX order status update (Admin only)  
- Advanced order listing with filters and search  
- Secure & scalable structure suitable for production  

---
