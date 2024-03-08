<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CidadeController;
use App\Http\Controllers\PessoaController;
use App\Http\Controllers\GeradorController;



Route::get('/', function () {
    return view('index');
});
//cadastros
Route::get('/cadastrarpessoa', [PessoaController::class, 'create']);
Route::get('/cadastrarcidade', [CidadeController::class, 'create']);

//store
Route::post('/storepessoa', [PessoaController::class, 'store']);
Route::post('/storecidade', [CidadeController::class, 'store']);

//consultas
Route::get('/consultarpessoa', [PessoaController::class, 'show']);
Route::get('/consultarcidade', [CidadeController::class, 'show']);

//busca
Route::get('/relatorio', [PessoaController::class, 'busca']);

//geradores
Route::post("/gerar", [GeradorController::class, 'gerar']);
