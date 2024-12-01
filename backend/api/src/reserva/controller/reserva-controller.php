<?php

require_once 'src/reserva/repositorio/reservaRepository.php';
require_once 'src/mesa/repositorio/mesa-repositorio.php';
require_once 'src/infra/DominioException.php';
require_once 'src/infra/NaoEncontradoException.php';

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

    // public function criarReserva($req, $res)
    // {
    //     $dados = (array) $req->body();
    //     foreach ($dados as $chave => &$valor) {
    //         $valor = htmlspecialchars($valor);
    //     }

    //     // Criar nova reserva
    //     $reserva = new Reserva(
    //         null, // id é auto-incremento, então passa null
    //         $dados['nomeCliente'],
    //         $dados['mesa'],
    //         $dados['data'],
    //         $dados['horarioInicial'],
    //         $dados['horarioTermino'],
    //         $dados['funcionario']
    //     );

    //     // Verifique se todas as chaves estão presentes
    //     if (!isset($dados['nomeCliente'], $dados['data'], $dados['horarioInicial'], $dados['horarioTermino'], $dados['mesa'], $dados['funcionario'])) {
    //         return $res->json(['status' => 'error', 'message' => 'Dados faltando'], 400);
    //     }

    //     // Valida a reserva
    //     $problemas = $reserva->validar();
    //     if (count($problemas)) {
    //         // Se houver problemas, lança a exceção
    //         throw (new DominioException())->setProblemas($problemas);
    //     }


    //     // Verificar disponibilidade
    //     if ($this->reservaRepo->verificarDisponibilidade(
    //         $dados['mesa'],
    //         $dados['data'],
    //         $dados['horarioInicial'],
    //         $dados['horarioTermino'],
    //     )) {
    //         return $res->json(['status' => 'error', 'message' => 'Mesa não disponível para o horário solicitado'], 400);
    //     }

    //     // Salvar reserva no banco de dados
    //     $this->reservaRepo->salvarReserva($reserva);

    //     // Atualizar o status da mesa para não disponível
    //     $this->mesaRepo->atualizarStatusMesa($dados['mesa'], false);

    //     return $res->json(['status' => 'success', 'message' => 'Reserva realizada com sucesso']);
    // }

    public function criarReserva($req, $res)
    {
        // Obtém os dados do corpo da requisição
        $dados = (array) $req->body();

        // Sanitiza os dados de entrada
        foreach ($dados as $chave => &$valor) {
            $valor = htmlspecialchars($valor); // Impede XSS
        }

        // Verifique se todas as chaves estão presentes
        if (!isset($dados['nomeCliente'], $dados['data'], $dados['horarioInicial'], $dados['horarioTermino'], $dados['mesa'], $dados['funcionario'])) {
            return $res->json(['status' => 'error', 'message' => 'Dados faltando'], 400);
        }

        // Criar nova reserva
        $reserva = new Reserva(
            null, // id é auto-incremento, então passa null
            $dados['nomeCliente'],
            $dados['mesa'],
            $dados['data'],
            $dados['horarioInicial'],
            $dados['horarioTermino'],
            $dados['funcionario']
        );

        try {
            // Valida a reserva
            $problemas = $reserva->validar();
            if (count($problemas)) {
                // Se houver problemas de validação, lança a exceção
                throw (new DominioException())->setProblemas($problemas);
            }

            // Verificar disponibilidade da mesa
            $disponivel = $this->reservaRepo->verificarDisponibilidade(
                $dados['mesa'],
                $dados['data'],
                $dados['horarioInicial'],
                $dados['horarioTermino']
            );

            if (!$disponivel) {
                return $res->json(['status' => 'error', 'message' => 'Mesa não disponível para o horário solicitado'], 400);
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


    public function cancelarReserva($req, $res)
    {
        $id = (int) $req->param("id");
        if (empty($id) || !is_numeric($id) || $id <= 0) {
            error_log("Invalid ID: $id");
            return $res->json(['status' => 'error', 'message' => 'ID inválido.'], 400);
        }

        // Call the repository method to update the status in the database
        $this->reservaRepo->cancelarReserva($id);

        return $res->json(['status' => 'success', 'message' => 'Reserva cancelada']);
    }

    // public function cancelarReserva($req, $res)
    // {
    //     $id = (int) $req->param("id");

    //     // Verifica se o ID é válido
    //     if (empty($id) || !is_numeric($id) || $id <= 0) {
    //         error_log("Invalid ID: $id");
    //         return $res->json(['status' => 'error', 'message' => 'ID inválido.'], 400);
    //     }

    //     try {
    //         // Verificar se a reserva existe no banco de dados
    //         $reserva = $this->reservaRepo->buscarReservaPorId($id);

    //         if (!$reserva) {
    //             // Se a reserva não for encontrada, lançar a exceção NaoEncontradoException
    //             throw new NaoEncontradoException("Reserva com ID $id não encontrada.");
    //         }

    //         // Se a reserva for encontrada, proceder com o cancelamento
    //         $this->reservaRepo->cancelarReserva($id);

    //         return $res->json(['status' => 'success', 'message' => 'Reserva cancelada com sucesso']);
    //     } catch (NaoEncontradoException $e) {
    //         // Captura a exceção e retorna um erro 404 (Não Encontrado)
    //         return $res->json([
    //             'status' => 'error',
    //             'message' => $e->getMessage()
    //         ], 404);
    //     } catch (\Exception $e) {
    //         // Captura qualquer outra exceção e retorna um erro 500 (Erro Interno)
    //         return $res->json([
    //             'status' => 'error',
    //             'message' => 'Erro interno',
    //             'details' => $e->getMessage()
    //         ], 500);
    //     }
    // }
}
