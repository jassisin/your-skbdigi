-- Add the missing created_at column to tv_dashboard table
ALTER TABLE `tv_dashboard` ADD COLUMN `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- Update existing records to have created_at value based on created_date
UPDATE `tv_dashboard` SET `created_at` = `created_date` WHERE `created_at` IS NULL;

-- Add indexes for better performance
CREATE INDEX `idx_tv_dashboard_created_at` ON `tv_dashboard`(`created_at`);
CREATE INDEX `idx_tv_dashboard_status` ON `tv_dashboard`(`status`);
CREATE INDEX `idx_tv_dashboard_status_created` ON `tv_dashboard`(`status`, `created_at`);

-- Show the updated table structure
DESCRIBE `tv_dashboard`;
