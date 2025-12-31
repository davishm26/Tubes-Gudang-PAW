<?php return array (
  'broadcasting' => 
  array (
    'default' => 'log',
    'connections' => 
    array (
      'reverb' => 
      array (
        'driver' => 'reverb',
        'key' => NULL,
        'secret' => NULL,
        'app_id' => NULL,
        'options' => 
        array (
          'host' => NULL,
          'port' => 443,
          'scheme' => 'https',
          'useTLS' => true,
        ),
        'client_options' => 
        array (
        ),
      ),
      'pusher' => 
      array (
        'driver' => 'pusher',
        'key' => NULL,
        'secret' => NULL,
        'app_id' => NULL,
        'options' => 
        array (
          'cluster' => NULL,
          'host' => 'api-mt1.pusher.com',
          'port' => 443,
          'scheme' => 'https',
          'encrypted' => true,
          'useTLS' => true,
        ),
        'client_options' => 
        array (
        ),
      ),
      'ably' => 
      array (
        'driver' => 'ably',
        'key' => NULL,
      ),
      'log' => 
      array (
        'driver' => 'log',
      ),
      'null' => 
      array (
        'driver' => 'null',
      ),
    ),
  ),
  'concurrency' => 
  array (
    'default' => 'process',
  ),
  'cors' => 
  array (
    'paths' => 
    array (
      0 => 'api/*',
      1 => 'sanctum/csrf-cookie',
    ),
    'allowed_methods' => 
    array (
      0 => '*',
    ),
    'allowed_origins' => 
    array (
      0 => '*',
    ),
    'allowed_origins_patterns' => 
    array (
    ),
    'allowed_headers' => 
    array (
      0 => '*',
    ),
    'exposed_headers' => 
    array (
    ),
    'max_age' => 0,
    'supports_credentials' => false,
  ),
  'hashing' => 
  array (
    'driver' => 'bcrypt',
    'bcrypt' => 
    array (
      'rounds' => '12',
      'verify' => true,
      'limit' => NULL,
    ),
    'argon' => 
    array (
      'memory' => 65536,
      'threads' => 1,
      'time' => 4,
      'verify' => true,
    ),
    'rehash_on_login' => true,
  ),
  'view' => 
  array (
    'paths' => 
    array (
      0 => 'D:\\Semester 3\\PAW\\TUBES\\tubes-gudang\\resources\\views',
    ),
    'compiled' => 'D:\\Semester 3\\PAW\\TUBES\\tubes-gudang\\storage\\framework\\views',
  ),
  'app' => 
  array (
    'name' => 'Laravel',
    'env' => 'local',
    'debug' => true,
    'url' => 'http://localhost',
    'frontend_url' => 'http://localhost:3000',
    'asset_url' => NULL,
    'timezone' => 'UTC',
    'locale' => 'en',
    'fallback_locale' => 'en',
    'faker_locale' => 'en_US',
    'cipher' => 'AES-256-CBC',
    'key' => 'base64:tDkyKryrsAf7NPXQdzCR7WSs8oQAxMyqB8/Ewsvwtdo=',
    'previous_keys' => 
    array (
    ),
    'maintenance' => 
    array (
      'driver' => 'file',
      'store' => 'database',
    ),
    'providers' => 
    array (
      0 => 'Illuminate\\Auth\\AuthServiceProvider',
      1 => 'Illuminate\\Broadcasting\\BroadcastServiceProvider',
      2 => 'Illuminate\\Bus\\BusServiceProvider',
      3 => 'Illuminate\\Cache\\CacheServiceProvider',
      4 => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
      5 => 'Illuminate\\Concurrency\\ConcurrencyServiceProvider',
      6 => 'Illuminate\\Cookie\\CookieServiceProvider',
      7 => 'Illuminate\\Database\\DatabaseServiceProvider',
      8 => 'Illuminate\\Encryption\\EncryptionServiceProvider',
      9 => 'Illuminate\\Filesystem\\FilesystemServiceProvider',
      10 => 'Illuminate\\Foundation\\Providers\\FoundationServiceProvider',
      11 => 'Illuminate\\Hashing\\HashServiceProvider',
      12 => 'Illuminate\\Mail\\MailServiceProvider',
      13 => 'Illuminate\\Notifications\\NotificationServiceProvider',
      14 => 'Illuminate\\Pagination\\PaginationServiceProvider',
      15 => 'Illuminate\\Auth\\Passwords\\PasswordResetServiceProvider',
      16 => 'Illuminate\\Pipeline\\PipelineServiceProvider',
      17 => 'Illuminate\\Queue\\QueueServiceProvider',
      18 => 'Illuminate\\Redis\\RedisServiceProvider',
      19 => 'Illuminate\\Session\\SessionServiceProvider',
      20 => 'Illuminate\\Translation\\TranslationServiceProvider',
      21 => 'Illuminate\\Validation\\ValidationServiceProvider',
      22 => 'Illuminate\\View\\ViewServiceProvider',
      23 => 'App\\Providers\\AppServiceProvider',
    ),
    'aliases' => 
    array (
      'App' => 'Illuminate\\Support\\Facades\\App',
      'Arr' => 'Illuminate\\Support\\Arr',
      'Artisan' => 'Illuminate\\Support\\Facades\\Artisan',
      'Auth' => 'Illuminate\\Support\\Facades\\Auth',
      'Benchmark' => 'Illuminate\\Support\\Benchmark',
      'Blade' => 'Illuminate\\Support\\Facades\\Blade',
      'Broadcast' => 'Illuminate\\Support\\Facades\\Broadcast',
      'Bus' => 'Illuminate\\Support\\Facades\\Bus',
      'Cache' => 'Illuminate\\Support\\Facades\\Cache',
      'Concurrency' => 'Illuminate\\Support\\Facades\\Concurrency',
      'Config' => 'Illuminate\\Support\\Facades\\Config',
      'Context' => 'Illuminate\\Support\\Facades\\Context',
      'Cookie' => 'Illuminate\\Support\\Facades\\Cookie',
      'Crypt' => 'Illuminate\\Support\\Facades\\Crypt',
      'Date' => 'Illuminate\\Support\\Facades\\Date',
      'DB' => 'Illuminate\\Support\\Facades\\DB',
      'Eloquent' => 'Illuminate\\Database\\Eloquent\\Model',
      'Event' => 'Illuminate\\Support\\Facades\\Event',
      'File' => 'Illuminate\\Support\\Facades\\File',
      'Gate' => 'Illuminate\\Support\\Facades\\Gate',
      'Hash' => 'Illuminate\\Support\\Facades\\Hash',
      'Http' => 'Illuminate\\Support\\Facades\\Http',
      'Js' => 'Illuminate\\Support\\Js',
      'Lang' => 'Illuminate\\Support\\Facades\\Lang',
      'Log' => 'Illuminate\\Support\\Facades\\Log',
      'Mail' => 'Illuminate\\Support\\Facades\\Mail',
      'Notification' => 'Illuminate\\Support\\Facades\\Notification',
      'Number' => 'Illuminate\\Support\\Number',
      'Password' => 'Illuminate\\Support\\Facades\\Password',
      'Process' => 'Illuminate\\Support\\Facades\\Process',
      'Queue' => 'Illuminate\\Support\\Facades\\Queue',
      'RateLimiter' => 'Illuminate\\Support\\Facades\\RateLimiter',
      'Redirect' => 'Illuminate\\Support\\Facades\\Redirect',
      'Request' => 'Illuminate\\Support\\Facades\\Request',
      'Response' => 'Illuminate\\Support\\Facades\\Response',
      'Route' => 'Illuminate\\Support\\Facades\\Route',
      'Schedule' => 'Illuminate\\Support\\Facades\\Schedule',
      'Schema' => 'Illuminate\\Support\\Facades\\Schema',
      'Session' => 'Illuminate\\Support\\Facades\\Session',
      'Storage' => 'Illuminate\\Support\\Facades\\Storage',
      'Str' => 'Illuminate\\Support\\Str',
      'Uri' => 'Illuminate\\Support\\Uri',
      'URL' => 'Illuminate\\Support\\Facades\\URL',
      'Validator' => 'Illuminate\\Support\\Facades\\Validator',
      'View' => 'Illuminate\\Support\\Facades\\View',
      'Vite' => 'Illuminate\\Support\\Facades\\Vite',
    ),
  ),
  'auth' => 
  array (
    'defaults' => 
    array (
      'guard' => 'web',
      'passwords' => 'users',
    ),
    'guards' => 
    array (
      'web' => 
      array (
        'driver' => 'session',
        'provider' => 'users',
      ),
    ),
    'providers' => 
    array (
      'users' => 
      array (
        'driver' => 'eloquent',
        'model' => 'App\\Models\\User',
      ),
    ),
    'passwords' => 
    array (
      'users' => 
      array (
        'provider' => 'users',
        'table' => 'password_reset_tokens',
        'expire' => 60,
        'throttle' => 60,
      ),
    ),
    'password_timeout' => 10800,
  ),
  'cache' => 
  array (
    'default' => 'database',
    'stores' => 
    array (
      'array' => 
      array (
        'driver' => 'array',
        'serialize' => false,
      ),
      'session' => 
      array (
        'driver' => 'session',
        'key' => '_cache',
      ),
      'database' => 
      array (
        'driver' => 'database',
        'connection' => NULL,
        'table' => 'cache',
        'lock_connection' => NULL,
        'lock_table' => NULL,
      ),
      'file' => 
      array (
        'driver' => 'file',
        'path' => 'D:\\Semester 3\\PAW\\TUBES\\tubes-gudang\\storage\\framework/cache/data',
        'lock_path' => 'D:\\Semester 3\\PAW\\TUBES\\tubes-gudang\\storage\\framework/cache/data',
      ),
      'memcached' => 
      array (
        'driver' => 'memcached',
        'persistent_id' => NULL,
        'sasl' => 
        array (
          0 => NULL,
          1 => NULL,
        ),
        'options' => 
        array (
        ),
        'servers' => 
        array (
          0 => 
          array (
            'host' => '127.0.0.1',
            'port' => 11211,
            'weight' => 100,
          ),
        ),
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'cache',
        'lock_connection' => 'default',
      ),
      'dynamodb' => 
      array (
        'driver' => 'dynamodb',
        'key' => '',
        'secret' => '',
        'region' => 'us-east-1',
        'table' => 'cache',
        'endpoint' => NULL,
      ),
      'octane' => 
      array (
        'driver' => 'octane',
      ),
      'failover' => 
      array (
        'driver' => 'failover',
        'stores' => 
        array (
          0 => 'database',
          1 => 'array',
        ),
      ),
    ),
    'prefix' => 'laravel-cache-',
  ),
  'database' => 
  array (
    'default' => 'mysql',
    'connections' => 
    array (
      'sqlite' => 
      array (
        'driver' => 'sqlite',
        'url' => NULL,
        'database' => 'db_manajemen_gudang',
        'prefix' => '',
        'foreign_key_constraints' => true,
        'busy_timeout' => NULL,
        'journal_mode' => NULL,
        'synchronous' => NULL,
        'transaction_mode' => 'DEFERRED',
      ),
      'mysql' => 
      array (
        'driver' => 'mysql',
        'url' => NULL,
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => 'db_manajemen_gudang',
        'username' => 'root',
        'password' => '',
        'unix_socket' => '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'prefix_indexes' => true,
        'strict' => true,
        'engine' => NULL,
        'options' => 
        array (
        ),
      ),
      'mariadb' => 
      array (
        'driver' => 'mariadb',
        'url' => NULL,
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => 'db_manajemen_gudang',
        'username' => 'root',
        'password' => '',
        'unix_socket' => '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'prefix_indexes' => true,
        'strict' => true,
        'engine' => NULL,
        'options' => 
        array (
        ),
      ),
      'pgsql' => 
      array (
        'driver' => 'pgsql',
        'url' => NULL,
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => 'db_manajemen_gudang',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
        'search_path' => 'public',
        'sslmode' => 'prefer',
      ),
      'sqlsrv' => 
      array (
        'driver' => 'sqlsrv',
        'url' => NULL,
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => 'db_manajemen_gudang',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8',
        'prefix' => '',
        'prefix_indexes' => true,
      ),
    ),
    'migrations' => 
    array (
      'table' => 'migrations',
      'update_date_on_publish' => true,
    ),
    'redis' => 
    array (
      'client' => 'phpredis',
      'options' => 
      array (
        'cluster' => 'redis',
        'prefix' => 'laravel-database-',
        'persistent' => false,
      ),
      'default' => 
      array (
        'url' => NULL,
        'host' => '127.0.0.1',
        'username' => NULL,
        'password' => NULL,
        'port' => '6379',
        'database' => '0',
        'max_retries' => 3,
        'backoff_algorithm' => 'decorrelated_jitter',
        'backoff_base' => 100,
        'backoff_cap' => 1000,
      ),
      'cache' => 
      array (
        'url' => NULL,
        'host' => '127.0.0.1',
        'username' => NULL,
        'password' => NULL,
        'port' => '6379',
        'database' => '1',
        'max_retries' => 3,
        'backoff_algorithm' => 'decorrelated_jitter',
        'backoff_base' => 100,
        'backoff_cap' => 1000,
      ),
    ),
  ),
  'demo_data' => 
  array (
    'categories' => 
    array (
      0 => 
      array (
        'id' => 1,
        'name' => 'Monitor',
        'description' => 'Monitor komputer dan display',
      ),
      1 => 
      array (
        'id' => 2,
        'name' => 'Laptop',
        'description' => 'Laptop dan notebook',
      ),
      2 => 
      array (
        'id' => 3,
        'name' => 'Keyboard',
        'description' => 'Keyboard dan input devices',
      ),
      3 => 
      array (
        'id' => 4,
        'name' => 'Mouse',
        'description' => 'Mouse dan pointing devices',
      ),
      4 => 
      array (
        'id' => 5,
        'name' => 'Headset',
        'description' => 'Headset dan audio devices',
      ),
    ),
    'suppliers' => 
    array (
      0 => 
      array (
        'id' => 1,
        'name' => 'PT. Monitor Technik',
        'contact' => '081234567890',
      ),
      1 => 
      array (
        'id' => 2,
        'name' => 'CV. Laptop Center',
        'contact' => '082345678901',
      ),
      2 => 
      array (
        'id' => 3,
        'name' => 'UD. Keyboard Pro',
        'contact' => '083456789012',
      ),
      3 => 
      array (
        'id' => 4,
        'name' => 'PT. Mouse Devices',
        'contact' => '084567890123',
      ),
      4 => 
      array (
        'id' => 5,
        'name' => 'Toko Headset Premium',
        'contact' => '085678901234',
      ),
    ),
    'products' => 
    array (
      0 => 
      array (
        'id' => 1,
        'name' => 'Monitor LED 24" Full HD',
        'code' => 'MONITOR-001',
        'category_id' => 1,
        'category_name' => 'Monitor',
        'supplier_id' => 1,
        'supplier_name' => 'PT. Monitor Technik',
        'price' => 2150000,
        'stock' => 28,
        'unit' => 'pcs',
        'description' => 'Monitor LED dengan resolusi Full HD dan panel IPS berkualitas tinggi',
        'image' => 'https://images.pexels.com/photos/777001/pexels-photo-777001.jpeg?auto=compress&cs=tinysrgb&w=400',
      ),
      1 => 
      array (
        'id' => 2,
        'name' => 'Monitor Curved 27" 144Hz',
        'code' => 'MONITOR-002',
        'category_id' => 1,
        'category_name' => 'Monitor',
        'supplier_id' => 1,
        'supplier_name' => 'PT. Monitor Technik',
        'price' => 4250000,
        'stock' => 15,
        'unit' => 'pcs',
        'description' => 'Monitor curved dengan refresh rate 144Hz untuk gaming profesional',
        'image' => 'https://images.pexels.com/photos/1229861/pexels-photo-1229861.jpeg?auto=compress&cs=tinysrgb&w=400',
      ),
      2 => 
      array (
        'id' => 3,
        'name' => 'Laptop Gaming Pro 15"',
        'code' => 'LAPTOP-001',
        'category_id' => 2,
        'category_name' => 'Laptop',
        'supplier_id' => 2,
        'supplier_name' => 'CV. Laptop Center',
        'price' => 18500000,
        'stock' => 12,
        'unit' => 'pcs',
        'description' => 'Laptop gaming dengan spesifikasi tinggi untuk kebutuhan profesional',
        'image' => 'https://images.pexels.com/photos/18105/pexels-photo.jpg?auto=compress&cs=tinysrgb&w=400',
      ),
      3 => 
      array (
        'id' => 4,
        'name' => 'Laptop Ultrabook 13" I7',
        'code' => 'LAPTOP-002',
        'category_id' => 2,
        'category_name' => 'Laptop',
        'supplier_id' => 2,
        'supplier_name' => 'CV. Laptop Center',
        'price' => 12500000,
        'stock' => 20,
        'unit' => 'pcs',
        'description' => 'Laptop ultrabook ringan dengan prosesor Intel i7 gen terbaru',
        'image' => 'https://images.pexels.com/photos/18105/pexels-photo.jpg?auto=compress&cs=tinysrgb&w=400',
      ),
      4 => 
      array (
        'id' => 5,
        'name' => 'Keyboard Mechanical RGB',
        'code' => 'KEYBOARD-001',
        'category_id' => 3,
        'category_name' => 'Keyboard',
        'supplier_id' => 3,
        'supplier_name' => 'UD. Keyboard Pro',
        'price' => 1250000,
        'stock' => 45,
        'unit' => 'pcs',
        'description' => 'Keyboard mechanical dengan RGB backlight dan switch berkualitas',
        'image' => 'https://images.pexels.com/photos/1772123/pexels-photo-1772123.jpeg?auto=compress&cs=tinysrgb&w=400',
      ),
      5 => 
      array (
        'id' => 6,
        'name' => 'Keyboard Wireless Compact',
        'code' => 'KEYBOARD-002',
        'category_id' => 3,
        'category_name' => 'Keyboard',
        'supplier_id' => 3,
        'supplier_name' => 'UD. Keyboard Pro',
        'price' => 650000,
        'stock' => 60,
        'unit' => 'pcs',
        'description' => 'Keyboard wireless ringkas dengan baterai tahan lama',
        'image' => 'https://images.pexels.com/photos/1772123/pexels-photo-1772123.jpeg?auto=compress&cs=tinysrgb&w=400',
      ),
      6 => 
      array (
        'id' => 7,
        'name' => 'Mouse Wireless Pro',
        'code' => 'MOUSE-001',
        'category_id' => 4,
        'category_name' => 'Mouse',
        'supplier_id' => 4,
        'supplier_name' => 'PT. Mouse Devices',
        'price' => 650000,
        'stock' => 87,
        'unit' => 'pcs',
        'description' => 'Mouse wireless dengan teknologi tracking presisi untuk produktivitas maksimal',
        'image' => 'https://images.pexels.com/photos/2115256/pexels-photo-2115256.jpeg?auto=compress&cs=tinysrgb&w=400',
      ),
      7 => 
      array (
        'id' => 8,
        'name' => 'Mouse Gaming RGB 8 Button',
        'code' => 'MOUSE-002',
        'category_id' => 4,
        'category_name' => 'Mouse',
        'supplier_id' => 4,
        'supplier_name' => 'PT. Mouse Devices',
        'price' => 950000,
        'stock' => 52,
        'unit' => 'pcs',
        'description' => 'Mouse gaming dengan 8 tombol programmable dan sensor DPI tinggi',
        'image' => 'https://images.pexels.com/photos/2115256/pexels-photo-2115256.jpeg?auto=compress&cs=tinysrgb&w=400',
      ),
      8 => 
      array (
        'id' => 9,
        'name' => 'Headset Gaming Stereo',
        'code' => 'HEADSET-001',
        'category_id' => 5,
        'category_name' => 'Headset',
        'supplier_id' => 5,
        'supplier_name' => 'Toko Headset Premium',
        'price' => 950000,
        'stock' => 3,
        'unit' => 'pcs',
        'description' => 'Headset gaming dengan audio surround dan microphone noise cancelling',
        'image' => 'https://images.pexels.com/photos/3587478/pexels-photo-3587478.jpeg?auto=compress&cs=tinysrgb&w=400',
      ),
      9 => 
      array (
        'id' => 10,
        'name' => 'Headset Wireless Premium',
        'code' => 'HEADSET-002',
        'category_id' => 5,
        'category_name' => 'Headset',
        'supplier_id' => 5,
        'supplier_name' => 'Toko Headset Premium',
        'price' => 2250000,
        'stock' => 0,
        'unit' => 'pcs',
        'description' => 'Headset wireless premium dengan driver audio 40mm dan noise cancellation aktif',
        'image' => 'https://images.pexels.com/photos/3587478/pexels-photo-3587478.jpeg?auto=compress&cs=tinysrgb&w=400',
      ),
    ),
    'inventory_ins' => 
    array (
      0 => 
      array (
        'id' => 1,
        'product_id' => 1,
        'product_name' => 'Monitor LED 24" Full HD',
        'quantity' => 20,
        'date' => '2025-01-15',
        'notes' => 'Stok awal bulan',
      ),
      1 => 
      array (
        'id' => 2,
        'product_id' => 2,
        'product_name' => 'Monitor Curved 27" 144Hz',
        'quantity' => 10,
        'date' => '2025-01-16',
        'notes' => 'Restok dari supplier',
      ),
      2 => 
      array (
        'id' => 3,
        'product_id' => 3,
        'product_name' => 'Laptop Gaming Pro 15"',
        'quantity' => 8,
        'date' => '2025-01-17',
        'notes' => 'Pengiriman dari CV. Laptop Center',
      ),
      3 => 
      array (
        'id' => 4,
        'product_id' => 4,
        'product_name' => 'Laptop Ultrabook 13" I7',
        'quantity' => 15,
        'date' => '2025-01-18',
        'notes' => 'Batch baru',
      ),
      4 => 
      array (
        'id' => 5,
        'product_id' => 5,
        'product_name' => 'Keyboard Mechanical RGB',
        'quantity' => 30,
        'date' => '2025-01-19',
        'notes' => 'Stok keyboard gaming',
      ),
      5 => 
      array (
        'id' => 6,
        'product_id' => 6,
        'product_name' => 'Keyboard Wireless Compact',
        'quantity' => 40,
        'date' => '2025-01-20',
        'notes' => 'Restok keyboard wireless',
      ),
      6 => 
      array (
        'id' => 7,
        'product_id' => 7,
        'product_name' => 'Mouse Wireless Pro',
        'quantity' => 50,
        'date' => '2025-01-21',
        'notes' => 'Pengiriman awal bulan',
      ),
      7 => 
      array (
        'id' => 8,
        'product_id' => 8,
        'product_name' => 'Mouse Gaming RGB 8 Button',
        'quantity' => 35,
        'date' => '2025-01-22',
        'notes' => 'Stock gaming mouse',
      ),
      8 => 
      array (
        'id' => 9,
        'product_id' => 9,
        'product_name' => 'Headset Gaming Stereo',
        'quantity' => 25,
        'date' => '2025-01-23',
        'notes' => 'Headset gaming stock',
      ),
      9 => 
      array (
        'id' => 10,
        'product_id' => 10,
        'product_name' => 'Headset Wireless Premium',
        'quantity' => 15,
        'date' => '2025-01-24',
        'notes' => 'Premium headset batch',
      ),
      10 => 
      array (
        'id' => 11,
        'product_id' => 1,
        'product_name' => 'Monitor LED 24" Full HD',
        'quantity' => 15,
        'date' => '2025-02-01',
        'notes' => 'Restok monitor februari',
      ),
      11 => 
      array (
        'id' => 12,
        'product_id' => 3,
        'product_name' => 'Laptop Gaming Pro 15"',
        'quantity' => 5,
        'date' => '2025-02-03',
        'notes' => 'Tambahan stok laptop gaming',
      ),
      12 => 
      array (
        'id' => 13,
        'product_id' => 5,
        'product_name' => 'Keyboard Mechanical RGB',
        'quantity' => 20,
        'date' => '2025-02-05',
        'notes' => 'Restock keyboard mekanik',
      ),
      13 => 
      array (
        'id' => 14,
        'product_id' => 7,
        'product_name' => 'Mouse Wireless Pro',
        'quantity' => 30,
        'date' => '2025-02-07',
        'notes' => 'Pengadaan mouse wireless',
      ),
      14 => 
      array (
        'id' => 15,
        'product_id' => 2,
        'product_name' => 'Monitor Curved 27" 144Hz',
        'quantity' => 8,
        'date' => '2025-02-10',
        'notes' => 'Monitor gaming restok',
      ),
      15 => 
      array (
        'id' => 16,
        'product_id' => 4,
        'product_name' => 'Laptop Ultrabook 13" I7',
        'quantity' => 10,
        'date' => '2025-02-12',
        'notes' => 'Ultrabook batch kedua',
      ),
      16 => 
      array (
        'id' => 17,
        'product_id' => 6,
        'product_name' => 'Keyboard Wireless Compact',
        'quantity' => 25,
        'date' => '2025-02-15',
        'notes' => 'Keyboard wireless tambahan',
      ),
      17 => 
      array (
        'id' => 18,
        'product_id' => 8,
        'product_name' => 'Mouse Gaming RGB 8 Button',
        'quantity' => 18,
        'date' => '2025-02-17',
        'notes' => 'Gaming mouse restock',
      ),
      18 => 
      array (
        'id' => 19,
        'product_id' => 9,
        'product_name' => 'Headset Gaming Stereo',
        'quantity' => 12,
        'date' => '2025-02-20',
        'notes' => 'Headset gaming pengadaan',
      ),
      19 => 
      array (
        'id' => 20,
        'product_id' => 10,
        'product_name' => 'Headset Wireless Premium',
        'quantity' => 8,
        'date' => '2025-02-22',
        'notes' => 'Premium headset tambahan',
      ),
    ),
    'inventory_outs' => 
    array (
      0 => 
      array (
        'id' => 1,
        'product_id' => 1,
        'product_name' => 'Monitor LED 24" Full HD',
        'quantity' => 5,
        'date' => '2025-01-25',
        'description' => 'Penjualan ke perusahaan ABC',
      ),
      1 => 
      array (
        'id' => 2,
        'product_id' => 3,
        'product_name' => 'Laptop Gaming Pro 15"',
        'quantity' => 2,
        'date' => '2025-01-26',
        'description' => 'Order dari toko retail',
      ),
      2 => 
      array (
        'id' => 3,
        'product_id' => 5,
        'product_name' => 'Keyboard Mechanical RGB',
        'quantity' => 8,
        'date' => '2025-01-27',
        'description' => 'Penjualan online',
      ),
      3 => 
      array (
        'id' => 4,
        'product_id' => 7,
        'product_name' => 'Mouse Wireless Pro',
        'quantity' => 12,
        'date' => '2025-01-28',
        'description' => 'Pengiriman ke distributor',
      ),
      4 => 
      array (
        'id' => 5,
        'product_id' => 9,
        'product_name' => 'Headset Gaming Stereo',
        'quantity' => 6,
        'date' => '2025-01-29',
        'description' => 'Penjualan retail',
      ),
    ),
    'user' => 
    array (
      'admin' => 
      array (
        'id' => 999,
        'name' => 'Demo Admin',
        'email' => 'admin@demo.com',
        'role' => 'admin',
      ),
      'staff' => 
      array (
        'id' => 998,
        'name' => 'Demo Staff',
        'email' => 'staff@demo.com',
        'role' => 'staff',
      ),
    ),
  ),
  'filesystems' => 
  array (
    'default' => 'local',
    'disks' => 
    array (
      'local' => 
      array (
        'driver' => 'local',
        'root' => 'D:\\Semester 3\\PAW\\TUBES\\tubes-gudang\\storage\\app/private',
        'serve' => true,
        'throw' => false,
        'report' => false,
      ),
      'public' => 
      array (
        'driver' => 'local',
        'root' => 'D:\\Semester 3\\PAW\\TUBES\\tubes-gudang\\storage\\app/public',
        'url' => 'http://localhost/storage',
        'visibility' => 'public',
        'throw' => false,
        'report' => false,
      ),
      's3' => 
      array (
        'driver' => 's3',
        'key' => '',
        'secret' => '',
        'region' => 'us-east-1',
        'bucket' => '',
        'url' => NULL,
        'endpoint' => NULL,
        'use_path_style_endpoint' => false,
        'throw' => false,
        'report' => false,
      ),
    ),
    'links' => 
    array (
      'D:\\Semester 3\\PAW\\TUBES\\tubes-gudang\\public\\storage' => 'D:\\Semester 3\\PAW\\TUBES\\tubes-gudang\\storage\\app/public',
    ),
  ),
  'logging' => 
  array (
    'default' => 'stack',
    'deprecations' => 
    array (
      'channel' => NULL,
      'trace' => false,
    ),
    'channels' => 
    array (
      'stack' => 
      array (
        'driver' => 'stack',
        'channels' => 
        array (
          0 => 'single',
        ),
        'ignore_exceptions' => false,
      ),
      'single' => 
      array (
        'driver' => 'single',
        'path' => 'D:\\Semester 3\\PAW\\TUBES\\tubes-gudang\\storage\\logs/laravel.log',
        'level' => 'debug',
        'replace_placeholders' => true,
      ),
      'daily' => 
      array (
        'driver' => 'daily',
        'path' => 'D:\\Semester 3\\PAW\\TUBES\\tubes-gudang\\storage\\logs/laravel.log',
        'level' => 'debug',
        'days' => 14,
        'replace_placeholders' => true,
      ),
      'slack' => 
      array (
        'driver' => 'slack',
        'url' => NULL,
        'username' => 'Laravel Log',
        'emoji' => ':boom:',
        'level' => 'debug',
        'replace_placeholders' => true,
      ),
      'papertrail' => 
      array (
        'driver' => 'monolog',
        'level' => 'debug',
        'handler' => 'Monolog\\Handler\\SyslogUdpHandler',
        'handler_with' => 
        array (
          'host' => NULL,
          'port' => NULL,
          'connectionString' => 'tls://:',
        ),
        'processors' => 
        array (
          0 => 'Monolog\\Processor\\PsrLogMessageProcessor',
        ),
      ),
      'stderr' => 
      array (
        'driver' => 'monolog',
        'level' => 'debug',
        'handler' => 'Monolog\\Handler\\StreamHandler',
        'handler_with' => 
        array (
          'stream' => 'php://stderr',
        ),
        'formatter' => NULL,
        'processors' => 
        array (
          0 => 'Monolog\\Processor\\PsrLogMessageProcessor',
        ),
      ),
      'syslog' => 
      array (
        'driver' => 'syslog',
        'level' => 'debug',
        'facility' => 8,
        'replace_placeholders' => true,
      ),
      'errorlog' => 
      array (
        'driver' => 'errorlog',
        'level' => 'debug',
        'replace_placeholders' => true,
      ),
      'null' => 
      array (
        'driver' => 'monolog',
        'handler' => 'Monolog\\Handler\\NullHandler',
      ),
      'emergency' => 
      array (
        'path' => 'D:\\Semester 3\\PAW\\TUBES\\tubes-gudang\\storage\\logs/laravel.log',
      ),
    ),
  ),
  'mail' => 
  array (
    'default' => 'log',
    'mailers' => 
    array (
      'smtp' => 
      array (
        'transport' => 'smtp',
        'scheme' => NULL,
        'url' => NULL,
        'host' => '127.0.0.1',
        'port' => '2525',
        'username' => NULL,
        'password' => NULL,
        'timeout' => NULL,
        'local_domain' => 'localhost',
      ),
      'ses' => 
      array (
        'transport' => 'ses',
      ),
      'postmark' => 
      array (
        'transport' => 'postmark',
      ),
      'resend' => 
      array (
        'transport' => 'resend',
      ),
      'sendmail' => 
      array (
        'transport' => 'sendmail',
        'path' => '/usr/sbin/sendmail -bs -i',
      ),
      'log' => 
      array (
        'transport' => 'log',
        'channel' => NULL,
      ),
      'array' => 
      array (
        'transport' => 'array',
      ),
      'failover' => 
      array (
        'transport' => 'failover',
        'mailers' => 
        array (
          0 => 'smtp',
          1 => 'log',
        ),
        'retry_after' => 60,
      ),
      'roundrobin' => 
      array (
        'transport' => 'roundrobin',
        'mailers' => 
        array (
          0 => 'ses',
          1 => 'postmark',
        ),
        'retry_after' => 60,
      ),
    ),
    'from' => 
    array (
      'address' => 'hello@example.com',
      'name' => 'Laravel',
    ),
    'markdown' => 
    array (
      'theme' => 'default',
      'paths' => 
      array (
        0 => 'D:\\Semester 3\\PAW\\TUBES\\tubes-gudang\\resources\\views/vendor/mail',
      ),
    ),
  ),
  'queue' => 
  array (
    'default' => 'database',
    'connections' => 
    array (
      'sync' => 
      array (
        'driver' => 'sync',
      ),
      'database' => 
      array (
        'driver' => 'database',
        'connection' => NULL,
        'table' => 'jobs',
        'queue' => 'default',
        'retry_after' => 90,
        'after_commit' => false,
      ),
      'beanstalkd' => 
      array (
        'driver' => 'beanstalkd',
        'host' => 'localhost',
        'queue' => 'default',
        'retry_after' => 90,
        'block_for' => 0,
        'after_commit' => false,
      ),
      'sqs' => 
      array (
        'driver' => 'sqs',
        'key' => '',
        'secret' => '',
        'prefix' => 'https://sqs.us-east-1.amazonaws.com/your-account-id',
        'queue' => 'default',
        'suffix' => NULL,
        'region' => 'us-east-1',
        'after_commit' => false,
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'default',
        'queue' => 'default',
        'retry_after' => 90,
        'block_for' => NULL,
        'after_commit' => false,
      ),
      'deferred' => 
      array (
        'driver' => 'deferred',
      ),
      'failover' => 
      array (
        'driver' => 'failover',
        'connections' => 
        array (
          0 => 'database',
          1 => 'deferred',
        ),
      ),
      'background' => 
      array (
        'driver' => 'background',
      ),
    ),
    'batching' => 
    array (
      'database' => 'mysql',
      'table' => 'job_batches',
    ),
    'failed' => 
    array (
      'driver' => 'database-uuids',
      'database' => 'mysql',
      'table' => 'failed_jobs',
    ),
  ),
  'services' => 
  array (
    'postmark' => 
    array (
      'key' => NULL,
    ),
    'resend' => 
    array (
      'key' => NULL,
    ),
    'ses' => 
    array (
      'key' => '',
      'secret' => '',
      'region' => 'us-east-1',
    ),
    'slack' => 
    array (
      'notifications' => 
      array (
        'bot_user_oauth_token' => NULL,
        'channel' => NULL,
      ),
    ),
  ),
  'session' => 
  array (
    'driver' => 'database',
    'lifetime' => 120,
    'expire_on_close' => false,
    'encrypt' => false,
    'files' => 'D:\\Semester 3\\PAW\\TUBES\\tubes-gudang\\storage\\framework/sessions',
    'connection' => NULL,
    'table' => 'sessions',
    'store' => NULL,
    'lottery' => 
    array (
      0 => 2,
      1 => 100,
    ),
    'cookie' => 'laravel-session',
    'path' => '/',
    'domain' => NULL,
    'secure' => NULL,
    'http_only' => true,
    'same_site' => 'lax',
    'partitioned' => false,
  ),
  'dompdf' => 
  array (
    'show_warnings' => false,
    'public_path' => NULL,
    'convert_entities' => true,
    'options' => 
    array (
      'font_dir' => 'D:\\Semester 3\\PAW\\TUBES\\tubes-gudang\\storage\\fonts',
      'font_cache' => 'D:\\Semester 3\\PAW\\TUBES\\tubes-gudang\\storage\\fonts',
      'temp_dir' => 'C:\\Users\\davis\\AppData\\Local\\Temp',
      'chroot' => 'D:\\Semester 3\\PAW\\TUBES\\tubes-gudang',
      'allowed_protocols' => 
      array (
        'data://' => 
        array (
          'rules' => 
          array (
          ),
        ),
        'file://' => 
        array (
          'rules' => 
          array (
          ),
        ),
        'http://' => 
        array (
          'rules' => 
          array (
          ),
        ),
        'https://' => 
        array (
          'rules' => 
          array (
          ),
        ),
      ),
      'artifactPathValidation' => NULL,
      'log_output_file' => NULL,
      'enable_font_subsetting' => false,
      'pdf_backend' => 'CPDF',
      'default_media_type' => 'screen',
      'default_paper_size' => 'a4',
      'default_paper_orientation' => 'portrait',
      'default_font' => 'serif',
      'dpi' => 96,
      'enable_php' => false,
      'enable_javascript' => true,
      'enable_remote' => false,
      'allowed_remote_hosts' => NULL,
      'font_height_ratio' => 1.1,
      'enable_html5_parser' => true,
    ),
  ),
  'tinker' => 
  array (
    'commands' => 
    array (
    ),
    'alias' => 
    array (
    ),
    'dont_alias' => 
    array (
      0 => 'App\\Nova',
    ),
  ),
);
