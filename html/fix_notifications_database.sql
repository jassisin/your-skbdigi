-- Simple SQL to add created_at column if it doesn't exist
-- Run this in your database to ensure notifications work properly

-- For MySQL/MariaDB that doesn't support IF NOT EXISTS for columns:
SET @sql = (
    SELECT CASE 
        WHEN COUNT(*) = 0 THEN 'ALTER TABLE tv_dashboard ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;'
        ELSE 'SELECT "Column created_at already exists" as message;'
    END as statement
    FROM information_schema.columns 
    WHERE table_schema = DATABASE() 
    AND table_name = 'tv_dashboard' 
    AND column_name = 'created_at'
);

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add indexes for better performance
CREATE INDEX IF NOT EXISTS idx_tv_dashboard_created_at ON tv_dashboard(created_at);
CREATE INDEX IF NOT EXISTS idx_tv_dashboard_status ON tv_dashboard(status);
CREATE INDEX IF NOT EXISTS idx_tv_dashboard_status_created ON tv_dashboard(status, created_at);

-- Update existing records to have created_at value
UPDATE tv_dashboard SET created_at = created_date WHERE created_at IS NULL OR created_at = '0000-00-00 00:00:00';

-- Show table structure to verify
SHOW COLUMNS FROM tv_dashboard;
