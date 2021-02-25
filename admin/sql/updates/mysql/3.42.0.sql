ALTER TABLE `#__lupo_categories` ADD COLUMN `subsets` TEXT NULL AFTER `samples`;
ALTER TABLE `#__lupo_agecategories` ADD COLUMN `subsets` TEXT NULL AFTER `age_number`;
ALTER TABLE `#__lupo_genres` ADD COLUMN `subsets` TEXT NULL AFTER `alias`;
