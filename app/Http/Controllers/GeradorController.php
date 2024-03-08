<?php

namespace App\Http\Controllers;

use App\Models\Cidade;
use App\Models\Pessoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class GeradorController extends Controller
{   
    public function gerar(Request $request){
 
        $pessoas = $this->buscar($request);
        if(!$pessoas->isEmpty()){
            if(isset($request->gerar_txt)){

                $this->gerarTxt($pessoas);

            }else if(isset($request->gerar_excel)){

                $this->gerarExcel($pessoas);

            }else if(isset($request->gerar_pdf)){

                $this->gerarPdf($pessoas);
            }
        }
    }

    public function gerarTxt($pessoas){
        $txtContent = "";
        foreach($pessoas as $pessoa){
            $txtContent .= $pessoa->nome . " ; " . date('d-m-Y', strToTime($pessoa->dataNascimento)) . ' ; ' . $pessoa->nome_cidade . "\n";
        }

        //Storage::disk('local')->put('currentDownload.txt', $txtContent);

        header("Content-type: text/txt");
        header("Cache-Control: no-store, no-cache");
        header('Content-Disposition: attachment; filename="relatorio.txt"');
        echo $txtContent;
        $file = fopen('php://output','w');
    }
    
    public function gerarExcel($pessoas){
        
    }

    
    public function gerarPdf($pessoas){
        
    }

    public function buscar(Request $request){
                 /**
            "nome" => null
            "data_inicio" => null
            "data_fim" => null
            "cidade" => null
            "gerar_txt" => "txt"
         */
        
         $pessoas = Pessoa::with('cidade')->join('Cidade', 'cidade.id', '=', 'pessoa.id_cidade')->select('pessoa.*', 'cidade.id as tabela_cidade_id','cidade.nome as nome_cidade', )->get();
         //dd($pessoas);
         if(isset($request->nome)){
             $pessoas = $pessoas->where('nome', $request->nome);
         }
         //onde tiver cidade
         if(isset($request->cidade)){
             $pessoas = $pessoas->filter(function($value, $key){
                 return $value->nome_cidade == request()->cidade;
             });
         }
    
         //se tiver data início, data fim 
         if(isset($request->data_inicio) && isset($request->data_fim)){
             $pessoas = $pessoas->whereBetween('dataNascimento', [request()->data_inicio, request()->data_fim]);
         }else if(isset($request->data_inicio)){
             //se não tiver data fim, assumir que o fim e até a data atual
             $pessoas = $pessoas->whereBetween('dataNascimento', [request()->data_inicio, date('Y-m-d')]);
         }

         return $pessoas;
    }
}
