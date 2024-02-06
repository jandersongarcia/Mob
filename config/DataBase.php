<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Tipo de Banco de Dados da Aplicação
    |--------------------------------------------------------------------------
    |
    | Defina qual banco de dados sua aplicação utilizará: MySQL ou PostgreSQL.
    | Deixe esta array vazia se a aplicação não utilizará banco de dados.
    | mysql -> Banco de dados Mysql
    ! pgsql -> Banco de dados PostgreSQL
    |
    */

    'app_data_type' => 'mysql',

    /*
    |--------------------------------------------------------------------------
    | Configurações de Conexão para MySQL
    |--------------------------------------------------------------------------
    |
    | Se você optou por usar o MySQL, preencha as configurações abaixo.
    |
    | 'driver'    => O motor do banco de dados, neste caso, MySQL.
    | 'host'      => O endereço do servidor MySQL.
    | 'port'      => A porta usada para a conexão MySQL.
    | 'database'  => O nome do banco de dados a ser usado.
    | 'username'  => Seu nome de usuário para autenticação no MySQL.
    | 'password'  => Sua senha para autenticação no MySQL.
    | 'charset'   => O conjunto de caracteres usado na comunicação com o MySQL.
    | 'collation' => A configuração de colação usada pelo MySQL.
    |
    */    
    'mysql' => [
        'driver' => 'mysql',
        'host' => 'localhost',
        'port' => '3306',
        'database' => 'mobidb',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
    ],

    /*
    |--------------------------------------------------------------------------
    | Configurações de Conexão para PostgreSQL
    |--------------------------------------------------------------------------
    |
    | Se você preferir usar o PostgreSQL, preencha as configurações abaixo.
    |
    | 'driver'   => O motor do banco de dados, neste caso, PostgreSQL.
    | 'host'     => O endereço do servidor PostgreSQL.
    | 'port'     => A porta usada para a conexão PostgreSQL.
    | 'database' => O nome do banco de dados a ser usado.
    | 'username' => Seu nome de usuário para autenticação no PostgreSQL.
    | 'password' => Sua senha para autenticação no PostgreSQL.
    | 'charset'  => O conjunto de caracteres usado na comunicação com o PostgreSQL.
    | 'schema'   => O nome do esquema no PostgreSQL.
    |
    */    
    'pgsql' => [
        'driver' => 'pgsql',
        'host' => 'localhost',
        'port' => '5432',
        'database' => '',
        'username' => '',
        'password' => '',
        'charset' => 'utf8',
        'schema' => 'public',
    ],

];
