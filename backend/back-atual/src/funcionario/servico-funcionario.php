<?php

require_once 'repositorio-funcionario.php';

class ServicoFuncionario
{

    protected FuncionarioRepositorio $funcionarioRepo;
    protected PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->funcionarioRepo = new FuncionarioRepositorio($pdo);
    }

    public function adicionarFuncionario($data)
    {
        // Gerar salt
        $salt = $this->gerarSal();

        // Gerar hash da senha com o salt
        $senhaComHash = $this->hashSenha($data->senha, $salt);

        // Salvar usuário no banco de dados através do repositório
        $this->funcionarioRepo->adicionarUsuario($data->nome, $data->usuario,  $data->cargo, $senhaComHash, $salt);
    }

    private function gerarSal(): string
    {
        return bin2hex(random_bytes(20));
    }

    private function hashSenha(string $senha, string $sal): string
    {
        return hash(
            'sha512',
            'zadciumabdjsjf' . $senha . $sal . '4932oewrifdjsép9723o er4421258 hjaagdtdw    '
        );
    }

    public function listarFuncionarios()
    {
        $funcionarios = $this->funcionarioRepo->listarFuncionarios();
        return $funcionarios;
    }

    public function login(string $username, string $password): array
    {
        $user = $this->funcionarioRepo->buscarPorUsuario($username);

        if (!$user) {
            return ['success' => false, 'message' => 'Invalid credentials'];
        }

        $storedSalt = $user['salt'];
        $storedHash = $user['senha'];

        $computedHash = hash(
            'sha512',
            'zadciumabdjsjf' . $password . $storedSalt . '4932oewrifdjsép9723o er4421258 hjaagdtdw    '
        );

        if ($computedHash === $storedHash) {
            return ['success' => true, 'user' => $user];
        } else {
            return ['success' => false, 'message' => 'Invalid credentials'];
        }
    }
}
