<?php

namespace app\Pages;

class TesteAdmin {

    public $title = 'TesteAdmin';

    // Declarar os componentes que serão usados na página.
    public $components = ['PreloaderMB','Test'];

    public function timeNow(){
        return date("H:m d/i/Y");
    }

}