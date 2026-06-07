# 🚀 Complete Installation Guide - Alain E-Shopping Platform

## Step-by-Step Setup for XAMPP on Windows

---

## 📋 Prerequisites

✅ **XAMPP** installed and running  
✅ **Apache** and **MySQL** services started  
✅ Project files in `s:\Alain-e-shopping\`

---

## 🎯 OPTION 1: Automatic Database Setup (Recommended)

The application will **automatically create the database** on first run. This is the easiest method!

### Step 1: Copy Project Files

1. Navigate to `C:\xampp\htdocs\`
2. Create a new folder called `neema`
3. Copy all files from `s:\Alain-e-shopping\` to `C:\xampp\htdocs\neema\`

**Your folder structure should be:**
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
└── ... (all other files)
```

### Step 2: Start XAMPP Services

1. Open **XAMPP Control Panel**
2. Click **Start** next to **Apache** (should turn green)
3. Click **Start** next to **MySQL** (should turn green)

### Step 3: Access Your Application

Open your web browser and go to:
```
http://localhost/neema/
```

**That's it!** The database will be created automatically on first visit. ✨

---

## 🎯 OPTION 2: Manual Database Import

If you prefer to manually create the database, follow these steps:

### Step 1: Copy Project Files

Same as Option 1 - copy all files to `C:\xampp\htdocs\neema\`

### Step 2: Import SQL File

#### Method A: Using phpMyAdmin (Easy)

1. Open your browser and go to: `http://localhost/phpmyadmin/`
2. Click on **"New"** in the left sidebar
3. Database name: `neema_db`
4. Collation: `utf8mb4_unicode_ci`
5. Click **"Create"**
6. Click on the `neema_db` database you just created
7. Click on **"Import"** tab at the top
8. Click **"Choose File"**
9. Select: `s:\Alain-e-shopping\database\neema_db.sql`
10. Scroll down and click **"Import"** button
11. Wait for success message ✅

#### Method B: Using MySQL Command Line

1. Open **Command Prompt** (CMD)
2. Navigate to XAMPP MySQL bin folder:
   ```cmd
   cd C:\xampp\mysql\bin
   ```
3. Login to MySQL:
   ```cmd
   mysql -u root -p
   ```
   (Press Enter when asked for password - default is empty)
4. Import the SQL file:
   ```sql
   source s:\Alain-e-shopping\database\neema_db.sql
   ```
5. Verify database was created:
   ```sql
   SHOW DATABASES;
   USE neema_db;
   SHOW TABLES;
   ```
6. Exit MySQL:
   ```sql
   exit;
   ```

### Step 3: Update Database Configuration (if needed)

Open `C:\xampp\htdocs\neema\config\db.php` and verify these settings:

```php
$host = 'localhost';
$user = 'root';
$pass = '';  // Default XAMPP password is empty
$db_name = 'neema_db';
```

### Step 4: Access Your Application

Open browser: `http://localhost/neema/`

---

## 🔑 Default Login Credentials

| Role     | Email                   | Password    | Dashboard URL                              |
|----------|-------------------------|-------------|--------------------------------------------|
| Admin    | admin@shopping.com      | admin123    | http://localhost/neema/admin/dashboard.php |
| Seller   | seller@shopping.com     | seller123   | http://localhost/neema/seller/dashboard.php|
| Customer | customer@shopping.com   | customer123 | http://localhost/neema/customer/dashboard.php|

---

## 📱 Application URLs

### Public Pages
- **Homepage**: http://localhost/neema/index.php
- **Shop**: http://localhost/neema/shop.php
- **Cart**: http://localhost/neema/cart.php
- **Login**: http://localhost/neema/login.php
- **Register**: http://localhost/neema/register.php
- **About**: http://localhost/neema/about.php
- **Contact**: http://localhost/neema/contact.php

### Product Pages
- **Product Details**: http://localhost/neema/product-details.php?id=1
- **Checkout**: http://localhost/neema/checkout.php
- **Order Confirmation**: http://localhost/neema/order-confirmation.php

---

## ✅ Verification Checklist

After installation, verify everything works:

### 1. Check Database
- [ ] Open phpMyAdmin: `http://localhost/phpmyadmin/`
- [ ] Database `neema_db` exists
- [ ] Tables are created (users, products, orders, etc.)
- [ ] Sample data is loaded

### 2. Test Frontend
- [ ] Homepage loads correctly
- [ ] Shop page shows products
- [ ] Product details page works
- [ ] Images display properly
- [ ] Navigation menu works

### 3. Test Authentication
- [ ] Login page loads
- [ ] Can login as Admin
- [ ] Can login as Seller
- [ ] Can login as Customer
- [ ] Logout works

### 4. Test Features
- [ ] Add product to cart
- [ ] Update cart quantities
- [ ] View cart
- [ ] Checkout process
- [ ] View order history

---

## 🛠️ Troubleshooting

### Problem 1: "Can't connect to database"
**Solutions:**
- Check if MySQL is running in XAMPP Control Panel
- Verify MySQL is using port 3306 (default)
- Check `config/db.php` credentials are correct
- Try stopping and starting MySQL service

### Problem 2: "Access denied for user 'root'@'localhost'"
**Solutions:**
- Default XAMPP password is empty (`''`)
- If you set a password, update `config/db.php`:
  ```php
  $pass = 'your_password_here';
  ```

### Problem 3: Page shows "404 Not Found"
**Solutions:**
- Verify files are in `C:\xampp\htdocs\neema\`
- Check URL is correct: `http://localhost/neema/`
- Make sure Apache is running

### Problem 4: Images not displaying
**Solutions:**
- Check `assets/uploads/` folder exists
- Verify image files are present:
  - hoodie.svg
  - laptop_stand.svg
  - mood_lamp.svg
  - icon_kit.svg
- Check file permissions

### Problem 5: CSS not loading (page looks unstyled)
**Solutions:**
- Hard refresh browser: `Ctrl + Shift + R` or `Ctrl + F5`
- Clear browser cache
- Check `assets/css/style.css` exists
- Check browser console (F12) for errors

### Problem 6: "Table doesn't exist" error
**Solutions:**
- Database wasn't created properly
- Re-import SQL file using phpMyAdmin
- Or delete database and revisit homepage to trigger auto-creation

### Problem 7: XAMPP ports already in use
**Solutions:**
- Apache (Port 80): Change to 8080 in XAMPP config
  - Access as: `http://localhost:8080/neema/`
- MySQL (Port 3306): Change to 3307 in XAMPP config
  - Update `config/db.php` host to `localhost:3307`

---

## 🔧 Advanced Configuration

### Change Project Folder Name

If you want to use a different folder name (not `neema`):

1. Copy files to: `C:\xampp\htdocs\your_folder_name\`
2. Access as: `http://localhost/your_folder_name/`
3. No code changes needed!

### Set Custom MySQL Password

1. Open phpMyAdmin: `http://localhost/phpmyadmin/`
2. Click "User accounts" tab
3. Click "Edit privileges" for `root` user
4. Click "Change password"
5. Set new password
6. Update `config/db.php`:
   ```php
   $pass = 'your_new_password';
   ```

### Enable Error Reporting (for debugging)

Add to the top of `index.php` temporarily:
```php
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
```

**Remove this in production!**

---

## 📊 Database Structure

### Tables Created
1. **users** (4 columns) - Admin, sellers, customers
2. **categories** (5 rows) - Product categories
3. **products** (4 sample products) - Product listings
4. **orders** - Customer orders
5. **order_items** - Order line items
6. **comments** (4 reviews) - Product reviews/ratings

### Sample Data Included
- ✅ 1 Admin account
- ✅ 1 Seller account (Apex Digital Store)
- ✅ 1 Customer account (John Doe)
- ✅ 5 Product categories
- ✅ 4 Sample products with images
- ✅ 4 Sample product reviews

---

## 🚀 Quick Start Commands

### Start XAMPP Services
```cmd
cd C:\xampp
xampp-control.exe
```

### Access phpMyAdmin
```
http://localhost/phpmyadmin/
```

### Access Application
```
http://localhost/neema/
```

### View Apache Error Logs (if issues)
```
C:\xampp\apache\logs\error.log
```

### View MySQL Error Logs (if issues)
```
C:\xampp\mysql\data\mysql_error.log
```

---

## 🎯 Next Steps After Installation

1. **Login as Admin**
   - Go to: http://localhost/neema/login.php
   - Email: admin@shopping.com
   - Password: admin123
   - Explore admin dashboard

2. **Login as Seller**
   - Email: seller@shopping.com
   - Password: seller123
   - Try adding a new product

3. **Login as Customer**
   - Email: customer@shopping.com
   - Password: customer123
   - Browse products and place a test order

4. **Test Complete Flow**
   - Browse products
   - Add to cart
   - Complete checkout
   - Check order history

5. **Customize**
   - Change default passwords
   - Add more products
   - Upload your own images
   - Customize styling

---

## 📁 Important Files Reference

### Configuration
- `config/db.php` - Database connection settings
- `includes/auth.php` - Authentication logic

### Frontend
- `assets/css/style.css` - Modern styling
- `assets/js/main.js` - JavaScript functionality
- `includes/header.php` - Navigation header
- `includes/footer.php` - Footer

### Admin
- `admin/dashboard.php` - Admin control panel

### Seller
- `seller/dashboard.php` - Seller dashboard
- `seller/edit-product.php` - Product management
- `seller/orders.php` - Seller orders

### Customer
- `customer/dashboard.php` - Customer account

---

## 🔒 Security Recommendations

1. **Change default passwords** immediately after installation
2. **Never use default credentials** in production
3. **Set proper file permissions** on uploads folder
4. **Enable HTTPS** in production
5. **Regular backups** of database
6. **Update PHP** to latest version
7. **Remove or protect** phpMyAdmin in production

---

## 💾 Backup Your Database

### Using phpMyAdmin
1. Go to: http://localhost/phpmyadmin/
2. Click on `neema_db`
3. Click "Export" tab
4. Select "Quick" method
5. Format: SQL
6. Click "Export" button
7. Save the .sql file

### Using Command Line
```cmd
cd C:\xampp\mysql\bin
mysqldump -u root neema_db > backup.sql
```

---

## 📞 Getting Help

If you encounter issues:

1. **Check Troubleshooting section** above
2. **View browser console** (Press F12)
3. **Check Apache error logs** at `C:\xampp\apache\logs\error.log`
4. **Verify all XAMPP services** are running
5. **Test with a simple PHP file** to ensure PHP is working:
   - Create `test.php` with: `<?php phpinfo(); ?>`
   - Access: `http://localhost/neema/test.php`

---

## ✨ Success!

If you can see the homepage with products displaying correctly, you're all set! 🎉

**Enjoy your modern e-shopping platform!** 🛍️

---

**Last Updated**: June 4, 2026  
**Version**: 2.0
