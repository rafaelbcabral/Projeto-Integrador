<?php

use phputil\router\HttpRequest;
use phputil\router\HttpResponse;

class ControladoraMesa
{
    protected ServicoMesa $servico;

    public function __construct(ServicoMesa $servico)
    {
        $this->servico = $servico;
    }

    public function listarMesas(HttpRequest $req, HttpResponse $res)
    {
        // Chama o serviço para listar as mesas
        $mesas = $this->servico->listarMesas();
        return $res->json($mesas);
    }

    public function listarMesasDisponiveis(HttpRequest $req, HttpResponse $res)
    {
        $dados = (array) $req->queries('data');

        try {
            // Chama o serviço para listar mesas disponíveis
            $mesasDisponiveis = $this->servico->listarMesasDisponiveis($dados);
            return $res->json($mesasDisponiveis);
        } catch (\InvalidArgumentException $e) {
            // Lida com erro de validação
            return $res->json(['error' => $e->getMessage()], 400);
        }
    }
}
