<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_new_users_can_register()
    {
        $response = $this->postJson('/api/register', [
            'email' => $this->faker()->email,
            'password' => $this->faker()->password,
        ]);

        $response->assertStatus(201)
            ->assertJson(['message' => 'User successfully registered']);
    }

    public function test_new_users_can_not_register_with_existing_email()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/register', [
            'email' => $user->email,
            'password' => $this->faker()->password,
        ]);

        $response->assertStatus(400)
            ->assertJson(['message' => 'Email already taken']);
    }
}
