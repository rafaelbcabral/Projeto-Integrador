<?php

require_once 'vendor/autoload.php';
require_once 'src/conexao/conexao.php';
require_once 'src/mesa/rotas-mesa.php';
require_once 'src/reserva/rotas-reserva.php';
require_once 'src/funcionario/rotas-funcionario.php';
require_once 'src/categoria/rotas-categoria.php';
require_once 'src/item/rotas-item.php';
require_once 'src/consumo/rotas-consumo.php';
require_once 'src/pagamento/rotas-pagamento.php';

use phputil\router\Router;
use function phputil\cors\cors;
use phputil\router\HttpRequest;
use phputil\router\HttpResponse;

$app = new Router();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE, HEAD, PATCH");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");



if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$app->use(cors());

$pdo = conectar();

$app->get('/', function (HttpRequest $req, HttpResponse $res) {
    $res->send('Bem-vindo à API de reservas');
});

$servico = new ServicoFuncionario($pdo);

$funcionarioController = new ControladoraFuncionario($servico);

// $app->get('/login', function (HttpRequest $req, HttpResponse $res) use ($funcionarioController) {
//     $funcionarioController->login($req, $res);
// });

$app->post('/login', function (HttpRequest $req, HttpResponse $res) use ($pdo) {
    session_name('sid');
    session_start();

    $body = $req->body();
    $username = $body->usuario ?? '';
    $password = $body->senha ?? '';

    if (empty($username) || empty($password)) {
        return $res->status(400)->json(['error' => 'Username and password are required.']);
    }

    try {
        $query = $pdo->prepare('SELECT * FROM funcionario WHERE usuario = :usuario');
        $query->bindValue(':usuario', $username);
        $query->execute();

        $user = $query->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $storedSalt = $user['salt'];
            $storedHash = $user['senha'];

            $computedHash = hash(
                'sha512',
                'zadciumabdjsjf' . $password . $storedSalt . '4932oewrifdjsép9723o er4421258 hjaagdtdw    '
            );

            if ($computedHash === $storedHash) {
                $_SESSION['id'] = $user['id'];
                $_SESSION['usuario'] = $user['usuario'];

                return $res->json([
                    'message' => 'Login successful',
                    'user_id' => $user['id']
                ]);
            } else {
                return $res->status(401)->json(['error' => 'Invalid credentials']);
            }
        } else {
            return $res->status(401)->json(['error' => 'Invalid credentials']);
        }
    } catch (PDOException $e) {
        return $res->status(500)->json(['error' => 'Internal server error.']);
    }
});

$app->post('/logout', function (HttpRequest $req, HttpResponse $res) {
    session_name('sid');
    session_start();
    session_unset();
    session_destroy();
    $res->json(['message' => 'Logout successful']);
});


criarRotasCategoria($app, $pdo);
criarRotasItem($app, $pdo);
criarRotasReserva($app, $pdo);
criarRotasMesa($app, $pdo);
criarRotasFuncionario($app, $pdo);
criarRotasConsumo($app, $pdo);
criarRotasPagamento($app, $pdo);

$app->listen();