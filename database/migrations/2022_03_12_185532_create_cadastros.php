<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::create('cadastros', function (Blueprint $table) {
            $table->id();
			$table->string('nomeCompleto',150);
            $table->date('dataNascimento');
            $table->enum('sexo', ['M','F','O']);
            $table->string('cep',8);
            $table->string('cpf',11);
            $table->string('uf',2);
            $table->string('cidade',150);
            $table->string('logradouro',150);
            $table->string('numeroLogradouro',20);
            $table->string('email',150);
            $table->text('expectativa')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cadastros');
    }
};
