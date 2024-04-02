<?php

return [

    /*
    |--------------------------------------------------------------------------
    | URL Base da Aplicação
    |--------------------------------------------------------------------------
    |
    | Aqui você define a URL base da sua aplicação. Essa URL é utilizada para
    | gerar links absolutos, redirecionamentos e notificações.
    |
    */

    'app_url' => 'http://mob.test/',

    'support_mail' => 'webmaster@codext.com.br',

    /*
    |--------------------------------------------------------------------------
    | Modo da Aplicação
    |--------------------------------------------------------------------------
    |
    | Define o modo da aplicação. 
    | 0 = Modo de Testes; 1 = Modo de Produção.
    |
    */

    'mode' => 0,

    /* 
    |--------------------------------------------------------------------------
    | Compressar HTML
    |--------------------------------------------------------------------------
    |
    | Ativo ou desativa a compressão dos scripts;
    |
    */
    'minify' => true,

    /*
    |--------------------------------------------------------------------------
    | Nome da Aplicação
    |--------------------------------------------------------------------------
    |
    | Este é o nome da sua aplicação. Será utilizado em notificações ou
    | em outros contextos onde o nome da aplicação é necessário.
    |
    */

    'app_name' => 'MOB',

    /*
    |--------------------------------------------------------------------------
    | Nome da Empresa
    |--------------------------------------------------------------------------
    |
    | Este é o nome da sua empresa. Será utilizado em notificações ou
    | em outros contextos onde o nome da empresa é necessário.
    |
    */

    'app_company' => 'MOB',

    /*
    |--------------------------------------------------------------------------
    | Fuso Horário da Aplicação
    |--------------------------------------------------------------------------
    |
    | Especifique o fuso horário padrão para sua aplicação, utilizado
    | pelas funções de data e hora do PHP.
    |
    */

    'timezone' => 'America/Sao_Paulo',

    /*
    |--------------------------------------------------------------------------
    | Configuração de Localidade da Aplicação
    |--------------------------------------------------------------------------
    |
    | Determine a localidade padrão utilizada pelo provedor de tradução. O nome
    | definido nesta array deverá conter dentro da pasta languages por exemplo:
    | pt-br.php.
    |
    */

    'locale' => 'pt-br',

    /*
    |--------------------------------------------------------------------------
    | Configuração de Idioma da Aplicação
    |--------------------------------------------------------------------------
    |
    | Define o idioma padrão da aplicação. Certifique-se de que o nome do arquivo
    | no diretório 'languages' segue o formato correto, por exemplo: 'en.php', 
    | com a classe e namespace "language" definidos.
    |
    */

    'language' => 'pt-br',

    /*
    |--------------------------------------------------------------------------
    | Modelo de formatação da tag <title>
    |--------------------------------------------------------------------------
    |
    | Configure como a tag <title> será formatada usando palavras-chave.
    | app_name => Nome do Projeto.
    | page_name => Nome da Página.
    |
    */
    'title_style' => 'app_name - page_name',

    /*
    |--------------------------------------------------------------------------
    | Logo da Aplicação
    |--------------------------------------------------------------------------
    |
    | Informe o caminho padrão da logo da sua aplicação
    |
    */
    'application_logo' => '/public/assets/images/logo-mobi.png',
    
];
