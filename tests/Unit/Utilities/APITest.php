<?php

namespace Tests\Unit\Utilities;

use App\Utilities\API;
use PHPUnit\Framework\TestCase;

class APITest extends TestCase
{
    private array $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->product = json_decode("{
            \"id\": 2585,
            \"title\": \"Product A\",
            \"category\": \"Games\",
            \"sku\": \"EM-JKT-0CR5099-AQUA-XS\",
            \"barcode\": \"123456789\",
            \"customized\": \"1\",
            \"created_at\": \"2016-09-23T19:32:41Z0\",
            \"updated_at\": \"2016-09-23T19:33:17Z0\",
            \"product\": {
                \"id\": 401,
                \"title\": \"Monogrammed New England Rain Jacket\"
            },
            \"sourcing_category\": {
                \"id\": 12,
                \"title\": \"Rain\"
            },
            \"options\": [
                {
                    \"id\": \"D68AA3BF-83D9-4705-9DD6-8664B52009B5\",
                    \"name\": \"Aqua\",
                    \"sku_value\": \"AQUA\",
                    \"attribute\": {
                        \"id\": \"281B498B-6D7D-4C74-8E02-AEDC6B09B1F9\",
                        \"name\": \"Color\",
                        \"title\": \"Color\",
                        \"attribute_type\": \"color\"
                    }
                },
                {
                    \"id\": \"F242C9DA-F7EA-4D71-BC5B-CEB0ACA3A37E\",
                    \"name\": \"Extra Small\",
                    \"sku_value\": \"XS\",
                    \"attribute\": {
                        \"id\": \"A45C5697-21CC-4680-8C8B-8B673B6295FF\",
                        \"name\": \"Sizes\",
                        \"title\": \"Size\",
                        \"attribute_type\": \"letter_size\"
                    }
                }
            ],
            \"images\": [
                {
                    \"id\": 12825,
                    \"url\": \"https://s3.amazonaws.com/myowner.products/legacy/optionmatrix/2585/53fe003b7c1681.24414782.jpg\"
                }
            ]
        }", true);

        $this->order = json_decode("{
            \"id\": 3078570,
            \"autostore_order_id\": 9846571,
            \"number\": \"20451-R57P5\",
            \"status\": \"Validated\",
            \"created_at\": \"2022-07-14T13:36:02Z\",
            \"instructions\": [
                {
                    \"type\": \"POST_PICK\",
                    \"text\": \"Special Handling!\"
                }
            ],
            \"items\": [
                {
                    \"id\": 11308437,
                    \"item_type\": \"shipping\",
                    \"fulfillment_type\": \"autostore\",
                    \"status\": null,
                    \"title\": \"Free Standard\",
                    \"barcode\": \"LIN11308437\",
                    \"sku\": \"EM-TOP-1011804-MINT-L_XL\",
                    \"customized\": 0,
                    \"quantity\": 1,
                    \"images\": [],
                    \"attributes\": []
                },
                {
                    \"id\": 11308447,
                    \"item_type\": \"promo\",
                    \"fulfillment_type\": \"autostore\",
                    \"status\": null,
                    \"title\": \"Free Standard\",
                    \"barcode\": \"LIN12308437\",
                    \"sku\": \"EM-TOP-1011904-MINT-L_XL\",
                    \"customized\": 0,
                    \"quantity\": 1,
                    \"images\": [],
                    \"attributes\": []
                },
                {
                    \"id\": 11308438,
                    \"item_type\": \"product\",
                    \"fulfillment_type\": \"autostore\",
                    \"status\": null,
                    \"title\": \"Monogrammed Fishing Shirt\",
                    \"barcode\": \"LIN11308438\",
                    \"sku\": \"EM-TOP-1011702-MINT-L_XL\",
                    \"customized\": 1,
                    \"quantity\": 1,
                    \"images\": [
                        {
                            \"url\": \"https://172.16.0.202/profiles/ml-product-detail/product/31816/kI1-personalized-cooler-in-lemons.jpg?wm=0\"
                        }
                    ],
                    \"attributes\": [
                        {
                            \"id\": 28545180,
                            \"title\": \"Color\",
                            \"attribute_type\": \"color\",
                            \"value\": \"Mint\",
                            \"personalizable\": false
                        },
                        {
                            \"id\": 28545181,
                            \"title\": \"Size\",
                            \"attribute_type\": \"letter_size\",
                            \"value\": \"Large/Extra Large\",
                            \"personalizable\": false
                        },
                        {
                            \"id\": 28545182,
                            \"title\": \"Letters\",
                            \"attribute_type\": \"text\",
                            \"value\": \"ACS\",
                            \"personalizable\": true
                        },
                        {
                            \"id\": 28545183,
                            \"title\": \"Design\",
                            \"attribute_type\": \"previewer_design\",
                            \"value\": \"Fishtail\",
                            \"personalizable\": true
                        },
                        {
                            \"id\": 28545184,
                            \"title\": \"Letter Color\",
                            \"attribute_type\": \"previewer_color\",
                            \"value\": \"08024D\",
                            \"personalizable\": true
                        }
                    ]
                },
                {
                    \"id\": 11308439,
                    \"item_type\": \"product\",
                    \"fulfillment_type\": null,
                    \"status\": null,
                    \"title\": \"Personalized Cooler\",
                    \"barcode\": \"LIN11308439\",
                    \"sku\": \"EM-BAG-HHM2631-MINT\",
                    \"customized\": true,
                    \"quantity\": 1,
                    \"images\": [],
                    \"attributes\": [
                        {
                            \"id\": 28545185,
                            \"title\": \"Color\",
                            \"attribute_type\": \"color\",
                            \"value\": \"Mint\",
                            \"personalizable\": false
                        },
                        {
                            \"id\": 28545186,
                            \"title\": \"Letters\",
                            \"attribute_type\": \"text\",
                            \"value\": \"ACS\",
                            \"personalizable\": true
                        },
                        {
                            \"id\": 28545187,
                            \"title\": \"Design\",
                            \"attribute_type\": \"previewer_design\",
                            \"value\": \"Octagonal\",
                            \"personalizable\": true
                        },
                        {
                            \"id\": 28545188,
                            \"title\": \"Letter Color\",
                            \"attribute_type\": \"previewer_color\",
                            \"value\": \"707070\",
                            \"personalizable\": true
                        }
                    ]
                }
            ]
        }", true);
    }

    /** @test */
    public function getProductId_should_return_sku_for_nom_customized_items()
    {
        $this->product['customized'] = 0;

        $productId = API::getProductId($this->product);

        $this->assertEquals($this->product['sku'], $productId);
    }

    /** @test */
    public function getProductId_should_return_barcode_for_customized_items()
    {
        $this->product['customized'] = 1;

        $productId = API::getProductId($this->product);

        $this->assertEquals($this->product['barcode'], $productId);
    }

    /** @test */
    public function getProductId_should_return_sku_for_customized_items_if_barcode_is_not_set()
    {
        unset($this->product['barcode']);

        $this->product['customized'] = 1;

        $productId = API::getProductId($this->product);

        $this->assertEquals($this->product['sku'], $productId);
    }

    /** @test */
    public function getProductId_should_return_barcode_for_nom_customized_items_if_sku_is_not_set()
    {
        unset($this->product['sku']);

        $this->product['customized'] = 0;

        $productId = API::getProductId($this->product);

        $this->assertEquals($this->product['barcode'], $productId);
    }

    /** @test */
    public function getProductId_should_return_null_if_sku_and_barcode_are_not_set()
    {
        unset($this->product['sku'], $this->product['barcode']);

        $this->product['customized'] = 1;

        $productId = API::getProductId($this->product);

        $this->assertEquals(null, $productId);

        $this->product['customized'] = 0;

        $productId = API::getProductId($this->product);

        $this->assertEquals(null, $productId);
    }

    /** @test */
    public function getOrderId_should_return_autostore_order_id()
    {
        $orderId = API::getOrderId($this->order);

        $this->assertEquals($this->order['autostore_order_id'], $orderId);
    }

    /** @test */
    public function makeOrderLineInputData_should_return_correct_array_format()
    {
        $orderItem = $this->order['items'][2];

        $productId = API::getProductId($orderItem);

        $orderLine = API::makeOrderLineInputData($orderItem['id'], $productId, $orderItem['quantity']);

        $expectedArray = [
            'orderLineNumber' => $orderItem['id'],
            'productId' => $productId,
            'displayPickUomId' => 'EACH',
            'quantityOrdered' => $orderItem['quantity'],
        ];

        $this->assertEquals($expectedArray, $orderLine);
    }

    /** @test */
    public function makeOrderExtraInputData_should_return_correct_array_format()
    {
        $extraInputData = API::makeOrderExtraInputData($this->order);

        $expectedArray['instruction'] = $this->order['instructions'];

        $this->assertEquals($expectedArray, $extraInputData);
    }

    /** @test */
    public function checkProductTypeIsAllowed_returns_true_if_item_type_is_product()
    {
        $this->product['item_type'] = 'product';

        $response = API::checkProductTypeIsAllowed($this->product);

        $this->assertEquals(true, $response);
    }

    /** @test */
    public function checkProductTypeIsAllowed_returns_true_if_item_type_is_promo()
    {
        $this->product['item_type'] = 'promo';

        $response = API::checkProductTypeIsAllowed($this->product);

        $this->assertEquals(true, $response);
    }

    /** @test */
    public function checkProductTypeIsAllowed_returns_false_if_item_type_is_not_valid()
    {
        $this->product['item_type'] = 'shipping';

        $response = API::checkProductTypeIsAllowed($this->product);

        $this->assertEquals(false, $response);

        unset($this->product['item_type']);

        $response = API::checkProductTypeIsAllowed($this->product);

        $this->assertEquals(false, $response);
    }

    /** @test */
    public function checkProductIsCustomized_returns_true_if_customized_is_true()
    {
        $this->product['customized'] = 1;

        $response = API::checkProductIsCustomized($this->product);

        $this->assertEquals(true, $response);
    }

    /** @test */
    public function checkProductIsCustomized_returns_false_if_customized_is_false()
    {
        $this->product['customized'] = 0;

        $response = API::checkProductIsCustomized($this->product);

        $this->assertEquals(false, $response);
    }

    /** @test */
    public function checkProductIsCustomized_returns_false_if_customized_is_not_set()
    {
        unset($this->product['customized']);

        $response = API::checkProductIsCustomized($this->product);

        $this->assertEquals(false, $response);
    }

    /** @test */
    public function checkProductFulfillsAutostore_returns_true_if_fulfillment_type_is_autostore()
    {
        $this->product['fulfillment_type'] = 'autostore';

        $response = API::checkProductFulfillsAutostore($this->product);

        $this->assertEquals(true, $response);
    }

    /** @test */
    public function checkProductFulfillsAutostore_returns_false_if_fulfillment_type_is_not_autostore()
    {
        $this->product['fulfillment_type'] = '';

        $response = API::checkProductFulfillsAutostore($this->product);

        $this->assertEquals(false, $response);

        unset($this->product['fulfillment_type']);

        $response = API::checkProductFulfillsAutostore($this->product);

        $this->assertEquals(false, $response);
    }

    /** @test */
    public function checkProductCanBeStored_returns_true_if_item_type_is_allowed_is_customized_and_fulfills_autostore()
    {
        $this->product['item_type'] = 'product';
        $this->product['customized'] = 1;
        $this->product['fulfillment_type'] = 'autostore';

        $response = API::checkProductCanBeStored($this->product);

        $this->assertEquals(true, $response);

        $this->product['item_type'] = 'promo';

        $response = API::checkProductCanBeStored($this->product);

        $this->assertEquals(true, $response);
    }

    /** @test */
    public function checkProductCanBeStored_returns_false_if_item_type_is_not_valid()
    {
        $this->product['item_type'] = 'shipping';
        $this->product['customized'] = 1;
        $this->product['fulfillment_type'] = 'autostore';

        $response = API::checkProductCanBeStored($this->product);

        $this->assertEquals(false, $response);

        unset($this->product['item_type']);

        $response = API::checkProductCanBeStored($this->product);

        $this->assertEquals(false, $response);
    }

    /** @test */
    public function checkProductCanBeStored_returns_false_if_customized_is_false_or_is_not_set()
    {
        $this->product['item_type'] = 'product';
        $this->product['customized'] = 0;
        $this->product['fulfillment_type'] = 'autostore';

        $response = API::checkProductCanBeStored($this->product);

        $this->assertEquals(false, $response);

        unset($this->product['customized']);

        $response = API::checkProductCanBeStored($this->product);

        $this->assertEquals(false, $response);
    }

    /** @test */
    public function checkProductCanBeStored_returns_false_if_fulfillment_type_is_not_autostore()
    {
        $this->product['item_type'] = 'product';
        $this->product['customized'] = 1;
        $this->product['fulfillment_type'] = '';

        $response = API::checkProductCanBeStored($this->product);

        $this->assertEquals(false, $response);

        unset($this->product['fulfillment_type']);

        $response = API::checkProductCanBeStored($this->product);

        $this->assertEquals(false, $response);
    }
}
