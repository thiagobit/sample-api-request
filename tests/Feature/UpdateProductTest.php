<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateProductTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function product_can_be_updated()
    {
        $this->put(route('api.product.update'), $this->productInputRequest)
            ->assertSuccessful();

        $productId = \App\Utilities\API::getProductId($this->productInputRequest['detail']['product_variant']);

        $this->productOutputRequest = $this->makeProductOutputRequest(
            \App\Utilities\API::getProductId($this->productInputRequest['detail']['product_variant']),
            $this->productInputRequest['detail']['product_variant']['product']['title'],
            $this->productInputRequest['detail']['product_variant']['sourcing_category']['title'],
            $this->productInputRequest['detail']['product_variant']['images'][0]['url'],
            'put',
        );

        $this->assertDatabaseCount('requests', 1);
        $this->assertDatabaseHas('requests', ['path' => "products/MyOwner/{$productId}", 'method' => 'put', 'params' => $this->productOutputRequest]);
    }

    /** @test */
    public function product_cannot_be_updated_with_invalid_product_variant()
    {
        $this->productInputRequest['detail']['product_variant'] = '';

        $this->put(route('api.product.update'), $this->productInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.product_variant' => 'The detail.product variant field is required.',
            ]);

        $this->productInputRequest['detail']['product_variant'] = 'A';

        $this->put(route('api.product.update'), $this->productInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.product_variant' => 'The detail.product variant must be an array.',
            ]);
    }

    /** @test */
    public function product_cannot_be_updated_with_invalid_product_variant_id()
    {
        $this->productInputRequest['detail']['product_variant']['id'] = '';

        $this->put(route('api.product.update'), $this->productInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.product_variant.id' => 'The detail.product variant.id field is required.',
            ]);

        $this->productInputRequest['productId'] = 'a';

        $this->put(route('api.product.update'), $this->productInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.product_variant.id' => 'The detail.product variant.id must be an integer.',
            ]);
    }

    /** @test */
    public function product_cannot_be_updated_with_invalid_product_variant_sku()
    {
        $this->productInputRequest['detail']['product_variant']['sku'] = '';

        $this->put(route('api.product.update'), $this->productInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.product_variant.sku' => 'The detail.product variant.sku field is required.',
            ]);

        $this->productInputRequest['detail']['product_variant']['sku'] = str_repeat('a', 65);

        $this->put(route('api.product.update'), $this->productInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.product_variant.sku' => 'The detail.product variant.sku must not be greater than 64 characters.',
            ]);
    }

    /** @test */
    public function product_cannot_be_updated_with_invalid_product_variant_created_at()
    {
        $this->productInputRequest['detail']['product_variant']['created_at'] = [];

        $this->put(route('api.product.update'), $this->productInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.product_variant.created_at' => 'The detail.product variant.created at must be a string.',
            ]);
    }

    /** @test */
    public function product_cannot_be_updated_with_invalid_product_variant_updated_at()
    {
        $this->productInputRequest['detail']['product_variant']['updated_at'] = [];

        $this->put(route('api.product.update'), $this->productInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.product_variant.updated_at' => 'The detail.product variant.updated at must be a string.',
            ]);
    }

    /** @test */
    public function product_cannot_be_updated_with_invalid_product_variant_product()
    {
        $this->productInputRequest['detail']['product_variant']['product'] = '';

        $this->put(route('api.product.update'), $this->productInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.product_variant.product' => 'The detail.product variant.product field is required.',
            ]);

        $this->productInputRequest['detail']['product_variant']['product'] = 'A';

        $this->put(route('api.product.update'), $this->productInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.product_variant.product' => 'The detail.product variant.product must be an array.',
            ]);
    }

    /** @test */
    public function product_cannot_be_updated_with_invalid_product_variant_product_id()
    {
        $this->productInputRequest['detail']['product_variant']['product']['id'] = 'a';

        $this->put(route('api.product.update'), $this->productInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.product_variant.product.id' => 'The detail.product variant.product.id must be an integer.',
            ]);
    }

    /** @test */
    public function product_cannot_be_updated_with_invalid_product_variant_product_title()
    {
        $this->productInputRequest['detail']['product_variant']['product']['title'] = '';

        $this->put(route('api.product.update'), $this->productInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.product_variant.product.title' => 'The detail.product variant.product.title field is required.',
            ]);

        $this->productInputRequest['detail']['product_variant']['product']['title'] = str_repeat('a', 2049);

        $this->put(route('api.product.update'), $this->productInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.product_variant.product.title' => 'The detail.product variant.product.title must not be greater than 2048 characters.',
            ]);
    }

    /** @test */
    public function product_can_be_updated_with_nullable_product_variant_sourcing_category()
    {
        $this->productInputRequest['detail']['product_variant']['sourcing_category'] = null;

        $this->put(route('api.product.update'), $this->productInputRequest)
            ->assertSuccessful();

        $this->productInputRequest['detail']['product_variant']['sourcing_category'] = [];

        $this->put(route('api.product.update'), $this->productInputRequest)
            ->assertSuccessful();

        unset($this->productInputRequest['detail']['product_variant']['sourcing_category']);

        $this->put(route('api.product.update'), $this->productInputRequest)
            ->assertSuccessful();
    }

    /** @test */
    public function product_cannot_be_updated_with_invalid_product_variant_sourcing_category()
    {
        $this->productInputRequest['detail']['product_variant']['sourcing_category'] = 'A';

        $this->put(route('api.product.update'), $this->productInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.product_variant.sourcing_category' => 'The detail.product variant.sourcing category must be an array.',
            ]);
    }

    /** @test */
    public function product_cannot_be_updated_with_invalid_product_variant_sourcing_category_id()
    {
        $this->productInputRequest['detail']['product_variant']['sourcing_category']['id'] = 'a';

        $this->put(route('api.product.update'), $this->productInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.product_variant.sourcing_category.id' => 'The detail.product variant.sourcing category.id must be an integer.',
            ]);
    }

    /** @test */
    public function product_cannot_be_updated_with_invalid_product_variant_sourcing_category_title()
    {
        $this->productInputRequest['detail']['product_variant']['sourcing_category']['title'] = 1;

        $this->put(route('api.product.update'), $this->productInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.product_variant.sourcing_category.title' => 'The detail.product variant.sourcing category.title must be a string.',
            ]);

        $this->productInputRequest['detail']['product_variant']['sourcing_category']['title'] = str_repeat('a', 65);

        $this->put(route('api.product.update'), $this->productInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.product_variant.sourcing_category.title' => 'The detail.product variant.sourcing category.title must not be greater than 64 characters.',
            ]);
    }

    /** @test */
    public function product_cannot_be_updated_with_invalid_product_variant_options()
    {
        $this->productInputRequest['detail']['product_variant']['options'] = 'a';

        $this->put(route('api.product.update'), $this->productInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.product_variant.options' => 'The detail.product variant.options must be an array.',
            ]);
    }

    /** @test */
    public function product_cannot_be_updated_with_invalid_product_variant_options_id()
    {
        $this->productInputRequest['detail']['product_variant']['options'][0]['id'] = [];

        $this->put(route('api.product.update'), $this->productInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.product_variant.options.0.id' => 'The detail.product_variant.options.0.id must be a string.',
            ]);
    }

    /** @test */
    public function product_cannot_be_updated_with_invalid_product_variant_options_name()
    {
        $this->productInputRequest['detail']['product_variant']['options'][0]['name'] = [];

        $this->put(route('api.product.update'), $this->productInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.product_variant.options.0.name' => 'The detail.product_variant.options.0.name must be a string.',
            ]);
    }

    /** @test */
    public function product_cannot_be_updated_with_invalid_product_variant_options_sku_value()
    {
        $this->productInputRequest['detail']['product_variant']['options'][0]['sku_value'] = [];

        $this->put(route('api.product.update'), $this->productInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.product_variant.options.0.sku_value' => 'The detail.product_variant.options.0.sku_value must be a string.',
            ]);
    }

    /** @test */
    public function product_cannot_be_updated_with_invalid_product_variant_options_attribute()
    {
        $this->productInputRequest['detail']['product_variant']['options'][0]['attribute'] = 'a';

        $this->put(route('api.product.update'), $this->productInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.product_variant.options.0.attribute' => 'The detail.product_variant.options.0.attribute must be an array.',
            ]);
    }

    /** @test */
    public function product_cannot_be_updated_with_invalid_product_variant_options_attribute_id()
    {
        $this->productInputRequest['detail']['product_variant']['options'][0]['attribute']['id'] = [];

        $this->put(route('api.product.update'), $this->productInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.product_variant.options.0.attribute.id' => 'The detail.product_variant.options.0.attribute.id must be a string.',
            ]);
    }

    /** @test */
    public function product_cannot_be_updated_with_invalid_product_variant_options_attribute_name()
    {
        $this->productInputRequest['detail']['product_variant']['options'][0]['attribute']['name'] = [];

        $this->put(route('api.product.update'), $this->productInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.product_variant.options.0.attribute.name' => 'The detail.product_variant.options.0.attribute.name must be a string.',
            ]);
    }

    /** @test */
    public function product_cannot_be_updated_with_invalid_product_variant_options_attribute_title()
    {
        $this->productInputRequest['detail']['product_variant']['options'][0]['attribute']['title'] = [];

        $this->put(route('api.product.update'), $this->productInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.product_variant.options.0.attribute.title' => 'The detail.product_variant.options.0.attribute.title must be a string.',
            ]);
    }

    /** @test */
    public function product_cannot_be_updated_with_invalid_product_variant_options_attribute_attribute_type()
    {
        $this->productInputRequest['detail']['product_variant']['options'][0]['attribute']['attribute_type'] = [];

        $this->put(route('api.product.update'), $this->productInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.product_variant.options.0.attribute.attribute_type' => 'The detail.product_variant.options.0.attribute.attribute_type must be a string.',
            ]);
    }

    /** @test */
    public function product_can_be_updated_with_empty_product_variant_images()
    {
        $this->productInputRequest['detail']['product_variant']['images'] = [];

        $this->put(route('api.product.update'), $this->productInputRequest)
            ->assertSuccessful();
    }

    /** @test */
    public function product_cannot_be_updated_with_invalid_product_variant_images()
    {
        $this->productInputRequest['detail']['product_variant']['images'] = 'a';

        $this->put(route('api.product.update'), $this->productInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.product_variant.images' => 'The detail.product variant.images must be an array.',
            ]);
    }

    /** @test */
    public function product_cannot_be_updated_with_invalid_product_variant_images_id()
    {
        $this->productInputRequest['detail']['product_variant']['images'][0]['id'] = 'a';

        $this->put(route('api.product.update'), $this->productInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.product_variant.images.0.id' => 'The detail.product_variant.images.0.id must be an integer.',
            ]);
    }

    /** @test */
    public function product_cannot_be_updated_with_invalid_product_variant_images_url()
    {
        $this->productInputRequest['detail']['product_variant']['images'][0]['url'] = '';

        $this->put(route('api.product.update'), $this->productInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.product_variant.images.0.url' => 'The detail.product_variant.images.0.url field is required.',
            ]);

        $this->productInputRequest['detail']['product_variant']['images'][0]['url'] = str_repeat('a', 2049);

        $this->put(route('api.product.update'), $this->productInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.product_variant.images.0.url' => 'The detail.product_variant.images.0.url must not be greater than 2048 characters.',
            ]);
    }
}
