<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estados', function (Blueprint $table) {
            $table->string('uf',2)->unique();
			$table->string('nome');
        });

        $this->addEstados();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('estados');
    }

    private function addEstados(){
        $estados = [['nome' => 'Acre', 'uf' => 'AC'],
        ['nome' => 'Alagoas', 'uf' => 'AL'],
        ['nome' => 'Amazonas', 'uf' => 'AM'],
        ['nome' => 'Amapá', 'uf' => 'AP'],
        ['nome' => 'Bahia', 'uf' => 'BA'],
        ['nome' => 'Ceará', 'uf' => 'CE'],
        ['nome' => 'Distrito Federal', 'uf' => 'DF'],
        ['nome' => 'Espírito Santo', 'uf' => 'ES'],
        ['nome' => 'Goiás', 'uf' => 'GO'],
        ['nome' => 'Maranhão', 'uf' => 'MA'],
        ['nome' => 'Minas Gerais', 'uf' => 'MG'],
        ['nome' => 'Mato Grosso do Sul', 'uf' => 'MS'],
        ['nome' => 'Mato Grosso', 'uf' => 'MT'],
        ['nome' => 'Pará', 'uf' => 'PA'],
        ['nome' => 'Paraíba', 'uf' => 'PB'],
        ['nome' => 'Pernambuco', 'uf' => 'PE'],
        ['nome' => 'Piauí', 'uf' => 'PI'],
        ['nome' => 'Paraná', 'uf' => 'PR'],
        ['nome' => 'Rio de Janeiro', 'uf' => 'RJ'],
        ['nome' => 'Rio Grande do Norte', 'uf' => 'RN'],
        ['nome' => 'Rondônia', 'uf' => 'RO'],
        ['nome' => 'Roraima', 'uf' => 'RR'],
        ['nome' => 'Rio Grande do Sul', 'uf' => 'RS'],
        ['nome' => 'Santa Catarina', 'uf' => 'SC'],
        ['nome' => 'Sergipe', 'uf' => 'SE'],
        ['nome' => 'São Paulo', 'uf' => 'SP'],
        ['nome' => 'Tocantins', 'uf' => 'TO']];

        foreach ($estados as $estado) {
           $this->addEstado($estado['uf'], $estado['nome']);
        }
    }

    private function addEstado($uf, $nome){
        DB::table('estados')->insert([
            'uf' => $uf,
            'nome' => $nome
        ]);
    }
};
