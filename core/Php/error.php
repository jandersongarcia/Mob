<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Erro</title>
    <link rel="stylesheet" href="/vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/vendor/twbs/bootstrap-icons/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .error-container {
            width: 90%;
            max-width: 960px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
    </style>
</head>
<body>

<div class="container error-container">
    <h1 class="text-danger display-1"><i class="bi bi-bug"></i></h1>
    <h2 class="text-danger">Erro Encontrado!</h2>
    <p class="lead text-center"><?= $error; ?></p>
</div>

<!-- Inclui Bootstrap JS e Popper.js (para funcionalidades do Bootstrap) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
