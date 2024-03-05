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

// Carrega lista de pacotes disponíveis
$path = "core/Json/ListPackages.json";

if (!file_exists($path)) {
    echo "\n$separadorLinha";
    echo colorizar("Erro: ", 31) . "Lista de pacotes não encontrada.\n\n";
    echo colorizar("RECOMENDAÇÃO: ", 33) . " realize o update do Mob através do " . colorizar("composer update", 33) . "\n\n";
    exit;
}

// Verifica se o pacote existe
$packages = json_decode(file_get_contents($path), true);

$action = @$argv[1];
$package = @$argv[2];

$actions = ['install', 'remove', 'update', 'enabled', 'disabled', 'list', 'status'];

if ($action == 'list') {
    echo $separadorLinha;
    echo "Lista de pacotes disponíveis\n\n";
    echo $separadorLinha;

    foreach ($packages as $packageName => $packageInfo) {
        $name = colorizar($packageName, 33);
        $description = $packageInfo['description'];
        echo exibirTabela($name, $description);
    }

    echo $separadorLinha;
    exit;
}

if (!in_array($action, $actions)) {
    echo "\n$separadorLinha";
    echo colorizar("Erro: ", 31) . "Linha de comando não reconhecida ou incompleta\n\n";
    echo "USO: " . colorizar("composer", 33) . " maestro " . colorizar("action packages", 33) . "\n\n";
    echo " Lista de " . colorizar("actions \n", 33);
    echo $separadorLinha;
    echo exibirTabela('install ' . colorizar("package-name", 33), 'Instala o pacote no projeto');
    echo exibirTabela('remove ' . colorizar("package-name", 33), 'Exclui um pacote do projeto');
    echo exibirTabela('update ' . colorizar("package-name", 33), 'Atualiza um pacote no projeto');
    echo exibirTabela('enabled ' . colorizar("package-name", 33), 'Ativa um pacote no projeto');
    echo exibirTabela('disabled ' . colorizar("package-name", 33), 'Desativa um pacote no projeto');
    echo exibirTabela('list ' . colorizar("", 33), 'Lista todos os pacotes disponíveis');
    echo "$separadorLinha\n";
    echo colorizar("package-name", 33) . " - o nome do pacote que será instalado\n\n";
    exit;
}

function camelize($input)
{
    $words = explode('-', $input);
    $capitalizedWords = array_map('ucfirst', $words);
    return implode('', $capitalizedWords);
}

function limparPasta($caminho)
{
    $arquivosPermitidos = ['css', 'php', 'js', 'MPack.json'];

    if ($handle = opendir($caminho)) {
        while (false !== ($arquivo = readdir($handle))) {
            $caminhoArquivo = $caminho . DIRECTORY_SEPARATOR . $arquivo;

            if ($arquivo != "." && $arquivo != "..") {
                $extensao = pathinfo($caminhoArquivo, PATHINFO_EXTENSION);

                if (!in_array($arquivo, $arquivosPermitidos) && !in_array($extensao, $arquivosPermitidos)) {
                    // Excluir o arquivo se não estiver na lista de arquivos permitidos
                    unlink($caminhoArquivo);
                }
            }
        }
        closedir($handle);
    }
}

function atualizarPackagesJson($destinationDir)
{
    // Carrega JSON de origem
    $jsonRead = json_decode(file_get_contents("{$destinationDir}\MPack.json"), true);

    // JSON gravação
    $jsonSave = json_decode(file_get_contents(ROOT.'/core/Json/Packages.json'), true);

    $type = @$jsonRead['type'];

    if (isset($jsonSave['packages'][$type])) {
        $jsonSave['packages'][$type]['name'] = $jsonRead['package-name'];
        $jsonSave['packages'][$type]['enabled'] = true;
        $jsonSave['packages'][$type]['files']['js'] = $jsonRead['files']['js'];
        $jsonSave['packages'][$type]['files']['css'] = $jsonRead['files']['css'];
        $jsonSave['packages'][$type]['files']['php'] = $jsonRead['files']['php'];

        // Salva as alterações no arquivo 'Packages.json'
        file_put_contents(ROOT.'/core/Json/Packages.json', json_encode($jsonSave, JSON_PRETTY_PRINT));
    }

}

function apagarPacote($caminho)
{
    if (!is_dir($caminho)) {
        // Se o caminho não for um diretório, retornar false
        return false;
    }

    $itens = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($caminho, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );

    foreach ($itens as $item) {
        if ($item->isDir()) {
            rmdir($item->getRealPath());
        } else {
            unlink($item->getRealPath());
        }
    }

    rmdir($caminho);

    return true;
}

if ($action == 'install') {
    if (isset($packages[$package])) {
        $gitUrl = $packages[$package]['url'];
        echo "Baixando pacote " . colorizar(" {$gitUrl}", 32);

        $e = explode("/", $gitUrl);
        $dirName = $e[1];
        $dirNameNew = camelize($dirName);

        // Comando para o Composer baixar o pacote com uma versão específica
        shell_exec("composer require {$gitUrl}");

        // Verificar se o diretório de destino existe, se não, criar
        $destinationDir = "packages\\";
        if (!is_dir($destinationDir)) {
            mkdir($destinationDir, 0755, true);
        }

        echo "Instalando pacotes no diretório " . colorizar("\packages", 36).": " . colorizar("[OK]", 32) . "\n";

        // Mover pacote - ajustado para o ambiente Windows
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            shell_exec("move vendor\\janderson.anjos\\{$dirName} {$destinationDir}");
            shell_exec("ren {$destinationDir}{$dirName} {$dirNameNew}");
        } else {
            shell_exec("mv vendor/janderson.anjos/{$dirName} {$destinationDir}");
            shell_exec("mv {$destinationDir}{$dirName} {$destinationDir}{$dirNameNew}");
        }

        echo "Removendo arquivos desnecessários de " . colorizar("{$destinationDir}{$dirNameNew}", 36).": " . colorizar("[OK]", 32) . "\n";

        limparPasta("{$destinationDir}{$dirNameNew}");

        // remove pacote
        shell_exec("composer remove {$gitUrl}");

        echo "Codificando pacote no MOB: " . colorizar("[OK]", 32) . "\n";
        atualizarPackagesJson("{$destinationDir}{$dirNameNew}");

        echo colorizar("Pacote instalado com sucesso", 32) . "\n";
        exit;
    }
}

if ($action == 'remove') {
    $jsonSave = json_decode(file_get_contents("core/Json/Packages.json"), true);

    if (isset($packages[$package])) {
        $category = $packages[$package]['category'];
        if($category == 'preloader'){
            // Ajuste para acessar os elementos corretamente
            $jsonSave['packages']['preloader']['name'] = '';
            $jsonSave['packages']['preloader']['enabled'] = null;
            $jsonSave['packages']['preloader']['files']['css'] = '';
            $jsonSave['packages']['preloader']['files']['php'] = '';
            $jsonSave['packages']['preloader']['files']['js'] = '';

            file_put_contents(ROOT.'/core/Json/Packages.json', json_encode($jsonSave, JSON_PRETTY_PRINT));

            apagarPacote("packages/{$package}");

            echo colorizar("Desinstalação do pacote concluída.", 32) . "\n";
            exit;
        }
    }

    echo "\n$separadorLinha";
    echo colorizar("Erro: ", 31) . "Falha ao tentar desinstalar pacote '{$package}'\n\n";
}


