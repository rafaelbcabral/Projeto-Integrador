<?php

class ListarItem
{
    public $id;
    public $codigo;
    public $descricao;
    public $preco;
    public $categoria;

    public function __construct($id, $codigo, $descricao, $preco, $categoria)
    {
        $this->id = $id;
        $this->codigo = $codigo;
        $this->descricao = $descricao;
        $this->preco = $preco;
        $this->categoria = $categoria;
    }
}
