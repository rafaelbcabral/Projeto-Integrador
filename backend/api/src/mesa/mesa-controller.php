<?php

use phputil\router\HttpRequest;
use phputil\router\HttpResponse;

require_once 'mesa-repositorio.php';

class MesaController
{
    protected MesaRepositorio $mesaRepo;
    protected PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->mesaRepo = new MesaRepositorio($pdo);
    }

    public function listarMesas(HttpRequest $req, HttpResponse $res)
    {
        $mesas = $this->mesaRepo->listarMesas();
        return $res->json($mesas);
    }

    public function listarMesasDisponiveis(HttpRequest $req, HttpResponse $res)
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
