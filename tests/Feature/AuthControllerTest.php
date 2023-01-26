<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testCanRegister()
    {
        $payload = [
            'name' => 'danielhe4rt',
            'email' => 'hey@danielheart.dev',
            'password' => 'postepado123@#lalala',
            'password_confirmation' => 'postepado123@#lalala'
        ];

        $response = $this->postJson(route('auth-register'), $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('users', [
            'email' => $payload['email']
        ]);
    }

    public function testCanAuth()
    {
        $user = User::factory()->create(['email' => 'hey@danielheart.dev']);


        $payload = [
            'email' => 'hey@danielheart.dev',
            'password' => 'he4rtftw123',
        ];

        $response = $this->postJson(route('auth-login'), $payload);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Usuário autenticado']);
    }
}
