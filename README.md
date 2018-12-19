# Cacher

Кэширование.

# Установка

```composer require arhone/caching```

Кэш на файлах
```php
<?php
use arhone\caching\CacherFileSystemAdapter;
include 'vendor/autoload.php';

$cacher = new CacherFileSystemAdapter();
```

Кэш в Redis
```php
<?php
use arhone\caching\CacherRedisAdapter;
include 'vendor/autoload.php';

$redis = new \Redis();
$redis->connect('localhost');
$cacher = new CacherRedisAdapter($redis);
```

Кэш в Memcached
```php
<?php
use arhone\caching\CacherMemcachedAdapter;
include 'vendor/autoload.php';

$memcached = new \Memcached();
$memcached->connect('localhost');
$cacher = new CacherMemcachedAdapter($memcached);
```

# Пример

```
$cacher->get(string $key); // Возвращает кэш по ключу
$cacher->set(string $key, $data, int $interval = null); // Сохраняет кэш по ключу. Можно задать время жизни в секундах.
```

```php
<?php
use arhone\caching\CacherFileSystemAdapter;
include 'vendor/autoload.php';

$cacher = new CacherFileSystemAdapter();

if (!$data = $cacher->get('key')) {
    
    $data = 'Привет'; // Какой то сложный код, получающий данные
    $cacher->set('key', $data);
    
}

echo $data;
```

```
$cacher->delete(string $key); // Удаляет кэш по ключу
$cacher->clear(); // Очищает весь кэш.
$cacher->has(string $key); // Проверяет существование кэша
```
