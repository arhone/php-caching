<?php declare(strict_types = 1);
namespace arhone\cache;

/**
 * Работа с кешем
 *
 * Class CacheFile
 * @package arhone\cache
 */
class CacheFile implements Cache {

    /**
     * Настройки класса
     *
     * @var array
     */
    protected $config = [
        'status'    => true,
        'directory' => __DIR__ . '/../../../cache'
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
     * @return mixed
     */
    public function get (string $key, int $interval = null) {

        if (!$this->config['status']) {
            return false;
        }

        $path = $this->getPath($key);

        if (is_file($path)) {

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
     * @return bool
     */
    public function set (string $key, $data, int $interval = null) : bool {

        if (!$this->config['status']) {
            return false;
        }

        $path = $this->getPath($key);
        $dir = dirname($path);

        if (!is_dir($dir)) {

            mkdir($dir, 0700, true);

        }

        $data = [
            'created' => time(),
            'remove'  => $interval ? time() + $interval : null,
            'data'    => $data
        ];
        return file_put_contents($path, serialize($data), LOCK_EX) == true;

    }

    /**
     * Очистка кеша
     *
     * @return bool
     */
    public function clear () : bool {

        return $this->deleteRecursive($this->config['directory']);

    }

    /**
     * Удаление кеша
     *
     * @param string $key
     * @return bool
     */
    public function delete (string $key) : bool {

        return $this->deleteRecursive($this->getPath($key));

    }

    /**
     * Рекурсивное удаление файлов
     *
     * @param $path
     * @return bool
     */
    function deleteRecursive ($path) : bool {

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
    protected function getPath (string $key) : string {

        $path = $this->config['directory'] . DIRECTORY_SEPARATOR . str_replace('.', DIRECTORY_SEPARATOR, $key);

        if (is_dir($path) || is_file($path)) {

            return $path;

        } else {

            $dir  = dirname($path);
            $hash = md5(basename($path));
            return $dir . DIRECTORY_SEPARATOR . '.' . $hash[0] . $hash[1] . DIRECTORY_SEPARATOR . '.' . $hash[2] . $hash[3] . DIRECTORY_SEPARATOR . '.' . $hash;

        }


    }

    /**
     * Задаёт конфигурацию
     *
     * @param array $config
     * @return array
     */
    public function config (array $config) : array {

        return $this->config = array_merge($this->config, $config);

    }

}