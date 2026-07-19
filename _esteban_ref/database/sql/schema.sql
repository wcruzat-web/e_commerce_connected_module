-- ============================================================
-- Database Schema for Product Display Management System
-- Import this file via phpMyAdmin > Import
-- ============================================================

CREATE DATABASE IF NOT EXISTS `abcd` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `abcd`;

-- --------------------------------------------------------
-- 1. product_table
-- --------------------------------------------------------
CREATE TABLE `product_table` (
  `product_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_name` VARCHAR(150) NOT NULL,
  `sku` VARCHAR(100) NOT NULL,
  `brand` VARCHAR(100) NOT NULL,
  `category` VARCHAR(100) NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `stock` INT NOT NULL DEFAULT 0,
  `product_image` VARCHAR(255) DEFAULT NULL,
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`product_id`),
  UNIQUE KEY `sku` (`sku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 2. product_specification
-- --------------------------------------------------------
CREATE TABLE `product_specification` (
  `spec_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` BIGINT UNSIGNED NOT NULL,
  `category_name` VARCHAR(100) NOT NULL,
  `attribute_name` VARCHAR(100) NOT NULL,
  `attribute_value` VARCHAR(100) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`spec_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `fk_spec_product` FOREIGN KEY (`product_id`) REFERENCES `product_table` (`product_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 3. product_compatibility
-- --------------------------------------------------------
CREATE TABLE `product_compatibility` (
  `compatibility_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` BIGINT UNSIGNED NOT NULL,
  `category_name` VARCHAR(100) NOT NULL,
  `item_name` VARCHAR(150) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`compatibility_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `fk_compat_product` FOREIGN KEY (`product_id`) REFERENCES `product_table` (`product_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 4. product_reviews
-- --------------------------------------------------------
CREATE TABLE `product_reviews` (
  `review_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` BIGINT UNSIGNED NOT NULL,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `comment` TEXT NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`review_id`),
  KEY `product_id` (`product_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `fk_review_product` FOREIGN KEY (`product_id`) REFERENCES `product_table` (`product_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_review_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 5. warehouses
-- --------------------------------------------------------
CREATE TABLE `warehouses` (
  `warehouse_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `warehouse_name` VARCHAR(100) NOT NULL,
  `location` VARCHAR(100) NOT NULL,
  `sync_status` ENUM('Synced','Pending','Error') NOT NULL DEFAULT 'Pending',
  `last_sync_at` DATETIME DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`warehouse_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 6. warehouse_stock
-- --------------------------------------------------------
CREATE TABLE `warehouse_stock` (
  `warehouse_stock_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `warehouse_id` BIGINT UNSIGNED NOT NULL,
  `product_id` BIGINT UNSIGNED NOT NULL,
  `quantity` INT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`warehouse_stock_id`),
  KEY `warehouse_id` (`warehouse_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `fk_ws_warehouse` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`warehouse_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_ws_product` FOREIGN KEY (`product_id`) REFERENCES `product_table` (`product_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 7. promo_banners
-- --------------------------------------------------------
CREATE TABLE `promo_banners` (
  `banner_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(100) NOT NULL,
  `subtitle` VARCHAR(100) NOT NULL DEFAULT '',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`banner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 8. revenue_overview
-- --------------------------------------------------------
CREATE TABLE `revenue_overview` (
  `revenue_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `month_label` VARCHAR(20) NOT NULL,
  `overview_year` YEAR NOT NULL,
  `revenue_amount` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`revenue_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- SEED DATA
-- ============================================================

-- Products
INSERT INTO `product_table` (`product_id`, `product_name`, `sku`, `brand`, `category`, `price`, `stock`, `is_featured`) VALUES
(1, 'NVIDIA RTX 4090 Founder Edition', 'NV-4090-FE', 'NVIDIA', 'GPU', 1599.00, 2, 1),
(2, 'Intel Core i9-14900K Processor', 'IN-14900K', 'Intel', 'CPU', 589.00, 7, 1),
(3, 'ASUS ROG Maximus Z790 Hero', 'AS-Z7090-MX', 'ASUS', 'Motherboard', 699.00, 4, 0),
(4, 'Corsair Vengeance DDR5 64GB', 'CO-DDR5-64', 'Corsair', 'Memory', 189.00, 11, 0),
(5, 'NZXT Kraken 360 AIO Cooler', 'NZ-KR360', 'NZXT', 'Cooling', 159.00, 18, 0),
(6, 'AMD Ryzen 9 7950X Processor', 'AM-7950X', 'AMD', 'CPU', 549.00, 9, 1),
(7, 'Samsung 990 Pro NVMe 2TB', 'SM-990P-2T', 'Samsung', 'Memory', 179.00, 0, 0),
(8, 'MSI Suprim X RTX 4080', 'MS-4080-SX', 'MSI', 'GPU', 1199.00, 3, 0);

-- Promo Banners
INSERT INTO `promo_banners` (`banner_id`, `title`, `subtitle`, `is_active`) VALUES
(1, 'Summer GPU Sale', '15% OFF', 1);

-- Warehouses
INSERT INTO `warehouses` (`warehouse_id`, `warehouse_name`, `location`, `sync_status`, `last_sync_at`) VALUES
(1, 'Warehouse A - Singapore', 'Singapore', 'Synced', NOW()),
(2, 'Warehouse B - Manila', 'Manila', 'Synced', NOW()),
(3, 'Warehouse C - Jakarta', 'Jakarta', 'Pending', DATE_SUB(NOW(), INTERVAL 20 MINUTE)),
(4, 'ERP Central Hub', 'Central', 'Synced', NOW());

-- Revenue Overview
INSERT INTO `revenue_overview` (`revenue_id`, `month_label`, `overview_year`, `revenue_amount`) VALUES
(1, 'July', 2025, 15200.00),
(2, 'August', 2025, 17400.00),
(3, 'September', 2025, 19800.00),
(4, 'October', 2025, 18100.00),
(5, 'November', 2025, 17600.00),
(6, 'December', 2025, 19000.00);
