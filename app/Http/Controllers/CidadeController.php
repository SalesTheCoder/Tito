<?php

namespace App\Http\Controllers;

use App\Models\Cidade;
use Illuminate\Http\Request;

class CidadeController extends Controller
{
    public function create(){
        return view('cidade.cadastrar_cidade');
    }

    public function store(Request $request){
        //colocando no BD
        $cidade = new Cidade;
        $cidade->nome = $request->nome;
        if($cidade->save()){
            return redirect('/cadastrarcidade')->with('sucesso', 'cadastro realizado com sucesso');
        }else{
            return redirect('/cadastrarcidade')->with('erro', 'erro no cadastro');
        }

    }
}
