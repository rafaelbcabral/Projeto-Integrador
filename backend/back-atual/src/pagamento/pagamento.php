<?php
class Pagamento
{
    public int $id;
    public int $reserva;
    public float $valorTotal;
    public string $formaPagamento;
    public float $desconto;
    public float $totalComDesconto;
    public int $totalDeItens;

    /**
     * Undocumented function
     *
     * @param integer $id
     * @param integer $reserva
     * @param float $valorTotal
     * @param string $formaPagamento
     * @param integer $desconto
     * @param float $totalComDesconto
     * @param integer $totalDeItens
     */
    public function __construct(
        int $id,
        int $reserva,
        float $valorTotal,
        string $formaPagamento,
        int $desconto,
        float $totalComDesconto,
        int $totalDeItens
    ) {
        $this->id = $id;
        $this->reserva = $reserva;
        $this->valorTotal = $valorTotal;
        $this->formaPagamento = $formaPagamento;
        $this->desconto = $desconto;
        $this->totalComDesconto = $totalComDesconto;
        $this->totalDeItens = $totalDeItens;
    }
}
