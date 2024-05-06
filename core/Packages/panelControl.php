<?php

namespace core\Packages\PanelControl;

use Core\MClass\Application;
use Database\MySQL\MySQL;

// Password: Admin!123@

class PanelControl extends MySQL
{

    private $conn;

    public $app;

    public function __construct()
    {
        $conn = CONN;
        $this->app = new Application;

        if (isset($conn['driver'])) {

        } else {
            $error = "Configure the 'DataBase.php' file inside the 'config' directory.";
        }

        if (isset($error)) {
            $erro = [
                'title' => 'Error',
                'message' => $error
            ];
            $value = "<br/>Error executing mobControl";
            if (file_exists('templates/Error/MobError.php')) {
                require_once('templates/Error/MobError.php');
            } else {
                echo $error['message'];
            }
            exit;
        }
    }

    public function controller($page = null)
    {
        $params = $this->uri();

        $p = is_null($page) ? @$params['p'] : $page;

        $array = $this->app->trataVariavel($p);

        $this->MVC($array);

    }

    private function path($n = 1)
    {
        $caminho = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $slicePath = explode('/', trim($caminho, '/'));

        // Verifica se $slicePath[$n] existe antes de retornar o valor
        return isset($slicePath[$n]) ? $slicePath[$n] : '';
    }

    private function uri()
    {
        // URL com a string de consulta
        $url = @$_SERVER['HTTP_REFERER'];
        // Obtém apenas a string de consulta
        $queryString = parse_url($url, PHP_URL_QUERY);

        // Inicializa um array para armazenar os parâmetros
        $params = [];

        // Converte a string de consulta em um array associativo
        parse_str($queryString, $params);

        // Exibe o array resultante
        return $params;
    }

    private function MVC($name = false)
    {
        // PACOTE DE IDIOMAS
        if (isset(APP['language'])) {
            $lang = APP['language'];
            $langFilePath = ROOT . "/packages/MobControl/lang/$lang.php";
            // Verifica se existe um arquivo de idioma para carregar
            if (file_exists($langFilePath)) {
                require_once($langFilePath);
            }
        }

        $file = ROOT . "/packages/MobControl/$name/$name";

        // Verifica se a string 'pagesjs' não está presente na URI
        if (strpos($_SERVER['REQUEST_URI'], 'pagesjs') !== false) {
            return; // Não faz nada se a string 'pagesjs' estiver presente na URI
        }

        $cssFilePath = "$file.css";
        // Carrega o arquivo CSS, se existir
        if (file_exists($cssFilePath)) {
            $conteudoCSS = file_get_contents($cssFilePath);
            echo "<style>$conteudoCSS</style>";
        }

        $modalFilePath = $file . "Modal.php";
        $controllerFilePath = $file . "Controller.php";
        $viewFilePath = $file . "View.php";

        // Verifica se os arquivos Modal, Controller e View existem
        if (file_exists($modalFilePath) && file_exists($controllerFilePath) && file_exists($viewFilePath)) {
            // Inclui os arquivos Modal, Controller e View
            require_once($modalFilePath);
            require_once($controllerFilePath);
            require_once($viewFilePath);
        } else {
            // Exibe uma mensagem de erro caso a estrutura do MVC esteja incorreta
            echo '<div class="container vh-100 d-flex justify-content-center align-items-center">
                <div class="alert alert-danger text-center" role="alert">
                  <h4 class="alert-heading"><i class="bi bi-bug-fill"></i> Erro!</h4>
                  <p>Erro na estrutura do MVC para <span class="font-monospace bg-danger p-1 rounded text-white">' . $name . '</span><br>Verifique se o nome do pacote está correto ou se os arquivos MVC existem.</p>
                </div>
              </div>';
        }
    }


}
