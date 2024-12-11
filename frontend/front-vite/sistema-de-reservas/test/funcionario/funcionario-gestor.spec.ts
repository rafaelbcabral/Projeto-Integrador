import { describe, it, expect, vi, afterEach } from 'vitest';
import { GestorFuncionarios } from '../../src/funcionario/gestor-funcionario';
import { Funcionario } from '../../src/funcionario/funcionario';

describe('GestorFuncionarios', () => {
  afterEach(() => {
    // evitando interferências...
    vi.restoreAllMocks();
  });

  it('deve listar os funcionários com sucesso', async () => {
    // Mock fetch
    const mockFuncionarios: Funcionario[] = [
      { id: 1, nome: 'João' },
      { id: 2, nome: 'Maria' },
    ];

    vi.spyOn(globalThis, 'fetch').mockResolvedValueOnce({
      ok: true,
      json: vi.fn().mockResolvedValueOnce(mockFuncionarios),
    } as unknown as Response);

    const gestor = new GestorFuncionarios();
    const funcionarios = await gestor.listarFuncionarios();

    expect(funcionarios).toEqual(mockFuncionarios);
    expect(fetch).toHaveBeenCalledWith('http://localhost:8000/funcionarios');
  });

  it('deve retornar uma lista vazia em caso de erro', async () => {
    
    vi.spyOn(globalThis, 'fetch').mockResolvedValueOnce({
      ok: false,
      status: 500,
      statusText: 'Internal Server Error',
      headers: new Headers(),
      json: vi.fn().mockResolvedValueOnce([]),
    } as unknown as Response);

    const gestor = new GestorFuncionarios();
    const funcionarios = await gestor.listarFuncionarios();

    
    expect(funcionarios).toEqual([]);
    expect(fetch).toHaveBeenCalledWith('http://localhost:8000/funcionarios');
  });

  it('deve retornar uma lista vazia em caso de exceção', async () => {
    
    vi.spyOn(globalThis, 'fetch').mockRejectedValueOnce(new Error('Erro ao consultar funcionários'));

    const gestor = new GestorFuncionarios();
    const funcionarios = await gestor.listarFuncionarios();

    expect(funcionarios).toEqual([]);
    expect(fetch).toHaveBeenCalledWith('http://localhost:8000/funcionarios');
  });
});
