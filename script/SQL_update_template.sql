USE `__DATABASE__`;

#TRUNCATE #__update_sites;
#TRUNCATE #__update_sites_extensions;


UPDATE `#__modules` SET published=0 WHERE `position`='icon' AND (`title`='Notifications' OR `title`='System' );
UPDATE `#__modules` SET `title`='LUPO', ordering=0, params='{"context":"mod_quickicon","header_icon":"fas fa-smile","load_plugins":"1","layout":"_:default","moduleclass_sfx":"","cache":1,"cache_time":900,"style":"0","module_tag":"div","bootstrap_size":"12","header_tag":"h2","header_class":""}' WHERE `position`='icon' AND `title`='3rd Party';
UPDATE `#__modules` SET title = 'Webseite', params='{"context":"site_quickicon","header_icon":"fas fa-desktop","show_global":"0","show_checkin":"0","show_cache":"0","show_users":"0","show_articles":"1","show_featured":"1","show_menuitems":"1","show_workflow":"0","show_banners":"0","show_finder":"0","show_newsfeeds":"0","show_tags":"0","show_redirect":"0","show_associations":"0","show_languages":"0","show_modules":"1","show_contact":"0","show_categories":"0","show_media":"0","show_plugins":"0","show_template_styles":"0","show_template_code":"0","layout":"_:default","moduleclass_sfx":"","cache":1,"cache_time":900,"module_tag":"div","bootstrap_size":"12","header_tag":"h2","header_class":"","style":"0"}'  WHERE `position`='icon' AND `title`='Site';
UPDATE `#__extensions` SET enabled=0 WHERE `name`='cassiopeia' AND `element`='cassiopeia';



/* ------------------------------------------------------------------------------------------------------- */

