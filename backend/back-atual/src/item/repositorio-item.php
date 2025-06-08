<?php
class ItemRepositorio
{
    protected PDO $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Método para listar todos os itens
    public function listarItens(): array
    {
        $sql = "SELECT 
            i.id, 
            i.codigo, 
            i.descricao, 
            i.preco, 
            c.nome AS categoria
        FROM item i
        JOIN categoria c ON i.categoria = c.id ";
        $stmt = $this->pdo->query($sql);
        $itens = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $itens;
    }

    public function listarItensPorCategoria($categoriaId)
    {
        $sql = "SELECT 
            i.id, 
            i.codigo, 
            i.descricao, 
            i.preco, 
            c.nome AS categoria
        FROM item i
        JOIN categoria c ON i.categoria = c.id
        WHERE i.categoria = :categoriaId ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['categoriaId' => $categoriaId]);
        $itens = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $itens;
    }

    // Método para buscar um item por código
    public function listarItemPorCodigo($codigo)
    {
        $sql = "SELECT 
            i.id, 
            i.codigo, 
            i.descricao, 
            i.preco, 
            c.nome AS categoria
        FROM item i
        JOIN categoria c ON i.categoria = c.id
        WHERE i.codigo = :codigo";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':codigo' => $codigo]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);
        return $item;
    }
}
