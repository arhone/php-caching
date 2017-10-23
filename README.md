# Cache

Кэширование.

# Установка

```composer require arhone/cache```

Кэш на файлах
```php
<?php
use arhone\cache\CacheFile;
include 'vendor/autoload.php';

$Cache = new CacheFile();
```

Кэш в Redis
```php
<?php
use arhone\cache\CacheRedis;
include 'vendor/autoload.php';

$Redis = new \Redis();
$Redis->connect('localhost');
$Cache = new CacheRedis($Redis);
```

Кэш в Memcached
```php
<?php
use arhone\cache\CacheRedis;
include 'vendor/autoload.php';

$Memcache = new \Memcache();
$Memcache->connect('localhost');
$Cache = new CacheRedis($Memcache);
```

# Пример

```
$Cache->get(string $key); // Возвращает кэш по ключу
$Cache->set(string $key, $data, int $interval = null); // Сохраняет кэш по ключу. Можно задать время жизни в секундах.
```

```php
<?php
use arhone\cache\CacheFile;
include 'vendor/autoload.php';

$Cache = new CacheFile();

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
