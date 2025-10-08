ALTER TABLE `#__lupo_game_editions` CHANGE `index` `index` INT(11) NULL;

ALTER TABLE `#__lupo_game` CHANGE `number` `number` CHAR(21) NULL;

ALTER TABLE `#__lupo_game_related` CHANGE `gameid` `gameid` INT(11) NOT NULL, CHANGE `number` `number` CHAR(21) NOT NULL;
