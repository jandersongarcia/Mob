
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php 
        $mobi->loadBootstrap(['css','icon']);
        $mobi->loadMobicss();
    ?>
    <title><?= $app->title(); ?></title>
</head>
<body>
    <div id="app" class="bg-light"></div>
    <?php
        $mobi->loadBootstrap(['js']);
        $mobi->loadMobijs();
    ?>
</body>
</html>