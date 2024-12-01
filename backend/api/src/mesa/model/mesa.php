<?php


class Mesa
{
    public $id;
    public $numeroDaMesa;
    public $disponivel;

    public function __construct($id, $numeroDaMesa, $disponivel)
    {
        $this->id = $id;
        $this->numeroDaMesa = $numeroDaMesa;
        $this->disponivel = $disponivel;
    }
}
