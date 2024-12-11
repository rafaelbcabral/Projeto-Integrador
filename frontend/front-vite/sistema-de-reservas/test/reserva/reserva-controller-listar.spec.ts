import { describe, it, expect, beforeEach, vi } from 'vitest';
import { ControladoraListarReservas } from '../../src/reserva/reserva-controller-listar';
import { ReservaListar } from '../../src/reserva/listar-reservas';

describe('ControladoraListarReservas', () => {
  let controladora: ControladoraListarReservas;
  let visaoMock: any;
  let gestorMock: any;

  beforeEach(() => {
    visaoMock = {
      desenharReservas: vi.fn(),
    };

    gestorMock = {
      listarReservas: vi.fn(),
      cancelarReserva: vi.fn(),
    };

    
    controladora = new ControladoraListarReservas(visaoMock);
    controladora['gestor'] = gestorMock;
  });

  it('deve listar as reservas com sucesso', async () => {
    const reservasMock: ReservaListar[] = [
      { id: '1', nomeCliente: 'João Silva', mesa: 'Mesa 1', data: '2024-12-01', horaInicial: '12:00', horaTermino: '14:00', nomeFuncionario: 'Carlos', status: 'confirmada' },
      { id: '2', nomeCliente: 'Maria Oliveira', mesa: 'Mesa 2', data: '2024-12-02', horaInicial: '15:00', horaTermino: '17:00', nomeFuncionario: 'Ana', status: 'confirmada' },
    ];

    
    gestorMock.listarReservas.mockResolvedValue(reservasMock);

    
    await controladora.ListarReservas();

    
    expect(visaoMock.desenharReservas).toHaveBeenCalledWith(reservasMock);
  });

  it('deve lidar com erro ao listar reservas', async () => {
    
    gestorMock.listarReservas.mockRejectedValue(new Error('Erro ao listar reservas'));

    
    await controladora.ListarReservas();

    
    expect(visaoMock.desenharReservas).not.toHaveBeenCalled();
  });

  it('deve cancelar a reserva com sucesso', async () => {
    const reservaId = '1';

    
    gestorMock.cancelarReserva.mockResolvedValue(undefined);

    
    await controladora.cancelarReserva(reservaId);

    
    expect(gestorMock.cancelarReserva).toHaveBeenCalledWith(reservaId);
  });

  it('deve lidar com erro ao cancelar a reserva', async () => {
    const reservaId = '1';

    
    gestorMock.cancelarReserva.mockRejectedValue(new Error('Erro ao cancelar reserva'));

    
    await controladora.cancelarReserva(reservaId);

    
    expect(gestorMock.cancelarReserva).toHaveBeenCalledWith(reservaId);
  });
});
