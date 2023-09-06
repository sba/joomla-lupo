
$joomla_dir = 'D:\htdocs\_ludos\ludodev.local\'
$target_dir = 'C:\Users\Stefan\Dropbox\Projekte\Lupo\web\joomla\lupo-extension\src\'
Copy-Item -Path ($joomla_dir + "administrator\components\com_widgetkit\plugins\content\lupo\*") -Destination ($target_dir + 'administrator\components\com_widgetkit\plugins\content\lupo') -Recurse -Container




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

