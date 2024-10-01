<?php

namespace source\FileModels;

use source\Base\FileDB;

class UsersModel extends FileDB
{
    /**
     * @var string|null
     */
    public ?string $folder = 'users';

    public $id, $name, $balance;
}