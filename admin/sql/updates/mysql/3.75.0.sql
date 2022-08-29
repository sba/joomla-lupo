CREATE TABLE `#__lupo_reservations_web` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `game_number` char(10) NOT NULL,
  `adrnr` int(10) unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;
