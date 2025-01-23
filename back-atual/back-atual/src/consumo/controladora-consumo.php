<?php

use phputil\router\HttpRequest;
use phputil\router\HttpResponse;

class ControladoraConsumo
{
    private ServicoConsumo $servico;

    public function __construct(ServicoConsumo $servico)
    {
        $this->servico = $servico;
    }

    // // Listar todos os consumos
    // public function listarConsumos(HttpRequest $req, HttpResponse $res)
    // {
    //     $consumos = $this->consumoRepo->listarConsumos();
    //     return $res->json($consumos);
    // }

    // // Listar consumos de uma reserva específica
    // public function listarConsumosPorReserva(HttpRequest $req, HttpResponse $res, $reservaId)
    // {
    //     $consumos = $this->consumoRepo->listarConsumosPorReserva($reservaId);
    //     return $res->json($consumos);
    // }

    public function adicionarConsumo(HttpRequest $req, HttpResponse $res)
    {
        $data = $req->body();

        try {
            $this->servico->adicionarConsumo($data);
            return $res->json(['sucesso' => 'Consumo registrado com sucesso!']);
        } catch (Exception $e) {
            // Retorna erro em caso de exceção
            return $res->json(['erro' => $e->getMessage()], 500);
        }
    }

    public function visualizarConsumoComDetalhes($req, $res)
    {
        // $reservaId = (int) $req->param("reservaId");
        $id = (int) $req->param("id");

        try {
            $resumo = $this->servico->visualizarConsumoParcial($id);
            return $res->json($resumo);
        } catch (Exception $e) {
            return $res->json(['erro' => $e->getMessage()], 400);
        }
    }
}
