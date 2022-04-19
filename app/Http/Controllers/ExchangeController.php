<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExchangeController extends Controller
{
    public function obtExchange()
    {
        return response()->json([
            "mensaje" => "Hola desde aqui"
        ], 200);
    }
}
