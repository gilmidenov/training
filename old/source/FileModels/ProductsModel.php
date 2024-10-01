<?php

namespace source\FileModels;

use source\Base\FileDB;

/**
 * @property int|null $price
 * @property string|null $name
 * @property int id
 */
class ProductsModel extends FileDB
{
    /**
     * @var string|null
     */
    public ?string $folder = 'products';

    /**
     * @var array|string[]
     */
    public array $columns = [
        'id',
        'name',
        'price'
    ];
}

