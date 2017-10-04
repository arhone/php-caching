<?php
namespace arhone\cache;

/**
 * Работа с кешем
 *
 * Class CacheMemory
 * @package arh\cache
 */
class CacheMemory implements Cache {

    /**
     * Настройки класса
     *
     * @var array
     */
    protected $config = [];

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
     * @return string
     */
    public function get (string $key, int $time = null) {

        return gzdecode($this->Memcached->get($key), 9);

    }

    /**
     * Записывает кэш в файл
     *
     * @param string $key
     * @param $data
     * @param int|null $interval
     * @return int
     */
    public function set (string $key, $data, int $interval = null) : int {

        return (int)$this->Memcached->set($key, gzencode($data, 9), MEMCACHE_COMPRESSED, $interval);

    }

    /**
     * Удаление кеша
     *
     * @param string $key
     */
    public function delete (string $key = '') {

        $this->Memcached->set($key, false);

    }

    /**
     * Задаёт конфигурацию
     *
     * @param array $config
     * @return array
     */
    public function config (array $config) {

        return $this->config = array_merge($this->config, $config);

    }

}