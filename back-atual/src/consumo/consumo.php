<?php

class Consumo
{
    public int $id;
    public int $reserva;
    public int $item;
    public int $quantidade;
    public int $funcionario;
    public float $valorTotalPorItem;

    /**
     * Undocumented function
     *
     * @param integer $id
     * @param integer $reserva
     * @param integer $item
     * @param integer $quantidade
     * @param integer $funcionario
     * @param float $valorTotalPorItem
     */
    public function __construct(int $id, int $reserva, int $item, int $quantidade, int $funcionario)
    {
        $this->id = $id;
        $this->reserva = $reserva;
        $this->item = $item;
        $this->quantidade = $quantidade;
        $this->funcionario = $funcionario;
    }
}
