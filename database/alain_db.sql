-- ====================================================================
-- Alain E-Shopping Platform - Database SQL Script
-- Database: alain_db
-- Date: June 4, 2026
-- ====================================================================

-- Create Database
CREATE DATABASE IF NOT EXISTS `alain_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `alain_db`;

-- ====================================================================
-- TABLE STRUCTURE
-- ====================================================================

-- 1. Users Table (Admin, Sellers, Customers)
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('admin', 'seller', 'customer') NOT NULL,
    `status` ENUM('pending', 'approved', 'rejected') DEFAULT 'approved',
    `seller_location` VARCHAR(255) DEFAULT NULL,
    `seller_documents` VARCHAR(255) DEFAULT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_email` (`email`),
    INDEX `idx_role` (`role`),
    INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Categories Table
CREATE TABLE IF NOT EXISTS `categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL UNIQUE,
    `icon` VARCHAR(100) DEFAULT 'bi-box',
    INDEX `idx_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Products Table
CREATE TABLE IF NOT EXISTS `products` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `seller_id` INT NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT NOT NULL,
    `price` DECIMAL(10, 2) NOT NULL,
    `category_id` INT NULL,
    `image_url` VARCHAR(255) NOT NULL,
    `file_url` VARCHAR(255) DEFAULT NULL,
    `stock` INT DEFAULT 10,
    `is_digital` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`seller_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL,
    INDEX `idx_seller` (`seller_id`),
    INDEX `idx_category` (`category_id`),
    INDEX `idx_price` (`price`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Orders Table
CREATE TABLE IF NOT EXISTS `orders` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `customer_id` INT NOT NULL,
    `total_amount` DECIMAL(10, 2) NOT NULL,
    `status` ENUM('pending', 'processing', 'completed', 'cancelled') DEFAULT 'pending',
    `shipping_address` TEXT NOT NULL,
    `payment_method` VARCHAR(100) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`customer_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    INDEX `idx_customer` (`customer_id`),
    INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Order Items Table
CREATE TABLE IF NOT EXISTS `order_items` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `order_id` INT NOT NULL,
    `product_id` INT NOT NULL,
    `price` DECIMAL(10, 2) NOT NULL,
    `quantity` INT NOT NULL,
    FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE,
    INDEX `idx_order` (`order_id`),
    INDEX `idx_product` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Comments/Reviews Table
CREATE TABLE IF NOT EXISTS `comments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `product_id` INT NOT NULL,
    `customer_id` INT NOT NULL,
    `rating` INT NOT NULL CHECK (`rating` BETWEEN 1 AND 5),
    `comment` TEXT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`customer_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    INDEX `idx_product` (`product_id`),
    INDEX `idx_customer` (`customer_id`),
    INDEX `idx_rating` (`rating`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ====================================================================
-- SEED DATA - Initial Categories
-- ====================================================================

INSERT INTO `categories` (`name`, `icon`) VALUES
('Electronics', 'bi-laptop'),
('Fashion & Apparel', 'bi-sunglasses'),
('Digital Products', 'bi-cloud-download'),
('Home & Living', 'bi-house-heart'),
('Books & Media', 'bi-book');

-- ====================================================================
-- SEED DATA - Default Users
-- ====================================================================

-- NOTE: The config/db.php auto-seeder uses PHP's password_hash() which generates
-- correct hashes. If you use THIS SQL file, use the hashes below.
-- These are bcrypt hashes for: admin123, seller123, customer123
-- Generated with PASSWORD_DEFAULT (bcrypt)

-- Admin: password = admin123
INSERT INTO `users` (`name`, `email`, `password`, `role`, `status`) VALUES
('System Admin', 'admin@shopping.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77bab2', 'admin', 'approved');

-- Seller: password = seller123
INSERT INTO `users` (`name`, `email`, `password`, `role`, `status`, `seller_location`) VALUES
('Apex Digital Store', 'seller@shopping.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77bab2', 'seller', 'approved', 'Kigali, Rwanda');

-- Customer: password = customer123
INSERT INTO `users` (`name`, `email`, `password`, `role`, `status`) VALUES
('John Doe', 'customer@shopping.com', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77bab2', 'customer', 'approved');

-- ⚠️ IMPORTANT: The above bcrypt hash was generated as a placeholder.
-- Use config/db.php auto-seeding instead (recommended) which generates
-- correct hashes via PHP's password_hash() function.
-- Default: admin@shopping.com / admin123 | seller@shopping.com / seller123 | customer@shopping.com / customer123

-- ====================================================================
-- SEED DATA - Sample Products
-- ====================================================================

-- Get the seller ID (should be 2 from the insert above)
SET @seller_id = 2;
SET @customer_id = 3;

-- Product 1: Laptop Stand
INSERT INTO `products` (`seller_id`, `name`, `description`, `price`, `category_id`, `image_url`, `file_url`, `stock`, `is_digital`) VALUES
(@seller_id, 'Premium Cyberpunk Laptop Stand', 'A heavy-duty aluminum laptop stand featuring built-in RGB lighting, dynamic angle adjustments, and ergonomic cable routing holes. The perfect additions for power-users.', 79.99, 1, 'assets/uploads/laptop_stand.svg', NULL, 15, 0);

-- Product 2: Hoodie
INSERT INTO `products` (`seller_id`, `name`, `description`, `price`, `category_id`, `image_url`, `file_url`, `stock`, `is_digital`) VALUES
(@seller_id, 'Futuristic Neon Hoodie', 'High-comfort water-resistant cyber hoodie with luminous glowing neon stripes along the sleeves. Standard streetwear sizing with breathable cotton-mesh fabric.', 120.00, 2, 'assets/uploads/hoodie.svg', NULL, 20, 0);

-- Product 3: Icon Kit (Digital)
INSERT INTO `products` (`seller_id`, `name`, `description`, `price`, `category_id`, `image_url`, `file_url`, `stock`, `is_digital`) VALUES
(@seller_id, 'Complete UI Icon Kit (Digital)', 'A pack of 500+ premium dynamic UI vector icons for professional developers. Includes raw SVG, Figma, and fully layered EPS resource formats for immediate download.', 24.50, 3, 'assets/uploads/icon_kit.svg', 'assets/uploads/icon_kit_v1.zip', 999, 1);

-- Product 4: Mood Lamp
INSERT INTO `products` (`seller_id`, `name`, `description`, `price`, `category_id`, `image_url`, `file_url`, `stock`, `is_digital`) VALUES
(@seller_id, 'Smart Ambient Mood Lamp', 'Wifi-enabled sleek ambient mood sphere with responsive sound-active music modes. Fits on bedside tables or desktops for high-end office visual comfort.', 45.00, 4, 'assets/uploads/mood_lamp.svg', NULL, 8, 0);

-- ====================================================================
-- SEED DATA - Sample Reviews
-- ====================================================================

-- Add reviews for each product
INSERT INTO `comments` (`product_id`, `customer_id`, `rating`, `comment`) VALUES
(1, @customer_id, 5, 'Absolutely stunning product! Exceeded my expectations. The quality is second to none.'),
(2, @customer_id, 5, 'Absolutely stunning product! Exceeded my expectations. The quality is second to none.'),
(3, @customer_id, 5, 'Absolutely stunning product! Exceeded my expectations. The quality is second to none.'),
(4, @customer_id, 5, 'Absolutely stunning product! Exceeded my expectations. The quality is second to none.');

-- ====================================================================
-- END OF SQL SCRIPT
-- ====================================================================

-- ====================================================================
-- DEFAULT LOGIN CREDENTIALS
-- ====================================================================
-- 
-- Admin:
--   Email: admin@shopping.com
--   Password: admin123
--
-- Seller:
--   Email: seller@shopping.com
--   Password: seller123
--
-- Customer:
--   Email: customer@shopping.com
--   Password: customer123
--
-- ⚠️ IMPORTANT: Change these passwords in production!
-- ====================================================================
