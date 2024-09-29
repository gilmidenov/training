<?php

namespace source\Base;

abstract class FileDB
{
    /**
     * @var string
     */
    protected string $main_path = 'C:\\OSPanel\\home\\full-example.local\\public\\db\\';
    /**
     * @var string
     */
    protected string $index_path = 'C:\\OSPanel\\home\\full-example.local\\public\\db_base\\';

    public array $columns = [];

    /**
     * @var string|null
     */
    protected ?string $folder = null;

    /**
     * @var string|null
     */
    protected ?string $main_dir = null;

    /**
     * @var array
     */
    protected array $variables = [];

    public function __construct(array $variables = [])
    {
        $this->variables = $variables;
        $this->main_dir = $this->main_path . $this->folder . '\\';
    }

    /**
     * @DESC AND | OR
     *
     * @param string $file_name
     * @return array|null
     */
    public function select(string $file_name): null|static
    {
        $path = $this->main_dir . $file_name;
        $class = static::class;

        return file_exists($path)
            ? new $class(json_decode(file_get_contents($path), true))
            : null;
    }

    /**
     * @param array $file_names
     * @param array $keys_values
     * @return int
     * @throws \Exception
     */
    public function update(array $file_names, array $keys_values): int
    {
        $counter = 0;

        foreach ($file_names as $key_filename => $file_name) {
            $path = $this->main_dir . $file_name;

            if (file_exists($path)) {
                $data = json_decode(file_get_contents($path), true);

                foreach ($keys_values[$key_filename] as $key => $value) {
                    $data[$key] = $value;
                }

                $this->checkDataColumns($keys_values[$key_filename], false);

                file_put_contents($path, json_encode($data));
                $counter += 1;
            } else {
                throw new \Exception('file not exists ' . $file_name);
            }
        }

        return $counter;
    }

    /**
     * @param array $file_names
     * @return int
     * @throws \Exception
     */
    public function delete(array $file_names): int
    {
        $counter = 0;

        foreach ($file_names as $file_name) {
            $path = $this->main_dir . $file_name;

            if (file_exists($path)) {
                unlink($path);

                $counter += 1;
            } else {
                throw new \Exception('file ' . $file_name . ' not exists');
            }
        }

        return $counter;
    }

    /**
     * [1 => ['id' => 1, 'name' => 'Igor', 'price' => 1], 2 => []]
     * [0 => 0, 1 => 1, ...]
     */
    /**
     * @param array $file_names
     * @param array $keys_values
     * @param array $indexes
     * @param bool $force
     * @return int
     * @throws \Exception
     */
    public function insert(array $file_names, array $keys_values, array $indexes = [], bool $force = false): int
    {
        $counter = 0;

        if (count($file_names) != count($keys_values)) {
            throw new \Exception('count filename differance');
        }

        foreach ($file_names as $key => $file_name) {
            $path = $this->main_dir . $file_name;

            if (file_exists($path) && !$force) {
                throw new \Exception('file exists ' . $file_name);
            }

            if (!array_key_exists($key, $keys_values)) {
                throw new \Exception('data for key ' . $key . ' doesn\'t exists');
            }

            $this->checkDataColumns($keys_values[$key]);

            /**
             * @Те файлы которые будет собирать и создавать по ним связи
             */
            if ($indexes) {
                foreach ($indexes as $index_key) {
                    if (array_key_exists($index_key, $keys_values[$key])) {
                        $index_path = $this->index_path . $this->folder . '\\' . $index_key . '\\';

                        file_put_contents(
                            $index_path . $keys_values[$key][$index_key],
                            $file_name . "\n",
                            FILE_APPEND
                        );
                    }
                }
            }

            $counter += 1;
            file_put_contents($path, json_encode($keys_values[$key]));
        }

        return $counter;
    }

    /**
     * @param array $array
     * @param bool $is_all
     * @return void
     * @throws \Exception
     */
    public function checkDataColumns(array $array, bool $is_all = true): void
    {
        foreach ($array as $key => $value) {
            if (!in_array($key, $this->columns)) {
                throw new \Exception('column doesn\'t exists in table ' . static::class);
            }
        }

        if ($is_all) {
            foreach ($this->columns as $column) {
                if (!array_key_exists($column, $array)) {
                    throw new \Exception('you don\'t use column ' . $column . ' in table ' . static::class);
                }
            }
        }
    }

    /**
     * @DESC JOIN FUNCTIONS
     */
    public function leftJoin(string $folder, string $key)
    {

    }

    public function rightJoin()
    {

    }

    public function innerJoin()
    {

    }

    public function join()
    {
        $this->innerJoin();
    }

    /**
     * @DESC magic methods
     *
     * @param $key
     * @param $value
     * @return void
     */
    public function __set($key, $value)
    {
        $this->variables[$key] = $value;
    }

    public function __get($key)
    {
        return $this->variables[$key] ?? null;
    }
}