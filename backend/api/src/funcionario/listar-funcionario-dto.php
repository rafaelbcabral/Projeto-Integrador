<?php

class ListarFuncionarioDTO
{
    public int $id = 0;
    public string $nome = '';

    public function __construct(int $id, string $nome)
    {
        $this->id = $id;
        $this->nome = $nome;
    }
}
