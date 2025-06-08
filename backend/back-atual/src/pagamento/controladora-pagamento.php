<?php

use phputil\router\HttpRequest;
use phputil\router\HttpResponse;

require_once 'servico-pagamento.php';

class ControladoraPagamento
{
    private ServicoPagamento $servico;

    public function __construct(ServicoPagamento $servico)
    {
        $this->servico = $servico;
    }

    public function adicionarPagamento(HttpRequest $req, HttpResponse $res)
    {
        // Recebe o corpo da requisição e transforma em objeto (JSON para array)
        $data = $req->body();

        // Chama o serviço para adicionar o pagamento
        try {
            $this->servico->adicionarPagamento($data);
            return $res->json(['sucesso' => 'Pagamento registrado com sucesso!']);
        } catch (Exception $e) {
            // Retorna erro em caso de exceção
            return $res->json(['erro' => $e->getMessage()], 500);
        }
    }

    public function listarPagamentosPorReserva($req, $res)
    {
        try {
            // Obtém o ID da reserva dos parâmetros da requisição
            $reservaId = (int) $req->param('reservaId');
            // Chama o serviço para listar os pagamentos
            $pagamentos = $this->servico->listarPagamentosPorReserva($reservaId);
            // Retorna a resposta em JSON
            return $res->json($pagamentos);
        } catch (Exception $e) {
            // Retorna um erro em caso de falha
            return $res->json(['erro' => $e->getMessage()], 400);
        }
    }
}
