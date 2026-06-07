# 🚀 Alain E-Shopping Platform - Setup Guide

## 📌 Prerequisites

Before you begin, ensure you have the following installed on your Windows machine:

1. **XAMPP** (or any PHP development environment)
   - Download from: https://www.apachefriends.org/download.html
   - Includes: Apache, MySQL, PHP

2. **Web Browser** (Chrome, Firefox, or Edge)

---

## 🛠️ Installation Steps

### Step 1: Install XAMPP

1. Download XAMPP for Windows
2. Run the installer
3. Install to default location: `C:\xampp`
4. Complete the installation

### Step 2: Start Services

1. Open **XAMPP Control Panel**
2. Click **Start** on **Apache**
3. Click **Start** on **MySQL**
4. Both should show green "Running" status

### Step 3: Setup Project Files

1. Navigate to: `C:\xampp\htdocs\`
2. Create a folder named `neema`
3. Copy all your project files into `C:\xampp\htdocs\neema\`

**Your structure should look like:**
```
C:\xampp\htdocs\neema\
├── admin\
├── assets\
├── config\
├── customer\
├── includes\
├── seller\
├── index.php
├── login.php
├── register.php
└── ... (other files)
```

### Step 4: Database Configuration (Automatic)

**Good news!** The database will be created automatically when you first access the site.

The `config/db.php` file will:
- ✅ Create database named `neema_db`
- ✅ Create all required tables
- ✅ Seed initial data (categories, users, products)
- ✅ Setup default accounts

**Default Login Credentials:**

| Role     | Email                    | Password     |
|----------|--------------------------|--------------|
| Admin    | admin@shopping.com       | admin123     |
| Seller   | seller@shopping.com      | seller123    |
| Customer | customer@shopping.com    | customer123  |

---

## 🌐 Access the Application

### Option 1: Using localhost
```
http://localhost/neema/
```

### Option 2: Using 127.0.0.1
```
http://127.0.0.1/neema/
```

---

## 📱 Key Pages & Features

### Public Pages
- **Homepage**: `http://localhost/neema/index.php`
- **Shop Catalogue**: `http://localhost/neema/shop.php`
- **Product Details**: `http://localhost/neema/product-details.php?id=1`
- **Login**: `http://localhost/neema/login.php`
- **Register**: `http://localhost/neema/register.php`
- **Cart**: `http://localhost/neema/cart.php`
- **Checkout**: `http://localhost/neema/checkout.php`
- **About**: `http://localhost/neema/about.php`
- **Contact**: `http://localhost/neema/contact.php`

### Admin Panel
- Login as Admin: `admin@shopping.com` / `admin123`
- Access: `http://localhost/neema/admin/dashboard.php`
- Features:
  - Approve/Reject Sellers
  - View All Orders
  - Manage Users
  - Platform Statistics

### Seller Dashboard
- Login as Seller: `seller@shopping.com` / `seller123`
- Access: `http://localhost/neema/seller/dashboard.php`
- Features:
  - Add/Edit/Delete Products
  - Upload Product Images
  - Manage Inventory
  - View Orders
  - Download Sales Reports

### Customer Dashboard
- Login as Customer: `customer@shopping.com` / `customer123`
- Access: `http://localhost/neema/customer/dashboard.php`
- Features:
  - View Order History
  - Track Orders
  - Download Digital Products
  - Write Product Reviews
  - Manage Profile

---

## 🔧 Troubleshooting

### Problem 1: "Connection failed" error
**Solution:**
- Make sure Apache and MySQL are running in XAMPP
- Check that MySQL port 3306 is not blocked
- Restart XAMPP services

### Problem 2: "Page not found" (404 error)
**Solution:**
- Verify files are in: `C:\xampp\htdocs\neema\`
- Use correct URL: `http://localhost/neema/`
- Clear browser cache

### Problem 3: Images not showing
**Solution:**
- Check that `assets/uploads/` folder exists
- Verify image files are present in that folder
- Check file permissions

### Problem 4: CSS not loading properly
**Solution:**
- Hard refresh browser: `Ctrl + F5`
- Clear browser cache
- Check that `assets/css/style.css` exists

### Problem 5: Can't login with default credentials
**Solution:**
- Database might not be seeded
- Navigate to: `http://localhost/neema/index.php`
- This triggers automatic database setup
- Try logging in again

---

## 📂 Important File Paths

### Configuration Files
- Database config: `config/db.php`
- Authentication: `includes/auth.php`

### Frontend Files
- Styles: `assets/css/style.css`
- JavaScript: `assets/js/main.js`
- Header: `includes/header.php`
- Footer: `includes/footer.php`

### Upload Directory
- Product images: `assets/uploads/`

---

## 🎨 Frontend Changes

The frontend has been completely redesigned with:
- ✨ Modern glassmorphism UI
- 🎭 Dark theme with gradient accents
- 📱 Fully responsive design
- 🚀 Smooth animations and transitions
- 💎 Premium typography (Outfit + Plus Jakarta Sans)
- 🎯 Enhanced user experience

**All backend functionality remains unchanged!**

---

## 🔐 Security Notes

1. **Change default passwords** after first login
2. **Never expose database credentials** in production
3. **Use HTTPS** in production environments
4. **Regularly backup** your database

---

## 📊 Database Structure

The application uses these main tables:
- `users` - Admin, Sellers, Customers
- `categories` - Product categories
- `products` - Product listings
- `orders` - Customer orders
- `order_items` - Individual order items
- `comments` - Product reviews/ratings

---

## 🎯 Next Steps

1. ✅ Login with default credentials
2. ✅ Explore the redesigned interface
3. ✅ Test all features (add to cart, checkout, etc.)
4. ✅ As seller: Add new products
5. ✅ As customer: Place test orders
6. ✅ As admin: Manage platform

---

## 💡 Additional Resources

- **PHP Documentation**: https://www.php.net/docs.php
- **Bootstrap 5 Docs**: https://getbootstrap.com/docs/5.3/
- **MySQL Documentation**: https://dev.mysql.com/doc/

---

## 🆘 Need Help?

If you encounter any issues:
1. Check the troubleshooting section above
2. Verify XAMPP services are running
3. Check browser console for JavaScript errors (F12)
4. Ensure all files are in correct locations

---

**Happy Shopping! 🛍️**
