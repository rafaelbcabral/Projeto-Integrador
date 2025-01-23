<?php

require_once 'controladora-item.php';
require_once 'servico-item.php';

use phputil\router\HttpRequest;
use phputil\router\HttpResponse;

function criarRotasItem($app, PDO $pdo): void
{
    $itemController = new ItemController($pdo);

    // Rota para listar todos os itens
    $app->get('/itens', function (HttpRequest $req, HttpResponse $res) use ($itemController) {
        $itemController->listarItens($req, $res);
    });

    // Rota para listar itens por categoria
    $app->get('/itens/categoria/:categoriaId', function (HttpRequest $req, HttpResponse $res) use ($itemController) {
        $itemController->listarItensPorCategoria($req, $res);
    });

    // Rota para buscar item por código
    $app->get('/itens/:codigo', function (HttpRequest $req, HttpResponse $res) use ($itemController) {
        $itemController->listarItemPorCodigo($req, $res);
    });
}
