<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CalculoImcController extends Controller
{

    /**
     * @OA\Post(
     *     tags={"IMC"},
     *     summary="Faz o cálculo do IMC",
     *     description="Realiza o cálculo do imc",
     *     path="/api/v1/imc/calcular",
     *     @OA\Parameter(
     *         description="Accept",
     *         in="header",
     *         name="Accept",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="applicationJson", value="application/json", summary="application/json"),
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 example=
     *                  {
     *                     "peso": "70.5",
     *                     "altura": "1.71"
     *                  }
     *             )
     *         )
     *     ),
     *     @OA\Response(response="200", description="Ok", @OA\JsonContent(@OA\Examples(example="result", value={"message": "Seu IMC é 20.2 Está classificado como Normal"},summary="Ok"))),
     *     @OA\Response(response="422", description="Parâmetros enviados são inválidos", @OA\JsonContent(@OA\Examples(example="result", value={{"message": "O campo ""peso"" é requerido. (and 1 more error)","errors": {"peso": {"O campo ""peso"" é requerido."},"altura": {"O campo ""altura"" é requerido."}}}},summary="Entidade Não processável"))),
     * ),
     * 
    */
    public function calcular(Request $request)
    {
        $rules = $this->getRegras();
        $customMessages = $this->getMensagensCustomizadas();
        $niceNames = $this->getNomeDeAtributosCustomizados();
        $calc = $this->validate($request, $rules, $customMessages, $niceNames);
        $imc = round($calc['peso'] / pow($calc['altura'], 2), 1);
        $mensagem = "";

        if($imc < 18.5){
            $mensagem = "Seu IMC é $imc. Está classificado como Magreza.";
        }else if($imc < 25){
            $mensagem = "Seu IMC é $imc. Está classificado como Normal.";
        }else if($imc < 30){
            $mensagem = "Seu IMC é $imc. Está classificado como Sobrepeso.";
        }else if($imc < 40){
            $mensagem = "Seu IMC é $imc. Está classificado como Obesidade.";
        }else{
            $mensagem = "Seu IMC é $imc. Está classificado como Obesidade Grave.";
        }

        return response()->json(["message" => $mensagem]);
    }


    private function getRegras(){
        return [
            'peso' => 'required|numeric|min:1|max:500',
            'altura' => 'required|numeric|min:0.1|max:3'
        ];
    }

    private function getMensagensCustomizadas()
    {
        return [
            'required' => 'O campo ":attribute" é requerido.',
            'size' => 'O campo ":attribute" tem que ter :size caracteres.',
            'in' => 'O ":attribute" selecionado não é válido.',
            'max' => 'O campo ":attribute" não pode ter mais de :max.',
            'min' => 'O campo ":attribute" não pode ter menos de :min.',
            'date' => 'O campo ":attribute" não tem uma data válida.',
        ];
    }

    private function getNomeDeAtributosCustomizados(){
        return [];
    }

}