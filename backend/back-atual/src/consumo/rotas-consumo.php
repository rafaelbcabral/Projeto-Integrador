<?php

use phputil\router\HttpRequest;
use phputil\router\HttpResponse;

require_once 'servico-consumo.php';
require_once 'controladora-consumo.php';

function criarRotasConsumo($app, PDO $pdo): void
{
    $servico = new ServicoConsumo($pdo);

    $consumoController = new ControladoraConsumo($servico);

    // Rota para criar consumos
    $app->post('/consumos', function (HttpRequest $req, HttpResponse $res) use ($consumoController) {
        $consumoController->adicionarConsumo($req, $res);
    });

    // Rota para ver consumo parcial de uma mesa
    $app->get('/consumos/:id', function (HttpRequest $req, HttpResponse $res) use ($consumoController) {
        $consumoController->visualizarConsumoComDetalhes($req, $res);
    });

    // Rota para ver consumo parcial de uma mesa
    $app->get('/relatorio/dia', function (HttpRequest $req, HttpResponse $res) use ($consumoController) {
        $consumoController->relatorioVendasPorPeriodoDia($req, $res);
    });

    // Rota para ver consumo parcial de uma mesa
    $app->get('/relatorio/pagamento', function (HttpRequest $req, HttpResponse $res) use ($consumoController) {
        $consumoController->relatorioVendasPorPeriodoPagamento($req, $res);
    });

    // Rota para ver consumo parcial de uma mesa
    $app->get('/relatorio/categoria', function (HttpRequest $req, HttpResponse $res) use ($consumoController) {
        $consumoController->relatorioVendasPorPeriodoCategoria($req, $res);
    });

    // Rota para ver consumo parcial de uma mesa
    $app->get('/relatorio/funcionario', function (HttpRequest $req, HttpResponse $res) use ($consumoController) {
        $consumoController->relatorioVendasPorPeriodoFuncionario($req, $res);
    });
}
