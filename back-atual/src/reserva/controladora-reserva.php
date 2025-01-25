<?php

use phputil\router\HttpRequest;
use phputil\router\HttpResponse;


class ControladoraReserva
{
    protected ServicoReserva $servicoReserva;

    public function __construct(ServicoReserva $servicoReserva)
    {
        $this->servicoReserva = $servicoReserva;
    }

    public function criarReserva(HttpRequest $req, HttpResponse $res)
    {
        $dados = (array) $req->body();

        try {
            $reserva = $this->servicoReserva->criarReserva($dados);
            return $res->json(['status' => 'success', 'message' => 'Reserva realizada com sucesso']);
        } catch (DominioException $e) {
            return $res->json([
                'status' => 'error',
                'message' => 'Erro de validação',
                'problemas' => $e->getProblemas()
            ], 400);
        } catch (\Exception $e) {
            return $res->json(['status' => 'error', 'message' => 'Erro interno', 'details' => $e->getMessage()], 500);
        }
    }

    public function listarReservas(HttpRequest $req, HttpResponse $res)
    {
        $reservas = $this->servicoReserva->listarReservas();
        return $res->json($reservas);
    }

    public function listarReservasPorPeriodo(HttpRequest $req, HttpResponse $res)
    {
        $dados = (array) $req->queries();
        foreach ($dados as $chave => &$valor) {
            $valor = htmlspecialchars($valor);
        }

        $dataInicial = $dados['dataInicial'];
        $dataFinal = $dados['dataFinal'];

        $reservas = $this->servicoReserva->listarReservasPorPeriodo($dataInicial, $dataFinal);
        return $res->json($reservas);
    }

    public function cancelarReserva(HttpRequest $req, HttpResponse $res)
    {
        $id = (int) $req->param("id");
        if (empty($id) || !is_numeric($id) || $id <= 0) {
            return $res->json(['status' => 'error', 'message' => 'ID inválido.'], 400);
        }

        $this->servicoReserva->cancelarReserva($id);

        return $res->json(['status' => 'success', 'message' => 'Reserva cancelada']);
    }
}
