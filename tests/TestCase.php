<?php

namespace Tests;

use App\Utilities\API;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected array $orderInputRequest;
    protected object $orderOutputRequest;

    protected array $productInputRequest;
    protected object $productOutputRequest;

    protected array $productDescriptionTranslationInputRequest;
    protected object $productDescriptionTranslationOutputRequest;

    protected array $orderItemInputRequest;
    protected object $orderItemOutputRequest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->orderInputRequest = $this->makeOrderInputRequest();
        $this->orderOutputRequest = $this->makeOrderOutputRequest($this->orderInputRequest);

        $this->productInputRequest = $this->makeProductInputRequest();
        $this->productOutputRequest = $this->makeProductOutputRequest(
            API::getProductId($this->productInputRequest['detail']['product_variant']),
            $this->productInputRequest['detail']['product_variant']['product']['title'],
            $this->productInputRequest['detail']['product_variant']['sourcing_category']['title'],
            $this->productInputRequest['detail']['product_variant']['images'][0]['url'],
        );

        $this->productDescriptionTranslationInputRequest = $this->makeProductDescriptionTranslationInputRequest();
        $this->productDescriptionTranslationOutputRequest = $this->makeProductDescriptionTranslationOutputRequest(
            API::getProductId($this->productDescriptionTranslationInputRequest['detail']['product_variant']),
            $this->productDescriptionTranslationInputRequest['detail']['product_variant']['description'],
        );

        $this->orderItemInputRequest = $this->makeOrderItemInputRequest();
        $this->orderItemOutputRequest = $this->makeProductOutputRequest(
            productId: API::getProductId($this->orderItemInputRequest['detail']['order_item']),
            description: $this->orderItemInputRequest['detail']['order_item']['title'],
            imageURL: $this->orderItemInputRequest['detail']['order_item']['images'][0]['url'],
        );
    }

    protected function makeOrderInputRequest(): array
    {
        return json_decode("{
            \"version\": \"0\",
            \"id\": \"efcfe12d-8761-e5fa-777f-63cd17cd9723\",
            \"detail-type\": \"order-status-update\",
            \"source\": \"sandbox.api.order\",
            \"account\": \"249547702379\",
            \"time\": \"2022-07-14T14:55:06Z\",
            \"region\": \"us-east-1\",
            \"resources\": [],
            \"detail\": {
                \"order\": {
                    \"id\": 3078570,
                    \"autostore_order_id\": \"9846571\",
                    \"number\": \"20451-R57P5\",
                    \"status\": \"Stowed\",
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
                }
            }
        }", true);
    }

    protected function makeOrderOutputRequest(array $orderInputRequest, string $method = 'post'): object
    {
        $request = "{
            \"priority\": 10,
            \"orderDate\": \"{$orderInputRequest['detail']['order']['created_at']}\",
            \"orderLine\": [
                {
                    \"productId\": \"{$orderInputRequest['detail']['order']['items'][1]['sku']}\",
                    \"orderLineNumber\": \"{$orderInputRequest['detail']['order']['items'][1]['id']}\",
                    \"quantityOrdered\": {$orderInputRequest['detail']['order']['items'][1]['quantity']},
                    \"displayPickUomId\": \"EACH\"
                },
                {
                    \"productId\": \"{$orderInputRequest['detail']['order']['items'][2]['barcode']}\",
                    \"orderLineNumber\": \"{$orderInputRequest['detail']['order']['items'][2]['id']}\",
                    \"quantityOrdered\": {$orderInputRequest['detail']['order']['items'][2]['quantity']},
                    \"displayPickUomId\": \"EACH\"
                }
            ],
            \"orderType\": \"AutoStore\",
            \"dispatchDate\": \"{$orderInputRequest['detail']['order']['created_at']}\",
            \"instruction\": [
                {
                    \"type\": \"{$orderInputRequest['detail']['order']['instructions'][0]['type']}\",
                    \"text\": \"{$orderInputRequest['detail']['order']['instructions'][0]['text']}\"
                }
            ],
            \"shortReleasingAllowed\": false,
            \"shortAllocationAllowed\": false,
            \"capability\": [
                {
                    \"capability\": \"Compile Order Pick\"
                }
            ]
        }";

        if ($method == 'post') {
            // removing first and last brackets for concatenation
            $requestBody = trim(substr(substr($request, 1), 0, -1));

            $request = "{
                \"order\": [
                    {
                        \"owner\": \"MyOwner\",
                        \"orderId\": \"{$orderInputRequest['detail']['order']['autostore_order_id']}\",
                        {$requestBody}
                    }
                ]
            }";
        }

        return $this->castAsJson($request);
    }

    protected function makeProductInputRequest(): array
    {
        return json_decode("{
            \"version\": \"0\",
            \"id\": \"efcfe12d-8761-e5fa-777f-63cd17cd9723\",
            \"detail-type\": \"order-status-update\",
            \"source\": \"sandbox.api.order\",
            \"account\": \"249547702379\",
            \"time\": \"2022-07-14T14:55:06Z\",
            \"region\": \"us-east-1\",
            \"resources\": [],
            \"detail\": {
                \"product_variant\": {
                    \"id\": 2585,
                    \"sku\": \"EM-JKT-0CR5099-AQUA-XS\",
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
                }
            }
        }", true);
    }

    protected function makeProductOutputRequest(string $productId, string $description, string $category = '', string $imageURL = '', string $method = 'post'): object
    {
        $request = "{
            \"attribute\": [
                {
                    \"name\": \"materialStatus\",
                    \"pickingAction\": \"NONE\"
                }
            ],
            \"productUom\": [
                {
                    \"ratio\": 1,
                    \"uomId\": \"EACH\",
                    \"width\": 0,
                    \"height\": 0,
                    \"length\": 0,
                    \"volume\": 0,
                    \"weight\": 0,
                    \"imagePath\": \"{$imageURL}\",
                    \"baseUomFlag\": true,
                    \"pickUomFlag\": true,
                    \"putawayUomFlag\": true,
                    \"weightTolerance\": 0
                }
            ],
            \"description\": \"{$description}\",
            \"defaultUomId\": \"EACH\",
            \"productCategory\": \"{$category}\",
            \"scanValidationMode\": \"EACH\"
        }";

        if ($method == 'post') {
            // removing first bracket for concatenation
            $requestBody = trim(substr($request, 1));

            $request = "{
                \"owner\": \"MyOwner\",
                \"productId\": \"{$productId}\",
                {$requestBody}";
        }

        return $this->castAsJson($request);
    }

    protected function makeProductDescriptionTranslationInputRequest(): array
    {
        return json_decode("{
            \"version\": \"0\",
            \"id\": \"efcfe12d-8761-e5fa-777f-63cd17cd9723\",
            \"detail-type\": \"product_variant-update\",
            \"source\": \"sandbox.api.product\",
            \"account\": \"249547702379\",
            \"time\": \"2022-07-14T14:55:06Z\",
            \"region\": \"us-east-1\",
            \"resources\": [],
            \"detail\": {
                \"product_variant\": {
                    \"id\": 2585,
                    \"sku\": \"EM-JKT-0CR5099-AQUA-XS\",
                    \"barcode\": \"LIN11308437\",
                    \"description\": \"A great product\"
                }
            }
        }", true);
    }

    protected function makeProductDescriptionTranslationOutputRequest(string $productId, string $description, string $languageCode = 'en_US', string $method = 'post'): object
    {
        $request = "{
            \"languageCode\": \"{$languageCode}\",
            \"description\": \"{$description}\"
        }";

        if ($method == 'post') {
            // removing first bracket for concatenation
            $requestBody = trim(substr($request, 1));

            $request = "{
                \"owner\": \"MyOwner\",
                \"productId\": \"{$productId}\",
                {$requestBody}";
        }

        return $this->castAsJson($request);
    }

    protected function makeOrderItemInputRequest(): array
    {
        return json_decode("{
            \"version\": \"0\",
            \"id\": \"efcfe12d-8761-e5fa-777f-63cd17cd9723\",
            \"detail-type\": \"order-status-update\",
            \"source\": \"sandbox.api.order\",
            \"account\": \"249547702379\",
            \"time\": \"2022-07-14T14:55:06Z\",
            \"region\": \"us-east-1\",
            \"resources\": [],
            \"detail\": {
                \"order_item\": {
                    \"id\": 9244770,
                    \"item_type\": \"product\",
                    \"fulfillment_type\": \"autostore\",
                    \"status\": \"Validated\",
                    \"title\": \"Monogrammed Quilted Barn Coat\",
                    \"barcode\": \"LIN9244770\",
                    \"sku\": \"EM-JKT-1318003-BLAK-M\",
                    \"customized\": true,
                    \"quantity\": 1,
                    \"created_at\": \"2020-10-02T13:49:41Z0\",
                    \"order\": {
                        \"id\": 2559086,
                        \"number\": \"1606701-4FPAI\"
                    },
                    \"images\": [
                        {
                            \"url\": \"https://172.16.0.202/profiles/ml-product-detail/product/31816/kI1-personalized-cooler-in-lemons.jpg?wm=0\"
                        }
                    ],
                    \"attributes\": [
                        {
                            \"id\": 22878603,
                            \"title\": \"Color\",
                            \"attribute_type\": \"color\",
                            \"value\": \"Black\",
                            \"personalizable\": false
                        },
                        {
                            \"id\": 22878604,
                            \"title\": \"Size\",
                            \"attribute_type\": \"letter_size\",
                            \"value\": \"Medium\",
                            \"personalizable\": false
                        },
                        {
                            \"id\": 22878605,
                            \"title\": \"Letters\",
                            \"attribute_type\": \"text\",
                            \"value\": \"MMM\",
                            \"personalizable\": true
                        },
                        {
                            \"id\": 22878606,
                            \"title\": \"Design\",
                            \"attribute_type\": \"previewer_design\",
                            \"value\": \"Interlock\",
                            \"personalizable\": true
                        },
                        {
                            \"id\": 22878607,
                            \"title\": \"Letter Color\",
                            \"attribute_type\": \"previewer_color\",
                            \"value\": \"692D14\",
                            \"personalizable\": true
                        },
                        {
                            \"id\": 22878603,
                            \"title\": \"Color\",
                            \"attribute_type\": \"color\",
                            \"value\": \"Black\",
                            \"personalizable\": false
                        },
                        {
                            \"id\": 22878604,
                            \"title\": \"Size\",
                            \"attribute_type\": \"letter_size\",
                            \"value\": \"Medium\",
                            \"personalizable\": false
                        },
                        {
                            \"id\": 22878605,
                            \"title\": \"Letters\",
                            \"attribute_type\": \"text\",
                            \"value\": \"MMM\",
                            \"personalizable\": true
                        },
                        {
                            \"id\": 22878606,
                            \"title\": \"Design\",
                            \"attribute_type\": \"previewer_design\",
                            \"value\": \"Interlock\",
                            \"personalizable\": true
                        },
                        {
                            \"id\": 22878607,
                            \"title\": \"Letter Color\",
                            \"attribute_type\": \"previewer_color\",
                            \"value\": \"692D14\",
                            \"personalizable\": true
                        }
                    ]
                }
            }
        }", true);
    }
}
