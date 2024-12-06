<?php

class FuncionarioController
{
    public int $id = 0;
    public string $nome = '';
    public string $usuario = '';
    public string $senha = '';

    public function __construct(int $id, string $nome, string $usuario, string $senha)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->usuario = $usuario;
        $this->senha = $senha;
    }
}
