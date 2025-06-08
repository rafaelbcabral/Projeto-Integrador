<?php

class MesaRepositorio
{
    protected PDO $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }


    public function listarMesas(): array
    {
        $sql = "SELECT * from mesa";
        $stmt = $this->pdo->query($sql);
        $mesas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $mesas;
    }

    public function atualizarStatusMesa($mesa_id, $status)
    {
        $stmt = $this->pdo->prepare("UPDATE mesa SET disponivel = ? WHERE id = ?");
        $stmt->execute([$status, $mesa_id]);
    }

    public function verificarDisponibilidade($data, $horaInicial)
    {
        $horaFinal = date('H:i:s', strtotime($horaInicial . ' +2 hours'));

        $stmt = $this->pdo->prepare("
            SELECT 1 
            FROM reserva
            WHERE dataReservada = :data
            AND (
                (inicio < :fim AND fim > :inicio)
            )
        ");

        $stmt->execute([
            ':data' => $data,
            ':inicio' => $horaInicial,
            ':fim' => $horaFinal
        ]);

        $resultados = $stmt->fetchAll();

        return count($resultados) > 0;
    }

    public function listarMesasDisponiveis($data, $horaInicial)
    {
        $sql = "SELECT * FROM mesa";
        $stmt = $this->pdo->query($sql);
        $mesas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $mesasDisponiveis = [];
        $horaFinal = date('H:i:s', strtotime($horaInicial . ' +2 hours'));
        foreach ($mesas as $mesa) {
            $stmt = $this->pdo->prepare("
            SELECT 1 
            FROM reserva
            WHERE dataReservada = :data
            AND mesa = :mesa
            AND (
                (inicio < :fim AND fim > :inicio)
            )
        ");

            $stmt->execute([
                ':data' => $data,
                ':inicio' => $horaInicial,
                ':fim' => $horaFinal,
                ':mesa' => $mesa['id']
            ]);

            $resultados = $stmt->fetchAll();

            if (count($resultados) == 0) {
                $mesasDisponiveis[] = $mesa;
            }
        }

        return $mesasDisponiveis;
    }
}
