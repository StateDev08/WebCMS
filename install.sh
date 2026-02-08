#!/usr/bin/env bash
set -euo pipefail

PROJECT_ROOT="$(pwd)"
DOMAIN="${1:-}"

if [[ -z "${DOMAIN}" ]]; then
  read -rp "Domain (z.B. drenor.de): " DOMAIN
fi

read -rp "APP_URL (z.B. https://${DOMAIN}): " APP_URL
APP_URL="${APP_URL:-https://${DOMAIN}}"

read -rp "DB_HOST [127.0.0.1]: " DB_HOST
DB_HOST="${DB_HOST:-127.0.0.1}"
read -rp "DB_PORT [3306]: " DB_PORT
DB_PORT="${DB_PORT:-3306}"
read -rp "DB_DATABASE: " DB_DATABASE
read -rp "DB_USERNAME: " DB_USERNAME
read -rsp "DB_PASSWORD: " DB_PASSWORD
echo

if [[ -z "${DB_DATABASE}" || -z "${DB_USERNAME}" ]]; then
  echo "DB_DATABASE und DB_USERNAME sind Pflicht."
  exit 1
fi

if [[ ! -f ".env" && ! -f ".env.local" ]]; then
  cat > .env <<EOF
APP_NAME="PHP CMS"
APP_ENV=production
APP_DEBUG=false
APP_URL=${APP_URL}
APP_LOCALE=de
APP_FALLBACK_LOCALE=en
APP_TIMEZONE=Europe/Berlin
DB_CONNECTION=mysql
DB_HOST=${DB_HOST}
DB_PORT=${DB_PORT}
DB_DATABASE=${DB_DATABASE}
DB_USERNAME=${DB_USERNAME}
DB_PASSWORD=${DB_PASSWORD}
THEME_DEFAULT=default
EOF
  echo ".env erstellt."
fi

mkdir -p storage/sessions storage/media
chmod -R 775 storage

php cli.php migrate
php cli.php seed

if [[ "$(id -u)" -ne 0 ]]; then
  echo "Apache-Konfiguration übersprungen (Root-Rechte benötigt)."
  echo "Bitte als Root ausführen, um Apache/VHost + rewrite zu setzen."
  exit 0
fi

apt-get update
apt-get install -y apache2 libapache2-mod-php php php-mysql php-cli php-curl php-xml php-mbstring php-zip mariadb-server mariadb-client
a2enmod rewrite

if ! command -v snap >/dev/null 2>&1; then
  apt-get install -y snapd
  systemctl enable --now snapd
  ln -sf /var/lib/snapd/snap /snap
fi

snap install core
snap refresh core
snap install certbot --classic
apt-get install -y phpmyadmin

cat > /etc/apache2/conf-available/phpmyadmin.conf <<'EOF'
Alias /phpmyadmin /usr/share/phpmyadmin
<Directory /usr/share/phpmyadmin>
    Options FollowSymLinks
    DirectoryIndex index.php
    AllowOverride All
    Require all granted
</Directory>
EOF
ln -sf /etc/apache2/conf-available/phpmyadmin.conf /etc/apache2/conf-enabled/phpmyadmin.conf


VHOST="/etc/apache2/sites-available/${DOMAIN}.conf"
cat > "${VHOST}" <<EOF
<VirtualHost *:80>
    ServerName ${DOMAIN}
    DocumentRoot ${PROJECT_ROOT}
    <Directory ${PROJECT_ROOT}>
        AllowOverride All
        Require all granted
    </Directory>
    ErrorLog \${APACHE_LOG_DIR}/${DOMAIN}-error.log
    CustomLog \${APACHE_LOG_DIR}/${DOMAIN}-access.log combined
</VirtualHost>
EOF

a2ensite "${DOMAIN}.conf"
systemctl reload apache2

echo "Certbot Beispiel:"
echo "  certbot --apache -d ${DOMAIN}"

echo "Fertig. DocumentRoot: ${PROJECT_ROOT}"
