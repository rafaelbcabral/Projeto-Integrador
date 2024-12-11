<?php

use phputil\router\HttpRequest;
use phputil\router\HttpResponse;

require_once 'repositorio-reserva.php';
require_once 'src/mesa/mesa-repositorio.php';
require_once 'src/infra/DominioException.php';
require_once 'src/infra/NaoEncontradoException.php';
require_once 'reserva.php';

class ReservaController
{
    protected ReservaRepositorio $reservaRepo;
    protected MesaRepositorio $mesaRepo;
    protected PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->reservaRepo = new ReservaRepositorio($pdo);
        $this->mesaRepo = new MesaRepositorio($pdo);
    }


    public function criarReserva(HttpRequest $req, HttpResponse $res)
    {
        // Obtém os dados do corpo da requisição
        $dados = (array) $req->body();
        // Sanitiza os dados de entrada
        foreach ($dados as $chave => &$valor) {
            $valor = htmlspecialchars($valor);
        }
        // Verifique se todas as chaves estão presentes
        if (!isset($dados['nomeCliente'], $dados['data'], $dados['horarioInicial'], $dados['mesa'], $dados['funcionario'])) {
            return $res->json(['status' => 'error', 'message' => 'Dados faltando'], 400);
        }
        // Criar nova reserva
        $reserva = new Reserva(
            0,
            $dados['nomeCliente'],
            $dados['mesa'],
            $dados['data'],
            $dados['horarioInicial'],
            $dados['funcionario']
        );
        // Verificar disponibilidade da mesa antes de validar a reserva
        $disponivel = $this->reservaRepo->verificarDisponibilidade($reserva);
        $reservasDia =  $this->reservaRepo->contarReservasDia($reserva->data);
        // Verifica se a mesa está disponível
        if ($disponivel) {
            return $res->json(['status' => 'error', 'message' => 'Mesa não disponível para o horário solicitado'], 400);
        }
        if (date('N', strtotime($reserva->data)) > 5) {
            // Final de semana (sábado e domingo)
            if ($reservasDia >= 10) {
                return $res->json(['status' => 'error', 'message' => 'Total de reservas excedido para o final de semana'], 400);
            }
        } else if (date('N', strtotime($reserva->data)) == 4 || date('N', strtotime($reserva->data)) == 5) {
            // Dias de semana (segunda a sexta-feira)
            if ($reservasDia >= 7) {
                return $res->json(['status' => 'error', 'message' => 'Total de reservas excedido para quinta e sexta-feira, o máximo são 7 reservas dias de semana.'], 400);
            }
        }
        try {
            // Valida a reserva
            $problemas = $reserva->validar();
            if (count($problemas)) {
                // Se houver problemas de validação, lança a exceção
                throw (new DominioException())->setProblemas($problemas);
            }

            // Salvar reserva no banco de dados
            $this->reservaRepo->salvarReserva($reserva);

            // Atualizar o status da mesa para não disponível
            $this->mesaRepo->atualizarStatusMesa($dados['mesa'], false);

            // Retorna uma resposta de sucesso
            return $res->json(['status' => 'success', 'message' => 'Reserva realizada com sucesso']);
        } catch (DominioException $e) {
            // Captura a exceção DominioException e retorna os problemas para o cliente
            return $res->json([
                'status' => 'error',
                'message' => 'Erro de validação',
                'problemas' => $e->getProblemas()
            ], 400);
        } catch (\Exception $e) {
            // Captura qualquer outro erro inesperado
            return $res->json(['status' => 'error', 'message' => 'Erro interno', 'details' => $e->getMessage()], 500);
        }
    }


    public function listarReservas(HttpRequest $req, HttpResponse $res)
    {
        $reservas = $this->reservaRepo->listarReservas();
        return $res->json($reservas);
    }

    public function listarTodasAsReservas(HttpRequest $req, HttpResponse $res)
    {
        $reservas = $this->reservaRepo->listarReservas();
        return $res->json($reservas);
    }

    public function listarReservasPorPeriodo(HttpRequest $req, HttpResponse $res)
    {

        $dados = (array) $req->queries();
        // Sanitiza os dados de entrada
        foreach ($dados as $chave => &$valor) {
            $valor = htmlspecialchars($valor);
        }

        $dataInicial = $dados['dataInicial'];
        $dataFinal = $dados['dataFinal'];

        $reservas = $this->reservaRepo->listarReservasPorPeriodo($dataInicial, $dataFinal);
        return $res->json($reservas);
    }


    public function cancelarReserva(HttpRequest $req, HttpResponse $res)
    {
        $id = (int) $req->param("id");
        if (empty($id) || !is_numeric($id) || $id <= 0) {
            return $res->json(['status' => 'error', 'message' => 'ID inválido.'], 400);
        }

        // Call the repository method to update the status in the database
        $this->reservaRepo->cancelarReserva($id);

        return $res->json(['status' => 'success', 'message' => 'Reserva cancelada']);
    }
}
