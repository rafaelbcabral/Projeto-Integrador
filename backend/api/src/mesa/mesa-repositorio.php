<?php

class MesaRepositorio
{
    protected PDO $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function atualizarStatusMesa($mesa_id, $status): void
    {
        $stmt = $this->pdo->prepare("UPDATE mesa SET disponivel = ? WHERE id = ?");
        $stmt->execute([$status, $mesa_id]);
    }

    public function listarMesas(): array
    {
        $sql = "SELECT * from mesa";
        $stmt = $this->pdo->query($sql);
        $mesas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $mesas;
    }

    // Função para verificar a disponibilidade de uma mesa
    public function verificarDisponibilidade($data, $horaInicial): bool
    {
        // Definindo uma duração fixa de 2 horas para a reserva
        $horaFinal = date('H:i:s', strtotime($horaInicial . ' +2 hours'));

        // Prepara a consulta para verificar se já existe uma reserva para a mesa nesse período
        $stmt = $this->pdo->prepare("
            SELECT 1 
            FROM reserva
            WHERE data_reservada = :data
            AND (
                (inicio_reserva < :fim AND fim_reserva > :inicio)
            )
        ");

        // Executa a consulta passando os parâmetros nomeados
        $stmt->execute([
            ':data' => $data,  // A data da reserva
            ':inicio' => $horaInicial,  // O horário inicial da reserva
            ':fim' => $horaFinal  // Calculando a hora final como +2 horas do horário inicial
        ]);

        $resultados = $stmt->fetchAll();

        // Se houver algum resultado, significa que a mesa já está reservada para o horário solicitado
        return count($resultados) > 0;
    }

    public function listarMesasDisponiveis($data, $horaInicial): array
    {
        $sql = "SELECT * FROM mesa";
        $stmt = $this->pdo->query($sql);
        $mesas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Filtra as mesas disponíveis utilizando a função de verificar disponibilidade
        $mesasDisponiveis = [];
        // Definindo uma duração fixa de 2 horas para a reserva
        $horaFinal = date('H:i:s', strtotime($horaInicial . ' +2 hours'));
        foreach ($mesas as $mesa) {
            // Verifica a disponibilidade de cada mesa para o horário solicitado
            $stmt = $this->pdo->prepare("
            SELECT 1 
            FROM reserva
            WHERE data_reservada = :data
            AND mesa = :mesa
            AND (
                (inicio_reserva < :fim AND fim_reserva > :inicio)
            )
        ");

            // Executa a consulta passando os parâmetros nomeados
            $stmt->execute([
                ':data' => $data,  // A data da reserva
                ':inicio' => $horaInicial,  // O horário inicial da reserva
                ':fim' => $horaFinal,  // Calculando a hora final como +2 horas do horário inicial
                ':mesa' => $mesa['id']  // Verifica a disponibilidade da mesa individualmente
            ]);

            $resultados = $stmt->fetchAll();

            // Se não houver resultado, significa que a mesa está disponível
            if (count($resultados) == 0) {
                // Adiciona a mesa à lista de mesas disponíveis
                $mesasDisponiveis[] = $mesa;
            }
        }

        // Retorna as mesas disponíveis
        return $mesasDisponiveis;
    }
}
