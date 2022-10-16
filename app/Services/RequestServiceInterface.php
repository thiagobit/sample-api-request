<?php

namespace App\Services;

use App\Repositories\RequestRepositoryInterface;

interface RequestServiceInterface
{
    public function __construct(
        string $resource,
        RequestRepositoryInterface $repository,
        \Illuminate\Http\Client\PendingRequest $httpClient
    );

    function create(array $params, string $path = ''): void;
    function update(string $id, array $params, string $path = ''): void;
    function delete(string $id): void;
}
