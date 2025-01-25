<?php

class Item
{
    public int $id;
    public string $codigo;
    public string $descricao;
    public float $preco;
    public int $categoria;

    /**
     * Undocumented function
     *
     * @param integer $id
     * @param string $codigo
     * @param string $descricao
     * @param float $preco
     * @param integer $categoria
     */
    public function __construct(int $id, string $codigo, string $descricao, float $preco, int $categoria)
    {
        $this->id = $id;
        $this->codigo = $codigo;
        $this->descricao = $descricao;
        $this->preco = $preco;
        $this->categoria = $categoria;
    }
}
