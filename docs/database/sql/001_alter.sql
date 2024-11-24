ALTER TABLE `users`
    ADD COLUMN `role_id` INT AFTER `id`,
    ADD COLUMN `created_at` DATE NULL AFTER `is_active`,
    ADD COLUMN `date_start` DATE NULL AFTER `created_at`,
    ADD COLUMN `date_end` DATE NULL AFTER `date_start`;

CREATE TABLE `roles` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT NULL,
    `url_group_wa` VARCHAR(255) NULL,
    `created_at` DATE NOT NULL,
    `created_by` INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
