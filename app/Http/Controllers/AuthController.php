<?php

namespace App\Http\Controllers;


use App\Models\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function registrar(Request $request)
    {
       $validacion = $request->validate([
           'name'   => 'required|string|max:200',
           'email'  => 'required|string|email|max:255|unique:users',
           'password'=> 'required|string|min:3'
       ]);

       $nuevoUsuario = User::create([
           'name'   => $validacion['name'],
           'email'  => $validacion['email'],
           'password'=> Hash::make($validacion['password'])
       ]);

       $token = $nuevoUsuario->createToken('auth_token')->plainTextToken;

       return response()->json([
           'token_generado' => $token,
           'tipo_token'     => 'Bearer'
       ]);
    }

    public function login(Request $request)
    {
        if(!Auth::attempt($request->only('email','password'))) {
            return response()->json([
                'mensaje'   => 'Usuario o contraseña invalido'
            ], 401);
        }

        $usuario = User::where('email', $request['email'])->firstOrFail();

        $token = $usuario->createToken('auth_token')->plainTextToken;


        return response()->json([
            'token_generado' => $token,
            'tipo_token'     => 'Bearer'
        ]);
    }
}
