# Kopiert die Joomla-Files ins github-Dir, verteilt die Files in components/modules/plugins Ordner, erstellt Zip und pkg_lupo Zip

$joomla_dir = 'D:\htdocs\_ludos\ludodev.local\'
$github_dir = 'C:\Users\Stefan\Dropbox\Projekte\Lupo\web\joomla\lupo-extension\'
$target_dir = $github_dir + 'src\'


# Platzhalter für die XML- und MD-Files
$creationDate = "September 2023"
$creationDate_changelog = "September 07, 2023"
$version_pkg_lupo = "4.0.0"


$placeholders = @{
    '{{creationDate}}'           = $creationDate
    '{{version_pkg_lupo}}'       = $version_pkg_lupo
    '{{creationDate_changelog}}' = $creationDate_changelog
}


# Copy all scr files to repository dir - Order alphabetically by folder
# ACHTUNG: Verzeichnisstruktur muss 1:1 vorhanden sein sonst funktioniert das Kopieren nicht fehlerfrei
Copy-Item -Path ($joomla_dir + 'administrator\components\com_lupo\*') -Destination ($target_dir + 'administrator\components\com_lupo') -Recurse -Force -Container

Copy-Item -Path ($joomla_dir + 'administrator\components\com_widgetkit\plugins\content\lupo\*') -Destination ($target_dir + 'administrator\components\com_widgetkit\plugins\content\lupo') -Force -Recurse -Container
Copy-Item -Path ($joomla_dir + 'administrator\components\com_widgetkit\languages\de_DE.json') -Destination ($target_dir + 'administrator\components\com_widgetkit\languages\de_DE.json') -Force 

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
$src_dir = $github_dir + 'src\'
$pkg_dir = $github_dir + 'pkg_lupo\src\'

# com_lupo
Copy-Item -Path ($src_dir + 'administrator\components\com_lupo\*') -Destination ($pkg_dir + 'com_lupo\admin') -Force -Recurse -Container
Copy-Item -Path ($src_dir + 'components\com_lupo\*') -Destination ($pkg_dir + 'com_lupo\site') -Force -Recurse -Container
Copy-Item -Path ($src_dir + 'media\com_lupo\*') -Destination ($pkg_dir + 'com_lupo\media') -Force -Recurse -Container
Copy-Item -Path ($src_dir + 'administrator\language\de-DE\*') -Filter *com_lupo* -Destination ($pkg_dir + 'com_lupo\admin\language\de-DE') -Force 
Copy-Item -Path ($src_dir + 'language\de-DE\*') -Filter *com_lupo* -Destination ($pkg_dir + 'com_lupo\site\language\de-DE') -Force 
Copy-Item -Path ($src_dir + 'language\en-GB\*') -Filter *com_lupo* -Destination ($pkg_dir + 'com_lupo\site\language\en-GB') -Force 

Move-Item ($pkg_dir + 'com_lupo\admin\lupo.xml') -Destination ($pkg_dir + 'com_lupo\lupo.xml') -Force

# pkg_lupo
Copy-Item ($src_dir + 'pkg_lupo.xml') -Destination ($pkg_dir + 'pkg_lupo\pkg_lupo.xml') -Force

# modules
Copy-Item -Path ($src_dir + 'modules\mod_lupo_categories\*') -Destination ($pkg_dir + 'mod_lupo_categories') -Force -Recurse -Container
Copy-Item -Path ($src_dir + 'language\de-DE\*') -Filter *mod_lupo_categories* -Destination ($pkg_dir + 'mod_lupo_categories\language') -Force 
Copy-Item -Path ($src_dir + 'language\en-GB\*') -Filter *mod_lupo_categories* -Destination ($pkg_dir + 'mod_lupo_categories\language') -Force 

Copy-Item -Path ($src_dir + 'modules\mod_lupo_login\*') -Destination ($pkg_dir + 'mod_lupo_login') -Force -Recurse -Container
Copy-Item -Path ($src_dir + 'language\de-DE\*') -Filter *mod_lupo_login.* -Destination ($pkg_dir + 'mod_lupo_login\language') -Force 
Copy-Item -Path ($src_dir + 'language\en-GB\*') -Filter *mod_lupo_login.* -Destination ($pkg_dir + 'mod_lupo_login\language') -Force 

Copy-Item -Path ($src_dir + 'modules\mod_lupo_loginlink\*') -Destination ($pkg_dir + 'mod_lupo_loginlink') -Force -Recurse -Container
Copy-Item -Path ($src_dir + 'language\de-DE\*') -Filter *mod_lupo_loginlink* -Destination ($pkg_dir + 'mod_lupo_loginlink\language') -Force 
Copy-Item -Path ($src_dir + 'language\en-GB\*') -Filter *mod_lupo_loginlink* -Destination ($pkg_dir + 'mod_lupo_loginlink\language') -Force 

# plugins
Copy-Item -Path ($src_dir + 'plugins\content\lupoprivacy\*') -Destination ($pkg_dir + 'plg_content_lupoprivacy') -Force -Recurse -Container
Copy-Item -Path ($src_dir + 'plugins\content\luporandomquote\*') -Destination ($pkg_dir + 'plg_content_luporandomquote') -Force
Copy-Item -Path ($src_dir + 'plugins\content\lupototaltoys\*') -Destination ($pkg_dir + 'plg_content_lupototaltoys') -Force
Copy-Item -Path ($src_dir + 'plugins\content\lupotoy\*') -Destination ($pkg_dir + 'plg_content_lupotoy') -Force
Copy-Item -Path ($src_dir + 'plugins\quickicon\lupo\*') -Destination ($pkg_dir + 'plg_quickicon_lupo') -Force
Copy-Item -Path ($src_dir + 'plugins\search\lupo\*') -Destination ($pkg_dir + 'plg_search_lupo') -Force

Copy-Item -Path ($src_dir + 'plugins\search\lupogenres\*') -Destination ($pkg_dir + 'plg_search_lupogenres') -Force 
Copy-Item -Path ($src_dir + 'language\de-DE\*') -Filter *plg_search_lupogenres* -Destination ($pkg_dir + 'plg_search_lupogenres\language') -Force 
Copy-Item -Path ($src_dir + 'language\en-GB\*') -Filter *plg_search_lupogenres* -Destination ($pkg_dir + 'plg_search_lupogenres\language') -Force 

# language packs
Copy-Item -Path ($src_dir + 'administrator\language\fr-FR\*') -Destination ($pkg_dir + 'pkg_lupo_languagepack_fr-FR\admin') -Force
Copy-Item -Path ($src_dir + 'language\fr-FR\*') -Destination ($pkg_dir + 'pkg_lupo_languagepack_fr-FR\site') -Force
Copy-Item ($src_dir + 'install_language_pack_fr-FR.xml') -Destination ($pkg_dir + 'pkg_lupo_languagepack_fr-FR\install.xml') -Force

Copy-Item -Path ($src_dir + 'administrator\language\it-IT\*') -Filter *_lupo* -Destination ($pkg_dir + 'pkg_lupo_languagepack_it-IT\admin') -Force
Copy-Item -Path ($src_dir + 'language\it-IT\*') -Destination ($pkg_dir + 'pkg_lupo_languagepack_it-IT\site') -Force
Copy-Item ($src_dir + 'install_language_pack_it-IT.xml') -Destination ($pkg_dir + 'pkg_lupo_languagepack_it-IT\install.xml') -Force


# Set version and date to xml-manifests an Changelog.md
foreach ($placeholder in $placeholders.keys) {
    $search = ${placeholder};
    $replace = $($placeholders.Item($placeholder));

    $configFiles = @(Get-ChildItem "$($github_dir)*" -Include CHANGELOG.md)
    $configFiles += @(Get-ChildItem "$($github_dir)pkg_lupo\src\*" -Include *.xml -Recurse)
    foreach ($file in $configFiles) {
        (Get-Content $file.PSPath) |
        Foreach-Object { $_ -replace $search, $replace } |
        Set-Content $file.PSPath
    }
}


# create zip-files
Get-ChildItem -Path "$($github_dir)\pkg_lupo\src\com_lupo" | Compress-Archive -DestinationPath "$($github_dir)pkg_lupo\packages\com_lupo" -Force
Get-ChildItem -Path "$($github_dir)\pkg_lupo\src\mod_lupo_categories" | Compress-Archive -DestinationPath "$($github_dir)pkg_lupo\packages\mod_lupo_categories" -Force
Get-ChildItem -Path "$($github_dir)\pkg_lupo\src\mod_lupo_login" | Compress-Archive -DestinationPath "$($github_dir)pkg_lupo\packages\mod_lupo_login" -Force
Get-ChildItem -Path "$($github_dir)\pkg_lupo\src\mod_lupo_loginlink" | Compress-Archive -DestinationPath "$($github_dir)pkg_lupo\packages\mod_lupo_loginlink" -Force
Get-ChildItem -Path "$($github_dir)\pkg_lupo\src\pkg_lupo_languagepack_fr-FR" | Compress-Archive -DestinationPath "$($github_dir)pkg_lupo\packages\pkg_lupo_languagepack_fr-FR" -Force
Get-ChildItem -Path "$($github_dir)\pkg_lupo\src\pkg_lupo_languagepack_it-IT" | Compress-Archive -DestinationPath "$($github_dir)pkg_lupo\packages\pkg_lupo_languagepack_it-IT" -Force
Get-ChildItem -Path "$($github_dir)\pkg_lupo\src\plg_content_lupoprivacy" | Compress-Archive -DestinationPath "$($github_dir)pkg_lupo\packages\plg_content_lupoprivacy" -Force
Get-ChildItem -Path "$($github_dir)\pkg_lupo\src\plg_content_luporandomquote" | Compress-Archive -DestinationPath "$($github_dir)pkg_lupo\packages\plg_content_luporandomquote" -Force
Get-ChildItem -Path "$($github_dir)\pkg_lupo\src\plg_content_lupototaltoys" | Compress-Archive -DestinationPath "$($github_dir)pkg_lupo\packages\plg_content_lupototaltoys" -Force
Get-ChildItem -Path "$($github_dir)\pkg_lupo\src\plg_content_lupotoy" | Compress-Archive -DestinationPath "$($github_dir)pkg_lupo\packages\plg_content_lupotoy" -Force
Get-ChildItem -Path "$($github_dir)\pkg_lupo\src\plg_quickicon_lupo" | Compress-Archive -DestinationPath "$($github_dir)pkg_lupo\packages\plg_quickicon_lupo" -Force
Get-ChildItem -Path "$($github_dir)\pkg_lupo\src\plg_search_lupo" | Compress-Archive -DestinationPath "$($github_dir)pkg_lupo\packages\plg_search_lupo" -Force
Get-ChildItem -Path "$($github_dir)\pkg_lupo\src\plg_search_lupogenres" | Compress-Archive -DestinationPath "$($github_dir)pkg_lupo\packages\plg_search_lupogenres" -Force


# create pkg_lupo.zip containing all zip-installers
Copy-Item -Path ($github_dir + 'pkg_lupo\packages\*') -Filter *.zip -Exclude pkg_lupo_languagepack* -Destination ($github_dir + 'pkg_lupo\src\pkg_lupo\packages') -Force
Get-ChildItem -Path "$($github_dir)\pkg_lupo\src\pkg_lupo" | Compress-Archive -DestinationPath "$($github_dir)\pkg_lupo\packages\pkg_lupo" -Force

