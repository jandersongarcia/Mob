# MOB: Modular Object Builder

<p align="center">
  <img src="https://raw.githubusercontent.com/jandersongarcia/Mob/main/core/Assets/Images/mob.png" alt="MOB">
</p>

O MOB é um framework em PHP com JavaScript, conhecido como MOB: Modular Object Builder. Projetado para simplificar a rápida construção de aplicações web completas ou atuar como backend, o MOB destaca-se pela abordagem modular do seu construtor de objetos.

Esta documentação fornece informações cruciais para começar o desenvolvimento com o MOB, focando especialmente no Modular Object Builder. Com essa abordagem modular na construção de objetos, o MOB proporciona uma flexibilidade organizada, tornando o desenvolvimento mais eficiente e estruturado.

## Sumário

- [MOB: Modular Object Builder](#mob-modular-object-builder)
  - [Sumário](#sumário)
  - [Pré-requisitos de Ambiente](#pré-requisitos-de-ambiente)
  - [Estrutura de Pastas](#estrutura-de-pastas)
  - [Dependências e Bibliotecas](#dependências-e-bibliotecas)
  - [Instalação](#instalação)
  - [Criação de Páginas](#criação-de-páginas)
    - [Estrutura da Página Criada](#estrutura-da-página-criada)
  - [Trabalhando com Rotas](#trabalhando-com-rotas)
    - [Criação de subrotas](#criação-de-subrotas)
    - [Listar Rotas](#listar-rotas)
    - [Renomear rota](#renomear-rota)
  - [Criação de Componentes](#criação-de-componentes)
    - [Estrutura do Componente Criado](#estrutura-do-componente-criado)
  - [Criação de Módulos](#criação-de-módulos)
    - [Estrutura do Módulo Criado](#estrutura-do-módulo-criado)
  - [Módulo de CRUD](#módulo-de-crud)
    - [Configuração do Banco de Dados](#configuração-do-banco-de-dados)
  - [Exemplo de Uso](#exemplo-de-uso)
    - [Exemplo de Uso com Consulta SQL Personalizada](#exemplo-de-uso-com-consulta-sql-personalizada)
    - [Consultas SQL Mais Complexas](#consultas-sql-mais-complexas)
  - [Métodos Disponíveis](#métodos-disponíveis)
    - [`insert($table, $data)`](#inserttable-data)
    - [`getAll($table)`](#getalltable)
    - [`getById($table, $primaryKey, $id)`](#getbyidtable-primarykey-id)
    - [`update($table, $data, $id)`](#updatetable-data-id)
    - [`delete($table, $id)`](#deletetable-id)
    - [`query($sql, $params)`](#querysql-params)
  - [Envio de E-mails](#envio-de-e-mails)
    - [Envio de e-mail com template](#envio-de-e-mail-com-template)
  - [Requisições com JavaScript](#requisições-com-javascript)
  - [Contribuição](#contribuição)
  - [Licença](#licença)

## Pré-requisitos de Ambiente

Antes de iniciar o desenvolvimento com o MOB, é crucial garantir que sua máquina atenda aos seguintes pré-requisitos de ambiente, listados em ordem de importância:

1. **PHP**: O MOB é baseado em PHP; portanto, certifique-se de ter o PHP instalado em sua máquina. Recomendamos a versão 7.2 ou superior. Você pode baixar o PHP em [php.net](https://www.php.net/).

   ```bash
   # Verifique se o PHP está instalado
   php --version
   ```

2. **Composer**: O Composer é uma ferramenta essencial para gerenciar as dependências do MOB. Certifique-se de ter o Composer instalado em sua máquina. Você pode baixar o Composer em [getcomposer.org](https://getcomposer.org/).

   ```bash
   # Verifique se o Composer está instalado
   composer --version
   ```

3. **Node.js e npm**: O MOB utiliza dependências JavaScript gerenciadas pelo Node.js e npm. Certifique-se de ter o Node.js e o npm instalados em sua máquina. Você pode baixar o Node.js em [nodejs.org](https://nodejs.org/).

   ```bash
   # Verifique se o Node.js está instalado
   node --version

   # Verifique se o npm está instalado
   npm --version
   ```

4. **Banco de Dados (MySQL ou PostgreSQL)**: Se você estiver utilizando um banco de dados, certifique-se de ter o MySQL ou o PostgreSQL instalado e configurado corretamente em sua máquina. Faça o download do MySQL em [mysql.com](https://www.mysql.com/) ou do PostgreSQL em [postgresql.org](https://www.postgresql.org/).

5. **Configuração do Banco de Dados**: Após a instalação do banco de dados, certifique-se de configurar corretamente as informações de conexão no arquivo `config/database.php`.

Esses pré-requisitos são essenciais para garantir um ambiente de desenvolvimento adequado e suave ao trabalhar com o MOB. Certifique-se de seguir essas etapas na ordem apresentada para uma configuração eficiente.

## Estrutura de Pastas

- **app**: Contém os componentes principais da aplicação.
  - **components**: Armazena os componentes reutilizáveis.
  - **modules**: Contém os módulos para interação com o backend.
  - **pages**: Guarda as páginas da aplicação.
  - **app.php**: Arquivo principal de configuração.

- **config**: Configurações da aplicação.
  - **app.php**: Configurações gerais.
  - **database.php**: Configurações de conexão com o banco de dados.
  - **prestart.php**: Arquivo de pré-inicialização.

- **core**: Núcleo do framework.
  - **class**: Classes principais do framework.
    - **application.php**: Classe de aplicação principal.
    - **mobi.php**: Classe principal do MOB.
    - **root.php**: Classe de raiz.
    - **routes.php**: Classe para o tratamento de rotas.
  - **database**: Classes de conexão com bancos de dados.
    - **mysql.php**: Classe para conexão com MySQL.
    - **pgsql.php**: Classe para conexão com PostgreSQL.
  - **js**: Arquivos JavaScript.
    - **routes.mobi.js**: Controlador JavaScript principal.
    - **request.mobi.js**: Tratamento de requisições via GET e POST.
  - **json**: Arquivos JSON.
    - **routes.json**: Definição de rotas em formato JSON.
  - **php**: Scripts PHP.
    - **composer**: Scripts para criação e exclusão de componentes e páginas.
    - **controller**: Scripts para gerar arquivos CSS e JavaScript.
    - **pages**: Páginas específicas.
    - **start.php**: Arquivo de inicialização.

- **languages**: Traduções da aplicação.
  - **pt-br.php**: Tradução para o português brasileiro.

- **node_modules**: Dependências Node.js.

- **public**: Recursos públicos acessíveis pelo navegador.
  - **assets**: Ícones e imagens.
  - **css**: Estilos da aplicação.
    - **styleRoot.css**: Estilo principal.
  - **error**: Páginas de erro.
    - **403.php**: Página de erro 403 (Acesso Negado).
    - **404.php**: Página de erro 404 (Não Encontrado).
  - **js**: Scripts JavaScript.
    - **styleRoot.js**: Script JavaScript principal.

- **templates**: Templates utilizados pelo sistema.
  - **email**: Temas de e-mails.
  - **erros**: Temas das mensagens de erro.

- **var**: Armazena variáveis temporárias.
  - **logs**: Registra informações importantes sobre o funcionamento da aplicação.
  - **temp**: Guarda arquivos temporários utilizados durante a execução do projeto.

- **vendor**: Dependências PHP.

- **.htaccess**: Configurações do Apache.

- **composer.json**, **composer.lock**: Configurações e bloqueio de versões para o Composer.

- **index.php**: Ponto de entrada da aplicação.

- **package-lock.json**, **package.json**: Configurações do Node.js.

- **robots.txt**: Arquivo de exclusão de robôs.

## Dependências e Bibliotecas

O MOB faz uso das seguintes bibliotecas e dependências, algumas incorporadas diretamente:

- **matthiasmullie**: Biblioteca PHP para manipulação de arquivos e diretórios. [matthiasmullie/github](https://github.com/matthiasmullie).

- **bootstrap**: Framework front-end para design responsivo, opcionalmente incorporado no MOB. [twbs/bootstrap](https://github.com/twbs/bootstrap).

- **bootstrap-icons**: Conjunto de ícones para uso com Bootstrap. [twbs/bootstrap-icons](https://github.com/twbs/bootstrap-icons).



- **navigo.js**: Biblioteca para roteamento no lado do cliente, simplificando a construção de Single Page Applications. [krasimir/navigo](https://github.com/krasimir/navigo).

- **jquery**: Biblioteca JavaScript para manipulação do DOM, opcionalmente incorporada no MOB. [jquery/jquery](https://github.com/jquery/jquery)

- **PHPMailer**: Biblioteca PHP para envio de e-mails. [phpmailer/phpmailer](https://github.com/phpmailer/phpmailer)

Ao utilizar o MOB, você tem a flexibilidade de incorporar o Bootstrap e o jQuery ou substituí-los por outras bibliotecas.

Certifique-se de revisar a documentação oficial de cada biblioteca para obter informações detalhadas sobre sua utilização e configuração.

## Instalação

- Antes de criar o projeto, certifique-se de que sua máquina local tenha **PHP** e **Composer** instalados.
- Depois de instalados, você pode criar um novo projeto através do comando _create-project_ do Composer:

```bash
composer create-project --stability=dev jandersongarcia/mob nome-do-projeto
```

## Criação de Páginas

O MOB simplifica a criação de páginas automaticamente através do Composer.

```bash
composer mob-create-page nome-da-pagina nome-da-rota
```

- Isso criará a pasta da página e configurará a rota em core/json/routes.json.
- A estrutura da pasta criada é a seguinte:

### Estrutura da Página Criada
- **app**
    - **pages**
      - **Novapagina**: pasta da página
        - **Novapagina.controller.php**: scripts de controle da página
        - **Novapagina.css**: folha de estilo CSS
        - **Novapagina.js**: arquivo JavaScript da página
        - **Novapagina.view.php**: página de visualização

- Para **excluir uma página**, utilize o comando.

```bash
composer mob-remove-page nome-da-pagina
```

## Trabalhando com Rotas

- Para utilizar as rotas, basta colocar o caminho dentro de um link.
- Supondo que temos as rotas 'product' e 'product/form' para chamar em links diferentes, ficaria assim:

```html
  <a href="/product" >Listar Produtos</a>
  <a href="/product/form" >Cadastrar Produto</a>
```

### Criação de subrotas

- No caso de subrotas, podemos informar o caminho na criação da página.
- **Por exemplo**: Se precisar de uma subrota _empresa/cadastro_, o comando seria

```bash
composer mob-create-page nome-da-pagina empresa/cadastro
```

### Listar Rotas

- Caso precise listar as rotas da sua aplicação, poderá utilizar o comando **composer mob-list-routes** ou acessá-las diretamente no arquivo json que fica em _core/json/routes.json_.

```bash
composer list-routes
```

### Renomear rota

- Para alterar o nome de uma rota, use o comando **composer mob-rename-route rota-atual nova-rota**
- Por exemplo: Supondo que precise alterar a rota _product_ para _register_, o comando ficaria o seguinte: **composer mob-rename-route product register**

```bash
composer rename-route rota-antiga nova-rota
```

## Criação de Componentes

A utilização de componentes oferece uma maneira simples e eficiente de reutilizar código.
- Utilize o comando:

```bash
composer mob-create-component nome-do-componente
```

- Isso criará o componente automaticamente dentro da pasta **components**.

### Estrutura do Componente Criado
- **app**
    - **components**
      - **Novocomponente**: pasta do componente
        - **Novocomponente.controller.php**: scripts de controle do componente
        - **Novocomponente.css**: folha de estilo CSS
        - **Novocomponente.js**: arquivo JavaScript do componente
        - **Novocomponente.view.php**: página de visualização do componente

- Para excluir um componente, podemos usar o comando remove.
```bash
composer mob-remove-component nome-do-componente
```

## Criação de Módulos

- Os módulos são úteis para interação com o backend via requisição.
- Os arquivos do módulo serão criados dentro do diretório modules.
- Utilize o comando:

```bash
composer mob-create-module nome-do-módulo
```

### Estrutura do Módulo Criado
- **app**
  - **modules**
    - **Novomodulo**: pasta do módulo
      - **Novomodulo.controller.php**: scripts de controle
      - **Novomodulo.modal.php**: página de modal

## Módulo de CRUD

O CRUD (Create, Read, Update, Delete) do MOB facilita a manipulação de dados em um banco de dados MySQL ou PostgreSQL. Este fornece métodos para realizar estas operações de forma eficiente, eliminando a necessidade de escrever consultas SQL manualmente.

### Configuração do Banco de Dados

Antes de utilizar o módulo de CRUD, é necessário configurar as informações do banco de dados no arquivo `database.php` dentro da pasta `config`. Certifique-se de fornecer as informações corretas de acordo com o banco de dados que você está utilizando (MySQL ou PostgreSQL). *O PostgreSQL atualmente está inoperante Inoperante*.

Para a seção mysql, preencha os campos "local" com os dados da sua conexão local e "web" com os dados do banco de dados do seu servidor web. Dessa maneira, a configuração precisa ser realizada apenas uma vez, proporcionando uma experiência mais eficiente.

```php
// Exemplo de configuração para MySQL
'app_data_type' => 'mysql',
'mysql' => [
  'local' => [
      'driver' => 'mysql',
      'host' => 'localhost',
      'port' => '3306',
      'database' => '',
      'username' => '',
      'password' => '',
      'charset' => 'utf8mb4',
      'collation' => 'utf8mb4_unicode_ci'
  ],
  'web' => [
      'driver' => 'mysql',
      'host' => 'localhost',
      'port' => '3306',
      'database' => '',
      'username' => '',
      'password' => '',
      'charset' => 'utf8mb4',
      'collation' => 'utf8mb4_unicode_ci'
  ]
],

// Exemplo de configuração para PostgreSQL
'app_data_type' => 'pgsql',
'pgsql' => [
    'driver' => 'pgsql',
    'host' => 'localhost',
    'port' => '5432',
    'database' => '',
    'username' => '',
    'password' => '',
    'charset' => 'utf8',
    'schema' => 'public',
],
```

## Exemplo de Uso

A seguir, apresentamos um exemplo didático de como utilizar o módulo de CRUD em um ambiente MySQL. O mesmo princípio se aplica ao PostgreSQL, ajustando apenas a configuração do banco de dados.

```php
<?php
use Sql\MySQL;

class ExemploCRUD extends MySQL
{
    // Métodos CRUD podem ser implementados aqui
}

// Exemplo de uso do CRUD MySQL
$crud = new ExemploCRUD();

// Inserir um novo registro
$dataToInsert = ['campo1' => 'valor1', 'campo2' => 'valor2'];
$resultInsert = $crud->insert('nome_tabela', $dataToInsert);
echo $resultInsert;

// Obter todos os registros
$resultSelectAll = $crud->getAll('nome_tabela');
echo $resultSelectAll;

// Obter um registro por ID
$resultSelectById = $crud->getById('nome_tabela', 'id', 1);
echo $resultSelectById;

// Atualizar um registro
$dataToUpdate = ['campo1' => 'novo_valor1', 'campo2' => 'novo_valor2'];
$resultUpdate = $crud->update('nome_tabela', $dataToUpdate, 1);
echo $resultUpdate;

// Excluir um registro
$resultDelete = $crud->delete('nome_tabela', 1);
echo $resultDelete;
```

Certifique-se de substituir `'nome_tabela'`, `'campo1'`, `'campo2'`, etc., com os valores correspondentes ao seu banco de dados.

### Exemplo de Uso com Consulta SQL Personalizada

Para realizar uma consulta SQL personalizada simples, você pode utilizar o método `query` do módulo de CRUD do MOB. Vamos exemplificar a execução de uma consulta SELECT básica.

```php
<?php

// Consulta SQL simples
$sqlQuerySimples = "SELECT * FROM tabela_exemplo WHERE coluna_condicao = ?";
$queryParamsSimples = ['valor_condicao'];

$resultSimples = $crud->query($sqlQuerySimples, $queryParamsSimples);

// Exibir os resultados da consulta SQL simples
echo $resultSimples;
```

Este exemplo executa uma consulta SQL simples utilizando um WHERE com um parâmetro de condição.

### Consultas SQL Mais Complexas

Para consultas mais complexas que envolvem INNER JOIN, ORDER BY e GROUP BY, você pode construir de acordo com suas necessidades. A seguir, apresentamos um exemplo que combina esses elementos.

```php
<?php

// Consulta SQL complexa com INNER JOIN, ORDER BY e GROUP BY
$sqlQueryComplexa = "SELECT usuarios.nome AS nome_usuario, COUNT(pedidos.id) AS total_pedidos
                     FROM usuarios
                     INNER JOIN pedidos ON usuarios.id = pedidos.id_usuario
                     WHERE usuarios.cidade = ?
                     GROUP BY usuarios.nome
                     ORDER BY total_pedidos DESC";

$queryParamsComplexa = ['Sao Paulo'];

$resultComplexa = $crud->query($sqlQueryComplexa, $queryParamsComplexa);

// Exibir os resultados da consulta SQL complexa
echo $resultComplexa;
```

Neste exemplo:

- Realizamos um INNER JOIN entre as tabelas `usuarios` e `pedidos`.
- Utilizamos um WHERE para filtrar por uma condição específica (cidade dos usuários).
- Aplicamos um GROUP BY para contar o total de pedidos por usuário.
- Utilizamos ORDER BY para ordenar os resultados pelo total de pedidos em ordem decrescente.

Todas as respostas desses métodos são fornecidas em formato JSON para facilitar a manipulação dos dados por outras linguagens, como o JavaScript.

## Métodos Disponíveis

A seguir, estão os métodos disponíveis no módulo de CRUD:

### `insert($table, $data)`

Insere dados em uma tabela e retorna um JSON indicando sucesso ou falha na inserção.

### `getAll($table)`

Obtém todos os registros de uma tabela e retorna um JSON.

### `getById($table, $primaryKey, $id)`

Obtém um registro por ID de uma tabela e retorna um JSON.

### `update($table, $data, $id)`

Atualiza um registro em uma tabela e retorna um JSON indicando sucesso ou falha na atualização.

### `delete($table, $id)`

Exclui um registro de uma tabela e retorna um JSON indicando sucesso ou falha na exclusão.

### `query($sql, $params)`

Executa uma consulta SQL personalizada e retorna os resultados em JSON.

Lembre-se de adaptar os exemplos conforme necessário para atender aos requisitos específicos da sua aplicação. Este é apenas um guia inicial para o uso do módulo de CRUD no MOB. Para obter informações detalhadas sobre outros métodos ou personalizações avançadas, consulte a documentação oficial do MOB.

## Envio de E-mails

Para enviar e-mails, primeiro, preencha o arquivo *PhpMailer.php* localizado na pasta *Config*. Se estiver testando em um servidor local, verifique se a configuração está correta. Uma vez configurado, execute o seguinte comando para confirmar o envio:

```cmd
composer mob-test-mail email@teste.com.br
```

Substitua **email@teste.com.br** pelo endereço de e-mail para receber a mensagem de teste.

### Envio de e-mail com template



## Requisições com JavaScript

Para simplificar o processo de envio de solicitações via POST ou GET em JavaScript, recomendo a utilização da biblioteca Mobi-Request. Essa pequena biblioteca já está pré-instalada por padrão no MOB. [Documentação][https://github.com/jandersongarcia/mobiRequest]

## Contribuição

Contribuições são bem-vindas! Sinta-se à vontade para abrir issues ou enviar pull requests para melhorar o MOB PHP.

## Licença

Este projeto é licenciado sob a [Licença MIT](LICENSE).
