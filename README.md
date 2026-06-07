# 🛒 Alain E-Shopping Platform

> A full-featured multi-role PHP e-commerce platform with modern glassmorphism UI design.

![PHP](https://img.shields.io/badge/PHP-8.0+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap_5-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)
![XAMPP](https://img.shields.io/badge/XAMPP-FB7A24?style=for-the-badge&logo=xampp&logoColor=white)

---

## 🌟 Project Overview

**Alain E-Shopping** is an academic e-commerce web application built with PHP & MySQL, featuring three distinct user roles, a premium glassmorphism dark UI, full order management, product reviews, and digital product downloads.

### ✅ Features at a Glance

| Feature | Details |
|---------|---------|
| **3 User Roles** | Admin, Seller, Customer |
| **Product Management** | Upload, edit, delete with image support |
| **Order System** | Full checkout → order tracking → invoice |
| **Digital Downloads** | Customers can download digital product files |
| **Product Reviews** | Star ratings + comments per product |
| **Seller Approval** | Admin approves/rejects sellers |
| **Role-based Access** | Secure PHP session-based auth |
| **Responsive Design** | Works on mobile, tablet, desktop |
| **Dark Glassmorphism UI** | Modern CSS with animated gradients |

---

## ⚡ Quick Start (XAMPP)

### Prerequisites
- [XAMPP](https://www.apachefriends.org/) (Apache + MySQL + PHP 8.0+)
- Git (optional)
- Web browser

### Step 1 — Clone or Download

```bash
# Option A: Clone with Git
git clone https://github.com/YOUR_USERNAME/alain-e-shopping.git

# Option B: Download ZIP from GitHub and extract
```

### Step 2 — Place Files in XAMPP

Copy (or clone) the project folder into your XAMPP `htdocs` directory:

```
C:\xampp\htdocs\alain-e-shopping\
```

> ⚠️ **Important**: The folder name must match! If you use a different name, the app still works — the `BASE_URL` is auto-detected.

### Step 3 — Start XAMPP Services

Open **XAMPP Control Panel** and start:
- ✅ **Apache**
- ✅ **MySQL**

### Step 4 — Database Setup (Auto-seeded!)

The database (`alain_db`) is **automatically created** on first page load. No manual import required.

Simply open your browser and go to:
```
http://localhost/alain-e-shopping/
```

The system will:
1. Create the `alain_db` database
2. Create all tables (users, products, orders, etc.)
3. Insert default admin, seller, and customer accounts
4. Add sample products with reviews

> **Optional**: If you prefer to import manually, run `database/alain_db.sql` in phpMyAdmin.

---

## 🔑 Default Login Credentials

| Role | Email | Password |
|------|-------|----------|
| **Admin** | admin@shopping.com | admin123 |
| **Seller** | seller@shopping.com | seller123 |
| **Customer** | customer@shopping.com | customer123 |

> ⚠️ Change these passwords for any production deployment!

---

## 📁 Project Structure

```
alain-e-shopping/
├── admin/
│   ├── dashboard.php       # Admin overview + seller approval
│   ├── products.php        # Admin product management
│   └── users.php           # Admin user management
├── seller/
│   ├── dashboard.php       # Seller product management
│   ├── orders.php          # Seller incoming orders
│   └── edit-product.php    # Edit a product listing
├── customer/
│   └── dashboard.php       # Order history + invoice download
├── assets/
│   ├── css/style.css       # Main stylesheet (glassmorphism design)
│   ├── js/main.js          # Interactive JS (toasts, animations)
│   └── uploads/            # Product images & seller documents
├── config/
│   └── db.php              # Database connection + auto-setup
├── includes/
│   ├── header.php          # Global navigation + session
│   ├── footer.php          # Footer + JS scripts
│   └── auth.php            # Auth functions + cart helpers
├── database/
│   └── alain_db.sql        # Manual SQL import (optional)
├── index.php               # Homepage
├── shop.php                # Product catalog with filters
├── product-details.php     # Product detail + reviews
├── cart.php                # Shopping cart
├── checkout.php            # Checkout + payment method
├── order-confirmation.php  # Post-order confirmation
├── login.php               # Login page
├── register.php            # Registration (customer/seller)
├── logout.php              # Session destroy
├── about.php               # About us page
└── contact.php             # Contact page
```

---

## 🎨 Design System

- **Theme**: Dark glassmorphism with animated mesh gradients
- **Colors**: Deep space blue (#0f0f23), Cyan (#00d9ff), Purple (#7c3aed), Pink (#ff006e)
- **Fonts**: [Sora](https://fonts.google.com/specimen/Sora) (headings) + [Inter](https://fonts.google.com/specimen/Inter) (body)
- **Framework**: Bootstrap 5 + Bootstrap Icons
- **Effects**: Backdrop blur, 3D card hover, shine animations, scroll fade-ins

---

## 🧪 Testing the Application

### Test Flow (for Presentation)

**1. Public Browsing**
- Visit `http://localhost/alain-e-shopping/`
- Browse the shop, click a product, see reviews

**2. Customer Flow**
- Login: `customer@shopping.com` / `customer123`
- Add items to cart → Checkout → Place order
- View order history → Download invoice

**3. Seller Flow**
- Login: `seller@shopping.com` / `seller123`
- Add a new product
- View incoming orders
- Update order status

**4. Admin Flow**
- Login: `admin@shopping.com` / `admin123`
- View platform stats
- Approve/reject sellers
- Manage all products and users

---

## 🛠️ Tech Stack

| Technology | Purpose |
|-----------|---------|
| PHP 8.0+ | Server-side logic, session management |
| MySQL | Relational database |
| Bootstrap 5 | Responsive CSS grid + components |
| Bootstrap Icons | Icon library |
| Vanilla CSS | Custom glassmorphism design system |
| JavaScript | Toasts, animations, UX interactions |
| XAMPP | Local development server (Apache + MySQL) |

---

## 📊 Database Schema

The following tables are auto-created:

| Table | Description |
|-------|-------------|
| `users` | All users (admin, seller, customer) |
| `categories` | Product categories |
| `products` | Product listings with image/file paths |
| `orders` | Customer orders |
| `order_items` | Items within each order |
| `comments` | Product reviews and ratings |

---

## 🚀 Deployment Notes

For deployment to a live server:
1. Upload all files to your web root (e.g., `public_html/`)
2. Update `config/db.php` with your server's MySQL credentials
3. Import `database/alain_db.sql` via phpMyAdmin
4. Ensure `assets/uploads/` is writable (`chmod 755`)

---

## 📄 License

This project was developed as an academic submission for **Web Design — CAT 2, 2026**.

---

*Built with ❤️ by the Alain E-Shopping Development Team*
