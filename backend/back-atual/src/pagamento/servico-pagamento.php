<?php

require_once 'pagamento.php';
require_once 'repositorio-pagamento.php';

class ServicoPagamento
{
    protected PagamentoRepositorio $pagamentoRepo;
    protected PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->pagamentoRepo = new PagamentoRepositorio($pdo);
    }

    public function adicionarPagamento($data)
    {
        // Validação da reserva, forma de pagamento e desconto
        if (empty($data->reserva) || empty($data->formaPagamento)) {
            throw new Exception("Dados incompletos");
        }

        // Passo 1: Obter o total de consumo da reserva
        $totalConsumo = $this->pagamentoRepo->obterTotalConsumoReserva($data->reserva);
        $totalDeItens = $this->pagamentoRepo->obterQuantidadeTotalItensReserva($data->reserva);

        // Passo 2: Calcular o desconto, se houver
        $desconto = 0;
        if (!empty($data->desconto)) {
            $desconto = $this->calcularDesconto($totalConsumo, $data->desconto);
        }

        // Passo 3: Calcular o total com desconto (se houver)
        $totalComDesconto = $totalConsumo - $desconto;

        // Passo 4: Criar um objeto Pagamento e inserir no banco
        $pagamento = new Pagamento(0, $data->reserva, $totalConsumo, $data->formaPagamento, $desconto, $totalComDesconto, $totalDeItens);

        // Inserir pagamento no banco
        $this->pagamentoRepo->adicionarPagamento($pagamento);
    }

    private function calcularDesconto($valorTotal, $percentualDesconto)
    {
        // O desconto vai de 1 a 5 (onde 1 = 1%, 2 = 2%, ..., 5 = 5%)
        if ($percentualDesconto < 1 || $percentualDesconto > 5) {
            throw new Exception("Desconto inválido. Deve ser um valor entre 1 e 5.");
        }

        // Calcular o valor do desconto
        $desconto = ($percentualDesconto / 100) * $valorTotal;

        return $desconto;
    }


    public function listarPagamentosPorReserva($reservaId)
    {
        if (empty($reservaId) || !is_numeric($reservaId)) {
            throw new Exception("ID da reserva inválido.");
        }

        return $this->pagamentoRepo->listarPagamentosPorReserva($reservaId);
    }
}
