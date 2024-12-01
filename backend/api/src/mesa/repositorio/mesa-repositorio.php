<?php

class MesaRepository
{
    protected $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function atualizarStatusMesa($mesa_id, $status)
    {
        $stmt = $this->pdo->prepare("UPDATE mesa SET disponivel = ? WHERE id = ?");
        $stmt->execute([$status, $mesa_id]);
    }
}
