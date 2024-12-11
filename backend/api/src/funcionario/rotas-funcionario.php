<?php

use phputil\router\HttpRequest;
use phputil\router\HttpResponse;

require_once 'funcionario-controller.php';

function criarRotasFuncionario($app, PDO $pdo): void
{
    $funcionarioController = new FuncionarioController($pdo);

    $app->get('/funcionarios', function (HttpRequest $req, HttpResponse $res) use ($funcionarioController) {
        $funcionarioController->listarFuncionarios($req, $res);
    });
}
