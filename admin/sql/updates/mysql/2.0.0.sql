ALTER TABLE `#__lupo_game`
  ADD COLUMN `keywords` VARCHAR (255) NULL AFTER `players`
  , ADD COLUMN `genres` VARCHAR (255) NULL AFTER `keywords`;


CREATE TABLE `#__lupo_game_documents` (
  `id` int NOT NULL AUTO_INCREMENT,
  `gameid` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `type` varchar(20) NOT NULL,
  `desc` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `documents_gameid` (`gameid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE `#__lupo_genres` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `genre` char(30) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `genre` (`genre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
