#replace all placeholders with content
$folder=$args[0]
Set-Location $folder

$creationDate = "July 2023"
$creationDate_changelog = "July 22, 2023"
$version_pkg_lupo = "4.0.0"

$placeholders = @{
    '{{creationDate}}' = $creationDate
    '{{version_pkg_lupo}}' = $version_pkg_lupo
    '{{creationDate_changelog}}' = $creationDate_changelog
}

foreach($placeholder in $placeholders.keys)
{
    $search = ${placeholder};
    $replace = $($placeholders.Item($placeholder));

    $configFiles = Get-ChildItem $folder\* -Include *.xml, *.md -rec
    foreach ($file in $configFiles)
    {
        (Get-Content $file.PSPath) |
        Foreach-Object { $_ -replace $search, $replace } |
        Set-Content $file.PSPath
    }
}

