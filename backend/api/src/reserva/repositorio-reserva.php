<?php

require_once 'reserva.php';
require_once 'src/mesa/mesa.php';
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
        FROM reserva 
        JOIN funcionario ON reserva.funcionario = funcionario.id 
        WHERE reserva.data_reservada >= CURDATE()  -- Filtra para reservas a partir de hoje
        ORDER BY reserva.data_reservada ASC, reserva.inicio_reserva ASC, reserva.mesa ASC;";

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

    public function listarTodasAsReservas(): array
    {
        $sql = "SELECT reserva.id, reserva.nome_cliente, reserva.data_reservada, reserva.inicio_reserva, reserva.fim_reserva, reserva.mesa, reserva.status, funcionario.nome AS nome_funcionario
        FROM reserva JOIN funcionario ON reserva.funcionario = funcionario.id ORDER BY
         reserva.data_reservada ASC, reserva.inicio_reserva ASC, reserva.mesa ASC;";

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

    public function listarReservasPorPeriodo(?string $dataInicial = null, ?string $dataFinal = null): array
    {
        // Define valores padrão para o período, transformando strings em objetos DateTime
        $dataInicial = $dataInicial
            ? DateTime::createFromFormat('Y-m-d', $dataInicial)
            : new DateTime('first day of this month');

        $dataFinal = $dataFinal
            ? DateTime::createFromFormat('Y-m-d', $dataFinal)
            : new DateTime('last day of this month');

        // Valida se as strings são datas válidas
        if (!$dataInicial || !$dataFinal) {
            throw new InvalidArgumentException('As datas fornecidas são inválidas.');
        }

        // Converte as datas para strings no formato Y-m-d
        $dataInicialStr = $dataInicial->format('Y-m-d');
        $dataFinalStr = $dataFinal->format('Y-m-d');

        // SQL com filtro por período de datas
        $sql = "SELECT reserva.id, reserva.nome_cliente, reserva.data_reservada, reserva.inicio_reserva, 
            reserva.fim_reserva, reserva.mesa, reserva.status, funcionario.nome AS nome_funcionario
            FROM reserva
            JOIN funcionario ON reserva.funcionario = funcionario.id
            WHERE reserva.data_reservada BETWEEN :dataInicial AND :dataFinal
            AND reserva.status IN ('confirmada', 'cancelada') -- Filtro para status específicos
            ORDER BY reserva.data_reservada ASC, reserva.inicio_reserva ASC, reserva.mesa ASC;";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'dataInicial' => $dataInicialStr,
            'dataFinal' => $dataFinalStr
        ]);

        $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $reservasDTO = [];

        foreach ($reservas as $reserva) {
            $dto = new ListarReservaDTO(
                $reserva['id'],               // id
                $reserva['nome_cliente'],     // nomeCliente
                $reserva['mesa'],             // mesa
                $reserva['data_reservada'],   // data
                $reserva['inicio_reserva'],   // horaInicial
                $reserva['fim_reserva'],      // horaTermino
                $reserva['nome_funcionario'], // nomeFuncionario
                $reserva['status']            // status
            );

            $reservasDTO[] = $dto;
        }

        return $reservasDTO;
    }
}
