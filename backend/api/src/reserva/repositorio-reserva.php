<?php

require_once 'reserva.php';
require_once './mesa/mesa.php';
require_once 'listar-reservas.php';

class ReservaRepository
{
    protected $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function verificarDisponibilidade(Reserva $reserva)
    {
        // Prepara a consulta para verificar a disponibilidade da mesa no horário solicitado
        $stmt = $this->pdo->prepare("
            SELECT 1 FROM reserva
            WHERE mesa = :mesa
            AND data_reservada = :data
            AND (
                (inicio_reserva < :fim AND fim_reserva > :inicio)
            )
        ");

        // Executa a consulta passando os parâmetros nomeados
        $stmt->execute([
            ':mesa' => $reserva->mesa,
            ':data' => $reserva->data, // A data sem o horário
            ':inicio' => $reserva->horaInicial,
            ':fim' => $reserva->horaTermino
        ]);

        $resultados = $stmt->fetchAll();

        // Se houver algum resultado, significa que a mesa já está reservada para o horário solicitado
        return count($resultados) > 0;
    }

    public function salvarReserva(Reserva $reserva)
    {
        $stmt = $this->pdo->prepare("INSERT INTO reserva (nome_cliente, data_reservada, inicio_reserva, 
        fim_reserva, mesa, funcionario) VALUES (?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            $reserva->nomeCliente,
            $reserva->data,
            $reserva->horaInicial,
            $reserva->horaTermino,
            $reserva->mesa,
            $reserva->funcionario
        ]);
    }

    public function cancelarReserva($id)
    {
        try {
            $sql = "UPDATE reserva SET status = 'cancelada' WHERE id = :id";
            $ps = $this->pdo->prepare($sql);
            $ps->execute(['id' => $id]);
        } catch (Exception $e) {
            // Log any exception during execution
            throw $e;  // Re-throw exception to be handled by controller
        }
    }

    // Método para buscar a reserva pelo ID
    public function buscarReservaPorId($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM reservas WHERE id = :id");
        $stmt->execute(['id' => $id]);
        // Retorna o resultado ou null se não encontrado
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function listarReservas(): array
    {
        $sql = "SELECT reserva.id, reserva.nome_cliente, reserva.data_reservada, reserva.inicio_reserva, reserva.fim_reserva, reserva.mesa, reserva.status, funcionario.nome AS nome_funcionario
        FROM reserva JOIN funcionario ON reserva.funcionario = funcionario.id";
        // $stmt = $this->pdo->query("SELECT * FROM reserva");
        $stmt = $this->pdo->query($sql);
        $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $reservasDTO = [];
        foreach ($reservas as $reserva) {
            $dto = new ListarReservaDTO(
                $reserva['id'],               // id
                $reserva['nome_cliente'],      // nomeCliente
                $reserva['mesa'],              // mesa
                $reserva['data_reservada'],    // data
                $reserva['inicio_reserva'],    // horaInicial
                $reserva['fim_reserva'],       // horaTermino
                $reserva['nome_funcionario'],  // nomeFuncionario
                $reserva['status']             // status
            );

            $reservasDTO[] = $dto;
        }
        return $reservasDTO;
    }
}
