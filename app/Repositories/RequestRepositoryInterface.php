<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

interface RequestRepositoryInterface
{
    public function __construct(Model $model);
    public function store(array $requestData);
    public function update(int $requestId, array $requestData);
}
