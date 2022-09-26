<?php

namespace Tests\Feature;

use App\Models\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateOrderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function order_can_be_created_with_status_stowed()
    {
        // product is not stored if order status is 'Stowed'
        $this->orderInputRequest['detail']['order']['status'] = 'Stowed';

        $this->post(route('api.order.store'), $this->orderInputRequest)
            ->assertSuccessful();

        $this->assertDatabaseHas('requests', ['path' => 'orders/batch', 'method' => 'post', 'params' => $this->orderOutputRequest]);
        $this->assertDatabaseMissing('requests', ['path' => 'products', 'method' => 'post']);
    }

    /** @test */
    public function order_cannot_be_created_with_invalid_order()
    {
        $this->orderInputRequest['detail']['order'] = '';

        $this->post(route('api.order.store'), $this->orderInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.order' => 'The detail.order field is required.',
            ]);

        $this->orderInputRequest['detail']['order'] = 'a';

        $this->post(route('api.order.store'), $this->orderInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.order' => 'The detail.order must be an array.',
            ]);
    }

    /** @test */
    public function order_cannot_be_created_with_invalid_order_autostore_order_id()
    {
        $this->orderInputRequest['detail']['order']['autostore_order_id'] = '';

        $this->post(route('api.order.store'), $this->orderInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.order.autostore_order_id' => 'The detail.order.autostore order id field is required.',
            ]);

        $this->orderInputRequest['detail']['order']['autostore_order_id'] = 1;

        $this->post(route('api.order.store'), $this->orderInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.order.autostore_order_id' => 'The detail.order.autostore order id must be a string.',
            ]);

        $this->orderInputRequest['detail']['order']['autostore_order_id'] = str_repeat(1, 65);

        $this->post(route('api.order.store'), $this->orderInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.order.autostore_order_id' => 'The detail.order.autostore order id must not be greater than 64 characters.',
            ]);
    }

    /** @test */
    public function order_cannot_be_created_with_invalid_order_status()
    {
        $this->orderInputRequest['detail']['order']['status'] = [];

        $this->post(route('api.order.store'), $this->orderInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.order.status' => 'The detail.order.status must be a string.',
            ]);

        $this->orderInputRequest['detail']['order']['status'] = 'Pending payment';

        $this->post(route('api.order.store'), $this->orderInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.order.status' => 'The selected detail.order.status is invalid.',
            ]);
    }

    /** @test */
    public function order_cannot_be_created_with_invalid_order_created_at()
    {
        $this->orderInputRequest['detail']['order']['created_at'] = '';

        $this->post(route('api.order.store'), $this->orderInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.order.created_at' => 'The detail.order.created at field is required.',
            ]);

        $this->orderInputRequest['detail']['order']['created_at'] = [];

        $this->post(route('api.order.store'), $this->orderInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.order.created_at' => 'The detail.order.created at must be a string.',
            ]);
    }

    /** @test */
    public function order_cannot_be_created_with_invalid_order_instructions()
    {
        $this->orderInputRequest['detail']['order']['instructions'] = 'a';

        $this->post(route('api.order.store'), $this->orderInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.order.instructions' => 'The detail.order.instructions must be an array.',
            ]);
    }

    /** @test */
    public function order_cannot_be_created_with_invalid_order_instructions_type()
    {
        $this->orderInputRequest['detail']['order']['instructions'][0]['type'] = '';

        $this->post(route('api.order.store'), $this->orderInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.order.instructions.0.type' => 'The detail.order.instructions.0.type field is required.',
            ]);

        $this->orderInputRequest['detail']['order']['instructions'][0]['type'] = [];

        $this->post(route('api.order.store'), $this->orderInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.order.instructions.0.type' => 'The detail.order.instructions.0.type must be a string.',
            ]);

        $this->orderInputRequest['detail']['order']['instructions'][0]['type'] = str_repeat('a', 33);

        $this->post(route('api.order.store'), $this->orderInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.order.instructions.0.type' => 'The detail.order.instructions.0.type must not be greater than 32 characters.',
            ]);
    }

    /** @test */
    public function order_cannot_be_created_with_invalid_order_instructions_text()
    {
        $this->orderInputRequest['detail']['order']['instructions'][0]['text'] = '';

        $this->post(route('api.order.store'), $this->orderInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.order.instructions.0.text' => 'The detail.order.instructions.0.text field is required.',
            ]);

        $this->orderInputRequest['detail']['order']['instructions'][0]['text'] = [];

        $this->post(route('api.order.store'), $this->orderInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.order.instructions.0.text' => 'The detail.order.instructions.0.text must be a string.',
            ]);

        $this->orderInputRequest['detail']['order']['instructions'][0]['text'] = str_repeat('a', 2049);

        $this->post(route('api.order.store'), $this->orderInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.order.instructions.0.text' => 'The detail.order.instructions.0.text must not be greater than 2048 characters.',
            ]);
    }

    /** @test */
    public function order_create_has_no_instructions_if_it_doesnt_exist_in_the_request()
    {
        unset($this->orderInputRequest['detail']['order']['instructions']);

        $this->post(route('api.order.store'), $this->orderInputRequest)
            ->assertSuccessful();

        $storedOrder = Request::where('path', 'orders/batch')->first();
        $storedOrderParams = json_decode($storedOrder->params, true);

        $this->assertArrayNotHasKey('instructions', $storedOrderParams['order'][0]);
    }

    /** @test */
    public function order_cannot_be_created_with_invalid_order_items()
    {
        $this->orderInputRequest['detail']['order']['items'] = '';

        $this->post(route('api.order.store'), $this->orderInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.order.items' => 'The detail.order.items field is required.',
            ]);

        $this->orderInputRequest['detail']['order']['items'] = 'a';

        $this->post(route('api.order.store'), $this->orderInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.order.items' => 'The detail.order.items must be an array.',
            ]);
    }

    /** @test */
    public function order_cannot_be_created_with_invalid_order_items_id()
    {
        $this->orderInputRequest['detail']['order']['items'][0]['id'] = '';

        $this->post(route('api.order.store'), $this->orderInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.order.items.0.id' => 'The detail.order.items.0.id field is required.',
            ]);

        $this->orderInputRequest['detail']['order']['items'][0]['id'] = 'a';

        $this->post(route('api.order.store'), $this->orderInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.order.items.0.id' => 'The detail.order.items.0.id must be an integer.',
            ]);
    }

    /** @test */
    public function order_cannot_be_created_with_invalid_order_items_sku()
    {
        $this->orderInputRequest['detail']['order']['items'][0]['sku'] = [];

        $this->post(route('api.order.store'), $this->orderInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.order.items.0.sku' => 'The detail.order.items.0.sku must be a string.',
            ]);

        $this->orderInputRequest['detail']['order']['items'][0]['sku'] = str_repeat('a', 65);

        $this->post(route('api.order.store'), $this->orderInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.order.items.0.sku' => 'The detail.order.items.0.sku must not be greater than 64 characters.',
            ]);
    }

    /** @test */
    public function order_cannot_be_created_with_invalid_order_items_barcode()
    {
        $this->orderInputRequest['detail']['order']['items'][0]['barcode'] = [];

        $this->post(route('api.order.store'), $this->orderInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.order.items.0.barcode' => 'The detail.order.items.0.barcode must be a string.',
            ]);
    }

    /** @test */
    public function order_cannot_be_created_with_invalid_order_items_item_type()
    {
        $this->orderInputRequest['detail']['order']['items'][0]['item_type'] = [];

        $this->post(route('api.order.store'), $this->orderInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.order.items.0.item_type' => 'The detail.order.items.0.item_type must be a string.',
            ]);
    }

    /** @test */
    public function order_cannot_be_created_with_invalid_order_items_fulfillment_type()
    {
        $this->orderInputRequest['detail']['order']['items'][0]['fulfillment_type'] = 1;

        $this->post(route('api.order.store'), $this->orderInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.order.items.0.fulfillment_type' => 'The detail.order.items.0.fulfillment_type must be a string.',
            ]);
    }

    /** @test */
    public function order_cannot_be_created_with_invalid_order_items_customized()
    {
        $this->orderInputRequest['detail']['order']['items'][0]['customized'] = 'a';

        $this->post(route('api.order.store'), $this->orderInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.order.items.0.customized' => 'The detail.order.items.0.customized field must be true or false.',
            ]);
    }

    /** @test */
    public function order_cannot_be_created_with_invalid_order_items_quantity()
    {
        $this->orderInputRequest['detail']['order']['items'][0]['quantity'] = '';

        $this->post(route('api.order.store'), $this->orderInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.order.items.0.quantity' => 'The detail.order.items.0.quantity field is required.',
            ]);

        $this->orderInputRequest['detail']['order']['items'][0]['quantity'] = 'a';

        $this->post(route('api.order.store'), $this->orderInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.order.items.0.quantity' => 'The detail.order.items.0.quantity must be a number.',
            ]);
    }

    /** @test */
    public function order_cannot_be_created_with_invalid_order_items_images()
    {
        unset($this->orderInputRequest['detail']['order']['items'][0]['images']);

        $this->post(route('api.order.store'), $this->orderInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.order.items.0.images' => 'The detail.order.items.0.images field must be present.',
            ]);

        $this->orderInputRequest['detail']['order']['items'][0]['images'] = 'a';

        $this->post(route('api.order.store'), $this->orderInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.order.items.0.images' => 'The detail.order.items.0.images must be an array.',
            ]);
    }

    /** @test */
    public function order_cannot_be_created_with_invalid_order_items_images_url()
    {
        $this->orderInputRequest['detail']['order']['items'][0]['images'][0]['url'] = '';

        $this->post(route('api.order.store'), $this->orderInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.order.items.0.images.0.url' => 'The detail.order.items.0.images.0.url field is required.',
            ]);

        $this->orderInputRequest['detail']['order']['items'][0]['images'][0]['url'] = [];

        $this->post(route('api.order.store'), $this->orderInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.order.items.0.images.0.url' => 'The detail.order.items.0.images.0.url must be a string.',
            ]);

        $this->orderInputRequest['detail']['order']['items'][0]['images'][0]['url'] = str_repeat('a', 2049);

        $this->post(route('api.order.store'), $this->orderInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.order.items.0.images.0.url' => 'The detail.order.items.0.images.0.url must not be greater than 2048 characters.',
            ]);
    }

    /** @test */
    public function order_cannot_create_a_request_if_there_are_no_items_with_valid_item_type()
    {
        $this->orderInputRequest['detail']['order']['items'][0]['item_type'] = 'shipping';
        $this->orderInputRequest['detail']['order']['items'][1]['item_type'] = 'shipping';
        $this->orderInputRequest['detail']['order']['items'][2]['item_type'] = 'shipping';

        $this->post(route('api.order.store'), $this->orderInputRequest)
            ->assertUnprocessable();

        $this->assertDatabaseCount('requests', 0);
    }

    /** @test */
    public function order_cannot_create_a_request_if_there_are_no_items_that_fulfills_autostore()
    {
        $this->orderInputRequest['detail']['order']['items'][0]['fulfillment_type'] = '';
        $this->orderInputRequest['detail']['order']['items'][1]['fulfillment_type'] = '';
        $this->orderInputRequest['detail']['order']['items'][2]['fulfillment_type'] = '';

        $this->post(route('api.order.store'), $this->orderInputRequest)
            ->assertUnprocessable();

        $this->assertDatabaseCount('requests', 0);
    }
}
