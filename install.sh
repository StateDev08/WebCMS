#!/bin/bash

##############################################
# Gaming CMS - Plesk Installation Script
# Autor: Gaming CMS Team
# Datum: 03.02.2026
##############################################

set -e  # Exit bei Fehler

echo "=========================================="
echo "Gaming CMS - Installation startet..."
echo "=========================================="
echo ""

# Farben für Output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Prüfe ob als root ausgeführt
if [ "$EUID" -ne 0 ]; then 
    echo -e "${RED}Bitte als root ausführen (sudo ./install.sh)${NC}"
    exit 1
fi

echo -e "${YELLOW}1. Prüfe PHP Extensions...${NC}"

# Erkenne PHP Version automatisch
PHP_VERSION=$(php -r "echo PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION;")
echo "PHP Version: $PHP_VERSION"

REQUIRED_EXTENSIONS="mbstring xml curl zip gd pdo_mysql tokenizer fileinfo intl bcmath"
MISSING_EXTENSIONS=""

for ext in $REQUIRED_EXTENSIONS; do
    if ! php -m | grep -q "^$ext$"; then
        MISSING_EXTENSIONS="$MISSING_EXTENSIONS php${PHP_VERSION}-${ext}"
    fi
done

if [ -n "$MISSING_EXTENSIONS" ]; then
    echo -e "${YELLOW}Installiere fehlende PHP Extensions:$MISSING_EXTENSIONS${NC}"
    apt-get update
    apt-get install -y $MISSING_EXTENSIONS
    
    # PHP-FPM neustarten (mehrere Varianten versuchen)
    systemctl restart php${PHP_VERSION}-fpm 2>/dev/null || \
    systemctl restart php-fpm 2>/dev/null || \
    service sw-cp-server restart 2>/dev/null || \
    echo -e "${YELLOW}⚠ Bitte PHP-FPM manuell neustarten${NC}"
    
    echo -e "${GREEN}✓ PHP Extensions installiert${NC}"
else
    echo -e "${GREEN}✓ Alle PHP Extensions vorhanden${NC}"
fi

echo ""
echo -e "${YELLOW}2. Setze Berechtigungen...${NC}"
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
echo -e "${GREEN}✓ Berechtigungen gesetzt${NC}"

echo ""
echo -e "${YELLOW}3. Installiere Composer Dependencies...${NC}"
if [ ! -f "composer.json" ]; then
    echo -e "${RED}✗ composer.json nicht gefunden!${NC}"
    exit 1
fi

# Prüfe ob composer installiert ist
if ! command -v composer &> /dev/null; then
    echo -e "${YELLOW}Composer nicht gefunden, installiere...${NC}"
    curl -sS https://getcomposer.org/installer | php
    mv composer.phar /usr/local/bin/composer
fi

COMPOSER_ALLOW_SUPERUSER=1 composer install --no-dev --optimize-autoloader
echo -e "${GREEN}✓ Composer Dependencies installiert${NC}"

echo ""
echo -e "${YELLOW}4. Environment-Konfiguration...${NC}"
if [ ! -f ".env" ]; then
    if [ -f ".env.example" ]; then
        cp .env.example .env
        echo -e "${GREEN}✓ .env erstellt aus .env.example${NC}"
    else
        echo -e "${RED}✗ Keine .env.example gefunden!${NC}"
        exit 1
    fi
else
    echo -e "${GREEN}✓ .env existiert bereits${NC}"
fi

# .env anpassen für Production
echo ""
echo -e "${YELLOW}Passe .env für Production an...${NC}"
sed -i 's/^APP_ENV=.*/APP_ENV=production/' .env
sed -i 's/^APP_DEBUG=.*/APP_DEBUG=false/' .env
sed -i 's/^CACHE_STORE=.*/CACHE_STORE=file/' .env
sed -i 's/^SESSION_DRIVER=.*/SESSION_DRIVER=database/' .env

# APP_KEY generieren falls leer
if ! grep -q "APP_KEY=base64:" .env; then
    php artisan key:generate --force
    echo -e "${GREEN}✓ APP_KEY generiert${NC}"
fi

echo ""
echo -e "${YELLOW}5. Datenbankverbindung prüfen...${NC}"
# Hole DB Credentials aus .env
DB_HOST=$(grep DB_HOST .env | cut -d '=' -f2)
DB_DATABASE=$(grep DB_DATABASE .env | cut -d '=' -f2)
DB_USERNAME=$(grep DB_USERNAME .env | cut -d '=' -f2)
DB_PASSWORD=$(grep DB_PASSWORD .env | cut -d '=' -f2)

echo "Database: $DB_DATABASE"
echo "Host: $DB_HOST"
echo "User: $DB_USERNAME"

# Test DB Connection
if php artisan db:show &> /dev/null; then
    echo -e "${GREEN}✓ Datenbankverbindung erfolgreich${NC}"
else
    echo -e "${RED}✗ Datenbankverbindung fehlgeschlagen!${NC}"
    echo -e "${YELLOW}Bitte .env Datenbankeinstellungen prüfen${NC}"
    exit 1
fi

echo ""
echo -e "${YELLOW}6. Führe Migrationen aus...${NC}"
read -p "Datenbank zurücksetzen? (migrate:fresh) [y/N]: " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    php artisan migrate:fresh --force
    echo -e "${GREEN}✓ Datenbank neu erstellt${NC}"
    
    read -p "Demo-Daten laden? [y/N]: " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        php artisan db:seed --class=DemoDataSeeder --force
        echo -e "${GREEN}✓ Demo-Daten geladen${NC}"
        echo -e "${YELLOW}Login: admin@gaming-cms.local / password${NC}"
    fi
else
    php artisan migrate --force
    echo -e "${GREEN}✓ Migrationen ausgeführt${NC}"
fi

echo ""
echo -e "${YELLOW}7. Storage Link erstellen...${NC}"
php artisan storage:link
echo -e "${GREEN}✓ Storage Link erstellt${NC}"

echo ""
echo -e "${YELLOW}8. Caches optimieren...${NC}"
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan filament:optimize
echo -e "${GREEN}✓ Caches optimiert${NC}"

echo ""
echo -e "${YELLOW}9. Assets kompilieren...${NC}"
if command -v npm &> /dev/null; then
    NODE_VERSION=$(node -v | cut -d'v' -f2 | cut -d'.' -f1)
    echo "Node Version: $(node -v)"
    
    if [ "$NODE_VERSION" -lt 20 ]; then
        echo -e "${YELLOW}⚠ Node.js >= 20 wird empfohlen (aktuell: v${NODE_VERSION})${NC}"
        echo -e "${YELLOW}Überspringe Assets Kompilierung${NC}"
        echo -e "${YELLOW}Bitte Assets lokal kompilieren und public/build/ hochladen${NC}"
    else
        echo "npm gefunden, installiere Node Dependencies..."
        npm install
        
        # Verwende npx um Permission-Probleme zu vermeiden
        npx vite build
        echo -e "${GREEN}✓ Assets kompiliert${NC}"
    fi
else
    echo -e "${YELLOW}⚠ npm nicht gefunden${NC}"
    echo -e "${YELLOW}Bitte Assets lokal kompilieren und public/build/ hochladen${NC}"
fi

echo ""
echo -e "${YELLOW}10. Finale Berechtigungen...${NC}"
chown -R www-data:www-data .
chmod -R 755 .
chmod -R 775 storage bootstrap/cache
echo -e "${GREEN}✓ Berechtigungen gesetzt${NC}"

echo ""
echo "=========================================="
echo -e "${GREEN}Installation abgeschlossen!${NC}"
echo "=========================================="
echo ""
echo -e "${YELLOW}Nächste Schritte:${NC}"
echo "1. Document Root in Plesk auf '/public' setzen"
echo "2. Admin-Panel aufrufen: https://ihre-domain.de/admin"
echo "3. Mit Demo-Login testen: admin@gaming-cms.local / password"
echo ""
echo -e "${YELLOW}Empfohlene Post-Installation:${NC}"
echo "• SSL-Zertifikat aktivieren (Let's Encrypt in Plesk)"
echo "• Cron-Job für Queue Worker: * * * * * php artisan schedule:run"
echo "• Backups einrichten (Plesk Backup Manager)"
echo ""
echo -e "${GREEN}Viel Erfolg mit Gaming CMS!${NC}"
