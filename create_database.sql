-- إنشاء قاعدة البيانات للمشروع
-- Taqnia Distribution Manager v4.0

-- إنشاء قاعدة البيانات
CREATE DATABASE IF NOT EXISTS `taqnia-distribution-manager` 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

-- استخدام قاعدة البيانات
USE `taqnia-distribution-manager`;

-- إنشاء مستخدم مخصص للمشروع (اختياري)
-- CREATE USER 'taqnia_user'@'localhost' IDENTIFIED BY 'taqnia_password';
-- GRANT ALL PRIVILEGES ON `taqnia-distribution-manager`.* TO 'taqnia_user'@'localhost';
-- FLUSH PRIVILEGES;

-- عرض الجداول بعد تشغيل الـ migrations
-- SHOW TABLES;

-- التحقق من البيانات الأولية
-- SELECT * FROM users;
-- SELECT * FROM products;
-- SELECT * FROM stores;
-- SELECT * FROM main_stock;