<?php declare(strict_types = 1);
namespace arhone\cache;

/**
 * Cache
 *
 * Interface CacheInterface
 * @package arhone\cache
 */
interface CacheInterface {

    /**
     * Проверяет и включает/отключат кеш
     *
     * @param bool $status
     * @return mixed
     */
    public function status (bool $status = null);

    /**
     * Записывает кеш в файл
     *
     * @param string $key
     * @param $data
     * @param int|null $interval
     * @return bool
     */
    public function set (string $key, $data, int $interval = null) : bool ;

    /**
     * Возвращает значение кеша
     *
     * @param string $key
     * @return mixed
     */
    public function get (string $key);

    /**
     * Проверяет существует ли ключ
     *
     * @param string $key
     * @return mixed
     */
    public function has (string $key);

    /**
     * Удаление кеша
     *
     * @param string $key
     * @return bool
     */
    public function delete (string $key) : bool ;

    /**
     * Очистка всего кеша
     *
     * @return bool
     */
    public function clear () : bool;

    /**
     * Задаёт конфигурацию
     *
     * @param array $config
     * @return array
     */
    public function config (array $config) : array;
        
}