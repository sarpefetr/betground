# Supernovabet - Canlı Bahis Sistemi

Laravel tabanlı profesyonel canlı bahis platformu.

## Özellikler

- 🏆 Canlı maç takibi ve bahis sistemi
- ⚽ SportsGameOdds API entegrasyonu
- 📊 Manuel maç yönetimi
- 💰 Bahis kuponu ve bakiye sistemi
- 🎯 25+ bahis seçeneği
- 🔐 Güvenli admin paneli
- 📱 Responsive tasarım

## Kurulum

### Gereksinimler

- PHP >= 7.4
- MySQL >= 5.7
- Composer
- Node.js & NPM (opsiyonel)

### Adımlar

1. `.env` dosyasını oluşturun:
```bash
cp env.example .env
```

2. Veritabanı bilgilerini `.env` dosyasında güncelleyin

3. Bağımlılıkları yükleyin:
```bash
composer install --no-dev --optimize-autoloader
```

4. Uygulama anahtarı oluşturun:
```bash
php artisan key:generate
```

5. Veritabanı tablolarını oluşturun:
```bash
php artisan migrate
```

6. Storage linkini oluşturun:
```bash
php artisan storage:link
```

7. Cache'leri optimize edin:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Plesk Kurulumu

Detaylı kurulum için `PLESK_KURULUM.md` dosyasına bakın.

## Lisans

Bu proje özel lisanslıdır. Tüm hakları saklıdır.