<?php

require_once 'repositorio-reserva.php';
require_once 'src/mesa/mesa-repositorio.php';
require_once 'src/infra/DominioException.php';
require_once 'src/infra/NaoEncontradoException.php';
require_once 'reserva.php';

class ReservaController
{
    protected ReservaRepository $reservaRepo;
    protected MesaRepository $mesaRepo;
    protected PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->reservaRepo = new ReservaRepository($pdo);
        $this->mesaRepo = new MesaRepository($pdo);
    }


    public function criarReserva($req, $res)
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

        // Verifica se a mesa está disponível
        if ($disponivel) {
            return $res->json(['status' => 'error', 'message' => 'Mesa não disponível para o horário solicitado'], 400);
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


    public function listarReservas($req, $res)
    {
        $reservas = $this->reservaRepo->listarReservas();
        return $res->json($reservas);
    }

    public function listarTodasAsReservas($req, $res)
    {
        $reservas = $this->reservaRepo->listarReservas();
        return $res->json($reservas);
    }


    public function cancelarReserva($req, $res)
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
