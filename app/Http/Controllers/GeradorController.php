<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use App\Models\Cidade;
use App\Models\Pessoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


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
        }else{
            return response(404);
        }
    }

    public function gerarTxt($pessoas){
        //gerar textos
        $txtContent = "";
        foreach($pessoas as $pessoa){
            $txtContent .= $pessoa->nome . " ; " . date('d-m-Y', strToTime($pessoa->dataNascimento)) . ' ; ' . $pessoa->nome_cidade . "\n";
        }
        
        header("Content-type: text/txt");
        header("Cache-Control: no-store, no-cache");
        header('Content-Disposition: attachment; filename="relatorio.txt"');
        echo $txtContent;
        $file = fopen('php://output','w');
    }
    
    public function gerarExcel($pessoas){
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();

        //tamanho
        $activeWorksheet->getColumnDimension('A')->setWidth(30);
        $activeWorksheet->getColumnDimension('B')->setWidth(30);
        $activeWorksheet->getColumnDimension('C')->setWidth(30);

        //primeiras colunas
        $activeWorksheet->setCellValue('A1', 'nome');
        $activeWorksheet->setCellValue('B1', 'data.Nasc');
        $activeWorksheet->setCellValue('C1', 'cidade');

        
        
       //atribuindo valores
        for($i = 0; $i < $pessoas->count(); $i++){
            $position = $i + 2;
            $activeWorksheet->setCellValue('A'.$position, $pessoas[$i]->nome);
            $activeWorksheet->setCellValue('B'.$position, date('d/m/Y', strToTime($pessoas[$i]->dataNascimento)));
            $activeWorksheet->setCellValue('C'.$position, $pessoas[$i]->nome_cidade);
        }

        //fazendo download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');

    }

    
    public function gerarPdf($pessoas){
        //gerando pdf
        $html =  '
        <style> 
            table {
                font-family: arial, sans-serif;
                border-collapse: collapse;
                width: 100%;
            }
            
            td, th {
                border: 1px solid #dddddd;
                text-align: left;
                padding: 8px;
            }
        </style>';

        $html .='<table>';
        $html .='
        <tr>
            <th>nome</th>
            <th>data.Nasc</th>
            <th>cidade</th>
        </tr>';
        foreach($pessoas as $pessoa){
            $pessoa->dataNascimento = date('d/m/Y', strToTime($pessoa->dataNascimento));
            $html .="
            <tr>
               <td> $pessoa->nome </td>
               <td> $pessoa->dataNascimento </td>
               <td> $pessoa->nome_cidade </td>
            </tr>
            ";
        } 
        $html .= '</table>';

        $dompdf = new DomPdf();

        $dompdf->loadHtml($html);

        $dompdf->render();
        $dompdf->stream();  
    }

    public function buscar(Request $request){
        //filtrar collection com base em parametros da busca
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
