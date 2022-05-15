<?php

namespace App\Http\Controllers;

use App\Models\Cadastro;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CadastroController extends Controller
{



    /**
     * @OA\Get(
     *     tags={"Cadastro"},
     *     summary="Validar documento",
     *     description="Valida se documento é válido para cadastro",
     *     path="/api/v1/cadastro/validar/documento/{documento}",
     *     @OA\Parameter(
     *         description="Accept",
     *         in="header",
     *         name="Accept",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="applicationJson", value="application/json", summary="application/json"),
     *     ),
     *     @OA\Parameter(
     *         description="CPF",
     *         in="path",
     *         name="documento",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="cpfValido", value="00000000191", summary="CPF Válido"),
     *         @OA\Examples(example="cpfInvalido", value="00000000190", summary="CPF Inválido"),
     *     ),
     *     @OA\Response(response="200", description="Ok", @OA\JsonContent(@OA\Examples(example="result", value={"message": "Este documento é válido"},summary="Documento válido"))),
     *     @OA\Response(response="400", description="Documento é inválido", @OA\JsonContent(@OA\Examples(example="result", value={"message": "Este documento é inválido"},summary="Documento Inválido"))),
     *     @OA\Response(response="412", description="Documento já foi cadastrado", @OA\JsonContent(@OA\Examples(example="result", value={"message": "Este documento já foi cadastrado"},summary="Documento Já Cadastrado"))),
     * ),
     * 
    */
    public function validarDocumento($documento)
    {
        $documentoValidado = $this->validarCPF($documento);
        return response()->json(["message" => $documentoValidado->message], $documentoValidado->httpResponse);
    }

    /**
     * @OA\Get(
     *     tags={"Cadastro"},
     *     summary="Listar cadastros",
     *     description="Lista todos os cadastros",
     *     path="/api/v1/cadastros",
     *     @OA\Parameter(
     *         description="Accept",
     *         in="header",
     *         name="Accept",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="applicationJson", value="application/json", summary="application/json"),
     *     ),
     *     @OA\Response(response="200", description="Ok",
     *         @OA\JsonContent(
     *             @OA\Examples(example="ListaCadastro", value={"cadastros": {
     * 
     * {
     *                                                     "id": 1,
     *                                                     "nomeCompleto": "João Silva",
     *                                                     "dataNascimento": "1920-01-01",
     *                                                     "sexo": "M",
     *                                                     "cep": "05555000",
     *                                                     "cpf": "12345678901",
     *                                                     "uf": "SP",
     *                                                     "cidade": "São Paulo",
     *                                                     "logradouro": "Rua do teste",
     *                                                     "numeroLogradouro": "202 A",
     *                                                     "email": "joao@teste.com",
     *                                                     "created_at": "2000-01-01T12:00:00.000000Z",
     *                                                     "updated_at": "2000-01-01T12:00:00.000000Z"
     * }
     * }},summary="ListaCadastro")),
     *     ),
     *     @OA\Response(response="204", description="Não tem conteúdo"),
     * ),
     * 
    */
    public function listarCadastros(){
        $cadastros = Cadastro::all();
        if( count( $cadastros ) > 0){
            return response()->json(["cadastros" => Cadastro::all()]);
        }

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    /**
     * @OA\Delete(
     *     tags={"Cadastro"},
     *     summary="Apaga cadastro pelo documento",
     *     description="Apaga cadastro pelo documento",
     *     path="/api/v1/cadastro/{documento}",
     *     @OA\Parameter(
     *         description="Accept",
     *         in="header",
     *         name="Accept",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="applicationJson", value="application/json", summary="application/json"),
     *     ),
     *     @OA\Parameter(
     *         description="CPF",
     *         in="path",
     *         name="documento",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="cpfValido", value="00000000191", summary="Documento"),
     *     ),
     *     @OA\Response(response="204", description="Cadastro apagado com sucesso"),
     *     @OA\Response(response="404", description="Documento não cadastrado",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/ErroResult",
     *             @OA\Examples(example="ErroResult", value={"message": "Documento não cadastrado"},summary="ErroResult")),
     *     ),
     * ),
     * 
     *  @OA\Schema(
     *   schema="ErroResult",
     *   title="ErroResult",
     *  	@OA\Property(
     *  		property="message",
     *  		type="string"
     *  	),
     *  ),
    */
    public function apagarCadastro($cpf){
        $cadastro = Cadastro::where(["cpf" => $cpf])->first();
        if(!$cadastro){
            return response()->json(["message" => "Documento não cadastrado"], Response::HTTP_NOT_FOUND);
        }

        $cadastro->delete();
        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    /**
     * @OA\Get(
     *     tags={"Cadastro"},
     *     summary="Consultar cadastro pelo documento",
     *     description="Consultar cadastro pelo documento",
     *     path="/api/v1/cadastro/{documento}",
     *     @OA\Parameter(
     *         description="Accept",
     *         in="header",
     *         name="Accept",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="applicationJson", value="application/json", summary="application/json"),
     *     ),
     *     @OA\Parameter(
     *         description="CPF",
     *         in="path",
     *         name="documento",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="cpfValido", value="00000000191", summary="Documento"),
     *     ),
     *     @OA\Response(
     *         response="200", 
     *         description="Ok",
     *         @OA\JsonContent(
     *             ref="#/components/schemas/CadastroResult",
     *             @OA\Examples(example="result", value={
     *                                                     "id": 1,
     *                                                     "nomeCompleto": "João Silva",
     *                                                     "dataNascimento": "1920-01-01",
     *                                                     "sexo": "M",
     *                                                     "cep": "05555000",
     *                                                     "cpf": "12345678901",
     *                                                     "uf": "SP",
     *                                                     "cidade": "São Paulo",
     *                                                     "logradouro": "Rua do teste",
     *                                                     "numeroLogradouro": "202 A",
     *                                                     "email": "joao@teste.com",
     *                                                     "created_at": "2000-01-01T12:00:00.000000Z",
     *                                                     "updated_at": "2000-01-01T12:00:00.000000Z"
     *          }, summary="CadastroResult"),
     *         )),
     *     @OA\Response(response="404", description="Documento não cadastrado"),
     * ),
     * 
     * @OA\Schema(
     *  schema="CadastroResult",
     *  title="CadastroResult",
     * 	@OA\Property(
     * 		property="id",
     * 		type="int"
     * 	),
     * 	@OA\Property(
     * 		property="nomeCompleto",
     * 		type="string"
     * 	),
     * 	@OA\Property(
     * 		property="dataNascimento",
     * 		type="date"
     * 	),
     * 	@OA\Property(
     * 		property="sexo",
     * 		type="string"
     * 	),
     * 	@OA\Property(
     * 		property="cep",
     * 		type="string"
     * 	),
     * 	@OA\Property(
     * 		property="cpf",
     * 		type="string"
     * 	),
     * 	@OA\Property(
     * 		property="uf",
     * 		type="string"
     * 	),
     * 	@OA\Property(
     * 		property="cidade",
     * 		type="string"
     * 	),
     * 	@OA\Property(
     * 		property="logradouro",
     * 		type="string"
     * 	),
     * 	@OA\Property(
     * 		property="numeroLogradouro",
     * 		type="string"
     * 	),
     * 	@OA\Property(
     * 		property="email",
     * 		type="string"
     * 	),
     * 	@OA\Property(
     * 		property="created_at",
     * 		type="datetime"
     * 	),
     * 	@OA\Property(
     * 		property="updated_at",
     * 		type="datetime"
     * 	),
     * )
     */
    public function obterCadastro($cpf){
        $cadastro = Cadastro::where(["cpf" => $cpf])->first();
        if(!$cadastro){
            return response()->json(["message" => "Documento não cadastrado"], Response::HTTP_NOT_FOUND);
        }

        return response()->json($cadastro);
    }

     /**
     * @OA\Post(
     *     tags={"Cadastro"},
     *     summary="Salvar Cadastro",
     *     description="Salva cadastro no sistema",
     *     path="/api/v1/cadastro",
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
     *                     "nomeCompleto": "João Silva",
     *                     "dataNascimento": "1920-01-01",
     *                     "sexo": "M",
     *                     "cep": "05555000",
     *                     "cpf": "00000000191",
     *                     "uf": "SP",
     *                     "cidade": "São Paulo",
     *                     "logradouro": "Rua do teste",
     *                     "numeroLogradouro": "202 A",
     *                     "email": "joao@teste.com",
     *                     "expectativa": "Ficar magro"
     *                  }
     *             )
     *         )
     *     ),
     *     @OA\Response(response="201", description="Cadastro realizado com sucesso", @OA\JsonContent(@OA\Examples(example="result", value={"message": "Cadastro realizado com sucesso"},summary="Cadastro realizado com sucesso"))),
     *     @OA\Response(response="400", description="Campo enviado é inválidoo", @OA\JsonContent(@OA\Examples(example="result", value={"message": "Favor informar um e-mail válido"},summary="Campo inválido"))),
     *     @OA\Response(response="412", description="Pré condição é inválida", @OA\JsonContent(@OA\Examples(example="result", value={"message": "Cadastro realizado com sucesso"},summary="Este documento já foi cadastrado"))),
     *     @OA\Response(response="422", description="Entidade não processável", @OA\JsonContent(@OA\Examples(example="result", value={"message": "O campo ""nome completo"" não pode ter menos de 5 caracteres.", "errors": {}}, summary=""))),
     * ),
     * 
     */
    public function salvarCadastro(Request $request)
    {
        $rules = $this->getRegrasSalvarCadastro();
        $customMessages = $this->getMensagensCustomizadas();
        $niceNames = $this->getNomeDeAtributosCustomizadosSalvarCadastro();

        $cadastro = $this->validate($request, $rules, $customMessages, $niceNames);

        if(!filter_var($cadastro['email'], FILTER_VALIDATE_EMAIL)) {
            return response()->json(["message" =>"Favor informar um e-mail válido"], Response::HTTP_BAD_REQUEST);
        }

        $documentoValidado = $this->validarCPF($cadastro['cpf']);
        if(!$documentoValidado->isValido){
            return response()->json(["message" => $documentoValidado->message], $documentoValidado->httpResponse);
        }

        Cadastro::create($cadastro);
        return response()->json(["message" =>"Cadastro realizado com sucesso!"], Response::HTTP_CREATED);
    }

     /**
     * @OA\Put(
     *     tags={"Cadastro"},
     *     summary="Atualizar Cadastro",
     *     description="Atualiza cadastro no sistema",
     *     path="/api/v1/cadastro",
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
     *                     "id": 1,
     *                     "nomeCompleto": "João Silva",
     *                     "dataNascimento": "1920-01-01",
     *                     "sexo": "M",
     *                     "cep": "05555000",
     *                     "cpf": "00000000191",
     *                     "uf": "SP",
     *                     "cidade": "São Paulo",
     *                     "logradouro": "Rua do teste",
     *                     "numeroLogradouro": "202 A",
     *                     "email": "joao@teste.com",
     *                     "expectativa": "Ficar obeso"
     *                  }
     *             )
     *         )
     *     ),
     *     @OA\Response(response="200", description="Cadastro atualizado com sucesso", @OA\JsonContent(@OA\Examples(example="result", value={"message": "Cadastro atualizado com sucesso"},summary="Cadastro atualizado com sucesso"))),
     *     @OA\Response(response="400", description="Campo enviado é inválidoo", @OA\JsonContent(@OA\Examples(example="result", value={"message": "Favor informar um e-mail válido"},summary="Campo inválido"))),
     *     @OA\Response(response="422", description="Entidade não processável", @OA\JsonContent(@OA\Examples(example="result", value={"message": "O campo ""nome completo"" não pode ter menos de 5 caracteres.", "errors": {}}, summary=""))),
     * ),
     * 
     */
    public function atualizarCadastro(Request $request)
    {
        $rules = $this->getRegrasSalvarCadastro();
        $rules['id'] = 'required';
        $customMessages = $this->getMensagensCustomizadas();
        $niceNames = $this->getNomeDeAtributosCustomizadosSalvarCadastro();

        $cadastro = $this->validate($request, $rules, $customMessages, $niceNames);

        if(!filter_var($cadastro['email'], FILTER_VALIDATE_EMAIL)) {
            return response()->json(["message" =>"Favor informar um e-mail válido"], Response::HTTP_BAD_REQUEST);
        }

        $documentoValidado = $this->validarCPF($cadastro['cpf'], false);

        if(!$documentoValidado->isValido){
            return response()->json(["message" => $documentoValidado->message], $documentoValidado->httpResponse);
        }

        $cadastroModel = Cadastro::find($cadastro["id"]);
        if(!$cadastroModel){
            return response()->json(["message" =>"Cadastro informado não localizado. Favor verifique o ID e tente novamente."], Response::HTTP_NOT_FOUND);
        }

        $cadastroModel->update($cadastro);
        return response()->json(["message" =>"Cadastro atualizado com sucesso!"], Response::HTTP_OK);
        
    }

    private function validarCPF($documento, $isValidarDocumentoJaCadastrado = true)
    {
        $cpf = preg_replace( '/[^0-9]/is', '', $documento);
        if (strlen($cpf) != 11) {
            return (object) ["isValido" => false, 
            "message" => "Favor informar 11 dígitos para documentos", 
                "httpResponse" => Response::HTTP_BAD_REQUEST];
        }if (preg_match('/(\d)\1{10}/', $cpf)) {
            return (object) ["isValido" => false, 
            "message" => "Números do documento não podem ser iguais", 
                "httpResponse" => Response::HTTP_BAD_REQUEST];

        }for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return (object) ["isValido" => false, 
                    "message" => "Este documento é inválido", 
                        "httpResponse" => Response::HTTP_BAD_REQUEST];
            }
        }
        
        if($isValidarDocumentoJaCadastrado && Cadastro::where(["cpf" => $cpf])->first() != null ){
            return (object) ["isValido" => false, 
                "message" => "Este documento já foi cadastrado", 
                "httpResponse" => Response::HTTP_PRECONDITION_FAILED];
        }

        return (object) ["isValido" => true, 
                    "message" => "Este documento é válido", 
                        "httpResponse" => Response::HTTP_OK];
    }

    private function getRegrasSalvarCadastro(){
        return [
            'nomeCompleto' => 'required|max:255|min:5',
            'dataNascimento' => 'required|date',
            'sexo' => 'required|in:M,F',
            'cep' => 'required|size:8',
            'cpf' => 'required|size:11',
            'cidade' => 'required',
            'uf' => 'required',
            'logradouro' => 'required|max:255',
            'numeroLogradouro' => 'required|max:20',
            'email' => 'required|max:150',
            'expectativa' => '',
        ];
    }

    private function getMensagensCustomizadas()
    {
        return [
            'required' => 'O campo ":attribute" é requerido.',
            'size' => 'O campo ":attribute" tem que ter :size caracteres.',
            'in' => 'O ":attribute" selecionado não é válido.',
            'max' => 'O campo ":attribute" não pode ter mais de :max caracteres.',
            'min' => 'O campo ":attribute" não pode ter menos de :min caracteres.',
            'date' => 'O campo ":attribute" não tem uma data válida.',
        ];
    }

    private function getNomeDeAtributosCustomizadosSalvarCadastro(){
        return [];
    }

}