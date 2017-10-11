<?php
namespace arhone\cache;
interface Cache {

    /**
     * Возвращает значение кеша
     *
     * @param string $key
     * @param int|null $time
     * @return mixed
     */
    public function get (string $key, int $time = null);

    /**
     * Записывает кеш в файл
     *
     * @param string $key
     * @param $data
     * @return bool
     */
    public function set (string $key, $data) : bool ;

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