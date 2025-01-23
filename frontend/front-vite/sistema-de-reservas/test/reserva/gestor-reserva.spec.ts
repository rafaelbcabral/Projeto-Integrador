import { describe, it, expect, vi, afterEach } from 'vitest';
import { GestorReservas } from '../../src/reserva/gestor-reserva';
import { ReservaListar } from '../../src/reserva/listar-reservas';
import { Reserva } from '../../src/reserva/criar-reserva';

describe('GestorReservas', () => {
  afterEach(() => {
    // Restaura todos os mocks após cada teste para evitar interferência
    vi.restoreAllMocks();
  });

  it('deve cancelar uma reserva com sucesso', async () => {
    // Mock para o fetch
    vi.spyOn(globalThis, 'fetch').mockResolvedValueOnce({
      ok: true,
      json: vi.fn().mockResolvedValueOnce({}),
    } as unknown as Response);

    // globalThis.location =! 0
    const originalLocation = globalThis.location;
    const reloadMock = vi.fn();
    globalThis.location = { ...originalLocation, reload: reloadMock };

    const gestor = new GestorReservas();
    await gestor.cancelarReserva('123');

    expect(fetch).toHaveBeenCalledWith('http://localhost:8000/reservas/123', expect.objectContaining({
      method: 'PUT',
      body: '{"status":"cancelado"}',
    }));

    expect(reloadMock).toHaveBeenCalledTimes(1);

    globalThis.location = originalLocation;
  });

  it('deve retornar reservas com sucesso', async () => {
    vi.spyOn(globalThis, 'fetch').mockResolvedValueOnce({
      ok: true,
      json: vi.fn().mockResolvedValueOnce([{
        id: '1',
        nomeCliente: 'João',
        mesa: 'Mesa 1',
        data: '2024-12-10',
        horaInicial: '12:00',
        horaTermino: '14:00',
        nomeFuncionario: 'Maria',
        status: 'confirmado',
      }] as ReservaListar[]),
    } as unknown as Response);

    const gestor = new GestorReservas();
    const reservas = await gestor.listarReservas();

    expect(fetch).toHaveBeenCalledWith('http://localhost:8000/reservas', expect.objectContaining({
      method: 'GET',
    }));

    expect(reservas).toEqual([{
      id: '1',
      nomeCliente: 'João',
      mesa: 'Mesa 1',
      data: '2024-12-10',
      horaInicial: '12:00',
      horaTermino: '14:00',
      nomeFuncionario: 'Maria',
      status: 'confirmado',
    }]);
  });

  it('deve falhar ao cancelar uma reserva caso a resposta não seja ok', async () => {
    vi.spyOn(globalThis, 'fetch').mockResolvedValueOnce({
      ok: false,
    } as unknown as Response);

    const gestor = new GestorReservas();

    await expect(gestor.cancelarReserva('123')).rejects.toThrow('Erro ao cancelar a reserva');
  });

  it('deve criar uma reserva com sucesso', async () => {
    const reserva: Reserva = {
      nomeCliente: 'João',
      mesa: 1,
      data: '2024-12-10',
      horarioInicial: '12:00',
      funcionario: 123,
      telefone: 988103858
    };

    vi.spyOn(globalThis, 'fetch').mockResolvedValueOnce({
      ok: true,
      json: vi.fn().mockResolvedValueOnce(reserva),
    } as unknown as Response);

    const gestor = new GestorReservas();
    const novaReserva = await gestor.criarReserva(reserva);

    expect(fetch).toHaveBeenCalledWith('http://localhost:8000/reservas', expect.objectContaining({
      method: 'POST',
      body: JSON.stringify(reserva),
    }));

    expect(novaReserva).toEqual(reserva);
  });

  it('deve falhar ao criar uma reserva caso a resposta não seja ok', async () => {
    vi.spyOn(globalThis, 'fetch').mockResolvedValueOnce({
      ok: false,
    } as unknown as Response);

    const gestor = new GestorReservas();

    await expect(gestor.criarReserva({
      nomeCliente: 'João',
      mesa: 1,
      data: '2024-12-10',
      horarioInicial: '12:00',
      funcionario: 123,
      telefone: 988103858,
    })).rejects.toThrow('Erro ao criar a reserva');
  });
});

