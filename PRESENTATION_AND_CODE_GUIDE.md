# 🎓 Alain-e-Shopping: Presentation & Coding Basics Guide

Welcome to your project! This guide is written specifically for you to help you **run the project**, **present it to your staff/assessors**, and **answer coding questions** like a professional—even if you are just starting out with coding.

---

## 🚀 PART 1: How to Setup & Run the Project

Since you already have **VS Code** and **XAMPP** installed, follow these exact steps to run the website on your computer.

### Step 1: Place the Project Folder
Ensure your project files are inside the XAMPP server folder:
* Path: `C:\xampp\htdocs\alain-e-shopping\`
* (If you cloned it using Git, it will already be in this location).

### Step 2: Start XAMPP Control Panel
1. Open the **XAMPP Control Panel** app on your computer.
2. Click the **Start** button next to **Apache** (Web Server).
3. Click the **Start** button next to **MySQL** (Database Server).
4. **Verify**: Both buttons should turn green.

### Step 3: Open in VS Code
1. Open **VS Code**.
2. Click **File** -> **Open Folder**.
3. Choose the `C:\xampp\htdocs\alain-e-shopping` folder.
4. Now you can view all the project files in your left sidebar.

### Step 4: Open in Web Browser
Open your browser (Chrome, Edge, etc.) and type this URL:
```
http://localhost/alain-e-shopping/
```

> ⚡ **The Database Magic**: You do **not** need to create database tables or write SQL commands manually. The code has an **auto-seeding script** built-in. The first time you visit the page, the database (`alain_db`), its tables, and sample products/accounts are automatically created for you!

---

## 🎤 PART 2: Step-by-Step Presentation Script (How to Demo)

When presenting, you want to show that this is a **multi-user system** with different access permissions (Customer, Seller, and Admin). Follow this 10-minute flow to impress your audience.

### 🌟 Introduction (1 Minute)
* **What to say**: *"Good morning/afternoon everyone. Today, I am presenting Alain-e-Shopping, a premium multi-role e-commerce web application. It features a modern 'Glassmorphism' dark UI design built with Bootstrap 5 and custom CSS. The system has three main portals: Customers can browse and purchase, Sellers can manage their own inventory, and Admins supervise the entire platform."*

---

### 🛒 Flow 1: Customer Journey (3 Minutes)
1. **Show the Homepage**: Scroll down to show the smooth animations, hover effects on product cards, and dark theme.
2. **Log In**: Click **Login** and log in as a Customer:
   * **Email**: `customer@shopping.com`
   * **Password**: `customer123`
3. **Browse Catalogue**: Click **Shop Catalogue** in the navigation bar. Show how the filters on the left work (e.g., search by name, filter by category).
4. **View Product Details**: Click on a product (e.g., *Smart Ambient Mood Lamp*). Show the rating stars, seller location coordinates, and description.
5. **Add to Cart**: Change the quantity using the quantity box, then click **Add To Shopping Cart**. Explain that the cart number updates instantly at the top.
6. **Checkout**: Go to the **Cart** page, click **Proceed to Checkout**. Fill in a random delivery address, select **Mobile Money** or **Credit Card**, and click **Place Order**.
7. **Show Invoice**: On the confirmation page, go to **My Account** (Customer Dashboard) and click **Download Invoice** to show the generated billing details.
8. **Leave a Review**: Go back to any product page, scroll down to the bottom, rate it 5 stars, write *"Excellent quality product!"*, and submit it. Show that it immediately posts.
9. **Log Out**: Click **Sign Out** from the user dropdown.

---

### 📦 Flow 2: Seller Journey (3 Minutes)
1. **Log In**: Log in as a Seller:
   * **Email**: `seller@shopping.com`
   * **Password**: `seller123`
2. **Dashboard Overview**: Show the **Seller Center**. This is where the seller sees their listed products and sales stats.
3. **Add a Product**: 
   * Click **Add New Product**.
   * Fill in the form: Name it *"Wireless Cyber Headphones"*, set price to `$150`, set stock to `10`, select a category, and upload a sample image.
   * Click **Add Product** and show that it appears in their inventory immediately.
4. **Seller Orders**: Go to **Seller Orders** in the dropdown. Show that they can see orders placed for their products and update the status (e.g., change from *Pending* to *Processing*).
5. **Log Out**.

---

### 🛡️ Flow 3: Admin Journey & Registration (3 Minutes)
1. **Register a New Seller**:
   * Click **Register** on the homepage.
   * Choose **Seller** profile type.
   * Fill in: Name: *"Gizmo Labs"*, Email: `gizmo@store.com`, Password: `password123`, Location: `Kigali`, and upload a mock document (ID/Business Certificate).
   * Click **Initialize Registration**. Show the warning message: *"Seller registration successful! Admin approval is pending review."*
2. **Admin Controls**:
   * Log out, and log in as Admin:
     * **Email**: `admin@shopping.com`
     * **Password**: `admin123`
   * Navigate to the **Admin Panel**.
   * Show the overview metrics (Total Customers, Sellers, listed products, and total revenue).
   * **Approve the New Seller**: Go to the **Sellers Compliance Desk** section, find *"Gizmo Labs"*, click the uploaded document to review it, and click **Approve**.
   * **Manage Products**: Show that the Admin can inspect all listed products on the platform and delete any item violating policy.
3. **Log Out** to conclude the presentation.

---

## 💡 PART 3: Presentation Questions & Answers (Coding Cheat-Sheet)

If a teacher, panel member, or staff member asks you about the code, here are the most common questions and how to answer them in a simple but tech-savvy way.

### Q1: "What is the tech stack (technologies) used in this project?"
* **Answer**: *"The project is built using native **PHP** for the backend logic and **MySQL** for the database. On the frontend, I used **HTML5** for structure, **CSS3** with custom glassmorphism design variables, and **Bootstrap 5** for responsiveness. **JavaScript** was used for client-side interactivity and toast alerts."*

### Q2: "How does the database connection work, and where is it?"
* **Answer**: *"The database configuration is in **`config/db.php`**. When the website loads, it establishes a connection using PHP's `new mysqli()` class. It automatically runs `CREATE DATABASE IF NOT EXISTS alain_db` and executes queries to set up all necessary tables (users, products, categories, orders, order_items, comments) if they don't already exist. This makes deployment plug-and-play."*

### Q3: "How do you handle user authentication and security?"
* **Answer**: 
  1. *"For passwords, I use **`password_hash()`** with the default bcrypt algorithm when registering, and **`password_verify()`** during login. Plain text passwords are never stored in the database."*
  2. *"To protect against SQL Injection, I use **Prepared Statements** (using `$conn->prepare()` and `$stmt->bind_param()`), which pre-compiles queries and prevents hackers from injecting malicious database code."*

### Q4: "How does the website know who is logged in and what role they have?"
* **Answer**: *"We use **PHP Sessions** (`$_SESSION`). When a user logs in successfully, we store their user ID, name, and role (`customer`, `seller`, or `admin`) in the session. In **`includes/auth.php`**, I wrote a function called `restrict_to_roles()`. We put this at the top of protected pages. If a user tries to access a page they don't have permission for, the code redirects them to their respective dashboard."*

### Q5: "How does the shopping cart save items without database tables?"
* **Answer**: *"The cart is stored directly in the session as an associative array: `$_SESSION['cart']`. The product ID is the key, and the quantity is the value. When a user adds an item, it calls `cart_add($product_id, $qty)` to update the session. When they checkout, the items are read from the session, inserted into the `orders` and `order_items` database tables, and the session cart is cleared using `unset($_SESSION['cart'])`."*

### Q6: "What is Output Buffering (`ob_start()`) and why is it used?"
* **Answer**: *"I implemented **`ob_start()`** at the top of the database configuration file. In PHP, you cannot redirect a user using `header("Location: ...")` if HTML code has already been sent to the browser. Output buffering tells PHP to write all HTML to a memory buffer first. This allows us to perform safe redirects at any point in our code without getting 'headers already sent' warnings."*

---

## 📁 PART 4: Where to Find Key Code Files (Quick Map)

If you are asked to open a specific file in VS Code to explain it:

1. **`config/db.php`** — Database creation, connection, table schemas, and default product seeding.
2. **`includes/auth.php`** — Login helper checks, role restrictions, and shopping cart functions (add, remove, count).
3. **`includes/header.php`** — Navigation bar structure, dropdown links customized by user role.
4. **`assets/css/style.css`** — Design system, colors, glassmorphism cards, responsive forms, scrollbars, and buttons.
5. **`product-details.php`** — Product information, add-to-cart form, and customer review comments database inserts.
6. **`checkout.php`** — Order placement logic using a database transaction (`$conn->begin_transaction()`) to ensure either all database inserts succeed or none do (database safety).
