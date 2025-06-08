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

    public function login(HttpRequest $req, HttpResponse $res)
    {
        session_name('sid');
        session_start();

        $body = $req->body();
        $username = $body->usuario ?? '';
        $password = $body->senha ?? '';

        if (empty($username) || empty($password)) {
            return $res->status(400)->json(['error' => 'Username and password are required.']);
        }

        try {
            $result = $this->servico->login($username, $password);

            if ($result['success']) {
                $_SESSION['id'] = $result['user']['id'];
                $_SESSION['usuario'] = $result['user']['usuario'];

                return $res->json([
                    'message' => 'Login successful',
                    'user_id' => $result['user']['id']
                ]);
            } else {
                return $res->status(401)->json(['error' => $result['message']]);
            }
        } catch (Exception $e) {
            return $res->status(500)->json(['error' => 'Internal server error.']);
        }
    }
}
