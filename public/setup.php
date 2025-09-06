<?php
// KURULUM SONRASI BU DOSYAYI SİLİN!

// Laravel bootstrap
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "<h2>Laravel Setup Script</h2>";
echo "<pre>";

// Key generate
echo "1. Generating application key...\n";
Artisan::call('key:generate');
echo Artisan::output() . "\n";

// Storage link
echo "2. Creating storage link...\n";
Artisan::call('storage:link');
echo Artisan::output() . "\n";

// Migrate
echo "3. Running migrations...\n";
Artisan::call('migrate', ['--force' => true]);
echo Artisan::output() . "\n";

// Cache clear
echo "4. Clearing caches...\n";
Artisan::call('cache:clear');
echo Artisan::output() . "\n";

// Config cache
echo "5. Caching config...\n";
Artisan::call('config:cache');
echo Artisan::output() . "\n";

// Route cache
echo "6. Caching routes...\n";
Artisan::call('route:cache');
echo Artisan::output() . "\n";

// View cache
echo "7. Caching views...\n";
Artisan::call('view:cache');
echo Artisan::output() . "\n";

echo "</pre>";
echo "<h3 style='color: green;'>Setup completed!</h3>";
echo "<p style='color: red;'><strong>IMPORTANT:</strong> Delete this setup.php file immediately!</p>";
?>
