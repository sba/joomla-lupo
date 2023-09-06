
$joomla_dir = 'D:\htdocs\_ludos\ludodev.local\'
$target_dir = 'C:\Users\Stefan\Dropbox\Projekte\Lupo\web\joomla\lupo-extension\src\'

# Copy all scr files to repository dir - Order alphabetically by folder
Copy-Item -Path ($joomla_dir + 'administrator\components\com_widgetkit\plugins\content\lupo\*') -Destination ($target_dir + 'administrator\components\com_widgetkit\plugins\content\lupo') -Force -Recurse -Container
Copy-Item -Path ($joomla_dir + 'administrator\components\com_widgetkit\languages\de_DE.json') -Destination ($target_dir + 'administrator\components\com_widgetkit\languages\de_DE.json') -Force 
Copy-Item -Path ($joomla_dir + 'administrator\components\com_lupo\*') -Destination ($target_dir + 'administrator\components\com_lupo') -Recurse -Force -Container

Copy-Item -Path ($joomla_dir + 'administrator\language\de-DE\*') -Filter *_lupo* -Destination ($target_dir + 'administrator\language\de-DE') -Force
Copy-Item -Path ($joomla_dir + 'administrator\language\en-GB\*') -Filter *_lupo* -Destination ($target_dir + 'administrator\language\en-GB') -Force
Copy-Item -Path ($joomla_dir + 'administrator\language\fr-FR\*') -Filter *_lupo* -Destination ($target_dir + 'administrator\language\fr-FR') -Force
Copy-Item -Path ($joomla_dir + 'administrator\language\it-IT\*') -Filter *_lupo* -Destination ($target_dir + 'administrator\language\it-IT') -Force

Copy-Item -Path ($joomla_dir + 'components\com_lupo\*') -Destination ($target_dir + 'components\com_lupo') -Recurse -Force -Container

Copy-Item -Path ($joomla_dir + 'language\de-DE\*') -Filter *_lupo* -Destination ($target_dir + 'language\de-DE') -Force
Copy-Item -Path ($joomla_dir + 'language\en-GB\*') -Filter *_lupo* -Destination ($target_dir + 'language\en-GB') -Force
Copy-Item -Path ($joomla_dir + 'language\fr-FR\*') -Filter *_lupo* -Destination ($target_dir + 'language\fr-FR') -Force
Copy-Item -Path ($joomla_dir + 'language\it-IT\*') -Filter *_lupo* -Destination ($target_dir + 'language\it-IT') -Force

Copy-Item -Path ($joomla_dir + 'media\com_lupo\*') -Destination ($target_dir + 'media\com_lupo') -Recurse -Force -Container

Copy-Item -Path ($joomla_dir + 'modules\mod_lupo_categories\*') -Destination ($target_dir + 'modules\mod_lupo_categories') -Recurse -Force -Container
Copy-Item -Path ($joomla_dir + 'modules\mod_lupo_login\*') -Destination ($target_dir + 'modules\mod_lupo_login') -Recurse -Force -Container
Copy-Item -Path ($joomla_dir + 'modules\mod_lupo_loginlink\*') -Destination ($target_dir + 'modules\mod_lupo_loginlink') -Recurse -Force -Container

Copy-Item -Path ($joomla_dir + 'plugins\content\lupoprivacy\*') -Destination ($target_dir + 'plugins\content\lupoprivacy') -Recurse -Force -Container
Copy-Item -Path ($joomla_dir + 'plugins\content\luporandomquote\*') -Destination ($target_dir + 'plugins\content\luporandomquote') -Recurse -Force -Container
Copy-Item -Path ($joomla_dir + 'plugins\content\lupototaltoys\*') -Destination ($target_dir + 'plugins\content\lupototaltoys') -Recurse -Force -Container
Copy-Item -Path ($joomla_dir + 'plugins\content\lupotoy\*') -Destination ($target_dir + 'plugins\content\lupotoy') -Recurse -Force -Container
Copy-Item -Path ($joomla_dir + 'plugins\quickicon\lupo\*') -Destination ($target_dir + 'plugins\quickicon\lupo') -Recurse -Force -Container
Copy-Item -Path ($joomla_dir + 'plugins\search\lupo\*') -Destination ($target_dir + 'plugins\search\lupo') -Recurse -Force -Container
Copy-Item -Path ($joomla_dir + 'plugins\search\lupogenres\*') -Destination ($target_dir + 'plugins\search\lupogenres') -Recurse -Force -Container



# Copy from joomla installation to lupo-extension repoitory
#$folders = @(
#    [pscustomobject] @{source = 'administrator\com_lupo' }
#    [pscustomobject] @{source = 'administrator\com_widgetkit\plugins\content\lupo' }
#    [pscustomobject] @{source = 'administrator\language\de-DE' }
#    [pscustomobject] @{source = 'administrator\language\en-GB' }
#    [pscustomobject] @{source = 'administrator\language\fr-FR' }
#    [pscustomobject] @{source = 'administrator\language\it-IT' }
#    [pscustomobject] @{source = 'components\com_lupo' }
#    [pscustomobject] @{source = 'language\de-DE' }
#    [pscustomobject] @{source = 'language\en-GB' }
#    [pscustomobject] @{source = 'language\fr-FR' }
#    [pscustomobject] @{source = 'language\it-IT' }
#    [pscustomobject] @{source = 'media\com_lupo' }
#    [pscustomobject] @{source = 'modules\mod_lupo_categories' }
#    [pscustomobject] @{source = 'modules\mod_lupo_login' }
#    [pscustomobject] @{source = 'modules\mod_lupo_loginlink' }
#    [pscustomobject] @{source = 'modules\mod_lupo_categories\tmpl' }
#    [pscustomobject] @{source = 'modules\mod_lupo_login\tmpl' }
#    [pscustomobject] @{source = 'modules\mod_lupo_loginlink\tmpl' }
#    [pscustomobject] @{source = 'plugins\content\lupoprivacy' }
#    [pscustomobject] @{source = 'plugins\content\luporandomquote' }
#    [pscustomobject] @{source = 'plugins\content\lupototaltoys' }
#    [pscustomobject] @{source = 'plugins\content\lupotoy' }
#    [pscustomobject] @{source = 'plugins\content\lupoprivacy\tmpl' }
#    [pscustomobject] @{source = 'plugins\quickicon\lupo' }
#    [pscustomobject] @{source = 'plugins\search\lupo' }
#    [pscustomobject] @{source = 'plugins\search\lupogenres' } 
#)

