<?php

namespace App\Http\Controllers;

use Goutte\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class ExchangeController extends Controller
{
    public function index(): JsonResponse
    {

        $banxico = $this->_obtDatosBanxico();
        $fixer = $this->_obtDatosFixer();
        $dof = $this->_obtDatosDof();

        return response()->json(array(
            "Valores" => array(
                "Fixer"=>$fixer,
                "Banxico"=>$banxico,
                "Diario Oficial de la Federación"=>$dof
            )
        ));
    }

    /**
     * Recupera la información de Banxico
     *
     * @return array
     */
    private function _obtDatosBanxico(): array
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

    private function _obtDatosFixer(): array
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
     * Realiza un Scraping a la página del Diario Oficial de la federacion en busca del tipo de cambio
     * actual, formatea la información y regresa el array con la respuesta al endpoint
     *
     * @return array[]
     */
    private function _obtDatosDof(): array
    {
        $cliente = new Client();

        $urlRequest = "http://dof.gob.mx/indicadores_detalle.php?cod_tipo_indicador=158";

        $fecha = date('Y-m-d');
        $af = explode('-', $fecha); //array fecha
        $fechaRequest = "{$af['2']}%2F{$af[1]}%2F{$af[0]}";


        $crawler = $cliente->request('GET', "{$urlRequest}&dfecha={$fechaRequest}&hfecha={$fechaRequest}");
        $strData = $crawler->filter('[class="Celda 1"]')->first();

        $valores = explode(" ", $strData->text(), 2);

        return array(
                    "Ultima Actualización" => $valores[0],
                    "valor" => number_format($valores[1], 2)
        );
    }
}
