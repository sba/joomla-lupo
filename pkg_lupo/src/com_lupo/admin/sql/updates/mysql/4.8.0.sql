ALTER TABLE `#__lupo_game_related` CHANGE `number` `number` CHAR(21) NOT NULL;

ALTER TABLE `#__lupo_reservations_web` CHANGE `game_number` `game_number` CHAR(21) NOT NULL;

ALTER TABLE `#__lupo_clients_borrowed` CHANGE `game_number` `game_number` CHAR(21) NULL;
