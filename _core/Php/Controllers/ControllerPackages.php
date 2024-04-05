<?php
// Inclui a classe Application do namespace Core\MClass
use Core\MClass\Application;

// Cria uma instância da classe Application
$app = new Application;

// Obtém o nome do pacote da segunda parte da URL
$packName = $app->path(2);

// Define uma variável para armazenar mensagens de erro
$error = false;

// Define o caminho para o pacote com base no nome do pacote
$path = ROOT . "\packages\\$packName";

// Verifica se o diretório do pacote existe
if (is_dir($path)) {
    // Se houver uma subpasta especificada
    $sub = $app->path(3);
    if($sub != ""){
        // Adiciona a subpasta ao caminho
        $lang = "$path\\lang";
        $path .= "\\$sub";
        // Verifica se o diretório da subpasta existe
        if(is_dir($path)){
            // Define os caminhos para os arquivos Modal e Controller
            $modal = $path."\\$sub"."Modal.php";
            $controller = $path."\\$sub"."Controller.php";
            $language = $path."\\lang";
            // Verifica se os arquivos Modal e Controller existem
            if(file_exists($modal) && file_exists($controller)){
                // Inclui o arquivo de Idioma
                if(is_dir($lang)){
                    $lang = "$lang\\".APP['language'].".php";
                    require_once($lang);
                }
                // Inclui os arquivos Modal e Controller
                require_once($modal);
                require_once($controller);
            } else {
                // Define a mensagem de erro se o framework MVC não for encontrado
                $error = "MVC framework was not found.";
            }
        } else {
            // Define a mensagem de erro se o caminho do pacote não existir
            $error = "Package path does not exist.";
        }
    } else {
        // Se não houver subpasta especificada, apenas exibe o caminho do pacote
        echo $path;
    }
} else {
    // Define a mensagem de erro se o caminho do pacote não existir
    $error = "Package path does not exist.";
}

// Se houver um erro, retorna a mensagem de erro como JSON
if($error){
    echo json_encode([
        "type" => "error",
        "message" => $error
    ],JSON_UNESCAPED_UNICODE);
}
?>
