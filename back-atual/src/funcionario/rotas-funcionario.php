<?php

use phputil\router\HttpRequest;
use phputil\router\HttpResponse;

require_once 'servico-funcionario.php';
require_once 'controladora-funcionario.php';


function criarRotasFuncionario($app, PDO $pdo): void
{
    $servico = new ServicoFuncionario($pdo);

    $funcionarioController = new ControladoraFuncionario($servico);

    $app->get('/funcionarios', function (HttpRequest $req, HttpResponse $res) use ($funcionarioController) {
        $funcionarioController->listarFuncionarios($req, $res);
    });

    $app->post('/funcionarios', function (HttpRequest $req, HttpResponse $res) use ($funcionarioController) {
        $funcionarioController->criarFuncionarios($req, $res);
    });
}
