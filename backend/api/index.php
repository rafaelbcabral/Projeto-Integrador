<?php
require_once 'vendor/autoload.php';
require_once 'src/database/conexao.php';
// require_once 'src/routes/reserva-routes.php';
require_once 'src/reserva/routes/rotas-reserva.php';

use \phputil\router\router;

// Criando a instância do Router
$app = new Router();

// Conectar ao banco de dados
$pdo = conectar();
defineRoutes($app, $pdo);

$app->listen();
