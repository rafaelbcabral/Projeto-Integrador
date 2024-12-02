<?php

// routes.php

require_once 'src/reserva/controller/reserva-controller.php';

/**
 * Undocumented function
 *
 * @param [type] $app
 * @param [type] $pdo
 * @return void
 */
function criarRotas($app, PDO $pdo)
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
}
