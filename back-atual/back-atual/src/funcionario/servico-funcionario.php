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
        $this->funcionarioRepo->adicionarUsuario($data->nome, $data->usuario, $senhaComHash, $salt);
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
}
