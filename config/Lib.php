<?php

/*
|--------------------------------------------------------------------------
| BIBLIOTECA DE APLICAÇÕES
|--------------------------------------------------------------------------
|
| Defina os caminhos das bibliotecas que seu sistema utilizará.
| Elas podem ser carregadas de uma só vez usando o comando: $mob->lib('tipo',['str1','str2'])
| O tipo pode ser 'css' ou 'js'.
| 'str1' e 'str2' representam os valores de cada elemento dentro do arquivo CSS ou JS.
| Este comando deve ser adicionado em app/App.php
|
*/

return [
    'css' => [
        'bs' => 'vendor/twbs/bootstrap/dist/css/bootstrap.min.css',
        'bs-icon' => 'vendor/twbs/bootstrap-icons/font/bootstrap-icons.min.css'
    ],

    'js' => [
        'bs' => 'vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js',
        'jquery' => 'node_modules/jquery/dist/jquery.min.js'
    ]
];