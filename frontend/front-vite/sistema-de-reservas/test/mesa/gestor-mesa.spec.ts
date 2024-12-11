import { describe, it, expect, vi, afterEach } from 'vitest';
import { GestorMesas } from '../../src/mesa/gestor-mesas';
import { Mesa } from '../../src/mesa/mesa';

describe('GestorMesas', () => {
  afterEach(() => {

    vi.restoreAllMocks();
  });

  it('deve consultar as mesas disponíveis com sucesso', async () => {
    
    const mockMesas: Mesa[] = [
      { id: '1', capacidade: 4 },
      { id: '2', capacidade: 2 },
    ];

    
    vi.spyOn(globalThis, 'fetch').mockResolvedValueOnce({
      ok: true,
      json: vi.fn().mockResolvedValueOnce(mockMesas),
    } as unknown as Response);

    const gestor = new GestorMesas();
    const mesas = await gestor.consultarMesasDisponiveis('2024-12-10', '12:00');

    expect(mesas).toEqual(mockMesas);
    expect(fetch).toHaveBeenCalledWith(
      'http://localhost:8000/mesas-disponiveis?data=2024-12-10&horarioInicial=12:00'
    );
  });

  it('deve retornar uma lista vazia em caso de erro', async () => {
    
    vi.spyOn(globalThis, 'fetch').mockResolvedValueOnce({
      ok: false,
      status: 500,
      statusText: 'Internal Server Error',
      headers: new Headers(),
      json: vi.fn().mockResolvedValueOnce([]),
    } as unknown as Response);

    const gestor = new GestorMesas();
    const mesas = await gestor.consultarMesasDisponiveis('2024-12-10', '12:00');

    
    expect(mesas).toEqual([]);
    expect(fetch).toHaveBeenCalledWith(
      'http://localhost:8000/mesas-disponiveis?data=2024-12-10&horarioInicial=12:00'
    );
  });

  it('deve retornar uma lista vazia em caso de exceção', async () => {
    
    vi.spyOn(globalThis, 'fetch').mockRejectedValueOnce(new Error('Erro ao consultar mesas disponíveis'));

    const gestor = new GestorMesas();
    const mesas = await gestor.consultarMesasDisponiveis('2024-12-10', '12:00');

    expect(mesas).toEqual([]);
    expect(fetch).toHaveBeenCalledWith(
      'http://localhost:8000/mesas-disponiveis?data=2024-12-10&horarioInicial=12:00'
    );
  });
});
