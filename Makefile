# ============================================================
#  Finance Tracker — Makefile
#  Usage: make <command>
# ============================================================

.PHONY: help install dev fresh seed clear optimize test pint

help:
	@echo ""
	@echo "  💰 Finance Tracker — Perintah Tersedia"
	@echo "  ────────────────────────────────────────"
	@echo "  make install   — Install dependensi & setup lengkap"
	@echo "  make dev       — Jalankan server pengembangan"
	@echo "  make fresh     — Reset database + seed ulang"
	@echo "  make seed      — Jalankan seeder saja"
	@echo "  make clear     — Bersihkan semua cache"
	@echo "  make optimize  — Cache config/route/view (production)"
	@echo "  make test      — Jalankan semua test"
	@echo "  make pint      — Format kode dengan Laravel Pint"
	@echo ""

install:
	composer install --no-interaction --prefer-dist
	@[ -f .env ] || cp .env.example .env
	php artisan key:generate --ansi
	php artisan migrate --force
	php artisan db:seed --force
	@echo ""
	@echo "✅ Instalasi selesai!"
	@echo "   Jalankan: make dev"
	@echo "   Buka:     http://localhost:8000"

dev:
	php artisan serve

fresh:
	php artisan migrate:fresh --seed
	@echo "✅ Database direset dan data contoh dimuat ulang."

seed:
	php artisan db:seed

clear:
	php artisan cache:clear
	php artisan config:clear
	php artisan route:clear
	php artisan view:clear
	@echo "✅ Semua cache dibersihkan."

optimize:
	php artisan config:cache
	php artisan route:cache
	php artisan view:cache
	@echo "✅ Cache dioptimasi untuk production."

test:
	php artisan test

pint:
	./vendor/bin/pint
