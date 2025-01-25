<?php

use phputil\router\HttpRequest;
use phputil\router\HttpResponse;

require_once 'controladora-pagamento.php';
require_once 'servico-pagamento.php';

function criarRotasPagamento($app, PDO $pdo): void
{

    $servico = new ServicoPagamento($pdo);
    $pagamentoController = new ControladoraPagamento($servico);

    // Rota para listar pagamentos
    $app->post('/pagamentos', function (HttpRequest $req, HttpResponse $res) use ($pagamentoController) {
        $pagamentoController->adicionarPagamento($req, $res);
    });

    // // Rota para listar pagamentos de uma reserva específica
    // $app->get('/pagamentos/{reservaId}', function (HttpRequest $req, HttpResponse $res) use ($pagamentoController) {
    //     $reservaId = $req->getAttribute('reservaId');
    //     $pagamentoController->listarPagamentosPorReserva($req, $res, $reservaId);
    // });
}
