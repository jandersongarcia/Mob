<?php

namespace php;

class Api
{

    public function verify($client, $token)
    {
        $file = "core\\Php\\Api\\apiKey.php";

        if (file_exists($file)) {
            $config = include $file;
            $secret = @$config['secret'];
            return $this->verifyKey($token, $client, $secret);
        }
    }

    public function module()
    {
        // Verifica se a URI contém '/api/'
        if (strpos($_SERVER['REQUEST_URI'], '/api/') !== false) {

            // Divide a URI para obter o caminho após '/api/'
            $parts = explode('api/', $_SERVER['REQUEST_URI']);

            // O caminho após '/api/' estará na segunda parte do array
            $module = isset($parts[1]) ? $parts[1] : '';

            // Diretório base para os módulos
            $modulePath = "app/Modules/";

            // Caminho completo para o módulo
            $moduleFile = $modulePath . $module . '/' . $module;

            // Verifica se o módulo existe
            if (file_exists($moduleFile . "Modal.php")) {
                require_once($moduleFile . "Modal.php");

                // Verifica se o controlador existe
                if (file_exists($moduleFile . "Controller.php")) {
                    require_once($moduleFile . "Controller.php");
                } else {
                    return json_encode(['error' => 'Controller não encontrado no módulo'], JSON_UNESCAPED_UNICODE);
                }
            } else {
                return json_encode(['error' => 'Modal não encontrado no módulo'], JSON_UNESCAPED_UNICODE);
            }
        } else {
            // URI não contém '/api/'
            return json_encode(['error' => 'Esta não é uma chamada de API'], JSON_UNESCAPED_UNICODE);
        }
    }

    private function verifyKey($key, $client, $secret)
    {
        // Concatenar client e secret com um delimitador (pode ser qualquer string)
        $combinedString = $client . ':' . $secret;

        // Gerar um código hash HMAC usando SHA256
        $hash = hash_hmac('sha256', $combinedString, $secret);

        // Formatando a chave
        $expectedKey = sprintf(
            '%s-%s-%s-%s-%s',
            substr($hash, 0, 8),
            substr($hash, 8, 4),
            substr($hash, 12, 4),
            substr($hash, 16, 4),
            substr($hash, 20, 12)
        );

        // Verifica se a chave gerada é igual à esperada
        return($key === $expectedKey);
    }



}