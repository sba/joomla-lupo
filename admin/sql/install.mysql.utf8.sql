DROP TABLE IF EXISTS `#__lupo_agecategories`;
DROP TABLE IF EXISTS `#__lupo_categories`;
DROP TABLE IF EXISTS `#__lupo_clients`;
DROP TABLE IF EXISTS `#__lupo_clients_borrowed`;
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
  `samples` varchar(255) NOT NULL DEFAULT '',
  `age_number` varchar(20) DEFAULT NULL,
  `subsets` text DEFAULT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `sort` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `#__lupo_categories` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(5120) NOT NULL DEFAULT '',
  `samples` varchar(255) NOT NULL DEFAULT '',
  `subsets` text DEFAULT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `sort` smallint(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `#__lupo_clients` (
  `adrnr` int(10) unsigned NOT NULL,
  `username` char(20) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `aboenddat` date DEFAULT NULL,
  `abotype` varchar(40) DEFAULT NULL,
  UNIQUE KEY `adrnr` (`adrnr`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `#__lupo_clients_borrowed` (
  `lupo_id` int(10) unsigned NOT NULL,
  `edition_id` int(11) DEFAULT NULL,
  `game_number` char(10) DEFAULT NULL,
  `adrnr` int(10) unsigned DEFAULT NULL,
  `tax_extended` float DEFAULT '0',
  `return_date` date DEFAULT NULL,
  `return_date_extended` date DEFAULT NULL,
  `return_extended` tinyint(4) DEFAULT NULL,
  `return_extended_online` tinyint(4) DEFAULT '0',
  `reminder_sent` tinyint(4) DEFAULT '0',
  `next_reservation` date DEFAULT NULL,
  `quarantine` tinyint(4) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `#__lupo_game` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_databauer` int(11) DEFAULT NULL,
  `ean` char(13) DEFAULT NULL,
  `catid` int(11) DEFAULT NULL,
  `age_catid` int(11) DEFAULT NULL,
  `number` char(10) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `description_title` varchar(255) DEFAULT NULL,
  `description` text,
  `fabricator` char(50) DEFAULT NULL,
  `author` varchar(50) DEFAULT NULL,
  `artist` varchar(50) DEFAULT NULL,
  `days` tinyint(4) DEFAULT NULL,
  `play_duration` char(35) DEFAULT NULL,
  `players` char(30) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT '',
  `genres` varchar(255) DEFAULT '',
  `prolongable` tinyint(4) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `game_catid` (`catid`),
  KEY `game_age_catid` (`age_catid`),
  KEY `game_number` (`number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `#__lupo_game_documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gameid` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `type` varchar(20) NOT NULL,
  `desc` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `documents_gameid` (`gameid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `#__lupo_game_editions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gameid` int(11) DEFAULT NULL,
  `index` smallint(6) DEFAULT NULL,
  `edition` varchar(50) DEFAULT NULL,
  `acquired_date` date DEFAULT NULL,
  `tax` double DEFAULT NULL,
  `next_reservation` date DEFAULT NULL,
  `content` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `editions_gameid` (`gameid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `#__lupo_game_genre` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gameid` int(11) NOT NULL,
  `genreid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `#__lupo_game_related` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gameid` int(11) DEFAULT NULL,
  `number` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `#__lupo_genres` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `genre` char(30) DEFAULT NULL,
  `alias` char(30) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `genre` (`alias`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
