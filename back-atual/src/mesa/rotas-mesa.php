<?php
require_once 'controladora-mesa.php';
require_once 'servico-mesa.php';

/**
 * Undocumented function
 *
 * @param [type] $app
 * @param PDO $pdo
 * @return void
 */
function criarRotasMesa($app, PDO $pdo): void
{
    $servico = new ServicoMesa($pdo);

    $mesaController = new ControladoraMesa($servico);

    $app->get('/mesas', function ($req, $res) use ($mesaController) {
        $mesaController->listarMesas($req, $res);
    });

    $app->get('/mesas-disponiveis', function ($req, $res) use ($mesaController) {
        $mesaController->listarMesasDisponiveis($req, $res);
    });
}
