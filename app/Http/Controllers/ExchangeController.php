<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ExchangeController extends Controller
{
    public function obtExchange()
    {

        $banxico = $this->obtDatosBanxico();
        $fixer = $this->obtDatosFixer();

        return response()->json([
            "banxico"   => $banxico,
            "fixer"     => $fixer
        ], 200);
    }

    /**
     * Recupera la informacion de Banxico
     *
     * @return array
     */
    private  function obtDatosBanxico()
    {
        $datos = HTTP::withHeaders([
            'Bmx-Token' => 'ecc29a8c92a6f342b48683de018b2f154324aa69ad7c041dfcbb0357667ef0f4'
        ])->get('https://www.banxico.org.mx/SieAPIRest/service/v1/series/SF43718/datos/oportuno');

        return $datos->json();
    }

    private function obtDatosFixer()
    {
        $KEY =  "c0bef04b1e0d3b06a0739e9d88990545";

        $datos = Http::get("http://data.fixer.io/api/latest?access_key={$KEY}&format=1&symbols=MXN,USD");

        return $datos->json();
    }
}
