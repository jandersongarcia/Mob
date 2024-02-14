<?php

// Função para adicionar cor ao texto
function colorizar($texto, $codigoCor)
{
    return "\033[{$codigoCor}m{$texto}\033[0m";
}

// Função para exibir uma tabela no prompt
function exibirTabela($comando, $descricao)
{
    echo " " . str_pad($comando, 35) . "| " . str_pad($descricao, 60) . PHP_EOL;
}

$separadorLinha = "---------------------------------------------------------------\n";

$action = @$argv[1];
$component = @$argv[2];
$route = @$argv[3];
$route = ($action == 'remove') ? true : $route;

$actions = ['create', 'remove'];

if (!in_array($action, $actions) || !isset($component) || !isset($route)) {
    echo "\n$separadorLinha";
    echo colorizar("Erro: ", 31) . "Linha de comando não reconhecida ou incompleta\n\n";
    echo "USO: " . colorizar("composer", 33) . " pages " . colorizar("action namePages", 33) . " " . colorizar("action nameRoute", 33) . "\n\n";
    echo " Lista de " . colorizar("actions \n", 33);
    echo $separadorLinha;
    echo exibirTabela('create ' . colorizar("namePages nameRoute", 33), 'Cria um novo módulo');
    echo exibirTabela('remove ' . colorizar("namePages nameRoute", 33), 'Exclui um módulo existente');
    echo "$separadorLinha\n";
    echo colorizar("namePages", 33) . " - o nome da página que será criado\n";
    echo colorizar("nameRoute", 33) . " - o nome da rota para a página\n\n";
    exit;
}

// Verificar se não começa com número
if (preg_match('/^\d/', $component)) {
    $msgErr = "O nome da página não deve começar com um número.\n";
} else {
    // Remover caracteres especiais, exceto underline
    $limpa = preg_replace('/[^a-zA-Z0-9_]/', '', $component);

    // Verificar se a string mudou após a remoção dos caracteres especiais
    if ($component !== $limpa) {
        $msgErr = "A página não deve conter caracteres especiais, exceto underline (_).\n";
    }
}

if (isset($msgErr)) {
    $mensagemErro = colorizar("Erro: ", 31) . $msgErr;
    echo "$separadorLinha $mensagemErro\n";
    exit();
}

$nomeComponent = ucfirst($component);

// Verificar se $nomeComponent está vazio após o tratamento
if (empty($nomeComponent)) {
    $mensagemErro = colorizar("Erro: ", 31) . "Nome da página inválido\n";
    echo "$separadorLinha $mensagemErro";
    exit();
}

if ($action == 'create') {

    $nomePagina = ucfirst($nomeComponent);
    $nomeRota = ($route == '/') ? '/' : $route;

    if (preg_match('/[^a-zA-Z0-9_\-\/]/', $nomeRota)) {
        $msgErr = "O nome da rota não deve conter caracteres especiais, exceto hífen (-) ou underline (_).\n";
    }

    if (isset($msgErr)) {
        $mensagemErro = colorizar("Erro: ", 31) . $msgErr;
        echo "$separadorLinha $mensagemErro\n";
        exit();
    }

    $nomeRota = ($nomeRota == '/') ? '/' : $nomeRota;

    // Adiciona subpasta, se fornecida
    if (isset($subpasta)) {
        //$subpasta = pathinfo($folder, PATHINFO_DIRNAME);
        if (!file_exists("app/pages/$subpasta")) {
            if (!mkdir("app/pages/$subpasta", 0777, true)) {
                echo "Erro ao tentar criar o diretório da subpasta " . colorizar("'$subpasta'", 36) . "\n Operação cancelada.\n\n";
                exit();
            }
        }
    }

    // Verificar se $nomePagina ou $nomeRota está vazio após o tratamento
    if (empty($nomePagina) || empty($nomeRota)) {
        $mensagemErro = colorizar("Erro: ", 31) . "Nome da página e/ou rota inválidos\n";
        echo "$separadorLinha $mensagemErro";
        exit();
    }

    echo $separadorLinha . "Iniciando o processo de criação...\n";

    // Verificar se a página é restrito do sistema
    if ($nomePagina == 'Ctrl') {
        $mensagem = colorizar("Atenção: ", 31) . "A página " . colorizar("'$nomePagina'", 36) . " é restrita para o uso do framework\n Operação cancelada.\n\n";
        echo "$separadorLinha $mensagem";
        exit();
    }

    // Verificar se a página já existe
    if (file_exists("app/pages/$nomePagina")) {
        $mensagem = colorizar("Atenção: ", 31) . "A página " . colorizar("'$nomePagina'", 36) . " já existe em app/pages\n Operação cancelada.\n\n";
        echo "$separadorLinha $mensagem";
        exit();
    }

    echo "Realizando o tratamento do nome da página e rota: " . colorizar("[OK]", 32) . "\n";

    // Tentar criar o diretório da página
    if (mkdir("app/pages/$nomePagina", 0777, true)) {
        echo "Criação do diretório $nomePagina: " . colorizar("[OK]", 32) . "\n";
    } else {
        echo "Erro ao tentar criar o diretório " . colorizar("'$nomePagina'", 36) . "\n Operação cancelada.\n\n";
        exit();
    }

    // Carregar rotas existentes do arquivo JSON
    $caminhoArquivoRotas = 'core/json/routes.json';
    if (file_exists($caminhoArquivoRotas)) {
        $rotasJson = file_get_contents($caminhoArquivoRotas);
        $rotas = json_decode($rotasJson, true);

        // Verificar se a rota já existe
        foreach ($rotas['routes'] as $rota) {
            $caminhoRotaExistente = ($nomeRota == "/") ? "/" : "/$nomeRota";

            if ($rota['path'] === $caminhoRotaExistente) {
                $mensagem = colorizar("Atenção: ", 31) . "A rota " . colorizar("'$caminhoRotaExistente'", 36) . " já existe em routes.json\n Desfazendo ações anterores: " . colorizar("[OK]", 31) . "\n Operação cancelada.\n\n";
                echo "$separadorLinha $mensagem";

                // Verificar se o diretório existe antes de removê-lo
                $caminhoPagina = "app/pages/$nomePagina";
                if (is_dir($caminhoPagina)) {
                    rmdir($caminhoPagina);
                }

                exit();
            }
        }
    } else {
        // Se o arquivo não existir, criar um novo JSON com uma estrutura inicial
        $rotas = ['routes' => []];
    }

    // Adicionar a nova rota ao JSON
    $novaRota = [
        'path' => ($nomeRota == "/") ? "/" : "/$nomeRota",
        'controller' => "$nomePagina",
        'method' => 'ALL'
    ];

    $rotas['routes'][] = $novaRota;

    // Salvar o JSON atualizado de volta ao arquivo
    file_put_contents($caminhoArquivoRotas, json_encode($rotas, JSON_PRETTY_PRINT));

    echo "Adicionando a rota " . colorizar("'/$nomeRota'", 36) . " à lista: " . colorizar("[OK]", 32) . "\n";

    // Criar arquivos dentro da pasta recém-criada

    // Página View
    file_put_contents("app/pages/$nomePagina/{$nomePagina}View.php", "<div class='w-100 vh-100 bg-dark text-light d-flex flex-column justify-content-center align-items-center'>\n    <h1>MobiPHP</h1>\n    <p>Página $nomePagina</p>\n</div>");
    echo "Visualização da página $nomePagina: " . colorizar("[OK]", 32) . "\n";

    // Página Modal
    file_put_contents("app/pages/$nomePagina/{$nomePagina}Modal.php", "<?php\n\nnamespace app\Pages;\n\nclass $nomePagina {\n\n    public " . '$title' . " = '$nomePagina';\n\n    // Declarar os componentes que serão usados na página.\n    public " . '$components' . " = [];\n\n}");
    echo "Modal da página $nomePagina: " . colorizar("[OK]", 32) . "\n";

    $arrayName = strtolower($nomePagina);

    // Página Controller
    file_put_contents("app/pages/$nomePagina/{$nomePagina}Controller.php", "<?php\n\nuse app\Pages\\$nomePagina;\n\n$$arrayName = new $nomePagina();\n\n");
    echo "Controlador da página $nomePagina: " . colorizar("[OK]", 32) . "\n";

    // Folha de estilos CSS
    file_put_contents("app/pages/$nomePagina/$nomePagina.css", "/* Estilos CSS para $nomePagina */");
    echo "Arquivo CSS para $nomePagina: " . colorizar("[OK]", 32) . "\n";

    // JS
    file_put_contents("app/pages/$nomePagina/$nomePagina.js", "// Scripts JavaScript para $nomePagina");
    echo "Arquivo JavaScript para $nomePagina: " . colorizar("[OK]", 32) . "\n\n";

    // Mensagem de conclusão
    echo colorizar("Página '$nomePagina' configurada com sucesso em ", 33) . colorizar("./app/pages\n", 94);
    echo colorizar("A rota ficou registrada como ", 33) . colorizar("'/$nomeRota'\n\n", 94);
    echo "Para listar as rotas da aplicação, use o comando " . colorizar("composer mobi-list-routes\n\n", 33);


}

if ($action == 'remove') {

    $nomePagina = ucfirst($nomeComponent);

    // Verificar se a página existe
    $caminhoDiretorio = "app/pages/$nomePagina";

    if (!file_exists($caminhoDiretorio)) {
        $mensagem = colorizar("Atenção: ", 31) . "A página " . colorizar("'$nomePagina'", 32) . " não existe em app/pages\n Operação cancelada.\n\n";
        echo "$separadorLinha $mensagem";
        exit();
    }

    echo "$separadorLinha\nIniciando o processo de exclusão para a página '$nomePagina'...\n";

    // Excluir arquivos associados
    $caminhosArquivosExcluir = [
        "$caminhoDiretorio/{$nomePagina}Controller.php",
        "$caminhoDiretorio/{$nomePagina}Modal.php",
        "$caminhoDiretorio/$nomePagina.css",
        "$caminhoDiretorio/$nomePagina.js",
        "$caminhoDiretorio/{$nomePagina}View.php"
    ];

    foreach ($caminhosArquivosExcluir as $caminhoArquivo) {
        if (file_exists($caminhoArquivo)) {
            unlink($caminhoArquivo); // Excluir arquivo
        }
    }

    // Excluir o diretório da página
    $arquivosNoDiretorio = glob("$caminhoDiretorio/*");
    if (count($arquivosNoDiretorio) === 0) {
        if (rmdir($caminhoDiretorio)) {
            echo "Exclusão do diretório $nomePagina: " . colorizar("[OK]", 32) . "\n";
        } else {
            $ultimoErro = error_get_last();
            echo colorizar("Erro ao tentar excluir o diretório $nomePagina: {$ultimoErro['message']}", 31) . "\n Operação cancelada.\n\n";
            exit();
        }
    } else {
        echo "Erro: O diretório $nomePagina não está vazio. Por favor, certifique-se de excluir todos os arquivos dentro do diretório antes de tentar excluir a página.\n";
        exit();
    }

    // Carregar rotas existentes do arquivo JSON
    $caminhoArquivoRotas = 'core/json/routes.json';
    if (file_exists($caminhoArquivoRotas)) {
        $rotasJson = file_get_contents($caminhoArquivoRotas);
        $rotas = json_decode($rotasJson, true);

        // Remover a rota correspondente do JSON
        $rotas['routes'] = array_filter($rotas['routes'], function ($rota) use ($nomePagina) {
            return $rota['controller'] !== $nomePagina;
        });

        // Salvar o JSON atualizado de volta ao arquivo
        file_put_contents($caminhoArquivoRotas, json_encode($rotas, JSON_PRETTY_PRINT));

        echo "Rota correspondente removida de routes.json: " . colorizar("[OK]", 32) . "\n";
    }

    echo colorizar("Página", 33) . colorizar(" '$nomePagina' ", 94) . colorizar("excluída com sucesso!\n\n", 33);

}


