DROP TABLE IF EXISTS `#__lupo_agecategories`;
DROP TABLE IF EXISTS `#__lupo_categories`;
DROP TABLE IF EXISTS `#__lupo_genres`;
DROP TABLE IF EXISTS `#__lupo_game`;
DROP TABLE IF EXISTS `#__lupo_game_editions`;
DROP TABLE IF EXISTS `#__lupo_game_documents`;
DROP TABLE IF EXISTS `#__lupo_game_genre`;
DROP TABLE IF EXISTS `#__lupo_game_documents`;
DROP TABLE IF EXISTS `#__lupo_game_related`;


CREATE TABLE `#__lupo_agecategories` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(5120) NOT NULL DEFAULT '',
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `sort` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `#__lupo_categories` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(5120) NOT NULL DEFAULT '',
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `sort` smallint(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `#__lupo_game` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `catid` int(11) DEFAULT NULL,
  `age_catid` int(11) DEFAULT NULL,
  `number` char(10) DEFAULT NULL,
  `title` char(50) DEFAULT NULL,
  `description` text,
  `fabricator` char(50) DEFAULT NULL,
  `days` tinyint(4) DEFAULT NULL,
  `play_duration` char(35) DEFAULT NULL,
  `players` char(30) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT '',
  `genres` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `game_catid` (`catid`),
  KEY `game_age_catid` (`age_catid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `#__lupo_game_editions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gameid` int(11) DEFAULT NULL,
  `index` smallint(6) DEFAULT NULL,
  `edition` varchar(50) DEFAULT NULL,
  `acquired_date` date DEFAULT NULL,
  `tax` double DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `editions_gameid` (`gameid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `#__lupo_game_documents` (
  `id` int NOT NULL AUTO_INCREMENT,
  `gameid` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `type` varchar(20) NOT NULL,
  `desc` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `documents_gameid` (`gameid`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `#__lupo_genres` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `genre` char(30) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `genre` (`genre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `#__lupo_game_genre` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gameid` int(11) NOT NULL,
  `genreid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `#__lupo_game_related` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gameid` int(11) DEFAULT NULL,
  `number` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

