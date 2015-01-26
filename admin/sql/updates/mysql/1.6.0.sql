ALTER TABLE `#__lupo_game`
  ADD COLUMN `keywords` VARCHAR (255) NULL AFTER `players`
  , ADD COLUMN `genres` VARCHAR (255) NULL AFTER `keywords` ;

