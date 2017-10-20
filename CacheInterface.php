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
     * Возвращает значение кеша
     *
     * @param string $key
     * @param int|null $interval
     * @return mixed
     */
    public function get (string $key, int $interval = null);

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