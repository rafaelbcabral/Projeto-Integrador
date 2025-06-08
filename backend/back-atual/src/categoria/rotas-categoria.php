<?php

require_once 'controladora-categoria.php';
require_once 'servico-categoria.php';

use phputil\router\HttpRequest;
use phputil\router\HttpResponse;

function criarRotasCategoria($app, PDO $pdo): void
{
    $servico = new ServicoCategoria($pdo);

    $categoriaController = new ControladoraCategoria($servico);

    $app->get('/categorias', function (HttpRequest $req, HttpResponse $res) use ($categoriaController) {
        $categoriaController->listarCategorias($req, $res);
    });

    $app->get('/categorias/:id', function (HttpRequest $req, HttpResponse $res) use ($categoriaController) {
        $categoriaController->buscarCategoriaPorId($req, $res);
    });
}
