<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteOrderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function order_can_be_deleted()
    {
        $this->delete(route('api.order.destroy'), $this->orderInputRequest)
            ->assertSuccessful();

        $this->assertDatabaseCount('requests', 1);
        $this->assertDatabaseHas('requests', [
            'path' => "orders/MyOwner/{$this->orderInputRequest['detail']['order']['autostore_order_id']}",
            'method' => 'delete',
        ]);
    }

    /** @test */
    public function order_cannot_be_deleted_with_invalid_order()
    {
        $this->orderInputRequest['detail']['order'] = '';

        $this->delete(route('api.order.destroy'), $this->orderInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.order' => 'The detail.order field is required.',
            ]);

        $this->orderInputRequest['detail']['order'] = 'a';

        $this->delete(route('api.order.destroy'), $this->orderInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.order' => 'The detail.order must be an array.',
            ]);
    }

    /** @test */
    public function order_cannot_be_deleted_with_invalid_order_autostore_order_id()
    {
        $this->orderInputRequest['detail']['order']['autostore_order_id'] = '';

        $this->delete(route('api.order.destroy'), $this->orderInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.order.autostore_order_id' => 'The detail.order.autostore order id field is required.',
            ]);

        $this->orderInputRequest['detail']['order']['autostore_order_id'] = 1;

        $this->delete(route('api.order.destroy'), $this->orderInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.order.autostore_order_id' => 'The detail.order.autostore order id must be a string.',
            ]);

        $this->orderInputRequest['detail']['order']['autostore_order_id'] = str_repeat(1, 65);

        $this->delete(route('api.order.destroy'), $this->orderInputRequest)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'detail.order.autostore_order_id' => 'The detail.order.autostore order id must not be greater than 64 characters.',
            ]);
    }
}
