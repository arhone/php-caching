<?php
namespace arhone\cache;

/**
 * Работа с кешем
 *
 * Class CacheMem
 * @package arh\cache
 */
class CacheMem implements Cache {

    protected $Memcache;

    /**
     * CacheMem constructor.
     * @param \Memcache $Memcache
     */
    public function __construct (\Memcache $Memcache) {

        $this->Memcache = $Memcache;
        $Memcache->setCompressThreshold(0, 1);

    }

    /**
     * Возвращает значение кэша
     *
     * @param string $key
     * @param int|null $time
     * @return string
     */
    public function get (string $key, int $time = null) {

        return gzdecode($this->Memcache->get($key), 9);

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

        return (int)$this->Memcache->set($key, gzencode($data, 9), MEMCACHE_COMPRESSED, $interval);

    }

    /**
     * Удаление кеша
     *
     * @param string $key
     * @return int
     */
    public function delete (string $key = '') {

        return (int)$this->Memcache->set($key, false);

    }

    /**
     * Задаёт конфигурацию
     *
     * @param array $config
     */
    public function config (array $config) {}

}