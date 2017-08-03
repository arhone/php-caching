<?php
namespace arhone\cache;

/**
 * Работа с кешем
 * 
 * Class CacheFile
 * @package arh\cache
 */
class CacheFile implements Cache {

    /**
     * Настройки класса
     *
     * @var array
     */
    protected $config = [
        'cache_dir' => '/cache', // Директория для кеширования файлов
        'language'  => 'ru'
    ];

    /**
     * @var array
     */
    public static $count = [
        'get' => 0,
        'set' => 0
    ];

    /**
     * CacheFile constructor.
     * @param array $config
     */
    public function __construct (array $config = []) {

        $this->config($config);

    }

    /**
     * Возвращает значение кэша
     *
     * @param string $key
     * @param int|null $interval
     * @return bool
     */
    public function get (string $key, int $interval = null) {

        $path = $this->getPath($key);

        if (is_file($path)) {

            self::$count['get']++; // Счётчик загруженых файлов
            $data = unserialize(file_get_contents($path));

            if (!empty($data['remove']) && $data['remove'] < time()) {

                return false;

            }

            if ($interval && !empty($data['created']) && $data['created'] < time() - $interval) {

                return false;

            }

            return $data['data'] ?? false;

        }

        return false;

    }

    /**
     * Записывает кэш в файл
     *
     * @param string $key
     * @param $data
     * @param int|null $interval
     * @return bool|int
     */
    public function set (string $key, $data, int $interval = null) {

        $path = $this->getPath($key);
        $dir = dirname($path);

        if (!is_dir($dir)) {

            mkdir($dir, 0700, true);

        }

        self::$count['get']++; // Счётчик записанных файлов
        $data = [
            'created' => time(),
            'remove'  => $interval ? time() + $interval : null,
            'data'    => $data
        ];
        return file_put_contents($path, serialize($data), LOCK_EX);

    }

    /**
     * Удаление кеша
     *
     * @param string $key
     * @return bool
     */
    public function delete (string $key) {

        $path = $this->getPath($key);
        return $this->deleteRecursive($path);

    }

    /**
     * Рекурсивное удаление файлов
     *
     * @param $path
     * @return bool
     */
    function deleteRecursive ($path) {

        if (is_dir($path)) {

            foreach (scandir($path) as $file) {

                if ($file != '.' && $file != '..') {

                    if (is_file($file)) {

                        unlink($file);

                    } else {

                        $this->deleteRecursive($path . DIRECTORY_SEPARATOR . $file);

                    }

                }

            }

            $emptyDir = count(glob($path . '*')) ? true : false;
            if($emptyDir) {
                return rmdir($path);
            }

        } elseif (is_file($path)) {

            return unlink($path);

        }

        return false;

    }

    /**
     * Возврщает путь до файла
     *
     * @param string $key
     * @return string
     */
    private function getPath (string $key) {

        $path = $this->config['cache_dir'] . DIRECTORY_SEPARATOR . str_replace('.', DIRECTORY_SEPARATOR, $key);
        if (is_dir($path) || is_file($path)) {

            return $path;

        } else {

            $dir  = dirname($path);
            $hash = md5(basename($path));
            return $dir . DIRECTORY_SEPARATOR . '.' . $hash[0] . $hash[1] . DIRECTORY_SEPARATOR . '.' . $hash[2] . $hash[3] . DIRECTORY_SEPARATOR . '.' . $hash . DIRECTORY_SEPARATOR . '.' . $this->config['language'];

        }


    }

    /**
     * Задаёт конфигурацию
     *
     * @param array $config
     */
    public function config (array $config) {

        $this->config = array_merge($this->config, $config);

    }

}