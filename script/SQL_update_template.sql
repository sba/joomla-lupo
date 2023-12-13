USE `__DATABASE__`;

#TRUNCATE #__update_sites;
#TRUNCATE #__update_sites_extensions;

UPDATE `#__extensions` SET `params` = '{"updatesource":"default","minimum_stability":"4","customurl":"","versioncheck":"1","backupcheck":"1"} ' WHERE `extension_id` = '28'; 

UPDATE `#__users` SET `name` = 'databauer (sba)' WHERE `name` = 'Stefan Bauer (databauer)';


#UPDATE `#__extensions` SET enabled=0 WHERE `name`='mod_post_installation_messages';
#UPDATE `#__extensions` SET enabled=0 WHERE `name`='plg_quickicon_overridecheck';
#UPDATE `#__extensions` SET enabled=0 WHERE `name`='plg_quickicon_downloadkey';
#UPDATE `#__extensions` SET enabled=0 WHERE `name`='plg_installer_webinstaller';
#UPDATE `#__extensions` SET enabled=0 WHERE `name`='plg_system_updatenotification';
#UPDATE `#__extensions` SET enabled=0 WHERE `name`='plg_system_webauthn';
#UPDATE `#__extensions` SET enabled=0 WHERE `name`='plg_authentication_ldap';
#UPDATE `#__extensions` SET enabled=0 WHERE `name`='mod_loginsupport';

#UPDATE `#__extensions` SET enabled=1 WHERE `name`='Inhalt - LUPO Spiel';
#UPDATE `#__extensions` SET enabled=1 WHERE `name`='plg_system_wf_responsive_widgets';
#UPDATE `#__extensions` SET enabled=1 WHERE `name`='plg_system_jce';

#UPDATE `#__guidedtours` SET published = 0 WHERE title IN('COM_GUIDEDTOURS_TOUR_GUIDEDTOURS_TITLE','COM_GUIDEDTOURS_TOUR_GUIDEDTOURSTEPS_TITLE','COM_GUIDEDTOURS_TOUR_TAGS_TITLE','COM_GUIDEDTOURS_TOUR_BANNERS_TITLE','COM_GUIDEDTOURS_TOUR_CONTACTS_TITLE','COM_GUIDEDTOURS_TOUR_NEWSFEEDS_TITLE','COM_GUIDEDTOURS_TOUR_SMARTSEARCH_TITLE');

/* ------------------------------------------------------------------------------------------------------- */
