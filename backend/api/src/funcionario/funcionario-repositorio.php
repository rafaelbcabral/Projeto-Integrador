<?php
require_once 'listar-funcionario-dto.php';

class FuncionarioRepositorio
{
    protected PDO $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }


    public function listarFuncionarios()
    {
        $sql = "SELECT id, nome from funcionario";
        $stmt = $this->pdo->query($sql);
        $funcionarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $funcionariosDTO = [];
        foreach ($funcionarios as $funcionario) {
            $dto = new ListarFuncionarioDTO(
                $funcionario['id'],
                $funcionario['nome']
            );
            $funcionariosDTO[] = $dto;
        }
        return $funcionariosDTO;
    }
}
