<?php
require_once 'mesa-controller.php';

function criarRotasMesa($app, PDO $pdo): void
{
    $mesaController = new MesaController($pdo);

    $app->get('/mesas', function ($req, $res) use ($mesaController) {
        $mesaController->listarMesas($req, $res);
    });

    $app->get('/mesas-disponiveis', function ($req, $res) use ($mesaController) {
        $mesaController->listarMesasDisponiveis($req, $res);
    });
}
