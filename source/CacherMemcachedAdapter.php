<?php
namespace arhone\caching;

/**
 * Работа с кэшем
 *
 * Class CacherMemcachedAdapter
 * @package arhone\caching
 * @author Алексей Арх <info@arh.one>
 */
class CacherMemcachedAdapter implements CacherInterface {

    /**
     * Настройки класса
     *
     * @var array
     */
    protected $configuration = [
        'state' => true
    ];

    /**
     * @var \Memcached
     */
    protected $Memcached;

    /**
     * CacherMemcachedAdapter constructor.
     * @param \Memcached $memcached
     * @param array $configuration
     */
    public function __construct (\Memcached $memcached, array $configuration = []) {

        $this->Memcached = $memcached;
        $this->Memcached->setCompressThreshold(0, 1);

        $this->configure($configuration);

    }

    /**
     * Проверяет и включает/отключат кеш
     *
     * @param bool $state
     * @return bool
     */
    protected function getState (bool $state = null) : bool {

        if ($state !== null) {
            $this->configuration['state'] = $state == true;
        }

        return ($this->configuration['state'] ?? false) == true;

    }

    /**
     * Возвращает значение кэша
     *
     * @param string $key
     * @return mixed
     */
    public function get (string $key) {

        if (!$this->getState()) {
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

        if (!$this->getState()) {
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

        return $this->Memcached->set($key, false) == true;

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

        return $this->Memcached->flush() == true;

    }

    /**
     * Задаёт конфигурацию
     *
     * @param array $configuration
     * @return array
     */
    public function configure (array $configuration = []) : array {

        return $this->configuration = array_merge($this->configuration, $configuration);

    }

}