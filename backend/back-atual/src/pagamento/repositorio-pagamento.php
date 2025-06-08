<?php
class PagamentoRepositorio
{
    protected PDO $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function adicionarPagamento(Pagamento $pagamento)
    {

        $this->pdo->beginTransaction();

        try {
            // 1. Inserir o pagamento na tabela pagamento
            $query = "
                INSERT INTO pagamento (reserva, valorTotal, formaPagamento, desconto, totalComDesconto, totalDeItens)
                VALUES (:reserva, :valorTotal, :formaPagamento, :desconto, :totalComDesconto, :totalDeItens)
            ";

            $stmt = $this->pdo->prepare($query);

            $stmt->execute([
                ':reserva' => $pagamento->reserva,
                ':valorTotal' => $pagamento->valorTotal,
                ':formaPagamento' => $pagamento->formaPagamento,
                ':desconto' => $pagamento->desconto,
                ':totalComDesconto' => $pagamento->totalComDesconto,
                ':totalDeItens' => $pagamento->totalDeItens
            ]);

            // 2. Atualiza o statusPagamento da reserva para 'fechado'
            $sqlAtualizaStatus = "UPDATE reserva SET statusPagamento = 'fechado' WHERE id = :reserva_id";
            $stmt2 = $this->pdo->prepare($sqlAtualizaStatus);
            $stmt2->execute([
                ':reserva_id' => $pagamento->reserva
            ]);

            // 3. Commit da transação se tudo correu bem
            $this->pdo->commit();
        } catch (Exception $e) {
            // Se algo der errado, faz rollback
            $this->pdo->rollBack();

            throw new Exception("Erro ao registrar o pagamento ou atualizar o status da reserva: " . $e->getMessage());
        }
    }

    public function obterTotalConsumoReserva($reservaId)
    {
        // Consultar o total de consumo da reserva, somando os itens consumidos
        $query = "SELECT r.id AS reserva, SUM(c.valorTotalPorItem) AS total FROM
        consumo c JOIN item i ON c.item = i.id JOIN reserva r ON c.reserva = r.id
        WHERE r.id = :reservaId";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':reservaId' => $reservaId]);
        $total = $stmt->fetch(PDO::FETCH_ASSOC);
        return (float) $total['total'];
    }

    public function obterQuantidadeTotalItensReserva($reservaId)
    {
        // Consultar a quantidade total de itens consumidos na reserva
        $query = "SELECT r.id AS reserva, SUM(c.quantidade) AS totalItens
              FROM consumo c
              JOIN reserva r ON c.reserva = r.id
              WHERE r.id = :reservaId";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':reservaId' => $reservaId]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int) $resultado['totalItens'];
    }


    // // Método para listar pagamentos
    // public function listarPagamentos(): array
    // {
    //     $sql = "SELECT * FROM pagamento";
    //     $stmt = $this->pdo->query($sql);
    //     $pagamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //     return $pagamentos;
    // }

    // Método para listar pagamentos de uma reserva específica
    public function listarPagamentosPorReserva($reservaId)
    {
        $sql = "SELECT * FROM pagamento WHERE reserva = :reservaId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':reservaId' => $reservaId]);
        $pagamento = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $pagamento;
    }
}
