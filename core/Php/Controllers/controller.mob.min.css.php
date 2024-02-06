<?php

require 'vendor/autoload.php'; // Certifique-se de carregar o autoload do Composer

use MatthiasMullie\Minify;


$minifier = new Minify\JS();

// Caminho do arquivo JavaScript original
$minifier->add('./public/css/styleRoot.css');

// Minificar e imprima diretamente na saída
echo $minifier->minify();



