<?php declare(strict_types = 1);

namespace arhone\caching;

/**
 * Cache
 *
 * Interface CacherInterface
 * @package arhone\caching
 * @author Алексей Арх <info@arh.one>
 */
interface CacherInterface {

    /**
     * Записывает кеш в файл
     *
     * @param string $key
     * @param $data
     * @param int|null $interval
     * @return bool
     */
    public function set (string $key, $data, int $interval = null) : bool;

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
     * @return bool
     */
    public function has (string $key) : bool;

    /**
     * Удаление кеша
     *
     * @param string $key
     * @return bool
     */
    public function delete (string $key) : bool;

    /**
     * Очистка всего кеша
     *
     * @return bool
     */
    public function clear () : bool;

    /**
     * Задаёт конфигурацию
     *
     * @param array $configuration
     * @return array
     */
    public function configure (array $configuration = []) : array;

}