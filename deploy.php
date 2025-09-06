<?php
/**
 * Plesk Deploy Script
 * Bu dosyayı public_html klasörüne koyun ve deploy sonrası çalıştırın
 */

// Proje kök dizinine git
chdir('..');

// Composer bağımlılıklarını yükle
echo "Installing composer dependencies...\n";
exec('composer install --no-dev --optimize-autoloader 2>&1', $output);
echo implode("\n", $output) . "\n\n";

// .env dosyasını oluştur
if (!file_exists('.env')) {
    echo "Creating .env file...\n";
    copy('.env.example', '.env');
    
    // Uygulama anahtarı oluştur
    exec('php artisan key:generate 2>&1', $output);
    echo implode("\n", $output) . "\n\n";
}

// Storage linkini oluştur
echo "Creating storage link...\n";
exec('php artisan storage:link 2>&1', $output);
echo implode("\n", $output) . "\n\n";

// Cache'leri temizle
echo "Clearing caches...\n";
exec('php artisan cache:clear 2>&1', $output);
echo implode("\n", $output) . "\n\n";

exec('php artisan config:clear 2>&1', $output);
echo implode("\n", $output) . "\n\n";

exec('php artisan view:clear 2>&1', $output);
echo implode("\n", $output) . "\n\n";

// Optimize et
echo "Optimizing application...\n";
exec('php artisan optimize 2>&1', $output);
echo implode("\n", $output) . "\n\n";

echo "Deployment completed successfully!\n";
echo "\nNOTE: Don't forget to:\n";
echo "1. Update .env file with production database credentials\n";
echo "2. Run migrations: php artisan migrate\n";
echo "3. Set proper file permissions (755 for directories, 644 for files)\n";
