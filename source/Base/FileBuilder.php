<?php

namespace source\Base;

class FileBuilder
{
    /**
     * @var array
     * @Файлы в которые мы заглядываем
     */
    public array $files = [];
    /**
     * @var array
     * @DESC действия/функции, которые мы выполняем, действия от 0 до n
     * select, ljoin, rjoin, innerjoin, where, on
     * SELECT * FROM users WHERE id = 52;
     *
     * $files = [$file_1, $file_1]
     * $to_do = ['select', 'where']
     * $conditions = [['*'], ['column = сondition/значение']]
     *
     */
    public array $to_do = [];
    /**
     * @var array
     * ot 0 do n
     * @DESC [[]]
     */
    public array $conditions = [];

    /**
     * @var int
     * @ Указывает на результаты количество результата вывода
     */
    public int $limit = 1;


    public function addAction($action, $file, $conditions)
    {

    }

}