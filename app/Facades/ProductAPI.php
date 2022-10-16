<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void create(array $params, string $path = '')
 * @method static void update(string $id, array $params, string $path = '')
 * @method static void delete(string $id)
 *
 * @see \App\Services\RequestService
 */
class ProductAPI extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'product-api';
    }
}
