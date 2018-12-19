<?php declare(strict_types=1);

namespace arhone\caching;

/**
 * Работа с кэшем
 *
 * Class CacherRedisAdapter
 * @package arhone\caching
 * @author Алексей Арх <info@arh.one>
 */
class CacherRedisAdapter implements CacherInterface {

    /**
     * Настройки класса
     *
     * @var array
     */
    protected $configuration = [
        'state' => true,
    ];

    /**
     * @var \Redis 
     */
    protected $Redis;

    /**
     * CacherRedisAdapter constructor.
     * @param \Redis $redis
     * @param array $configuration
     */
    public function __construct (\Redis $redis, array $configuration = []) {

        $this->Redis = $redis;
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
     * @return bool
     */
    public function get (string $key) {

        if (!$this->getState()) {
            return false;
        }

        $data = unserialize($this->Redis->get($key));

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

        $data = [
            'created' => time(),
            'remove'  => $interval ? time() + $interval : null,
            'data'    => $data
        ];
        return $this->Redis->set($key, serialize($data)) == true;

    }

    /**
     * Удаление кеша
     *
     * @param string $key
     * @return bool
     */
    public function delete (string $key) : bool {

        $result = false;
        $this->Redis->delete($key);
        foreach ($this->Redis->keys($key . '.*') as $key) {
            $this->Redis->delete($key);
            $result = true;
        }

        return $result;

    }

    /**
     * Проверяет существование ключа
     *
     * @param string $key
     * @return bool
     */
    public function has (string $key) : bool {

        return $this->Redis->exists($key);

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
     * Задаёт конфигурацию
     *
     * @param array $configuration
     * @return array
     */
    public function configure (array $configuration = []) : array {

        return $this->configuration = array_merge($this->configuration, $configuration);

    }

}