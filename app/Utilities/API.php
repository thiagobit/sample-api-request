<?php

namespace App\Utilities;

class API
{
    /**
     * Returns the productId field to use in API calls.
     *
     * @param array $productParams
     *
     * @return string|null
     */
    public static function getProductId(array $productParams): string|null
    {
        if (!isset($productParams['sku']) and !isset($productParams['barcode'])) {
            return null;
        }

        $productId = $productParams['sku'] ?? $productParams['barcode'];

        // using barcode for customized items
        if (self::checkProductIsCustomized($productParams)) {
            $productId = $productParams['barcode'] ?? $productParams['sku'];
        }

        return $productId;
    }

    /**
     * Returns the orderId field to use in API calls.
     *
     * @param array $orderParams
     *
     * @return string|null
     */
    public static function getOrderId(array $orderParams): string|null
    {
        return $orderParams['autostore_order_id'] ?? null;
    }

    /**
     * Makes a product master node acceptable input data.
     *
     * @param string $productId
     * @param string $title
     * @param string $category
     * @param string $imageURL
     *
     * @return array
     */
    public static function makeProductInputData(
        string $productId,
        string $title,
        string $category = '',
        string $imageURL = ''
    ): array {
        return [
            'productId' => $productId,
            'owner' => config('api.owner'),
            'description' => $title,
            'productCategory' => $category,
            'scanValidationMode' => 'EACH',
            'defaultUomId' => 'EACH',
            'productUom' => [
                [
                    'uomId' => 'EACH',
                    'imagePath' => $imageURL,
                    'ratio' => 1,
                    'baseUomFlag' => true,
                    'pickUomFlag' => true,
                    'putawayUomFlag' => true,
                    'length' => 0,
                    'height' => 0,
                    'width' => 0,
                    'weight' => 0,
                    'volume' => 0,
                    'weightTolerance' => 0,
                ],
            ],
            'attribute' => [
                [
                    'name' => 'materialStatus',
                    'pickingAction' => 'NONE',
                ],
            ],
        ];
    }

    /**
     * Makes an order acceptable input data.
     *
     * @param array $orderParams
     * @param array $orderLine
     *
     * @return array
     */
    public static function makeOrderInputData(array $orderParams, array $orderLine): array
    {
        $orderId = API::getOrderId($orderParams);

        // additional optional fields
        $extraFields = self::makeOrderExtraInputData($orderParams);

        return array_merge(
            [
                'orderId' => $orderId,
                'owner' => config('api.owner'),
                'orderType' => 'AutoStore',
                'dispatchDate' => $orderParams['created_at'] ?? null,
                'orderDate' => $orderParams['created_at'] ?? null,
                'priority' => 10,
                'shortReleasingAllowed' => false,
                'shortAllocationAllowed' => false,
                'capability' => [
                    [
                        'capability' => 'Compile Order Pick',
                    ],
                ],
                'orderLine' => $orderLine,
            ],
            $extraFields
        );
    }

    /**
     * Makes an acceptable input data for order line of items.
     *
     * @param string $itemId
     * @param string $productId
     * @param int $quantity
     *
     * @return array
     */
    public static function makeOrderLineInputData(string $itemId, string $productId, int $quantity): array
    {
        return [
            'orderLineNumber' => $itemId,
            'productId' => $productId,
            'displayPickUomId' => 'EACH',
            'quantityOrdered' => $quantity,
        ];
    }

    /**
     * Makes an array with additional optional fields for order creation.
     *
     * @param array $orderParams
     *
     * @return array
     */
    public static function makeOrderExtraInputData(array $orderParams): array
    {
        $extra = [];

        if (isset($orderParams['instructions']) && !empty($orderParams['instructions'])) {
            $extra['instruction'] = $orderParams['instructions'];
        }

        return $extra;
    }

    /**
     * Checks if a product type is allowed
     *
     * @param array $productParams
     *
     * @return bool
     */
    public static function checkProductTypeIsAllowed(array $productParams): bool
    {
        $allowedTypes = ['product', 'promo'];

        return (isset($productParams['item_type']) && in_array($productParams['item_type'], $allowedTypes));
    }

    /**
     * Checks if a product is customized
     *
     * @param array $productParams
     *
     * @return bool
     */
    public static function checkProductIsCustomized(array $productParams): bool
    {
        return (isset($productParams['customized']) && $productParams['customized']);
    }

    /**
     * Checks if a product fulfills Autostore
     *
     * @param array $productParams
     *
     * @return bool
     */
    public static function checkProductFulfillsAutostore(array $productParams): bool
    {
        return (isset($productParams['fulfillment_type']) && ($productParams['fulfillment_type'] == 'autostore'));
    }

    /**
     * Checks if a product can be stored into product master node
     *
     * @param array $productParams
     *
     * @return bool
     */
    public static function checkProductCanBeStored(array $productParams): bool
    {
        return (
            self::checkProductTypeIsAllowed($productParams) &&
            self::checkProductIsCustomized($productParams) &&
            self::checkProductFulfillsAutostore($productParams)
        );
    }

    /**
     * Checks if a product can be sent as an order item
     *
     * @param array $productParams
     *
     * @return bool
     */
    public static function checkProductCanBeOrdered(array $productParams): bool
    {
        return (self::checkProductTypeIsAllowed($productParams) && self::checkProductFulfillsAutostore($productParams));
    }
}
