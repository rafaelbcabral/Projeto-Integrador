<?php
class ConsumoRepositorio
{
    protected PDO $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // // Método para listar os consumos
    // public function listarConsumos(): array
    // {
    //     $sql = "SELECT * FROM consumo";
    //     $stmt = $this->pdo->query($sql);
    //     $consumos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //     return $consumos;
    // }

    // // Método para listar consumos de uma reserva específica
    // public function listarConsumosPorReserva($reservaId): array
    // {
    //     $sql = "SELECT * FROM consumo WHERE reserva = :reservaId";
    //     $stmt = $this->pdo->prepare($sql);
    //     $stmt->bindParam(':reservaId', $reservaId, PDO::PARAM_INT);
    //     $stmt->execute();
    //     $consumos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //     return $consumos;
    // }

    // public function adicionarConsumo(Consumo $consumo)
    // {

    //     $sql = "INSERT INTO consumo (reserva, item, quantidade, funcionario) 
    //             VALUES (:reserva, :item, :quantidade, :funcionario)";

    //     $stmt = $this->pdo->prepare($sql);
    //     $stmt->execute([
    //         ':reserva' => $consumo->reserva,
    //         ':item' => $consumo->item,
    //         ':quantidade' => $consumo->quantidade,
    //         ':funcionario' => $consumo->funcionario
    //     ]);
    // }

    public function adicionarConsumo(Consumo $consumo)
    {
        // Obter o preço do item a partir da tabela 'item'
        $sqlPreco = "SELECT preco FROM item WHERE id = :item_id";
        $stmtPreco = $this->pdo->prepare($sqlPreco);
        $stmtPreco->execute([':item_id' => $consumo->item]);

        $item = $stmtPreco->fetch(PDO::FETCH_ASSOC);



        $valorTotalPorItem = $item['preco'] * $consumo->quantidade;

        $sql = "INSERT INTO consumo (reserva, item, quantidade, funcionario, valorTotalPorItem) 
            VALUES (:reserva, :item, :quantidade, :funcionario, :valorTotalPorItem)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':reserva' => $consumo->reserva,
            ':item' => $consumo->item,
            ':quantidade' => $consumo->quantidade,
            ':funcionario' => $consumo->funcionario,
            ':valorTotalPorItem' => $valorTotalPorItem
        ]);
    }

    public function verificarConsumoExistente($reservaId, $itemId)
    {
        $sql = "SELECT * FROM consumo WHERE reserva = :reservaId AND item = :itemId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':reservaId' => $reservaId,
            ':itemId' => $itemId
        ]);
        return $stmt->fetch(PDO::FETCH_OBJ);  // Retorna o consumo, caso exista
    }

    public function atualizarQuantidadeConsumo($reservaId, $itemId, $novaQuantidade)
    {
        // Obter o preço do item a partir da tabela 'item'
        $sqlPreco = "SELECT preco FROM item WHERE id = :itemId";
        $stmtPreco = $this->pdo->prepare($sqlPreco);
        $stmtPreco->execute([':itemId' => $itemId]);

        $item = $stmtPreco->fetch(PDO::FETCH_ASSOC);

        if (!$item) {
            throw new Exception("Item não encontrado.");
        }

        // Calcular o valor total por item com a nova quantidade
        $valorTotalPorItem = $item['preco'] * $novaQuantidade;

        // Atualizar a quantidade e o valor total por item na tabela consumo
        $sql = "UPDATE consumo SET quantidade = :quantidade, valorTotalPorItem = :valorTotalPorItem 
                WHERE reserva = :reservaId AND item = :itemId";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':quantidade' => $novaQuantidade,
            ':valorTotalPorItem' => $valorTotalPorItem,
            ':reservaId' => $reservaId,
            ':itemId' => $itemId
        ]);
    }



    public function obterResumoConsumoParcial($reservaId)
    {
        $query = "
        SELECT 
            SUM(c.quantidade * i.preco) AS valorTotal,
            SUM(c.quantidade) AS totalItens,
            r.mesa AS numeroMesa,
            f.nome AS nomeFuncionario
        FROM consumo c
        JOIN item i ON c.item = i.id
        JOIN reserva r ON c.reserva = r.id
        JOIN funcionario f ON r.funcionario = f.id
        WHERE c.reserva = :reservaId
    ";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':reservaId' => $reservaId]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return [
            'valorTotal' => (float) ($resultado['valorTotal'] ?? 0),
            'totalItens' => (int) ($resultado['totalItens'] ?? 0),
            'numeroMesa' => $resultado['numeroMesa'] ?? null,
            'nomeFuncionario' => $resultado['nomeFuncionario'] ?? null,
        ];
    }
}
