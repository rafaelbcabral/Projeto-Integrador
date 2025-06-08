<?php

class Categoria
{
    public int $id;
    public string $nome;

    /**
     * Undocumented function
     *
     * @param integer $id
     * @param string $nome
     */
    public function __construct(int $id, string $nome)
    {
        $this->id = $id;
        $this->nome = $nome;
    }
}
