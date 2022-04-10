<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cadastro extends Model
{
    protected $fillable = [
        'nomeCompleto','dataNascimento','sexo','cep','cpf','cidade',
        'uf','logradouro','numeroLogradouro', 'email', "expectativa"
    ];
}