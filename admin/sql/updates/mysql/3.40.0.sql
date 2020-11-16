ALTER TABLE `#__lupo_clients_borrowed` ADD COLUMN `quarantine` TINYINT DEFAULT 0 NULL AFTER `next_reservation`;
