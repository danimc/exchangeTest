<?php

namespace App\Http\Controllers;

use Goutte\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ExchangeController extends Controller
{
    public function obtExchange()
    {

        $banxico = $this->obtDatosBanxico();
        $fixer = $this->obtDatosFixer();
        $dof = $this->obtDatosDof();

        return response()->json([
           // "banxico"   => $banxico,
           // "fixer"     => $fixer,
            "dof"       => $dof
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

    private  function obtDatosDof()
    {
        $cliente = new Client();

        $crawler = $cliente->request('GET','http://dof.gob.mx/indicadores_detalle.php?cod_tipo_indicador=158&dfecha=19%2F04%2F2022&hfecha=19%2F04%2F2022');
        $strData = $crawler->filter('[class="Celda 1"]')->first();

        $valores = explode(" ", $strData->text(),2);









        echo json_encode($valores);


        die();


        return json_encode($extracto);
    }
}
