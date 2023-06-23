<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class SearchController extends Controller
{
    public function index(){

        $resultados = DB::table('cotacao')
        ->join('servicos', 'cotacao.id_servico', '=', 'servicos.id')
        ->join('transportadoras', 'servicos.id_transportadora', '=', 'transportadoras.id')
        ->select('cotacao.*', 'servicos.*', 'transportadoras.*')->paginate(1);

        return view('index' , compact('resultados'));
    }



    public function search(Request $request){

        $cep_origem = $request->cep_origem;
        $cep_destino = $request->cep_destino;
        $peso = $request->peso;

        $resultados = DB::table('cotacao')
        ->join('servicos', 'cotacao.id_servico', '=', 'servicos.id')
        ->join('transportadoras', 'servicos.id_transportadora', '=', 'transportadoras.id')
        ->select('cotacao.*', 'servicos.*', 'transportadoras.*')->groupBy('cotacao.id', 'servicos.id', 'transportadoras.id')
        ->orderby('transportadoras.nm_transportadora' , 'ASC');


        if($cep_origem)
        {
            $resultados = $resultados->where("cep_inicio", "$request->cep_origem");
        }
        if($cep_destino)
        {
            $resultados = $resultados->where('cep_final', '=' ,"$request->cep_destino");
        }
        if($peso)
        {
            $resultados = $resultados->where('peso_final', '=' ,"$request->peso");
        }
        $resultados = $resultados->get();

        return view('result', compact('resultados','cep_origem','cep_destino','peso'));
    }
}

