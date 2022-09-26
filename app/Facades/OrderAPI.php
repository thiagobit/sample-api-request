<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void create(array $params, string $path = '')
 * @method static void update(string $id, array $params, string $path = '')
 * @method static void delete(string $id)
 *
 * @see \App\Services\ApiRequest
 */
class OrderAPI extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'order-api';
    }
}
