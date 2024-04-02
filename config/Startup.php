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

// Página de redirecionamento
$page = "access";

// Ativar segurança de sessions
$security = false;

if ($security) {

    $uri = $_SERVER['REQUEST_URI'];
    $palavra = 'access';

    // Verificar se a palavra 'access' existe na string $uri
    if (strpos($uri, $palavra) !== false && isset($_SESSION['STATE_USER'])) {
        unset($_SESSION['STATE_USER']);
    }

    // Verifica se o estado do usuário não está definido na sessão
    if (empty($_SESSION['STATE_USER'])) {
        // Verifica se a página atual não está dentro do diretório 'access'
        if (strpos($uri, "/$page/") !== 0) {
            // Verifica se a URL não termina com uma barra
            if (substr($uri, -1) !== '/') {
                header("Location: " . $uri . "/");
                exit;
            } else {
                header("Location: /$page/");
                exit;
            }
        }
    }
}



