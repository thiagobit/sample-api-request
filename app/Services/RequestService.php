<?php

namespace App\Services;

use App\Repositories\RequestRepositoryInterface;
use Illuminate\Support\Facades\Log;

class RequestService implements RequestServiceInterface
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

    /**
     * Sends a request
     *
     * @param string $method
     * @param string $path
     * @param array  $params
     *
     * @return void
     */
    private function request(string $method, string $path, array $params = []): void
    {
        Log::debug(__METHOD__, ['PID' => getmypid(), 'method' => $method, 'path' => $path, 'params' => $params]);

        if (!in_array($method, self::ALLOWED_METHODS)) {
            return;
        }

        $request = $this->repository->store(
            [
                'path' => $path,
                'method' => $method,
                'params' => json_encode($params),
            ]
        );

        try {
            $response = $this->httpClient->$method($path, $params);

            $requestUpdate = ['status' => $response->status(), 'reason' => $response->reason()];

            // getting body on failing requests
            if ($response->failed()) {
                $requestUpdate['body'] = $response->body();
            }

            $this->repository->update($request->id, $requestUpdate);

            Log::debug(
                __METHOD__,
                [
                    'PID' => getmypid(),
                    'status' => $response->status(),
                    'reason' => $response->reason(),
                    'body' => $response->body()
                ]
            );
        } catch (\Exception $e) {
            Log::error(__METHOD__, ['code' => $e->getCode(), 'message' => $e->getMessage()]);
        }
    }

    /**
     * Sends a POST request
     *
     * @param array  $params
     * @param string $path
     *
     * @return void
     */
    public function create(array $params, string $path = ''): void
    {
        $this->request('post', $this->resource . $path, $params);
    }

    /**
     * Sends a PUT request
     *
     * @param string $id
     * @param array  $params
     * @param string $path
     *
     * @return void
     */
    public function update(string $id, array $params, string $path = ''): void
    {
        $this->request('put', "{$this->resource}{$path}/" . config('api.owner') . "/{$id}", $params);
    }

    /**
     * Sends a DELETE request
     *
     * @param string $id
     *
     * @return void
     */
    public function delete(string $id): void
    {
        $this->request('delete', "{$this->resource}/" . config('api.owner') . "/{$id}");
    }
}
