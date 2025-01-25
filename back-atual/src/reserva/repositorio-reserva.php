<?php

require_once 'reserva.php';
require_once 'src/mesa/mesa.php';
require_once 'listar-reservas-dto.php';

class ReservaRepositorio
{
    protected PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function verificarDisponibilidade(Reserva $reserva): bool
    {
        $stmt = $this->pdo->prepare("
            SELECT 1 FROM reserva
            WHERE mesa = :mesa
            AND dataReservada = :data
            AND (
                (inicio < :fim AND fim > :inicio)
            )
        ");

        $stmt->execute([
            ':mesa' => $reserva->mesa,
            ':data' => $reserva->data,
            ':inicio' => $reserva->inicio,
            ':fim' => $reserva->fim
        ]);

        $resultados = $stmt->fetchAll();

        return count($resultados) > 0;
    }

    public function contarReservasDia(string $data): int
    {
        $diaSemana = (int)date('N', strtotime($data));
        $limiteReservas = ($diaSemana >= 5) ? Reserva::MESAS_MAX_FINAL_DE_SEMANA : Reserva::MESAS_MAX_DIA_DE_SEMANA;

        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM reserva WHERE dataReservada = ?");
        $stmt->execute([$data]);

        $quantidadeReservas = (int)$stmt->fetchColumn();

        return $quantidadeReservas;
    }

    public function salvarReserva(Reserva $reserva)
    {

        $stmt = $this->pdo->prepare("INSERT INTO reserva (nomeCliente, dataReservada, inicio, 
        fim, mesa, funcionario) VALUES (?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            $reserva->nomeCliente,
            $reserva->data,
            $reserva->inicio,
            $reserva->fim,
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
            throw $e;
        }
    }

    public function buscarReservaPorId($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM reservas WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function listarReservas(): array
    {

        $sql = "SELECT reserva.id, reserva.nomeCliente, reserva.dataReservada, reserva.inicio, reserva.fim, reserva.mesa, reserva.status, reserva.statusPagamento, funcionario.nome AS nomeFuncionario
        FROM reserva 
        JOIN funcionario ON reserva.funcionario = funcionario.id 
        WHERE reserva.dataReservada >= CURDATE()  
        ORDER BY reserva.dataReservada ASC, reserva.inicio ASC, reserva.mesa ASC;";

        $stmt = $this->pdo->query($sql);
        $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $reservasDTO = [];
        foreach ($reservas as $reserva) {
            $dto = new ListarReservaDTO(
                $reserva['id'],
                $reserva['nomeCliente'],
                $reserva['mesa'],
                $reserva['dataReservada'],
                $reserva['inicio'],
                $reserva['fim'],
                $reserva['nomeFuncionario'],
                $reserva['status'],
                $reserva['statusPagamento']
            );

            $reservasDTO[] = $dto;
        }
        return $reservasDTO;
    }

    // public function listarTodasAsReservas(): array
    // {
    //     $sql = "SELECT reserva.id, reserva.nome_cliente, reserva.data_reservada, reserva.inicio_reserva, reserva.fim_reserva, reserva.mesa, reserva.status, funcionario.nome AS nome_funcionario
    //     FROM reserva JOIN funcionario ON reserva.funcionario = funcionario.id ORDER BY
    //      reserva.data_reservada ASC, reserva.inicio_reserva ASC, reserva.mesa ASC;";

    //     $stmt = $this->pdo->query($sql);
    //     $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    //     $reservasDTO = [];
    //     foreach ($reservas as $reserva) {
    //         $dto = new ListarReservaDTO(
    //             $reserva['id'],
    //             $reserva['nome_cliente'],
    //             $reserva['mesa'],
    //             $reserva['data_reservada'],
    //             $reserva['inicio_reserva'],
    //             $reserva['fim_reserva'],
    //             $reserva['nome_funcionario'],
    //             $reserva['status']
    //         );

    //         $reservasDTO[] = $dto;
    //     }
    //     return $reservasDTO;
    // }

    public function listarReservasPorPeriodo(?string $dataInicial = null, ?string $dataFinal = null): array
    {
        $dataInicial = $dataInicial
            ? DateTime::createFromFormat('Y-m-d', $dataInicial)
            : new DateTime('first day of this month');

        $dataFinal = $dataFinal
            ? DateTime::createFromFormat('Y-m-d', $dataFinal)
            : new DateTime('last day of this month');

        if (!$dataInicial || !$dataFinal) {
            throw new InvalidArgumentException('As datas fornecidas são inválidas.');
        }

        $dataInicialStr = $dataInicial->format('Y-m-d');
        $dataFinalStr = $dataFinal->format('Y-m-d');

        $sql = "SELECT reserva.id, reserva.nomeCliente, reserva.dataReservada, reserva.inicio, 
            reserva.fim, reserva.mesa, reserva.status, reserva.statusPagamento, funcionario.nome AS nomeFuncionario
            FROM reserva
            JOIN funcionario ON reserva.funcionario = funcionario.id
            WHERE reserva.dataReservada BETWEEN :dataInicial AND :dataFinal
            AND reserva.status IN ('confirmada', 'cancelada') 
            ORDER BY reserva.dataReservada ASC, reserva.inicio ASC, reserva.mesa ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'dataInicial' => $dataInicialStr,
            'dataFinal' => $dataFinalStr
        ]);

        $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $reservasDTO = [];
        foreach ($reservas as $reserva) {
            $dto = new ListarReservaDTO(
                $reserva['id'],
                $reserva['nomeCliente'],
                $reserva['mesa'],
                $reserva['dataReservada'],
                $reserva['inicio'],
                $reserva['fim'],
                $reserva['nomeFuncionario'],
                $reserva['status'],
                $reserva['statusPagamento']
            );

            $reservasDTO[] = $dto;
        }

        return $reservasDTO;
    }
}
