<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $erro['title'] ?></title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Estilos personalizados -->
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            width: 100%;
            height: 100vh;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .jumbotron {
            background-color: #e9ecef;
            border-radius: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="jumbotron">
            <h1 class="display-4 text-danger"><i class="bi bi-bug-fill"></i> <?= $erro['title'] ?></h1>
            <hr class="my-4">
            <p class="fs-6 text-dark"><?= $erro['message'] . " <strong>" . $value . "</strong>" ?></p>
        </div>
    </div>
    
</body>
</html>