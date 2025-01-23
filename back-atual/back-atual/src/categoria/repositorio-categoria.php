<?php
require_once 'src/infra/nao-encontrado-exception.php';
require_once 'categoria.php';

class CategoriaRepositorio
{
    protected PDO $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function listarCategorias(): array
    {
        $sql = "SELECT * FROM categoria";
        $stmt = $this->pdo->query($sql);
        $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $categorias;
    }

    public function buscarCategoriaPorId($id)
    {
        $sql = "SELECT * FROM categoria WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $categoria = $stmt->fetch(PDO::FETCH_ASSOC);

        return $categoria;
    }
}
