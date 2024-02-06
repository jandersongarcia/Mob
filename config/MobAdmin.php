<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Permitir que os usuários criem suas próprias contas?
    |--------------------------------------------------------------------------
    |
    | Ao definir este campo como true, você permitirá que qualquer pessoa crie
    | uma conta em sua aplicação.
    |
    */
    'registration_for_new_users' => true, // true ou false

    /*
    |--------------------------------------------------------------------------
    | Ativar confirmação por e-mail
    |--------------------------------------------------------------------------
    |
    | Se este campo for configurado como true, todo novo usuário receberá um
    | link de confirmação em seu e-mail para ativar o acesso à página.
    |
    */
    'enable_email_confirmation' => false, // true ou false

    /*
    |--------------------------------------------------------------------------
    | Restrição de domínio para novos cadastros
    |--------------------------------------------------------------------------
    |
    | Se você deseja restringir os cadastros a um conjunto específico de
    | domínios de e-mail, insira os domínios desejados no array abaixo.
    | Exemplo: Para permitir apenas cadastros com e-mails dos domínios
    | 'meuprojet.com' e 'projetoraiz.com', configure o campo da seguinte forma:
    | allowed_email_domains = ['meuprojet.com', 'projetoraiz.com']
    |
    */
    'allowed_email_domains' => ['codext.com.br']
];
