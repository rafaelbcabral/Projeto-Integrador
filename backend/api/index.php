<?php
// require_once './vendor/autoload.php';

require_once 'vendor/autoload.php';
require_once 'src/database/conexao.php';
require_once 'src/mesa/rotas-mesa.php';
require_once 'src/reserva/rotas-reserva.php';
require_once 'src/funcionario/rotas-funcionario.php';

use phputil\router\HttpRequest;
use phputil\router\HttpResponse;
use phputil\router\Router;

use function phputil\cors\cors; // Step 1: Declare the namespace usage for the function.

$app = new Router();
$pdo = conectar();

$app->use(cors());
// $app->use(cors(['origin' => 'http://127.0.0.1:5500']));

$app->get('/', function (HttpRequest $req, HttpResponse $res) {
    $res->send('Bem vindo a api de reservas');
});

criarRotasReserva($app, $pdo);
criarRotasMesa($app, $pdo);
criarRotasFuncionario($app, $pdo);

$app->listen();
