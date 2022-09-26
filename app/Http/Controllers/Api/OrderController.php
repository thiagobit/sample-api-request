<?php

namespace App\Http\Controllers\Api;

use App\Facades\OrderAPI;
use App\Http\Controllers\Controller;
use App\Http\Requests\DeleteOrderRequest;
use App\Http\Requests\StoreOrderRequest;
use App\Utilities\API;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Listener of order-status-store event
     *
     * @param StoreOrderRequest $request
     *
     * @return JsonResponse
     */
    public function store(StoreOrderRequest $request): JsonResponse
    {
        Log::debug(__METHOD__, ['PID' => getmypid(), 'request' => $request->detail]);

        $order = $request->detail['order'];
        $orderItems = [];

        foreach ($order['items'] as $item) {
            $productId = API::getProductId($item);

            if (API::checkProductCanBeOrdered($item)) {
                $orderItems[] = API::makeOrderLineInputData($item['id'], $productId, $item['quantity']);
            }
        }

        // do not create order without items
        if (empty($orderItems)) {
            return response()->json(['message' => 'None of the items can be ordered.'], 422);
        }

        $orderParams = API::makeOrderInputData($order, $orderItems);

        OrderAPI::create(['order' => [$orderParams]], '/batch');

        return response()->json('', 201);
    }

    public function destroy(DeleteOrderRequest $request): JsonResponse
    {
        Log::debug(__METHOD__, ['PID' => getmypid(), 'request' => $request->detail]);

        OrderAPI::delete($request->detail['order']['autostore_order_id']);

        return response()->json('', 204);
    }
}
