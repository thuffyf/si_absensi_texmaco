-- Create `scan_attempts` table if it does not exist
CREATE TABLE IF NOT EXISTS `scan_attempts` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `uid_kartu` VARCHAR(100) NOT NULL,
  `device_id` BIGINT UNSIGNED NULL,
  `status` ENUM('success','already_attended','unregistered','error') NOT NULL DEFAULT 'error',
  `response_message` VARCHAR(255) DEFAULT NULL,
  `scanned_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  INDEX `idx_scan_attempts_uid_kartu` (`uid_kartu`),
  INDEX `idx_scan_attempts_scanned_at` (`scanned_at`),
  INDEX `idx_scan_attempts_status` (`status`),
  CONSTRAINT `fk_scan_attempts_device` FOREIGN KEY (`device_id`) REFERENCES `nfc_devices`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
