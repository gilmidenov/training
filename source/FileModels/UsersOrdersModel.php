<?php

namespace source\FileModels;

use source\Base\FileDB;

/**
 * @property int|null $price
 * @property string|null $name
 * @property int id
 */
class UsersOrdersModel extends FileDB
{
    /**
     * @var string|null
     */
    public ?string $folder = 'users_orders';

    /**
     * @var array|string[]
     */
    public array $columns = [
        'id',
        'user_id',
        'order_id'
    ];
}

