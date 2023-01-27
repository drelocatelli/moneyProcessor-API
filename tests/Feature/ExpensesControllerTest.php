<?php

namespace Tests\Feature;

use App\Models\Expenses\Expenses;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ExpensesControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testIndex()
    {
        $user = User::factory()
            ->has(Expenses::factory())
            ->create();

        $response = $this->actingAs($user)
            ->getJson(route('expenses.index'));

        $response->assertStatus(Response::HTTP_OK);
    }

    public function testCreate()
    {
        $user = User::factory()
            ->has(Expenses::factory())
            ->create();

        $payload = [
            'title' => 'titulo foda',
            'total' => 12,
        ];

        $response = $this->actingAs($user)
            ->postJson(route('expenses.create'), $payload);

        $response->assertStatus(Response::HTTP_CREATED);
        $this->assertDatabaseHas('expenses', $payload);
    }

    public function testUpdate()
    {

    }


    
}
