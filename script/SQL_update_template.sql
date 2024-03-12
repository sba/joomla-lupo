USE `__DATABASE__`;

TRUNCATE #__update_sites;
TRUNCATE #__update_sites_extensions;

#UPDATE `#__extensions` SET `params` = '{"updatesource":"default","minimum_stability":"4","customurl":"","versioncheck":"1","backupcheck":"1"} ' WHERE `extension_id` = '28'; 

UPDATE `#__extensions` SET enabled=1 WHERE element='lupowidgetkit';

/* ------------------------------------------------------------------------------------------------------- */
