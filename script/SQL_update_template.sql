USE `__DATABASE__`;

TRUNCATE #__update_sites;
TRUNCATE #__update_sites_extensions;

INSERT  INTO `#__update_sites`(`update_site_id`,`name`,`type`,`location`,`enabled`,`last_check_timestamp`,`extra_query`,`checked_out`,`checked_out_time`) VALUES (1,'Joomla! Core','collection','https://update.joomla.org/core/list.xml',1,1712815918,'',NULL,NULL);
INSERT  INTO `#__update_sites_extensions`(`update_site_id`,`extension_id`) VALUES (1,700);

UPDATE `#__extensions` SET `params` = '{"updatesource":"default","minimum_stability":"4","customurl":"","versioncheck":"1","backupcheck":"1"} ' WHERE `extension_id` = '28'; 

/* ------------------------------------------------------------------------------------------------------- */
