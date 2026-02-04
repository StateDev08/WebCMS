@echo off
REM ============================================================
REM Gaming CMS - Windows Apache Installation Script
REM Für Apache auf Port 80 (XAMPP, WAMP, etc.)
REM ============================================================

color 0A
echo.
echo ========================================
echo   Gaming CMS Installation (Windows)
echo   Apache Server - Port 80
echo ========================================
echo.

REM Admin-Rechte prüfen
net session >nul 2>&1
if %errorLevel% neq 0 (
    echo [FEHLER] Bitte als Administrator ausfuehren!
    echo Rechtsklick auf install.bat -^> "Als Administrator ausfuehren"
    pause
    exit /b 1
)

REM ============================================================
REM 1. PHP-Installation finden
REM ============================================================
echo [1/10] Suche PHP-Installation...

set "PHP_PATH="
set "PHP_VERSION="

REM XAMPP prüfen
if exist "C:\xampp\php\php.exe" (
    set "PHP_PATH=C:\xampp\php"
    set "APACHE_PATH=C:\xampp\apache"
    set "INSTALL_TYPE=XAMPP"
)

REM WAMP prüfen (verschiedene Versionen)
if exist "C:\wamp64\bin\php" (
    for /d %%i in ("C:\wamp64\bin\php\php*") do (
        if exist "%%i\php.exe" (
            set "PHP_PATH=%%i"
            set "APACHE_PATH=C:\wamp64\bin\apache"
            set "INSTALL_TYPE=WAMP64"
        )
    )
)

REM Laragon prüfen
if exist "C:\laragon\bin\php" (
    for /d %%i in ("C:\laragon\bin\php\php*") do (
        if exist "%%i\php.exe" (
            set "PHP_PATH=%%i"
            set "APACHE_PATH=C:\laragon\bin\apache"
            set "INSTALL_TYPE=Laragon"
        )
    )
)

REM System PHP prüfen
if "%PHP_PATH%"=="" (
    where php >nul 2>&1
    if %errorLevel% equ 0 (
        for /f "tokens=*" %%i in ('where php') do set "PHP_PATH=%%~dpi"
        set "PHP_PATH=%PHP_PATH:~0,-1%"
        set "INSTALL_TYPE=System"
    )
)

if "%PHP_PATH%"=="" (
    echo [FEHLER] PHP nicht gefunden!
    echo Bitte installiere XAMPP, WAMP oder Laragon.
    pause
    exit /b 1
)

REM PHP-Version ermitteln
for /f "tokens=2" %%i in ('"%PHP_PATH%\php.exe" -v ^| findstr /R "PHP [0-9]"') do (
    set "PHP_VERSION=%%i"
    goto :php_version_found
)
:php_version_found

echo [OK] PHP gefunden: %INSTALL_TYPE% (%PHP_VERSION%)
echo      Pfad: %PHP_PATH%

REM ============================================================
REM 2. PHP Extensions prüfen
REM ============================================================
echo.
echo [2/10] Pruefe PHP Extensions...

set "PHP_INI=%PHP_PATH%\php.ini"
if not exist "%PHP_INI%" (
    echo [WARNUNG] php.ini nicht gefunden, kopiere php.ini-development...
    copy "%PHP_PATH%\php.ini-development" "%PHP_INI%" >nul
)

REM Erforderliche Extensions
set "REQUIRED_EXTS=mbstring pdo_mysql intl bcmath gd curl zip xml"

for %%e in (%REQUIRED_EXTS%) do (
    "%PHP_PATH%\php.exe" -m | findstr /i "%%e" >nul
    if errorLevel 1 (
        echo [WARNUNG] Extension %%e nicht aktiviert
        echo           Aktiviere in php.ini: extension=%%e
        findstr /i "extension=%%e" "%PHP_INI%" >nul
        if errorLevel 1 (
            echo extension=%%e>> "%PHP_INI%"
            echo           [OK] Hinzugefuegt zu php.ini
        ) else (
            powershell -Command "(gc '%PHP_INI%') -replace ';extension=%%e', 'extension=%%e' | Out-File -encoding ASCII '%PHP_INI%'"
            echo           [OK] Aktiviert in php.ini
        )
    ) else (
        echo [OK] %%e
    )
)

REM ============================================================
REM 3. Composer prüfen
REM ============================================================
echo.
echo [3/10] Pruefe Composer...

where composer >nul 2>&1
if %errorLevel% neq 0 (
    echo [FEHLER] Composer nicht gefunden!
    echo Bitte installiere Composer von https://getcomposer.org
    pause
    exit /b 1
)

for /f "tokens=3" %%i in ('composer --version 2^>nul') do (
    echo [OK] Composer %%i gefunden
    goto :composer_found
)
:composer_found

REM ============================================================
REM 4. Dependencies installieren
REM ============================================================
echo.
echo [4/10] Installiere Composer Dependencies...

composer install --no-interaction --prefer-dist --optimize-autoloader --ignore-platform-req=ext-redis
if %errorLevel% neq 0 (
    echo [FEHLER] Composer install fehlgeschlagen!
    pause
    exit /b 1
)
echo [OK] Dependencies installiert

REM ============================================================
REM 5. Environment konfigurieren
REM ============================================================
echo.
echo [5/10] Konfiguriere Environment...

if not exist ".env" (
    if exist ".env.example" (
        copy ".env.example" ".env" >nul
        echo [OK] .env erstellt aus .env.example
    ) else (
        echo [FEHLER] .env.example nicht gefunden!
        pause
        exit /b 1
    )
) else (
    echo [OK] .env existiert bereits
)

REM APP_KEY generieren
findstr /c:"APP_KEY=base64:" .env >nul
if errorLevel 1 (
    "%PHP_PATH%\php.exe" artisan key:generate --force
    echo [OK] APP_KEY generiert
)

REM .env anpassen für Apache
powershell -Command "(gc .env) -replace 'APP_URL=.*', 'APP_URL=http://localhost' | Out-File -encoding ASCII .env"
powershell -Command "(gc .env) -replace 'CACHE_STORE=.*', 'CACHE_STORE=file' | Out-File -encoding ASCII .env"
powershell -Command "(gc .env) -replace 'REDIS_CLIENT=.*', 'REDIS_CLIENT=predis' | Out-File -encoding ASCII .env"
powershell -Command "(gc .env) -replace 'DB_HOST=.*', 'DB_HOST=127.0.0.1' | Out-File -encoding ASCII .env"
powershell -Command "(gc .env) -replace 'DB_DATABASE=.*', 'DB_DATABASE=gaming_cms' | Out-File -encoding ASCII .env"

echo [OK] Environment konfiguriert fuer Apache

REM ============================================================
REM 6. Datenbank Setup
REM ============================================================
echo.
echo [6/10] Datenbank Setup...

set /p "DB_SETUP=Soll die Datenbank jetzt erstellt werden? (j/n): "
if /i "%DB_SETUP%"=="j" (
    echo Bitte MySQL Root-Passwort eingeben:
    set /p "MYSQL_ROOT_PASSWORD=Password: "
    
    REM MySQL-Client finden
    set "MYSQL_PATH="
    if exist "%APACHE_PATH%\..\mysql\bin\mysql.exe" (
        set "MYSQL_PATH=%APACHE_PATH%\..\mysql\bin"
    )
    if "%MYSQL_PATH%"=="" if exist "C:\xampp\mysql\bin\mysql.exe" (
        set "MYSQL_PATH=C:\xampp\mysql\bin"
    )
    if "%MYSQL_PATH%"=="" if exist "C:\wamp64\bin\mysql" (
        for /d %%i in ("C:\wamp64\bin\mysql\mysql*") do (
            if exist "%%i\bin\mysql.exe" set "MYSQL_PATH=%%i\bin"
        )
    )
    
    if not "%MYSQL_PATH%"=="" (
        echo CREATE DATABASE IF NOT EXISTS gaming_cms CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci; | "%MYSQL_PATH%\mysql.exe" -u root -p%MYSQL_ROOT_PASSWORD%
        if errorLevel 1 (
            echo [WARNUNG] Datenbank-Erstellung fehlgeschlagen
        ) else (
            echo [OK] Datenbank 'gaming_cms' erstellt
        )
    ) else (
        echo [WARNUNG] MySQL-Client nicht gefunden, bitte manuell erstellen:
        echo            CREATE DATABASE gaming_cms CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
    )
) else (
    echo [INFO] Datenbank-Setup uebersprungen
)

REM ============================================================
REM 7. Migrations ausführen
REM ============================================================
echo.
echo [7/10] Fuehre Migrations aus...

set /p "RUN_MIGRATIONS=Migrations jetzt ausfuehren? (j/n): "
if /i "%RUN_MIGRATIONS%"=="j" (
    "%PHP_PATH%\php.exe" artisan migrate --force
    if %errorLevel% equ 0 (
        echo [OK] Migrations erfolgreich
        
        set /p "RUN_SEEDERS=Demo-Daten laden (Seeder)? (j/n): "
        if /i "%RUN_SEEDERS%"=="j" (
            "%PHP_PATH%\php.exe" artisan db:seed --class=DemoDataSeeder --force
            "%PHP_PATH%\php.exe" artisan db:seed --class=BouncerSeeder --force
            echo [OK] Demo-Daten geladen
            echo      Login: admin@gaming-cms.local / password
        )
    ) else (
        echo [WARNUNG] Migrations fehlgeschlagen
    )
) else (
    echo [INFO] Migrations uebersprungen
)

REM ============================================================
REM 8. Storage Link erstellen
REM ============================================================
echo.
echo [8/10] Erstelle Storage Symlink...

if exist "public\storage" (
    rmdir "public\storage" >nul 2>&1
)
"%PHP_PATH%\php.exe" artisan storage:link
echo [OK] Storage Link erstellt

REM ============================================================
REM 9. Permissions setzen
REM ============================================================
echo.
echo [9/10] Setze Verzeichnis-Permissions...

REM Windows braucht keine chmod, aber wir prüfen Schreibrechte
if not exist "storage\logs" mkdir "storage\logs"
if not exist "bootstrap\cache" mkdir "bootstrap\cache"

echo [OK] Verzeichnisse geprueft

REM ============================================================
REM 10. Apache VirtualHost konfigurieren
REM ============================================================
echo.
echo [10/10] Apache VirtualHost Konfiguration...

set "VHOST_FILE=%APACHE_PATH%\conf\extra\httpd-vhosts.conf"
set "PROJECT_PATH=%cd%"

echo.
echo ========================================
echo   Apache VirtualHost Setup
echo ========================================
echo.
echo Fuege folgende Konfiguration zu:
echo %VHOST_FILE%
echo.
echo ^<VirtualHost *:80^>
echo     ServerName gaming-cms.local
echo     ServerAlias www.gaming-cms.local
echo     DocumentRoot "%PROJECT_PATH%\public"
echo.
echo     ^<Directory "%PROJECT_PATH%\public"^>
echo         Options Indexes FollowSymLinks
echo         AllowOverride All
echo         Require all granted
echo     ^</Directory^>
echo.
echo     ErrorLog "%PROJECT_PATH%\storage\logs\apache-error.log"
echo     CustomLog "%PROJECT_PATH%\storage\logs\apache-access.log" combined
echo ^</VirtualHost^>
echo.
echo Und in C:\Windows\System32\drivers\etc\hosts:
echo 127.0.0.1    gaming-cms.local
echo.

set /p "AUTO_VHOST=Soll ich das automatisch hinzufuegen? (j/n): "
if /i "%AUTO_VHOST%"=="j" (
    REM VirtualHost hinzufügen
    if exist "%VHOST_FILE%" (
        echo. >> "%VHOST_FILE%"
        echo # Gaming CMS >> "%VHOST_FILE%"
        echo ^<VirtualHost *:80^> >> "%VHOST_FILE%"
        echo     ServerName gaming-cms.local >> "%VHOST_FILE%"
        echo     ServerAlias www.gaming-cms.local >> "%VHOST_FILE%"
        echo     DocumentRoot "%PROJECT_PATH%\public" >> "%VHOST_FILE%"
        echo. >> "%VHOST_FILE%"
        echo     ^<Directory "%PROJECT_PATH%\public"^> >> "%VHOST_FILE%"
        echo         Options Indexes FollowSymLinks >> "%VHOST_FILE%"
        echo         AllowOverride All >> "%VHOST_FILE%"
        echo         Require all granted >> "%VHOST_FILE%"
        echo     ^</Directory^> >> "%VHOST_FILE%"
        echo. >> "%VHOST_FILE%"
        echo     ErrorLog "%PROJECT_PATH%\storage\logs\apache-error.log" >> "%VHOST_FILE%"
        echo     CustomLog "%PROJECT_PATH%\storage\logs\apache-access.log" combined >> "%VHOST_FILE%"
        echo ^</VirtualHost^> >> "%VHOST_FILE%"
        echo [OK] VirtualHost hinzugefuegt
    )
    
    REM Hosts-Datei ändern
    findstr /c:"gaming-cms.local" C:\Windows\System32\drivers\etc\hosts >nul
    if errorLevel 1 (
        echo 127.0.0.1    gaming-cms.local >> C:\Windows\System32\drivers\etc\hosts
        echo [OK] hosts-Datei aktualisiert
    )
    
    echo.
    echo [INFO] Bitte Apache neu starten:
    if "%INSTALL_TYPE%"=="XAMPP" (
        echo        - XAMPP Control Panel: Apache Stop/Start
    ) else if "%INSTALL_TYPE%"=="WAMP64" (
        echo        - WAMP Tray Icon: Restart All Services
    ) else (
        echo        - Apache Service neu starten
    )
)

REM ============================================================
REM Abschluss
REM ============================================================
echo.
echo ========================================
echo   Installation abgeschlossen!
echo ========================================
echo.
echo [OK] Gaming CMS ist bereit!
echo.
echo Naechste Schritte:
echo 1. Apache neu starten (%INSTALL_TYPE%)
echo 2. Browser oeffnen: http://gaming-cms.local
echo 3. Admin-Panel: http://gaming-cms.local/admin
echo.
echo Demo-Login:
echo    Email:    admin@gaming-cms.local
echo    Password: password
echo.
echo Debugging:
echo    Debugbar:  Automatisch in Browser
echo    Telescope: http://gaming-cms.local/telescope
echo.
echo Dokumentation:
echo    - GAMING_CMS.md
echo    - SYSTEM_CHECK_REPORT.md
echo.

REM Cache optimieren (optional)
set /p "OPTIMIZE=Cache optimieren fuer Production? (j/n): "
if /i "%OPTIMIZE%"=="j" (
    "%PHP_PATH%\php.exe" artisan config:cache
    "%PHP_PATH%\php.exe" artisan route:cache
    "%PHP_PATH%\php.exe" artisan view:cache
    echo [OK] Cache optimiert
)

echo.
pause
