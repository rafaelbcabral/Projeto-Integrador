<?php

use phputil\router\HttpRequest;
use phputil\router\HttpResponse;

require_once 'src/infra/nao-encontrado-exception.php';
require_once 'servico-categoria.php';

class ControladoraCategoria
{
    protected ServicoCategoria $servico;

    public function __construct(ServicoCategoria $servico)
    {
        $this->servico = $servico;
    }

    public function listarCategorias(HttpRequest $req, HttpResponse $res)
    {
        $categorias = $this->servico->listarCategorias();
        return $res->json($categorias);
    }


    public function buscarCategoriaPorId(HttpRequest $req, HttpResponse $res)
    {
        $id = (int) $req->param("id");

        try {
            $categoria = $this->servico->buscarCategoriaPorId($id);

            return $res->json($categoria);
        } catch (NaoEncontradoException $e) {
            $erro = [
                'erro' => $e->getMessage()
            ];

            return $res->json($erro, 404);
        }
    }
}
