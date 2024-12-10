<?php

use Kahlan\Arg;

describe('MesaController', function () {
    beforeEach(function () {
        // Criação de um mock do MesaRepository
        $this->mesaRepo = $this->getDouble('MesaRepository', [
            'listarMesas' => [
                (object) ['id' => 1, 'nome' => 'Mesa 1'],
                (object) ['id' => 2, 'nome' => 'Mesa 2']
            ],
            'listarMesasDisponiveis' => [
                (object) ['id' => 1, 'nome' => 'Mesa 1', 'status' => 'disponível'],
                (object) ['id' => 3, 'nome' => 'Mesa 3', 'status' => 'disponível']
            ]
        ]);

        // Instancia do controller com o mock do repositório
        $this->controller = new MesaController(new PDO('sqlite::memory:'));
        $this->controller->mesaRepo = $this->mesaRepo;
    });

    afterEach(function () {
        unset($this->controller, $this->mesaRepo);
    });

    describe('listarMesas', function () {
        it('should list all mesas', function () {
            $req = $this->getDouble('Request', ['getQuery' => Arg::type('array')]);
            $res = $this->getDouble('Response', ['json' => null]);

            $result = $this->controller->listarMesas($req, $res);
            expect($result)->toBeJson()
                ->and($result->json)->toContain('nome', 'Mesa 1')
                ->and($result->json)->toContain('nome', 'Mesa 2');
        });
    });

    describe('listarMesasDisponiveis', function () {
        it('should list available mesas based on date and time', function () {
            $req = $this->getDouble('Request', [
                'queries' => [
                    'data' => '2024-12-10',
                    'horarioInicial' => '18:00'
                ]
            ]);
            $res = $this->getDouble('Response', ['json' => null]);

            $result = $this->controller->listarMesasDisponiveis($req, $res);
            expect($result)->toBeJson()
                ->and($result->json)->toContain('status', 'disponível')
                ->and($result->json)->toContain('nome', 'Mesa 1')
                ->and($result->json)->toContain('nome', 'Mesa 3');
        });

        it('should return error when required parameters are missing', function () {
            $req = $this->getDouble('Request', ['queries' => []]);
            $res = $this->getDouble('Response', ['json' => null]);

            $result = $this->controller->listarMesasDisponiveis($req, $res);
            expect($result->json)->toBeJson()
                ->and($result->json)->toContain('error', 'Faltando parâmetros de data ou horário inicial');
        });
    });
});
