<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use App\Models\Estado;

class EnderecoController extends Controller
{


    /**
     * @OA\Get(
     *     tags={"Endereco"},
     *     summary="Constulta endereço pelo CEP",
     *     description="Constulta endereço pelo CEP",
     *     path="/api/v1/endereco/{cep}",
     *     @OA\Parameter(
     *         description="Accept",
     *         in="header",
     *         name="Accept",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="applicationJson", value="application/json", summary="application/json"),
     *     ),
     *     @OA\Parameter(
     *         description="CEP",
     *         in="path",
     *         name="cep",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="cepValido", value="01001000", summary="CEP Válido"),
     *         @OA\Examples(example="cepInvalido", value="0100100", summary="CEP Inválido"),
     *     ),
     *     @OA\Response(response="200", description="Ok", @OA\JsonContent(@OA\Examples(example="result", value={"cep": "01001-000","logradouro": "Praça da Sé","complemento": "lado ímpar","bairro": "Sé","localidade": "São Paulo","uf": "SP","ibge": "3550308","gia": "1004","ddd": "11","siafi": "7107"},summary="Endereco Encontrado"))),
     *     @OA\Response(response="400", description="CEP Inválido", @OA\JsonContent(@OA\Examples(example="result", value={"message": "CEP deve ter 8 dígitos"},summary="CEP Inválido"))),
     *     @OA\Response(response="404", description="CEP não encontrado", @OA\JsonContent(@OA\Examples(example="result", value={"message": "Deu algo errado na busca deste endereço. Verifique se digitou o CEP corretamente."},summary="CEP não encontrado"))),
     *     @OA\Response(response="500", description="Erro interno no servidor", @OA\JsonContent(@OA\Examples(example="result", value={"message": "Ocorreu um erro inesperado, por favor, tente mais tarde.","error": ""},summary="Erro Interno"))),
     * ),
     * 
    */
    public function consultarEndereco($cep)
    {
        $cep = $this->validarCPF($cep);
        $url = "https://viacep.com.br/ws/$cep/json/";
        try {
            $endereco = json_decode(file_get_contents($url));
            if (isset($endereco->erro))
                return response()->json(
                    ["message" => "Deu algo errado na busca deste endereço. Verifique se digitou o CEP corretamente."],
                    Response::HTTP_BAD_REQUEST
                );

            if (isset($endereco->cep)) return response()->json($endereco, Response::HTTP_OK);
        } catch (\Exception $ex) {
            return response()->json(["message" => "Ocorreu um erro inesperado, por favor, tente mais tarde.", "error" => $ex->getMessage()], 
            Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

        /**
     * @OA\Get(
     *     tags={"Endereco"},
     *     summary="Lista de estados brasileiros",
     *     description="Lista de estados brasileiros",
     *     path="/api/v1/endereco/estados",
     *     @OA\Parameter(
     *         description="Accept",
     *         in="header",
     *         name="Accept",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="applicationJson", value="application/json", summary="application/json"),
     *     ),
     *     @OA\Response(response="200", description="Ok", @OA\JsonContent(@OA\Examples(example="result", value={  {"uf": "AC", "nome": "Acre"}, {"uf": "AL", "nome": "Alagoas"} },summary="Lista de Estados"))),
     *     @OA\Response(response="204", description="Sem conteúdo"),
     * ),
     * 
    */
    public function listarEstados(){
        $estados = Estado::orderBy('nome', 'ASC')->get();

        if( count($estados) == 0 ){
            return response()->json([], Response::HTTP_NO_CONTENT);
        }
        return response()->json($estados);
    }

    private function validarCPF($cep)
    {
        $cep = preg_replace( '/[^0-9]/is', '', $cep);
        if (strlen($cep) != 8) {
            abort(Response::HTTP_BAD_REQUEST, "CEP deve ter 8 dígitos");
        }

        return $cep;
    }
}
