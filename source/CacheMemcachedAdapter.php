<?php
namespace arhone\caching;

/**
 * Работа с кэшем
 *
 * Class CacheMemcachedAdapter
 * @package arhone\caching
 * @author Алексей Арх <info@arh.one>
 */
class CacheMemcachedAdapter implements CacheInterface {

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
     * Проверяет и включает/отключат кеш
     *
     * @param bool $status
     * @return mixed
     */
    public function status (bool $status = null) {

        if ($status !== null) {
            $this->config['status'] = $status == true;
        }

        return $this->config['status'];

    }

    /**
     * Возвращает значение кэша
     *
     * @param string $key
     * @return mixed
     */
    public function get (string $key) {

        if (!$this->status()) {
            return false;
        }

        $data = unserialize($this->Memcached->get($key));

        if (!empty($data['remove']) && $data['remove'] < time()) {

            return false;

        }

        return $data['data'] ?? null;

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

        if (!$this->status()) {
            return false;
        }

        return $this->Memcached->set($key, gzencode($data, 9), MEMCACHE_COMPRESSED, $interval) == true;

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
     * Удаление кеша
     *
     * @param string|null $key
     * @return bool
     */
    public function has (string $key = null) : bool {

        return !empty($this->Memcached->get($key));

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
     * Задаёт конфигурацию
     *
     * @param array $config
     * @return array
     */
    public function config (array $config) : array {

        return $this->config = array_merge($this->config, $config);

    }

}