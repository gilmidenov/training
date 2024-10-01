<?php

class Logger
{
    public static function write(string $message, string $name = 'default'): void
    {
        file_put_contents(
            'C:\\OSPanel\\home\\full-example.local\\public\\logs\\' . $name . '.log',
            date('d-m-Y H:i:s') . ' | ' . $message . "\n",
            FILE_APPEND
        );
    }
}