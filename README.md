## Configurando o projeto:

Necessário ter:
PHP 8: https://www.php.net/downloads.php  
Composer: https://getcomposer.org/download  

Com o PHP 8 e composer configurados faça o clone do projeto

```git clone https://github.com/professordaniloalves/2022-atividade2-api/```

Entre na pasta que o projeto foi clonado

```cd 2022-atividade2-api```

Copie o arquivo ".env-example" e salve como ".env"

```cp .env.example .env```

Configure o banco de dados. No arquivo ".env", tem um exemplo de conexão com [sqllite](https://www.sqlite.org/index.html). Crie na sua máquina um arquivo do tipo ".sqlite" e referencie-o no ".env" no parâmetro "DB_DATABASE".
Ex.:  

```
DB_CONNECTION=sqlite
DB_DATABASE=C:\\database\\saudeapp.sqlite
```

Com a base configurada, instale o projeto:

```php composer install```

Crie as tabelas:

```php artisan migrate```

Atualize a documentação (swagger):
```php artisan l5-swagger:generate```

Suba o servidor da aplicação:
```php artisan serve```

Para saber como usar, consulte a documentação: http://127.0.0.1:8000/api/documentation
