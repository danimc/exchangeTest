<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;


class AuthTest extends TestCase
{

    public function test_prohibe_registro_vacio()
    {
        $this->post('api/registrar')
        ->assertStatus(400);
    }

    public function test_prohibe_registro_sin_nombre()
    {
        $nuevoUsuario = array(
            'email' => 'agente47@yahoo.com',
            'password'  => '1234567'
        );

         $this->json('POST','api/registrar',$nuevoUsuario)
             ->assertStatus(400)
             ->assertJsonFragment([
                 'name' => ['The name field is required.']
             ]);

    }

    public function test_prohibe_registro_sin_password()
    {
        $nuevoUsuario = array(
            'name'  => 'Sujeto de Pruebas',
            'email' => 'agente47@yahoo.com'
        );

        $this->json('POST','api/registrar',$nuevoUsuario)
            ->assertStatus(400)
            ->assertJsonFragment([
                'password' => ['The password field is required.']
            ]);
    }

    public function test_password_invalido()
    {
        $nuevoUsuario = array(
            'name'      => 'Sujeto de Pruebas',
            'email'     => 'agente47@yahoo.com',
            'password'  => '12'
        );

        $this->json('POST','api/registrar',$nuevoUsuario)
            ->assertStatus(400)
            ->assertJsonFragment([
                'password' => ['The password must be at least 3 characters.']
            ]);
    }

    public function test_prohibe_registro_sin_email()
    {
        $nuevoUsuario = array(
            'name'  => 'Sujeto de Pruebas',
            'password'  => '1234567'
        );

        $this->json('POST','api/registrar',$nuevoUsuario)
            ->assertStatus(400)
            ->assertJsonFragment([
                'email' => ['The email field is required.']
            ]);
    }

    public function test_registro_exitoso()
    {
        $nuevoUsuario = array(
            'name'  => 'Sujeto de Pruebas',
            'email'     => 'agente47@yahoo.com',
            'password'  => '1234567'
        );

        $this->json('POST','api/registrar',$nuevoUsuario)
            ->assertCreated()
            ->assertJsonFragment([
                'tipo_token' => 'Bearer'
            ]);
    }

    public function test_prohibe_email_repetido()
    {
        $nuevoUsuario = array(
            'name'  => 'Sujeto de Pruebas',
            'email'     => 'agente47@yahoo.com',
            'password'  => '1234567'
        );

        $this->json('POST','api/registrar',$nuevoUsuario)
            ->assertStatus(400)
            ->assertJsonFragment([
                'email' => ['The email has already been taken.']
            ]);
    }

    public function test_error_login()
    {
        $nuevoUsuario = array(
            'email'     => 'agente47@yahoo.com',
            'password'  => '123'
        );

        $this->json('POST','api/login')
            ->assertStatus(401)
            ->assertExactJson([
                "ok"        => false,
                "mensaje"   => "Usuario o contraseña invalido"]
            );
    }

    public function test_login_correcto()
    {
        $usuario = array(
            'email'     => 'agente47@yahoo.com',
            'password'  => '1234567'
        );

        $this->json('POST','api/login', $usuario)
            ->assertOk()
            ->assertJsonFragment(['ok' => 'true']);
    }









}
