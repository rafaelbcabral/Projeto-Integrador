<?php
require_once 'repositorio-item.php';
require_once 'src/infra/nao-encontrado-exception.php';

class ServicoItem
{

    protected ItemRepositorio $itemRepo;
    protected PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->itemRepo = new ItemRepositorio($pdo);
    }

    public function listarItens()
    {
        $itens = $this->itemRepo->listarItens();

        // Cria um array para armazenar as instâncias de ListarItem
        $itensListados = [];

        // Para cada item retornado, cria uma instância de ListarItem
        foreach ($itens as $item) {
            // Instancia o ListarItem com os dados de cada item
            $itensListados[] = new ListarItem(
                $item['id'],           // id do item
                $item['codigo'],       // código do item
                $item['descricao'],    // descrição do item
                $item['preco'],        // preço do item
                $item['categoria']     // categoria do item
            );
        }
        return $itensListados;
    }

    // Buscar categoria por ID
    public function listarItensPorCategoria($categoriaId)
    {

        $itens = $this->itemRepo->listarItensPorCategoria($categoriaId);

        if (!$itens) {
            throw new NaoEncontradoException("Categoria com ID {$categoriaId} nao encontrada.");
        }
        // Cria um array para armazenar as instâncias de ListarItem
        $itensListados = [];

        // Para cada item retornado, cria uma instância de ListarItem
        foreach ($itens as $item) {
            // Instancia o ListarItem com os dados de cada item
            $itensListados[] = new ListarItem(
                $item['id'],           // id do item
                $item['codigo'],       // código do item
                $item['descricao'],    // descrição do item
                $item['preco'],        // preço do item
                $item['categoria']     // categoria do item
            );
        }

        return $itensListados;
    }

    public function buscarItemPorCodigo($codigo)
    {

        // Chama o repositório para buscar o item
        $dados = $this->itemRepo->listarItemPorCodigo($codigo);

        // Se não encontrar o item, lança a exceção
        if (!$dados) {
            throw new NaoEncontradoException("Item com Código {$codigo} não encontrado.");
        }

        $item = new ListarItem(
            $dados['id'],           // id do item
            $dados['codigo'],       // código do item
            $dados['descricao'],    // descrição do item
            $dados['preco'],        // preço do item
            $dados['categoria']     // categoria do item);
        );

        return $item;
    }
}
