<!DOCTYPE html>
<html lang="pt-br">

<head>
    <link href="https://cdn.datatables.net/v/dt/dt-1.13.8/datatables.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    $mob->loadMob('css');
    $mob->lib('css', ['bs', 'bs-icon']);
    ?>
    <title>
        <?= $app->title(); ?>
    </title>
</head>

<body>

    <div id="app"></div>

    <?php
    $mob->lib('js', ['bs', 'jquery']);
    $mob->loadMob('js');
    ?>

</body>

</html>