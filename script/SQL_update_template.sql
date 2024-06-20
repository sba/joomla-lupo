USE `__DATABASE__`;

#TRUNCATE #__update_sites;
#TRUNCATE #__update_sites_extensions;

#UPDATE #__template_styles
#SET params = '{"hue":"hsl(214, 63%, 20%)","bg-light":"#f0f4fb","text-dark":"#495057","text-light":"#ffffff","link-color":"#2a69b8","link-color-dark":"#6fbfdb","special-color":"#001b4c","monochrome":"0","colorScheme":"light","loginLogo":"","loginLogoAlt":"","logoBrandLarge":"","logoBrandLargeAlt":"","logoBrandSmall":"","logoBrandSmallAlt":""}'
#WHERE template = 'atum';

#UPDATE #__extensions SET params='{"updatesource":"default","minimum_stability":"4","customurl":"","versioncheck":"1","backupcheck":"1"}' WHERE `name` = 'com_joomlaupdate';

UPDATE #__users SET password = '$2y$10$f/1hU2NoIaaNl1wbzn6UOeB9H.49NdnE11Px8FJrwC.SANXGgsyYG', `name` = 'databauer (sba)' WHERE username = 'sbauer';

/* ------------------------------------------------------------------------------------------------------- */

