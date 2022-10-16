<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class RequestRepositoryEloquent implements RequestRepositoryInterface
{
    public function __construct(private Model $model)
    {
    }

    public function store(array $requestData)
    {
        return $this->model->create($requestData);
    }

    public function update(int $requestId, array $requestData)
    {
        return $this->model->find($requestId)?->update($requestData);
    }
}
