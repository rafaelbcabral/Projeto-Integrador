<?php
require_once 'funcionario-controller.php';

function criarRotasFuncionario($app, PDO $pdo)
{
    $funcionarioController = new FuncionarioController($pdo);

    $app->get('/funcionarios', function ($req, $res) use ($funcionarioController) {
        $funcionarioController->listarFuncionarios($req, $res);
    });
}
