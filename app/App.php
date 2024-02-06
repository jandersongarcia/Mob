<!DOCTYPE html>
<html lang="pt-br">

<head>
    <link href="https://cdn.datatables.net/v/dt/dt-1.13.8/datatables.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    $mob->loadBootstrap(['css', 'icon']);
    $mob->loadMobcss();
    ?>
    <title>
        <?= $app->title(); ?>
    </title>
</head>

<body>
    <div id="app"></div>
    <?php
    $mob->loadBootstrap(['js']);
    $mob->loadMobjs();
    $mob->loadJquery();
    ?>

 <script src="https://cdn.datatables.net/v/dt/dt-1.13.8/datatables.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</body>

</html>