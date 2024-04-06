<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configurações do servidor SMTP
    |--------------------------------------------------------------------------
    |
    | Aqui, você especifica as configurações necessárias para o servidor SMTP,
    | essenciais para o envio de e-mails através da sua aplicação. 
    |
    */
    'host' => '', // Substitua pelo endereço do seu servidor SMTP
    'port' => 465, // Escolha entre 587 ou 465 para uma conexão segura usando SSL
    'username' => '', // Substitua pelo seu endereço de e-mail
    'password' => '', // Substitua pela sua senha
    'smtp_secure' => 'ssl', // Escolha entre 'tls' ou 'ssl' para uma conexão segura

    /*
    |--------------------------------------------------------------------------
    | Configurações do remetente
    |--------------------------------------------------------------------------
    |
    | Nesta seção, são definidas as configurações relacionadas ao remetente,
    | importantes para o envio de e-mails pela aplicação. 
    |
    */
    'from_email' => '', // Endereço de e-mail padrão do remetente
    'from_name' => '', // Nome padrão do remetente

    /*
    |--------------------------------------------------------------------------
    | Configurações padrão do e-mail
    |--------------------------------------------------------------------------
    |
    | Aqui, você define as configurações padrão para o corpo do e-mail, essenciais
    | para o envio de e-mails pela aplicação. 
    |
    */
    'is_html' => true, // Indica se o e-mail terá conteúdo HTML
    'charset' => 'utf-8', // Conjunto de caracteres do e-mail
];
