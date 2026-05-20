#!/usr/bin/env bash
# ============================================================
#  Finance Tracker — Script Instalasi Otomatis
#  Laravel 11 + Livewire 3 + MySQL
# ============================================================
set -e

GREEN='\033[0;32m'; YELLOW='\033[1;33m'; CYAN='\033[0;36m'; RED='\033[0;31m'; NC='\033[0m'
info()    { echo -e "${CYAN}ℹ  $1${NC}"; }
success() { echo -e "${GREEN}✅ $1${NC}"; }
warn()    { echo -e "${YELLOW}⚠  $1${NC}"; }
error()   { echo -e "${RED}❌ $1${NC}"; exit 1; }

echo ""
echo -e "${GREEN}============================================${NC}"
echo -e "${GREEN}  💰 Finance Tracker — Instalasi Otomatis  ${NC}"
echo -e "${GREEN}============================================${NC}"
echo ""

# ── Cek prasyarat ──────────────────────────────────────────
php -v > /dev/null 2>&1 || error "PHP tidak ditemukan. Install PHP 8.2+ terlebih dahulu."
composer -V > /dev/null 2>&1 || error "Composer tidak ditemukan. Install Composer terlebih dahulu."
PHP_VER=$(php -r "echo PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION;")
info "PHP versi: $PHP_VER"

# ── 1. Install Composer dependencies ──────────────────────
info "Menginstal dependensi Composer..."
composer install --no-interaction --prefer-dist --optimize-autoloader
success "Dependensi terinstal"

# ── 2. Setup .env ──────────────────────────────────────────
if [ ! -f .env ]; then
    cp .env.example .env
    info "File .env dibuat dari .env.example"
fi

# ── 3. App key ─────────────────────────────────────────────
info "Membuat application key..."
php artisan key:generate --ansi
success "App key dibuat"

# ── 4. Konfigurasi Database ────────────────────────────────
echo ""
echo -e "${YELLOW}🗄️  Konfigurasi Database MySQL:${NC}"
read -p "  DB_HOST   [127.0.0.1]: " db_host;   db_host=${db_host:-127.0.0.1}
read -p "  DB_PORT   [3306]:      " db_port;   db_port=${db_port:-3306}
read -p "  DB_NAME   [finance_tracker]: " db_name; db_name=${db_name:-finance_tracker}
read -p "  DB_USER   [root]:      " db_user;   db_user=${db_user:-root}
read -sp "  DB_PASS   :            " db_pass;   echo ""

# Update .env (macOS & Linux compatible)
if [[ "$OSTYPE" == "darwin"* ]]; then
    sed -i '' "s|DB_HOST=.*|DB_HOST=$db_host|"          .env
    sed -i '' "s|DB_PORT=.*|DB_PORT=$db_port|"          .env
    sed -i '' "s|DB_DATABASE=.*|DB_DATABASE=$db_name|"  .env
    sed -i '' "s|DB_USERNAME=.*|DB_USERNAME=$db_user|"  .env
    sed -i '' "s|DB_PASSWORD=.*|DB_PASSWORD=$db_pass|"  .env
else
    sed -i "s|DB_HOST=.*|DB_HOST=$db_host|"             .env
    sed -i "s|DB_PORT=.*|DB_PORT=$db_port|"             .env
    sed -i "s|DB_DATABASE=.*|DB_DATABASE=$db_name|"     .env
    sed -i "s|DB_USERNAME=.*|DB_USERNAME=$db_user|"     .env
    sed -i "s|DB_PASSWORD=.*|DB_PASSWORD=$db_pass|"     .env
fi

# ── 5. Buat database ───────────────────────────────────────
info "Membuat database '$db_name' jika belum ada..."
mysql -h "$db_host" -P "$db_port" -u "$db_user" ${db_pass:+-p"$db_pass"} \
    -e "CREATE DATABASE IF NOT EXISTS \`$db_name\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" \
    2>/dev/null && success "Database '$db_name' siap" \
    || warn "Tidak bisa auto-create database — pastikan sudah dibuat manual."

# ── 6. Migrasi ─────────────────────────────────────────────
info "Menjalankan migrasi database..."
php artisan migrate --force
success "Migrasi selesai"

# ── 7. Seeder ──────────────────────────────────────────────
info "Mengisi data contoh..."
php artisan db:seed --force
success "Data contoh berhasil diisi"

# ── 8. Storage link ────────────────────────────────────────
php artisan storage:link 2>/dev/null || true

# ── 9. Selesai ─────────────────────────────────────────────
echo ""
echo -e "${GREEN}============================================${NC}"
echo -e "${GREEN}  🎉 Instalasi Selesai!                    ${NC}"
echo -e "${GREEN}============================================${NC}"
echo ""
echo -e "  Jalankan server:  ${CYAN}php artisan serve${NC}"
echo -e "  Buka di browser:  ${CYAN}http://localhost:8000${NC}"
echo ""
