<?php

require_once 'src/reserva/reserva.php';

describe('Reserva', function () {

    it('deve retornar erro se a mesa for inválida no final de semana', function () {
        // Testando para um dia onde o limite de mesas é 10 (Sábado)
        $reserva = new Reserva(1, 'Cliente Teste', 11, '2024-12-07', '10:00:00', 1); // Sábado
        $problemas = $reserva->validar();
        expect($problemas)->toContain('O número da mesa deve ser entre 1 e ' . Reserva::MESAS_MAX_FINAL_DE_SEMANA . '.');
    });

    it('deve retornar erro se a mesa for inválida no dia de semana', function () {
        // Testando para um dia onde o limite de mesas é 7 (Quinta-feira)
        $reserva = new Reserva(1, 'Cliente Teste', 8, '2024-12-05', '10:00:00', 1); // Quinta-feira
        $problemas = $reserva->validar();
        expect($problemas)->toContain('O número da mesa deve ser entre 1 e ' . Reserva::MESAS_MAX_DIA_DE_SEMANA . '.');
    });

    it('deve retornar erro se a data ou hora de início forem inválidos', function () {
        // Hora inválida (25:00:00)
        $reserva = new Reserva(1, 'Cliente Teste', 5, '2024-12-09', '25:00:00', 1); // Hora inválida
        $problemas = $reserva->validar();
        // Corrigindo para verificar a mensagem correta relacionada ao horário
        expect($problemas)->toContain('A reserva deve ser feita entre as 11:00 e as 20:00.');
    });

    it('deve retornar erro se a reserva for fora do intervalo permitido de horário', function () {
        // Hora fora do intervalo permitido (23:00:00)
        $reserva = new Reserva(1, 'Cliente Teste', 5, '2024-12-09', '10:00:00', 1); // Hora inválida
        $problemas = $reserva->validar();
        // Corrigindo para verificar o erro relacionado ao horário fora do intervalo
        expect($problemas)->toContain('A reserva deve ser feita entre as 11:00 e as 20:00.');
    });

    it('deve retornar erro se a reserva for em um dia inválido (antes de quinta-feira)', function () {
        // Segunda-feira (3 é o código do dia de semana para segunda-feira)
        $reserva = new Reserva(1, 'Cliente Teste', 6, '2024-12-02', '12:00:00', 1); // Segunda-feira
        $problemas = $reserva->validar();
        expect($problemas)->toContain('A reserva só pode ser feita entre quinta e domingo.');
    });

    it('deve retornar erro se a duração da reserva for inválida', function () {
        // Duração diferente de 2 horas
        $reserva = new Reserva(1, 'Cliente Teste', 5, '2024-12-05', '12:00:00', 1); // Quinta-feira
        // A reserva deve terminar antes das 14:00 (1 hora de duração) para gerar o erro de duração inválida
        $reserva->fim = '13:00:00';
        $problemas = $reserva->validar();
        expect($problemas)->toContain('A reserva deve ter duração de 2 horas.');
    });

    it('deve retornar erro se o ID do funcionário for inválido (negativo)', function () {
        // ID do funcionário inválido
        $reserva = new Reserva(1, 'Cliente Teste', 5, '2024-12-05', '12:00:00', -1); // ID inválido
        $problemas = $reserva->validar();
        expect($problemas)->toContain('O ID do funcionário deve ser um número não negativo.');
    });
});
