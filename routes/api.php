<?php

use App\Http\Controllers\CadastroController;
use App\Http\Controllers\CalculoImcController;
use App\Http\Controllers\EnderecoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/v1/cadastro/validar/documento/{documento}', [CadastroController::class, "validarDocumento"]);
Route::get('/v1/cadastros', [CadastroController::class, "listarCadastros"]);
Route::get('/v1/cadastro/{cpf}', [CadastroController::class, "obterCadastro"]);
Route::post('/v1/cadastro', [CadastroController::class, "salvarCadastro"]);
Route::delete('/v1/cadastro/{cpf}', [CadastroController::class, "apagarCadastro"]);


Route::get('/v1/endereco/estados', [EnderecoController::class, "listarEstados"]);
Route::get('/v1/endereco/{cep}', [EnderecoController::class, "consultarEndereco"]);

Route::post('/v1/imc/calcular', [CalculoImcController::class, "calcular"]);
