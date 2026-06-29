-- Performance Optimization SQL Queries
-- Run these queries directly in phpMyAdmin

-- ============================================
-- CARS TABLE INDEXES
-- ============================================

-- Composite index for status, approval and expiration queries
ALTER TABLE `cars` ADD INDEX `cars_status_approved_expired` (`status`, `approved_by_admin`, `expired_date`);

-- Composite index for condition-based queries
ALTER TABLE `cars` ADD INDEX `cars_condition_status_approved` (`condition`, `status`, `approved_by_admin`);

-- Composite index for featured cars
ALTER TABLE `cars` ADD INDEX `cars_featured_status_approved` (`is_featured`, `status`, `approved_by_admin`);

-- Index for brand filtering
ALTER TABLE `cars` ADD INDEX `cars_brand_status` (`brand_id`, `status`);

-- Index for dealer filtering
ALTER TABLE `cars` ADD INDEX `cars_dealer_status` (`dealer_id`, `status`);

-- Individual indexes for year filtering
ALTER TABLE `cars` ADD INDEX `cars_year` (`year`);
ALTER TABLE `cars` ADD INDEX `cars_from_year` (`from_year`);
ALTER TABLE `cars` ADD INDEX `cars_to_year` (`to_year`);
ALTER TABLE `cars` ADD INDEX `cars_motorcheck_year` (`motorcheck_year`);

-- Indexes for numeric filtering
ALTER TABLE `cars` ADD INDEX `cars_mileage` (`mileage`);
ALTER TABLE `cars` ADD INDEX `cars_regular_price` (`regular_price`);
ALTER TABLE `cars` ADD INDEX `cars_offer_price` (`offer_price`);

-- Index for JSON field (MySQL 5.7+)
ALTER TABLE `cars` ADD INDEX `cars_motorcheck_raw_index` ((CAST(`motorcheck_raw` AS CHAR(255))));

-- ============================================
-- CAR_PARTS TABLE INDEXES
-- ============================================

-- Composite index for status, approval and expiration
ALTER TABLE `car_parts` ADD INDEX `parts_status_approved_expired` (`status`, `approved_by_admin`, `expired_date`);

-- Index for brand filtering
ALTER TABLE `car_parts` ADD INDEX `parts_brand_status` (`brand_id`, `status`);

-- Individual indexes for year filtering
ALTER TABLE `car_parts` ADD INDEX `parts_year` (`year`);
ALTER TABLE `car_parts` ADD INDEX `parts_from_year` (`from_year`);
ALTER TABLE `car_parts` ADD INDEX `parts_to_year` (`to_year`);

-- ============================================
-- USERS TABLE INDEXES
-- ============================================

-- Composite index for dealer queries
ALTER TABLE `users` ADD INDEX `users_status_banned_dealer` (`status`, `is_banned`, `is_dealer`);

-- Index for email verification
ALTER TABLE `users` ADD INDEX `users_email_verified` (`email_verified_at`);

-- ============================================
-- BLOGS TABLE INDEXES
-- ============================================

-- Composite index for blog queries
ALTER TABLE `blogs` ADD INDEX `blogs_status_created` (`status`, `created_at`);

-- ============================================
-- BRANDS TABLE INDEXES
-- ============================================

-- Composite index for brand queries
ALTER TABLE `brands` ADD INDEX `brands_status_name` (`status`, `name`);

-- ============================================
-- ADDITIONAL OPTIMIZATION QUERIES
-- ============================================

-- Analyze tables to update index statistics
ANALYZE TABLE `cars`;
ANALYZE TABLE `car_parts`;
ANALYZE TABLE `users`;
ANALYZE TABLE `blogs`;
ANALYZE TABLE `brands`;

-- Check existing indexes (run this to see current indexes)
SHOW INDEX FROM `cars`;
SHOW INDEX FROM `car_parts`;
SHOW INDEX FROM `users`;
SHOW INDEX FROM `blogs`;
SHOW INDEX FROM `brands`;

-- ============================================
-- PERFORMANCE VERIFICATION QUERIES
-- ============================================

-- Test query performance (run before and after indexes)
EXPLAIN SELECT * FROM `cars` 
WHERE `status` = 'enable' 
AND `approved_by_admin` = 'approved' 
AND (`expired_date` IS NULL OR `expired_date` >= CURDATE())
ORDER BY `created_at` DESC 
LIMIT 8;

EXPLAIN SELECT * FROM `car_parts` 
WHERE `status` = 'enable' 
AND `approved_by_admin` = 'approved' 
AND (`expired_date` IS NULL OR `expired_date` >= CURDATE())
ORDER BY `created_at` DESC 
LIMIT 8;

-- ============================================
-- CLEANUP QUERIES (if needed)
-- ============================================

-- To drop indexes if you need to recreate them
-- ALTER TABLE `cars` DROP INDEX `cars_status_approved_expired`;
-- ALTER TABLE `cars` DROP INDEX `cars_condition_status_approved`;
-- ALTER TABLE `cars` DROP INDEX `cars_featured_status_approved`;
-- ALTER TABLE `cars` DROP INDEX `cars_brand_status`;
-- ALTER TABLE `cars` DROP INDEX `cars_dealer_status`;
-- ALTER TABLE `cars` DROP INDEX `cars_year`;
-- ALTER TABLE `cars` DROP INDEX `cars_from_year`;
-- ALTER TABLE `cars` DROP INDEX `cars_to_year`;
-- ALTER TABLE `cars` DROP INDEX `cars_motorcheck_year`;
-- ALTER TABLE `cars` DROP INDEX `cars_mileage`;
-- ALTER TABLE `cars` DROP INDEX `cars_regular_price`;
-- ALTER TABLE `cars` DROP INDEX `cars_offer_price`;
-- ALTER TABLE `cars` DROP INDEX `cars_motorcheck_raw_index`;

-- ALTER TABLE `car_parts` DROP INDEX `parts_status_approved_expired`;
-- ALTER TABLE `car_parts` DROP INDEX `parts_brand_status`;
-- ALTER TABLE `car_parts` DROP INDEX `parts_year`;
-- ALTER TABLE `car_parts` DROP INDEX `parts_from_year`;
-- ALTER TABLE `car_parts` DROP INDEX `parts_to_year`;

-- ALTER TABLE `users` DROP INDEX `users_status_banned_dealer`;
-- ALTER TABLE `users` DROP INDEX `users_email_verified`;

-- ALTER TABLE `blogs` DROP INDEX `blogs_status_created`;
-- ALTER TABLE `brands` DROP INDEX `brands_status_name`;
