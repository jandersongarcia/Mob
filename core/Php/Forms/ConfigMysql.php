<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>Configuração do Banco de Dados</title>
    <!-- Inclusão do CSS do Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2>Configuração de Conexão MySQL - Local</h2>
        <form action="/submit-local" method="post" class="mb-3">
            <div class="mb-3">
                <label for="driver-local" class="form-label">Driver:</label>
                <input type="text" id="driver-local" name="driver" value="mysql" class="form-control">
            </div>
            <div class="mb-3">
                <label for="host-local" class="form-label">Host:</label>
                <input type="text" id="host-local" name="host" value="localhost" class="form-control">
            </div>
            <div class="mb-3">
                <label for="port-local" class="form-label">Porta:</label>
                <input type="text" id="port-local" name="port" value="3306" class="form-control">
            </div>
            <div class="mb-3">
                <label for="database-local" class="form-label">Banco de Dados:</label>
                <input type="text" id="database-local" name="database" value="eldorado" class="form-control">
            </div>
            <div class="mb-3">
                <label for="username-local" class="form-label">Usuário:</label>
                <input type="text" id="username-local" name="username" value="root" class="form-control">
            </div>
            <div class="mb-3">
                <label for="password-local" class="form-label">Senha:</label>
                <input type="password" id="password-local" name="password" class="form-control">
            </div>
            <div class="mb-3">
                <label for="charset-local" class="form-label">Charset:</label>
                <input type="text" id="charset-local" name="charset" value="utf8mb4" class="form-control">
            </div>
            <div class="mb-3">
                <label for="collation-local" class="form-label">Collation:</label>
                <input type="text" id="collation-local" name="collation" value="utf8mb4_unicode_ci"
                    class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Salvar Configuração Local</button>
        </form>

        <h2>Configuração de Conexão MySQL - Web</h2>
        <form action="/submit-web" method="post">
            <div class="mb-3">
                <label for="driver-web" class="form-label">Driver:</label>
                <input type="text" id="driver-web" name="driver" value="mysql" class="form-control">
            </div>
            <div class="mb-3">
                <label for="host-web" class="form-label">Host:</label>
                <input type="text" id="host-web" name="host" value="localhost" class="form-control">
            </div>
            <div class="mb-3">
                <label for="port-web" class="form-label">Porta:</label>
                <input type="text" id="port-web" name="port" value="3306" class="form-control">
            </div>
            <div class="mb-3">
                <label for="database-web" class="form-label">Banco de Dados:</label>
                <input type="text" id="database-web" name="database" value="u956227714_iris" class="form-control">
            </div>
            <div class="mb-3">
                <label for="username-web" class="form-label">Usuário:</label>
                <input type="text" id="username-web" name="username" value="u956227714_user" class="form-control">
            </div>
            <div class="mb-3">
                <label for="password-web" class="form-label">Senha:</label>
                <input type="password" id="password-web" name="password" value="6%PSk?$0qXf2" class="form-control">
            </div>
            <div class="mb-3">
                <label for="charset-web" class="form-label">Charset:</label>
                <input type="text" id="charset-web" name="charset" value="utf8mb4" class="form-control">
            </div>
            <div class="mb-3">
                <label for="collation-web" class="form-label">Collation:</label>
                <input type="text" id="collation-web" name="collation" value="utf8mb4_unicode_ci" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Salvar Configuração Web</button>
        </form>
    </div>
</body>

</html>