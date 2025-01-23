<?php
// require_once './vendor/autoload.php';

require_once 'vendor/autoload.php';

require_once 'src/conexao/conexao.php';
require_once 'src/mesa/rotas-mesa.php';
require_once 'src/reserva/rotas-reserva.php';
require_once 'src/funcionario/rotas-funcionario.php';
require_once 'src/categoria/rotas-categoria.php';
require_once 'src/item/rotas-item.php';
require_once 'src/consumo/rotas-consumo.php';
require_once 'src/pagamento/rotas-pagamento.php';

use phputil\router\HttpRequest;
use phputil\router\HttpResponse;
use phputil\router\Router;

use function phputil\cors\cors;



$app = new Router();

$pdo = conectar();

$app->use(cors());

// session_start();

$app->get('/', function (HttpRequest $req, HttpResponse $res) {
    $res->send('Bem vindo a api de reservas');
});

$app->post('/login', function (HttpRequest $req, HttpResponse $res) use ($pdo) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

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
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['usuario'];
                return $res->json([
                    'message' => 'Login successful',
                    'user_id' => $user['id'] // Inclui o ID do usuário no retorno
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


// Logout route
$app->post('/logout', function (HttpRequest $req, HttpResponse $res) {
    session_unset();
    session_destroy();
    $res->json(['message' => 'Logout successful']);
});

// // Middleware for authentication
// $app->use(function (HttpRequest $req, HttpResponse $res, $next) {
//     session_start();

//     $publicRoutes = ['/', '/login'];
//     $requestUri = $_SERVER['REQUEST_URI'];
//     echo $requestUri;

//     if (!in_array($requestUri, $publicRoutes) && !isset($_SESSION['user_id'])) {
//         error_log("Usuário não autenticado.");
//         $res->status(403)->json(['error' => 'Unauthorized']);
//         return;
//     }

//     $next();
// });



criarRotasCategoria($app, $pdo);
criarRotasItem($app, $pdo);
criarRotasReserva($app, $pdo);
criarRotasMesa($app, $pdo);
criarRotasFuncionario($app, $pdo);
criarRotasConsumo($app, $pdo);
criarRotasPagamento($app, $pdo);

$app->listen();
