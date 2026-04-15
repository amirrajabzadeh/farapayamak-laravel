# مسیر پوشه پکیج
$path = "D:\Develop\Packages Develop\farapayamak-laravel"

# تغییر در composer.json
$composerJson = Get-Content "$path\composer.json" -Raw
$composerJson = $composerJson -replace '"name": "farapayamak/laravel"', '"name": "amirrajabzadeh/farapayamak-laravel"'
$composerJson = $composerJson -replace '"Farapayamak\\\\Laravel\\\\": "src/"', '"Amirrajabzadeh\\\\FarapayamakLaravel\\\\": "src/"'
$composerJson = $composerJson -replace 'Farapayamak\\\\Laravel\\\\', 'Amirrajabzadeh\\\\FarapayamakLaravel\\\\'
Set-Content "$path\composer.json" $composerJson

# تغییر در فایل‌های PHP
Get-ChildItem -Path "$path\src" -Recurse -File -Filter *.php | ForEach-Object {
    $content = Get-Content $_.FullName -Raw
    $content = $content -replace 'Farapayamak\\Laravel', 'Amirrajabzadeh\\FarapayamakLaravel'
    Set-Content $_.FullName $content
}

# تغییر در فایل کانفیگ
$configFile = "$path\config\farapayamak.php"
if (Test-Path $configFile) {
    $content = Get-Content $configFile -Raw
    $content = $content -replace 'Farapayamak\\Laravel', 'Amirrajabzadeh\\FarapayamakLaravel'
    Set-Content $configFile $content
}

Write-Host "✅ تغییرات با موفقیت اعمال شد!" -ForegroundColor Green