<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class OrderTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $headers;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();

        $token = JwtAuth::fromUser($user);
        $this->headers = ['Authorization' => "Bearer $token"];
    }

    public function test_guess_can_not_place_order()
    {
        $response = $this->postJson('/api/order', []);

        $response->assertStatus(401);
    }

    public function test_users_can_place_order()
    {
        $product = Product::factory()->create();
        $currentStock = $product->available_stock;

        $data = [
            'product_id' => $product->id,
            'quantity' => $this->faker()->numberBetween(1, 10)
        ];

        $response = $this->postJson('/api/order', $data,  $this->headers);

        $response->assertStatus(201)
            ->assertJson(['message' => 'You have successfully ordered this product']);

        $this->assertEquals($product->fresh()->available_stock, $currentStock - $data['quantity']);
    }

    public function test_users_can_not_place_order_with_product_out_of_stock()
    {
        $product = Product::factory()->create(['available_stock' => 0]);

        $data = [
            'product_id' => $product->id,
            'quantity' => $this->faker()->numberBetween(1, 10)
        ];

        $response = $this->postJson('/api/order', $data,  $this->headers);

        $response->assertStatus(400)
            ->assertJson(['message' => 'Failed to order this product due to unavailability of the stock']);
    }
}
