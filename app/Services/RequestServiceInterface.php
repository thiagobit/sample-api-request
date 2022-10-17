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

    public function create(array $params, string $path = ''): void;
    public function update(string $id, array $params, string $path = ''): void;
    public function delete(string $id): void;
}
