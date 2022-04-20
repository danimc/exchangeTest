<?php

namespace App\Http\Controllers;

use Goutte\Client;
use Illuminate\Support\Facades\Http;

class ExchangeController extends Controller
{
    public function obtExchange()
    {

        $banxico = $this->obtDatosBanxico();
        $fixer = $this->obtDatosFixer();
        $dof = $this->obtDatosDof();

        $respuesta = array(
            "Valores" => array(
                "Fixer"=>$fixer,
                "Banxico"=>$banxico,
                "Diario Oficial de la Federación"=>$dof
            )
        );

        return response()->json($respuesta,200);
    }

    /**
     * Recupera la información de Banxico
     *
     * @return array
     */
    private function obtDatosBanxico()
    {
        $datos = HTTP::withHeaders([
            'Bmx-Token' => $_ENV['BANXICO_TOKEN'],
        ])->get('https://www.banxico.org.mx/SieAPIRest/service/v1/series/SF43718/datos/oportuno');
        $datos = $datos->object();

        $filtro = $datos->bmx->series[0]->datos[0];

        return array(
                "Ultima Actualización" => $filtro->fecha,
                "valor" => number_format($filtro->dato, 2)

        );
    }

    private function obtDatosFixer()
    {

        $datos = Http::get("http://data.fixer.io/api/latest?access_key={$_ENV['FIXER_KEY']}&format=1&symbols=MXN,USD");

        $datos = $datos->object();

        $fecha = $datos->date;
        $valores = $datos->rates;

        $usd = ($valores->MXN / $valores->USD);

        return array(
                    "Ultima Actualización" => $fecha,
                    "valor" => number_format($usd, 2),
        );
    }

    /**
     * Regresa el tipo de cambio desde el DOF
     *
     * Realiza un Scraping a la pagina del Diario Oficial de la federacion en busca del tipo de cambio
     * actual, formatea la información y regresa el array con la respuesta al endpoint
     *
     * @return array[]
     */
    private function obtDatosDof()
    {
        $cliente = new Client();

        $crawler = $cliente->request('GET', 'http://dof.gob.mx/indicadores_detalle.php?cod_tipo_indicador=158&dfecha=19%2F04%2F2022&hfecha=19%2F04%2F2022');
        $strData = $crawler->filter('[class="Celda 1"]')->first();

        $valores = explode(" ", $strData->text(), 2);

        return array(
                    "Ultima Actualización" => $valores[0],
                    "valor" => number_format($valores[1], 2)
        );
    }
}
