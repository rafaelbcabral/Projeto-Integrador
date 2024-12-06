<?php


class Mesa
{
    public int $id;
    public int $numeroDaMesa;
    public bool $disponivel;

    public function __construct(int $id, int $numeroDaMesa, bool $disponivel)
    {
        $this->id = $id;
        $this->numeroDaMesa = $numeroDaMesa;
        $this->disponivel = $disponivel;
    }
}
