<?php

// routes.php

use phputil\router\HttpRequest;
use phputil\router\HttpResponse;

require_once 'reserva-controller.php';

/**
 * Undocumented function
 *
 * @param [type] $app
 * @param [type] $pdo
 * @return void
 */
function criarRotasReserva($app, PDO $pdo): void
{
    $reservaController = new ReservaController($pdo);

    // Rota principal
    $app->get('/', function (HttpRequest $req, HttpResponse $res) {
        $res->send('Bem-vindo ao sistema de Reservas!');
    });

    // Rota para cancelar reserva
    $app->put('/reservas/:id', function (HttpRequest $req, HttpResponse $res) use ($reservaController) {
        // Call the cancelarReserva method in the controller
        $reservaController->cancelarReserva($req, $res);
    });

    // Rota para criar reservas
    $app->post('/reservas', function (HttpRequest $req, HttpResponse $res) use ($reservaController) {
        $reservaController->criarReserva($req, $res);
    });

    $app->get('/reservas', function (HttpRequest $req, HttpResponse $res) use ($reservaController) {
        $reservaController->listarReservas($req, $res);
    });

    $app->get('/periodo', function (HttpRequest $req, HttpResponse $res) use ($reservaController) {
        $reservaController->listarReservasPorPeriodo($req, $res);
    });

    $app->get('/todas-as-reservas', function (HttpRequest $req, HttpResponse $res) use ($reservaController) {
        $reservaController->listarReservas($req, $res);
    });
}
