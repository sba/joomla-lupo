
$joomla_dir = 'D:\htdocs\_ludos\ludodev.local\'
$target_dir = 'C:\Users\Stefan\Dropbox\Projekte\Lupo\web\joomla\lupo-extension\src\'


# Platzhalter für die XML- und MD-Files
$creationDate = "September 2023"
$creationDate_changelog = "September 06, 2023"
$version_pkg_lupo = "4.0.0"


$placeholders = @{
    '{{creationDate}}'           = $creationDate
    '{{version_pkg_lupo}}'       = $version_pkg_lupo
    '{{creationDate_changelog}}' = $creationDate_changelog
}


function CompileAndCreateZip ([string]$src_folder) {
    
    Set-Location ($pkg_dir + $src_folder)
    
    foreach ($placeholder in $placeholders.keys) {
        $search = ${placeholder};
        $replace = $($placeholders.Item($placeholder));
    
        $configFiles = Get-ChildItem $src_folder\* -Include *.xml, *.md -rec
        foreach ($file in $configFiles) {
            (Get-Content $file.PSPath) |
            Foreach-Object { $_ -replace $search, $replace } |
            Set-Content $file.PSPath
        }
    }

    Get-ChildItem -Path $src_folder | Compress-Archive -DestinationPath "$($pkg_dir)packages\$($src_folder)" -Force
}




# Copy all scr files to repository dir - Order alphabetically by folder
# ACHTUNG: Verzeichnisstruktur muss 1:1 vorhanden sein sonst funktioniert das Kopieren nicht fehlerfrei
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
Copy-Item -Path ($joomla_dir + 'plugins\content\luporandomquote\*') -Destination ($target_dir + 'plugins\content\luporandomquote') -Force 
Copy-Item -Path ($joomla_dir + 'plugins\content\lupototaltoys\*') -Destination ($target_dir + 'plugins\content\lupototaltoys') -Recurse -Force 
Copy-Item -Path ($joomla_dir + 'plugins\content\lupotoy\*') -Destination ($target_dir + 'plugins\content\lupotoy') -Recurse -Force 
Copy-Item -Path ($joomla_dir + 'plugins\quickicon\lupo\*') -Destination ($target_dir + 'plugins\quickicon\lupo') -Recurse -Force 
Copy-Item -Path ($joomla_dir + 'plugins\search\lupo\*') -Destination ($target_dir + 'plugins\search\lupo') -Recurse -Force 
Copy-Item -Path ($joomla_dir + 'plugins\search\lupogenres\*') -Destination ($target_dir + 'plugins\search\lupogenres') -Recurse -Force



# Copy files again to pkg_lupo filestructure
$scr_dir = 'C:\Users\Stefan\Dropbox\Projekte\Lupo\web\joomla\lupo-extension\src\'
$pkg_dir = 'C:\Users\Stefan\Dropbox\Projekte\Lupo\web\joomla\lupo-extension\pkg_lupo\src\'

# com_lupo
Copy-Item -Path ($scr_dir + 'administrator\components\com_lupo\*') -Destination ($pkg_dir + 'com_lupo\admin') -Force -Recurse -Container
Copy-Item -Path ($scr_dir + 'components\com_lupo\*') -Destination ($pkg_dir + 'com_lupo\site') -Force -Recurse -Container
Copy-Item -Path ($scr_dir + 'media\com_lupo\*') -Destination ($pkg_dir + 'com_lupo\media') -Force -Recurse -Container
Copy-Item -Path ($scr_dir + 'administrator\language\de-DE\*') -Filter *com_lupo* -Destination ($pkg_dir + 'com_lupo\admin\language\de-DE') -Force 
Copy-Item -Path ($scr_dir + 'language\de-DE\*') -Filter *com_lupo* -Destination ($pkg_dir + 'com_lupo\site\language\de-DE') -Force 
Copy-Item -Path ($scr_dir + 'language\en-GB\*') -Filter *com_lupo* -Destination ($pkg_dir + 'com_lupo\site\language\en-GB') -Force 

# modules
Copy-Item -Path ($scr_dir + 'modules\mod_lupo_categories\*') -Destination ($pkg_dir + 'mod_lupo_categories') -Force -Recurse -Container
Copy-Item -Path ($scr_dir + 'language\de-DE\*') -Filter *mod_lupo_categories* -Destination ($pkg_dir + 'mod_lupo_categories\language') -Force 
Copy-Item -Path ($scr_dir + 'language\en-GB\*') -Filter *mod_lupo_categories* -Destination ($pkg_dir + 'mod_lupo_categories\language') -Force 

Copy-Item -Path ($scr_dir + 'modules\mod_lupo_login\*') -Destination ($pkg_dir + 'mod_lupo_login') -Force -Recurse -Container
Copy-Item -Path ($scr_dir + 'language\de-DE\*') -Filter *mod_lupo_login.* -Destination ($pkg_dir + 'mod_lupo_login\language') -Force 
Copy-Item -Path ($scr_dir + 'language\en-GB\*') -Filter *mod_lupo_login.* -Destination ($pkg_dir + 'mod_lupo_login\language') -Force 

Copy-Item -Path ($scr_dir + 'modules\mod_lupo_loginlink\*') -Destination ($pkg_dir + 'mod_lupo_loginlink') -Force -Recurse -Container
Copy-Item -Path ($scr_dir + 'language\de-DE\*') -Filter *mod_lupo_loginlink* -Destination ($pkg_dir + 'mod_lupo_loginlink\language') -Force 
Copy-Item -Path ($scr_dir + 'language\en-GB\*') -Filter *mod_lupo_loginlink* -Destination ($pkg_dir + 'mod_lupo_loginlink\language') -Force 

# plugins
Copy-Item -Path ($scr_dir + 'plugins\content\lupoprivacy\*') -Destination ($pkg_dir + 'plg_content_lupoprivacy') -Force -Recurse -Container
Copy-Item -Path ($scr_dir + 'plugins\content\luporandomquote\*') -Destination ($pkg_dir + 'plg_content_luporandomquote') -Force
Copy-Item -Path ($scr_dir + 'plugins\content\lupototaltoys\*') -Destination ($pkg_dir + 'plg_content_lupototaltoys') -Force
Copy-Item -Path ($scr_dir + 'plugins\content\lupotoy\*') -Destination ($pkg_dir + 'plg_content_lupotoy') -Force
Copy-Item -Path ($scr_dir + 'plugins\quickicon\lupo\*') -Destination ($pkg_dir + 'plg_quickicon_lupo') -Force
Copy-Item -Path ($scr_dir + 'plugins\search\lupo\*') -Destination ($pkg_dir + 'plg_search_lupo') -Force

Copy-Item -Path ($scr_dir + 'plugins\search\lupogenres\*') -Destination ($pkg_dir + 'plg_search_lupogenres') -Force 
Copy-Item -Path ($scr_dir + 'language\de-DE\*') -Filter *plg_search_lupogenres* -Destination ($pkg_dir + 'plg_search_lupogenres\language') -Force 
Copy-Item -Path ($scr_dir + 'language\en-GB\*') -Filter *plg_search_lupogenres* -Destination ($pkg_dir + 'plg_search_lupogenres\language') -Force 

#language packs
Copy-Item -Path ($scr_dir + 'administrator\language\fr-FR\*') -Destination ($pkg_dir + 'pkg_lupo_languagepack_fr-FR\admin') -Force
Copy-Item -Path ($scr_dir + 'language\fr-FR\*') -Destination ($pkg_dir + 'pkg_lupo_languagepack_fr-FR\site') -Force
Copy-Item ($scr_dir + 'install_language_pack_fr-FR.xml') -Destination ($pkg_dir + 'pkg_lupo_languagepack_fr-FR\install.xml') -Force

Copy-Item -Path ($scr_dir + 'administrator\language\it-IT\*') -Filter *_lupo* -Destination ($pkg_dir + 'pkg_lupo_languagepack_it-IT\admin') -Force
Copy-Item -Path ($scr_dir + 'language\it-IT\*') -Destination ($pkg_dir + 'pkg_lupo_languagepack_it-IT\site') -Force
Copy-Item ($scr_dir + 'install_language_pack_it-IT.xml') -Destination ($pkg_dir + 'pkg_lupo_languagepack_it-IT\install.xml') -Force



CompileAndCreateZip "com_lupo" 
#CompileAndCreateZ1p "plg_search_lupo" 
#CompileAndCreateZip "plg_search_lupogenres"
#CompileAndCreateZip "plg_content_lupototaltoys"
#compileAndCreateZip "plg_content_lupotoy" 
#CompileAndCreateZip "plg_content_luporandomquote"
#CompileAndCreateZip "mod_lupo_categories" 
#CompileAndCreateZip 
#CompileAndCreateZip "mod_lupo_loginlink"
#compileAndCreateZip "pkg_lupo" 
#CompileAndCreateZip 
#CompileAndCreateZip "pkg_lupo_languagepack_it-lT" 
