<?php

require_once 'repositorio-categoria.php';
require_once 'src/infra/nao-encontrado-exception.php';

class ServicoCategoria
{
    protected CategoriaRepositorio $categoriaRepo;
    protected PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->categoriaRepo = new CategoriaRepositorio($pdo);
    }


    public function listarCategorias()
    {
        $categorias = $this->categoriaRepo->listarCategorias();
        return $categorias;
    }


    public function buscarCategoriaPorId($id)
    {

        $dados = $this->categoriaRepo->buscarCategoriaPorId($id);

        if (!$dados) {
            throw new NaoEncontradoException("Categoria com ID {$id} nao encontrada.");
        }

        $categoria = new Categoria($dados['id'], $dados['nome']);

        return $categoria;
    }
}
