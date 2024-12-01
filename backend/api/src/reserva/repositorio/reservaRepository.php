<?php

require_once 'src/reserva/model/reserva.php';
require_once 'src/mesa/model/mesa.php';

class ReservaRepository
{
    protected $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function verificarDisponibilidade($mesa, $data, $horaInicial, $horaTermino)
    {
        // Preparamos a consulta para verificar a disponibilidade da mesa
        $stmt = $this->pdo->prepare("
            SELECT * FROM reserva
            WHERE mesa = :mesa
            AND (
                (data_reservada = :data AND inicio_reserva BETWEEN :inicio AND :fim)
                OR
                (data_reservada = :data AND fim_reserva BETWEEN :inicio AND :fim)
            )
        ");

        // Executamos a consulta passando os parâmetros nomeados
        $stmt->execute([
            ':mesa' => $mesa,
            ':data' => $data,
            ':inicio' => $horaInicial,
            ':fim' => $horaTermino
        ]);

        // Verificamos se existem registros que indicam conflito de reserva
        return $stmt->rowCount() > 0;
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
            error_log("Error in cancelarReserva: " . $e->getMessage());
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

    public function listarReservas()
    {
        $stmt = $this->pdo->query("SELECT * FROM reserva ORDER BY inicio_reserva ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
