<?php
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

    public function listarFuncionarios($req, $res)
    {
        $funcionarios = $this->funcionarioRepo->listarFuncionarios();
        return $res->json($funcionarios);
    }
}
