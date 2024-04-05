<?php

$file = 'packages/MobLoader/MobLoader.css';

if (file_exists($file)) {
    $lines = file($file);

    foreach ($lines as $line) {
        $firstChar = trim($line[0]);
        $lastChar = substr(trim($line), -1);
        $txt = '';

        if (in_array($firstChar, ['[', '.', '#']) || ctype_alpha($firstChar)) {
            $txt = '#CLASSE ';
            $nLine = rtrim(str_replace(",", ", #CLASSE", $line), ',');
        } else {
            $nLine = $line;
        }

        echo "$txt $nLine";
    }
} else {
    echo 'O arquivo não existe.';
}
