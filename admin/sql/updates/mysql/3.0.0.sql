ALTER TABLE `joomla`.`jom_lupo_game` ADD COLUMN `prolongable` TINYINT DEFAULT 1 NULL AFTER `genres`;

CREATE TABLE `jom_lupo_clients` (
  `adrnr` int(10) unsigned NOT NULL,
  `username` char(20) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `aboenddat` date DEFAULT NULL,
  `abotype` varchar(40) DEFAULT NULL,
  UNIQUE KEY `adrnr` (`adrnr`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `jom_lupo_clients_borrowed` (
  `lupo_id` int(10) unsigned NOT NULL,
  `edition_id` int(11) DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `return_extended` tinyint(4) DEFAULT NULL,
  `return_extended_online` tinyint(4) DEFAULT '0',
  `adrnr` int(10) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
