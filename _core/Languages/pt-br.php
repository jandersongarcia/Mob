<?php

namespace Core\Languages;

class Language{

    public $err3000 = [
        "title" => "Error: 3000",
        "message" => "Os componentes precisam ser declarados dentro de um ['array'].<br>Exemplo: <span class='font-monospace'>\$mob->component(['nome_componente'])</span>"
    ];

    public $err3001 = [
        "title" => "Error: 3001",
        "message" => 'Não foi possível localizar todos os ítens do componente'
    ];

    public $err3002 = [
        "title" => "Error: 3002",
        "message" => "Arquivo de configuração do banco de dados ('config/DataBase.php') não foi encontrado."
    ];

    public $err3003 = [
        "title" => "Error: 3003",
        "message" => "O Arquivo de configuração da aplicação ('config/App.php') não foi encontrado."
    ];

    public $err3004 = [
        "title" => "Error: 3004",
        "message" => "Arquivo de configuração do envio de e-mail ('config/PhpMailer.php') não foi encontrado."
    ];

    public $err3005 = [
        "title" => "Error: 3005",
        "message" => "O ítem 'timezone' não foi encontrado ou definido em 'config/App.php'."
    ];

    public $err3006 = [
        "title" => "Error: 3006",
        "message" => "O arquivo de idioma não encontrado no diretório 'languages'."
    ];

    public $err3007 = [
        "title" => "Error: 3007",
        "message" => "O Idioma não definido na configuração da aplicação."
    ];

    public $err3008 = [
        "title" => "Error: 3008",
        "message" => "Não foi possível conectar ao banco de dados.<br>"
    ];

    public $err3009 = [
        "title" => "Error: 3009",
        "message" => "Problema ao tentar carregar o MC do Components"
    ];

    public $err3010 = [
        "title" => "Error: 3010",
        "message" => "O tipo de banco de dados declarado em <strong class='text-danger'>app_data_type</strong> no arquivo <strong class='text-danger'>/config/database.php</strong> está incorreto. <br>Certifique-se de preencher esta variável com <strong class='text-danger'>'mysql'</strong> para MySQL ou <strong class='text-danger'>'pgsql'</strong> para usar o Banco de dados PostgreSQL."
    ];

}