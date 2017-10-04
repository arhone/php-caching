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
     * @return mixed
     */
    public function set (string $key, $data);

    /**
     * Удаление кеша
     *
     * @param string $key
     */
    public function delete (string $key);

    /**
     * Задаёт конфигурацию
     *
     * @param array $config
     * @return array
     */
    public function config (array $config) : array ;
        
}