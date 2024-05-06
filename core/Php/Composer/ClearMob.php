<?php

// Função para adicionar cor ao texto
function colorizar($texto, $codigoCor)
{
    return "\033[{$codigoCor}m{$texto}\033[0m";
}

$separadorLinha = "\n------------------------------------------------\n";

echo $separadorLinha. "Iniciando limpeza das configurações do Mob\n";

// Caminho do arquivo a ser modificado
$arquivo = 'core/Php/Api/ApiKey.php';

// Substituir as informações de client e secret
$conteudoNovo = "<?php\n\nreturn [\n    'client' => null,\n    'secret' => null\n];\n";

if (file_put_contents($arquivo, $conteudoNovo)) {
    echo "- Limpeza das chaves de API. " . colorizar("[OK]", "32"); // 32 é o código para verde
} else {
    echo "- Limpeza das chaves de API. " . colorizar("[ERROR]", "31"); // 31 é o código para vermelho
}

// Processo finalizado
echo colorizar("\nProcesso finalizado", "32") . $separadorLinha;

