Param(
    [string]$ProjectPath = "C:\inetpub\vhosts\httpdocs"
)

Set-StrictMode -Version Latest
$ErrorActionPreference = "Stop"

Write-Host "== Gaming CMS Deploy (Plesk/Windows) =="
Write-Host "Path: $ProjectPath"

if (-not (Test-Path $ProjectPath)) {
    throw "Projektpfad nicht gefunden: $ProjectPath"
}

Set-Location $ProjectPath

# Composer Platform fuer Server-PHP
$env:COMPOSER_PLATFORM_PHP = "8.4.17"

Write-Host "-> Composer install (no-dev)"
composer install --no-dev --optimize-autoloader

Write-Host "-> Laravel cache clear"
php artisan optimize:clear

Write-Host "-> Migrations"
php artisan migrate --force

Write-Host "-> Storage link"
php artisan storage:link

Write-Host "-> Cache build"
php artisan config:cache
php artisan route:cache
php artisan view:cache

Write-Host "-> Assets build"
npm install
npm run build

Write-Host "== Deploy fertig =="
