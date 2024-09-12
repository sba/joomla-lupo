USE `__DATABASE__`;

#TRUNCATE #__update_sites;
#TRUNCATE #__update_sites_extensions;

#UPDATE #__template_styles
#SET params = '{"hue":"hsl(214, 63%, 20%)","bg-light":"#f0f4fb","text-dark":"#495057","text-light":"#ffffff","link-color":"#2a69b8","link-color-dark":"#6fbfdb","special-color":"#001b4c","monochrome":"0","colorScheme":"light","loginLogo":"","loginLogoAlt":"","logoBrandLarge":"","logoBrandLargeAlt":"","logoBrandSmall":"","logoBrandSmallAlt":""}'
#WHERE template = 'atum';


ALTER TABLE #__lupo_agecategories ENGINE=INNODB; 
ALTER TABLE #__lupo_categories ENGINE=INNODB; 
ALTER TABLE #__lupo_game ENGINE=INNODB; 
ALTER TABLE #__lupo_game_documents ENGINE=INNODB; 
ALTER TABLE #__lupo_game_editions ENGINE=INNODB; 

/* ------------------------------------------------------------------------------------------------------- */

