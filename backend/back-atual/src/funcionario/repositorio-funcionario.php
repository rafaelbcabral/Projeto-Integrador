<?php
require_once 'listar-funcionario-dto.php';

class FuncionarioRepositorio
{
    protected PDO $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function listarFuncionarios(): array
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

    public function findByUsername($username)
    {
        $query = "SELECT * FROM funcionario WHERE username = :username";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['username' => $username]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }


    public function adicionarUsuario($nome, $usuario, $cargo, $senhaComHash, $salt)
    {
        $query = "INSERT INTO funcionario (nome, usuario, cargo, senha, salt) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($query);

        $stmt->execute([$nome, $usuario, $cargo, $senhaComHash, $salt]);
    }

    public function buscarPorUsuario(string $username): ?array
    {
        $query = $this->pdo->prepare('SELECT * FROM funcionario WHERE usuario = :usuario');
        $query->bindValue(':usuario', $username);
        $query->execute();

        $user = $query->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }
}
