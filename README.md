# Cacher

Кэширование.

# Установка

```composer require arhone/caching```

Кэш на файлах
```php
<?php
use arhone\caching\CacherFileSystemAdapter;
include 'vendor/autoload.php';

$Cacher = new CacherFileSystemAdapter();
```

Кэш в Redis
```php
<?php
use arhone\caching\CacherRedisAdapter;
include 'vendor/autoload.php';

$Redis = new \Redis();
$Redis->connect('localhost');
$Cacher = new CacherRedisAdapter($Redis);
```

Кэш в Memcached
```php
<?php
use arhone\caching\CacherMemcachedAdapter;
include 'vendor/autoload.php';

$Memcached = new \Memcached();
$Memcached->connect('localhost');
$Cacher = new CacherMemcachedAdapter($Memcached);
```

# Пример

```
$Cacher->get(string $key); // Возвращает кэш по ключу
$Cacher->set(string $key, $data, int $interval = null); // Сохраняет кэш по ключу. Можно задать время жизни в секундах.
```

```php
<?php
use arhone\caching\CacherFileSystemAdapter;
include 'vendor/autoload.php';

$Cacher = new CacherFileSystemAdapter();

if (!$data = $Cacher->get('key')) {
    
    $data = 'Привет'; // Какой то сложный код, получающий данные
    $Cacher->set('key', $data);
    
}

echo $data;
```

```
$Cacher->delete(string $key); // Удаляет кэш по ключу
$Cacher->clear(); // Очищает весь кэш.
$Cacher->has(string $key); // Проверяет существование кэша
```
