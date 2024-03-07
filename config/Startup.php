<?php
    /*
    |--------------------------------------------------------------------------
    | Startup
    |--------------------------------------------------------------------------
    |
    | Esta página é carregada antes da app.php. Seus scripts não afetam as páginas
    | da controladora ctrl
    | 
    |
    */

    /* VERIFICA SE O USUÁRIO ESTÁ CONECTADO */
    if (empty($_SESSION['stateUser']) && basename($_SERVER['REQUEST_URI']) != "login") {
        #header("Location: /login");
        #exit;
    }