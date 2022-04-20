<?php

namespace App\Http\Controllers;


use App\Models\User;
use http\Env\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function registrar(RegisterRequest $request): JsonResponse
    {

       $nuevoUsuario = User::create([
           'name'   => $request['name'],
           'email'  => $request['email'],
           'password'=> Hash::make(request()['password'])
       ]);

       $token = $nuevoUsuario->createToken('auth_token')->plainTextToken;

       return response()->json([
           'ok'         => true,
           'token_generado' => $token,
           'tipo_token'     => 'Bearer'
       ],201);
    }

    public function login(Request $request): JsonResponse
    {
        if(!Auth::attempt($request->only('email','password'))) {
            return response()->json([
                'ok'    => false,
                'mensaje'   => 'Usuario o contraseña invalido'
            ], 401);
        }

        $usuario = User::where('email', $request['email'])->firstOrFail();

        $token = $usuario->createToken('auth_token')->plainTextToken;

        return response()->json([
            'ok'             => 'true',
            'token_generado' => $token,
            'tipo_token'     => 'Bearer'
        ]);
    }
}
