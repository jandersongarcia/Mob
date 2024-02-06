<?php
// Função para adicionar cor ao texto
function colorizar($texto, $codigoCor)
{
    return "\033[{$codigoCor}m{$texto}\033[0m";
}

function listRoutes()
{
    $routesJson = file_get_contents('core/json/routes.json');
    $routesData = json_decode($routesJson, true);

    if ($routesData && isset($routesData['routes']) && is_array($routesData['routes'])) {
        // Cabeçalhos da tabela
        echo "\n";
        echo str_pad(colorizar("Rota", 33), 29) . " | " . str_pad(colorizar("Caminho (app/pages)",33), 20) . "\n";
        echo str_repeat("-", 42) . "\n";

        foreach ($routesData['routes'] as $route) {
            $path = $route['path'];
            $controller = $route['controller'];

            // Extrair o nome da pasta do controlador
            $folderName = explode('_', $controller)[0];

            // Adicionar linha à tabela
            echo str_pad($path, 20) . " | " . str_pad($folderName, 20) . "\n";
        }
        echo "\n";
    } else {
        echo "Erro ao ler o arquivo de rotas.\n";
    }
}

// Verifica se o script está sendo executado a partir da linha de comando
if (php_sapi_name() === 'cli') {
    listRoutes();
} else {
    echo "Este script deve ser executado a partir da linha de comando usando 'composer mobi-list-routes'.\n";
    exit(1);
}
