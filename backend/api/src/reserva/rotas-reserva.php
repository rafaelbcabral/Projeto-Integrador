<?php

// routes.php

require_once 'reserva-controller.php';

/**
 * Undocumented function
 *
 * @param [type] $app
 * @param [type] $pdo
 * @return void
 */
function criarRotasReserva($app, PDO $pdo)
{
    $reservaController = new ReservaController($pdo);

    // Rota principal
    $app->get('/', function ($req, $res) {
        $res->send('Bem-vindo ao sistema de Reservas!');
    });

    // Rota para cancelar reserva
    $app->put('/reservas/:id', function ($req, $res) use ($reservaController) {
        // Call the cancelarReserva method in the controller
        $reservaController->cancelarReserva($req, $res);
    });

    // Rota para criar reservas
    $app->post('/reservas', function ($req, $res) use ($reservaController) {
        $reservaController->criarReserva($req, $res);
    });

    $app->get('/reservas', function ($req, $res) use ($reservaController) {
        $reservaController->listarReservas($req, $res);
    });

    $app->get('/todas-as-reservas', function ($req, $res) use ($reservaController) {
        $reservaController->listarReservas($req, $res);
    });
}
