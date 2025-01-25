<?php
class ConsumoRepositorio
{
    protected PDO $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function adicionarConsumo(Consumo $consumo)
    {
        // Obter o preço do item a partir da tabela 'item'
        $sqlPreco = "SELECT preco FROM item WHERE id = :itemId";
        $stmtPreco = $this->pdo->prepare($sqlPreco);
        $stmtPreco->execute([':itemId' => $consumo->item]);

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
            r.mesa AS numeroMesa, -- Número da mesa
            f.nome AS nomeFuncionario, -- Nome do funcionário
            i.descricao AS nomeItem, -- Nome do item
            c.quantidade AS quantidadePorItem, -- Quantidade de cada item
            (c.quantidade * i.preco) AS valorTotalPorItem -- Valor total por item
        FROM consumo c
        JOIN item i ON c.item = i.id
        JOIN reserva r ON c.reserva = r.id
        JOIN funcionario f ON r.funcionario = f.id
        WHERE c.reserva = :reservaId
        ";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':reservaId' => $reservaId]);
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Agora, calculamos o valor total de todos os itens consumidos
        $valorTotal = 0;
        $totalItens = 0;
        $itens = [];

        foreach ($resultado as $item) {
            // Calculando o valor total
            $valorTotal += (float) $item['valorTotalPorItem'];
            $totalItens += (int) $item['quantidadePorItem'];

            $itens[] = [
                'nomeItem' => $item['nomeItem'],
                'quantidadePorItem' => (int) $item['quantidadePorItem'],
                'valorTotalPorItem' => (float) $item['valorTotalPorItem'],
            ];
        }

        return [
            'valorTotal' => $valorTotal,  // Valor total do pedido
            'totalItens' => $totalItens,  // Total de itens consumidos
            'numeroMesa' => $resultado[0]['numeroMesa'] ?? null, // Número da mesa
            'nomeFuncionario' => $resultado[0]['nomeFuncionario'] ?? null, // Nome do funcionário
            'itens' => $itens, // Lista de itens consumidos
        ];
    }


    public function relatorioVendasPorPeriodoFormaPagamento($formaDePagamento, $dataInicio, $dataFim)
    {
        $query = "
            SELECT 
                p.formaPagamento,
                SUM(p.valorTotal) AS totalVendido,
                (SUM(p.valorTotal) / (SELECT SUM(valorTotal) FROM pagamento WHERE reserva IN (SELECT id FROM reserva WHERE dataReservada BETWEEN :dataInicio AND :dataFim))) * 100 AS percentual
            FROM pagamento p
            JOIN reserva r ON p.reserva = r.id
            WHERE r.dataReservada BETWEEN :dataInicio AND :dataFim
            AND p.formaPagamento = :formaDePagamento
            GROUP BY p.formaPagamento
        ";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            ':dataInicio' => $dataInicio,
            ':dataFim' => $dataFim,
            ':formaDePagamento' => $formaDePagamento
        ]);

        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $totalVendidoPeriodo = 0;
        foreach ($resultado as $item) {
            $totalVendidoPeriodo += $item['totalVendido'];
        }

        return [
            'totalVendidoPeriodo' => $totalVendidoPeriodo,
            'formasPagamento' => $resultado,
        ];
    }



    public function relatorioVendasPorPeriodoFuncionario($idFuncionario, $dataInicio, $dataFim)
    {
        $query = "
    SELECT 
        f.nome AS nomeFuncionario,
        SUM(p.valorTotal) AS totalVendido,
        (SUM(p.valorTotal) / (SELECT SUM(valorTotal) FROM pagamento WHERE reserva IN (SELECT id FROM reserva WHERE dataReservada BETWEEN :dataInicio AND :dataFim))) * 100 AS percentual
    FROM pagamento p
    JOIN reserva r ON p.reserva = r.id
    JOIN funcionario f ON r.funcionario = f.id
    WHERE r.dataReservada BETWEEN :dataInicio AND :dataFim
    AND f.id = :idFuncionario
    GROUP BY f.id
    ";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            ':dataInicio' => $dataInicio,
            ':dataFim' => $dataFim,
            ':idFuncionario' => $idFuncionario
        ]);

        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $totalVendidoPeriodo = 0;
        foreach ($resultado as $item) {
            $totalVendidoPeriodo += $item['totalVendido'];
        }

        return [
            'totalVendidoPeriodo' => $totalVendidoPeriodo,
            'vendasPorFuncionario' => $resultado,
        ];
    }



    public function relatorioVendasPorPeriodoCategoria($idCategoria, $dataInicio, $dataFim)
    {
        $query = "
        SELECT 
            c.nome AS nomeCategoria,
            SUM(i.preco * cs.quantidade) AS totalVendido,
            (SUM(i.preco * cs.quantidade) / (SELECT SUM(i.preco * cs.quantidade) FROM consumo cs JOIN item i ON cs.item = i.id WHERE cs.reserva IN (SELECT id FROM reserva WHERE dataReservada BETWEEN :dataInicio AND :dataFim))) * 100 AS percentual
        FROM consumo cs
        JOIN item i ON cs.item = i.id
        JOIN categoria c ON i.categoria = c.id
        JOIN reserva r ON cs.reserva = r.id
        WHERE r.dataReservada BETWEEN :dataInicio AND :dataFim
        AND c.id = :idCategoria
        GROUP BY c.id
        ";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            ':dataInicio' => $dataInicio,
            ':dataFim' => $dataFim,
            ':idCategoria' => $idCategoria
        ]);

        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $totalVendidoPeriodo = 0;
        foreach ($resultado as $item) {
            $totalVendidoPeriodo += $item['totalVendido'];
        }

        return [
            'totalVendidoPeriodo' => $totalVendidoPeriodo,
            'vendasPorCategoria' => $resultado,
        ];
    }


    public function relatorioVendasPorPeriodoDia($dataInicio, $dataFim)
    {
        $query = "
        SELECT 
            DATE(r.dataReservada) AS dia,
            SUM(p.valorTotal) AS totalVendido
        FROM pagamento p
        JOIN reserva r ON p.reserva = r.id
        WHERE r.dataReservada BETWEEN :dataInicio AND :dataFim
        GROUP BY DATE(r.dataReservada)
        ";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute([
            ':dataInicio' => $dataInicio,
            ':dataFim' => $dataFim
        ]);

        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'vendasPorDia' => $resultado, // Retorna os dados para exibição no gráfico de colunas
        ];
    }
}
