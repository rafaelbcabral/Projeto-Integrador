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

    public function relatorioVendasPorPeriodoFuncionario($req, $res)
    {
        $dados = (array) $req->queries();


        try {
            $relatorio = $this->servico->relatorioVendasPorPeriodoFuncionario($dados);
            return $res->json($relatorio);
        } catch (Exception $e) {
            return $res->json(['erro' => $e->getMessage()], 400);
        }
    }

    public function relatorioVendasPorPeriodoCategoria($req, $res)
    {
        $dados = (array) $req->queries();


        try {
            $relatorio = $this->servico->relatorioVendasPorPeriodoCategoria($dados);
            return $res->json($relatorio);
        } catch (Exception $e) {
            return $res->json(['erro' => $e->getMessage()], 400);
        }
    }

    public function relatorioVendasPorPeriodoDia($req, $res)
    {
        $dados = (array) $req->queries();


        try {
            $relatorio = $this->servico->relatorioVendasPorPeriodoDia($dados);
            return $res->json($relatorio);
        } catch (Exception $e) {
            return $res->json(['erro' => $e->getMessage()], 400);
        }
    }

    public function relatorioVendasPorPeriodoPagamento($req, $res)
    {
        $dados = (array) $req->queries();


        try {
            $relatorio = $this->servico->relatorioVendasPorPeriodoFormaPagamento($dados);
            return $res->json($relatorio);
        } catch (Exception $e) {
            return $res->json(['erro' => $e->getMessage()], 400);
        }
    }
}
