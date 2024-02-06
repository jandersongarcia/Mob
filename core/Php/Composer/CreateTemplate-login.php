<?php

// Função para adicionar cor ao texto
function colorizar($texto, $codigoCor)
{
    return "\033[{$codigoCor}m{$texto}\033[0m";
}

$separadorLinha = "------------------------------------------------\n";

print_r($argc);

// Verificar se o número correto de argumentos foi fornecido
if ($argc !== 2 || empty($argv[1])) {
    $mensagemAtencao = colorizar("Atenção: ", 31) . "Forneça o nome da página que será criado o tema\n";
    $uso = "Uso correto: composer mobi-create-theme-page " . colorizar("nome-da-pagina", 36) . "\n\n";

    echo "$separadorLinha $mensagemAtencao $uso";
    exit();
}

// Obter o nome da página e o nome da rota a partir dos argumentos da linha de comando
$nomePagina = ucfirst(strtolower(preg_replace('/[^a-zA-Z0-9-_]/', '', $argv[1])));

// Verificar se $nomePagina após o tratamento
if (empty($nomePagina)) {
    $mensagemErro = colorizar("Erro: ", 31) . "Nome da página inválida\n";
    echo "$separadorLinha $mensagemErro";
    exit();
}

echo $separadorLinha . "Iniciando o processo de criação do template...\n";

// Verificar se a página é restrito do sistema
if ($nomePagina == 'Ctrl') {
    $mensagem = colorizar("Atenção: ", 31) . "A página " . colorizar("'$nomePagina'", 36) . " é restrita para o uso do framework\n Operação cancelada.\n\n";
    echo "$separadorLinha $mensagem";
    exit();
}

// Verificar se a página já existe
if (!file_exists("app/pages/$nomePagina")) {
    $mensagem = colorizar("Atenção: ", 31) . "A página " . colorizar("'$nomePagina'", 36) . " deve existir para aplicar o template\n Operação cancelada.\n\n";
    echo "$separadorLinha $mensagem";
    exit();
}

$filePath = "app/pages/$nomePagina/$nomePagina.view.php";
// Abrir o arquivo existente, limpar seu conteúdo e adicionar o HTML fornecido
$fileContent = file_get_contents($filePath);
$fileContent = ''; // Limpar conteúdo

$htmlContent = <<<HTML
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Login</h4>
                </div>
                <div class="card-body">
                    <form>
                        <div class="mb-3">
                            <label for="usuario" class="form-label">Usuário:</label>
                            <input type="text" class="form-control" id="usuario" name="usuario" required>
                        </div>
                        <div class="mb-3">
                            <label for="senha" class="form-label">Senha:</label>
                            <input type="password" class="form-control" id="senha" name="senha" required>
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Iniciar Sessão</button>
                        </div>
                        <div class="mb-3">
                            <a href="#" class="text-decoration-none">Esqueceu a conta?</a>
                        </div>
                        <div>
                            <p class="mb-0">Não tem uma conta? <a href="#" class="text-decoration-none">Criar Nova Conta</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
HTML;

file_put_contents($filePath, $htmlContent);

echo colorizar("Página '$nomePagina' configurada com sucesso em ", 33) . colorizar("./app/pages\n\n", 94);
