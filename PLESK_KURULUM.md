# Plesk Kurulum Kılavuzu

## 1. Dosyaları Hazırlama

### Yerel bilgisayarınızda:
```bash
# .env.example'dan production .env oluşturun
cp .env.example .env.production

# Gereksiz dosyaları temizleyin
rm -rf node_modules
rm -rf .git
rm -rf tests
rm -rf storage/logs/*
rm -rf storage/framework/cache/*
rm -rf storage/framework/sessions/*
rm -rf storage/framework/views/*
```

### .env.production dosyasını düzenleyin:
```env
APP_NAME=BetGround
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://sizin-domain.com

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=plesk_veritabani_adi
DB_USERNAME=plesk_kullanici_adi
DB_PASSWORD=plesk_sifre

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DRIVER=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

# SportsGameOdds API
SPORTS_API_KEY=c2af01689c2b194053fb461e45f52841
SPORTS_API_BASE_URL=https://api.sportsgameodds.com/v2
```

## 2. Plesk'te Domain Hazırlama

1. **Plesk panele giriş yapın**
2. **"Web Siteleri & Domainler"** > Domain'inizi seçin
3. **PHP Ayarları**:
   - PHP versiyonu: **8.0** veya üzeri
   - memory_limit: **256M**
   - max_execution_time: **300**
   - post_max_size: **100M**
   - upload_max_filesize: **100M**

4. **Document Root'u Değiştirme**:
   - "Hosting Ayarları" > "Document root": `/httpdocs/public`

## 3. Dosyaları Yükleme

### FTP ile:
1. FileZilla veya benzeri FTP programı kullanın
2. Plesk FTP bilgilerinizle bağlanın
3. Tüm dosyaları `/httpdocs/` klasörüne yükleyin
4. `.env.production` dosyasını `.env` olarak yeniden adlandırın

### Plesk File Manager ile:
1. "Dosyalar" sekmesine gidin
2. ZIP olarak sıkıştırılmış projeyi yükleyin
3. ZIP'i çıkarın
4. `.env.production` → `.env` olarak değiştirin

## 4. Veritabanı Kurulumu

1. **Plesk'te Veritabanı Oluşturma**:
   - "Veritabanları" > "Veritabanı Ekle"
   - Veritabanı adı, kullanıcı adı ve şifre belirleyin
   - Bu bilgileri `.env` dosyasına yazın

2. **phpMyAdmin'den SQL İmport**:
   - Plesk'te "Veritabanları" > phpMyAdmin'e git
   - Oluşturduğunuz veritabanını seçin
   - "İçe Aktar" (Import) sekmesi
   - Yerel bilgisayarınızdan `.sql` dosyasını seçin
   - "Git" butonuna tıklayın

## 5. SSH ile Composer ve Artisan Komutları

### SSH Erişimi Varsa:
```bash
cd /var/www/vhosts/sizin-domain.com/httpdocs

# Composer bağımlılıklarını yükle
composer install --optimize-autoloader --no-dev

# Laravel key oluştur
php artisan key:generate

# Cache temizle ve optimize et
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Storage link oluştur
php artisan storage:link

# İzinleri ayarla
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

### SSH Erişimi Yoksa (Scheduled Task ile):
1. Plesk > "Zamanlanmış Görevler" (Scheduled Tasks)
2. Yeni görev ekle:
   - Komut: `/usr/bin/php /var/www/vhosts/sizin-domain.com/httpdocs/artisan key:generate`
   - Bir kere çalıştır ve sil

## 6. İzin Ayarları

File Manager'dan:
- `storage` klasörü: 755 veya 775
- `bootstrap/cache` klasörü: 755 veya 775
- `.env` dosyası: 644

## 7. .htaccess Ayarları

`public/.htaccess` dosyasında şunların olduğundan emin olun:

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

# PHP ayarları (gerekirse)
php_value upload_max_filesize 100M
php_value post_max_size 100M
php_value max_execution_time 300
php_value max_input_time 300
```

## 8. SSL Sertifikası

1. Plesk > "SSL/TLS Sertifikaları"
2. "Let's Encrypt" ücretsiz sertifika al
3. "www ve domain'i koru" seçeneğini işaretle
4. Sertifikayı yükle

## 9. Cron Jobs (Zamanlanmış Görevler)

Laravel schedule çalıştırmak için:
1. "Zamanlanmış Görevler" > "Görev Ekle"
2. Komut: `/usr/bin/php /var/www/vhosts/sizin-domain.com/httpdocs/artisan schedule:run`
3. Çalışma sıklığı: Her dakika (* * * * *)

## 10. Troubleshooting

### Beyaz Ekran veya 500 Hatası:
1. `.env` dosyasını kontrol edin
2. `storage/logs/laravel.log` dosyasını kontrol edin
3. PHP error log'larını kontrol edin

### Veritabanı Bağlantı Hatası:
1. `.env` dosyasındaki DB bilgilerini kontrol edin
2. Veritabanı kullanıcısının yetkileri olduğundan emin olun

### Asset'ler Yüklenmiyor:
1. `APP_URL` değerinin doğru olduğundan emin olun
2. `php artisan storage:link` komutunu çalıştırın

## Önemli Notlar:

1. **Güvenlik**:
   - `.env` dosyasına web üzerinden erişim engellenmelidir
   - `APP_DEBUG=false` olmalıdır
   - Gereksiz dosyaları (`.git`, `tests` vb.) silmeyi unutmayın

2. **Performans**:
   - OPcache'i aktif edin
   - Redis veya Memcached kullanmayı düşünün
   - CDN entegrasyonu yapın

3. **Yedekleme**:
   - Düzenli veritabanı yedeği alın
   - Dosya yedeği alın
   - `.env` dosyasını güvenli bir yerde saklayın
