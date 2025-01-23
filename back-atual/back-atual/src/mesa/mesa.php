<?php

class Mesa
{
    public int $id;
    public int $numeroDaMesa;
    public bool $disponivel;

    /**
     * Undocumented function
     *
     * @param integer $id
     * @param integer $numeroDaMesa
     * @param boolean $disponivel
     */
    public function __construct(int $id, int $numeroDaMesa, bool $disponivel)
    {
        $this->id = $id;
        $this->numeroDaMesa = $numeroDaMesa;
        $this->disponivel = $disponivel;
    }
}
