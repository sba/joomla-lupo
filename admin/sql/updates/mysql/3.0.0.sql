ALTER TABLE `#__lupo_game` ADD COLUMN `prolongable` TINYINT DEFAULT 1 NULL AFTER `genres`;
ALTER TABLE `#__lupo_game` ADD INDEX `game_number` (`number`);
ALTER TABLE `#__lupo_categories` ADD COLUMN `samples` VARCHAR(255) DEFAULT '' NOT NULL AFTER `description`;
ALTER TABLE `#__lupo_agecategories` ADD COLUMN `samples` VARCHAR(255) DEFAULT '' NOT NULL AFTER `description`;

CREATE TABLE `#__lupo_clients` (
  `adrnr` int(10) unsigned NOT NULL,
  `username` char(20) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `aboenddat` date DEFAULT NULL,
  `abotype` varchar(40) DEFAULT NULL,
  UNIQUE KEY `adrnr` (`adrnr`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `#__lupo_clients_borrowed` (
  `lupo_id` int(10) unsigned NOT NULL,
  `edition_id` int(11) DEFAULT NULL,
  `adrnr` int(10) unsigned DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `return_date_extended` date DEFAULT NULL,
  `return_extended` tinyint(4) DEFAULT NULL,
  `return_extended_online` tinyint(4) DEFAULT '0',
  `reminder_sent` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;
