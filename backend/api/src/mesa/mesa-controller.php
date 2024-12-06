<?php
require_once 'mesa-repositorio.php';

class MesaController
{
    protected MesaRepository $mesaRepo;
    protected PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->mesaRepo = new MesaRepository($pdo);
    }

    public function listarMesas($req, $res)
    {
        $mesas = $this->mesaRepo->listarMesas();
        return $res->json($mesas);
    }

    public function listarMesasDisponiveis($req, $res)
    {
        // Usando getQuery() ou método alternativo
        $dados = (array) $req->queries('data');
        $data = $dados['data'];
        $horarioInicial = $dados['horarioInicial'];

        // Verificar se os parâmetros existem
        if (!$data || !$horarioInicial) {
            return $res->json(['error' => 'Faltando parâmetros de data ou horário inicial'], 400);
        }
        // Aqui você chama o método listarMesasDisponiveis
        $mesasDisponiveis = $this->mesaRepo->listarMesasDisponiveis($data, $horarioInicial);
        return $res->json($mesasDisponiveis);
    }
}
