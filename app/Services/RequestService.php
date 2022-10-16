<?php

namespace App\Services;

use App\Models\Request;
use App\Repositories\RequestRepositoryInterface;
use Illuminate\Support\Facades\Log;

class RequestService
{
    private const ALLOWED_METHODS = [
        'post',
        'put',
        'delete',
    ];

    public function __construct(
        private string $resource,
        private RequestRepositoryInterface $repository,
        private \Illuminate\Http\Client\PendingRequest $httpClient
    ) {
    }

    private function request(string $method, string $path, array $params = []): void
    {
        Log::debug(__METHOD__, ['PID' => getmypid(), 'method' => $method, 'path' => $path, 'params' => $params]);

        if (!in_array($method, self::ALLOWED_METHODS)) {
            return;
        }

        $request = $this->repository->store([
            'path' => $path,
            'method' => $method,
            'params' => json_encode($params),
        ]);

        try {
            $response = $this->httpClient->$method($path, $params);

            $requestUpdate = ['status' => $response->status(), 'reason' => $response->reason()];

            // getting body on failing requests
            if ($response->failed()) {
                $requestUpdate['body'] = $response->body();
            }

            $this->repository->update($request->id, $requestUpdate);

            Log::debug(__METHOD__, [
                'PID' => getmypid(),
                'status' => $response->status(),
                'reason' => $response->reason(),
                'body' => $response->body()
            ]);
        } catch (\Exception $e) {
            Log::error(__METHOD__, ['code' => $e->getCode(), 'message' => $e->getMessage()]);
        }
    }

    public function create(array $params, string $path = ''): void
    {
        $this->request('post', $this->resource . $path, $params);
    }

    public function update(string $id, array $params, string $path = ''): void
    {
        $this->request('put', "{$this->resource}{$path}/" . config('api.owner') . "/{$id}", $params);
    }

    public function delete(string $id): void
    {
        $this->request('delete', "{$this->resource}/" . config('api.owner') . "/{$id}");
    }
}
