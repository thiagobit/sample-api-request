<?php

namespace App\Http\Controllers\Api;

use App\Facades\ProductAPI;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateProductRequest;
use App\Utilities\API;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Listener of product-status-store event.
     * Sends a product POST request to SynQ API.
     *
     * @param StoreUpdateProductRequest $request
     *
     * @return JsonResponse
     */
    public function store(StoreUpdateProductRequest $request): JsonResponse
    {
        Log::debug(__METHOD__, ['PID' => getmypid(), 'request' => $request->detail]);

        $productParams = API::makeProductInputData(
            API::getProductId($request->detail['product_variant']),
            $request->detail['product_variant']['product']['title'],
            $request->detail['product_variant']['sourcing_category']['title'] ?? '',
            $request->detail['product_variant']['images'][0]['url'] ?? '',
        );

        ProductAPI::create($productParams);

        return response()->json('', 201);
    }

    /**
     * Listener of product-status-update event.
     * Sends a product PUT request to SynQ API.
     *
     * @param StoreUpdateProductRequest $request
     *
     * @return JsonResponse
     */
    public function update(StoreUpdateProductRequest $request): JsonResponse
    {
        Log::debug(__METHOD__, ['PID' => getmypid(), 'request' => $request->detail]);

        $productId = API::getProductId($request->detail['product_variant']);

        $productParams = API::makeProductInputData(
            $productId,
            $request->detail['product_variant']['product']['title'],
            $request->detail['product_variant']['sourcing_category']['title'] ?? '',
            $request->detail['product_variant']['images'][0]['url'] ?? '',
        );

        // removing not needed key params
        unset($productParams['owner'], $productParams['productId']);

        ProductAPI::update($productId, $productParams);

        return response()->json('', 204);
    }
}
