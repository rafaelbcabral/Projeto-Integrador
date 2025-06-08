<?php

class Funcionario
{
    public int $id = 0;
    public string $nome = '';
    public string $usuario = '';
    public string $senha = '';
    public string $cargo = '';

    /**
     * Undocumented function
     *
     * @param integer $id
     * @param string $nome
     * @param string $usuario
     * @param string $senha
     */
    public function __construct(int $id, string $nome, string $usuario, string $cargo, string $senha)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->usuario = $usuario;
        $this->cargo = $cargo;
        $this->senha = $senha;
    }
}
