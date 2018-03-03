<?php
namespace arhone\cache;

/**
 * Работа с кэшем
 *
 * Class CacheRedis
 * @package arhone\cache
 */
class CacheRedis implements CacheInterface {

    /**
     * Настройки класса
     *
     * @var array
     */
    protected $config = [
        'status' => true,
    ];

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

        if (!$this->config['status']) {
            return false;
        }

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
    public function set (string $key, $data, int $interval = null) : bool {

        if (!$this->config['status']) {
            return false;
        }

        $data = [
            'created' => time(),
            'remove'  => $interval ? time() + $interval : null,
            'data'    => $data
        ];
        return $this->Redis->set($key, serialize($data)) == true;

    }


    /**
     * Очищает кеш
     *
     * @return bool
     */
    public function clear () : bool {

        return $this->Redis->flushAll() == true;

    }

    /**
     * Удаление кеша
     *
     * @param string $key
     * @return bool
     */
    public function delete (string $key) : bool {

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