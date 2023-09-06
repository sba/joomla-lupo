# Skript kopiert Dateien und Ordner von $main_source nach $main_path_target, ersetzt alle Platzhalter in den XML- und MD-Files
# und komprimiert die einzelenen Ordner
#
# Im Zielpfad folgende Dateistruktur erstellen:
#
# joomla:
#   - xml:
#       - fr_install.xml
#       - it_install.xml
#       - update_pkg_lupo.xml

# Ziel- und Quellpfad definieren

# Dropbox anhalten



# Platzhalter für die XML- und MD-Files
$creationDate = "September 2023"
$creationDate_changelog = "September 06, 2023"
$version_pkg_lupo = "4.0.0"


$placeholders = @{
    '{{creationDate}}' = $creationDate
    '{{version_pkg_lupo}}' = $version_pkg_lupo
    '{{creationDate_changelog}}' = $creationDate_changelog
}




if ([System.Security.Principal.WindowsIdentity]::GetCurrent().Name -eq 'SBA-AHW2019\Stefan'){
    [string] $path_source = 'D:\htdocs\_ludos\ludodev.local\'
    [string] $main_path_target = 'C:\Users\Stefan\Dropbox\Projekte\Lupo\web\joomla\'    

} elseif ([System.Security.Principal.WindowsIdentity]::GetCurrent().Name -eq 'DESKTOP-FHLIOIU\serha') {
    [string] $path_source = 'C:\xampp\htdocs\databauer\ludodev.local\'
    [string] $main_path_target = 'C:\xampp\htdocs\databauer\joomla\'  

} else {
    [string] $path_source = 'C:\htdocs\_ludos\ludodev.local\'
    [string] $main_path_target = 'C:\htdocs\joomla\'      
}

Write-Host "`n----------------------------------------------------"
Write-Host "Quellordner: $path_source"
Write-Host "Zielordner: $main_path_target"
Write-Host "----------------------------------------------------`n"


# Funktionen
function CopyFilesAndFolder ([string]$path_target, [array]$files_and_folder){
    foreach ($item in $files_and_folder) {
        $source = $path_source
        $source += $item | % { $_.source }
        $target = $path_target
        $target += $item | % { $_.target }

        if (!(Test-Path -Path $target)) {
            New-Item $target -Type Directory
        }

        Copy-Item -Path $source -Destination $target -Force -Recurse
        Write-Host "Kopiere $source nach $target"
    }
}

function CompileAndCreateZip ([string]$zip_filename, [string]$path_target) {
    Set-Location $path_target
    
    foreach($placeholder in $placeholders.keys)
    {
        $search = ${placeholder};
        $replace = $($placeholders.Item($placeholder));
    
        $configFiles = Get-ChildItem $path_target\* -Include *.xml, *.md -rec
        foreach ($file in $configFiles)
        {
            (Get-Content $file.PSPath) |
            Foreach-Object { $_ -replace $search, $replace } |
            Set-Content $file.PSPath
        }
    }

    Get-ChildItem -Path $path_target -Exclude *.git | Compress-Archive -DestinationPath "$($main_path_target)zip\$($zip_filename)" -Force
}


# Component
$com_lupo = @(
    [pscustomobject] @{source = 'components\com_lupo\*'; target = 'site'}
    [pscustomobject] @{source = 'language\en-GB\en-GB.com_lupo.ini'; target = 'site\language\en-GB\'},
    [pscustomobject] @{source = 'language\de-DE\de-DE.com_lupo.ini'; target = 'site\language\de-DE\'}
    [pscustomobject] @{source = 'administrator\components\com_lupo\*'; target = 'admin\'}
    [pscustomobject] @{source = 'administrator\language\en-GB\en-GB.com_lupo.ini'; target = 'admin\language\en-GB\'}
    [pscustomobject] @{source = 'administrator\language\en-GB\en-GB.com_lupo.sys.ini'; target = 'admin\language\en-GB\'}
    [pscustomobject] @{source = 'administrator\language\en-GB\en-GB.mod_lupo_quickicon.ini'; target = 'admin\language\en-GB\'}
    [pscustomobject] @{source = 'administrator\language\de-DE\de-DE.com_lupo.ini'; target = 'admin\language\de-DE\'}
    [pscustomobject] @{source = 'administrator\language\de-DE\de-DE.com_lupo.sys.ini'; target = 'admin\language\de-DE\'}
    [pscustomobject] @{source = 'administrator\language\de-DE\de-DE.mod_lupo_quickicon.ini'; target = 'admin\language\de-DE\'}
    [pscustomobject] @{source = 'media\com_lupo\*'; target = 'media'}
)

$path_target = $main_path_target + "com_lupo\"
CopyFilesAndFolder $path_target $com_lupo
Move-Item -Path "$($path_target)admin\lupo.xml" -Destination $path_target -Force
Move-Item -Path "$($path_target)admin\script.php" -Destination $path_target -Force

if (!(Test-Path -Path "$($main_path_target)pkg_lupo")) {
    New-Item "$($main_path_target)pkg_lupo" -Type Directory
}

Move-Item -Path "$($path_target)site\pkg_lupo.xml" -Destination "$($main_path_target)pkg_lupo\" -Force
Move-Item -Path "$($path_target)site\CHANGELOG.md" -Destination $path_target -Force
Copy-Item -Path "$($main_path_target)xml\update_pkg_lupo.xml" -Destination "$($path_target)admin\" -Force -Recurse
Copy-Item -Path "$($main_path_target)xml\update_pkg_lupo.xml" -Destination "$($path_target)admin\update_pkg_lupo.xml" -Force -Recurse
CompileAndCreateZip "com_lupo.zip" $path_target

# Such-Plugin für Spiele
$plg_search_lupo = @(
    [pscustomobject] @{source = 'plugins\search\lupo\*'; target = ''}
    [pscustomobject] @{source = 'administrator\language\en-GB\en-GB.plg_search_lupo.ini';    target = ''}
    [pscustomobject] @{source = 'administrator\language\en-GB\en-GB.plg_search_lupo.sys.ini'; target = ''}
    [pscustomobject] @{source = 'administrator\language\de-DE\de-DE.plg_search_lupo.ini';    target = ''}
    [pscustomobject] @{source = 'administrator\language\de-DE\de-DE.plg_search_lupo.sys.ini'; target = ''}
)

$path_target = $main_path_target + "plg_search_lupo\"
CopyFilesAndFolder $path_target $plg_search_lupo
CompileAndCreateZip "plg_search_lupo.zip" $path_target

# Such-Plugin für Genres
$plg_search_lupogenres = @(
    [pscustomobject] @{source = 'plugins\search\lupogenres\*'; target = ''}
    [pscustomobject] @{source = 'administrator\language\en-GB\en-GB.plg_search_lupogenres.ini'; target = ''}
    [pscustomobject] @{source = 'administrator\language\en-GB\en-GB.plg_search_lupogenres.sys.ini'; target = ''},
    [pscustomobject] @{source = 'administrator\language\de-DE\de-DE.plg_search_lupogenres.ini'; target = ''}
    [pscustomobject] @{source = 'administrator\language\de-DE\de-DE.plg_search_lupogenres.sys.ini'; target = ''}
)

$path_target = $main_path_target + "plg_search_lupogenres\"
CopyFilesAndFolder $path_target $plg_search_lupogenres
CompileAndCreateZip "plg_search_lupogenres.zip" $path_target

# Content-Plugin für totale Spiele
$plg_content_lupototaltoys = @(
    [pscustomobject] @{source = 'plugins\content\lupototaltoys\*'; target = ''}
)

$path_target = $main_path_target + "plg_content_lupototaltoys\"
CopyFilesAndFolder $path_target $plg_content_lupototaltoys
CompileAndCreateZip "plg_content_lupototaltoys.zip" $path_target

# Content-Plugin um Spiele anzuzeigen
$plg_content_lupotoy = @(
    [pscustomobject] @{source = 'plugins\content\lupotoy\*'; target = ''}
)

$path_target = $main_path_target + "plg_content_lupotoy\"
CopyFilesAndFolder $path_target $plg_content_lupotoy
CompileAndCreateZip "plg_content_lupotoy.zip" $path_target

# Content-Plugin für zufällige Zitate
$plg_content_luporandomquote = @(
    [pscustomobject] @{source = 'plugins\content\luporandomquote\*'; target = ''}
)

$path_target = $main_path_target + "plg_content_luporandomquote\"
CopyFilesAndFolder $path_target $plg_content_luporandomquote
CompileAndCreateZip "plg_content_luporandomquote.zip" $path_target

# Kategorie-Modul
$mod_lupo_categories = @(
    [pscustomobject] @{source = 'modules\mod_lupo_categories\*'; target = ''}
    [pscustomobject] @{source = 'language\en-GB\en-GB.mod_lupo_categories.ini'; target = 'language\'}
    [pscustomobject] @{source = 'language\en-GB\en-GB.mod_lupo_categories.sys.ini'; target = 'language\'}
    [pscustomobject] @{source = 'language\de-DE\de-DE.mod_lupo_categories.ini'; target = 'language\'}
    [pscustomobject] @{source = 'language\de-DE\de-DE.mod_lupo_categories.sys.ini'; target = 'language\'}
)

$path_target = $main_path_target + "mod_lupo_categories\"
CopyFilesAndFolder $path_target $mod_lupo_categories
CompileAndCreateZip "mod_lupo_categories.zip" $path_target

# Login-Modul
$mod_lupo_login = @(
    [pscustomobject] @{source = 'modules\mod_lupo_login\*'; target = ''}
    [pscustomobject] @{source = 'language\en-GB\en-GB.mod_lupo_login.ini'; target = 'language\'}
    [pscustomobject] @{source = 'language\en-GB\en-GB.mod_lupo_login.sys.ini'; target = 'language\'}
    [pscustomobject] @{source = 'language\de-DE\de-DE.mod_lupo_login.ini'; target = 'language\'}
    [pscustomobject] @{source = 'language\de-DE\de-DE.mod_lupo_login.sys.ini'; target = 'language\'}
)

$path_target = $main_path_target + "mod_lupo_login\"
CopyFilesAndFolder $path_target $mod_lupo_login
CompileAndCreateZip "mod_lupo_login.zip" $path_target

# Loginlink-Modul
$mod_lupo_loginlink = @(
    [pscustomobject] @{source = 'modules\mod_lupo_loginlink\*'; target = ''}
    [pscustomobject] @{source = 'language\en-GB\en-GB.mod_lupo_loginlink.ini'; target = 'language\'},
    [pscustomobject] @{source = 'language\en-GB\en-GB.mod_lupo_loginlink.sys.ini'; target = 'language\'}
    [pscustomobject] @{source = 'language\de-DE\de-DE.mod_lupo_loginlink.ini'; target = 'language\'}
    [pscustomobject] @{source = 'language\de-DE\de-DE.mod_lupo_loginlink.sys.ini'; target = 'language\'}
)

$path_target = $main_path_target + "mod_lupo_loginlink\"
CopyFilesAndFolder $path_target $mod_lupo_loginlink
CompileAndCreateZip "mod_lupo_loginlink.zip" $path_target

# Quickicon-Modul
$mod_lupo_quickicon = @(
    [pscustomobject] @{source = 'administrator\modules\mod_lupo_quickicon\*'; target = ''}
    [pscustomobject] @{source = 'administrator\language\en-GB\en-GB.mod_lupo_quickicon.ini'; target = 'language\'}
    [pscustomobject] @{source = 'administrator\language\de-DE\de-DE.mod_lupo_quickicon.ini'; target = 'language\'}
)

$path_target = $main_path_target + "mod_lupo_quickicon\"
CopyFilesAndFolder $path_target $mod_lupo_quickicon
CompileAndCreateZip "mod_lupo_quickicon.zip" $path_target

# Paket erstellen
$pkg_lupo = @(
    [pscustomobject] @{source = 'zip\com_lupo.zip'; target = ''}
    [pscustomobject] @{source = 'zip\plg_search_lupo.zip'; target = ''}
    [pscustomobject] @{source = 'zip\plg_search_lupogenres.zip'; target = ''}
    [pscustomobject] @{source = 'zip\mod_lupo_categories.zip'; target = ''}
    [pscustomobject] @{source = 'zip\mod_lupo_login.zip'; target = ''}
    [pscustomobject] @{source = 'zip\mod_lupo_loginlink.zip'; target = ''}
    [pscustomobject] @{source = 'zip\mod_lupo_loginlink.zip'; target = ''}
    [pscustomobject] @{source = 'zip\mod_lupo_quickicon.zip'; target = ''}
    [pscustomobject] @{source = 'zip\plg_content_lupototaltoys.zip'; target = ''}
    [pscustomobject] @{source = 'zip\plg_content_luporandomquote.zip'; target = ''}
    [pscustomobject] @{source = 'zip\plg_content_lupotoy.zip'; target = ''}
)

# Erstellt Ordner "pkg_lupo\packages\" wo alle komprimierten Ordner sind bis auf die Sprachpakete
# Deshalb ist der Quellordner in dem Fall der Zielordner, weil sich da alle komprimierten Ordner befinden
# hacky ...
$temp_path_source = $path_source
$path_source = $main_path_target # Quelle ist im Zielordner
$path_target = $main_path_target + "pkg_lupo\"
CopyFilesAndFolder "$($path_target)packages\" $pkg_lupo
$path_source = $temp_path_source
CompileAndCreateZip "pkg_lupo.zip" $path_target

# Französisch Sprachpaket
$pkg_lupo_languagepack_fr = @(
    [pscustomobject] @{source = 'administrator\language\fr-FR\fr-FR.com_lupo.ini'; target = 'admin\'}
    [pscustomobject] @{source = 'administrator\language\fr-FR\fr-FR.com_lupo.sys.ini'; target = 'admin\'}
    [pscustomobject] @{source = 'administrator\language\fr-FR\fr-FR.plg_search_lupo.ini'; target = 'admin\'}
    [pscustomobject] @{source = 'administrator\language\fr-FR\fr-FR.plg_search_lupo.sys.ini'; target = 'admin\'}
    [pscustomobject] @{source = 'administrator\language\fr-FR\fr-FR.plg_search_lupogenres.ini'; target = 'admin\'}
    [pscustomobject] @{source = 'administrator\language\fr-FR\fr-FR.plg_search_lupogenres.sys.ini'; target = 'admin\'}
    [pscustomobject] @{source = 'administrator\language\fr-FR\fr-FR.mod_lupo_quickicon.ini'; target = 'admin\'}
    [pscustomobject] @{source = 'language\fr-FR\fr-FR.com_lupo.ini'; target = 'site\'}
    [pscustomobject] @{source = 'language\fr-FR\fr-FR.mod_lupo_categories.ini'; target = 'site\'}
    [pscustomobject] @{source = 'language\fr-FR\fr-FR.mod_lupo_categories.sys.ini'; target = 'site\'}
    [pscustomobject] @{source = 'language\fr-FR\fr-FR.mod_lupo_login.ini'; target = 'site\'}
    [pscustomobject] @{source = 'language\fr-FR\fr-FR.mod_lupo_login.sys.ini'; target = 'site\'}
    [pscustomobject] @{source = 'language\fr-FR\fr-FR.mod_lupo_loginlink.ini'; target = 'site\'}
    [pscustomobject] @{source = 'language\fr-FR\fr-FR.mod_lupo_loginlink.sys.ini'; target = 'site\'}
)

$path_target = $main_path_target + "pkg_lupo_languagepack_fr-FR\"
CopyFilesAndFolder $path_target $pkg_lupo_languagepack_fr
Copy-Item -Path "$($main_path_target)xml\fr_install.xml" -Destination "$($main_path_target)pkg_lupo_languagepack_fr-FR\install.xml" -Force
CompileAndCreateZip "pkg_lupo_languagepack_fr-FR.zip" $path_target

# Italienisch Sprachpaket
$pkg_lupo_languagepack_it = @(
    [pscustomobject] @{source = 'administrator\language\it-IT\it-IT.com_lupo.ini'; target = 'admin\'}
    [pscustomobject] @{source = 'administrator\language\it-IT\it-IT.com_lupo.sys.ini'; target = 'admin\'}
    [pscustomobject] @{source = 'administrator\language\it-IT\it-IT.plg_search_lupo.ini'; target = 'admin\'}
    [pscustomobject] @{source = 'administrator\language\it-IT\it-IT.plg_search_lupo.sys.ini'; target = 'admin\'}
    [pscustomobject] @{source = 'administrator\language\it-IT\it-IT.plg_search_lupogenres.ini'; target = 'admin\'}
    [pscustomobject] @{source = 'administrator\language\it-IT\it-IT.plg_search_lupogenres.sys.ini'; target = 'admin\'}
    [pscustomobject] @{source = 'administrator\language\it-IT\it-IT.mod_lupo_quickicon.ini'; target = 'admin\'}
    [pscustomobject] @{source = 'language\it-IT\it-IT.com_lupo.ini'; target = 'site\'}
    [pscustomobject] @{source = 'language\it-IT\it-IT.mod_lupo_categories.ini'; target = 'site\'}
    [pscustomobject] @{source = 'language\it-IT\it-IT.mod_lupo_categories.sys.ini'; target = 'site\'}
    [pscustomobject] @{source = 'language\it-IT\it-IT.mod_lupo_login.ini'; target = 'site\'}
    [pscustomobject] @{source = 'language\it-IT\it-IT.mod_lupo_login.sys.ini'; target = 'site\'}
    [pscustomobject] @{source = 'language\it-IT\it-IT.mod_lupo_loginlink.ini'; target = 'site\'}
    [pscustomobject] @{source = 'language\it-IT\it-IT.mod_lupo_loginlink.sys.ini'; target = 'site\'}
)

$path_target = $main_path_target + "pkg_lupo_languagepack_it-IT\"
CopyFilesAndFolder $path_target $pkg_lupo_languagepack_it
Copy-Item -Path "$($main_path_target)xml\it_install.xml" -Destination "$($main_path_target)pkg_lupo_languagepack_it-IT\install.xml" -Force
CompileAndCreateZip "pkg_lupo_languagepack_it-IT.zip" $path_target

Write-Host "`nFertig!"