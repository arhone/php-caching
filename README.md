# Cache

Кэширование.

# Установка

```composer require arhone/caching```

Кэш на файлах
```php
<?php
use arhone\caching\CacheFileSystemAdapter;
include 'vendor/autoload.php';

$Cache = new CacheFileSystemAdapter();
```

Кэш в Redis
```php
<?php
use arhone\caching\CacheRedisAdapter;
include 'vendor/autoload.php';

$Redis = new \Redis();
$Redis->connect('localhost');
$Cache = new CacheRedisAdapter($Redis);
```

Кэш в Memcached
```php
<?php
use arhone\caching\CacheMemcachedAdapter;
include 'vendor/autoload.php';

$Memcached = new \Memcached();
$Memcached->connect('localhost');
$Cache = new CacheMemcachedAdapter($Memcached);
```

# Пример

```
$Cache->get(string $key); // Возвращает кэш по ключу
$Cache->set(string $key, $data, int $interval = null); // Сохраняет кэш по ключу. Можно задать время жизни в секундах.
```

```php
<?php
use arhone\caching\CacheFileSystemAdapter;
include 'vendor/autoload.php';

$Cache = new CacheFileSystemAdapter();

if (!$data = $Cache->get('key')) {
    
    $data = 'Привет'; // Какой то сложный код, получающий данные
    $Cache->set('key', $data);
    
}

echo $data;
```

```
$Cache->delete(string $key); // Удаляет кэш по ключу
$Cache->clear(); // Очищает весь кэш.
$Cache->has(string $key); // Проверяет существование кэша
```
