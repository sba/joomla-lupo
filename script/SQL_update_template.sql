USE `__DATABASE__`;

#TRUNCATE #__update_sites;
#TRUNCATE #__update_sites_extensions;


UPDATE `#__extensions` SET enabled=0 WHERE folder='editors-xtd' AND element IN('pagebreak','contact','fields','weblink');
UPDATE `#__extensions` SET enabled=1 WHERE folder='editors-xtd' AND element IN('lupotoy','module');


/* ------------------------------------------------------------------------------------------------------- */

