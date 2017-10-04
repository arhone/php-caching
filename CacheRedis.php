<?php
namespace arhone\cache;

/**
 * Работа с кэшем
 */
class CacheRedis implements Cache {

    /**
     * Настройки класса
     *
     * @var array
     */
    protected $config = [];

    protected $Redis;

    /**
     * Cache constructor.
     *
     * CacheRedis constructor.
     * @param \Redis $Redis
     * @param array $config
     */
    public function __construct (\Redis $Redis, array $config = []) {

        $this->Redis = $Redis;
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

        $data = unserialize($this->Redis->get($key));

        if (!empty($data['remove']) && $data['remove'] < time()) {

            return false;

        }

        if ($interval && !empty($data['created']) && $data['created'] < time() - $interval) {

            return false;

        }

        return $data['data'] ?? false;

    }

    /**
     * Записывает кэш в файл
     *
     * @param string $key
     * @param $data
     * @param int|null $interval
     * @return bool
     */
    public function set (string $key, $data, int $interval = null) {

        $data = [
            'created' => time(),
            'remove'  => $interval ? time() + $interval : null,
            'data'    => $data
        ];
        return $this->Redis->set($key, serialize($data));

    }

    /**
     * Удаление кеша
     *
     * @param string $key
     */
    public function delete (string $key) {

        $this->Redis->delete($key);
        foreach ($this->Redis->keys($key . '.*') as $key) {
            $this->Redis->delete($key);
        }

    }

    /**
     * Задаёт конфигурацию
     *
     * @param array $config
     * @return array
     */
    public function config (array $config) : array {

        $this->config = array_merge($this->config, $config);

    }

}