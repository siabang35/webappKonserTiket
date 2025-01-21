'mysql_backup' => [
    'driver' => 'mysql',
    'host' => env('DB_BACKUP_HOST', '127.0.0.1'),
    'port' => env('DB_BACKUP_PORT', '3306'),
    'database' => env('DB_BACKUP_DATABASE', 'backup'),
    'username' => env('DB_BACKUP_USERNAME', 'backup_user'),
    'password' => env('DB_BACKUP_PASSWORD', ''),
],
