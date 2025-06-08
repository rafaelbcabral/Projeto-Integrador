<?php

use phputil\router\HttpRequest;
use phputil\router\HttpResponse;

require_once 'src/infra/nao-encontrado-exception.php';

class ItemController
{
    protected ItemRepositorio $itemRepo;
    protected PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->itemRepo = new ItemRepositorio($pdo);
    }

    // Listar todos os itens
    public function listarItens(HttpRequest $req, HttpResponse $res)
    {
        $itens = $this->itemRepo->listarItens();
        return $res->json($itens);
    }

    // Listar itens de uma categoria específica
    public function listarItensPorCategoria(HttpRequest $req, HttpResponse $res)
    {
        $categoriaId = (int) $req->param("categoriaId");
        $itens = $this->itemRepo->listarItensPorCategoria($categoriaId);
        return $res->json($itens);
    }

    // Buscar item por código
    public function listarItemPorCodigo(HttpRequest $req, HttpResponse $res)
    {
        $codigo = (string) $req->param("codigo");
        try {
            $item = $this->itemRepo->listarItemPorCodigo($codigo);
            return $res->json($item);
        } catch (NaoEncontradoException $e) {
            $erro = [
                'erro' => $e->getMessage()
            ];

            return $res->json($erro, 404);
        }
    }
}
