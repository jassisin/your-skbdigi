-- Add created_at column to tv_dashboard table for notification system
-- This column will track when a patient status was last updated

-- Check if created_at column exists, if not add it
ALTER TABLE `tv_dashboard` 
ADD COLUMN IF NOT EXISTS `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- Update existing records to have a created_at timestamp
UPDATE `tv_dashboard` 
SET `created_at` = `created_date` 
WHERE `created_at` IS NULL OR `created_at` = '0000-00-00 00:00:00';

-- Add index for better performance on notification queries
ALTER TABLE `tv_dashboard` 
ADD INDEX IF NOT EXISTS `idx_created_at` (`created_at`);

-- Add index for status-based queries
ALTER TABLE `tv_dashboard` 
ADD INDEX IF NOT EXISTS `idx_status` (`status`);

-- Add composite index for notification queries
ALTER TABLE `tv_dashboard` 
ADD INDEX IF NOT EXISTS `idx_status_created` (`status`, `created_at`);
