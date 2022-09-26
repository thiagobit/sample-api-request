<?php

namespace App\Services;

use App\Models\Request;
use Illuminate\Support\Facades\Log;

class ApiRequest
{
    private const ALLOWED_METHODS = [
        'post',
        'put',
        'delete',
    ];

    public function __construct(
        protected string $resource,
        protected \Illuminate\Http\Client\PendingRequest $httpClient
    ) {}

    private function request(string $method, string $path, array $params = []): void
    {
        Log::debug(__METHOD__, ['PID' => getmypid(), 'method' => $method, 'path' => $path, 'params' => $params]);

        if (!in_array($method, self::ALLOWED_METHODS)) return;

        $request = Request::create([
            'path' => $path,
            'method' => $method,
            'params' => json_encode($params),
        ]);

        try {
            $response = $this->httpClient->$method($path, $params);

            $requestUpdate = ['status' => $response->status(), 'reason' => $response->reason()];

            // getting body on failing requests
            if ($response->failed()) $requestUpdate['body'] = $response->body();

            $request->fill($requestUpdate);
            $request->save();

            Log::debug(__METHOD__, ['PID' => getmypid(), 'status' => $response->status(), 'reason' => $response->reason(), 'body' => $response->body()]);
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
