<?php
// require_once './vendor/autoload.php';

require_once 'vendor/autoload.php';
require_once './database/conexao.php';
require_once './mesa/mesa-controller.php';
require_once './mesa/rotas-mesa.php';
require_once './reserva/rotas-reserva.php';
require_once './funcionario/rotas-funcionario.php';

use phputil\router\Router;

use function phputil\cors\cors; // Step 1: Declare the namespace usage for the function.

$app = new Router();
$pdo = conectar();

$app->use(cors());
// $app->use(cors(['origin' => 'http://127.0.0.1:5500']));

$app->get('/', function ($req, $res) {
    $res->send('Bem vindo a api de reservas');
});

// $app->get('/funcionario', function ($req, $res) {
//     // Definir o conteúdo JSON diretamente
//     $funcionario = [
//         'id' => 1,
//         'nome' => 'João da Silva',
//         'cargo' => 'Desenvolvedor',
//         'email' => 'joao.silva@empresa.com',
//         'data_admissao' => '2020-03-15'
//     ];

//     $funcionario2 = [
//         'id' => 2,
//         'nome' => 'João da Silva',
//         'cargo' => 'Desenvolvedor',
//         'email' => 'joao.silva@empresa.com',
//         'data_admissao' => '2020-03-15'
//     ];

//     $res->json([$funcionario, $funcionario2]);
// });

criarRotasReserva($app, $pdo);
criarRotasMesa($app, $pdo);
criarRotasFuncionario($app, $pdo);

$app->listen();
