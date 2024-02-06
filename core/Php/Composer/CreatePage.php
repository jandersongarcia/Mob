<?php

// Função para adicionar cor ao texto
function colorizar($texto, $codigoCor)
{
    return "\033[{$codigoCor}m{$texto}\033[0m";
}

$separadorLinha = "------------------------------------------------\n";

// Verificar se o número correto de argumentos foi fornecido
if ($argc !== 3 || empty($argv[1]) || empty($argv[2])) {
    $mensagemAtencao = colorizar("Atenção: ", 31) . "Forneça o nome da página e o nome da rota\n";
    $uso = "Uso correto: composer mobi-create-page " . colorizar("nome-da-pagina", 36) . " " . colorizar("nome-da-rota", 36) . "\n\n";

    echo "$separadorLinha $mensagemAtencao $uso";
    exit();
}

// Obter o nome da página e o nome da rota a partir dos argumentos da linha de comando
$entrada = trim($argv[1]);
// Verifica se a entrada fica em algum diretório
$divisor = explode("/",$entrada);

if (count($divisor) > 1) {
    $subpasta = $divisor[0];
    $entrada = $divisor[1];
}

// Verificar se não começa com número
if (preg_match('/^\d/', $entrada)) {
    $msgErr = "O nome da página não pode começar com um número.\n";
} else {
    if (preg_match('/[^a-zA-Z0-9_]/', $entrada)) {
        $msgErr = "O nome da página não deve conter caracteres especiais, exceto underline (_).\n";
    }
}

$nomePagina = ucfirst($entrada);
$nomeRota = ($argv[2] == '/') ? '/' : $argv[2];

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
if(isset($subpasta)){
    //$subpasta = pathinfo($folder, PATHINFO_DIRNAME);
    if (!file_exists("app/pages/$subpasta")) {
        if (!mkdir("app/pages/$subpasta", 0777, true)) {
            echo "Erro ao tentar criar o diretório da subpasta " . colorizar("'$subpasta'", 36) . "\n Operação cancelada.\n\n";
            exit();
        }
    }
}


exit;
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
            $mensagem = colorizar("Atenção: ", 31) . "A rota ". colorizar("'$caminhoRotaExistente'",36) ." já existe em routes.json\n Desfazendo ações anterores: " . colorizar("[OK]", 31) . "\n Operação cancelada.\n\n";
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
file_put_contents("app/pages/$nomePagina/$nomePagina.view.php", "<div class='w-100 vh-100 bg-dark text-light d-flex flex-column justify-content-center align-items-center'>\n    <h1>MobiPHP</h1>\n    <p>Página $nomePagina</p>\n</div>");
echo "Visualização da página $nomePagina: " . colorizar("[OK]", 32) . "\n";

// Página Controller
file_put_contents("app/pages/$nomePagina/$nomePagina.controller.php", "<?php\n\n // Controlador para Pg$nomePagina \n\nclass $nomePagina {\n\n    public ".'$title'." = '$nomePagina';\n\n    // Declarar os componentes que serão usados na página.\n    public ".'$components'." = [];\n\n}");
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
