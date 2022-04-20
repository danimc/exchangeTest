<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;


class ExchangeTest extends TestCase
{

    public function test_no_autorizado()
    {
        $this->json('GET','api/exchange')
            ->assertStatus(401)
            ->assertJsonFragment([
                "message" => "Unauthenticated."
            ]);
    }


    public function test_respuesta_valida()
    {
        Sanctum::actingAs(
            User::factory()->create([
                'name'  => 'test',
                'email' =>  'prueba@correo.com',
                'password' => '123sfd']),
            ['*']
        );

        $response = $this->get('api/exchange');

        $response->assertOk();
    }
}
