<?php

describe('Reserva', function () {

    context('validar()', function () {

        it('deve retornar erro se a mesa for inválida', function () {
            $reserva = new Reserva(1, 'Cliente Teste', 11, '2024-12-09', '10:00:00', 1);
            $problemas = $reserva->validar();
            expect($problemas)->toContain('O número da mesa deve ser entre 1 e ' . Reserva::MESAS_MAX . '.');
        });

        it('deve retornar erro se a data ou hora de início forem inválidos', function () {
            $reserva = new Reserva(1, 'Cliente Teste', 5, '2024-12-09', '25:00:00', 1);
            $problemas = $reserva->validar();
            expect($problemas)->toContain('Data ou hora de início inválidos.');
        });

        it('deve retornar erro se o horário for fora do intervalo permitido', function () {
            $reserva = new Reserva(1, 'Cliente Teste', 5, '2024-12-09', '23:00:00', 1);
            $problemas = $reserva->validar();
            expect($problemas)->toContain('A reserva deve ser feita entre as 11:00 e as 20:00.');
        });

        it('deve retornar erro se a reserva for em dia inválido', function () {
            $reserva = new Reserva(1, 'Cliente Teste', 5, '2024-12-09', '12:00:00', 1);
            $problemas = $reserva->validar();
            expect($problemas)->toContain('A reserva só pode ser feita entre quinta e domingo.');
        });

        it('deve retornar erro se a duração da reserva for diferente de 2 horas', function () {
            $reserva = new Reserva(1, 'Cliente Teste', 5, '2024-12-09', '12:00:00', 1);
            $problemas = $reserva->validar();
            expect($problemas)->toContain('A reserva deve ter duração de 2 horas.');
        });

        it('deve retornar erro se o ID do funcionário for inválido', function () {
            $reserva = new Reserva(1, 'Cliente Teste', 5, '2024-12-09', '12:00:00', -1);
            $problemas = $reserva->validar();
            expect($problemas)->toContain('O ID do funcionário deve ser um número não negativo.');
        });
    });
});
