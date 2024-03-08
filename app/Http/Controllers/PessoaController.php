<?php

namespace App\Http\Controllers;

use App\Models\Cidade;
use App\Models\Pessoa;
use Illuminate\Http\Request;

class PessoaController extends Controller
{
    public function show(){
        //consultar pessoa
        $pessoas = Pessoa::with('cidade')->get();
        dd($pessoas);
    }

    public function create(){
        $cidades = Cidade::all();

        return view('pessoa.cadastrar_pessoa', ['cidades'=>$cidades]);
    }

    public function store(Request $request){
        //colocando no BD
        $pessoa = new Pessoa;
        $pessoa->id_cidade = $request->cidade;
        $pessoa->nome = $request->nome;
        $pessoa->idade = $request->idade;
        $pessoa->dataNascimento = $request->dataNascimento;

        if($pessoa->save()){
            return redirect('/cadastrarpessoa')->with('sucesso', 'cadastro realizado com sucesso');
        }else{
            return redirect('/cadastrarpessoa')->with('erro', 'erro no cadastro');
        }

    }

    public function busca(){
        return view('pesquisar');
    }
}
