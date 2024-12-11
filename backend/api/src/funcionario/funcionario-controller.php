<?php

use phputil\router\HttpRequest;
use phputil\router\HttpResponse;

require_once 'funcionario-repositorio.php';

class FuncionarioController
{
    protected FuncionarioRepositorio $funcionarioRepo;
    protected PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->funcionarioRepo = new FuncionarioRepositorio($pdo);
    }

    public function listarFuncionarios(HttpRequest $req, HttpResponse $res)
    {
        $funcionarios = $this->funcionarioRepo->listarFuncionarios();
        return $res->json($funcionarios);
    }
}
