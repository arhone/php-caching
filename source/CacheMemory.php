<?php
namespace arhone\cache;

/**
 * Работа с кешем
 *
 * Class CacheMemory
 * @package arhone\cache
 */
class CacheMemory implements CacheInterface {

    /**
     * Настройки класса
     *
     * @var array
     */
    protected $config = [
        'status' => true
    ];

    protected $Memcached;

    /**
     * CacheMem constructor.
     * @param \Memcached $Memcached
     */
    public function __construct (\Memcached $Memcached) {

        $this->Memcached = $Memcached;
        $Memcached->setCompressThreshold(0, 1);

    }

    /**
     * Возвращает значение кэша
     *
     * @param string $key
     * @param int|null $time
     * @return mixed
     */
    public function get (string $key, int $time = null) {

        if (!$this->config['status']) {
            return false;
        }

        return gzdecode($this->Memcached->get($key), 9);

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

        return $this->Memcached->set($key, gzencode($data, 9), MEMCACHE_COMPRESSED, $interval) == true;

    }

    /**
     * Очистка кеша
     *
     * @return bool
     */
    public function clear () : bool {

        return $this->Memcached->flush();

    }

    /**
     * Удаление кеша
     *
     * @param string|null $key
     * @return bool
     */
    public function delete (string $key = null) : bool {

        return $this->Memcached->set($key, false);

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