<?php

use phputil\router\HttpRequest;
use phputil\router\HttpResponse;

require_once 'servico-funcionario.php';

class ControladoraFuncionario
{
    private ServicoFuncionario $servico;

    public function __construct(ServicoFuncionario $servico)
    {
        $this->servico = $servico;
    }

    public function listarFuncionarios(HttpRequest $req, HttpResponse $res)
    {
        $funcionarios = $this->servico->listarFuncionarios();
        return $res->json($funcionarios);
    }

    public function criarFuncionarios(HttpRequest $req, HttpResponse $res)
    {
        $data = $req->body();

        try {
            $this->servico->adicionarFuncionario($data);
            return $res->json(['sucesso' => 'Funcionario registrado com sucesso!']);
        } catch (Exception $e) {
            // Retorna erro em caso de exceção
            return $res->json(['erro' => $e->getMessage()], 500);
        }
    }
}
