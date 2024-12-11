<?php
describe('ReservaRepository', function () {

    beforeEach(function () {
        $this->pdoMock = $this->mock(\PDO::class);
        $this->reservaRepository = new ReservaRepository($this->pdoMock->get());

        // Mockando a execução do método prepare e a função fetch
        $this->stmtMock = $this->mock(\PDOStatement::class);
        $this->pdoMock->shouldReceive('prepare')->andReturn($this->stmtMock);
    });

    it('should verify table availability', function () {
        $this->stmtMock->shouldReceive('fetchAll')
            ->andReturn([]); // retorna array vazio, simulando que não há reservas conflitantes

        $reserva = new Reserva();
        $reserva->mesa = 1;
        $reserva->data = '2024-12-12';
        $reserva->horaInicial = '18:00:00';
        $reserva->horaTermino = '20:00:00';

        $result = $this->reservaRepository->verificarDisponibilidade($reserva);

        expect($result)->toBe(false);
    });

    it('should save a reservation', function () {
        $this->stmtMock->shouldReceive('execute')
            ->once()->with([
                ':nome_cliente' => 'João Silva',
                ':data_reservada' => '2024-12-12',
                ':inicio_reserva' => '18:00:00',
                ':fim_reserva' => '20:00:00',
                ':mesa' => 1,
                ':funcionario' => 2,
            ]); // Simula a execução do SQL INSERT

        $reserva = new Reserva();
        $reserva->nomeCliente = 'João Silva';
        $reserva->data = '2024-12-12';
        $reserva->horaInicial = '18:00:00';
        $reserva->horaTermino = '20:00:00';
        $reserva->mesa = 1;
        $reserva->funcionario = 2;

        $this->reservaRepository->salvarReserva($reserva);
    });

    it('should cancel a reservation', function () {
        $this->stmtMock->shouldReceive('execute')
            ->once()->with([':id' => 1]); // Simula a execução do SQL UPDATE

        $this->reservaRepository->cancelarReserva(1);
    });

    it('should find a reservation by id', function () {
        $this->stmtMock->shouldReceive('fetch')
            ->andReturn([
                'id' => 1,
                'nome_cliente' => 'João Silva',
                'data_reservada' => '2024-12-12',
                'inicio_reserva' => '18:00:00',
                'fim_reserva' => '20:00:00',
                'mesa' => 1,
                'status' => 'confirmada'
            ]); // Simula a busca por uma reserva no banco

        $reserva = $this->reservaRepository->buscarReservaPorId(1);

        expect($reserva)->toBeAnInstanceOf(Reserva::class);
        expect($reserva->id)->toBe(1);
        expect($reserva->nomeCliente)->toBe('João Silva');
        expect($reserva->data)->toBe('2024-12-12');
    });

    it('should list future reservations', function () {
        $this->stmtMock->shouldReceive('fetchAll')
            ->andReturn([ // Simula várias reservas futuras
                ['id' => 1, 'nome_cliente' => 'João Silva', 'data_reservada' => '2024-12-12', 'inicio_reserva' => '18:00:00', 'fim_reserva' => '20:00:00', 'mesa' => 1, 'status' => 'confirmada', 'nome_funcionario' => 'Carlos'],
                ['id' => 2, 'nome_cliente' => 'Maria Silva', 'data_reservada' => '2024-12-13', 'inicio_reserva' => '19:00:00', 'fim_reserva' => '21:00:00', 'mesa' => 2, 'status' => 'confirmada', 'nome_funcionario' => 'Ana']
            ]);

        $reservas = $this->reservaRepository->listarReservas();

        expect(count($reservas))->toBe(2);
        expect($reservas[0]->nomeCliente)->toBe('João Silva');
        expect($reservas[1]->nomeCliente)->toBe('Maria Silva');
    });

    it('should list all reservations', function () {
        $this->stmtMock->shouldReceive('fetchAll')
            ->andReturn([ // Simula todas as reservas no banco
                ['id' => 1, 'nome_cliente' => 'João Silva', 'data_reservada' => '2024-12-12', 'inicio_reserva' => '18:00:00', 'fim_reserva' => '20:00:00', 'mesa' => 1, 'status' => 'confirmada', 'nome_funcionario' => 'Carlos'],
                ['id' => 2, 'nome_cliente' => 'Maria Silva', 'data_reservada' => '2024-12-13', 'inicio_reserva' => '19:00:00', 'fim_reserva' => '21:00:00', 'mesa' => 2, 'status' => 'confirmada', 'nome_funcionario' => 'Ana']
            ]);

        $reservas = $this->reservaRepository->listarTodasAsReservas();

        expect(count($reservas))->toBe(2);
        expect($reservas[0]->nomeCliente)->toBe('João Silva');
        expect($reservas[1]->nomeCliente)->toBe('Maria Silva');
    });

    it('should list reservations within a period', function () {
        $this->stmtMock->shouldReceive('fetchAll')
            ->andReturn([ // Simula reservas em um período específico
                ['id' => 1, 'nome_cliente' => 'João Silva', 'data_reservada' => '2024-12-12', 'inicio_reserva' => '18:00:00', 'fim_reserva' => '20:00:00', 'mesa' => 1, 'status' => 'confirmada', 'nome_funcionario' => 'Carlos'],
                ['id' => 2, 'nome_cliente' => 'Maria Silva', 'data_reservada' => '2024-12-13', 'inicio_reserva' => '19:00:00', 'fim_reserva' => '21:00:00', 'mesa' => 2, 'status' => 'confirmada', 'nome_funcionario' => 'Ana']
            ]);

        $reservas = $this->reservaRepository->listarReservasPorPeriodo('2024-12-12', '2024-12-14');

        expect(count($reservas))->toBe(2);
        expect($reservas[0]->nomeCliente)->toBe('João Silva');
        expect($reservas[1]->nomeCliente)->toBe('Maria Silva');
    });
});
