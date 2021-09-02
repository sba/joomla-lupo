ALTER TABLE `#__lupo_clients` ADD COLUMN `email` VARCHAR(50) NULL AFTER `lastname`;
ALTER TABLE `#__lupo_clients` ADD COLUMN `phone` VARCHAR(20) NULL AFTER `email`;
